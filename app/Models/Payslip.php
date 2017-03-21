<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payslip extends Model
{
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
