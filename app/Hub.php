<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Hub extends Model
{
    protected $table = 'hubs';

    protected $fillable = ['name', 'description'];

    public function posts() {
      return $this->hasMany('App\Post');
    }
}
