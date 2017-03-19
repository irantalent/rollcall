<?php
/**
 * Created by PhpStorm.
 * User: Mohammad
 * Date: 04/02/2017
 * Time: 12:18 AM
 */

namespace App\Repositories;


use App\Http\Requests\RollcallRequest;
use App\Models\Rollcall;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RollcallRepository
{
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
            'type' => 1,
        ]));
    }

    public function departNow()
    {
        return request()->user()->rollcalls()->save(new Rollcall([
            'rollcall_time' => Carbon::now(),
            'type' => 2,
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

    public function show(Rollcall $rollcall, $paginate = 50)
    {
        return Rollcall::whereDate('rollcall_time', $rollcall->rollcall_time->toDateString())->paginate($paginate);
    }
}