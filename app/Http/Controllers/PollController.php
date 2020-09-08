<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \Carbon\Carbon;
use App\Poll;
use App\PollOption;

class PollController extends Controller
{
  public function __construct()
  {
      $this->middleware('auth');
  }

  public function index(Request $req){

    $recent = Poll::where('status', 'Active')
      ->where('public', true)
      ->orderBy('created_at', 'DESC')
      ->limit(3)
      ->get();

    return view('polls.index', [
      'newp' => $recent
    ]);
  }

  public function mypolls(Request $req){

    $polls = Poll::where('user_id', $req->user()->id)->get();

    return view('polls.list', [
      'polls' => $polls
    ]);
  }

  public function createPoll(Request $req){

    $tomorrow = new Carbon;
    $tomorrow->addDay();

    return view('polls.create', [
      'tomorrow' => $tomorrow->toDateString()
    ]);
  }

  public function docreatePoll(Request $req){
    $po = new Poll;
    $po->user_id = $req->user()->id;
    $po->title = $req->title;
    $po->description = $req->desc;
    if($req->filled('actdate')){
      $po->end_time = $req->actdate;
    }

    if($req->has('privatepoll')){
      $po->public = false;
    } else {
      $po->public = true;
    }
    if($req->has('anonpoll')){
      $po->anonymous = true;
    } else {
      $po->anonymous = false;
    }

    $po->save();

    return redirect(route('poll.view', ['pid' => $po->id]));
  }

  public function deletePoll(Request $req){
    if($req->filled('pid')){
      $po = Poll::find($req->pid);
      if($po){

        if($req->user()->role < 2 || $po->user_id == $req->user()->id){
          $po->status = 'Closed';
          $po->save();

          return redirect()->back()->with([
            'a_type' => 'success',
            'alert' => 'poll closed'
          ]);
        } else {
          abort(403);
        }

      } else {
        return redirect(route('poll.index'))->with([
          'a_type' => 'warning',
          'alert' => 'poll not found'
        ]);
      }
    } else {
      return redirect(route('poll.index'));
    }
  }

  public function publishPoll(Request $req){
    if($req->filled('pid')){
      $po = Poll::find($req->pid);
      if($po){

        $po->status = 'Active';
        $po->save();

        return redirect()->back()->with([
          'a_type' => 'success',
          'alert' => 'poll published'
        ]);

      } else {
        return redirect(route('poll.index'))->with([
          'a_type' => 'warning',
          'alert' => 'poll not found'
        ]);
      }
    } else {
      return redirect(route('poll.index'));
    }
  }

  public function addOption(Request $req){
    if($req->filled('pid')){
      $po = Poll::find($req->pid);
      if($po){

        $opt = new PollOption;
        $opt->label = $req->optlable;
        $opt->description = $req->details;
        $opt->poll_id = $req->pid;
        $opt->save();

        return redirect()->back()->with([
          'a_type' => 'success',
          'alert' => 'poll option added'
        ]);

      } else {
        return redirect(route('poll.index'))->with([
          'a_type' => 'warning',
          'alert' => 'poll not found'
        ]);
      }
    } else {
      return redirect(route('poll.index'));
    }
  }

  public function removeOption(Request $req){
    $opt = PollOption::find($req->poid);
    if($opt){
      if($opt->poll->user_id == $req->user()->id){
        $opt->delete();
        return redirect()->back()->with([
          'a_type' => 'warning',
          'alert' => 'Poll option removed'
        ]);
      } else {
        return redirect()->back()->with([
          'a_type' => 'warning',
          'alert' => 'you are not the owner'
        ]);
      }
    } else {
      return redirect()->back();
    }
  }

  public function viewLogRedir(Request $req){
    return redirect(route('poll.view', ['pid' => $req->pid]));
  }



}
