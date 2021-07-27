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
    td.highlight {
        font-weight: bold;
        color: blue;
    }
    #deposits_table tbody tr.highlight-discrepancy{
        background: #f1a472!important;
    }

    #users_table td{
        position: relative;
    }

    #users_table td .voucher-code{
        display  : none;
        /*position : absolute;*/
    }

    #users_table tr:hover .voucher-code{
        display     : initial;
    }
    
    #users_table td .set-badges{
        display  : none;
    }
    
    #users_table tr:hover .set-badges{
        display  : initial;
    }
    
    #users_table td .set-credits{
        position: absolute;
        right: 20px;
        top:10px;
        font-size: 15px;
        display: none;
        cursor: pointer;
    }
    
    #users_table tr:hover .set-credits{
        display  : initial;
    }

    #users_table tr:hover .dropdown_display_button{
        display     : initial;
    }

    #users_table td .dropdown_display_button {
        position: absolute;
        right: 5px;
        top:5px;
        font-size: 15px;
        display: none;
        cursor: pointer;
    }

    #users_table select {
        font-size: 12px;
    }

    #users_table .cell_edit_hint {
        color: #555;
        font-size: .8em;
        margin: .3em .2em;
        display: block;
    }

    #users_table th:eq(5){
        width:100px;
    }

    /*table{
      margin: 0 auto;
      width: 100%;
      clear: both;
      border-collapse: collapse;
      table-layout: fixed;
      word-wrap:break-word;
    }*/
    .btn-circle-micro {
      width: 19px;
      height: 19px;
      text-align: center;
      padding: 1px 0;
      font-size: 11px;
      line-height: 1.1;
      border-radius: 30px;
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
    
    .bubble_counter {
        position:relative;
    }
    .bubble_counter[data-count]:after {
        content:attr(data-count);
        position:absolute;
        top:-15px;
        right:-15px;
        font-size:.8em;
        font-weight:bold;
        background:red;
        color:black;
        width:22px;height:22px;
        text-align:center;
        line-height:22px;
        border-radius:50%;
        box-shadow:0 0 1px #333;
    }
    .main-menu-header {
        list-style-type: none;
        margin: 0;
        padding: 0;
        overflow: hidden;
        background-color: #333;
    }

    .main-menu-header li {
        float: left;
    }

    .main-menu-header li a, .sub-menu-btn {
        display: inline-block;
        color: white;
        text-align: center;
        padding: 14px 16px;
        text-decoration: none;
        font-size: 20px;
        font-weight: 700;
        text-transform: uppercase;
        line-height: 35px;
    }

    .main-menu-header li a:hover, .sub-menus:hover .sub-menu-btn {
        background-color: #717171;
    }

    .main-menu-header li.sub-menus {
        display: inline-block;
    }

    .main-menu-header li a:active, .sub-menus:active{
        background-color: #717171;
    }

    .sub-menus-content {
        display: none;
        position: absolute;
        background-color: #f9f9f9;
        min-width: 160px;
        box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
        z-index: 1;
    }

    .sub-menus .sub-menus-content a {
        color: black;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
        text-align: left;
    }

    .sub-menus-content a:hover {background-color: #f1f1f1}

    .sub-menus:hover .sub-menus-content {
        display: block;
    }
    #partner_table .edit-button{
        font-size: 22px;
        position: relative;
        top: 3px;
        left: 5px;
        cursor: pointer;
        display: none;
    }
    #partner_table tr:hover .edit-button{
        display: initial;
    }
   .admin_header_active{
        background-color: #717171;
    }
    .open-update-info, .save-partner-info{
        display: none;
    }
    .form-twin-column{
        width: 48%;
        margin-bottom: 10px;
    }
    #update-info{
        max-height: 600px;
        display: none;
        overflow-y: auto;
        overflow-x: hidden;
    }
    .text-wrap{
        cursor: pointer;
        font-weight: bold;
    }
    .ticket_thread_column{
        max-height: 600px;
        overflow-y: auto;
    }
    .thread_box{
        padding: 10px; 
        background-color: #d8d8d8; 
        margin: 5px 0px; 
        word-wrap: break-word;
        border-radius: 10px;
    }
    .thread_box_owner{
        padding: 10px; 
        background-color: #efefef; 
        margin: 5px 0px; 
        word-wrap: break-word;
        border-radius: 10px;
    }
    .reply_box_thread{
        margin: 8px 25px;
    }
    .thread_img_square{
        height: 50px; 
        float: left; 
        margin-bottom: 10px;
    }
    .thread_icon{
        width: 65px; 
        height: 50px; 
        font-size: 46px; 
        padding: 2px 10px; 
        display: inline-block;
    }
    .thread_info_panel{
        width: calc(100% - 65px); 
        height: 50px; 
        float: left; 
        margin-bottom: 10px;
    }
    .thread_user_name{
        font-size: 18px; 
        font-weight: bold; 
        margin: 5px 0px 0px;
    }
    .thread_content{
        padding: 0px 15px;
    }
    .clickable{
        cursor: pointer;   
    }

    .panel-heading span {
        margin-top: -20px;
        font-size: 15px;
    }
    fieldset.scheduler-border {
        border: 1px groove #ddd !important;
        padding: 0 1.4em 1.4em 1.4em !important;
        margin: 0 0 1.5em 0 !important;
        -webkit-box-shadow:  0px 0px 0px 0px #000;
                box-shadow:  0px 0px 0px 0px #000;
    }

    legend.scheduler-border {
        width:inherit; /* Or auto */
        padding:0 10px; /* To give a bit of padding on the left and right */
        border-bottom:none;
    }

    #deposits_table .btn-sm, #cashout_table .btn-sm{
        margin-bottom: 2px;
    }

    .data-tables-btn{
        margin-left: 10px;
    }

    .ban-user{
        margin-left: 5px;
    }

    .message-user{
        margin-left: 5px;
    }

    .message-user-textarea{
        max-width: 100%;
    }

    .float-right {
        float: right;
    }


    .transaction-completed,
    .transaction-1{
        background-color: #aef1ae;
        text-align: center;
        vertical-align: middle !important;
        font-weight: 600;
        color: green;
    }

    .transaction-rejected,
    .transaction-2{
        background-color: #ffbdbc;
        text-align: center;
        vertical-align: middle !important;
        font-weight: 600;  
        color: red;      
    }    

    .transaction-processing,
    .transaction-0{
        text-align: center;
        vertical-align: middle !important;
        font-weight: 600;
        color: #f39c12;
        background-color: #ffefd1;
    }

    .data-tables-select{
        padding: 3px;
        margin-left: 8px;
    }  

    .select-user-log {
        width: 150px;
        height: 28px;
        border-radius: 3px;
    }
</style>


    <!-- Modal for adding item -->
    <style type="text/css">
        .btn-file {
            position: relative;
            overflow: hidden;
        }
        .btn-file input[type=file] {
            position: absolute;
            top: 0;
            right: 0;
            min-width: 100%;
            min-height: 100%;
            font-size: 100px;
            text-align: right;
            filter: alpha(opacity=0);
            opacity: 0;
            outline: none;
            background: white;
            cursor: inherit;
            display: block;
        }

        #item-image-upload{
            width: 50%;
            margin: 25px 130px;
        }
    </style>
<link rel="stylesheet" href="{{ asset('bower_components/bootstrap-sweetalert/dist/sweetalert.css') }}">
<link rel="stylesheet" href="{{ asset('css/fileinput.css') }}">
<link rel="stylesheet" href="{{ asset('css/datatables.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/viewer.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/select2/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/bootstrap-multiselect.css') }}">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.1/css/responsive.dataTables.min.css">
<link rel="stylesheet" href="{{ asset('/bower_components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css') }}"/>
@endsection

@section('content')

