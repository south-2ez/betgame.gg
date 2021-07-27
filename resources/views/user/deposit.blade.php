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

    .success-text{
        color: green;
    }
</style>
<link rel="stylesheet" href="{{ asset("css/select2/select2.min.css") }}">
<link rel="stylesheet" href="{{ asset("css/fileinput.css") }}">
<link rel="stylesheet" href="{{ asset('bower_components/bootstrap-sweetalert/dist/sweetalert.css') }}">
@endsection

@section('content')
<div class="main-container dark-grey">
    <div class="m-container3">
        <div class="main-ct temp_div">
            <div class="title2">PURCHASE</div>
            <div class="clearfix"></div>
            <div class="blk-1">
                <div class="col-md-12">

                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#direct-deposit" id="direct_transaction_tab" aria-controls="home" role="tab" data-toggle="tab">Direct Purchase</a></li>
                        <li role="presentation"><a href="#partner-deposit" id="partner_transaction_tab" aria-controls="cashout" role="tab" data-toggle="tab">Purchase via Partner</a></li>
                    </ul>
                    <br>
                    <div class="col-md-12">
                        <div class="alert alert-danger" role="alert">
                            <strong>Important!</strong> Please read the guide on how to process your request after submission.

                        @if (session('affliate_voucher_code_warning'))
                            <br/>
                            <strong>Warning!</strong> {{ session('affliate_voucher_code_warning') }}
                        @endif

                        </div>
                    </div>

                    <div class="tab-content">
                        <div class="tab-pane fade in active" id="direct-deposit" role="tabpanel">
                            <div class="tab-content" style="margin-top: 20px">
                            <form id="temp_form" autocomplete="off" action="" method="POST" role="form">

                                <div class="col-md-6 form-group">   
                                    <label for="amount">Ez credits</label>
                                    <input type="text" class="form-control" id="amount" name="amount" placeholder="Amount" value="500">
                                    <label class="error-label">Invalid Input</label>
                                </div>

                                <div class="col-md-6 form-group">
                                    <label for="mop">Mode of Payment</label>
                                    <select class="form-control mop" name="provider" id="provider" style="width: 100%" data-placeholder="Select mode of payment">
                                        {{-- <optgroup label="Online Bank Transfer">
                                            <option value="BDO-online" selected>BDO Online</option>
                                            <option value="BPI-online">BPI Online</option>
                                            <option value="Securitybank-online">Security Bank Online</option>
                                        </optgroup>
                                        <optgroup label="Bank Deposit">
                                            <option value="BDO-deposit">BDO</option>
                                            <option value="BPI-deposit">BPI</option>
                                            <option value="Securitybank-deposit">Security Bank Savings</option>
                                        </optgroup> --}}
                                        <optgroup label="Others">
                                            <option value="others-deposit">Others</option>
                                        </optgroup>
                                    </select>
                                    <label class="error-label">Invalid Input</label>
                                </div>

                                @if(!empty(Auth::user()->redeem_voucher_code) && 1 == 2)
                                <div class="col-md-6 form-group form-group" id="voucher_code_container">
                                    <label for="voucher_code">Voucher Code</label>
                                    <input type="text" class="form-control" id="voucher_code" name="voucher_code" placeholder="Voucher Code" value="{{ Auth::user()->redeem_voucher_code ?? '' }}" readonly>

                                    <span class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true" style="right: 10px; display:none;"></span>
                                    <span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true" style="right:10px; display:none;"></span>
                                    <label class="error-label">Invalid voucher code.</label>
                                </div>
                                @endif

                                <div class="col-md-6 form-group coinsph" style="display: none">
                                    <label>For Coins.Ph Payments</label>
                                    <input type="text" class="form-control" name="coinsph_user" placeholder="Enter your Email or Mobile number">
                                    <label class="error-label">Invalid Input</label>
                                </div>

                                <div class="col-md-12 form-group">
                                    <button type="button" class="btn btn-primary temp_submit" style="float: right;" data-progress-text="Saving ... <i class='fa fa-circle-o-notch fa-spin fa-fw'></i>" autocomplete="off">Submit</button>
                                </div>
                                </form>
                            </div> 
                            
                            



                        </div>

                        <div class="tab-pane fade in" id="partner-deposit" role="tabpanel">
                            
                            <div class="tab-content" style="margin-top: 20px">
                            <form id="partner_form" autocomplete="off" action="" method="POST" role="form">

                                <div class="col-md-6 form-group">   
                                    <label for="amount">Ez credits</label>
                                    <input type="text" class="form-control" id="amount_partner" name="amount_partner" placeholder="Amount" value="500">
                                    <label class="error-label">Invalid Input</label>
                                </div>

                                <div class="col-md-6 form-group">
                                    <label for="partners">Select Partner</label>
                                    <select class="form-control partners" name="partner" id="partner" style="width: 100%" data-placeholder="Select partner">
                                        <option selected disabled></option>
                                    </select>
                                    <label class="error-label">Invalid Input</label>
                                </div>

                                @if(!empty(Auth::user()->redeem_voucher_code) && 1 == 2)
                                <div class="col-md-6 form-group" id="partner-voucher-code-container">   
                                    <label for="amount">Voucher Code</label>
                                    <input type="text" class="form-control" id="voucher_code_partner" name="voucher_code_partner" placeholder="Voucher Code" value="{{ Auth::user()->redeem_voucher_code ?? '' }}"  readonly>
                                    
                                    <span class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true" style="right: 10px; display:none;"></span>
                                    <span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true" style="right:10px; display:none;"></span>
                                    <label class="error-label">Invalid voucher code.</label>
                                </div>
                                @endif

                                <div class="col-md-6 form-group coinsph" style="display: none">
                                    <label>For Coins.Ph Payments</label>
                                    <input type="text" class="form-control" name="coinsph_user" placeholder="Enter your Email or Mobile number">
                                    <label class="error-label">Invalid Input</label>
                                </div>

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
        <div class="main-ct success_div" style="display: none;">
            <div class="title2">DEPOSIT</div>
            <div class="clearfix"></div>
            <div class="blk-1">
                <div class="col-md-12">
                    
                    <div class="col-md-12">
                        <div class="alert alert-success" role="alert">
                          <strong>Saved!</strong> Request was successfull. <b style="font-size: 17px;">BC Code: </b><span id="code" style="font-size: 17px;"></span>
                        </div>
                    </div>

                    <div class="col-md-12 form-group"> 
                        <div id="first-deposit-message" style="display:none;">
                            Hi <strong>{{ Auth()->user()->name }}</strong>, <br/><br/>

                            We have noticed that this is your first purchase request at 2ez.bet. <br/>
                            
                            In order for us to verify your identity, please PM our facebook page 

                            <a href="https://www.facebook.com/2ez.bet/" target="_blank">https://www.facebook.com/2ez.bet/</a> <br/>
                            
                            This is to ensure there are no minors joining our community. <br/>
                            
                            Our verification process requirements are: <br/>
                            
                            1. One (1) valid ID <br/>
                            2. Selfie with ID <br/>
                            3. Short video with ID <br/>
                            <br/>
                            Thank you for your interest in joining our community.    <br/>     
                        </div>    
                        <div id="normal-deposit" style="display:none;">
                            <h3>To complete your credit purchase, follow the steps below:</h3>
                            <ul class="list-unstyled" style="font-size: 15px;" id="deposit-steps">
                            </ul>
                        </div>

                    </div>
                            
                    <form id="upload_image_form" enctype="multipart/form-data" autocomplete="off" action="" method="POST" role="form">

                        @if(1 == 2)
                        <div class="col-md-12 form-group">
                            {{-- <label for="image">Upload Image</label> --}}
                                {{-- <input type="file" accept="image/*" id="image" name="image"  class="file-loading" data-allowed-file-extensions='[]'> --}}
                                <input id="image" name="image" accept="image/*" class="file-loading" type="file">
                            <label class="error-label">Invalid Input</label>
                        </div>
                        @endif

                        <div class="col-md-12 form-group">  
                            {{-- <div class="col-md-12 form-group"> --}}
                                <button type="button" class="btn btn-primary complete" style="float: right;" autocomplete="off"> Complete Deposit</button>
                                <a href="{{url('/profile#deposits')}}" type="submit" class="btn btn-default goto_history"> Go to transaction history <i class="fa fa-arrow-circle-o-right" aria-hidden="true"></i></a>
                            {{-- </div> --}}
                        </div>
                    </form>
                </div>
            </div>
        </div>
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
                            @if($partner->show_in_site == 1)
                                <tr>
                                    <td>
                                        <a href="{{ route('deposit.page', ['via' => 'partner', 'partner_id' => $partner->id]) }}">{{ $partner->partner_name}}</a>
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
                                    <td class="text-center"><a  href="{{ route('deposit.page', ['via' => 'partner', 'partner_id' => $partner->id]) }}" class="btn btn-primary btn-xs">Purchase</a></td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div> --}}

