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

    .info-box {
        display: block;
        min-height: 56px;
        background: #fff;
        width: 100%;
        box-shadow: 0 1px 1px rgba(0,0,0,0.1);
        border-radius: 2px;
        margin-bottom: 15px;
    }

    .info-box-icon {
        border-top-left-radius: 2px;
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
        border-bottom-left-radius: 2px;
        display: block;
        float: left;
        height: 56px;
        width: 56px;
        text-align: center;
        font-size: 30px;
        line-height: 51px;
        background: rgb(212, 175, 55);
    }

    .info-box-icon .bg-dark{
            background: rgb(51, 51, 51);
    }
    .info-box-content {
        padding: 5px 10px;
        margin-left: 60px;
    }
    .info-box-text {
        text-transform: uppercase;
    }
    .info-box-number {
        display: block;
        font-weight: bold;
        font-size: 18px;
    }
    .sweet-overlay{
        z-index: 2000!important;
    }
    /* .text-wrap{
        white-space:normal;
    }
    .width-200{
        width:1000px;
    } */
    table td {
        word-wrap: break-word;
        max-width: 400px;
    }
    .form-twin-column{
        width: 48%;
        margin-bottom: 10px;
    }
    .unconfirmed-partner{
        opacity: 0.5;
        cursor: pointer;
        -webkit-filter: grayscale(100%); /* Safari 6.0 - 9.0 */
        filter: grayscale(100%);
    }
    .hover-message {
        position: relative;
        display: inline-block;
    }

    .hover-message .hover-message-text {
        visibility: hidden;
        width: 200px;
        background-color: #717171;
        border: 1px solid black;
        color: #fff;
        text-align: center;
        border-radius: 6px;
        padding: 10px;
        
        position: absolute;
        z-index: 1;
        top: 100%;
        left: 0%;
    }

    .hover-message:hover .hover-message-text {
        visibility: visible;
    }

    .data-tables-btn{
        margin-left: 10px;
    }    

    .cursor-pointer {
        cursor:pointer;
    }
</style>
<link rel="stylesheet" href="{{ asset('bower_components/bootstrap-sweetalert/dist/sweetalert.css') }}">
<link rel="stylesheet" href="{{ asset("css/fileinput.css") }}">
<link rel="stylesheet" href="{{ asset("css/datatables.min.css") }}">
<link rel="stylesheet" href="{{ asset("css/viewer.min.css") }}">
<link rel="stylesheet" href="{{ asset("css/select2/select2.min.css") }}">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.1/css/responsive.dataTables.min.css">
<link rel="stylesheet" href="{{ asset('/bower_components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css') }}"/>
@endsection

@section('content')

