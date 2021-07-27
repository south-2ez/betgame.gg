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
<div class="main-container dark-grey">
    <div class="m-container1" id="left">
        <div class="main-ct" style="margin-bottom: 0">
            <div class="title">EVENTS </div>
            <div class="clearfix"></div>
            <div class="col-md-12 tournament_bet">
                
                <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                    @foreach($leagues as $key => $league)
                    <div class="panel panel-default">
                        <div class="panel-heading" role="tab" id="heading{{$league->id}}">
                            <h4 class="panel-title" style="text-align: left">
                                @if($key == 0)
                                <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse{{$league->id}}" aria-expanded="true" aria-controls="collapse{{$league->id}}">
                                    {{$league->name}}
                                </a>
                                @else
                                <a {{$league->betting_status == 1 ? '' : 'class="collapsed"'}} role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse{{$league->id}}" aria-expanded="{{$league->betting_status == 1 ? 'true' : 'false'}}" aria-controls="collapse{{$league->id}}">
                                    {{$league->name}}
                                </a>
                                @endif
                                @if($league->betting_status == 1)
                                <span class="pull-right green"><strong>Open</strong></span>
                                @elseif($league->betting_status == -1)
                                <span class="pull-right">Settled: <strong class="green">{{$league->champion->name}}</strong> (Winner)</span>
                                @else
                                <span class="pull-right"><strong>Ongoing</strong></span>
                                @endif
                            </h4>
                        </div>
                        @if($key == 0)
                        <div id="collapse{{$league->id}}" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading{{$league->id}}">
                        @else
                        <div id="collapse{{$league->id}}" class="panel-collapse collapse {{$league->betting_status == 1 ? 'in' : ''}}" role="tabpanel" aria-labelledby="heading{{$league->id}}">
                        @endif
                            <div class="panel-body" style="padding: 1px; background-image: url({{asset('/images/bg_03.jpg')}})">
                                @foreach($league->teams as $index => $team)
                                @if($index == 0)
                                <div class="col-md-6" style="padding-left: 0; padding-right: 1px">
                                    <table border="1" class="table table-responsive table-border" style="text-align: center; margin-bottom: 0">
                                        <thead>
                                            <tr>
                                                <th style="text-align: center; width: 82px;">Team</th>
                                                <th style="text-align: center; font-size: 90%">% of Winning</th>
                                                <th style="text-align: center">Ratio</th>
                                                <th style="text-align: center; font-size: 90%">Possible win per 100</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @endif
                                            @if($index == intVal(round($league->teams->count()/2)))
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-md-6" style="padding-left: 1px; padding-right: 0">
                                    <table border="1" class="table table-responsive table-border" style="text-align: center; margin-bottom: 0">
                                        <thead>
                                            <tr>
                                                <th style="text-align: center; width: 82px;">Team</th>
                                                <th style="text-align: center; font-size: 90%">% of Winning</th>
                                                <th style="text-align: center">Ratio</th>
                                                <th style="text-align: center; font-size: 90%">Possible win per 100</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @endif
                                            <tr>
                                                <td style="position: relative">
                                                    <img src="{{asset($team->image)}}" title="{{$team->name}}" style="width: 82px;">
                                                    @if($team->pivot->is_favorite)
                                                    <span class="favorite_team" title="Favorite team"></span>
                                                    @endif
                                                </td>
                                                <td style="vertical-align: middle;">{{ round($team->teamPercentage, 2) }}%</td>
                                                <td style="vertical-align: middle;">
                                                    {{ bcdiv($team->teamRatio, 1 ,2) }}
                                                </td>
                                                <td style="vertical-align: middle;">
                                                    @if($team->teamRatio > 0)
                                                    <strong style="color: green">&#8369; {{ number_format((100 * bcdiv($team->teamRatio, 1 ,2) ), 2, '.', ',') }}</strong>
                                                    @else
                                                    &#8369; 0
                                                    @endif
                                                </td>
                                            </tr>
                                            @if($league->teams->count() == ($index+1))
                                        </tbody>
                                    </table>
                                </div>
                                @endif
                                @endforeach
                                <div style="position: relative">
                                    <img src="{{$league->bottom_image ? url('/') . '/' . $league->bottom_image : asset('images/bottom_tournament_bg.png')}}" style="width: 100%; max-height: 103px;">
                                    <a href="{{url('/tournament') . '/' . $league->id}}" style="position: absolute; bottom: 5px; right: 5px; font-weight: bold; color: black; background-color: orange" class="btn btn-warning">Place Bet</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                
            </div>
        </div>
    </div>
    <div class="m-container2">
        <div  class="main-ct">
            <div class="title">MATCHES </div>
                <!-- Category buttons All, Dota2, Csgo, Sports -->
                <div style="background-color: #f1f1f1;">
                    <button id="loadAll" class="all-match-button btncategory focusbtn selected categorybtn" data-pointer="0" onclick=""><strong class="txt"> ALL</strong></button>
                    <button id="loadDota" class="dota-match-button btncategory focusbtn categorybtn" data-pointer="0" onclick=""><img src="{{ asset('/images/dota2icon.png')}}"><strong class="txt"> DOTA2</strong></button>
                    <button id="loadCsgo" class="csgo-match-button btncategory focusbtn categorybtn" data-pointer="0" onclick=""><img src="{{ asset('/images/csgoicon.png')}}"><strong class="txt"> CS:GO<strong></button>
                    <button id="loadLol" class="lol-match-button btncategory focusbtn categorybtn" data-pointer="0" onclick=""><img src="{{ asset('/images/lol24px.png')}}"><strong class="txt"> LoL<strong></button>
                    <button id="loadSports" class="sports-match-button btncategory focusbtn categorybtn" data-pointer="0" onclick=""><img src="{{ asset('/images/sportsicon.png')}}"><strong class="txt"> SPORTS</strong></button>
                    <button id="loadNbaPlayoffs" class="nbaplayoffs-match-button btncategory focusbtn categorybtn" data-pointer="0" onclick=""><img src="{{ asset('/images/nba24px.png')}}"><strong class="txt"> NBA PLAYOFFS SERIES<strong></button>
                </div>
            <div id="matchesHolder">
                @foreach($matches as $index => $match)
                <div class="matchmain">
                    <div class="infor">
                        <div class="time">
                            @if($match->status == 'ongoing')
                            <span style="color: #72A326; text-shadow: 1px 1px 0px #4A7010; font-weight: bold; font-size: 16px">&nbsp;LIVE</span>
                            @elseif($match->status == 'settled')
                            {{$match->schedule->diffForHumans()}} <span style="color: #606060; font-weight: bold; font-size: 16px">&nbsp;SETTLED</span>
                            @elseif($match->status == 'draw')
                            {{$match->schedule->diffForHumans()}} <span style="color: #606060; font-weight: bold; font-size: 16px">&nbsp;DRAW - CREDITS RETURNED</span>
                            @else
                            <strong class="match_countdown" data-schedule="{{$match->schedule}}">{{$match->schedule->diffForHumans()}}</strong>
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
                    @if(in_array($match->status, ['open','ongoing']))
                    <div class="match " style="background-image: url({{ url('/public_image') . '/' . $match->league->image }})">
                    @else
                    <div class="match " style="background-image: url({{ url('/public_image') . '/' . $match->league->image }});">
                    @endif
                
                        <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
                            <a href="{{ url('/') . '/match/' . $match->id }}">
                                <div class="match-details">
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center">
                                        {{ !!$match->label ? $match->label : '' }}
                                    </div>
                                    <div class="row small-gutter flex-center">
                                        <div class="col-xs-4 col-sm-3 col-md-3 col-lg-3 no-padding text-center">
                                            
                                            <div><img class="match-team-logo" src="{{ $match->teamA->image }}" /></div>
                                            <div class="team-name hide-mobile">
                                                {!! str_limit($match->teamA->name, 13, '...') !!}
                                            </div>
                                            <div class="team-name hide-desktop">
                                                {!! str_limit($match->teamA->shortname, 10, '..') !!}
                                            </div>  
                                            <button class="btn btn-warning team-percentage-btn btn-sm w-100 hide-desktop">{{$match->teama_percentage}}%</button>                                          
                                            {{-- <div>{{$match->teama_percentage}}%</div> --}}
                                        </div>
                                        <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 no-padding text-left bet-percentage hide-mobile">
                                            
                                            <button class="btn btn-warning team-percentage-btn btn-sm w-100">{{$match->teama_percentage}}%</button>
                                        </div>

                                        <div class="col-xs-4 col-sm-2 col-md-2 col-lg-2 no-padding text-center">
                                            
                                            <div class="match-counter-container">{{$match->best_of}}</div>
                                            @if( !empty($match->team_c) )
                                                <div class="hide-mobile">
                                                    <div class="no-padding text-center bet-percentage">
                                                        <button class="btn btn-warning team-percentage-btn btn-sm w-100">{{ !!$match->teamc_percentage ? $match->teamc_percentage : 0.00 }}%</button>
                                                    </div>
                                                    <div class="team-name draw-text">DRAW</div>
                                                </div>
                                            
                                                <div class="hide-desktop">
                                                    <div class="team-name draw-text">DRAW</div>
                                                    <div class="no-padding text-center bet-percentage">
                                                        <button class="btn btn-warning team-percentage-btn btn-sm w-100">{{ !!$match->teamc_percentage ? $match->teamc_percentage : 0.00 }}%</button>
                                                    </div>
                                                </div>                                                
                                            @endif
                                        </div>

                                        <div class="col-xs-4 col-sm-2 col-md-2 col-lg-2 no-padding text-right bet-percentage hide-mobile">
                                            <button class="btn btn-warning team-percentage-btn btn-sm w-100">{{$match->teamb_percentage}}%</button>
                                        </div>                                                                                                                        
                                        <div class="col-xs-4 col-sm-3 col-md-3 col-lg-3 no-padding text-center">
                                        
                                            <div><img class="match-team-logo" src="{{ $match->teamB->image }}" /></div>
                                            <div class="team-name hide-mobile">
                                                {!! str_limit($match->teamB->name, 13, '...') !!}
                                            </div>
                                            <div class="team-name hide-desktop">
                                                {!! str_limit($match->teamB->shortname,10, '..') !!}
                                            </div>
                                            <button class="btn btn-warning team-percentage-btn btn-sm w-100 hide-desktop">{{$match->teamb_percentage}}%</button>    
                                        </div>   
                                    </div>                                                                  
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
                </div>
            </div>
            <!-- Show more buttons all -->
            <div style="text-align: center; margin-bottom: 10px">
                <center><button id="loadAllMore" type="button" class="btn btn-default btn-sm hidebtn btnall loadMoreBtn" data-pointer="0" data-loading-text="Loading ... <span class='glyphicon glyphicon-refresh fa-spin'></span>">Show more <i class="fa fa-angle-double-down"></i></button></center>
            </div>
            <!-- Show more button dota -->
            <div style="text-align: center; margin-bottom: 10px">
                <center><button id="loadDotaMore" type="button" class="btn btn-default btn-sm hidebtn btndota loadMoreBtn" data-pointer="0" data-loading-text="Loading ... <span class='glyphicon glyphicon-refresh fa-spin'></span>">Show more <i class="fa fa-angle-double-down"></i></button></center>
            </div>
            <!-- Show more button csgo -->
            <div style="text-align: center; margin-bottom: 10px">
                <center><button id="loadCsgoMore" type="button" class="btn btn-default btn-sm hidebtn btncsgo loadMoreBtn" data-pointer="0" data-loading-text="Loading ... <span class='glyphicon glyphicon-refresh fa-spin'></span>">Show more <i class="fa fa-angle-double-down"></i></button></center>
            </div>
            <div style="text-align: center; margin-bottom: 10px">
                <center><button id="loadLolMore" type="button" class="btn btn-default btn-sm hidebtn btnlol loadMoreBtn" data-pointer="0" data-loading-text="Loading ... <span class='glyphicon glyphicon-refresh fa-spin'></span>">Show more <i class="fa fa-angle-double-down"></i></button></center>
            </div>            
            <!-- Show more button sports -->
            <div style="text-align: center; margin-bottom: 10px">
                <center><button id="loadSportsMore" type="button" class="btn btn-default btn-sm hidebtn btnsports loadMoreBtn" data-pointer="0" data-loading-text="Loading ... <span class='glyphicon glyphicon-refresh fa-spin'></span>">Show more <i class="fa fa-angle-double-down"></i></button></center>
            </div>

            <div style="text-align: center; margin-bottom: 10px">
                <center><button id="loadNbaPlayoffsMore" type="button" class="btn btn-default btn-sm hidebtn btnnbaplayoffs loadMoreBtn" data-pointer="0" data-loading-text="Loading ... <span class='glyphicon glyphicon-refresh fa-spin'></span>">Show more <i class="fa fa-angle-double-down"></i></button></center>
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
    <div class="matchmain">
        <div class="infor">
            <div class="time">
                @{{& status}}
            </div>
            <div class="series">
                @{{#isDOTA}}
                    <img src="/images/dota2icon.png" />
                @{{/isDOTA }}
                @{{#isCSGO}}
                    <img src="/images/csgoicon.png" />
                @{{/isCSGO }}  
                @{{#isLOL}}
                    <img src="/images/lol24px.png" />    
                @{{/isLOL }} 
                @{{#isSPORTS}}
                    <img src="/images/sportsicon.png" />    
                @{{/isSPORTS }}                   
                @{{league.name}}
            </div>
        </div>
        @{{#is_current}}
        <div class="match " style="background-image: url({{url('/public_image')}}/@{{league.image}})">
        @{{/is_current}}
        @{{^is_current}}
        <div class="match " style="background-image: url({{url('/public_image')}}/@{{league.image}});">
        @{{/is_current}}

            <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
                <a href="{{url('/')}}/match/@{{id}}">
                    <div class="match-details">
                        @{{#label}}
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center">
                            @{{label}}
                        </div>
                        @{{/label}}
                        <div class="row small-gutter flex-center">
                
                            <div class="col-xs-4 col-sm-3 col-md-3 col-lg-3 no-padding text-center">
                                <div><img class="match-team-logo" src="{{url('/')}}/@{{team_a.image}}" /></div>
                                <div class="team-name hide-mobile">
                                    <span @{{#team_a_winner}} class="text-success" @{{/team_a_winner}}>
                                        @{{team_a.name}}
                                    </span>
                                    @{{#team_a_winner}}
                                        <i
                                            class="fa fa-trophy text-primary"
                                            aria-hidden="true"
                                            title="Winner"
                                        ></i>
                                    @{{/team_a_winner}}
                                </div>
                                <div class="team-name hide-desktop">
                                    <span @{{#team_a_winner}} class="text-success" @{{/team_a_winner}}>
                                        @{{team_a.shortname}}
                                    </span>                                    
                                    @{{#team_a_winner}}
                                        <i
                                            class="fa fa-trophy text-primary"
                                            aria-hidden="true"
                                            title="Winner"
                                        ></i>
                                    @{{/team_a_winner}}                                    
                                </div>  
                                <button class="btn btn-warning team-percentage-btn btn-sm w-100 hide-desktop">@{{teamawin_percentage}}%</button>                                          
                            </div>
                            <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 no-padding text-left bet-percentage hide-mobile">
                                
                                <button class="btn btn-warning team-percentage-btn btn-sm w-100">@{{teamawin_percentage}}%</button>
                            </div>

                            <div class="col-xs-4 col-sm-2 col-md-2 col-lg-2 no-padding text-center">
                                
                                <div class="match-counter-container">@{{best_of}}</div>
                                @{{#team_c}}
                                    <div class="hide-mobile">
                                        <div class="no-padding text-center bet-percentage">
                                            <button class="btn btn-warning team-percentage-btn btn-sm w-100">@{{teamcwin_percentage}}%</button>
                                        </div>
                                        <div class="team-name draw-text">
                                            <span @{{#team_c_winner}} class="text-success" @{{/team_c_winner}}>
                                                DRAW
                                            </span>
                                            @{{#team_c_winner}}
                                                <i
                                                    class="fa fa-trophy text-primary"
                                                    aria-hidden="true"
                                                    title="Winner"
                                                ></i>
                                            @{{/team_c_winner}}                                           
                                        </div>
                                    </div>
                                
                                    <div class="hide-desktop">
                                        <div class="team-name draw-text">
                                            <span @{{#team_c_winner}} class="text-success" @{{/team_c_winner}}>
                                                DRAW
                                            </span>
                                            @{{#team_c_winner}}
                                                <i
                                                    class="fa fa-trophy text-primary"
                                                    aria-hidden="true"
                                                    title="Winner"
                                                ></i>
                                            @{{/team_c_winner}}                                                
                                        </div>
                                        <div class="no-padding text-center bet-percentage">
                                            <button class="btn btn-warning team-percentage-btn btn-sm w-100">@{{teamcwin_percentage}}%</button>
                                        </div>
                                    </div>                                                
                                @{{/team_c}}
                            </div>

                            <div class="col-xs-4 col-sm-2 col-md-2 col-lg-2 no-padding text-right bet-percentage hide-mobile">
                                <button class="btn btn-warning team-percentage-btn btn-sm w-100">@{{teambwin_percentage}}%</button>
                            </div>         
                            

                            <div class="col-xs-4 col-sm-3 col-md-3 col-lg-3 no-padding text-center">                     
                            
                                <div><img class="match-team-logo" src="{{url('/')}}/@{{team_b.image}}" /></div>
                                <div class="team-name hide-mobile">
                                    <span @{{#team_b_winner}} class="text-success" @{{/team_b_winner}}>
                                        @{{team_b.name}}
                                    </span>
                                    @{{#team_b_winner}}
                                        <i
                                            class="fa fa-trophy text-primary"
                                            aria-hidden="true"
                                            title="Winner"
                                        ></i>
                                    @{{/team_b_winner}}                                    
                                </div>
                                <div class="team-name hide-desktop">
                                    <span @{{#team_b_winner}} class="text-success" @{{/team_b_winner}}>
                                        @{{team_b.shortname}}
                                    </span>
                                    @{{#team_b_winner}}
                                        <i
                                            class="fa fa-trophy text-primary"
                                            aria-hidden="true"
                                            title="Winner"
                                        ></i>
                                    @{{/team_b_winner}}                                         
                                </div>  
                                <button class="btn btn-warning team-percentage-btn btn-sm w-100 hide-desktop">@{{teambwin_percentage}}%</button>    
                            </div>   
                        </div>                                                                  
                    </div>
                </a>
            </div>



        </div>
    </div>
</script>
<!-- Matches template Ongoing and Open -->
<script id="matches" type="text/template">
    <div class="matchmain">
        <div class="infor">
            <div class="time">
                    @{{#ongoing}}
                        <span style="color: #72A326; text-shadow: 1px 1px 0px #4A7010; font-weight: bold; font-size: 16px">&nbsp;LIVE</span>
                    @{{/ongoing}}
                    @{{#open}}
                        <strong class="match_countdown" data-schedule="@{{formatted_schedule}}">
                        @{{formatted_schedule}}</strong>
                    @{{/open}}
                </div>
                        
            <div class="series">
                @{{#isDOTA}}
                    <img src="/images/dota2icon.png" />
                @{{/isDOTA }}
                @{{#isCSGO}}
                    <img src="/images/csgoicon.png" />
                @{{/isCSGO }}  
                @{{#isLOL}}
                    <img src="/images/lol24px.png" />    
                @{{/isLOL }} 
                @{{#isSPORTS}}
                    <img src="/images/sportsicon.png" />    
                @{{/isSPORTS }}                   
                @{{league.name}}
            </div>
        </div>
            @{{#is_current}}
            <div class="match " style="background-image: url({{url('/public_image')}}/@{{league.image}})">
            @{{/is_current}}
            @{{^is_current}}
            <div class="match " style="background-image: url({{url('/public_image')}}/@{{league.image}})">
            @{{/is_current}}
            <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
                <a href="{{url('/')}}/match/@{{id}}">
                    <div class="match-details">
                        @{{#label}}
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center">
                            @{{label}}
                        </div>
                        @{{/label}}
                        <div class="row small-gutter flex-center">
                            <div class="col-xs-4 col-sm-3 col-md-3 col-lg-3 no-padding text-center">
                                
                                <div><img class="match-team-logo" src="{{url('/')}}/@{{team_a.image}}" /></div>
                                <div class="team-name hide-mobile">
                                    @{{team_a.name}}
                                </div>
                                <div class="team-name hide-desktop">
                                    @{{team_a.shortname}}
                                </div>  
                                <button class="btn btn-warning team-percentage-btn btn-sm w-100 hide-desktop">@{{teama_percentage}}%</button>                                          
                            </div>
                            <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 no-padding text-left bet-percentage hide-mobile">
                                
                                <button class="btn btn-warning team-percentage-btn btn-sm w-100">@{{teama_percentage}}%</button>
                            </div>

                            <div class="col-xs-4 col-sm-2 col-md-2 col-lg-2 no-padding text-center">
                                
                                <div class="match-counter-container">@{{best_of}}</div>
                                @{{#team_c}}
                                    <div class="hide-mobile">
                                        <div class="no-padding text-center bet-percentage">
                                            <button class="btn btn-warning team-percentage-btn btn-sm w-100">@{{teamc_percentage}}%</button>
                                        </div>
                                        <div class="team-name draw-text">DRAW</div>
                                    </div>
                                
                                    <div class="hide-desktop">
                                        <div class="team-name draw-text">DRAW</div>
                                        <div class="no-padding text-center bet-percentage">
                                            <button class="btn btn-warning team-percentage-btn btn-sm w-100">@{{teamc_percentage}}%</button>
                                        </div>
                                    </div>                                                
                                @{{/team_c}}
                            </div>

                            <div class="col-xs-4 col-sm-2 col-md-2 col-lg-2 no-padding text-right bet-percentage hide-mobile">
                                <button class="btn btn-warning team-percentage-btn btn-sm w-100">@{{teamb_percentage}}%</button>
                            </div>                                                                                                                        
                            <div class="col-xs-4 col-sm-3 col-md-3 col-lg-3 no-padding text-center">
                            
                                <div><img class="match-team-logo" src="{{url('/')}}/@{{team_b.image}}" /></div>
                                <div class="team-name hide-mobile">
                                    @{{team_b.name}}
                                </div>
                                <div class="team-name hide-desktop">
                                    @{{team_b.shortname}}
                                </div>  
                                <button class="btn btn-warning team-percentage-btn btn-sm w-100 hide-desktop">@{{teamb_percentage}}%</button>    
                            </div>   
                        </div>                                                                  
                    </div>
                </a>
            </div>
        </div>
    </div>
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
        $(".btnall").attr('style','display: block !important');
        $(".btndota").attr('style','display: none !important');
        $(".btncsgo").attr('style','display: none !important');
        $(".btnsports").attr('style','display: none !important');
        $(".btnlol").attr('style','display: none !important');
        $(".btnnbaplayoffs").attr('style','display: none !important');
    });
    $(".dota-match-button").click(function(){
        $(".btnall").attr('style','display: none !important');
        $(".btndota").attr('style','display: block !important');
        $(".btncsgo").attr('style','display: none !important');
        $(".btnsports").attr('style','display: none !important');
        $(".btnlol").attr('style','display: none !important');
        $(".btnnbaplayoffs").attr('style','display: none !important');        
    });
    $(".csgo-match-button").click(function(){
        $(".btnall").attr('style','display: none !important');
        $(".btncsgo").attr('style','display: block !important');
        $(".btndota").attr('style','display: none !important');
        $(".btnsports").attr('style','display: none !important');
        $(".btnlol").attr('style','display: none !important');
        $(".btnnbaplayoffs").attr('style','display: none !important');        
    });
    $(".sports-match-button").click(function(){
        $(".btnall").attr('style','display: none !important');
        $(".btnsports").attr('style','display: block !important');
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
        $(".btnlol").attr('style','display: block !important');
        $(".btnnbaplayoffs").attr('style','display: none !important');    
    });    
    $(".nbaplayoffs-match-button").click(function(){
        $(".btnall").attr('style','display: none !important');
        $(".btndota").attr('style','display: none !important');
        $(".btncsgo").attr('style','display: none !important');
        $(".btnsports").attr('style','display: none !important');
        $(".btnlol").attr('style','display: none !important');
        $(".btnnbaplayoffs").attr('style','display: block !important');    
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
                element.innerHTML = (days > 0 ? days + "d " : "") + (hours > 0 ? hours + "h " : "") + (minutes > 0 ? minutes + "m " : "") + seconds + "s from now";

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
    });
//    $(window).scroll(function() {
//        if($(window).scrollTop() == $(document).height() - $(window).height()) {
//            console.log('loading more items...');
//        }
//    });
</script>
@endsection
