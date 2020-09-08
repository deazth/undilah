<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Poll;
use App\PollOption;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }


    public function vote(Request $req){

      $po = Poll::find($req->pid);
      if($po){
        $voted = false;
        if(\Auth::check()){
          $voted = $req->user()->Votes->where('id', $req->pid)->count() != 0;
        } else {
          if(session()->has('v'.$req->pid)){
            $voted = true;
          }
        }

        if($voted){
          return redirect()->back()->with([
            'a_type' => 'warning',
            'alert' => 'You have already voted for this poll'
          ]);
        }

        $opt = PollOption::find($req->voteid);
        if($opt){
          // set this user as voted
          if(\Auth::check()){
            $po->Users()->attach($req->user());
            // then assign the vote option
            $opt->Users()->attach($req->user());
          } else {
            $opt->anon_vote_count = $opt->anon_vote_count + 1;
            $opt->save();
            session(['v'.$req->pid => 'done']);
          }

          return redirect()->back()->with([
            'a_type' => 'success',
            'alert' => 'Thank you for your vote'
          ]);

        } else {
          return redirect()->back()->with([
            'a_type' => 'warning',
            'alert' => 'Invalid poll option'
          ]);
        }


      } else {
        return redirect(route('poll.index'))->with([
          'a_type' => 'warning',
          'alert' => 'poll not found'
        ]);
      }

    }

    public function viewPoll(Request $req){
      if($req->filled('pid')){
        $po = Poll::find($req->pid);
        if($po){

          if($po->status != 'Draft'){
            $graphdata = $this->getGraph($po);
            // dd($graphdata);
          } else {
            $graphdata = false;
          }

          if(\Auth::check() && $po->user_id == $req->user()->id){
            return view('polls.polladdopt', [
              'poll' => $po, 'graph' => $graphdata
            ]);
          } else {
            $voted = false;
            // check if already voted
            if(\Auth::check()){
              $voted = $req->user()->Votes->where('id', $req->pid)->count() != 0;
            } else {
              // anon. check from session
              if(session()->has('v'.$req->pid)){
                $voted = true;
              }
            }

            if($po->status != 'Active'){
              $voted = true;
            }

            return view('polls.vote', ['poll' => $po, 'voted' => $voted, 'graph' => $graphdata]);
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

    private function getGraph($poll){
      $counter = 0;
      $label = [];
      $value = [];
      // $bgcolor = ['rgba(255, 99, 132, 0.6)', 'rgba(75, 192, 192, 0.6)', 'rgba(255, 99, 132, 0.6)', 'rgba(75, 192, 192, 0.6)'];

      foreach($poll->options as $prtn){
        $counter++;
        array_push($label, $prtn->label);
        array_push($value, $prtn->votecounts());
        // if(($counter % 2) == 1){
        //   array_push($bgcolor, 'rgba(255, 99, 132, 0.6)');
        // } else {
        //   array_push($bgcolor, 'rgba(75, 192, 192, 0.6)');
        // }
      }

      if(count($value) < 4){
        $heighttt = 200;
        $typec = 'bar';
      } else {
        $heighttt = 40 + (20 * count($value));
        $typec = 'horizontalBar';
      }


      $schart = app()->chartjs
           ->name('barChartTest')
           ->type($typec)
           ->size(['width' => 400, 'height' => $heighttt])
           ->labels($label)
           ->datasets([
               [
                   "label" => "Votes",
                   'backgroundColor' => 'rgba(75, 192, 192, 0.6)',
                   'data' => $value
               ]
           ])
           ->options([
             'responsive' => true,
             'title' => [
               'display' => true,
               'text' => $poll->title,
             ],
             'tooltips' => [
               'mode' => 'index',
               'intersect' => false,
             ],
             'hover' => [
               'mode' => 'nearest',
               'intersect' => true,
             ],
             'scales' => [
               'xAxes' => [[
                 'display' => true,
                 'scaleLabel' => [
                   'display' => true,
                   'LabelString' => 'Count',
                 ]
               ]],
               'yAxes' => [[
                 'display' => true,
                 'scaleLabel' => [
                   'display' => true,
                   'LabelString' => 'Poll Option',
                 ]
               ]]
             ]
           ]);
      return $schart;

    }
}
