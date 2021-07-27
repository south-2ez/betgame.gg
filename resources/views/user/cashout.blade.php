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
    
    .matchleft a {
        color: #333;
        text-decoration: none;
        cursor: pointer;
    }
    
    .format {
        font-size: 0.7em;
        font-weight: bold;
    }
    .select2-container{
        vertical-align: inherit !important;
    }
    .select2-selection--single{
        min-height: 33px;
    }

    .has-error .select2-selection {
        border: 1px solid #a94442!important;
        border-radius: 4px!important;
    }

    .error-label {
        color: #a94442;
        display: none;
        padding: 3px;
    }
    
    .has-error .error-label {
        display: block;
    }
    .file-preview{
        border: 1px solid #a7a6a6 !important;
    }
    #transaction_table {
        font-size: 90%;
    }
    #transaction_table td {
        border: 1px solid #000;
        padding: 1px;
    }
    .nav-tabs {
        position: initial!important; 
    }
    .nav > li > a {
        padding: 10px 15px!important;
    }
    .nav-tabs { border-bottom: 2px solid #DDD; }
    .nav-tabs > li.active > a, .nav-tabs > li.active > a:focus, .nav-tabs > li.active > a:hover { border-width: 0; }
    .nav-tabs > li > a { border: none; color: #666; }
    .nav-tabs > li.active > a, .nav-tabs > li > a:hover { border: none; color: #414141 !important; background: transparent; }
    .nav-tabs > li > a::after { content: ""; background: #414141; height: 2px; position: absolute; width: 100%; left: 0px; bottom: -1px; transition: all 250ms ease 0s; transform: scale(0); }
    .nav-tabs > li.active > a::after, .nav-tabs > li:hover > a::after { transform: scale(1); }
    .partner_content { background-color: #DEDEDE; padding: 20px; }
    .table_content_hide { display: none; }
</style>
<link rel="stylesheet" href="{{ asset("css/select2/select2.min.css") }}">
<link rel="stylesheet" href="{{ asset("css/fileinput.css") }}">
<link rel="stylesheet" href="{{ asset('bower_components/bootstrap-sweetalert/dist/sweetalert.css') }}">
@endsection

@section('content')
<div class="main-container dark-grey">
    <div class="m-container3">
        <div class="main-ct temp_div">
            <div class="title2">TRANSFER</div>
            <div class="clearfix"></div>
            <div class="blk-1">
                <div class="col-md-12">

                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#direct-cashout" id="direct_transaction_tab" aria-controls="home" role="tab" data-toggle="tab">Direct Transfer</a></li>
                        <li role="presentation"><a href="#partner-cashout" id="partner_transaction_tab" aria-controls="cashout" role="tab" data-toggle="tab">Transfer via Partner</a></li>
                    </ul>
                    <br>                
                    <div class="col-md-12">
                        <div class="alert alert-danger" role="alert">
                            <strong>Important!</strong> Please always double check your cachout request.
                        </div>
                    </div>
                    
                    <div class="tab-content">
                        <div class="tab-pane fade in active" id="direct-cashout" role="tabpanel">
                            <div class="tab-content" style="margin-top: 20px">
                                <form id="temp_form" autocomplete="off" action="" method="POST" role="form">

                                    <div class="col-md-6 form-group">   
                                        <label for="amount">Ez credits</label>
                                        <input type="text" class="form-control" id="amount" name="amount" placeholder="Ez credits" value="{{number_format(Auth::user()->credits,2 ,'.', '')}}">
                                        <label class="error-label">Invalid Input</label>
                                    </div>

                                    <div class="col-md-6 form-group">
                                        <label for="mop">Mode of Payment</label>
                                        <select class="form-control mop" name="provider" id="provider" style="width: 100%" data-placeholder="Select mode of payment">
                                            <option></option>
                                            {{-- <optgroup label="3rd Party Payment">
                                                <option value="CoinsPH-payment" disabled>Coins.PH</option>
                                            </optgroup>
                                            <optgroup label="Bank Deposit">
                                                <option value="BDO-deposit">BDO</option>
                                                <option value="BPI-deposit">BPI</option>
                                                <option value="Securitybank-deposit">Security Bank Savings</option> 

                                                {{-- <option value="Metrobank-deposit">Metrobank</option>
                                                <option value="Chinabank-deposit" disabled>Chinabank - coming soon</option>
                                                <option value="Eastwest-deposit" disabled>Eastwest</option>
                                                <option value="PNB-deposit" disabled>PNB - coming soon</option>
                                                <option value="PSBank-deposit" disabled>PSBank - coming soon</option>
                                                <option value="rcbc-deposit" disabled>RCBC Savings Bank - coming soon</option>
                                                <option value="Unionbank-deposit" disabled>Unionbank - coming soon</option> 

                                            </optgroup>
                                            <optgroup label="Money Remittance">
                                                <option value="cebuana-remittance">Cebuana Lhuiller Pera Padala</option>
                                                <option value="mlhuiller-remittance">M Lhuillier Kwarta Padala</option>
                                                <option value="lbc-remittance">LBC Peso Padala</option>
                                                <option value="palawan-remittance">Palawan Express</option>
                                                <option value="western-remittance">Western Union</option>
                                                <option value="rd-remittance" disabled>RD Pawnshop - coming soon</option>
                                            </optgroup> --}}
                                            <optgroup label="Others">
                                                <option value="others-deposit">Others</option>
                                            </optgroup>
                    {{--                         <optgroup label="Online Bank Transfer">
                                                <option value="BDO-online">BDO</option>
                                                <option value="BPI-online">BPI</option>
                                                <option value="Chinabank-online" disabled>Chinabank - coming soon</option>
                                                <option value="Eastwest-online" disabled>Eastwest - coming soon</option>
                                                <option value="Metrobank-online">Metrobank</option>
                                                <option value="PNB-online" disabled>PNB - coming soon</option>
                                                <option value="PSBank-online" disabled>PSBank - coming soon</option>
                                                <option value="securitybank-online" disabled>Security Bank Savings - coming soon</option>
                                                <option value="Unionbank-online" disabled>Unionbank - coming soon</option>
                                            </optgroup> --}}
                                        </select>
                                        <label class="error-label">Invalid Input</label>
                                    </div>

                                    <div id="field-required"></div>

                                    <div class="col-md-12 form-group">
                                        <button type="submit" class="btn btn-primary temp_submit" style="float: right;" data-progress-text="Saving ... <i class='fa fa-circle-o-notch fa-spin fa-fw'></i>" autocomplete="off">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="tab-pane fade in" id="partner-cashout" role="tabpanel">
                            <div class="tab-content" style="margin-top: 20px">
                                <div class="tab-content" style="margin-top: 20px">
                                <form id="partner_form" autocomplete="off" action="" method="POST" role="form">

                                    <div class="col-md-6 form-group">   
                                        <label for="amount">Ez credits</label>
                                        <input type="text" class="form-control" id="amount_partner" name="amount_partner" placeholder="Amount" value="{{number_format(Auth::user()->credits,2 ,'.', '')}}">
                                        <label class="error-label">Invalid Input</label>
                                    </div>

                                    <div class="col-md-6 form-group">
                                        <label for="partners">Select Partner</label>
                                        <select class="form-control partners" name="partner" id="partner-select" style="width: 100%" data-placeholder="Select partner">
                                            <option selected disabled></option>
                                        </select>
                                        <label class="error-label">Invalid Input</label>
                                    </div>

                                    <div class="col-md-6 form-group coinsph" style="display: none">
                                        <label>For Coins.Ph Payments</label>
                                        <input type="text" class="form-control" name="coinsph_user" placeholder="Enter your Email or Mobile number">
                                        <label class="error-label">Invalid Input</label>
                                    </div>
                                    <div id="partner-field-required"></div>
                                    <div class="col-md-12 form-group">
                                        <button type="button" class="btn btn-primary partner_submit" style="float: right;" data-progress-text="Saving ... <i class='fa fa-circle-o-notch fa-spin fa-fw'></i>" autocomplete="off">Submit</button>
                                    </div>
                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="main-ct success_div" style="display: none;">
            <div class="title2">CASH OUT</div>
            <div class="clearfix"></div>
            <div class="blk-1">
                <div class="col-md-12">
                    
                    <div class="col-md-12">
                        <div class="alert alert-success" role="alert">
                          <strong>Saved!</strong> Request was successfull. <b style="font-size: 17px;">CO Code: </b><span style="font-size: 17px;"></span>
                        </div>
                    </div>

                    <div class="col-md-12 form-group"> 
                        <h3>Details</h3>
                        <dl class="row" id="details" style="font-size: 16px;"></dl>
                    </div>

                    <div class="col-md-12">
                        <div class="alert alert-danger" role="alert">
                          <strong>Important!</strong> Processing may take 48 hours on peak/busy days.
                          <br/>
                          <strong>Note: You may follow up on your transactions by messaging the <a href="https://www.facebook.com/2ez.bet/">2ez.bet</a> Facebook Page.</strong>
                        </div>
                    </div>

                    <div class="col-md-12 form-group"> 
                        <a href="{{url('/profile#cashout')}}"  type="submit" class="btn btn-default goto_history"> Go to transaction history <i class="fa fa-arrow-circle-o-right" aria-hidden="true"></i></a>
                    </div>
                </div>
            </div>
        </div>
        {{-- <div class="main-ct success_div" style="display: block; font-family:  -apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif;">
            <div class="title2">FOLLOW THIS GUIDE</div>
            <div class="clearfix"></div>
            <div class="blk-1">
                <div class="col-md-12">
                <div class="embed-responsive embed-responsive-4by3">
                  <iframe class="embed-responsive-item" src="//www.youtube.com/watch?v=tW_isbLnSbs&feature=youtu.be"></iframe>
                </div>
                </div>
            </div>
        </div> --}}
    </div>
    
    {{-- <div class="m-container3 transaction_table_container" style="background: none; box-shadow: none;">
        <div class="main-ct">
            <div class="title2">PURCHASE VIA OUR PARTNERS</div>
            <div class="clearfix"></div>
            <div class="blk-1 table-responsive" style="padding-top: 0px; ">
                <table class="table table-striped" style="width: 100%;">
                    <thead>
                        <tr>
                            <th class="text-left">Partner</th>
                            <th class="text-center">BDO</th>
                            <th class="text-center">BPI</th>
                            <th class="text-center">Security Bank</th>
                            <th class="text-center">Union Bank</th>
                            <th class="text-center">Metro Bank</th>
                            <th class="text-center">PNB</th>
                            <th class="text-center">Gcash</th>
                            <th class="text-center">PayMaya</th>
                            <th class="text-center">Coins.ph</th>
                            <th class="text-center">Smart Padala</th>
                            <th class="text-center">Palawan</th>
                            <th class="text-center">ML</th>
                            <th class="text-center">Cebuana</th>
                            <th class="text-center">RD</th>
                            <th class="text-center">&nbsp;</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($partners as $partner)
                            <tr>
                                <td>
                                    <a href="{{ route('cashout.page', ['via' => 'partner', 'partner_id' => $partner->id]) }}">{{ $partner->partner_name}}</a>
                                </td>
                                <td class="text-center"><i class="fa fa-check-circle text-success" aria-hidden="true"></i></td>
                                <td class="text-center"><i class="fa fa-check-circle text-success" aria-hidden="true"></i></td>
                                <td class="text-center"><i class="fa fa-check-circle text-success" aria-hidden="true"></i></td>
                                <td class="text-center"><i class="fa fa-check-circle text-success" aria-hidden="true"></i></td>
                                <td class="text-center"><i class="fa fa-check-circle text-success" aria-hidden="true"></i></td>
                                <td class="text-center"><i class="fa fa-check-circle text-success" aria-hidden="true"></i></td>
                                <td class="text-center"><i class="fa fa-check-circle text-success" aria-hidden="true"></i></td>
                                <td class="text-center"><i class="fa fa-check-circle text-success" aria-hidden="true"></i></td>
                                <td class="text-center"><i class="fa fa-check-circle text-success" aria-hidden="true"></i></td>
                                <td class="text-center"><i class="fa fa-check-circle text-success" aria-hidden="true"></i></td>
                                <td class="text-center"><i class="fa fa-check-circle text-success" aria-hidden="true"></i></td>
                                <td class="text-center"><i class="fa fa-check-circle text-success" aria-hidden="true"></i></td>
                                <td class="text-center"><i class="fa fa-check-circle text-success" aria-hidden="true"></i></td>
                                <td class="text-center"><i class="fa fa-check-circle text-success" aria-hidden="true"></i></td>
                                <td class="text-center"><a  href="{{ route('cashout.page', ['via' => 'partner', 'partner_id' => $partner->id]) }}" class="btn btn-primary btn-xs">Transfer</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div> --}}

    <div class="m-container3 transaction_table_container" style="background: none; box-shadow: none;">
        <div class="main-ct">
            <div class="blk-1">
                @if(1 == 2)
                <div class="col-md-12 direct_deposit_table">
                    <table id="transaction_table" class="table table-responsive table-condensed table-bordered" width="80%">
                        <body>
                        <tr>
                            <td>Method of purchase/transfer</td>
                            <td>Purchase Approval time(office hours)</td>
                            <td>Transfer Approval time(office hours)</td>
                            <td>Non-Office hours/Holidays/No admin online</td>
                        </tr>
                        {{-- <tr style="background: #ffd966">
                            <td>Office Walk-in</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr style="background: #fff2cc">
                            <td>Walk-in to 2ez.bet office</td>
                            <td>Few minutes</td>
                            <td>Few minutes</td>
                            <td>Not available</td>
                        </tr>
                        <tr style="background: #fff2cc">
                            <td>Walk-in to partner's location</td>
                            <td>Few minutes</td>
                            <td>Few minutes</td>
                            <td>Will vary *</td>
                        </tr> --}}
                        {{-- <tr style="background: #9bc2e6">
                            <td>3rd Party Processors</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr style="background: #ddebf7">
                            <td>Coins.ph</td>
                            <td>Few minutes</td>
                            <td>Few minutes</td>
                            <td>Not available</td>
                        </tr> --}}
                        {{-- <tr style="background: #f4b084">
                            <td>Bank Options</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr style="background: #fce4d6">
                            <td>BDO</td>
                            <td>Few minutes to an hour</td>
                            <td>Few minutes to an hour</td>
                            <td>Not available</td>
                        </tr>
                        <tr style="background: #fce4d6">
                            <td>BPI</td>
                            <td>Few minutes to an hour</td>
                            <td>Few minutes to an hour</td>
                            <td>Not available</td>
                        </tr>
                        <tr style="background: #fce4d6">
                            <td>Security Bank</td>
                            <td>Few minutes to an hour</td>
                            <td>Few minutes to an hour</td>
                            <td>Not available</td>
                        </tr>                         --}}
                        {{-- <tr style="background: #fce4d6">
                            <td>MetroBank</td>
                            <td>Few minutes to an hour</td>
                            <td>Few minutes to an hour</td>
                            <td>Not available</td>
                        </tr> --}}
                        {{-- <tr style="background: #c6e0b4">
                            <td>Remittance Centers</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr style="background: #e2efda">
                            <td>Palawan</td>
                            <td>Approval at 5pm daily, 3pm cut off**</td>
                            <td>Processed at 5pm daily, 3pm cut off**</td>
                            <td>Not available</td>
                        </tr>
                        <tr style="background: #e2efda">
                            <td>Cebuana</td>
                            <td>Approval at 5pm daily, 3pm cut off**</td>
                            <td>Processed at 5pm daily, 3pm cut off**</td>
                            <td>Not available</td>
                        </tr>
                        <tr style="background: #e2efda">
                            <td>ML padala</td>
                            <td>Approval at 5pm daily, 3pm cut off**</td>
                            <td>Processed at 5pm daily, 3pm cut off**</td>
                            <td>Not available</td>
                        </tr>
                        <tr style="background: #e2efda">
                            <td>LBC remit</td>
                            <td>Approval at 5pm daily, 3pm cut off**</td>
                            <td>Processed at 5pm daily, 3pm cut off**</td>
                            <td>Not available</td>
                        </tr>
                        <tr style="background: #e2efda">
                            <td>Western Union</td>
                            <td>Approval at 5pm daily, 3pm cut off**</td>
                            <td>Processed at 5pm daily, 3pm cut off**</td>
                            <td>Not available</td>
                        </tr> --}}
                        <tr>
                            <td colspan="4">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="4">* 2ez.bet partners operating hours/days will vary depending on the partner.</td>
                        </tr>
                        <tr>
                            <td colspan="4">** Remittances compeleted before 3pm will be approved by 5-6pm daily. For those who missed the cut off, completed request will be approved on the next day.</td>
                        </tr>
                        </body>
                    </table>
                </div>
                @endif
                <div class="col-md-12 partner_deposit_table table_content_hide">
                    <div class="title2">PROFILE</div>
                    <div class="tab-pane fade in active partner_content">
                        asdsadasdsadsa
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>
</div>
@endsection
@section('script')
<script type="text/javascript" src="{{ asset('js/select2/select2.full.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/fileinput.js')}}"></script>
<script type="text/javascript" src="{{ asset('bower_components/bootstrap-sweetalert/dist/sweetalert.min.js')}}"></script>
<script src="{{ asset('js/mustache.min.js') }}"></script>
<script id="bank-required-field" type="text/template">
    <div class="col-md-6 form-group">
        <label for="account_name">Account Name</label>
        <input type="text" class="form-control" id="account_name" name="account_name" placeholder="Account Name">
        <label class="error-label">Invalid Input</label>
    </div>
    <div class="col-md-6 form-group">
        <label for="account_numer">Account Number</label>
        <input type="text" class="form-control" id="account_number" name="account_number" placeholder="Account Number">
        <label class="error-label">Invalid Input</label>
    </div>
    <div class="col-md-6 form-group">
        <label for="account_type">Account Type</label>
        <select class="form-control mop" name="account_type" id="account_type" style="width: 100%">
            <option selected disabled>Please select type</option>
            <option value="savings">Savings</option>
            <option value="credit">Credit</option>
            <option value="e-wallet">E-wallet</option>
            <option value="remittance">Remittance</option>
        </select>
        <label class="error-label">Invalid Input</label>
    </div>
    <div class="col-md-6 form-group">
        <label for="account_currency">Account Currency</label>
        <select class="form-control mop" name="account_currency" id="account_currency" style="width: 100%">
            <option selected disabled>Please select currency</option>
            <option value="us-dollars">US Dollars</option>
            <option value="peso">Peso</option>
        </select>
        <label class="error-label">Invalid Input</label>
    </div>
    <div class="form-group">
        <label class="col-sm-12">Cashout request summary:</label>
        <table>
            <tr>
                <td style="width:50%;">
                    <dt class="col-sm-4">Cashout via:</dt>
                    <dd class="col-sm-8">@{{via}}</dd>
                    <dt class="col-sm-4">Account Name:</dt>
                    <dd class="col-sm-8"><span id="summary_account_name">N/A</span></dd>
                    <dt class="col-sm-4">Account Number:</dt>
                    <dd class="col-sm-8"><span id="summary_account_number">N/A</span></dd>
                    <dt class="col-sm-4">Account Type:</dt>
                    <dd class="col-sm-8"><span id="summary_account_type">N/A</span></dd>
                    <dt class="col-sm-4">Account Currency:</dt>
                    <dd class="col-sm-8"><span id="summary_account_currency">N/A</span></dd></td>
                <td>
                    <dt class="col-sm-4">Cashout PHP</dt>
                    <dd class="col-sm-8">&#8369; <span id="summary_amount">@{{amount}}</span></dd>
                    <dt class="col-sm-4">Donation:</dt>
                    <dd class="col-sm-8">&#8369; <span id="summary_donation">@{{donation}}</span> <i class="fa fa-question-circle" style="cursor: pointer;" data-toggle="tooltip" title="We rely on donations from bettors to keep our website running and to pay our processing staff. Thank you for donating at Least 5% of your cash-out amount."  data-placement="right"></i></dd>
                    <dt class="col-sm-4">Net Cashout in PHP:</dt>
                    <dd class="col-sm-8"><span  id="summary_net_cashout_part" style="font-weight:600; font-size: 20px; color:green;">&#8369; @{{netCashout}}</span> <i class="fa fa-question-circle" style="cursor: pointer;" data-toggle="tooltip" title="Cashout amount less Donations"  data-placement="right"></i></dd>                    
                </td>
            </tr>
        </table>

        <p class="col-sm-12"><b>Disclaimer:</b> Third party cashout options may incur fees which we do not control. These fee's will deducted from your cashout amount. </p>
    </div>
</script>
<script id="remittance-required-field" type="text/template">
    <div class="col-md-6 form-group">
        <label for="full_name">Full Name</label>
        <input type="text" class="form-control" id="full_name" name="full_name" placeholder="Full Name">
        <label class="error-label">Invalid Input</label>
    </div>
    <div class="col-md-6 form-group">
        <label for="mobile_number">Mobile Number</label>
        <input type="text" class="form-control" id="mobile_number" name="mobile_number" placeholder="Mobile Number">
        <label class="error-label">Invalid Input</label>
    </div>
    <div class="col-md-6 form-group">
        <label for="location">Location</label>
        <input type="text" class="form-control" id="location" name="location" placeholder="Location">
        <label class="error-label">Invalid Input</label>
    </div>
    <div class="clearfix"></div>
    <div class="form-group">
       {{--  <label for="donation">Cashout request summary <i class="fa fa-question-circle" style="cursor: pointer;" data-toggle="tooltip" title="We rely on donations from bettors to keep our website running and to pay our processing staff. Thank you for donating at Least 5% of your cash-out amount."  data-placement="right"></i></label> --}}
        <label class="col-sm-12">Cashout request summary:</label>
        <dt class="col-sm-4">Cashout PHP equivalent </dt>
        <dd class="col-sm-8">&#8369; <span id="summary_amount">@{{amount}}</span></dd>
        <dt class="col-sm-4">Cashout via:</dt>
        <dd class="col-sm-8">@{{via}}</dd>
        <dt class="col-sm-4">Full Name:</dt>
        <dd class="col-sm-8"><span id="summary_full_name">N/A</span></dd>
        <dt class="col-sm-4">Mobile Number:</dt>
        <dd class="col-sm-8"><span id="summary_mobile_number">N/A</span></dd>
        <dt class="col-sm-4">Location:</dt>
        <dd class="col-sm-8"><span id="summary_location">N/A</span></dd>
        <dt class="col-sm-4">Donation:</dt>
        <dd class="col-sm-8">&#8369; <span id="summary_donation">@{{donation}}</span> <i class="fa fa-question-circle" style="cursor: pointer;" data-toggle="tooltip" title="We rely on donations from bettors to keep our website running and to pay our processing staff. Thank you for donating at Least 5% of your cash-out amount."  data-placement="right"></i></dd>
        <dt class="col-sm-4">Net Cashout in PHP:</dt>
        <dd class="col-sm-8">&#8369; <span id="summary_net_cashout_part">@{{netCashout}}</span> <i class="fa fa-question-circle" style="cursor: pointer;" data-toggle="tooltip" title="Cashout amount less Donations"  data-placement="right"></i></dd>       
        <p class="col-sm-12"><b>Disclaimer:</b> Third party cashout options may incur fees which we do not control. These fee's will deducted from your cashout amount. </p>
    </div>
</script>
<script id="coinsph-required-field" type="text/template">
    <div class="col-md-6 form-group">
        <label for="mobile_number">Email/Mobile Number</label>
        <input type="text" class="form-control" id="mobile_number" name="mobile_number" placeholder="Coins.PH Email/Mobile Number">
        <label class="error-label">Invalid Input</label>
    </div>
    <div class="col-md-6 form-group">
        <label for="location">Wallet Address</label>
        <input type="text" class="form-control" id="location" name="location" placeholder="Wallet Address">
        <label class="error-label">Invalid Input</label>
    </div>
    <div class="clearfix"></div>
    <div class="form-group">
       {{--  <label for="donation">Cashout request summary <i class="fa fa-question-circle" style="cursor: pointer;" data-toggle="tooltip" title="We rely on donations from bettors to keep our website running and to pay our processing staff. Thank you for donating at Least 5% of your cash-out amount."  data-placement="right"></i></label> --}}
        <label class="col-sm-12">Cashout request summary:</label>
        <dt class="col-sm-4">Cashout PHP equivalent </dt>
        <dd class="col-sm-8">&#8369; <span id="summary_amount">@{{amount}}</span></dd>
        <dt class="col-sm-4">Cashout via:</dt>
        <dd class="col-sm-8">@{{via}}</dd>
        <dt class="col-sm-4">Email/Mobile Number:</dt>
        <dd class="col-sm-8"><span id="summary_mobile_number">N/A</span></dd>
        <dt class="col-sm-4">Wallet Address:</dt>
        <dd class="col-sm-8"><span id="summary_location">N/A</span></dd>
        <dt class="col-sm-4">Donation:</dt>
        <dd class="col-sm-8">&#8369; <span id="summary_donation">@{{donation}}</span> <i class="fa fa-question-circle" style="cursor: pointer;" data-toggle="tooltip" title="We rely on donations from bettors to keep our website running and to pay our processing staff. Thank you for donating at Least 5% of your cash-out amount."  data-placement="right"></i></dd>
        <dt class="col-sm-4">Net Cashout in PHP:</dt>
        <dd class="col-sm-8">&#8369; <span id="summary_net_cashout_part">@{{netCashout}}</span> <i class="fa fa-question-circle" style="cursor: pointer;" data-toggle="tooltip" title="Cashout amount less Donations"  data-placement="right"></i></dd>        
        <p class="col-sm-12"><b>Disclaimer:</b> Third party cashout options may incur fees which we do not control. These fee's will deducted from your cashout amount.</p>
    </div>
</script>
<script id="walkin-required-field" type="text/template">
    <div class="clearfix"></div>
    <div class="form-group">
    <!--    <label class="col-sm-12">Cashout request summary:</label>
        <dt class="col-sm-4">Cashout PHP equivalent </dt>
        <dd class="col-sm-8">&#8369; <span id="summary_amount_part">@{{amount}}</span></dd>
        <dt class="col-sm-4">Cashout via:</dt>
        <dd class="col-sm-8">@{{via}}</dd>
        <dt class="col-sm-4">Location:</dt>
        <dd class="col-sm-8">@{{location}}</dd>
        <dt class="col-sm-4">Donation:</dt>
        <dd class="col-sm-8">&#8369; <span id="summary_donation_part">@{{donation}}</span> <i class="fa fa-question-circle" style="cursor: pointer;" data-toggle="tooltip" title="We rely on donations from bettors to keep our website running and to pay our processing staff. Thank you for donating at least 5% of your cash-out amount."  data-placement="right"></i></dd>
        <dt class="col-sm-4">Net Cashout in PHP:</dt>
        <dd class="col-sm-8">&#8369; <span id="summary_net_cashout_part">@{{netCashout}}</span> <i class="fa fa-question-circle" style="cursor: pointer;" data-toggle="tooltip" title="Cashout amount less Donations"  data-placement="right"></i></dd>        
        <p class="col-sm-12"><b>Disclaimer:</b> Partner(s) may incur fees which we do not control. These fees will deducted from your cashout amount. <a href="">See full disclaimer here.</a></p> -->
        <label class="col-sm-12">Cashout request summary:</label>
        <table  style="width:100%;">
            <tr>
                <td style="width:100%;">
                    <dt class="col-sm-4">Cashout via:</dt>
                    <dd class="col-sm-8">@{{via}}</dd>
                    <dt class="col-sm-4">Cashout PHP</dt>
                    <dd class="col-sm-8">&#8369; <span class="summary_amount_part" id="summary_amount_part">@{{amount}}</span></dd>
                    <dt class="col-sm-4">Donation:</dt>
                    <dd class="col-sm-8">&#8369; <span class="summary_donation_part" id="summary_donation_part">@{{donation}}</span> <i class="fa fa-question-circle" style="cursor: pointer;" data-toggle="tooltip" title="We rely on donations from bettors to keep our website running and to pay our processing staff. Thank you for donating at Least 5% of your cash-out amount."  data-placement="right"></i></dd>
                    <dt class="col-sm-4">Net Cashout in PHP:</dt>
                    <dd class="col-sm-8"><span style="font-weight:600; font-size: 20px; color:green;">&#8369;</span> <span style="font-weight:600; font-size: 20px; color:green;" class="summary_net_cashout_part" id="summary_net_cashout_part">@{{netCashout}}</span> <i class="fa fa-question-circle" style="cursor: pointer;" data-toggle="tooltip" title="Cashout amount less Donations"  data-placement="right"></i></dd>    
            </tr>
        </table>

    </div>
</script>
<script id="bank-details" type="text/template">
    <dt class="col-sm-3">Amount:</dt>
    <dd class="col-sm-9">&#8369; @{{amount}}</dd>
    <dt class="col-sm-3">Account Number:</dt>
    <dd class="col-sm-9">@{{account_number}}</dd>
    <dt class="col-sm-3">Account Name:</dt>
    <dd class="col-sm-9">@{{account_name}}</dd>
    <dt class="col-sm-3">Account Type:</dt>
    <dd class="col-sm-9">@{{account_type}}</dd>
    <dt class="col-sm-3">Account Currency:</dt>
    <dd class="col-sm-9">@{{account_currency}}</dd>
    <dt class="col-sm-3">Mode of Payment:</dt>
    <dd class="col-sm-9">@{{mop}}.</dd>
</script>
<script id="remittance-details" type="text/template">
    <dt class="col-sm-3">Amount:</dt>
    <dd class="col-sm-9">&#8369; @{{amount}}</dd>
    <dt class="col-sm-3">Full Name:</dt>
    <dd class="col-sm-9">@{{full_name}}</dd>
    <dt class="col-sm-3">Mobile Number:</dt>
    <dd class="col-sm-9">@{{mobile_number}}</dd>
    <dt class="col-sm-3">Location:</dt>
    <dd class="col-sm-9">@{{location}}</dd>
    <dt class="col-sm-3">Mode of Payment:</dt>
    <dd class="col-sm-9">@{{mop}}</dd>
</script>
<script id="walkin-details" type="text/template">
    <dt class="col-sm-3">Amount:</dt>
    <dd class="col-sm-9">&#8369; @{{amount}}</dd>
    <dt class="col-sm-3">Partner Name:</dt>
    <dd class="col-sm-9">@{{name}}</dd>
    <dt class="col-sm-3">Location:</dt>
    <dd class="col-sm-9">@{{location}}</dd>
</script>
<script id="coinsph-details" type="text/template">
    <dt class="col-sm-3">Amount:</dt>
    <dd class="col-sm-9">&#8369; @{{amount}}</dd>
    <dt class="col-sm-3">Email/Mobile Number:</dt>
    <dd class="col-sm-9">@{{mobile_number}}</dd>
    <dt class="col-sm-3">Wallet Address:</dt>
    <dd class="col-sm-9">@{{location}}</dd>
    <dt class="col-sm-3">Mode of Payment:</dt>
    <dd class="col-sm-9">@{{mop}}</dd>
</script>
<script>
    // swal({
    //     title: "Hi!",
    //     text: 'We are currently in Open Beta.<br/>Cashouts are temporarily disabled. <br/>We will allow cashouts after ESL Birmingham, which will start on May 23 - May 27, 2018.<br/>If you need help, please visit our community group. <br/><a href="https://web.facebook.com/groups/2ez.bet">https://web.facebook.com/groups/2ez.bet</a>',
    //     type: "info",
    //     html: true,
    // }, function() {
    //   // Redirect the user
    //   window.location.href = "{{url('/profile')}}";
    // });
    $("body").tooltip({ selector: '[data-toggle=tooltip]' });
    // mini jQuery plugin that formats to two decimal places

    $(".mop").select2({
        placeholder: "Please select mode of payment"
    });

    $(".partners").select2({
        placeholder: "Please select mode of payment"
    });

    $(function(){
        populatePartnerList();

        $('#partner').trigger('change');

        $('#amount').currencyFormat();

        $('#amount_partner').currencyFormat();

        $('#amount').keyup(function(event) {
            var donation = $(this).val()*0.05
            var netCashout = parseFloat( $(this).val() - donation );
            var amount = $(this).val() == '' ? '0' : $(this).val()
            $('#summary_donation').text(numberWithCommas(donation));
            $('#summary_net_cashout_part').text(numberWithCommas(netCashout.toFixed(2)));
            $('#summary_amount').text(numberWithCommas(amount))
            // if ($(this).val() < donation) {
            //      $('#temp_form').find(':input[name=donation]').parent().addClass('has-error');
            // }
        });

        $('#amount_partner').keyup(function(event) {
            var donation = $(this).val()*0.05
            var netCashout = parseFloat($(this).val() - donation);
            var amount = $(this).val() == '' ? '0' : $(this).val()
            
            $(this).closest("#partner_form").find(".summary_donation_part").text(numberWithCommas(donation.toFixed(2)));
            $(this).closest("#partner_form").find(".summary_net_cashout_part").text(numberWithCommas(netCashout.toFixed(2)));
            $(this).closest("#partner_form").find(".summary_amount_part").text(numberWithCommas(amount));

            // $('#summary_donation_part').text(numberWithCommas(donation))
            // $('#summary_net_cashout_part').text(numberWithCommas(netCashout.toFixed(2)));
            // $('#summary_amount_part').text(numberWithCommas(amount))
        });

        $('.temp_submit').click(function(event) {
            var $this = $(this);
             $this.prop('disabled', true);
             $this.button('progress');    
            var form = $('#temp_form')[0];
            var mop = $('#provider').val().split('-');
            var donation = $('#amount').val()*0.05
            $(form).append(
                $('<input>')
                .attr('type', 'hidden')
                .attr('name', 'type')
                .val('cashout')
            );
            if (mop[1] == 'deposit' || mop[1] == 'online') {
                $(form).append(
                    $('<input>')
                    .attr('type', 'hidden')
                    .attr('name', 'provider_type')
                    .val('bank')
                );

            }else{
                $(form).append(
                    $('<input>')
                    .attr('type', 'hidden')
                    .attr('name', 'provider_type')
                    .val('remittance')
                );

            }
            if ($('#donation').val() < donation) {
                event.preventDefault()
                $(':input[name=donation]').parent().addClass('has-error');
                $this.prop('disabled', false);
                $this.button('reset'); 
            }else{
                $('#temp_form').find(':input[name=donation]').parent().removeClass('has-error');
                $(form).append(
                    $('<input>')
                    .attr('type', 'hidden')
                    .attr('name', 'donation')
                    .val(donation)
                );
                $.ajax({
                    url: '{{ route('transact') }}',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    type: 'POST',
                    data: $(form).serialize(),
                    success: function(data) {

                        $('.form-group input,select').change(function(){
                            $(this.parentNode).removeClass('has-error');
                        });

                        if (data.errors) {
                            $.each( data.errors, function( key, value ) {
                                $('#temp_form').find(':input[name='+ key +']').parent().addClass('has-error');
                                $('#temp_form').find(':input[name='+ key +']').parent().find('.error-label').text(value[0]);
                            });
                        }else {
                            if (!data.success) {
                                swal({
                                    title: "Oops!",
                                    text: data.message,
                                    type: "error",
                                    html: true,
                                });
                                $this.prop('disabled', false);
                                $this.button('reset'); 
                                return 
                            }
                            if(data.transaction.data.mop == 'CoinsPH-payment')
                                var container = $("#coinsph-details").html();
                            else
                                var container = data.transaction.data.mop.split('-')[1] == 'remittance' ? $("#remittance-details").html() : $("#bank-details").html();
                            if (data.transaction.data.mop == 'CoinsPH-payment' || data.transaction.data.mop.split('-')[1] == 'remittance') {
                                var mop,_data = {}
                                switch (data.transaction.data.mop.split('-')[0]) {
                                    case 'cebuana':
                                        mop = 'Cebuana Lhuiller Pera Padala'
                                        break;
                                    case 'lbc':
                                        mop = 'LBC Peso Padala'
                                        break;
                                    case 'palawan':
                                        mop = 'Palawan Express'
                                        break;
                                }
                                _data.amount = data.transaction.amount
                                _data.full_name = data.transaction.data.full_name
                                _data.mobile_number = data.transaction.data.mobile_number
                                _data.location = data.transaction.data.location
                                _data.mop = data.transaction.data.mop == 'CoinsPH-payment' ? 'Coins.PH' : mop
                                $('#details').append(Mustache.render(container,_data));

                            }else{
                                var mop,_data = {}
                                switch (data.transaction.data.mop.split('-')[0]) {
                                    case 'rcbc':
                                        mop = 'RCBC Savings Bank'
                                        break;
                                    case 'securitybank-online':
                                    case 'Securitybank-online':
                                        mop = 'Security Bank Savings - coming soon'
                                        break;
                                    default:
                                        mop = data.transaction.data.mop.split('-')[0]
                                        break;
                                }
                                _data.amount = data.transaction.amount
                                _data.account_number = data.transaction.data.account_number
                                _data.account_name = data.transaction.data.account_name
                                _data.account_type = data.transaction.data.account_type
                                _data.account_currency = data.transaction.data.account_currency
                                _data.mop = mop
                                $('#details').append(Mustache.render(container,_data));
                            }
                            $('.temp_div').remove();
                            $('.success_div .alert-success').find('span').html('<b>'+data.transaction.code+'</b>');
                            $('.success_div').css('display','block');
                            $('input[type="hidden"][name="type"]', form).remove();
                            $('input[type="hidden"][name="provider_type"]', form).remove();
                            $('input[type="hidden"][name="donation"]', form).remove();
                            
                        }
                        $this.prop('disabled', false);
                        $this.button('reset'); 
                    },
                    fail: function(xhr, status, error) {
                        console.log(error);
                        $this.prop('disabled', false);
                        $this.button('reset'); 
                    }
                });
            }
        });

        $('.partner_submit').click(function(event) {
            var $this = $(this);
             $this.prop('disabled', true);
             $this.button('progress');    
            var form = $('#partner_form')[0];
            var def = 'partner-walkin';
            var mop = def.split('-');
            var donation = $('#amount_partner').val()*0.05;
            var new_amount = $('#amount_partner').val() - ($('#amount_partner').val()*0.05);
            $(form).append(
                $('<input>')
                .attr('type', 'hidden')
                .attr('name', 'type')
                .val('cashout')
            );

            $(form).append(
                $('<input>')
                .attr('type', 'hidden')
                .attr('name', 'provider_type')
                .val('cashout')
            );

            $(form).append(
                $('<input>')
                .attr('type', 'hidden')
                .attr('name', 'mop')
                .val('partner-walkin')
            );

            $(form).append(
                $('<input>')
                .attr('type', 'hidden')
                .attr('name', 'donation')
                .val(donation)
            );

            $(form).append(
                $('<input>')
                .attr('type', 'hidden')
                .attr('name', 'new_amount')
                .val(new_amount)
            );

            $.ajax({
                url: "{{ route('deposit_to_partner') }}",
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                type: 'POST',
                data: $(form).serialize(),
                success: function(data) {
                    if(data.errors){
                        $.each( data.errors, function( key, value ) {
                            var error_message = 'None';
                            if (key == 'amount_partner') {
                                error_message = value[0];
                            }
                            if (key == 'partner') {
                                error_message = value[0];
                            }
                            swal({
                                title: "Oops!",
                                text: error_message,
                                type: "error",
                                html: true,
                            });
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
                            var container = $("#walkin-details").html();
                            var _data = {}
                                _data.amount = data.data.amount
                                _data.name = data.data.partner
                                _data.location = data.data.address
                                $('#details').append(Mustache.render(container,_data));
                            $('.temp_div').remove();
                            $('.success_div .alert-success').find('span').html('<b>'+data.data.code+'</b>');
                            $('.success_div').css('display','block');
                            $('input[type="hidden"][name="type"]', form).remove();
                            $('input[type="hidden"][name="provider_type"]', form).remove();
                            $('input[type="hidden"][name="donation"]', form).remove();
                            $('input[type="hidden"][name="new_amount"]', form).remove();
                        }
                    }
                },
                fail: function(xhr, status, error) {
                    console.log(error);
                    $this.prop('disabled', true);
                    $this.button('progress');
                }
            });
        });

        $('#provider').change(function(event) {
            $('#field-required').empty();
            var donation = $('#amount').val()*0.05
            var netCashout = parseFloat( $('#amount').val() - donation);
            var selected = $(this).val().split('-');
            var data = {}
            if($(this).val() == 'CoinsPH-payment')
                var container = $("#coinsph-required-field").html();
            else
                var container = selected[1] == 'deposit' || selected == 'online' ? $("#bank-required-field").html() : $("#remittance-required-field").html();
            data.donation = donation.toFixed(2);
            data.via = $('#provider option:selected').text()
            data.amount = numberWithCommas($('#amount').val())
            data.netCashout = numberWithCommas(netCashout.toFixed(2));
            $('#field-required').append(Mustache.render(container,data));
            // $('#donation').val(donation);
            // $('#donation').currencyFormat();
            // $('#donation').keyup(function(event) {
            //     var donation = $('#amount').val()*0.05
            //     if ($(this).val() < donation) {
            //          $('#temp_form').find(':input[name=donation]').parent().addClass('has-error');
            //     }else{
            //          $('#temp_form').find(':input[name=donation]').parent().removeClass('has-error');
            //     }
            // });

            $('#account_name').keyup(function(event) {
                var text = $(this).val() == '' ? 'N/A' :$(this).val()
                $('#summary_account_name').text(text)
            });

            $('#account_number').keyup(function(event) {
                var text = $(this).val() == '' ? 'N/A' :$(this).val()
                $('#summary_account_number').text(text)
            });

            $('#account_type').change(function(event) {
                $('#summary_account_type').text($('#account_type option:selected').text())
            });

            $('#account_currency').change(function(event) {
                $('#summary_account_currency').text($('#account_currency option:selected').text())
            });

            $('#full_name').keyup(function(event) {
                var text = $(this).val() == '' ? 'N/A' :$(this).val()
                $('#summary_full_name').text(text)
            });

            $('#mobile_number').keyup(function(event) {
                var text = $(this).val() == '' ? 'N/A' :$(this).val()
                $('#summary_mobile_number').text(text)
            });

            $('#location').keyup(function(event) {
                var text = $(this).val() == '' ? 'N/A' :$(this).val()
                $('#summary_location').text(text)
            });
        });

        $('#partner-select').change(function(event) {
            $('#partner-field-required').empty();
            var donation = $('#amount_partner').val()*0.05
            var netCashout = parseFloat( $('#amount_partner').val() - donation);
            var selected = $(this).val().split('-');
            var container =  $("#walkin-required-field").html();
            var data = {}
            data.donation = donation.toFixed(2);
            data.via = $('#partner-select option:selected').text()
            data.amount = numberWithCommas($('#amount_partner').val())
            data.location = $('#partner-select option:selected').attr('data-place')
            data.netCashout  = numberWithCommas(netCashout.toFixed(2));
            $('#partner-field-required').append(Mustache.render(container,data));

            $('#account_name').keyup(function(event) {
                var text = $(this).val() == '' ? 'N/A' :$(this).val()
                $('#summary_account_name').text(text)
            });

            $('#account_number').keyup(function(event) {
                var text = $(this).val() == '' ? 'N/A' :$(this).val()
                $('#summary_account_number').text(text)
            });

            $('#account_type').change(function(event) {
                $('#summary_account_type').text($('#account_type option:selected').text())
            });

            $('#account_currency').change(function(event) {
                $('#summary_account_currency').text($('#account_currency option:selected').text())
            });

            $('#full_name_part').keyup(function(event) {
                var text = $(this).val() == '' ? 'N/A' :$(this).val()
                $('#summary_full_name_part').text(text)
            });

            $('#mobile_number_part').keyup(function(event) {
                var text = $(this).val() == '' ? 'N/A' :$(this).val()
                $('#summary_mobile_number_part').text(text)
            });

            $('#location').keyup(function(event) {
                var text = $(this).val() == '' ? 'N/A' :$(this).val()
                $('#summary_location_part').text(text)
            });
        
            $("#mobile_number_part").keydown(function (e) {
                if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
                    (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) || 
                    (e.keyCode >= 35 && e.keyCode <= 40)) {
                    return;
                }
                if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                    e.preventDefault();
                }
            });
        });

        var profile = [];
        $('#partner-select').on('change', function(event){
            if($('.partner_deposit_table').hasClass('table_content_hide')){
                $('.direct_deposit_table').addClass('table_content_hide');
                $('.partner_deposit_table').removeClass('table_content_hide');
            }
            $('.partner_content').html( profile[$(this).find('option:selected').data('array_count')].content );
        });

        $('#direct_transaction_tab').on('click', function(event){
            $('.direct_deposit_table').removeClass('table_content_hide');
            $('.partner_deposit_table').addClass('table_content_hide');
            $('.partner_content').html('<center><h3>No profile to show</h3></center>');
        });

        const purchaseVia = "{{$via}}";
        console.log('purchaseVia:', purchaseVia)
        if(purchaseVia == 'partner'){
            $('#partner_transaction_tab').trigger('click');
        }


        $('#partner_transaction_tab').on('click', function(event){
            $('.direct_deposit_table').addClass('table_content_hide');
            $('.partner_deposit_table').removeClass('table_content_hide');
            $('.partner_content').html('<center><h3>No profile to show</h3></center>');
        });

        function populatePartnerList(){
            const partnerViaId = "{{$partner_id}}";
            $.ajax({
                url: "{{ route('get_partners_list') }}",
                type: "GET",
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                success: function(data){
                    var country = [];
                    var optlist = document.createDocumentFragment();
                    const autoSelectedPartner = {!! json_encode($autoSelectedPartner) !!}

                    if( !!autoSelectedPartner && parseInt(autoSelectedPartner.show_in_site) !== 1 ){
                        data.partners.push(autoSelectedPartner);
                        $('#partner').prop('disabled',true);
                        $("#partner_form").append(`<input type="hidden" name="partner" value="${autoSelectedPartner.id}"/>`);
                    }                    
                    $.each(data.partners,function(index,partner){
                        if(jQuery.inArray( partner.province.province, country ) == -1){
                            var optgroup = document.createElement('optgroup');
                            $(optgroup).attr({'label': partner.province.province, 'id': 'place-version-'+partner.province.id});
                            optlist.append(optgroup);

                            country.push(partner.province.province)
                        }
                    })
                    $('#partner-select').append(optlist);
                    for(var i = 0; i < data.partners.length; i++){
                        var option = document.createElement('option');
                        $(option).attr('value', data.partners[i].id).attr('data-place', data.partners[i].address+', '+data.partners[i].province.province).text(data.partners[i].partner_name).data('array_count', i);
                        if(partnerViaId == data.partners[i].id){
                            $(option).prop('selected', true)
                        }                        
                        $('#place-version-'+data.partners[i].province_id).append(option);
                        if(data.partners[i].profile == null){
                            profile.push({content:'<center><h3>No partner profile to show</h3></center>', id: data.partners[i].id});
                        }
                        else{
                            profile.push({content:data.partners[i].profile.content, id: data.partners[i].id});
                        }
                    }

                    if(!!partnerViaId ){
                        $('#partner-select').trigger('change');
                    }
                },
            });
        }

    })
//END PICTURE UPLOAD    
</script>
@endsection
