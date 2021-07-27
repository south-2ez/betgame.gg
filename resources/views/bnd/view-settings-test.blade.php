@extends('layouts.main')

@section('styles')
  <style>
    .settings-container{
      padding: 20px;
    }

    .main-match{
      color: #fff
    }

    .panel-title{
      font-weight: bold;
      font-size: 18px;
    }

    .text-right{
      text-align: right;
    }

    .text-bold{
      font-weight: bold;
    }

    .text-red{
      color: red;
    }

    .match-status{
      text-transform: uppercase;
      float: right;
    }

    table td{
      font-size: 18px;
    }

    .time{
      width: 100%;
      margin-top: 10px;
      margin-bottom: 10px;
      text-align: center;
      color: #fff;
    }
  </style>
@endsection

@section('content')
<div class="settings-container">
  <h1 class="text-center main-match">
    {{ $match->type != 'main' ? $main->name .' | '. $match->name  : $match->name}}
  </h1>

  <div class="time">
      <h2>
        Match Schedule: {{$match->schedule}} &nbsp;
        @if($match->status == 'open' && $match->schedule < Carbon\Carbon::now())
        <strong style="color: green">(Starting)</strong>
        &nbsp;<strong>Match will start soon</strong>
        @elseif($match->status == 'open' && $match->schedule > Carbon\Carbon::now())
        <strong style="color: green">({{ucfirst($match->status)}})</strong>
        &nbsp;<strong id="match_schedule">{{$match->schedule->diffForHumans()}}</strong>
        @else
        <strong>
            @if($match->status == 'ongoing')
                <span style="color: #72A326; text-shadow: 1px 1px 0px #4A7010; font-weight: bold; font-size: 16px">LIVE</span>
            @else
                ({{ucfirst($match->status)}}) 

                @if($match->status == 'open')
                    {{$match->schedule->diffForHumans()}}
                @endif

                @if( ( $match->status == 'cancelled' || $match->status == 'draw') && !empty($match->more_info_link))
                    <!-- more info link -->
                    <a href="{{ $match->more_info_link}}" target="_blank">Why? or Read More...</a>
                @endif

            @endif
        </strong>
        @endif
      </h2>
  </div>    
  
  <div class="row">
    <div class="col col-md-6">
      <div class="panel panel-info">
        <div class="panel-heading">
          <h3 class="panel-title">
            {{ $match->type != 'main' ? $match->name  : 'Match Winner'}} <span class="match-status">({{ $match->status }})</span>
          </h3>
        </div>
        <div class="panel-body">
          <table class="table table-bordered">
            <thead>
              <td>&nbsp;</td>
              <td>{{ $match->teamA->name }}</td>
              <td>{{ $match->teamB->name }}</td>
            </thead>
            <tbody>
              @php 
                $match_id = $match->id;
                $matchBetsCacheKey = "{$match->status}-match-{$match->id}-{$match->updated_at}";
                $bets = $match->status == 'open' ? 
                $betRepo->getBetsByMatchId($match->id, ['id','amount','team_id','user_id','ratio','gains']) : 
                Cache::remember( md5($matchBetsCacheKey), 60 , function () use ($match_id, $betRepo) {
                    
                    return $betRepo->getBetsByMatchId($match_id, ['id','amount','team_id','user_id','ratio','gains','match_id']);
                });


                $bndBet = $bets->where('user_id', $bnd_id)->first();

                $adminMMUsersIds = getAdminMatchManagersUserIds();
                $team_a_bets = $bets->where('team_id', $match->team_a)->whereNotIn('user_id', $adminMMUsersIds)->sum('amount');
                $team_b_bets = $bets->where('team_id', $match->team_b)->whereNotIn('user_id', $adminMMUsersIds)->sum('amount');

                $matchRatios = calculateRealMatchTeamRatios($match, $bets);
                $teamBmaxPercent = 100 - $match->team_a_threshold_percent;
                $teamAbndPossibleOdds = $match->team_a_threshold_percent != 0 ? 
                          ( 
                            ( ( $teamBmaxPercent / $match->team_a_threshold_percent ) + 1 ) * 0.96
                          ) : 0;
                  

                $teamAmaxPercent = 100 - $match->team_b_threshold_percent;            
                $teamBbndPossibleOdds = $match->team_b_threshold_percent != 0 ? 
                          ( 
                            ( ( $teamAmaxPercent / $match->team_b_threshold_percent ) + 1 ) * 0.96
                          ) : 0;    

              @endphp
              <tr>
                <td class="text-right">Bets %:</td>
                <td>{{ bcdiv($matchRatios['team_a_percentage'],1,2) }}</td>
                <td>{{ bcdiv($matchRatios['team_b_percentage'],1,2) }}</td>
              </tr>
              <tr>
                <td class="text-right text-red">BND Settings %:</td>
                <td class="text-bold text-red">{{ $match->team_a_threshold_percent }}</td>
                <td class="text-bold text-red">{{ $match->team_b_threshold_percent }}</td>
              </tr>
              <tr>
                <td class="text-right text-red">BND % Odds:</td>
                <td class="text-bold text-red">{{ bcdiv($teamAbndPossibleOdds,1,2) }}</td>
                <td class="text-bold text-red">{{ bcdiv($teamBbndPossibleOdds,1,2) }}</td>
              </tr>              
              <tr>
                <td class="text-right">Odds:</td>
                <td>{{ bcdiv($matchRatios['team_a_ratio'],1,2) }}</td>
                <td>{{ bcdiv($matchRatios['team_b_ratio'],1,2) }}</td>
              </tr>
              <tr>
                <td class="text-right text-red">BND Betted Amount:</td>
                <td class="text-bold text-red">{{ !!$bndBet && $bndBet->team_id == $match->team_a ? number_format($bndBet->amount, 2, '.', ',') : 0  }}</td>
                <td class="text-bold text-red">{{ !!$bndBet && $bndBet->team_id == $match->team_b ? number_format($bndBet->amount, 2, '.', ',') : 0 }}</td>
              </tr>
              <tr>
                <td class="text-right text-red">Total Bets:</td>
                <td class="text-bold text-red">{{  number_format($team_a_bets, 2, '.', ',')  }}</td>
                <td class="text-bold text-red">{{  number_format($team_b_bets, 2, '.', ',') }}</td>
              </tr>              
            </tbody>

          </table>

        </div>
        <div class="panel-footer"><label>Match link: </label> <input type="text" class="form-control" readonly value="{{ url('/') . '/match/' . $match->id }}"/></div>
      </div>
    </div>

    @foreach($submatches as $sub)
      <div class="col col-md-6">
        <div class="panel panel-info">
          <div class="panel-heading">
            <h3 class="panel-title">{{ $sub->name }} <span class="match-status">({{ $match->status }})</span></h3>
          </div>
          <div class="panel-body">
          <table class="table table-bordered">
            <thead>
              <td>&nbsp;</td>
              <td>{{ $sub->teamA->name }}</td>
              <td>{{ $sub->teamB->name }}</td>
            </thead>
            <tbody>
              @php 
                $match_id = $sub->id;
                $matchBetsCacheKey = "{$sub->status}-match-{$sub->id}-{$sub->updated_at}";
                $bets = $sub->status == 'open' ? 
                $betRepo->getBetsByMatchId($sub->id, ['id','amount','team_id','user_id','ratio','gains']) : 
                Cache::remember( md5($matchBetsCacheKey), 60 , function () use ($match_id, $betRepo) {
                    
                    return $betRepo->getBetsByMatchId($match_id, ['id','amount','team_id','user_id','ratio','gains','match_id']);
                });

                $bndBet = $bets->where('user_id', $bnd_id)->first();

                $adminMMUsersIds = getAdminMatchManagersUserIds();
                $team_a_bets = $bets->where('team_id', $sub->team_a)->whereNotIn('user_id', $adminMMUsersIds)->sum('amount');
                $team_b_bets = $bets->where('team_id', $sub->team_b)->whereNotIn('user_id', $adminMMUsersIds)->sum('amount');

                $matchRatios = calculateRealMatchTeamRatios($sub, $bets);

                $teamBmaxPercent = 100 - $sub->team_a_threshold_percent;
                $teamAbndPossibleOdds = $sub->team_a_threshold_percent != 0 ? 
                          ( 
                            ( ( $teamBmaxPercent / $sub->team_a_threshold_percent ) + 1 ) * 0.96
                          ) : 0;
                  

                $teamAmaxPercent = 100 - $sub->team_b_threshold_percent;            
                $teamBbndPossibleOdds = $sub->team_b_threshold_percent != 0 ? 
                          ( 
                            ( ( $teamAmaxPercent / $sub->team_b_threshold_percent ) + 1 ) * 0.96
                          ) : 0;                
              @endphp
              <tr>
                <td class="text-right">Bets %:</td>
                <td>{{ bcdiv($matchRatios['team_a_percentage'],1,2) }}</td>
                <td>{{ bcdiv($matchRatios['team_b_percentage'],1,2) }}</td>
              </tr>     
              <tr>
                <td class="text-right text-red">BND Settings %:</td>
                <td class="text-bold text-red">{{ $sub->team_a_threshold_percent }}</td>
                <td class="text-bold text-red">{{ $sub->team_b_threshold_percent }}</td>
              </tr>
              <tr>
                <td class="text-right text-red">BND % Odds:</td>
                <td class="text-bold text-red">{{ bcdiv($teamAbndPossibleOdds,1,2) }}</td>
                <td class="text-bold text-red">{{ bcdiv($teamBbndPossibleOdds,1,2) }}</td>
              </tr>   
              <tr>
                <td class="text-right">Odds:</td>
                <td>{{ bcdiv($matchRatios['team_a_ratio'],1,2) }}</td>
                <td>{{ bcdiv($matchRatios['team_b_ratio'],1,2) }}</td>
              </tr>
              <tr>
                <td class="text-right text-red">BND Betted Amount:</td>
                <td class="text-bold text-red">{{ !!$bndBet && $bndBet->team_id == $sub->team_a ? number_format($bndBet->amount, 2, '.', ',') : 0  }}</td>
                <td class="text-bold text-red">{{ !!$bndBet && $bndBet->team_id == $sub->team_b ? number_format($bndBet->amount, 2, '.', ',') : 0 }}</td>
              </tr>  
              <tr>
                <td class="text-right text-red">Total Bets:</td>
                <td class="text-bold text-red">{{  number_format($team_a_bets, 2, '.', ',')  }}</td>
                <td class="text-bold text-red">{{  number_format($team_b_bets, 2, '.', ',') }}</td>
              </tr>                       
            </tbody>

          </table>
          </div>
          <div class="panel-footer"><label>Match link:</label>  <input type="text" class="form-control" readonly value="{{ url('/') . '/match/' . $sub->id }}"/></div>
        </div>
      </div>
    @endforeach
  </div>


@endsection

  <script type="text/javascript" src="{{ asset('js/jquery.min.js') }}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.6/moment.min.js"></script>
  <script type="text/javascript">
    $(document).ready(function() {

        var countDownDate = parseInt(moment("{{$match->schedule}}").format('x'));
        var x = setInterval(function() {

            // Get todays date and time
            var now = new Date().getTime();

            // Find the distance between now an the count down date
            var distance = countDownDate - now;

            // Time calculations for days, hours, minutes and seconds
            var days = Math.floor(distance / (1000 * 60 * 60 * 24));
            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);

            // Display the result in the element with id="demo"
            $('#match_schedule').html((days > 0 ? days + " days " : "") + (hours > 0 ? hours + "h " : "") + (minutes > 0 ? minutes + " minutes " : "") + seconds + "s ");
            // $('#match_schedule').html("Match will start soon");

            // If the count down is finished, write some text 
            if (distance < 0) {
                clearInterval(x);
                $('#match_schedule').html('Match will start soon');
            }
        }, 1000);
    });
  </script>