{{-- 
    <div class="m-container3 transaction_table_container" style="background: none; box-shadow: none;">
        <div class="main-ct">
            <div class="blk-1">
                <div class="direct_deposit_table">
                    <table id="transaction_table" class="table table-responsive table-condensed table-bordered" width="80%">
                        <body>
                        <tr>
                            <td>Method of purchase/transfer</td>
                            <td>Purchase Approval time(office hours)</td>
                            <td>Transfer Approval time(office hours)</td>
                            <td>Non-Office hours/Holidays/No admin online</td>
                        </tr>
                        <tr style="background: #f4b084">
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
                        </tr>
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
                <div class="col-md-12 partner_deposit_table table_content_hide">
                    <div class="title2">PROFILE</div>
                    <div class="tab-pane fade in active partner_content">
                        
                    </div>
                </div>
            </div>
        </div>
    </div> --}}

<div id="optionModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Update Partner Information</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="control-label">Selected Wallet:</label>
                        <select name="user_choice_deposit" id="user_choice_deposit" class="form-control">
                            <option selected disabled>- Select Wallet for Deposit -</option>
                            <option value="0">User Wallet</option>
                            <option value="1">Partner Wallet</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="deposit-user-btn" type="button" class="btn btn-primary">Update</button>
                    <button type="button" class="btn btn-default close-modal" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>

