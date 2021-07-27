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
    td {
        vertical-align: middle;
    }
    #tournament_bet {
        text-align: center;
        background-image: url({{asset('images/bg_03.jpg')}}); 
        position: relative; 
        background-position: center; 
        background-size: cover; 
        background-repeat: no-repeat; 
        padding: 10px;
    }
    #tournament_bet th {
        background-color: #717171;
        color: #ffffff;
        font-weight: normal;
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
        right: 0px; 
        bottom: 0px; 
        background-image: url({{asset('images/fav.png')}}); 
        width: 25px; 
        height: 25px;
    }
    .input-xs {
        height: 30px;
        padding: 2px 5px;
        line-height: 1.5; /* If Placeholder of the input is moved up, rem/modify this. */
        border-radius: 3px;
    }
    .top_up_amount {
        color: blue;
        font-style: italic;
    }
</style>

@if(hasMatchManagementAccess(Auth::user()))
<style>
    #changeAdminBets{
        float:right;
        margin-right: 6px;
        margin-top: 6px;
    }
</style>
@endif
<link rel="stylesheet" href="{{ asset('bower_components/bootstrap-sweetalert/dist/sweetalert.css') }}">
@endsection

@section('content')
<div class="main-container dark-grey">
    <div class="m-container2" style="width: 98% !important">
        <div class="main-ct" style="margin-bottom: 0">
            <div class="title">
                {{$league->name}}

                @if(hasMatchManagementAccess(Auth::user()) && $league->betting_status == 1)
                    <button class="btn btn-xs btn-info" id="changeAdminBets"  data-toggle="modal" data-target="#changeAdminBetsModal">Change Admin Bets</button>
                @endif
            </div>
            <div class="clearfix"></div>
            <div id="tournament_bet" class="col-md-12">
            @foreach($league->teams as $index => $team)
                @if($index == 0)
                <div class="col-md-6" style="padding-left: 0; padding-right: 1px">
                <table border="1" class="table table-responsive table-border" style="text-align: center;">
                    <thead>
                        <tr>
                            <th style="width: 82px;">Team</th>
                            <th style="text-align: center">% of Winning</th>
                            <th style="text-align: center">Ratio</th>
                            <th style="text-align: center">Potential Winnings</th>
                            <th style="text-align: center">Current Bet</th>
                            <th style="text-align: center"></th>
                        </tr>
                    </thead>
                    <tbody>
                @endif
                @if($index == intVal(round($league->teams->count()/2)))
                    </tbody>
                </table>
                </div>
                <div class="col-md-6" style="padding-left: 1px; padding-right: 0">
                <table border="1" class="table table-responsive table-border" style="text-align: center;">
                    <thead>
                        <tr>
                            <th style="width: 82px;">Team</th>
                            <th style="text-align: center">% of Winning</th>
                            <th style="text-align: center">Ratio</th>
                            <th style="text-align: center">Potential Winnings</th>
                            <th style="text-align: center">Current Bet</th>
                            <th style="text-align: center"></th>
                        </tr>
                    </thead>
                    <tbody>
                @endif
                    <tr>
                        <td>
                            <img src="{{asset($team->image)}}" title="{{$team->name}}" style="width: 82px;">
                            @if($team->pivot->is_favorite)
                            <span class="favorite_team" title="Favorite team"></span>
                            @endif
                        </td>
                        <td style="vertical-align: middle;">{{  tournamentWinPercentagePerTeam($league->id, $team->id) }}%</td>
                        <td style="vertical-align: middle;">{{  tournamentRatioPerTeam($league->id, $team->id) }}</td>
                        <td style="vertical-align: middle;">
                            @if(potentialTournamentWinningPerUserPerTeam($league->id, $team->id, Auth::user()->id))
                            <strong style="color: green">&#8369; {{ number_format(potentialTournamentWinningPerUserPerTeam($league->id, $team->id, Auth::user()->id), 2, '.', ',') }}</strong>
                            @else
                            &#8369; 0
                            @endif
                        </td>
                        <td style="vertical-align: middle;">
                            @if($user_bets->where('team_id', $team->id)->first())
                            <strong style="color: green">&#8369; {{ number_format($user_bets->where('team_id', $team->id)->first()->amount, 0, '.', ',') }}</strong>
                            @else
                            &#8369; 0
                            @endif
                        </td>
                        <td style="vertical-align: middle;">
                            @if($league->betting_status == 1)
                                @if($user_bets->where('team_id', $team->id)->count())
                                <button class="btn btn-info btn-xs updateBetBtn" data-toggle="modal" data-target="#updateBetModal" 
                                        data-betamount="{{ $user_bets->where('team_id', $team->id)->first()->amount }}" data-team="{{$team}}" 
                                        data-teamratio="{{ tournamentRatioPerTeam($league->id, $team->id) }}" data-winamount="{{ number_format(potentialTournamentWinningPerUserPerTeam($league->id, $team->id, Auth::user()->id), 2, '.', ',') }}"
                                        data-betid="{{$user_bets->where('team_id', $team->id)->first()->id}}">Update</button>
                                <button class="btn btn-danger btn-xs cancelBetBtn" data-betid="{{$user_bets->where('team_id', $team->id)->first()->id}}" data-teamid="{{$team->id}}">Cancel</button>
                                @else
                                <button class="btn btn-warning btn-xs addBetBtn" data-toggle="modal" data-target="#addBetModal" data-team="{{$team}}" data-teamratio="{{ tournamentRatioPerTeam($league->id, $team->id) }}">Bet</button>
                                @endif

                                @if($team->type == 'tbd' && hasMatchManagementAccess(Auth::user()) )
                                    <button class="btn btn-success btn-xs setTbdTeam" data-toggle="modal" data-target="#setTbdTeamModal" data-team="{{$team}}" data-teamratio="{{ tournamentRatioPerTeam($league->id, $team->id) }}">Set Team</button
                                @endif
                            
                            @else
                                <strong>
                                    Closed
                                    @if($league->champion && $league->champion->id == $team->id)
                                    <br/><span style="color: green">(Winner)</span>
                                    @endif
                                </strong>
                            @endif
                        </td>
                    </tr>
                @if($league->teams->count() == ($index+1))
                    </tbody>
                </table>
                </div>
                @endif
            @endforeach
            </div>
        </div>
    </div>
    @if(Auth::check() && hasMatchManagementAccess(Auth::user()))
        <div class="loading-spinner-container text-center" style="color: #fff;">
            <span class='glyphicon glyphicon-refresh fa-spin'></span>
            <br/>
            <i>Getting tournament report...</i>
            <br/>
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
                    <input type="hidden" name="teamid" />
                    <div class="row">
                        <div class="pull-left col-md-4">
                            <img class="team_image" src="" />
                        </div>
                        <div class="col-md-8">
                            Name: <strong class="team_name"></strong><br/>
                            Ratio: <strong class="team_ratio"></strong><br/>
                            Possible winnings: <strong class="winning_amount">0.00</strong><br/>
                            <input type="text" name="bet_amount" placeholder="Place your bet here" class="form-control input-xs" 
                                style="margin-top: 10px; margin-bottom: 3px" />
                            <span class="error_field" style="color: red; display: none"></span>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <div class="buttons">
                    <button class="btn btn-primary btn-sm confirmBetBtn">
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

