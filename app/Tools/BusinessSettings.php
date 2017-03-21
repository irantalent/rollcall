<?php
/**
 * Created by PhpStorm.
 * User: Mohammad
 * Date: 21/03/2017
 * Time: 11:42 PM
 */

namespace App\Tools;


class BusinessSettings
{
    const START_WORK_TIME = '09:00:00';
    const FINISH_WORK_TIME = '17:00:00';

    public static function getStartWorkTime(){
        return BusinessSettings::START_WORK_TIME;
    }

    public static function getFinishWorkTime(){
        return BusinessSettings::FINISH_WORK_TIME;
    }
}