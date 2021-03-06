<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResponseDetail extends Model
{
    public function master() 
    {
        return $this->belongsTo('App\Models\ResponseMaster');
    }


    public function question() 
    {
        return $this->hasOne('App\Models\Question', 'id', 'question_id');
    }
}