<!-- Modal NOTICE remove after-->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title text-bold" id="exampleModalLabel"> 
            <strong>NOTICE:</strong>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </h2>
      </div>
      <div class="modal-body text-center">
        <h3>Please be aware that we will be having our company Christmas party on <strong>Monday 08:00 am (December 09, 2019)</strong>.
        <br/>We will resume regular operations<br/> on <strong>Wednesday 08:00 am</strong>.</h3>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

</div>
@endsection
@section('script')
<script type="text/javascript" src="{{ asset('js/select2/select2.full.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/fileinput.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/theme.js')}}"></script>
<script type="text/javascript" src="{{ asset('bower_components/bootstrap-sweetalert/dist/sweetalert.min.js')}}"></script>
<script src="{{ asset('js/mustache.min.js') }}"></script>
<script id="coinsph-steps-template" type="text/template">
    <li>1. Send the amount <insert amount> to this wallet: <strong>{{$settings['coins-wallet-address']}}</strong></li>
    <li>2. Take screenshot</li>
    <li>3. Edit the screenshot and write your BC-code <span style="font-weight: bold;">@{{code}}</span></li>
    <li>4. Upload the edited reciept with your bccode</li>
    <li>5. Wait for approval.</li>
    <br/>
    <strong>Note: You may follow up on your transactions by messaging the <a href="https://www.facebook.com/2ez.bet/">2ez.bet</a> Facebook Page.</strong>
