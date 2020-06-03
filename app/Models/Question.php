<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    public function detail() 
    {
        return $this->belongsTo('App\Models\ResponseDetails');
    }
}
