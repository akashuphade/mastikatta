<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResponseMaster extends Model
{
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function details()
    {
        return $this->hasMany('App\Models\ResponseDetail');
    }
}
