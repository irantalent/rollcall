<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rollcall extends Model
{
    protected $fillable = [
        'type',
        'rollcall_time',
    ];

    public $dates =[
        'rollcall_time'
    ];

    public function getTypeString()
    {
        switch ($this->type) {
            case Rollcall::ARRIVE:
                return __('rollcall.arrive');
            case Rollcall::DEPART:
                return __('rollcall.depart');
        }
    }

    const ARRIVE = 1;
    const DEPART = 2;
}
