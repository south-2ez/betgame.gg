@extends('layouts.main')

@section('styles')
<style>
    .match, .winnings {
        float: left;
        width: 100%;
        min-height: 75px;
        background-color: #BBB;
        background-repeat: no-repeat;
        background-position: right;
        border-radius: 5px;
        box-shadow: 1px 1px 2px #888;
        margin: 5px 0px;
        padding: 2px;
        background-size: cover;
    }
    .match .oitm {
        width: 24%;
    }
    .matchheader {
        float: left;
        width: 100%;
        padding: 0.6em 1%;
        border-top: solid 1px #ccc;
        background: #e3e3e3;
        background: radial-gradient(ellipse at top, #eee 0%,#d7d7d7 70%);
        font-size: 100%;
    }
    .matchright {
        width: 28%;
        float: left;
        margin: 0.5em 1%;
    }
    .whenm, .eventm {
        font-size: 0.8rem;
        float: left;
        text-shadow: 1px 1px 0 #E5E5E5;
    }
    .eventm {
        float: right;
    }
    .tournament {
        width: 60%;
        float: right;
    }
    .betpoll {
        float: left;
        min-height: 10px;
        float: left;
        margin: 0.5em 1.5%;
        width: 97%;
    }
    .betpoll #active {
        min-height: 50px;
        display: block;
    }
    .betpoll .oitm {
        width: 24%;
    }
    .betpoll .left {
        margin: 0;
    }
    .betpoll .left::before {
        display: none;
    }
    .winsorloses {
        float: left;
        width: 98%;
        max-width: 400px;
        margin: 0.5em 1%;
        background: #BBB;
        -webkit-border-radius: 5px;
        border-radius: 5px;
    }
    .winsorloses .oitm {
        width: 24%;
    }
    .betheader {
        text-overflow: ellipsis;
        white-space: nowrap;
        overflow: hidden;
        margin: 0.5em 2% 0 2%;
        width: 96%;
        float: left;
    }
    .team {
        width: 60px;
        height: 50px;
        -webkit-border-radius: 5px;
        border-radius: 5px;
    }
    .teamtext {
        background: rgba(187, 187, 187, 0.9);
        border-radius: 5px;
        padding: 5px;
        line-height: 1.4em;
        text-align: center;
        text-transform: none;
    }
    .teamtext i {
        font-style: normal;
    }
    
    .matchleft a,
    .match-details a {
        color: #333;
        text-decoration: none;
        cursor: pointer;
    }
    
    .format {
        font-size: 0.7em;
        font-weight: bold;
    }
    .tournament_bet {
        text-align: center;
        background-image: url({{asset('images/bg_03.jpg')}}); 
        position: relative; 
        background-position: center; 
        background-size: cover; 
        background-repeat: no-repeat; 
        /*min-height: 600px;*/
        padding: 10px;
    }
    .tournament_bet th {
        background-color: #717171;
        color: #ffffff;
        font-weight: normal;
        padding: 0;
    }
    .tournament_bet tbody tr td {
        vertical-align: middle;
    }
    .tournament_bet tbody tr td:first-child {
        padding: 0; 
        position: relative;
    }
    .favorite_team {
        position: absolute; 
        right: 0px; 
        bottom: 0px; 
        background-image: url({{asset('images/fav.png')}}); 
        width: 25px; 
        height: 25px;
    }
    .sweet-alert {
        width: 850px !important;
    }
        /* Style the buttons */
    .btncategory, .img {
      border: none;
      outline: none;
      padding: 10px 16px;
      background-color: #f1f1f1;
      opacity: 0.5;
    }
    /* Style the active class (and buttons on mouse-over) */
     .btncategory:hover {
      background-color: #f1f1f1;
      opacity: 1.0;
    }
    .focusbtn.selected{
        background-color: #f1f1f1;
        border-bottom: 2px solid #F39C12;
        opacity: 1.0;
    }
    /* Show more buttons style class */
    .btndota, .btncsgo, .btnsports, .btnlol, .btnnbaplayoffs {
        display: none !important;
    }
    .match-details{
        background: rgb(239 239 239 / 75%);
        padding: 10px;
        border-radius: 10px;
        font-size: 16px;
        margin-top: 10px;
        margin-bottom: 7px;
        color: #0f0f0f;
        text-decoration: none;
        cursor: pointer;        
    }

    .match-details .team-name{
        font-size: 14px;
    }

    .bet-percentage{
        margin: 0px 5px;
    }

    .draw-text{
        position: relative;
        bottom: -10px;
    }

    .no-padding{
        padding-left: 0px;
        padding-top: 0px;
        padding-right: 0px;
        padding-bottom: 0px;
    }

    .pr-1{
        padding-right: 5px;
    }

    .pl-1{
        padding-left: 5px;
    }

    .flex-center{
        display: flex;
        align-items: center;
        width: 100%;
        
    }

    .match-team-logo-container{
        margin-top: 5px;
    }

    .match-label-container{
        margin-top: 5px;
        margin-bottom: 5px;
    }

    .small-gutter{
        margin-left: -1px !important;
        margin-right: -1px !important;
    }

    /*Category text media only screen*/
    @media only screen and (max-width: 377px){
        .txt{
            font-size: 10px;
        }
    }
    /*Category text media only screen*/
    @media only screen and (max-width: 320px){
        .txt{
            font-size: 8px;
        }
        .txtteam{
            font-size: 20px;
        }
    }
    /*Image no match media only screen*/
    @media only screen and (max-width: 320px){
        .imgnomatch{
            width: 255px;
        }
    }
    /*Image no match media only screen*/
    @media only screen and (max-width: 2560px){
        .imgnomatch{
            width: 40%;
        }
    }
    .matchLabel {background: #fff; padding-left: 5px; padding-right: 5px}

    .match-team-logo{
        max-width: 81px;
        max-height: 72px;
    }
    .losing-team{
        opacity: 0.7;
    }

</style>
<link rel="stylesheet" href="{{ asset('bower_components/bootstrap-sweetalert/dist/sweetalert.css') }}">
@endsection

@section('content')
<div class="main-container" id="app2ez">
    <div class="container app-container">
        <div>
            <div class="col-md-7">
                <div class="row margin-bottom-20 type-container">
                    <button id="loadAll" type="button" class="all-match-button btn btn-info btn-all-matches categorybtn" data-pointer="0">All Games</button>
                    <img id="loadDota" class="dota-match-button icon-game-type img-circle categorybtn" data-pointer="0" src="{{ asset('images/dota-icon.png') }}"/>
                    <img id="loadCsgo" class="csgo-match-button icon-game-type img-circle categorybtn" data-pointer="0" src="{{ asset('images/csgo-icon.png') }}"/>
                    <img id="loadLol" class="lol-match-button icon-game-type img-circle categorybtn" data-pointer="0" src="{{ asset('images/lol-icon.png') }}"/>
                    <img id="loadNbaPlayoffs" class="nbaplayoffs-match-button icon-game-type img-circle categorybtn" data-pointer="0" src="{{ asset('images/nba-icon.png') }}"/>
                    <img id="loadSports" class="sports-match-button icon-game-type img-circle categorybtn" data-pointer="0" src="{{ asset('images/sports-icon.png') }}"/>

                    <div class="menu pull-right margin-right-18 ur-mobile">
                        <button type="button" class="btn btn-link" id="btn-upcoming">Upcoming</button>
                        <span class="span-divider">|</span>
                        <button type="button" class="btn btn-link" id="btn-result">Results</button>
                    </div>
                </div>

                <div class="row upcoming-display">
                    <div class="col-md-12" id="matchesHolder">
                    @foreach($matches as $index => $match)
                        <a href="{{ url('/') . '/match/' . $match->id }}">
                            <div class="row game-card matchmain">
                                <div class="col-md-4 col-xs-12 game-card-img hide-mobile" style="background-image: url({{ url('/images') . '/' . $match->league->image }});">
                                    <span class="game-card-time">
                                    @if($match->status == 'ongoing')
                                        <img class="" src="{{ asset('images/live.png') }}"/>
                                    @elseif($match->status == 'settled')
                                        {{$match->schedule->diffForHumans()}} <span style="color: #606060; font-weight: bold; font-size: 16px">&nbsp;SETTLED</span>
                                    @elseif($match->status == 'draw')
                                        {{$match->schedule->diffForHumans()}} <span style="color: #606060; font-weight: bold; font-size: 16px">&nbsp;DRAW - CREDITS RETURNED</span>
                                    @else
                                    <span class="match_countdown" data-schedule="{{$match->schedule}}">{{$match->schedule->diffForHumans()}}</span>
                                    @endif
                                    </span>
                                </div>
                                <div class="col-md-8 col-xs-12 game-card-padding">
                                    <div class="row text-center game-card-title-container">
                                        <span class="game-card-title align-baseline">{{ !!$match->label ? $match->label : '' }}</span>
                                    </div>

                                    <div class="row text-center">
                                        <div class="col-md-4 col-xs-4">
                                            <div>
                                                <img class="match-team-logo" src="{{ $match->teamA->image }}" />
                                            </div>
                                            <div class="match-team-name">
                                                {!! str_limit($match->teamA->shortname, 10, '..') !!}
                                            </div>
                                            <div class="match-team-percentage">
                                                {{ $match->teama_percentage }}%
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-xs-4"><div class="match-type">{{$match->best_of}}</div></div>
                                        <div class="col-md-4 col-xs-4">
                                            <div>
                                                <img class="match-team-logo" src="{{ $match->teamB->image }}" />
                                            </div>
                                            <div class="match-team-name">
                                                {!! str_limit($match->teamB->shortname, 10, '..') !!}
                                            </div>
                                            <div class="match-team-percentage">
                                                {{ $match->teamb_percentage }}%
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 col-xs-12 game-card-img" style="background-image: url({{ url('/images') . '/' . $match->league->image }});">
                                    <span class="game-card-time">
                                    @if($match->status == 'ongoing')
                                        <img class="" src="{{ asset('images/live.png') }}"/>
                                    @elseif($match->status == 'settled')
                                        {{$match->schedule->diffForHumans()}} <span style="color: #606060; font-weight: bold; font-size: 16px">&nbsp;SETTLED</span>
                                    @elseif($match->status == 'draw')
                                        {{$match->schedule->diffForHumans()}} <span style="color: #606060; font-weight: bold; font-size: 16px">&nbsp;DRAW - CREDITS RETURNED</span>
                                    @else
                                    <span class="match_countdown" data-schedule="{{$match->schedule}}">{{$match->schedule->diffForHumans()}}</span>
                                    @endif
                                    </span>
                                </div>


                        </a>
                    @endforeach
                    </div>
                </div>

                <div class="row upcoming-display {{ (count($matches)) == 0 ? 'display-none' : '' }}" id="show-more-container">
                    <div class="col-md-12" id="matchesHolder">
                        <div class="row game-card-more matchmain">
                            <div class="col-md-12 text-center">
                                <!-- Show more buttons all -->
                                <button id="loadAllMore" type="button" class="btn btn-info btn-show-more hidebtn btnall loadMoreBtn" data-pointer="0" data-loading-text="Loading ... <span class='glyphicon glyphicon-refresh fa-spin'></span>"><span><b>Show More <i class="fa fa-angle-double-down"></i></b></span></button>

                                <!-- Show more button dota -->
                                <button id="loadDotaMore" type="button" class="btn btn-info btn-show-more hidebtn btndota loadMoreBtn" data-pointer="0" data-loading-text="Loading ... <span class='glyphicon glyphicon-refresh fa-spin'></span>"><span><b>Show More <i class="fa fa-angle-double-down"></i></b></span></button>

                                <!-- Show more button csgo -->
                                <button id="loadCsgoMore" type="button" class="btn btn-info btn-show-more hidebtn btncsgo loadMoreBtn" data-pointer="0" data-loading-text="Loading ... <span class='glyphicon glyphicon-refresh fa-spin'></span>"><span><b>Show More <i class="fa fa-angle-double-down"></i></b></span></button>

                                <button id="loadLolMore" type="button" class="btn btn-info btn-show-more hidebtn btnlol loadMoreBtn" data-pointer="0" data-loading-text="Loading ... <span class='glyphicon glyphicon-refresh fa-spin'></span>"><span><b>Show More <i class="fa fa-angle-double-down"></i></b></span></button>

                                <!-- Show more button sports -->
                                <button id="loadSportsMore" type="button" class="btn btn-info btn-show-more hidebtn btnsports loadMoreBtn" data-pointer="0" data-loading-text="Loading ... <span class='glyphicon glyphicon-refresh fa-spin'></span>"><span><b>Show More <i class="fa fa-angle-double-down"></i></b></span></button>

                                <button id="loadNbaPlayoffsMore" type="button" class="btn btn-info btn-show-more hidebtn btnnbaplayoffs loadMoreBtn" data-pointer="0" data-loading-text="Loading ... <span class='glyphicon glyphicon-refresh fa-spin'></span>"><span><b>Show More <i class="fa fa-angle-double-down"></i></b></span></button>
                                <!-- <button type="button" class="btn btn-info btn-show-more"><span><b>Show More</b></span></button> -->
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row result-display">
                @if( !empty(Route::current()) && Route::current()->getName() != 'admin' && Route::current()->getName() != 'agent' && Route::current()->getName() != 'bnd.matches.active' && Route::current()->getName() != 'bnd.match.view' && Route::current()->getName() != 'matchmaker' )
                    @if( Route::current()->getName() == 'match.view')
                        @if(Auth::guest() || (Auth::guest() == false && Auth::user()->type == 'user'))
                            <recent-matches></recent-matches>
                        @endif
                    @else 

                    <ul class="ul-recent-matches">
                        <li class="li-recent-matches display-none" id="recent-matches-template">
                            <div data-v-9b896f18="" class="row">
                                <div data-v-9b896f18="" class="col-xs-9">
                                    <div data-v-9b896f18="" class="row">
                                        <div data-v-9b896f18="" class="col-xs-2">
                                            <img data-v-9b896f18="" src="/public_image/1622450610.png" class="results-team-logo team-a-img">
                                        </div>
                                        <div data-v-9b896f18="" class="col-xs-8 ">
                                            <span data-v-9b896f18="" class="text-green team-a-name">
                                                INF
                                            </span>
                                        </div>
                                        <div data-v-9b896f18="" class="col-xs-2 team-a-win-count">2</div>
                                    </div>
                                    <div data-v-9b896f18="" class="row">
                                        <div data-v-9b896f18="" class="col-xs-2">
                                            <img data-v-9b896f18="" src="/public_image/1622439192.png" class="results-team-logo team-b-img">
                                        </div>
                                        <div data-v-9b896f18="" class="col-xs-8">
                                            <span data-v-9b896f18="" class="text-gray team-b-name">
                                                simply TOOBASED
                                            </span>
                                        </div>
                                        <div data-v-9b896f18="" class="col-xs-2 team-b-win-count">0</div>
                                    </div>
                                </div>

                                
                                <div data-v-9b896f18="" class="col-xs-3">
                                    <i class="text-danger cancelled-match display-none" aria-hidden="true">Cancelled</i>
                                    <i class="draw-match draw-match-d display-none" aria-hidden="true">Draw</i>
                                    <i class="draw-match draw-match-c display-none" aria-hidden="true">Draw</i>
                                    <i class="text-danger forfeit-match display-none" aria-hidden="true">Forfeit</i>

                                    <div data-v-9b896f18="" class="row trophy-container">
                                        <div data-v-9b896f18="" class="col-xs-12 team-a-trophy">
                                            <i data-v-9b896f18="" aria-hidden="true" title="Winner" class="fa fa-trophy text-primary display-none"></i>
                                        </div>
                                    </div>
                                    <div data-v-9b896f18="" class="row trophy-container">
                                        <div data-v-9b896f18="" class="col-xs-12 team-a-trophy">
                                            <i data-v-9b896f18="" aria-hidden="true" title="Winner" class="fa fa-trophy text-primary display-none"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>

                    
                    @endif





                    
                    <user-messages logged-in="{{ Auth::guest() ? 0 : 1 }}"></user-messages>
                    {{-- <chinese-new-year-flip-cards logged-in="{{ Auth::guest() ? 0 : 1 }}"></chinese-new-year-flip-cards> --}}
                    @if(!Auth::guest() && Auth::user()->type == 'user' &&  Auth::user()->credits >= 100)
                        <easter-egg-event logged-in="{{ Auth::guest() ? 0 : 1 }}" user-id="{{ Auth::id() }}"></easter-egg-event>
                    @endif
                @endif
                </div>
            </div>

            <div class="col-md-5 events-mobile">
                <div class="row margin-bottom-28">
                    <div class="col-md-12">
                        <div class="event-container">Events ({{ count($leagues) }})</div>
                    </div>
                </div>

                <div class="panel-group" id="accordion">
                @foreach($leagues as $key => $league)
                    <div class="panel panel-default event-panel">
                        <div class="panel-heading event-panel-heading">
                            <div class="row">
                                <div class="col-md-6">
                                    <button type="button" class="btn btn-link btn-event-open">Open</button>
                                </div>
                                <div class="col-md-6 text-right">
                                    <a href="{{url('/tournament') . '/' . $league->id}}">
                                        <button type="button" class="btn btn-warning btn-sm btn-place-bet">Place Bet</button>
                                    </a>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 text-center league-title">
                                    {{$league->name}}
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 text-center league-title">
                                    <span class="info-box-icon collapse-event bg-aqua" data-toggle="collapse" data-parent="#accordion" href="#collapse{{$key}}"><i class="fa fa-angle-down" aria-hidden="true"></i></span>
                                </div>
                            </div>
          
                        </div>
                        <div id="collapse{{$key}}" class="panel-collapse collapse {{ ($key == 0) ? 'in' : '' }}">
                            <div class="panel-body event-panel-body">

                                <table class="event-table">
                                    <thead>
                                        <tr class="tr-event">
                                            <th class="table-th text-center">Team</th>
                                            <th class="table-th text-center">Winning %</th>
                                            <th class="table-th text-center">Ratio</th>
                                            <th class="table-th text-center">Possible win per 100</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($league->teams as $index => $team)
                                        <tr class="tr-event">
                                            <th class="text-center"><img src="{{asset($team->image)}}" title="{{$team->name}}" style="width: 82px;"></th>
                                            <td class="text-center">{{ round($team->teamPercentage, 2) }}%</td>
                                            <td class="text-center">{{ bcdiv($team->teamRatio, 1 ,2) }}</td>
                                            <td class="text-center">
                                            @if($team->teamRatio > 0)
                                                <strong style="color: green">&#8369; {{ number_format((100 * bcdiv($team->teamRatio, 1 ,2) ), 2, '.', ',') }}</strong>
                                                @else
                                                &#8369; 0
                                            @endif
                                            </td>
                                    @endforeach
                                        </tr>
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                @endforeach
                </div>






            </div>
        </div>
    </div>
</div>

<div id="pending-deposit" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title text-bold" id="exampleModalLabel"> 
                    <strong>Pending Purchase:</strong>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </h2>
            </div>
            <div class="modal-body">
                You have an incomplete purchase. Kindly upload any needed document.
                <br><br>

                <form class="form-horizontal" action="/action_page.php">
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="email">BC #:</label>
                        <div class="col-sm-10">
                        <input type="text" class="form-control" readonly value="{{ $pending_deposit->code ?? '' }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="amount">Amount:</label>
                        <div class="col-sm-10">
                        <input type="text" class="form-control" readonly value="{{ $pending_deposit->amount ?? '' }}">
                        </div>
                    </div>

                    @if($pending_deposit && $pending_deposit->deposit_type == 'direct')
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="amount">MOP:</label>
                        <div class="col-sm-10">
                        <input type="text" class="form-control" readonly value="{{ $pending_deposit->data->mop ?? '' }}">
                        </div>
                    </div>
                    @endif

                    @if($pending_deposit && $pending_deposit->deposit_type == 'partner')
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="amount">Partner:</label>
                        <div class="col-sm-10">
                        <input type="text" class="form-control" readonly value="{{ $pending_deposit->partner->partner_name ?? '' }}">
                        </div>
                    </div>
                    @endif

                </form>

                @if($pending_deposit)
                <a type="button" class="btn btn-link" href="{{ url('/my-purchase/' . $pending_deposit->deposit_type . '/' . $pending_deposit->id) }}">Navigate to Purchase page</a>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btn-purchase-reminder">Remind me later</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script id="matches-template" type="text/template">
<a href="{{url('/')}}/match/@{{id}}">
        <div class="row game-card matchmain">
            <div class="col-md-4 game-card-img" style="background-image: url({{url('/images')}}/@{{league.image}});">
                <span class="game-card-time">
                @{{#ongoing}}
                    <img class="" src="{{ asset('images/live.png') }}"/>
                @{{/ongoing}}
                @{{#open}}
                    <span class="match_countdown" data-schedule="@{{formatted_schedule}}">@{{formatted_schedule}}</span>
                @{{/open}}
                </span>
            </div>
            <div class="col-md-8 game-card-padding">
                <div class="row text-center game-card-title-container">
                    <span class="game-card-title align-baseline">@{{label}}</span>
                </div>

                <div class="row text-center">
                    <div class="col-md-4">
                        <div>
                            <img class="match-team-logo" src="{{url('/')}}/@{{team_a.image}}" />
                        </div>
                        <div class="match-team-name">
                            <div class="team-name hide-mobile">
                                @{{team_a.name}}
                            </div>
                            <div class="team-name hide-desktop">
                                @{{team_a.shortname}}
                            </div>
                        </div>
                        <div class="match-team-percentage">
                            @{{teama_percentage}}%
                        </div>
                    </div>
                    <div class="col-md-4"><div class="match-type">{{$match->best_of}}</div></div>
                    <div class="col-md-4">
                        <div>
                            <img class="match-team-logo" src="{{url('/')}}/@{{team_b.image}}" />
                        </div>
                        <div class="match-team-name">
                            <div class="team-name hide-mobile">
                                @{{team_b.name}}
                            </div>
                            <div class="team-name hide-desktop">
                                @{{team_b.shortname}}
                            </div>
                        </div>
                        <div class="match-team-percentage">
                            @{{teamb_percentage}}%
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </a>
</script>
<!-- Matches template Ongoing and Open -->
<script id="matches" type="text/template">
    <a href="{{url('/')}}/match/@{{id}}">
        <div class="row game-card matchmain">
            <div class="col-md-4 game-card-img" style="background-image: url({{url('/images')}}/@{{league.image}});">
                <span class="game-card-time">
                @{{#ongoing}}
                    <img class="" src="{{ asset('images/live.png') }}"/>
                @{{/ongoing}}
                @{{#open}}
                    <span class="match_countdown" data-schedule="@{{formatted_schedule}}">@{{formatted_schedule}}</span>
                @{{/open}}
                </span>
            </div>
            <div class="col-md-8 game-card-padding">
                <div class="row text-center game-card-title-container">
                    <span class="game-card-title align-baseline">@{{label}}</span>
                </div>

                <div class="row text-center">
                    <div class="col-md-4">
                        <div>
                            <img class="match-team-logo" src="{{url('/')}}/@{{team_a.image}}" />
                        </div>
                        <div class="match-team-name">
                            <div class="team-name hide-mobile">
                                @{{team_a.name}}
                            </div>
                            <div class="team-name hide-desktop">
                                @{{team_a.shortname}}
                            </div>
                        </div>
                        <div class="match-team-percentage">
                            @{{teama_percentage}}%
                        </div>
                    </div>
                    <div class="col-md-4"><div class="match-type">{{$match->best_of}}</div></div>
                    <div class="col-md-4">
                        <div>
                            <img class="match-team-logo" src="{{url('/')}}/@{{team_b.image}}" />
                        </div>
                        <div class="match-team-name">
                            <div class="team-name hide-mobile">
                                @{{team_b.name}}
                            </div>
                            <div class="team-name hide-desktop">
                                @{{team_b.shortname}}
                            </div>
                        </div>
                        <div class="match-team-percentage">
                            @{{teamb_percentage}}%
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </a>
</script>
<!-- No matches template -->
<script id="nomatch" type="text/template" >
    <div class="matchmain nomatches">
        <div class="row" style="text-align: center;">
            <span>No active matches available.</span>
        </div>
        <div class="row" style="text-align: center;">
            <img src="{{asset('images/BB-PNG.png')}}" class="imgnomatch">
        </div>
    </div>
</script>
<!-- Category button click focus -->
<script type="text/javascript">
    //Selected button category
    $('.focusbtn').on('click', function(){
        $('.focusbtn').removeClass('selected');
        $(this).addClass('selected');
    });
</script>
<!-- Show more buttons hide attribute when clicked -->
<script type="text/javascript">
$(document).ready(function(){
    $(".all-match-button").click(function(){
        $(".btnall").attr('style','display: inline !important');
        $(".btndota").attr('style','display: none !important');
        $(".btncsgo").attr('style','display: none !important');
        $(".btnsports").attr('style','display: none !important');
        $(".btnlol").attr('style','display: none !important');
        $(".btnnbaplayoffs").attr('style','display: none !important');
    });
    $(".dota-match-button").click(function(){
        $(".btnall").attr('style','display: none !important');
        $(".btndota").attr('style','display: inline !important');
        $(".btncsgo").attr('style','display: none !important');
        $(".btnsports").attr('style','display: none !important');
        $(".btnlol").attr('style','display: none !important');
        $(".btnnbaplayoffs").attr('style','display: none !important');        
    });
    $(".csgo-match-button").click(function(){
        $(".btnall").attr('style','display: none !important');
        $(".btncsgo").attr('style','display: inline !important');
        $(".btndota").attr('style','display: none !important');
        $(".btnsports").attr('style','display: none !important');
        $(".btnlol").attr('style','display: none !important');
        $(".btnnbaplayoffs").attr('style','display: none !important');        
    });
    $(".sports-match-button").click(function(){
        $(".btnall").attr('style','display: none !important');
        $(".btnsports").attr('style','display: inline !important');
        $(".btndota").attr('style','display: none !important');
        $(".btncsgo").attr('style','display: none !important');
        $(".btnlol").attr('style','display: none !important');
        $(".btnnbaplayoffs").attr('style','display: none !important');        
    });
    $(".lol-match-button").click(function(){
        $(".btnall").attr('style','display: none !important');
        $(".btndota").attr('style','display: none !important');
        $(".btncsgo").attr('style','display: none !important');
        $(".btnsports").attr('style','display: none !important');
        $(".btnlol").attr('style','display: inline !important');
        $(".btnnbaplayoffs").attr('style','display: none !important');    
    });    
    $(".nbaplayoffs-match-button").click(function(){
        $(".btnall").attr('style','display: none !important');
        $(".btndota").attr('style','display: none !important');
        $(".btncsgo").attr('style','display: none !important');
        $(".btnsports").attr('style','display: none !important');
        $(".btnlol").attr('style','display: none !important');
        $(".btnnbaplayoffs").attr('style','display: inline !important');    
    });
});
</script>
<script type="text/javascript" src="{{ asset('js/moment.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('bower_components/bootstrap-sweetalert/dist/sweetalert.min.js')}}"></script>
<script src="{{ asset('js/mustache.min.js') }}"></script>
<script>    
    $(function(){
        var now = moment(moment().format('YYYY-MM-DD'));
        var to_tournament_date = moment("2017-08-03T00:00:00+08:00").fromNow();
        $('.tournament-winner-timer').html(to_tournament_date);

    })

    $(document).ready(function() {
        // var app = new Vue({
        //     el: '#app2ez',
        //     data: function () {
        //         return {
        //             category    : "loadAll",
        //             selected_tab: 'upcoming',
        //         }
        //     },
        //     methods: {
        //         // callChildCreateItem: function() {
        //         //     this.$refs.child.createItem()
        //         // },
        //         getRecentMatches: function(category) {
        //             this.category = category;
        //             console.log('tr',this.$refs.recentMatches)
        //             this.$refs.recentMatches.test(category);
        //         },
        //         selectTab: function(tab) {
        //             this.selected_tab = tab;
        //         }
        //     },
        //     created() {
        //         this.selected_tab = 'upcoming';

        //         console.log(this.selected_tab, 'add')
        //     }
        // });


        checkPendingDeposit();

        $.each($('.match_countdown'), function(key, index) {
            countdown(this, $(this).data('schedule'));
        });
        
        function countdown(element, time) {
            var countDownDate = parseInt(moment(time).format('x'));
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
                element.innerHTML = "in " + (days > 0 ? days + "d " : "") + (hours > 0 ? hours + "h " : "") + (minutes > 0 ? minutes + "m " : "") + seconds + "s";

                // If the count down is finished, write some text 
                if (distance < 0) {
                    clearInterval(x);
                    element.innerHTML = "Match will start soon";
                }
            }, 1000);
        }

        // Show more buttons function
        $('.loadMoreBtn').click(function() {
            $btn = $(this);
            $btn.button('loading');
            var recent = $(this).attr('id');
            var currIndex = $('#matchesHolder .matchmain:last').index();
            var container = $("#matches-template").html();
            var container2 = $("#nomatch").html();
            var url2 = "";
            switch(recent){
                case 'loadAllMore':
                    url2 = "{{url('/match/load')}}/" + $btn.data('pointer')
                    break;
                case 'loadDotaMore':
                    url2 = "{{url('/match/loadDota')}}/" + $btn.data('pointer')
                    break;
                case 'loadCsgoMore':
                    url2 = "{{url('/match/loadCsgo')}}/" + $btn.data('pointer')
                    break;
                case 'loadSportsMore':
                    url2 = "{{url('/match/loadSports')}}/" + $btn.data('pointer')
                    break;
                case 'loadLol':
                case 'loadLolMore':
                    url2 = "{{url('/match/loadLol')}}/" + $btn.data('pointer')
                    break;
                case 'loadNbaPlayoffs':
                    url2 = "{{url('/match/loadNbaPlayoffs')}}/" + $btn.data('pointer')
                    break;
            }
            $.get(url2)
            .done(function(data) {
                $btn.button('reset');
                $new_contents = '';
                $.each(data.matches, function() {
                    switch (this.league.type){
                        case 'dota2':
                            this.isDOTA = true;
                            break;
                        case 'csgo':
                            this.isCSGO = true;
                            break;
                        case 'LoL':
                            this.isLOL = true;
                            break;
                        default:
                            this.isSPORTS = true;
                            break;
                    }
                    $new_contents += Mustache.render(container, this);
                });


                if($btn.data('pointer') == 0){
                    $('.nomatches').attr('style','display: none !important');
                    $('#matchesHolder').append($new_contents);
                }else{
                    $('#matchesHolder').append($new_contents);
                }
                $btn.data('pointer', data.pointer);
            });
        });

        // Category buttons function
         $('.categorybtn').click(function(){
            var $btn = $(this);
            $btn.button('loading');
            var categ = $(this).attr('id');
            var currIndex = $('#matchesHolder .matchmain:last').index();
            var container = $("#matches").html();
            var container2 = $("#nomatch").html();
            var url = "";
            let recent_match_visibility = !$(".result-display").is(":hidden");

            if(!recent_match_visibility) {
                switch(categ){
                    case 'loadAll':
                        $("#loadAllMore").data('pointer',0);
                        url = "{{url('/match/loadAll')}}/" + $btn.data('pointer')
                        break;
                    case 'loadDota':
                        $("#loadDotaMore").data('pointer',0);
                        url = "{{url('/match/dota2')}}/" + $btn.data('pointer')
                        break;
                    case 'loadCsgo':
                        $("#loadCsgoMore").data('pointer',0);
                        url = "{{url('/match/csgo')}}/" + $btn.data('pointer')
                        break;
                    case 'loadSports':
                        $("#loadSportsMore").data('pointer',0);
                        url = "{{url('/match/sports')}}/" + $btn.data('pointer')
                        break;
                    case 'loadLol':
                        $("#loadLolMore").data('pointer',0);
                        url = "{{url('/match/lol')}}/" + $btn.data('pointer')
                        break;
                    case 'loadNbaPlayoffs':
                        $("#loadNbaPlayoffsMore").data('pointer',0);
                        url = "{{url('/match/nbaplayoffs')}}/" + $btn.data('pointer')
                        break;
                    break;
                }

                $.get(url)
                .done(function(data) {
                    $btn.button('reset');
                    $new_contents = '';

                    if(data.matches.length > 0) {
                        $("#show-more-container").removeClass("display-none");
                    } else {
                        $("#show-more-container").addClass("display-none");
                    }

                    $.each(data.matches, function() {
                        var match = this;

                        switch(this.status){
                            case 'open':
                                match.open = true;
                                break;
                            case 'ongoing':
                                match.ongoing = true;
                                break;
                        }
                        switch (this.league.type){
                            case 'dota2': 
                                match.isDOTA = true;
                                break;
                            case 'csgo': 
                                match.isCSGO = true;
                                break;  
                            case 'LoL': 
                                match.isLOL = true;
                                break;
                            default:
                                match.isSPORTS = true; 
                                break;                                                         
                        }
                        match.formatted_schedule = moment().format(this.schedule);
                        $new_contents += Mustache.render(container, this);
                        
                    });

                    if($btn.data('pointer') == 0){
                        $('#matchesHolder').html($new_contents);
                    }
                    else{
                        $('#matchesHolder').append($new_contents);
                    }

                    $btn.data('pointer', data.pointer);

                    $.each($('.match_countdown'), function(key, index) {
                        countdown(this, $(this).data('schedule'));
                    });
                    
                    if(data.matches == ''){
                        $("#matchesHolder").html(container2)
                    }
                });
            } else {
                $("body").find(".removable-recent-matches").remove();

                let type = 'all';

                switch(categ){
                    case 'loadAll':
                        type = 'all';
                        break;
                    case 'loadDota':
                        type = 'dota2';
                        break;
                    case 'loadCsgo':
                        type = 'csgo';
                        break;
                    case 'loadSports':
                        type = 'sports';
                        break;
                    case 'loadLol':
                        type = 'lol';
                        break;
                    case 'loadNbaPlayoffs':
                        type = 'nbaplayoffs';
                        break;
                    break;
                }

                $.ajax({
                    url: '/listrecentmatches',
                    type: 'GET',
                    data: { type: type },
                    success: function(data) {
                        $btn.button('reset');
                        //$('.recent-box').show();
                        let dataAppend = "";

                        if(data.length > 0) {
                            data.forEach((datum) => {
                                let cloned = $('#recent-matches-template').clone();
                                cloned.removeClass('display-none');

                                cloned.removeAttr("id");
                                cloned.addClass("removable-recent-matches");

                                cloned.find(".team-a-img").attr("src", datum.team_a.image);
                                cloned.find(".team-a-name").text(datum.team_a.name);
                                cloned.find(".team-a-win-count").text(datum.teama_score);

                                cloned.find(".team-b-img").attr("src", datum.team_b.image);
                                cloned.find(".team-b-name").text(datum.team_b.name);
                                cloned.find(".team-b-win-count").text(datum.teamb_score);

                                if(datum.status == 'settled' && datum.team_winner == datum.team_a.id) {
                                    cloned.find('.team-a-trophy').removeClass('display-none');
                                }

                                if(datum.status == 'settled' && datum.team_winner == datum.team_a.id) {
                                    cloned.find('.team-b-trophy').removeClass('display-none');
                                }

                                if(datum.status == 'cancelled') {
                                    cloned.find(".cancelled-match").removeClass('display-none');
                                }

                                if(datum.status == 'draw') {
                                    cloned.find(".draw-match-d").removeClass('display-none');
                                }

                                if(!!datum.team_c && datum.team_winner == datum.team_c.id) {
                                    cloned.find(".draw-match-d=c").removeClass('display-none');
                                }

                                if(datum.status == 'forfeit') {
                                    cloned.find(".forfeit-match").removeClass('display-none');
                                }

                                dataAppend += cloned;

                                $(".ul-recent-matches").append(cloned)
                            });
                        }

                    //console.log('ddd', data)
                    //self.recent_matches = data;
                    },
                    error: function(error) {
                        console.log('errr', error)
                    }
                });
            }
         });

        function checkPendingDeposit() {
            let deposit      = "{{ $pending_deposit ? $pending_deposit : '' }}";
            let remind_later = checkCookie();

            if(deposit && remind_later == '') {
                $('#pending-deposit').modal('show');
            }
        }

        $(document).on("click", "#btn-purchase-reminder", function() {
            var date = new Date();
            date.setTime(date.getTime() + ( 60 * 60 * 1000));
            var expires = "; expires="+date.toUTCString();

            document.cookie = "purchase_reminder" + "=" + "true" + ";" + expires + ";path=/";

            $('#pending-deposit').modal('hide');
        })

        function getCookie(cname) {
            var name = cname + "=";
            var ca = document.cookie.split(';');
            for(var i = 0; i < ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0) == ' ') {
                c = c.substring(1);
                }
                if (c.indexOf(name) == 0) {
                return c.substring(name.length, c.length);
                }
            }
            return "";
        }

        function checkCookie() {
            var reminder = getCookie("purchase_reminder");

            return reminder;
        }


        $("#btn-upcoming").click(function() {
            $(".upcoming-display").show();
            $(".result-display").hide();
        });

        $("#btn-result").click(function() {
            $(".result-display").show();
            $(".upcoming-display").hide();
            
            $('.categorybtn').click();
        });

    });
//    $(window).scroll(function() {
//        if($(window).scrollTop() == $(document).height() - $(window).height()) {
//            console.log('loading more items...');
//        }
//    });

</script>
@endsection