</script>
{{-- <script id="manual-steps-template" type="text/template">
    <li>1. Visit any branch of @{{bank}}, the branch will be open around @{{bopen}} to @{{bclose}}, malls will be open at @{{mopen}} closes at @{{mopen}}.</li>
    <li>2. Get the Blue Slip (deposit slip) from the Guard or at the provided shelf with desk provided for slips.</li>
    <li>3. Fill out the necessary fields.</li>
        <ul>
            <li>Account Name: <b>@{{accountname}}</b></li>
            <li>Account Number: <b>@{{accountnumber}}</b></li>
            <li>Date of the Day: <b>(MM/DD/YY)</b></li>
            <li>Insert amount: <span style="font-weight: bold;">Php @{{amount}}</span></li>
        </ul>
    <li>4. Get into the line for bank deposit.</li>
    <li>5. Keep the Official RECEIPT of the tranasaction.</li>
    <li>6. Write the BC Code <span style="font-weight: bold;">@{{code}}</span> on top of the Official Receipt, and take a picture of it. (make sure the Image and the details written are clear).</li>
    <br/>
    <strong>Note: You may follow up on your transactions by messaging the <a href="https://www.facebook.com/2ez.bet/">2ez.bet</a> Facebook Page.</strong>
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
    <br/>
    <strong>Note: You may follow up on your transactions by messaging the <a href="https://www.facebook.com/2ez.bet/">2ez.bet</a> Facebook Page.</strong>
</script>
<script id="BPI-desktop" type="text/template">
    <li>1. Go to BPI.com.ph, enter the user ID and Password.</li>
    <li>2. Go to fund transfer tab then select fund transfer on the drop down menu, then click the Transfer funds today.</li>
    <li>3. Fill out the required information, the AMOUNT to transfer, select the source amount, select the enrolled account of 2ez.bet with the account number <b>{{$settings['bpi-account-number']}}</b> and add remarks.</li>
    <li>4. And click Transfer Now/Sumit Button.</li>
    <li>5. <span style="color:red;font-weight: bold">Important: </span>Write your BC CODE on remarks <span style="font-weight: bold;">@{{code}}</span>.</li>
    <li>6. Make a screen shot of the transaction.</li>
    <br/>
    <strong>Note: You may follow up on your transactions by messaging the <a href="https://www.facebook.com/2ez.bet/">2ez.bet</a> Facebook Page.</strong>
</script>
<script id="Metrobank-desktop" type="text/template">
    <li>1. Go to metrobank.com.ph, enter the user IDandPassword.</li>
    <li>2. Go to fund transfertab then selectfund transferon the drop down menu, then click theTransfer funds today.</li>
    <li>3. Fill out the required information, the AMOUNT to transfer, select the source amount, select the enrolled account of 2EZ.bet with the account number <b>{{$settings['metro-account-number']}}</b> and add remarks.</li>
    <li>4. And click Transfer Now.</li>
    <li>5. Then press the SUBMIT button.</li>
    <li>6. And screen shot the transaction promt after you press the Submit Button.</li>
    <br/>
    <strong>Note: You may follow up on your transactions by messaging the <a href="https://www.facebook.com/2ez.bet/">2ez.bet</a> Facebook Page.</strong>
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
            <li>Insert amount: <span style="font-weight: bold;">Php @{{amount}}</span></li>
        </ul>
    <li>3. On the Transaction Type Check the box of Sending.</li>
    <li>4. Place the desired amount to send at Sending/Receiving Amount.</li>
    <li>5. And on the Buttom sign the signature over printed name.</li>
    <li>6. Submit the Form together with your I.D. And Amount at the counter (any I.D. Will do as long as it is a goverment issued I.D. With a picture, your signature and name on it and not expired).</li>
    <li>7. Once the transaction is complete. Write the BC Code  <span style="font-weight: bold;">@{{code}}</span>  and 10-digit control number on top  of the Official RECEIPT.</li>
    <br/>
    <strong>Note: You may follow up on your transactions by messaging the <a href="https://www.facebook.com/2ez.bet/">2ez.bet</a> Facebook Page.</strong>
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
    <br/>
    <strong>Note: You may follow up on your transactions by messaging the <a href="https://www.facebook.com/2ez.bet/">2ez.bet</a> Facebook Page.</strong>
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
    <br/>
    <strong>Note: You may follow up on your transactions by messaging the <a href="https://www.facebook.com/2ez.bet/">2ez.bet</a> Facebook Page.</strong>
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
    <br/>
    <strong>Note: You may follow up on your transactions by messaging the <a href="https://www.facebook.com/2ez.bet/">2ez.bet</a> Facebook Page.</strong>
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
    <br/>
    <strong>Note: You may follow up on your transactions by messaging the <a href="https://www.facebook.com/2ez.bet/">2ez.bet</a> Facebook Page.</strong>
</script>
<script id="others-others" type="text/template">
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
    <br/>
    <strong>Note: You may follow up on your transactions by messaging the <a href="https://www.facebook.com/2ez.bet/">2ez.bet</a> Facebook Page.</strong>
</script>
<script id="partner-walkin" type="text/template">
    <li>1. Visit @{{partner}} at @{{address}} in this schedule, @{{schedule}}.</li>
    <li>2. Approach the contact person in the area and pay for the amount.</li>
    <li>3. Once the person receives the money, he/she will transfer the credits to your account.</li>
</script>
<script>
    $(document).ready(function() {
        if( typeof($('#voucher_code_partner').val()) != 'undefined' && $('#voucher_code_partner').val().length > 0) {
            $("#partner_transaction_tab").click();
        }
    });
    // mini jQuery plugin that formats to two decimal places
    (function($) {
        $.fn.currencyFormat = function() {
            this.each( function( i ) {
                $(this).change( function( e ){
                    if( isNaN( parseFloat( this.value ) ) ) return;
                    this.value = parseFloat(this.value);
                });

                $(this).keydown( function( e ){
                    -1!==$.inArray(e.keyCode,[46,8,9,27,13,110,190])||/65|67|86|88/.test(e.keyCode)&&(!0===e.ctrlKey||!0===e.metaKey)||35<=e.keyCode&&40>=e.keyCode||(e.shiftKey||48>e.keyCode||57<e.keyCode)&&(96>e.keyCode||105<e.keyCode)&&e.preventDefault();
                });
            });
            return this; //for chaining
        }
    })( jQuery );
    
//    $(document).on('change', '#provider', function() {
//        if($(this).val() == 'CoinsPH-payment') {
//            $('#temp_form').find('.coinsph').show();
//            $('#temp_form').find('.coinsph').removeClass('has-error');
//        } else {
//            $('#temp_form').find('.coinsph').hide();
//        }
//        
//    });

    $(".mop").select2({
        placeholder: "Please select mode of payment"
    });

    $(".partners").select2({
        placeholder: "Please select partner"
    });

    $(function(){

        //don't excute if people are back from xmas party
        var breakEndDate = new Date('2019/12/11 07:59:00');
        var currentDate = new Date();
        if(breakEndDate > currentDate){
            $('#myModal').modal('show');
        }
        //end showing notice modal
                
        populatePartnerList();
        
        $('#provider').trigger('change');

        $("#image").fileinput({
            previewFileType: "image",
            theme: "fa",
            showUpload: false,
            showCaption: false,
            showRemove: false,
            browseClass: "btn btn-primary",
            browseLabel: "Pick Image",
            browseIcon: "<i class=\"fa fa-picture-o\"></i> ",
            validateInitialCount:true,
            // removeClass: "btn btn-danger",
            // removeLabel: "Delete",
            // removeIcon: "<i class=\"fa fa-trash-o\"></i> ",
            // uploadClass: "btn btn-info",
            // uploadLabel: "Upload",
            // uploadIcon: "<i class=\"fa fa-upload\"></i> ",
            allowedFileExtensions: ["jpg", "gif", "png"],
        }).on("filebatchselected", function(event, files) {
            $('.kv-upload-progress').css('display', 'none');
            $('.file-upload-indicator').css({
                backgroundColor: '#fcf8e3',
                borderColor: '#faebcc'
            });
            $('.file-upload-indicator').find('i').removeClass('fa-check-circle').removeClass('text-success').addClass('fa-hand-o-down').addClass('text-warning')
            var _url = $('input[name=depositVia]').val() ? "{{ route('upload_partner') }}" : "{{ route('upload') }}", status = $('input[name=depositVia]').val() ? 0 : 'processing';
            $.ajax({
                url: _url,
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                type: 'POST',
                data: {
                        photo: $('.file-preview-image').attr('src'),
                        id: $('input[type="hidden"][name="id"]', 
                        $('#upload_image_form')[0]).val(),
                    },
                success: function(data) {

                    if (data.success) {
                        $('.kv-upload-progress').removeClass('hide').css('display', 'block').find('.progress-bar').css('width', '100%').text('Done')
                        $('.file-upload-indicator').css({
                            backgroundColor: '#dff0d8',
                            borderColor: '#d6e9c6'
                        });
                        $('.file-upload-indicator').find('i').removeClass('fa-hand-o-down').removeClass('text-warning').addClass('fa-check-circle').addClass('text-success')

                    }
                },
                fail: function(xhr, status, error) {
                    console.log(error);
                }
            });

        });

        // $('#image').on('filepreupload', function(event, data, previewId, index) {
        //     data.form.append('photo',$('.file-preview-image').attr('src'))
        //     data.form.append('id', $('input[type="hidden"][name="id"]', $('#upload_image_form')[0]).val())
        //     console.log('File pre upload triggered');
        // });

        $('#amount').currencyFormat();

        $('.temp_submit').click(function(event) {
            var $this = $(this);
             $this.prop('disabled', true);
             $this.button('progress');    
            var form = $('#temp_form')[0];
            var mop = $('#provider').val().split('-');
            $(form).append(
                $('<input>')
                .attr('type', 'hidden')
                .attr('name', 'type')
                .val('deposit')
            );

            $(form).append(
                $('<input>')
                .attr('type', 'hidden')
                .attr('name', 'provider_type')
                .val('deposit')
            );

            $.ajax({
                url: "{{ route('transact') }}",
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
                        }else{
                            $('.temp_div').remove();
                        }

                        var _form = $('#upload_image_form')[0];
                        $(_form).append(
                            $('<input>')
                            .attr('type', 'hidden')
                            .attr('name', 'id')
                            .val(data.transaction.id)
                        );
                        $('input[type="hidden"][name="depositVia"]', _form).remove();
                        const depositCount = !!data.transaction && data.transaction.deposit_count ? data.transaction.deposit_count : 0;
                        if(depositCount == 0){
                            $('#upload_image_form').hide();
                            $('#first-deposit-message').show();
                           
                        }else{
                            $('#upload_image_form').show();
                            $('#normal-deposit').show();
                            renderStepsManual(mop,data.transaction);
                        }
                       
                        $('.success_div .alert-success').find('span').html('<b>'+data.transaction.code+'</b>');
                        $('.success_div').css('display','block');
                        $('input[type="hidden"][name="type"]', form).remove();
                        $('input[type="hidden"][name="provider_type"]', form).remove();



                    }
                    $this.prop('disabled', false);
                    $this.button('reset'); 
                },
                fail: function(xhr, status, error) {
                    console.log(error);
                    $this.prop('disabled', true);
                    $this.button('progress');
                }
            });
        });

        $('.partner_submit').click(function(event) {
            var $this = $(this);
             $this.prop('disabled', true);
             $this.button('progress');    
            var form = $('#partner_form')[0];
            var def = 'partner-walkin';
            var mop = def.split('-');
            const voucher_code_partner = $('#voucher_code_partner').val();


            $(form).append(
                $('<input>')
                .attr('type', 'hidden')
                .attr('name', 'type')
                .val('deposit')
            );

            $(form).append(
                $('<input>')
                .attr('type', 'hidden')
                .attr('name', 'provider_type')
                .val('deposit')
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
                .attr('name', 'voucher_code')
                .val(voucher_code_partner)
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
                            $('.temp_div').remove();
                            var _form = $('#upload_image_form')[0];
                            $(_form).append(
                                $('<input>')
                                .attr('type', 'hidden')
                                .attr('name', 'id')
                                .val(data.data.id)
                            );
                            $(_form).append(
                                $('<input>')
                                .attr('type', 'hidden')
                                .attr('name', 'depositVia')
                                .val('partner')
                            );
                            partnerStepsManual(mop,data.data);
                            $('.success_div .alert-success').find('span').html('<b>'+data.data.code+'</b>');
                            $('.success_div').css('display','block');
                            $('input[type="hidden"][name="type"]', form).remove();
                            $('input[type="hidden"][name="provider_type"]', form).remove();
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

        $('.complete').click(function(event) {
            event.preventDefault();
            var code = $('#code').text()
            var title = $('.file-preview-image').attr('src') == null ? 'You have not uploaded your receipt yet' : 'Your deposit request '+code+'  is currently being processed.'
            var message = $('.file-preview-image').attr('src') == null ? 'You may monitor your deposits in you profile\'s deposits history' : 'It usually takes a few hours but sometimes can take a full day. Please contact our admins if your request has not been processed after 24 hours.'
            swal({
                title: title,
                text: message,
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Yes, please continue!",
                showLoaderOnConfirm: true,
                closeOnConfirm: false
            },
            function(){
                window.location.replace("{{url('/profile')}}");
            });
        });

        $('.submit_image').click(function(event) {
            var $this = $(this);
            $this.prop('disabled', true);
            $this.button('progress');    
            var form = $('#upload_image_form')[0];
            var image = $('.file-preview-image').attr('src');
            $(form).append(
                $('<input>')
                .attr('type', 'hidden')
                .attr('name', 'image')
                .val(image)
            );
            $(form).append(
                $('<input>')
                .attr('type', 'hidden')
                .attr('name', 'status')
                .val('processing')
            );
            $.ajax({
                url: '{{ route('upload') }}',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                type: 'POST',
                data: $(form).serialize(),
                success: function(data) {
                    $('.form-group input,select').change(function(){
                        $(this.parentNode).removeClass('has-error');
                    });

                    if (data.errors) {
                        $.each( data.errors, function( key, value ) {
                            $('#upload_image_form').find(':input[name='+ key +']').parent().find('.form-group').addClass('has-error');
                            $('#upload_image_form').find(':input[name='+ key +']').parent().find('.error-label').text(value[0]);
                        });
                    }else {
                        $('.success_div .alert-success').find('span').html('<b>'+data.transaction.code+'</b>')
                        $('.success_div').css('display','block');
                        $('input[type="hidden"][name="id"]', form).remove();
                        $('input[type="hidden"][name="image"]', form).remove();
                        $('input[type="hidden"][name="status"]', form).remove();
                        
                    }
                    $this.prop('disabled', false);
                    $this.button('reset'); 
                },
                fail: function(xhr, status, error) {
                    console.log(error);
                    $this.button('reset');
                }
            });
        });

        var profile = [];
        $('#partner').on('change', function(event){
            let hasAffliates = $(this).find('option:selected').attr('has-affliates');
            //$('#voucher_code_partner').prop('readonly',false);

            hasAffliates = hasAffliates ? parseInt(hasAffliates) : 0;
            hasRedeemVoucherCode = '{{ Auth::user()->redeem_voucher_code }}';
            $('.partner_content').html( profile[$(this).find('option:selected').data('array_count')].content );
            //console.log(hasRedeemVoucherCode)


/*//set voucher code field to readonly
            if(hasAffliates > 0 && hasRedeemVoucherCode == ''){
                $('#voucher_code_partner').prop('disabled',false);
            }else{
                $('#voucher_code_partner').prop('disabled',true);
            }
*/

/*
            //OLD SCRIPT
            $('#voucher_code_partner').val('');
            $('#voucher_code_partner').trigger('blur');

            if($('.partner_deposit_table').hasClass('table_content_hide')){
                $('.direct_deposit_table').addClass('table_content_hide');
                $('.partner_deposit_table').removeClass('table_content_hide');
            }
            $('.partner_content').html( profile[$(this).find('option:selected').data('array_count')].content );

            const hasAffliates = $(this).find('option:selected').attr('has-affliates');

            if(hasAffliates > 0){
                $('#partner-voucher-code-container').show();
                $('#voucher_code_partner').prop('disabled',false);
            }else{
                $('#partner-voucher-code-container').hide();
                $('#voucher_code_partner').prop('disabled',true);
            }
*/
        });

        const purchaseVia = "{{$via}}";
        
        if(purchaseVia == 'partner'){
            $('#partner_transaction_tab').trigger('click');
        }


        $('#direct_transaction_tab').on('click', function(event){
            $('.direct_deposit_table').removeClass('table_content_hide');
            $('.partner_deposit_table').addClass('table_content_hide');
            $('.partner_content').html('<center><h3>No profile to show</h3></center>');
        });

        $('#partner_transaction_tab').on('click', function(event){
            $('.direct_deposit_table').addClass('table_content_hide');
            $('.partner_deposit_table').removeClass('table_content_hide');
            $('.partner_content').html('<center><h3>No profile to show</h3></center>');
        });

        var voucherCodeTimeout = null;  
        $('#voucher_code').keyup(function(){
            $('.temp_submit').prop('disabled', true);
            if(voucherCodeTimeout != null) clearTimeout(voucherCodeTimeout);  
            voucherCodeTimeout =setTimeout(checkVoucherCode,800);  
        });

        $('#voucher_code_partner').on('keyup blur', function (e) {
            const voucher_code = $(this).val().toLowerCase();
            $(this).parent().find('.glyphicon-ok').hide();
            $(this).parent().find('.glyphicon-remove').hide();
            $('.partner_submit').prop('disabled', true);
            
            if(!!voucher_code === false){

                $(this).parent().removeClass('has-feedback has-error has-success');
                $('.partner_submit').prop('disabled', false);

            }else{

                const affliates = JSON.parse($('#partner').find('option:selected').attr('affliates'));
                const voucherFound = affliates.some( affliate => affliate.user.voucher_code.toLowerCase() == voucher_code );

                if(voucherFound){
                    $(this).parent().find('.glyphicon-remove').hide();
                    $(this).parent().find('.glyphicon-ok').show();
                    $(this).parent().removeClass('has-feedback has-error');
                    $(this).parent().addClass('has-feedback has-success');
                    $('.partner_submit').prop('disabled', false);
                }else{
                    $(this).parent().find('.glyphicon-ok').hide();
                    $(this).parent().find('.glyphicon-remove').show();
                    $(this).parent().removeClass('has-feedback has-success');
                    $(this).parent().addClass('has-feedback has-error');
                }   

            }
        })

        function checkVoucherCode(){  
            voucherCodeTimeout = null;  
            const voucher_code = $('#voucher_code').val();
            if(!!voucher_code === false){
                $('#voucher_code_container').find('.glyphicon-ok').hide();
                $('#voucher_code_container').find('.glyphicon-remove').hide();
                $('#voucher_code_container').removeClass('has-success has-feedback has-error');
                $('.temp_submit').data('progress-text',`Saving ... <i class='fa fa-circle-o-notch fa-spin fa-fw'></i>`);
                $('.temp_submit').prop('disabled', false);
                $('.temp_submit').button('reset');   
                return false;
            }
            
            $.ajax({
                url: '{{ route('deposit.check.voucher_code') }}',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                type: 'POST',
                data: {
                    voucher_code
                },
                dataType: 'json',
                // beforeSend: function(){
                //     $('.temp_submit').data('progress-text',`Checking Voucher... <i class='fa fa-circle-o-notch fa-spin fa-fw'></i>`);
                //     $('.temp_submit').prop('disabled', true);
                //     $('.temp_submit').button('progress');    
                //     $('#voucher_code_container').find('.glyphicon-ok').hide();
                //     $('#voucher_code_container').find('.glyphicon-remove').hide();
                //     $('#voucher_code_container').removeClass('has-success has-feedback has-error');
                // },
                success: function(data) {
                    const { success, fail_type, message } = data;                    

                    if(success){
                        $('.temp_submit').data('progress-text',`Saving ... <i class='fa fa-circle-o-notch fa-spin fa-fw'></i>`);
                        $('.temp_submit').prop('disabled', false);
                        $('.temp_submit').button('reset');    
                        $('#voucher_code_container').addClass('has-success has-feedback');
                        $('#voucher_code_container').find('.glyphicon-ok').show();

                    }else{
                        
                        /*
                        if(fail_type == 2){
                            const { partners } = data;
                            let errorText = `
                                    1. Select option "Purchase via Partner" <br/>
                                    2. Select one of the following partner(s): <br/>[ ${partners.map(partner =>` <strong>${partner.partner_name}</strong>`)} ] <br/>
                                    3. Enter Voucher code
                                `;

                            swal({
                                title: message,
                                text: errorText ,
                                type: "warning",
                                html: true,
                            });
                        }else{
                            $('#voucher_code_container').addClass('has-error has-feedback');
                            $('#voucher_code_container').find('.glyphicon-remove').show();
                        }
                        */

                        if(fail_type == 1){
                            $('#voucher_code_container').addClass('has-error has-feedback');
                            $('#voucher_code_container').find('.glyphicon-remove').show();
                        }

                        if(fail_type == 2){
                            $('#voucher_code_container').removeClass('has-error');
                            $('#voucher_code_container').addClass('has-success has-feedback');
                            $('.temp_submit').button('reset');
                            $('.temp_submit').prop('disabled', false);
                        }
                    }

                },
                fail: function(xhr, status, error) {
                    $('.temp_submit').data('progress-text',`Saving ... <i class='fa fa-circle-o-notch fa-spin fa-fw'></i>`);
                    $('.temp_submit').prop('disabled', false);
                    $('.temp_submit').button('reset');   
                }
            });      

        }

        function renderStepsManual(mop,transaction)
        {

            console.log('mop:', mop, 'transaction: ', transaction)
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
                    data.amount = transaction.amount;
                    data.code = transaction.code;
                    $('#deposit-steps').append(Mustache.render(container, data));
                    break;
                case 'remittance':
                    var data = {}
                    data.amount = transaction.amount;
                    data.code = transaction.code;
                    var container = $("#"+mop[0]+"-remittance").html();
                    $('#deposit-steps').append(Mustache.render(container, data));
                    break;
                case 'online':
                    var container = $("#"+mop[0]+"-desktop").html();
                    var data = {}
                    data.code = transaction.code;
                    $('#deposit-steps').append(Mustache.render(container, data));
                    break;
            }

        }

        function partnerStepsManual(mop, partner){
            switch (mop[1]) {
                case 'walkin':
                    var container = $("#"+mop[0]+"-walkin").html();
                    var data = {}
                    data.code = partner.code;
                    data.partner = partner.partner;
                    data.address = partner.address;
                    data.schedule = partner.operation;
                    // data.owner = partner.user_owner.name;
                    // data.person = partner.contact_person;
                    $('#deposit-steps').append(Mustache.render(container, data));
                    break;
            }
        }

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
                    $('#partner').append(optlist);

                    for(var i = 0; i < data.partners.length; i++){
                        var option = document.createElement('option');
                        $(option).attr('value', data.partners[i].id).text(data.partners[i].partner_name).data('array_count', i);
                        $(option).attr('has-affliates', data.partners[i].affliates.length );
                        $(option).attr('affliates', JSON.stringify(data.partners[i].affliates) );
                        
                        // $(option).data('');
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
                    if(!!partnerViaId){
                        $('#partner').trigger('change');
                        $('#voucher_code_partner').val("{{$voucher_code}}").trigger('blur');
                    }
                },
            });
        }

    })
//END PICTURE UPLOAD    
</script>
@endsection
