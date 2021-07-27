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
    table td {
        word-wrap: break-word;
        max-width: 400px;
    }
    .form-twin-column{
        width: 48%;
        margin-bottom: 10px;
    }
    .checkbox-container {
        display: block;
        position: relative;
        padding-left: 35px;
        margin-bottom: 12px;
        cursor: pointer;
        font-size: 14px;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }
    .checkbox-container .checkbox-item {
        position: absolute;
        opacity: 0;
        cursor: pointer;
    }
    .checkmark {
        position: absolute;
        top: 0;
        left: 3px;
        height: 22px;
        width: 22px;
        border-radius: 4px;
        background-color: #eee;
    }
    .checkbox-container:hover .checkbox-item ~ .checkmark {
        background-color: #ccc;
    }
    .checkbox-container .checkbox-item:checked ~ .checkmark {
        background-color: #2196F3;
    }
    .checkmark:after {
        content: "";
        position: absolute;
        display: none;
    }
    .checkbox-container .checkbox-item:checked ~ .checkmark:after {
        display: block;
    }
    .checkbox-container .checkmark:after {
        left: 7px;
        top: 1px;
        width: 8px;
        height: 16px;
        border: solid white;
        border-width: 0 3px 3px 0;
        -webkit-transform: rotate(45deg);
        -ms-transform: rotate(45deg);
        transform: rotate(45deg);
    }
    #complete_buy_credits{
        max-height: 600px;
        display: none;
        overflow-y: auto;
        overflow-x: hidden;
    }
    #complete-deposit-partner{
        display: none;
    }

    .bubble_counter {
        position:relative;
    }
    .bubble_counter[data-count]:after {
        content:attr(data-count);
        position:absolute;
        top:-15px;
        right:-15px;
        font-size:.9em;
        font-weight:bold;
        background:red;
        color:white;
        width:22px;height:22px;
        text-shadow: 2px 1px 1px black;
        text-align:center;
        line-height:22px;
        border-radius:50%;
        box-shadow: 1px 1px 1px #000;
    }

    .sub-user-m-container1{
        float:none !important;
        margin: 10px auto !important;
    }

    .partner-download-buttons{
        color: #414141 !important;
    }

    .info-box-number.download{
        margin-top: 10px;
    }
</style>
<link rel="stylesheet" href="{{ asset('bower_components/bootstrap-sweetalert/dist/sweetalert.css') }}">
<link rel="stylesheet" href="{{ asset('css/fileinput.css') }}">
<link rel="stylesheet" href="{{ asset('css/datatables.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/viewer.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/select2/select2.min.css') }}">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.1/css/responsive.dataTables.min.css">
<link rel="stylesheet" href="{{ asset('/bower_components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css') }}"/>
<link rel="stylesheet" href="{{ asset('css/partner-mobile-view.css') }}">

@endsection

@section('content')

<div class="mobile-sidebar-overlay">
    <div class="mobile-sidebar">
        <div class="mobile-retreat">
            <i class="fa fa-angle-double-left" aria-hidden="true"></i>
        </div>
        <div class="mobile-profile-tab">
            <div class="mobile-profile-img" 
            @if(Auth::user()->provider == 'local')
            style="background-image: url('{{ url('/').'/'.Auth::user()->avatar }}')"
            @else
            style="background-image: url('{{ Auth::user()->avatar }}')"
            @endif
            ></div>
            <p class="mobile-profile-name">{{ Auth::user()->name }}</p>
            <p class="mobile-profile-credits">EZ Credits: <span>{{ Auth::user()->credits }}</span></p>
        </div>
        <div class="mobile-menu-options">
            <a href="{{ url('home') }}"><div class="mobile-menu-item">&nbsp;&nbsp;<i class="fa fa-trophy" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;&nbsp;<span>Matches</span></div></a>
            <a href="{{ url('/profile') }}"><div class="mobile-menu-item">&nbsp;&nbsp;<i class="fa fa-user" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;&nbsp;<span>My Profile</span></div></a>
            <a href="{{ url('/partner') }}"><div class="mobile-menu-item">&nbsp;&nbsp;<i class="fa fa-handshake-o" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;&nbsp;<span>Partners</span></div></a>
            <a href="{{ url('/market') }}"><div class="mobile-menu-item">&nbsp;&nbsp;<i class="fa fa-tag" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;&nbsp;<span>Market</span></div></a>
            <a href="{{ url('careers') }}"><div class="mobile-menu-item">&nbsp;&nbsp;<i class="fa fa-at" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;&nbsp;<span>Matches</span></div></a>
            @if (!Auth::guest() && Auth::user()->isAdmin())
            <a href="{{ url('/matchmanager') }}"><div class="mobile-menu-item">&nbsp;&nbsp;<i class="fa fa-bolt" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;&nbsp;<span>Match Manager</span></div></a>
            <a href="{{ url('/admin') }}"><div class="mobile-menu-item">&nbsp;&nbsp;<i class="fa fa-cogs" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;&nbsp;<span>Admin</span></div></a>
            @else
                @if (!Auth::guest() && Auth::user()->isMatchManager())
                <a href="{{ url('/matchmanager') }}"><div class="mobile-menu-item">&nbsp;&nbsp;<i class="fa fa-bolt" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;&nbsp;<span>Match Manager</span></div></a>
                @endif
            @endif
            @if (!Auth::guest() && (Auth::user()->isAgent()) && (Auth::user()->userPartner && Auth::user()->userPartner->verified == 1 && Auth::user()->userPartner->active == 1) )
            <a href="{{ url('/agent') }}"><div class="mobile-menu-item">&nbsp;&nbsp;<i class="fa fa-tachometer" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;&nbsp;<span>Partner Dashboard</span></div></a>
            @endif
            @if (!Auth::guest())
            <a href="{{ url('/logout') }}"><div class="mobile-menu-item">&nbsp;&nbsp;<i class="fa fa-sign-out" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;&nbsp;<span>Logout</span></div></a>
            @endif
        </div>
        <div class="mobile-sidebar-footer">
            <div>
                <span>© 2017</span><br>
                <span>Powered by <a href="{{ url('/') }}">2ez.bet</a></span>
            </div>
        </div>
    </div>
</div>

<div class="header-exclusive-mobile">
    <div class="header-mobile-menu">
        <span class="open-mobile-sidebar-menu"><i class="fa fa-bars" aria-hidden="true"></i></span>
    </div>
    <a href="{{ url('home')}}" data-pjax><img src="{{ asset('images/ezbet_logo_main.png') }}" /></a>
