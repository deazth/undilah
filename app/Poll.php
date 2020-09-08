<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Poll extends Model
{
  public function options(){
    return $this->hasMany(PollOption::class);
  }

  public function Users(){
    return $this->belongsToMany(User::class);
  }

  public function Owner(){
    return $this->belongsTo(User::class, 'user_id');
  }
}
