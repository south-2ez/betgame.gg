<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Repositories\BetRepository;
use Carbon;
use Cache;
use App\Match;

class BndController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function showMatches()
    {

      $allowed = [71919,71920,72074,73085,48115];
      $user_id = \Auth::id();

      if(!\Auth::user()->isAdmin() && !in_array($user_id,$allowed)){
        abort(404);
      }

      
      $matches = Match::mainMatches()
                    ->whereIn('matches.status',['open','ongoing'])
                    ->orderBy('schedule', 'asc')
                    ->with('league','submatches')
                    ->get();

      $betRepo = new BetRepository;
      //$matchBetsCacheKey = "{$match->status}-match-{$match->id}-{$match->updated_at}";


      return view('bnd.matches-active', compact('matches'));
    }

    public function viewMatchSettings(\App\Match $match)
    {

      $allowed = [71919,71920,72074,73085,48115];
      $user_id = \Auth::id();

      if(!\Auth::user()->isAdmin() && !in_array($user_id,$allowed)){
        abort(404);
      }

      $betRepo = new BetRepository;
      $bnd_id = env('BND_USER_ID',1066);

      if($match->type == 'main'){
        $submatches = $match->subMatches->whereIn('game_grp', [0,1]);
        $main = $match;
      }else{
      
        $where = [
            'main_match' => $match->main_match,
            'game_grp' => $match->game_grp,
            'type' => 'sub',
        ];

        $submatches = \App\Match::where($where)
                        ->where('id', '!=', $match->id)
                        ->with('teamA','teamB')                              
                        ->get();
        $main = \App\Match::where('id',$match->main_match)->first();
      }

      return view('bnd.view-settings', compact('match', 'submatches','betRepo','bnd_id','main'));
    }

    public function viewMatchSettingsTest(\App\Match $match)
    {

      $allowed = [71919,71920,72074,73085,48115];
      $user_id = \Auth::id();

      if(!\Auth::user()->isAdmin() && !in_array($user_id,$allowed)){
        abort(404);
      }

      $betRepo = new BetRepository;
      $bnd_id = env('BND_USER_ID',1066);

      if($match->type == 'main'){
        $submatches = $match->subMatches->whereIn('game_grp', [0,1]);
        $main = $match;
      }else{
      
        $where = [
            'main_match' => $match->main_match,
            'game_grp' => $match->game_grp,
            'type' => 'sub',
        ];

        $submatches = \App\Match::where($where)
                        ->where('id', '!=', $match->id)
                        ->with('teamA','teamB')                              
                        ->get();
        $main = \App\Match::where('id',$match->main_match)->first();
      }

      return view('bnd.view-settings-test', compact('match', 'submatches','betRepo','bnd_id','main'));
    }
    
}