</div>
<div class="main-container dark-grey" style="width:100%; margin-top: 10px;">
    <div class="m-container1 {{isPartnerSubUser($user) ? 'sub-user-m-container1': ''}}">
        <div class="main-ct">
            <div class="title2">Partnership Profile</div>
            <div class="clearfix"></div>
            <div class="user-info pd-blk">
                <div class="user-avata">
                    @if($partnership->logo != null)
                    <a href="#"><img src="{{ str_replace('public', 'storage', url('/')).$partnership->logo }}"></a>
                    @else
                        @if(Auth::user()->provider == 'local')
                        <a href="#"><img src="{{ url('/').'/'.Auth::user()->avatar }}"></a>
                        @else
                        <a href="#"><img src="{{ Auth::user()->avatar }}"></a>
                        @endif
                    @endif
                    {{-- <a href="#"><img src="{{ asset('transaction/images').str_replace('/uploads', '', $partnership->logo) }}"></a> --}}
                </div>
                <div class="info-user-txt">
                    <span class="name"><a href="#" target="_blank">{{ $partnership->partner_name }}</a></span>
                    <div class="col-md-12"  style="margin-left: -15px;  margin-top: 10px; font-size: 14px;">
                        Address: <span style="font-weight: bold;">            
                            @foreach($provinces as $province)
                                @if($province->id == $partnership->province_id)
                                    {{ $partnership->address.', '.$province->province }}
                                @endif
                            @endforeach
                         </span>
                    </div>
                    <div class="col-md-12" style="margin-left: -15px; font-size: 14px;">
                        EZ Wallet: <span style="font-weight: bold;" id="partner-ez-credits" data-number>&#8369; </span>
                    </div>
                    @if( ! isPartnerSubUser($user) )
                        <div class="col-md-12" style="margin-left: -15px; font-size: 14px;">
                            Agent Earnings: <span style="font-weight: bold;" id="partner-earnings" data-number>&#8369; </span>
                        </div>
                        <div class="col-sm-6"  style="margin-left: -15px; margin-top: 10px;">
                        <a href="#" id="buy-partner-credits" class="btn btn-warning" style="width: 149px;" data-toggle="modal">BUY CREDITS</a>
                        </div>
                        <div class="col-sm-6"  style="margin-left: -15px; margin-top: 10px;">
                            <a href="#" id="sell-partner-credits" class="btn btn-success" style="width: 149px;" data-toggle="modal">SELL CREDITS</a>
                        </div>
                        @if(!App::environment('prod'))
                        <div class="col-sm-6"  style="margin-left: -15px; margin-top: 10px;">
                            <a href="#" id="update-partner-details" class="btn btn-primary" style="width: 149px;" data-toggle="modal" disabled>UPDATE DETAILS</a>
                        </div>
                        @endif
                        <div class="col-sm-6"  style="margin-left: -15px; margin-top: 10px;">
                            <a href="#" id="update-public-profile" class="btn btn-primary" data-toggle="modal">UPDATE PUBLIC PROFILE</a>
                        </div>
                    @endif
                    

                </div>
            </div>
        </div>
    </div>
    {{-- @if(  1 == 2)
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
                            <span class="info-box-number" data-number>&#8369; 0</span>
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
                            <span class="info-box-number" data-number>&#8369; 0</span>
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
                            <span class="info-box-number" data-number>&#8369; 0</span>
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
                            <span class="info-box-number" data-number>&#8369; 0</span>
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
                            <span class="info-box-number" data-number>&#8369; 0</span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    @endif --}}

    @if( ! isPartnerSubUser($user) )
        <div class="m-container1">
            <div class="main-ct">
                <div class="title2">Downloads</div>
                <div class="clearfix"></div>
                <div class="user-info pd-blk">
                    <div class="col-md-12">
                        <div class="col-md-6">
                            <a href="#" class="partner-download-buttons partner-downloads" data-trade-type="partner-user" data-type="deposit">
                                <div class="info-box">
                                    <span class="info-box-icon bg-aqua"><i class="fa fa-download" aria-hidden="true"></i></span>

                                    <div class="info-box-content">
                                        {{-- <span class="info-box-text"></span> --}}
                                        <span class="info-box-number download" data-number>User Initiated Deposits</span>
                                    </div>
                                    <!-- /.info-box-content -->
                                </div>
                                <!-- /.info-box -->
                            </a>
                        </div>
                        
                        <div class="col-md-6">
                            <a href="#" class="partner-download-buttons partner-downloads" data-trade-type="partner-user" data-type="cashout">
                                <div class="info-box">
                                    <span class="info-box-icon bg-aqua"><i class="fa fa-money"></i></span>

                                    <div class="info-box-content">
                                        {{-- <span class="info-box-text"></span> --}}
                                        <span class="info-box-number download" data-number>User Initiated Cashouts</span>
                                    </div>
                                    <!-- /.info-box-content -->
                                </div>
                                <!-- /.info-box -->
                            </a>
                        </div>
                                                
                    </div>
                </div>
            </div>
        </div>
    @endif    

    <div class="m-container2" style="width: 98%">
        <div class="main-ct">
            <div class="title2">History</div>
            <div class="clearfix"></div>
            <div class="blk-1">
                <div class="col-md-12">
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#deposits" aria-controls="deposits" role="tab" data-toggle="tab"><span id="depo_cnt" class="bubble_counter">User Initiated Deposits</span></a></li>
                        <li role="presentation"><a href="#cashout" aria-controls="cashout" role="tab" data-toggle="tab"><span id="cash_cnt" class="bubble_counter">User Initiated Cashouts</span></a></li>
                        <li role="presentation"><a href="#transactions" aria-controls="transactions" role="tab" data-toggle="tab">Transactions</a></li>
                        <li role="presentation"><a href="#buy_sell_transactions" aria-controls="buy_sell_transactions" role="tab" data-toggle="tab">Buy/Sell Credits</a></li>
                        @if( ! isPartnerSubUser($user) )
                            <li role="presentation"><a href="#agent_transactions" aria-controls="agent_transactions" role="tab" data-toggle="tab"><span id="agent-transactions-cnt" class="bubble_counter">Agent Transactions</span></a></li>
                            <li role="presentation"><a href="#payout" aria-controls="payout" role="tab" data-toggle="tab">Payouts History</a></li>
                        @endif
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane fade in active" id="deposits" role="tabpanel">
                            <div class="tab-content" style="margin-top: 20px">
                                <table id="deposits_table" class="table table-striped" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Deposit Code</th>
                                            <th>Cust. ID</th>
                                            <th width="240px;">Customer</th>
                                            <th>Request Date</th>
                                            <th>Apprvd/Rjctd Date</th>
                                            <th>Amount</th>
                                            <th class="{{ isPartnerSubUser($user) ? 'hide' : '' }}">Earnings</th>
                                            <th>Voucher Code</th>
                                            <th>Status</th>
                                            {{-- <th>Details</th> --}}
                                            <th>Receipt</th>
                                            <th>Processed By</th>
                                            <th>Action</th>
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
                                            <th>Cashout Code</th>
                                            <th>Cust. ID</th>
                                            <th style="width: 240px !important;">Customer</th>
                                            <th>Request Date</th>
                                            <th>Apprvd/Rjctd Date</th>
                                            <th>Amount</th>
                                            <th class="{{ isPartnerSubUser($user) ? 'hide' : '' }}">Earnings</th>
                                            <th>Status</th>
                                            {{-- <th>Details</th> --}}
                                            <th>Receipt</th>
                                            <th>Processed By</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade in" id="agent_transactions" role="tabpanel">
                            <div class="tab-content" style="margin-top: 20px">
                                <table id="agent_transactions_table" class="table table-striped" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Code</th>
                                            <th>Agent</th>
                                            <th>Customer</th>
                                            <th>Request Date</th>
                                            <th>Apprvd/Rjctd Date</th>
                                            <th>Amount</th>
                                            <th>Sub-agent Earnings</th>
                                            <th>Commission Earned</th>
                                            <th>Status</th>
                                            <th>Receipt</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    
                                </table>
                            </div>
                        </div>                        
                        <div class="tab-pane fade in" id="transactions" role="tabpanel">
                            <div class="tab-content" style="margin-top: 20px">
                                <table id="transactions_table" class="table table-striped" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Transaction Code</th>
                                            <th>Transaction Type</th>
                                            <th>Transaction With</th>
                                            <th>Request Date</th>
                                            <th>Apprvd/Rjctd Date</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                            <th>Balance</th>
                                            <th>Comment</th>
                                            {{-- <th>Details</th> --}}
                                            <th>Receipt</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade in" id="buy_sell_transactions" role="tabpanel">
                            <div class="tab-content" style="margin-top: 20px">
                                <table id="buy_sell_transactions_table" class="table table-striped" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Transaction Code</th>
                                            <th>Transaction Type</th>
                                            <th>Transaction With</th>
                                            <th>Request Date</th>
                                            <th>Apprvd/Rjctd Date</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                            <th>Comment</th>
                                            <th>Receipt</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>                        
                        <div class="tab-pane fade in" id="payout" role="tabpanel">
                            <div class="tab-content" style="margin-top: 20px">
                                <table id="earnings_table" class="table table-striped" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Transaction Code</th>
                                            <th>Date & Time</th>
                                            <th>Amount</th>
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

    <div id="partner_rejection_modal" class="modal fade" role="dialog">
        <div class="modal-dialog" style="width: 400px;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h5 class="modal-title">Reasons for Rejecting</h5>
                </div>
                <div class="modal-body" >
                    <form id="reject_partner_deposit_cashout" method="POST">

                        <div class="alert alert-danger print-error-msg" style="display:none">
                            <ul></ul>
                        </div>

                        <input type="hidden" id="rejection_type" value="">
                        <div class="form-group">
                            <label class="control-label">Name: <span class="name_span_text"></span></label>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Code: <span class="code_span_text"></span></label>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Message:</label>
                            <textarea id="partner_comment" name="partner_comment" class="form-control" style="resize: none;"></textarea>
                            <label class="error-label">Message is required</label>
                        </div>
                        
                    </form>
                </div>
                <div class="modal-footer">
                    <button id="reject-save-btn" type="button" class="btn btn-primary">Save</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <div id="sell-credit-modal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close close-partner-modal-btn" data-dismiss="modal">&times;</button>
                    <h5 class="modal-title transaction-title">Just 2ez</h5>
                </div>
                <div class="modal-body">
                    <div class="credit_transactions">
                        <form id="partner_wallet_update" autocomplete="off" action="" method="POST" role="form">
                            <input type="hidden" id="transaction_type" name="transaction_type">
                            <input type="hidden" id="transaction_trade_type" name="transaction_trade_type">
                            <div class="form-group">
                                <label class="control-label">Amount:</label>
                                <input id="partner_request_credits" name="amount" class="form-control" style="resize: none;" placeholder="Add comment here...">
                                <label class="error-label message"></label>
                            </div> 
                            <div class="form-group">
                                <label class="control-label transaction_label"></label>
                                    <label class="checkbox-container">BPI 
                                        <input type="radio" name="partner_choice" value="BPI" class="checkbox-item" name="radio">
                                        <span class="checkmark"></span>
                                    </label>
                                    <label class="checkbox-container">BDO
                                        <input type="radio" name="partner_choice" value="BDO" class="checkbox-item" name="radio">
                                        <span class="checkmark"></span>
                                    </label>
                                    <label class="checkbox-container">Others 
                                        <input type="radio" name="partner_choice" value="cash" class="checkbox-item" name="radio">
                                        <span class="checkmark"></span>
                                    </label>
                            </div>  
                            <div class="form-group" style="margin-bottom: 35px;">
                                <label class="control-label">Account Details</label><br>
                                <div class="form-twin-column" style="float: left;">
                                    <input type="text" name="account_number" id="account_number" class="form-control disabled-fields" placeholder="Account Number" disabled>
                                </div>
                                <div class="form-twin-column" style="float: right;">
                                    <input type="text" name="account_name" id="account_name" class="form-control disabled-fields" placeholder="Account Name" disabled>
                                </div>
                                <label class="error-label">Please fill it up</label>
                            </div>                    
                        </form>
                    </div>

                    <div id="complete_buy_credits">
                        <div class="alert alert-success" role="alert">
                            <strong>Saved!</strong> Request was successful. <b style="font-size: 17px;">BC Code: </b><span id="partner-code" style="font-size: 17px;"></span>
                        </div>
                        <ul class="list-unstyled" id="partner-deposit-steps"></ul>
                        <form autocomplete="off" action="" method="POST" role="form">
                            {{-- <input type="hidden" id="transaction_type" name="transaction_type"> --}}
                            <div class="form-group">
                                <label class="control-label">Receipt:</label>
                                <input id="partner_receipt_input" name="image" accept="image/*" class="file-loading partner_receipt_input" type="file">
                            </div>                    
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="partner-credit-initiate" type="button" class="btn btn-primary" disabled>Submit</button>
                    <button id="complete-deposit-partner" type="button" class="btn btn-primary">Complete Deposit</button>
                    <button type="button" class="btn btn-default close-partner-modal-btn" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <div id="update-partner-modal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close close-partner-modal-btn" data-dismiss="modal">&times;</button>
                    <h5 class="modal-title transaction-title">Update Details</h5>
                </div>
                <div class="modal-body">
                    <form id="partner_details_update" autocomplete="off" action="" method="POST" role="form"> 
                        <div class="form-group">
                            
                        </div>                    
                    </form>
                </div>
                <div class="modal-footer">
                    <button id="partner-info-update" type="button" class="btn btn-primary">Submit</button>
                    <button type="button" class="btn btn-default close-partner-modal-btn" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <div id="public-profile-modal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close close-partner-modal-btn" data-dismiss="modal">&times;</button>
                    <h5 class="modal-title transaction-title">Set/Update Public Profile</h5>
                </div>
                <div class="modal-body">
                    <form id="public_profile_form" autocomplete="off" action="" method="POST" role="form"> 
                        <div class="form-group">
                            <label> Profile Title </label>
                            <input id="profile_title" name="title" class="form-control" style="resize: none;" value="{{ $partnership->partner_name }}" placeholder="Public Profile Title" data-toggle="tooltip" title="Your partnership name will be the default profile title if this field is empty.">
                            <label class="error-label">Invalid Input</label>
                        </div>    
                        <div class="form-group">
                            <label> Content </label>
                            <textarea class="form-control" id="section_content_ckedit"></textarea>
                            <label class="error-label">Invalid Input</label>
                        </div>                    
                    </form>
                </div>
                <div class="modal-footer">
                    <button id="partner-profile" type="button" class="btn btn-primary">Submit</button>
                    <button type="button" class="btn btn-default close-partner-modal-btn" data-dismiss="modal">Cancel</button>
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
                    <h5 class="modal-title">Download: <span id="download-type"></span></h5>
                </div>
                <div class="modal-body" >
                    <form id="partner-download-transactions-form" method="POST" class="form-horizontal">
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
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Include following Transaction: </label>
                            <div class="col-sm-9">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="inlineCheckbox1" value="1" checked name="status[]">
                                    <label class="form-check-label" for="inlineCheckbox1">Completed</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="inlineCheckbox2" value="2" checked name="status[]">
                                    <label class="form-check-label" for="inlineCheckbox2">Rejected</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="inlineCheckbox3" value="0" checked name="status[]">
                                    <label class="form-check-label" for="inlineCheckbox3">Pending/Processing</label>
                                </div>
                            </div>                            

                        </div>

                        <input type="hidden" name="partner_id" value="{{$partnership->id}}" />
                        <input type="hidden" name="type" id="type" />
                        <input type="hidden" name="tradeType" id="tradeType" />
                    </form>
                </div>
                <div class="modal-footer">
                    <button id="proceed-download-btn" type="button" class="btn btn-primary">Download</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <!-- end downloading parnter transactions modal -->

    <!-- uploading receipt/image to transaction modal -->
    <div id="attach-receipt-modal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close close-partner-modal-btn" data-dismiss="modal">&times;</button>
                    <h5 class="modal-title transaction-title">Attach Receipt for Transaction</h5>
                </div>
                <div class="modal-body">

                    <div>
                        {{-- <div class="alert alert-success" role="alert">
                            <strong>Saved!</strong> Request was successful. <b style="font-size: 17px;">BC Code: </b><span id="partner-code" style="font-size: 17px;"></span>
                        </div> --}}
                        <form autocomplete="off" action="" method="POST" role="form">
                            {{-- <input type="hidden" id="transaction_type" name="transaction_type"> --}}
                            <div class="form-group">
                                <label class="control-label">Receipt:</label>
                                <input id="partner_receipt_input" name="image" accept="image/*" class="file-loading partner_receipt_input" type="file">
                            </div>                    
                        </form>
                    </div>
                </div>
                <input type="hidden" id="transaction_id" value="0" />
                <input type="hidden" id="refresh_table" value="transcations" />
                <div class="modal-footer">
                    <button id="submit-attached-receipt" type="button" class="btn btn-primary">Submit</button>
                    <button type="button" class="btn btn-default close-partner-modal-btn" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
    <!-- end uploading receipt/image to transaction modal -->

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
<script src="https://cdn.ckeditor.com/4.5.7/full/ckeditor.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        $('.datetime_sched').datetimepicker({
            // viewMode: 'days',
            format: 'YYYY-MM-DD HH:mm:ss'
        }).on('dp.change', function(e){ $(this).parent().removeClass('has-error'); });
    });
</script>

