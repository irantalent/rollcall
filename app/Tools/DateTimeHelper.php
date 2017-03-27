<?php
/**
 * Created by PhpStorm.
 * User: Mohammad
 * Date: 21/03/2017
 * Time: 04:11 PM
 */

namespace App\Tools;


use Carbon\Carbon;
use Carbon\CarbonInterval;

class DateTimeHelper
{
    public static function getMondaysOfMonthShot($date)
    {
        $cDate = new Carbon($date);
        //dd($cDate->startOfMonth()->startOfWeek()->toDateString() . " - " . $cDate->endOfMonth()->startOfWeek()->toDateString());
        return new \DatePeriod(
            (new Carbon($date))->startOfMonth()->startOfWeek(),
            CarbonInterval::week(),
            (new Carbon($date))->endOfMonth()->startOfWeek()->addDay(1)
        );
    }

    public static function monthWeeklyCalendarShot($date)
    {
        $mondays = DateTimeHelper::getMondaysOfMonthShot($date);
        $month = [];
        foreach ($mondays as $monday) {
            $month[] = new \DatePeriod(
                $monday,
                CarbonInterval::day(),
                (new Carbon($monday))->endOfWeek()
            );
        }

        return $month;
    }

    public static function fromSecondsToStandard($seconds) {
        return sprintf("%02d:%02d:%02d", intval($seconds / 3600), intval(($seconds % 3600) / 60), $seconds % 60);
    }
}