<div id="setTbdTeamModal" class="modal fade" role="dialog">
    <div class="modal-dialog" style="max-width: 600px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Set Team for <span class="team_name"></span></h4>
            </div>
            <div class="modal-body" style="padding-bottom: 5px;">
                <form id="updateTbdTeam">
                    <input type="hidden" name="teamid" />
                    <div class="row">
                        <div class="pull-left col-md-2">
                            <img class="team_image" src="" />
                        </div>
                        <div class="col-md-8">
                            Current Team: <strong class="team_name"></strong><br/>
                            Change to:  <select id="select-teams" class="form-control input-sm" name="team_id" placeholder="Select teams..." style="margin-top:10px;"></select>
                        </div>
                    </div>
                    <input type="hidden" name="league_id" value="{{$league->id}}" />
                    <input type="hidden" name="tbd_id" id="tbd_id" value="" />

                </form>
            </div>
            <div class="modal-footer">
                <div class="buttons">
                    <button class="btn btn-primary btn-sm saveTbdBtn">
                        Save
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
                    <input type="hidden" name="bet_id" />
                    <div class="row">
                        <div class="pull-left col-md-4" style="padding-bottom: 10px">
                            <img class="team_image" src="" style="width: 100px"/>
                        </div>
                        <div class="col-md-8">
                            Name: <strong class="team_name"></strong><br/>
                            Ratio: <strong class="team_ratio"></strong><br/>
                            Current bet: <strong class="bet_amount"></strong> <strong class="top_up_amount"></strong><br/>
                            Possible winnings: <strong class="winning_amount">0.00</strong><br/>
                            <input type="text" name="bet_amount" placeholder="Place your bet here" class="form-control input-xs" 
                                style="margin-top: 10px; margin-bottom: 3px; max-width: 220px" />
                            <span class="error_field" style="color: red; display: none"></span>
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

    @if(Auth::check() && hasMatchManagementAccess(Auth::user()))
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
                            <input type="hidden" name="match_id" value="{{$league->id}}" />
                            <div class="row">
                                <div class="form-group col-md-12 text-center">
                                    <h3>{{$league->name}}</h3>
                                </div>
                                <div class="col-md-12">
                                    <form class="form-inline">
                                        <div class="form-group">
                                            <label for="exampleInputName2">Total Admin Bets to work on: </label>
                                            <input type="number" min="100" class="form-control" id="admin-bet-input-percentage-base-amount" placeholder="e.g: 500,000" value="500000">
                                            <span id="helpBlock" class="help-block">
                                                <em>*the bet amount value based on the percentage will be taken from here. <br/> 
                                                    e.g: 300,000 - if you set Team A to have 70% of 300,000, <br/> 
                                                    210,000 wll be placed on Team A and remaining amount will be placed on Team B. <br/>
                                                    Total Percentages should add up to 100%
                                                </em>
                                            </span>
                                        </div>

                                        <div class="row">
                                            @foreach($league->teams as $index => $team)
                                                <div class="form-group col-md-6">
                                                    <label for="exampleInputName2">{{ $team->name }} Percentage: </label>
                                                    <input type="number" min="6" max="94" class="form-control admin-bet-input-percentage" placeholder="Input percentage" value="0" data-team-id="{{ $team->id }}">
                                                    <span id="helpBlock"><em>*<strong id="admin-credits-bet-{{ $team->id }}">{{ $team->name }}</strong> Admin Bet: <strong id="admin-credits-bet-value-{{ $team->id }}">0.00</strong></em></span>
                                                </div>
                                            @endforeach
                                        </div>
                                        {{-- <div class="form-group">
                                            <label for="exampleInputName2">{{ $match->teamA->name }} Percentage: </label>
                                            <input type="number" min="6" max="94" class="form-control admin-bet-input-percentage" id="admin-bet-input-percentage-a" placeholder="Input percentage" value="{{ number_format($match_data['team_a_percentage'], 2)  }}" readonly>
                                            <span id="helpBlock" class="help-block"><em>*Double click input to edit percentage for <strong>{{ $match->teamA->name }}</strong>.</em></span>
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail2">{{ $match->teamB->name }} Percentage: </label>
                                            <input type="number"  min="6" max="94"  class="form-control admin-bet-input-percentage" id="admin-bet-input-percentage-b"  placeholder="Input percentage" value="{{ number_format($match_data['team_b_percentage'], 2)  }}" readonly>
                                            <span id="helpBlock" class="help-block"><em>*Double click input to edit percentage <strong>{{ $match->teamB->name }}</strong>.</em></span>
                                        </div>
                                        <input type="hidden" id="current-admin-bet-input-percentage-a" value="{{ number_format($match_data['team_a_percentage'], 2)  }}" />
                                        <input type="hidden" id="current-admin-bet-input-percentage-b" value="{{ number_format($match_data['team_b_percentage'], 2)  }}" /> --}}
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

