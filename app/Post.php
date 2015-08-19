<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $table = 'posts';

    protected $fillable = ['title', 'content'];

    public function user() {
      $this->belongsTo('App\User');
    }

    public function hub() {
      $this->belongsTo('App\Hub');
    }

    public function comments() {
      $this->hasMany('App\Comment');
    }
}
