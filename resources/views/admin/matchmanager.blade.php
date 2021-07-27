@extends('layouts.main')

@section('styles')
<style>
    .nav-tabs {
        position: initial!important; 
    }
    .nav-tabs > li.active > a, .nav-tabs > li.active > a:hover, .nav-tabs > li.active > a:focus {
        background: #414141!important; 
        color: #f39c12 !important;
    }
    .nav > li > a {
        padding: 10px 15px!important;
    }
    .nav-tabs a{
        color: #428bca !important;
    }
    .nav-tabs {
        border-bottom: 1px solid #000;
    }

    .error-label {
        color: #a94442;
        display: none;
        padding: 3px;
    }
    
    .has-error .error-label {
        display: block;
    }
    
    .sweet-overlay {
        z-index: 2000!important;
    }
    
    .team_image_src {
        max-width:100px;
        height:100px;
        margin-top:10px;
    }
    
    .radio {
        padding-left: 20px; }
    .radio label {
        display: inline-block;
        position: relative;
        padding-left: 5px; }
    .radio label::before {
        content: "";
        display: inline-block;
        position: absolute;
        width: 17px;
        height: 17px;
        left: 0;
        margin-left: -20px;
        border: 1px solid #cccccc;
        border-radius: 50%;
        background-color: #fff;
        -webkit-transition: border 0.15s ease-in-out;
        -o-transition: border 0.15s ease-in-out;
        transition: border 0.15s ease-in-out; }
    .radio label::after {
        display: inline-block;
        position: absolute;
        content: " ";
        width: 11px;
        height: 11px;
        left: 3px;
        top: 3px;
        margin-left: -20px;
        border-radius: 50%;
        background-color: #555555;
        -webkit-transform: scale(0, 0);
        -ms-transform: scale(0, 0);
        -o-transform: scale(0, 0);
        transform: scale(0, 0);
        -webkit-transition: -webkit-transform 0.1s cubic-bezier(0.8, -0.33, 0.2, 1.33);
        -moz-transition: -moz-transform 0.1s cubic-bezier(0.8, -0.33, 0.2, 1.33);
        -o-transition: -o-transform 0.1s cubic-bezier(0.8, -0.33, 0.2, 1.33);
        transition: transform 0.1s cubic-bezier(0.8, -0.33, 0.2, 1.33); }
    .radio input[type="radio"] {
        opacity: 0; }
    .radio input[type="radio"]:focus + label::before {
        outline: thin dotted;
        outline: 5px auto -webkit-focus-ring-color;
        outline-offset: -2px; }
    .radio input[type="radio"]:checked + label::after {
        -webkit-transform: scale(1, 1);
        -ms-transform: scale(1, 1);
        -o-transform: scale(1, 1);
        transform: scale(1, 1); }
    .radio input[type="radio"]:disabled + label {
        opacity: 0.65; }
    .radio input[type="radio"]:disabled + label::before {
        cursor: not-allowed; }
    .radio.radio-inline {
        margin-top: 0; }

    .radio-primary input[type="radio"] + label::after {
        background-color: #428bca; }
    .radio-primary input[type="radio"]:checked + label::before {
        border-color: #428bca; }
    .radio-primary input[type="radio"]:checked + label::after {
        background-color: #428bca; }

    .radio-danger input[type="radio"] + label::after {
        background-color: #d9534f; }
    .radio-danger input[type="radio"]:checked + label::before {
        border-color: #d9534f; }
    .radio-danger input[type="radio"]:checked + label::after {
        background-color: #d9534f; }

    .radio-info input[type="radio"] + label::after {
        background-color: #5bc0de; }
    .radio-info input[type="radio"]:checked + label::before {
        border-color: #5bc0de; }
    .radio-info input[type="radio"]:checked + label::after {
        background-color: #5bc0de; }

    .radio-warning input[type="radio"] + label::after {
        background-color: #f0ad4e; }
    .radio-warning input[type="radio"]:checked + label::before {
        border-color: #f0ad4e; }
    .radio-warning input[type="radio"]:checked + label::after {
        background-color: #f0ad4e; }

    .radio-success input[type="radio"] + label::after {
        background-color: #5cb85c; }
    .radio-success input[type="radio"]:checked + label::before {
        border-color: #5cb85c; }
    .radio-success input[type="radio"]:checked + label::after {
        background-color: #5cb85c; }
    
/*    .selectize-control.teams .selectize-input > div {
            padding: 1px 10px;
            font-size: 13px;
            font-weight: normal;
            -webkit-font-smoothing: auto;
            color: #f7fbff;
            text-shadow: 0 1px 0 rgba(8,32,65,0.2);
            background: #2183f5;
            background: -moz-linear-gradient(top, #2183f5 0%, #1d77f3 100%);
            background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#2183f5), color-stop(100%,#1d77f3));
            background: -webkit-linear-gradient(top,  #2183f5 0%,#1d77f3 100%);
            background: -o-linear-gradient(top,  #2183f5 0%,#1d77f3 100%);
            background: -ms-linear-gradient(top,  #2183f5 0%,#1d77f3 100%);
            background: linear-gradient(to bottom,  #2183f5 0%,#1d77f3 100%);
            filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#2183f5', endColorstr='#1d77f3',GradientType=0 );
            border: 1px solid #0f65d2;
            -webkit-border-radius: 999px;
            -moz-border-radius: 999px;
            border-radius: 999px;
            -webkit-box-shadow: 0 1px 1px rgba(0,0,0,0.15);
            -moz-box-shadow: 0 1px 1px rgba(0,0,0,0.15);
            box-shadow: 0 1px 1px rgba(0,0,0,0.15);
    }
    .selectize-control.teams .selectize-input > div.active {
            background: #0059c7;
            background: -moz-linear-gradient(top, #0059c7 0%, #0051c1 100%);
            background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#0059c7), color-stop(100%,#0051c1));
            background: -webkit-linear-gradient(top,  #0059c7 0%,#0051c1 100%);
            background: -o-linear-gradient(top,  #0059c7 0%,#0051c1 100%);
            background: -ms-linear-gradient(top,  #0059c7 0%,#0051c1 100%);
            background: linear-gradient(to bottom,  #0059c7 0%,#0051c1 100%);
            filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#0059c7', endColorstr='#0051c1',GradientType=0 );
            border-color: #0051c1;
    }*/

    .selectize-control.teams .selectize-dropdown [data-selectable] {
            border-bottom: 1px solid rgba(0,0,0,0.05);
            height: 60px;
            position: relative;
            -webkit-box-sizing: content-box;
            box-sizing: content-box;
            padding: 10px 10px 10px 85px;
    }
    .selectize-control.teams .selectize-dropdown [data-selectable]:last-child {
            border-bottom: 0 none;
    }
    .selectize-control.teams .selectize-dropdown .by {
            font-size: 11px;
            opacity: 0.8;
    }
    .selectize-control.teams .selectize-dropdown .by::before {
            content: 'by ';
    }
    .selectize-control.teams .selectize-dropdown .name {
            font-weight: bold;
            margin-right: 5px;
    }
    .selectize-control.teams .selectize-dropdown .description {
            font-size: 12px;
            color: #a0a0a0;
    }
    .selectize-control.teams .selectize-dropdown .actors,
    .selectize-control.teams .selectize-dropdown .description,
    .selectize-control.teams .selectize-dropdown .title {
            display: block;
            white-space: nowrap;
            width: 100%;
            overflow: hidden;
            text-overflow: ellipsis;
    }
    .selectize-control.teams .selectize-dropdown .actors {
            font-size: 10px;
            color: #a0a0a0;
    }
    .selectize-control.teams .selectize-dropdown .actors span {
            color: #606060;
    }
    .selectize-control.teams .selectize-dropdown img {
            height: 60px;
            left: 10px;
            position: absolute;
            border-radius: 3px;
            background: rgba(0,0,0,0.04);
    }
    .selectize-control.teams .selectize-dropdown .meta {
            list-style: none;
            margin: 0;
            padding: 0;
            font-size: 10px;
    }
    .selectize-control.teams .selectize-dropdown .meta li {
            margin: 0;
            padding: 0;
            display: inline;
            margin-right: 10px;
    }
    .selectize-control.teams .selectize-dropdown .meta li span {
            font-weight: bold;
    }
    .selectize-control.teams::before {
            -moz-transition: opacity 0.2s;
            -webkit-transition: opacity 0.2s;
            transition: opacity 0.2s;
            content: ' ';
            z-index: 2;
            position: absolute;
            display: block;
            top: 12px;
            right: 34px;
            width: 16px;
            height: 16px;
            background: url(images/spinner.gif);
            background-size: 16px 16px;
            opacity: 0;
    }
    .selectize-control.teams.loading::before {
            opacity: 0.4;
    }
    .input-group-addon.submatch input[type="checkbox"],
    .input-group-addon.submatch input[type="radio"] {
        display: none;
    }
    /*Set Match Score button*/
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

    .data-tables-btn{
        margin-left: 10px;
    }    
</style>
<link rel="stylesheet" href="{{ asset('css/datatables.min.css') }}">
<link rel="stylesheet" href="{{ asset('/bower_components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css') }}"/>
<link rel="stylesheet" href="{{ asset('bower_components/bootstrap-sweetalert/dist/sweetalert.css') }}">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.1/css/responsive.dataTables.min.css">
<link rel="stylesheet" href="{{ asset('css/selectize.bootstrap3.css') }}">
@endsection

@section('content')
<div class="main-container dark-grey">
    <div class="m-container1" style="width: 98% !important;">
        <div class="main-ct">
            <div class="title2">MATCH Manager</div>
            <div class="clearfix"></div>

            <div class="blk-1">
                <div class="col-md-12">

                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#matches" aria-controls="home" role="tab" data-toggle="tab">Matches</a></li>
                        <li role="presentation"><a href="#leagues" aria-controls="cashout" role="tab" data-toggle="tab">Leagues</a></li>
                        <li role="presentation"><a href="#teams" aria-controls="teams" role="tab" data-toggle="tab">Teams</a></li>
                        <li role="presentation"><a href="#game_types" aria-controls="game_types" role="tab" data-toggle="tab">Game Types</a></li>
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade in active" id="matches">
                            <div class="tab-content" style="margin-top: 20px">
                                <table id="matches-table" class="table table-striped" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Schedule</th>
                                            <th>Match ID</th>
                                            <th>Name</th>
                                            <th>Teams</th>
                                            <th>Best Of</th>
                                            <th>League</th>
                                            <th>Match Winner</th>
                                            <th>Fee %</th>
                                            <th>Status</th>
                                            <th style="min-width: 125px">Actions</th>
                                        </tr>
                                    </thead>
                                </table>
                                <button class="btn btn-primary showCreateMatchModalBtn" data-toggle="modal" data-target="#createMatchesModal">Create Matches</button>
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane fade in" id="leagues">
                            <div class="tab-content" style="margin-top: 20px">
                                <table id="leagues-table" class="table table-striped" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Type</th>
                                            <th>Expired</th>
                                            <th>Active League</th>
                                            <th>Betting Status</th>
                                            <th>Teams</th>
                                            <th>Options</th>
                                        </tr>
                                    </thead>
                                </table>
                                <button class="btn btn-primary" data-toggle="modal" data-target="#createLeaguesModal">Add More Leagues</button>
                                <arrange-home-page-outrights-display></arrange-home-page-outrights-display>
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane fade in" id="teams">
                            <div class="tab-content" style="margin-top: 20px">
                                <table id="teams-table" class="table table-striped" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Favorite</th>
                                            <th>Type</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                </table>
                                <button style="margin-top: 20px" class="btn btn-primary" data-toggle="modal" data-target="#createTeamsModal">Add More Teams</button>
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane fade in" id="game_types">
                            <div class="tab-content" style="margin-top: 20px">
                                <table id="gametypes-table" class="table table-striped" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Description</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                </table>
                                <button style="margin-top: 20px" class="btn btn-primary" data-toggle="modal" data-target="#createGameTypesModal">Add New Types</button>
                                
                                @if(!App::environment('prod'))
                                <div style="margin-top: 20px">
                                    <h3>Sub-Match Templates</h3>
                                    <table id="submatchtemplate-table" class="table table-striped" width="100%">
                                        <thead>
                                            <tr>
                                                <th>Type</th>
                                                <th>Best Of</th>
                                                <th>Sub Matches</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                    </table>
                                    <button style="margin-top: 20px" class="btn btn-primary" data-toggle="modal" data-target="#createMatchTemplateModal">Add New Template</button>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="createMatchesModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Create new Match</h4>
            </div>
            <div class="modal-body" >
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"><a href="#main_match" role="tab" data-toggle="tab">Main Match</a></li>
                    <li role="presentation"><a href="#sub_matches" role="tab" data-toggle="tab">Sub Matches</a></li>
                </ul>
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane fade in active" id="main_match">
                        <br/>
                        <form id="matchesForm" class="form-horizontal" autocomplete="off">
                            <input type="hidden" name='match_id' value='' />
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Name: </label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="name" placeholder="Name this match (optional)"/>
                                    <span class="error-label"></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">League: </label>
                                <div class="col-sm-9">
                                    <select name='league_id' class='form-control' id="create_match_league_id">
                                    </select>
                                    <span class="error-label"></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Schedule: </label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control datetime_sched" name="schedule" placeholder="Select schedule of Match" required/>
                                    <span class="error-label"></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Match count: </label>
                                <div class="col-sm-9">
                                    <select name='best_of' class='form-control'>
                                        <option value="1">BO1</option>
                                        <option value="2">BO2</option>
                                        <option value="3">BO3</option>
                                        <option value="5">BO5</option>
                                        <option value="7">BO7</option>
                                    </select>
                                    <span class="error-label"></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Label: </label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="label" placeholder="Add match note or label"/>
                                    <span class="error-label"></span>
                                </div>
                            </div>

                            <div class="form-group" style="display: none;">
                                <label class="col-sm-3 control-label">Enable Initial Odds: </label>
                                <div class="col-sm-9" style="text-align:center">
                                    <select id="is_initial_odds_enabled" name='is_initial_odds_enabled' class='form-control' style="width: 130px">
                                        <option value="1">Enable</option>
                                        <option value="0" selected>Disable</option>
                                    </select>
                                </div>
                            </div> 

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Enable "Draw" Betting: </label>
                                <div class="col-sm-9" style="text-align:center">
                                    <select id="draw_betting_enabled" name='draw_betting_enabled' class='form-control' style="width: 130px">
                                        <option value="1">Enable</option>
                                        <option value="0" selected>Disable</option>
                                    </select>
                                </div>
                            </div>                             

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Teams: </label>
                                <div class="col-sm-9" style="text-align:center">
                                    <div class='col-sm-5' style="padding-left: 0">
                                        <select name='team_a' class='form-control input-sm team_selection'>
                                            <option value=''>Select team</option>
                                            {{-- @foreach($teams as $team)
                                            <option value='{{$team->id}}' data-name='{{$team->name}}' data-image='{{$team->image}}'>{{$team->name}}</option>
                                            @endforeach --}}
                                        </select>
                                        <div class="team_image" style="padding-top: 10px"></div>
                                        <span class="error-label"></span>
                                    </div>
                                    <div class='col-sm-2'><strong>VS</strong></div>
                                    <div class='col-sm-5'>
                                        <select name='team_b' class='form-control input-sm team_selection'> 
                                            <option value=''>Select team</option>
                                            {{-- @foreach($teams as $team)
                                            <option value='{{$team->id}}' data-name='{{$team->name}}' data-image='{{$team->image}}'>{{$team->name}}</option>
                                            @endforeach --}}
                                        </select>
                                        <div class="team_image" style="padding-top: 10px"></div>
                                        <span class="error-label"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group" style="display: none;">
                                <label class="col-sm-3 control-label">Team Initial Odds: </label>
                                <div class="col-sm-9" style="text-align:center">
                                    <div class='col-sm-5' style="padding-left: 0">
                                        <input type="number" step="1" min="0" max="100" class="form-control team_initial_odds" name="team_a_initial_odd" placeholder="For Team A" value="50" disabled/>
                                        <span class="error-label"></span>
                                    </div>
                                    <div class='col-sm-2'><strong>VS</strong></div>
                                    <div class='col-sm-5'>
                                        <input type="number" step="1" min="0" max="100" class="form-control team_initial_odds" name="team_b_initial_odd"  placeholder="For Team B" value="50" disabled/>
                                        <span class="error-label"></span>
                                    </div>
                                </div>
                            </div>   

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Fee %: </label>
                                <div class="col-sm-9">
                                    <select id="match-fee" name='fee' class='form-control' style="width: 125px">
                                        @for($x = 0; $x < 50; $x++)
                                        <option value="{{number_format($x, 1)}}">{{$x}}%</option>

                                        @if($x == 0.0)
                                        <option value="0.5">0.5%</option>
                                        @endif

                                        @if($x == 1.0)
                                        <option value="1.5">1.5%</option>
                                        @endif

                                        @if($x == 2.0)
                                        <option value="2.5">2.5%</option>
                                        @endif
                                        @endfor
                                    </select>
                                    <span class="error-label"></span>
                                </div>
                            </div>
                            <div class="form-group" style="display: none">
                                <label class="col-sm-3 control-label">Status: </label>
                                <div class="col-sm-9">
                                    <select name='status' class='form-control' style="width: 130px">
                                        <option value='open'>open</option>
                                        <option value='ongoing'>ongoing</option>
                                        <option value='cancelled'>cancelled</option>
                                    </select>
                                    <span class="error-label"></span>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div role="tabpanel" class="tab-pane fade in" id="sub_matches">
                
                        <div class="row">
                            <div class="col-lg-12" style="padding-top: 20px; padding-bottom: 10px;">
                                <center>
                                    <span class="h3" style="margin-right: 10px;">
                                        <span id="submatch-teama-name-display"></span>
                                        <span class="h5 text-muted">( Team A )</span>
                                    </span> 
                                    <span class="h4"> - vs -</span>
                                    <span class="h3" style="margin-left: 10px;">
                                        <span id="submatch-teamb-name-display"></span> 
                                        <span class="h5 text-muted">( Team B ) </span>
                                    </span>
                                </center>
                            </div>
                            <div class="col-lg-12" style="max-height: 600px; overflow-y: auto">
                                <div class="input-group" style="padding-top: 10px">
                                    <span class="input-group-addon submatch">
                                        <input type="checkbox" checked data-matchtype="main">
                                    </span>
                                    <input type="text" class="form-control" value="Main Match Winner" disabled>
                                </div>
                                <button id="addSubMatchBtn" type="button" class="btn btn-primary btn-sm" style="margin-top: 10px" data-overunder="0" data-overundervalue="0"><i class="fa fa-plus" ></i> Add More</button>
                                <button id="addSubMatchBtn" type="button" class="btn btn-primary btn-sm" style="margin-top: 10px"  data-overunder="1" data-overundervalue="0"><i class="fa fa-plus"></i> Add Over/Under</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button id="createMatchBtn" type="button" class="btn btn-primary" data-edit-text="Update">Create</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<div id="setScoreModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body" >
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"><a href="#main_match" role="tab" data-toggle="tab">Main Match</a></li>
                </ul>
                <!-- Modal for Set Match Score button -->
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane fade in active" id="main_match">
                        <br/>
                        <form id="matchesForm2" class="form-horizontal" autocomplete="off">
                            <div class="row">
                                <input type="hidden" name='match_id' value='' />
                                <div class="form-group col-md-5" style="text-align: center;">
                                    <div class='team_aname' style="font-size: 15px; font-weight: bold;">Team A name</div>
                                    <div class="team_aimage" style="padding-top: 10px"></div>
                                    <input type ="text" name='teama_score' class='form-control score' style="width: 45%">
                                </div>
                                <div class="col-md-2">
                                   <div class="row"  style="text-align: center;">
                                    <label style="font-size: 25px;">VS</label>
                                   </div>
                                </div>
                                <div class="form-group col-md-5" style="text-align: center;">
                                    <div class='team_bname' style="font-size: 15px; font-weight: bold;">Team B name</div>
                                    <div class="team_bimage" style="padding-top: 10px"></div>
                                    <input type="text" name='teamb_score' class='form-control score' style="width: 45%">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button id="setScoreBtn" type="button" class="btn btn-primary" data-edit-text="Update">Create</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<div id="settleMatchesModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Settle Match</h4>
            </div>
            <div class="modal-body" >
                <form id="settleMatchesForm" class="form-horizontal">
                    <div class="col-md-12 matchname" style="text-align: center; font-weight: bold; padding-bottom: 20px"></div>
                    <input type='hidden' name='match_id' />
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Teams: </label>
                        <div class="col-sm-9" style="text-align:center">
                            <div class='col-sm-4' style="padding-left: 0">
                                <div class='teama_name' style="width: 130px">
                                    asdsad
                                </div>
                                <div class="teama_image" style="padding-top: 10px"></div>
                            </div>
                            <div class='col-sm-2'><strong>VS</strong></div>
                            <div class='col-sm-4'>
                                <div class='teamb_name' style="width: 130px"> 
                                    sadsad
                                </div>
                                <div class="teamb_image" style="padding-top: 10px"></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Choose Winner: </label>
                        <div class="col-sm-9">
                            <select name='team_winner' class='form-control team_selection' style="width: 130px">
                                <option value=''>Select team</option>
                            </select>
                            <div class="team_image" style="padding-top: 10px"></div>
                            <span class="error-label"></span>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button id="settleMatchesBtn" type="button" class="btn btn-primary">Settle Match</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<div id="createLeaguesModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add Leagues</h4>
            </div>
            <div class="modal-body" >
                <form id="leaguesForm" action="{{ route('setleagues') }}" enctype="multipart/form-data" method="POST">
                    
                    <div class="alert alert-danger print-error-msg" style="display:none">
                        <ul></ul>
                    </div>

                    <input type="hidden" name="league_id" />

                    <div class="form-group">
                        <label>Name:</label>
                        <input type="text" name="name" class="form-control" placeholder="League name">
                    </div>
                    
                    <div class="form-group">
                        <label>Description:</label>
                        <textarea name="description" class="form-control" placeholder="League description"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>Type:</label>
                        <select class="form-control" name="type" id="create-league-game-type">
                            @foreach($types as $type)
                            <option value="{{$type->name}}">{{$type->description}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Background Image:</label>
                        <input type="file" name="image" class="form-control">
                    </div>
                    
                    <div class="form-group">
                        <label>Bottom Image:</label>
                        <input type="file" name="bottom_image" class="form-control">
                    </div>
                    
                    <div class="form-group">
                        <div class="control-group" id="select-teams-create-league-container">
                            <label for="select-teams">Teams:</label>
                            <select id="select-teams" class="teams" name="teams[]" placeholder="Select teams..."></select>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="form-group col-xs-6 col-md-4">
                            <label>Active League?:</label>
                            <div style="padding-top: 5px">
                                <div class="radio radio-primary radio-inline">
                                    <input type="radio" id="active_no" value="0" name="status" checked="">
                                    <label for="active_no"> No </label>
                                </div>
                                <div class="radio radio-primary radio-inline">
                                    <input type="radio" id="active_yes" value="1" name="status">
                                    <label for="active_yes"> Yes </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-xs-6 col-md-4">
                            <label>Betting Status:</label>
                            <div style="padding-top: 5px">
                                <div class="radio radio-primary radio-inline">
                                    <input type="radio" id="enable_betting" value="1" name="betting_status">
                                    <label for="enable_betting"> Active </label>
                                </div>
                                <div class="radio radio-primary radio-inline">
                                    <input type="radio" id="disable_betting" value="0" name="betting_status" checked="">
                                    <label for="disable_betting"> Inactive </label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group col-xs-6 col-md-4">
                            <label>Expired:</label>
                            <div style="padding-top: 5px">
                                <div class="radio radio-primary radio-inline">
                                    <input type="radio" id="set_league_expired" value="1" name="expired">
                                    <label for="set_league_expired"> Yes </label>
                                </div>
                                <div class="radio radio-primary radio-inline">
                                    <input type="radio" id="set_league_expired_no" value="0" name="expired" checked="">
                                    <label for="set_league_expired_no"> No </label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group col-xs-6 col-md-5">
                            <label>Betting Fee:</label>
                            <select name='betting_fee' class='form-control' style="width: 130px">
                                @for($x = 0; $x < 50; $x++)
                                    <option value="{{number_format($x, 1)}}">{{$x}}%</option>

                                    @if($x == 0.0)
                                    <option value="0.5">0.5%</option>
                                    @endif

                                    @if($x == 1.0)
                                    <option value="1.5">1.5%</option>
                                    @endif

                                    @if($x == 2.0)
                                    <option value="2.5">2.5%</option>
                                    @endif
                                @endfor
                            </select>
                            <span class="error-label"></span>
                        </div>
                        
                        <div class="form-group col-xs-6 col-md-5">
                            <label>Favorites min. bet:</label>
                            <input type="text" name="favorites_minimum" class="form-control" placeholder="Minimum bets for Favorites">
                            <span class="error-label"></span>
                        </div>
                    </div>
                    
                </form>
            </div>
            <div class="modal-footer">
                <button id="createLeagueBtn" type="button" class="btn btn-success" data-edit-text="Update">Add League</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<div id="showTeamsModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">League Teams</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 teamsHolder">
                        
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="display: none">
                <div class="pull-left form-inline">
                    <label>Declare Champion:</label>
                    <select name="champion" class="form-control">
                        <option value="">None</option>
                    </select>
                </div>
                <button id="setLeagueChampionBtn" type="button" class="btn btn-primary" data-edit-text="Update">Set League</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<div id="createTeamsModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add Teams</h4>
            </div>
            <div class="modal-body" >
                <form id="teamsForm" enctype="multipart/form-data" method="POST">
                    <input type="hidden" name="team_id" />
                    <div class="row">
                        <div class="form-group col-xs-6">
                            <label>Name:</label>
                            <input type="text" name="name" class="form-control" placeholder="Team name">
                            <span class="error-label"></span>
                        </div>

                        <div class="form-group col-xs-6">
                            <label>Short Name:</label>
                            <input type="text" name="shortname" class="form-control" placeholder="Short name">
                            <span class="error-label"></span>
                        </div>

                        <div class="form-group col-xs-6">
                            <label>Type:</label>
                            <select name="type" class="form-control">
                                @foreach($types as $type)
                                <option value="{{$type->name}}">{{$type->description}}</option>
                                @endforeach
                            </select>
                            <span class="error-label"></span>
                        </div>

                        <div class="form-group col-xs-12">
                            <label>Image:</label>
                            <input type="file" name="image" class="form-control">
                            <img class="team_image_src" src="{{ asset('images/default_team_image.png') }}" alt="your image" />
                            <span class="error-label"></span>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button id="createTeamBtn" type="button" class="btn btn-primary" data-edit-text="Update">Add Team</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<div id="createGameTypesModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add Game Types</h4>
            </div>
            <div class="modal-body" >
                <form id="gameTypeForm">
                    <input type="hidden" name="type_id" />
                    <div class="row">
                        <div class="form-group col-xs-6">
                            <label>Name:</label>
                            <input type="text" name="name" class="form-control" placeholder="Game Type">
                            <span class="error-label"></span>
                        </div>

                        <div class="form-group col-xs-6">
                            <label>Description:</label>
                            <input type="text" name="description" class="form-control" placeholder="Description">
                            <span class="error-label"></span>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button id="createGameTypeBtn" type="button" class="btn btn-primary" data-edit-text="Update">Add Type</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<div id="extendMatchModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Extend Match Schedule</h4>
            </div>
            <div class="modal-body" >
                <form id="extendMatchForm">
                    <input type="hidden" name="match_id" />
                    <div class="row">
                        <div class="form-group col-xs-12">
                            <label>Match:</label>
                            <div class="match_name">Match name</div>
                        </div>
                        <div class="form-group col-xs-6">
                            <label>Extension time:</label>
                            <select class="form-control" name="ext_time">
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
                <button id="extendMatchTimeBtn" type="button" class="btn btn-warning">Extend</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script type="text/javascript" src="{{ asset('js/datatables.min.js')}}"></script>
<script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.1/js/dataTables.responsive.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.6/moment.min.js"></script>
<script type="text/javascript" src="{{ asset('/bower_components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('bower_components/bootstrap-sweetalert/dist/sweetalert.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/selectize.min.js')}}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        initSubmatches();
        $('.datetime_sched').datetimepicker({
            viewMode: 'days',
            minDate : new Date()
        }).on('dp.change', function(e){ $(this).parent().removeClass('has-error'); });

        
        $(document).on('change', '#create_match_league_id', function() {
            console.log($(this).val());
        })

        $(document).on('change', '#matchesForm .team_selection', function() {
            $('#matchesForm').find('.team_selection option').attr('disabled',false);
            $('#matchesForm').find('.team_selection').each(function(){
                var $this = $(this);
                $('#matchesForm').find('.team_selection').not($this).find('option').each(function(){
                    if($(this).attr('value') == $this.val() && $(this).attr('value'))
                        $(this).attr('disabled',true);
                });
            });
            
            if($(this).val()) {
                var url_image = "{{url('/')}}/" + $(this).find(':selected').attr('data-image');
                $(this).next().html('<img src="'+url_image+'" style="width: 110px"/>');
            } else {
                $(this).next().html('');
            }
            
            var game_type = $('#matchesForm :input[name=league_id]').find(':selected').data('type');
            var best_of = parseInt($('#matchesForm :input[name=best_of]').val());
            loadSubMatches(game_type, best_of);
        });
        var leaguesTable = $('#leagues-table').DataTable({
            initComplete : function() {
                var input = $('#leagues-table_filter input').unbind(),
                    self = this.api(),
                    $searchButton = $('<button class="data-tables-btn">')
                            .text('Search')
                            .click(function() {
                                self.search(input.val()).draw();
                            }),
                    $clearButton = $('<button class="data-tables-btn">')
                            .text('Clear')
                            .click(function() {
                                input.val('');
                                $searchButton.click(); 
                            }) 
                $('#leagues-table_filter').append($searchButton, $clearButton);
            },              
            processing: true,
            serverSide:true,
            responsive: true,
            deferLoading: 0,       
            "ajax": "{!! route('showleagues') !!}",
            "columns": [
                {data: "name"},
                {data: "type"},
                {
                    data: "expired",
                    render: function ( data, type, row ) {
                        return data ? '<label class="label label-danger">Yes</label>' : 'No';
                    }
                },
                {
                    data: "status",
                    render: function ( data, type, row ) {
                        return data ? '<label class="label label-success">Active</label>' : 'Inactive';
                    }
                },
                {
                    data: "betting_status",
                    render: function ( data, type, row ) {
                        if(data == -1)
                            return 'Settled: <label class="label label-success">' + row['champion'].name + '</label>';
                        else
                            return data ? '<label class="label label-success">Active</label>' : 'Inactive';
                    }
                },
                {
                    data: "teams",
                    render: function ( data, type, row ) {
                        return data ? '<button type="button" class="btn btn-info btn-xs showLeagueTeams" data-toggle="modal" data-target="#showTeamsModal"><strong>' + data.length + '</strong> team(s) added</button>' : 'None';
                    }
                },
                {
                    data: "betting_status",
                    render: function ( data, type, row ) {
                        if(data != -1)
                            return '<button type="button" class="btn btn-warning btn-xs editLeague" data-toggle="modal" data-target="#createLeaguesModal">Edit</button> ' +
                                '<button type="button" class="btn btn-danger btn-xs delLeague">Delete</button>';
                        else
                            return row.btn_options;
                    }
                }
            ],
        });
        
        var teamsTable = $('#teams-table').DataTable({
            initComplete : function() {
                var input = $('#teams-table_filter input').unbind(),
                    self = this.api(),
                    $searchButton = $('<button class="data-tables-btn">')
                            .text('Search')
                            .click(function() {
                                self.search(input.val()).draw();
                            }),
                    $clearButton = $('<button class="data-tables-btn">')
                            .text('Clear')
                            .click(function() {
                                input.val('');
                                $searchButton.click(); 
                            }) 
                $('#teams-table_filter').append($searchButton, $clearButton);
            },              
            processing: true,
            serverSide:true,
            responsive: true,
            deferLoading: 0,       
            "ajax": "{!! route('get_all_teams') !!}",
            "columns": [
                {
                    data:'name',
                    name:'name',
                    render: function(data,type,row){
                        var image_url = "{{url('')}}/" + row['image'];
                        return data + '<br/><image src="'+ image_url +'" width="60px" />';
                    }
                },
                {
                    data: "is_favorite",
                    render: function(data,type,row){
                        return data ? 'YES' : 'NO';
                    }
                },
                {
                    data:'type',
                    name:'type',
                    render: function(data,type,row){
                        return data;
                    }
                },
                {
                    searchable:false,
                    render: function(data,type,row){
                        return '<button type="button" class="btn btn-warning btn-xs editTeam" data-toggle="modal" data-target="#createTeamsModal">Edit</button> ' +
                                '<button type="button" class="btn btn-danger btn-xs delTeam">Delete</button>';
                    }
                }
            ],
        });
        
        var gametypesTable = $('#gametypes-table').DataTable({
            initComplete : function() {
                var input = $('#gametypes-table_filter input').unbind(),
                    self = this.api(),
                    $searchButton = $('<button class="data-tables-btn">')
                            .text('Search')
                            .click(function() {
                                self.search(input.val()).draw();
                            }),
                    $clearButton = $('<button class="data-tables-btn">')
                            .text('Clear')
                            .click(function() {
                                input.val('');
                                $searchButton.click(); 
                            }) 
                $('#gametypes-table_filter').append($searchButton, $clearButton);
            },
            processing: true,
            serverSide:true,
            responsive: true,
            deferLoading: 0,              
            "ajax": "{!! route('list_game_types') !!}",
            "order": [[ 0, "desc" ]],
            "responsive": true,
            "columns": [
                {data: "name"},
                {data: "description"},
                {
                    data: "id",
                    render: function ( data, type, row ) {
                        return '<button type="button" class="btn btn-warning btn-xs editGameType" data-toggle="modal" data-target="#createGameTypesModal">Edit</button> '
                                +'<button type="button" class="btn btn-danger btn-xs delGameType">Delete</button>';
                    }
                }
            ]
        });
        
        var matchesTable = $('#matches-table').DataTable({
            initComplete : function() {
                var input = $('#matches-table_filter input').unbind(),
                    self = this.api(),
                    $searchButton = $('<button class="data-tables-btn">')
                            .text('Search')
                            .click(function() {
                                self.search(input.val()).draw();
                            }),
                    $clearButton = $('<button class="data-tables-btn">')
                            .text('Clear')
                            .click(function() {
                                input.val('');
                                $searchButton.click(); 
                            }) 
                $('#matches-table_filter').append($searchButton, $clearButton);
            },               
            processing: true,
            serverSide:true,
            responsive: true,
            deferLoading: 0,       
            "ajax": "{!! route('showmatches') !!}",
            "order": [[ 0, "desc" ]],
            "responsive": true,
            "columns": [
                {data: "schedule"},
                {data: "id"},
                {data: "name"},
                {
                    data: "status",
                    render: function ( data, type, row ) {
                        return '<a href="/match/'+row.id+'" target="_blank">Team A: ' + row['team_a'].name + '<br/>' +
                                'Team B: ' + row['team_b'].name + '</a>';
                    }
                },
                {data: "best_of"},
                {data: "league.name"},
                {
                    data: "teamwinner.name",
                    render: function ( data, type, row ) {
                        return data ? data : 'N/A';
                    }
                },
                {
                    data: "fee",
                    render: function ( data, type, row ) {
                        return (data * 100) + '%';
                    }
                },
                {
                   data: "status",
                   render: function ( data, type, row ) {
                       switch(data) {
                           case 'open':
                               return '<strong style="color:green">'+data+'</strong>';
                            case 'ongoing':
                                return '<strong style="color:blue">'+data+'</strong>';
                            case 'settled':
                                return '<span style="color:#3276b1">'+data+'</span>';
                            default:
                                return data;
                        }
                   }
                },
                {
                    data: "status",
                    render: function ( data, type, row ) {
                        switch(data) {
                            case 'open':
                                return '<button type="button" class="btn btn-info btn-xs extendMatch" data-toggle="modal" data-target="#extendMatchModal">Extend</button> '
                                        +'<button type="button" class="btn btn-warning btn-xs editMatch" data-matchdata="" data-toggle="modal" data-target="#createMatchesModal">Edit</button> '
                                        +'<button type="button" class="btn btn-danger btn-xs delMatch" data-matchid="'+row['id']+'">Delete</button>';
                            case 'ongoing':
                                return '<button type="button" class="btn btn-warning btn-xs openBackMatch">Set Open</button> '
                                        +'<button type="button" class="btn btn-primary btn-xs settleMatch" data-matchdata="" data-toggle="modal" data-target="#settleMatchesModal">Settle</button>';
                            case 'settled':
                                return '<button type="button" class="btn btn-warning btn-xs editScore" data-matchdata="" data-toggle="modal" data-target="#setScoreModal">Set Match Score</button> ';
                            default:
                                return '';
                        }
                    }
                }
            ],
            "createdRow": function( nRow, aData, iDataIndex ) {
                $(nRow).find('td:eq(7) .editMatch').data('matchdata', aData);
                if(aData.status == 'ongoing')
                $(nRow).find('td:eq(7) .editScore').data('matchdata', aData);
                $(nRow).find('td:eq(7) .settleMatch').data('matchdata', aData);
                if(aData.status == 'settled')
                $(nRow).find('td:eq(7) .editScore').data('matchdata', aData);
            }
        });
        
        $('#createMatchBtn').click(function() {
            var form = new FormData($("#matchesForm")[0]);
            let hasInitialOddsError = false;
            const createBtnOrigText = $('#createMatchBtn').text();
            $('#createMatchBtn').prop('disabled', true).text("Processing...");

            $.each($('#createMatchesModal :input[name="sub_matches[]"]'),  function(_inx, _val) {
               
                if($(this).parent().find('input[type=checkbox]').is(':checked')) {

                    
                    let s_gindex = $(this).data('gindex');
                    let s_subtype = $(this).data('subtype');
                    let subMatchIndex = $(this).data('submatch-index');

                    let sub_match_team_a_initial_odd = $(`#sub_matches_team_a_initial_odd_${subMatchIndex}_${s_gindex}_${s_subtype}`).val();
                    let sub_match_team_b_initial_odd = $(`#sub_matches_team_b_initial_odd_${subMatchIndex}_${s_gindex}_${s_subtype}`).val()
                    let is_over_under = $(`#sub_matches_is_over_under_${subMatchIndex}_${s_gindex}_${s_subtype}`).val()

                    form.append('submatches['+_inx+'][ctr]', $(this).data('gindex'));
                    form.append('submatches['+_inx+'][name]', this.value);
                    form.append('submatches['+_inx+'][subtype]', $(this).data('subtype'));
                    form.append('submatches['+_inx+'][team_a_initial_odd]', sub_match_team_a_initial_odd);
                    form.append('submatches['+_inx+'][team_b_initial_odd]', sub_match_team_b_initial_odd);
                    form.append('submatches['+_inx+'][is_over_under]', is_over_under);

                    if(sub_match_team_a_initial_odd === "" || sub_match_team_b_initial_odd === ""){
                        hasInitialOddsError = true;
                        return false;
                    }
                }
            });
   
            if(form.get('status') == 'cancelled' || form.get('status') == 'forfeit') {
                var status_name = form.get('status') == 'cancelled' ? 'Cancel' : 'Forfeit';
                swal({
                    title: status_name + " match?",
                    text: "This match will be "+form.get('status')+" and all bets be cancelled/released!",
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
                        url: "{{ route('setmatches') }}",  //Server script to process data
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        type: 'POST',
                        success: function(data){
                            if(data.error)
                                $.each( data.error, function( key, value ) {
                                    $('#matchesForm').find(':input[name='+ key +']').parent().addClass('has-error');
                                    $('#matchesForm').find(':input[name='+ key +']').parent().find('.error-label').text(value[0]);
                                });
                            else {
                                $('#matchesForm')[0].reset();
                                $('#leaguesForm .print-error-msg').hide();
                                $('#createMatchesModal').modal('hide');
                                matchesTable.ajax.reload();
                                swal("Match "+form.get('status')+"!", "The match has now been "+form.get('status')+".", "success");
                            }
                            $('#createMatchBtn').prop('disabled', false).text(createBtnOrigText);
                        },
                        data: form,
                        cache:false,
                        contentType: false,
                        processData: false,
                    });
                });
            } else {
                $.ajax({
                    url: "{{ route('setmatches') }}",  //Server script to process data
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    type: 'POST',
                    async: false,
                    success: function(data){
                        if(data.error)
                            $.each( data.error, function( key, value ) {
                                    let keySplit = key.split('.');
        
                                    if(keySplit.length > 1){
                                        swal({
                                            title: "Initial odds fields",
                                            text: "are required for all sub-matches.",
                                            type: "warning",
                                            showCancelButton: false,
                                            confirmButtonClass: "btn-danger",
                                            confirmButtonText: "Okay, got it.",
                                            cancelButtonText: "No",
                                            closeOnConfirm: true,
                                            showLoaderOnConfirm: false
                                        })                                 
                                    }else{
                                        $('#matchesForm').find(':input[name='+ key +']').parent().addClass('has-error');
                                        $('#matchesForm').find(':input[name='+ key +']').parent().find('.error-label').text(value[0]);
                                    }


                                // $('#matchesForm').find(':input[name='+ key +']').parent().addClass('has-error');
                                // $('#matchesForm').find(':input[name='+ key +']').parent().find('.error-label').text(value[0]);
                            });
                        else {
                            $('#matchesForm')[0].reset();
                            $('#leaguesForm .print-error-msg').hide();
                            $('#createMatchesModal').modal('hide');
                            matchesTable.ajax.reload();
                        }
                        $('#createMatchBtn').prop('disabled', false).text(createBtnOrigText);
                    },
                    data: form,
                    cache:false,
                    contentType: false,
                    processData: false,
                    
                });
            }
        });

        $('#setScoreBtn').click(function() {
            var form = new FormData($("#matchesForm2")[0]);
            $.each($('#setScoreModal'), function(_inx, _val) {
            });
            if(form.get('status') == 'cancelled' || form.get('status') == 'forfeit') {
                var status_name = form.get('status') == 'cancelled' ? 'Cancel' : 'Forfeit';
                swal({
                    title: status_name + " match?",
                    text: "This match will be "+form.get('status')+" and all bets be cancelled/released!",
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
                        url: "{{ route('setscore') }}",  //Server script to process data
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        type: 'POST',
                        success: function(data){
                            if(data.error)
                                $.each( data.error, function( key, value ) {
                                    $('#matchesForm2').find(':input[name='+ key +']').parent().addClass('has-error');
                                    $('#matchesForm2').find(':input[name='+ key +']').parent().find('.error-label').text(value[0]);
                                });
                            else {
                                $('#matchesForm2')[0].reset();
                                $('#leaguesForm .print-error-msg').hide();
                                $('#setScoreModal').modal('hide');
                                matchesTable.ajax.reload();
                                swal("Match "+form.get('status')+"!", "The match has now been "+form.get('status')+".", "success");
                            }
                        },
                        data: form,
                        cache:false,
                        contentType: false,
                        processData: false,
                    });
                });
            } else {
                $.ajax({
                    url: "{{ route('setscore') }}",  //Server script to process data
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    type: 'POST',
                    success: function(data){
                        if(data.error)
                            $.each( data.error, function( key, value ) {
                                $('#matchesForm2').find(':input[name='+ key +']').parent().addClass('has-error');
                                $('#matchesForm2').find(':input[name='+ key +']').parent().find('.error-label').text(value[0]);
                            });
                        else {
                            $('#matchesForm2')[0].reset();
                            $('#leaguesForm .print-error-msg').hide();
                            $('#setScoreModal').modal('hide');
                            matchesTable.ajax.reload();
                        }
                    },
                    data: form,
                    cache:false,
                    contentType: false,
                    processData: false,
                });
            }
        });
        
        $('#setLeagueChampionBtn').click(function() {
            var that = $(this);
            that.prop('disabled', true);
            var selected_winner = $('#showTeamsModal :input[name=champion]').val();
            var league = $('#showTeamsModal :input[name=champion]').find('option[value="'+selected_winner+'"]').data('leagueid');
            $.ajax({
                url: "{{ route('settle_tournament') }}",  //Server script to process data
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                type: 'POST',
                data: {league_id: league, team_id: selected_winner},
                success: function(data){
                    that.prop('disabled', false);
                    if(data.success) {
                        swal("Success", "The tournament has now been settled!", "success");
                        leaguesTable.ajax.reload();
                        $('#showTeamsModal').modal('hide');
                    } else {
                        swal("Error", data.error, "error");
                    }
                }
            });
        });
        
        $('#createLeagueBtn').click(function() {
            var form = new FormData($("#leaguesForm")[0]);
            $.ajax({
                url: "{{ route('setleagues') }}",  //Server script to process data
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                type: 'POST',
                success: function(data){
                    if(data.error)
                        printErrorMsg(data.error);
                    else {
                        $('#leaguesForm')[0].reset();
                        $('#leaguesForm .print-error-msg').hide();
                        $('#createLeaguesModal').modal('hide');
                        leaguesTable.ajax.reload();
                    }
                },
                data: form,
                cache:false,
                contentType: false,
                processData: false,
            });
        });
        
        $('#settleMatchesBtn').click(function() {
            var form = new FormData($("#settleMatchesForm")[0]);
            swal({
                title: "Confirm match settle?",
                text: "This match will be settled and declare team the winner.",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Yes, settle it!",
                cancelButtonText: "No",
                closeOnConfirm: false,
                showLoaderOnConfirm: true
            },
            function(){
                $.ajax({
                    url: "{{ route('json_matches_settle') }}",  //Server script to process data
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    type: 'POST',
                    success: function(data){
                        if(data.error)
                            swal("Error!", "There was an error occured!", "error");
                        else {
                            $('#settleMatchesForm')[0].reset();
                            $('#settleMatchesModal').modal('hide');
                            swal("Success!", "The match was successfully settled.", "success");
                            matchesTable.ajax.reload();
                        }
                    },
                    data: form,
                    cache:false,
                    contentType: false,
                    processData: false,
                });
            });
        });
        
        $(document).on('click', '.showCreateMatchModalBtn', function() {
            $('#matchesForm').find(':input[name=match_id]').val('');
            loadMatchLeagues();
        });
        
        $('#createTeamBtn').click(function() {
            var form = new FormData($("#teamsForm")[0]);
            $.ajax({
                url: "{{ route('setTeams') }}",  //Server script to process data
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                type: 'POST',
                success: function(data){
                    if(data.success) {
                        $('#createTeamsModal').modal('hide');
                        swal("Success!", "Successfully set team " + data.team.name, "success");
                        teamsTable.ajax.reload();
                    } else {
                        $.each(data.errors, function( key, value ) {
                            $('#teamsForm').find(':input[name='+ key +']').parent().addClass('has-error');
                            $('#teamsForm').find(':input[name='+ key +']').parent().find('.error-label').text(value[0]);
                        });
                    }
                },
                data: form,
                cache:false,
                contentType: false,
                processData: false,
            });
        });
        
        $('#createGameTypeBtn').click(function() {
            var form = new FormData($("#gameTypeForm")[0]);
            $.ajax({
                url: "{{ route('setGameTypes') }}",  //Server script to process data
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                type: 'POST',
                success: function(data){
                    if(data.success) {
                        $('#createGameTypesModal').modal('hide');
                        swal("Success!", "Successfully set type " + data.type.name, "success");
                        gametypesTable.ajax.reload();
                    } else {
                        $.each(data.errors, function( key, value ) {
                            $('#gameTypeForm').find(':input[name='+ key +']').parent().addClass('has-error');
                            $('#gameTypeForm').find(':input[name='+ key +']').parent().find('.error-label').text(value[0]);
                        });
                    }
                },
                data: form,
                cache:false,
                contentType: false,
                processData: false,
            });
        });
        
        $('#extendMatchTimeBtn').click(function() {
            var form = new FormData($("#extendMatchForm")[0]);
            $.ajax({
                url: "{{ route('extend_match_time') }}",  //Server script to process data
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                type: 'POST',
                success: function(data){
                    if(data.success) {
                        $('#extendMatchModal').modal('hide');
                        swal("Success!", "Successfully updated match schedule.", "success");
                        matchesTable.ajax.reload();
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
        
        $(document).on('click', '#teams-table .editTeam', function() {
            $tr = $(this).closest('tr').hasClass('child') ? 
                $(this).closest('tr').prev() : $(this).closest('tr');
            var team = teamsTable.row($tr).data();
            var $btn = $('#createTeamsModal #createTeamBtn');
            $btn.removeClass('btn-primary').addClass('btn-warning');
            $btn.button('edit');
            $('#createTeamsModal .modal-title').text('Edit ' + team.name);
            $('#teamsForm').find(':input[name=team_id]').val(team.id);
            $('#teamsForm').find(':input[name=name]').val(team.name);
            $('#teamsForm').find(':input[name=shortname]').val(team.shortname);
            $('#teamsForm').find(':input[name=type]').val(team.type);
            $('#teamsForm .team_image_src').attr('src', "{{url('/')}}/" + team.image);
            if(team.is_favorite)
                $('#teamsForm #favorite_yes').prop('checked', true);
            else
                $('#teamsForm #favorite_no').prop('checked', true);
        });
        
        $(document).on('change', '#teamsForm :input[name=image]', function() {
            if (this.files && this.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#teamsForm .team_image_src').attr('src', e.target.result);
                };

                reader.readAsDataURL(this.files[0]);
            }
        });
        
        $(document).on('click', '#leagues-table .setLeagueStatus', function() {
            $tr = $(this).closest('tr').hasClass('child') ? 
                $(this).closest('tr').prev() : $(this).closest('tr');
            var league = leaguesTable.row($tr).data();
            var status_type = $(this).data('sts_type');
            var title_ = '', text_ = '';
            switch(status_type) {
                case 'inactive':
                    title_ = 'Make this League Inactive?';
                    text_ = 'League will no longer show on the homepage.';
                    break;
                case 'undo_inactive':
                    title_ = 'Make this League Active again?';
                    text_ = 'League will now show on the homepage.';
                    break;
                case 'expire':
                    title_ = 'Set this League to expire?';
                    text_ = 'Expired leagues will no longer show when creating matches.';
                    break;
                case 'undo_expire':
                    title_ = 'Undo expired league';
                    text_ = 'This League will now show when creating matches.';
                    break;
            }
            swal({
                title: title_,
                text: text_,
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Yes",
                cancelButtonText: "No",
                closeOnConfirm: false,
                showLoaderOnConfirm: true
            }, function() {
                $.ajax({
                    url:'{{route("set_league_status")}}',
                    type:'PATCH',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    data: {league_id: league.id, type: status_type},
                    success:function(data){
                        if(data.error) {
                            swal("Error!", data.error, "error");
                        } else {
                            swal("Success!", "Successfully set league to inactive!", "success");
                            leaguesTable.ajax.reload();
                        }
                    }
                });
            });
        });
        
        $(document).on('click', '#gametypes-table .editGameType', function() {
            $tr = $(this).closest('tr').hasClass('child') ? 
                $(this).closest('tr').prev() : $(this).closest('tr');
            var gameType = gametypesTable.row($tr).data();
            $('#createGameTypesModal').find('.modal-title').text('Edit Game Type: ' + gameType.name);
            $('#gameTypeForm').find(':input[name=type_id]').val(gameType.id);
            $('#gameTypeForm').find(':input[name=name]').val(gameType.name);
            $('#gameTypeForm').find(':input[name=description]').val(gameType.description);
            $('#createGameTypeBtn').removeClass('btn-primary').addClass('btn-warning');
            $('#createGameTypeBtn').button('edit');
        });
        
        $(document).on('click', '#leagues-table .editLeague', function() {
            $tr = $(this).closest('tr').hasClass('child') ? 
                $(this).closest('tr').prev() : $(this).closest('tr');
            var league = leaguesTable.row($tr).data();
            var items = [];
            // var selectiones = $team_selection[0].selectize;
            // selectiones.clear();
            for (ctr in league.teams) {
                items.push(league.teams[ctr].id);
            }
            // selectiones.setValue(items);
            updateTeamSelectionField(league.type,items);
            $('#createLeaguesModal .modal-title').text('Edit current League');
            $('#leaguesForm').find(':input[name=league_id]').val(league.id);
            $('#leaguesForm').find(':input[name=type]').val(league.type);
            $('#leaguesForm').find(':input[name=name]').val(league.name);
            $('#leaguesForm').find(':input[name=description]').val(league.description);
            $('#leaguesForm').find(':input[name=favorites_minimum]').val(league.favorites_minimum);
            $('#leaguesForm').find(':input[name=status][value='+league.status+']').prop('checked', true);
            $('#leaguesForm').find(':input[name=betting_status][value='+league.betting_status+']').prop('checked', true);
            $('#leaguesForm').find(':input[name=expired][value='+league.expired+']').prop('checked', true);
            $('#leaguesForm :input[name=betting_fee]').find('option[value="'+(league.betting_fee*100).toFixed(1)+'"]').prop('selected', true);
            $('#createLeagueBtn').removeClass('btn-primary').addClass('btn-warning');
            $('#createLeagueBtn').button('edit');
        });
        
        $(document).on('click', '#leagues-table .showLeagueTeams', function() {
            $tr = $(this).closest('tr').hasClass('child') ? 
                $(this).closest('tr').prev() : $(this).closest('tr');
            var league = leaguesTable.row($tr).data();
            if(league.teams.length > 0)
                $('#showTeamsModal .modal-footer').show();
            else
                $('#showTeamsModal .modal-footer').hide();
            $('#showTeamsModal .teamsHolder').html('');
            $('#showTeamsModal select').html($('<option>', {value:'', text:'None'}))
            $.each(league.teams, function() {
                var team_img_url = "{{url('/')}}/" + this.image;
                var fav_btn = '<button type="button" class="btn btn-success btn-xs setFavBtn" data-teamid="'+this.id+'" data-leagueid="'+league.id+'" data-teamname="'+this.name+'" data-setteam="1">Set as Favorite</button>';
                var cancel_fav_btn = '<button type="button" class="btn btn-danger btn-xs setFavBtn" data-teamid="'+this.id+'" data-leagueid="'+league.id+'" data-teamname="'+this.name+'" data-setteam="0">Remove Favorite</button>';
                $('#showTeamsModal select').append('<option value="'+this.id+'" data-leagueid="'+league.id+'">'+this.name+'</option>');
                $('#showTeamsModal .teamsHolder').append('<div class="col-xs-4 col-md-3" style="padding-left: 5px; padding-right: 5px">'+
                        '<div class="thumbnail"><img src="'+team_img_url+'" alt="'+this.name+'">'+this.name+
                        '<br/>'+(this.pivot.is_favorite ? cancel_fav_btn : fav_btn)+'</div></div>');
            });
            if(league.status == 0)
                $('#showTeamsModal .modal-footer').hide();
            else {
                if(league.betting_status >= 0)
                    $('#showTeamsModal .modal-footer').show();
                else
                    $('#showTeamsModal .modal-footer').hide();
            }
        });
        
        $(document).on('click', '#showTeamsModal .setFavBtn', function() {
            var team_id = $(this).data('teamid');
            var team_name = $(this).data('teamname');
            var league_id = $(this).data('leagueid');
            var fav_set = $(this).data('setteam');
            if(fav_set == 1) {
                $title = "Set as Favorite?";
                $text = "Make " + team_name + " as a favorite team for this league!"
            } else {
                $title = "Remove Favorite?";
                $text = "Remove " + team_name + " from favorites for this league!";
            }
            swal({
                title: $title,
                text: $text,
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Yes",
                cancelButtonText: "No",
                closeOnConfirm: false,
                showLoaderOnConfirm: true
            },
            function(){
                $.ajax({
                    url:'{{route("json_set_favorite_team")}}',
                    type:'PATCH',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    data: {team_id: team_id, league_id: league_id, favopt: fav_set},
                    success:function(data){
                        if(data.error) {
                            swal("Error!", data.error, "error");
                        } else {
                            swal("Success!", "Successfully set team!", "success");
                            leaguesTable.ajax.reload();
                            $('#showTeamsModal').modal('hide');
                        }
                    }
                });
            });
        });
        

        var $team_selection = null;
        var select_teams_dropdown = $("#select-teams").clone()
        // updateTeamSelectionField();

        function updateTeamSelectionField(gameType = 'all', selectedTeams = []){

            console.log('selectedTeams : ', selectedTeams)
            if(!!$team_selection){
                // var selectiones = $team_selection[0].selectize;
                // selectiones.clear();
                $('.selectize-control').remove();
                $('#select-teams').remove();
                const newClone = select_teams_dropdown.clone();
                $('#select-teams-create-league-container').append(newClone);
                $.get(`{{url('/matchmanager/listteams')}}/${gameType}`, function(data) {
                    //selectiones.items(data).refreshItems();
                    // selectiones.selectize({
                    //     preload: true,
                    //     persist: false,
                    //     maxItems: null,
                    //     valueField: 'id',
                    //     labelField: 'name',
                    //     searchField: 'name',
                    //     options: data,
                    //     create: false,
                    //     render: {
                    //             item: function(item, escape) {
                    //                     return '<div style="display: inline-grid" class="selected-team-league">' +
                    //                             '<img src="{{url('/')}}/' + escape(item.image) + '" width="60" alt="">' +
                    //                             '<span>' + escape(item.name) + '</span>' +
                    //                             '</div>';
                    //             },
                    //             option: function(item, escape) {
                    //                     return '<div>' +
                    //                             '<img src="{{url('/')}}/' + escape(item.image) + '" alt="">' +
                    //                             '<span class="name">' + escape(item.name) + '</span>' +
                    //                             '</div>';
                    //             }
                    //     }
                    // }).refreshItems();

                    $team_selection = newClone.selectize({
                        preload: true,
                        persist: false,
                        maxItems: null,
                        valueField: 'id',
                        labelField: 'name',
                        searchField: 'name',
                        options: data,
                        create: false,
                        render: {
                                item: function(item, escape) {
                                        return '<div style="display: inline-grid" class="selected-team-league">' +
                                                '<img src="{{url('/')}}/' + escape(item.image) + '" width="60" alt="">' +
                                                '<span>' + escape(item.name) + '</span>' +
                                                '</div>';
                                },
                                option: function(item, escape) {
                                        return '<div>' +
                                                '<img src="{{url('/')}}/' + escape(item.image) + '" alt="">' +
                                                '<span class="name">' + escape(item.name) + '</span>' +
                                                '</div>';
                                }
                        }
                    });

                    $team_selection[0].selectize.clear();
                    $team_selection[0].selectize.setValue(selectedTeams);

                    
                });                
            }else{
                $.get(`{{url('/matchmanager/listteams')}}/${gameType}`, function(data) {


                    $team_selection = $('#select-teams').selectize({
                        preload: true,
                        persist: false,
                        maxItems: null,
                        valueField: 'id',
                        labelField: 'name',
                        searchField: 'name',
                        options: data,
                        create: false,
                        render: {
                                item: function(item, escape) {
                                        return '<div style="display: inline-grid" class="selected-team-league">' +
                                                '<img src="{{url('/')}}/' + escape(item.image) + '" width="60" alt="">' +
                                                '<span>' + escape(item.name) + '</span>' +
                                                '</div>';
                                },
                                option: function(item, escape) {
                                        return '<div>' +
                                                '<img src="{{url('/')}}/' + escape(item.image) + '" alt="">' +
                                                '<span class="name">' + escape(item.name) + '</span>' +
                                                '</div>';
                                }
                        }
                    });

                    $team_selection[0].selectize.clear();
                    $team_selection[0].selectize.setValue(selectedTeams);

                    
                });
            }


        }

        $(document).on('change', '#create-league-game-type', function() {
            const gameType = $(this).val();
            updateTeamSelectionField(gameType)
        })
        
        $(document).on('click', '#matches-table .openBackMatch', function() {
            $tr = $(this).closest('tr').hasClass('child') ? 
                $(this).closest('tr').prev() : $(this).closest('tr');
            var match = matchesTable.row($tr).data();
            swal({
                title: "Open back this match?",
                text: "This match will be set to Open status including all its sub-matches!",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Yes, open it!",
                cancelButtonText: "No",
                closeOnConfirm: false,
                showLoaderOnConfirm: true
            },
            function(){
                $.ajax({
                    url:'{{route("json_matches_setopen")}}',
                    type:'PATCH',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    data: {match_id: match.id},
                    success:function(data){
                        if(data.error) {
                            swal("Error!", data.error, "error");
                        } else {
                            swal("Match set!", "The match has now been set back to Open.", "success");
                            matchesTable.ajax.reload();
                        }
                    }
                });
            });
        });
        
        $(document).on('click', '#matches-table .extendMatch', function() {
            $tr = $(this).closest('tr').hasClass('child') ? 
                $(this).closest('tr').prev() : $(this).closest('tr');
            var match = matchesTable.row($tr).data();
            $('#extendMatchModal .match_name').text(match.name);
            $('#extendMatchModal :input[name=match_id]').val(match.id);
        });

        $('')
        
        $(document).on('click', '#matches-table .editMatch', function() {
            $tr = $(this).closest('tr').hasClass('child') ? 
                $(this).closest('tr').prev() : $(this).closest('tr');
            var match = matchesTable.row($tr).data();

            loadMatchLeagues(match.league_id, match.team_a.id, match.team_b.id, true);
            $('#createMatchesModal :input[name=status]').closest('.form-group').show();
            $('#createMatchesModal .modal-title').text('Edit current match');
            $('#createMatchBtn').removeClass('btn-primary').addClass('btn-warning');
            $('#createMatchBtn').button('edit');
            $('#matchesForm').find(':input[name=match_id]').val(match.id);
            $('#matchesForm :input[name=name]').val(match.name);
            $('#matchesForm :input[name=label]').val(match.label);
            $('#matchesForm :input[name=schedule]').val(match.schedule);
            $('#matchesForm :input[name=team_a]').find('option[value='+match.team_a.id+']').prop('selected', true).trigger('change');
            $('#matchesForm :input[name=team_b]').find('option[value='+match.team_b.id+']').prop('selected', true).trigger('change');
            $('#matchesForm :input[name=best_of] option').filter(function () { return $(this).html() == match.best_of; }).prop('selected', true);
            $('#matchesForm :input[name=fee]').find('option[value="'+(match.fee*100).toFixed(1)+'"]').prop('selected', true);
            $('#matchesForm :input[name=status]').val(match.status);

            $('#matchesForm :input[name=is_initial_odds_enabled]').find('option[value='+match.is_initial_odds_enabled+']').prop('selected', true).trigger('change');
            $('#matchesForm :input[name=team_a_initial_odd]').val(match.team_a_initial_odd);
            $('#matchesForm :input[name=team_b_initial_odd]').val(match.team_b_initial_odd);

//            $.each(match, function(index, value) {
//                switch(index) {
//                    case 'id':
//                        $('#matchesForm').find(':input[name=match_id]').val(value);
//                        break;
//                    case 'team_a':
//                    case 'team_b':
//                        $('#matchesForm :input[name='+index+']').find('option[value='+value.id+']').prop('selected', true).trigger('change');
//                        break;
//                    case 'best_of':
//                        $('#matchesForm :input[name='+index+'] option').filter(function () { return $(this).html() == value; }).prop('selected', true);
//                        break;
//                    case 'fee':
//                        $('#matchesForm :input[name='+index+']').find('option[value="'+(value*100).toFixed(1)+'"]').prop('selected', true);
//                        break;
//                    case 'league_id':
//                        break;
//                    default:
//                        $('#matchesForm').find(':input[name='+index+']').val(value);
//                        break;
//                }
//            });
        });

        $(document).on('change', '#is_initial_odds_enabled', function(event){
            if(event.currentTarget.value == 0){
                $('#matchesForm :input[name=team_a_initial_odd]').prop('disabled', true);
                $('#matchesForm :input[name=team_b_initial_odd]').prop('disabled', true);
                $('.sub_matches_team_initial_odd').prop('disabled', true);                
                 
            }else{
                $('#matchesForm :input[name=team_a_initial_odd]').prop('disabled', false);
                $('#matchesForm :input[name=team_b_initial_odd]').prop('disabled', false);
                $('.sub_matches_team_initial_odd').prop('disabled', false);                 
            }
        });

        $(document).on('click', '#matches-table .editScore', function() {
            $tr = $(this).closest('tr').hasClass('child') ? 
                $(this).closest('tr').prev() : $(this).closest('tr');
            var match = matchesTable.row($tr).data();
            loadMatchLeagues(match.league_id, match.team_a.id, match.team_b.id, true);
            var url_imageA = "{{url('/')}}/" + match.team_a.image;
            var url_imageB = "{{url('/')}}/" + match.team_b.image;
            $('#setScoreModal :input[name=status]').closest('.form-group').show();
            $('#setScoreModal .modal-title').text('Edit current match');
            $('#setScoreBtn').removeClass('btn-primary').addClass('btn-warning');
            $('#setScoreBtn').button('edit');
            $('#matchesForm2').find(':input[name=match_id]').val(match.id);
            $('#matchesForm2 :input[name=team_a]').find('option[value='+match.team_a.id+']').prop('selected', true).trigger('change');
            $('#matchesForm2 :input[name=team_b]').find('option[value='+match.team_b.id+']').prop('selected', true).trigger('change');
            $('#matchesForm2 :input[name=teama_score]').val(match.teama_score);
            $('#matchesForm2 :input[name=teamb_score]').val(match.teamb_score);
            $('#matchesForm2').find('.team_aname').html(match.team_a.name);
            $('#matchesForm2').find('.team_bname').html(match.team_b.name);
            $('#matchesForm2').find('.team_aimage').html('<img src="'+url_imageA+'" style="width: 110px"/>');
            $('#matchesForm2').find('.team_bimage').html('<img src="'+url_imageB+'" style="width: 110px"/>');
//            $.each(match, function(index, value) {
//                switch(index) {
//                    case 'id':
//                        $('#matchesForm').find(':input[name=match_id]').val(value);
//                        break;
//                    case 'team_a':
//                    case 'team_b':
//                        $('#matchesForm :input[name='+index+']').find('option[value='+value.id+']').prop('selected', true).trigger('change');
//                        break;
//                    case 'best_of':
//                        $('#matchesForm :input[name='+index+'] option').filter(function () { return $(this).html() == value; }).prop('selected', true);
//                        break;
//                    case 'fee':
//                        $('#matchesForm :input[name='+index+']').find('option[value="'+(value*100).toFixed(1)+'"]').prop('selected', true);
//                        break;
//                    case 'league_id':
//                        break;
//                    default:
//                        $('#matchesForm').find(':input[name='+index+']').val(value);
//                        break;
//                }
//            });
        });
        
        $(document).on('click', '#matches-table .settleMatch', function() {
            $tr = $(this).closest('tr').hasClass('child') ? 
                $(this).closest('tr').prev() : $(this).closest('tr');
            var match = matchesTable.row($tr).data();
            var url_imageA = "{{url('/')}}/" + match.team_a.image;
            var url_imageB = "{{url('/')}}/" + match.team_b.image;
            $('#settleMatchesForm').find('.matchname').html(match.name);
            $('#settleMatchesForm').find('.teama_name').html(match.team_a.name);
            $('#settleMatchesForm').find('.teama_image').html('<img src="'+url_imageA+'" style="width: 110px"/>');
            $('#settleMatchesForm').find('.teamb_name').html(match.team_b.name);
            $('#settleMatchesForm').find('.teamb_image').html('<img src="'+url_imageB+'" style="width: 110px"/>');
            $('#settleMatchesForm').find(':input[name=match_id]').val(match.id);
            $('#settleMatchesForm').find(':input[name=team_winner]').html('<option value="">Select Team</option>');
            $('#settleMatchesForm').find(':input[name=team_winner]').append('<option value="'+match.team_a.id+'">'+match.team_a.name+'</option>');
            $('#settleMatchesForm').find(':input[name=team_winner]').append('<option value="'+match.team_b.id+'">'+match.team_b.name+'</option>');
            $('#settleMatchesForm').find(':input[name=team_winner]').append('<option value="draw">Draw Match</option>');
        });
        
        $(document).on('click', '#matches-table .delMatch', function() {
            $tr = $(this).closest('tr').hasClass('child') ? 
                $(this).closest('tr').prev() : $(this).closest('tr');
            var match = matchesTable.row($tr).data();
            swal({
                title: "Delete this match?",
                text: "The selected match will be deleted and cannot be retreived!",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "No",
                closeOnConfirm: false,
                showLoaderOnConfirm: true
            },
            function(){
                $.ajax({
                    url:'{{route("json_matches_delete")}}',
                    type:'DELETE',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    data: {match_id: match.id},
                    success:function(data){
                        if(data.error) {
                            swal("Error!", data.error, "error");
                        } else {
                            swal("Match deleted!", "The match has now been deleted.", "success");
                            matchesTable.ajax.reload();
                        }
                    }
                });
            });
        });
        
        $(document).on('click', '#leagues-table .delLeague', function() {
            $tr = $(this).closest('tr').hasClass('child') ? 
                $(this).closest('tr').prev() : $(this).closest('tr');
            var league = leaguesTable.row($tr).data();
            swal({
                title: "Delete this league?",
                text: "The selected league will be deleted and cannot be retreived!",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "No",
                closeOnConfirm: false,
                showLoaderOnConfirm: true
            },
            function(){
                $.ajax({
                    url:'{{route("delete_league")}}',
                    type:'DELETE',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    data: {league_id: league.id},
                    success:function(data){
                        if(data.error) {
                            swal("Error!", data.error, "error");
                        } else {
                            swal("League deleted!", "The league has now been deleted.", "success");
                            leaguesTable.ajax.reload();
                        }
                    }
                });
            });
        });
        
        $(document).on('click', '#gametypes-table .delGameType', function() {
            $tr = $(this).closest('tr').hasClass('child') ? 
                $(this).closest('tr').prev() : $(this).closest('tr');
            var gameType = gametypesTable.row($tr).data();
            swal({
                title: "Delete this Game Type?",
                text: "The selected type will be deleted and cannot be retreived!",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "No",
                closeOnConfirm: false,
                showLoaderOnConfirm: true
            },
            function(){
                $.ajax({
                    url:'{{route("del_game_type")}}',
                    type:'DELETE',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    data: {type_id: gameType.id},
                    success:function(data){
                        if(data.error) {
                            swal("Error!", data.error, "error");
                        } else {
                            swal("Type deleted!", "The type "+gameType.name+" has now been deleted.", "success");
                            gametypesTable.ajax.reload();
                        }
                    }
                });
            });
        });
        
        $(document).on('click', '#teams-table .delTeam', function() {
            $tr = $(this).closest('tr').hasClass('child') ? 
                $(this).closest('tr').prev() : $(this).closest('tr');
            var team = teamsTable.row($tr).data();
            swal({
                title: "Delete this team?",
                text: "The selected team will be deleted and cannot be retreived!",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "No",
                closeOnConfirm: false,
                showLoaderOnConfirm: true
            },
            function(){
                $.ajax({
                    url:'{{route("delete_team")}}',
                    type:'DELETE',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    data: {team_id: team.id},
                    success:function(data){
                        if(data.error) {
                            swal("Error!", data.error, "error");
                        } else {
                            swal("Team deleted!", "The team "+team.name+" has now been deleted.", "success");
                            teamsTable.ajax.reload();
                        }
                    }
                });
            });
        });
        
        $('#createTeamsModal').on('hidden.bs.modal', function() {
            $('#teamsForm')[0].reset();
            $('#teamsForm :input[name=team_id]').val('');
            $('#teamsForm .team_image_src').attr('src', "{{ asset('images/default_team_image.png') }}");
            $('#createTeamBtn').button('reset');
            $('#createTeamBtn').removeClass('btn-warning').addClass('btn-primary');
            $(this).find('.modal-title').text('Add Team');
            $('#teamsForm :input').closest('.has-error').removeClass('has-error');
        });
        
        $('#createLeaguesModal').on('hidden.bs.modal', function() {
            $('#leaguesForm')[0].reset();
            $('#leaguesForm :input[name=league_id]').val('');
            $('#leaguesForm .print-error-msg').hide();
            $('#createLeagueBtn').button('reset');
            $('#createLeagueBtn').removeClass('btn-warning').addClass('btn-primary');
            $(this).find('.modal-title').text('Add League');
            var selectiones = $team_selection[0].selectize;
            selectiones.clear();
        });
        
        $('#createMatchesModal').on('hidden.bs.modal', function() {
            $(this).find('.modal-title').text('Create new Match');
            $('#createMatchesModal .nav-tabs a[href="#main_match"]').tab('show');
            $('#createMatchesModal :input[name=status]').closest('.form-group').hide();
            $('#createMatchBtn').button('reset');
            $('#createMatchBtn').removeClass('btn-warning').addClass('btn-primary');
            $('#matchesForm')[0].reset();
            $('#matchesForm').find('.team_selection').trigger('change');
            $('#matchesForm :input[name=match_id]').val('');
            $('#matchesForm :input[name=team_a]').prop('disabled', false);
            $('#matchesForm :input[name=team_b]').prop('disabled', false);
            $('#matchesForm :input[name=league_id]').prop('disabled', false);
            $('#matchesForm :input').closest('.has-error').removeClass('has-error');
        });
        
        $('#createGameTypesModal').on('hidden.bs.modal', function() {
            $(this).find('.modal-title').text('Add Game Type');
            $('#gameTypeForm')[0].reset();
            $('#gameTypeForm :input[name=type_id]').val('');
            $('#createGameTypeBtn').button('reset');
            $('#createGameTypeBtn').removeClass('btn-warning').addClass('btn-primary');
            $('#gameTypeForm :input').closest('.has-error').removeClass('has-error');
        });
        
        $('#extendMatchModal').on('hidden.bs.modal', function() {
            $(this).find('.match_name').text('');
            $('#extendMatchForm')[0].reset();
            $('#extendMatchForm :input[name=match_id]').val('');
        });
        
        $(document).on('change', ':input', function(e){
            $(this).closest('.has-error').removeClass('has-error');
        });

        $(document).on('shown.bs.tab', '.nav-tabs a', function (e) {
            switch(e.currentTarget.hash) {
                case '#matches':
                    matchesTable.ajax.reload();
                    break;
                case '#leagues':
                    leaguesTable.ajax.reload();
                    break;
                case '#teams':
                    teamsTable.ajax.reload();
            }
        });
        
        $(document).on('change', '#matchesForm :input[name=league_id]', function(e, teams) {
            console.log('teams: ', teams)
            if(teams)
                loadMatchTeam($(this).find(':selected').val(), teams.team_a, teams.team_b);
            else
                loadMatchTeam($(this).find(':selected').val());
            
            var game_type = $(this).find(':selected').data('type');
            var best_of = parseInt($('#matchesForm :input[name=best_of]').val());
            loadSubMatches(game_type, best_of);
        });
        
        $(document).on('change', '#matchesForm :input[name=best_of]', function(e) {
            var game_type = $('#matchesForm :input[name=league_id]').find(':selected').data('type');
            var best_of = parseInt($(this).val());
            loadSubMatches(game_type, best_of);
        });
    });
    
    function loadMatchLeagues(selected_league, team_a, team_b, not_selectable) {
        $.get("{{url('/matchmanager/getongoingleagues')}}", function(data) {
            $('#matchesForm').find(':input[name=league_id]').html('');
            $.each(data, function() {
                if(selected_league && this.id == selected_league) {
                    $('#matchesForm').find(':input[name=league_id]')
                        .append('<option value="'+this.id+'" data-type="'+this.type+'" selected>'+this.name+'</option>');
                } else {
                    if(not_selectable)
                        $('#matchesForm')
                            .find(':input[name=league_id]').append('<option value="'+this.id+'" data-type="'+this.type+'" disabled>'+this.name+'</option>');
                    else
                        $('#matchesForm')
                            .find(':input[name=league_id]').append('<option value="'+this.id+'" data-type="'+this.type+'">'+this.name+'</option>');
                }
            });
            $('#matchesForm').find(':input[name=league_id]').trigger('change', {team_a: team_a, team_b: team_b});
        });
    }
    
    function loadMatchTeam(league_id, selected_team_a, selected_team_b) {
        $.get("{{url('/matchmanager/showteam')}}/"+league_id, function(data) {
            $teamA = $('#matchesForm').find(':input[name=team_a]');
            $teamB = $('#matchesForm').find(':input[name=team_b]');
            $teamA.html('<option value="">Select team</option>');
            $teamB.html('<option value="">Select team</option>');
            $.each(data, function() {
                if(selected_team_a && this.id == selected_team_a)
                    $teamA.append('<option value="'+this.id+'" data-name="'+this.name+'" data-image="'+this.image+'" selected>'+this.name+'</option>');
                else
                    $teamA.append('<option value="'+this.id+'" data-name="'+this.name+'" data-image="'+this.image+'">'+this.name+'</option>');
                
                if(selected_team_b && this.id == selected_team_b)
                    $teamB.append('<option value="'+this.id+'" data-name="'+this.name+'" data-image="'+this.image+'" selected>'+this.name+'</option>');
                else
                    $teamB.append('<option value="'+this.id+'" data-name="'+this.name+'" data-image="'+this.image+'">'+this.name+'</option>');
            });
            $teamA.trigger('change');
            $teamB.trigger('change');
        });
    }
    
    function loadSubMatches(game_type, best_of, match) {
        var sub_match = [];
        var team_a = $('#matchesForm :input[name=team_a]').find(':selected').val() ? $('#matchesForm :input[name=team_a]').find(':selected').data('name') : 'Team A';
        var team_b = $('#matchesForm :input[name=team_b]').find(':selected').val() ? $('#matchesForm :input[name=team_b]').find(':selected').data('name') : 'Team B';
       
        $('#submatch-teama-name-display').text(team_a);
        $('#submatch-teamb-name-display').text(team_b);

        if(game_type == 'dota2') {
            switch(best_of) {
                case 1:
                    sub_match.push({game_ctr: 0, sub_type: 'other', name: 'First Team Kill', desc: 'Which team will get the First Kill ?'});
                    sub_match.push({game_ctr: 0, sub_type: 'other', name: 'First 10 Kills', desc: 'Which team will get the First 10 Kills ?'});
                    sub_match.push({game_ctr: 0, sub_type: 'other', name: 'First Roshan Kill', desc: 'Which team will get the First Roshan Kill ?'});
                    break;
                case 2:
                    sub_match.push({game_ctr: 0, sub_type: 'handicap', name: 'Series Handicapped '+team_a+' | '+team_b+' +1.5', desc: 'If the whole match has a handicap with '+team_b+' +1.5, which team will win ?'});
                    sub_match.push({game_ctr: 0, sub_type: 'handicap', name: 'Series Handicapped '+team_b+' | '+team_a+' +1.5', desc: 'If the whole match has a handicap with '+team_a+' +1.5, which team will win ?'});
                    sub_match.push({game_ctr: 1, sub_type: 'main', name: 'Game 1 Winner', desc: 'Which team will win the 1st game in the whole match ?'});
                    sub_match.push({game_ctr: 1, sub_type: 'other', name: 'Game 1 First Team Kill', desc: 'Which team will get the First Kill ?'});
                    sub_match.push({game_ctr: 1, sub_type: 'other', name: 'Game 1 First 10 Kills', desc: 'Which team will get the First 10 Kills ?'});
                    sub_match.push({game_ctr: 1, sub_type: 'other', name: 'Game 1 First Roshan Kill', desc: 'Which team will get the First Roshan Kill ?'});
                    sub_match.push({game_ctr: 2, sub_type: 'main', name: 'Game 2 Winner', desc: 'Which team will win the 2st game in the whole match ?'});
                    sub_match.push({game_ctr: 2, sub_type: 'other', name: 'Game 2 First Team Kill', desc: 'Which team will get the First Kill ?'});
                    sub_match.push({game_ctr: 2, sub_type: 'other', name: 'Game 2 First 10 Kills', desc: 'Which team will get the First 10 Kills ?'});
                    sub_match.push({game_ctr: 2, sub_type: 'other', name: 'Game 2 First Roshan Kill', desc: 'Which team will get the First Roshan Kill ?'});
                    break;
                case 3:
                    sub_match.push({game_ctr: 0, sub_type: 'handicap', name: 'Series Handicapped '+team_a+' | '+team_b+' +1.5', desc: 'If the whole match has a handicap with '+team_b+' +1.5, which team will win ?'});
                    sub_match.push({game_ctr: 0, sub_type: 'handicap', name: 'Series Handicapped '+team_b+' | '+team_a+' +1.5', desc: 'If the whole match has a handicap with '+team_a+' +1.5, which team will win ?'});
                    sub_match.push({game_ctr: 1, sub_type: 'main', name: 'Game 1 Winner', desc: 'Which team will win the 1st game in the whole match ?'});
                    sub_match.push({game_ctr: 1, sub_type: 'other', name: 'Game 1 First Team Kill', desc: 'Which team will get the First Kill ?'});
                    sub_match.push({game_ctr: 1, sub_type: 'other', name: 'Game 1 First 10 Kills', desc: 'Which team will get the First 10 Kills ?'});
                    sub_match.push({game_ctr: 1, sub_type: 'other', name: 'Game 1 First Roshan Kill', desc: 'Which team will get the First Roshan Kill ?'});
                    sub_match.push({game_ctr: 2, sub_type: 'main', name: 'Game 2 Winner', desc: 'Which team will win the 2st game in the whole match ?'});
                    sub_match.push({game_ctr: 2, sub_type: 'other', name: 'Game 2 First Team Kill', desc: 'Which team will get the First Kill ?'});
                    sub_match.push({game_ctr: 2, sub_type: 'other', name: 'Game 2 First 10 Kills', desc: 'Which team will get the First 10 Kills ?'});
                    sub_match.push({game_ctr: 2, sub_type: 'other', name: 'Game 2 First Roshan Kill', desc: 'Which team will get the First Roshan Kill ?'});
                    sub_match.push({game_ctr: 3, sub_type: 'main', name: 'Game 3 Winner', desc: 'Which team will win the 2st game in the whole match ?'});
                    sub_match.push({game_ctr: 3, sub_type: 'other', name: 'Game 3 First Team Kill', desc: 'Which team will get the First Kill ?'});
                    sub_match.push({game_ctr: 3, sub_type: 'other', name: 'Game 3 First 10 Kills', desc: 'Which team will get the First 10 Kills ?'});
                    sub_match.push({game_ctr: 3, sub_type: 'other', name: 'Game 3 First Roshan Kill', desc: 'Which team will get the First Roshan Kill ?'});
                    break;
                case 5:
                    sub_match.push({game_ctr: 0, sub_type: 'handicap', name: 'Series Handicapped '+team_a+' | '+team_b+' +1.5', desc: 'If the whole match has a handicap with '+team_b+' +1.5, which team will win ?'});
                    sub_match.push({game_ctr: 0, sub_type: 'handicap', name: 'Series Handicapped '+team_b+' | '+team_a+' +1.5', desc: 'If the whole match has a handicap with '+team_a+' +1.5, which team will win ?'});
                    sub_match.push({game_ctr: 0, sub_type: 'handicap', name: 'Series Handicapped '+team_a+' | '+team_b+' +2.5', desc: 'If the whole match has a handicap with '+team_b+' +2.5, which team will win ?'});
                    sub_match.push({game_ctr: 0, sub_type: 'handicap', name: 'Series Handicapped '+team_b+' | '+team_a+' +2.5', desc: 'If the whole match has a handicap with '+team_a+' +2.5, which team will win ?'});
                    sub_match.push({game_ctr: 1, sub_type: 'main', name: 'Game 1 Winner', desc: 'Which team will win the 1st game in the whole match ?'});
                    sub_match.push({game_ctr: 1, sub_type: 'other', name: 'Game 1 First Team Kill', desc: 'Which team will get the First Kill ?'});
                    sub_match.push({game_ctr: 1, sub_type: 'other', name: 'Game 1 First 10 Kills', desc: 'Which team will get the First 10 Kills ?'});
                    sub_match.push({game_ctr: 1, sub_type: 'other', name: 'Game 1 First Roshan Kill', desc: 'Which team will get the First Roshan Kill ?'});
                    sub_match.push({game_ctr: 2, sub_type: 'main', name: 'Game 2 Winner', desc: 'Which team will win the 2st game in the whole match ?'});
                    sub_match.push({game_ctr: 2, sub_type: 'other', name: 'Game 2 First Team Kill', desc: 'Which team will get the First Kill ?'});
                    sub_match.push({game_ctr: 2, sub_type: 'other', name: 'Game 2 First 10 Kills', desc: 'Which team will get the First 10 Kills ?'});
                    sub_match.push({game_ctr: 2, sub_type: 'other', name: 'Game 2 First Roshan Kill', desc: 'Which team will get the First Roshan Kill ?'});
                    sub_match.push({game_ctr: 3, sub_type: 'main', name: 'Game 3 Winner', desc: 'Which team will win the 2st game in the whole match ?'});
                    sub_match.push({game_ctr: 3, sub_type: 'other', name: 'Game 3 First Team Kill', desc: 'Which team will get the First Kill ?'});
                    sub_match.push({game_ctr: 3, sub_type: 'other', name: 'Game 3 First 10 Kills', desc: 'Which team will get the First 10 Kills ?'});
                    sub_match.push({game_ctr: 3, sub_type: 'other', name: 'Game 3 First Roshan Kill', desc: 'Which team will get the First Roshan Kill ?'});
                    sub_match.push({game_ctr: 4, sub_type: 'main', name: 'Game 4 Winner', desc: 'Which team will win the 2st game in the whole match ?'});
                    sub_match.push({game_ctr: 4, sub_type: 'other', name: 'Game 4 First Team Kill', desc: 'Which team will get the First Kill ?'});
                    sub_match.push({game_ctr: 4, sub_type: 'other', name: 'Game 4 First 10 Kills', desc: 'Which team will get the First 10 Kills ?'});
                    sub_match.push({game_ctr: 4, sub_type: 'other', name: 'Game 4 First Roshan Kill', desc: 'Which team will get the First Roshan Kill ?'});
                    sub_match.push({game_ctr: 5, sub_type: 'main', name: 'Game 5 Winner', desc: 'Which team will win the 2st game in the whole match ?'});
                    sub_match.push({game_ctr: 5, sub_type: 'other', name: 'Game 5 First Team Kill', desc: 'Which team will get the First Kill ?'});
                    sub_match.push({game_ctr: 5, sub_type: 'other', name: 'Game 5 First 10 Kills', desc: 'Which team will get the First 10 Kills ?'});
                    sub_match.push({game_ctr: 5, sub_type: 'other', name: 'Game 5 First Roshan Kill', desc: 'Which team will get the First Roshan Kill ?'});
                    break;
                case 7:
                    sub_match.push({game_ctr: 0, sub_type: 'handicap', name: 'Series Handicapped '+team_a+' | '+team_b+' +1.5', desc: 'If the whole match has a handicap with '+team_b+' +1.5, which team will win ?'});
                    sub_match.push({game_ctr: 0, sub_type: 'handicap', name: 'Series Handicapped '+team_b+' | '+team_a+' +1.5', desc: 'If the whole match has a handicap with '+team_a+' +1.5, which team will win ?'});
                    sub_match.push({game_ctr: 0, sub_type: 'handicap', name: 'Series Handicapped '+team_a+' | '+team_b+' +2.5', desc: 'If the whole match has a handicap with '+team_b+' +2.5, which team will win ?'});
                    sub_match.push({game_ctr: 0, sub_type: 'handicap', name: 'Series Handicapped '+team_b+' | '+team_a+' +2.5', desc: 'If the whole match has a handicap with '+team_a+' +2.5, which team will win ?'});
                    sub_match.push({game_ctr: 0, sub_type: 'handicap', name: 'Series Handicapped '+team_a+' | '+team_b+' +3.5', desc: 'If the whole match has a handicap with '+team_b+' +3.5, which team will win ?'});
                    sub_match.push({game_ctr: 0, sub_type: 'handicap', name: 'Series Handicapped '+team_b+' | '+team_a+' +3.5', desc: 'If the whole match has a handicap with '+team_a+' +3.5, which team will win ?'});
                    sub_match.push({game_ctr: 1, sub_type: 'main', name: 'Game 1 Winner', desc: 'Which team will win the 1st game in the whole match ?'});
                    sub_match.push({game_ctr: 1, sub_type: 'other', name: 'Game 1 First Team Kill', desc: 'Which team will get the First Kill ?'});
                    sub_match.push({game_ctr: 1, sub_type: 'other', name: 'Game 1 First 10 Kills', desc: 'Which team will get the First 10 Kills ?'});
                    sub_match.push({game_ctr: 1, sub_type: 'other', name: 'Game 1 First Roshan Kill', desc: 'Which team will get the First Roshan Kill ?'});
                    sub_match.push({game_ctr: 2, sub_type: 'main', name: 'Game 2 Winner', desc: 'Which team will win the 2st game in the whole match ?'});
                    sub_match.push({game_ctr: 2, sub_type: 'other', name: 'Game 2 First Team Kill', desc: 'Which team will get the First Kill ?'});
                    sub_match.push({game_ctr: 2, sub_type: 'other', name: 'Game 2 First 10 Kills', desc: 'Which team will get the First 10 Kills ?'});
                    sub_match.push({game_ctr: 2, sub_type: 'other', name: 'Game 2 First Roshan Kill', desc: 'Which team will get the First Roshan Kill ?'});
                    sub_match.push({game_ctr: 3, sub_type: 'main', name: 'Game 3 Winner', desc: 'Which team will win the 2st game in the whole match ?'});
                    sub_match.push({game_ctr: 3, sub_type: 'other', name: 'Game 3 First Team Kill', desc: 'Which team will get the First Kill ?'});
                    sub_match.push({game_ctr: 3, sub_type: 'other', name: 'Game 3 First 10 Kills', desc: 'Which team will get the First 10 Kills ?'});
                    sub_match.push({game_ctr: 3, sub_type: 'other', name: 'Game 3 First Roshan Kill', desc: 'Which team will get the First Roshan Kill ?'});
                    sub_match.push({game_ctr: 4, sub_type: 'main', name: 'Game 4 Winner', desc: 'Which team will win the 2st game in the whole match ?'});
                    sub_match.push({game_ctr: 4, sub_type: 'other', name: 'Game 4 First Team Kill', desc: 'Which team will get the First Kill ?'});
                    sub_match.push({game_ctr: 4, sub_type: 'other', name: 'Game 4 First 10 Kills', desc: 'Which team will get the First 10 Kills ?'});
                    sub_match.push({game_ctr: 4, sub_type: 'other', name: 'Game 4 First Roshan Kill', desc: 'Which team will get the First Roshan Kill ?'});
                    sub_match.push({game_ctr: 5, sub_type: 'main', name: 'Game 5 Winner', desc: 'Which team will win the 2st game in the whole match ?'});
                    sub_match.push({game_ctr: 5, sub_type: 'other', name: 'Game 5 First Team Kill', desc: 'Which team will get the First Kill ?'});
                    sub_match.push({game_ctr: 5, sub_type: 'other', name: 'Game 5 First 10 Kills', desc: 'Which team will get the First 10 Kills ?'});
                    sub_match.push({game_ctr: 5, sub_type: 'other', name: 'Game 5 First Roshan Kill', desc: 'Which team will get the First Roshan Kill ?'});
                    sub_match.push({game_ctr: 6, sub_type: 'main', name: 'Game 6 Winner', desc: 'Which team will win the 2st game in the whole match ?'});
                    sub_match.push({game_ctr: 6, sub_type: 'other', name: 'Game 6 First Team Kill', desc: 'Which team will get the First Kill ?'});
                    sub_match.push({game_ctr: 6, sub_type: 'other', name: 'Game 6 First 10 Kills', desc: 'Which team will get the First 10 Kills ?'});
                    sub_match.push({game_ctr: 6, sub_type: 'other', name: 'Game 6 First Roshan Kill', desc: 'Which team will get the First Roshan Kill ?'});
                    sub_match.push({game_ctr: 7, sub_type: 'main', name: 'Game 7 Winner', desc: 'Which team will win the 2st game in the whole match ?'});
                    sub_match.push({game_ctr: 7, sub_type: 'other', name: 'Game 7 First Team Kill', desc: 'Which team will get the First Kill ?'});
                    sub_match.push({game_ctr: 7, sub_type: 'other', name: 'Game 7 First 10 Kills', desc: 'Which team will get the First 10 Kills ?'});
                    sub_match.push({game_ctr: 7, sub_type: 'other', name: 'Game 7 First Roshan Kill', desc: 'Which team will get the First Roshan Kill ?'});
                    break;
            }
        } else if(game_type == 'csgo') {
            switch(best_of) {
                case 1:
                    sub_match.push({game_ctr: 0, sub_type: 'other', name: 'Pistol Round 1 Winner', desc: 'Which team will get the First Blood ?'});
                    sub_match.push({game_ctr: 0, sub_type: 'other', name: 'Pistol Round 16 Winner', desc: 'Which team will get the First 10 Kills ?'});
                    sub_match.push({game_ctr: 0, sub_type: 'other', name: 'Handicapped team_a | team_b + 1.5', desc: 'Which team will get the First Roshan Kill ?'});
                    break;
                case 2:
                    sub_match.push({game_ctr: 0, sub_type: 'handicap', name: 'Series Handicapped '+team_a+' | '+team_b+' + 1.5', desc: 'If the whole match has a handicap with team_b +1.5, which team will win ?'});
                    sub_match.push({game_ctr: 0, sub_type: 'handicap', name: 'Series Handicapped '+team_b+' | '+team_a+' + 1.5', desc: 'If the whole match has a handicap with team_a +1.5, which team will win ?'});
                    sub_match.push({game_ctr: 1, sub_type: 'main', name: 'Map 1 Winner', desc: 'If the whole match has a handicap with team_a +1.5, which team will win ?'});
                    sub_match.push({game_ctr: 1, sub_type: 'other', name: 'Map 1 Pistol Round 1 Winner', desc: 'If the whole match has a handicap with team_a +1.5, which team will win ?'});
                    sub_match.push({game_ctr: 1, sub_type: 'other', name: 'Map 1 Pistol Round 16 Winner', desc: 'If the whole match has a handicap with team_a +1.5, which team will win ?'});
                    sub_match.push({game_ctr: 1, sub_type: 'other', name: 'Map 1 Handicapped '+team_a+' | '+team_b+' + 1.5', desc: 'If the whole match has a handicap with team_a +1.5, which team will win ?'});
                    sub_match.push({game_ctr: 2, sub_type: 'main', name: 'Map 2 Winner', desc: 'If the whole match has a handicap with team_a +1.5, which team will win ?'});
                    sub_match.push({game_ctr: 2, sub_type: 'other', name: 'Map 2 Pistol Round 1 Winner', desc: 'If the whole match has a handicap with team_a +1.5, which team will win ?'});
                    sub_match.push({game_ctr: 2, sub_type: 'other', name: 'Map 2 Pistol Round 16 Winner', desc: 'If the whole match has a handicap with team_a +1.5, which team will win ?'});
                    sub_match.push({game_ctr: 2, sub_type: 'other', name: 'Map 2 Handicapped '+team_a+' | '+team_b+' + 1.5', desc: 'If the whole match has a handicap with team_a +1.5, which team will win ?'});
                    break;
                case 3:
                    sub_match.push({game_ctr: 0, sub_type: 'handicap', name: 'Series Handicapped '+team_a+' | '+team_b+' + 1.5', desc: 'If the whole match has a handicap with team_b +1.5, which team will win ?'});
                    sub_match.push({game_ctr: 0, sub_type: 'handicap', name: 'Series Handicapped '+team_b+' | '+team_a+' + 1.5', desc: 'If the whole match has a handicap with team_a +1.5, which team will win ?'});
                    sub_match.push({game_ctr: 1, sub_type: 'main', name: 'Map 1 Winner', desc: 'If the whole match has a handicap with team_a +1.5, which team will win ?'});
                    sub_match.push({game_ctr: 1, sub_type: 'other', name: 'Map 1 Pistol Round 1 Winner', desc: 'If the whole match has a handicap with team_a +1.5, which team will win ?'});
                    sub_match.push({game_ctr: 1, sub_type: 'other', name: 'Map 1 Pistol Round 16 Winner', desc: 'If the whole match has a handicap with team_a +1.5, which team will win ?'});
                    sub_match.push({game_ctr: 1, sub_type: 'other', name: 'Map 1 Handicapped '+team_a+' | '+team_b+' + 1.5', desc: 'If the whole match has a handicap with team_a +1.5, which team will win ?'});
                    sub_match.push({game_ctr: 2, sub_type: 'main', name: 'Map 2 Winner', desc: 'If the whole match has a handicap with team_a +1.5, which team will win ?'});
                    sub_match.push({game_ctr: 2, sub_type: 'other', name: 'Map 2 Pistol Round 1 Winner', desc: 'If the whole match has a handicap with team_a +1.5, which team will win ?'});
                    sub_match.push({game_ctr: 2, sub_type: 'other', name: 'Map 2 Pistol Round 16 Winner', desc: 'If the whole match has a handicap with team_a +1.5, which team will win ?'});
                    sub_match.push({game_ctr: 2, sub_type: 'other', name: 'Map 2 Handicapped '+team_a+' | '+team_b+' + 1.5', desc: 'If the whole match has a handicap with team_a +1.5, which team will win ?'});
                    sub_match.push({game_ctr: 3, sub_type: 'main', name: 'Map 3 Winner', desc: 'If the whole match has a handicap with team_a +1.5, which team will win ?'});
                    sub_match.push({game_ctr: 3, sub_type: 'other', name: 'Map 3 Pistol Round 1 Winner', desc: 'If the whole match has a handicap with team_a +1.5, which team will win ?'});
                    sub_match.push({game_ctr: 3, sub_type: 'other', name: 'Map 3 Pistol Round 16 Winner', desc: 'If the whole match has a handicap with team_a +1.5, which team will win ?'});
                    sub_match.push({game_ctr: 3, sub_type: 'other', name: 'Map 3 Handicapped '+team_a+' | '+team_b+' + 1.5', desc: 'If the whole match has a handicap with team_a +1.5, which team will win ?'});
                    break;
                case 5:
                    sub_match.push({game_ctr: 0, sub_type: 'handicap', name: 'Series Handicapped '+team_a+' | '+team_b+' + 1.5', desc: 'If the whole match has a handicap with team_b +1.5, which team will win ?'});
                    sub_match.push({game_ctr: 0, sub_type: 'handicap', name: 'Series Handicapped '+team_b+' | '+team_a+' + 1.5', desc: 'If the whole match has a handicap with team_a +1.5, which team will win ?'});
                    sub_match.push({game_ctr: 1, sub_type: 'main', name: 'Map 1 Winner', desc: 'If the whole match has a handicap with team_a +1.5, which team will win ?'});
                    sub_match.push({game_ctr: 1, sub_type: 'other', name: 'Map 1 Pistol Round 1 Winner', desc: 'If the whole match has a handicap with team_a +1.5, which team will win ?'});
                    sub_match.push({game_ctr: 1, sub_type: 'other', name: 'Map 1 Pistol Round 16 Winner', desc: 'If the whole match has a handicap with team_a +1.5, which team will win ?'});
                    sub_match.push({game_ctr: 1, sub_type: 'other', name: 'Map 1 Handicapped '+team_a+' | '+team_b+' + 1.5', desc: 'If the whole match has a handicap with team_a +1.5, which team will win ?'});
                    sub_match.push({game_ctr: 2, sub_type: 'main', name: 'Map 2 Winner', desc: 'If the whole match has a handicap with team_a +1.5, which team will win ?'});
                    sub_match.push({game_ctr: 2, sub_type: 'other', name: 'Map 2 Pistol Round 1 Winner', desc: 'If the whole match has a handicap with team_a +1.5, which team will win ?'});
                    sub_match.push({game_ctr: 2, sub_type: 'other', name: 'Map 2 Pistol Round 16 Winner', desc: 'If the whole match has a handicap with team_a +1.5, which team will win ?'});
                    sub_match.push({game_ctr: 2, sub_type: 'other', name: 'Map 2 Handicapped '+team_a+' | '+team_b+' + 1.5', desc: 'If the whole match has a handicap with team_a +1.5, which team will win ?'});
                    sub_match.push({game_ctr: 3, sub_type: 'main', name: 'Map 3 Winner', desc: 'If the whole match has a handicap with team_a +1.5, which team will win ?'});
                    sub_match.push({game_ctr: 3, sub_type: 'other', name: 'Map 3 Pistol Round 1 Winner', desc: 'If the whole match has a handicap with team_a +1.5, which team will win ?'});
                    sub_match.push({game_ctr: 3, sub_type: 'other', name: 'Map 3 Pistol Round 16 Winner', desc: 'If the whole match has a handicap with team_a +1.5, which team will win ?'});
                    sub_match.push({game_ctr: 3, sub_type: 'other', name: 'Map 3 Handicapped '+team_a+' | '+team_b+' + 1.5', desc: 'If the whole match has a handicap with team_a +1.5, which team will win ?'});
                    sub_match.push({game_ctr: 4, sub_type: 'main', name: 'Map 4 Winner', desc: 'If the whole match has a handicap with team_a +1.5, which team will win ?'});
                    sub_match.push({game_ctr: 4, sub_type: 'other', name: 'Map 4 Pistol Round 1 Winner', desc: 'If the whole match has a handicap with team_a +1.5, which team will win ?'});
                    sub_match.push({game_ctr: 4, sub_type: 'other', name: 'Map 4 Pistol Round 16 Winner', desc: 'If the whole match has a handicap with team_a +1.5, which team will win ?'});
                    sub_match.push({game_ctr: 4, sub_type: 'other', name: 'Map 4 Handicapped '+team_a+' | '+team_b+' + 1.5', desc: 'If the whole match has a handicap with team_a +1.5, which team will win ?'});
                    sub_match.push({game_ctr: 5, sub_type: 'main', name: 'Map 5 Winner', desc: 'If the whole match has a handicap with team_a +1.5, which team will win ?'});
                    sub_match.push({game_ctr: 5, sub_type: 'other', name: 'Map 5 Pistol Round 1 Winner', desc: 'If the whole match has a handicap with team_a +1.5, which team will win ?'});
                    sub_match.push({game_ctr: 5, sub_type: 'other', name: 'Map 5 Pistol Round 16 Winner', desc: 'If the whole match has a handicap with team_a +1.5, which team will win ?'});
                    sub_match.push({game_ctr: 5, sub_type: 'other', name: 'Map 5 Handicapped '+team_a+' | '+team_b+' + 1.5', desc: 'If the whole match has a handicap with team_a +1.5, which team will win ?'});
                    break;
                case 7:
                    break;
            }
        } else if(game_type == 'nbaplayoffs') {
            
            sub_match.push({game_ctr: 0, sub_type: 'handicap', name: 'Series Handicapped '+team_a+' | '+team_b+' +1.5', desc: 'If the whole match has a handicap with '+team_b+' +1.5, which team will win ?'});
            sub_match.push({game_ctr: 0, sub_type: 'handicap', name: 'Series Handicapped '+team_b+' | '+team_a+' +1.5', desc: 'If the whole match has a handicap with '+team_a+' +1.5, which team will win ?'});
            sub_match.push({game_ctr: 0, sub_type: 'handicap', name: 'Series Handicapped '+team_a+' | '+team_b+' +2.5', desc: 'If the whole match has a handicap with '+team_b+' +2.5, which team will win ?'});
            sub_match.push({game_ctr: 0, sub_type: 'handicap', name: 'Series Handicapped '+team_b+' | '+team_a+' +2.5', desc: 'If the whole match has a handicap with '+team_a+' +2.5, which team will win ?'});
            sub_match.push({game_ctr: 0, sub_type: 'handicap', name: 'Series Handicapped '+team_a+' | '+team_b+' +3.5', desc: 'If the whole match has a handicap with '+team_b+' +3.5, which team will win ?'});
            sub_match.push({game_ctr: 0, sub_type: 'handicap', name: 'Series Handicapped '+team_b+' | '+team_a+' +3.5', desc: 'If the whole match has a handicap with '+team_a+' +3.5, which team will win ?'});
        }else if( typeof(game_type) != 'undefined' && game_type.toLowerCase() == 'lol'){
            switch(best_of) {
                case 1:
                    sub_match.push({game_ctr: 0, sub_type: 'other', name: 'First Team Kill', desc: 'Which team will get the First Kill ?'});
                    sub_match.push({game_ctr: 0, sub_type: 'other', name: 'First 5 Kills', desc: 'Which team will get the First 5 Kills ?'});
                    sub_match.push({game_ctr: 0, sub_type: 'other', name: 'First Baron Kill', desc: 'Which team will get the First Baron Kill ?'});
                    break;
                case 2:
                    sub_match.push({game_ctr: 0, sub_type: 'handicap', name: 'Series Handicapped '+team_a+' | '+team_b+' +1.5', desc: 'If the whole match has a handicap with '+team_b+' +1.5, which team will win ?'});
                    sub_match.push({game_ctr: 0, sub_type: 'handicap', name: 'Series Handicapped '+team_b+' | '+team_a+' +1.5', desc: 'If the whole match has a handicap with '+team_a+' +1.5, which team will win ?'});
                    sub_match.push({game_ctr: 1, sub_type: 'main', name: 'Game 1 Winner', desc: 'Which team will win the 1st game in the whole match ?'});
                    sub_match.push({game_ctr: 1, sub_type: 'other', name: 'Game 1 First Team Kill', desc: 'Which team will get the First Kill ?'});
                    sub_match.push({game_ctr: 1, sub_type: 'other', name: 'Game 1 First 5 Kills', desc: 'Which team will get the First 5 Kills ?'});
                    sub_match.push({game_ctr: 1, sub_type: 'other', name: 'Game 1 First Baron Kill', desc: 'Which team will get the First Baron Kill ?'});
                    sub_match.push({game_ctr: 2, sub_type: 'main', name: 'Game 2 Winner', desc: 'Which team will win the 2st game in the whole match ?'});
                    sub_match.push({game_ctr: 2, sub_type: 'other', name: 'Game 2 First Team Kill', desc: 'Which team will get the First Kill ?'});
                    sub_match.push({game_ctr: 2, sub_type: 'other', name: 'Game 2 First 5 Kills', desc: 'Which team will get the First 5 Kills ?'});
                    sub_match.push({game_ctr: 2, sub_type: 'other', name: 'Game 2 First Baron Kill', desc: 'Which team will get the First Baron Kill ?'});
                    break;
                case 3:
                    sub_match.push({game_ctr: 0, sub_type: 'handicap', name: 'Series Handicapped '+team_a+' | '+team_b+' +1.5', desc: 'If the whole match has a handicap with '+team_b+' +1.5, which team will win ?'});
                    sub_match.push({game_ctr: 0, sub_type: 'handicap', name: 'Series Handicapped '+team_b+' | '+team_a+' +1.5', desc: 'If the whole match has a handicap with '+team_a+' +1.5, which team will win ?'});
                    sub_match.push({game_ctr: 1, sub_type: 'main', name: 'Game 1 Winner', desc: 'Which team will win the 1st game in the whole match ?'});
                    sub_match.push({game_ctr: 1, sub_type: 'other', name: 'Game 1 First Team Kill', desc: 'Which team will get the First Kill ?'});
                    sub_match.push({game_ctr: 1, sub_type: 'other', name: 'Game 1 First 5 Kills', desc: 'Which team will get the First 5 Kills ?'});
                    sub_match.push({game_ctr: 1, sub_type: 'other', name: 'Game 1 First Baron Kill', desc: 'Which team will get the First Baron Kill ?'});
                    sub_match.push({game_ctr: 2, sub_type: 'main', name: 'Game 2 Winner', desc: 'Which team will win the 2st game in the whole match ?'});
                    sub_match.push({game_ctr: 2, sub_type: 'other', name: 'Game 2 First Team Kill', desc: 'Which team will get the First Kill ?'});
                    sub_match.push({game_ctr: 2, sub_type: 'other', name: 'Game 2 First 5 Kills', desc: 'Which team will get the First 5 Kills ?'});
                    sub_match.push({game_ctr: 2, sub_type: 'other', name: 'Game 2 First Baron Kill', desc: 'Which team will get the First Baron Kill ?'});
                    sub_match.push({game_ctr: 3, sub_type: 'main', name: 'Game 3 Winner', desc: 'Which team will win the 2st game in the whole match ?'});
                    sub_match.push({game_ctr: 3, sub_type: 'other', name: 'Game 3 First Team Kill', desc: 'Which team will get the First Kill ?'});
                    sub_match.push({game_ctr: 3, sub_type: 'other', name: 'Game 3 First 5 Kills', desc: 'Which team will get the First 5 Kills ?'});
                    sub_match.push({game_ctr: 3, sub_type: 'other', name: 'Game 3 First Baron Kill', desc: 'Which team will get the First Baron Kill ?'});
                    break;
                case 5:
                    sub_match.push({game_ctr: 0, sub_type: 'handicap', name: 'Series Handicapped '+team_a+' | '+team_b+' +1.5', desc: 'If the whole match has a handicap with '+team_b+' +1.5, which team will win ?'});
                    sub_match.push({game_ctr: 0, sub_type: 'handicap', name: 'Series Handicapped '+team_b+' | '+team_a+' +1.5', desc: 'If the whole match has a handicap with '+team_a+' +1.5, which team will win ?'});
                    sub_match.push({game_ctr: 0, sub_type: 'handicap', name: 'Series Handicapped '+team_a+' | '+team_b+' +2.5', desc: 'If the whole match has a handicap with '+team_b+' +2.5, which team will win ?'});
                    sub_match.push({game_ctr: 0, sub_type: 'handicap', name: 'Series Handicapped '+team_b+' | '+team_a+' +2.5', desc: 'If the whole match has a handicap with '+team_a+' +2.5, which team will win ?'});
                    sub_match.push({game_ctr: 1, sub_type: 'main', name: 'Game 1 Winner', desc: 'Which team will win the 1st game in the whole match ?'});
                    sub_match.push({game_ctr: 1, sub_type: 'other', name: 'Game 1 First Team Kill', desc: 'Which team will get the First Kill ?'});
                    sub_match.push({game_ctr: 1, sub_type: 'other', name: 'Game 1 First 5 Kills', desc: 'Which team will get the First 5 Kills ?'});
                    sub_match.push({game_ctr: 1, sub_type: 'other', name: 'Game 1 First Baron Kill', desc: 'Which team will get the First Baron Kill ?'});
                    sub_match.push({game_ctr: 2, sub_type: 'main', name: 'Game 2 Winner', desc: 'Which team will win the 2st game in the whole match ?'});
                    sub_match.push({game_ctr: 2, sub_type: 'other', name: 'Game 2 First Team Kill', desc: 'Which team will get the First Kill ?'});
                    sub_match.push({game_ctr: 2, sub_type: 'other', name: 'Game 2 First 5 Kills', desc: 'Which team will get the First 5 Kills ?'});
                    sub_match.push({game_ctr: 2, sub_type: 'other', name: 'Game 2 First Baron Kill', desc: 'Which team will get the First Baron Kill ?'});
                    sub_match.push({game_ctr: 3, sub_type: 'main', name: 'Game 3 Winner', desc: 'Which team will win the 2st game in the whole match ?'});
                    sub_match.push({game_ctr: 3, sub_type: 'other', name: 'Game 3 First Team Kill', desc: 'Which team will get the First Kill ?'});
                    sub_match.push({game_ctr: 3, sub_type: 'other', name: 'Game 3 First 5 Kills', desc: 'Which team will get the First 5 Kills ?'});
                    sub_match.push({game_ctr: 3, sub_type: 'other', name: 'Game 3 First Baron Kill', desc: 'Which team will get the First Baron Kill ?'});
                    sub_match.push({game_ctr: 4, sub_type: 'main', name: 'Game 4 Winner', desc: 'Which team will win the 2st game in the whole match ?'});
                    sub_match.push({game_ctr: 4, sub_type: 'other', name: 'Game 4 First Team Kill', desc: 'Which team will get the First Kill ?'});
                    sub_match.push({game_ctr: 4, sub_type: 'other', name: 'Game 4 First 5 Kills', desc: 'Which team will get the First 5 Kills ?'});
                    sub_match.push({game_ctr: 4, sub_type: 'other', name: 'Game 4 First Baron Kill', desc: 'Which team will get the First Baron Kill ?'});
                    sub_match.push({game_ctr: 5, sub_type: 'main', name: 'Game 5 Winner', desc: 'Which team will win the 2st game in the whole match ?'});
                    sub_match.push({game_ctr: 5, sub_type: 'other', name: 'Game 5 First Team Kill', desc: 'Which team will get the First Kill ?'});
                    sub_match.push({game_ctr: 5, sub_type: 'other', name: 'Game 5 First 5 Kills', desc: 'Which team will get the First 5 Kills ?'});
                    sub_match.push({game_ctr: 5, sub_type: 'other', name: 'Game 5 First Baron Kill', desc: 'Which team will get the First Baron Kill ?'});
                    break;
                case 7:
                    sub_match.push({game_ctr: 0, sub_type: 'handicap', name: 'Series Handicapped '+team_a+' | '+team_b+' +1.5', desc: 'If the whole match has a handicap with '+team_b+' +1.5, which team will win ?'});
                    sub_match.push({game_ctr: 0, sub_type: 'handicap', name: 'Series Handicapped '+team_b+' | '+team_a+' +1.5', desc: 'If the whole match has a handicap with '+team_a+' +1.5, which team will win ?'});
                    sub_match.push({game_ctr: 0, sub_type: 'handicap', name: 'Series Handicapped '+team_a+' | '+team_b+' +2.5', desc: 'If the whole match has a handicap with '+team_b+' +2.5, which team will win ?'});
                    sub_match.push({game_ctr: 0, sub_type: 'handicap', name: 'Series Handicapped '+team_b+' | '+team_a+' +2.5', desc: 'If the whole match has a handicap with '+team_a+' +2.5, which team will win ?'});
                    sub_match.push({game_ctr: 0, sub_type: 'handicap', name: 'Series Handicapped '+team_a+' | '+team_b+' +3.5', desc: 'If the whole match has a handicap with '+team_b+' +3.5, which team will win ?'});
                    sub_match.push({game_ctr: 0, sub_type: 'handicap', name: 'Series Handicapped '+team_b+' | '+team_a+' +3.5', desc: 'If the whole match has a handicap with '+team_a+' +3.5, which team will win ?'});
                    sub_match.push({game_ctr: 1, sub_type: 'main', name: 'Game 1 Winner', desc: 'Which team will win the 1st game in the whole match ?'});
                    sub_match.push({game_ctr: 1, sub_type: 'other', name: 'Game 1 First Team Kill', desc: 'Which team will get the First Kill ?'});
                    sub_match.push({game_ctr: 1, sub_type: 'other', name: 'Game 1 First 5 Kills', desc: 'Which team will get the First 5 Kills ?'});
                    sub_match.push({game_ctr: 1, sub_type: 'other', name: 'Game 1 First Baron Kill', desc: 'Which team will get the First Baron Kill ?'});
                    sub_match.push({game_ctr: 2, sub_type: 'main', name: 'Game 2 Winner', desc: 'Which team will win the 2st game in the whole match ?'});
                    sub_match.push({game_ctr: 2, sub_type: 'other', name: 'Game 2 First Team Kill', desc: 'Which team will get the First Kill ?'});
                    sub_match.push({game_ctr: 2, sub_type: 'other', name: 'Game 2 First 5 Kills', desc: 'Which team will get the First 5 Kills ?'});
                    sub_match.push({game_ctr: 2, sub_type: 'other', name: 'Game 2 First Baron Kill', desc: 'Which team will get the First Baron Kill ?'});
                    sub_match.push({game_ctr: 3, sub_type: 'main', name: 'Game 3 Winner', desc: 'Which team will win the 2st game in the whole match ?'});
                    sub_match.push({game_ctr: 3, sub_type: 'other', name: 'Game 3 First Team Kill', desc: 'Which team will get the First Kill ?'});
                    sub_match.push({game_ctr: 3, sub_type: 'other', name: 'Game 3 First 5 Kills', desc: 'Which team will get the First 5 Kills ?'});
                    sub_match.push({game_ctr: 3, sub_type: 'other', name: 'Game 3 First Baron Kill', desc: 'Which team will get the First Baron Kill ?'});
                    sub_match.push({game_ctr: 4, sub_type: 'main', name: 'Game 4 Winner', desc: 'Which team will win the 2st game in the whole match ?'});
                    sub_match.push({game_ctr: 4, sub_type: 'other', name: 'Game 4 First Team Kill', desc: 'Which team will get the First Kill ?'});
                    sub_match.push({game_ctr: 4, sub_type: 'other', name: 'Game 4 First 5 Kills', desc: 'Which team will get the First 5 Kills ?'});
                    sub_match.push({game_ctr: 4, sub_type: 'other', name: 'Game 4 First Baron Kill', desc: 'Which team will get the First Baron Kill ?'});
                    sub_match.push({game_ctr: 5, sub_type: 'main', name: 'Game 5 Winner', desc: 'Which team will win the 2st game in the whole match ?'});
                    sub_match.push({game_ctr: 5, sub_type: 'other', name: 'Game 5 First Team Kill', desc: 'Which team will get the First Kill ?'});
                    sub_match.push({game_ctr: 5, sub_type: 'other', name: 'Game 5 First 5 Kills', desc: 'Which team will get the First 5 Kills ?'});
                    sub_match.push({game_ctr: 5, sub_type: 'other', name: 'Game 5 First Baron Kill', desc: 'Which team will get the First Baron Kill ?'});
                    sub_match.push({game_ctr: 6, sub_type: 'main', name: 'Game 6 Winner', desc: 'Which team will win the 2st game in the whole match ?'});
                    sub_match.push({game_ctr: 6, sub_type: 'other', name: 'Game 6 First Team Kill', desc: 'Which team will get the First Kill ?'});
                    sub_match.push({game_ctr: 6, sub_type: 'other', name: 'Game 6 First 5 Kills', desc: 'Which team will get the First 5 Kills ?'});
                    sub_match.push({game_ctr: 6, sub_type: 'other', name: 'Game 6 First Baron Kill', desc: 'Which team will get the First Baron Kill ?'});
                    sub_match.push({game_ctr: 7, sub_type: 'main', name: 'Game 7 Winner', desc: 'Which team will win the 2st game in the whole match ?'});
                    sub_match.push({game_ctr: 7, sub_type: 'other', name: 'Game 7 First Team Kill', desc: 'Which team will get the First Kill ?'});
                    sub_match.push({game_ctr: 7, sub_type: 'other', name: 'Game 7 First 5 Kills', desc: 'Which team will get the First 5 Kills ?'});
                    sub_match.push({game_ctr: 7, sub_type: 'other', name: 'Game 7 First Baron Kill', desc: 'Which team will get the First Baron Kill ?'});
                    break;    
            }        
        }
        
        populateSubMatches(sub_match, best_of);
    }

    function printErrorMsg (msg) {
        $(".print-error-msg").find("ul").html('');
        $(".print-error-msg").css('display','block');
        $.each( msg, function( key, value ) {
                $(".print-error-msg").find("ul").append('<li>'+value+'</li>');
        });
    }
    
    $(document).on('click', '#createMatchesModal #addSubMatchBtn', function() {
        $('#addSubMatchBtn').data('overundervalue', $(this).data('overunder'));
        populateSubMatches();
        $target = $(this).parent();
        $target.animate({scrollTop: $target.prop("scrollHeight")}, 100);
    });
    
    function populateSubMatches(data, best_of) {
        if($.isArray(data)) {
            clearSubMatches(best_of);
            $.each(data, function(subMatchIndex, value) {
                
                // var content = '<div class="input-group" style="padding-top: 10px">'+
                //                 '<span class="input-group-addon submatch">'+
                //                     '<input type="checkbox" checked>'+
                //                 '</span>'+
                //                 '<input type="text" class="form-control" name="sub_matches[]" value="'+this.name+'" data-gindex="'+this.game_ctr+'" data-subtype="'+this.sub_type+'">'+
                //                 '<div class="input-group-addon" style="padding: 3px 6px">'+
                //                     '<button type="button" class="btn btn-danger" style="padding: 1px 6px">'+
                //                         '<i class="fa fa-trash-o"></i>'+
                //                     '</button>'+
                //                 '</div></div>';


                var content = `
                    <div class="input-group" style="padding-top: 10px">
                        <span class="input-group-addon submatch">
                            <input type="checkbox" checked>
                        </span>
                        <input type="text" class="form-control sub-match-name subMatches" name="sub_matches[]" value="${this.name}" data-submatch-index="${subMatchIndex}" data-gindex="${this.game_ctr}" data-subtype="${this.sub_type}" style="width: 420px;">
                        <span class="input-group-addon submatchx">TEAM A:</span>
                        <input type="number" class="form-control sub_matches_team_initial_odd sub_matches_team_a_initial_odd_${subMatchIndex}" name="sub_matches_team_a_initial_odd[]" id="sub_matches_team_a_initial_odd_${subMatchIndex}_${this.game_ctr}_${this.sub_type}" value="50" data-gindex="${this.game_ctr}" data-subtype="${this.sub_type}" required>
                        <span class="input-group-addon submatchx">TEAM B:</span>
                        <input type="number" class="form-control sub_matches_team_initial_odd sub_matches_team_b_initial_odd_${subMatchIndex}" name="sub_matches_team_b_initial_odd[]" id="sub_matches_team_b_initial_odd_${subMatchIndex}_${this.game_ctr}_${this.sub_type}" value="50" data-gindex="${this.game_ctr}" data-subtype="${this.sub_type}" required>
                        <div class="input-group-addon" style="padding: 3px 6px">
                            <button type="button" class="btn btn-danger remove-sub-match-btn" style="padding: 1px 6px">
                                <i class="fa fa-trash-o"></i>
                            </button>
                        </div>
                    </div>
                `;
                
                $(content).insertBefore($('#addSubMatchBtn'));
            });
        } else {
            // var content = '<div class="input-group" style="padding-top: 10px">'+
            //                     '<span class="input-group-addon submatch">'+
            //                         '<input type="checkbox">'+
            //                     '</span>'+
            //                     '<input type="text" class="form-control" name="sub_matches[]" data-gindex="0" data-subtype="other">'+
            //                     '<div class="input-group-addon" style="padding: 3px 6px">'+
            //                         '<button type="button" class="btn btn-danger" style="padding: 1px 6px">'+
            //                             '<i class="fa fa-trash-o"></i>'+
            //                         '</button>'+
            //                     '</div></div>';

        
             const subMatchIndexOther = $('.subMatches').length - 1;
             const game_ctrOther = 0;
             const sub_typeOther = 'other';
             
            let isOverUnder = $('#addSubMatchBtn').data('overundervalue');
            //  console.log('isOverUnderisOverUnder: ',isOverUnder)
            const placeholderText = isOverUnder  == 1 ? `Over/Under points or score` : ``;

             var content = `<div class="input-group" style="padding-top: 10px">
                                <span class="input-group-addon submatch">
                                    <input type="checkbox">
                                </span>
                                <input type="text" class="form-control subMatches" name="sub_matches[]" data-gindex="0" data-submatch-index="${subMatchIndexOther}" data-subtype="other" style="width: 420px;" placeholder="${placeholderText}">
                                <span class="input-group-addon submatchx">TEAM A:</span>
                                <input type="text" class="form-control sub_matches_team_initial_odd sub_matches_team_a_initial_odd_${subMatchIndexOther}" name="sub_matches_initial_odd_teama[]" value="50" data-gindex="0" data-subtype="other" id="sub_matches_team_a_initial_odd_${subMatchIndexOther}_${game_ctrOther}_${sub_typeOther}" >
                                <span class="input-group-addon submatchx">TEAM B:</span>
                                <input type="text" class="form-control sub_matches_team_initial_odd sub_matches_team_b_initial_odd_${subMatchIndexOther}" name="sub_matches_initial_odd_teamb[]" value="50" data-gindex="0" data-subtype="other" id="sub_matches_team_b_initial_odd_${subMatchIndexOther}_${game_ctrOther}_${sub_typeOther}" >                                
                                <input type="hidden" class="sub_matches_is_over_under_${subMatchIndexOther}" name="sub_matches_is_over_under[]" data-gindex="0" data-subtype="other" id="sub_matches_is_over_under_${subMatchIndexOther}_${game_ctrOther}_${sub_typeOther}" value="${isOverUnder}"/>
                                <div class="input-group-addon" style="padding: 3px 6px">
                                    <button type="button" class="btn btn-danger" style="padding: 1px 6px">
                                        <i class="fa fa-trash-o"></i>
                                    </button>
                                </div></div>`;

            $(content).insertBefore($('#addSubMatchBtn'));
        }
     
        initSubmatches();
        updateSubMatchCount();
    }
    
    function clearSubMatches(best_of) {
        $('.input-group-addon.submatch').each(function () {
            var $widget = $(this),
            $parent = $widget.parent(),
            $removeBtn = $parent.find('button.btn-danger'),
            $input = $widget.find('input'),
            type = $input.attr('type');
            
            if($input.data('matchtype') != 'main') {
                $parent.remove();
            } else {
                $widget.closest('.input-group').find('input[type="text"]').val('Match Winner BO'+best_of);
            }
        });
    }
    
    function updateSubMatchCount() {
        var ctr = $('#createMatchesModal .submatch input:checked').length;
        $('#createMatchesModal').find('.nav-tabs li:eq(1) a').find('strong').remove();
        $('#createMatchesModal').find('.nav-tabs li:eq(1) a').append(' <strong>('+ctr+')</strong>');
    }
    
    function initSubmatches() {
        $('.input-group-addon.submatch').each(function () {

            var $widget = $(this),
            $parent = $widget.parent(),
            //$removeBtn = $parent.find('button.btn-danger'),
            $removeBtn = $parent.find('button.remove-sub-match-btn'),
            $input = $widget.find('input'),
            type = $input.attr('type');
            settings = {
                checkbox: {
                    on: { icon: 'fa fa-check-square-o fa-lg text-success' },
                    off: { icon: 'fa fa-square-o fa-lg' }
                },
                radio: {
                    on: { icon: 'fa fa-dot-circle-o' },
                    off: { icon: 'fa fa-circle-o' }
                }
            };

            if(!$widget.children().first().hasClass('fa')) {
                $widget.prepend('<span class="' + settings[type].off.icon + '"></span>');

                $widget.on('click', function (e) {
                    if($input.data('matchtype') != 'main') {
                        if($widget.closest('.input-group').find('input[type="text"]').val().trim().length) {
                            $widget.closest('.input-group').removeClass('has-error');
                            $input.prop('checked', !$input.is(':checked'));
                            updateDisplay();
                        } else {
                            $widget.closest('.input-group').addClass('has-error');
                            $widget.closest('.input-group').effect( "shake", { direction: "left", times: 6, distance: 5}, 500 );
                        }
                    }
                });

                $removeBtn.on('click', function(e) {
                    $parent.remove();
                    updateSubMatchCount();
                });

                function updateDisplay() {
                    var isChecked = $input.is(':checked') ? 'on' : 'off';

                    $widget.find('.fa').attr('class', settings[type][isChecked].icon);

                    //Just for desplay
                    isChecked = $input.is(':checked');
                    $widget.closest('.input-group').find('.btn-danger').prop('disabled', isChecked);
                    $widget.closest('.input-group').find('input[type="text"].sub-match-name').prop('readonly', isChecked);
                    $widget.closest('.input-group').find('input[type="text"].sub-match-name').css({'background': isChecked ? '#DDDD': 'none'});
                    updateSubMatchCount();
                }

                updateDisplay();
            }
        });
    }
</script>
@endsection
