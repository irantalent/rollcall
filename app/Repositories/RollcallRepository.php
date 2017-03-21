<?php
/**
 * Created by PhpStorm.
 * User: Mohammad
 * Date: 04/02/2017
 * Time: 12:18 AM
 */

namespace App\Repositories;


use App\Http\Requests\RollcallRequest;
use App\Models\Payslip;
use App\Models\Rollcall;
use App\Tools\DateTimeHelper;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class RollcallRepository
{
    protected $payslips;

    public function __construct(PayslipRepository $payslips)
    {

        $this->payslips = $payslips;
    }

    public function index($paginate = 50)
    {
        $rollcalls = request()->user()->rollcalls()
            ->groupBy([DB::raw('date(rollcall_time)')])
            ->paginate($paginate, [
                'id',
                DB::raw('date(rollcall_time) as date'),
                DB::raw('count(*) as passes'),
            ]);

        foreach ($rollcalls as $rollcall) {
            $this->loadSingle($rollcall);
        }

        return $rollcalls;
    }

    public function store(RollcallRequest $request)
    {
        return $request->user()->rollcalls()->save(new Rollcall($request->all()));
    }

    public function update(RollcallRequest $request, Rollcall $rollcall)
    {
        return $rollcall->update($request->all());
    }

    public function destroy(Rollcall $rollcall)
    {
        return $rollcall->delete();
    }

    public function arriveNow()
    {
        return request()->user()->rollcalls()->save(new Rollcall([
            'rollcall_time' => Carbon::now(),
            'type' => Rollcall::ARRIVE,
        ]));
    }

    public function departNow()
    {
        return request()->user()->rollcalls()->save(new Rollcall([
            'rollcall_time' => Carbon::now(),
            'type' => Rollcall::DEPART,
        ]));
    }


    //////////////////// LOAD

    /**
     * this function will load first arrived rollcall and
     * last depart time at given rollcall date and load them
     * all into current object
     *
     * @param Rollcall $rollcall
     * @return mixed|void
     */
    public function loadSingle($rollcall)
    {
        $firstArriveRecord = Rollcall::where('type', Rollcall::ARRIVE)
            ->whereDate('rollcall_time', $rollcall->date)
            ->orderBy('rollcall_time', 'asc')
            ->first();
        $rollcall->first_arrive_datetime = is_null($firstArriveRecord) ? '' : $firstArriveRecord->rollcall_time;

        $lastDepartRecord = Rollcall::where('type', Rollcall::DEPART)
            ->whereDate('rollcall_time', $rollcall->date)
            ->orderBy('rollcall_time', 'desc')
            ->first();
        $rollcall->last_depart_datetime = is_null($lastDepartRecord) ? '' : $lastDepartRecord->rollcall_time;

        return $rollcall;
    }

    public function show($date, $paginate = 50)
    {
        return Rollcall::whereDate('rollcall_time', $date)->orderBy('rollcall_time', 'ASC')->paginate($paginate);
    }

    public function storeAt($request)
    {
        return request()->user()->rollcalls()->save(new Rollcall([
            'rollcall_time' => Carbon::createFromFormat('Y-m-d H:i', $request->date . ' ' . $request->time),
            'type' => (is_null($request->arrive) ? Rollcall::DEPART : Rollcall::ARRIVE),
        ]));
    }

    public function calendarMonthView()
    {
        //$this->calculateMonthSummary();
        $date = (\DateTime::createFromFormat('Y-m-d', Input::get("date")) !== false)
            ? new Carbon(Input::get("date"))
            : Carbon::now();

        $weeks = [];
        foreach (DateTimeHelper::monthWeeklyCalendarShot($date->toDateString()) as $week) {
            $processedWeek = [];
            foreach ($week as $day) {
                $processedWeek[] = (object)[
                    'first_arrive' => request()->user()
                        ->rollcalls()
                        ->whereDate('rollcall_time', $day)
                        ->where('type', Rollcall::ARRIVE)
                        ->orderBy('rollcall_time', 'ASC')
                        ->first(),
                    'last_depart' => request()->user()
                        ->rollcalls()
                        ->whereDate('rollcall_time', $day)
                        ->where('type', Rollcall::DEPART)
                        ->orderBy('rollcall_time', 'DESC')
                        ->first(),
                    'number_of_passes' => request()->user()
                        ->rollcalls()
                        ->whereDate('rollcall_time', $day)
                        ->count(),
                    'is_in_this_month' => $day->month == $date->month,
                    'date' => $day
                ];
            }
            $weeks[] = $processedWeek;
        }

        return [
            'weeks' => $weeks,
            'date' => $date,
            'payslip' =>
                request()->user()->payslips()->where([
                    ['month', $date->month],
                    ['year', $date->year],
                ])->firstOrNew([]),
        ];
    }

    public function calculateMonthSummary()
    {
        $this->payslips->calculatePayslipTimeForUser(request()->user(), Carbon::now());

        $date = (\DateTime::createFromFormat('Y-m-d', Input::get("date")) !== false)
            ? new Carbon(Input::get("date"))
            : Carbon::now();
        $total_working_hours = 0;

        $rollcalls = request()->user()
            ->rollcalls()
            ->whereDate('rollcall_time', '>=', (clone $date)->firstOfMonth()->toDateString())
            ->whereDate('rollcall_time', '<=', (clone $date)->endOfMonth()->toDateString())
            ->orderBy('rollcall_time', 'ASC')->get();

        for ($i = 0; $i < $rollcalls->count(); $i++) {
            if ($rollcalls[$i]->type == Rollcall::ARRIVE) {
                $j = $i + 1;
                while ($j < $rollcalls->count() &&
                    $rollcalls[$j]->rollcall_time->toDateString() == $rollcalls[$i]->rollcall_time->toDateString() &&
                    $rollcalls[$j]->type == Rollcall::ARRIVE) {
                    $j++;
                }

                if ($j >= $rollcalls->count()) break;
                if ($rollcalls[$j]->rollcall_time->toDateString() != $rollcalls[$i]->rollcall_time->toDateString()) {
                    $i = $j - 1;
                    continue;
                }

                $switch = false;

                while ($j < $rollcalls->count() &&
                    $rollcalls[$j]->rollcall_time->toDateString() == $rollcalls[$i]->rollcall_time->toDateString() &&
                    $rollcalls[$j]->type == Rollcall::DEPART) {
                    $j++;
                    $switch = true;
                }
                if ($switch) {
                    $j--;
                    //if found first depart rollcall in same day as rollcalls[$i] should calculate difference in hours
                    $total_working_hours += $rollcalls[$j]->rollcall_time->diffInHours($rollcalls[$i]->rollcall_time);
                    echo "here" . $i . $j . $total_working_hours;
                    $j++;
                }

                if ($j >= $rollcalls->count()) break;
                if ($rollcalls[$j]->rollcall_time->toDateString() != $rollcalls[$i]->rollcall_time->toDateString()) {
                    $i = $j - 1;
                    continue;
                }
            }
        }

        dd($total_working_hours);
    }
}