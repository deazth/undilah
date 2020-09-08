<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PollOption extends Model
{
  public function poll(){
    return $this->belongsTo(Poll::class);
  }

  public function votecounts(){
    return $this->anon_vote_count + $this->Users->count();
  }

  public function Users(){
    return $this->belongsToMany(User::class);
  }
}