<script id="manual-steps-template" type="text/template">
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
</script>
<script id="partner-walkin" type="text/template">
    <li>1. Visit @{{partner}} at @{{address}} in this schedule, @{{schedule}}.</li>
    <li>2. Approach the contact person in the area and tell about the transaction code - <strong>@{{code}}</strong>.</li>
    <li>3. Pay for the amount to the person.</li>
    <li>4. The person will transfer the credits to your account once the payment is complete.</li>
</script>
<script id="cash-others" type="text/template">
    <li>Please contact 2ez.bet page for instructions.</li>
</script>
<script>
    var deposits_table
    var cashout_table
    var agent_transactions_table
    var buy_sell_transactions_table
    var referal_table
    var bugs_table
    var promo_table
    var parentPartner = {!! !empty($parentPartner) ? json_encode($parentPartner->partner) : 'null'  !!};
    //parentPartner = !!parentPartner ? JSON.parse(parentPartner) : null;

    $(function(){
        updatePartnerCredits();
        CKEDITOR.replace('section_content_ckedit', {
            extraAllowedContent: [
                'iframe(*)[*]{*}','p(*)[*]{*}','a(*)[*]{*}','div(*)[*]{*}'
            ],
            toolbar: [
                { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ], items: [ 'Bold', 'Italic', 'Underline', 'Strike' ] },
                { name: 'paragraph', groups: [ 'list', 'indent', 'align' ], items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock' ] },
                { name: 'insert', items: [ 'Image', 'Table', 'Iframe' ] },
                { name: 'links', items : [ 'Link','Unlink','Anchor' ] },
                '/',
                { name: 'colors', items: [ 'TextColor', 'BGColor' ] },
                { name: 'styles', items: [ 'Styles', 'Font', 'FontSize' ] },
                { name: 'document', items: [ 'NewPage', 'Preview', 'Print', 'Source' ] },
            ]
        });
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
            serverSide: true,
            responsive: true,
            deferLoading: 0,
            ajax: "{!! route('get_partner_users_transactions',['type' => 'deposit', 'trade_type' => 'partner-user', 'partner_id' => $partnership->id]) !!}",
            order: [[ 3, "desc" ]],
            columns: [
                {
                    data: 'code', 
                    name: 'code'
                },
                {
                    data: 'user.id',
                    name: 'user.id',
                },
                {
                    data: 'user.name', 
                    name: 'user.name',
                    render: function(data,type,row){
                        // console.log('rowwwww: ', row)
                        let displayName = `${data}`;
                        let html = ``;

                        const verifiedBy = !!row['verified_by'] ? row['verified_by'] : false;
                        const isVerifiedByUs = !!row['verified_by'] ? row['verified_by'].filter(v => v.verified_type == '2ez') : false;
                        const isVerifiedByPartners = !!row['verified_by'] ? row['verified_by'].filter(v => v.verified_type == 'partner') : false;
                        if(!!verifiedBy){

                            if(isVerifiedByUs.length > 0){
                                html +=  `<br/><span class="badge verified-2ez"> <i class="fa fa-check-circle" aria-hidden="true"></i> Verified by 2ez.bet</span>`;
                            }

                            if(isVerifiedByPartners.length > 0){
                                html +=  `<br/><span class="badge verified-partner" title="Click to view Partners that verified ${row.user.name}." data-user-id="${row.id}"> <i class="fa fa-check-circle" aria-hidden="true"></i> Verified by Partner <i class="fa fa-info-circle" aria-hidden="true"></i></span>`;
                            }
                        }

                        return displayName + html;
                    }
                },
                {
                    data: 'created_at', 
                    name: 'created_at',
                    searchable: false,
                    render: function(data,type,row){
                        return moment(data).format('llll')+'<br>'+moment(data).fromNow()
                    }
                },
                {
                    data: 'approved_rejected_date', 
                    name: 'approved_rejected_date',
                    searchable: false,
                    render: function(data,type,row){
                        return !!data ? moment(data).format('llll')+'<br>'+moment(data).fromNow() : '';
                    }
                },                  
                {
                    data: 'amount', 
                    name: 'amount',
                    render: function(data,type,row){
                        return '&#8369; ' + numberWithCommas(data);
                    }
                },
                {
                    data: 'partner_earnings', 
                    name: 'partner_earnings',
                    visible: "{{ isPartnerSubUser($user) }}" != '' ? false : true ,
                    render: function(data,type,row){
                        return '&#8369; '+ data;                            
                    }
                },
                {
                    data: 'voucher_code', 
                    name: 'voucher_code',
                    render: function(data,type,row){
                        if(!!data){
                            return data;
                        }else{
                            if( "{{ $partnership->id == env('TIMS_AGENT_PARTNER_ID')  ? 1 : 0 }}" == 1 ){
                                const registeredDate = moment(row['user']['created_at']);
                                const aMonthAgo = moment().subtract(1, 'months');
                                const startingApril = moment('2021-03-31 23:59:59');

                                if(aMonthAgo.isBefore(registeredDate) && registeredDate.isAfter(startingApril) ){
                                    //console.log(' before aMonthAgo registeredDate', aMonthAgo, registeredDate, startingApril, row.code)
                                    //return 'CAN CHANGE';
                            
                                return `<button class="btn btn-primary btn-xs set-user-voucher">
                                    SET VOUCHER
                                    </button>`;

                                }else{
                                    //console.log(' not before aMonthAgo registeredDate', aMonthAgo, registeredDate, startingApril, row.code)
                                    return 'N/A';
                                }
                                
                            }else{
                                return 'N/A'
                            }
                        }
    
                    }
                },
                {
                    data: 'status', 
                    name: 'status',
                    searchable:false,
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
                                default: 
                                    return "Needs Approval";
                            }
                        }
                    }
                },
                // {
                //     render: function(data,type,row){
                //         return '<a href="#" class="btn btn-default view-details"><i class="fa fa-info" aria-hidden="true"></i></a>';
                //     }
                // },
                {
                    data: 'picture',
                    searchable:false,
                    render: function(data,type,row){
                        let pictureText= '';
                        if(!!data){ //means there's an image already attached on this transaction
                            pictureText += `<div>`;
                            pictureText += `<a href="#" class="btn btn-default btn-xs view-receipt"><i class="fa fa-picture-o" aria-hidden="true"></i></a> `;
                            
                            if(row['status'] == 0){ //if its not yet approved or rejected then allow partner to change image
                                pictureText += `<a href="#" class="btn btn-xs btn-primary attach-receipt" data-id="${row['id']}" data-code="${row['code']}" data-table="deposits"><i class="fa fa-picture-o" aria-hidden="true"></i> Change</a>`;
                            }

                            pictureText += `</div>`;
                        }else{ //else there's no image; so we have to check if its a deposit or cashout; if deposit then partner can upload his/her receipt
                            
                            if(row['status'] != 2){
                                pictureText += `<a href="#" class="btn btn-xs btn-primary attach-receipt" data-id="${row['id']}" data-code="${row['code']}" data-table="deposits"><i class="fa fa-picture-o" aria-hidden="true"></i> Attach</a>`;
                            }
                            
                        }    
                        
                        return pictureText;
                        // return data ? '<a href="#" class="btn btn-default view-receipt"><i class="fa fa-picture-o" aria-hidden="true"></i></a>' : 
                        //                 `<a href="#" class="btn btn-sm btn-primary attach-receipt"><i class="fa fa-picture-o" aria-hidden="true"></i> Attach </a>`;
                    }
                },
                {
                    data: 'process_by.name', 
                    name: 'process_by.name',
                },
                {
                    render: function ( data, type, row ) {
                        if(row['partner_comment'] != null && row['status'] > 1){
                            return row['partner_comment'];
                        }
                        else{
                            const verifiedBy = !!row['verified_by'] ? row['verified_by'] : false;
                            const isVerifiedByUs = !!row['verified_by'] ? row['verified_by'].filter(v => v.verified_type == 'partner' && v.verified_by == {{  $partnership->id }}) : false;
                            
                            let btn_mark_verified = isVerifiedByUs.length > 0 ? `` : ` <button class="btn btn-primary btn-sm mark-verified-user"><i class="fa fa-check-o" aria-hidden="true"></i> Mark User as Verified</button>`;
                            switch(row['status']){
                                case 0:
                                case '0':
                                    return '<button type="button" class="btn btn-success btn-sm accept-deposit" data-tid="'+row['id']+'" data-userid="'+row['user_id']+'">Accept</button> '
                                        +'<button type="button" class="btn btn-danger btn-sm decline-deposit" data-tid="'+row['id']+'" data-userid="'+row['user_id']+'" data-toggle="modal">Reject</button>'
                                        + btn_mark_verified;
                                default:
                                    return '<button type="button" class="btn btn-success btn-sm" data-tid="'+row['id']+'" data-userid="'+row['user_id']+'" disabled>Done</button>' + btn_mark_verified;
                            }
                        }
                    }
                }
            ],
            drawCallback: function( settings ) {
                var pending_tr = this.api().rows().data();
                var number = 0;
                if(pending_tr.length > 0){
                    for(var i = 0; i < pending_tr.length; i++){
                        if(pending_tr[i].status == 0){
                            number += 1;
                            if(number > 0)
                                $('#depo_cnt').attr('data-count', number);
                            else
                                $('#depo_cnt').removeAttr('data-count');
                        }
                    }
                }
                else{
                    $('#depo_cnt').removeAttr('data-count');
                }
            }
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
            destroy: true,
            responsive: true,
            deferLoading: 0,
            ajax: "{!! route('get_partner_users_transactions',['type' => 'cashout', 'trade_type' => 'partner-user', 'partner_id' => $partnership->id]) !!}",
            order: [[ 3, "desc" ]],
            columns: [
                {
                    data: 'code', 
                    name: 'code'
                },
                {
                    data: 'user.id', 
                    name: 'user.id',
                },
                {
                    data: 'user.name', 
                    name: 'user.name',
                    render: function(data,type,row){
                        // console.log('rowwwww: ', row)
                        let displayName = `${data}`;
                        let html = ``;

                        const verifiedBy = !!row['verified_by'] ? row['verified_by'] : false;
                        const isVerifiedByUs = !!row['verified_by'] ? row['verified_by'].filter(v => v.verified_type == '2ez') : false;
                        const isVerifiedByPartners = !!row['verified_by'] ? row['verified_by'].filter(v => v.verified_type == 'partner') : false;
                        if(!!verifiedBy){

                            if(isVerifiedByUs.length > 0){
                                html +=  `<br/><span class="badge verified-2ez"> <i class="fa fa-check-circle" aria-hidden="true"></i> Verified by 2ez.bet</span>`;
                            }

                            if(isVerifiedByPartners.length > 0){
                                html +=  `<br/><span class="badge verified-partner" title="Click to view Partners that verified ${row.user.name}." data-user-id="${row.id}"> <i class="fa fa-check-circle" aria-hidden="true"></i> Verified by Partner <i class="fa fa-info-circle" aria-hidden="true"></i></span>`;
                            }
                        }

                        return displayName + html;
                    }
                },
                {
                    data: 'created_at', 
                    name: 'created_at',
                    render: function(data,type,row){
                        return moment(data).format('llll')+'<br>'+moment(data).fromNow()
                    }
                },
                {
                    data: 'approved_rejected_date', 
                    name: 'approved_rejected_date',
                    render: function(data,type,row){
                        return !!data ? moment(data).format('llll')+'<br>'+moment(data).fromNow() : '';
                    }
                },                
                {
                    data: 'amount', 
                    name: 'amount',
                    render: function(data,type,row){
                        var net = parseFloat(data) -  parseFloat(data) * parseFloat('{{ env('CASHOUT_FEE')}}');
                        
                        return '&#8369; ' + numberWithCommas(net.toFixed(2));
                    }
                },
                {
                    data: 'partner_earnings', 
                    name: 'partner_earnings',
                    visible: "{{ isPartnerSubUser($user) }}" != '' ? false : true ,
                    render: function(data,type,row){
                        return '&#8369; '+ data;                            
                        
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
                                    break;
                            }
                        }
                    }
                },
                // {
                //     render: function(data,type,row){
                //         return '<a href="#" class="btn btn-default view-details"><i class="fa fa-info" aria-hidden="true"></i></a>';
                //     }
                // },
                {
                    data: 'picture',
                    searchable:false,
                    render: function(data,type,row){
                        let pictureText= '';
                        if(!!data){ //means there's an image already attached on this transaction
                            pictureText += `<div>`;
                            pictureText += `<a href="#" class="btn btn-default btn-xs view-receipt"><i class="fa fa-picture-o" aria-hidden="true"></i></a> `;
                            
                            if(row['status'] == 0){ //if its not yet approved or rejected then allow partner to change image
                                pictureText += `<a href="#" class="btn btn-xs btn-primary attach-receipt" data-id="${row['id']}" data-code="${row['code']}" data-table="cashouts"><i class="fa fa-picture-o" aria-hidden="true"></i> Change</a>`;
                            }

                            pictureText += `</div>`;
                        }else{ //else there's no image; so we have to check if its a deposit or cashout; if deposit then partner can upload his/her receipt
                            
                            if(row['status'] != 2){
                                    pictureText += `<a href="#" class="btn btn-xs btn-primary attach-receipt" data-id="${row['id']}" data-code="${row['code']}" data-table="deposits"><i class="fa fa-picture-o" aria-hidden="true"></i> Attach</a>`;
                            }
                        }    
                        
                        return pictureText;                        
                        // return data ? '<a href="#" class="btn btn-default view-receipt"><i class="fa fa-picture-o" aria-hidden="true"></i></a>' : 
                        //                 `<a href="#" class="btn btn-sm btn-primary attach-receipt"><i class="fa fa-picture-o" aria-hidden="true"></i> Attach </a>`;
                    }
                },
                {
                    data: 'process_by.name', 
                    name: 'process_by.name',
                },
                {
                    render: function ( data, type, row ) {
                        if(row['partner_comment'] != null && row['status'] > 1){
                            return row['partner_comment'];
                        }
                        else{
                            const verifiedBy = !!row['verified_by'] ? row['verified_by'] : false;
                            const isVerifiedByUs = !!row['verified_by'] ? row['verified_by'].filter(v => v.verified_type == 'partner' && v.verified_by == {{  $partnership->id }}) : false;
                            
                            let btn_mark_verified = isVerifiedByUs.length > 0 ? `` : ` <button class="btn btn-primary btn-sm mark-verified-user"><i class="fa fa-check-o" aria-hidden="true"></i> Mark User as Verified</button>`;
                            switch(row['status']){
                                case 0:
                                case '0':
                                    return '<button type="button" class="btn btn-success btn-sm accept-cashout" data-tid="'+row['id']+'" data-userid="'+row['user_id']+'">Accept</button> '
                                        +'<button type="button" class="btn btn-danger btn-sm decline-cashout" data-tid="'+row['id']+'" data-userid="'+row['user_id']+'" data-toggle="modal">Reject</button>';
                                        +btn_mark_verified
                                case -1:
                                case '-1':
                                    return '<button type="button" class="btn btn-warning btn-sm" data-tid="'+row['id']+'" data-userid="'+row['user_id']+'" disabled>Pending Admin Verification</button>' + btn_mark_verified;                                    
                                default:
                                    return '<button type="button" class="btn btn-success btn-sm" data-tid="'+row['id']+'" data-userid="'+row['user_id']+'" disabled>Done</button>' + btn_mark_verified;
                            }
                        }
                    }
                }
            ],
            drawCallback: function( settings ) {
                var pending_tr = this.api().rows().data();
                var number = 0;
                if(pending_tr.length > 0){
                    for(var i = 0; i < pending_tr.length; i++){
                        if(pending_tr[i].status == 0){
                            number += 1;
                            if(number > 0)
                                $('#cash_cnt').attr('data-count', number);
                            else
                                $('#cash_cnt').removeAttr('data-count');
                        }
                    }
                }
                else{
                    $('#cash_cnt').removeAttr('data-count');
                }
            }
         })

        agent_transactions_table = $('#agent_transactions_table').DataTable({
            processing: true,
            serverSide:true,
            destroy: true,
            responsive: true,
            deferLoading: 0,
            ajax: "{!! route('get_partner_agent_transactions',[ 'trade_type' => 'partner-user', 'main_partner_id' => $partnership->id]) !!}",
            order: [[ 2, "desc" ]],
            columns: [
                {
                    data: 'code', 
                    name: 'code'
                },
                {
                    data: 'partner_name', 
                    name: 'partner_name',
                },
                {
                    data: 'partner_transactions.name', 
                    name: 'partner_transactions.name',
                },
                {
                    data: 'created_at', 
                    name: 'created_at',
                    render: function(data,type,row){
                        return moment(data).format('llll')+'<br>'+moment(data).fromNow()
                    }
                },
                {
                    data: 'approved_rejected_date', 
                    name: 'approved_rejected_date',
                    render: function(data,type,row){
                        return !!data ? moment(data).format('llll')+'<br>'+moment(data).fromNow() : '';
                    }
                },                   
                {
                    data: 'amount', 
                    name: 'amount',
                    render: function(data,type,row){
                        var net = parseFloat(data);
                        return '&#8369; ' + net;
                    }
                },
                {
                    data: 'partner_earnings', 
                    name: 'partner_earnings',
                    render: function(data,type,row){
                            return '&#8369; '+ data;                            
                    }
                },
                {
                    data: 'main_partner_earnings', 
                    name: 'main_partner_earnings',
                    render: function(data,type,row){
                        return '&#8369; '+ data;                            
                        
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
                                    break;
                            }
                        }
                    }
                },
                // {
                //     render: function(data,type,row){
                //         return '<a href="#" class="btn btn-default view-details"><i class="fa fa-info" aria-hidden="true"></i></a>';
                //     }
                // },
                {
                    data: 'picture',
                    searchable:false,
                    render: function(data,type,row){
                        return data ? '<a href="#" class="btn btn-default view-receipt"><i class="fa fa-picture-o" aria-hidden="true"></i></a>' : 
                                       'No receipt';
                    }
                },
            ],
            drawCallback: function( settings ) {
                var pending_tr = this.api().rows().data();
                var number = 0;
                if(pending_tr.length > 0){
                    for(var i = 0; i < pending_tr.length; i++){
                        if(pending_tr[i].status == 0){
                            number += 1;
                            if(number > 0)
                                $('#cash_cnt').attr('data-count', number);
                            else
                                $('#cash_cnt').removeAttr('data-count');
                        }
                    }
                }
                else{
                    $('#cash_cnt').removeAttr('data-count');
                }
            }
         })         

        transactions_table = $('#transactions_table').DataTable({
            processing: true,
            serverSide:true,
            responsive: true,
            deferLoading: 0,
            ajax: "{!! route('get_partner_admin_transactions',['trade_type' => 'partner-admin', 'partner_id' => $partnership->id]) !!}",
            order: [[ 4, "desc" ]], //order by date & time
            drawCallback: function(settings) {
                // tips_view_table.draw()
                var data = this.api().rows().data()
                if (data.length > 0) {
                    for (var i = 0; i < data.length; i++) {
                        $(".image-"+data[i].id).fileinput({
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
                            var data = transactions_table.row($(this).parents('tr') ).data();
                            var code = data.code;
                            $.ajax({
                                url: '{{ route('upload_partner') }}',
                                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                                type: 'POST',
                                data: { photo:$('.file-preview-image').attr('src'),id:data.id },
                                success: function(data) {
                                    if (data.success) {
                                        transactions_table.ajax.reload()
                                        swal({
                                            title: "Your deposit request "+code+" is currently being processed.",
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
                    data: 'type', 
                    name: 'type',
                    render: function(data,type,row){
                        switch(row['trade_type']){
                            case 'partner-user':
                                return data == 'deposit' ? 'Purchase' : 'Transfer';
                                break;                                
                            case 'partner-sub': 
                            case 'partner-admin':
                                return data == 'deposit' ? 'Buy credits' : 'Sell credits';
                            
                                break;
                        }                        
                    }
                },
                {
                    data: 'trade_type', 
                    name: 'trade_type',
                    render: function(data,type,row){
                        switch(data){
                            case 'partner-user':
                                return `${row['user']['name']} (bettor)` ;
                                break;
                            case 'partner-admin':
                                return '2ez.bet';
                                break;
                            case 'partner-sub': 
                                let tradeTypeText =  row['partner_id']  == '{{$partnership->id}}' ?  'MAIN AGENT'  : row['partner']['partner_name'];
                                tradeTypeText +=  row['partner_id']  != '{{$partnership->id}}' ? ` (sub-agent)` : '';
                                return `${tradeTypeText}`;
                                break;
                        }
                        //return data == 'partner-admin' ? '2ez.bet' : (  row['partner_id']  == '{{$partnership->id}}' ?  row['parent_partner_name']  : row['partner_name']) ;
                    }
                },
                {
                    data: 'created_at', 
                    name: 'created_at',
                    render: function(data,type,row){
                        return moment(data).format('llll')+'<br>'+moment(data).fromNow()
                    }
                },
                {
                    data: 'approved_rejected_date', 
                    name: 'approved_rejected_date',
                    render: function(data,type,row){
                        return !!data ? moment(data).format('llll')+'<br>'+moment(data).fromNow() : '';
                    }
                },                   
                {
                    data: 'amount', 
                    name: 'amount',
                    render: function(data,type,row){
                        switch(row['trade_type']){
                            case 'partner-user':
                            var cashoutNet = parseFloat(data) -  parseFloat(data) * parseFloat('{{ env('CASHOUT_FEE')}}');
                            // return '&#8369; ' + numberWithCommas(cashoutNet.toFixed(2));
                            return row['type'] == 'deposit' ? `<span class="text-danger">&#8369; ${numberWithCommas(data)} (-)</span>` :  `<span class="text-success">&#8369; ${numberWithCommas(cashoutNet.toFixed(2))} (+)</span>`;
                                break;
                            case 'partner-sub': 
                                if(row['partner_id']  == '{{$partnership->id}}'){
                                    return row['type'] == 'deposit' ?  `<span class="text-success">&#8369; ${numberWithCommas(data)} (+)</span>` : `<span class="text-danger">&#8369; ${numberWithCommas(data)} (-)</span>`;
                                }else{
                                    return row['type'] == 'deposit' ? `<span class="text-danger">&#8369; ${numberWithCommas(data)} (-)</span>` :  `<span class="text-success">&#8369; ${numberWithCommas(data)} (+)</span>`;
                                }
                                
                                break;
                            case 'partner-admin':
                                return row['type'] == 'deposit' ? `<span class="text-success">&#8369; ${numberWithCommas(data)} (+)</span>` :  `<span class="text-danger">&#8369; ${numberWithCommas(data)} (-)</span>`;
                                break;
                        }
                        // return '&#8369; ' + data;
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
                                default: 
                                    return "Needs Approval";
                            }
                        }
                    }
                },
                {
                    data: 'remaining_credits', 
                    name: 'remaining_credits',
                    // render: function ( data, type, row ) {
                    //     return row['status'] == 1 ?  `&#8369; ${numberWithCommas(data)}` : '<small>N/A</small>';
                    // }
                    render: function(data,type,row){
                        switch(row['trade_type']){
                            case 'partner-user':
                            case 'partner-admin':
                                return row['status'] == 1 ?  `&#8369; ${numberWithCommas(data)}` : '<small>N/A</small>';
                                break;                                
                            case 'partner-sub': 
                                return row['partner_id'] == '{{$partnership->id}}' ?  `&#8369; ${numberWithCommas(data)}` : '-';
                                break;
                        }                        
                    }

                },                
                {
                    data: 'partner_comment', 
                    name: 'partner_comment'
                },
                // {
                //     data: 'data', 
                //     name: 'data',
                //     render: function(data,type,row){
                //         return '<a href="#" class="btn btn-default view-details"><i class="fa fa-info" aria-hidden="true"></i></a>';
                //     }
                // },
                {
                    data:'picture',
                    searchable:false,
                    render: function(data,type,row){
                        switch(row['trade_type']){
                            case 'partner-admin':
                            case 'partner-sub':
                            case 'partner-user':

                                let pictureText = "";
                                if(!!data){ //means there's an image already attached on this transaction
                                    pictureText += `<div>`;
                                    pictureText += `<a href="#" class="btn btn-default btn-xs view-receipt"><i class="fa fa-picture-o" aria-hidden="true"></i></a> `;
                                    
                                    if(row['status'] == 0){ //if its not yet approved or rejected then allow partner to change image
                                        pictureText += `<a href="#" class="btn btn-xs btn-primary attach-receipt" data-id="${row['id']}" data-code="${row['code']}" data-table="transactions"><i class="fa fa-picture-o" aria-hidden="true"></i> Change</a>`;
                                    }

                                    pictureText += `</div>`;
                                }else{ //else there's no image; so we have to check if its a deposit or cashout; if deposit then partner can upload his/her receipt
                                    
                                    if(row['type'] == 'deposit' && row['status'] != 2){
                                        pictureText += `<a href="#" class="btn btn-xs btn-primary attach-receipt" data-id="${row['id']}" data-code="${row['code']}" data-table="transactions"><i class="fa fa-picture-o" aria-hidden="true"></i> Attach</a>`;
                                    }else{ //else cashout

                                        pictureText += `<small>No receipt</small>`;
                                    }
                                }

                                return pictureText;

                            break;
                            default: 
                                return `<small>N/A</small>`;

                            break;
                        }
                    
                    }
                },
                {
                    data: 'status',
                    searchable:false,
                    render: function ( data, type, row ) {
                        if( row['trade_type'] == 'partner-admin' || data == 2 ){
                            return 'N/A';
                        }else if( row['trade_type'] == 'partner-user'){
                            return `<small>N/A - Check ${row['type'] == 'deposit' ? 'User Initiated Deposits' : 'User Initiated Cashouts'} Tab</small>`;
                        }
                        else{
                            if(row['main_partner_id'] != '{{$partnership->id}}'){
                                return 'N/A';  
                            }
                            else if(row['type'] == 'deposit'){
                                if (row['picture'] == null && data != 1) {
                                    return '<a href="#" data-status="rejected" class="btn btn-danger btn-sm btn-reject">Reject</a> <a href="#" data-status="deleted" class="btn btn-primary btn-sm btn-edit approve">Check and approve</a>'
                                }else{
                                    if (data == 1) {
                                        return 'N/A';
                                    }else{
                                        return '<a href="#" data-status="rejected" class="btn btn-danger btn-sm btn-reject">Reject</a> <a href="#" data-status="deleted" class="btn btn-primary btn-sm btn-edit approve">Check and approve</a>'
                                    }
                                }
                            }
                            else{
                                if (data == 1) {
                                    return '<a href="#" data-status="discrepancy" class="btn btn-success btn-sm btn-edit" disabled>Process Done</a>'
                                }else{
                                    return '<a href="#" data-status="rejected" class="btn btn-danger btn-sm btn-reject">Reject</a> <a href="#" data-status="deleted" class="btn btn-primary btn-sm btn-edit approve">Mark as Processed</a>'
                                }
                            }
                        }
                    }
                }

                
            ]
         })

         buy_sell_transactions_table = $('#buy_sell_transactions_table').DataTable({
            processing: true,
            serverSide:true,
            responsive: true,
            deferLoading: 0,
            ajax: "{!! route('get_partner_buy_sell_admin_transactions',['trade_type' => 'partner-admin', 'partner_id' => $partnership->id]) !!}",
            order: [[ 3, "desc" ]], //order by date & time
            drawCallback: function(settings) {
                // tips_view_table.draw()
                var data = this.api().rows().data()
                if (data.length > 0) {
                    for (var i = 0; i < data.length; i++) {
                        $(".image-"+data[i].id).fileinput({
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
                            var data = transactions_table.row($(this).parents('tr') ).data();
                            var code = data.code;
                            $.ajax({
                                url: '{{ route('upload_partner') }}',
                                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                                type: 'POST',
                                data: { photo:$('.file-preview-image').attr('src'),id:data.id },
                                success: function(data) {
                                    if (data.success) {
                                        transactions_table.ajax.reload()
                                        swal({
                                            title: "Your deposit request "+code+" is currently being processed.",
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
                    data: 'type', 
                    name: 'type',
                    render: function(data,type,row){
                        return data == 'deposit' ? 'Buy credits' : 'Sell credits';
                    }
                },
                {
                    data: 'trade_type', 
                    name: 'trade_type',
                    render: function(data,type,row){
                        switch(data){
                            case 'partner-admin':
                                return '2ez.bet';
                                break;
                            case 'partner-sub': 
                                const tradeTypeText =  row['partner_id']  == '{{$partnership->id}}' ?  row['parent_partner_name']  : row['partner_name'];
                                return `${tradeTypeText}`;
                                break;
                        }
                        
                    }
                },
                {
                    data: 'created_at', 
                    name: 'created_at',
                    render: function(data,type,row){
                        return moment(data).format('llll')+'<br>'+moment(data).fromNow()
                    }
                },
                {
                    data: 'approved_rejected_date', 
                    name: 'approved_rejected_date',
                    render: function(data,type,row){
                        return !!data ? moment(data).format('llll')+'<br>'+moment(data).fromNow() : '';
                    }
                },                   
                {
                    data: 'amount', 
                    name: 'amount',
                    render: function(data,type,row){

                        return `&#8369; ${numberWithCommas(data)}`;
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
                                default: 
                                    return "Needs Approval";
                            }
                        }
                    }
                },               
                {
                    data: 'partner_comment', 
                    name: 'partner_comment'
                },
                // {
                //     data: 'data', 
                //     name: 'data',
                //     render: function(data,type,row){
                //         return '<a href="#" class="btn btn-default view-details"><i class="fa fa-info" aria-hidden="true"></i></a>';
                //     }
                // },
                {
                    data:'picture',
                    searchable:false,
                    render: function(data,type,row){
                        switch(row['trade_type']){
                            case 'partner-admin':
                            case 'partner-sub':
                            case 'partner-user':

                                let pictureText = "";
                                if(!!data){ //means there's an image already attached on this transaction
                                    pictureText += `<div>`;
                                    pictureText += `<a href="#" class="btn btn-default btn-xs view-receipt"><i class="fa fa-picture-o" aria-hidden="true"></i></a> `;
                                    
                                    if(row['status'] == 0){ //if its not yet approved or rejected then allow partner to change image
                                        pictureText += `<a href="#" class="btn btn-xs btn-primary attach-receipt" data-id="${row['id']}" data-code="${row['code']}" data-table="transactions"><i class="fa fa-picture-o" aria-hidden="true"></i> Change</a>`;
                                    }

                                    pictureText += `</div>`;
                                }else{ //else there's no image; so we have to check if its a deposit or cashout; if deposit then partner can upload his/her receipt
                                    
                                    if(row['type'] == 'deposit' && row['status'] != 2){
                                        pictureText += `<a href="#" class="btn btn-xs btn-primary attach-receipt" data-id="${row['id']}" data-code="${row['code']}" data-table="transactions"><i class="fa fa-picture-o" aria-hidden="true"></i> Attach</a>`;
                                    }else{ //else cashout

                                        pictureText += `<small>No receipt</small>`;
                                    }
                                }

                                return pictureText;

                            break;
                            default: 
                                return `<small>N/A</small>`;

                            break;
                        }
                    
                    }
                },
                {
                    data: 'status',
                    searchable:false,
                    render: function ( data, type, row ) {
                        if( row['trade_type'] == 'partner-admin' || data == 2 ){
                            return 'N/A';
                        }else if( row['trade_type'] == 'partner-user'){
                            return `<small>N/A - Check ${row['type'] == 'deposit' ? 'User Initiated Deposits' : 'User Initiated Cashouts'} Tab</small>`;
                        }
                        else{
                            if(row['main_partner_id'] != '{{$partnership->id}}'){
                                return 'N/A';  
                            }
                            else if(row['type'] == 'deposit'){
                                if (row['picture'] == null && data != 1) {
                                    return '<a href="#" data-status="rejected" class="btn btn-danger btn-sm btn-reject">Reject</a> <a href="#" data-status="deleted" class="btn btn-primary btn-sm btn-edit approve">Check and approve</a>'
                                }else{
                                    if (data == 1) {
                                        return 'N/A';
                                    }else{
                                        return '<a href="#" data-status="rejected" class="btn btn-danger btn-sm btn-reject">Reject</a> <a href="#" data-status="deleted" class="btn btn-primary btn-sm btn-edit approve">Check and approve</a>'
                                    }
                                }
                            }
                            else{
                                if (data == 1) {
                                    return '<a href="#" data-status="discrepancy" class="btn btn-success btn-sm btn-edit" disabled>Process Done</a>'
                                }else{
                                    return '<a href="#" data-status="rejected" class="btn btn-danger btn-sm btn-reject">Reject</a> <a href="#" data-status="deleted" class="btn btn-primary btn-sm btn-edit approve">Mark as Processed</a>'
                                }
                            }
                        }
                    }
                }

                
            ]
         })

        earnings_table = $('#earnings_table').DataTable({
            processing: true,
            serverSide:true,
            responsive: true,
            deferLoading: 0,
            ajax: "{!! route('partner_payouts') !!}",
            order: [[ 1, "desc" ]],
            columns: [
                {
                    data: 'code', 
                    name: 'code'
                },
                {
                    data: 'created_at', 
                    name: 'created_at',
                    render: function(data,type,row){
                        return moment(data).format('llll')+'<br>'+moment(data).fromNow()
                    }
                },
                {
                    data: 'earnings', 
                    name: 'earnings',
                    render: function(data,type,row){
                        return '&#8369; ' + data;
                    }
                },
                {
                    data: 'data', 
                    name: 'data',
                    render: function(data,type,row){
                        return '<a href="#" class="btn btn-default view-details"><i class="fa fa-info" aria-hidden="true"></i></a>';
                    }
                },
                {
                    data: 'picture', 
                    name: 'picture',
                    searchable:false,
                    render: function(data,type,row){
                        return data ? '<a href="#" class="btn btn-default view-details"><i class="fa fa-picture-o" aria-hidden="true"></i></a>' : 'No Attached Image';
                    }
                }
            ]
         })
        
        $.fn.digits = function(){ 
            return this.each(function(){ 
                $(this).text( $(this).text().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,") ); 
            })
        }
        function updatePartnerCredits(){
            $.ajax({
                url: "{{ route('get_partner_credits') }}",
                type: "GET",
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                success: function(data){
                    var earnings = parseFloat(data.earnings), credits = parseFloat(data.credits); 
                    $("#partner-ez-credits").html("&#8369; "+ numberWithCommas(credits.toFixed(2)) )
                    $("#partner-ez-credits").data("amount", credits.toFixed(2));
                    $("#partner-earnings").html("&#8369; "+ numberWithCommas(earnings.toFixed(2)) )
                    $("#partner-earnings").data("amount", earnings.toFixed(2));
                    userCredits();
                },
            });
        }
        function userCredits(){
            $.ajax({
                url: "{{ route('get_user_credits') }}",
                type: "GET",
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                success: function(data){
                    var credits = parseFloat(data.credits); 
                    $(".credits_top").find('span').html("&#8369; "+ credits.toFixed(2));
                    $(".mobile-profile-credits").find('span').html("&#8369; "+ credits.toFixed(2));
                },
            });
        }
        @if(!App::environment('prod'))
        $('.open-mobile-sidebar-menu, .mobile-retreat').click(function () {
            $('.mobile-sidebar-overlay').toggle('slide', {
                direction: 'left'
            }, 300);
        });
        @endif
        $('#profile_title').focus(function(){
            $('[data-toggle="tooltip"]').tooltip(); 
        });
        $('#deposits_table').on('click', '.accept-deposit', function(event) {
            event.preventDefault();
            $btn =  $(this); 
            $btn.prop('disabled', true);
            $tr = $(this).closest('tr').hasClass('child') ? $(this).closest('tr').prev() : $(this).closest('tr');
            var row = deposits_table.row($tr).data();

            swal({
                title: `Approve ${row.code} transaction`,
                text: `You are about to approve a <span style='font-weight:bold;color: #820804'>${row.type}</span> transaction ( <span style='font-weight:bold;color: #820804'>${row.code}</span> ).
                    Requested by <span style='font-weight:bold;color: #820804'>${row.user.name}</span> 
                    with the amount of <span style='font-weight:bold;color: #820804'>&#8369;${numberWithCommas(row.amount)}</span>. 
                        Do you want to proceed?
                    `,
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-primary",
                confirmButtonText: "Approve",
                showLoaderOnConfirm: true,
                closeOnConfirm: false,
                html:true
            },
            function(isConfirmed){
                if(isConfirmed){
                    $.ajax({
                        url:'{{route("json_partner_user_deposit")}}',
                        type:'POST',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        data: {
                            id: row['id'],
                            buyer_id: row['user_id'],
                            approved: 'approved',
                        },
                        success:function(data){
                            deposits_table.ajax.reload();
                            if(!data.success){
                                swal({
                                    title: "Transfer Credits Error!",
                                    text: data.message,
                                    type: "error",
                                    html: true,
                                });
                            }
                            else{
                                swal({
                                    title: "Transfer Credits Success!",
                                    text: data.message,
                                    type: "success",
                                    html: true,
                                });
                                updatePartnerCredits();
                            }
                        }
                    });
                }
                else
                {
                    $btn.prop('disabled', false);
                }
            });
        


        });
        $('#deposits_table').on('click', '.decline-deposit', function(event) {
            $tr = $(this).closest('tr').hasClass('child') ? $(this).closest('tr').prev() : $(this).closest('tr');
            var transaction = deposits_table.row($tr).data(), rejection = $('#partner_rejection_modal');
            rejection.find('h5').html('Reject Deposit <span style="color: #820804; font-weight: 700;">'+ transaction.code +'</span>');
            rejection.find('#rejection_type').val($(this).attr('data-tid'));
            rejection.find('.name_span_text').text(transaction.user.name);
            rejection.find('.code_span_text').text(transaction.code);
            rejection.find('#rejection_type').data('type', 'deposit');
            rejection.find('.btn-primary').data('transaction_id', transaction.id);
            rejection.modal('show');
        });
        $('#cashout_table').on('click', '.accept-cashout', function(event) {
            $btn =  $(this); 
            $btn.prop('disabled', true);
            event.preventDefault();
            $tr = $(this).closest('tr').hasClass('child') ? $(this).closest('tr').prev() : $(this).closest('tr');
            var row = cashout_table.row($tr).data();
            swal({
                title: "Are you sure?",
                text: "Transaction <span style='font-weight:bold; color: #820804'>"+row.code+"</span> will be approved",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-primary",
                confirmButtonText: "Approve",
                showLoaderOnConfirm: true,
                closeOnConfirm: false,
                html:true
            },
            function(isConfirmed){
                if(isConfirmed){
                    $.ajax({
                        url:'{{route("json_partner_user_cashout")}}',
                        type:'POST',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        data: {
                            id: row['id'],
                            buyer_id: row['user_id'],
                            approved: 'approved',
                        },
                        success:function(data){
                            cashout_table.ajax.reload();
                            if(!data.success){
                                swal({
                                    title: "Can't Process Cashout!",
                                    text: data.message,
                                    type: "error",
                                    html: true,
                                });
                            }
                            else{
                                swal({
                                    title: "Success!",
                                    text: data.message,
                                    type: "success",
                                    html: true,
                                });
                                updatePartnerCredits();
                            }
                        }
                    });
                }
                else
                {
                    $btn.prop('disabled', false);
                }
            });
        });
        $('#cashout_table').on('click', '.decline-cashout', function(event) {
            $tr = $(this).closest('tr').hasClass('child') ? $(this).closest('tr').prev() : $(this).closest('tr');
            var transaction = cashout_table.row($tr).data(), rejection = $('#partner_rejection_modal');
            rejection.find('h5').html('Reject Cashout <span style="font-size: 20px; color: #820804; font-weight: 700;">'+ transaction.code +'</span>');
            rejection.find('#rejection_type').val($(this).attr('data-tid'));
            rejection.find('.name_span_text').text(transaction.user.name);
            rejection.find('.code_span_text').text(transaction.code);
            rejection.find('#rejection_type').data('type', 'cashout');
            rejection.find('.btn-primary').data('transaction_id', transaction.id).data('buyer_id', transaction.user_id);
            rejection.modal('show');
        });
        $('#reject-save-btn').click(function(event) {
            event.preventDefault();
            $this = $(this);
            

            // console.log('data trans: ',$this.data());
            // return false;
            var id = $("#rejection_type").val();
           // $this.data('trade_type') == 'partner-user' ? ( $('#rejection_type').data('type') == 'deposit' ? '{{route("json_partner_user_deposit")}}' : '{{route("json_partner_user_cashout")}}' ) : '{{ route("declined_transactions") }}';
            switch($("#rejection_type").data('type')){
                case 'deposit':
                    var _url = '{{route("json_partner_user_deposit")}}';
                    break;
                case 'cashout':
                    var _url = '{{route("json_partner_user_cashout")}}';    
                    break
                case 'deposit-partner-sub': 
                case 'cashout-partner-sub': 
                    var _url = '{{ route("declined_transactions") }}';
                    break;
            }
            var form = new FormData();
            form.append('id', id);
            form.append('process_by', {{Auth::user()->id}});
            form.append('approved', 'declined');
            form.append('buyer_id', $this.data('buyer_id'));
            form.append('partner_id', $this.data('buyer_id'));
            form.append('partner_comment', $("#partner_comment").val());
            form.append('trade_type',  $this.data('trade_type'));
            form.append('type', $this.data('type'));
            form.append('status', 2);
            $.ajax({
                url: _url,
                type:'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                data: form,
                cache: false,
                contentType: false,
                processData: false,
                success:function(data){
                    $('#partner_rejection_modal').modal('hide');
                    if(!data.success){
                        swal({
                            title: "Request Denied!",
                            text: data.message,
                            type: "error",
                            html: true,
                        });
                    }
                    else{
                        swal({
                            title: "Rejection Success!",
                            text: data.message,
                            type: "success",
                            html: true,
                        });
                    }
                    switch($("#rejection_type").data('type')){
                        case 'deposit':
                            deposits_table.ajax.reload();
                            break;
                        case 'cashout':
                            cashout_table.ajax.reload(); 
                            break
                        case 'deposit-partner-sub': 
                        case 'cashout-partner-sub': 
                            buy_sell_transactions_table.ajax.reload();
                            break;
                    }
                    
                    
                }
            });
        });

        //verify user by partner

         //verifying user
        $('#deposits_table, #cashout_table').on('click', '.mark-verified-user', function(event) {
            event.preventDefault();
            const table = $(this).closest('table').attr('id') == 'deposits_table' ? deposits_table : cashout_table
            const transaction = table.row($(this).closest('tr')).data();
            const { user } = transaction;
  
            const title =  `Verify User`;
            const swalText = `Mark <strong>${user.name}</strong> as Verified by {{ $partnership->partner_name }}?`;
            swal({
                title:title ,
                text: swalText,
                icon: `<i class="fa fa-envelope-o" aria-hidden="true"></i>`,
                type: "info",
                showCancelButton: true,
                confirmButtonClass: "btn-primary",
                confirmButtonText: "Yes, please proceed",
                showLoaderOnConfirm: true,
                closeOnConfirm: false,
                html:true
            },
            function(confirmed){
                if(confirmed){
                    $.ajax({
                        type: 'POST',
                        url:  "{!! route('user.mark.verified.partner') !!}",
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        dataType: 'json',
                        data: {
                            user_id: user.id,
                            partner_id : {{ $partnership->id }}
                        },
                        success: function(response) {
                            const { success, message } = response;
                            swal({
                                    title: message, 
                                    type: success ? "success" : "error",
                                    html: true
                            }); 
                            table.ajax.reload( null, false );
                        }
                    });   
                }
            });  
            


        });     
        
        //set-user-voucher
         //verifying user
        $('#deposits_table, #cashout_table').on('click', '.set-user-voucher', function(event) {
            event.preventDefault();
            const table = $(this).closest('table').attr('id') == 'deposits_table' ? deposits_table : cashout_table
            const transaction = table.row($(this).closest('tr')).data();
            const { user } = transaction;
  
            const title =  `Set User Voucher Code`;
            const swalText = `
            Set <strong>"TIMS"</strong> Voucher Code for <strong>${user.name}</strong>? <br/>
            `;
            swal({
                title:title ,
                text: swalText,
                icon: `<i class="fa fa-envelope-o" aria-hidden="true"></i>`,
                type: "info",
                showCancelButton: true,
                confirmButtonClass: "btn-primary",
                confirmButtonText: "Yes, please proceed",
                showLoaderOnConfirm: true,
                closeOnConfirm: false,
                html:true
            },
            function(confirmed){
                if(confirmed){
                    $.ajax({
                        type: 'POST',
                        url:  "{!! route('user.set.voucher.tims') !!}",
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        dataType: 'json',
                        data: {
                            user_id: user.id,
                            partner_id : {{ $partnership->id }}
                        },
                        success: function(response) {
                            const { success, message } = response;
                            swal({
                                    title: message, 
                                    type: success ? "success" : "error",
                                    html: true
                            }); 
                            table.ajax.reload( null, false );
                        }
                    });   
                }
            });  
            


        });     
        
        //view partners that verifed
        //verified-partner
        $('#deposits_table, #cashout_table').on('click', '.verified-partner', function(event) {
            event.preventDefault();
            const table = $(this).closest('table').attr('id') == 'deposits_table' ? deposits_table : cashout_table
            const transaction = table.row($(this).closest('tr')).data();
            const { user } = transaction;
            const title =  `${user.name} was verified by following Partner/s:`;

            console.log('title:', title)

            $.ajax({
                type: 'GET',
                url:  "{!! route('get.user.verified.by.partners') !!}",
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                dataType: 'json',
                data: {
                    user_id: user.id
                },
                success: function(response) {
                    const { success, partners } = response;
                    let partnersText = ``;
                    let count = 1;
                    if(partners.length > 0){
                        partners.forEach(partner => {
                            partnersText += `<div class="row">`;
                                partnersText += `<div class="col-sm-12 small text-left">`;
                                    partnersText += `${count}. <strong>${partner.partner.partner_name}</strong> verified on ${partner.created_at}`;
                                    partnersText += `</div>`;
                            partnersText += `</div>`;
                            count++;
                        })

                    }else{
                        partnersText += 'No Partners found.'
                    }

                    swal({
                        title:title ,
                        text: partnersText,
                        icon: `<i class="fa fa-envelope-o" aria-hidden="true"></i>`,
                        type: "info",
                        html:true
                    });  
                    
   
                }
            }); 



        });   

        //end verify user by partner
        $("#partner_request_credits").keydown(function (e) {
            if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
                (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) || 
                (e.keyCode >= 35 && e.keyCode <= 40)) {
                return;
            }
            if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                e.preventDefault();
            }
        });

        $('#buy-partner-credits').click(function(event) {
            const transactionTitle = `Buy Credits from ${!!parentPartner ? parentPartner.partner_name : '2ez.bet'}`;

            $('#sell-credit-modal').modal('show');
            $('.transaction-title').text(transactionTitle);
            $('.transaction_label').text('Pay via:');
            $('#transaction_type').val('deposit');
            $('#transaction_trade_type').val(`${!!parentPartner ? 'partner-sub' : 'partner-admin'}`);
            $('#partner_request_credits').val('10000');
        });

        $('input[name="partner_choice"]').click(function(event) {
            if($(this).val() != 'cash'){
                var acc_number = $(this).val() == 'BPI' ? '{{ $partnership->bpi_account }}' : '{{ $partnership->bdo_account }}';
                var acc_name = $(this).val() == 'BPI' ? '{{ $partnership->bpi_account_name }}' : '{{ $partnership->bdo_account_name }}';
                $('.disabled-fields').prop('disabled', false);
                $('#account_number').val(acc_number);
                $('#account_name').val(acc_name);
                $('#partner-credit-initiate').prop('disabled', false);
            }
            else{
                $('.disabled-fields').prop('disabled', true);
                $('#account_number').val('');
                $('#account_name').val('');
                $('#partner-credit-initiate').prop('disabled', false);
            }
        });

        $('#sell-partner-credits').click(function(event) {
            $('#sell-credit-modal').modal('show');
            $('.transaction-title').text('Sell Credits to 2ez.bet');
            $('.transaction_label').text('Paid via:');
            $('#transaction_type').val('cashout');
            $('#transaction_trade_type').val(`${!!parentPartner ? 'partner-sub' : 'partner-admin'}`);
            $('#partner_request_credits').val($('#partner-ez-credits').data('amount'));
        });

        // $('#update-partner-details').click(function(event) {
        //     $('#update-partner-modal').modal('show');
        // });

        $('#update-public-profile').click(function(event) {
            $('#public-profile-modal').modal('show');
        });

        $('#public-profile-modal').on('hidden.bs.modal', function(event){
            $('#public_profile_form')[0].reset();
            CKEDITOR.instances.section_content_ckedit.setData('');
        });

        $('#partner-credit-initiate').click(function(event){
            var $this = $(this);
            $this.prop('disabled', true);
            $this.button('progress');    
            var form = $('#partner_wallet_update')[0];
            var choice = $('input[name=partner_choice]:checked').val() == 'cash' ? $('input[name=partner_choice]:checked').val()+'-others' : $('input[name=partner_choice]:checked').val()+'-deposit' ;
            var prov_type = $('input[name=partner_choice]:checked').val() == 'cash' ? 'others' : 'bank';
            var mop = choice.split('-');
            $(form).append(
                $('<input>')
                .attr('type', 'hidden')
                .attr('name', 'type')
                .val($('#transaction_type').val())
            );
            $(form).append(
                $('<input>')
                .attr('type', 'hidden')
                .attr('name', 'trade_type')
                .val($('#transaction_trade_type').val())
            );
            $(form).append(
                $('<input>')
                .attr('type', 'hidden')
                .attr('name', 'main_partner_id')
                .val(!!parentPartner ? parentPartner.id : 0)
            );
            $(form).append(
                $('<input>')
                .attr('type', 'hidden')
                .attr('name', 'provider')
                .val(choice)
            );
            $(form).append(
                $('<input>')
                .attr('type', 'hidden')
                .attr('name', 'provider_type')
                .val(prov_type)
            );
            $.ajax({
                url: !!parentPartner == false ? "{{ route('deposit_to_admin') }}" : "{{ route('deposit_to_parent') }}",
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                type: 'POST',
                data: $(form).serialize(),
                success: function(data) {
                    if(data.errors){
                        $.each( data.errors, function( key, value ) {
                            if (key == 'amount') {
                                $('#partner_request_credits').siblings('.message').text(value[0]).css('display', 'block');
                            }
                            else{
                                $('#account_number').parent().parent().find('.error-label').text('Please fill up for your account details.').css('display', 'block');
                            }
                        });
                        $this.prop('disabled', false);
                        $this.button('reset'); 
                    }else{
                        if (!data.success) {
                            swal({
                                title: "Oops!",
                                text: data.message,
                                type: "error",
                                html: true,
                            });
                            $this.prop('disabled', false);
                            $this.button('reset'); 
                        }
                        else{
                            if(data.transaction.type == 'deposit'){
                                partnerStepsManual(mop,data.transaction)
                                $('#partner-code').text(data.transaction.code).data('code', data.transaction.code);
                                $('#partner-credit-initiate, .credit_transactions').hide();
                                $('#complete_buy_credits').show();
                                $('#complete-deposit-partner').show().data('transaction_id', data.transaction.id).data('type', data.transaction.type);
                            }else{
                                swal({
                                    title: "Sell Credits Request Sent",
                                    text: "Your request is currently in process. Please wait for admins' response or contact them regarding on this transaction ",
                                    type: "success",
                                    html: true,
                                });
                                updatePartnerCredits();
                                $('#sell-credit-modal').modal('hide');
                            }
                            transactions_table.ajax.reload();
                            $this.prop('disabled', false);
                            $this.button('reset'); 
                        }
                    }
                },
                fail: function(xhr, status, error) {
                    
                }
            });
        });

        $('#partner-profile').click(function(event){
            var $this = $(this);
            $this.prop('disabled', true);
            $this.button('progress');    

            $.ajax({
                url: "{{ route('public_profile') }}",
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                type: 'POST',
                data: {
                    title: $('#profile_title').val(),
                    content: CKEDITOR.instances.section_content_ckedit.getData(),
                },
                success: function(data) {
                    $('#public-profile-modal').modal('hide');
                    swal({
                        title: "Public Profile Saved",
                        text: data.message,
                        type: "success",
                        html: true,
                    });
                    $this.prop('disabled', false);
                    $this.button('reset'); 
                }
            });
        });

        $('#deposits_table').on('click', '.view-receipt', function(event) {
            event.preventDefault();
            $tr = $(this).closest('tr').hasClass('child') ? $(this).closest('tr').prev() : $(this).closest('tr');
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
            $tr = $(this).closest('tr').hasClass('child') ? $(this).closest('tr').prev() : $(this).closest('tr');
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

        $('#deposits_table').on('click', '.view-details', function(event) {
            event.preventDefault();
            $tr = $(this).closest('tr').hasClass('child') ? $(this).closest('tr').prev() : $(this).closest('tr');
            var transaction = deposits_table.row($tr).data();
            var hex = transaction.data.replace(/&quot;/g, '"');
            hex = JSON.parse(hex);
            $('#view-details').find('.modal-body').find('#deposit-steps').empty();
            renderStepsManual(hex.mop.split('-'),transaction)
            $('#view-details').modal('show');
        });

        $('#cashout_table').on('click', '.view-details', function(event) {
            event.preventDefault();
            $tr = $(this).closest('tr').hasClass('child') ? $(this).closest('tr').prev() : $(this).closest('tr');
            var transaction = cashout_table.row($tr).data();
            var hex = transaction.data.replace(/&quot;/g, '"');
            hex = JSON.parse(hex);
            // console.log('cashout_table transaction: ',hex, JSON.parse(hex))
            $('#view-details').find('.modal-body').find('#deposit-steps').empty();
            renderStepsManual(hex.mop.split('-'),transaction)
            $('#view-details').modal('show');
        });

        $('#transactions_table').on('click', '.view-receipt', function(event) {
            event.preventDefault();
            $tr = $(this).closest('tr').hasClass('child') ? $(this).closest('tr').prev() : $(this).closest('tr');
            var row = transactions_table.row($tr).data();
            var url = '{{asset('transaction/images/')}}';
            if (row['picture']) {
                $('#viewReceipt').find('h5').html('Receipt')
                $('#viewReceipt').find('.modal-body').html('<image class="form-control" style="height:100%" src="'+(url+row['picture']).replace('/uploads','')+'"/>')
                $('#viewReceipt').modal('show');
            }else{
                swal("No Receipt!", 'No receipt has been uploaded', "warning");

            }
        });

        $('#complete-deposit-partner').click(function(event) {
            event.preventDefault();
            var code = $('#code').text(), id = $(this).data('transaction_id'), type = $(this).data('type');
            var title = $('.file-preview-image').attr('src') == null ? 'You have not uploaded your receipt yet' : 'Your deposit request '+code+' is currently being processed.'
            var message = $('.file-preview-image').attr('src') == null ? 'You may monitor your buy credits transactions in your partner dashboard\'s transactions history' : 'It usually takes a few hours but sometimes can take a full day. Please contact our admins if your request has not been processed after 24 hours.'
            swal({
                title: title,
                text: message,
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Yes, please continue!",
                showLoaderOnConfirm: true,
                closeOnConfirm: true
            },
            function(){
                if($('.file-preview-image').attr('src') != null){
                    $.ajax({
                        url: '{{ route("upload_partner") }}',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        type: 'POST',
                        data: { photo:$('.file-preview-image').attr('src'), id: id, status: 0, type: type },
                        success: function(data) {
                            if (data.success) {
                                transactions_table.ajax.reload();
                            }
                        },
                        fail: function(xhr, status, error) {
                            console.log('error');
                        }
                    });
                }
                $('#sell-credit-modal').modal('hide');
                transactions_table.ajax.reload();
            });
        });

        $('#transactions_table').on('click', '.view-details', function(event) {
            event.preventDefault();
            $tr = $(this).closest('tr').hasClass('child') ? $(this).closest('tr').prev() : $(this).closest('tr');
            var transaction = transactions_table.row($tr).data();
            var data = JSON.parse(transaction.data)
            $('#view-details').find('.modal-body').find('#deposit-steps').empty();
            renderStepsManual(data.mop.split('-'),transaction)
            $('#view-details').modal('show');
        });


        $('#transactions_table').on('click', '.approve', function(event) {
            event.preventDefault();
            $tr = $(this).closest('tr').hasClass('child') ? $(this).closest('tr').prev() : $(this).closest('tr');
            var row = transactions_table.row($tr).data();
            const requestData = { 
                id: row.id, 
                status: 1, 
                type: row.type, 
                partner_id: 
                row.partner_id
            };

            swal({
                title: `Approve ${row.code} transaction`,
                text: `You are about to approve a <span style='font-weight:bold;color: #820804'>${row.type}</span> transaction ( <span style='font-weight:bold;color: #820804'>${row.code}</span> ).
                        Requested by <span style='font-weight:bold;color: #820804'>${row.partner_name}</span> 
                        with the amount of <span style='font-weight:bold;color: #820804'>&#8369;${numberWithCommas(row.amount)}</span>. 
                            Do you want to proceed?
                        `,
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Yes, please proceed.",
                showLoaderOnConfirm: true,
                closeOnConfirm: false,
                html:true
            },
            function(){
                $.ajax({
                    url: '{{ route("partner_transactions") }}',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    type: 'POST',
                    data: requestData,
                    success: function(data) {
                        if (data.success) {
                            swal("Approved!", data.message, "success");
                            transactions_table.ajax.reload( null, false );
                        }else{
                            swal({
                                title: "Oops!",
                                text: data.message,
                                type: "error",
                                html:true
                            });
                        }
                    },
                    fail: function(xhr, status, error) {
                        console.log(error);
                    }
                });
            });
        });


        $('#transactions_table').on('click', '.btn-reject', function(event) {
            event.preventDefault();
            $tr = $(this).closest('tr').hasClass('child') ? $(this).closest('tr').prev() : $(this).closest('tr');
            var transaction = transactions_table.row($tr).data(), rejection = $('#partner_rejection_modal');
            const partnerNameText = `${transaction.partner_name} <small>(${transaction.user.name})</small>`;
            $('#partner_comment').val('');
            rejection.find('h5').html('Reject <span style="color: #820804; font-weight: 700;">'+ transaction.code +'</span>');
            rejection.find('.name_span_text').html(partnerNameText);
            rejection.find('.code_span_text').text(transaction.code);
            rejection.find('#rejection_type').val(transaction.id);
            rejection.find('#rejection_type').data('type', `${transaction.type}-${transaction.trade_type}`);
            rejection.find('.btn-primary').data('transaction_id', transaction.id).data('buyer_id', transaction.user_id).data('trade_type', transaction.trade_type).data('type', transaction.type);
            rejection.modal('show');
        });

        $('#buy_sell_transactions_table').on('click', '.view-details', function(event) {
            event.preventDefault();
            $tr = $(this).closest('tr').hasClass('child') ? $(this).closest('tr').prev() : $(this).closest('tr');
            var transaction = buy_sell_transactions_table.row($tr).data();
            var data = JSON.parse(transaction.data)
            $('#view-details').find('.modal-body').find('#deposit-steps').empty();
            renderStepsManual(data.mop.split('-'),transaction)
            $('#view-details').modal('show');
        });


        $('#buy_sell_transactions_table').on('click', '.approve', function(event) {
            event.preventDefault();
            $tr = $(this).closest('tr').hasClass('child') ? $(this).closest('tr').prev() : $(this).closest('tr');
            var row = buy_sell_transactions_table.row($tr).data();
            const requestData = { 
                id: row.id, 
                status: 1, 
                type: row.type, 
                partner_id: 
                row.partner_id
            };

            swal({
                title: `Approve ${row.code} transaction`,
                text: `You are about to approve a <span style='font-weight:bold;color: #820804'>${row.type}</span> transaction ( <span style='font-weight:bold;color: #820804'>${row.code}</span> ).
                        Requested by <span style='font-weight:bold;color: #820804'>${row.partner_name}</span> 
                        with the amount of <span style='font-weight:bold;color: #820804'>&#8369;${numberWithCommas(row.amount)}</span>. 
                            Do you want to proceed?
                        `,
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Yes, please proceed.",
                showLoaderOnConfirm: true,
                closeOnConfirm: false,
                html:true
            },
            function(){
                $.ajax({
                    url: '{{ route("partner_transactions") }}',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    type: 'POST',
                    data: requestData,
                    success: function(data) {
                        if (data.success) {
                            swal("Approved!", data.message, "success");
                            buy_sell_transactions_table.ajax.reload( null, false );
                        }else{
                            swal({
                                title: "Oops!",
                                text: data.message,
                                type: "error",
                                html:true
                            });
                        }
                    },
                    fail: function(xhr, status, error) {
                        console.log(error);
                    }
                });
            });
        });

        $('#buy_sell_transactions_table').on('click', '.btn-reject', function(event) {
            event.preventDefault();
            $tr = $(this).closest('tr').hasClass('child') ? $(this).closest('tr').prev() : $(this).closest('tr');
            var transaction = buy_sell_transactions_table.row($tr).data(), rejection = $('#partner_rejection_modal');
            const partnerNameText = `${transaction.partner_name} <small>(${transaction.partner_transactions.name})</small>`;
            $('#partner_comment').val('');
            rejection.find('h5').html('Reject <span style="color: #820804; font-weight: 700;">'+ transaction.code +'</span>');
            rejection.find('.name_span_text').html(partnerNameText);
            rejection.find('.code_span_text').text(transaction.code);
            rejection.find('#rejection_type').val(transaction.id);
            rejection.find('#rejection_type').data('type', `${transaction.type}-${transaction.trade_type}`);
            rejection.find('.btn-primary').data('transaction_id', transaction.id).data('buyer_id', transaction.user_id).data('trade_type', transaction.trade_type).data('type', transaction.type);
            rejection.modal('show');
        });

        function renderStepsManual(mop,transaction)
        {
            switch (mop[1]) {
                case 'deposit':
                    var data = {}, bank=mop[0], bopen, bclose, mopen, mclose, accountnumber, accountname;
                    var container = $("#manual-steps-template").html();
                    switch (mop[0]) {
                        case 'BDO':
                            bopen = '9AM'
                            bclose = '3PM'
                            mopen = '10AM'
                            mclose = '7PM'
                            accountnumber = '007680183551'
                            accountname = 'Neil Mark A. Basinillo'
                            break;
                        case 'BPI':
                            bopen = '9AM'
                            bclose = '3PM'
                            mopen = '10AM'
                            mclose = '6PM'
                            accountnumber = '1019165937'
                            break;
                        case 'Metrobank':
                            bopen = '9AM'
                            bclose = '3PM'
                            mopen = '10AM'
                            mclose = '6PM'
                            accountnumber = '486-3-48623798-9'
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
                    data.partner = transaction.partner_name;
                    // data.address = transaction.partner.address + ', ' + transaction.partner.province.province;
                    // data.schedule = transaction.operation_time;
                    $('#view-details').find('.modal-body').find('#deposit-steps').append(Mustache.render(container, data));
                    break;
                case 'others':
                    var container = $("#"+mop[0]+"-others").html();
                    var data = {}
                    $('#view-details').find('.modal-body').find('#deposit-steps').append(Mustache.render(container, data));
                    break;
            }

        }

        function partnerStepsManual(mop,transaction)
        {
            $('#partner-deposit-steps').html('');
            switch (mop[1]) {
                case 'deposit':
                    var data = {}, bank=mop[0], bopen, bclose, mopen, mclose, accountnumber, accountname;
                    var container = $("#manual-steps-template").html();
                    switch (mop[0]) {
                        case 'BDO':
                            bopen = '9AM'
                            bclose = '3PM'
                            mopen = '10AM'
                            mclose = '7PM'
                            accountnumber = '007680183551'
                            accountname = 'Neil Mark A. Basinillo'
                            break;
                        case 'BPI':
                            bopen = '9AM'
                            bclose = '3PM'
                            mopen = '10AM'
                            mclose = '6PM'
                            accountnumber = '1019165937'
                            break;
                        case 'Metrobank':
                            bopen = '9AM'
                            bclose = '3PM'
                            mopen = '10AM'
                            mclose = '6PM'
                            accountnumber = '486-3-48623798-9'
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
                    $('#sell-credit-modal').find('.modal-body').find('#complete_buy_credits').find('#partner-deposit-steps').append(Mustache.render(container, data));
                    break;
                case 'others':
                    var container = $("#"+mop[0]+"-others").html();
                    var data = {}
                    $('#sell-credit-modal').find('.modal-body').find('#complete_buy_credits').find('#partner-deposit-steps').append(Mustache.render(container, data));
                    break;
            }
        }
        
        var url = document.location.toString();
        if (url.match("#")) {
            $('.nav-tabs a[href="#' + url.split('#')[1] + '"]').tab('show');
            startupDatatable(url.split('#')[1])
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
            window.location.hash = e.target.hash;
        })

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

    function progress_bar_animate() {
        var elem = $('.kv-upload-progress').find('.progress-bar');   
        elem.css('width', '0%');
        var width = 0;
        var id = setInterval(frame, 25);
        function frame() {
            if (width >= 100) {
                clearInterval(id);
                elem.text('Done');
                elem.removeClass('progress-bar-warning').addClass('progress-bar-success');
                $('.file-upload-indicator').css({
                    backgroundColor: '#dff0d8',
                    borderColor: '#d6e9c6'
                });
                $('.file-upload-indicator').find('i').removeClass('fa-hand-o-down').removeClass('text-warning').addClass('fa-check-circle').addClass('text-success'); 
            } 
            else {
                width++; 
                elem.css('width', width + '%'); 
                elem.text( width + '%');
                elem.removeClass('progress-bar-success').addClass('progress-bar-warning');
                $('.file-upload-indicator').css({
                    backgroundColor: '#fcf8e3',
                    borderColor: '#faebcc'
                });
                $('.file-upload-indicator').find('i').removeClass('fa-check-circle').removeClass('text-success').addClass('fa-hand-o-down').addClass('text-warning');
            }
        }
    }

    function startupDatatable(target){
        switch(target) {
            case "deposits":
                deposits_table.ajax.reload();
                break;
            case "cashout":
                cashout_table.ajax.reload();
                break;
            case "agent_transactions":
                agent_transactions_table.ajax.reload();
                break;
            case "transactions":
                transactions_table.ajax.reload();
                break;
            case "buy_sell_transactions": 
                buy_sell_transactions_table.ajax.reload();
                break;
            case "payout":
                earnings_table.ajax.reload();
                break;
            default:
                deposits_table.ajax.reload();
                break;

        }
    }

    $('#sell-credit-modal, #complete-step-modal, #update-partner-modal').on('hidden.bs.modal', function () {
        $('#partner_wallet_update')[0].reset();
        $('fileinput-remove').trigger('click');
        $('.kv-upload-progress').addClass('hide');
        $('.error-label').css('display', 'none');
        $('.disabled-fields').prop('disabled', true);
        $('.message').css('display', 'none');
        $('#partner-credit-initiate').prop('disabled', false);
        $('#complete-deposit-partner, #complete_buy_credits').hide();
        $('#partner-credit-initiate, .credit_transactions').show();
    });

    $(".partner_receipt_input").fileinput({
        previewFileType: "image",
        theme: "fa",
        showUpload: false,
        showCaption: false,
        showRemove: false,
        browseClass: "btn btn-primary btn-sm",
        browseLabel: "Pick Image",
        browseIcon: "<i class=\"fa fa-picture-o\"></i> ",
        allowedFileExtensions: ["jpg", "gif", "png", "jpeg"],
    }).on("filebatchselected", function(event, files) {
        // $('.kv-upload-progress').css('display', 'none');
        $('.kv-upload-progress').removeClass('hide').css('display', 'block');
        progress_bar_animate();
    });

    $('.nav-tabs a').on('shown.bs.tab', function (e) {
        switch(e.currentTarget.hash) {
            case '#deposits':
                deposits_table.ajax.reload();
                break;
            case '#cashout':
                cashout_table.ajax.reload();
                break;
            case '#agent_transactions':
                agent_transactions_table.ajax.reload();
                break;                
            case '#transactions':
                transactions_table.ajax.reload();
                break;
            case '#payout':
                earnings_table.ajax.reload();
                break;
            case '#buy_sell_transactions': 
                buy_sell_transactions_table.ajax.reload();
                break;
        }
    });

    $('.partner-downloads').click(function(event){
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

    $('#proceed-download-btn').click(function(event){
        event.preventDefault();
        // const downloadData = {
        //     fromDate: '',
        //     toDate: '',
        //     status: '',
        // };
        const that = $(this);
        that.prop('disabled',true);
        const downloadData =new FormData($('#partner-download-transactions-form')[0]);
        $.ajax({
                url: "{{ route('partner.download.transactions') }}",
                type:'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                data: downloadData,
                cache: false,
                contentType: false,
                processData: false,
                success:function(data){

                    window.location = `{{ route('partner.download.transactions.file') }}/?file=${data.file}`
                    that.prop('disabled',false);
                }
            });
    });

    $(document).on('click', '.attach-receipt', function(event){
        event.preventDefault();
        //clear image before proceeding
        $('#attach-receipt-modal form')[0].reset();

        const data = $(this).data();
        const modalTitle = `Attach Receipt for Transaction: <strong>${data.code}</strong>`;

        $('#attach-receipt-modal #transaction_id').val(data.id);
        $('#attach-receipt-modal #refresh_table').val(data.table);
        $('#attach-receipt-modal .transaction-title').html(modalTitle);
        $('#attach-receipt-modal').modal('show');
    });

    $('#attach-receipt-modal #submit-attached-receipt').click(function(event){
        event.preventDefault();
        const receiptImageSrc = $('#attach-receipt-modal .file-preview-image').attr('src');
        const id = $('#attach-receipt-modal #transaction_id').val();
        const refreshTable = $('#attach-receipt-modal #refresh_table').val();

        if( receiptImageSrc != null){
            $.ajax({
                url: '{{ route("upload_partner") }}',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                type: 'POST',
                data: {photo: receiptImageSrc, id: id },
                success: function(data) {
                    if (data.success) {
                        $('#attach-receipt-modal').modal('hide');
                        swal({
                            title: "Upload Success.",
                            text: "Successfully uploaded receipt.",
                            type: "success",
                            html: true,
                        });

                        switch(refreshTable){
                            case 'transactions': 
                                transactions_table.ajax.reload();
                                break; 
                            case 'deposits': 
                                deposits_table.ajax.reload();
                                break;
                            case 'cashouts': 
                                cashout_table.ajax.reload();
                                break;
                        }
                        
                    }
                },
                fail: function(xhr, status, error) {
                    console.log('error');
                }
            });

        }else{
            swal({
                title: "Upload error.",
                text: "Please attach your receipt before pressing submit button.",
                type: "error",
                html: true,
            });
        }     
    })

    
</script>
@endsection
