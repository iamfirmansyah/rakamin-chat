<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    protected $keyType = 'string';

    public $incrementing = false;

    public $primaryKey  = 'id';

    protected $appends = ['with_user'];

    public function getWithUserAttribute()
    {
        if($this->attributes['to'] === auth()->user()->id){
            return User::where('id', $this->attributes['from'])->select('id', 'phone', 'fullname')->first();
        }

        return User::where('id', $this->attributes['to'])->select('id', 'phone', 'fullname')->first();

    }
}
