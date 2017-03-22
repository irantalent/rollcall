<?php
/**
 * Created by PhpStorm.
 * User: Mohammad
 * Date: 21/03/2017
 * Time: 11:25 PM
 */

namespace App\Repositories;


use App\Models\Payslip;
use App\Models\Rollcall;
use App\Tools\BusinessSettings;
use App\User;
use Carbon\Carbon;
use Carbon\CarbonInterval;

class PayslipRepository
{
    public function calculateWorkingHours()
    {
        $now = Carbon::now();

        foreach (User::all() as $user) {
            $payslip = $this->calculatePayslipTimeForUser($user, $now);

        }
    }

    public function calculatePayslipTimeForUser(User $user, Carbon $carbon)
    {
        $payslip = $user->payslips()->where([['month', $carbon->month], ['year', $carbon->year]])->firstOrNew([]);
        $payslip->month = $carbon->month;
        $payslip->year = $carbon->year;
        $payslip->user_id = $user->id;
        $payslip->total_working_time = 0;
        $payslip->late_in_time = 0;
        $payslip->early_out_time = 0;
        $payslip->overtime_time = 0;

        foreach (
            new \DatePeriod(
                (clone $carbon)->firstOfMonth(),
                CarbonInterval::day(),
                (clone $carbon)->endOfMonth()
            ) as $day
        ) {
            $rollcalls = $user
                ->rollcalls()
                ->whereDate('rollcall_time', $day->toDateString())
                ->orderBy('rollcall_time', 'ASC')->get();
            for ($i = 0; $i < $rollcalls->count(); $i++) {
                if ($rollcalls[$i]->type == Rollcall::ARRIVE) {
                    $j = $i + 1;

                    //skip all arrive records right after first one
                    while ($j < $rollcalls->count() && $rollcalls[$j]->type == Rollcall::ARRIVE) {
                        $j++;
                    }

                    $switch = false; // check if are there any departs?

                    // stop at last depart record before next arrive (or at the end of queue)
                    while ($j < $rollcalls->count() && $rollcalls[$j]->type == Rollcall::DEPART) {
                        $j++;
                        $switch = true;
                    }
                    if ($switch) { // if there are at least one (or more) departs then
                        $j--; // role back one step
                        //calculate difference in seconds
                        $payslip->total_working_time +=
                            $rollcalls[$j]->rollcall_time
                                ->diffInSeconds($rollcalls[$i]->rollcall_time);
                        echo "here" . $i . " - " . $rollcalls[$i]->rollcall_time . "<br>" . $j . " - " . $rollcalls[$j]->rollcall_time . "<br>" . $payslip->total_working_time . "<br><br>";

                        $payslip->late_in_time += $this->calculateLateIn($rollcalls, $i);
                        $payslip->early_out_time += $this->calculateEarlyOut($rollcalls, $j);
                        $payslip->overtime_time += $this->calculateOvertime($rollcalls, $i, $j);

                    }
                    $i = $j; // continue form next record ($i will increase by one at the beginning of for)
                }
            }
        }

        $payslip->save();

        return $payslip;
    }

    public function calculateLateIn($rollcalls, $indexArrive)
    {
        if (!$this->isFirstArrive($rollcalls, $indexArrive)) return 0;

        $start = new Carbon($rollcalls[$indexArrive]->rollcall_time->toDateString() . " " . BusinessSettings::getStartWorkTime());
        $finish = new Carbon($rollcalls[$indexArrive]->rollcall_time->toDateString() . " " . BusinessSettings::getFinishWorkTime());

        if($finish->lt($rollcalls[$indexArrive]->rollcall_time)) return 0;

        if ($start->lt($rollcalls[$indexArrive]->rollcall_time)) {
            return $start->diffInSeconds($rollcalls[$indexArrive]->rollcall_time);
        }

        return 0;
    }

    public function calculateEarlyOut($rollcalls, $indexDepart)
    {
        if (!$this->isLastDepart($rollcalls, $indexDepart)) return 0;

        $start = new Carbon($rollcalls[$indexDepart]->rollcall_time->toDateString() . " " . BusinessSettings::getStartWorkTime());
        $finish = new Carbon($rollcalls[$indexDepart]->rollcall_time->toDateString() . " " . BusinessSettings::getFinishWorkTime());

        if($start->gt($rollcalls[$indexDepart]->rollcall_time)) return 0;

        if ($finish->gt($rollcalls[$indexDepart]->rollcall_time)) {
            return $finish->diffInSeconds($rollcalls[$indexDepart]->rollcall_time);
        }

        return 0;

    }

    public function isFirstArrive($rollcalls, $indexArrive)
    {
        for ($i = 0; $i < $indexArrive && $i < $rollcalls->count(); $i++) {
            if ($rollcalls[$i]->type == Rollcall::ARRIVE)
                return false;
        }

        return true;
    }

    public function isLastDepart($rollcalls, $indexDepart)
    {
        for ($i = $rollcalls->count() - 1; $i > $indexDepart && $i >= 0; $i--) {
            if ($rollcalls[$i]->type == Rollcall::DEPART)
                return false;
        }

        return true;
    }

    public function calculateOvertime($rollcalls, $indexArrive, $indexDepart)
    {
        $start = new Carbon($rollcalls[$indexArrive]->rollcall_time->toDateString() . " " . BusinessSettings::getStartWorkTime());
        $finish = new Carbon($rollcalls[$indexDepart]->rollcall_time->toDateString() . " " . BusinessSettings::getFinishWorkTime());

        if ($start->gt($rollcalls[$indexArrive]->rollcall_time)) {
            if ($start->gt($rollcalls[$indexDepart]->rollcall_time)) {
                return $rollcalls[$indexArrive]->rollcall_time->diffInSeconds($rollcalls[$indexDepart]->rollcall_time);
            } else {
                return $start->diffInSeconds($rollcalls[$indexArrive]->rollcall_time) + (
                    $finish->lt($rollcalls[$indexDepart]->rollcall_time)
                        ? $finish->diffInSeconds($rollcalls[$indexDepart]->rollcall_time)
                        : 0
                );
            }
        } else if ($finish->lt($rollcalls[$indexDepart]->rollcall_time)) {
            if($finish->lt($rollcalls[$indexArrive]->rollcall_time)) {
                return $rollcalls[$indexArrive]->rollcall_time->diffInSeconds($rollcalls[$indexDepart]->rollcall_time);
            } else {
                return $finish->diffInSeconds($rollcalls[$indexDepart]->rollcall_time);
            }
        }

        return 0;
    }
}