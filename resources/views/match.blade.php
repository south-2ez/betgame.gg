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
    }
    .matchmain {
        float: left;
        width: 100%;
        padding: 15px;
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
    .matchleft {
        max-width: 555px;
        float: left;
        margin: 12px 1%;
        font-size: 17px;
        min-width: 380px;
        width: 68%;
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
        border-radius: 5px;
        padding: 5px;
        line-height: 1.4em;
        text-align: center;
        text-transform: none;
    }
    .teamtext i {
        font-style: normal;
    }
    
    .matchleft a {
        color: #333;
        text-decoration: none;
        cursor: pointer;
    }
    
    .format {
        font-weight: bold;
    }
    #tournament_bet {
        text-align: center;
        background-image: url({{asset('images/bg_03.jpg')}}); 
        position: relative; 
        background-position: center; 
        background-size: cover; 
        background-repeat: no-repeat; 
        min-height: 600px;
        padding: 10px;
    }
    #tournament_bet th {
        background-color: #717171;
        color: #ffffff;
        font-weight: normal;
        padding: 0;
    }
    #tournament_bet tbody tr td {
        vertical-align: middle;
    }
    #tournament_bet tbody tr td:first-child {
        padding: 0; 
        position: relative;
    }
    .favorite_team {
        position: absolute; 
        right: 2px; 
        bottom: 2px; 
        background-image: url('{{asset("images/team_logos/fav_star.png")}}');
        width: 20px; 
        height: 20px;
    }
    .top_up_amount {
        color: blue;
        font-style: italic;
    }
    .score{
        display: block;
        border: 1px solid rgba(255,255,255,0.6);
        background: linear-gradient(#eee, #fff);
        transition: all 0.3s ease-out;
        box-shadow: 
            inset 0 1px 4px rgba(0,0,0,0.4);
        padding: 5px;
        color: black;
        text-align: center; 
        font-family:customfont;
        margin: 0 auto;
    }

    .match-main-container{
        padding: 0px;
        margin-left: 40px;
        margin-top: 20px;
        margin-bottom: 10px;  
        background: none repeat scroll 0% 0% #DEDEDE;
    }

    .match-stream-container{
        padding: 0px;
        margin-left: 10px;
        margin-top: 20px;
        margin-bottom: 10px;  
        background: none repeat scroll 0% 0% #DEDEDE;
        min-height: 200px;
    }

    .match-stream-container .inner{
        padding: 10px;
    }

    .match-stream-container .stream-links{
        font-size: 16px;
    }

    .match-stream-container .stream-links > div {
        margin-bottom: 5px;
    }

    .match-stream-container .stream-links a {
        color: #414141;
    }

    .team-logo{
        max-width: 82px;
        /* max-height: 71px; */
    }

    .team-container{
        padding-left: 0; 
        padding-right: 0; 
        text-align: center;
        margin-top: 50px;
    }


    @media screen and (max-width: 1024px) {
        .match-main-container{
            width: 90%;
        }

        .match-stream-container{
            min-width: 90%;
        }
    }

    @media screen and (max-width: 768px) {
        .draw-match-page{
            margin-top:40px;
        }
    }
</style>


<link rel="stylesheet" href="{{ asset('bower_components/bootstrap-sweetalert/dist/sweetalert.css') }}">
<link rel="stylesheet" href="{{ asset('/bower_components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css') }}"/>
<link rel="stylesheet" href="{{ asset('css/datatables.min.css') }}">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.1/css/responsive.dataTables.min.css">
@endsection

@section('content')

<div class="main-container dark-grey {{ $hasMatchManagementAccess ? 'w-100' : '' }}">
        <div class="row">
            <div class="col-md-9 match-main-container">
                <div class="title">Bet match winner</div>
                <div class="clearfix"></div>
                <div class="matchmain">
                    <div class="infor">
                        <div class="time" style="font-size: 110%">
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
                        </div>
                        <div class="series">
                            @if($match->league->type == 'dota2')
                                <img src="/images/dota2icon.png" />
                            @elseif($match->league->type == 'csgo')
                                <img src="/images/csgoicon.png" />
                            @elseif($match->league->type == 'LoL')
                                <img src="/images/lol24px.png" />    
                            @else
                                <img src="/images/sportsicon.png" />                                                               
                            @endif                            
                            {{ $match->league->name }}
                        </div>
                    </div>
                    <div class="match" style="padding-top: 20px; padding-bottom: 20px; font-size: 120%">
                        <div class="col-sm-12 col-md-12" style="text-align: center; font-weight: bold; padding-bottom: 10px">
                            {{$match->label ? $match->label : ($match->type == 'main' ? 'Whole Match Winner' : '')}}
                        </div>
                        <div class="col-md-5 team-container">
                            <div class="col-md-9" style="padding-right: 0; text-align: center">
                                <div class="teamtext">
                                    @if($match->team_winner == $match->team_a)
                                    <div style="position: absolute; left: 0; top: 20px; color: green; font-weight: bold">Winner</div>
                                    @endif
                                    <b>{{$match->teamA->name}}</b><br>
                                    <i class="percent-coins">
                                        <span id="team_a_percentage_span">{{number_format($match_data['team_a_percentage'], 2)}}</span>%
                                        <br/>Payout Ratio (1:<span id="team_a_payout_ratio_span">{{number_format($match_data['team_a_ratio'], 2)}}</span>)</i>
                                    
                                    {{-- @if (!App::environment('prod')) 
                                        <div>
                                            <hr/>
                                            Initial Ratio: <strong style="color:red">{{ $match->team_a_initial_odd}}</strong>
                                        </div>
                                    @endif --}}

                                </div>
                                @if($match->type == 'sub' && $match->status == 'settled')
                                <b>Team Score: </b>{{$match->teama_score}}
                                @endif
                                @if($match->type == 'sub' && $match->status == 'ongoing')
                                <b>Team Score: </b>{{$match->teama_score}}
                                @endif
                                @if($match->type == 'sub' && $match->status == 'draw')
                                <b>Team Score: </b>{{$match->teama_score}}
                                @endif
                                @if($match->type == 'main' && $match->status == 'settled')
                                <b>Overall: </b>{{$match->teama_score}}
                                @endif
                                @if($match->type == 'main' && $match->status == 'ongoing')
                                <b>Overall: </b>{{$match->teama_score}}
                                @endif
                                @if($match->type == 'main' && $match->status == 'draw')
                                <b>Overall: </b>{{$match->teama_score}}
                                @endif
                            </div>
                            <div class="col-md-3" style="padding-left: 0; padding-right: 0; text-align: center">
                                <img src="{{asset($match->teamA->image)}}"  class="team-logo">
                                <br/>
                                @if(Auth::check() && ($match->status == 'open' && $match->schedule < Carbon\Carbon::now()  && $match_bet <= 0) && Auth::user()->id == 1066)
                                <button class="btn btn-danger btn-sm addBetBtn" style="margin-top: 10px" data-toggle="modal" data-target="#addBetModal"
                                        data-teamid="{{$match->teamA->id}}" data-teamname="{{$match->teamA->name}}" data-teamimage="{{$match->teamA->image}}"
                                        data-matchid="{{$match->id}}" data-teamratio="{{ number_format($match_data['team_a_ratio'], 2) }}">Bet on {{$match->teamA->shortname}}</button>
                                @elseif(Auth::check() && ($match->status == 'open' && $match->schedule > Carbon\Carbon::now() && $match_bet <= 0))
                                <button class="btn btn-danger btn-sm addBetBtn" style="margin-top: 10px" data-toggle="modal" data-target="#addBetModal"
                                        data-teamid="{{$match->teamA->id}}" data-teamname="{{$match->teamA->name}}" data-teamimage="{{$match->teamA->image}}"
                                        data-matchid="{{$match->id}}" data-teamratio="{{ number_format($match_data['team_a_ratio'], 2) }}">Bet on {{$match->teamA->shortname}}</button>
                                @else
                                    
                                    @if($match->status == 'open' && Auth::check() && $hasMatchManagementAccess && !isset($team_a_bet))
                                    <button class="btn btn-danger btn-sm addBetBtn" style="margin-top: 10px" data-toggle="modal" data-target="#addBetModal"
                                        data-teamid="{{$match->teamA->id}}" data-teamname="{{$match->teamA->name}}" data-teamimage="{{$match->teamA->image}}"
                                        data-matchid="{{$match->id}}" data-teamratio="{{ number_format($match_data['team_a_ratio'], 2) }}">Bet on {{$match->teamA->shortname}}</button>
                                    @endif
                                    @if(!Auth::check() && $match->status == 'open' && $match->schedule > Carbon\Carbon::now())
                                    <a class="btn btn-danger btn-sm" href="{{url('/login')}}" style="margin-top: 10px">Bet on {{$match->teamA->shortname}}</a>
                                    @endif
                                @endif
                            </div>
                        </div>
                        <div class="col-md-2 {{ empty($match->team_c) ?  'team-container' : 'draw-match-page'  }} " style="text-align: center; padding-left: 0; padding-right: 0;">
                            <span class="format">{{$match->best_of}}</span>
                            <br>vs  
                            @if( !empty($match->team_c) )
                                <div>
                                    <div class="teamtext">
                                        <b>{{$match->teamC->name}}</b><br>
                                        <i class="percent-coins">
                                            <span id="team_c_percentage_span">{{number_format($match_data['team_c_percentage'], 2)}}</span>%
                                            <br/>Payout Ratio (1:<span id="team_c_payout_ratio_span">{{number_format($match_data['team_c_ratio'], 2)}}</span>)</i>       
                                    </div>                         
                                    @if(Auth::check() && ($match->status == 'open' && $match->schedule < Carbon\Carbon::now() && $match_bet <= 0) && Auth::user()->id == 1066)
                                        <button class="btn btn-info btn-sm addBetBtn" style="margin-top: 10px" data-toggle="modal" data-target="#addBetModal" 
                                                data-teamid="{{$match->teamC->id}}" data-teamname="{{$match->teamC->name}}" data-teamimage="{{$match->teamC->image}}"
                                                data-matchid="{{$match->id}}" data-teamratio="{{ number_format($match_data['team_c_ratio'], 2) }}">Bet on {{$match->teamC->name}}</button>
                                    
                                    @elseif(Auth::check() && ($match->status == 'open' && $match->schedule > Carbon\Carbon::now() && $match_bet <= 0))
                                        <button class="btn btn-info btn-sm addBetBtn" style="margin-top: 10px" data-toggle="modal" data-target="#addBetModal" 
                                                data-teamid="{{$match->teamC->id}}" data-teamname="{{$match->teamC->name}}" data-teamimage="{{$match->teamC->image}}"
                                                data-matchid="{{$match->id}}" data-teamratio="{{ number_format($match_data['team_c_ratio'], 2) }}">Bet on {{$match->teamC->name}}</button>
                                    @else
                                        @if($match->status == 'open' && Auth::check() && $hasMatchManagementAccess && !isset($team_c_bet))
                                            <button class="btn btn-info btn-sm addBetBtn" style="margin-top: 10px" data-toggle="modal" data-target="#addBetModal" 
                                                data-teamid="{{$match->teamC->id}}" data-teamname="{{$match->teamC->name}}" data-teamimage="{{$match->teamC->image}}"
                                                data-matchid="{{$match->id}}" data-teamratio="{{ number_format($match_data['team_c_ratio'], 2) }}">Bet on {{$match->teamC->name}}</button>
                                        @endif
                                        @if(!Auth::check() && $match->status == 'open' && $match->schedule > Carbon\Carbon::now())
                                            <a class="btn btn-info btn-sm" href="{{url('/login')}}" style="margin-top: 10px">Bet on {{$match->teamC->name}}</a>
                                        @endif
                                    @endif
                                </div>
                                <div class="teamtext">
                                    @if($match->team_winner == $match->team_c)
                                        <br/>
                                        <div style="color: green; font-weight: bold">Winner: Draw</div>
                                    @endif

                                </div>
                                    {{-- @if($match->type == 'main' && $match->status == 'settled')
                                    <b>Overall: </b>{{$match->teamc_score}}
                                    @endif
                                    @if($match->type == 'main' && $match->status == 'ongoing')
                                    <b>Overall: </b>{{$match->teamc_score}}
                                    @endif
                                    @if($match->type == 'main' && $match->status == 'draw')
                                    <b>Overall: </b>{{$match->teamc_score}}
                                    @endif
                                    @if($match->type == 'sub' && $match->status == 'settled')
                                    <b>Team Score: </b>{{$match->teamc_score}}
                                    @endif
                                    @if($match->type == 'sub' && $match->status == 'ongoing')
                                    <b>Team Score: </b>{{$match->teamc_score}}
                                    @endif
                                    @if($match->type == 'sub' && $match->status == 'draw')
                                    <b>Team Score: </b>{{$match->teamc_score}}
                                    @endif                                 --}}
                            @endif


                        </div>
                        <div class="col-md-5 team-container">
                            <div class="col-md-3" style="padding-left: 0; padding-right: 0; text-align: center">
                                <img src="{{asset($match->teamB->image)}}" class="team-logo">
                                <br/>
                                @if(Auth::check() && ($match->status == 'open' && $match->schedule < Carbon\Carbon::now() && $match_bet <= 0) && Auth::user()->id == 1066)
                                <button class="btn btn-warning btn-sm addBetBtn" style="margin-top: 10px" data-toggle="modal" data-target="#addBetModal" 
                                        data-teamid="{{$match->teamB->id}}" data-teamname="{{$match->teamB->name}}" data-teamimage="{{$match->teamB->image}}"
                                        data-matchid="{{$match->id}}" data-teamratio="{{ number_format($match_data['team_b_ratio'], 2) }}">Bet on {{$match->teamB->shortname}}</button>
                                
                                @elseif(Auth::check() && ($match->status == 'open' && $match->schedule > Carbon\Carbon::now() && $match_bet <= 0))
                                <button class="btn btn-warning btn-sm addBetBtn" style="margin-top: 10px" data-toggle="modal" data-target="#addBetModal" 
                                        data-teamid="{{$match->teamB->id}}" data-teamname="{{$match->teamB->name}}" data-teamimage="{{$match->teamB->image}}"
                                        data-matchid="{{$match->id}}" data-teamratio="{{ number_format($match_data['team_b_ratio'], 2) }}">Bet on {{$match->teamB->shortname}}</button>
                                @else
                                    @if($match->status == 'open' && Auth::check() && $hasMatchManagementAccess && !isset($team_b_bet))
                                    <button class="btn btn-warning btn-sm addBetBtn" style="margin-top: 10px" data-toggle="modal" data-target="#addBetModal" 
                                        data-teamid="{{$match->teamB->id}}" data-teamname="{{$match->teamB->name}}" data-teamimage="{{$match->teamB->image}}"
                                        data-matchid="{{$match->id}}" data-teamratio="{{ number_format($match_data['team_b_ratio'], 2) }}">Bet on {{$match->teamB->shortname}}</button>
                                    @endif
                                    @if(!Auth::check() && $match->status == 'open' && $match->schedule > Carbon\Carbon::now())
                                    <a class="btn btn-warning btn-sm" href="{{url('/login')}}" style="margin-top: 10px">Bet on {{$match->teamB->shortname}}</a>
                                    @endif
                                @endif
                            </div>
                            <div class="col-md-9" style="padding-left: 0; text-align: center">
                                <div class="teamtext">
                                    @if($match->team_winner == $match->team_b)
                                    <div style="position: absolute; right: 0; top: 20px; color: green; font-weight: bold">Winner</div>
                                    @endif
                                    <b>{{$match->teamB->name}}</b><br>
                                    <i class="percent-coins">
                                        <span id="team_b_percentage_span">{{number_format($match_data['team_b_percentage'], 2)}}</span>%
                                        <br/>Payout Ratio (1:<span id="team_b_payout_ratio_span">{{number_format($match_data['team_b_ratio'], 2)}}</span>)</i>
                                    {{-- @if (!App::environment('prod')) 
                                        <div>
                                            <hr/>
                                            Initial Ratio: <strong style="color:red">{{ $match->team_b_initial_odd}}</strong>
                                        </div>
                                    @endif --}}

                                </div>
                                @if($match->type == 'main' && $match->status == 'settled')
                                <b>Overall: </b>{{$match->teamb_score}}
                                @endif
                                @if($match->type == 'main' && $match->status == 'ongoing')
                                <b>Overall: </b>{{$match->teamb_score}}
                                @endif
                                @if($match->type == 'main' && $match->status == 'draw')
                                <b>Overall: </b>{{$match->teamb_score}}
                                @endif
                                @if($match->type == 'sub' && $match->status == 'settled')
                                <b>Team Score: </b>{{$match->teamb_score}}
                                @endif
                                @if($match->type == 'sub' && $match->status == 'ongoing')
                                <b>Team Score: </b>{{$match->teamb_score}}
                                @endif
                                @if($match->type == 'sub' && $match->status == 'draw')
                                <b>Team Score: </b>{{$match->teamb_score}}
                                @endif
                            </div>
                        </div>
                        @if(Auth::check())
                            @if($hasMatchManagementAccess)
                      
                            <div class="col-md-12" style="width:100%;">
                                <div class="col-md-4" style="padding-top: 20px; text-align: center">
                                    @if(isset($team_a_bet))
                                        Credits placed: <strong style='color: green'>&#8369; {{number_format($team_a_bet->amount, 2, '.', ',')}}</strong><br/>
                                        Your pick: <strong style='color: green'>{{$team_a_bet->team->name}}</strong><br/>
                                        @if($match->status == 'settled')
                                            @if($match->teamwinner == $team_a_bet->team)
                                            Won: <strong style='color: green'>&#8369; {{number_format($match_data['potential_team_a_winning'], 2, '.', ',')}}</strong>
                                            @else
                                            Lost: <strong style='color: red'>&#8369; {{number_format($team_a_bet->amount, 2, '.', ',')}}</strong>
                                            @endif
                                        @else
                                        Possible winnings: 
                                        <strong style='color: green'>&#8369; {{number_format($match_data['potential_team_a_winning'], 2, '.', ',')}}</strong>
                                        @endif
                                        @if($match->status == 'open')
                                        <div class="row" style="padding-top: 10px">
                                            <button class="btn btn-warning btn-xs editAdminBet" data-betid="{{$team_a_bet->id}}" data-teamid="{{$team_a_bet->team->id}}"
                                                        data-teamname="{{$team_a_bet->team->name}}" data-teamimage="{{$team_a_bet->team->image}}"
                                                        data-toggle="modal" data-target="#editBetModal" data-betamount="{{$team_a_bet->amount}}"
                                                        data-teamratio="{{ number_format($match_data['team_a_ratio'], 2) }}"
                                                        data-potentialwinning="{{number_format($match_data['potential_team_a_winning'], 2, '.', ',')}}">Edit Bet</button>
                                            <button class="btn btn-danger btn-xs cancelBetBtn" data-betid="{{$team_a_bet->id}}">Cancel Bet</button>
                                        </div>
                                        @endif
                                    @endif
                                </div>

                                <div class="col-md-4" style="padding-top: 20px; text-align: center">
                                    @if(isset($team_c_bet))
                                        Credits placed: <strong style='color: green'>&#8369; {{number_format($team_c_bet->amount, 2, '.', ',')}}</strong><br/>
                                        Your pick: <strong style='color: green'>{{$team_c_bet->team->name}}</strong><br/>
                                        @if($match->status == 'settled')
                                            @if($match->teamwinner == $team_c_bet->team)
                                            Won: <strong style='color: green'>&#8369; {{number_format($match_data['potential_team_c_winning'], 2, '.', ',')}}</strong>
                                            @else
                                            Lost: <strong style='color: red'>&#8369; {{number_format($team_c_bet->amount, 2, '.', ',')}}</strong>
                                            @endif
                                        @else
                                        Possible winnings: 
                                        <strong style='color: green'>&#8369; {{number_format($match_data['potential_team_c_winning'], 2, '.', ',')}}</strong>
                                        @endif
                                        @if($match->status == 'open')
                                        <div class="row" style="padding-top: 10px">
                                            <button class="btn btn-warning btn-xs editAdminBet" data-betid="{{$team_c_bet->id}}" data-teamid="{{$team_c_bet->team->id}}"
                                                        data-teamname="{{$team_c_bet->team->name}}" data-teamimage="{{$team_c_bet->team->image}}"
                                                        data-toggle="modal" data-target="#editBetModal" data-betamount="{{$team_c_bet->amount}}"
                                                        data-teamratio="{{ number_format($match_data['team_c_ratio'], 2) }}"
                                                        data-potentialwinning="{{number_format($match_data['potential_team_c_winning'], 2, '.', ',')}}">Edit Bet</button>
                                            <button class="btn btn-danger btn-xs cancelBetBtn" data-betid="{{$team_c_bet->id}}">Cancel Bet</button>
                                        </div>
                                        @endif
                                    @endif
                                </div>

                                <div class="col-md-4" style="padding-top: 20px; text-align: center">
                                    @if(isset($team_b_bet))
                                        Credits placed: <strong style='color: green'>&#8369; {{number_format($team_b_bet->amount, 2, '.', ',')}}</strong><br/>
                                        Your pick: <strong style='color: green'>{{$team_b_bet->team->name}}</strong><br/>
                                        @if($match->status == 'settled')
                                            @if($match->teamwinner == $team_b_bet->team)
                                            Won: <strong style='color: green'>&#8369; {{number_format($match_data['potential_team_b_winning'], 2, '.', ',')}}</strong>
                                            @else
                                            Lost: <strong style='color: red'>&#8369; {{number_format($team_b_bet->amount, 2, '.', ',')}}</strong>
                                            @endif
                                        @else
                                        Possible winnings: 
                                        <strong style='color: green'>&#8369; {{number_format($match_data['potential_team_b_winning'], 2, '.', ',')}}</strong>
                                        @endif
                                        @if($match->status == 'open')
                                        <div class="row" style="padding-top: 10px">
                                            <button class="btn btn-warning btn-xs editAdminBet" data-betid="{{$team_b_bet->id}}" data-teamid="{{$team_b_bet->team->id}}"
                                                        data-teamname="{{$team_b_bet->team->name}}" data-teamimage="{{$team_b_bet->team->image}}"
                                                        data-toggle="modal" data-target="#editBetModal" data-betamount="{{$team_b_bet->amount}}"
                                                        data-teamratio="{{ number_format($match_data['team_b_ratio'], 2) }}"
                                                        data-potentialwinning="{{number_format($match_data['potential_team_b_winning'], 2, '.', ',')}}">Edit Bet</button>
                                            <button class="btn btn-danger btn-xs cancelBetBtn" data-betid="{{$team_b_bet->id}}">Cancel Bet</button>
                                        </div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                            @else
                                @if(isset($bet))
                                <div class="col-md-12" style="padding-top: 20px; text-align: center">
                                    <div class="col-md-4" style="text-align: right; padding-right: 0; padding-bottom: 10px">

                                    </div>
                                    <div class="col-md-4">
                                        Credits placed: <strong style='color: green'>&#8369; {{number_format($bet->amount, 2, '.', ',')}}</strong><br/>
                                        Your pick: <strong style='color: green'>{{$bet->team->name}}</strong><br/>
                                        @if($match->status == 'settled')
                                            @if($match->team_winner == $bet->team_id)
                                            Won: <strong style='color: green'>&#8369; {{number_format($potential_team_winning, 2, '.', ',')}}</strong>
                                            @else
                                            Lost: <strong style='color: red'>&#8369; {{number_format($bet->amount, 2, '.', ',')}}</strong>
                                            @endif
                                        @else
                                        Possible winnings: 
                                        <strong style='color: green'>&#8369; {{number_format($potential_team_winning, 2, '.', ',')}}</strong>
                                        @endif
                                        @if($match->status == 'open' && $match->schedule < Carbon\Carbon::now() && Auth::user()->id == 1066)
                                        <div class="row" style="padding-top: 10px">
                                            <button class="btn btn-primary btn-xs updateBetBtn" data-betamount="{{$bet->amount}}"
                                                    data-teamname="{{$bet->team->name}}" data-teamimage="{{$bet->team->image}}"
                                                    data-toggle="modal" data-target="#updateBetModal"
                                                    data-teamratio="{{ number_format($team_ratio, 2) }}">Add Bets</button>
                                            <button class="btn btn-danger btn-xs cancelBetBtn">Cancel Bet</button>
                                        </div>
                                        @elseif($match->status == 'open' && $match->schedule > Carbon\Carbon::now())
                                        <div class="row" style="padding-top: 10px">
                                            <button class="btn btn-primary btn-xs updateBetBtn" data-betamount="{{$bet->amount}}"
                                                    data-teamname="{{$bet->team->name}}" data-teamimage="{{$bet->team->image}}"
                                                    data-toggle="modal" data-target="#updateBetModal"
                                                    data-teamratio="{{ number_format($team_ratio, 2) }}">Add Bets</button>
                                            <button class="btn btn-danger btn-xs cancelBetBtn">Cancel Bet</button>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="col-md-4" style="text-align: left; padding-left: 0; padding-bottom: 10px">

                                    </div>
                                </div>
                                @endif
                            @endif
                        @endif
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="col-md-12" style="padding-bottom: 10px">
                    {{-- {{ dd($submatches) }} --}}
                    @foreach($submatches as $submatch)
                    <a href="{{url('/match') . '/' . $submatch->id}}" class="btn {{$submatch->id == $match->id ? 'btn-warning active' : 'btn-default'}} btn-sm" style="margin: 7px 5px">
                        {{$submatch->type == 'main' ? 'Match Winner' : $submatch->name}} 
                        @if($submatch->status == 'open')
                        <span class="label label-info">Open</span>
                        @elseif($submatch->status == 'ongoing')
                        <span class="label label-success">LIVE</span>
                        @else
                        <span class="label label-default">{{ucfirst($submatch->status)}}</span>
                        @endif
                    </a>
                    @endforeach
                </div>
            </div>

            <div class="col-md-2 match-stream-container">
                <div class="title">Watch</div>
                <div class="inner">
                    @if( empty($match->stream_twitch) && empty($match->stream_yt) && empty($match->stream_fb) && empty($match->stream_other) )
                        <h5>Sorry, no live streams provided.</h5>
                    @else 

                        <div class="stream-links">
                            @if(!empty($match->stream_twitch))
                                <div><a href="{{ $match->stream_twitch }}" target="_blank"><i class="fa fa-twitch" aria-hidden="true" style="color: #9147ff;"></i> Twitch</a></div>
                            @endif

                            @if(!empty($match->stream_yt))
                                <div><a href="{{ $match->stream_yt }}" target="_blank"><i class="fa fa-youtube-play" aria-hidden="true"  style="color: red;"></i> YouTube</a></div>
                            @endif

                            @if(!empty($match->stream_fb))
                                <div><a href="{{ $match->stream_fb }}" target="_blank"><i class="fa fa-facebook-square" aria-hidden="true" style="color: #4267b2"></i> Facebook</a></div>
                            @endif
                            
                            @if(!empty($match->stream_other))
                                <div><a href="{{ $match->stream_other }}" target="_blank"><i class="fa fa-television" aria-hidden="true"></i> Live Stream</a></div>
                            @endif    
                        </div>                    
                    @endif
                </div>

            </div>
        </div>

    
    @if($hasMatchManagementAccess)
        <div id="append_match_report_here">
            <admin-match-report :bets="{{ json_encode($bets) }}" :match="{{ json_encode($match_data) }}" :bnd-main-id="{{ env('BND_MAIN_USER_ID',1066) }}"/>
        </div>
    @endif;

    @if(in_array($match->status, ['ongoing','open']) && Auth::check() && $hasMatchManagementAccess)
    <div class="m-container2" style="width: 98%; margin-bottom: 30px;">
        <div class="main-ct" style="margin-bottom: 0">
            <div class="title">Match Manager Options</div>
            <div class="clearfix"></div>
            <div class="row">
                <div class="col-md-6 text-center"> <!-- bets related stuff -->
                    <div class="matchmain">

                        @if($match->status != 'ongoing')
                                <!--  MatchBndAutoBetSettings -->

                                <div class="col-md-3" style="padding-left: 0; padding-right: 0; text-align: center">
                                    <button class="btn btn-info btn-sm" style="margin-top: 10px" data-toggle="modal" data-target="#changeAdminBetsModal">Change Admin Bets</button>
                                </div>        

                                <div class="col-md-3" style="padding-left: 0; padding-right: 0; text-align: center">
                                    <button class="btn btn-danger btn-sm" style="margin-top: 10px" id="remove-admin-bets">Remove Admin Bets</button>
                                </div>    

                                @if(in_array(Auth()->id(), getSuperAdminIds()))

                                    <div class="col-md-3" style="padding-left: 0; padding-right: 0; text-align: center">
                                        <button class="btn btn-danger btn-sm lockMatchBettingBtn" style="margin-top: 10px">LOCK BETTING [1 MIN]</button>
                                    </div>  

                                    <div class="col-md-3" style="padding-left: 0; padding-right: 0; text-align: center">
                                        <button class="btn btn-warning btn-sm unlockMatchBettingBtn" style="margin-top: 10px">UNLOCK BETTING</button>
                                    </div>  
                                @endif

                        @endif

                        @if($match->type == 'main' || $match->sub_type == 'main')
                            <div class="col-md-3" style="padding-left: 0; padding-right: 0; text-align: center; margin-top: 10px;">
                                <match-bnd-auto-bet-settings match-id="{{ $match->id }}"  match-status="{{ $match->status }}"/>
                            </div>
                        @endif
                 


 
                    </div>
                </div> <!-- end bets related stuff -->

                <div class="col-md-6">
                    <div class="matchmain">
                        @if($match->status == 'ongoing') <!-- start ongoing match status buttons -->
                            <div class="col-md-3" style="padding-left: 0; padding-right: 0; text-align: center">
                                <img src="{{asset($match->teamA->image)}}" class="team-logo" /><br/>
                                <button class="btn btn-danger btn-sm declareWinnerBtn" style="margin-top: 10px"
                                        data-teamid="{{$match->teamA->id}}" data-teamname="{{$match->teamA->name}}"
                                        data-teamimage="{{$match->teamA->image}}">{{$match->teamA->name}} as Winner</button>
                            </div>

                            @if(!empty($match->team_c))
                                <div class="col-md-3" style="padding-left: 0; padding-right: 0; text-align: center">
                                    <img src="{{asset($match->teamC->image)}}" class="team-logo" /><br/>
                                    <button class="btn btn-danger btn-sm declareWinnerBtn" style="margin-top: 10px"
                                            data-teamid="{{$match->teamC->id}}" data-teamname="{{$match->teamC->name}}"
                                            data-teamimage="{{$match->teamC->image}}">{{$match->teamC->name}} as Winner</button>
                                </div>  
                            @endif                          
                            <div class="col-md-3" style="padding-left: 0; padding-right: 0; text-align: center">
                                <img src="{{asset($match->teamB->image)}}" class="team-logo" /><br/>
                                <button class="btn btn-warning btn-sm declareWinnerBtn" style="margin-top: 10px"
                                        data-teamid="{{$match->teamB->id}}" data-teamname="{{$match->teamB->name}}"
                                        data-teamimage="{{$match->teamB->image}}">{{$match->teamB->name}} as Winner</button>
                            </div>
                            <div class="col-md-3" style="padding-left: 0; padding-right: 0; text-align: center">
                                {{-- <button class="btn btn-info btn-sm declareWinnerBtn" style="margin-top: 10px" 
                                        data-teamid="draw" data-teamname="draw" data-option="draw" data-teamimage="">Set Match as Draw [RETURN CREDITS]</button> --}}
                                <div class="col-md-3" style="padding-left: 0; padding-right: 0; text-align: center">
                                    <button class="btn btn-danger btn-sm cancelMatchBtn" style="margin-top: 10px"  data-teamid="cancel" data-teamname="cancel" data-option="cancel">Cancel this Match</button>
                                </div>

                            </div>
                                @if($match->type == 'main' || ($match->type == 'sub' && $match->sub_type == 'main' && $match->game_grp > 1))
                                <div class="col-md-3" style="padding-left: 0; padding-right: 0; text-align: center">
                                    <button id="openBackMatchBtn" class="btn btn-success btn-sm" style="margin-top: 10px">Set to Open</button>
                                </div>
                                @endif
                                <div class="col-md-3" style="padding-left: 0; padding-right: 0; text-align: center">
                                <button class="btn btn-success btn-sm" style="margin-top: 10px" data-toggle="modal" data-target="#editScoreModal">Edit Scoreboard</button>
                            </div>
                        @else <!-- else -->
             
                            @if($match->type == 'main' || ($match->type == 'sub' && $match->sub_type == 'main' && $match->game_grp > 1))
                                <div class="col-md-3" style="padding-left: 0; padding-right: 0; text-align: center">
                                    <button class="btn btn-info btn-sm" style="margin-top: 10px" data-toggle="modal" data-target="#extendMatchModal">Extend Match Sched</button>
                                </div>
                            @endif
                            <div class="col-md-3" style="padding-left: 0; padding-right: 0; text-align: center">
                                <button class="btn btn-warning btn-sm" style="margin-top: 10px" data-toggle="modal" data-target="#editMatchModal">Edit Match</button>
                            </div>
                            <div class="col-md-3" style="padding-left: 0; padding-right: 0; text-align: center">
                                <button class="btn btn-danger btn-sm cancelMatchBtn" style="margin-top: 10px"  data-teamid="cancel" data-teamname="cancel" data-option="cancel">Cancel this Match</button>
                            </div>
                        @endif


                        @if($match->type == 'main')
                            <div class="col-md-3" style="padding-left: 0; padding-right: 0; text-align: center">
                                <button class="btn btn-warning btn-sm" style="margin-top: 10px" data-toggle="modal" data-target="#editSteamLinks">Update Steam Links</button>
                            </div>
                        @endif

                    </div>

                </div>
            </div>

        </div>
    </div>
    @endif

    @if(in_array($match->status, ['settled']) && Auth::check() && $hasMatchManagementAccess)
    <div class="m-container2 pull-right">
        <div class="main-ct" style="margin-bottom: 0">
            <div class="title">Match Manager Options</div>
            <div class="clearfix"></div>
            <div class="matchmain">
                @if($match->status == 'settled')

                @if($match->type == 'main' || $match->sub_type == 'main')
                    <div class="col-md-3" style="padding-left: 0; padding-right: 0; text-align: center; margin-top: 10px;">
                        <match-bnd-auto-bet-settings match-id="{{ $match->id }}" match-status="{{ $match->status }}"/>
                    </div>
                @endif

                <div class="col-md-3" style="padding-left: 0; padding-right: 0; text-align: center">
                    <button class="btn btn-success btn-sm" style="margin-top: 10px" data-toggle="modal" data-target="#editSettledModal">Edit Scoreboard</button>
                    @if(in_array(Auth()->id(), getSuperAdminIds()))
                        <button class="btn btn-warning btn-sm revertMatchToLiveBtn" style="margin-top: 10px">Revert to LIVE</button>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif

    @if(in_array($match->status, ['draw', 'cancelled']) && Auth::check() && $hasMatchManagementAccess)
    <div class="m-container2 pull-right">
        <div class="main-ct" style="margin-bottom: 0">
            <div class="title">Match Manager Options</div>
            <div class="clearfix"></div>
            <div class="matchmain">
                @if($match->status == 'draw')
                <div class="col-md-3" style="padding-left: 0; padding-right: 0; text-align: center">
                    <button class="btn btn-success btn-sm" style="margin-top: 10px" data-toggle="modal" data-target="#editSettledModal">Edit Scoreboard</button>
                    @if(in_array(Auth()->id(), getSuperAdminIds()))
                        <button class="btn btn-warning btn-sm revertMatchToLiveBtn" style="margin-top: 10px">Revert to LIVE</button>
                    @endif
                </div>
                @endif
            
                <button id="addWhyLinkDrawCancelled" class="btn btn-info btn-sm" style="margin-top: 10px" >Update Why/More Info Link</button>
            </div>
        </div>
    </div>
    @endif

</div>

<div id="addBetModal" class="modal fade" role="dialog">
    <div class="modal-dialog" style="max-width: 400px">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Betting for <span class="team_name"></span></h4>
            </div>
            <div class="modal-body" style="padding-bottom: 5px;">
                <form id="bettingForm">
                    <input type="hidden" name="betid" value="" />
                    <input type="hidden" name="teamid" />
                    <div class="row">
                        <div class="pull-left col-md-4" style="padding-bottom: 10px">
                            <img class="team_image" src="" style="width: 100px"/>
                        </div>
                        <div class="col-md-8">
                            Name: <strong class="team_name"></strong><br/>
                            Ratio: <strong class="team_ratio"></strong><br/>
                            Possible winnings: <strong class="winning_amount">0.00</strong><br/>
                            (<strong>Note</strong>: Bets cannot be cancelled under 5 mins before the match schedule.)
                            <input type="text" name="bet_amount" placeholder="Place your bet here" class="form-control input-xs" 
                               style="margin-top: 10px; margin-bottom: 3px; max-width: 220px" />
                            <span class="betting_error_field error_field" style="color: red; display: none"></span>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <div class="buttons">
                    <button class="btn btn-primary btn-sm confirmBetBtn" data-loading-text="Loading ... <span class='glyphicon glyphicon-refresh fa-spin'></span>">
                        Bet Now
                    </button>
                    <a class="btn btn-default btn-sm" data-dismiss="modal">
                        Cancel
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="updateBetModal" class="modal fade" role="dialog">
    <div class="modal-dialog" style="max-width: 400px">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add/Increase Bets for <span class="team_name"></span></h4>
            </div>
            <div class="modal-body" style="padding-bottom: 5px;">
                <form id="updatebettingForm">
                    <input type="hidden" name="teamid" />
                    <input type="hidden" name="match_id" value="{{$match->id}}" />
                    <input type="hidden" name="matchid" value="{{$match->id}}" />
                    <div class="row">
                        <div class="pull-left col-md-4" style="padding-bottom: 10px">
                            <img class="team_image" src="" style="width: 100px"/>
                        </div>
                        <div class="col-md-8">
                            Name: <strong class="team_name"></strong><br/>
                            Ratio: <strong class="team_ratio"></strong><br/>
                            Current bet: <strong class="bet_amount"></strong> <strong class="top_up_amount"></strong><br/>
                            Possible winnings: <strong class="winning_amount">{{isset($bet) ? number_format($potential_team_winning, 2, '.', ',') : 0.00}}</strong><br/>
                            (<strong>Note</strong>: Bets cannot be cancelled under 5 mins before the match schedule.)<br/>
                            <input type="text" name="bet_amount" placeholder="Place your bet here" class="form-control input-xs" 
                                style="margin-top: 10px; margin-bottom: 3px; max-width: 220px" />
                            <span class="addMoreBetBtn_error_field error_field" style="color: red; display: none"></span>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <div class="buttons">
                    <button class="btn btn-primary btn-sm addMoreBetBtn" data-loading-text="Loading ... <span class='glyphicon glyphicon-refresh fa-spin'></span>">
                        Add Bets
                    </button>
                    <a class="btn btn-default btn-sm" data-dismiss="modal">
                        Cancel
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@if(Auth::check() && $hasMatchManagementAccess)

<div id="extendMatchModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Extend Match Schedule</h4>
            </div>
            <div class="modal-body" >
                <form id="extendMatchForm">
                    <input type="hidden" name="match_id" value="{{$match->id}}" />
                    <div class="row">
                        <div class="form-group col-xs-12">
                            <label>Match:</label>
                            <div class="match_name">{{$match->name}}</div>
                        </div>
                        <div class="form-group col-xs-6">
                            <label>Extension time:</label>
                            <select class="form-control" name="ext_time">
                                <option value="3">3 minutes</option>
                                <option value="5">5 minutes</option>
                                <option value="10">10 minutes</option>
                                <option value="15">15 minutes</option>
                                <option value="30">30 minutes</option>
                                <option value="60">1 hour</option>
                            </select>
                            <span class="error-label"></span>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button id="extendMatchTimeBtn" type="button" class="btn btn-warning" data-loading-text="Loading ... <span class='glyphicon glyphicon-refresh fa-spin'></span>">Extend</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Score Modal -->
<div id="editScoreModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">{{$match->name}}</h4>
            </div>
            <div class="modal-body">
                <form id="matchesForm2" class="form-horizontal" autocomplete="off">
                    <input type="hidden" name='match_id' value='{{$match->id}}' />
                            <div class="form-group">
                                <div class="row">
                                <div class="col-md-5" style="text-align: center;">
                                    <div class="row">
                                        <img src="{{asset($match->teamA->image)}}" style="width: 80px; border-radius: 2px;">
                                    </div>
                                    <div class="row">
                                        <label class="control-label">{{$match->teamA->name}}</label>
                                    </div>
                                    <div class="row">
                                        <input type ="text" name='teama_score' class='form-control score' value="{{$match->teama_score}}" style="width: 45%;"/>
                                        <span class="error-label"></span>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                   <div class="row"  style="text-align: center;">
                                    <label style="font-size: 25px;">VS</label>
                                   </div>
                                </div>
                                 <div class="col-md-5" style="text-align: center;">
                                        <div class="row">
                                            <img src="{{asset($match->teamB->image)}}" style="width: 80px; border-radius: 2px;">
                                        </div>
                                        <div class="row">
                                            <label class="control-label">{{$match->teamB->name}}</label>
                                        </div>
                                        <div class="row">
                                        <input type ="text" name='teamb_score' class='form-control score' value="{{$match->teamb_score}}" style="width: 45%;"/>
                                        <span class="error-label"></span>
                                        </div>                       
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button id="editScoreBtn" type="button" class="btn btn-success" data-edit-text="Update">Update</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                        </div>
                    </form>
            </div>
        </div>
    </div>
</div>
<!-- Edit Settled Modal -->
<div id="editSettledModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">{{$match->name}}</h4>
            </div>
            <div class="modal-body">
                <form id="matchesForm5" class="form-horizontal" autocomplete="off">
                    <input type="hidden" name='match_id' value='{{$match->id}}' />
                            <div class="form-group">
                                <div class="row">
                                <div class="col-md-5" style="text-align: center;">
                                    <div class="row">
                                        <img src="{{asset($match->teamA->image)}}" style="width: 80px; border-radius: 2px;">
                                    </div>
                                    <div class="row">
                                        <label class="control-label">{{$match->teamA->name}}</label>
                                    </div>
                                    <div class="row">
                                        <input type ="text" name='teama_score' class='form-control score' value="{{$match->teama_score}}" style="width: 45%;"/>
                                        <span class="error-label"></span>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                   <div class="row"  style="text-align: center;">
                                    <label style="font-size: 25px;">VS</label>
                                   </div>
                                </div>
                                 <div class="col-md-5" style="text-align: center;">
                                        <div class="row">
                                            <img src="{{asset($match->teamB->image)}}" style="width: 80px; border-radius: 2px;">
                                        </div>
                                        <div class="row">
                                            <label class="control-label">{{$match->teamB->name}}</label>
                                        </div>
                                        <div class="row">
                                        <input type ="text" name='teamb_score' class='form-control score' value="{{$match->teamb_score}}" style="width: 45%;"/>
                                        <span class="error-label"></span>
                                        </div>                       
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button id="editSettledBtn" type="button" class="btn btn-success" data-edit-text="Update">Update</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                        </div>
                    </form>
            </div>
        </div>
    </div>
</div>
<!-- Edit Match Modal -->
<div id="editMatchModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">{{$match->name}}</h4>
            </div>
            <div class="modal-body" >
                <form id="matchesForm" class="form-horizontal" autocomplete="off">
                    <input type="hidden" name='match_id' value='{{$match->id}}' />
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Name: </label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="name" value='{{$match->name}}' placeholder="Name this match (optional)"/>
                            <span class="error-label"></span>
                        </div>
                    </div>
                    @if($match->type == 'main' || ($match->type == 'sub' && $match->sub_type == 'main' && $match->game_grp > 1))
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Schedule: </label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control datetime_sched" name="schedule" value='{{$match->schedule->format("m/d/Y h:i A")}}' placeholder="Select schedule of Match" required/>
                            <span class="error-label"></span>
                        </div>
                    </div>
                    @endif
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Label: </label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="label" value="{{$match->label}}" placeholder="Add match note or label"/>
                            <span class="error-label"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Fee %: </label>
                        <div class="col-sm-9">
                            <select name='fee' class='form-control' style="width: 130px">
                                @for($x = 0; $x < 50; $x++)
                                <option value="{{number_format($x, 1)}}" {{$match->fee*100 == number_format($x, 1) ? 'selected' : ''}}>{{$x}}%</option>

                                @if($x == 0.0)
                                <option value="0.5" {{$match->fee*100 == 0.5 ? 'selected' : ''}}>0.5%</option>
                                @endif

                                @if($x == 1.0)
                                <option value="1.5" {{$match->fee*100 == 1.5 ? 'selected' : ''}}>1.5%</option>
                                @endif

                                @if($x == 2.0)
                                <option value="2.5" {{$match->fee*100 == 2.5 ? 'selected' : ''}}>2.5%</option>
                                @endif
                                @endfor
                            </select>
                            <span class="error-label"></span>
                        </div>
                    </div>
                    @if($match->type == 'main' || ($match->type == 'sub' && $match->sub_type == 'main' && $match->game_grp > 1))
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Status: </label>
                        <div class="col-sm-9">
                            <select name='status' class='form-control' style="width: 130px">
                                <option value='open' {{$match->status == 'open' ? 'selected' : ''}}>open</option>
                                <option value='ongoing' {{$match->status == 'ongoing' ? 'selected' : ''}}>ongoing</option>
                            </select>
                            <span class="error-label"></span>
                        </div>
                    </div>
                    @endif


                    {{-- @if($match->is_initial_odds_enabled) --}}
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Enable Initial Odds: </label>
                            <div class="col-sm-9" style="text-align:center">
                                <select id="is_initial_odds_enabled" name='is_initial_odds_enabled' class='form-control' style="width: 130px">
                                    <option value="1" {{ $match->is_initial_odds_enabled == 1 ? 'selected':''}}>Enable</option>
                                    <option value="0" {{ $match->is_initial_odds_enabled == 0 ? 'selected':''}}>Disable</option>
                                </select>
                            </div>
                        </div> 

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Change Initial Odds: </label>
                            <div class="col-sm-9" style="text-align:center; padding-left:0;">
                                <div class='col-sm-8' style="padding-left: 0; margin-left: -10px;">
                                    <input type="checkbox" id="checkboxChangeInitialOdds" name="initial_odds_change_allowed"/> <small>(Require's Operations Manager's Password)</small>
                                </div>
                            </div>
                        </div>  

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Team Initial Odds: </label>
                            <div class="col-sm-9" style="text-align:center">
                                <div class='col-sm-4' style="padding-left: 0">
                                <input type="number" step="1" min="0" max="100" value="{{$match->team_a_initial_odd}}" {{ $match->is_initial_odds_enabled == 0 ? 'disabled':''}} class="form-control" name="team_a_initial_odd" placeholder="For Team A" disabled/>
                                    <span class="error-label"></span>
                                </div>
                                <div class='col-sm-2'><strong>VS</strong></div>
                                <div class='col-sm-4'>
                                    <input type="number" step="1" min="0" max="100" value="{{$match->team_b_initial_odd}}" {{ $match->is_initial_odds_enabled == 0 ? 'disabled':''}}  class="form-control" name="team_b_initial_odd" placeholder="For Team B" style="width: 130px;" disabled/>
                                    <span class="error-label"></span>
                                </div>
                            </div>
                        </div>  
                    {{-- @endif --}}

                    <div class="modal-footer">
                        <button id="editMatchBtn" type="button" class="btn btn-warning" data-edit-text="Update">Update</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
            
        </div>
    </div>
</div>

<div id="editSteamLinks" class="modal fade" role="dialog">
    <div class="modal-dialog" style="max-width: 400px">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Update Steam Links for Match #{{ $match->id}}<span class="team_name"></span></h4>
            </div>
            <div class="modal-body" style="padding-bottom: 5px;">
                <div class="row">
                    <div class="form-group col-xs-12">
                        <label>
                            Twitch:
                        </label>
                        <input type="text" id="twitch-stream-link" class="form-control" placeholder="Twitch Steam Link" value="{{ $match->stream_twitch }}">
                        <span class="error-label"></span>
                    </div>    
                    <div class="form-group col-xs-12">
                        <label>
                            YouTube:
                        </label>
                        <input type="text" id="youtube-stream-link" class="form-control" placeholder="YouTube Stream Link" value="{{ $match->stream_yt }}">
                        <span class="error-label"></span>
                    </div>    
                    <div class="form-group col-xs-12">
                        <label>
                            Facebook:
                        </label>
                        <input type="text" id="facebook-stream-link" class="form-control" placeholder="Facebook Stream Link" value="{{ $match->stream_fb }}">
                        <span class="error-label"></span>
                    </div>    
                    <div class="form-group col-xs-12">
                        <label>
                            Other:
                        </label>
                        <input type="text" id="other-stream-link" class="form-control" placeholder="Other Livestream Link" value="{{ $match->stream_other }}">
                        <span class="error-label"></span>
                    </div>     
                </div>             
            </div>
            <div class="modal-footer">
                <div class="buttons">
                    <button class="btn btn-primary btn-sm updateMatchStreamLinks" data-loading-text="Loading ... <span class='glyphicon glyphicon-refresh fa-spin'></span>">
                        Save Steam Links
                    </button>
                    <a class="btn btn-default btn-sm" data-dismiss="modal">
                        Cancel
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="editBetModal" class="modal fade" role="dialog">
    <div class="modal-dialog" style="max-width: 400px">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Update Bets for <span class="team_name"></span></h4>
            </div>
            <div class="modal-body" style="padding-bottom: 5px;">
                <form id="editbettingForm">
                    <input type="hidden" name="betid" />
                    <input type="hidden" name="teamid" />
                    <div class="row">
                        <div class="pull-left col-md-4" style="padding-bottom: 10px">
                            <img class="team_image" src="" style="width: 100px"/>
                        </div>
                        <div class="col-md-8">
                            Name: <strong class="team_name"></strong><br/>
                            Ratio: <strong class="team_ratio"></strong><br/>
                            Possible winnings: <strong class="winning_amount"></strong><br/>
                            (<strong>Note</strong>: Bets cannot be cancelled under 5 mins before the match schedule.)
                            <input type="text" name="bet_amount" placeholder="Place your bet here" class="form-control input-xs" 
                                    style="margin-top: 10px; margin-bottom: 3px; max-width: 220px" />
                            <span class="error_field" style="color: red; display: none"></span>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <div class="buttons">
                    <button class="btn btn-primary btn-sm editBetBtn" data-loading-text="Loading ... <span class='glyphicon glyphicon-refresh fa-spin'></span>">
                        Update Bets
                    </button>
                    <a class="btn btn-default btn-sm" data-dismiss="modal">
                        Cancel
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- change admin bets modal -->
<div id="changeAdminBetsModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Change/Update Admin Bets</h4>
            </div>
            <div class="modal-body" >
                <form id="extendMatchForm">
                    <input type="hidden" name="match_id" value="{{$match->id}}" />
                    <div class="row">
                        <div class="form-group col-md-12 text-center">
                            <h3>{{$match->name}}</h3>
                        </div>
                        <div class="col-md-12">
                            <form class="form-inline">
                                <div class="form-group">
                                    <label for="exampleInputName2">Total Admin Bets to work on: </label>
                                    <input type="number" min="100" class="form-control" id="admin-bet-input-percentage-base-amount" placeholder="e.g: 300,000" value="300000">
                                    <span id="helpBlock" class="help-block"><em>*the bet amount value based on the percentage will be taken from here. <br/> e.g: 300,000 - if you set Team A to have 70% of 300,000, <br/> 210,000 wll be placed on Team A and remaining amount will be placed on Team B.</em></span>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputName2">{{ $match->teamA->name }} Percentage: </label>
                                    <input type="number" min="6" max="94" class="form-control admin-bet-input-percentage" id="admin-bet-input-percentage-a" placeholder="Input percentage" value="{{ number_format($match_data['team_a_percentage'], 2)  }}" readonly>
                                    <span id="helpBlock" class="help-block"><em>*Double click input to edit percentage for <strong>{{ $match->teamA->name }}</strong>.</em></span>
                                </div>
                            
                                <div class="form-group" style="{{ !empty($match->team_c) ? '' : 'display:none;' }}">
                                    <label for="exampleInputName2">{{ !empty($match->team_c) ? $match->teamC->name : '' }} Percentage: </label>
                                    <input type="number" min="6" max="94" class="form-control admin-bet-input-percentage" id="admin-bet-input-percentage-c" placeholder="Input percentage" value="{{ number_format($match_data['team_c_percentage'], 2)  }}" readonly>
                                    <span id="helpBlock" class="help-block"><em>*Double click input to edit percentage for <strong>{{ !empty($match->team_c) ? $match->teamC->name : ''}}</strong>.</em></span>
                                </div>    
                                                    
                                <div class="form-group">
                                    <label for="exampleInputEmail2">{{ $match->teamB->name }} Percentage: </label>
                                    <input type="number"  min="6" max="94"  class="form-control admin-bet-input-percentage" id="admin-bet-input-percentage-b"  placeholder="Input percentage" value="{{ number_format($match_data['team_b_percentage'], 2)  }}" readonly>
                                    <span id="helpBlock" class="help-block"><em>*Double click input to edit percentage <strong>{{ $match->teamB->name }}</strong>.</em></span>
                                </div>
                                <input type="hidden" id="current-admin-bet-input-percentage-a" value="{{ number_format($match_data['team_a_percentage'], 2)  }}" />
                                <input type="hidden" id="current-admin-bet-input-percentage-b" value="{{ number_format($match_data['team_b_percentage'], 2)  }}" />
                                <input type="hidden" id="current-admin-bet-input-percentage-c" value="{{ number_format($match_data['team_c_percentage'], 2)  }}" />
                            </form>
                        </div>

                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button id="saveAdminBetsPercentagesButton" type="button" class="btn btn-warning" data-loading-text="Loading ... <span class='glyphicon glyphicon-refresh fa-spin'></span>">Save Percentages</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
<!-- end change admin bets modal -->

@endif


<div id="draw-notification-modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title text-bold" id="exampleModalLabel"> 
                    <strong>DRAW Betting Update [Important]</strong>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </h2>
            </div>
            <div class="modal-body">
                <img src="/images/DRAW-BET-SAMPLE.png" class="w-100"/>
                <h3>Hello bettors!</h3>
                <h4>
                    <p>
                        We have added the "Bet on Draw" option for best-of-two (BO2) matches. You can now choose from either of the teams or choose the DRAW option in the Match Winner tab. If the score for the BO2 series is 1-1; the ones who placed their bets on both teams lose, 
                        and the ones who placed their bets on "DRAW" wins. 
                        <br/>
                        <br/>
                        Please be guided accordingly. Thanks and happy betting!
                        <br/>
                    </p>
                </h4>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="btn-purchase-reminder" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script type="text/javascript" src="{{ asset('bower_components/bootstrap-sweetalert/dist/sweetalert.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.6/moment.min.js"></script>
<script type="text/javascript" src="{{ asset('/bower_components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        @if(!empty($match->team_c))
            showDrawNotificationModal();
        @endif

        $('.datetime_sched').datetimepicker({
            viewMode: 'days',
            minDate : new Date()
        }).on('dp.change', function(e){ $(this).parent().removeClass('has-error'); });
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
        
        @if(Auth::check() && $hasMatchManagementAccess && 1 == 2)
            $.ajax({
                url:'{{route("report_match")}}',
                type:'GET',
                data: {match_id: {{$match->id}}},
                success:function(data){
                    
                    
                    // $('.m-container3').after(response.div);
                    const returnData = JSON.parse(data);
            
                    if(data != 'no data'){
                        $('#append_match_report_here').html(returnData.div);
                        $('#team_a_payout_ratio_span').text(returnData.team_a_ratio.toFixed(2));
                        $('#team_b_payout_ratio_span').text(returnData.team_b_ratio.toFixed(2));
                        $('#team_a_percentage_span').text(returnData.team_a_percentage.toFixed(2));
                        $('#team_b_percentage_span').text(returnData.team_b_percentage.toFixed(2));
                    }
                
                }
                }); 

            // let nextCall = 30 * 1000;
            
            // setTimeout(() => {
            //     updateAdminReport();
            //     setInterval(() => {
            //         updateAdminReport();
            //     }, nextCall);
            // },1000);


            // let updateAdminReport =  () => {

            //     $.ajax({
            //         url:'{{route("report_match")}}',
            //         type:'GET',
            //         data: {match_id: {{$match->id}}},
            //         success:function(data){
                        
                        
            //             // $('.m-container3').after(response.div);
            //             const returnData = JSON.parse(data);
                
            //             if(data != 'no data'){
            //                 $('#append_match_report_here').html(returnData.div);
            //                 $('#team_a_payout_ratio_span').text(returnData.team_a_ratio.toFixed(2));
            //                 $('#team_b_payout_ratio_span').text(returnData.team_b_ratio.toFixed(2));
            //                 $('#team_a_percentage_span').text(returnData.team_a_percentage.toFixed(2));
            //                 $('#team_b_percentage_span').text(returnData.team_b_percentage.toFixed(2));
            //             }
                    
            //         }
            //     }); 
            // }

                
        @endif

        $(":input[name=bet_amount]").currencyFormat();
        
        $('#bettingForm').on('submit', function() {
            return false;
        });

        var xhrFetchRequest = null;
        var isFetchingAmount = false;
        var currAmount = 0;
        
        $(":input[name=bet_amount]").keyup(function(event) {
            var $modal = $(this).closest('.modal');
            @if(isset($bet))
            var curr_ratio = {{$team_ratio}};
            var winning_amount = {{$potential_team_winning}};
            @else
            var curr_ratio = $(this).closest('.modal').data('team_ratio');
                @if(Auth::check() && $hasMatchManagementAccess)
                    var winning_amount = $modal.find(':input[name=winning_amount]').val();
                @else
                    var winning_amount = 0.00;
                @endif
            @endif
            var amount = $(this).val();
            var match_id = {{$match->id}};
            var team_id = $modal.find(':input[name=teamid]').val();

            var useWinningAmount =  parseFloat(amount) * curr_ratio;
            useWinningAmount = $.isNumeric(useWinningAmount) ? useWinningAmount : 0;
            console.log('curr_ratio', curr_ratio, winning_amount, useWinningAmount)

            if($.isNumeric(amount) && parseFloat(amount) > 0 && parseFloat(amount) != parseFloat(currAmount)) {
                currAmount = amount;
                
                $modal.find('.top_up_amount').html('(+' + numberWithCommas(parseFloat(amount).toFixed(2)) + ')');
                $modal.find('.winning_amount').html(useWinningAmount.toFixed(2));
                $modal.find('.team_ratio').html(curr_ratio ? numberWithCommas(parseFloat(curr_ratio).toFixed(2)) : '0.00');

                return;
                if(isFetchingAmount) {
                    xhrFetchRequest.abort();
                    isFetchingAmount = false;
                }
                
                isFetchingAmount = true;
                xhrFetchRequest = $.ajax({
                    url:'{{route("json_match_possible_winning")}}',
                    type:'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    @if(Auth::check() && $hasMatchManagementAccess)
                    data: {teamid: team_id, matchid: match_id, amount: amount, betid: $modal.find(':input[name=betid]').val()},
                    @else
                    data: {teamid: team_id, matchid: match_id, amount: amount},
                    @endif
                    success:function(data){
                        $modal.find('.team_ratio').html(numberWithCommas(data.ratio.toFixed(2)));
                        $modal.find('.winning_amount').html(numberWithCommas(data.amount.toFixed(2)));
                        isFetchingAmount = false;
                    },
                    error:function(data) {
                        isFetchingAmount = false;
                    }
                });


            } else {
    
                $modal.find('.top_up_amount').html('');
                $modal.find('.winning_amount').html(numberWithCommas(useWinningAmount.toFixed(2)));
                $modal.find('.team_ratio').html(curr_ratio ? numberWithCommas(parseFloat(curr_ratio).toFixed(2)) : '0.00');
            }
            // if($.inArray(event.keyCode, [9,16,17,18,91,37,38,39,40]) == -1) {
                
            // }
        });
        

        $(document).on('click', '.addBetBtn', function() {
            $('#addBetModal').data('team_ratio', $(this).data('teamratio'));
            $('#addBetModal :input[name=teamid]').val($(this).data('teamid'));
            $('#addBetModal .team_image').attr('src', "{{url('/')}}/" + $(this).data('teamimage'));
            $('#addBetModal .team_name').html($(this).data('teamname'));
            $('#addBetModal .team_ratio').html($(this).data('teamratio'));
        });
        
        $(document).on('click', '.updateBetBtn', function() {
            $('#updateBetModal').data('team_ratio', $(this).data('teamratio'));
            $('#updatebettingForm :input[name=teamid]').val({{isset($bet) ? $bet->team->id : 0}});
            $('#updatebettingForm .bet_amount').text($(this).data('betamount'));
            $('#updateBetModal .team_image').attr('src', "{{url('/')}}/" + $(this).data('teamimage'));
            $('#updateBetModal .team_name').html($(this).data('teamname'));
            $('#updateBetModal .team_ratio').html($(this).data('teamratio'));
        });        

        var bindAddMoreBet = function(){
            $('.addMoreBetBtn').one('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                var form = $('#updatebettingForm').serializeArray();
                form.push({
                    name: 'betid',
                    value: {{isset($bet) ? $bet->id : 0}}
                });
                var errorBox = $('.addMoreBetBtn_error_field');
                errorBox.hide();
                $.ajax({
                    url:'{{route("json_match_updatebet")}}',
                    type:'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    data: form,
                    success:function(data){
                        if(data.success) {
                            $('#updateBetModal').modal('hide');
                            swal("Success!", "Your bet has been added.", "success");
                            window.setTimeout(function(){
                                location.reload();
                            }, 2000);
                        } else {
                            if(data.errors) {
                                var list = "";
                                $.each(data.errors, function(key, value) {
                                    list += value[0] + "<br/>";
                                });
                                errorBox.html(list).show();
                                bindAddMoreBet();
                            }
                        }
                    }
                });
            });
        };
        
        var bindAddBet = function(){
            $('.confirmBetBtn').one("click", e => {
                e.preventDefault();
                e.stopPropagation();
                var $button = $(this);
                $button.button('loading');
                $button.prop('disabled', true);
                var form = $('#bettingForm').serializeArray();
                form.push({
                    name: 'matchid',
                    value: {{$match->id}}
                });
                //var errorBox = $(this).closest('.modal-content').find('.error_field');
                var errorBox = $('.betting_error_field');
                errorBox.hide();
                $.ajax({
                    url:'{{route("json_match_addbet")}}',
                    type:'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    data: form,
                    success:function(data){
                        $button.button('reset');
                        $button.prop('disabled', false);
                        if(data.success) {
                            $('#addBetModal').modal('hide');
                            swal("Success!", "Your bet has been set.", "success");
                            window.setTimeout(function(){
                                location.reload();
                            }, 2000);
                        } else {
                            if(data.errors) {
                                var list = "";
                                $.each(data.errors, function(key, value) {
                                    list += value[0] + "<br/>";
                                });
                                errorBox.html(list).show();
                                bindAddBet();
                            }
                        }
                    }
                });
            })
        }

        $(function(){
            bindAddBet();
            bindAddMoreBet();
        })
        
        @if(in_array($match->status, ['ongoing','open','settled','draw', 'cancelled']) && Auth::check() && $hasMatchManagementAccess)

            $(document).on('change', '#is_initial_odds_enabled', function(event){
                
                if(event.currentTarget.value == 0){
                    $('#matchesForm :input[name=team_a_initial_odd]').prop('disabled', true);
                    $('#matchesForm :input[name=team_b_initial_odd]').prop('disabled', true);
                    $('#sub_matches :input[name=sub_matches_team_a_initial_odd]').prop('disabled', true);
                    $('#sub_matches :input[name=sub_matches_team_b_initial_odd]').prop('disabled', true);
                }else{
                    $('#matchesForm :input[name=team_a_initial_odd]').prop('disabled', false);
                    $('#matchesForm :input[name=team_b_initial_odd]').prop('disabled', false);
                    $('#sub_matches :input[name=sub_matches_team_a_initial_odd]').prop('disabled', false);
                    $('#sub_matches :input[name=sub_matches_team_b_initial_odd]').prop('disabled', false);                
                }
            })


            $(document).on('change', '#checkboxChangeInitialOdds', function(event){
                $(this).prop("checked", false);
                swal({
                    title: "Operations Manager's Passcode required",
                    text: 'to change initial odds of this match.',
                    type: "input",
                    inputType: "password",
                    html: true,
                    inputPlaceholder: "Type passcode here",
                    showCancelButton: true,
                    confirmButtonClass: "btn-danger",
                    confirmButtonText: "Yes, allow updating",
                    cancelButtonText: "No",
                    showLoaderOnConfirm: true,
                    closeOnConfirm: false
                }, function(passcode) {
                    if (passcode === false || passcode === "" ){
                        swal.showInputError("Please contact admin for the passcode!");
                        return false
                    }else if(passcode === "{{ env('OVERRIDE_CODE')}}" || passcode === "{{ env('OVERRIDE_CODE2')}}"){
                        $("#checkboxChangeInitialOdds").prop("checked", true);
                        $('input[name=team_a_initial_odd]').prop("disabled", false);
                        $('input[name=team_b_initial_odd]').prop("disabled", false);
                        swal.close();
                    }else{
                        swal.showInputError("Please contact admin for the passcode!");
                        return false
                    }
                    

                })            
            })
                    
            $(document).on('click', '.declareWinnerBtn', function() {
                $btn = $(this);
                $team_img = '<img src="{{url('/')}}/'+$btn.data('teamimage')+'" style="width: 100px;border-radius: 2px;" /><br/>';
                swal({
                    title: $btn.data('teamid') == 'draw' ? "Declare match as Draw?" : "Declare "+$btn.data('teamname')+" as winner? <br/>{{$match->name}}",
                    text: $btn.data('teamid') == 'draw' ? 'For confirmation, please type draw below to settle this match.' : $team_img + "For confirmation, type below the name of the team you want to declare as winner!",
                    type: "input",
                    html: true,
                    inputPlaceholder: "Team Name",
                    showCancelButton: true,
                    confirmButtonClass: "btn-danger",
                    confirmButtonText: $btn.data('teamid') == 'draw' ? "Yes, set match as Draw" : "Yes, winner is " + $btn.data('teamname'),
                    cancelButtonText: "Cancel",
                    showLoaderOnConfirm: true,
                    closeOnConfirm: false
                }, function(inputValue){
                    if (inputValue === false) return false;
                    if (inputValue === "") {
                        swal.showInputError($btn.data('teamid') == 'draw' ? "You need to write draw to settle this match!" : "You need to write name of the winning team!");
                        return false
                    }
                    if (inputValue === $btn.data('teamname')) {
                        $.ajax({
                        url: "{{ route('json_matches_settle') }}",
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        type: 'POST',
                        data: {match_id: {{$match->id}}, team_winner: $btn.data('teamid')},
                        success: function(data){
                            if(data.error)
                                swal("Error!", data.error, "error");
                            else {
                                swal("Success!", "The match was successfully settled.", "success");
                                window.setTimeout(function(){
                                    location.reload();
                                }, 2000);
                            }
                        }
                    });
                    } else {
                        swal.showInputError("Wrong team selected!");
                        return false
                    }
                });
            });
            $(document).on('click', '.cancelMatchBtn', function() {
                $btn = $(this);
                swal({
                    title: "Cancel this Match?",
                    text: 'For confirmation, please type "cancel" below to cancel this match.',
                    type: "input",
                    html: true,
                    inputPlaceholder: "Type cancel here",
                    showCancelButton: true,
                    confirmButtonClass: "btn-danger",
                    confirmButtonText: "Yes, set cancel this match",
                    cancelButtonText: "No",
                    showLoaderOnConfirm: true,
                    closeOnConfirm: false
                }, function(inputValue){
                    if (inputValue === false) return false;
                    if (inputValue === "") {
                        swal.showInputError("You need to write cancel to settle this match!");
                        return false
                    }
                    if (inputValue === $btn.data('teamname')) {
                        $.ajax({
                        url: "{{ route('json_matches_settle') }}",
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        type: 'POST',
                        data: {match_id: {{$match->id}}, team_winner: 'cancelled'},
                        success: function(data){
                            if(data.error)
                                swal("Error!", data.error, "error");
                            else {
                                swal("Success!", "The match was successfully cancelled.", "success");
                                window.setTimeout(function(){
                                    location.reload();
                                }, 2000);
                            }
                        }
                    });
                    } else {
                        swal.showInputError("Wrong option selected!");
                        return false
                    }
                });
            });



            $(document).on('click', '.cancelAdminBet', function() {
                var betid = $(this).data('betid');
                swal({
                    title: "Cancel this admin bet?",
                    text: "This bet will be cancelled and deleted!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: "btn-danger",
                    confirmButtonText: "Yes, cancel bet!",
                    cancelButtonText: "No",
                    closeOnConfirm: false,
                    showLoaderOnConfirm: true
                },
                function(){
                    $.ajax({
                        url:'{{route("cancel-admin-bet")}}',
                        type:'DELETE',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        data: {betid: betid},
                        success:function(data){
                            if(data.error) {
                                swal("Error!", data.error, "error");
                            } else {
                                swal("Bet Cancelled!", "Bet has now been cancelled.", "success");
                                window.setTimeout(function(){
                                    location.reload();
                                }, 2000);
                            }
                        }
                    });
                });
            });

            $(document).on('click', '#remove-admin-bets', function(){
                const matchId = "{{ $match->id }}";
                swal({
                    title: "Remove Admin Bets",
                    text: `Are you sure you want to remove <strong>ALL</strong> admin bets on this match (#${matchId})?`,
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: "btn-danger",
                    confirmButtonText: "Yes, remove admin bets.",
                    cancelButtonText: "No",
                    closeOnConfirm: false,
                    showLoaderOnConfirm: true,
                    html: true
                },
                function(){
                    $.ajax({
                        url:'{{route("remove-admin-bets")}}',
                        type:'DELETE',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        data: { match_id : matchId },
                        success:function(data){
                            if(!data.success) {
                                swal("Error!", data.message, "error");
                            } else {
                                swal("Bet Cancelled!", data.message, "success");
                                window.setTimeout(function(){
                                    location.reload();
                                }, 2000);
                            }
                        }
                    });
                });
            });

            $(document).on('click', '#addWhyLinkDrawCancelled', function() {

                const matchId = "{{ $match->id }}";
                swal({
                    title: "Input Link",
                    text: 'Add Why or More Info link why this match got cancelled or declared as draw.',
                    type: "input",
                    html: true,
                    inputPlaceholder: "Enter link here",
                    inputValue: "{{ $match->more_info_link }}",
                    showCancelButton: true,
                    confirmButtonClass: "btn-info",
                    confirmButtonText: "Save Link",
                    cancelButtonText: "No",
                    showLoaderOnConfirm: true,
                    closeOnConfirm: false
                }, function(inputValue){

                    $.ajax({
                        url: "{{ route('admin.update-more-info-link') }}",
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        type: 'PUT',
                        data: {
                            match_id : matchId,
                            link: inputValue    
                        },
                        success: function(data){
                            if(!data.success)
                                swal("Error!", data.message, "error");
                            else {
                                swal("Success!", data.message, "success");
                                window.setTimeout(function(){
                                    location.reload();
                                }, 2000);
                            }
                        }
                    });
                    

                });

            });        

            $('#extendMatchTimeBtn').click(function() {
                $btn = $(this).button('loading');
                var form = new FormData($("#extendMatchForm")[0]);
                $.ajax({
                    url: "{{ route('extend_match_time') }}",  //Server script to process data
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    type: 'POST',
                    success: function(data){
                        if(data.success) {
                            $btn.button('reset');
                            $('#extendMatchModal').modal('hide');
                            swal("Success!", "Successfully updated match schedule.", "success");
                            window.setTimeout(function(){
                                location.reload();
                            }, 2000);
                        } else {
                            $.each(data.errors, function( key, value ) {
                                $('#extendMatchForm').find(':input[name='+ key +']').parent().addClass('has-error');
                                $('#extendMatchForm').find(':input[name='+ key +']').parent().find('.error-label').text(value[0]);
                            });
                        }
                    },
                    data: form,
                    cache:false,
                    contentType: false,
                    processData: false,
                });
            });
            $('#editMatchBtn').click(function() {
                $(this).prop("disabled",true);
                var form = new FormData($("#matchesForm")[0]);
                $.ajax({
                    url: "{{ route('edit_match_page') }}",  //Server script to process data
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    type: 'POST',
                    success: function(data){
                        if(data.success) {
                            $('#editMatchModal').modal('hide');
                            swal("Success!", "Successfully updated match", "success");
                            window.setTimeout(function(){
                                location.reload();
                            }, 2000);
                        } else {
                            $.each(data.errors, function( key, value ) {
                                $('#matchesForm').find(':input[name='+ key +']').parent().addClass('has-error');
                                $('#matchesForm').find(':input[name='+ key +']').parent().find('.error-label').text(value[0]);
                            });
                        }
                    },
                    data: form,
                    cache:false,
                    contentType: false,
                    processData: false,
                });
            });
            $('#editScoreBtn').click(function() {
                var form = new FormData($("#matchesForm2")[0]);
                $.ajax({
                    url: "{{ route('edit_match_page') }}",  //Server script to process data
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    type: 'POST',
                    success: function(data){
                        if(data.success) {
                            $('#editScoreBtn').modal('hide');
                            swal("Success!", "Successfully updated match", "success");
                            window.setTimeout(function(){
                                location.reload();
                            }, 2000);
                        } else {
                            $.each(data.errors, function( key, value ) {
                                $('#matchesForm2').find(':input[name='+ key +']').parent().addClass('has-error');
                                $('#matchesForm2').find(':input[name='+ key +']').parent().find('.error-label').text(value[0]);
                            });
                        }
                    },
                    data: form,
                    cache:false,
                    contentType: false,
                    processData: false,
                });
            });
            $('#editSettledBtn').click(function() {
                var form = new FormData($("#matchesForm5")[0]);
                $.ajax({
                    url: "{{ route('edit_match_page') }}",  //Server script to process data
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    type: 'POST',
                    success: function(data){
                        if(data.success) {
                            $('#editSettledBtn').modal('hide');
                            swal("Success!", "Successfully updated match", "success");
                            window.setTimeout(function(){
                                location.reload();
                            }, 2000);
                        } else {
                            $.each(data.errors, function( key, value ) {
                                $('#matchesForm5').find(':input[name='+ key +']').parent().addClass('has-error');
                                $('#matchesForm5').find(':input[name='+ key +']').parent().find('.error-label').text(value[0]);
                            });
                        }
                    },
                    data: form,
                    cache:false,
                    contentType: false,
                    processData: false,
                });
            });

            $('#openBackMatchBtn').click(function() {
                swal({
                    title: "Open back this Match?",
                    text: 'For confirmation, please type "Open Match" below to open up bettings for this match.',
                    type: "input",
                    html: true,
                    inputPlaceholder: "Type cancel here",
                    showCancelButton: true,
                    confirmButtonClass: "btn-danger",
                    confirmButtonText: "Yes, open it!",
                    cancelButtonText: "No",
                    showLoaderOnConfirm: true,
                    closeOnConfirm: false
                },
                function(inputValue){
                    if (inputValue === false) return false;
                    if (inputValue === "") {
                        swal.showInputError("You need to write 'Open Match' to open this match!");
                        return false
                    }
                    if (inputValue === "Open Match") {
                        $.ajax({
                            url:'{{route("json_matches_setopen")}}',
                            type:'PATCH',
                            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                            data: {match_id: {{$match->id}}},
                            success:function(data){
                                if(data.success) {
                                    swal("Match set!", "The match has now been set back to Open.", "success");
                                    window.setTimeout(function(){
                                        location.reload();
                                    }, 2000);
                                } else {
                                    if(data.need_auth)
                                        matchOpenCode();
                                    else
                                        swal("Error!", data.error, "error");
                                }
                            }
                        });
                    } else {
                        swal.showInputError("You need to write 'Open Match' to open this match!");
                        return false
                    }
                });
            });

            $('.revertMatchToLiveBtn').click(function() {
                swal({
                    title: "Set this Match back to Live/Ongoing?",
                    text: 'For confirmation, please type "Revert" below to revert this match to live/ongoing status.',
                    type: "input",
                    html: true,
                    inputPlaceholder: "Type Revert here",
                    showCancelButton: true,
                    confirmButtonClass: "btn-danger",
                    confirmButtonText: "Yes, revert it!",
                    cancelButtonText: "No",
                    showLoaderOnConfirm: true,
                    closeOnConfirm: false
                },
                function(inputValue){
                    if (inputValue === false) return false;
                    if (inputValue === "") {
                        swal.showInputError("You need to write 'Revert' to revert this match!");
                        return false
                    }
                    if (inputValue === "Revert") {
                        $.ajax({
                            url:'{{route("json_matches_undo_settled")}}',
                            type:'PUT',
                            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                            data: {match_id: {{$match->id}}},
                            success:function(data){
                                if(data.success) {
                                    swal("Match set!", "The match has now been set back to LIVE/ONGOING.", "success");
                                    window.setTimeout(function(){
                                        location.reload();
                                    }, 2000);
                                } else {
                                    swal("Error!", data.error, "error");
                                }
                            }
                        });
                    } else {
                        swal.showInputError("You need to write 'Revert' to revert this match!");
                        return false
                    }
                });
            });

            $('.lockMatchBettingBtn').click(function() {
                swal({
                    title: "Lock betting for this Match?",
                    text: 'For confirmation, please type "Lock" below to lock betting on this match',
                    type: "input",
                    html: true,
                    inputPlaceholder: "Type Lock here",
                    showCancelButton: true,
                    confirmButtonClass: "btn-danger",
                    confirmButtonText: "Yes, lock it!",
                    cancelButtonText: "No",
                    showLoaderOnConfirm: true,
                    closeOnConfirm: false
                },
                function(inputValue){
                    if (inputValue === false) return false;
                    if (inputValue === "") {
                        swal.showInputError("You need to write 'Lock' to lock betting on this match!");
                        return false
                    }
                    if (inputValue === "Lock") {
                        $.ajax({
                            url:'{{route("json_lock_match_betting")}}',
                            type:'POST',
                            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                            data: {match_id: {{$match->id}}},
                            success:function(data){
                                if(data.success) {
                                    swal("Match Lock", "Betting on this match has been lock for 1 minute.", "success");
                                    window.setTimeout(function(){
                                        location.reload();
                                    }, 2000);
                                } else {
                                    swal("Error!", data.error, "error");
                                }
                            }
                        });
                    } else {
                        swal.showInputError("You need to write 'Lock' to lock betting on this match!");
                        return false
                    }
                });
            });        

            $('.unlockMatchBettingBtn').click(function() {
                swal({
                    title: "Unlock betting for this Match?",
                    text: 'For confirmation, please type "Unlock" below to allow betting on this match',
                    type: "input",
                    html: true,
                    inputPlaceholder: "Type Lock here",
                    showCancelButton: true,
                    confirmButtonClass: "btn-danger",
                    confirmButtonText: "Yes, lock it!",
                    cancelButtonText: "No",
                    showLoaderOnConfirm: true,
                    closeOnConfirm: false
                },
                function(inputValue){
                    if (inputValue === false) return false;
                    if (inputValue === "") {
                        swal.showInputError("You need to write 'Unlock' to allow betting on this match!");
                        return false
                    }
                    if (inputValue === "Unlock") {
                        $.ajax({
                            url:'{{route("json_unlock_match_betting")}}',
                            type:'POST',
                            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                            data: {match_id: {{$match->id}}},
                            success:function(data){
                                if(data.success) {
                                    swal("Match Unlock", "Betting on this match is now allowed.", "success");
                                    window.setTimeout(function(){
                                        location.reload();
                                    }, 2000);
                                } else {
                                    swal("Error!", data.error, "error");
                                }
                            }
                        });
                    } else {
                        swal.showInputError("You need to write 'Unlock' to allow betting on this match!");
                        return false
                    }
                });
            });          

            $(document).on('click', '.updateMatchStreamLinks', function(){
                const twitchLink = $('#twitch-stream-link').val();
                const youtubeLink = $('#youtube-stream-link').val();
                const facebookLink = $('#facebook-stream-link').val();
                const otherLink = $('#other-stream-link').val();
                const matchId = "{{ $match->id }}";

                $.ajax({
                    url: "{{ route('admin.update-stream-links') }}",  //Server script to process data
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    type: 'PUT',
                    data: {
                        twitchLink,
                        youtubeLink, 
                        facebookLink,
                        otherLink,
                        match_id: matchId
                    },
                    success: function(data){
                        if(data.success) {
                            $('#editSteamLinks').modal('hide');
                            swal("Success!", "Successfully updated match", "success");
                            window.setTimeout(function(){
                                location.reload();
                            }, 2000);
                        } 
                    },
                });            
            })

            
            $(document).on('click', '.editAdminBet', function() {
                $('#editBetModal').data('team_ratio', $(this).data('teamratio'));
                $('#editbettingForm :input[name=betid]').val($(this).data('betid'));
                $('#editbettingForm :input[name=teamid]').val($(this).data('teamid'));
                $('#editbettingForm :input[name=bet_amount]').val($(this).data('betamount'));
                $('#editbettingForm .winning_amount').text($(this).data('potentialwinning'));
                $('#editBetModal .team_image').attr('src', "{{url('/')}}/" + $(this).data('teamimage'));
                $('#editBetModal .team_name').html($(this).data('teamname'));
                $('#editBetModal .team_ratio').html($(this).data('teamratio'));
            });

            //edit admin bets percentages stuff
            $(document).on('dblclick', '.admin-bet-input-percentage', function() {
                $('.admin-bet-input-percentage').prop('readonly',true);
                $(this).prop('readonly',false);
            });

            $(document).on('change', '.admin-bet-input-percentage', function() {
                const inputId = $(this).attr('id');
                const currentVal = $(this).val();
                const hasDraw =  '{{ !empty($match->team_c) ? 1 : 0 }}'

                if(currentVal >= 6 && currentVal <= 94){
                    if(hasDraw == 1){
                        const totalValue = parseFloat($('#admin-bet-input-percentage-a').val()) 
                                            + parseFloat($('#admin-bet-input-percentage-b').val()) 
                                            + parseFloat($('#admin-bet-input-percentage-c').val())
                   
                        if(totalValue > 100){
                            swal("Mismatch", "You can only set the percentages between 6% to 94% AND total of 100%", "error");
                        }
                    }else{
                        if(inputId == 'admin-bet-input-percentage-a'){
                            $('#admin-bet-input-percentage-b').val(parseFloat(100 - currentVal));
                        }else{
                            $('#admin-bet-input-percentage-a').val(parseFloat(100 - currentVal));
                        }
                    }

                }else{
                    swal("Mismatch", "You can only set the percentages between 6% to 94% AND total of 100%", "error");
                }
            });

            $(document).on('click', '#saveAdminBetsPercentagesButton', function(e) {
                e.preventDefault();
                e.stopPropagation();
                var $button = $(this);
                $button.button('loading');
                const currentTeamAPercentage = $('#current-admin-bet-input-percentage-a').val();
                const currentTeamBPercentage = $('#current-admin-bet-input-percentage-b').val();
                const currentTeamCPercentage = $('#current-admin-bet-input-percentage-c').val();
                const newTeamAPercentage =  $('#admin-bet-input-percentage-a').val();
                const newTeamBPercentage =  $('#admin-bet-input-percentage-b').val();
                const newTeamCPercentage =  $('#admin-bet-input-percentage-c').val();
                const baseAmount = $('#admin-bet-input-percentage-base-amount').val();
                const teamABet = parseFloat( baseAmount * (newTeamAPercentage / 100) );
                const teamBBet = parseFloat( baseAmount * (newTeamBPercentage / 100) );
                const teamCBet = parseFloat( baseAmount * (newTeamCPercentage / 100) );

                const totalValue = parseFloat($('#admin-bet-input-percentage-a').val()) 
                                    + parseFloat($('#admin-bet-input-percentage-b').val()) 
                                    + parseFloat($('#admin-bet-input-percentage-c').val())
                if(totalValue > 100){
                    $button.button('reset');
                    swal("Mismatch", "You can only set the percentages between 6% to 94% AND total of 100%", "error");
                    return false;
                }
                const updateData = {
                    match_id : "{{ $match->id }}",
                    team_a_percentage: newTeamAPercentage,
                    team_b_percentage: newTeamBPercentage,
                    team_c_percentage: newTeamCPercentage,
                    team_a_bet: teamABet,
                    team_b_bet: teamBBet,
                    team_c_bet: teamCBet
                };

                let isBigAdjustment = false;
                // if( (newTeamAPercentage > currentTeamAPercentage) && ( parseFloat(newTeamAPercentage - currentTeamAPercentage) >= 20 ) ){
                //     isBigAdjustment = true;
                // }else if( (newTeamBPercentage > currentTeamBPercentage) && ( parseFloat(newTeamBPercentage - currentTeamBPercentage) >= 20 ) ){
                //     isBigAdjustment = true;
                // }else if( (currentTeamAPercentage > newTeamAPercentage) && ( parseFloat(currentTeamAPercentage - newTeamAPercentage) >= 20 ) ){
                //     isBigAdjustment = true;
                // }else if( (currentTeamBPercentage > newTeamBPercentage) && ( parseFloat(currentTeamBPercentage - newTeamBPercentage) >= 20 ) ){
                //     isBigAdjustment = true;
                // }

                if(isBigAdjustment){
                swal({
                    title: `Warning!`,
                    text: `You are about to change the admin bets with over 20% odd swing. Do you want to proceed?`,
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: "btn-warning",
                    confirmButtonText: "Yes, proceed with update.",
                    cancelButtonText: "Cancel",
                    showLoaderOnConfirm: true,
                    closeOnConfirm: false
                }, function(approved) {
                    if(approved){
                        processUpdateAdminBets(updateData);
                    }else{
                        console.log(`Doing nothing...`);
                    }
                })
                }else{
                    processUpdateAdminBets(updateData);
                }
            });

            function processUpdateAdminBets(data){
                console.log('Processing admin bets update.... : ', data)
                $.ajax({
                    url:'{{route("update-both-admin-bets")}}',
                    type:'PUT',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    data: data,
                    success:function(data){
                        const { success, message } = data;
                        if(success){
                            swal("Success!", message, "success");
                            window.setTimeout(function(){
                                location.reload();
                            }, 2000);
                        }else{
                            swal("Error!", message, "error");
                        }
                    }
                });
            }
        
            //end edit admin bets percentages stuff

            $(document).on('click', '#editBetModal :button.editBetBtn', function(e) {
                e.preventDefault();
                e.stopPropagation();
                var $button = $(this);
                $button.button('loading');
                var form = $('#editbettingForm').serializeArray();
                form.push({
                    name: 'matchid',
                    value: {{$match->id}}
                });
                var errorBox = $(this).closest('.modal-content').find('.error_field');
                errorBox.hide();
                $.ajax({
                    url:'{{route("json_match_editbet")}}',
                    type:'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    data: form,
                    success:function(data){
                        $button.button('reset');
                        if(data.success) {
                            $('#editBetModal').modal('hide');
                            swal("Success!", "Your bet has been set.", "success");
                            window.setTimeout(function(){
                                location.reload();
                            }, 2000);
                        } else {
                            if(data.errors) {
                                var list = "";
                                $.each(data.errors, function(key, value) {
                                    list += value[0] + "<br/>";
                                });
                                errorBox.html(list).show();
                            }
                        }
                    }
                });
            });
            
            function matchOpenCode(msg = '') {
                swal({
                    title: msg ? msg : "Passcode required!",
                    text: 'To open this match, please contact admin!',
                    type: "input",
                    html: true,
                    inputPlaceholder: "Type passcode here",
                    showCancelButton: true,
                    confirmButtonClass: "btn-danger",
                    confirmButtonText: "Yes, open it!",
                    cancelButtonText: "No",
                    showLoaderOnConfirm: true,
                    closeOnConfirm: false
                }, function(passcode) {
                    if (passcode === false) return false;
                    if (passcode === "") {
                        swal.showInputError("Please contact admin for the passcode!");
                        return false
                    } else {
                        $.ajax({
                            url:'{{route("json_matches_setopen")}}',
                            type:'PATCH',
                            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                            data: {match_id: {{$match->id}}, passcode: passcode},
                            success:function(data) {
                                if(data.success) {
                                    swal("Match set!", "The match has now been set back to Open.", "success");
                                    window.setTimeout(function(){
                                        location.reload();
                                    }, 2000);
                                } else {
                                    matchOpenCode('Invalid passcode!');
                                }
                            }
                        });
                    }
                })
            }
        @endif
        
        $(document).on('click', '.cancelBetBtn', function() {
            $btn = $(this);
            var betid = $btn.data('betid') ? $btn.data('betid') : 
                    '{{isset($bet) ? $bet->id : 0}}';
            swal({
                title: "Cancel your bet?",
                text: "Your bet will be cancelled and deleted!",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Yes, cancel it!",
                cancelButtonText: "No",
                closeOnConfirm: false,
                showLoaderOnConfirm: true
            },
            function(){
                $.ajax({
                    url:'{{route("json_matches_cancelbet")}}',
                    type:'DELETE',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    data: {betid: betid},
                    success:function(data){
                        if(data.error) {
                            swal("Error!", data.error, "error");
                        } else {
                            swal("Bet Cancelled!", "Your bet has now been cancelled.", "success");
                            window.setTimeout(function(){
                                location.reload();
                            }, 2000);
                        }
                    }
                });
            });
        });
        
        $(document).on('hidden.bs.modal', "#addBetModal", function () {
            var curr_ratio = $(this).find('.btn-primary').data('teamratio');
            var winning_amount = 0;
            $(this).find('.winning_amount').html(winning_amount.toFixed(2));
            $(this).find('.team_ratio').html(curr_ratio);
            $(this).find(':input[name=bet_amount]').val('');
            $(this).find('.confirmBetBtn').button('reset');
            $(this).find('.error_field').hide();
        });
        
        $(document).on('hidden.bs.modal', "#updateBetModal", function () {
            @if(isset($bet))
                var winning_amount = {{$potential_team_winning}};
            @else
                var winning_amount = 0.00;
            @endif
            var curr_ratio = $(this).find('.btn-primary').data('teamratio');
            $(this).find('.winning_amount').html(numberWithCommas(winning_amount.toFixed(2)));
            $(this).find('.top_up_amount').html('');
            $(this).find('.team_ratio').html(curr_ratio);
            $(this).find(':input[name=bet_amount]').val('');
            $(this).find('.error_field').hide();
        });
        
    });

    function showDrawNotificationModal() {
        let remind_later = checkDrawNotiCookie();

        if(remind_later == '') {
            $('#draw-notification-modal').modal('show');
        }
    }

    function checkDrawNotiCookie() {
        var reminder = getDrawNotiCookie("draw_notification_cookie");

        return reminder;
    }

    function getDrawNotiCookie(cname) {
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


    $(document).on("click", "#btn-purchase-reminder", function() {
        var date = new Date();
        console.log('1  ', date.toUTCString())
        date.setTime(date.getTime() + ( (60 * 60 * 1000) * 4));
        console.log('date.toUTCString: ', date.toUTCString())
        var expires = "; expires="+date.toUTCString();

        document.cookie = "draw_notification_cookie" + "=" + "true" + ";" + expires + ";path=/";

        $('#draw-notification-modal').modal('hide');
    })
</script>
{{-- 
@if(Auth::check() && $hasMatchManagementAccess)

    <script type="text/javascript" src="{{ asset('js/datatables.min.js')}}"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.1/js/dataTables.responsive.min.js"></script>
    <script src="{{ asset('js/datatables-datetime-moment.js') }}"></script>
    <!--audit user -->
    @include('admin/audit');
    <!--end audit user-->
@endif --}}

@endsection