<div class="main-container dark-grey">
<div class="profile-wrapper">
    <div class="m-container1">
        <div class="main-ct">
            <div class="title2">My Profile <edit-profile-settings></edit-profile-settings></div>
            <div class="clearfix"></div>
            <div class="user-info pd-blk">
                <div class="user-avata">
                    @if(Auth::user()->provider == 'local')
                    <a href="#"><img src="{{ url('/').'/'.Auth::user()->avatar }}"></a>
                    @else
                    <a href="#"><img src="{{ Auth::user()->avatar }}"></a>
                    @endif
                </div>
                <div class="info-user-txt" style="overflow: hidden; text-overflow: ellipsis;">
                    <span class="name"><a href="#" target="_blank">{{ Auth::user()->name }}</a></span>
                    
                    <div class="col-md-12" style="margin-left: -15px; margin-top: 10px;font-size: 14px;">
                        User ID: <span style="font-weight: bold;">#{{ Auth::id() }}</span>
                    </div>
                    <div class="col-md-12" style="margin-left: -15px; font-size: 14px;">
                        Ez credits: <span style="font-weight: bold;" data-number>&#8369; {{ Auth::user()->credits ? Auth::user()->credits : '0.00' }}</span>
                    </div>
                    {{-- <div class="col-md-12" style="margin-left: -15px;font-size: 14px;">
                        Wallet: <span style="font-weight: bold;" data-number>&#8369; {{ $totalActiveWallet }}</span> 
                        @if($totalActiveWallet >= 1)
                            <a type="button" class="btn btn-warning btn-xs transfer-wallet">Transfer</a>
                        @endif
                    </div> --}}

                    <div class="col-md-12"  style="margin-left: -15px;font-size: 14px;">
                        Redeemed Voucher Code: <span style="font-weight: bold;">{{ Auth::user()->redeem_voucher_code ? strtoupper(Auth::user()->redeem_voucher_code) : 'n/a' }}</span>
                    </div>      
                    {{-- <div class="col-md-12" style="margin-left: -15px;font-size: 14px;">
                        Real Money: <span style="font-weight: bold;" data-number>&#8369; {{ Auth::user()->real_money }}</span>
                    </div> --}}
                    @if(Auth::user()->voucher_code)

                    <div class="col-md-12"  style="margin-left: -15px;font-size: 14px;">
                        Commissions (Payables): <span style="font-weight: bold;" data-number>&#8369; {{ number_format( ( Auth::user()->totalPayableCommission() + Auth::user()->totalPayablePartnerCommission() +  Auth::user()->totalPayableCommissionBets() ) ,2)  }}</span>
                    </div>
                    <div class="col-md-12"  style="margin-left: -15px;font-size: 14px;">
                        Commissions (Overall): <span style="font-weight: bold;" data-number>&#8369; {{ number_format( ( Auth::user()->totalCommission() + Auth::user()->totalPartnerCommission() +  Auth::user()->totalCommissionBets() ),2)}}</span>@if(Auth::user()->voucher_code) <!--<a href="#" class="btn btn-success btn-xs" style="top: 0px; right: 15px;font-size: 10px">Convert to Credits</a> <a href="#" class="btn btn-danger btn-xs btn-convert-commiss" style="position: absolute;top: 3px; font-size: 10px;">Cashout Commissions</a>-->@endif
                    </div>

                    <div class="col-md-12"  style="margin-left: -15px;font-size: 14px;">
                        Voucher Code: <span style="font-weight: bold;">{{ Auth::user()->voucher_code ? Auth::user()->voucher_code: 'n/a' }}</span>
                    </div>
              
                    <div class="col-md-12"  style="margin-left: -15px;font-size: 14px;">
                        Affliate Link: <span style="font-weight: bold;" data-toggle="tooltip" data-placement="top" title="Copy to clipboard" id="affliate-voucher-code" class="cursor-pointer copy-voucher-code">{{ url('/affliate/' . Auth::user()->voucher_code ) }}</span>
                        &nbsp;&nbsp;<span class="glyphicon glyphicon-copy text-info cursor-pointer copy-voucher-code">
                    </div>

                    @endif

                    <div class="col-sm-6"  style="margin-left: -15px; margin-top: 10px;">
                        <a href="{{url('/purchase')}}" class="btn btn-warning w-100">PURCHASE CREDITS</a>
                    </div>
                    <div class="col-sm-6"  style="margin-left: -15px; margin-top: 10px;">
                        <a href="{{url('/transfer')}}" class="btn btn-success w-100">TRANSFER CREDITS</a>
                    </div>

                    <div class="col-sm-6"  style="margin-left: -15px; margin-top: 10px;">
                        <a href="{{url('/market')}}"  class="btn btn-info w-100">SHOP</a>
                    </div>
                    <div class="col-sm-6"  style="margin-left: -15px; margin-top: 10px;">
                        <a id="report-bug" href="#" class="btn btn-danger w-100">FILE A TICKET</a>
                    </div>
                    {{-- <div class="col-sm-6"  style="margin-left: -15px; margin-top: 10px;">
                        <button id="add-promotion" class="btn btn-warning" style="width: 149px;" {{Auth::user()->promotions()->whereDate('created_at','=',date('Y-m-d'))->count() == 0 ? '' : 'disabled'}} disabled>PROMOTION</button>
                    </div> --}}

                    <div class="col-sm-6"  style="margin-left: -15px; margin-top: 10px;">
                        <get-free-credits></get-free-credits>
                    </div>

                    
                    @if(Auth::user()->voucher_code)
                        <div class="col-sm-6"  style="margin-left: -15px; margin-top: 10px;">
                            <a href="#" class="btn btn-primary download-voucher-users w-100">View Voucher Users</a>
                        </div>

                        <div class="col-sm-6"  style="margin-left: -15px; margin-top: 10px;">
                            <a href="#" class="btn btn-primary get-new-voucher-users w-100">Get NEW Voucher Users</a>
                        </div>                        
                    @endif

                    

               {{--      <br>Account Type: {{ ucfirst(Auth::user()->provider)  }}
                    @if(Auth::user()->provider == 'steam')
                    <br>Steam ID: {{ Auth::user()->provider_id }}
                    @13 --}}
             {{--        <br>Current Credits: <span id="total_profit">0</span>
                    <br>Total bets: <span id="total_bet">0</span>
                    <br>Total won: <span id="total_won">0</span>
                    <br>Rank: #<span id="topbet_position">..</span> --}}
                    {{-- <a href="#" class="btn btn-danger" style="position: absolute;top: 0px; right: 15px;">Edit Profile</a> --}}
                    {{-- <a class="refresh orange-btn">Add Credits</a> --}}
                </div>
                <div class="info-user-txt" style="padding-left: 0">
                    <div class="col-md-12 badges-area" style="padding-top: 20px; padding-left: 0">
                        @foreach($user_badges as $badge)
                            @if($badge->name == 'PARTNER')
                                @if( !empty($hasBadgePartner) && empty($partner) )
                                <div class="col-md-3" id="fill-up-info" style="text-align: center; padding: 0; max-width: 100px">
                                    <div class="hover-message">
                                        <img src="{{url('/public_image') . '/' . $badge->image}}" class="unconfirmed-partner" width="80px" alt="{{$badge->name}}" title="{{$badge->description}}">
                                        <strong>{{$badge->name}}</strong>
                                        <span class="hover-message-text">You need to complete your information in order to access the partner dashboard and start your transaction. Click here to submit your information.</span>
                                    </div>
                                </div>
                                @elseif( $isAgent && ($partner && $partner->verified == 0) )
                                <div class="col-md-3" style="text-align: center; padding: 0; max-width: 100px">
                                    <div class="hover-message">
                                        <img src="{{url('/public_image') . '/' . $badge->image}}" class="unconfirmed-partner" width="80px" alt="{{$badge->name}}" title="{{$badge->description}}">
                                        <strong>{{$badge->name}}</strong>
                                        <span class="hover-message-text">Partnership information is successfully submitted. Please wait for the admins to process your partnership request.</span>
                                    </div>
                                </div>
                                @else
                                <div class="col-md-3" style="text-align: center; padding: 0; max-width: 100px">
                                    <img src="{{url('/public_image') . '/' . $badge->image}}" width="80px" alt="{{$badge->name}}" title="{{$badge->description}}">
                                    <strong>{{$badge->name}}</strong>
                                </div>
                                @endif
                            @else
                                @if($badge->name == 'SUB-AFFILIATE')
                                <div class="col-md-3" style="text-align: center; padding: 0; max-width: 100px">
                                    <img src="{{url('/public_image') . '/' . $badge->image}}" width="80px" alt="AFFILIATE" title="AFFILIATE">
                                    <strong>AFFILIATE</strong>
                                </div>
                                @else
                                    <div class="col-md-3" style="text-align: center; padding: 0; max-width: 100px">
                                        <img src="{{url('/public_image') . '/' . $badge->image}}" width="80px" alt="{{$badge->name}}" title="{{$badge->description}}">
                                        <strong>{{$badge->name}}</strong>
                                    </div>
                                @endif

                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    <div class="m-container1">
        <div class="main-ct">
            <div class="title2">Earnings</div>
            <div class="clearfix"></div>
            <div class="user-info pd-blk">
                <div class="col-md-12">
                    <div class="col-md-6">
                      <div class="info-box">
                        <span class="info-box-icon bg-aqua"><i class="fa fa-money"></i></span>

                        <div class="info-box-content">
                          <span class="info-box-text">Today's Earnings</span>
                        <span class="info-box-number" data-number>&#8369; {{ $earnings['today'] ?? 0 }}</span>
                        </div>
                        <!-- /.info-box-content -->
                      </div>
                      <!-- /.info-box -->
                    </div>
                    
                    <div class="col-md-6">
                      <div class="info-box">
                        <span class="info-box-icon bg-aqua"><i class="fa fa-money"></i></span>

                        <div class="info-box-content">
                          <span class="info-box-text">Weekly Earnings</span>
                          <span class="info-box-number" data-number>&#8369; {{ $earnings['weekly'] ?? 0 }}</span>
                        </div>
                        <!-- /.info-box-content -->
                      </div>
                      <!-- /.info-box -->
                    </div>
                    
                    <div class="col-md-6">
                      <div class="info-box">
                        <span class="info-box-icon bg-aqua"><i class="fa fa-money"></i></span>

                        <div class="info-box-content">
                          <span class="info-box-text">Monthly Earnings</span>
                          <span class="info-box-number" data-number>&#8369; {{ $earnings['monthly'] ?? 0  }}</span>
                        </div>
                        <!-- /.info-box-content -->
                      </div>
                      <!-- /.info-box -->
                    </div>
                    
                    <div class="col-md-6">
                      <div class="info-box">
                        <span class="info-box-icon bg-aqua"><i class="fa fa-money"></i></span>

                        <div class="info-box-content">
                          <span class="info-box-text">Annual Earnings</span>
                          <span class="info-box-number" data-number>&#8369; {{ $earnings['annual'] ?? 0 }}</span>
                        </div>
                        <!-- /.info-box-content -->
                      </div>
                      <!-- /.info-box -->
                    </div>
                    
                    <div class="col-md-6">
                      <div class="info-box">
                        <span class="info-box-icon bg-aqua"><i class="fa fa-money"></i></span>

                        <div class="info-box-content">
                          <span class="info-box-text">Total Earnings</span>
                          <span class="info-box-number" data-number>&#8369; {{ $earnings['total'] ?? 0 }}</span>
                        </div>
                        <!-- /.info-box-content -->
                      </div>
                      <!-- /.info-box -->
                    </div>
                    
                    <div class="col-md-6">
                      <div class="info-box">
                        <span class="info-box-icon bg-aqua"><i class="fa fa-money"></i></span>

                        <div class="info-box-content">
                          <span class="info-box-text">Total Commissions</span>
                          <span class="info-box-number" data-number>&#8369; {{ $earnings['total_commissions'] ?? 0 }}</span>
                        </div>
                         {{-- /.info-box-content  --}}
                      </div>
                       {{-- /.info-box  --}}
                    </div>
                </div>
            </div>
            <div class="col-md-12 text-right">
                <small><i>Note: Earnings updates every 5 minutes. {{ $earnings['last_updated'] ?? '' }}</i></small>
            </div>
        </div>
    </div>
    <div class="m-container2" style="width: 98%">
        <div class="main-ct">
            <div class="title2">History</div>
            <div class="clearfix"></div>
            <div class="blk-1">
                <div class="col-md-12">
                      <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#bets" aria-controls="bets" role="tab" data-toggle="tab">Predictions</a></li>
                        <li role="presentation"><a href="#deposits" aria-controls="home" role="tab" data-toggle="tab">Purchases</a></li>
                        <li role="presentation"><a href="#cashout" aria-controls="cashout" role="tab" data-toggle="tab">Transfers</a></li>
                        <li role="presentation"><a href="#partner_deposit" aria-controls="home" role="tab" data-toggle="tab">Purchases via Partner</a></li>
                        <li role="presentation"><a href="#partner_cashout" aria-controls="cashout" role="tab" data-toggle="tab">Transfers via Partner</a></li>
                        {{-- <li role="presentation"><a href="#referals" aria-controls="referrals" role="tab" data-toggle="tab">Referrals</a></li> --}}
                        {{-- <li role="presentation"><a href="#rewards" aria-controls="rewards" role="tab" data-toggle="tab">Rewards</a></li> --}}
                        <li role="presentation"><a href="#gift_codes" aria-controls="gift_codes" role="tab" data-toggle="tab">Gift Codes</a></li>
                        <li role="presentation"><a href="#bugs" aria-controls="bugs" role="tab" data-toggle="tab">Reported Bugs</a></li>
                        {{-- <li role="presentation"><a href="#promos" aria-controls="promos" role="tab" data-toggle="tab">Promotions</a></li> --}}
                        {{-- <li role="presentation"><a href="#rebates" aria-controls="rebates" role="tab" data-toggle="tab">Rebates</a></li> --}}
                        @if($user_badges->where('name','like','AFFILIATE')->count() != 0 || $user_badges->where('name','like','SUB-AFFILIATE')->count() != 0)
                            <li role="presentation"><a href="#commissions" aria-controls="commissions" role="tab" data-toggle="tab">Commissions</a></li>
                            <li role="presentation"><a href="#commissions_partners" aria-controls="commissions_partners" role="tab" data-toggle="tab">Commissions via Partners</a></li>
                            <li role="presentation"><a href="#commissions_bets" aria-controls="commissions_bets" role="tab" data-toggle="tab">Commissions from Bets</a></li>
                        @endif
                        @if(Auth::id() == 585)
                            <li role="presentation"><a href="#commissions_sub_streamers" aria-controls="commissions_sub_streamers" role="tab" data-toggle="tab">SUB Streamers - Commissions</a></li>
                        @endif
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane fade in active" id="bets" role="tabpanel">
                            <div class="tab-content" style="margin-top: 20px">
                                <table id="bets_table" class="table table-striped" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Match</th>
                                            <th>Tournament</th>
                                            <th>Team Selected</th>
                                            <th>Team Ratio</th>
                                            <th>Total Bet</th>
                                            <th>Potential Winnings</th>
                                            <th>Status</th>
                                            <th>Profit/Loss</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade in" id="deposits" role="tabpanel">
                            <div class="tab-content" style="margin-top: 20px">
                                <table id="deposits_table" class="table table-striped" width="100%">
                                    <thead>
                                        <tr>
                                            <!-- Data priority sequence -->
                                            <th data-priority="1">BC #</th>
                                            <th>Date</th>
                                            <th data-priority="3">Amount</th>
                                            <th>MOP</th>
                                            <th>Status</th>
                                            <th>Details</th>
                                            <th data-priority="2">Receipt</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade in" id="cashout" role="tabpanel">
                            <div class="tab-content" style="margin-top: 20px">
                                <table id="cashout_table" class="table table-striped" width="100%">
                                    <thead>
                                        <tr>
                                            <th>CO #</th>
                                            <th>Date</th>
                                            <th>Amount</th>
                                            <th>MOP</th>
                                            <th>Donation</th>
                                            <th>Status</th>
                                            <th>Details</th>
                                            <th>Receipt</th>
                                            <th>Notes</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade in" id="referals" role="tabpanel">
                            <div class="tab-content" style="margin-top: 20px">
                                <table id="referals_table" class="table table-striped" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Code</th>
                                            <th>Refered User</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade in" id="rewards" role="tabpanel">
                            <div class="tab-content" style="margin-top: 20px">
                                <table id="rewards_table" class="table table-striped" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Credits</th>
                                            <th>Reasons</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade in" id="gift_codes" role="tabpanel">
                            <div class="tab-content" style="margin-top: 20px">
                                <table id="gift_codes_table" class="table table-striped" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Date Redeemed</th>
                                            <th>Code</th>
                                            <th>Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>                        
                        <div class="tab-pane fade in" id="bugs" role="tabpanel">
                            <div class="tab-content" style="margin-top: 20px">
                                <table id="bugs_table" class="table table-striped" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Comment</th>
                                            <th>Admin Comment</th>
                                            <th>Image</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade in" id="promos" role="tabpanel">
                            <div class="tab-content" style="margin-top: 20px">
                                <table id="promo_table" class="table table-striped" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Comment</th>
                                            <th>Admin Comment</th>
                                            <th>Link</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade in" id="rebates" role="tabpanel">
                            <div class="tab-content" style="margin-top: 20px">
                                <table id="rebates_table" class="table table-striped" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Transaction Code</th>
                                            <th>Amount Deposited</th>
                                            <th>Amount Earned</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade in" id="commissions" role="tabpanel">
                            <div class="tab-content" style="margin-top: 20px">
                                <table id="commissions_table" class="table table-striped" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Name</th>
                                            <th>Amount Deposited</th>
                                            <th>Amount Earned</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="tab-pane fade in" id="commissions_partners" role="tabpanel">
                            <div class="tab-content" style="margin-top: 20px">
                                <table id="commissions_partners_table" class="table table-striped" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Name</th>
                                            <th>Amount Deposited</th>
                                            <th>Amount Earned</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="tab-pane fade in" id="commissions_bets" role="tabpanel">
                            <div class="tab-content" style="margin-top: 20px">
                                <table id="commissions_bets_table" class="table table-striped" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Match Date Settled</th>
                                            <th>ID</th>
                                            <th>Earnings</th>
                                            <th>Bets</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>           
                            
                        <div class="tab-pane fade in" id="commissions_sub_streamers" role="tabpanel">
                            <div class="tab-content" style="margin-top: 20px">
                                <table id="commissions_sub_streamers_table" class="table table-striped" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Sub Streamer</th>
                                            <th>Voucher Code</th>
                                            <th>Total Earned (Unpaid)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>    
                        <div class="tab-pane fade in" id="deposit_commissions" role="tabpanel">
                            <div class="tab-content" style="margin-top: 20px">
                                <table id="deposit_commissions_table" class="table table-striped" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Name</th>
                                            <th>Amount Deposited</th>
                                            <th>Amount Earned</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade in" id="partner_deposit" role="tabpanel">
                            <div class="tab-content" style="margin-top: 20px">
                                <table id="deposit_partner_table" class="table table-striped" width="100%">
                                    <thead>
                                        <tr>
                                            <th>BC #</th>
                                            <th>Partner</th>
                                            <th>Date</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                            <th>Details</th>
                                            <th>Receipt</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade in" id="partner_cashout" role="tabpanel">
                            <div class="tab-content" style="margin-top: 20px">
                                <table id="cashout_partner_table" class="table table-striped" width="100%">
                                    <thead>
                                        <tr>
                                            <th>CO #</th>
                                            <th>Partner</th>
                                            <th>Date</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                            <th>Details</th>
                                            <th>Receipt</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                        
                </div>
            </div>
        </div>
    </div>

    <!-- Modal viewReceipt-->
    <div class="modal fade" id="viewReceipt" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Receipt</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal viewReceipt-->
    <div class="modal fade" id="view-details" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Details</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <ul class="list-unstyled" id="deposit-steps"></ul>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Details-->
    <div class="modal fade" id="modal-details" tabindex="-1" role="dialog" aria-labelledby="modal-details">
      <div class="modal-dialog " role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="exampleModalLabel">Details</h4>
          </div>
          <div class="modal-body">
            <dl class="dl-horizontal">
    
            </dl>     
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Bug Modal-->
    <div id="bugModal" class="modal fade" role="dialog">
        <div class="modal-dialog" style="width: 400px">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Report Bug</h4>
                </div>
                <div class="modal-body" >
                    <form id="bugForm" enctype="multipart/form-data">

                        <div class="alert alert-danger print-error-msg" style="display:none">
                            <ul></ul>
                        </div>
                        <div class="form-group">
                            <label>Subject:</label>
                            <input type="text" name="subject" placeholder="Subject" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Content:</label>
                            <textarea name="comment" class="form-control" placeholder="Comment" rows="8"></textarea>
                        </div>

                        <div class="form-group">
                            <label>Photo:</label>
                            <input type="file" name="picture" class="form-control" accept="image/*">
                        </div>
                        
                        <div class="alert alert-info">
                            <strong>To submit a report please follow this steps!</strong> <br>1. Description - describe the bug <br> 2. Write steps to replicate the bug <br> 3. Attach screenshots of errors if applicable
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button id="add-bug-report-btn" type="button" class="btn btn-primary">Submit Ticket</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bug Modal-->
    <div id="promoModal" class="modal fade" role="dialog">
        <div class="modal-dialog" style="width: 400px">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Promotion</h4>
                </div>
                <div class="modal-body" >
                    <form id="promoForm" enctype="multipart/form-data">

                        <div class="alert alert-danger print-error-msg" style="display:none">
                            <ul></ul>
                        </div>
                        <div class="form-group">
                            <label>Comment:</label>
                            <textarea name="comment" class="form-control" placeholder="Comment"></textarea>
                        </div>

                        <div class="form-group">
                            <label>Link:</label>
                            <textarea name="link" class="form-control" placeholder="Comment"></textarea>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button id="add-promo-btn" type="button" class="btn btn-primary">Add Promo</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    @if(Auth::user()->voucher_code)
        <!-- Commissions Bets Modal-->
        <div id="commissionsBetsModal" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header text-center">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Commissions from Match ID: <span id="match_id">0</span></h4>
                        <h4 class="modal-title">Total Earnings on this match: <span id="total_earnings">0</span></h4>
                    </div>
                    <div class="modal-body" >
                        <div id="commissions-bets-container" style="max-height: 350px; overflow-y: scroll; padding: 10px;"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>


    <!-- downloading partner transactions modal -->

    <div id="partner_download_transactions_modal" class="modal fade" role="dialog">
        <div class="modal-dialog" >
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h5 class="modal-title"><strong>{{ Auth::user()->voucher_code }}</strong> Voucher Code Users  </h5>
                </div>
                <div class="modal-body" >
                    <form id="affiliate-voucher-users-download-form" method="POST" class="form-horizontal">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">From: </label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control datetime_sched" name="fromDate" placeholder="Select date from" required />
                                <span class="error-label"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">To: </label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control datetime_sched" name="toDate" placeholder="Select date until" required />
                                <span class="error-label"></span>
                            </div>
                        </div>
                    </form>

                    <div>
                        <strong>Users:</strong>
                    </div>
                    <textarea id="affiliates-voucher-users" class="form-control" rows="5" style="max-width: 100%;" readonly></textarea>
                </div>
                <div class="modal-footer">
                    <button id="proceed-download-btn" type="button" class="btn btn-primary">Get Users</button>
                    <button id="clear-voucher-users-textarea" type="button" class="btn btn-info">Clear</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- end downloading parnter transactions modal -->

    <!-- getting new voucher code users of streamers modal -->

    <div id="get_new_voucher_code_users_modal" class="modal fade" role="dialog">
        <div class="modal-dialog" >
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h5 class="modal-title"><strong>{{ Auth::user()->voucher_code }}</strong> Voucher Code Users - [<strong>NEW</strong>]</h5>
                </div>
                <div class="modal-body" >
                    <form id="affiliate-voucher-users-download-form" method="POST" class="form-horizontal">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">From: </label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control datetime_sched" name="fromDate" placeholder="Select date from" required />
                                <span class="error-label"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">To: </label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control datetime_sched" name="toDate" placeholder="Select date until" required />
                                <span class="error-label"></span>
                            </div>
                        </div>
                    </form>

                    <div>
                        <strong>Users:</strong>
                    </div>
                    <textarea id="affiliates-voucher-users" class="form-control" rows="5" style="max-width: 100%;" readonly></textarea>
                </div>
                <div class="modal-footer">
                    <button id="proceed-get-voucher-users-btn" type="button" class="btn btn-primary">Get Users</button>
                    <button id="clear-voucher-users-textarea" type="button" class="btn btn-info">Clear</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- end getting new voucher code users of streamers modal   -->

    @endif

    @if(!empty($hasBadgePartner))
        <div id="partnerInfoModal" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Update Partner Information</h4>
                    </div>
                    <div class="modal-body" >
                        <form id="partnerForm" enctype="multipart/form-data">

                            <div class="alert alert-danger print-error-msg" style="display:none">
                                <ul></ul>
                            </div>

                            <div class="form-group">
                                <label class="control-label">Partner Name: <b style="color: red;">*</b></label>
                                <input type="text" name="partner_name" id="partner_name" class="form-control" placeholder="Partner Name">
                                <label class="error-label">Please fill it up</label>
                            </div>

                            <div class="form-group">
                                <label class="control-label">Address: <b style="color: red;">*</b></label><br>
                                <div class="form-twin-column" style="float: left;">
                                    <input type="text" name="partner_address" id="partner_address" class="form-control" placeholder="Street Address">
                                </div>
                                <div class="form-twin-column" style="float: right;">
                                    <select name="partner_province" id="partner_province" class="form-control">
                                        <option selected disabled>- Select City/Municipality -</option>
                                        @foreach($provinces as $province)
                                        <option value="{{ $province->id }}">{{ $province->province }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <label class="error-label">Please fill it up</label>
                            </div>

                            <div class="form-group">
                                <label class="control-label">Contact Number(s): <b style="color: red;">*</b></label><br>
                                <div class="form-twin-column" style="float: left;">
                                    <input type="text" name="partner_mobile" id="partner_mobile" class="form-control" placeholder="Mobile Number">
                                </div>
                                <div class="form-twin-column" style="float: right;">
                                    <input type="text" name="partner_telephone" id="partner_telephone" class="form-control" placeholder="Telephone Number">
                                </div>
                                <label class="error-label">Please fill it up</label>
                            </div>

                            <div class="form-group">
                                <label class="control-label">Contact Person: <b style="color: red;">*</b></label>
                                <input type="text" name="partner_person" id="partner_person" class="form-control" placeholder="Contact Person">
                                <label class="error-label">Please fill it up</label>
                            </div>

                            <div class="form-group">
                                <label class="control-label">Email: <b style="color: red;">*</b></label>
                                <input type="text" name="partner_email" id="partner_email" class="form-control" placeholder="Email">
                                <label class="error-label">Please fill it up</label>
                            </div>

                            <div class="form-group">
                                <label class="control-label">Facebook Profile Link: <b style="color: red;">*</b></label>
                                <input type="text" name="partner_fb_link" id="partner_fb_link" class="form-control" placeholder="Facebook Profile Link">
                                <label class="error-label">Please fill it up</label>
                            </div>

                            <div class="form-group">
                                <label class="control-label">Logo: <b style="color: red;">*</b></label>&nbsp;<span>{{ $partner ? " - Already uploaded a logo" : "" }}</span>
                                <input id="partner_select_logo" name="image" accept="image/*" class="file-loading partner-select-logo" type="file">
                                <label class="error-label">Please choose your logo</label>
                            </div>

                            <div class="form-group">
                                <label>Operational Days & Hours: <b style="color: red;">*</b></label>
                                <textarea name="partner_operation" id="partner_operation" class="form-control" placeholder="Monday - Sunday, 8:00AM-8:00PM" rows="8"></textarea>
                                <label class="error-label">Please fill it up</label>
                            </div>

                            <div class="form-group">
                                <label>Modes of Payment: <b style="color: red;">*</b></label>
                                <textarea name="partner_mop" id="partner_mop" class="form-control" placeholder="E.g. Walk in deposit, walk in cashout" rows="8"></textarea>
                                <label class="error-label">Please fill it up</label>
                            </div>

                            <div class="form-group">
                                <label>Additional Details: <b style="color: red;">*</b></label>
                                <textarea name="partner_details" id="partner_details" class="form-control" placeholder="E.g. We are still open in holodays" rows="8"></textarea>
                                <label class="error-label">Please fill it up</label>
                            </div>

                            <div class="form-group">
                                <label class="control-label">Bank Accounts (for Purchases and Cashouts): <b style="color: red;">*</b></label><br>
                                <div class="form-twin-column" style="float: left;">
                                    <label class="control-label">BPI Account No.:</label>
                                    <input type="text" name="partner_bpi" id="partner_bpi" class="form-control" placeholder="BPI Account Number" style="margin-bottom: 10px;">
                                    <input type="text" name="partner_bpi_name" id="partner_bpi_name" class="form-control" placeholder="BPI Account Name">
                                </div>
                                <div class="form-twin-column" style="float: right;">
                                    <label class="control-label">BDO Account No.:</label>
                                    <input type="text" name="partner_bdo" id="partner_bdo" class="form-control" placeholder="BDO Account Number" style="margin-bottom: 10px;">
                                    <input type="text" name="partner_bdo_name" id="partner_bdo_name" class="form-control" placeholder="BPI Account Name">
                                </div>
                                <label class="error-label">Please choose one of the bank accounts and fill it up</label>
                            </div>
                            
                            <div class="alert alert-info" style="margin-top: 115px;">
                                <strong>Notes:</strong> 
                                <br>1. Telephone Number is optional. You can leave it blank if you don't have. 
                                <br>2. You can have one bank account between BPI and BDO, or both of them. Leave one of the fields empty if you only have one. 
                                <br>3. If you have both bank accounts, choose one for transacting cashouts. Your current bank account will be chosen if you only have one.
                            </div>

                        </form>
                    </div>
                    <div class="modal-footer">
                        <button id="update-partner-info-btn" type="button" class="btn btn-primary">Update</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>
@endsection
@section('script')
<script type="text/javascript" src="{{ asset('js/select2/select2.full.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/fileinput.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/theme.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/datatables.min.js')}}"></script>
<script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.1/js/dataTables.responsive.min.js"></script>
<script type="text/javascript" src="{{ asset('js/viewer.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/moment.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('bower_components/bootstrap-sweetalert/dist/sweetalert.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('/bower_components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js') }}"></script>
<script src="{{ asset('js/datatables-datetime-moment.js') }}"></script>
<script src="{{ asset('js/mustache.min.js') }}"></script>
<script id="coinsph-steps-template" type="text/template">
    <li>1. Send the amount <insert amount> to this wallet: <strong>{{$settings['coins-wallet-address']}}</strong></li>
    <li>2. Take screenshot</li>
    <li>3. Edit the screenshot and write your BC-code <span style="font-weight: bold;">@{{code}}</span></li>
    <li>4. Upload the edited reciept with your bccode</li>
    <li>5. Wait for approval.</li>
</script>
{{-- <script id="manual-steps-template" type="text/template">
    <li>1. Visit any branch of @{{bank}}, the branch will be open around @{{bopen}} to @{{bclose}}, malls will be open at @{{mopen}} closes at @{{mopen}}.</li>
    <li>2. Get the Blue Slip (deposit slip) from the Guard or at the provided shelf with desk provided for slips.</li>
    <li>3. Fill out the necessary fields.</li>
        <ul>
            <li>Account Name: <b>@{{accountname}}</b></li>
            <li>Account Number: <b>@{{accountnumber}}</b></li>
            <li>Date of the Day: <b>(MM/DD/YY)</b></li>
            <li>Insert amount: <span style="font-weight: bold;">&#8369; @{{amount}}</span></li>
        </ul>
    <li>4. Get into the line for bank deposit.</li>
    <li>5. Keep the Official RECEIPT of the tranasaction.</li>
    <li>6. Write the BC Code <span style="font-weight: bold;">@{{code}}</span> ontop of the Official Receipt, and take a picture of it. (make sure the Image and the details written are clear).</li>
</script> --}}
<script id="manual-steps-template" type="text/template">
    <li>1. Copy your BC Code <span style="font-weight: bold;">@{{code}}</span></li>
    <li>2. Then PM or message our facebook page <a href="https://www.facebook.com/2ez.bet/">2ez.bet</a> to help you with your purchase. We are available 24/7!</li>
    <li><img src="/images/fb-2ez-page.jpg" style="width:100%; margin-top: 10px;"/></li>
</script>
<script id="BDO-desktop" type="text/template">
    <li>1. Log in to your BDO.COM, enter the user ID and Password.</li>
    <li>2. Enter the OTP that was sent via SMS by BDO Online on your mobile number.</li>
    <li>3. At the right of your screen, select FINANCIAL SERVIES and click on TRANSFER MONEY.</li>
    <li>4. Enter the TRANSFER FROM box, from the desired account where you want to credit your pay.</li>
    <li>5. 2EZ.BET Account number is <b>{{$settings['bdo-account-number']}}</b>, enter the desired AMOUNT on the next box.</li>
    <li>6. <span style="color:red;font-weight: bold">Important: </span>Write your BC CODE on remarks <span style="font-weight: bold;">@{{code}}</span>.</li>
    <li>7. Then screen shot the transaction once you press the SUBMIT button.</li>
</script>
<script id="BPI-desktop" type="text/template">
    <li>1. Go to BPI.com.ph, enter the user ID and Password.</li>
    <li>2. Go to fund transfer tab then select fund transfer on the drop down menu, then click the Transfer funds today.</li>
    <li>3. Fill out the required information, the AMOUNT to transfer, select the source amount, select the enrolled account of 2ez.bet with the account number <b>{{$settings['bpi-account-number']}}</b> and add remarks.</li>
    <li>4. And click Transfer Now/Sumit Button.</li>
    <li>5. <span style="color:red;font-weight: bold">Important: </span>Write your BC CODE on remarks <span style="font-weight: bold;">@{{code}}</span>.</li>
    <li>6. Make a screen shot of the transaction.</li>
</script>
<script id="Metrobank-desktop" type="text/template">
    <li>1. Go tometrobank.com.ph, enter the user IDandPassword.</li>
    <li>2. Go to fund transfertab then selectfund transferon the drop down menu, then click theTransfer funds today.</li>
    <li>3. Fill out the required information, the AMOUNT to transfer, select the source amount, select the enrolled account of 2EZ.bet with the account number <b>{{$settings['metro-account-number']}}</b> and add remarks.</li>
    <li>4. And click Transfer Now.</li>
    <li>5. Then press the SUBMIT button.</li>
    <li>6. And screen shot the transaction promt after you press the Submit Button.</li>
</script>
<script id="Securitybank-desktop" type="text/template">
    <li>1. Go to your Security bank mobile banking app.</li>
    <li>2. Click transfer on the tab below</li>
    <li>3. Select transfer to others.</li>
    <li>4. Enter the account number <b>{{$settings['security-account-number']}}.</b></li>
    <li>5. Enter the amount.</li>
    <li>6. Place the bc code on the remarks <b>@{{code}}</b>.</li>
    <li>7. Click confirm transfer.</li>
    <br/>
    <strong>Note: You may follow up on your transactions by messaging the <a href="https://www.facebook.com/2ez.bet/">2ez.bet</a> Facebook Page.</strong>
</script>
<script id="cebuana-remittance" type="text/template">
    <li>1. Visit any Cebuana Remmitance Center,and take a Priority number from the Guard (remmitance botiques outside the malls opens at 8AM to 5PM, while the malls opens 10AM to 8:30PM).</li>
    <li>2. Fill out the Cebuana Pera Padala Format the neceassary fields</li>
        <ul>
            <li>Receiver Name: <b>{{$settings['remittance-name']}}</b></li>
            <li>Receiver Number: <b>{{$settings['remittance-number']}}</b></li>
            <li>Receiver Location: <b>{{$settings['remittance-location']}}</b></li>
            <li>Sender Name: <b>(Indicate your name)</b></li>
            <li>Insert amount: <span style="font-weight: bold;">&#8369; @{{amount}}</span></li>
        </ul>
    <li>3. On the Transaction Type Check the box of Sending.</li>
    <li>4. Place the desired amount to send at Sending/Receiving Amount.</li>
    <li>5. And on the Buttom sign the signature over printed name.</li>
    <li>6. Submit the Form together with your I.D. And Amount at the counter (any I.D. Will do as long as it is a goverment issued I.D. With a picture, your signature and name on it and not expired).</li>
    <li>7. Once the transaction is complete. Write the BC Code  <span style="font-weight: bold;">@{{code}}</span>  and 10-digit control number on top  of the Official RECEIPT.</li>
</script>
<script id="mlhuiller-remittance" type="text/template">
    <li>1. Visit any MLhuillier Kwarta Padala Remmitance Center,and take a Priority number from the Guard (remmitance botiques outside the malls opens at 8AM to 5PM, while the malls opens 10AM to 8:30PM).</li>
    <li>2. Fill out the MLhuillier Kwarta Padala Format the neceassary fields</li>
        <ul>
            <li>Receiver Name: <b>{{$settings['remittance-name']}}</b></li>
            <li>Receiver Number: <b>{{$settings['remittance-number']}}</b></li>
            <li>Receiver Location: <b>{{$settings['remittance-location']}}</b></li>
            <li>Sender Name: <b>(Indicate your name)</b></li>
            <li>Insert amount: <span style="font-weight: bold;">Php @{{amount}}</span></li>
        </ul>
    <li>3. On the Transaction Type Check the box of Sending.</li>
    <li>4. Place the desired amount to send at Sending/Receiving Amount.</li>
    <li>5. And on the Buttom sign the signature over printed name.</li>
    <li>6. Submit the Form together with your I.D. And Amount at the counter (any I.D. Will do as long as it is a goverment issued I.D. With a picture, your signature and name on it and not expired).</li>
    <li>7. Once the transaction is complete. Write the BC Code  <span style="font-weight: bold;">@{{code}}</span>  and 10-digit control number on top  of the Official RECEIPT.</li>
</script>
<script id="lbc-remittance" type="text/template">
    <li>1. Visit any LBC Center,and take a Priority number from the Guard (remmitance botiques outside the malls opens at 8AM to 5PM, while the malls opens 10AM to 8:30PM).</li>
    <li>2. Fill out the LBC Money Remmitance Format the neceassary fields.</li>
        <ul>
            <li>Receiver Name: <b>{{$settings['remittance-name']}}</b></li>
            <li>Receiver Number: <b>{{$settings['remittance-number']}}</b></li>
        </ul>
    <li>3. Place the desired amount to send.</li>
    <li>4. Place your Mobile number.</li>
    <li>5. Submit the Form together with your 2 Valid I.D. And Amount at the counter (any I.D. Will do as long as it is a goverment issued I.D. With a picture, your signature and name on it and not expired).</li>
    <li>6. Once the transaction is complete, write the 12-Digit Tracking number and BC Code <span style="font-weight: bold;">@{{code}}</span> on the Official Receipt andtake a picture of the Official RECEIPT.</li>
</script>
<script id="palawan-remittance" type="text/template">
    <li>1. Visit any Palawan Express Remittance Center,and take a Priority number from the Guard (remmitance botiques outside the malls opens at 8AM to 5PM, while the malls opens 10AM to 8:30PM).</li>
    <li>2. Fill out the Palawan Express Sendout Format the neceassary fields.</li>
        <ul>
            <li>Receiver Name: <b>{{$settings['remittance-name']}}</b></li>
            <li>Receiver Number: <b>{{$settings['remittance-number']}}</b></li>
        </ul>
    <li>3. Place your Mobile number.</li>
    <li>4. Place the desired amount to send.</li>
    <li>5. And on the Buttom sign the signature over printed name.</li>
    <li>6. submit the Form together with your 2 Valid I.D. And Amount at the counter (any I.D. Will do as long as it is a goverment issued I.D. With a picture, your signature and name on it and not expired).</li>
    <li>7. Once the transaction is complete, write the Tracking number and BC Code <span style="font-weight: bold;">@{{code}}</span> on the Official Receipt and take a picture of the Official RECEIPT.</li>
</script>
<script id="western-remittance" type="text/template">
    <li>1. Visit any Western Union Remittance Center,and take a Priority number from the Guard (remmitance botiques outside the malls opens at 8AM to 5PM, while the malls opens 10AM to 8:30PM).</li>
    <li>2. Fill out the Palawan Express Sendout Format the neceassary fields.</li>
        <ul>
            <li>Receiver Name: <b>{{$settings['remittance-name']}}</b></li>
            <li>Receiver Number: <b>{{$settings['remittance-number']}}</b></li>
        </ul>
    <li>3. Place your Mobile number.</li>
    <li>4. Place the desired amount to send.</li>
    <li>5. And on the Buttom sign the signature over printed name.</li>
    <li>6. submit the Form together with your 2 Valid I.D. And Amount at the counter (any I.D. Will do as long as it is a goverment issued I.D. With a picture, your signature and name on it and not expired).</li>
    <li>7. Once the transaction is complete, write the Tracking number and BC Code <span style="font-weight: bold;">@{{code}}</span> on the Official Receipt and take a picture of the Official RECEIPT.</li>
</script>
<script id="partner-walkin" type="text/template">
    <li>1. Visit @{{partner}} at @{{address}} in this schedule, @{{schedule}}.</li>
    <li>2. Approach the contact person in the area and tell about the transaction code - <strong>@{{code}}</strong>.</li>
    <li>3. Pay for the amount to the person.</li>
    <li>4. The person will transfer the credits to your account once the payment is complete.</li>
</script>
<script type="text/javascript">
    $(document).ready(function() {
        $('.datetime_sched').datetimepicker({
            // viewMode: 'days',
            format: 'YYYY-MM-DD HH:mm:00'
        }).on('dp.change', function(e){ $(this).parent().removeClass('has-error'); });
    });
</script>
<script>
    var deposits_table
    var cashout_table
    var referal_table
    var bugs_table
    var promo_table
    var rebates_table
    var commissions_table
    var commissions_partners_table
    var commissions_bets_table
    var commissions_sub_streamers_table
    var deposit_commissions_table
    var deposit_partner_table
    var cashout_partner_table
    var commissionBets = null;
    $(function(){
        $.fn.dataTable.ext.errMode = 'none';
        $.fn.dataTable.moment('llll');
        deposits_table = $('#deposits_table').DataTable({
            initComplete : function() {
                var input = $('#deposits_table_filter input').unbind(),
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
                $('#deposits_table_filter').append($searchButton, $clearButton);
            },              
            processing: true,
            serverSide:true,
            responsive: true,
            deferLoading: 0,
            ajax: "{!! route('get_profile_transactions',['type' => 'deposit']) !!}",
            order: [[ 1, "desc" ]],
            drawCallback: function(settings) {
                // tips_view_table.draw()
                var data = this.api().rows().data()
                if (data.length > 0) {
                    for (var i = 0; i < data.length; i++) {
                        $(".image-"+data[i].id).fileinput({
                            previewFileType: "image",
                            theme: "fa",
                            responsive: true,
                            showUpload: false,
                            showCaption: false,
                            showRemove: false,
                            browseClass: "btn btn-primary btn-"+data[i].id+" btn-sm",
                            browseLabel: "Upload Image",
                            browseIcon: "<i class=\"fa fa-picture-o\"></i> ",
                            allowedFileExtensions: ["jpg", "gif", "png"],
                        }).on("filebatchselected", function(event, files) {
                            var data = deposits_table.row($(this).parents('tr') ).data();
                            console.log(data);
                            $.ajax({
                                url: '{{ route('upload') }}',
                                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                                type: 'POST',
                                data: {photo:$('.file-preview-image').attr('src'),id:data.id,status:'processing'},
                                success: function(data) {
                                    if (data.success) {
                                        deposits_table.ajax.reload()
                                        swal({
                                            title: "Your deposit request "+data.code+" is currently being processed.",
                                            text: 'It usually takes a few hours but sometimes can take a full day. Please contact our admins if your request has not been processed after 24 hours.',
                                            type: "success",
                                            html: true,
                                        });
                                    }
                                },
                                error: function(xhr, status, error) { 
                                    // File too large popup message
                                    swal("File is too large", "Image must not exceed 2MB file size and 2500x2500 dimension ","error");
                                    console.log('error');
                                }
                            });

                        });
                    }

                }
            },
            columns:[
                {data:'code',name:'code'},
                {
                    data:'created_at',
                    name:'created_at',
                    render: function(data,type,row){
                        return moment(data).format('llll')+'<br>'+moment(data).fromNow()
                    }
                },
                {
                    data:'amount',
                    name:'amount',
                    searchable: false,
                    render: function(data,type,row){
                        if (row.discrepancy.length > 0) {
                            if (row.discrepancy[row.discrepancy.length - 1 ].amount != null) {
                                return '&#8369; '+numberWithCommas(row.discrepancy[row.discrepancy.length - 1 ].amount);
                            }else{
                                return '&#8369; '+numberWithCommas(data);

                            }
                            
                        }else{
                            return '&#8369; '+numberWithCommas(data);
                        }
                    }
                },
                {
                    data:'data.mop',
                    name:'data.mop',
                    searchable: false,
                    orderable: false,
                    render: function(data,type,row){
                        var _status = '';
                        if(row['data'].request) {
                            switch(row['data'].request.status) {
                                case 'pending':
                                    _status = '<br/>(<span style="font-size: 90%; font-style: italic; color: #eea236">Waiting for Payment</span>)';
                                    break;
                                case 'complete':
                                    _status = '<br/>(<span style="font-size: 90%; font-style: italic;">Complete</span>)';
                                    break;
                            }
                        }
                        return data + _status;
                    }
                },
                {
                    data:'status',
                    searchable: false,
                    orderable: false,
                    render: function(data,type,row){
                        // return row['picture'] == null ? 'incomplete' : data == 'completed' ? 'Approved and Completed' : 'Needs Approval' 
                        if(data == 'rejected'){
                            return 'rejected'
                        }else{
                            if(row['picture'] == null){
                                return 'Incomplete'
                            }else{
                                switch (data) {
                                    case 'completed':
                                        return 'Approved and Completed'
                                    default:
                                        return 'Needs Approval'
                                }
                            }
                        }
                    }
                },
                {
                    data:'data',
                    searchable: false,
                    orderable: false,
                    render: function(data,type,row){
                        return  '<a href="#" class="btn btn-default view-details"><i class="fa fa-info" aria-hidden="true"></i></a>'
                    }
                },
                    // marwin
                {
                    data:'picture',
                    searchable:false,
                    orderable: false,
                    render: function(data,type,row){
                        if(row['status'] == 'rejected'){
                            return row['notes'].length > 0 ? row['notes'][0].message : '';
                        }else{
                            return  data != null ? '<div><a href="#" class="btn btn-default view-receipt"><i class="fa fa-picture-o" aria-hidden="true"></i></a></div>' : '-';
                            //return  data != null ? '<div><a href="#" class="btn btn-default view-receipt"><i class="fa fa-picture-o" aria-hidden="true"></i></a></div>' : '<input id="image-'+row['id']+'" name="image" accept="image/*" class="file-loading image-'+row['id']+'" type="file">'
                        }
                    }
                },
            ]

         })

         cashout_table = $('#cashout_table').DataTable({
            initComplete : function() {
                var input = $('#cashout_table_filter input').unbind(),
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
                $('#cashout_table_filter').append($searchButton, $clearButton);
            },               
            processing: true,
            serverSide:true,
            responsive: true,
            deferLoading: 0,
            ajax: "{!! route('get_profile_transactions',['type' => 'cashout']) !!}",
            order: [[ 1, "desc" ]],
            columns:[
                {data:'code',name:'code'},
                {
                    data:'created_at',
                    name:'created_at',
                    render: function(data,type,row){
                        return moment(data).format('llll')+'<br>'+moment(data).fromNow()
                    }
                },
                {
                    data:'amount',
                    name:'amount',
                    searchable:false,
                    render: function(data,type,row){
                        return '&#8369; '+numberWithCommas(data);
                    }
                },
                {data:'data.mop',searchable:false,orderable: false,name:'data.mop'},
                {
                    data:'donation',
                    searchable:false,
                    orderable: false,
                    render: function(data,type,row){
                        return data == null ? 'n/a' : data['amount']
                    }
                },
                {
                    data:'status',
                    searchable:false,
                    orderable: false,
                    render: function(data,type,row){
                        if(data == 'rejected'){
                            return 'Rejected'
                        }else{
                            if(row['picture'] == null){
                                return 'Processing'
                            }else{
                                return 'Processed'
                            }
                        }
                    }
                },
                {
                    data:'data',
                    searchable: false,
                    orderable: false,
                    render: function(data,type,row){
                        return  '<a href="#" class="btn btn-default view-details"><i class="fa fa-info" aria-hidden="true"></i></a>'
                    }
                },
                {
                    data:'picture',
                    searchable:false,
                    orderable: false,
                    render: function(data,type,row){
                        return  data != null ? '<div><a href="#" class="btn btn-default view-receipt"><i class="fa fa-picture-o" aria-hidden="true"></i></a></div>' : 'n/a'
                    }
                },
                {
                    data:'notes',
                    searchable:false,
                    orderable: false,
                    render: function(data,type,row){
                        return data.length == 0 ? 'n/a' : data[0].message
                    }
                },
            ]

         })

         referal_table = $('#referals_table').DataTable({
            initComplete : function() {
                var input = $('#referals_table_filter input').unbind(),
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
                $('#referals_table_filter').append($searchButton, $clearButton);
            },                
            processing: true,
            serverSide:true,
            responsive: true,
            deferLoading: 0,
            ajax: "{!! route('get-user-referals') !!}",
            order: [[ 1, "desc" ]],
            columns:[
                {
                    data:'created_at',
                    name:'created_at',
                    render: function(data,type,row){
                        return moment(data).format('llll')+'<br>'+moment(data).fromNow()
                    }
                },
                {
                    data:'code',
                    name:'code',
                },
                {data:'refered_user.name',name:'refered_user.name'},
                {
                    data:'code',
                    name:'code',
                    render: function(data,type,row){
                        var active = []
                        let transactions = row.refered_user.transactions
                        if(transactions.length > 0){
                            active = $.grep(transactions,function(transaction){
                                if(transaction.status == 'completed'){
                                    return transaction;
                                }
                            })
                        }
                        return active.length > 0 ? 'Bettor' : 'Inactive'
                    }
                },
            ]

         })
         
        reward_table = $('#rewards_table').DataTable({
            initComplete : function() {
                var input = $('#rewards_table_filter input').unbind(),
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
                $('#rewards_table_filter').append($searchButton, $clearButton);
            },               
           processing: true,
           serverSide:true,
           responsive: true,
           deferLoading: 0,
           ajax: "{!! route('get-user-rewards') !!}",
           order: [[ 1, "desc" ]],
           columns:[
               {
                   data:'created_at',
                   name:'created_at',
                   render: function(data,type,row){
                       return moment(data).format('llll')+'<br>'+moment(data).fromNow()
                   }
               },
               {
                   data:'credits',
                   name:'credits',
               },
               {
                   data:'description',
                   name:'description',
               },
           ]

        });

        gift_codes_table = $('#gift_codes_table').DataTable({
            initComplete : function() {
                var input = $('#gift_codes_table_filter input').unbind(),
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
                $('#gift_codes_table_filter').append($searchButton, $clearButton);
            },               
           processing: true,
           serverSide:true,
           responsive: true,
           deferLoading: 0,
           ajax: "{!! route('get-user-gift-codes') !!}",
           order: [[ 1, "desc" ]],
           columns:[
               {
                   data:'date_redeemed',
                   name:'date_redeemed',
                   render: function(data,type,row){
                       return moment(data).format('llll')+'<br>'+moment(data).fromNow()
                   }
               },
               {
                   data:'code',
                   name:'code',
               },
               {
                    data: 'amount',
                    render: function(data,type,row){
                        return '&#8369; '+numberWithCommas(data);
                    }
               },
           ]

        });        
         
        bets_table = $('#bets_table').DataTable({
            initComplete : function() {
                var input = $('#bets_table_filter input').unbind(),
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
                $('#bets_table_filter').append($searchButton, $clearButton);
            },              
            responsive: true,
            processing: true,
            serverSide:true,
            deferLoading: 0,
            ajax: "{!! route('json_tournament_userbets') !!}",
            order: [[ 0, "desc" ]],
            columns: [
                {data: 'updated_at'},
                {
                    data: 'match',
                    name: 'match.name',
                    render: function(data,type,row){
                        if(data) {
                            var url = "{{url('/')}}/match/" + data.id;
                            return '<a href="'+url+'" target="_new">' + data.name + '</a>';
                        } else {
                            var url = "{{url('/')}}/tournament/" + row['league'].id;
                            return '<a href="'+url+'" target="_new">' + row['league'].name + ' Winner</a>';
                        }
                    }
                },
                {
                    data: 'league',
                    name: 'league.name',
                    render: function(data,type,row){
                        if(data)
                            return data.name;
                        else
                            return row['tournament'];
                    }
                },
                {data: 'team.name'},
                {
                    data: 'ratio',
                    render: function(data,type,row){
                        return data ? parseFloat(data).toFixed(2) : 'N/A';
                    }
                },
                {
                    data: 'amount',
                    render: function(data,type,row){
                        return '&#8369; '+numberWithCommas(data);
                    }
                },
                {
                    data: 'amount',
                    name: 'amount',
                    render: function(data,type,row){
                        return '&#8369; '+numberWithCommas(parseFloat(row.potential_winnings).toFixed(2));
                    }
                },
                {
                    data: 'match',
                    name: 'match.status',
                    render: function(data,type,row){
                        if(data) {
                            switch(data.status) {
                                case 'open':
                                    return '<strong style="color: green">Open</strong>';
                                case 'ongoing':
                                    return '<strong style="color: green">Ongoing</strong>';
                                case 'closed':
                                case 'settled':
                                    if(row['team_id'] == data.team_winner)
                                        return 'Settled (<strong style="color: blue">Win</strong>)';
                                    else
                                        return 'Settled (<strong style="color: red">Loss</strong>)';
                                default:
                                    return data.status.toLowerCase().replace(/\b[a-z]/g, function(letter) {
                                        return letter.toUpperCase();
                                    });
                            }
                        } else {
                            if(row['type'] == 'tournament') {
                                switch(row['league'].betting_status) {
                                    case 0:
                                        if (row['league'].league_winner)
                                            return 'Closed';
                                        else
                                            return '<strong style="color: green">Ongoing</strong>';
                                        break;
                                    case 1:
                                        return '<strong style="color: green">Open</strong>';
                                        break;
                                    case -1:
                                        if(row['league'].league_winner == row['team_id'])
                                            return 'Settled (<strong style="color: blue">Win</strong>)';
                                        else
                                            return 'Settled (<strong style="color: red">Loss</strong>)';
                                        break;
                                    default:
                                        return 'Closed';
                                        break;
                                }
                            }
                        }
                    }
                },
                {
                    data: 'amount',
                    name: 'amount',
                    render: function(data,type,row){
                        if(row['match']) {
                            switch(row['match'].status) {
                                case 'cancelled':
                                case 'forfeit':
                                case 'draw':
                                    return 0;
                                case 'settled':
                                case 'closed':
                                    // var profit = row['potential_winnings'] - row['amount'];
                                    if(row['team_id'] == row['match'].team_winner)
                                        return '<span style="color:green">+'+numberWithCommas(parseFloat(row.gains).toFixed(2))+'</span>';
                                    else
                                        return '<span style="color:red">-'+numberWithCommas(row['amount'])+'</span>';
                                    break;
                                default:
                                    return 'N/A';
                            }
                        } else {
                            if(row['type'] == 'tournament') {
                                if(row['league'].betting_status == -1) {
                                    if(row['league'].league_winner == row['team_id']) {
                                        var _gains = row['potential_winnings'] - row.amount;
                                        return '<span style="color:green">+'+numberWithCommas(parseFloat(_gains).toFixed(2))+'</span>';
                                    } else
                                        return '<span style="color:red">-'+numberWithCommas(row['amount'])+'</span>';
                                } else
                                    return 'N/A';
                            } else
                                return 'N/A';
                        }
                    }
                },
            ]
        });

        bugs_table = $('#bugs_table').DataTable({ 
            initComplete : function() {
                var input = $('#bugs_table_filter input').unbind(),
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
                $('#bugs_table_filter').append($searchButton, $clearButton);
            },              
            processing: true,
            serverSide:true,
            responsive: true,
            deferLoading: 0,
            ajax: "{!! route('get-all-reported-bugs',['type' => 'profile']) !!}",
            order: [[ 0, "desc" ]],
            columnDefs: [
                {
                    render: function (data, type, full, meta) {
                        return "<div class='text-wrap width-200'>" + data + "</div>";
                    },
                    targets: 1
                }
            ],
            columns: [
                {data: 'created_at'},
                {data: 'comment'},
                {data: 'admin_comment'},
                {
                    data:'hasImage',
                    searchable:false,
                    render: function(data,type,row){
                        return  data ? '<a href="#" class="btn btn-default view-bug"><i class="fa fa-picture-o" aria-hidden="true"></i></a>' : 'n/a'
                    }
                },
                {
                    data:'status',
                    render: function(data,type,row){
                        switch (data) {
                            case 0:
                                return '<strong class="label label-primary">Processing</strong>'
                                break;
                            case 1:
                                return '<strong class="label label-success">Accepted</strong>'
                                break;
                            case 2:
                                return '<strong class="label label-danger">Rejected</strong>'
                                break;
                        
                            default:
                                break;
                        }
                        // return  data ? '<strong class="label label-success">Done</strong>' : '<strong class="label label-primary">Processing</strong>'
                    }
                },
            ]
        });


        promo_table = $('#promo_table').DataTable({ 
            initComplete : function() {
                var input = $('#promo_table_filter input').unbind(),
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
                $('#promo_table_filter').append($searchButton, $clearButton);
            },              
            processing: true,
            serverSide:true,
            responsive: true,
            deferLoading: 0,
            ajax: "{!! route('get-all-promo',['type' => 'profile']) !!}",
            order: [[ 0, "desc" ]],
            columns: [
                {data: 'created_at'},
                {data: 'comment'},
                {data: 'admin_comment'},
                {data: 'link'},
                // {
                //     data:'link',
                //     searchable:false,
                //     render: function(data,type,row){
                //         return  '<a href="'+(data)+'" target="_blank">'+(data)+'</a>'
                //     }
                // },
                {
                    data:'status',
                    render: function(data,type,row){
                        switch (data) {
                            case 0:
                                return '<strong class="label label-primary">Processing</strong>'
                                break;
                            case 1:
                                return '<strong class="label label-success">Accepted</strong>'
                                break;
                            case 2:
                                return '<strong class="label label-danger">Rejected</strong>'
                                break;
                        
                            default:
                                break;
                        }
                        // return  data ? '<strong class="label label-success">Done</strong>' : '<strong class="label label-primary">Processing</strong>'
                    }
                },
            ]
        });

        rebates_table = $('#rebates_table').DataTable({ 
            initComplete : function() {
                var input = $('#rebates_table_filter input').unbind(),
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
                $('#rebates_table_filter').append($searchButton, $clearButton);
            },            
            processing: true,
            serverSide:true,
            responsive: true,
            deferLoading: 0,
            ajax: "{!! route('get-rebates-profile') !!}",
            order: [[ 0, "desc" ]],
            columns: [
                {data: 'created_at', name:'created_at'},
                {data:'transaction.code', name:'transaction.code'},
                {
                    data:'transaction',
                    searchable: false,
                    orderable: false,
                    render: function (data,type,row) {
                        if (!!data.discrepancy && data.discrepancy.length > 0) {
                            if (data.discrepancy[data.discrepancy.length - 1 ].amount != null) {
                                return '&#8369; '+numberWithCommas(data.discrepancy[data.discrepancy.length - 1 ].amount);
                            }else{
                                return '&#8369; '+numberWithCommas(data.amount);

                            }
                            
                        }else{
                            return '&#8369; '+numberWithCommas(data.amount);
                        }
                    }
                },
                {data: 'collected'},
                {
                    data:'transfered',
                    name:'transfered',
                    searchable: false,
                    orderable: false,
                    render: function(data,type,row){
                        return data ? 'Transfered' : 'Not Transfered';
                    }
                },
            ]
        });

        commissions_table = $('#commissions_table').DataTable({ 
            initComplete : function() {
                var input = $('#commissions_table_filter input').unbind(),
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
                $('#commissions_table_filter').append($searchButton, $clearButton);
            },              
            processing: true,
            serverSide:true,
            responsive: true,
            deferLoading: 0,
            ajax: "{!! route('get-commissions-profile',['type' => 'profile']) !!}",
            order: [[ 0, "desc" ]],
            columns: [
                {data: 'created_at', name:'created_at'},
                {data:'transaction.user.name', name:'transaction.user.name'},
                {
                    data:'transaction.discrepancy',
                    name:'transaction.discrepancy',
                    render: function (data,type,row) {
                        if(data.length > 0){
                            var _dis = $.grep(data,function(discrepancy){
                                   if(discrepancy.amount){
                                       return discrepancy;
                                   }
                            });
                            return _dis.length > 0 ? '&#8369; '+numberWithCommas(_dis[_dis.length - 1].amount) : 
                                    '&#8369; '+numberWithCommas(row.transaction.amount);
                        }else{
                            return '&#8369; '+numberWithCommas(row.transaction.amount)
                        }
                    }
                },
                {data: 'amount'},
                {
                    data:'status',
                    name:'status',
                    searchable: false,
                    orderable: false,
                    render: function(data,type,row){
                        return data ? 'Paid' : 'Unpaid';
                    }
                },
            ]
        });

        commissions_partners_table = $('#commissions_partners_table').DataTable({ 
            initComplete : function() {
                var input = $('#commissions_partners_table_filter input').unbind(),
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
                $('#commissions_partners_table_filter').append($searchButton, $clearButton);
            },              
            processing: true,
            serverSide:true,
            responsive: true,
            deferLoading: 0,
            ajax: "{!! route('get-commissions-partners-profile',['type' => 'profile']) !!}",
            order: [[ 0, "desc" ]],
            columns: [
                {data: 'created_at', name:'created_at'},
                {data:'transaction.user.name', name:'transaction.user.name'},
                {data:'transaction.amount', name:'transaction.amount'},
                {data: 'amount'},
                {
                    data:'status',
                    name:'status',
                    searchable: false,
                    orderable: false,
                    render: function(data,type,row){
                        return data ? 'Paid' : 'Unpaid';
                    }
                },
            ]
        });        

        commissions_bets_table = $('#commissions_bets_table').DataTable({ 
            initComplete : function() {
                var input = $('#commissions_bets_table_filter input').unbind(),
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
                $('#commissions_bets_table_filter').append($searchButton, $clearButton);
            },              
            processing: true,
            serverSide:true,
            responsive: true,
            deferLoading: 0,
            ajax: "{!! route('get-commissions-bets-profile',['type' => 'profile']) !!}",
            order: [[ 0, "desc" ]],
            columns: [
                {data: 'date_settled', name:'date_settled'},
                {
                    data:'match_id', 
                    name:'match_id',
                    render: function(data,type,row){
                        const matchLink = `/match/${data}`;
                        return `<a href="${matchLink}" target="_blank">${data}</a>`;
                    }
                },
                {data:'amount', name:'amount'},
                {
                    data: 'user_bets', 
                    name: 'user_bets',
                    searchable: false,
                    orderable: false,
                    render: function(data,type,row){
                        const bets = Object.values(data);
                        let returnHtml = ``;
                        returnHtml += row.type == 'own' ? `Total Bets: ${bets.length} 
                                    <a class="btn btn-xs btn-primary view-commissions-bets-btn" href="#" data-match-id="${row.match_id}" data-amount="${row.amount}" >
                                        View Bets
                                    </a>
                                    ` :  `Total Bets: ${bets.length} <small>(FROM SUB)</small>`;
                        return returnHtml
                    }                    
                },                    
                {
                    data:'status',
                    name:'status',
                    searchable: false,
                    orderable: false,
                    render: function(data,type,row){
                        return data ? 'Paid' : 'Unpaid';
                    }
                },
            ]
        });   

        commissions_sub_streamers_table = $('#commissions_sub_streamers_table').DataTable({ 
            initComplete : function() {
                var input = $('#commissions_sub_streamers_table_filter input').unbind(),
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
                $('#commissions_sub_streamers_table_filter').append($searchButton, $clearButton);
            },              
            processing: true,
            serverSide:true,
            responsive: true,
            deferLoading: 0,
            ajax: "{!! route('get-commissions-subs-total-profile',['type' => 'profile']) !!}",
            order: [[ 0, "desc" ]],
            columns: [
                {data: 'sub_owner.name', name:'sub_owner.name'},
                {data: 'sub_owner.voucher_code', name:'sub_owner.voucher_code'},
                {data: 'totalAmount', name:'totalAmount'},
            ]
        });           
        // deposit_commissions_table = $('#deposit_commissions_table').DataTable({ 
        //     processing: true,
        //     serverSide:true,
        //     responsive: true,
        //     ajax: "{!! route('get-deposit-commissions') !!}",
        //     order: [[ 0, "desc" ]],
        //     columns: [
        //         {data: 'created_at', name:'created_at'},
        //         {
        //             data:'transaction.discrepancy',
        //             name:'transaction.discrepancy',
        //             render: function (data,type,row) {
        //                 if(data.length > 0){
        //                     var _dis = $.grep(data,function(discrepancy){
        //                         if(discrepancy.amount){
        //                             return discrepancy;
        //                         }
        //                     });
        //                     return _dis.length > 0 ? '&#8369; '+numberWithCommas(_dis[_dis.length - 1].amount) : 
        //                             '&#8369; '+numberWithCommas(row.transaction.amount);
        //                 }else{
        //                     return '&#8369; '+numberWithCommas(row.transaction.amount)
        //                 }
        //             }
        //         },
        //         {data: 'amount'},
        //     ]
        // });


        deposit_partner_table = $('#deposit_partner_table').DataTable({
            initComplete : function() {
                var input = $('#deposit_partner_table_filter input').unbind(),
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
                $('#deposit_partner_table_filter').append($searchButton, $clearButton);
            },             
            processing: true,
            serverSide:true,
            responsive: true,
            deferLoading: 0,
            ajax: "{!! route('get_partner_users_transactions',['type' => 'deposit', 'trade_type' => 'partner-user', 'user_id' => Auth::user()->id]) !!}",
            order: [[ 2, "desc" ]],
            drawCallback: function(settings) {
                // tips_view_table.draw()
                var data = this.api().rows().data()
                if (data.length > 0) {
                    for (var i = 0; i < data.length; i++) {
                        $("#photo-"+data[i].id).fileinput({
                            previewFileType: "image",
                            theme: "fa",
                            showUpload: false,
                            showCaption: false,
                            showRemove: false,
                            browseClass: "btn btn-primary btn-"+data[i].id+" btn-sm",
                            browseLabel: "Pick Image",
                            browseIcon: "<i class=\"fa fa-picture-o\"></i> ",
                            allowedFileExtensions: ["jpg", "gif", "png"],
                        }).on("filebatchselected", function(event, files) {
                            var data = deposit_partner_table.row($(this).parents('tr') ).data();
                            console.log(data);
                            $.ajax({
                                url: '{{ route('upload') }}',
                                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                                type: 'POST',
                                data: {photo:$('.file-preview-image').attr('src'),id:data.id,status:'processing'},
                                success: function(data) {
                                    if (data.success) {
                                        deposits_table.ajax.reload()
                                        swal({
                                            title: "Your deposit request "+data.code+" is currently being processed.",
                                            text: 'It usually takes a few hours but sometimes can take a full day. Please contact our admins if your request has not been processed after 24 hours.',
                                            type: "success",
                                            html: true,
                                        });
                                    }
                                },
                                fail: function(xhr, status, error) {
                                    console.log('error');
                                }
                            });

                        });
                    }

                }
            },
            columns: [
                {
                    data: 'code', 
                    name: 'code'
                },
                {
                    data: 'partner.partner_name', 
                    name: 'partner.partner_name',
                },
                {
                    data: 'created_at', 
                    name: 'created_at',
                    render: function(data,type,row){
                        return moment(data).format('llll')+'<br>'+moment(data).fromNow()
                    }
                },
                {
                    data: 'amount', 
                    name: 'amount',
                    render: function(data,type,row){
                        var net = parseFloat(data);
                        return '&#8369; '+numberWithCommas( net.toFixed(2) );
                    }
                },
                {
                    data: 'status', 
                    name: 'status',
                    render: function ( data, type, row ) {
                        if(row['picture'] == null && data < 1 ){
                            return "Incomplete";
                        }
                        else{
                            switch(data){
                                case 1:
                                case '1':
                                    return "Approved"; break;
                                case 2:
                                case '2':
                                    return "Rejected"; break;
                                case -1:
                                case '-1':
                                    return "Pending Admin Verification"; break;
                                default: 
                                    return "Needs Approval";
                            }
                        }
                    }
                },
                {
                    render: function(data,type,row){
                        return '<a href="#" class="btn btn-default view-details"><i class="fa fa-info" aria-hidden="true"></i></a>';
                    }
                },
                {
                    data: 'picture',
                    searchable:false,
                    render: function(data,type,row){
                        if(row['status'] > 1){
                            return row['partner_comment'];
                        }
                        else{
                            return data != null ? '<a href="#" class="btn btn-default view-receipt"><i class="fa fa-picture-o" aria-hidden="true"></i></a>' : 'N/A';
                        }
                    }
                },
            ]
        })

        cashout_partner_table = $('#cashout_partner_table').DataTable({
            initComplete : function() {
                var input = $('#cashout_partner_table_filter input').unbind(),
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
                $('#cashout_partner_table_filter').append($searchButton, $clearButton);
            },              
            processing: true,
            serverSide:true,
            responsive: true,
            deferLoading: 0,
            ajax: "{!! route('get_partner_users_transactions',['type' => 'cashout', 'trade_type' => 'partner-user', 'user_id' => Auth::user()->id]) !!}",
            order: [[ 2, "desc" ]],
            columns: [
                {
                    data: 'code', 
                    name: 'code'
                },
                {
                    data: 'partner.partner_name', 
                    name: 'partner.partner_name',
                },
                {
                    data: 'created_at', 
                    name: 'created_at',
                    render: function(data,type,row){
                        return moment(data).format('llll')+'<br>'+moment(data).fromNow()
                    }
                },
                {
                    data: 'amount', 
                    name: 'amount',
                    render: function(data,type,row){
                        var net = parseFloat(data);
                        return '&#8369; '+numberWithCommas( net.toFixed(2) );
                    }
                },
                {
                    data: 'status', 
                    name: 'status',
                    render: function ( data, type, row ) {
                        if(row['picture'] == null && data < 1 ){
                            return "Incomplete";
                        }
                        else{
                            switch(data){
                                case 1:
                                case '1':
                                    return "Approved"; break;
                                case 2:
                                case '2':
                                    return "Rejected"; break;
                                case -1:
                                case '-1':
                                    return "Pending Admin Verification"; break;
                                default: 
                                    return "Needs Approval";
                            }
                        }
                    }
                },
                {
                    render: function(data,type,row){
                        return '<a href="#" class="btn btn-default view-details"><i class="fa fa-info" aria-hidden="true"></i></a>';
                    }
                },
                {
                    data: 'picture',
                    searchable:false,
                    render: function(data,type,row){
                        if(row['status'] > 1){
                            return row['partner_comment'];
                        }
                        else{
                            return data != null ? '<a href="#" class="btn btn-default view-receipt"><i class="fa fa-picture-o" aria-hidden="true"></i></a>' : 'N/A';
                        }
                    }
                },
            ]
        })

        $('.transfer-wallet').click(function(){
            swal({
                title: "Transfer wallet to credits??",
                text: "This action is irreversible and cannot be undone",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-info",
                confirmButtonText: "Confirm",
                closeOnConfirm: false,
                showLoaderOnConfirm: true
            },
            function(){
                $.ajax({
                    url: "{{ route('get-wallet-transfer') }}",  //Server script to process data
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    type: 'POST',
                    success: function(data){
                        if(!data.success){
                                swal("Oops!", data.message, "error");
                            }else{
                                swal("Success!", data.message, "success");
                        $('.sweet-alert').on('click', '.confirm', function() {
                            window.location.reload();
                        });
                        }
                    },
                    cache:false,
                    contentType: false,
                    processData: false,
                });
            });

        })

        $('#fill-up-info').click(function(event) {
            $('#partnerInfoModal').modal('show');
        });

        $('#update-partner-info-btn').click(function() {
            $('.error-label').css('display', 'none');
            $('.badges-area').off('click', '#fill-up-info');
            if(setPartnerInfo()){
                $.ajax({
                    url:'{{ route("json_set_partner_info") }}',
                    type:'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    data: {
                        partner_name: $('#partner_name').val(),
                        address: $('#partner_address').val(),
                        mobile_number: $('#partner_mobile').val(),
                        landline_number: $('#partner_telephone').val() != '' ? $('#partner_telephone').val() : null,
                        contact_person: $('#partner_person').val(),
                        email: $('#partner_email').val(),
                        operation_time: $('#partner_operation').val(),
                        payment_mode: $('#partner_mop').val(),
                        details: $('#partner_details').val(),
                        bpi_account: $('#partner_bpi').val() != '' ? $('#partner_bpi').val() : null,
                        bpi_account_name: $('#partner_bpi_name').val() != '' ? $('#partner_bpi_name').val() : null,
                        bdo_account: $('#partner_bdo').val() != '' ? $('#partner_bdo').val() : null,
                        bdo_account_name: $('#partner_bdo_name').val() != '' ? $('#partner_bdo_name').val() : null,
                        province_id: $('#partner_province').val(),
                        facebook_link: $('#partner_fb_link').val(),
                    },
                    success:function(data){
                        if(data.success){
                            swal("Partnership Info Submitted!", "Partnership information is successfully submitted. Please wait for the admins to process your partnership request.", "success");
                            $('#partnerInfoModal').modal('hide');
                            $('#partnerInfoModal').attr('id', 'completeInfoModal');
                            $('.hover-message-text').text('Partnership information is successfully submitted. Please wait for the admins to process your partnership request.');
                        }
                    }
                });
            }
        });

        $('#bugs_table').on('click', '.view-bug', function(event) {
            event.preventDefault();
            var row = bugs_table.row($(this).closest('tr')).data();
            var url = "{{url('/reported-bugs/showimage')}}/" + row['id'];
            if (row['hasImage']) {
                // var viewer = new Viewer(document.getElementById('receipt-'+row['id']), {url:'data-url'});
                $('#viewReceipt').find('h5').html('Image')
                $('#viewReceipt').find('.modal-body').html('<image class="form-control" style="height:100%" src="'+(url)+'"/>')
                $('#viewReceipt').modal('show');
            }else{
                swal("No Image!", 'No image has been uploaded', "warning");

            }
        });

        $('#deposits_table').on('click', '.view-receipt', function(event) {
            event.preventDefault();
            $tr = $(this).closest('tr').hasClass('child') ? 
                $(this).closest('tr').prev() : $(this).closest('tr');
            var row = deposits_table.row($tr).data();
            var url = '{{asset('transaction/images/')}}';
            if (row['picture']) {
                // var viewer = new Viewer(document.getElementById('receipt-'+row['id']), {url:'data-url'});
                $('#viewReceipt').find('h5').html('Receipt')
                $('#viewReceipt').find('.modal-body').html('<image class="form-control" style="height:100%" src="'+(url+row['picture']).replace('/uploads','')+'"/>')
                $('#viewReceipt').modal('show');
            }else{
                swal("No Receipt!", 'No receipt has been uploaded', "warning");

            }
        });

        $('#cashout_table').on('click', '.view-receipt', function(event) {
            event.preventDefault();
            $tr = $(this).closest('tr').hasClass('child') ? 
                $(this).closest('tr').prev() : $(this).closest('tr');
            var row = cashout_table.row($tr).data();
            var url = '{{asset('transaction/images/')}}';
            if (row['picture']) {
                // var viewer = new Viewer(document.getElementById('receipt-'+row['id']), {url:'data-url'});
                $('#viewReceipt').find('h5').html('Receipt')
                $('#viewReceipt').find('.modal-body').html('<image class="form-control" style="height:100%" src="'+(url+row['picture']).replace('/uploads','')+'"/>')
                $('#viewReceipt').modal('show');
            }else{
                swal("No Receipt!", 'No receipt has been uploaded', "warning");

            }
        });

        $('#deposit_partner_table').on('click', '.view-receipt', function(event) {
            event.preventDefault();
            $tr = $(this).closest('tr').hasClass('child') ? 
                $(this).closest('tr').prev() : $(this).closest('tr');
            var row = deposit_partner_table.row($tr).data();
            var url = '{{asset('transaction/images/')}}';
            if (row['picture']) {
                // var viewer = new Viewer(document.getElementById('receipt-'+row['id']), {url:'data-url'});
                $('#viewReceipt').find('h5').html('Receipt')
                $('#viewReceipt').find('.modal-body').html('<image class="form-control" style="height:100%" src="'+(url+row['picture']).replace('/uploads','')+'"/>')
                $('#viewReceipt').modal('show');
            }else{
                swal("No Receipt!", 'No receipt has been uploaded', "warning");
            }
        });

        $('#cashout_partner_table').on('click', '.view-receipt', function(event) {
            event.preventDefault();
            $tr = $(this).closest('tr').hasClass('child') ? 
                $(this).closest('tr').prev() : $(this).closest('tr');
            var row = cashout_partner_table.row($tr).data();
            var url = '{{asset('transaction/images/')}}';
            if (row['picture']) {
                // var viewer = new Viewer(document.getElementById('receipt-'+row['id']), {url:'data-url'});
                $('#viewReceipt').find('h5').html('Receipt')
                $('#viewReceipt').find('.modal-body').html('<image class="form-control" style="height:100%" src="'+(url+row['picture']).replace('/uploads','')+'"/>')
                $('#viewReceipt').modal('show');
            }else{
                swal("No Receipt!", 'No receipt has been uploaded', "warning");
            }
        });

        $('#deposit_partner_table').on('click', '.view-details', function(event) {
            event.preventDefault();
            var transaction = deposit_partner_table.row($(this).closest('tr')).data();
            var data = JSON.parse(transaction.data)
            $('#view-details').find('.modal-body').find('#deposit-steps').empty();
            renderStepsManual(data.mop.split('-'),transaction)
            $('#view-details').modal('show');
        });

        $('#cashout_partner_table').on('click', '.view-details', function(event) {
            event.preventDefault();
            var transaction = cashout_partner_table.row($(this).closest('tr')).data();
            var data = JSON.parse(transaction.data)
            $('#view-details').find('.modal-body').find('#deposit-steps').empty();
            renderStepsManual(data.mop.split('-'),transaction)
            $('#view-details').modal('show');
        });

        $('#cashout_table').on('click', '.view-details', function(event) {
            event.preventDefault();
            $tr = $(this).closest('tr').hasClass('child') ? 
                $(this).closest('tr').prev() : $(this).closest('tr');
            var cashout = cashout_table.row($tr).data();
            var modal = $('#modal-details');
            $(modal).find('.modal-body .dl-horizontal').empty();
            var details
            $.each(cashout.data, function(index, val) {
                details  = '<dt>'+index+'</dt>' +
                           '<dd>'+val+'</dd>'
                $(modal).find('.modal-body .dl-horizontal').append(details);
            });
            $(modal).modal('show');
        });

        $('#deposits_table').on('click', '.view-details', function(event) {
            event.preventDefault();
            $tr = $(this).closest('tr').hasClass('child') ? 
                $(this).closest('tr').prev() : $(this).closest('tr');
            var transaction = deposits_table.row($tr).data();
            $('#view-details').find('.modal-body').find('#deposit-steps').empty();
            renderStepsManual(transaction.data.mop.split('-'),transaction)
            $('#view-details').modal('show');
        });
        
        $('#report-bug').click(function(e) {
            $('#bugModal').modal('show');
        })

        $('#add-promotion').click(function(e) {
            $('#promoModal').modal('show');
        })

        $('#add-bug-report-btn').click(function(){
            var form = new FormData($("#bugForm")[0]);
            swal({
                title: "Are you sure to report this bug ?",
                text: "You will get a reward accordingly",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-info",
                confirmButtonText: "Confirm",
                closeOnConfirm: false,
                showLoaderOnConfirm: true
            },
            function(){
                $.ajax({
                    url: "{{ route('add-report-bug') }}",  //Server script to process data
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    type: 'POST',
                    success: function(data){
                        if(data.error){
                            printErrorMsg(data.error);
                            swal.close()
                        }
                        else {
                            $('#bugForm')[0].reset();
                            $('#bugForm .print-error-msg').hide();
                            $('#bugModal').modal('hide');
                            swal("Success!", 'Bug successfully reported', "success");
                            bugs_table.ajax.reload();
                        }
                    },
                    data: form,
                    cache:false,
                    contentType: false,
                    processData: false,
                });
            });

        })
        
        $('#add-promo-btn').click(function(){
            var form = new FormData($("#promoForm")[0]);
            swal({
                title: "Are you sure to add this promo ?",
                text: "You will get a reward accordingly",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-info",
                confirmButtonText: "Confirm",
                closeOnConfirm: false,
                showLoaderOnConfirm: true
            },
            function(){
                $.ajax({
                    url: "{{ route('add-promotion') }}",  //Server script to process data
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    type: 'POST',
                    success: function(data){
                        if(data.error){
                            printErrorMsg(data.error);
                            swal.close()
                        }
                        else {
                            if(!data.success){
                                swal("Oops!", data.message, "error");
                            }else{
                                $('#promoForm')[0].reset();
                                $('#promoForm .print-error-msg').hide();
                                $('#promoModal').modal('hide');
                                swal("Success!", 'Promo successfully added', "success");
                                promo_table.ajax.reload();
                            }
                        }
                    },
                    data: form,
                    cache:false,
                    contentType: false,
                    processData: false,
                });
            });

        })
        
        
        $('#request_betatester_btn').click(function(e) {
            e.preventDefault();
            e.stopPropagation();
            swal({
                title: "Acquire BETATESTER badge?",
                text: "You will be receiving the BETATESTER badge with a {{$beta_badge ? $beta_badge->credits : 500}} credits reward.",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-info",
                confirmButtonText: "Confirm",
                closeOnConfirm: false,
                showLoaderOnConfirm: true
            },
            function(){
                $.ajax({
                    url: "{{ route('request_betatester') }}",
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    type: 'POST',
                    data: { user_id: {{Auth::user()->id}} },
                    success: function(data) {
                        if (data.success) {
                            swal("Success!", 'You now have the BETATESTER badge', "success");
                            $('.sweet-alert').on('click', '.confirm', function() {
                                window.location.reload();
                            });
                        }else{
                            swal("Oops!", 'Something went wrong!', "error");
                        }
                    },
                    fail: function(xhr, status, error) {
                        console.log(error);
                    }
                });
            });
        });

        function renderStepsManual(mop,transaction)
        {
            switch (mop[1]) {
                case 'payment':
                    var container = $("#coinsph-steps-template").html();
                    var data = {}
                    data.code = transaction.code;
                    $('#deposit-steps').append(Mustache.render(container, data));
                    break;
                case 'deposit':
                    var data = {}, bank=mop[0], bopen, bclose, mopen, mclose, accountnumber, accountname;
                    var container = $("#manual-steps-template").html();
                    switch (mop[0]) {
                        case 'BDO':
                            bopen = '9AM'
                            bclose = '3PM'
                            mopen = '10AM'
                            mclose = '7PM'
                            accountnumber = "{{$settings['bdo-account-number']}}"
                            accountname = "{{$settings['bdo-account-name']}}"
                            break;
                        case 'BPI':
                            bopen = '9AM'
                            bclose = '3PM'
                            mopen = '10AM'
                            mclose = '6PM'
                            accountnumber = "{{$settings['bpi-account-number']}}"
                            accountname = "{{$settings['bpi-account-name']}}"
                            break;
                        case 'Metrobank':
                            bopen = '9AM'
                            bclose = '3PM'
                            mopen = '10AM'
                            mclose = '6PM'
                            accountnumber = "{{$settings['metro-account-number']}}"
                            accountname = "{{$settings['metro-account-name']}}"
                            break;
                        case 'Securitybank':
                            bopen = '9AM'
                            bclose = '3PM'
                            mopen = '10AM'
                            mclose = '6PM'
                            accountnumber = "{{$settings['security-account-number']}}"
                            accountname = "{{$settings['security-account-name']}}"
                            break;
                    }
                    data.bank = bank;
                    data.bopen = bopen;
                    data.bclose = bclose;
                    data.mopen = mopen;
                    data.mclose = mclose;
                    data.accountnumber = accountnumber;
                    data.accountname = accountname;
                    data.amount = numberWithCommas(transaction.amount);
                    data.code = transaction.code;
                    $('#view-details').find('.modal-body').find('#deposit-steps').append(Mustache.render(container, data));
                    break;
                case 'remittance':
                    var data = {}
                    data.amount = numberWithCommas(transaction.amount);
                    data.code = transaction.code;
                    var container = $("#"+mop[0]+"-remittance").html();
                    $('#view-details').find('.modal-body').find('#deposit-steps').append(Mustache.render(container, data));
                    break;
                case 'online':
                    var container = $("#"+mop[0]+"-desktop").html();
                    var data = {}
                    data.code = transaction.code;
                    $('#view-details').find('.modal-body').find('#deposit-steps').append(Mustache.render(container, data));
                    break;
                case 'walkin':
                    var container = $("#"+mop[0]+"-walkin").html();
                    var data = {}
                    data.code = transaction.code;
                    data.partner = transaction.partner.partner_name;
                    data.address = transaction.partner.address + ', ' + transaction.partner.province.province;
                    data.schedule = transaction.partner.operation_time;
                    $('#view-details').find('.modal-body').find('#deposit-steps').append(Mustache.render(container, data));
                    break;
            }

        }
        
        var url = document.location.toString();
        if (url.match("#")) {

        }
        else {
            startupDatatable(null)
        }

        $('.nav-tabs a').click(function(e){
            // $('.nav-tabs a[href="#' + url.split('#')[1] + '"]').tab('show');
            startupDatatable(e.currentTarget.hash.split("#")[1])
        })

        // Change hash for page-reload
        $('.nav-tabs a').on('shown.bs.tab', function (e) {
            console.log('testing: ', e)
            window.location.hash = e.target.hash;
            switch(e.currentTarget.hash) {
                case '#bets':
                    bets_table.ajax.reload();
                    break;
                case '#cashout':
                    cashout_table.ajax.reload();
                    break;
                case '#deposits':
                    deposits_table.ajax.reload();
                    break;
                case "#partner_cashout":
                    cashout_partner_table.ajax.reload();
                    break;
                case "#partner_deposit":
                    deposit_partner_table.ajax.reload();
                    break;
                case '#referals':
                    referal_table.ajax.reload();
                    break;
                case '#rewards':
                    reward_table.ajax.reload();
                    break;
                case '#gift_codes':
                    gift_codes_table.ajax.reload();
                    break;                    
                case '#bugs':
                    bugs_table.ajax.reload();
                    break;
                case '#promos':
                    promo_table.ajax.reload();
                    break;
                case "#rebates":
                    rebates_table.ajax.reload();
                    break;
                case "#commissions":
                    commissions_table.ajax.reload();
                    break;
                case "#commissions_partners":
                    commissions_partners_table.ajax.reload();
                    break;
                case "#commissions_bets":
                    commissions_bets_table.ajax.reload();
                    break;
                case "#commissions_sub_streamers":
                    commissions_sub_streamers_table.ajax.reload();
                    break;

            }
        })

        function startupDatatable(target){
            switch(target) {
                case "bets":
                    bets_table.ajax.reload();
                    break;
                case "cashout":
                    cashout_table.ajax.reload();
                    break;
                case "deposits":
                    deposits_table.ajax.reload();
                    break;
                case "partner_cashout":
                    cashout_partner_table.ajax.reload();
                    break;
                case "partner_deposit":
                    deposit_partner_table.ajax.reload();
                    break;
                case "referals":
                    referal_table.ajax.reload();
                    break;
                case "rewards":
                    reward_table.ajax.reload();
                    break;
                case "gift_codes":
                    gift_codes_table.ajax.reload();
                    break;                    
                case "bugs":
                    bugs_table.ajax.reload();
                    break;
                case "promos":
                    promo_table.ajax.reload();
                    break;
                case "rebates":
                    rebates_table.ajax.reload();
                    break;
                case "commissions":
                    commissions_table.ajax.reload();
                    break;
                case "commissions_partners":
                    commissions_partners_table.ajax.reload();
                    break; 
                case "commissions_bets":
                    commissions_bets_table.ajax.reload();
                    break;   
                case "commissions_sub_streamers":
                    commissions_sub_streamers_table.ajax.reload();
                    break;                                          
                default:
                    bets_table.ajax.reload();
                    break;
            }
        }

    });
    
    $.fn.capitalize = function () {
        $.each(this, function () {
            var caps = this.value;
            caps = caps.charAt(0).toUpperCase() + caps.slice(1);
            this.value = caps;
        });
        return this;
    };

    function printErrorMsg (msg) {
        $(".print-error-msg").find("ul").html('');
        $(".print-error-msg").css('display','block');
        $.each( msg, function( key, value ) {
                $(".print-error-msg").find("ul").append('<li>'+value+'</li>');
        });
    }

    function isNullOrWhitespace( input ) 
    {
        if (typeof input === 'undefined' || input == null) return true;
        return input.replace(/\s/g, '').length < 1;
    }

    function setPartnerInfo(){
        var pass = true;
        if(isNullOrWhitespace($('#partner_bpi').val()) && isNullOrWhitespace($('#partner_bdo').val()) && isNullOrWhitespace($('#partner_bpi_name').val()) && isNullOrWhitespace($('#partner_bdo_name').val())){
            $('#partner_bpi').parent().parent().find('.error-label').css('display', 'block');
            $('#partner_bpi').parent().parent().parent().find('.alert').css('margin-top', '0px');
            pass = false;
        }
        else if(!isNullOrWhitespace($('#partner_bpi').val()) && isNullOrWhitespace($('#partner_bpi_name').val())){
            $('#partner_bpi').parent().parent().find('.error-label').css('display', 'block');
            $('#partner_bpi').parent().parent().find('.error-label').text("Please don't leave your BPI account name empty");
            $('#partner_bpi').parent().parent().parent().find('.alert').css('margin-top', '0px');
            pass = false;
        }
        else if(!isNullOrWhitespace($('#partner_bdo').val()) && isNullOrWhitespace($('#partner_bdo_name').val())){
            $('#partner_bpi').parent().parent().find('.error-label').css('display', 'block');
            $('#partner_bpi').parent().parent().find('.error-label').text("Please don't leave your BDO account name empty");
            $('#partner_bpi').parent().parent().parent().find('.alert').css('margin-top', '0px');
            pass = false;
        }
        else if(!isNullOrWhitespace($('#partner_bdo').val()) && isNullOrWhitespace($('#partner_bdo_name').val()) && !isNullOrWhitespace($('#partner_bpi').val()) && isNullOrWhitespace($('#partner_bpi_name').val())){
            $('#partner_bpi').parent().parent().find('.error-label').css('display', 'block');
            $('#partner_bpi').parent().parent().find('.error-label').text("Please don't leave your bank accounts name empty");
            $('#partner_bpi').parent().parent().parent().find('.alert').css('margin-top', '0px');
            pass = false;
        }
        if(isNullOrWhitespace($('#partner_fb_link').val())){
            $('#partner_fb_link').siblings('.error-label').css('display', 'block');
            pass = false;
        }
        if(isNullOrWhitespace($('#partner_mop').val())){
            $('#partner_mop').siblings('.error-label').css('display', 'block');
            pass = false;
        }
        if(isNullOrWhitespace($('#partner_details').val())){
            $('#partner_details').parent().parent().find('.error-label').css('display', 'block');
            pass = false; 
        }
        if(isNullOrWhitespace($('#partner_name').val())){
            $('#partner_name').siblings('.error-label').css('display', 'block');
            pass = false;
        }
        if(isNullOrWhitespace($('#partner_address').val()) || !$('#partner_province').val()){
            $('#partner_address').parent().parent().find('.error-label').css('display', 'block');
            pass = false;
        }
        if(isNullOrWhitespace($('#partner_mobile').val())){
            $('#partner_mobile').parent().parent().find('.error-label').css('display', 'block');
            pass = false; 
        }
        if(isNullOrWhitespace($('#partner_person').val())){
            $('#partner_person').siblings('.error-label').css('display', 'block');
            pass = false; 
        }
        if(isNullOrWhitespace($('#partner_email').val())){
            $('#partner_email').siblings('.error-label').css('display', 'block');
            pass = false; 
        }
        if(isNullOrWhitespace($('#partner_operation').val())){
            $('#partner_operation').siblings('.error-label').css('display', 'block');
            pass = false; 
        }
        else if(  
            ( (!isNullOrWhitespace($('#partner_bdo').val()) && !isNullOrWhitespace($('#partner_bdo_name').val())) || (!isNullOrWhitespace($('#partner_bpi').val()) && !isNullOrWhitespace($('#partner_bpi_name').val())) ) &&
               !isNullOrWhitespace($('#partner_fb_link').val()) && !isNullOrWhitespace($('#partner_mop').val()) && !isNullOrWhitespace($('#partner_details').val()) &&
               !isNullOrWhitespace($('#partner_name').val()) && !isNullOrWhitespace($('#partner_address').val()) && !isNullOrWhitespace($('#partner_mobile').val()) &&
               !isNullOrWhitespace($('#partner_person').val()) && !isNullOrWhitespace($('#partner_email').val()) && !isNullOrWhitespace($('#partner_operation').val()) &&
               !$('#partner_province').val()
            ){
            $('.error-label').css('display', 'none');
            $('#partner_bpi').parent().parent().parent().find('.alert').css('margin-top', '115px');
            pass = true;
        }
        console.log(pass);
        return pass;
    }

    function updateBadge(){
        $.ajax({
            url: "{{ route('get_partner_credits') }}",
            type: "GET",
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            success: function(data){
                var earnings = parseFloat(data.earnings), credits = parseFloat(data.credits); 
                console.log(credits.toFixed(2) + " and "+ earnings.toFixed(2));
                $("#partner-ez-credits").html("&#8369; "+ credits.toFixed(2)).digits();
                $("#partner-ez-credits").attr("data-amount", credits.toFixed(2));
                $("#partner-earnings").html("&#8369; "+ earnings.toFixed(2)).digits();
                $("#partner-earnings").attr("data-amount", earnings.toFixed(2));
            },
        });
    }

    $("#partner_address").focusout(function(){
        $(this).parent().parent().find('.error-label').css('display', 'none');
    });
    $("#partner_mobile").focusout(function(){
        $(this).parent().parent().find('.error-label').css('display', 'none');
    });
    $("#partner_bpi").focusout(function(){
        $(this).parent().parent().find('.error-label').css('display', 'none');
        $('#partner_bpi').parent().parent().parent().find('.alert').css('margin-top', '115px');
    });
    $("#partner_bpi_name").focusout(function(){
        $(this).parent().parent().find('.error-label').css('display', 'none');
        $('#partner_bpi').parent().parent().parent().find('.alert').css('margin-top', '115px');
    });
    $("#partner_bdo").focusout(function(){
        $(this).parent().parent().find('.error-label').css('display', 'none');
        $('#partner_bpi').parent().parent().parent().find('.alert').css('margin-top', '115px');
    });
    $("#partner_bdo_name").focusout(function(){
        $(this).parent().parent().find('.error-label').css('display', 'none');
        $('#partner_bpi').parent().parent().parent().find('.alert').css('margin-top', '115px');
    });
    $("#partner_mobile, #partner_bpi, #partner_bdo").keydown(function (e) {
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
            (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) || 
            (e.keyCode >= 35 && e.keyCode <= 40)) {
            return;
        }
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });

    $(".partner-select-logo").fileinput({
        previewFileType: "image",
        theme: "fa",
        showUpload: false,
        showCaption: false,
        showRemove: false,
        browseClass: "btn btn-primary btn-sm",
        browseLabel: "Pick Image",
        browseIcon: "<i class=\"fa fa-picture-o\"></i> ",
        allowedFileExtensions: ["jpg", "gif", "png"],
    }).on("filebatchselected", function(event, files) {
        $('.kv-upload-progress').css('display', 'none');
         $('.file-upload-indicator').css({
            backgroundColor: '#fcf8e3',
            borderColor: '#faebcc'
        });
        $('.file-upload-indicator').find('i').removeClass('fa-check-circle').removeClass('text-success').addClass('fa-hand-o-down').addClass('text-warning')
        $.ajax({
            url: '{{ route("set_partner_logo") }}',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            type: 'POST',
            data: {photo:$('.file-preview-image').attr('src'),user_id:{{Auth::user()->id}}, },
            success: function(data) {
                if (data.success) {
                        $('.kv-upload-progress').removeClass('hide').css('display', 'block').find('.progress-bar').css('width', '100%').text('Done');
                        $('.file-upload-indicator').css({
                            backgroundColor: '#dff0d8',
                            borderColor: '#d6e9c6'
                        });
                        $('.file-upload-indicator').find('i').removeClass('fa-hand-o-down').removeClass('text-warning').addClass('fa-check-circle').addClass('text-success');
                }
            },
            fail: function(xhr, status, error) {
                console.log('error');
            }
        });
    });

    @if(Auth::user()->voucher_code) //for affliates only

        $(document).on("click", ".copy-voucher-code", function() {
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val($('#affliate-voucher-code').text()).select();
            document.execCommand("copy");
            $temp.remove();

            swal("Affliate Link", "Copied affliate voucher code Link.", "success");
        })

        $(document).on("click",".view-commissions-bets-btn", function(event){
            event.preventDefault();
            const row = commissions_bets_table.row($(this).closest('tr')).data();
            const matchId = $(this).data('matchId');
            const totalEarnings = $(this).data('amount');
            const bets = Object.values(row.user_bets);

            let betsTable = `<table class="table table-condensed">`;
            betsTable += `
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Amount</th>
                                <th>Earnings</th>
                            </tr>
                        </thead>
                        <tbody>   
                        `;
                        
            bets.forEach(bet => {
                betsTable += `
                    <tr>
                        <td>${bet.name}</td>
                        <td>&#8369; ${  numberWithCommas( parseFloat(bet.amount).toFixed(2) ) }</td>
                        <td>&#8369; ${  numberWithCommas(  parseFloat(bet.earnings).toFixed(2) ) }</td>
                    </tr>
                `;
            });


            betsTable += `</tbody></table>`;

            //commissions-bets-container
            $('#commissionsBetsModal').find('#match_id').text(matchId);
            $('#commissionsBetsModal').find('#total_earnings').html(`&#8369; ${  numberWithCommas(  parseFloat(totalEarnings).toFixed(2)) }`);
            $('#commissionsBetsModal').find('#commissions-bets-container').html(betsTable);
            $('#commissionsBetsModal').modal('show');
        });


        $('.download-voucher-users').click(function(event){
            $('#partner_download_transactions_modal').modal('show');
            const dataAttr = $(this).data();
            $('#partner_download_transactions_modal #type').val(dataAttr.type);
            $('#partner_download_transactions_modal #tradeType').val(dataAttr.tradeType);

            switch(dataAttr.tradeType){
                case 'partner-user': 
                    const downloadTypeText = dataAttr.type == 'deposit' ? 'User Initiated Deposits' : 'User Initiated Cashouts';
                    $('#download-type').html(downloadTypeText);
                    break;
                case 'partner-admin': 
                    break;
            }
            //download-type

        })
    

        $('.get-new-voucher-users').click(function(event){
            $('#get_new_voucher_code_users_modal').modal('show');
            const dataAttr = $(this).data();
        })

        $('#proceed-download-btn').click(function(event){
            event.preventDefault();
            const that = $(this);
            that.prop('disabled',true);
            const downloadData =new FormData($('#affiliate-voucher-users-download-form')[0]);
            $.ajax({
                    url: "{{ route('affiliate.download.voucher.users') }}",
                    type:'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    data: downloadData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success:function(data){
                        const { success, message, users } = data;
                        if(success){
                            //affiliates-voucher-users;
                            if(typeof users == 'object'){
                                $('#affiliates-voucher-users').text(Object.values(users).join('\r\n'));
                            }else{
                                $('#affiliates-voucher-users').text(users.join('\r\n'));
                            }
                            
                            
                        }else{
                            swal("Filter Error",message,"error");
                        }
                        that.prop('disabled',false);
                    }
                });
        });

        $('#clear-voucher-users-textarea').click(function(event){
            event.preventDefault();
            $('#affiliates-voucher-users').text('');
        })

        $('#proceed-get-voucher-users-btn').click(function(event){
            event.preventDefault();
            const that = $(this);
            that.prop('disabled',true);
            const downloadData =new FormData($('#get_new_voucher_code_users_modal #affiliate-voucher-users-download-form')[0]);
            $.ajax({
                    url: "{{ route('affiliate.get.voucher.users') }}",
                    type:'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    data: downloadData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success:function(data){
                        const { success, message, users } = data;
                        if(success){
                            //affiliates-voucher-users;
                            if(typeof users == 'object'){
                                $('#get_new_voucher_code_users_modal #affiliates-voucher-users').text(Object.values(users).join('\r\n'));
                            }else{
                                $('#get_new_voucher_code_users_modal #affiliates-voucher-users').text(users.join('\r\n'));
                            }
                            
                            
                        }else{
                            swal("Filter Error",message,"error");
                        }
                        that.prop('disabled',false);
                    }
                });
        });

        $('#get_new_voucher_code_users_modal #clear-voucher-users-textarea').click(function(event){
            event.preventDefault();
            $('#get_new_voucher_code_users_modal #affiliates-voucher-users').text('');
        })
        

    @endif

    $('[data-toggle="tooltip"]').tooltip()

</script>
@endsection