@endsection

@section('script')
<script type="text/javascript" src="{{ asset('bower_components/bootstrap-sweetalert/dist/sweetalert.min.js')}}"></script>
<script type="text/javascript">
    // mini jQuery plugin that formats to two decimal places
    (function($) {
        $.fn.currencyFormat = function() {
            this.each( function( i ) {
                $(this).change( function( e ){
                    if( isNaN( parseFloat( this.value ) ) ) return;
                    this.value = parseFloat(this.value).toFixed(0);
                });

                $(this).keydown( function( e ){
                    -1!==$.inArray(e.keyCode,[46,8,9,27,13,110,190])||/65|67|86|88/.test(e.keyCode)&&(!0===e.ctrlKey||!0===e.metaKey)||35<=e.keyCode&&40>=e.keyCode||(e.shiftKey||48>e.keyCode||57<e.keyCode)&&(96>e.keyCode||105<e.keyCode)&&e.preventDefault();
                });
            });
            return this; //for chaining
        }
    })( jQuery );
    
    $(document).ready(function() {
        $(":input[name=bet_value]").currencyFormat();
        
        $('#bettingForm').on('submit', function() {
            return false;
        });
        
        @if(Auth::check() && hasMatchManagementAccess(Auth::user()))
            $.ajax({
                url:'{{route("report_league")}}',
                type:'GET',
                data: {league_id: {{$league->id}}},
                success:function(data){
                    if(data != 'no data'){
                        $('.m-container2').after(data);
                        $('.loading-spinner-container').hide();
                    }
                }
            });

            $(document).on('click', 'table :button.setTbdTeam', function() {
                var team = $(this).data('team');
                const currentTeams = {!! json_encode($league->teams->toArray()) !!};
                const teamIds = currentTeams.map(team => team.id);
                $.get("{{url('/matchmanager/listteams')}}", function(data) {
                    console.log('aljun trace:', data, teamIds)
                    $.each(data, function (i, team) {
                    
                        $('#setTbdTeamModal #select-teams').append($('<option>', { 
                            value: team.id,
                            text : team.name,
                            'data-image': team.image,
                            disabled: teamIds.includes(team.id)
                        }));
                    });       
                });

                $('#setTbdTeamModal :input[name=teamid]').val(team.id);
                $('#setTbdTeamModal .team_image').attr('src', "{{url('/')}}/" + team.image);
                $('#setTbdTeamModal .team_name').html(team.name);
                $('#setTbdTeamModal #tbd_id').val(team.id);
            });

            $(document).on('click', '#setTbdTeamModal :button.saveTbdBtn', function(e) {
                e.preventDefault();
                e.stopPropagation();
                const currentTeams = {!! json_encode($league->teams->toArray()) !!};
                let teamIds = currentTeams.map(team => team.id);

                var formData = new FormData($('#updateTbdTeam')[0]);
                formData.append('teams', teamIds);

                var errorBox = $(this).closest('.modal-content').find('.error_field');
                errorBox.hide();
                $.ajax({
                    url:'{{route("adminUpdateLeagueTbdTeam")}}',
                    type:'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    data: formData,
                    success:function(data){
                        if(data.success) {
                            $('#setTbdTeamModal').modal('hide');
                            swal("Success!", "TBD Team updated!", "success");
                            $('table :button').prop('disabled', true);
                            window.setTimeout(function(){
                                window.location.href = "{{url('/tournament') . '/' . $league->id}}";
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
                    },
                    data: formData,
                    cache:false,
                    contentType: false,
                    processData: false,
                });
            });

        $(document).on('change', '.admin-bet-input-percentage', function() {

            const teamId = $(this).data('team-id');
            const currentVal = $(this).val();
            const allPercentages = $('.admin-bet-input-percentage').map( (index,input) => input.value ).get();
            const totalPercentage = allPercentages.reduce( (a,b) => parseFloat(a) + parseFloat(b) );

            if(totalPercentage > 100){
                swal("Mismatch", "Total of all percentages should only add up to 100%", "error");
            }else if(currentVal >= 0 ){
                const creditValue = parseFloat( $('#admin-bet-input-percentage-base-amount').val() * (  currentVal / 100 )  );
                $(`#admin-credits-bet-value-${teamId}`).text( numberWithCommas(creditValue));
            }else{
                swal("Mismatch", "Cannot set percentage below 0%", "error");
            }
        });

        $(document).on('click', '#saveAdminBetsPercentagesButton', function() {
            $('#saveAdminBetsPercentagesButton').prop('disabled', true);
            const totalPercentage = $('.admin-bet-input-percentage').map( (index,input) => input.value ).get().reduce( (a,b) => parseFloat(a) + parseFloat(b) );

            if(totalPercentage > 100){
                swal("Mismatch", "Total of all percentages should only add up to 100%", "error");
            }else{

                const allPercentages = $('.admin-bet-input-percentage').map( (index,input) =>   ( { team_id:  $(input).data('team-id'), percentage : input.value } ) ).get();
                $.ajax({
                    url:'{{route("update-tournament-admin-bets")}}',
                    type:'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    data: {
                        league_id : "{{ $league->id }}",
                        base_amount :  $('#admin-bet-input-percentage-base-amount').val(),
                        percentages : allPercentages
                    },
                    // contentType: 'application/json',
                    success:function(data){
                    
                        if(data.success) {
                            $('#changeAdminBetsModal').modal('hide');
                            swal("Success!", data.message, "success");
                           
                            window.setTimeout(function(){
                                window.location.href = "{{url('/tournament') . '/' . $league->id}}";
                            }, 2000);
                        } else {
                            swal("Error!", data.message, "error");
                            $('#saveAdminBetsPercentagesButton').prop('disabled', false);
                        }
                    },
                });                
            }
        

            
        });

        @endif
        
        $(document).on('click', 'table :button.addBetBtn', function() {
            var team = $(this).data('team');
            $('#addBetModal :input[name=teamid]').val(team.id);
            $('#addBetModal .team_image').attr('src', "{{url('/')}}/" + team.image);
            $('#addBetModal .team_name').html(team.name);
            $('#addBetModal .team_ratio').html($(this).data('teamratio'));
            $('#addBetModal').data('curr_amount', 0);
            $('#addBetModal').data('curr_ratio', $(this).data('teamratio'));
            $('#addBetModal').data('winning_amount', 0);
        });
        
        $(document).on('click', 'table :button.updateBetBtn', function() {
            var team = $(this).data('team');
            $('#updateBetModal :input[name=teamid]').val(team.id);
            $('#updateBetModal :input[name=bet_id]').val($(this).data('betid'));
            $('#updateBetModal .bet_amount').text($(this).data('betamount'));
            $('#updateBetModal .team_image').attr('src', "{{url('/')}}/" + team.image);
            $('#updateBetModal .team_name').html(team.name);
            $('#updateBetModal .team_ratio').html($(this).data('teamratio'));
            $('#updateBetModal .winning_amount').html($(this).data('winamount'));
            $('#updateBetModal').data('curr_amount', $(this).data('betamount'));
            $('#updateBetModal').data('curr_ratio', $(this).data('teamratio'));
            $('#updateBetModal').data('winning_amount', $(this).data('winamount'));
        });
        
        $(":input[name=bet_amount]").keyup(function(event) {
            var $modal = $(this).closest('.modal');
            var curr_ratio = $(this).closest('.modal').data('curr_ratio');
            var curr_amount = $(this).closest('.modal').data('curr_amount');
            var winning_amount = $(this).closest('.modal').data('winning_amount');
            var amount = $(this).val();
            var match_id = {{$league->id}};
            var possibleWinnings = (parseFloat(curr_amount)+parseFloat(amount)) * curr_ratio;
            var team_id = $modal.find(':input[name=teamid]').val();
            if($.inArray(event.keyCode, [9,16,17,18,91,37,38,39,40]) == -1) {
                if($.isNumeric(amount) && parseFloat(amount) > 0) {
                    $modal.find('.top_up_amount').html('(+' + numberWithCommas(parseFloat(amount).toFixed(2)) + ')');
                    $modal.find('.winning_amount').html(numberWithCommas(possibleWinnings.toFixed(2)));
                    // $.ajax({
                    //     url:'{{route("json_tournament_possible_winning")}}',
                    //     type:'POST',
                    //     headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    //     data: {teamid: team_id, leagueid: match_id, amount: amount},
                    //     success:function(data){
                    //         $modal.find('.team_ratio').html(numberWithCommas(data.ratio.toFixed(2)));
                    //         $modal.find('.winning_amount').html(numberWithCommas(data.amount.toFixed(2)));
                    //     }
                    // });
                } else {
                    $modal.find('.top_up_amount').html('');
                    $modal.find('.winning_amount').html(winning_amount);
                    $modal.find('.team_ratio').html(numberWithCommas(parseFloat(curr_ratio).toFixed(2)));
                }
            }
        });
        
        $(document).on('click', '#addBetModal :button.confirmBetBtn', function(e) {
            e.preventDefault();
            e.stopPropagation();
            var formData = new FormData($('#bettingForm')[0]);
            formData.append('leagueid', '{{$league->id}}');
            var errorBox = $(this).closest('.modal-content').find('.error_field');
            errorBox.hide();
            $.ajax({
                url:'{{route("json_tournament_addbet")}}',
                type:'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                data: formData,
                success:function(data){
                    if(data.success) {
                        $('#addBetModal').modal('hide');
                        swal("Success!", "Your bet has been set.", "success");
                        $('table :button').prop('disabled', true);
                        window.setTimeout(function(){
                            window.location.href = "{{url('/tournament') . '/' . $league->id}}";
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
                },
                data: formData,
                cache:false,
                contentType: false,
                processData: false,
            });
        });
        
        $(document).on('click', '#updateBetModal :button.addMoreBetBtn', function(e) {
            e.preventDefault();
            e.stopPropagation();
            var formData = new FormData($('#updatebettingForm')[0]);
            formData.append('leagueid', '{{$league->id}}');
            var errorBox = $(this).closest('.modal-content').find('.error_field');
            errorBox.hide();
            $.ajax({
                url:'{{route("json_tournament_updatebet")}}',
                type:'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                data: formData,
                success:function(data){
                    if(data.success) {
                        $('#updateBetModal').modal('hide');
                        swal("Success!", "Your bet has been set.", "success");
                        $('table :button').prop('disabled', true);
                        window.setTimeout(function(){
                            window.location.href = "{{url('/tournament') . '/' . $league->id}}";
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
                },
                data: formData,
                cache:false,
                contentType: false,
                processData: false,
            });
        });
        
        $(document).on('click', 'table :button.cancelBetBtn', function() {
            var betid = $(this).data('betid');
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
                    url:'{{route("json_tournament_cancelbet")}}',
                    type:'DELETE',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    data: {bet_id: betid, league_id: '{{$league->id}}'},
                    success:function(data){
                        if(data.success) {
                            swal("Bet Cancelled!", "Your bet has now been cancelled.", "success");
                            $('table :button').prop('disabled', true);
                            window.setTimeout(function(){
                                window.location.href = "{{url('/tournament'). '/' . $league->id}}";
                            }, 2000);
                        } else {
                            swal("Error!", "Tournament bet is already closed. Cannot cancel bet!", "error");
                        }
                    }
                });
            });
        });



        $(document).on('hidden.bs.modal', "#addBetModal", function () {
            $(this).find(':input[name=teamid]').val('');
            $(this).find(':input[name=bet_amount]').val('').trigger('keyup');
            $(this).find('.error_field').hide();
        });
        
        $(document).on('hidden.bs.modal', "#updateBetModal", function () {
            $(this).find(':input[name=teamid]').val('');
            $(this).find(':input[name=bet_amount]').val('').trigger('keyup');
            $(this).find('.error_field').hide();
        });
    });
</script>
@endsection