<div id="app-admin" class="main-container dark-grey" style="width:100%;">
    
    <div class="m-container1" style="width: 98% !important; margin-bottom: 0px;">
        <ul class="main-menu-header">
            <li role="presentation" class="active"><a href="#user-administration" class="admin_header_active" id="user" aria-controls="user-administration" role="tab" data-toggle="tab">User Administration</a></li>
            <li role="presentation"><a href="#partner-administration" id="partner" aria-controls="partner-administration" role="tab" data-toggle="tab">Partner Administration</a></li>
            <li role="presentation">
                <a href="#market-place" id="market" aria-controls="market-place" role="tab" data-toggle="tab">Market Manager</a>
            </li>
            <li role="presentation"><a href="#site-settings" id="settings" aria-controls="site-settings" role="tab" data-toggle="tab">Site Settings</a></li>
            <!-- <li role="presentation" class="sub-menus">
                <a href="javascript:void(0)" class="sub-menu-btn">Dropdown</a>
                <div class="sub-menus-content">
                    <a href="#" aria-controls="home" role="tab" data-toggle="tab">Link 1</a>
                    <a href="#" aria-controls="home" role="tab" data-toggle="tab">Link 2</a>
                    <a href="#" aria-controls="home" role="tab" data-toggle="tab">Link 3</a>
                </div>
            </li> -->
        </ul>
    </div>
    
    <div class="m-container1" style="width: 98% !important; display: none;" id="site-settings">
        <div class="main-ct">
            <div class="title2">Site Settings</div>
            <div class="clearfix"></div>
            <div class="blk-1">
                <div class="col-md-12">
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active">
                            <a href="#site-config" aria-controls="site-config" role="tab" data-toggle="tab">2EZ Configuration</a>
                        </li>
                        <li role="presentation">
                            <a href="#site-tos" aria-controls="site-tos" role="tab" data-toggle="tab">Terms &amp; Conditions Editor</a>
                        </li>

                        @if( in_array(Auth()->id(), getSuperAdminIds() ))
                            <li role="presentation">
                                <a href="#site-account-manager" aria-controls="site-account-manager" role="tab" data-toggle="tab" id="site-account-manager-link">Account Management</a>
                            </li>
                        @endif
                    </ul>
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade in" id="site-account-manager">
                            {{-- <add-bank-account></add-bank-account>
                            <bank-account-lists></bank-account-lists> --}}
                            <manage-bank-accounts></manage-bank-accounts>
                        </div>
                        <div role="tabpanel" class="tab-pane fade in active" id="site-config">
                            <form id="site_settings_form">
                                <div class="col-md-3">
                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">BDO</legend>
                                        <div class="form-group">
                                            <label for="bdo-account-name">Select BDO Account:</label>
                                            <select id="bdo-account-name-select" name="bdo-account-name-select" data-type="bdo" class="form-control account-name-select" >
                                                @foreach($bankAccounts['bdo'] as $key => $account)
                                                    <option value="{{$key}}" {{ $account[0]->status == 1 ? 'selected' : ''}}> {{ $account[0]->value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="bdo-account-name">Account Name:</label>
                                            <input id="bdo-account-name" class="form-control" type="text" value="{{$settings['bdo-account-name']}}" readonly/>
                                            <label class="error-label">Invalid Input</label>
                                        </div>
                                        <div class="form-group">
                                            <label for="bdo-account-number">Account Number:</label>
                                            <input id="bdo-account-number" class="form-control" type="text" value="{{$settings['bdo-account-number']}}" readonly/>
                                            <label class="error-label">Invalid Input</label>
                                        </div>
                                    </fieldset>
                                </div>
                                <div class="col-md-3">
                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">BPI</legend>
                                        <div class="form-group">
                                            <label for="bpi-account-name">Select BPI Account:</label>
                                            <select id="bpi-account-name-select" name="bpi-account-name-select" data-type="bpi" class="form-control account-name-select" >
                                                @foreach($bankAccounts['bpi'] as $key => $account)
                                                    <option value="{{$key}}" {{ $account[0]->status == 1 ? 'selected' : ''}}> {{ $account[0]->value }}</option>
                                                @endforeach
                                            </select>
                                        </div>                                        
                                        <div class="form-group">
                                            <label for="bpi-account-name">Account Name:</label>
                                            <input id="bpi-account-name" class="form-control" type="text" value="{{$settings['bpi-account-name']}}" readonly/>
                                            <label class="error-label">Invalid Input</label>
                                        </div>
                                        <div class="form-group">
                                            <label for="bpi-account-number">Account Number:</label>
                                            <input id="bpi-account-number" class="form-control" type="text" value="{{$settings['bpi-account-number']}}" readonly/>
                                            <label class="error-label">Invalid Input</label>
                                        </div>
                                    </fieldset>
                                </div>
                                <div class="col-md-3">
                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">MetroBank</legend>
                                        <div class="form-group">
                                            <label for="metro-account-name">Select MetroBank Account:</label>
                                            <select id="metro-account-name-select" name="metro-account-name-select" data-type="metro" class="form-control account-name-select" >
                                                @foreach($bankAccounts['metro'] as $key => $account)
                                                    <option value="{{$key}}" {{ $account[0]->status == 1 ? 'selected' : ''}}> {{ $account[0]->value }}</option>
                                                @endforeach
                                            </select>
                                        </div>                                         
                                        <div class="form-group">
                                            <label for="metro-account-name">Account Name:</label>
                                            <input id="metro-account-name" class="form-control" type="text" value="{{$settings['metro-account-name']}}" readonly/>
                                            <label class="error-label">Invalid Input</label>
                                        </div>
                                        <div class="form-group">
                                            <label for="metro-account-number">Account Number:</label>
                                            <input id="metro-account-number" class="form-control" type="text" value="{{$settings['metro-account-number']}}" readonly/>
                                            <label class="error-label">Invalid Input</label>
                                        </div>
                                    </fieldset>
                                </div>
                                <div class="col-md-3">
                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">SecurityBank</legend>
                                        <div class="form-group">
                                            <label for="security-account-name">Select SecurityBank Account:</label>
                                            <select id="security-account-name-select" name="security-account-name-select" data-type="security" class="form-control account-name-select" >
                                                @foreach($bankAccounts['security'] as $key => $account)
                                                    <option value="{{$key}}" {{ $account[0]->status == 1 ? 'selected' : ''}}> {{ $account[0]->value }}</option>
                                                @endforeach
                                            </select>
                                        </div>                                           
                                        <div class="form-group">
                                            <label for="security-account-name">Account Name:</label>
                                            <input id="security-account-name" class="form-control" type="text" value="{{$settings['security-account-name']}}" readonly/>
                                            <label class="error-label">Invalid Input</label>
                                        </div>
                                        <div class="form-group">
                                            <label for="security-account-number">Account Number:</label>
                                            <input id="security-account-number" class="form-control" type="text" value="{{$settings['security-account-number']}}" readonly/>
                                            <label class="error-label">Invalid Input</label>
                                        </div>
                                    </fieldset>
                                </div>
                                {{-- <div class="col-md-6">
                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">Remittances</legend>
                                        <div class="form-group">
                                            <label for="remittance-name">Receiver Name:</label>
                                            <input id="remittance-name" class="form-control" type="text" name="remittance-name" value="{{$settings['remittance-name']}}" readonly/>
                                            <label class="error-label">Invalid Input</label>
                                        </div>
                                        <div class="form-group">
                                            <label for="remittance-number">Receiver Number/Contact:</label>
                                            <input id="remittance-number" class="form-control" type="text" name="remittance-number" value="{{$settings['remittance-number']}}" readonly/>
                                            <label class="error-label">Invalid Input</label>
                                        </div>
                                        <div class="form-group">
                                            <label for="remittance-location">Receiver Location:</label>
                                            <input id="remittance-location" class="form-control" type="text" name="remittance-location" value="{{$settings['remittance-location']}}" />
                                            <label class="error-label">Invalid Input</label>
                                        </div>
                                    </fieldset>
                                </div>
                                <div class="col-md-6">
                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">Coins.PH</legend>
                                        <div class="form-group">
                                            <label for="wallet-address">Wallet Address:</label>
                                            <input id="wallet-address" class="form-control" type="text" name="coins-wallet-address" value="{{$settings['coins-wallet-address']}}"/>
                                            <label class="error-label">Invalid Input</label>
                                        </div>
                                    </fieldset>
                                </div> --}}
                            </form>
                            <div class="col-md-12">
                                <p>Last updated: </p>
                                <button id="save_settings_btn" class="btn btn-primary" data-progress-text="Saving ... <i class='fa fa-circle-o-notch fa-spin fa-fw'></i>">Submit</button>
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane fade in" id="site-tos">
                            <br/>
                            <p class="last-modified-text">Last Modified: {{ $tos->updated_at->toDayDateTimeString() }} by {{ $tos->editor->name }}<br/>
                                URL: <a target="_new" href="{{url('/termsandconditions')}}">{{url('/termsandconditions')}}</a></p>
                            <form id="tos_form" autocomplete="off" action="" method="POST" role="form">
                                <div class="form-group">
                                    <label>T&amp;C Content</label>
                                    <textarea class="form-control" id="tos_content_ckedit">
                                        {!! $tos->contents !!}
                                    </textarea>
                                    <label class="error-label">Invalid Input</label>
                                </div>
                            </form>
                            <button id="save-tos-btn" type="button" class="btn btn-primary" data-progress-text="Saving ... <span class='glyphicon glyphicon-refresh fa-spin'></span>">Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="m-container1" style="width: 98% !important; display: none;" id="market-place">
        <div class="main-ct">
            <div class="title2">Market Options and Purchases</div>
            <div class="clearfix"></div>
            <div class="blk-1">
                <div class="col-md-12">
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active">
                            <a href="#product-options" aria-controls="product-options" role="tab" data-toggle="tab">Items</a>
                        </li>
                        <li role="presentation">
                            <a href="#product-purchase" aria-controls="product-purchase" role="tab" data-toggle="tab">Product Purchased</a>
                        </li>
                        <li role="presentation">
                            <a href="#gift-codes" aria-controls="gift-codes" role="tab" data-toggle="tab" id="gift-codes-market">Gift Codes</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade in active" id="product-options">
                            <div class="col-md-4" style="margin: 0px 0px 10px -15px;">
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add-market-item">
                                  <i class="fa fa-plus"></i> Add Item
                                </button>                                
                            </div>
                            <div class="tab-content" style="margin-top: 20px">   
                                <table id="market_item_list" class="table table-striped" width="100%">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Item Name</th>
                                            <th>Item Price</th>
                                            <th>Details</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>                    
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane fade in" id="product-purchase">
                            <div class="tab-content" style="margin-top: 20px">   
                                <table id="" class="table table-striped" width="100%">
                                    <thead>
                                        <tr>
                                            <th>User</th>
                                            <th>Purchased Item</th>
                                            <th>Price</th>
                                            <th>Address</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>                    
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane fade in" id="gift-codes">
                                
                            <!-- End modal for adding item -->
                            <br />
                            <create-gift-codes></create-gift-codes>
                            <br/>
                            <gift-codes-list></gift-codes-list>
                            <br/>
                            <create-gift-codes></create-gift-codes>
                            <br/>
                           
                            <!-- vue component for creating/generating gift codes -->

                        </div>                        
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="add-market-item" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <button type="button" class="close" 
                       data-dismiss="modal">
                           <span aria-hidden="true">&times;</span>
                           <span class="sr-only">Close</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel">
                        Add Product
                    </h4>
                </div>
                
                <!-- Modal Body -->
                <div class="modal-body">                    
                    <form role="form" method="POST" enctype="multipart/form-data" id="market-product-form">
                      <div class="form-group">
                        <div class="row">
                        <div class="col-md-7">
                          <label for="market_item_name">Product Name</label>
                          <input type="text" class="form-control" id="market_item_name" name="market_item_name">
                        </div>
                        <div class="col-md-5">
                          <label for="market_item_price">Product Price</label>
                          <input type="number" class="form-control" id="market_item_price" name="market_item_price">
                        </div>
                        </div>
                      </div>
                      <div class="form-group">
                          <label for="market_item_desc">Product Details</label>
                          <textarea class="form-control rounded-0" id="market_item_desc" rows="3" name="market_item_desc"></textarea>
                      </div>
                      <div class="form-group">
                            <label>Upload Image</label>
                              <div class="input-group">
                                  <span class="input-group-btn">
                                      <span class="btn btn-default btn-file">
                                          Browse… <input type="file" id="imgInp" accept="image/*" name="market_item_image">
                                      </span>
                                  </span>
                                  <input type="text" class="form-control" readonly>
                              </div>
                              <div class="alert alert-danger" role="alert">
                                Image should be 270x245
                              </div>
                            <img id='item-image-upload'/>
                      </div>
                    </form>   
                </div>                
                <!-- Modal Footer -->
                <div class="modal-footer">                    
                    <button type="button" class="btn btn-success" id="upload-market-item">Save</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
    
    <div class="m-container1" style="width: 98% !important;" id="user-administration">
	<div class="main-ct">

            <div class="title2">Administration</div>
            <div class="clearfix"></div>
            <div class="blk-1">
                <div class="col-md-12">

                      <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#dasboard" aria-controls="dasboard" role="tab" data-toggle="tab">Dashboard</a></li>
                        <li role="presentation"><a href="#deposits" aria-controls="deposits" role="tab" data-toggle="tab">Deposits</a></li>
                        <li role="presentation"><a href="#cashout" aria-controls="cashout" role="tab" data-toggle="tab">Cash Outs</a></li>
                        <li role="presentation"><a href="#bettings" aria-controls="bettings" role="tab" data-toggle="tab">Bettings</a></li>
                        <li role="presentation"><a href="#donations" aria-controls="donations" role="tab" data-toggle="tab">Donations</a></li>
                        <li role="presentation"><a href="#commissions" aria-controls="commissions" role="tab" data-toggle="tab">Commissions</a></li>
                        <li role="presentation"><a href="#commissions_partners" aria-controls="commissions_partners" role="tab" data-toggle="tab">Commissions (vP)</a></li>
                        <li role="presentation"><a href="#commissions_bets" aria-controls="commissions_bets" role="tab" data-toggle="tab">Commissions from Bets</a></li>
                        <li role="presentation"><a href="#badges" aria-controls="badges" role="tab" data-toggle="tab">Badges</a></li>
                        <li role="presentation"><a href="#usermanagement" aria-controls="usermanagement" role="tab" data-toggle="tab">User Management</a></li>
                        <li role="presentation"><a href="#rewards" aria-controls="rewards" role="tab" data-toggle="tab">Rewards</a></li>
                        <li role="presentation"><a href="#bugs" aria-controls="bugs" role="tab" data-toggle="tab"><span id="bug_ctr" class="bubble_counter">Reported Bugs</span></a></li>
                        <li role="presentation"><a href="#promos" aria-controls="promos" role="tab" data-toggle="tab"><span id="promo_ctr" class="bubble_counter">Promotions</span></a></li>
                        <li role="presentation"><a href="#referrals" aria-controls="referrals" role="tab" data-toggle="tab">Referrals</a></li>
                        <li role="presentation"><a href="#payouts" aria-controls="payouts" role="tab" data-toggle="tab">Payouts</a></li>
                        <li role="presentation"><a href="#rebates" aria-controls="rebates" role="tab" data-toggle="tab">Rebates</a></li>
                        <li role="presentation"><a href="#earnings" aria-controls="earnings" role="tab" data-toggle="tab">Earnings</a></li>
                    </ul>

                      <!-- Tab panes -->
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade in active" id="dasboard">
                            <div class="tab-content" style="margin-top: 20px">
                                <h3>Fees Collected</h3>
                                <table id="fees_table" class="table table-striped" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Type</th>
                                            <th>User/Match/Tournament ID</th>
                                            <th>Details</th>
                                            <th>Amount Earned</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                                <h3>Overview</h3>
                                <div class="row" style="margin-bottom:25px;" id="loading-text"><div class="col-md-6"></div>Loading...</div>
                                <div class="row" style="margin-bottom:25px;">
                                    <div class="col-md-6" id="overview-circulating"></div>
                                    <div class="col-md-6" id="overview-available"></div>
                                </div>
                                <div class="row" style="margin-bottom:25px;">
                                    <div class="col-md-6" id="overview-betted"></div>
                                    <div class="col-md-6" id="overview-fees"></div>
                                </div>
                                <div class="row" style="margin-bottom:25px;">
                                    <div class="col-md-6" id="overview-cashouts"></div>
                                    <div class="col-md-6" id="overview-deposits"></div>
                                    <div class="col-md-6" id="overview-partners"></div>
                                </div>
                                <div class="row" style="margin-bottom:25px;">
                                    <div class="col-md-12" id="overview-users_who"></div>
                                </div>
    
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane fade in" id="deposits">
                            <div class="tab-content" style="margin-top: 20px">
                                <table id="deposits_table" class="table table-striped" width="100%">
                                    <thead>
                                        <tr>
                                            <th>O.R. #</th>
                                            <th>Request Date</th>
                                            <th>Apprvd/Rjctd Date</th>
                                            <th>User ID</th>
                                            <th>User</th>
                                            <th>Amount</th>
                                            <th>MOP</th>
                                            <th>Status</th>
                                            <th>Details</th>
                                            <th>Receipt</th>
                                            <th>Last updated by</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    
                                </table>
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane fade in" id="cashout">
                            <div class="tab-content" style="margin-top: 20px">
                                <table id="cashout_table" class="table table-striped" width="100%">
                                    <thead>
                                        <tr>
                                            <th>O.R. #</th>
                                            <th>Request Date</th>
                                            <th>Apprvd/Rjctd Date</th>
                                            <th>User ID</th>
                                            <th>User</th>
                                            <th>Amount</th>
                                            <th>MOP</th>
                                            <th>Details</th>
                                            <th>Donation</th>
                                            <th>Status</th>
                                            <th>Receipt</th>
                                            <th>Notes</th>
                                            <th>Last updated by</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    
                                </table>
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane fade in" id="bettings">
                            <div class="tab-content" style="margin-top: 20px">
                                <table id="bettings_table" class="table table-striped" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>User</th>
                                            <th>Team Selected</th>
                                            <th>Tournament</th>
                                            <th>Match</th>
                                            <th>Winner</th>
                                            <th>Total Bet</th>
                                            <th>Status</th>
                                            <th>Profit/Loss</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div role="tabpanel" class="tab-pane fade in" id="donations">
                            <div class="tab-content" style="margin-top: 20px">
                                <table id="donations_table" class="table table-striped" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>User</th>
                                            <th>Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div role="tabpanel" class="tab-pane fade in" id="commissions">
                            <div class="tab-content" style="margin-top: 20px">
                                <table id="commissions_table" class="table table-striped" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Belongs To</th>
                                            <th>Transaction Code</th>
                                            <th>By</th>
                                            <th>Ammount Deposited</th>
                                            <th>Amount Earned</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>


                        
                        <div role="tabpanel" class="tab-pane fade in" id="commissions_partners">
                            <div class="tab-content" style="margin-top: 20px">
                                <table id="commissions_partners_table" class="table table-striped" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Belongs To</th>
                                            <th>Transaction Code</th>
                                            <th>By</th>
                                            <th>Ammount Deposited</th>
                                            <th>Amount Earned</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>


                        <div role="tabpanel" class="tab-pane fade in" id="commissions_bets">
                            <div class="tab-content" style="margin-top: 20px">
                                <table id="commissions_bets_table" class="table table-striped" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Match Date Settled</th>
                                            <th>Belongs To</th>
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

                        <div role="tabpanel" class="tab-pane fade in" id="badges">
                            <div class="tab-content" style="margin-top: 20px">
                                <table id="badges_table" class="table table-striped" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Badge</th>
                                            <th>Description</th>
                                            <th>Reward Credits</th>
                                            <th># of Assigned Users</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                                <button class="btn btn-primary showCreateBadgesModalBtn" data-toggle="modal" data-target="#createBadgesModal">Create Badges</button>
                            </div>
                        </div>

                        <div role="tabpanel" class="tab-pane fade in" id="usermanagement">
                            <div class="tab-content" style="margin-top: 20px">
                                <table id="users_table" class="table table-striped" width="100%">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Badges</th>
                                            <th>Provider</th>
                                            <th>Credits</th>
                                            <th>Voucher Code</th>
                                            <th>Roles</th>
                                            <th>Payables</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <div role="tabpanel" class="tab-pane fade in" id="rewards">
                            <div class="tab-content" style="margin-top: 20px">
                                <table id="rewards_table" class="table table-striped" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>User</th>
                                            <th>Credits Awarded</th>
                                            <th>Reward Type</th>
                                            <th>Reasons</th>
                                            <th>Added By</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane fade in" id="bugs">
                            <div class="tab-content" style="margin-top: 20px">
                                <table id="bugs_table" class="table table-striped" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Name</th>
                                            <th>Subject</th>
                                            <th>Comment</th>
                                            <th>Image</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane fade in" id="promos" >
                            <div class="tab-content" style="margin-top: 20px">
                                <table id="promo_table" class="table table-striped" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Name</th>
                                            <th>Comment</th>
                                            <th>Admin Comment</th>
                                            <th>Link</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div role="tabpanel" class="tab-pane fade in" id="referrals">
                            <div class="tab-content" style="margin-top: 20px">
                                <table id="referrals_table" class="table table-striped" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Code</th>
                                            <th>Belong to</th>
                                            <th>Refered User</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane fade in" id="payouts">
                            <div class="tab-content" style="margin-top: 20px;">
                                <table id="payout_table" class="table table-striped" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>O.R. #</th>
                                            <th>User</th>
                                            <th>Amount</th>
                                            <th>Receipt</th>
                                            <th>Last Updated by</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>

                        <div role="tabpanel" class="tab-pane fade in" id="rebates">
                            <div class="tab-content" style="margin-top: 20px;">
                                <table id="rebates_table" class="table table-striped" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>User</th>
                                            <th>Transaction Code</th>
                                            <th>Amount Deposited</th>
                                            <th>Amount Earned</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>

                        <div role="tabpanel" class="tab-pane fade in" id="referrals">
                            <div class="tab-content" style="margin-top: 20px">
                                <table id="referrals_table" class="table table-striped" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Code</th>
                                            <th>Belong to</th>
                                            <th>Refered User</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div role="tabpanel" class="tab-pane fade in" id="earnings">
                            <div class="tab-content" style="margin-top: 20px">
                                <table id="earnings_table" class="table table-striped" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Match ID</th>
                                            <th>Name</th>
                                            <th>Teams</th>
                                            <th>League</th>
                                            <th>Match Winner</th>
                                            <th>Status</th>
                                            <th>Earnings</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="m-container1" style="width: 98% !important; display: none;" id="partner-administration">
        <div class="main-ct">
            <div class="title2">Partner Administration</div>
            <div class="clearfix"></div>
            <div class="blk-1">
                <div class="col-md-12">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#partner-deposit" aria-controls="partner-deposit" role="tab" data-toggle="tab">User Deposits (vP)</a></li>
                        <li role="presentation"><a href="#partner-cashout" aria-controls="partner-cashout" role="tab" data-toggle="tab">User Cashouts (vP)</a></li>
                        <li role="presentation"><a href="#partner-earning" aria-controls="partner-earning" role="tab" data-toggle="tab">Payouts</a></li>
                        <li role="presentation"><a href="#partner-transaction" aria-controls="partner-transaction" role="tab" data-toggle="tab"><span id="partner_buy" class="bubble_counter">Partner-2ez Transactions</span></a></li>
                        <li role="presentation"><a href="#partner-approval" aria-controls="partner-approval" role="tab" data-toggle="tab">Partnership Approval</a></li>
                        <li role="presentation"><a href="#partner-manage" aria-controls="partner-manage" role="tab" data-toggle="tab">Partner Management</a></li>
                    </ul>
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade in active" id="partner-deposit">
                            <div class="tab-content" style="margin-top: 20px">
                                <table id="partner_user_deposit_table" class="table table-striped" width="100%">
                                    <thead>
                                        <tr>
                                            <th>O.R. #</th>
                                            <th>User ID</th>
                                            <th>User</th>
                                            <th>Partner for Transaction</th>
                                            <th>Request Date</th>
                                            <th>Apprvd/Rjctd Date</th>
                                            <th>Amount</th>
                                            <th>MOP</th>
                                            <th>Status</th>
                                            <th>Details</th>
                                            <th>Receipt</th>
                                            <th>Last updated by</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>   
                                </table>
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane fade in" id="partner-cashout">
                            <div class="tab-content" style="margin-top: 20px">
                                <table id="partner_user_cashout_table" class="table table-striped" width="100%">
                                    <thead>
                                        <tr>
                                            <th>O.R. #</th>
                                            <th>User ID</th>
                                            <th>User</th>
                                            <th>Partner for Transaction</th>
                                            <th>Request Date</th>
                                            <th>Apprvd/Rjctd Date</th>
                                            <th>Amount</th>
                                            <th>Donation</th>
                                            <th>Earnings</th>
                                            <th>Status</th>
                                            <th>Details</th>
                                            <th>Receipt</th>
                                            <th>Last updated by</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>  
                                </table>
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane fade in" id="partner-earning">
                            <div class="tab-content" style="margin-top: 20px;">
                                <table id="partner_payout_table" class="table table-striped" width="100%">
                                    <thead>
                                        <tr>
                                            <th>O.R. #</th>
                                            <th>Partner</th>
                                            <th>User</th>
                                            <th>Date</th>
                                            <th>Amount</th>
                                            <th>Details</th>
                                            <th>Receipt</th>
                                            <th>Message</th>
                                            <th>Last Updated by</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                            {{-- <div class="tab-content" style="margin-top: 20px;">
                                <center><h3>Coming Soon - Partner Payouts Table</h3></center>
                            </div> --}}
                        </div>
                        <div role="tabpanel" class="tab-pane fade in" id="partner-transaction">
                            <div class="tab-content" style="margin-top: 20px">
                                <table id="partner_admin_transactions_table" class="table table-striped" width="100%">
                                    <thead>
                                        <tr>
                                            <th>O.R. #</th>
                                            <th>Type</th>
                                            <th>Request Date</th>
                                            <th>Apprvd/Rjctd Date</th>
                                            <th>Partner</th>
                                            <th>User</th>
                                            <th>Amount</th>
                                            <th>MOP</th>
                                            <th>Status</th>
                                            <th>Details</th>
                                            <th>Receipt</th>
                                            <th>Last updated by</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane fade in" id="partner-approval">
                            <div class="tab-content" style="margin-top: 20px">
                                <table id="partner_approval_table" class="table table-striped" width="100%">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Partner Name</th>
                                            <th>Owner</th>
                                            <th>Mobile Number</th>
                                            <th>Contact Number</th>
                                            <th>Status</th>
                                            <th>Payout to Info</th>
                                            <th>Other Details</th>
                                            <th>Last updated by</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane fade in" id="partner-manage">
                            <div class="tab-content" style="margin-top: 20px">
                                <table id="partner_table" class="table table-striped" width="100%">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Partner Name</th>
                                            <th>Owner</th>
                                            <th>Wallet Balance</th>
                                            <th>Earnings</th>
                                            <th>Contact Number(s)</th>
                                            <th>Affliates</th>
                                            <th>Sub-users</th>
                                            <th>Sub Agents</th>
                                            <th>Payout to Info</th>
                                            <th>Partner Info</th>
                                            <th>Last updated by</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    

    <!-- Modal viewReceipt-->
    <div class="modal fade" id="viewReceipt" tabindex="-1" role="dialog" aria-labelledby="viewReceipt" aria-hidden="true" style="z-index: 3000">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Receipt</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body" style="min-height: 500px !important;">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
    <!-- Modal modalDiscrepancy-->
    <div class="modal fade" id="modalDiscrepancy" tabindex="-1" role="dialog" aria-labelledby="modalDiscrepancy">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="exampleModalLabel">Discrepancy BC Code: <span id="bc-code" style="font-weight: bold;color: #820804"></span></h4>
          </div>
          <div class="modal-body" style="max-height: 730px;overflow-y: scroll;">
            <form id="discrepancy_form" enctype="multipart/form-data" autocomplete="off" action="" method="POST" role="form">
              <div class="form-group">
                <label for="discrepancy-amount" class="control-label">Amount:</label>
                <input type="text" class="form-control" id="discrepancy-amount" name="amount">
              </div>
              <div class="form-group add_rebate" style="display: none">
                <label for="add_rebate" class="control-label"> <input id="add_rebate" name="add_rebate" type="checkbox"> Add Rebate</label>
              </div>

                <div class="form-group">
                    <label for="mop">Mode of Payment</label>
                    <select class="form-control mop" name="provider" id="provider" style="width: 100%" data-placeholder="Select mode of payment">
                        <option></option>
                        <optgroup label="Bank Deposit">
                            <option value="BDO-deposit">BDO</option>
                            <option value="BPI-deposit">BPI</option>
                            <option value="Chinabank-deposit" disabled>Chinabank - coming soon</option>
                            <option value="Eastwest-deposit" disabled>Eastwest</option>
                            <option value="Metrobank-deposit">Metrobank</option>
                            <option value="PNB-deposit" disabled>PNB - coming soon</option>
                            <option value="PSBank-deposit" disabled>PSBank - coming soon</option>
                            <option value="rcbc-depositc" disabled>RCBC Savings Bank - coming soon</option>
                            <option value="securitybank-deposit" disabled>Security Bank Savings - coming soon</option>
                            <option value="Unionbank-deposit" disabled>Unionbank - coming soon</option>
                        </optgroup>
                        <optgroup label="Money Remittance">
                            <option value="cebuana-remittance">Cebuana Lhuiller Pera Padala</option>
                            <option value="mlhuiller-remittance" disabled>M Lhuillier Kwarta Padala - coming soon</option>
                            <option value="lbc-remittance">LBC Peso Padala</option>
                            <option value="palawan-remittance">Palawan Express</option>
                            <option value="western-remittance" disabled>Western Union - coming soon</option>
                            <option value="rd-remittance" disabled>RD Pawnshop - coming soon</option>
                        </optgroup>
                        <optgroup label="Mobile Money">
                            <option value="gcash-mobilemoney" disabled>G Cash - coming soon</option>
                            <option value="buycredits-mobilemoney" disabled>Buy Credits-Online - coming soon</option>
                        </optgroup>
                        <optgroup label="Online Bank Transfer">
                            <option value="BDO-online">BDO</option>
                            <option value="BPI-online">BPI</option>
                            <option value="Chinabank-online" disabled>Chinabank - coming soon</option>
                            <option value="Eastwest-online" disabled>Eastwest - coming soon</option>
                            <option value="Metrobank-online">Metrobank</option>
                            <option value="PNB-online" disabled>PNB - coming soon</option>
                            <option value="PSBank-online" disabled>PSBank - coming soon</option>
                            <option value="securitybank-online" disabled>Security Bank Savings - coming soon</option>
                            <option value="Unionbank-online" disabled>Unionbank - coming soon</option>
                        </optgroup>
                    </select>
                </div>

                <div class="form-group image">
                    <input id="disc-image" name="image" accept="image/*" class="file-loading" type="file">
                     <label class="error-label">The image field is required</label>
                </div>

              <div class="form-group message">
                <label for="message-text" class="control-label">Message:</label>
                <textarea class="form-control" id="disc-message" name="message"></textarea>
                <label class="error-label">Invalid Input</label>
              </div>

                <div class="form-group">
                    <label for="">History</label>
                    <table id="discrepancy_table" class="table table-striped" width="100%">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Receipt</th>
                                <th>MOP</th>
                                <th>Message</th>
                                <th>Last updated by</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>

                </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary submit_discrepancy" data-progress-text="Saving ... <i class='fa fa-circle-o-notch fa-spin fa-fw'></i>" autocomplete="off">Submit</button>
          </div>
        </div>
      </div>
    </div>
    <!-- Modal modal-mark-as-processed-->
    <div class="modal fade" id="modal-mark-as-processed" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="exampleModalLabel">Mark as Processed</h4>
          </div>
          <div class="modal-body">
            <form id="mark_as_processed_form" enctype="multipart/form-data" autocomplete="off" action="" method="POST" role="form">
                <div class="form-group photo">
                    <input id="image" name="photo" accept="image/*" class="file-loading" type="file">
                    <label class="error-label">Invalid Input</label>
                </div>

              <div class="form-group message">
                <label for="message-text" class="control-label">Message:</label>
                <textarea class="form-control" id="message" name="message"></textarea>
                <label class="error-label">Invalid Input</label>
              </div>

              <div class="form-group waive-fee">
                <label for="waive-fee" class="control-label"> <input id="waive-fee" name="waive-fee" type="checkbox"> Waive Fee</label>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary submit_mark_as_processed" data-progress-text="Saving ... <i class='fa fa-circle-o-notch fa-spin fa-fw'></i>" autocomplete="off">Submit</button>
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
          <div class="modal-body" style="min-height: 500px !important;">
            <div class="details-list">
                <ul class="list-unstyled" id="deposit-steps"></ul>
            </div>
            
            <div id="update-info">
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
                        <label class="control-label">Logo: <b style="color: red;">*</b></label>&nbsp;<span>{{ Auth::user()->userPartner ? " - Already uploaded a logo" : "" }}</span>
                        <input id="partner_select_logo" name="logo" accept="image/*" class="file-loading partner-select-logo" type="file">
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

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary open-update-info" data-toggle="modal">Update</button>
            <button type="button" class="btn btn-success save-partner-info" data-toggle="modal">Update</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Voucher Code-->
    <div class="modal fade" id="modal-view-code" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Voucher Code for  <span id="user-name" style="font-weight: bold;color: #820804"></span></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form id="voucher-form" autocomplete="off" action="" method="POST" role="form">
                <div class="form-group code">
                <label for="code" class="control-label">Code:</label>
                    <input id="voucher_code" name="voucher_code" type="text" placeholder="User Code" class="form-control">
                    <label class="error-label">Invalid Input</label>
                </div>

              <div class="form-group percent">
                <label for="percent" class="control-label">Percent:</label>
                    <input id="voucher_percent" name="voucher_percent" type="text" class="form-control" placeholder="Percent" >
                <label class="error-label">Invalid Input</label>
              </div>
            </form>

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary submit_voucher" data-progress-text="Saving ... <i class='fa fa-circle-o-notch fa-spin fa-fw'></i>" autocomplete="off">Submit</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Voucher Code-->
    <div class="modal fade" id="modal-resetpassword" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Reset Password for  <span id="user-name" style="font-weight: bold;color: #820804"></span></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form id="password-form" autocomplete="off" action="" method="POST" role="form">
                <div class="form-group code">
                <label for="password" class="control-label">Password:</label>
                    <input id="password" name="password" type="text" placeholder="Password" class="form-control">
                    <label class="error-label">Invalid Input</label>
                </div>
            </form>

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary submit_password" data-progress-text="Saving ... <i class='fa fa-circle-o-notch fa-spin fa-fw'></i>" autocomplete="off">Submit</button>
          </div>
        </div>
      </div>
    </div>
    
    <div id="createBadgesModal" class="modal fade" role="dialog">
        <div class="modal-dialog" style="width: 400px">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Add Badges</h4>
                </div>
                <div class="modal-body" >
                    <form id="badgesForm" action="{{ route('setbadges') }}" enctype="multipart/form-data" method="POST">

                        <div class="alert alert-danger print-error-msg" style="display:none">
                            <ul></ul>
                        </div>

                        <input type="hidden" name="badge_id" />

                        <div class="form-group">
                            <label>Name:</label>
                            <input type="text" name="name" class="form-control" placeholder="Badge name">
                        </div>

                        <div class="form-group">
                            <label>Description:</label>
                            <textarea name="description" class="form-control" placeholder="Badge description"></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label>Reward Credits (Optional):</label>
                            <input type="text" name="credits" class="form-control" value="0" placeholder="(100)">
                        </div>

                        <div class="form-group">
                            <label>Image:</label>
                            <input type="file" name="image" class="form-control">
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button id="createBadgeBtn" type="button" class="btn btn-primary" data-edit-text="Update">Add Badge</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
    <div id="setBadgesModal" class="modal fade" role="dialog">
        <div class="modal-dialog" style="width: 500px">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h5 class="modal-title">Set Badges</h5>
                </div>
                <div class="modal-body" >
                    <form id="setBadgesForm" action="{{ route('assignbadges') }}" method="POST">

                        <div class="alert alert-danger print-error-msg" style="display:none">
                            <ul></ul>
                        </div>

                        <input type="hidden" name="user_id" />

                        <div class="form-group">
                            <label>Assigned Badges:</label>
                            <select id="badges-selection" name="badges[]" multiple="multiple">
                                @foreach($badge_list as $badge)
                                <option value="{{$badge->id}}">{{$badge->name}}</option>
                                @endforeach
                            </select>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button id="setBadgeBtn" type="button" class="btn btn-primary">Set Badge</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
    <div id="setCreditsModal" class="modal fade" role="dialog">
        <div class="modal-dialog" style="width: 400px">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h5 class="modal-title">Update Credits</h5>
                </div>
                <div class="modal-body" >
                    <form id="setCreditsForm" action="{{ route('assignbadges') }}" method="POST">

                        <div class="alert alert-danger print-error-msg" style="display:none">
                            <ul></ul>
                        </div>

                        <input type="hidden" name="user_id" />

                        <div class="form-group">
                            <label>Credits:</label>
                            <input type="text" name="credits" class="form-control" placeholder="Credits">
                            <label class="error-label">Invalid Input</label>
                        </div>
                        
                    </form>
                </div>
                <div class="modal-footer">
                    <button id="setCreditBtn" type="button" class="btn btn-warning">Update Credit</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
    <div id="addRewardsModal" class="modal fade" role="dialog">
        <div class="modal-dialog" style="width: 400px">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h5 class="modal-title">Add Reward Credits</h5>
                </div>
                <div class="modal-body" >
                    <form id="add_rewards_form" method="POST">

                        <div class="alert alert-danger print-error-msg" style="display:none">
                            <ul></ul>
                        </div>
                        
                        <input type="hidden" name="user_id" />
                        
                        <div class="form-group">
                            <label>Name:</label>
                            <div class="user_image" style="padding-top: 10px"></div>
                        </div>

                        <div class="form-group">
                            <label class="control-label">Credits:</label>
                            <input type="text" name="credits" class="form-control" placeholder="Credits">
                            <label class="error-label">Invalid Input</label>
                        </div>
                        
                        <div class="form-group">
                            <label class="control-label">Reasons:</label>
                            <textarea name="reasons" class="form-control" placeholder="Reason for adding credits"></textarea>
                            <label class="error-label">Invalid Input</label>
                        </div>
                        
                    </form>
                </div>
                <div class="modal-footer">
                    <button id="addRewardBtn" type="button" class="btn btn-primary">Add Reward</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
    {{-- AWARD CREDITS BUGS AND PROMOTION MODAL --}}
    <div id="bugPromoModal" class="modal fade" role="dialog">
        <div class="modal-dialog" style="width: 400px">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h5 class="modal-title">Mark as Done</h5>
                </div>
                <div class="modal-body" >
                    <form id="bug_promo_form" method="POST">

                        <div class="alert alert-danger print-error-msg" style="display:none">
                            <ul></ul>
                        </div>
                        
                        <input type="hidden" name="id" />
                        <input type="hidden" name="type" />
                        
                        <div class="form-group">
                            <label>Name:</label>
                            <div class="user_image" style="padding-top: 10px"></div>
                        </div>

                        <div class="form-group">
                            <label class="control-label">Add Credits:</label>
                            <input type="text" name="credits" class="form-control" placeholder="Credits">
                            <label class="error-label">Invalid Input</label>
                        </div>
                        
                        <div class="form-group">
                            <label class="control-label">Reasons:</label>
                            <textarea name="reasons" class="form-control" placeholder="Reason for adding credits"></textarea>
                            <label class="error-label">Invalid Input</label>
                        </div>
                        
                        <div class="form-group">
                            <label class="control-label">Comment:</label>
                            <textarea name="admin_comment" class="form-control" placeholder="Add comment here..."></textarea>
                            <label class="error-label">Invalid Input</label>
                        </div>
                        
                    </form>
                </div>
                <div class="modal-footer">
                    <button id="bugpromo-save-btn" type="button" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </div>
    <div id="reject-modal" class="modal fade" role="dialog">
        <div class="modal-dialog" style="width: 400px">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h5 class="modal-title">Reject Deposit</h5>
                </div>
                <div class="modal-body">

                    <div class="alert alert-danger print-error-msg" style="display:none">
                        <ul></ul>
                    </div>
                    <div class="form-group">
                      <label class="control-label user">Name:</label>
                    </div>
                    <div class="form-group">
                      <label class="control-label code">Code:</label>
                    </div>
                    <form id="deposit-reject-form" method="POST">
                        <div class="form-group">
                        <label class="control-label">Message:</label>
                        <textarea name="admin_comment" class="form-control"></textarea>
                        <label class="error-label">Invalid Input</label>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary btn-reject">Save</button>
                </div>
            </div>
        </div>

    </div>

    <!-- Partner transactions with discrepancy -->
    <div class="modal fade" id="partner_modal_discrepancy" tabindex="-1" role="dialog" aria-labelledby="partner-modal-discrepancy">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="exampleModalLabel">Discrepancy BC Code: <span id="partner-bc-code" style="font-weight: bold;color: #820804"></span></h4>
          </div>
          <div class="modal-body" style="max-height: 730px;overflow-y: scroll;">
            <form id="partner_discrepancy_form" enctype="multipart/form-data" autocomplete="off" action="" method="POST" role="form">
              <div class="form-group">
                <label for="partner-discrepancy-amount" class="control-label">Amount:</label>
                <input type="text" class="form-control" id="partner-discrepancy-amount" name="partner_amount">
              </div>

                <div class="form-group">
                    <label for="mop">Mode of Payment</label>
                    <select class="form-control mop" name="partner_provider" id="partner_provider" style="width: 100%" data-placeholder="Select mode of payment">
                        <option></option>
                        <optgroup label="Bank Deposit">
                            <option value="BDO-deposit">BDO</option>
                            <option value="BPI-deposit">BPI</option>
                            <option value="Chinabank-deposit" disabled>Chinabank - coming soon</option>
                            <option value="Eastwest-deposit" disabled>Eastwest</option>
                            <option value="Metrobank-deposit">Metrobank</option>
                            <option value="PNB-deposit" disabled>PNB - coming soon</option>
                            <option value="PSBank-deposit" disabled>PSBank - coming soon</option>
                            <option value="rcbc-depositc" disabled>RCBC Savings Bank - coming soon</option>
                            <option value="securitybank-deposit" disabled>Security Bank Savings - coming soon</option>
                            <option value="Unionbank-deposit" disabled>Unionbank - coming soon</option>
                        </optgroup>
                        <optgroup label="Money Remittance">
                            <option value="cebuana-remittance">Cebuana Lhuiller Pera Padala</option>
                            <option value="mlhuiller-remittance" disabled>M Lhuillier Kwarta Padala - coming soon</option>
                            <option value="lbc-remittance">LBC Peso Padala</option>
                            <option value="palawan-remittance">Palawan Express</option>
                            <option value="western-remittance" disabled>Western Union - coming soon</option>
                            <option value="rd-remittance" disabled>RD Pawnshop - coming soon</option>
                        </optgroup>
                        <optgroup label="Mobile Money">
                            <option value="gcash-mobilemoney" disabled>G Cash - coming soon</option>
                            <option value="buycredits-mobilemoney" disabled>Buy Credits-Online - coming soon</option>
                        </optgroup>
                        <optgroup label="Online Bank Transfer">
                            <option value="BDO-online">BDO</option>
                            <option value="BPI-online">BPI</option>
                            <option value="Chinabank-online" disabled>Chinabank - coming soon</option>
                            <option value="Eastwest-online" disabled>Eastwest - coming soon</option>
                            <option value="Metrobank-online">Metrobank</option>
                            <option value="PNB-online" disabled>PNB - coming soon</option>
                            <option value="PSBank-online" disabled>PSBank - coming soon</option>
                            <option value="securitybank-online" disabled>Security Bank Savings - coming soon</option>
                            <option value="Unionbank-online" disabled>Unionbank - coming soon</option>
                        </optgroup>
                    </select>
                    </select>
                </div>

                <div class="form-group image">
                    <input id="partner-image" name="image" accept="image/*" class="file-loading" type="file">
                     <label class="error-label">The image field is required</label>
                </div>

              <div class="form-group message">
                <label for="message-text" class="control-label">Message:</label>
                <textarea class="form-control" id="partner-message" name="message"></textarea>
                <label class="error-label">Invalid Input</label>
              </div>

                <div class="form-group">
                    <label for="">History</label>
                    <table id="partner_discrepancy_table" class="table table-striped" width="100%">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Receipt</th>
                                <th>MOP</th>
                                <th>Message</th>
                                <th>Last updated by</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>

                </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary partner_discrepancy" data-progress-text="Saving ... <i class='fa fa-circle-o-notch fa-spin fa-fw'></i>" autocomplete="off">Submit</button>
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

    <div id="partner_cashout_mark_verified_modal" class="modal fade" role="dialog">
        <div class="modal-dialog" style="width: 400px;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h5 class="modal-title">Mark Cashout as Verified</h5>
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
                            <label class="control-label">Partner: <span class="partner_name_span_text"></span></label>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Code: <span class="code_span_text"></span></label>
                        </div>                        
                    </form>
                </div>
                <div class="modal-footer">
                    <button id="mark-as-verfied-save-btn" type="button" class="btn btn-primary">Save</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <div id="bug_thread_modal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h5 class="modal-title">Ticket Thread</h5>
                </div>
                <div class="modal-body">
                    <div class="ticket_thread_column">

                    </div>
                    <div>
                        
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary">Save</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
    
    <div id="auditUserModal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <input type="hidden" id="user-id" />
                    <h4 class="modal-title">Audit User: <strong class="username text-danger"></strong> <a href='{{ url("bet-log") . "/" . Auth()->id() }}' target="_blank" class="float-right">View Credits Logs</a></h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table" width="100%">
                                <tr>
                                    <th colspan="2">Formula A</th>
                                </tr>
                                <tr>
                                    <th>Total Cashout</th>
                                    <td class="total_cashout">N/A</td>
                                </tr>
                                <tr>
                                    <th>Betted Credits</th>
                                    <td class="curr_bets">N/A</td>
                                </tr>
                                <tr>
                                    <th>Remaining Credits</th>
                                    <td class="curr_credits">N/A</td>
                                </tr>
                                <tr>
                                    <th>&nbsp;</th>
                                    <th>&nbsp;</th>
                                </tr>
                                <tr>
                                    <th>&nbsp;</th>
                                    <th>&nbsp;</th>
                                </tr>                                                                
                                <tr>
                                    <th style="text-align: right">Total = </th>
                                    <td class="total_fa">N/A</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table" width="100%">
                                <tr>
                                    <th colspan="2">Formula B</th>
                                </tr>
                                <tr>
                                    <th>Total Deposit</th>
                                    <td class="total_deposit">N/A</td>
                                </tr>
                                <tr>
                                    <th>Transfered Rebates</th>
                                    <td class="total_rebate">N/A</td>
                                </tr>
                                <tr>
                                    <th>Total Rewards Claimed:</th>
                                    <td class="total_rewards">N/A</td>
                                </tr>   
                                <tr>
                                    <th>Total GC Claimed:</th>
                                    <td class="total_gift_codes">N/A</td>
                                </tr>                                                                
                                <tr>
                                    <th>Profit/Loss</th>
                                    <td class="profit_loss">N/A</td>
                                </tr>
                                <tr>
                                    <th style="text-align: right">Total = </th>
                                    <td class="total_fb">N/A</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="panel panel-primary" style="margin-bottom: 10px">
                        <div class="panel-heading" data-audit-data="bets">
                            <h3 class="panel-title">Bets</h3>
                            <span class="pull-right clickable">(Total Profit/Loss: <strong class="profit_loss"></strong>)&nbsp;&nbsp; <i class="glyphicon glyphicon-chevron-up"></i></span>
                        </div>
                        <div class="panel-body" style="padding: 0 0 15px 0">
                            <table id="audituser-table-bets" class="table table-striped" width="100%" style="font-size: 90%">
                                <thead>
                                    <th>Date/Time</th>
                                    <th>Match</th>
                                    <th>Team Selected</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Profit/Loss</th>
                                </thead>
                            </table>
                        </div>
                    </div>
                    <div class="panel panel-primary" style="margin-bottom: 10px">
                        <div class="panel-heading" data-audit-data="deposits">
                            <h3 class="panel-title">Deposits</h3>
                            <span class="pull-right clickable">(Total Deposits: <strong class="total_deposit"></strong>)&nbsp;&nbsp; <i class="glyphicon glyphicon-chevron-up"></i></span>
                        </div>
                        <div class="panel-body" style="padding: 0 0 15px 0">
                            <table id="audituser-table-deposits" class="table table-striped" width="100%" style="font-size: 90%">
                                <thead>
                                    <th>Date/Time</th>
                                    <th>O.R. #</th>
                                    <th>Amount</th>
                                    <th>MOP</th>
                                    <th>Status</th>
                                    <th>Processed By</th>
                                </thead>
                            </table>
                            <h3>Partner Deposits</h3>
                            <table id="audituser-table-partner-deposits" class="table table-striped" width="100%" style="font-size: 90%">
                                <thead>
                                    <th>Date/Time</th>
                                    <th>O.R. #</th>
                                    <th>Partner</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                </thead>
                            </table>
                        </div>
                    </div>
                    <div class="panel panel-primary" style="margin-bottom: 10px">
                        <div class="panel-heading" data-audit-data="rebates">
                            <h3 class="panel-title">Rebates</h3>
                            <span class="pull-right clickable">(Total Rebates: <strong class="total_rebate"></strong>)&nbsp;&nbsp; <i class="glyphicon glyphicon-chevron-up"></i></span>
                        </div>
                        <div class="panel-body" style="padding: 0 0 15px 0">
                            <table id="audituser-table-rebates" class="table table-striped" width="100%" style="font-size: 90%">
                                <thead>
                                    <th>Date/Time</th>
                                    <th>Transaction Code</th>
                                    <th>Amount Deposited</th>
                                    <th>Amount Earned</th>
                                    <th>Status</th>
                                </thead>
                            </table>
                        </div>
                    </div>

                    <div class="panel panel-primary" style="margin-bottom: 10px">
                        <div class="panel-heading" data-audit-data="cashouts">
                            <h3 class="panel-title">Cashouts</h3>
                            <span class="pull-right clickable">(Total Cashouts: <strong class="total_cashout"></strong>)&nbsp;&nbsp; <i class="glyphicon glyphicon-chevron-up"></i></span>
                        </div>
                        <div class="panel-body" style="padding: 0 0 15px 0">
                            <table id="audituser-table-cashouts" class="table table-striped" width="100%" style="font-size: 90%">
                                <thead>
                                    <th>Date/Time</th>
                                    <th>O.R. #</th>
                                    <th>Amount</th>
                                    <th>MOP</th>
                                    <th>Status</th>
                                    <th>Processed By</th>
                                </thead>
                            </table>
                            <h3>Partner Cashouts</h3>
                            <table id="audituser-table-partner-cashouts" class="table table-striped" width="100%" style="font-size: 90%">
                                <thead>
                                    <th>Date/Time</th>
                                    <th>O.R. #</th>
                                    <th>Partner</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                </thead>
                            </table>
                        </div>
                    </div>

                    <div class="panel panel-primary" style="margin-bottom: 10px">
                        <div class="panel-heading" data-audit-data="rewards">
                            <h3 class="panel-title">Rewards</h3>
                            <span class="pull-right clickable">(Total Rewards Claimed: <strong class="total_rewards"></strong>)&nbsp;&nbsp; <i class="glyphicon glyphicon-chevron-up"></i></span>
                        </div>
                        <div class="panel-body" style="padding: 0 0 15px 0">
                            <table id="audituser-table-rewards" class="table table-striped" width="100%" style="font-size: 90%">
                                <thead>
                                    <th>Date/Time</th>
                                    <th>Type</th>
                                    <th>Amount</th>
                                    <th>Description</th>
                                    <th>Added By</th>
                                </thead>
                            </table>
                        </div>
                    </div>


                    <div class="panel panel-primary" style="margin-bottom: 10px">
                        <div class="panel-heading" data-audit-data="giftcodes">
                            <h3 class="panel-title">Gift Codes</h3>
                            <span class="pull-right clickable">(Total GC Claimed: <strong class="total_gift_codes"></strong>)&nbsp;&nbsp; <i class="glyphicon glyphicon-chevron-up"></i></span>
                        </div>
                        <div class="panel-body" style="padding: 0 0 15px 0">
                            <table id="audituser-table-gift-codes" class="table table-striped" width="100%" style="font-size: 90%">
                                <thead>
                                    <th>Date Redeemed</th>
                                    <th>Code</th>
                                    <th>Amount</th>
                                    <th>Description</th>
                                </thead>
                            </table>
                        </div>
                    </div>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div id="reject-modal" class="modal fade" role="dialog">
        <div class="modal-dialog" style="width: 400px">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h5 class="modal-title">Reject Deposit</h5>
                </div>
                <div class="modal-body">

                    <div class="alert alert-danger print-error-msg" style="display:none">
                        <ul></ul>
                    </div>
                    <div class="form-group">
                      <label class="control-label user">Name:</label>
                    </div>
                    <div class="form-group">
                      <label class="control-label code">Code:</label>
                    </div>
                    <form id="deposit-reject-form" method="POST">
                        <div class="form-group">
                        <label class="control-label">Message:</label>
                        <textarea name="admin_comment" class="form-control"></textarea>
                        <label class="error-label">Invalid Input</label>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary btn-reject">Save</button>
                </div>
            </div>
        </div>

    </div>

    <!-- Partner transactions with discrepancy -->
    <div class="modal fade" id="partner_payout_modal" tabindex="-1" role="dialog" aria-labelledby="partner_payout_modal">
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Payout for <span id="partner-name-code" style="font-weight: bold;color: #820804"></span></h4>
            </div>
            <div class="modal-body" style="max-height: 730px;overflow-y: scroll;">
                <form id="partner_payout_form" enctype="multipart/form-data" autocomplete="off" action="" method="POST" role="form">
                    <div class="alert alert-info">
                        <strong>Notes:</strong> 
                        <br>1. <b>Submit Button</b> can't be clicked unless selected partner's earnings reach the minimum amount, which is <b>&#8369;1000.00</b>. 
                        <br>2. Selected partner's earnings will be <b>Reset to &#8369;0.00</b> after a successful payout. 
                    </div> 

                    <div class="form-group">
                        <label>Amount:</label>
                        <input type="text" class="form-control" id="partner-payout-amount" name="payout" readonly>
                    </div>

                    <div class="form-group">
                        <label>Pay via Credits <small>(will be added to Partner/Agent's wallet)</small>:</label>
                            <select name="partner-payout-use-credits" id="partner-payout-use-credits" class="form-control">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                    </div>

                    <div class="form-group image">
                        <label>Receipt</label>
                        <input id="payout-image" name="image" accept="image/*" class="file-loading" type="file">
                        <label class="error-label">The image field is required</label>
                    </div>
    
                    <div class="form-group message">
                        <label>Message:</label>
                        <textarea class="form-control" id="payout-message" name="message"></textarea>
                        <label class="error-label">Invalid Input</label>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary partner_payout_btn" data-progress-text="<i class='fa fa-circle-o-notch fa-spin fa-fw'></i> Processing" autocomplete="off">Submit</button>
            </div>
          </div>
        </div>
      </div>

    <!-- Partner affliates -->
    <div class="modal fade" id="partner_affliates_modal" tabindex="-1" role="dialog" aria-labelledby="partner_affliates_modal">
        <div class="modal-dialog modal-sm" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Affliates for <span id="partner-name" style="font-weight: bold;color: #820804"></span></h4>
            </div>
            <div class="modal-body">
                <form id="partner_payout_form" enctype="multipart/form-data" autocomplete="off" action="" method="POST" role="form">
                    <input type="hidden" id="partner_id" value="0"/>
                    <div class="affliates-container"></div>
                </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary partner_affliates_save" data-progress-text="<i class='fa fa-circle-o-notch fa-spin fa-fw'></i> Processing" autocomplete="off">Save</button>
            </div>
          </div>
        </div>
    </div>

    <!-- Partner Sub users -->
    <div class="modal fade" id="partner_sub_users_modal" tabindex="-1" role="dialog" aria-labelledby="partner_sub_users_modal">
        <div class="modal-dialog modal-sm" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Sub-users of <span id="partner-name" style="font-weight: bold;color: #820804"></span></h4>
            </div>
            <div class="modal-body">
                <form id="partner_payout_form" enctype="multipart/form-data" autocomplete="off" action="" method="POST" role="form">
                    <input type="hidden" id="partner_id" value="0"/>
                    <div class="sub-users-container"></div>
                </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary partner_sub_users_save" data-progress-text="<i class='fa fa-circle-o-notch fa-spin fa-fw'></i> Processing" autocomplete="off">Save</button>
            </div>
          </div>
        </div>
      </div>

    <!-- Partner Sub-agents -->
    <div class="modal fade" id="partner_subagents_modal" tabindex="-1" role="dialog" aria-labelledby="partner_subagents_modal">
        <div class="modal-dialog modal-sm" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Sub-agents of <span id="partner-name" style="font-weight: bold;color: #820804"></span></h4>
            </div>
            <div class="modal-body">
                <form id="partner_payout_form" enctype="multipart/form-data" autocomplete="off" action="" method="POST" role="form">
                    <input type="hidden" id="partner_id" value="0"/>
                    <div class="subagents-container"></div>
                </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary partner_subagents_save" data-progress-text="<i class='fa fa-circle-o-notch fa-spin fa-fw'></i> Processing" autocomplete="off">Save</button>
            </div>
          </div>
        </div>
      </div>

      <!-- Partner transactions with discrepancy -->
    <div class="modal fade" id="payout_modal" tabindex="-1" role="dialog" aria-labelledby="payout_modal">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Payout for</h4>
                </div>
                <div class="modal-body" style="max-height: 730px;overflow-y: scroll;">
                    <form id="partner_payout_form" enctype="multipart/form-data" autocomplete="off" action="" method="POST" role="form">

                        <div class="form-group">
                            <label>Pay Until 12 Midnight of:</label>
                            <input type="text" class="form-control datetimepicker" id="affliate-payout-until" name="affliate-payout-until" >
                        </div>

                        <div class="form-group">
                            <label>Amount:</label>
                            <input type="text" class="form-control" id="payout-amount" name="amount" disabled>
                        </div>
  
                        <div class="form-group image">
                            <label>Receipt</label>
                            <input id="payout-image-2" name="image" accept="image/*" class="file-loading" type="file">
                            <label class="error-label">The image field is required</label>
                        </div>
      
                        <div class="form-group message">
                            <label>Message:</label>
                            <textarea class="form-control"></textarea>
                            <label class="error-label">Invalid Input</label>
                        </div>
  
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary user_payout_btn" data-progress-text="<i class='fa fa-circle-o-notch fa-spin fa-fw'></i> Processing" autocomplete="off">Submit</button>
                </div>
            </div>
        </div>
    </div>

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
                <input type="hidden" id="refresh_table" value="partner_admin_transactions_table" />
                <div class="modal-footer">
                    <button id="submit-attached-receipt" type="button" class="btn btn-primary">Submit</button>
                    <button type="button" class="btn btn-default close-partner-modal-btn" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
    <!-- end uploading receipt/image to transaction modal -->    

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
                        <hr/>  
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Transactions: </label>
                            <div class="col-sm-9">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="inlineCheckbox01" value="deposit" checked name="type[]">
                                    <label class="form-check-label" for="inlineCheckbox01">Deposits</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="inlineCheckbox02" value="cashout" checked name="type[]">
                                    <label class="form-check-label" for="inlineCheckbox02">Cashouts</label>
                                </div>
                            </div>                            

                        </div>   
                        <hr/>                       
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Transaction Status: </label>
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

                        <input type="hidden" name="partner_id" value="" id="download-transaction-partner-id"/>
                        {{-- <input type="hidden" name="type" id="type" /> --}}
                        <input type="hidden" name="tradeType" id="tradeType" value="partner-user" />
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


</div>

@endsection


@section('script')

<script type="text/javascript" src="{{ asset('js/select2/select2.full.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/fileinput.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/theme.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/datatables.min.js')}}" ></script>
<script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.1/js/dataTables.responsive.min.js"></script>
<script type="text/javascript" src="{{ asset('js/viewer.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/moment.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('bower_components/bootstrap-sweetalert/dist/sweetalert.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/bootstrap-multiselect.js')}}"></script>
<script src="{{ asset('js/datatables-datetime-moment.js') }}"></script>
<script src="{{ asset('js/mustache.min.js') }}"></script>
<script src="https://cdn.ckeditor.com/4.10.0/standard-all/ckeditor.js"></script>
<script type="text/javascript" src="{{ asset('/bower_components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js') }}"></script>


<script id="coinsph-steps-template" type="text/template">
    <li>1. Send the amount <insert amount> to this wallet: <strong>{{$settings['coins-wallet-address']}}</strong></li>
    <li>2. Take screenshot</li>
    <li>3. Edit the screenshot and write your BC-code <span style="font-weight: bold;">@{{code}}</span></li>
    <li>4. Upload the edited reciept with your bccode</li>
    <li>5. Wait for approval.</li>
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
<script id="BDO-desktop" type="text/template">
    <li>1. Log in to your BDO.COM, enter the user ID and Password.</li>
    <li>2. Enter the OTP that was sent via SMS by BDO Online on your mobile number.</li>
    <li>3. At the right of your screen, select FINANCIAL SERVIES and click on TRANSFER MONEY.</li>
    <li>4. Enter the TRANSFER FROM box, from the desired account where you want to credit your pay.</li>
    <!--<li>5. 2EZ.BET Account number is <b>{{$settings['bdo-account-number']}}</b>, enter the desired AMOUNT on the next box.</li>-->
    <li>5. 2EZ.BET Account number is <b>@{{accountnumber}}</b>, enter the desired AMOUNT on the next box.</li>
    <li>6. <span style="color:red;font-weight: bold">Important: </span>Write your BC CODE on remarks <span style="font-weight: bold;">@{{code}}</span>.</li>
    <li>7. Then screen shot the transaction once you press the SUBMIT button.</li>
</script>
<script id="BPI-desktop" type="text/template">
    <li>1. Go to BPI.com.ph, enter the user ID and Password.</li>
    <li>2. Go to fund transfer tab then select fund transfer on the drop down menu, then click the Transfer funds today.</li>
    <!--<li>3. Fill out the required information, the AMOUNT to transfer, select the source amount, select the enrolled account of 2ez.bet with the account number <b>{{$settings['bpi-account-number']}}</b> and add remarks.</li>-->
    <li>3. Fill out the required information, the AMOUNT to transfer, select the source amount, select the enrolled account of 2ez.bet with the account number <b>@{{accountnumber}}</b> and add remarks.</li>
    <li>4. And click Transfer Now/Sumit Button.</li>
    <li>5. <span style="color:red;font-weight: bold">Important: </span>Write your BC CODE on remarks <span style="font-weight: bold;">@{{code}}</span>.</li>
    <li>6. Make a screen shot of the transaction.</li>
</script>
<script id="Metrobank-desktop" type="text/template">
    <li>1. Go to metrobank.com.ph, enter the user IDandPassword.</li>
    <li>2. Go to fund transfertab then selectfund transferon the drop down menu, then click theTransfer funds today.</li>
    <!--<li>3. Fill out the required information, the AMOUNT to transfer, select the source amount, select the enrolled account of 2EZ.bet with the account number <b>{{$settings['metro-account-number']}}</b> and add remarks.</li>-->
    <li>3. Fill out the required information, the AMOUNT to transfer, select the source amount, select the enrolled account of 2EZ.bet with the account number <b>@{{accountnumber}}</b> and add remarks.</li>
    <li>4. And click Transfer Now.</li>
    <li>5. Then press the SUBMIT button.</li>
    <li>6. And screen shot the transaction promt after you press the Submit Button.</li>
</script>
<script id="Securitybank-desktop" type="text/template">
    <li>1. Go to your Security bank mobile banking app.</li>
    <li>2. Click transfer on the tab below</li>
    <li>3. Select transfer to others.</li>
    <li>4. Enter the account number <b>@{{accountnumber}}</b></li>
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
            <!--<li>Receiver Name: <b>{{$settings['remittance-name']}}</b></li>
            <li>Receiver Number: <b>{{$settings['remittance-number']}}</b></li>
            <li>Receiver Location: <b>{{$settings['remittance-location']}}</b></li>
            <li>Sender Name: <b>(Indicate your name)</b></li>
            <li>Insert amount: <span style="font-weight: bold;">&#8369; @{{amount}}</span></li>-->
            <li>Receiver Name: <b>@{{full_name}}</b></li>
            <li>Receiver Number: <b>@{{mobile_number}}</b></li>
            <li>Receiver Location: <b>@{{location}}</b></li>
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
            <!--<li>Receiver Name: <b>{{$settings['remittance-name']}}</b></li>
            <li>Receiver Number: <b>{{$settings['remittance-number']}}</b></li>
            <li>Receiver Location: <b>{{$settings['remittance-location']}}</b></li>
            <li>Sender Name: <b>(Indicate your name)</b></li>
            <li>Insert amount: <span style="font-weight: bold;">&#8369; @{{amount}}</span></li>-->
            <li>Receiver Name: <b>@{{full_name}}</b></li>
            <li>Receiver Number: <b>@{{mobile_number}}</b></li>
            <li>Receiver Location: <b>@{{location}}</b></li>
            <li>Sender Name: <b>(Indicate your name)</b></li>
            <li>Insert amount: <span style="font-weight: bold;">&#8369; @{{amount}}</span></li>       
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
            <!--<li>Receiver Name: <b>{{$settings['remittance-name']}}</b></li>
            <li>Receiver Number: <b>{{$settings['remittance-number']}}</b></li>--->
            <li>Receiver Name: <b>@{{full_name}}</b></li>
            <li>Receiver Number: <b>@{{mobile_number}}</b></li>            
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
            <!--<li>Receiver Name: <b>{{$settings['remittance-name']}}</b></li>
            <li>Receiver Number: <b>{{$settings['remittance-number']}}</b></li>--->
            <li>Receiver Name: <b>@{{full_name}}</b></li>
            <li>Receiver Number: <b>@{{mobile_number}}</b></li>     
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
            <!--<li>Receiver Name: <b>{{$settings['remittance-name']}}</b></li>
            <li>Receiver Number: <b>{{$settings['remittance-number']}}</b></li>--->
            <li>Receiver Name: <b>@{{full_name}}</b></li>
            <li>Receiver Number: <b>@{{mobile_number}}</b></li>     
        </ul>
    <li>3. Place your Mobile number.</li>
    <li>4. Place the desired amount to send.</li>
    <li>5. And on the Buttom sign the signature over printed name.</li>
    <li>6. submit the Form together with your 2 Valid I.D. And Amount at the counter (any I.D. Will do as long as it is a goverment issued I.D. With a picture, your signature and name on it and not expired).</li>
    <li>7. Once the transaction is complete, write the Tracking number and BC Code <span style="font-weight: bold;">@{{code}}</span> on the Official Receipt and take a picture of the Official RECEIPT.</li>
</script>

<script id="partner-details" type="text/template">
    <li>Partner Name: <span style="font-weight: bold;">@{{partner_name}}</span> </li>
    <li>Address: <span style="font-weight: bold;">@{{address}}</span> </li>
    <li>Owner: <span style="font-weight: bold;">@{{name}}</span></li>
    <li>Contact Person: <span style="font-weight: bold;">@{{person}}</span></li>
    <li>Contacts</li>
    <li>&nbsp;&nbsp;&nbsp;&nbsp;Mobile: <span style="font-weight: bold;">@{{mobile}}</span></li>
    <li>&nbsp;&nbsp;&nbsp;&nbsp;Landline: <span style="font-weight: bold;">@{{landline}}</span></li>
    <li>&nbsp;&nbsp;&nbsp;&nbsp;Email: <span style="font-weight: bold;">@{{email}}</span></li>
    <li>&nbsp;&nbsp;&nbsp;&nbsp;Facebook Link: <span style="font-weight: bold;">@{{fb_link}}</span></li>
    <li>Bank Accounts</li>
    <li>&nbsp;&nbsp;BPI</li>
    <li>&nbsp;&nbsp;&nbsp;&nbsp;Account No.: <span style="font-weight: bold;">@{{bpi_num}}</span></li>
    <li>&nbsp;&nbsp;&nbsp;&nbsp;Account Name: <span style="font-weight: bold;">@{{bpi_name}}</span></li>
    <li>&nbsp;&nbsp;BDO</li>
    <li>&nbsp;&nbsp;&nbsp;&nbsp;Account No.: <span style="font-weight: bold;">@{{bdo_num}}</span></li>
    <li>&nbsp;&nbsp;&nbsp;&nbsp;Account Name: <span style="font-weight: bold;">@{{bdo_name}}</span></li>
</script>
<script id="partner-account" type="text/template">
    <li><span style="font-weight: bold;">@{{name}}</span>'s Bank Account(s)</li>
    <li>•&nbsp;BPI</li>
    <li>&nbsp;&nbsp;&nbsp;&nbsp;Account No.: <span style="font-weight: bold;">@{{bpi_num}}</span></li>
    <li>&nbsp;&nbsp;&nbsp;&nbsp;Account Name: <span style="font-weight: bold;">@{{bpi_name}}</span></li>
    <li>•&nbsp;BDO</li>
    <li>&nbsp;&nbsp;&nbsp;&nbsp;Account No.: <span style="font-weight: bold;">@{{bdo_num}}</span></li>
    <li>&nbsp;&nbsp;&nbsp;&nbsp;Account Name: <span style="font-weight: bold;">@{{bdo_name}}</span></li>
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
    $(document).on('click', '.panel-heading span.clickable', function(e){
        var $this = $(this);
        if(!$this.hasClass('panel-collapsed')) {
                $this.parents('.panel').find('.panel-body').slideUp();
                $this.addClass('panel-collapsed');
                $this.find('i').removeClass('glyphicon-chevron-up').addClass('glyphicon-chevron-down');
        } else {
                $this.parents('.panel').find('.panel-body').slideDown();
                $this.removeClass('panel-collapsed');
                $this.find('i').removeClass('glyphicon-chevron-down').addClass('glyphicon-chevron-up');
        }
    })
    $(document).ready(function() {
        $.fn.dataTable.ext.errMode = 'none';

        // $('.datetimepicker').datetimepicker({
        //     viewMode: 'days',
        //     maxDate : moment().subtract(1, 'day'),
        //     format: 'YYYY-MM-DD'
        // }).on('dp.change', function(e){ $(this).parent().removeClass('has-error'); });
        $('.datetime_sched').datetimepicker({
            // viewMode: 'days',
            format: 'YYYY-MM-DD HH:mm:ss'
        }).on('dp.change', function(e){ $(this).parent().removeClass('has-error'); });

        $('#badges-selection').multiselect({
            includeSelectAllOption: true
        });
        CKEDITOR.replace('tos_content_ckedit', {
            extraPlugins: 'embed,autoembed,image2',
            height: 300,
            contentsCss: [ CKEDITOR.basePath + 'contents.css', 'https://sdk.ckeditor.com/samples/assets/css/widgetstyles.css' ],
            embed_provider: '//ckeditor.iframe.ly/api/oembed?url={url}&callback={callback}',
            image2_alignClasses: [ 'image-align-left', 'image-align-center', 'image-align-right' ],
            image2_disableResizer: true,
            extraAllowedContent: 'iframe(*){*}[*]',
        });
        $('#save-tos-btn').click(function() {
            $btn = $(this);
            $btn.button('progress');
            $btn.prop('disabled', true);
            console.log('saving tos...');
            $.ajax({
                url: "{{route('updateTOS')}}",
                type: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                data: {content: CKEDITOR.instances.tos_content_ckedit.getData()},
                success: function(data) {
                    $btn.button('reset');
                    $btn.prop('disabled', false);
                    if(data.success)
                        swal("Success!", 'Successfully updated T&C!', "success");
                    else
                        swal("Oops!", 'Something went wrong. Please try again!', "error");
                }, 
                error: function(xhr, status, error) {
                    $btn.button('reset');
                    $btn.prop('disabled', false);
                    swal("Oops!", 'Something went wrong. Please try again!', "error");
                }
            });
        });
    });
    $(".mop").select2({
        placeholder: "Please select mode of payment"
    }); 

    $('#voucher_percent').currencyFormat();

     var url = '{{asset('transaction/images/')}}'

    var $image = $('#disc-image'), initPlugin = function() {
        $image.fileinput({
            previewFileType: "image",
            theme: "fa",
            showUpload: false,
            showCaption: false,
            showRemove: false,
            browseClass: "btn btn-primary",
            browseLabel: "Pick Image",
            browseIcon: "<i class=\"fa fa-picture-o\"></i> ",
            validateInitialCount:true,
            allowedFileExtensions: ["jpg", "gif", "png"],
        })
    };

    var $_image = $('#modal-mark-as-processed #image'), _initPlugin = function() {
        $_image.fileinput({
            previewFileType: "image",
            theme: "fa",
            showUpload: false,
            showCaption: false,
            showRemove: false,
            browseClass: "btn btn-primary",
            browseLabel: "Pick Image",
            browseIcon: "<i class=\"fa fa-picture-o\"></i> ",
            validateInitialCount:true,
            allowedFileExtensions: ["jpg", "gif", "png"],
        })
    };

    var $partner_image = $('#partner_modal_discrepancy #partner-image'), partnerInitPlugin = function() {
        $partner_image.fileinput({
            previewFileType: "image",
            theme: "fa",
            showUpload: false,
            showCaption: false,
            showRemove: false,
            browseClass: "btn btn-primary",
            browseLabel: "Pick Image",
            browseIcon: "<i class=\"fa fa-picture-o\"></i> ",
            validateInitialCount:true,
            allowedFileExtensions: ["jpg", "gif", "png"],
        }).on("filebatchselected", function(event, files) {
            $('.kv-upload-progress').removeClass('hide').css('display', 'block').find('.progress-bar').css('width', '100%').text('Done');
            $('.file-upload-indicator').css({
                backgroundColor: '#dff0d8',
                borderColor: '#d6e9c6'
            });
            $('.file-upload-indicator').find('i').removeClass('fa-hand-o-down').removeClass('text-warning').addClass('fa-check-circle').addClass('text-success'); 
        });
    };

    var $payout_image = $('#partner_payout_modal #payout-image'), partnerPayoutInitPlugin = function() {
        $payout_image.fileinput({
            previewFileType: "image",
            theme: "fa",
            showUpload: false,
            showCaption: false,
            showRemove: false,
            browseClass: "btn btn-primary",
            browseLabel: "Pick Image",
            browseIcon: "<i class=\"fa fa-picture-o\"></i> ",
            validateInitialCount:true,
            allowedFileExtensions: ["jpg", "gif", "png"],
        });
    };

    var $payout_image_2 = $('#payout_modal #payout-image-2'), payoutInitPlugin = function() {
        $payout_image_2.fileinput({
            previewFileType: "image",
            theme: "fa",
            showUpload: false,
            showCaption: false,
            showRemove: false,
            browseClass: "btn btn-primary",
            browseLabel: "Pick Image",
            browseIcon: "<i class=\"fa fa-picture-o\"></i> ",
            validateInitialCount:true,
            allowedFileExtensions: ["jpg", "gif", "png"],
        }).on("filebatchselected", function(event, files) {
            $('.kv-upload-progress').removeClass('hide').css('display', 'block').find('.progress-bar').css('width', '100%').text('Done');
            $('.file-upload-indicator').css({
                backgroundColor: '#dff0d8',
                borderColor: '#d6e9c6'
            });
            $('.file-upload-indicator').find('i').removeClass('fa-hand-o-down').removeClass('text-warning').addClass('fa-check-circle').addClass('text-success'); 
        });
    };
 
    // initialize plugin
    initPlugin();
    _initPlugin();
    partnerInitPlugin();
    payoutInitPlugin();
    partnerPayoutInitPlugin();

    var deposits_table
    var initDepositsTableDatables
    var cashout_table
    var initCashoutTableDatables
    var bettings_table
    var initBettingsTableDatables
    var donations_table
    var initDonationsTableDatables
    var commissions_table
    var initCommissionsTableDatables
    var commissions_partners_table
    var initCommissionsPartnersTableDatables
    var commissions_bets_table
    var initCommissionsBetsTableDatables    
    var badges_table
    var initBadgesTableDatables
    var discrepancy_table
    var users_table
    var initUsersTableDatables
    var rewards_table
    var initRewardsTableDatables
    var bugs_table
    var initBugsTableDatables
    var promo_table
    var initPromosTableDatables
    var partner_discrepancy_table
    var referrals_table
    var initReferralsTableDatables
    var payout_table
    var initPayoutsTableDatables
    var rebates_table
    var initRebatesTableDatables
    var earnings_table
    var initEarningsTableDatables
    var fees_table
    var audit_user_bets
    var audit_user_cashouts
    var audit_user_deposits
    var audit_user_partner_deposits
    var audit_user_partner_cashouts
    var audit_user_rebates
    var audit_user_rewards
    var audit_user_gift_codes
    $(function(){
        $.ajax({
            url: '/admin/dashboard',
            type: 'GET',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            success: function(data) {
                var dt = $('<dt class="col-sm-6"></dt>')
                var dd = $('<dd class="col-sm-6"></dd>')
                $.each(data,function(index,item){
                    var _dt = dt.clone().append(humanize(index)+': ')
                    var _dd = dd.clone().append(item)
                    if(index.indexOf('circulating') != -1){
                        $('#overview-circulating').append(_dt)
                        $('#overview-circulating').append(_dd)
                    }

                    if(index.indexOf('available') != -1){
                        $('#overview-available').append(_dt)
                        $('#overview-available').append(_dd)
                    }

                    if(index.indexOf('betted_credits') != -1){
                        $('#overview-betted').append(_dt)
                        $('#overview-betted').append(_dd)
                    }

                    if(index.indexOf('fees') != -1){
                        $('#overview-fees').append(_dt)
                        $('#overview-fees').append(_dd)
                    }

                    if(index.indexOf('cashouts') != -1){
                        $('#overview-cashouts').append(_dt)
                        $('#overview-cashouts').append(_dd)
                    }

                    if(index.indexOf('_deposits_') != -1){
                        $('#overview-deposits').append(_dt)
                        $('#overview-deposits').append(_dd)
                    }               

                    if(index.indexOf('partners') != -1){
                        $('#overview-partners').append(_dt)
                        $('#overview-partners').append(_dd)
                    }

                    if(index.indexOf('users_who') != -1){
                        $('#overview-users_who').append(_dt)
                        $('#overview-users_who').append(_dd)
                    }
                })
                $('#loading-text').html('')
                
                function humanize(str) {
                    var frags = str.split('_');
                    for (i=0; i<frags.length; i++) {
                        frags[i] = frags[i].charAt(0).toUpperCase() + frags[i].slice(1);
                    }
                    return frags.join(' ');
                }
            },
            fail: function(obj) {
                
            }
        });
        $.fn.dataTable.moment('llll');

        fees_table = $('#fees_table').DataTable({ 
            initComplete : function() {
                var input = $('#fees_table_filter input').unbind(),
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
                $('#fees_table_filter').append($searchButton, $clearButton);
            },         
            processing: true,
            serverSide:true,
            stateSave: true,
            responsive: true,
            ajax: "{!! route('get-all-fees') !!}",
            order: [[ 1, "desc" ]], 
            columns:[
                {
                    data:'created_at',
                    name:'created_at',
                    render: function(data,type,row){
                        return moment(data).format('llll')+'<br>'+moment(data).fromNow()
                    }
                },
                {data:'meta_key',name:'meta_key'},
                {data:'meta_value',name:'meta_value'},
                {
                    data:'transaction',
                    searchable:false,
                    render: function(data,type,row){
                        switch (row['meta_key']) {
                            case 'cashout':
                                return data.code
                            case 'cashout-partner':
                                return data.code
                            case 'match':
                                return data.name
                            case 'tournament':
                                return data.name
                            default:
                                break;
                        }
                    }
                },
                {data:'collected',name:'collected'},
            ]
        });

        discrepancy_table = $('#discrepancy_table').DataTable({ 
            initComplete : function() {
                var input = $('#discrepancy_table_filter input').unbind(),
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
                $('#discrepancy_table_filter').append($searchButton, $clearButton);
            },               
            paging:   false,
            info:     false,
            searching: false,
            ordering: false,
            destroy: true,
            responsive: true,
            columns :[
                {
                    data:'created_at',
                    render: function(data,type,row){
                        return moment(data).format('llll')+'<br>'+moment(data).fromNow()
                    }
                },
                {
                    data:'amount',
                    render: function(data,type,row){
                        return data ? data : 'n/a'
                    }
                },
                {
                    data:'picture',
                    searchable:false,
                    render: function(data,type,row){
                        return data ? '<a href="#" class="btn btn-default view-receipt"><i class="fa fa-picture-o" aria-hidden="true"></i></a>' : 'n/a'
                    }
                },
                {data: 'mop'},
                {data: 'message'},
                {data: 'process_by.name'},

            ]
        });

        initDepositsTableDatables = function(){
            deposits_table = $('#deposits_table').DataTable({
                initComplete : function() {
                  
                    var input = $('#deposits_table_filter input').unbind(),
                        statusInput = $('#deposits_table_filter select#deposit-select-status').unbind(),
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
                                }),
                        
                        $selectStatus =  $('<select id="deposit-select-status" class="data-tables-select">')
                                .change(function() {
                                    const depositUrl = "{!! route('get_all_transactions',['type' => 'deposit']) !!}&status=" + $(this).val()
                                    self.ajax.url(depositUrl).load();
                                });
                               
                        $selectStatus.append('<option value="">All</option>');
                        $selectStatus.append('<option value="processing">Incomplete</option>');
                        $selectStatus.append('<option value="completed">Completed</option>');
                        $selectStatus.append('<option value="rejected">Rejected</option>');

                    $('#deposits_table_filter').append($selectStatus, $searchButton, $clearButton);
            
                },   
                fixedHeader: {
            header: true,
            footer: true
        },                 
                processing: true,
                serverSide:true,
                stateSave: true,
                responsive: true,
                ajax: "{!! route('get_all_transactions',['type' => 'deposit']) !!}",
                order: [[ 1, "desc" ]], 
                createdRow: function ( row, data, index ) {
                    var transaction = data;
                    if (transaction.discrepancy.length > 0) {
                        $('td', row).eq(3).addClass('highlight');
                        $('td', row).eq(4).addClass('highlight');
                        // $('td', row).closest('tr').addClass('highlight-dicrepancy');
                    }

                    $('td', row).eq(7).addClass(`transaction-${transaction.status}`);
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
                        data:'approved_rejected_date',
                        name:'approved_rejected_date',
                        render: function(data,type,row){
                            switch(row.status){
                                case 'processing':
                                    return 'Processing';
                                case 'rejected':
                                case 'completed':
                                    const useDate = !!data ? data : row.updated_at;
                                    return moment(useDate).format('llll')+'<br>'+moment(useDate).fromNow();
                            }
                        }
                    },
                    {
                        data:'user.id',
                        name:'user.id',
                    },
                    {
                        data:'user.name',
                        name:'user.name',
                    },
                    {
                        data:'amount',
                        name:'amount',
                        render: function(data,type,row){
                            return '&#8369; '+numberWithCommas(data);
                        }
                    },
                    {data:'data.mop',name:'data'},
                    {
                        data:'status',
                        name:'status',
                        render: function(data,type,row){
                            // return row['picture'] == null  && data != 'completed' ? 'incomplete' : data == 'completed' ? 'Approved and Completed' : 'Needs Approval' 
                            if(data == 'rejected'){
                                return 'rejected'
                            }else{
                                if(row['picture'] == null  && data != 'completed'){
                                    return 'incomplete'
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
                        render: function(data,type,row){
                            return  '<a href="#" class="btn btn-default view-details"><i class="fa fa-info" aria-hidden="true"></i></a>'
                        }
                    },
                    {
                        data:'picture',
                        searchable:false,
                        render: function(data,type,row){
                            return  data != null ? '<a href="#" class="btn btn-default view-receipt"><i class="fa fa-picture-o" aria-hidden="true"></i></a>' : 'n/a'
                        }
                    },
                    {
                        data:'process_by',
                        searchable:false,
                        render: function(data,type,row){
                            return data == null ? 'n/a' : data['name']
                        }
                    },
                    {
                        data:'status',
                        searchable:false,
                        render: function(data,type,row){
                            if(data == 'rejected'){
                                return row['notes'].length > 0 && row['notes'][0] ? row['notes'][0].message : '';
                                
                            }else{
                            if (row['picture'] == null && data != 'completed') {
                                    return '<a href="#" data-status="rejected" class="btn btn-danger btn-sm btn-reject">Reject</a> <a href="#" data-status="discrepancy" class="btn btn-warning btn-sm btn-edit discrepancy">Approve w/ discrepancy</a> <button class="btn btn-info btn-sm audit-user" data-toggle="modal" data-target="#auditUserModal">Audit</button>'

                            }else{
                                if (data == 'completed') {
                                    return '<a href="#" data-status="discrepancy" class="btn btn-success btn-sm btn-edit discrepancy">Update w/ discrepancy</a>'
                                }else{
                                    return '<a href="#" data-status="rejected" class="btn btn-danger btn-sm btn-reject">Reject</a> <a href="#" data-status="pocessing" class="btn btn-primary btn-sm btn-edit approve">Check and approve</a> <button class="btn btn-info btn-sm audit-user" data-toggle="modal" data-target="#auditUserModal">Audit</button>'
                                }

                                }
                            }
                            // return '<a href="#" data-status="'+(data == 'completed' ? 'disapproved' : 'approved')+'" class="btn btn-'+(data == 'completed' ? 'danger' : 'primary')+' btn-sm btn-edit approve">'+(data == 'completed' ? 'Disapprove' : 'Approve')+'</a>'
                        }
                    },
                ]

            })
        }

        initCashoutTableDatables = function(){
            cashout_table = $('#cashout_table').DataTable({
                initComplete : function() {
                    var input = $('#cashout_table_filter input').unbind(),
                        self = this.api(),
                        $searchButton = $('<button class="data-tables-btn">')
                                .text('Search')
                                .click(function() {
                                    self.search(input.val()).draw();
                                }),
                        statusInput = $('#cashout_table_filter select#cashout-select-status').unbind(),
                        $clearButton = $('<button class="data-tables-btn">')
                                .text('Clear')
                                .click(function() {
                                    input.val('');
                                    $searchButton.click(); 
                                }) 

                        $selectStatus =  $('<select id="cashout-select-status" class="data-tables-select">')
                                .change(function() {
                                    const cashoutUrl = "{!! route('get_all_transactions',['type' => 'cashout']) !!}&status=" + $(this).val()
                                    self.ajax.url(cashoutUrl).load();
                                });
                        
                        $selectStatus.append('<option value="">All</option>');
                        $selectStatus.append('<option value="processing">Incomplete</option>');
                        $selectStatus.append('<option value="completed">Completed</option>');
                        $selectStatus.append('<option value="rejected">Rejected</option>');


                    $('#cashout_table_filter').append($selectStatus, $searchButton, $clearButton);
                },                    
                processing: true,
                serverSide:true,
                stateSave: true,
                responsive: true,
                ajax: "{!! route('get_all_transactions',['type' => 'cashout']) !!}",
                order: [[ 1, "desc" ]],
                createdRow: function ( row, data, index ) {
                    var transaction = data;
                    $('td', row).eq(8).addClass(`transaction-${transaction.status}`);
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
                        data:'approved_rejected_date',
                        name:'approved_rejected_date',
                        render: function(data,type,row){
                            switch(row.status){
                                case 'processing':
                                    return 'Processing';
                                case 'rejected':
                                case 'completed':
                                    const useDate = !!data ? data : row.updated_at;
                                    return moment(useDate).format('llll')+'<br>'+moment(useDate).fromNow();
                            }
                        }
                    },    
                    {data:'user.id',name:'user.id'},                
                    {data:'user.name',name:'user.name'},
                    {
                        data:'amount',
                        name:'amount',
                        render: function(data,type,row){
                            return '&#8369; '+numberWithCommas(data);
                        }
                    },
                    {data:'data.mop',name:'data'},
                    {
                        data:'data',
                        searchable:false,
                        render: function(data,type,row){
                            return  data != null ? '<a href="#" class="btn btn-default view-details"><i class="fa fa-info" aria-hidden="true"></i></a>' : 'n/a'
                        }
                    },
                    {
                        data:'donation',
                        searchable:false,
                        render: function(data,type,row){
                            return data == null ? 'n/a' : parseFloat(data['amount']).toFixed(2)
                        }
                    },
                    {
                        data:'status',
                        name:'status',
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
                        data:'picture',
                        searchable:false,
                        render: function(data,type,row){
                            return  data != null ? '<a href="#" class="btn btn-default view-receipt"><i class="fa fa-picture-o" aria-hidden="true"></i></a>' : 'n/a'
                        }
                    },
                    {
                        data:'notes',
                        searchable:false,
                        render: function(data,type,row){
                            return data.length == 0 ? 'n/a' : data[0].message
                        }
                    },
                    {
                        searchable:false,
                        render: function(data,type,row){
                            return  row['process_by'] == null ? 'n/a' : row['process_by'].name
                        }
                    },
                    {
                        data:'status',
                        searchable:false,
                        render: function(data,type,row){
                            if(data == 'rejected'){
                                let statusText = row['notes'].length > 0 ? row['notes'][0].message : '';

                                statusText += '<br/><button class="btn btn-info btn-sm audit-user" data-toggle="modal" data-target="#auditUserModal">Audit</button>';
                                return statusText;
                            }else{
                                if (row['picture'] == null) {
                                    return '<a href="#" data-status="rejected" class="btn btn-danger btn-sm btn-reject">Reject</a> <a href="#" data-status="deleted" class="btn btn-primary btn-sm mark-as-processed">Mark as processed</a> <button class="btn btn-info btn-sm audit-user" data-toggle="modal" data-target="#auditUserModal">Audit</button>'
                                }else{
                                    return '<button class="btn btn-info btn-sm audit-user" data-toggle="modal" data-target="#auditUserModal">Audit</button>'
                                }
                            }
                        }
                    },
                ]

            });

        }

         
        initBettingsTableDatables = function(){
            bettings_table = $('#bettings_table').DataTable({
                initComplete : function() {
                    var input = $('#bettings_table_filter input').unbind(),
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
                    $('#bettings_table_filter').append($searchButton, $clearButton);
                },                    
                processing: true,
                serverSide:true,
                responsive: true,
                ajax: "{!! route('json_tournament_allbets') !!}",
                order: [[ 0, "desc" ]],
                columns: [
                    {data: 'updated_at'},
                    {
                        data: 'user.name',
                        name: 'user.name',
                        render: function(data,type,row){
                        return data;
                    }
                    },
                    {
                        data: 'team.name',
                        data: 'team.name'
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
                        data: 'match.teamwinner',
                        name: 'match.teamwinner.name',
                        render: function(data,type,row){
                            if(data) {
                                return data.name;
                            } else {
                                if(row['type'] == 'tournament')
                                    return row['league'].champion ? row['league'].champion.name : 'N/A';
                                else
                                    return 'N/A';
                            }
                        }
                    },
                    {
                        data: 'amount',
                        name: 'amount',
                        render: function(data,type,row) {
                            return numberWithCommas(parseFloat(data).toFixed(2));
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
                                                return 'Ongoing';
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
                                    if(row.league.betting_status == -1) {
                                        if(row.league.league_winner == row['team_id'])
                                            return '<span style="color:green">+'+numberWithCommas(parseFloat(row.gains).toFixed(2))+'</span>';
                                        else
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
        }


        $('#deposits_table').on('click', '.approve', function(event) {
            event.preventDefault();
            $tr = $(this).closest('tr').hasClass('child') ? 
                $(this).closest('tr').prev() : $(this).closest('tr');
            var row = deposits_table.row($tr).data();
            let message = `Transaction <span style="font-weight:bold;color: #820804">${row.code}</span> will be approved <br> <label for="add_rebate" class="control-label"> <input id="add_rebate" name="add_rebate" type="checkbox"> Add Rebate</label>`

            swal({
                title: "Are you sure?",
                text: "Transaction <span style='font-weight:bold;color: #820804'>"+row.code+"</span> will be approved",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Yes, please continue!",
                showLoaderOnConfirm: true,
                closeOnConfirm: false,
                html:true
            },
            function(){
                var add_rebate = $('.sweet-alert').find('#add_rebate').is(':checked') ? 1 : 0;

                $.ajax({
                    url: '{{ route('set_status') }}',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    type: 'POST',
                    data: { id: row['id'],status:'completed',type: 'deposit', user_id:row['user'].id, add_rebate: add_rebate },
                    success: function(data) {
                        if (data.success) {
                            swal("Approved!", 'Transaction successfully approved', "success");
                            deposits_table.ajax.reload( null, false );
                        }else{
                            if(!!data.errors == true){
                                swal("Oops!", data.errors['alreadyProcessed'], "error");
                            }else{
                                swal("Oops!", 'Something went wrong!', "error");
                            }
                           
                        }
                    },
                    fail: function(xhr, status, error) {
                        console.log(error);
                    }
                });
            });
            if(!row.voucher_code){
                $('.sweet-alert').find('.lead').html(message);
            }else{

                if( !row.voucher_code.toLowerCase() == 'kuya-jop' || !row.voucher_code.toLowerCase() == 'cbb'){
                    $('.sweet-alert').find('.lead').html(message);
                }
            }
        });

        initDonationsTableDatables = function(){
            donations_table = $('#donations_table').DataTable({
                initComplete : function() {
                    var input = $('#donations_table_filter input').unbind(),
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
                    $('#donations_table_filter').append($searchButton, $clearButton);
                },                    
                processing: true,
                serverSide:true,
                responsive: true,
                ajax: "{!! route('get_donations_transactions') !!}",
                order: [[ 1, "desc" ]],
                columns:[
                    {
                        data:'created_at',
                        name:'created_at',
                        render: function(data,type,row){
                            return moment(data).format('llll')+'<br>'+moment(data).fromNow()
                        }
                    },
                    {data:'user.name',name:'user.name'},
                    {
                        data:'amount',
                        name:'amount',
                        render: function(data,type,row){
                            return '&#8369; '+numberWithCommas(parseFloat(data).toFixed(2));
                        }
                    },
                ]

            })
        }

        initCommissionsTableDatables = function(){
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
                ajax: "{!! route('get_commissions_transactions') !!}",
                order: [[ 0, "desc" ]],
                columns:[
                    {
                        data:'created_at',
                        name:'created_at',
                        render: function(data,type,row){
                            return moment(data).format('llll')+'<br>'+moment(data).fromNow()
                        }
                    },
                    {data:'user.name',name:'user.name'},
                    {
                        data:'transaction.code',
                        name:'transaction.code',
                    },
                    {
                        data:'transaction.user.name',
                        name:'transaction.user.name',
                    },
                    {
                        data:'transaction.discrepancy',
                        render: function (data,type,row) {
                            if(!!data == true && data.length > 0){
                                var _dis = $.grep(data,function(discrepancy){
                                    if(discrepancy.amount){
                                        return discrepancy;
                                    }
                                })
                                return _dis.length > 0 ? _dis[_dis.length - 1].amount : 
                                        !!row.transaction == true ?  row.transaction.amount :  0;
                            }else{
                                return !!row.transaction == true ?  row.transaction.amount :  0;
                            }
                        }
                    },
                    {
                        data:'amount',
                        name:'amount',
                        render: function(data,type,row){
                            return data;
                        }
                    },
                    {
                        data:'status',
                        name:'status',
                        render: function(data,type,row){
                            return data ? 'Paid' : 'Unpaid';
                        }
                    },
                ]

            })
        }

        initCommissionsPartnersTableDatables = function(){
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
                ajax: "{!! route('get_commissions_partners_transactions') !!}",
                order: [[ 0, "desc" ]],
                columns:[
                    {
                        data:'created_at',
                        name:'created_at',
                        render: function(data,type,row){
                            return moment(data).format('llll')+'<br>'+moment(data).fromNow()
                        }
                    },
                    {data:'user.name',name:'user.name'},
                    {
                        data:'transaction.code',
                        name:'transaction.code',
                    },
                    {
                        data:'transaction.user.name',
                        name:'transaction.user.name',
                    },
                    {
                        data:'transaction.amount',
                        name:'transaction.amount',
                    },
                    {
                        data:'amount',
                        name:'amount',
                        render: function(data,type,row){
                            return data;
                        }
                    },
                    {
                        data:'status',
                        name:'status',
                        render: function(data,type,row){
                            return data ? 'Paid' : 'Unpaid';
                        }
                    },
                ]

            })
        }

        initCommissionsBetsTableDatables = function(){
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
                ajax: "{!! route('get_commissions_bets_transactions') !!}",
                order: [[ 0, "desc" ]],
                columns:[
                    {data: 'date_settled', name:'date_settled'},
                    {data: 'user.name', name: 'user.name'},
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
                            returnHtml += `Total Bets: ${bets.length} 
                                        <a class="btn btn-xs btn-primary view-commissions-bets-btn" href="#" data-match-id="${row.match_id}" data-amount="${row.amount}" >
                                            View Bets
                                        </a> ${row.type == 'from-sub' ? '<small>(From SUB)</small>' : ''}
                                        `;
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

            })
        }

        initBadgesTableDatables = function(){
            badges_table = $('#badges_table').DataTable({
                initComplete : function() {
                    var input = $('#badges_table_filter input').unbind(),
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
                    $('#badges_table_filter').append($searchButton, $clearButton);
                },                 
                processing: true,
                serverSide:true,
                responsive: true,
                ajax: "{!! route('get_all_badges') !!}",
                order: [[ 1, "desc" ]],
                columns:[
                    {
                        data:'name',
                        name:'name',
                        render: function(data,type,row){
                            var image_url = "{{url('/public_image')}}/" + row['image'];
                            return data + '<br/><image src="'+ image_url +'" width="60px" />';
                        }
                    },
                    {
                        data:'description',
                        name:'description',
                        render: function(data,type,row){
                            return data;
                        }
                    },
                    {
                        data:'credits',
                        name:'credits',
                        render: function(data,type,row){
                            return data;
                        }
                    },
                    {
                        data:'users',
                        name:'users',
                        render: function(data,type,row){
                            return data.length;
                        }
                    },
                    {
                        searchable:false,
                        render: function(data,type,row){
                            return '<button type="button" class="btn btn-warning btn-xs editBadge" data-badgeid="'+row['id']+'">Edit</button> ' +
                                    '<button type="button" class="btn btn-danger btn-xs delBadge" data-badgeid="'+row['id']+'">Delete</button>';
                        }
                    }
                ]

            });
        }

        initUsersTableDatables = function(){
            users_table = $('#users_table').DataTable({
                initComplete : function() {
                    var input = $('#users_table_filter input').unbind(),
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
                    $('#users_table_filter').append($searchButton, $clearButton);
                },                      
                processing: true,
                serverSide:true,
                responsive: true,
                ajax: "{!! route('users') !!}",
                order: [[ 0, "asc" ]],
                createdRow: function ( row, data, index ) {
                    $('td', row).eq(4).css('width', '100px');
                },
                columns:[
                    {   data:'id'   },
                    {
                        data:'name',
                        render: function(data,type,row){
                            let html = data 
                            const verifiedBy = row['verified_by'].length ? row['verified_by'] : false;
                            const isVerifiedByUs = row['verified_by'].length ? row['verified_by'].filter(v => v.verified_type == '2ez') : false;
                            const isVerifiedByPartners = row['verified_by'].length ? row['verified_by'].filter(v => v.verified_type == 'partner') : false;
                            if(!!verifiedBy){
                                console.log('isVerifiedByUs : ', isVerifiedByUs)
                                if(isVerifiedByUs.length > 0){
                                    html +=  `<br/><span class="badge verified-2ez"> <i class="fa fa-check-circle" aria-hidden="true"></i> Verified by 2ez.bet</span>`;
                                }

                                if(isVerifiedByPartners.length > 0){
                                    html +=  `<br/><span class="badge verified-partner" title="Click to view Partners that verified ${row.name}." data-user-id="${row.id}"> <i class="fa fa-check-circle" aria-hidden="true"></i> Verified by Partner <i class="fa fa-info-circle" aria-hidden="true"></i></span>`;
                                }
                            }
                            

                            html += '<br/><span style="font-size: 75%;">' + row['email'] + '</span>';
                            return html;
                        }
                    },
                    {
                        data:'badges',
                        name:'badges.name',
                        render: function(data,type,row){
                            if(data.length) {
                                var badges = [];
                                $.each(data, function() {
                                    badges.push(this.name);
                                });
                                return badges.join(', ') + ' <a href="#" class="btn btn-primary btn-xs set-badges">Set</a>';
                            } else
                                return 'n/a <a href="#" class="btn btn-primary btn-xs set-badges">Set</a>';
                        }
                    },
                    {
                        data:'provider',
                    },
                    {
                        data:'credits',
                        render: function(data,type,row){
                            // return '&#8369; '+numberWithCommas(parseFloat(data).toFixed(2)) + ' <span class="set-credits fa fa-plus-circle"></span>';
                            return '&#8369; '+numberWithCommas(parseFloat(data).toFixed(2));
                        }
                    },
                    {
                        data:'voucher_code',
                        render: function(data,type,row){
                            return data ? data+' <a href="#" class="btn btn-success btn-xs update-code voucher-code">Update</a>' : 'n/a <a href="#" class="btn btn-primary btn-xs add-code voucher-code">Add</a>'
                        }
                    },
                    {
                        width: '100px',
                        data:'type',
                        render: function(data,type,row){
                            var html = ''
                            if (row['roles'].length > 0) {
                                
                                $.each(row['roles'], function(index, val) {
                                    var letter,color;
                                    switch (parseInt(val.id)) {
                                        case 1:
                                            letter = 'A';
                                            color = 'danger'
                                            break;
                                        case 2:
                                            letter = 'AG';
                                            color = 'warning'
                                            break;
                                        case 3:
                                            letter = 'BT';
                                            color = 'success'
                                            break;
                                        case 4:
                                            letter = 'M';
                                            color = 'primary'
                                            break;
                                        case 5:
                                            letter = 'MM';
                                            color = 'danger';
                                        default:
                                            break;
                                    }
                                    html += '<span class="btn btn-'+color+' btn-circle-micro value">'+letter+'</span>'
                                });
                                html += '<span class="dropdown_display_button fa fa-pencil-square"></span>'
                                return html;
                            }else{
                                var letter,color;
                                switch (data) {
                                    case 'admin':
                                        letter = 'A';
                                        color = 'danger'
                                        break;
                                    case 'user':
                                        letter = 'M';
                                        color = 'primary'
                                        break;
                                    case 'matchmanager':
                                        letter = 'MM';
                                        color = 'danger';
                                        break;
                                    default:
                                        break;
                                }
                                return '<span class="btn btn-'+color+' btn-circle-micro value">'+letter+'</span><span class="dropdown_display_button fa fa-pencil-square"></span>';

                            }
                            // return '<span class="value" data-id="'+row['id']+'" data-type="'+data+'">'+data+'</span><span class="dropdown_display_button fa fa-pencil-square"></span>'
                        }
                    },
                    {
                        data:'unpaid_bets_commissions',
                        searchable:false,
                        ordering: false,
                        render: function(data,type,row) {
                            let amountViaDirect = 0.00;
                            let amountViaPartner = 0.00;
                            let amountViaBets = 0.00;
                            // if(data.filter((el) => el.transaction != null).length){
                            //     amountViaDirect =  data.filter((el) => el.transaction != null).reduce((sum, current) => { // <== Note `sum` parameter
                            //         return sum + parseFloat(current.amount);            // <== Using `sum`
                            //     }, 0);  
                            // }

                            // if(row['unpaid_partner_commissions'].filter((el) => el.transaction != null).length){
                            //     amountViaPartner =  row['unpaid_partner_commissions'].filter((el) => el.transaction != null).reduce((sum, current) => { // <== Note `sum` parameter
                            //         return sum + parseFloat(current.amount);            // <== Using `sum`
                            //     }, 0);  
                            // }

                            amountViaBets = row['unpaid_bets_commissions'].reduce((sum, current) => {
                                 return sum + parseFloat(current.amount);  
                            }, 0);

                            return `
                                    &#8369; ${numberWithCommas(parseFloat(amountViaDirect).toFixed(2))} (Direct) 
                                    <br/> 
                                    &#8369; ${numberWithCommas(parseFloat(amountViaPartner).toFixed(2))} (vP)
                                    <br/> 
                                    &#8369; ${numberWithCommas(parseFloat(amountViaBets).toFixed(2))} (Bets)
                                    `;
                        }
                    },
                    {
                        searchable:false,
                        render: function(data,type,row){
                            // return row.provider != 'facebook' ? '<a href="#" class="btn btn-primary btn-xs add-rewards">Add Rewards</a> <a href="#" class="btn btn-warning btn-xs reset-password">Reset Password</a>' : 
                            //         '<a href="#" class="btn btn-primary btn-xs add-rewards" data-toggle="modal">Add Rewards</a>'
                            // let total_commission = row['unpaid_commissions'].length ? row['unpaid_commissions'].filter((el) => el.transaction != null).reduce((sum, current) => { return sum + parseFloat(current.amount);}, 0) : 0 
                            // let total_partner_commission = row['unpaid_partner_commissions'].length ? row['unpaid_partner_commissions'].filter((el) => el.transaction != null).reduce((sum, current) => { return sum + parseFloat(current.amount);}, 0) : 0 
                            let total_commission = 0; 
                            let total_partner_commission = 0;
                            let amountViaBets = row['unpaid_bets_commissions'].reduce((sum, current) => {
                                 return sum + parseFloat(current.amount);  
                            }, 0);
                            let overall_commission = parseFloat(total_commission + total_partner_commission +amountViaBets) ;

                            let btn_payout = overall_commission >= 1000 ? '<button class="btn btn-success btn-xs show-payout-modal">Payout</button> ' : ''
                            var html = '<button class="btn btn-info btn-xs audit-user" data-toggle="modal" data-target="#auditUserModal">Audit</button> ' +btn_payout+
                                (row.provider != 'facebook' ? '<a href="#" class="btn btn-warning btn-xs reset-password">Reset Password</a>' : '');

                            const currentlyBanned = !!row.banned_until ? new Date(row.banned_until).getTime() > new Date().getTime() : false;
                            html += `<button class="btn btn-danger btn-xs ban-user">${(currentlyBanned ? 'Unban User' : 'Ban User')}</button>`;
                            html += `<button class="btn btn-warning btn-xs message-user"><i class="fa fa-envelope-o" aria-hidden="true"></i></button>`;
                            
                            //Mark as verified by 2ez
                            const isVerifiedByUs = row['verified_by'].length ? row['verified_by'].filter(v => v.verified_type == '2ez') : [];
                            if(isVerifiedByUs.length == 0){
                                let btn_mark_verified = ` <button class="btn btn-primary btn-xs mark-verified-user"><i class="fa fa-check-o" aria-hidden="true"></i> Mark as Verified</button>`;
                                html += btn_mark_verified;
                            }

                            return html;
                        }
                    },
                ]

            })
         };

        initRewardsTableDatables = function(){
            rewards_table = $('#rewards_table').DataTable({
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
            ajax: "{!! route('get_all_rewards') !!}",
            order: [[ 0, "desc" ]],
            columns:[
                {
                    data:'created_at',
                    name:'created_at',
                    render: function(data,type,row){
                        return moment(data).format('llll')+'<br>'+moment(data).fromNow();
                    }
                },
                {
                    data:'user.name',
                    name:'user.name',
                    render: function(data,type,row){
                        return data;
                    }
                },
                {
                    data:'credits',
                    name:'credits',
                    render: function(data,type,row){
                        return data;
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
                    data:'description',
                    name:'description',
                    render: function(data,type,row){
                        return data;
                    }
                },
                {
                    data:'added_by',
                    name:'added_by',
                    render: function(data,type,row){
                        return data ? data.name : 'System';
                    }
                }
            ]

            });
        }

        initBugsTableDatables = function(){
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
                ajax: "{!! route('get-all-reported-bugs',['type' => 'admin']) !!}",
                order: [[ 0, "desc" ]],
                columnDefs: [
                    {
                        render: function (data, type, full, meta) {
                            return "<div class='text-wrap width-200'>" + data + "</div>";
                        },
                        targets: 2
                    }
                ],
                columns: [
                    {data: 'created_at'},
                    {data: 'user.name'},
                    {data: 'subject'},
                    {data: 'comment'},
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
                    {
                        data:'status',
                        render: function(data,type,row){
                            return  data ? '<a href="#" class="btn btn-success btn-xs" data-toggle="modal" disabled>Done</a>' : '<a href="#" class="btn btn-primary btn-xs bug-done-btn" data-toggle="modal">Mark as done</a>'
                        }
                    },
                ],
                drawCallback: function( settings ) {
                    var api = this.api();
                    var pending_ctr = api.rows().data()[0].pending_bugs;
                    if(pending_ctr > 0)
                        $('#bug_ctr').attr('data-count', pending_ctr);
                    else
                        $('#bug_ctr').removeAttr('data-count');
                }
            });
        }


        initPromosTableDatables = function(){
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
                ajax: "{!! route('get-all-promo',['type' => 'admin']) !!}",
                order: [[ 0, "desc" ]],
                columns: [
                    {data: 'created_at'},
                    {data: 'user.name'},
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
                    {
                        data:'status',
                        render: function(data,type,row){
                            return  data ? '<a href="#" class="btn btn-success btn-xs" data-toggle="modal" disabled>Done</a>' : '<a href="#" class="btn btn-primary btn-xs promo-done-btn" data-toggle="modal">Mark as done</a>'
                        }
                    },
                ],
                drawCallback: function( settings ) {
                    var api = this.api();
                    var pending_ctr = api.rows().data()[0].pending_promos;
                    if(pending_ctr > 0)
                        $('#promo_ctr').attr('data-count', pending_ctr);
                    else
                        $('#promo_ctr').removeAttr('data-count');
                }
            });
        }

        initReferralsTableDatables = function(){
            referrals_table = $('#referrals_table').DataTable({
            initComplete : function() {
                var input = $('#referrals_table_filter input').unbind(),
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
                $('#referrals_table_filter').append($searchButton, $clearButton);
            },                 
            processing: true,
            serverSide:true,
            responsive: true,
            ajax: "{!! route('get-all-referrals') !!}",
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
                {data:'owner.name',name:'owner.name'},
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
        }

        initPayoutsTableDatables = function(){
            payout_table = $('#payout_table').DataTable({
                initComplete : function() {
                    var input = $('#payout_table_filter input').unbind(),
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
                    $('#payout_table_filter').append($searchButton, $clearButton);
                },                  
                processing: true,
                serverSide:true,
                responsive: true,
                ajax: "{!! route('get.all.payout') !!}",
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
                    {   data: 'user.name', 
                        name: 'user.name'
                    },
                    {
                        data:'amount',
                        name:'amount',
                        render: function(data,type,row){
                            return '&#8369; '+numberWithCommas(data);
                        }
                    },
                    {
                        data: 'receipt',
                        searchable: false,
                        orderable: false,
                        render: function(data,type,row){
                            return data ? '<a href="#" class="btn btn-default view-receipt"><i class="fa fa-picture-o" aria-hidden="true"></i></a>' : 'n/a'
                        }
                    },
                    {   data: 'process_by.name', 
                        searchable: false,
                        orderable: false,
                    },
                ]

            })
        }

        initRebatesTableDatables = function(){
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
                ajax: "{!! route('get-rebates') !!}",
                order: [[ 0, "desc" ]],
                columns: [
                    {data: 'created_at', name:'created_at'},
                    {data: 'user.name', name:'user.name'},
                    {data:'transaction.code', name:'transaction.code'},
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
        }

        initEarningsTableDatables = function(){
            earnings_table = $('#earnings_table').DataTable({ 
                initComplete : function() {
                    var input = $('#earnings_table_filter input').unbind(),
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
                    $('#earnings_table_filter').append($searchButton, $clearButton);
                },                  
                processing: true,
                serverSide:true,
                responsive: true,
                ajax: "{!! route('get-earnings') !!}",
                order: [[ 0, "desc" ]],
                columns: [
                    {data: 'created_at', name:'created_at'},
                    {data: 'id', name:'id'},
                    {data: 'name', name:'name'},
                    {
                        data: "status",
                        render: function ( data, type, row ) {
                            return '<a href="/match/'+row.id+'" target="_blank">Team A: ' + ((row.team_a) ? row.team_a : 'N/A') + '<br/>' +
                                    'Team B: ' + ((row.team_b) ? row.team_b : 'N/A') + '</a>';
                        }
                    },
                    {
                        data: 'league',
                        render: function ( data, type, row ) {
                            return (row.league && row.league.name) ? row.league.name : 'N/A';
                        }
                    },
                    {
                        data: 'team_winner',
                        render: function ( data, type, row ) {
                            return (row.teamwinner && row.teamwinner.name) ? row.teamwinner.name : 'N/A';
                        }
                    },
                    {data: 'status', name:'status'},
                    {
                        data: 'round_off_earnings',
                        render: function ( data, type, row ) {
                            return  '<span style="display: flex; flex-flow: row nowrap; float: right;">' + ((row.round_off_earnings) ? row.round_off_earnings : '0.00') + '</span>';
                        }

                    }

                ]
            });
        }

        $('#bugs_table').on('click', '.view-bug', function(event) {
            event.preventDefault();
            var row = bugs_table.row($(this).closest('tr')).data();
            var url = "{{url('/reported-bugs/showimage')}}/" + row['id'];
            if (row['hasImage']) {
                // var viewer = new Viewer(document.getElementById('receipt-'+row['id']), {url:'data-url'});
                $('#viewReceipt').find('h5').html('Image')
                $('#viewReceipt').find('.modal-body').html('<image class="img-responsive" src="'+(url)+'"/>')
                $('#viewReceipt').modal('show');
            }else{
                swal("No Image!", 'No image has been uploaded', "warning");

            }
        });

        $('#bugs_table').on('click', '.text-wrap', function(event) {
            event.preventDefault();
            var row = bugs_table.row($(this).closest('tr')).data();
            mainThread(row);
            $('#bug_thread_modal').find('.modal-title').text('[#'+row.id+'] - ' + row.subject);
            $('#bug_thread_modal').modal('show');
        });

        $('#bugs_table').on('click', '.bug-done-btn', function(event) {
            event.preventDefault();
            var row = bugs_table.row($(this).closest('tr')).data();
            var url_image = row.user.avatar;
            $('#bugPromoModal').find('h5').html('Mark as done promotion')
            $('#bugPromoModal').find('input[name=id]').val(row.id);
            $('#bugPromoModal').find('input[name=type]').val('bug');
            $('#bugPromoModal').find('.user_image').html(
                row.user.name + '<br/><img src="'+url_image+'" style="width: 110px"/>');
            $('#bugPromoModal').modal('show');
        });

        $('#promo_table').on('click', '.promo-done-btn', function(event) {
            event.preventDefault();
            var row = promo_table.row($(this).closest('tr')).data();
            var url_image = row.user.avatar;
            $('#bugPromoModal').find('h5').html('Mark as done promotion')
            $('#bugPromoModal').find('input[name=id]').val(row.id);
            $('#bugPromoModal').find('input[name=type]').val('promotion');
            $('#bugPromoModal').find('.user_image').html(
                row.user.name + '<br/><img src="'+url_image+'" style="width: 110px"/>');
            $('#bugPromoModal').modal('show');
        });
        
        $('#bugpromo-save-btn').click(function() {
            var $modal = $(this).closest('.modal');
            var form = new FormData($("#bug_promo_form")[0]);
            $.ajax({
                url: "{{ route('generic-update-status') }}",  //Server script to process data
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                type: 'POST',
                success: function(data){
                    if(data.error)
                        printErrorMsg(data.error);
                    else {
                        $('#bug_promo_form')[0].reset();
                        $('#bug_promo_form .print-error-msg').hide();
                        $('#bugPromoModal').modal('hide');
                        swal("Success!", 'Status successfully updated', "success");
                        bugs_table.ajax.reload( null, false );
                        promo_table.ajax.reload( null, false );
                        rewards_table.ajax.reload( null, false );
                    }
                },
                data: form,
                cache:false,
                contentType: false,
                processData: false,
            });
        });
        
        $('#bugPromoModal').on('hidden.bs.modal', function() {
            $('#bug_promo_form')[0].reset();
            $('#bug_promo_form .print-error-msg').hide();
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

        $('#deposits_table').on('click', '.discrepancy', function(event) {
            event.preventDefault();
            $tr = $(this).closest('tr').hasClass('child') ? 
                $(this).closest('tr').prev() : $(this).closest('tr');
            var transaction = deposits_table.row($tr).data(); 
            var form = $('#discrepancy_form')[0];
            var note_id
            var discrepancy_id
            $('input[type="hidden"][name="user_id"]', form).remove();
            $('input[type="hidden"][name="id"]', form).remove();
            $('input[type="hidden"][name="approveWDiscrepancy"]', form).remove();
            // $('input[type="hidden"][name="note_id"]', form).remove();
            // $('input[type="hidden"][name="discrepancy_id"]', form).remove();
            $('#discrepancy-amount').val('');
            $('textarea#message').val('');
            $("#provider").select2().val(null).trigger('change');
            $image.fileinput('refresh');
            $('#discrepancy_form').find('.message').removeClass('has-error');
            $image.fileinput('destroy');

            if ($image.data('fileinput')) {
                return;
            }
            initPlugin();
            if ($image.val()) {
                $image.trigger('change');
            }
            // if (transaction.notes.length > 0) {
            //     $('textarea#message').val(transaction.notes[transaction.notes.length - 1].message );
            // }else{
            //     $('textarea#message').val('');
            // }
            $('#modalDiscrepancy').find('#bc-code').text(transaction.code);
            $('.add_rebate').hide()
            $('#add_rebate').prop('checked', false)
            discrepancy_table.clear().draw();
           
            if (transaction.discrepancy.length > 0) {
                discrepancy_table.rows.add(transaction.discrepancy).draw();
                discrepancy_table.columns.adjust().draw();
                // discrepancy_id = transaction.discrepancy[transaction.discrepancy.length - 1].id
                // note_id = transaction.notes[transaction.notes.length - 1].id
                // if (transaction.discrepancy[transaction.discrepancy.length - 1].amount != null) {

                //     $('#discrepancy-amount').val(transaction.discrepancy[transaction.discrepancy.length - 1].amount);
                // }else{

                //     $('#discrepancy-amount').val('');
                // }

                // if (transaction.discrepancy[transaction.discrepancy.length - 1].mop != null) {
                //     $('#provider').select2().val(transaction.discrepancy[transaction.discrepancy.length - 1].mop).trigger('change');
                // }else{
                //     $("#provider").select2().val(null).trigger('change');
                // }
                // if (transaction.discrepancy[transaction.discrepancy.length - 1].picture != null) {
                //     $image.fileinput('refresh', {
                //         overwriteInitial: true,
                //         initialPreview: [
                //             url+transaction.discrepancy[transaction.discrepancy.length - 1].picture.replace('/uploads',''),
                //         ],
                //         initialPreviewAsData: true, // identify if you are sending preview data only and not the raw markup
                //         initialPreviewFileType: 'image', // image is the default and can be overridden in config below
                //         initialPreviewConfig: [
                //             {caption: "receipt.jpg", size: 576237, width: "120px",},
                //         ],
                //     });

                // }else{
                //     $image.fileinput('refresh');
                // }
            }
            else{ 
                if(!!transaction.voucher_code && (transaction.voucher_code.toLowerCase() == 'kuya-jop' || transaction.voucher_code.toLowerCase() == 'cbb')){
                    $('.add_rebate').hide()
                }else{
                    $('.add_rebate').show()
                }
            //     $image.fileinput('destroy');

            //     if ($image.data('fileinput')) {
            //         return;
            //     }
            //     initPlugin();
            //     if ($image.val()) {
            //         $image.trigger('change');
            //     }
            //     $("#provider").select2().val(null).trigger('change');
            //     $('#discrepancy-amount').val('');
            }


            // if ($('.file-preview-image').attr('src') != null) {

            // }
            $(form).append(
                $('<input>')
                .attr('type', 'hidden')
                .attr('name', 'user_id')
                .val(transaction['user'].id)
            );
            $(form).append(
                $('<input>')
                .attr('type', 'hidden')
                .attr('name', 'id')
                .val(transaction['id'])
            );
            if (transaction.picture == null) {
                $(form).append(
                    $('<input>')
                    .attr('type', 'hidden')
                    .attr('name', 'approveWDiscrepancy')
                    .val(1)
                );
            }
            // $(form).append(
            //     $('<input>')
            //     .attr('type', 'hidden')
            //     .attr('name', 'note_id')
            //     .val(note_id)
            // );
            // $(form).append(
            //     $('<input>')
            //     .attr('type', 'hidden')
            //     .attr('name', 'discrepancy_id')
            //     .val(discrepancy_id)
            // );

            $('#modalDiscrepancy').modal('show');
            $('#discrepancy-amount').currencyFormat();
        });

        $('#cashout_table').on('click', '.btn-reject', function(event) {
            event.preventDefault();
            var div = $('#reject-modal');
            var transaction = cashout_table.row($(this).closest('tr')).data();
            div.find('textarea').val('')
            div.find('.modal-title').html('Reject <span style="color:red"><b>'+transaction.code+'</b></span>')
            div.find('.user').html('Name: '+transaction.user.name)
            div.find('.code').html('Code: '+transaction.code)
            div.find('.btn-primary').data('transaction_id',transaction.id)
            div.modal('show')
        });

        $('#reject-modal').on('click', '.btn-reject', function(event) {
            var $modal = $(this).closest('.modal');
            var $this = $(this)
            $this.prop('disabled', true);
            $this.button('progress');
            if( $modal.find('textarea').val() != ''){
                $.ajax({
                    url: "{{ route('generic-update-status') }}",  //Server script to process data
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    type: 'POST',
                    data: {admin_comment: $modal.find('textarea').val(),transaction_id:$this.data('transaction_id'),type:'transaction',status:'rejected'},
                    success: function(data){
                        if(data.success){
                            $modal.modal('hide');
                            swal("Success!", data.message, "success");
                            if(!!deposits_table){
                                deposits_table.ajax.reload( null, false );
                            }

                            if(!!cashout_table){
                                cashout_table.ajax.reload( null, false );
                            }
                           
                            
                        }
                        else {
                            swal("Oops!", 'Something went wrong', "danger");
                        }
                        $this.prop('disabled', false);
                        $this.button('reset');
                    },
                });
            }else{
                $modal.find('textarea').parent().addClass('has-error');
                $modal.find('textarea').parent().find('.error-label').text('Message is needed');
                $this.prop('disabled', false);
                $this.button('reset');
            }
        });

        

        $('#deposits_table').on('click', '.btn-reject', function(event) {
            event.preventDefault();
            $tr = $(this).closest('tr').hasClass('child') ? 
                $(this).closest('tr').prev() : $(this).closest('tr');
            var div = $('#reject-modal');
            var transaction = deposits_table.row($tr).data();
            div.find('textarea').val('')
            div.find('.modal-title').html('Reject Deposit <span style="color:red"><b>'+transaction.code+'</b></span>')
            div.find('.user').html('Name: '+transaction.user.name)
            div.find('.code').html('Code: '+transaction.code)
            div.find('.btn-primary').data('transaction_id',transaction.id)
            div.modal('show')
        });

        $('#cashout_table').on('click', '.btn-reject', function(event) {
            event.preventDefault();
            var div = $('#reject-modal');
            $tr = $(this).closest('tr').hasClass('child') ? 
                $(this).closest('tr').prev() : $(this).closest('tr');
            var transaction = cashout_table.row($tr).data();
            div.find('textarea').val('')
            div.find('.modal-title').html('Reject <span style="color:red"><b>'+transaction.code+'</b></span>')
            div.find('.user').html('Name: '+transaction.user.name)
            div.find('.code').html('Code: '+transaction.code)
            div.find('.btn-primary').data('transaction_id',transaction.id)
            div.modal('show')
        });

        $('#reject-modal').on('click', '.btn-reject', function(event) {
            var $modal = $(this).closest('.modal');
            var $this = $(this)
            $this.prop('disabled', true);
            $this.button('progress');
            if( $modal.find('textarea').val() != ''){
                $.ajax({
                    url: '/admin/transaction/'+$this.data('transaction_id')+'/reject',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    type: 'POST',
                    data: {admin_comment: $modal.find('textarea').val()},
                    success: function(data){
                        if(data.success){
                            $modal.modal('hide');
                            swal("Success!", data.message, "success");
                            deposits_table.ajax.reload( null, false );
                            cashout_table.ajax.reload( null, false );
                        }
                        else {
                            $modal.modal('hide');
                            swal("Oops!", data.message, "error");
                        }
                        $this.prop('disabled', false);
                        $this.button('reset');
                    },
                });
            }else{
                $modal.find('textarea').parent().addClass('has-error');
                $modal.find('textarea').parent().find('.error-label').text('Message is needed');
                $this.prop('disabled', false);
                $this.button('reset');
            }
        });

        

        $('#deposits_table').on('click', '.delete', function(event) {
            event.preventDefault();
            var row = deposits_table.row($(this).closest('tr')).data();

            swal({
                title: "Are you sure?",
                text: "Transaction <span style='font-weight:bold;color: #820804'>"+row.code+"</span> will be deleted",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Yes, please continue!",
                showLoaderOnConfirm: true,
                closeOnConfirm: false,
                html: true
            },
            function(){

                $.ajax({
                    url: '{{ route('delete_transaction') }}',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    type: 'DELETE',
                    data: { id: row['id']},
                    success: function(data) {
                        if (data.success) {
                            swal("Deleted!", 'Transaction successfully deleted', "success");
                            deposits_table.ajax.reload( null, false );
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

        $('#deposits_table').on('click', '.view-receipt', function(event) {
            event.preventDefault();
            $tr = $(this).closest('tr').hasClass('child') ? 
                $(this).closest('tr').prev() : $(this).closest('tr');
            var row = deposits_table.row($tr).data();
            if (row['picture']) {
                // var viewer = new Viewer(document.getElementById('receipt-'+row['id']), {url:'data-url'});
                $('#viewReceipt').find('h5').html('Receipt')
                $('#viewReceipt').find('.modal-body').html('<image class="img-responsive" src="'+(url+row['picture']).replace('/uploads','')+'"/>')
                $('#viewReceipt').modal('show');
            }else{
                swal("No Receipt!", 'No receipt has been uploaded', "warning");

            }
        });

        $('#payout_table').on('click', '.view-receipt', function(event) {
            event.preventDefault();
            $tr = $(this).closest('tr').hasClass('child') ? 
                $(this).closest('tr').prev() : $(this).closest('tr');
            var row = payout_table.row($tr).data();
            if (row['receipt']) {
                // var viewer = new Viewer(document.getElementById('receipt-'+row['id']), {url:'data-url'});
                $('#viewReceipt').find('h5').html('Receipt')
                $('#viewReceipt').find('.modal-body').html('<image class="img-responsive" src="'+(url+row['receipt']).replace('/uploads','')+'"/>')
                $('#viewReceipt').modal('show');
            }else{
                swal("No Receipt!", 'No receipt has been uploaded', "warning");

            }
        });

        $('#discrepancy_table').on('click', '.view-receipt', function(event) {
            event.preventDefault();
            $tr = $(this).closest('tr').hasClass('child') ? 
                $(this).closest('tr').prev() : $(this).closest('tr');
            var row = discrepancy_table.row($tr).data();
            if (row['picture']) {
                // var viewer = new Viewer(document.getElementById('receipt-'+row['id']), {url:'data-url'});
                $('#viewReceipt').find('h5').html('Receipt')
                $('#viewReceipt').find('.modal-body').html('<image class="img-responsive" src="'+(url+row['picture']).replace('/uploads','')+'"/>')
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
            if (row['picture']) {
                // var viewer = new Viewer(document.getElementById('receipt-'+row['id']), {url:'data-url'});
                $('#viewReceipt').find('.modal-body').html('<image class="img-responsive" src="'+(url+row['picture']).replace('/uploads','')+'"/>')
                $('#viewReceipt').modal('show');
            }else{
                swal("No Receipt!", 'No receipt has been uploaded', "warning");

            }
        });

        $('.submit_discrepancy').click(function(event) {
            var $this = $(this);
            $this.prop('disabled', true);
            $this.button('progress');
            var form = $('#discrepancy_form')[0];
            $('#disc-image').on('filebatchselected');
            var message = $('textarea#disc-message').val();
            var amount = $('input[name="amount"]').val();
            var mop = $('[name="provider"]', form).val();
            var user_id = $('input[type="hidden"][name="user_id"]', form).val();
            var id = $('input[type="hidden"][name="id"]', form).val();
            var note_id = $('input[type="hidden"][name="note_id"]', form).val();
            var discrepancy_id = $('input[type="hidden"][name="discrepancy_id"]', form).val();
            var photo = $('#modalDiscrepancy').find('.file-preview-image').attr('src');
            var _url = $('input[type="hidden"][name="approveWDiscrepancy"]', form).val() ? '{{ route('approveWDiscrepancy') }}' : '{{ route('adminExtraAction') }}';
            var add_rebate = $('#add_rebate').is(':checked') ? 1 : 0;

            $.ajax({
                url: _url,
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                type: 'POST',
                data: {photo:photo,message:message,amount:amount,mop:mop,user_id:user_id,id:id,note_id:note_id,discrepancy_id:discrepancy_id,add_rebate: add_rebate},
                success: function(data) {

                    if (data.errors) {
                        $.each( data.errors, function( key, value ) {
                            if (key == 'message') {
                                $('#discrepancy_form').find('.message').addClass('has-error');
                                $('#discrepancy_form').find('textarea#message').parent().find('.error-label').text(value[0]);
                            }
                            if (key == 'photo') {
                                $('#discrepancy_form').find('.image').addClass('has-error');
                            }

                            if(key == 'alreadyProcessed'){
                                swal("Error!",value[0], "error");
                            }
                        });
                    }else{
                        if ($('#modalDiscrepancy').find('.file-preview-image').attr('src') != null) {
                            $('#modalDiscrepancy').find('.kv-upload-progress').removeClass('hide').css('display', 'block').find('.progress-bar').css('width', '100%').text('Done')
                            $('#modalDiscrepancy').find('.file-upload-indicator').css({
                                backgroundColor: '#dff0d8',
                                borderColor: '#d6e9c6'
                            });
                            $('#modalDiscrepancy').find('.file-upload-indicator').find('i').removeClass('fa-hand-o-down').removeClass('text-warning').addClass('fa-check-circle').addClass('text-success')

                        }
                        $('#disc-image').fileinput('clear')
                        $('textarea#disc-message').val('')
                        $('#modalDiscrepancy').modal('hide');
                        swal("Added!", 'Discrepancy successfully added', "success");
                        deposits_table.ajax.reload( null, false );

                    }
                    $this.prop('disabled', false);
                    $this.button('reset');
                },
                fail: function(xhr, status, error) {
                    console.log(error);
                    $('#disc-image').fileinput('clear')
                }
            });
            
        });

        $('#cashout_table').on('click', '.mark-as-processed', function(event) {
            event.preventDefault();
            $tr = $(this).closest('tr').hasClass('child') ? 
                $(this).closest('tr').prev() : $(this).closest('tr');
            var row = cashout_table.row($tr).data();
            var form = $('#mark_as_processed_form')[0];
            $_image.fileinput('refresh');
            $('textarea#message').val('');
            $('#waive-fee').attr('checked',false);
            $('#mark_as_processed_form').find('.message').removeClass('has-error');
            $('#modal-mark-as-processed').find('.submit_mark_as_processed').data('transaction',row)
            // $(form).append(
            //     $('<input>')
            //     .attr('type', 'hidden')
            //     .attr('name', 'id')
            //     .val(row['id'])
            // );
            // $(form).append(
            //     $('<input>')
            //     .attr('type', 'hidden')
            //     .attr('name', 'user_id')
            //     .val(row['user'].id)
            // );
            $('#modal-mark-as-processed').modal('show');


        });

        $('.submit_mark_as_processed').click(function(event) {
            var $this = $(this);
            var transaction = $this.data('transaction')
            $this.prop('disabled', true);
            $this.button('progress');
            // $('#image').on('filebatchselected');
            var message = $('#mark_as_processed_form textarea#message').val();
            var user_id = transaction.user_id;
            var id = transaction.id
            var photo = $('#modal-mark-as-processed').find('.file-preview-image').attr('src');
            var waive_fee = $('#waive-fee').is(':checked') ? 1 : 0;

            if (photo == null) {
                $('#mark_as_processed_form').find('.photo').addClass('has-error');
                $('#mark_as_processed_form').find('.photo').find('.error-label').text('Image is required');
                $this.prop('disabled', false);
                $this.button('reset');

            }else{

                $.ajax({
                    url: '{{ route('adminExtraAction') }}',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    type: 'POST',
                    data: {photo:photo,user_id:user_id,id:id,message:message, waive_fee: waive_fee},
                    success: function(data) {

                        if (data.errors) {
                            $.each( data.errors, function( key, value ) {
                                $('#mark_as_processed_form').find('.message').addClass('has-error');
                                $('#mark_as_processed_form').find('textarea#message').parent().find('.error-label').text(value[0]);
                            });
                        }else{
                            if ($('#modal-mark-as-processed').find('.file-preview-image').attr('src') != null) {
                                $('#modal-mark-as-processed').find('.kv-upload-progress').removeClass('hide').css('display', 'block').find('.progress-bar').css('width', '100%').text('Done')
                                $('#modal-mark-as-processed').find('.file-upload-indicator').css({
                                    backgroundColor: '#dff0d8',
                                    borderColor: '#d6e9c6'
                                });
                                $('#modal-mark-as-processed').find('.file-upload-indicator').find('i').removeClass('fa-hand-o-down').removeClass('text-warning').addClass('fa-check-circle').addClass('text-success')

                            }
                            $('#modal-mark-as-processed').modal('hide');
                            $('#modal-mark-as-processed').find('#image').fileinput('clear')
                            swal("Success!", 'Cashout successfully proccessed', "success");
                            $this.removeData('transaction')
                            cashout_table.ajax.reload( null, false );

                        }
                        $this.prop('disabled', false);
                        $this.button('reset');
                    },
                    fail: function(xhr, status, error) {
                        console.log(error);
                        $('#modal-mark-as-processed').find('#image').fileinput('clear')
                    }
                });

            }
            
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

        $('#users_table').on('click', '.voucher-code', function(event) {
            event.preventDefault();
            var user = users_table.row($(this).closest('tr')).data();
            var form = $('#voucher-form')[0];
            $('#voucher-form').removeClass('.has-error');
            $('#voucher-form').find('.form-group').each(function(index, el) {
                $(el).removeClass('has-error')
            });
            $('input[type="hidden"][name="user_id"]', form).remove();
            $('#modal-view-code').find('.modal-body').find('#voucher_code').val('')
            $('#modal-view-code').find('.modal-body').find('#voucher_percent').val('')
            $('#modal-view-code').find('#user-name').text(user.name);
            if (user.voucher_code) {
                $('#modal-view-code').find('.modal-body').find('#voucher_code').val(user.voucher_code)
                $('#modal-view-code').find('.modal-body').find('#voucher_percent').val(user.voucher_percent)
            }
            $(form).append(
                $('<input>')
                .attr('type', 'hidden')
                .attr('name', 'user_id')
                .val(user.id)
            );
            $('#modal-view-code').modal('show');
        });

        $('#users_table').on('click', '.show-payout-modal', function(event) {
            event.preventDefault();
            $tr = $(this).closest('tr').hasClass('child') ? $(this).closest('tr').prev() : $(this).closest('tr');
            $('#affliate-payout-until').val(null).trigger('change');
            let user = users_table.row($tr).data();
            //let total_commission = user.unpaid_commissions.filter((el) => el.transaction != null).reduce((sum, current) => { return sum + parseFloat(current.amount);}, 0)
            //let total_partner_commission = user.unpaid_partner_commissions.filter((el) => el.transaction != null).reduce((sum, current) => { return sum + parseFloat(current.amount);}, 0)
            let total_commission = 0;
            let total_partner_commission = 0;
            let total_bets_commimssion = user.unpaid_bets_commissions.reduce((sum, current) => { return sum + parseFloat(current.amount);}, 0)
            let overall_commission = parseFloat(total_partner_commission+total_commission+total_bets_commimssion);

            var $modal = $('#payout_modal')
            $modal.find('.modal-title').html('Payout for '+user.name)
            $modal.find('#payout-amount').val(overall_commission)
            $modal.find('.user_payout_btn').data('user',user)
            $modal.modal('show');
        });

        $('#affliate-payout-until').datetimepicker({
            viewMode: 'days',
            maxDate : moment().subtract(1, 'day'),
            format: 'YYYY-MM-DD',
        }).on('dp.change', function(e){
            //  $(this).parent().removeClass('has-error'); 

            const userPayoutBtn = $('.user_payout_btn');
            const selectedDate = $(this).val();
            const user = $(userPayoutBtn).data('user')
            // let amount = user.unpaid_commissions.filter((el) => el.transaction != null).reduce((sum, current) => { return sum + parseFloat(current.amount);}, 0)
            // let amountPartner = user.unpaid_partner_commissions.filter((el) => el.transaction != null).reduce((sum, current) => { return sum + parseFloat(current.amount);}, 0)

            // let amount = user.unpaid_commissions.filter((commission) => {
            //     return !!commission.transaction &&  moment(  commission.created_at).isSameOrBefore(selectedDate, 'date');
            // }).reduce((sum, current) => { return sum + parseFloat(current.amount);}, 0)

            // let amountPartner = user.unpaid_partner_commissions.filter((commission) => {
            //     return !!commission.transaction &&  moment( commission.transaction.created_at).isSameOrBefore(selectedDate, 'date');
            // }).reduce((sum, current) => { return sum + parseFloat(current.amount);}, 0) 
            let amount = 0;
            let amountPartner = 0;
            console.log('user ::: ', user)

            let amountBetsCommissions = user.unpaid_bets_commissions.filter((commission) => {
                return !!commission &&  moment( commission.date_settled).isSameOrBefore(selectedDate, 'date');
            }).reduce((sum, current) => { return sum + parseFloat(current.amount);}, 0) 

            const totalAmount = parseFloat(amount + amountPartner+amountBetsCommissions);

            $('#payout-amount').val(totalAmount);
        }); 

        $('.user_payout_btn').click(function(event) {
            var $this = $(this);
            $this.prop('disabled', true);
            $this.button('progress');
            let user = $this.data('user')
            const totalAmount = $('#payout-amount').val();
            const selectedDate = $('#affliate-payout-until').val();

            let message = $('#payout_modal textarea').val();
            let user_id = user.id;
            let photo = $('#payout_modal').find('.file-preview-image').attr('src');

            let payoutData = {
                photo,
                user_id,
                message,
                amount: totalAmount,
                untilDate: selectedDate
            }

            if (photo == null) {
                $('#mark_as_processed_form').find('.photo').addClass('has-error');
                $('#mark_as_processed_form').find('.photo').find('.error-label').text('Image is required');
                $this.prop('disabled', false);
                $this.button('reset');

            }else if(!!selectedDate == false){
                $('#mark_as_processed_form').find('.photo').addClass('has-error');
                $('#mark_as_processed_form').find('.photo').find('.error-label').text('Date is required');
                $this.prop('disabled', false);
                $this.button('reset');
            }else{

                $.ajax({
                    url: '{{ route('payout.user') }}',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    type: 'POST',
                    data: payoutData,
                    success: function(data) {

                        if (data.errors) {
                            $.each( data.errors, function( key, value ) {
                                $('#payout_modal').find('.message').addClass('has-error');
                                $('#payout_modal').find('textarea').parent().find('.error-label').text(value[0]);
                            });
                        }else{
                            if ($('#payout_modal').find('.file-preview-image').attr('src') != null) {
                                $('#payout_modal').find('.kv-upload-progress').removeClass('hide').css('display', 'block').find('.progress-bar').css('width', '100%').text('Done')
                                $('#payout_modal').find('.file-upload-indicator').css({
                                    backgroundColor: '#dff0d8',
                                    borderColor: '#d6e9c6'
                                });
                                $('#payout_modal').find('.file-upload-indicator').find('i').removeClass('fa-hand-o-down').removeClass('text-warning').addClass('fa-check-circle').addClass('text-success')

                            }
                            $('#payout_modal').modal('hide');
                            $('#payout_modal').find('#payout-image-2').fileinput('clear')
                            swal("Success!", data.message, "success");
                            $this.removeData('user')
                            users_table.ajax.reload( null, false );

                        }
                        $this.prop('disabled', false);
                        $this.button('reset');
                    },
                    fail: function(xhr, status, error) {
                        console.log(error);
                        $('#payout_modal').find('#payout-image-2').fileinput('clear')
                    }
                });

            }
            
        });
        
        $('#users_table').on('click', '.set-badges', function(event) {
            event.preventDefault();
            var user = users_table.row($(this).closest('tr')).data();
            $('#setBadgesModal').find('.modal-title').html('Set Badges for <span style="color: #820804">' + user.name + '</span>');
            $('#setBadgesModal').find('input[name=user_id]').val(user.id);
            $.each(user.badges, function() {
                $('#setBadgesModal select[name="badges[]"]')
                        .find('option[value="'+this.id+'"]')
                        .prop('selected',true);
            });
            $('#badges-selection').multiselect('refresh');
            $('#setBadgesModal').modal('show');
        });
        
        $('#users_table').on('click', '.set-credits', function(event) {
            event.preventDefault();
            var user = users_table.row($(this).closest('tr')).data();
            $('#setCreditsModal').find('.modal-title').html('Update Credits for <span style="color: #820804">' + user.name + '</span>');
            $('#setCreditsModal').find('input[name=user_id]').val(user.id);
            $('#setCreditsModal').find('input[name=credits]').val(user.credits);
            $('#setCreditsModal').modal('show');
        });

        $('.submit_voucher').click(function(event) {
            var $this = $(this);
            var form = $('#voucher-form')[0];
            $this.prop('disabled', true);
            $this.button('progress');
            $.ajax({
                url: '{{ route('add_update_voucher') }}',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                type: 'POST',
                data:  $(form).serialize(),
                success: function(data) {

                    if (data.errors) {
                        $.each( data.errors, function( key, value ) {
                                $('#voucher-form').find(':input[name='+ key +']').parent().addClass('has-error');
                                $('#voucher-form').find(':input[name='+ key +']').parent().find('.error-label').text(value[0]);
                        });
                    }else{
                        $('#modal-view-code').modal('hide');
                        swal("Success!", 'Voucher code successfully added', "success");
                        users_table.ajax.reload( null, false );

                    }
                    $this.prop('disabled', false);
                    $this.button('reset');
                },
                fail: function(xhr, status, error) {
                    console.log(error);
                }
            });
            
        });

        $('#users_table').on('click', '.reset-password', function(event) {
            event.preventDefault();
            var user = users_table.row($(this).closest('tr')).data();
            var form = $('#password-form')[0];
            $('#password-form').removeClass('.has-error');
            $('#password-form').find('.form-group').each(function(index, el) {
                $(el).removeClass('has-error')
            });
            $('input[type="hidden"][name="user_id"]', form).remove();
            $('#modal-resetpassword').find('.modal-body').find('#password').val('')
            $('#modal-resetpassword').find('#user-name').text(user.name);
            $(form).append(
                $('<input>')
                .attr('type', 'hidden')
                .attr('name', 'user_id')
                .val(user.id)
            );
            $('#modal-resetpassword').modal('show');
        });

        $('.submit_password').click(function(event) {
            var $this = $(this);
            var form = $('#password-form')[0];
            $this.prop('disabled', true);
            $this.button('progress');
            $.ajax({
                url: '{{ route('reset_password') }}',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                type: 'POST',
                data:  $(form).serialize(),
                success: function(data) {

                    if (data.errors) {
                        $.each( data.errors, function( key, value ) {
                                $('#password-form').find(':input[name='+ key +']').parent().addClass('has-error');
                                $('#password-form').find(':input[name='+ key +']').parent().find('.error-label').text(value[0]);
                        });
                    }else{
                        $('#modal-resetpassword').modal('hide');
                        swal("Success!", 'Password successfully changed', "success");
                        users_table.ajax.reload( null, false );

                    }
                    $this.prop('disabled', false);
                    $this.button('reset');
                },
                fail: function(xhr, status, error) {
                    console.log(error);
                }
            });
            
        });

        
        
        $('#createBadgeBtn').click(function() {
            var form = new FormData($("#badgesForm")[0]);
            $.ajax({
                url: "{{ route('setbadges') }}",  //Server script to process data
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                type: 'POST',
                success: function(data){
                    if(data.error)
                        printErrorMsg(data.error);
                    else {
                        $('#badgesForm')[0].reset();
                        $('#badgesForm .print-error-msg').hide();
                        $('#createBadgesModal').modal('hide');
                        badges_table.ajax.reload();
                    }
                },
                data: form,
                cache:false,
                contentType: false,
                processData: false,
            });
        });
        
        $(document).on('click', '#badges_table .editBadge', function() {
            var badge = badges_table.row($(this).closest('tr')).data();
            $('#createBadgesModal .modal-title').text('Edit current Badge');
            $('#badgesForm').find(':input[name=badge_id]').val(badge.id);
            $('#badgesForm').find(':input[name=name]').val(badge.name);
            $('#badgesForm').find(':input[name=description]').val(badge.description);
            $('#badgesForm').find(':input[name=credits]').val(badge.credits);
            $('#createBadgesModal').modal('show');
            $('#createBadgeBtn').removeClass('btn-primary').addClass('btn-warning');
            $('#createBadgeBtn').button('edit');
        });
        
        $(document).on('click', '#badges_table .delBadge', function() {
            var badgeid = $(this).data('badgeid');
            swal({
                title: "Delete this badge?",
                text: "The selected badge will be deleted and cannot be retreived!",
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
                    url:'{{route("json_badge_delete")}}',
                    type:'DELETE',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    data: {badge_id: badgeid},
                    success:function(data){
                        if(data.error) {
                            swal("Error!", data.error, "error");
                        } else {
                            swal("Badge deleted!", "The badge has now been deleted.", "success");
                            badges_table.ajax.reload();
                        }
                    }
                });
            });
        });
        
        $('#users_table').on('click', '.add-rewards', function(event) {
            var user = users_table.row($(this).closest('tr')).data();
            var url_image = user.avatar;
            $('#addRewardsModal').find('input[name=user_id]').val(user.id);
            $('#addRewardsModal').find('.user_image').html(
                    user.name + '<br/><img src="'+url_image+'" style="width: 110px"/>');
            $('#addRewardsModal').modal('show');
        });
        
        $('#setBadgeBtn').click(function() {
            var $modal = $(this).closest('.modal');
            var userid = $modal.find('input[name=user_id]').val();
            var selected_badges = $modal.find('select[name="badges[]"]').val();
            $.ajax({
                url:'{{route("assignbadges")}}',
                type:'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                data: {user_id: userid, badges: selected_badges},
                success:function(data){
                    if(data.success) {
                        users_table.ajax.reload(null, false);
                        $modal.modal('hide');
                    }
                }
            });
        });
        
        $('#setCreditBtn').click(function() {
            var $modal = $(this).closest('.modal');
            var userid = $modal.find('input[name=user_id]').val();
            var credits = $modal.find('input[name=credits]').val();
            $.ajax({
                url:'{{route("set_user_credits")}}',
                type:'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                data: {user_id: userid, credits: credits},
                success:function(data){
                    if(data.errors) {
                        $.each( data.errors, function( key, value ) {
                            $('#setCreditsForm').find(':input[name='+ key +']').parent().addClass('has-error');
                            $('#setCreditsForm').find(':input[name='+ key +']').parent().find('.error-label').text(value[0]);
                        });
                    } else {
                        users_table.ajax.reload(null, false);
                        $modal.modal('hide');
                    }
                }
            });
        });
        
        $('#addRewardBtn').click(function() {
            var $modal = $(this).closest('.modal');
            var form = new FormData($("#add_rewards_form")[0]);
            $.ajax({
                url: "{{ route('set_user_rewards') }}",  //Server script to process data
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                type: 'POST',
                success: function(data){
                    if(data.error)
                        printErrorMsg(data.error);
                    else {
                        $('#add_rewards_form')[0].reset();
                        $('#add_rewards_form .print-error-msg').hide();
                        $('#addRewardsModal').modal('hide');
                        users_table.ajax.reload();
                        rewards_table.ajax.reload();
                    }
                },
                data: form,
                cache:false,
                contentType: false,
                processData: false,
            });
        });

 
        //banning users
        $('#users_table').on('click', '.ban-user', function(event) {
            event.preventDefault();
            const user = users_table.row($(this).closest('tr')).data();
            const currentlyBanned = !!user.banned_until ? new Date(user.banned_until).getTime() > new Date().getTime() : false;
            const title =  currentlyBanned ? `Unban User: ${user.name}` : `Banning user <strong>${user.name}</strong>`;
            const swalText = currentlyBanned ? `Revoking ban status of user ${user.name}` : `Banning this user will prevent him/her from logging in to the site.`;

        
            swal({
                title:title ,
                text: swalText,
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-primary",
                confirmButtonText: "Yes, please continue.",
                showLoaderOnConfirm: true,
                closeOnConfirm: false,
                html:true
            },
            function(confirmed){
                if(confirmed){
                    $.ajax({
                        type: 'POST',
                        url:  "{!! route('update_ban_status') !!}",
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        dataType: 'json',
                        data: {
                            id: user.id,
                            status: currentlyBanned ? 1 : 0,
                        },
                        success: function(response) {
                            const { success, message } = response;
                            swal({
                                    title: message, 
                                    type: success ? "success" : "error",
                                    html: true
                             }); 
                        }
                    });   
                }


                
        
            });    

        });


         //sending message to user
        $('#users_table').on('click', '.message-user', function(event) {
            event.preventDefault();
            const user = users_table.row($(this).closest('tr')).data();
            const currentlyBanned = !!user.banned_until ? new Date(user.banned_until).getTime() > new Date().getTime() : false;
            const title =  `Message <strong>${user.name}</strong>`;
            const swalText = `
                                <textarea id="message-to-user" class="form-control message-user-textarea" id="exampleFormControlTextarea1" rows="6" placheholder="Your message here."></textarea>`;
            swal({
                title:title ,
                text: swalText,
                icon: `<i class="fa fa-envelope-o" aria-hidden="true"></i>`,
                type: "info",
                showCancelButton: true,
                confirmButtonClass: "btn-primary",
                confirmButtonText: "Send Message",
                showLoaderOnConfirm: true,
                closeOnConfirm: false,
                html:true
            },
            function(confirmed){
                if(confirmed){
                    const messageToUser = $('#message-to-user').val();
                    $.ajax({
                        type: 'POST',
                        url:  "{!! route('user.message.create') !!}",
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        dataType: 'json',
                        data: {
                            id: user.id,
                            message: messageToUser
                        },
                        success: function(response) {
                            const { success, message } = response;
                            swal({
                                    title: message, 
                                    type: success ? "success" : "error",
                                    html: true
                             }); 
                        }
                    });   
                }


                
        
            });    

        });      

         //verifying user
        $('#users_table').on('click', '.mark-verified-user', function(event) {
            event.preventDefault();
            const user = users_table.row($(this).closest('tr')).data();
            const currentlyBanned = !!user.banned_until ? new Date(user.banned_until).getTime() > new Date().getTime() : false;
            const title =  `Verify User`;
            const swalText = `Mark <strong>${user.name}</strong> as Verified by 2ez.bet?`;
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
                    const messageToUser = $('#message-to-user').val();
                    $.ajax({
                        type: 'POST',
                        url:  "{!! route('user.mark.verified') !!}",
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        dataType: 'json',
                        data: {
                            user_id: user.id
                        },
                        success: function(response) {
                            const { success, message } = response;
                            swal({
                                    title: message, 
                                    type: success ? "success" : "error",
                                    html: true
                            }); 
                            users_table.ajax.reload( null, false );
                        }
                    });   
                }
            });  
            


        });       
        
        //view partners that verifed
        //verified-partner
        $('#users_table').on('click', '.verified-partner', function(event) {
            event.preventDefault();
            const user = users_table.row($(this).closest('tr')).data();
            const currentlyBanned = !!user.banned_until ? new Date(user.banned_until).getTime() > new Date().getTime() : false;
            const title =  `${user.name} was verified by following Partner/s:`;


            $.ajax({
                type: 'GET',
                url:  "{!! route('user.verified.by.partners') !!}",
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

        
        $('#setBadgesModal').on('hidden.bs.modal', function() {
            $(this).find('.modal-title').html('Set Badges');
            $('#setBadgesForm')[0].reset();
            $('#badges-selection').multiselect('refresh');
        });
        
        $('#addRewardsModal').on('hidden.bs.modal', function() {
            $('#add_rewards_form')[0].reset();
            $('#add_rewards_form .print-error-msg').hide();
        });
        
        $('#createBadgesModal').on('hidden.bs.modal', function() {
            $(this).find('.modal-title').html('Add Badges');
            $('#badgesForm')[0].reset();
            $('#badgesForm .print-error-msg').hide();
            $('#createBadgeBtn').button('reset');
            $('#createBadgeBtn').removeClass('btn-warning').addClass('btn-primary');
        });
        
        $('#setCreditsModal').on('hidden.bs.modal', function() {
            $(this).find('.modal-title').html('Set Credits');
            $('#setCreditsForm')[0].reset();
            $('#setCreditsForm :input').closest('.has-error').removeClass('has-error');
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
                    var data = {}, bank=mop[0], bopen, bclose, mopen, mclose, accountnumber;
                    var container = $("#manual-steps-template").html();
                    var transactionData = transaction.data;
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
                    }
                    data.bank = bank;
                    data.bopen = bopen;
                    data.bclose = bclose;
                    data.mopen = mopen;
                    data.mclose = mclose;

                    data.accountnumber = !!transactionData.account_number == true ? transactionData.account_number : accountnumber;
                    data.accountname = !!transactionData.account_name == true ? transactionData.account_name : accountname;
                    data.amount = numberWithCommas(transaction.amount);
                    data.code = transaction.code;
                    $('#view-details').find('.modal-body').find('#deposit-steps').append(Mustache.render(container, data));
                    break;
                case 'remittance':
                    var data = {}
                    data.amount = numberWithCommas(transaction.amount);
                    data.code = transaction.code;
                    var container = $("#"+mop[0]+"-remittance").html();

                    var transactionData = transaction.data;
                    data.full_name =  !!transactionData.full_name == true ? transactionData.full_name : "{{$settings['remittance-name']}}";
                    data.mobile_number =  !!transactionData.mobile_number == true ? transactionData.mobile_number : "{{$settings['remittance-number']}}";
                    data.location =  !!transactionData.location == true ? transactionData.location : "{{$settings['remittance-location']}}";

                    $('#view-details').find('.modal-body').find('#deposit-steps').append(Mustache.render(container, data));
                    break;
                case 'online':
                    var container = $("#"+mop[0]+"-desktop").html();
                    var data = {}

                    var transactionData = transaction.data;
                    switch (mop[0]) {
                        case 'BDO':
                            accountnumber = "{{$settings['bdo-account-number']}}"
                            accountname = "{{$settings['bdo-account-name']}}"
                            break;
                        case 'BPI':
                            accountnumber = "{{$settings['bpi-account-number']}}"
                            accountname = "{{$settings['bpi-account-name']}}"
                            break;
                        case 'Metrobank':
                            accountnumber = "{{$settings['metro-account-number']}}"
                            accountname = "{{$settings['metro-account-name']}}"
                            break;
                        case 'Securitybank':
                            accountnumber = "{{$settings['security-account-number']}}"
                            accountname = "{{$settings['security-account-name']}}"
                            break;
                    }

                    data.accountnumber = !!transactionData.account_number == true ? transactionData.account_number : accountnumber;
                    data.accountname = !!transactionData.account_name == true ? transactionData.account_name : accountname;
                    data.code = transaction.code;
                    $('#view-details').find('.modal-body').find('#deposit-steps').append(Mustache.render(container, data));
                    break;
            }

        }

        // The first thread - from a user
        function mainThread(ticket){
            // console.log(ticket);
            var main_frag = document.createDocumentFragment(), thread_box = document.createElement('div'), thread_img_square = document.createElement('div'),
                thread_icon = document.createElement('div'), thread_img = document.createElement('img'), thread_info_panel = document.createElement('div'),
                thread_user_name = document.createElement('p'), thread_span = document.createElement('span'), thread_content = document.createElement('div');

                $(thread_img).attr('src', "{{asset('images/default_avatar.png')}}").css('width', '100%');
                $(thread_icon).addClass('thread_icon').append(thread_img);
                $(thread_img_square).addClass('thread_img_square').append(thread_icon);
                $(thread_user_name).addClass('thread_user_name').text(ticket.user.name);
                $(thread_span).text(moment(ticket.created_at).format('LLLL')+' | '+moment(ticket.created_at).fromNow());
                $(thread_info_panel).addClass('thread_info_panel').append(thread_user_name).append(thread_span);
                $(thread_content).addClass('thread_content').text(ticket.comment);
                $(thread_box).addClass('thread_box').append(thread_img_square).append(thread_info_panel).append(thread_content);
                main_frag.append(thread_box);
                $('.ticket_thread_column').html('').append(main_frag);
                if(ticket.thread.length > 0){
                    replyThreads(ticket.thread, ticket.user.id);
                }
        }

        function replyThreads(thread, user){
            // console.log(thread)
            var main_frag = document.createDocumentFragment();
            for(var i = 0; i < thread.length; i++){
                var thread_box = document.createElement('div'), thread_img_square = document.createElement('div'), thread_icon = document.createElement('div'), 
                    thread_img = document.createElement('img'), thread_info_panel = document.createElement('div'), thread_user_name = document.createElement('p'), 
                    thread_span = document.createElement('span'), thread_content = document.createElement('div'), class_name = 'thread_box_owner reply_box_thread';
                if(thread[i].commented_by.id == user){
                    class_name = 'thread_box reply_box_thread';
                }
                $(thread_img).attr('src', "{{asset('images/default_avatar.png')}}").css('width', '100%');
                $(thread_icon).addClass('thread_icon').append(thread_img);
                $(thread_img_square).addClass('thread_img_square').append(thread_icon);
                $(thread_user_name).addClass('thread_user_name').text(thread[i].commented_by.name);
                $(thread_span).text(moment(thread[i].created_at).format('LLLL')+' | '+moment(thread[i].created_at).fromNow());
                $(thread_info_panel).addClass('thread_info_panel').append(thread_user_name).append(thread_span);
                $(thread_content).addClass('thread_content').text(thread[i].comment);
                $(thread_box).addClass(class_name).append(thread_img_square).append(thread_info_panel).append(thread_content);
                main_frag.append(thread_box);
            }
            $('.ticket_thread_column').append(main_frag);
        }

        /**
         * Change role for a user 
         */
        $(document).on('click', '#users_table .dropdown_display_button', function() {
            var user = users_table.row($(this).closest('tr')).data();
            var select = document.createElement('select');
            select.innerHTML = "<option value='1'>admin</option>"
                               +"<option value='2'>agent</option>"
                               +"<option value='3'>beta-tester</option>"
                               +"<option value='4'>member</option>"
                               +"<option value='5'>match-manager</option>"
                               ;
            var thisCell = this.parentNode;
            var currtype = $(this.parentNode).find('.value').data('type');
            var id = user.id;
            var cancelled = false;
            var hint = "<span class='cell_edit_hint'>Press escape to cancel selection.</span>";

            this.parentNode.appendChild(select);
            $(this.parentNode).append(hint);
            $(this.parentNode).find('.value').hide();
            $(select).val($(this.parentNode).find('.value').data('type'));
            $(select).focus();

            $(select).on('keyup',function(evt) {
                if (evt.keyCode == 27) {
                    cancelled = true;
                    this.blur();
                }
            });

            $(select).change(function(){});

            $(select).focusout(function() {
                thisCell.removeChild(this);
                $(thisCell).find('.value').show();
                $(thisCell).find('.cell_edit_hint').remove();
                if(!cancelled && currtype != this.value && this.value != '') { 
                    var type, role_id = this.value;
                    switch (this.value) {
                        case '1':
                            type = 'admin'
                            break;
                        case '2':
                            type = 'agent'
                            break;
                        case '3':
                            type = 'beta-tester'
                            break;
                        case '4':
                            type = 'member'
                            break;
                        case '5':
                            type = 'match-manager';
                            break;
                        default:
                            // statements_def
                            break;
                    }
                    swal({
                        title: "Are you sure?",
                        text: "Add "+type+" role to "+user.name,
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonClass: "btn-danger",
                        confirmButtonText: "Yes, please continue!",
                        showLoaderOnConfirm: true,
                        closeOnConfirm: false,
                        html:true
                    },
                    function(){

                        $.ajax({
                            url: '{{ route("add_role") }}',
                            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                            type: 'POST',
                            data: {id: id, type: role_id },
                            success: function(obj) {
                                // $(thisCell).find('.value').text(this.value);
                                // $(thisCell).find('.value').data('id', id); 
                                if(obj.success){
                                    swal("Success!", 'User role successfully added', "success");
                                }else{
                                    swal("Oops!", 'Role already exist for the user', "error");
                                }
                                users_table.ajax.reload( null, false );
                            }
                        });
                    });
                }
            });
        });

        //audit stuff
        audit_user_bets = $('#auditUserModal #audituser-table-bets').DataTable({
        processing: true,
        serverSide:true,
        responsive: true,
        ajax: "{!! route('get-audit-data', ['type' => 'bets']) !!}",
        columns: [
            {
                data:'updated_at',
                name:'updated_at'
            },
            {
                data:'match',
                name:'match.name',
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
                data: 'team.name',
                name: 'team.name'
            },
            {
                data: 'amount',
                render: function(data,type,row){
                    return '&#8369; '+numberWithCommas(data);
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
            }
        ]
    });
    audit_user_deposits = $('#auditUserModal #audituser-table-deposits').DataTable({
        searching: false,
        lengthChange: false,
        processing: true,
        serverSide:true,
        responsive: true,
        ajax: "{!! route('get-audit-data', ['type' => 'deposits']) !!}",
        columns: [
            {
                data:'created_at',
                name:'created_at',
            },
            {   data:'code',name:'code' },
            {
                data:'amount',
                name:'amount',
                render: function(data,type,row){
                    if(row.discrepancy.length) {
                        var disc_amount = row.discrepancy.reduce(function(total, currVal) {
                            return total + currVal.amount;
                        }, 0);
                        return '&#8369; '+numberWithCommas(data)+
                            ' (<span style="font-weight:bold; color: blue">'+
                            numberWithCommas(disc_amount)+
                            '</span>)';
                    } else
                        return '&#8369; '+numberWithCommas(data);
                }
            },
            {data:'data.mop',name:'data'},
            {
                data:'status',
                name:'status',
                render: function(data,type,row){
                    // return row['picture'] == null  && data != 'completed' ? 'incomplete' : data == 'completed' ? 'Approved and Completed' : 'Needs Approval' 
                    if(data == 'rejected'){
                        return 'rejected'
                    }else{
                        if(row['picture'] == null  && data != 'completed'){
                            return 'incomplete'
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
                data:'process_by',
                searchable:false,
                render: function(data,type,row){
                    return data == null ? 'n/a' : data['name']
                }
            }
        ]
    });
    audit_user_cashouts = $('#auditUserModal #audituser-table-cashouts').DataTable({
        searching: false,
        lengthChange: false,
        processing: true,
        serverSide:true,
        responsive: true,
        ajax: "{!! route('get-audit-data', ['type' => 'cashouts']) !!}",
        columns: [
            {
                data:'created_at',
                name:'created_at',
            },
            {   data:'code',name:'code' },
            {
                data:'amount',
                name:'amount',
                render: function(data,type,row){
                    return '&#8369; '+numberWithCommas(data);
                }
            },
            {data:'data.mop',name:'data'},
            {
                data:'status',
                name:'status',
                render: function(data,type,row){
                    // return row['picture'] == null  && data != 'completed' ? 'incomplete' : data == 'completed' ? 'Approved and Completed' : 'Needs Approval' 
                    if(data == 'rejected'){
                        return 'rejected'
                    }else{
                        if(row['picture'] == null  && data != 'completed'){
                            return 'incomplete'
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
                data:'process_by',
                searchable:false,
                render: function(data,type,row){
                    return data == null ? 'n/a' : data['name']
                }
            }
        ]
    });
    
    audit_user_partner_deposits = $('#auditUserModal #audituser-table-partner-deposits').DataTable({
        searching: false,
        lengthChange: false,
        processing: true,
        serverSide:true,
        responsive: true,
        ajax: "{!! route('get-audit-data', ['type' => 'partner_deposits']) !!}",
        columns: [
            {
                data:'created_at',
                name:'created_at',
            },
            {   data:'code',name:'code' },
            {   data:'partner.partner_name',name:'partner.partner_name' },
            {
                data:'amount',
                name:'amount',
                render: function(data,type,row){
                    return '&#8369; '+numberWithCommas(data);
                }
            },
            {
                data:'status',
                name:'status',
                render: function(data,type,row) {
                    if(row['picture'] == null && data < 1 ) {
                        return "Incomplete";
                    }
                    else{
                        switch(data){
        case "1":
                            case 1:
                                return "Approved"; break;
        case "2":
                            case 2:
                                return "Rejected"; break;
                            default: 
                                return "Needs Approval";
                        }
                    }
                }
            }
        ]
    });
    
    audit_user_partner_cashouts = $('#auditUserModal #audituser-table-partner-cashouts').DataTable({
        searching: false,
        lengthChange: false,
        processing: true,
        serverSide:true,
        responsive: true,
        ajax: "{!! route('get-audit-data', ['type' => 'partner_cashouts']) !!}",
        columns: [
            {
                data:'created_at',
                name:'created_at',
            },
            {   data:'code',name:'code' },
            {   data:'partner.partner_name',name:'partner.partner_name' },
            {
                data:'amount',
                name:'amount',
                render: function(data,type,row){
                    return '&#8369; '+numberWithCommas(data);
                }
            },
            {
                data:'status',
                name:'status',
                render: function(data,type,row) {
                    if(row['picture'] == null && data < 1 ) {
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
            }
        ]
    });

    audit_user_rebates = $('#auditUserModal #audituser-table-rebates').DataTable({
        searching: false,
        lengthChange: false,
        processing: true,
        serverSide:true,
        responsive: true,
        ajax: "{!! route('get-audit-data', ['type' => 'rebate']) !!}",
        columns: [
            {
                data:'created_at',
                name:'created_at',
            },
            {   data:'transaction.code',name:'transaction.code' },
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
            {
                data:'collected',
                name:'collected',
                render: function(data,type,row){
                    return '&#8369; '+numberWithCommas(data);
                }
            },
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

    audit_user_rewards = $('#auditUserModal #audituser-table-rewards').DataTable({
        searching: false,
        lengthChange: false,
        processing: true,
        serverSide:true,
        responsive: true,
        ajax: "{!! route('get-audit-data', ['type' => 'reward']) !!}",
        columns: [
            {
                data:'created_at',
                name:'created_at',
            },
            {   data:'type',name:'type' },
            {
                data:'credits',
                name:'credits',
                render: function(data,type,row){
                    return '&#8369; '+numberWithCommas(data);
                }
            },
            {
                data:'description',
                name:'description'
            },
            {
                data:'added_by.name',
                name:'added_by.name',
            },
        ]
    });   
    
    
    audit_user_gift_codes = $('#auditUserModal #audituser-table-gift-codes').DataTable({
        searching: false,
        lengthChange: false,
        processing: true,
        serverSide:true,
        responsive: true,
        ajax: "{!! route('get-audit-data', ['type' => 'gift_code']) !!}",
        columns: [
            {
                data:'date_redeemed',
                name:'date_redeemed'
            },            
            {
                data:'code',
                name:'code',
            },
            {
                data:'amount',
                name:'amount',
                render: function(data,type,row){
                    return '&#8369; '+numberWithCommas(data);
                }
            },
            {
                data:'description',
                name:'description',
            },
        ]
    });   

        $(document).on('click', '#users_table .audit-user', function() {
            $tr = $(this).closest('tr').hasClass('child') ? $(this).closest('tr').prev() : $(this).closest('tr');
            var user = users_table.row($tr).data();
            showAuditUser(user);
        });
        
        $(document).on('click', '#cashout_table .audit-user', function() {
            $tr = $(this).closest('tr').hasClass('child') ? $(this).closest('tr').prev() : $(this).closest('tr');
            var data = cashout_table.row($tr).data();
            showAuditUser(data.user);
        });

        $(document).on('click', '#deposits_table .audit-user', function() {
            $tr = $(this).closest('tr').hasClass('child') ? $(this).closest('tr').prev() : $(this).closest('tr');
            var data = deposits_table.row($tr).data();
            showAuditUser(data.user);
        });

        
        $(document).on('click', '#auditUserModal .panel-heading', function() {
            const auditData = $(this).data('auditData');
            const userId = $('#auditUserModal #user-id').val();

            switch(auditData){
                case 'bets':
                    audit_user_bets.ajax.url("{!! route('get-audit-data', ['type' => 'bets']) !!}&userid="+userId).load();
                    break;

                case 'deposits':
                    audit_user_deposits.ajax.url("{!! route('get-audit-data', ['type' => 'deposits']) !!}&userid="+userId).load();
                    audit_user_partner_deposits.ajax.url("{!! route('get-audit-data', ['type' => 'partner_deposits']) !!}&userid="+userId).load();
                    break;

                case 'cashouts':
                    audit_user_cashouts.ajax.url("{!! route('get-audit-data', ['type' => 'cashouts']) !!}&userid="+userId).load();
                    audit_user_partner_cashouts.ajax.url("{!! route('get-audit-data', ['type' => 'partner_cashouts']) !!}&userid="+userId).load();
                    break;

                case 'rebates':
                    audit_user_rebates.ajax.url("{!! route('get-audit-data', ['type' => 'rebate']) !!}&userid="+userId).load();
                    break;

                case 'rewards': 
                    audit_user_rewards.ajax.url("{!! route('get-audit-data', ['type' => 'reward']) !!}&userid="+userId).load();
                    break;

                case 'giftcodes': 
                    audit_user_gift_codes.ajax.url("{!! route('get-audit-data', ['type' => 'gift_code']) !!}&userid="+userId).load();
                    break;

            }
        });

    })
    
    function showAuditUser(user) {
        $('#auditUserModal #user-id').val(user.id);
        $.each($('#auditUserModal .panel-heading span.clickable'), function() {
            if(!$(this).hasClass('panel-collapsed')) {
                $(this).parents('.panel').find('.panel-body').slideUp();
                $(this).addClass('panel-collapsed');
                $(this).find('i').removeClass('glyphicon-chevron-up').addClass('glyphicon-chevron-down');
            }
        });
        
        $.ajax({
            url: '{{ route("user-audit-info") }}',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            type: 'POST',
            data: { userid: user.id },
            success: function(data) {
               
                if (data.success) {
                    var total_rewards = parseFloat(data.total_rewards);
                    var total_gift_codes = parseFloat(data.total_gift_codes);
                    var total_rebates = parseFloat(data.total_rebate);
                    var total_deposits = parseFloat(data.ez_deposit) + parseFloat(data.partner_deposit);
                    var total_cashouts = parseFloat(data.ez_cashout) + parseFloat(data.partner_cashout);
                    var total_a = total_cashouts + parseFloat(data.curr_bets) + parseFloat(data.user_credit);
                    var total_b = total_deposits + parseFloat(data.profit_loss) + parseFloat(data.total_rebate) + parseFloat(data.total_rewards) + parseFloat(data.total_gift_codes);
                    $('#auditUserModal .username').html(user.name + ' (Ez credit: ' + numberWithCommas(parseFloat(data.user_credit).toFixed(2)) + ')');
                    $('#auditUserModal .curr_credits').html(numberWithCommas(parseFloat(data.user_credit).toFixed(2)));
                    $('#auditUserModal .curr_bets').html(numberWithCommas(data.curr_bets.toFixed(2)));
                    $('#auditUserModal .profit_loss').html(numberWithCommas(data.profit_loss.toFixed(2)));
                    $('#auditUserModal .total_deposit').html(numberWithCommas(total_deposits.toFixed(2)));
                    $('#auditUserModal .total_cashout').html(numberWithCommas(total_cashouts.toFixed(2)));
                    $('#auditUserModal .total_rebate').html(numberWithCommas(total_rebates.toFixed(2)));
                    $('#auditUserModal .total_rewards').html(numberWithCommas(total_rewards.toFixed(2)));
                    $('#auditUserModal .total_gift_codes').html(numberWithCommas(total_gift_codes.toFixed(2)));
                    $('#auditUserModal .total_fa').html(numberWithCommas(total_a.toFixed(2)));
                    $('#auditUserModal .total_fb').html(numberWithCommas(total_b.toFixed(2)));
                }
            },
            fail: function(xhr, status, error) {
                console.log(error);
            }
        });
        // audit_user_bets.ajax.url("{!! route('get-audit-data', ['type' => 'bets']) !!}&userid="+user.id).load();
        // audit_user_deposits.ajax.url("{!! route('get-audit-data', ['type' => 'deposits']) !!}&userid="+user.id).load();
        // audit_user_cashouts.ajax.url("{!! route('get-audit-data', ['type' => 'cashouts']) !!}&userid="+user.id).load();
        // audit_user_partner_deposits.ajax.url("{!! route('get-audit-data', ['type' => 'partner_deposits']) !!}&userid="+user.id).load();
        // audit_user_partner_cashouts.ajax.url("{!! route('get-audit-data', ['type' => 'partner_cashouts']) !!}&userid="+user.id).load();
        // audit_user_rebates.ajax.url("{!! route('get-audit-data', ['type' => 'rebate']) !!}&userid="+user.id).load();
        // audit_user_rewards.ajax.url("{!! route('get-audit-data', ['type' => 'reward']) !!}&userid="+user.id).load();
        // audit_user_gift_codes.ajax.url("{!! route('get-audit-data', ['type' => 'gift_code']) !!}&userid="+user.id).load();

    }
    
    //end audit stuff

    function printErrorMsg (msg) {
        $(".print-error-msg").find("ul").html('');
        $(".print-error-msg").css('display','block');
        $.each( msg, function( key, value ) {
                $(".print-error-msg").find("ul").append('<li>'+value+'</li>');
        });
    }

    if (document.location.toString().match('#')) {
        $('.nav-tabs a[href="#' + url.split('#')[1] + '"]').tab('show');
    }

    $('.nav-tabs a').on('shown.bs.tab', function (e) {
        switch(e.currentTarget.hash) {
            case '#deposits':
                if(typeof deposits_table === "undefined"){
                    initDepositsTableDatables();
                }else{
                    deposits_table.ajax.reload();
                }
                break;
            case '#cashout':
                if(typeof cashout_table === "undefined"){
                    initCashoutTableDatables();
                }else{
                    cashout_table.ajax.reload();
                }            
                break;                            
            case '#bettings':
                if(typeof bettings_table === "undefined"){
                    initBettingsTableDatables();
                }else{
                    bettings_table.ajax.reload();
                }
                break;
            case '#donations':
                if(typeof donations_table === "undefined"){
                    initDonationsTableDatables();
                }else{
                    donations_table.ajax.reload();
                }           
                break;
            case '#commissions':
                if(typeof commissions_table === "undefined"){
                    initCommissionsTableDatables();
                }else{
                    commissions_table.ajax.reload();
                }              
                break;   
            case '#commissions_partners':
                if(typeof commissions_partners_table === "undefined"){
                    initCommissionsPartnersTableDatables();
                }else{
                    commissions_partners_table.ajax.reload();
                }              
                break;     
            case '#commissions_bets':
                if(typeof commissions_bets_table === "undefined"){
                    initCommissionsBetsTableDatables();
                }else{
                   commissions_bets_table.ajax.reload();
                }              
                break;                                            
            case '#badges':
                if(typeof badges_table === "undefined"){
                    initBadgesTableDatables();
                }else{
                    badges_table.ajax.reload();
                }               
                break;  
            case '#rewards':
                if(typeof rewards_table === "undefined"){
                    initRewardsTableDatables();
                }else{
                    rewards_table.ajax.reload();
                }               
                break;                                     
            case '#referrals':
                if(typeof referrals_table === "undefined"){
                    initReferralsTableDatables();
                }else{
                    referrals_table.ajax.reload();
                }               
                break;
            case '#payouts':
                if(typeof payout_table === "undefined"){
                    initPayoutsTableDatables();
                }else{
                    payout_table.ajax.reload();
                }             
                break;
            case '#bugs':
                if(typeof bugs_table === "undefined"){
                    initBugsTableDatables();
                }else{
                    bugs_table.ajax.reload();
                }               
                break;
            case '#promos':
                if(typeof promo_table === "undefined"){
                    initPromosTableDatables();
                }else{
                    promo_table.ajax.reload();
                }                  
                break;
            case '#rebates':
                if(typeof rebates_table === "undefined"){
                    initRebatesTableDatables();
                }else{
                    rebates_table.ajax.reload();
                }                    
                break;
            case '#usermanagement':
                if(typeof users_table === "undefined"){
                    initUsersTableDatables();
                }else{
                    users_table.ajax.reload();
                }
                break;
            case '#earnings':
                if(typeof earnings_table === "undefined"){
                    initEarningsTableDatables();
                }else{
                    earnings_table.ajax.reload();
                }                    
                break;

        }
    })
</script>

<script>
$(document).ready(function(){
    $('#user').click(function(){
        $(this).addClass('admin_header_active');
        $('#partner').removeClass('admin_header_active');
        $('#market').removeClass('admin_header_active');
        $('#settings').removeClass('admin_header_active');

        $('#partner-administration').hide();
        $('#market-place').hide();
        $('#site-settings').hide();
        $('#user-administration').fadeIn();
    });
    $('#partner').click(function(){
        $(this).addClass('admin_header_active');
        $('#user').removeClass('admin_header_active');
        $('#market').removeClass('admin_header_active');
        $('#settings').removeClass('admin_header_active');

        $('#user-administration').hide();
        $('#market-place').hide();
        $('#site-settings').hide();
        $('#partner-administration').fadeIn();
    });
    $('#market').click(function(){
        $(this).addClass('admin_header_active');        
        $('#user').removeClass('admin_header_active');
        $('#partner').removeClass('admin_header_active');
        $('#settings').removeClass('admin_header_active');

        $('#user-administration').hide();
        $('#partner-administration').hide();
        $('#site-settings').hide();
        $('#market-place').fadeIn();
    });
    $('#settings').click(function(){
        $(this).addClass('admin_header_active');        
        $('#user').removeClass('admin_header_active');
        $('#partner').removeClass('admin_header_active');
        $('#market').removeClass('admin_header_active');

        $('#user-administration').hide();
        $('#partner-administration').hide();
        $('#market-place').hide();
        $('#site-settings').fadeIn();
    });
    
    $(document).on('click', '#save_settings_btn', function() {
        var form = new FormData($("#site_settings_form")[0]);
        $btn = $(this);
        $btn.button('progress');
        $.ajax({
            url: "{{ route('set-site-settings') }}",  //Server script to process data
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            type: 'POST',
            success: function(data){
                $btn.button('reset');
                if (data.errors) {
                    $.each( data.errors, function( key, value ) {
                        $.each( data.errors, function( key, value ) {
                            $('#site_settings_form').find(':input[name='+ key +']').parent().addClass('has-error');
                            $('#site_settings_form').find(':input[name='+ key +']').parent().find('.error-label').text(value[0]);
                        });
                    });
                    swal("Error!", 'Please fill-in required fields!', "error");
                } else
                    swal("Success", 'Successfully set site settings!', "success");
            },
            data: form,
            cache:false,
            contentType: false,
            processData: false,
        });
    });

    $(document).on('change','.account-name-select', function(){
        const accountType = $(this).data('type');
        const accountKey = $(this).val();
        const bankAccounts = {!! json_encode($bankSettings) !!};
        const selectedBankAccount = bankAccounts.filter(account => account.account_key == accountKey);
        const accountName = selectedBankAccount.find(account => account.name == `${accountType}-account-name`);
        const accountNumber = selectedBankAccount.find(account => account.name == `${accountType}-account-number`);

        $(`#${accountType}-account-name`).val(accountName.value);
        $(`#${accountType}-account-number`).val(accountNumber.value);
        // console.log('accountType:', accountType, accountKey)
        // console.log('bankAccounts:', selectedBankAccount, accountName, accountNumber)
    });

    // Data Tables
    partner_user_deposit_table = $('#partner_user_deposit_table').DataTable({
            initComplete : function() {
                var input = $('#partner_user_deposit_table_filter input').unbind(),
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
                $('#partner_user_deposit_table_filter').append($searchButton, $clearButton);
            },          
            processing: true,
            serverSide:true,
            responsive: true,
            ajax: "{!! route('dash_partner_users_transactions',['type' => 'deposit', 'trade_type' => 'partner-user']) !!}",
            order: [[ 4, "desc" ]],
            columns: [
                {
                    data: 'code', 
                    name: 'code'
                },
                {
                    data: 'user_id', 
                    name: 'user_id',
                },
                {
                    data: 'partner_transactions.name', 
                    name: 'partner_transactions.name',
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
                    data: 'approved_rejected_date', 
                    name: 'approved_rejected_date',
                    render: function(data,type,row){
                        switch(row.status){
                            case '0':
                            case 0:
                            case '-1': 
                            case -1:
                                return 'Processing';
                            case '1':
                            case 1:
                            case '2':
                            case 2:
                                const useDate = !!data ? data : row.updated_at;
                                return moment(useDate).format('llll')+'<br>'+moment(useDate).fromNow();
                        }
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
                    data: 'data', 
                    name: 'data',
                    render: function(data,type,row){
                        var mop = data ? JSON.parse(data) : '';
                        return mop.mop;
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
				case "1":
                                    return "Approved"; break;
                                case 2:
				case "2":
                                    return "Rejected"; break;
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
                        return data ? '<a href="#" class="btn btn-default view-receipt"><i class="fa fa-picture-o" aria-hidden="true"></i></a>' : 'N/A';
                    }
                },
                {
                    data: 'process_by.name', 
                    name: 'process_by.name',
                    render: function(data,type,row){
                        return data != null ? data : 'n/a';
                    }
                },
                {
                    render: function ( data, type, row ) {
                        if(row['partner_comment'] != null && data > 1){
                            return row['partner_comment'];
                        }
                        else{
                            switch(row['status']){
                                case 0: case '0':
                                    return '<button type="button" class="btn btn-danger btn-sm decline-deposit" data-toggle="modal">Reject</button>';
                                default:
                                    return '<button type="button" class="btn btn-success btn-sm" disabled>Done</button>';
                            }
                        }
                    }
                }
            ]
        })

        partner_user_cashout_table = $('#partner_user_cashout_table').DataTable({
            initComplete : function() {
                var input = $('#partner_user_cashout_table_filter input').unbind(),
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
                $('#partner_user_cashout_table_filter').append($searchButton, $clearButton);
            },              
            processing: true,
            serverSide:true,
            responsive: true,
            ajax: "{!! route('dash_partner_users_transactions',['type' => 'cashout', 'trade_type' => 'partner-user']) !!}",
            order: [[ 4, "desc" ]],
            columns: [
                {
                    data: 'code', 
                    name: 'code'
                },
                {
                    data: 'user_id', 
                    name: 'user_id',
                },
                {
                    data: 'partner_transactions.name', 
                    name: 'partner_transactions.name',
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
                    data: 'approved_rejected_date', 
                    name: 'approved_rejected_date',
                    render: function(data,type,row){
                        switch(row.status){
                            case '0':
                            case 0:
                            case '-1': 
                            case -1:
                                return 'Processing';
                            case '1':
                            case 1:
                            case '2':
                            case 2:
                                const useDate = !!data ? data : row.updated_at;
                                return moment(useDate).format('llll')+'<br>'+moment(useDate).fromNow();
                        }
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
                    data: 'donation', 
                    name: 'donation',
                    render: function(data,type,row){
                        // var net = (parseFloat(row['amount']) - (parseFloat(row['amount']) * 0.05)),
                        //     percentage = parseFloat(row['amount']) * 0.025;
                        return '&#8369; '+ data;
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
                    data: 'status', 
                    name: 'status',
                    render: function ( data, type, row ) {
                        if(row['picture'] == null && data < 1 ){
                            return "Incomplete";
                        }
                        else{
                            switch(data){
                                case 1:
				                case "1":
                                    return "Approved"; break;
                                case 2:
				                case "2":
                                    return "Rejected"; break;
                                default: 
                                    return "Needs Approval";
                            }
                        }
                    }
                },
                {
                    render: function(data,type,row){
                        return '<a href="#" class="btn btn-default view-bug"><i class="fa fa-info" aria-hidden="true"></i></a>';
                    }
                },
                {
                    data: 'picture',
                    searchable:false,
                    render: function(data,type,row){
                        return data ? '<a href="#" class="btn btn-default view-receipt"><i class="fa fa-picture-o" aria-hidden="true"></i></a>' : 'N/A';
                    }
                },
                {
                    data: 'process_by.name', 
                    name: 'process_by.name',
                    render: function(data,type,row){
                        return data != null ? data : 'n/a';
                    }
                },
                {
                    render: function ( data, type, row ) {
                        if(row['partner_comment'] != null && data > 1){
                            return row['partner_comment'];
                        }
                        else{
                            switch(row['status']){
                                case 0: case '0':
                                    return '<button type="button" class="btn btn-danger btn-sm decline-cashout" data-toggle="modal">Reject</button>';
                                case -1: case '-1':
                                return '<button type="button" class="btn btn-primary btn-sm mark-verified-cashout" data-toggle="modal">Mark as Verified</button>';
                                default:
                                    return '<button type="button" class="btn btn-success btn-sm" disabled>Done</button>';
                            }
                        }
                    }
                }
            ]
        })

        partner_admin_transactions_table = $('#partner_admin_transactions_table').DataTable({
            initComplete : function() {
                var input = $('#partner_admin_transactions_table_filter input').unbind(),
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
                $('#partner_admin_transactions_table_filter').append($searchButton, $clearButton);
            },             
            processing: true,
            serverSide:true,
            responsive: true,
            ajax: "{!! route('dash_partner_admin_transactions',['trade_type' => 'partner-admin']) !!}",
            order: [[ 2, "desc" ]],
            createdRow: function ( row, data, index ) {
                var transaction = data;
                if (transaction.discrepancy.length > 0) {
                    $('td', row).eq(5).addClass('highlight');
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
                        return data == 'deposit' ? 'Buy Credits' : 'Sell Credits';
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
                        switch(row.status){
                            case '0':
                            case 0:
                            case '-1': 
                            case -1:
                                return 'Processing';
                            case '1':
                            case 1:
                            case '2':
                            case 2:
                                const useDate = !!data ? data : row.updated_at;
                                return moment(useDate).format('llll')+'<br>'+moment(useDate).fromNow();
                        }
                    }
                },                
                {
                    data: 'partner.partner_name', 
                    name: 'partner.partner_name',
                },
                {
                    data: 'partner_transactions.name', 
                    name: 'partner_transactions.name',
                },
                {
                    data: 'amount', 
                    name: 'amount',
                    render: function(data,type,row){
                        return '&#8369; '+numberWithCommas(data);
                    }
                },
                {
                    data: 'data', 
                    name: 'data',
                    render: function(data,type,row){
                        var type = JSON.parse(data)
                        return !!type ? type.mop : 'Other';
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
                                case 1: case '1':
                                    return "Approved"; break;
                                case 2: case '2':
                                    return "Rejected"; break;
                                default: 
                                    return "Needs Approval";
                            }
                        }
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
                    data:'picture',
                    searchable:false,
                    render: function(data,type,row){
                        let pictureText = ''; 
                        if(!!data){ //means there's an image already attached on this transaction
                            pictureText += `<div>`;
                            pictureText += `<a href="#" class="btn btn-default btn-xs view-receipt"><i class="fa fa-picture-o" aria-hidden="true"></i></a> `;
                            
                            if(row['status'] == 0){ //if its not yet approved or rejected then allow admin to change image
                                pictureText += `<a href="#" class="btn btn-xs btn-primary attach-receipt" data-id="${row['id']}" data-code="${row['code']}" data-table="partner_admin_transactions_table"><i class="fa fa-picture-o" aria-hidden="true"></i> Change</a>`;
                            }

                            pictureText += `</div>`;
                        }else{ //else there's no image; so we have to check if its a deposit or cashout; if deposit then partner can upload his/her receipt
                            
                            if(row['status'] != 2){
                                pictureText += `<a href="#" class="btn btn-xs btn-primary attach-receipt" data-id="${row['id']}" data-code="${row['code']}" data-table="partner_admin_transactions_table"><i class="fa fa-picture-o" aria-hidden="true"></i> Attach</a>`;
                            }
                            
                        } 
                        return pictureText;
                       // return  data != null ? '<div><a href="#" class="btn btn-default view-receipt"><i class="fa fa-picture-o" aria-hidden="true"></i></a></div>' : 'N/A'
                    }
                },
                {
                    data: 'process_by.name', 
                    name: 'process_by.name',
                    render: function(data,type,row){
                        return data != null ? data : 'N/A';
                    }
                },
                {
                    data: 'status',
                    render: function ( data, type, row ) {
                        if(row['partner_comment'] != null && data > 1){
                            return row['partner_comment'];
                        }
                        else{
                            if(row['type'] == 'deposit'){
                                if (row['picture'] == null && data != 1) {
                                    return '<a href="#" data-status="rejected" class="btn btn-danger btn-xs btn-reject">Reject</a> <a href="#" data-status="discrepancy" class="btn btn-warning btn-xs btn-edit discrepancy">Approve w/ discrepancy</a> '
                                }else{
                                    if (data == 1) {
                                        return '<a href="#" data-status="discrepancy" class="btn btn-success btn-xs btn-edit discrepancy">Update w/ discrepancy</a> '
                                    }else{
                                        return '<a href="#" data-status="rejected" class="btn btn-danger btn-xs btn-reject">Reject</a> <a href="#" data-status="deleted" class="btn btn-primary btn-xs btn-edit approve">Check and approve</a> '
                                    }
                                }
                            }
                            else{
                                if (data == 1) {
                                    return '<a href="#" data-status="discrepancy" class="btn btn-success btn-xs btn-edit" disabled>Process Done</a> '
                                }else if( data == 2){
                                      return '<a href="#" data-status="discrepancy" class="btn btn-danger btn-xs btn-edit" disabled>Rejected</a> '
                                }else{
                                    return '<a href="#" data-status="rejected" class="btn btn-danger btn-xs btn-reject">Reject</a> <a href="#" data-status="deleted" class="btn btn-primary btn-xs btn-edit approve">Mark as Processed</a>'
                                }
                            }
                        }
                    }
                }
            ],
            drawCallback: function( settings ) {
                var api = this.api();
                var pending_tr = api.rows().data();
                var number = 0;
                if(pending_tr.length > 0){
                    for(var i = 0; i < pending_tr.length; i++){
                        if(pending_tr[i].status == 0){
                            number += 1;
                            if(number > 0)
                                $('#partner_buy').attr('data-count', number);
                            else
                                $('#partner_buy').removeAttr('data-count');
                        }
                    }
                }
                else{
                    $('#partner_buy').removeAttr('data-count');
                }
            }
        })

        partner_payout_table = $('#partner_payout_table').DataTable({
            initComplete : function() {
                var input = $('#partner_payout_table_filter input').unbind(),
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
                $('#partner_payout_table_filter').append($searchButton, $clearButton);
            },              
            processing: true,
            serverSide:true,
            responsive: true,
            ajax: "{!! route('get_partner_payouts') !!}",
            order: [[ 3, "desc" ]],
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
                    data: 'partner.user_owner.name', 
                    name: 'partner.user_owner.name',
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
                        var net = parseFloat(data);
                        return '&#8369; '+numberWithCommas( net.toFixed(2) );
                    }
                },
                {
                    render: function(data,type,row){
                        return '<a href="#" class="btn btn-default view-bug"><i class="fa fa-info" aria-hidden="true"></i></a>';
                    }
                },
                {
                    data: 'receipt',
                    searchable:false,
                    render: function(data,type,row){
                        return data ? '<a href="#" class="btn btn-default view-receipt"><i class="fa fa-picture-o" aria-hidden="true"></i></a>' : 'N/A';
                    }
                },
                {
                    data: 'message',
                    name: 'message',
                },
                {
                    data: 'process_by.name', 
                    name: 'process_by.name',
                }
            ]
        })

        partner_approval_table = $('#partner_approval_table').DataTable({
            initComplete : function() {
                var input = $('#partner_approval_table_filter input').unbind(),
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
                $('#partner_approval_table_filter').append($searchButton, $clearButton);
            },              
            processing: true,
            serverSide:true,
            responsive: true,
            ajax: "{!! route('partners', ['verified' => 0]) !!}",
            order: [[ 0, "desc" ]],
            columns: [
                {
                    data: 'id', 
                    name: 'id'
                },
                {
                    data: 'partner_name', 
                    name: 'partner_name',
                },
                {
                    data: 'user_owner.name', 
                    name: 'user_owner.name',
                },
                {
                    data: 'mobile_number', 
                    name: 'mobile_number',
                },
                {
                    data: 'landline_number', 
                    name: 'landline_number',
                    render: function(data,type,row){
                        return data != null ? data : 'No Contact Number';
                    }
                },
                {
                    render: function(data,type,row){
                        var bpi = (row['bpi_account'] == null && row['bpi_account_name'] == null) ? false : true;
                        var bdo = (row['bdo_account'] == null && row['bdo_account_name'] == null) ? false : true;
                       if(row['partner_name'] == null && row['mobile_number'] == null && row['landline_number'] == null && row['contact_person'] == null &&
                        row['email'] == null && !bpi && !bpo && row['facebook_link'] == null && row['address'] == null && row['province_id'] == null){
                            return "Pending Partner Details";
                       }
                       else{
                           return "Pending Approval";
                       }
                    }
                },
                {
                    render: function(data,type,row){
                        return '<a href="#" class="btn btn-default view-payout-info"><i class="fa fa-info" aria-hidden="true"></i></a>';
                    }
                },
                {
                    render: function(data,type,row){
                        return '<a href="#" class="btn btn-default view-partner-info"><i class="fa fa-info" aria-hidden="true"></i></a>';
                    }
                },
                {
                    data: 'process_by.name', 
                    name: 'process_by.name',
                    render: function(data,type,row){
                        return data != null ? data : 'n/a';
                    }
                },
                {
                    render: function ( data, type, row ) {
                        switch(row['verified']){
                            case 1: case '1':
                                return '<button type="button" class="btn btn-success btn-xs"  data-toggle="modal" disabled>Done</button>';
                            default:
                                return '<button type="button" class="btn btn-success btn-xs verify-partner" >Approve</button>';
                        }
                    }
                }
            ]
        })

        partner_table = $('#partner_table').DataTable({
            initComplete : function() {
                var input = $('#partner_table_filter input').unbind(),
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
                $('#partner_table_filter').append($searchButton, $clearButton);
            },              
            processing: true,
            serverSide:true,
            responsive: true,
            ajax: "{!! route('partners', ['verified' => 1]) !!}",
            order: [[ 0, "asc" ]],
            columns: [
                {
                    data: 'id', 
                    name: 'id'
                },
                {
                    data: 'partner_name', 
                    name: 'partner_name',
                    render: function(data,type,row){
                        return `<a href="#" class="show-partner-link">${data}</a>`;
                    }
                },
                {
                    data: 'user_owner.name', 
                    name: 'user_owner.name',
                },
                {
                    data: 'partner_credits', 
                    name: 'partner_credits',
                    render: function(data,type,row){
                        var net = data ? parseFloat(data) : 0.00;
                        return '&#8369; '+numberWithCommas( net.toFixed(2) );
                    }
                },
                {
                    data: 'partner_earnings', 
                    name: 'partner_earnings',
                    render: function(data,type,row){
                        var net = data ? parseFloat(data) : 0.00;
                        return '&#8369; ' + (net.toFixed(2)) + ' <button type="button" class="btn btn-primary btn-xs payout-btn float-right" data-toggle="modal" data-target="#partner_payout_modal">Payout</button>';
                    }
                },
                {
                    data: 'mobile_number', 
                    name: 'mobile_number',
                    render: function(data,type,row){
                        let returnText = data;
                        returnText += row.landline_number != null ? `<br/> ${row.landline_number}` : '';
                        return returnText;
                    }
                },
                {
                    data: 'affliates', 
                    name: 'affliates',
                    searchable: false,
                    sortable: false,
                    render: function(data,type,row){
                        const editAffliates = `<button class="btn btn-primary btn-xs update-partner-affliates" data-toggle="modal" data-target="#partner_affliates_modal">${data.length} Affiliates </button>`;
                        return editAffliates;
                        
                    }
                }, 
                {
                    data: 'sub_users', 
                    name: 'sub_users',
                    searchable: false,
                    sortable: false,
                    render: function(data,type,row){
                        const editSubUsers = `<button class="btn btn-primary btn-xs update-partner-sub-users" data-toggle="modal" data-target="#partner_sub_users_modal">${data.length} Sub-users </button>`;
                        return editSubUsers;
                        
                    }
                },                 
                {
                    data: 'sub_agents', 
                    name: 'sub_agents',
                    searchable: false,
                    sortable: false,
                    render: function(data,type,row){
                        // let returnText = data.map(affliate => {
                        //     return `${affliate.user.name} - <small>(${affliate.user.voucher_code})</small><br/>`;
                        // }).join('');

                        // returnText = !!returnText ? `${returnText}<hr/>` : '';

                        const editSubAgents = `<button class="btn btn-primary btn-xs update-partner-subagents" data-toggle="modal" data-target="#partner_subagents_modal">${data.length} Sub-agents </button>`;
                        // return returnText + editAffliates;
                        return editSubAgents;
                        
                    }
                },                              
                {
                    data: 'bdo_account', 
                    name: 'bdo_account',
                    render: function(data,type,row){
                        return '<a href="#" class="btn btn-default view-payout-info"><i class="fa fa-info" aria-hidden="true"></i></a>';
                    }
                },
                {
                    render: function(data,type,row){
                        return '<a href="#" class="btn btn-default view-partner-info"><i class="fa fa-info" aria-hidden="true"></i></a>';
                    }
                },
                {
                    data: 'process_by.name', 
                    name: 'process_by.name',
                    render: function(data,type,row){
                        return data != null ? data : 'n/a';
                    }
                },
                {
                    render: function ( data, type, row ) {
                        let partnerActionButtons  = '<button type="button" class="btn btn-sm partner-downloads"><i class="fa fa-download" aria-hidden="true"></i></button> &nbsp;';
                        switch(row['active']){
                            case 1: case '1':
                                partnerActionButtons += '<button type="button" class="btn btn-danger btn-sm deactivate-partner">Deactivate</button>';
                                break;
                            default:
                                partnerActionButtons+= '<button type="button" class="btn btn-success btn-sm activate-partner">Activate</button>';
                                break;
                        }

                        switch(row['show_in_site']){
                            case 1: case '1':
                                partnerActionButtons += ' <button type="button" class="btn btn-info btn-sm hide-partner">Visible</button>';
                                break;
                            default:
                                partnerActionButtons+= ' <button type="button" class="btn btn-default btn-sm show-partner">Hidden</button>';
                                break;
                        }
                        

                        return partnerActionButtons;
                    }
                }
            ]
        })

        partner_discrepancy_table = $('#partner_discrepancy_table').DataTable({ 
            initComplete : function() {
                var input = $('#partner_discrepancy_table_filter input').unbind(),
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
                $('#partner_discrepancy_table_filter').append($searchButton, $clearButton);
            },              
            paging:   false,
            info:     false,
            searching: false,
            ordering: false,
            destroy: true,
            responsive: true,
            columns :[
                {
                    data:'created_at',
                    render: function(data,type,row){
                        return moment(data).format('llll')+'<br>'+moment(data).fromNow()
                    }
                },
                {
                    data:'amount',
                    render: function(data,type,row){
                        return data ? data : 'n/a'
                    }
                },
                {
                    data:'picture',
                    searchable:false,
                    render: function(data,type,row){
                        return data ? '<a href="#" class="btn btn-default view-receipt"><i class="fa fa-picture-o" aria-hidden="true"></i></a>' : 'n/a'
                    }
                },
                {data: 'mop'},
                {data: 'message'},
                {data: 'process_by.name'},

            ]
        });

        $('#partner_payout_table').on('click', '.view-bug', function(event) {
            $tr = $(this).closest('tr').hasClass('child') ? $(this).closest('tr').prev() : $(this).closest('tr');
            var row = partner_payout_table.row($tr).data();
        });

        $('#partner_approval_table').on('click', '.verify-partner', function(event) {
            event.preventDefault();
            $tr = $(this).closest('tr').hasClass('child') ? $(this).closest('tr').prev() : $(this).closest('tr');
            var row = partner_approval_table.row($tr).data();
            swal({
                title: "Are you sure?",
                text: "The <span style='font-weight:bold;color: #820804'>"+row.partner_name+"</span> can start his/her transactions with customers after being verified.",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Yes, please continue!",
                showLoaderOnConfirm: true,
                closeOnConfirm: false,
                html:true
            },
            function(){

                $.ajax({
                    url: '{{ route("partner_status") }}',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    type: 'POST',
                    data: { id: row['id'],type: 'verify' },
                    success: function(data) {
                        if (data.success) {
                            swal({
                                title: "Approved!", 
                                text: "Partner <span style='font-weight:bold;color: #820804'>"+row.partner_name+"</span> is successfully verified",
                                type: "success",
                                html: true
                            });
                            partner_approval_table.ajax.reload( null, false );
                            partner_table.ajax.reload( null, false );
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

        $('#partner_table').on('click', '.activate-partner', function(event) {
            event.preventDefault();
            $tr = $(this).closest('tr').hasClass('child') ? $(this).closest('tr').prev() : $(this).closest('tr');
            var row = partner_table.row($tr).data();
            swal({
                title: "Are you sure?",
                text: "Partner <span style='font-weight:bold;color: #820804'>"+row.partner_name+"</span> can transact with his/her customers again",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-success",
                confirmButtonText: "Yes, please continue!",
                showLoaderOnConfirm: true,
                closeOnConfirm: false,
                html:true
            },
            function(){

                $.ajax({
                    url: '{{ route("partner_status") }}',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    type: 'POST',
                    data: { id: row['id'],type: 'activate' },
                    success: function(data) {
                        if (data.success) {
                            swal({
                                title: "Activated!", 
                                text: "Partner <span style='font-weight:bold;color: #820804'>"+row.partner_name+"</span> has been activated again",
                                type: "success",
                                html: true
                            });
                            partner_table.ajax.reload( null, false );
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

        $('#partner_table').on('click', '.deactivate-partner', function(event) {
            event.preventDefault();
            $tr = $(this).closest('tr').hasClass('child') ? $(this).closest('tr').prev() : $(this).closest('tr');
            var row = partner_table.row($tr).data();
            swal({
                title: "Are you sure?",
                text: "Partner <span style='font-weight:bold;color: #820804'>"+row.partner_name+"</span> will temporarily lose the ability to transact with customers.",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Yes, please continue!",
                showLoaderOnConfirm: true,
                closeOnConfirm: false,
                html:true
            },
            function(){

                $.ajax({
                    url: '{{ route("partner_status") }}',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    type: 'POST',
                    data: { id: row['id'],type: 'deactivate' },
                    success: function(data) {
                        if (data.success) {
                            swal({
                                title: "Deactivated!", 
                                text: "Partner <span style='font-weight:bold;color: #820804'>"+row.partner_name+"</span> has been deactivated",
                                type: "success",
                                html: true
                            });
                            partner_table.ajax.reload( null, false );
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

        $('#partner_table').on('click', '.show-partner', function(event) {
            event.preventDefault();
            $tr = $(this).closest('tr').hasClass('child') ? $(this).closest('tr').prev() : $(this).closest('tr');
            var row = partner_table.row($tr).data();
            swal({
                title: `Are you sure?`,
                text: "Partner <span style='font-weight:bold;color: #820804'>"+row.partner_name+"</span> will show up in 2ez.bet Deposit/Cashout Page.",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Yes, please continue!",
                showLoaderOnConfirm: true,
                closeOnConfirm: false,
                html:true
            },
            function(){

                $.ajax({
                    url: '{{ route("partner_show_in_site") }}',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    type: 'POST',
                    data: { id: row['id'],type: 'show' },
                    success: function(data) {
                        if (data.success) {
                            swal({
                                title: "Partner now <strong>visible</strong>", 
                                text: "Partner <span style='font-weight:bold;color: #820804'>"+row.partner_name+"</span> now visiable in Deposit/Cashout Page.",
                                type: "success",
                                html: true
                            });
                            partner_table.ajax.reload( null, false );
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
        
        $('#partner_table').on('click', '.hide-partner', function(event) {
            event.preventDefault();
            $tr = $(this).closest('tr').hasClass('child') ? $(this).closest('tr').prev() : $(this).closest('tr');
            var row = partner_table.row($tr).data();
            swal({
                title: `Are you sure?`,
                text: "Partner <span style='font-weight:bold;color: #820804'>"+row.partner_name+"</span> will be hidden in 2ez.bet Deposit/Cashout Page.",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Yes, please continue!",
                showLoaderOnConfirm: true,
                closeOnConfirm: false,
                html:true
            },
            function(){

                $.ajax({
                    url: '{{ route("partner_show_in_site") }}',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    type: 'POST',
                    data: { id: row['id'],type: 'hide' },
                    success: function(data) {
                        if (data.success) {
                            swal({
                                title: "Partner now <strong>hidden</strong>", 
                                text: "Partner <span style='font-weight:bold;color: #820804'>"+row.partner_name+"</span> now hidden in Deposit/Cashout Page.",
                                type: "success",
                                html: true
                            });
                            partner_table.ajax.reload( null, false );
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

        //show-partner-link
        $('#partner_table').on('click', '.show-partner-link', function(event) {
            event.preventDefault();
            $tr = $(this).closest('tr').hasClass('child') ? $(this).closest('tr').prev() : $(this).closest('tr');
            var row = partner_table.row($tr).data();
            swal({
                title: `${row['partner_name']} Shortcut Link`,
                text: `<div><strong>Deposit:</strong> <a href="{{ route('deposit.page', ['via' => 'partner'])}}/${row['id']}" target="_blank">{{ route('deposit.page', ['via' => 'partner'])}}/${row['id']}</a> <div>
                        <div><strong>Cashout:</strong> <a href="{{ route('cashout.page', ['via' => 'partner'])}}/${row['id']}" target="_blank">{{ route('cashout.page', ['via' => 'partner'])}}/${row['id']}</a> <div>
                        `,
                type: "info",
                showCancelButton: false,
                confirmButtonClass: "btn-info",
                confirmButtonText: "Close",
                showLoaderOnConfirm: true,
                closeOnConfirm: true,
                html:true
            },
            function(){

            });
        });        
        //show-partner-link

        $('#partner_table').on('click', '.payout-btn', function(event) {
            event.preventDefault();
            $tr = $(this).closest('tr').hasClass('child') ? $(this).closest('tr').prev() : $(this).closest('tr');
            var row = partner_table.row($tr).data();
            
            $('#partner_payout_modal').find('#partner-name-code').text( row['partner_name'] )
            $('#partner_payout_modal').find('#partner-payout-amount').val( row['partner_earnings'] )
            $('#partner_payout_modal').find('.partner_payout_btn').prop('disabled', row['partner_earnings'] < 1000 ? true : false).html(row['partner_earnings'] < 1000 ? '<i class="fa fa-ban" aria-hidden="true"></i>&nbsp;&nbsp;Submit' : 'Submit').data('partner_id', row['id'] )
        });

        // updating parnter affliates

        $('#partner_table').on('click', '.update-partner-affliates', function(event) {
            $('.affliates-container').html('');
            event.preventDefault();
            $tr = $(this).closest('tr').hasClass('child') ? $(this).closest('tr').prev() : $(this).closest('tr');
            const row = partner_table.row($tr).data();
            const currentAffliates = row.affliates.map(affliate => parseInt(affliate.streamer_user_id));

            $.ajax({
                url: '{{ route("admin.get.affliates") }}',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                type: 'GET',
                success: function(data) {
                    const affliates = JSON.parse(data)
                    const affliateText = affliates.map(affliate => {
                        return `<div class="form-check">
                                    <input 
                                        class="form-check-input affliates-checkbox" 
                                        type="checkbox" 
                                        value="${affliate.id}" 
                                        id="affliate-checkbox-${affliate.id}" 
                                        ${currentAffliates.includes(affliate.id) ? 'checked' : ''}
                                    />
                                    <label class="form-check-label" for="affliate-checkbox-${affliate.id}">
                                        ${affliate.name} <small class="text-muted">(${affliate.voucher_code})</small>
                                    </label>
                                </div>`;
                    }).join('');
                    
                    $('.affliates-container').html(affliateText);
                },
                fail: function(xhr, status, error) {
                    $('.affliates-container').html(`No affliates found or something went wrong.`);
                }
            });
                                 
            
            $('#partner_affliates_modal').find('#partner-name').html( row['partner_name'] );
            $('#partner_affliates_modal #partner_id').val(row.id);
        });

        $('#partner_affliates_modal').on('click', '.partner_affliates_save', function(event) {
            event.preventDefault();
            const that = $(this);
            $(this).prop('disabled', true);

            const checkedAffliates = [];
            $('.affliates-checkbox:checked').each( function(){
                checkedAffliates.push(this.value)
             });

            const partner_id = $('#partner_affliates_modal #partner_id').val();

            $.ajax({
                url: '{{ route("admin.update.affliates") }}',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                type: 'PUT',
                dataType: 'json',
                data: {
                    partner_id : partner_id,
                    affliates: checkedAffliates
                },
                success: function(data) {
                    // console.log('data:', data, data.message)
                    that.prop('disabled', false);
                    partner_table.ajax.reload();
                    swal(data.message,"","success");
                   
                    
                },
                fail: function(xhr, status, error) {
                    console.log('error', error)
                }
            });
        });
        
        //end update partner affliates

        //updating parnter sub agents
        $('#partner_table').on('click', '.update-partner-subagents', function(event) {
            $('.subagents-container').html('');
            event.preventDefault();
            $tr = $(this).closest('tr').hasClass('child') ? $(this).closest('tr').prev() : $(this).closest('tr');
            const row = partner_table.row($tr).data();

            const currentAgents = row.sub_agents.map(agent => parseInt(agent.sub_partner_id));

            $.ajax({
                url: '{{ route("admin.get.subagents") }}',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                type: 'GET',
                success: function(data) {
                    const agents = JSON.parse(data);
                    const agentsText = agents.partners.map(agent => {
                        
                        const disabled =  currentAgents.includes(agent.id) ? '' : ( agents.alreadySubs.includes(agent.id) ? 'disabled' : '' ) ;

                        return `<div class="form-check">
                                    <input 
                                        class="form-check-input agents-checkbox" 
                                        type="checkbox" 
                                        value="${agent.id}" 
                                        id="agents-checkbox-${agent.id}" 
                                        ${currentAgents.includes(agent.id) ? 'checked' : ''}
                                        ${disabled}
                                    />
                                    <label class="form-check-label" for="agents-checkbox-${agent.id}" disabled="${disabled}">
                                        ${agent.partner_name} 
                                    </label>
                                </div>`;
                    }).join('');
                    
                    $('.subagents-container').html(agentsText);
                },
                fail: function(xhr, status, error) {
                    $('.subagents-container').html(`No sub-agents found or something went wrong.`);
                }
            }); 
            
            $('#partner_subagents_modal').find('#partner-name').html( row['partner_name'] );
            $('#partner_subagents_modal #partner_id').val(row.id);
        });

        $('#partner_subagents_modal').on('click', '.partner_subagents_save', function(event) {
            event.preventDefault();
            const that = $(this);
            $(this).prop('disabled', true);

            const checkedAgents = [];
            $('.agents-checkbox:checked').each( function(){
                checkedAgents.push(this.value)
             });

            const partner_id = $('#partner_subagents_modal #partner_id').val();

            $.ajax({
                url: '{{ route("admin.update.subagents") }}',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                type: 'PUT',
                dataType: 'json',
                data: {
                    partner_id : partner_id,
                    agents: checkedAgents
                },
                success: function(data) {
                    // console.log('data:', data, data.message)
                    that.prop('disabled', false);
                    partner_table.ajax.reload();
                    swal(data.message,"","success");
                   
                    
                },
                fail: function(xhr, status, error) {
                    console.log('error', error)
                }
            });
        });

        //end updating partner sub agents
        
        //updating partner sub-users
        $('#partner_table').on('click', '.update-partner-sub-users', function(event) {
            $('.sub-users-container').html('');
            event.preventDefault();
            $tr = $(this).closest('tr').hasClass('child') ? $(this).closest('tr').prev() : $(this).closest('tr');
            const row = partner_table.row($tr).data();
            const curretSubUsers = row.sub_users.map(sub_user => parseInt(sub_user.user_id));

            $.ajax({
                url: '{{ route("admin.get.partner.sub-users") }}',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                type: 'GET',
                success: function(data) {
                    const subUsers = JSON.parse(data)
                    const subUsersText = subUsers.map(subUser => {
                        return `<div class="form-check">
                                    <input 
                                        class="form-check-input sub-users-checkbox" 
                                        type="checkbox" 
                                        value="${subUser.id}" 
                                        id="sub-users-checkbox-${subUser.id}" 
                                        ${curretSubUsers.includes(subUser.id) ? 'checked' : ''}
                                    />
                                    <label class="form-check-label" for="sub-users-checkbox-${subUser.id}">
                                        ${subUser.name}
                                    </label>
                                </div>`;
                    }).join('');
                    
                    $('.sub-users-container').html(subUsersText);
                },
                fail: function(xhr, status, error) {
                    $('.sub-users-container').html(`No sub-users found or something went wrong.`);
                }
            });
                                 
            
            $('#partner_sub_users_modal').find('#partner-name').html( row['partner_name'] );
            $('#partner_sub_users_modal #partner_id').val(row.id);
        });


        //show download partner transactions modal
        $('#partner_table').on('click', '.partner-downloads', function(event) {
            event.preventDefault();
            $tr = $(this).closest('tr').hasClass('child') ? $(this).closest('tr').prev() : $(this).closest('tr');
            var partner = partner_table.row($tr).data();            
            $('#partner_download_transactions_modal').modal('show');
            const dataAttr = $(this).data();
            const downloadTypeText = `${partner.partner_name} User Transactions.`;
            $('#partner_download_transactions_modal #download-type').html(downloadTypeText);
            $('#partner_download_transactions_modal #download-transaction-partner-id').val(partner.id);
            //download-type

        })
        //end show download parnter transactions modal

        //proceed downloading of partner transaction
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
        //end proceed downloading of partner transaction       

        $('#partner_sub_users_modal').on('click', '.partner_sub_users_save', function(event) {
            event.preventDefault();
            const that = $(this);
            $(this).prop('disabled', true);

            const checkedSubUsers = [];
            $('.sub-users-checkbox:checked').each( function(){
                checkedSubUsers.push(this.value)
             });

            const partner_id = $('#partner_sub_users_modal #partner_id').val();

            $.ajax({
                url: '{{ route("admin.update.partner.sub-users") }}',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                type: 'PUT',
                dataType: 'json',
                data: {
                    partner_id : partner_id,
                    sub_users: checkedSubUsers
                },
                success: function(data) {
                    // console.log('data:', data, data.message)
                    that.prop('disabled', false);
                    partner_table.ajax.reload();
                    swal(data.message,"","success");
                   
                    
                },
                fail: function(xhr, status, error) {
                    console.log('error', error)
                }
            });
        });

        //end updating partner sub-users



        $('#partner_payout_modal').on('click', '.partner_payout_btn', function(event){
            $this = $(this);
            $.ajax({
                url: '{{ route("process_payout") }}',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                type: 'POST',
                data: { 
                    id: $this.data('partner_id'),
                    photo: $('#partner_payout_modal').find('.file-preview-image').attr('src'),
                    amount: $('#partner-payout-amount').val(),
                    useCredits: $('#partner-payout-use-credits').val(),
                    message: $('textarea#payout-message').val(),
                },
                beforeSend: function(){
                    if($('#partner_payout_modal').find('.file-preview-image').attr('src') != undefined){
                        $('.kv-upload-progress').removeClass('hide').css('display', 'block').find('.progress-bar').removeClass('progress-bar-success');
                    }
                    $this.prop('disabled', true).html('<i class="fa fa-spinner fa-pulse"></i> Submitting Request').siblings().prop('disabled', true).html('<i class="fa fa-ban" aria-hidden="true"></i> Close')
                },
                xhr: function() {
                    var xhr = new window.XMLHttpRequest();
                    xhr.upload.addEventListener("progress", function(evt){
                        if (evt.lengthComputable && $('#partner_payout_modal').find('.file-preview-image').attr('src') != undefined) {
                            var percentComplete = evt.loaded / evt.total;
                            $('.kv-upload-progress').removeClass('hide').css('display', 'block').find('.progress-bar').width((percentComplete * 100) + '%').text((percentComplete * 100) === 100 ? 'Upload Complete' : 'Uploading - ' + (percentComplete * 100) + '%').addClass((percentComplete * 100) === 100 ? 'progress-bar-success' : '');
                            $('.file-upload-indicator').css({
                                backgroundColor: '#dff0d8',
                                borderColor: '#d6e9c6'
                            });
                            $('.file-upload-indicator').find('i').addClass((percentComplete * 100) === 100 ? 'fa-check-circle text-success' : 'fa-hand-o-down text-warning').removeClass((percentComplete * 100) === 100 ? 'fa-hand-o-down text-warning' : 'fa-check-circle text-success'); 
                        }
                    }, false);

                    return xhr;
                },
                success: function(data) {
                    if (data.success) {
                        $('#partner_payout_modal').modal('hide');
                        swalModifier("Payout Success!", data.message, "success")
                        partner_table.ajax.reload( null, false );
                    }
                    else{
                        if(data.errors){
                            $.each( data.errors, function( key, value ) {
                                if (key == 'message') {
                                    $('#partner_payout_modal').find('.message').addClass('has-error')
                                    $('#partner_payout_modal').find('.message').find('.error-label').text(value);
                                }
                                if (key == 'photo') {
                                    $('#partner_payout_modal').find('.image').addClass('has-error');
                                    $('#partner_payout_modal').find('.image').find('.error-label').text(value);
                                }
                            });
                        }
                        else{
                            swalModifier("Can't process payout!", data.message, "error")
                        }
                    }
                    $this.prop('disabled', false).html('Submit').siblings().prop('disabled', false).html('Close');
                    $('.kv-upload-progress').addClass('hide').css('display', 'none').find('.progress-bar').width('0%').removeClass('progress-bar-success');
                    $('.file-upload-indicator').css({
                        backgroundColor: '#dff0d8',
                        borderColor: '#d6e9c6'
                    });
                    $('.file-upload-indicator').find('i').addClass('fa-hand-o-down text-warning').removeClass('fa-check-circle text-success').addClass('fa-hand-o-down text-warning'); 
                },
                fail: function(xhr, status, error) {
                    console.log(error);
                }
            });
        });

        $('#partner_admin_transactions_table').on('click', '.view-receipt', function(event) {
            event.preventDefault();
            $tr = $(this).closest('tr').hasClass('child') ? $(this).closest('tr').prev() : $(this).closest('tr');
            var row = partner_admin_transactions_table.row($tr).data();
            if (row['picture']) {
                $('#viewReceipt').find('h5').html('Receipt')
                $('#viewReceipt').find('.modal-body').html('<image class="img-responsive" src="'+(url+row['picture']).replace('/uploads','')+'"/>')
                $('#viewReceipt').modal('show');
            }else{
                swal("No Receipt!", 'No receipt has been uploaded', "warning");

            }
        });

        $('#partner_user_deposit_table').on('click', '.view-receipt', function(event) {
            event.preventDefault();
            $tr = $(this).closest('tr').hasClass('child') ? $(this).closest('tr').prev() : $(this).closest('tr');
            var row = partner_user_deposit_table.row($tr).data();
            if (row['picture']) {
                $('#viewReceipt').find('h5').html('Receipt')
                $('#viewReceipt').find('.modal-body').html('<image class="img-responsive" src="'+(url+row['picture']).replace('/uploads','')+'"/>')
                $('#viewReceipt').modal('show');
            }else{
                swal("No Receipt!", 'No receipt has been uploaded', "warning");

            }
        });

        $('#partner_user_cashout_table').on('click', '.view-receipt', function(event) {
            event.preventDefault();
            $tr = $(this).closest('tr').hasClass('child') ? $(this).closest('tr').prev() : $(this).closest('tr');
            var row = partner_user_cashout_table.row($tr).data();
            if (row['picture']) {
                $('#viewReceipt').find('h5').html('Receipt')
                $('#viewReceipt').find('.modal-body').html('<image class="img-responsive" src="'+(url+row['picture']).replace('/uploads','')+'"/>')
                $('#viewReceipt').modal('show');
            }else{
                swal("No Receipt!", 'No receipt has been uploaded', "warning");

            }
        });
        
        $('#partner_admin_transactions_table').on('click', '.approve', function(event) {
            event.preventDefault();
            $tr = $(this).closest('tr').hasClass('child') ? $(this).closest('tr').prev() : $(this).closest('tr');
            var row = partner_admin_transactions_table.row($tr).data();

            if(!!row.picture == false){
                swal({
                    title: `Transaction Approval Error.`,
                    text: `Please upload the receipt first before approving this deposit.`,
                    type: `error`,
                    html: true,
                });    
                
                return false;
            }else{
                swal({
                    title: "Are you sure?",
                    text: "Transaction <span style='font-weight:bold;color: #820804'>"+row.code+"</span> will be approved",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: "btn-danger",
                    confirmButtonText: "Yes, please continue!",
                    showLoaderOnConfirm: true,
                    closeOnConfirm: false,
                    html:true
                },
                function(){
                    $.ajax({
                        url: '{{ route("partner_transactions") }}',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        type: 'POST',
                        data: { id: row['id'], status: 1, type: row['type'], partner_id: row['partner_id']},
                        success: function(data) {
                            if (data.success) {
                                swal("Approved!", data.message, "success");
                                partner_admin_transactions_table.ajax.reload( null, false );
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
            }

        });

        $('#partner_user_deposit_table').on('click', '.decline-deposit', function(event) {
            $tr = $(this).closest('tr').hasClass('child') ? $(this).closest('tr').prev() : $(this).closest('tr');
            var transaction = partner_user_deposit_table.row($tr).data(), rejection = $('#partner_rejection_modal');
            rejection.find('h5').html('Reject Deposit <span style="color: #820804; font-weight: 700;">'+ transaction.code +'</span>');
            rejection.find('.name_span_text').text(transaction.partner_transactions.name);
            rejection.find('.code_span_text').text(transaction.code);
            rejection.find('#rejection_type').data('type', 'deposit');
            rejection.find('#reject-partner-btn').css('display', 'none');
            rejection.find('#reject-save-btn').data('transaction_id', transaction.id).attr('id', 'reject-save-btn').data('trade_type', 'partner-user');
            rejection.modal('show');
        });

        $('#partner_user_cashout_table').on('click', '.decline-cashout', function(event) {
            $tr = $(this).closest('tr').hasClass('child') ? $(this).closest('tr').prev() : $(this).closest('tr');
            var transaction = partner_user_cashout_table.row($tr).data(), rejection = $('#partner_rejection_modal');
            rejection.find('h5').html('Reject Cashout <span style="color: #820804; font-weight: 700;">'+ transaction.code +'</span>');
            rejection.find('.name_span_text').text(transaction.partner_transactions.name);
            rejection.find('.code_span_text').text(transaction.code);
            rejection.find('.btn-primary').data('transaction_id', transaction.id).data('buyer_id', transaction.user_id).data('trade_type', 'partner-user');
            rejection.modal('show');
        });

        $('#partner_user_cashout_table').on('click', '.mark-verified-cashout', function(event) {
            $tr = $(this).closest('tr').hasClass('child') ? $(this).closest('tr').prev() : $(this).closest('tr');
            var transaction = partner_user_cashout_table.row($tr).data(), rejection = $('#partner_cashout_mark_verified_modal');
            rejection.find('h5').html('Mark User Cashout (vP) as Verified: <span style="color: #337ab7; font-weight: 700;">'+ transaction.code +'</span>');
            rejection.find('.name_span_text').html(transaction.partner_transactions.name);
            rejection.find('.partner_name_span_text').html(transaction.partner.partner_name);
            rejection.find('.code_span_text').text(transaction.code);
            rejection.find('.btn-primary').data('transaction_id', transaction.id).data('buyer_id', transaction.user_id).data('trade_type', 'partner-user');
            rejection.modal('show');
        });
        
        $('#partner_admin_transactions_table').on('click', '.btn-reject', function(event) {
            $tr = $(this).closest('tr').hasClass('child') ? $(this).closest('tr').prev() : $(this).closest('tr');
            var transaction = partner_admin_transactions_table.row($tr).data(), rejection = $('#partner_rejection_modal');
            rejection.find('h5').html('Reject <span style="color: #820804; font-weight: 700;">'+ transaction.code +'</span>');
            rejection.find('.name_span_text').text(transaction.partner_transactions.name);
            rejection.find('.code_span_text').text(transaction.code);
            rejection.find('#rejection_type').data('type', transaction.type);
            rejection.find('.btn-primary').data('transaction_id', transaction.id).data('buyer_id', transaction.user_id).data('trade_type', 'partner-admin').data('partner_id',transaction.partner_id);
            rejection.modal('show');
        });

        $('#reject-save-btn').click(function(event) {
            var $modal = $(this).closest('.modal');
            var $this = $(this),
                _url = $this.data('trade_type') == 'partner-user' ? ( $('#rejection_type').data('type') == 'deposit' ? '{{route("json_partner_user_deposit")}}' : '{{route("json_partner_user_cashout")}}' ) : '{{ route("declined_transactions") }}';
            $this.prop('disabled', true);
            $this.button('progress');
            if( $modal.find('textarea').val() != ''){
                $.ajax({
                    url: _url,  //Server script to process data
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    type: 'POST',
                    data: {partner_comment: $modal.find('textarea').val(),id:$this.data('transaction_id'),approved:'declined', buyer_id:$this.data('buyer_id'),status: 2, type: $('#rejection_type').data('type'), partner_id: $this.data('partner_id')},
                    success: function(data){
                        if(data.success){
                            $modal.modal('hide');
                            swal("Success!", data.message, "success");
                        }
                        else {
                            swal("Error!", data.message, "error");
                        }
                        partner_user_deposit_table.ajax.reload( null, false );
                        partner_user_cashout_table.ajax.reload( null, false );
                        partner_admin_transactions_table.ajax.reload( null, false );
                        $this.prop('disabled', false);
                        $this.button('reset');
                    },
                });
            }else{
                $modal.find('textarea').parent().addClass('has-error');
                $modal.find('textarea').parent().find('.error-label').text('Message is needed');
                $this.prop('disabled', false);
                $this.button('reset');
            }
        });

        $('#mark-as-verfied-save-btn').click(function(event) {
            var $modal = $(this).closest('.modal');
            var $this = $(this),
                _url = $this.data('trade_type') == 'partner-user' ? ( $('#rejection_type').data('type') == 'deposit' ? '{{route("json_partner_user_deposit")}}' : '{{route("json_partner_user_cashout")}}' ) : '{{ route("declined_transactions") }}';
            $this.prop('disabled', true);
            $this.button('progress');
            if( $modal.find('textarea').val() != ''){
                $.ajax({
                    url: _url,  //Server script to process data
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    type: 'POST',
                    data: {partner_comment: $modal.find('textarea').val(),id:$this.data('transaction_id'),approved:'verified', buyer_id:$this.data('buyer_id'),status: 2, type: $('#rejection_type').data('type'), partner_id: $this.data('buyer_id')},
                    success: function(data){
                        if(data.success){
                            $modal.modal('hide');
                            swal("Success!", data.message, "success");
                        }
                        else {
                            swal("Error!", data.message, "error");
                        }
                        partner_user_deposit_table.ajax.reload( null, false );
                        partner_user_cashout_table.ajax.reload( null, false );
                        partner_admin_transactions_table.ajax.reload( null, false );
                        $this.prop('disabled', false);
                        $this.button('reset');
                    },
                });
            }else{
                $modal.find('textarea').parent().addClass('has-error');
                $modal.find('textarea').parent().find('.error-label').text('Message is needed');
                $this.prop('disabled', false);
                $this.button('reset');
            }
        });      



        $('.partner_discrepancy').click(function(event) {
            var $this = $(this);
            $this.prop('disabled', true);
            $this.button('progress');
            var form = $('#partner_discrepancy_form')[0];
            $('#partner-image').on('filebatchselected');
            var message = $('textarea#partner-message').val();
            var amount = $('input[name="partner_amount"]').val();
            var partner_id = $(this).data('partner_id');
            var mop = $('[name="partner_provider"]', form).val();
            var user_id = $('input[type="hidden"][name="user_id"]', form).val();
            var id = $('input[type="hidden"][name="id"]', form).val();
            var note_id = $('input[type="hidden"][name="note_id"]', form).val();
            var discrepancy_id = $('input[type="hidden"][name="discrepancy_id"]', form).val();
            var photo = $('.file-preview-image').attr('src');
            var _url = $('input[type="hidden"][name="approveWDiscrepancy"]', form).val() ? '{{ route('partnerDiscrepancy') }}' : '{{ route('adminExtraActionOnPartner') }}';
            $.ajax({
                url: _url,
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                type: 'POST',
                data: {photo:photo,message:message,amount:amount,mop:mop,partner_id:partner_id,user_id:user_id,id:id,note_id:note_id,discrepancy_id:discrepancy_id},
                success: function(data) {

                    if (data.errors) {
                        $.each( data.errors, function( key, value ) {
                            if (key == 'message') {
                                $('#partner_discrepancy_form').find('.message').addClass('has-error');
                                $('#partner_discrepancy_form').find('textarea#partner-message').parent().find('.error-label').text(value[0]);
                            }
                            if (key == 'photo') {
                                $('#partner_discrepancy_form').find('.image').addClass('has-error');
                            }
                        });
                    }else{
                        if(!data.success){
                            swal({
                                title: "Oops!",
                                text: data.message,
                                type: "error",
                                html: true
                            });
                        }
                        else{
                            if ($('.file-preview-image').attr('src') != null) {
                                $('.kv-upload-progress').removeClass('hide').css('display', 'block').find('.progress-bar').css('width', '100%').text('Done')
                                $('.file-upload-indicator').css({
                                    backgroundColor: '#dff0d8',
                                    borderColor: '#d6e9c6'
                                });
                                $('.file-upload-indicator').find('i').removeClass('fa-hand-o-down').removeClass('text-warning').addClass('fa-check-circle').addClass('text-success')
                            }
                            $('#partner_modal_discrepancy #partner-image').fileinput('clear')
                            swal("Added!", 'Discrepancy successfully added', "success");
                            partner_admin_transactions_table.ajax.reload( null, false );
                            partner_table.ajax.reload( null, false );
                        }
                        $('#partner_modal_discrepancy').modal('hide');
                    }
                    $this.prop('disabled', false);
                    $this.button('reset');
                },
                fail: function(xhr, status, error) {
                    console.log(error);
                }
            });
            
        });

        $('#partner_provider').trigger('change');

        $('#partner_admin_transactions_table').on('click', '.discrepancy', function(event) {
            event.preventDefault();
            $tr = $(this).closest('tr').hasClass('child') ? $(this).closest('tr').prev() : $(this).closest('tr');
            var transaction = partner_admin_transactions_table.row($tr).data(); 
            var form = $('#partner_discrepancy_form')[0];
            var note_id
            var discrepancy_id
            var samples = transaction.discrepancy
            $('input[type="hidden"][name="user_id"]', form).remove();
            $('input[type="hidden"][name="id"]', form).remove();
            $('input[type="hidden"][name="approveWDiscrepancy"]', form).remove();
            $('#partner-discrepancy-amount').val('');
            $('textarea#partner-message').val('');
            $("#partner_provider").select2().val(null).trigger('change');
            $partner_image.fileinput('refresh');
            $('#partner_discrepancy_form').find('.message').removeClass('has-error');
            $partner_image.fileinput('destroy');

            if ($partner_image.data('fileinput')) {
                return;
            }
            partnerInitPlugin();
            if ($partner_image.val()) {
                $partner_image.trigger('change');
            }
            // if (transaction.notes.length > 0) {
            //     $('textarea#message').val(transaction.notes[transaction.notes.length - 1].message );
            // }else{
            //     $('textarea#message').val('');
            // }
            $('#partner_modal_discrepancy').find('#partner-bc-code').text(transaction.code);
            $('#partner_modal_discrepancy').find('.partner_discrepancy').data('partner_id', transaction.partner.id);
            partner_discrepancy_table.clear().draw();
            if (transaction.discrepancy.length > 0) {
                partner_discrepancy_table.rows.add(transaction.discrepancy).draw();
                partner_discrepancy_table.columns.adjust().draw();
            }
            $(form).append(
                $('<input>')
                .attr('type', 'hidden')
                .attr('name', 'user_id')
                .val(transaction['partner_transactions'].id)
            );
            $(form).append(
                $('<input>')
                .attr('type', 'hidden')
                .attr('name', 'id')
                .val(transaction['id'])
            );
            if (transaction.picture == null) {
                $(form).append(
                    $('<input>')
                    .attr('type', 'hidden')
                    .attr('name', 'approveWDiscrepancy')
                    .val(1)
                );
            }

            $('#partner_modal_discrepancy').modal('show').data('transaction_id', transaction.id);
            $('#partner-discrepancy-amount').currencyFormat();
        });

        // When hiding the Discrepancy Modal (Partners)
        $('#partner_modal_discrepancy').on('hidden.bs.modal', function(event){
            $(this).find('#partner-image').fileinput('clear');
        });

        // When hiding the Payout Modal (Partners)
        $('#partner_payout_modal').on('hidden.bs.modal', function(event){
            $(this).find('#payout-image').fileinput('clear');
            $(this).find('textarea#payout-message').val(null);
            $(this).find('.partner_payout_btn').prop('disabled', false).html('Submit');
        });

        $('#partner_admin_transactions_table').on('click', '.view-details', function(event) {
            event.preventDefault();
            $tr = $(this).closest('tr').hasClass('child') ? $(this).closest('tr').prev() : $(this).closest('tr');
            var transaction = partner_admin_transactions_table.row($tr).data();
            var data = JSON.parse(transaction.data)
            $('#view-details').find('.modal-body').find('#deposit-steps').empty();
            renderStepsManual(data.mop.split('-'),transaction)
            $('#view-details').modal('show');
        });

        $('#partner_user_deposit_table').on('click', '.view-details', function(event) {
            event.preventDefault();
            $tr = $(this).closest('tr').hasClass('child') ? $(this).closest('tr').prev() : $(this).closest('tr');
            var transaction = partner_user_deposit_table.row($tr).data();
            var data = JSON.parse(transaction.data);
            $('#view-details').find('.modal-body').find('#deposit-steps').empty();
            renderStepsManual(data.mop.split('-'),transaction)
            $('#view-details').modal('show');
        });

        $('#partner_user_cashout_table').on('click', '.view-details', function(event) {
            event.preventDefault();
            $tr = $(this).closest('tr').hasClass('child') ? $(this).closest('tr').prev() : $(this).closest('tr');
            var transaction = partner_user_cashout_table.row($tr).data();
            var data = JSON.parse(transaction.data);
            $('#view-details').find('.modal-body').find('#deposit-steps').empty();
            renderStepsManual(data.mop.split('-'),transaction)
            $('#view-details').modal('show');
        });

        $('#partner_table').on('click', '.view-partner-info', function(event) {
            event.preventDefault();
            $tr = $(this).closest('tr').hasClass('child') ? $(this).closest('tr').prev() : $(this).closest('tr');
            var transaction = partner_table.row($tr).data();
            var hex = "partner-details";
            $('#view-details').find('.modal-body').find('#deposit-steps').empty();
            $('#view-details').find('.modal-body').find('#update-info').data('transaction', transaction);
            renderStepsManual(hex.split('-'),transaction)
            $('#view-details').modal('show');
            $('.open-update-info').show();
        });

        $('#partner_approval_table').on('click', '.view-partner-info', function(event) {
            event.preventDefault();
            $tr = $(this).closest('tr').hasClass('child') ? $(this).closest('tr').prev() : $(this).closest('tr');
            var transaction = partner_approval_table.row($tr).data();
            var hex = "partner-details";
            $('#view-details').find('.modal-body').find('#deposit-steps').empty();
            $('#view-details').find('.modal-body').find('#update-info').data('transaction', transaction);
            renderStepsManual(hex.split('-'),transaction)
            $('#view-details').modal('show');
            $('.open-update-info').show();
        });

        $('#partner_table').on('click', '.view-payout-info', function(event) {
            event.preventDefault();
            $tr = $(this).closest('tr').hasClass('child') ? $(this).closest('tr').prev() : $(this).closest('tr');
            var transaction = partner_table.row($tr).data(), hex = "partner-account";
            $('#view-details').find('.modal-body').find('#deposit-steps').empty();
            $('#view-details').find('.modal-body').find('#update-info').data('transaction', transaction);
            renderStepsManual(hex.split('-'),transaction)
            $('#view-details').modal('show');
            $('.open-update-info').show();
        });

        $('#partner_approval_table').on('click', '.view-payout-info', function(event) {
            event.preventDefault();
            $tr = $(this).closest('tr').hasClass('child') ? $(this).closest('tr').prev() : $(this).closest('tr');
            var transaction = partner_approval_table.row($tr).data(), hex = "partner-account";
            $('#view-details').find('.modal-body').find('#deposit-steps').empty();
            $('#view-details').find('.modal-body').find('#update-info').data('transaction', transaction);
            renderStepsManual(hex.split('-'),transaction)
            $('#view-details').modal('show');
            $('.open-update-info').show();
        });

        $('#partner_payout_table').on('click', '.view-receipt', function(event) {
            event.preventDefault();
            $tr = $(this).closest('tr').hasClass('child') ? 
                $(this).closest('tr').prev() : $(this).closest('tr');
            var row = partner_payout_table.row($tr).data();
            if (row['receipt']) {
                $('#viewReceipt').find('h5').html('Receipt')
                $('#viewReceipt').find('.modal-body').html('<image class="img-responsive" src="' + (url + row['receipt']).replace('/uploads','') + '"/>')
                $('#viewReceipt').modal('show');
            }else{
                swal("No Receipt!", 'No receipt has been uploaded', "warning");
            }
        });

        $('#view-details').on('hidden.bs.modal', function(){
            $('#view-details').find('#update-info').hide();
            $('#view-details').find('.details-list').show();
            $('.open-update-info').hide();
            $('.save-partner-info').hide();
            $('#partnerForm')[0].reset();
        });

        $('.open-update-info').click(function(){
            $('#view-details').find('#update-info').show();
            $('#view-details').find('.details-list').hide();
            $(this).hide();
            $('.save-partner-info').show();
            partnerInfo( $('#view-details').find('.modal-body').find('#update-info').data('transaction') );
        });

        $('.save-partner-info').click(function(){
            if(setPartnerInfo()){
                $.ajax({
                    url:'{{ route("json_update_partner_info") }}',
                    type:'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    data: {
                        user_id: $(".partner-select-logo").data('user_id'),
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
                            swal("Updated Partner Info!", "Partnership information has been updated.", "success");
                            $('#view-details').modal('hide');
                            partner_approval_table.ajax.reload();
                            partner_table.ajax.reload();
                        }
                    }
                });
            }
        });

        $('#partner_discrepancy_table').on('click', '.view-receipt', function(event) {
            event.preventDefault();
            $tr = $(this).closest('tr').hasClass('child') ? 
                $(this).closest('tr').prev() : $(this).closest('tr');
            var row = partner_discrepancy_table.row($tr).data();
            if (row['picture']) {
                $('#viewReceipt').find('h5').html('Receipt')
                $('#viewReceipt').find('.modal-body').html('<image class="img-responsive" src="'+(url+row['picture']).replace('/uploads','')+'"/>')
                $('#viewReceipt').modal('show');
            }else{
                swal("No Receipt!", 'No receipt has been uploaded', "warning");
            }
        });


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

        function renderStepsManual(mop,transaction)
        {
            switch (mop[1]) {
                case 'deposit':
                    var data = {}, bank=mop[0], bopen, bclose, mopen, mclose, accountnumber;
                    var container = $("#manual-steps-template").html();
                    switch (mop[0]) {
                        case 'BDO':
                            bopen = '9AM'
                            bclose = '3PM'
                            mopen = '10AM'
                            mclose = '7PM'
                            accountnumber = transaction.type == 'deposit' ? '007610166283' : transaction.partner.bdo_account;
                            break;
                        case 'BPI':
                            bopen = '9AM'
                            bclose = '3PM'
                            mopen = '10AM'
                            mclose = '6PM'
                            accountnumber = transaction.type == 'deposit' ? '1019165937' : transaction.partner.bpi_account;
                            break;
                        case 'Metrobank':
                            bopen = '9AM'
                            bclose = '3PM'
                            mopen = '10AM'
                            mclose = '6PM'
                            accountnumber = '486-3-48623798-9'
                            accountnumber = transaction.type == 'deposit' ? '1019165937' : 'No data / Contact Partner';
                            break;
                    }
                    data.bank = bank;
                    data.bopen = bopen;
                    data.bclose = bclose;
                    data.mopen = mopen;
                    data.mclose = mclose;
                    data.accountnumber = accountnumber;
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
                case 'details':
                    var container = $("#"+mop[0]+"-details").html();
                    var data = {}
                    data.partner_name = transaction.partner_name;
                    data.address = transaction.address + ", " + transaction.province.province;
                    data.name = transaction.user_owner.name;
                    data.person = transaction.contact_person;
                    data.mobile = transaction.mobile_number;
                    data.landline = transaction.landline_number ? transaction.landline_number : "No provided telephone number";
                    data.email = transaction.email;
                    data.fb_link = transaction.facebook_link;
                    data.bpi_num = transaction.bpi_account ? transaction.bpi_account : "No BPI account";
                    data.bpi_name = transaction.bpi_account_name ? transaction.bpi_account_name : "No BPI account";
                    data.bdo_num = transaction.bdo_account ? transaction.bdo_account : "No BDO account" ;
                    data.bdo_name = transaction.bdo_account_name ? transaction.bdo_account_name : "No BDO account";
                    $('#view-details').find('.modal-body').find('#deposit-steps').append(Mustache.render(container, data));
                    break;
                case 'account':
                    var container = $("#"+mop[0]+"-account").html();
                    var data = {}
                    data.name = transaction.user_owner.name;
                    data.bpi_num = transaction.bpi_account ? transaction.bpi_account : "N/A";
                    data.bpi_name = transaction.bpi_account_name ? transaction.bpi_account_name : "N/A";
                    data.bdo_num = transaction.bdo_account ? transaction.bdo_account : "N/A" ;
                    data.bdo_name = transaction.bdo_account_name ? transaction.bdo_account_name : "N/A";
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
                case 'others':
                    var container = $("#"+mop[0]+"-others").html();
                    var data = {}
                    $('#view-details').find('.modal-body').find('#deposit-steps').append(Mustache.render(container, data));
                    break;
            }

        }

        function swalModifier(title, message, type){
            type = type || 'info';
            return swal({
                title: title,
                text: message,
                type: type,
                html: true,
            });
        }

        function partnerInfo(partner)
        {
            var div = $('#view-details').find('.modal-body').find('#update-info').find('#partnerForm');
            div.find('#partner_name').val(partner.partner_name);
            div.find('#partner_address').val(partner.address);
            // .text(partner.province.province);
            div.find('#partner_province option').filter(function () { return $(this).html() == partner.province.province; }).prop('selected', true)
            div.find('#partner_mobile').val(partner.mobile_number);
            div.find('.partner-select-logo').data('user_id', partner.user_owner.id);
            div.find('#partner_telephone').val(partner.landline_number);
            div.find('#partner_person').val(partner.contact_person);
            div.find('#partner_email').val(partner.email);
            div.find('#partner_fb_link').val(partner.facebook_link);
            div.find('#partner_operation').val(partner.operation_time);
            div.find('#partner_mop').val(partner.payment_mode);
            div.find('#partner_details').val(partner.details);
            div.find('#partner_bpi').val(partner.bpi_account);
            div.find('#partner_bpi_name').val(partner.bpi_account_name);
            div.find('#partner_bdo').val(partner.bdo_account);
            div.find('#partner_bdo_name').val(partner.bdo_account_name);
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
            $('#partner_email').siblings('.error-label ').css('display', 'block');
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
        return pass;
    }

    $(".partner-select-logo").fileinput({
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
            data: {photo:$('.file-preview-image').attr('src'),user_id: $(this).data('user_id'), },
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

    $('.nav-tabs a').on('shown.bs.tab', function (e) {
        switch(e.currentTarget.hash) {
            case '#partner-deposit':
                partner_user_deposit_table.ajax.reload();
                break;
            case '#partner-cashout':
                partner_user_cashout_table.ajax.reload();
                break;
            case '#partner-earning':
                partner_payout_table.ajax.reload();
                break;
            case '#partner-transaction':
                partner_admin_transactions_table.ajax.reload();
                break;
            case '#partner-approval':
                partner_approval_table.ajax.reload();
                break;
            case '#partner-manage':
                partner_table.ajax.reload();
                break;
        }
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

                        partner_admin_transactions_table.ajax.reload();

                        // switch(refreshTable){
                        //     case 'partner_admin_transactions_table': 
                        //         break
                        // }
                        
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

});
</script>

<script type="text/javascript">
    $(document).ready(function(){
        var appJSscriptLoaded = false;
        var loadedBy = '';
        $('#gift-codes-market').on('shown.bs.tab', function (e) {
            var target = $(e.target).attr("href") // activated tab
            if(target == '#gift-codes' && !appJSscriptLoaded){
                window.location.vueElement = "#gift-codes"
                $.getScript("/js/2ez-202010-secured.js", function(){
                    appJSscriptLoaded = true;
                    loadedBy = 'gift-codes';
                });
            }

            if(appJSscriptLoaded && loadedBy != 'gift-codes'){
                alert('Something went wrong, please refresh this page.');
                location.reload();
            }
        });

        $('#site-account-manager-link').on('shown.bs.tab', function (e) {
            var target = $(e.target).attr("href") // activated tab
            if(target == '#site-account-manager' && !appJSscriptLoaded){
                window.location.vueElement = "#site-account-manager"
                $.getScript("/js/2ez-202010-secured.js", function(){
                    appJSscriptLoaded = true;
                    loadedBy = 'site-account-manager';
                });
            }

            if(appJSscriptLoaded && loadedBy != 'site-account-manager'){
                alert('Something went wrong, please refresh this page.');
                location.reload();
            }
        });
    })
              
</script>

@endsection