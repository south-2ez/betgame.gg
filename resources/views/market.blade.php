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
        box-shadow: 1px 1px 2px #888
;        margin: 5px 0px;
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
        font-size: 0.7em;
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
    .error-label {
        color: #a94442;
        font-weight: bold;
    }
    .thumbnail {
        padding: 0;
    }

    .hovereffect {
    width:100%;
    height:100%;
    float:left;
    overflow:hidden;
    position:relative;
    text-align:center;
    cursor:default;
    }

    .hovereffect .overlay {
    width:100%;
    height:100%;
    position:absolute;
    overflow:hidden;
    top:0;
    left:0;
    opacity:0;
    background-color:rgba(0,0,0,0.5);
    -webkit-transition:all .4s ease-in-out;
    transition:all .4s ease-in-out
    }

    .hovereffect img {
    display:block;
    position:relative;
    -webkit-transition:all .4s linear;
    transition:all .4s linear;
    }

    .hovereffect h2 {
    text-transform:uppercase;
    color:#fff;
    text-align:center;
    position:relative;
    font-size:17px;
    background:rgba(0,0,0,0.6);
    -webkit-transform:translatey(-100px);
    -ms-transform:translatey(-100px);
    transform:translatey(-100px);
    -webkit-transition:all .2s ease-in-out;
    transition:all .2s ease-in-out;
    padding:10px;
    }

    .hovereffect a.info {
    text-decoration:none;
    display:inline-block;
    text-transform:uppercase;
    color:#fff;
    border:1px solid #fff;
    background-color:transparent;
    opacity:0;
    filter:alpha(opacity=0);
    -webkit-transition:all .2s ease-in-out;
    transition:all .2s ease-in-out;
    margin:50px 0 0;
    padding:7px 14px;
    }

    .hovereffect a.info:hover {
    box-shadow:0 0 5px #fff;
    }

    .hovereffect:hover img {
    -ms-transform:scale(1.2);
    -webkit-transform:scale(1.2);
    transform:scale(1.2);
    }

    .hovereffect:hover .overlay {
    opacity:1;
    filter:alpha(opacity=100);
    }

    .hovereffect:hover h2,.hovereffect:hover a.info {
    opacity:1;
    filter:alpha(opacity=100);
    -ms-transform:translatey(0);
    -webkit-transform:translatey(0);
    transform:translatey(0);
    }

    .hovereffect:hover a.info {
    -webkit-transition-delay:.2s;
    transition-delay:.2s;
    }
    p{
        color: #ffff;
    }
    #overlay {
        position: fixed;
        display: none;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0,0,0,0.5);
        z-index: 2;
        cursor: pointer;
    }

    #text-overlay{
        position: absolute;
        top: 50%;
        left: 50%;
        font-size: 50px;
        font-weight: bold;
        color: white;
        transform: translate(-50%,-50%);
        -ms-transform: translate(-50%,-50%);
    }

    @media screen and (max-width:480px){
        #text-overlay{
            font-size: 14px;
        }
        h2{
            font-size: 14px;
        }
    }
</style>
<link rel="stylesheet" href="{{ asset('bower_components/bootstrap-sweetalert/dist/sweetalert.css') }}">
@endsection

@section('content')
<div class="main-container dark-grey">
    <div class="m-container1" style="width: 98% !important;">
        <div class="main-ct">
            <div class="title2">2ez Market</div>
            <div class="clearfix"></div>

            <div class="blk-1">
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-3 pull-right">
                            <div class="input-group add-on">
                              <input class="form-control" placeholder="Search Product" name="srch-term" id="srch-term" type="text">
                              <div class="input-group-btn">
                                <button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
                              </div>
                            </div>                        
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="row" style="padding-top: 15px">
                        <div class="col-md-12">
                             <div class="row text-center text-lg-left">
                                <div class="col-md-12">
                                    <div id="overlay" onclick="offOverlay()">
                                        <div id="text-overlay">OUT OF STOCK</div>
                                    </div>
                                    @if(!\App::environment('prod')) 
                                    @foreach($upload as $file)
                                    <div class="col-lg-2 col-md-3 col-sm-6 col-xs-6" style="margin: 5px 0px;">
                                        <div class="hovereffect">
                                            <a href="#" class="d-block mb-4 h-100">
                                            <img class="img-responsive" src="{{ asset('../storage/product/'.$file->product_image) }}">
                                            </a>
                                            <div class="overlay">
                                               <h2 style="font-size: 14px;">{{ $file->product_desc }}</h2>
                                               @if(Auth::user()->credits >= $file->product_price)
                                               <a class="info" onclick="" data-toggle="modal" data-target=".checkout-modal{{$file->id}}">Add to cart</a>
                                               @else
                                               <a class="info insufficient-credit">Add to cart</a>
                                               @endif
                                            </div>
                                            <div style="background: #2d2d2d; padding: 9px;">                                                
                                                <span class="credits_top">EZ Credits: <span data-number="">₱ {{ $file->product_price }}.00</span></span>
                                                <p>{{ $file->product_name }}</p> 
                                            </div> 
                                        </div>                                                                  
                                    </div>                                  

                                    <!-- Checkout Modal -->                                    
                                    <div class="modal fade checkout-modal{{$file->id}}" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-hidden="true">
                                        <div class="modal-dialog">
                                          <div class="modal-content">
                                            <div class="modal-header">
                                              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                              <h3 id="myModalLabel">2EZ.bet Checkout</h3>
                                            </div>
                                            <div class="modal-body" id="market-wizard">                                              
                                            <div class="progress">
                                               <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="1" aria-valuemin="1" aria-valuemax="4" style="width: 20%;">
                                                 Step 1 of 2
                                               </div>
                                            </div>
                                            
                                            <div class="navbar">
                                            <div class="navbar-inner">
                                                  <ul class="nav nav-pills">
                                                    <li class="active">
                                                        <button class="btn btn-primary btn-sm" data-target="#step1" 
                                                                                               data-toggle="tab" 
                                                                                               data-step="1">Step 1</button>
                                                    </li>
                                                    <li>
                                                        <button class="btn btn-primary btn-sm" data-target="#step2{{$file->id}}" 
                                                                                               data-toggle="tab" 
                                                                                               data-step="2"  disabled="disabled" id="a-step2{{$file->id}}">Step 2</button>
                                                    </li>
                                                  </ul>
                                            </div>
                                            </div>   
                                            <form id="market-form{{$file->id}}">
                                                <div class="tab-content" style="margin-top: -20px;">
                                                <div class="tab-pane fade in active" id="step1">                                                   
                                                    <div class="col-md-12">                                                     
                                                        <div class="form-group">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <label>Name</label>
                                                                    <input type="text" class="form-control" readonly="readonly" id="buyer{{$file->id}}" name="i_buyer" value="{{ Auth::user()->name }}">
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label>Contact #</label>
                                                                    <input type="text" class="form-control" id="inputContactNumber{{$file->id}}" name="inputContactNumber{{$file->id}}">
                                                                </div>
                                                            </div>
                                                          
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="row">
                                                                <div class="form-group col-md-6">
                                                                    <label>City</label>
                                                                    <input type="text" class="form-control" id="inputCity{{$file->id}}" name="inputCity{{$file->id}}">
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                    <label>Province</label>
                                                                    <input type="text" class="form-control" id="inputProvince{{$file->id}}" name="inputProvince{{$file->id}}">
                                                                </div>
                                                            </div> 
                                                        </div>                                                          
                                                        <div class="form-group">
                                                          <label>Street</label>
                                                          <input type="text" class="form-control" id="inputStreet{{$file->id}}" name="inputStreet{{$file->id}}">
                                                        </div>                                                                                                   
                                                    </div>
                                                  <button class="btn btn-primary" id="nextBTN{{$file->id}}" data-target="#step2{{$file->id}}" 
                                                                                               data-toggle="tab"  
                                                                                               data-step="2" disabled="disabled">Continue</button>
                                                </div>

                                                <div class="tab-pane fade" id="step2{{$file->id}}">
                                                   <div class="col-md-12">
                                                    <div class="form-group">
                                                      <div class="row">
                                                        <div class="col-md-6">
                                                            <a href="#" class="d-block mb-4 h-100">
                                                            <img class="img-responsive" src="{{ asset('../storage/product/'.$file->product_image) }}">
                                                            </a>
                                                        </div>
                                                          <div class="form-group col-md-6">
                                                            <ul class="list-group">
                                                              <li class="list-group-item">
                                                                  <label>Product Name</label>
                                                                  <h5 id="inputProduct{{$file->id}}" name="inputProduct{{$file->id}}">{{$file->product_name}}</h5>
                                                              </li>
                                                              <li class="list-group-item">
                                                                  <label>Product Price</label>
                                                                  <div style="margin: 3px 0px 0px 0px;">
                                                                      <span class="credits_top">EZ Credits: <span data-number="" id="inputPrice{{$file->id}}" name="inputPrice{{$file->id}}">
                                                                        ₱ {{$file->product_price}}.00</span></span>

                                                                        <input type="hidden" id="price{{$file->id}}" value="{{$file->product_price}}"  name="price{{$file->id}}">
                                                                  </div>
                                                              </li>
                                                              <li class="list-group-item">
                                                                  <label>Total Price</label>
                                                                  <h4 id="total{{$file->id}}"></h4>
                                                              </li>
                                                            </ul>   
                                                              <div class="alert alert-warning">
                                                                <strong>Note!</strong> Shipping fee will carry out upon approve.
                                                              </div>         
                                                              <label>Quantity</label>
                                                              <input type="number" class="form-control" id="inputQuantity{{$file->id}}" name="inputQuantity{{$file->id}}">
                                                          </div>
                                                      </div> 
                                                    </div>
                                                   </div>
                                                   <button class="btn btn-success checkout-btn{{$file->id}}">Checkout</button>
                                                </div>
                                                </div>
                                            </form>
                                            </div>                                            
                                            <div class="modal-footer">
                                              <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Cancel</button>
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                      @endforeach
                                      @endif
                                    <!-- End Checkout Modal -->

                                    <div class="col-lg-2 col-md-3 col-sm-6 col-xs-6" style="margin: 5px 0px;">
                                        <div class="hovereffect">
                                            <a href="#" class="d-block mb-4 h-100">
                                            <img class="img-responsive" src="{{ asset('market_img/pc1.png')}}" alt="">
                                            </a>
                                            <div class="overlay">
                                               <h2 style="font-size: 14px;">[i7-7700HQ][GTX 1060 6GB][16GB DDR4][128GB SSD + 1TB HDD]</h2>
                                               <a class="info" onclick="onOverlay()">Add to cart</a>
                                            </div>
                                            <div style="background: #2d2d2d; padding: 9px;">
                                                <span class="credits_top">EZ Credits: <span data-number="">₱ 92,500.00</span></span>
                                                <p>MSI GS43VR PHANTOM PRO-069 14.0" Gaming Laptop</p> 
                                            </div> 
                                        </div>                                                                  
                                    </div>

                                    <div class="col-lg-2 col-md-3 col-sm-6 col-xs-6" style="margin: 5px 0px;">
                                        <div class="hovereffect">
                                            <a href="#" class="d-block mb-4 h-100">
                                            <img class="img-responsive" src="{{ asset('market_img/msi-m-2.png')}}" alt="">
                                            </a>
                                            <div class="overlay">
                                               <h2 style="font-size: 14px;">[IPS][i7-8750H][GTX1050Ti 4GB][16GB DDR4][128GB SSD + 1TB HDD]</h2>
                                               <a class="info" onclick="onOverlay()">Add to cart</a>
                                            </div>
                                            <div style="background: #2d2d2d; padding: 9px;">
                                                <span class="credits_top">EZ Credits: <span data-number="">₱ 82,500.00</span></span>
                                                <p>MSI GP63 LEOPARD-041 15.6" Gaming Laptop</p> 
                                            </div> 
                                        </div>                                                                  
                                    </div>

                                    <div class="col-lg-2 col-md-3 col-sm-6 col-xs-6" style="margin: 5px 0px;">
                                        <div class="hovereffect">
                                            <a href="#" class="d-block mb-4 h-100">
                                            <img class="img-responsive" src="{{ asset('market_img/ms1-laptop.png')}}" alt="">
                                            </a>
                                            <div class="overlay">
                                               <h2 style="font-size: 14px;">[IPS][i7-7700HQ][GTX 1050Ti 4GB][8GB DDR4][128GB SSD + 1TB HDD]</h2>
                                               <a class="info" onclick="onOverlay()">Add to cart</a>
                                            </div>
                                            <div style="background: #2d2d2d; padding: 9px;">
                                                <span class="credits_top">EZ Credits: <span data-number="">₱ 69,300.00</span></span>
                                                <p>MSI GL62M 7REX-1896 15.6" Gaming Laptop</p> 
                                            </div> 
                                        </div>                                                                  
                                    </div>

                                    <div class="col-lg-2 col-md-3 col-sm-6 col-xs-6" style="margin: 5px 0px;">
                                        <div class="hovereffect">
                                            <a href="#" class="d-block mb-4 h-100">
                                            <img class="img-responsive" src="{{ asset('market_img/giga.png')}}" alt="">
                                            </a>
                                            <div class="overlay">
                                               <h2 style="font-size: 14px;">[i7-7700HQ][GTX 1050Ti 4GB][16GB DDR4][256GB SSD + 1TB HDD]</h2>
                                               <a class="info" onclick="onOverlay()">Add to cart</a>
                                            </div>
                                            <div style="background: #2d2d2d; padding: 9px;">
                                                <span class="credits_top">EZ Credits: <span data-number="">₱ 71,400.00</span></span>
                                                <p>GIGABYTE SABRE 15K-KB3 15.6" Gaming Laptop</p> 
                                            </div> 
                                        </div>                                                                  
                                    </div>

                                    <div class="col-lg-2 col-md-3 col-sm-6 col-xs-6" style="margin: 5px 0px;">
                                        <div class="hovereffect">
                                            <a href="#" class="d-block mb-4 h-100">
                                            <img class="img-responsive" src="{{ asset('market_img/key1.png')}}" alt="">
                                            </a>
                                            <div class="overlay">
                                               <h2 style="font-size: 14px;">[World's fastest RGB mechanical gaming keyboard: Exclusive Romer-G Mechanical Switches with up to 25 percent faster actuation]</h2>
                                               <a class="info" onclick="onOverlay()">Add to cart</a>
                                            </div>
                                            <div style="background: #2d2d2d; padding: 9px;">
                                                <span class="credits_top">EZ Credits: <span data-number="">₱ 11,300.00</span></span>
                                                <p>Orion Spark Mechanical Gaming Keyboard</p> 
                                            </div> 
                                        </div>                                                                  
                                    </div>

                                    <div class="col-lg-2 col-md-3 col-sm-6 col-xs-6" style="margin: 5px 0px;">
                                        <div class="hovereffect">
                                            <a href="#" class="d-block mb-4 h-100">
                                            <img class="img-responsive" src="{{ asset('market_img/key2.png')}}" alt="">
                                            </a>
                                            <div class="overlay">
                                               <h2 style="font-size: 14px;">[Ten (10) additional sculpted FPS keys (W,A,S,D and 1 to 6) for superior reaction and control - interchangeable with the standard keyboard keys]</h2>
                                               <a class="info" onclick="onOverlay()">Add to cart</a>
                                            </div>
                                            <div style="background: #2d2d2d; padding: 9px;">
                                                <span class="credits_top">EZ Credits: <span data-number="">₱ 11,300.00</span></span>
                                                <p>Corsair Vengeance K60 Gaming Keyboard</p> 
                                            </div> 
                                        </div>                                                                  
                                    </div>

                                    <div class="col-lg-2 col-md-3 col-sm-6 col-xs-6" style="margin: 5px 0px;">
                                        <div class="hovereffect">
                                            <a href="#" class="d-block mb-4 h-100">
                                            <img class="img-responsive" src="{{ asset('market_img/razer.png')}}" alt="">
                                            </a>
                                            <div class="overlay">
                                               <h2 style="font-size: 14px;">[120Hz][i7-7700HQ][GTX 1060 6GB][16GB DDR4][256GB SSD + 2TB HDD]</h2>
                                               <a class="info" onclick="onOverlay()">Add to cart</a>
                                            </div>
                                            <div style="background: #2d2d2d; padding: 9px;">
                                                <span class="credits_top">EZ Credits: <span data-number="">₱ 119,900.00</span></span>
                                                <p>Razer Blade Pro 17.3” Gaming Laptop</p> 
                                            </div> 
                                        </div>                                                                  
                                    </div>

                                    <div class="col-lg-2 col-md-3 col-sm-6 col-xs-6" style="margin: 5px 0px;">
                                        <div class="hovereffect">
                                            <a href="#" class="d-block mb-4 h-100">
                                            <img class="img-responsive" src="{{ asset('market_img/game-controller.png')}}" alt="">
                                            </a>
                                            <div class="overlay">
                                               <h2 style="font-size: 14px;">[12 action buttons, an eight-way hat switch, and a rapid-fire trigger]</h2>
                                               <a class="info" onclick="onOverlay()">Add to cart</a>
                                            </div>
                                            <div style="background: #2d2d2d; padding: 9px;">
                                                <span class="credits_top">EZ Credits: <span data-number="">₱ 5,500.00</span></span>
                                                <p>Logitech Extreme 3D Pro Game Controller</p> 
                                            </div> 
                                        </div>                                                                  
                                    </div>

                                    <div class="col-lg-2 col-md-3 col-sm-6 col-xs-6" style="margin: 5px 0px;">
                                        <div class="hovereffect">
                                            <a href="#" class="d-block mb-4 h-100">
                                            <img class="img-responsive" src="{{ asset('market_img/alienware1.png')}}" alt="">
                                            </a>
                                            <div class="overlay">
                                               <h2 style="font-size: 14px;">[8th Gen][LATEST GTX 10 Series GPUs]</h2>
                                               <a class="info" onclick="onOverlay()">Add to cart</a>
                                            </div>
                                            <div style="background: #2d2d2d; padding: 9px;">
                                                <span class="credits_top">EZ Credits: <span data-number="">₱ 99,000.00</span></span>
                                                <p>NEW ALIENWARE 17 R5 17.3 Gaming Laptop</p> 
                                            </div> 
                                        </div>                                                                  
                                    </div>

                                    <div class="col-lg-2 col-md-3 col-sm-6 col-xs-6" style="margin: 5px 0px;">
                                        <div class="hovereffect">
                                            <a href="#" class="d-block mb-4 h-100">
                                            <img class="img-responsive" src="{{ asset('market_img/razer-mouse-pad.png')}}" alt="">
                                            </a>
                                            <div class="overlay">
                                               <h2 style="font-size: 14px;">[Discover a whole new level of personalization with 16.8 million colors and a myriad of customizable lighting effects.]</h2>
                                               <a class="info" onclick="onOverlay()">Add to cart</a>
                                            </div>
                                            <div style="background: #2d2d2d; padding: 9px;">
                                                <span class="credits_top">EZ Credits: <span data-number="">₱ 5,100.00</span></span>
                                                <p>Razer Firefly Chroma Lighting Gaming Mouse Pad</p> 
                                            </div> 
                                        </div>                                                                  
                                    </div>

                                    <div class="col-lg-2 col-md-3 col-sm-6 col-xs-6" style="margin: 5px 0px;">
                                        <div class="hovereffect">
                                            <a href="#" class="d-block mb-4 h-100">
                                            <img class="img-responsive" src="{{ asset('market_img/razer-mouse.png')}}" alt="">
                                            </a>
                                            <div class="overlay">
                                               <h2 style="font-size: 14px;">[Each and every button on the Razer Naga to your personal skillset]</h2>
                                               <a class="info" onclick="onOverlay()">Add to cart</a>
                                            </div>
                                            <div style="background: #2d2d2d; padding: 9px;">
                                                <span class="credits_top">EZ Credits: <span data-number="">₱ 3,700.00</span></span>
                                                <p>Razer Naga 2014 MMO Gaming Mouse</p> 
                                            </div> 
                                        </div>                                                                  
                                    </div>

                                    <div class="col-lg-2 col-md-3 col-sm-6 col-xs-6" style="margin: 5px 0px;">
                                        <div class="hovereffect">
                                            <a href="#" class="d-block mb-4 h-100">
                                            <img class="img-responsive" src="{{ asset('market_img/razer-mouse-1.png')}}" alt="">
                                            </a>
                                            <div class="overlay">
                                               <h2 style="font-size: 14px;">[Each and every button on the Razer Naga to your personal skillset]</h2>
                                               <a class="info" onclick="onOverlay()">Add to cart</a>
                                            </div>
                                            <div style="background: #2d2d2d; padding: 9px;">
                                                <span class="credits_top">EZ Credits: <span data-number="">₱ 3,700.00</span></span>
                                                <p>Razer DeathAdder Chroma Gaming Mouse</p> 
                                            </div> 
                                        </div>                                                                  
                                    </div>

                                    <div class="col-lg-2 col-md-3 col-sm-6 col-xs-6" style="margin: 5px 0px;">
                                        <div class="hovereffect">
                                            <a href="#" class="d-block mb-4 h-100">
                                            <img class="img-responsive" src="{{ asset('market_img/vr1.png')}}" alt="">
                                            </a>
                                            <div class="overlay">
                                               <h2 style="font-size: 14px;">[ENHANCED GRAPHICS - Super-rich colors and sharper than ever details: 2880 x 1660 and 615 PPI]</h2>
                                               <a class="info" onclick="onOverlay()">Add to cart</a>
                                            </div>
                                            <div style="background: #2d2d2d; padding: 9px;">
                                                <span class="credits_top">EZ Credits: <span data-number="">₱ 49,900.00</span></span>
                                                <p>HTC VIVE Pro Virtual Reality Headset</p> 
                                            </div> 
                                        </div>                                                                  
                                    </div>

                                    <div class="col-lg-2 col-md-3 col-sm-6 col-xs-6" style="margin: 5px 0px;">
                                        <div class="hovereffect">
                                            <a href="#" class="d-block mb-4 h-100">
                                            <img class="img-responsive" src="{{ asset('market_img/cool.png')}}" alt="">
                                            </a>
                                            <div class="overlay">
                                               <h2 style="font-size: 14px;">[USB HUB function with 1 USB input port and 3 output ports for your additional USB device]</h2>
                                               <a class="info" onclick="onOverlay()">Add to cart</a>
                                            </div>
                                            <div style="background: #2d2d2d; padding: 9px;">
                                                <span class="credits_top">EZ Credits: <span data-number="">₱ 5,900.00</span></span>
                                                <p>Deepcool M6 FS 17" Gaming Laptop Cooler</p> 
                                            </div> 
                                        </div>                                                                  
                                    </div>

                                    <div class="col-lg-2 col-md-3 col-sm-6 col-xs-6" style="margin: 5px 0px;">
                                        <div class="hovereffect">
                                            <a href="#" class="d-block mb-4 h-100">
                                            <img class="img-responsive" src="{{ asset('market_img/cool1.png')}}" alt="">
                                            </a>
                                            <div class="overlay">
                                               <h2 style="font-size: 14px;">[The creative Multi-Core Control Technology enables users to apply 4 different fan-working]</h2>
                                               <a class="info" onclick="onOverlay()">Add to cart</a>
                                            </div>
                                            <div style="background: #2d2d2d; padding: 9px;">
                                                <span class="credits_top">EZ Credits: <span data-number="">₱ 3,300.00</span></span>
                                                <p>Deepcool Multi Core X6 15.6" Laptop Cooler</p> 
                                            </div> 
                                        </div>                                                                  
                                    </div>

                                </div>
                             </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script type="text/javascript" src="{{ asset('bower_components/bootstrap-sweetalert/dist/sweetalert.min.js')}}"></script>
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.0/jquery.validate.min.js"></script>
@foreach($upload as $file)
<script type="text/javascript">
$(document).ready(function(){
    $('.insufficient-credit').click(function(){
        swal("Insufficient Credits!", "", "warning");
    });

    $('.next').click(function(){        
      var nextId = $(this).parents('.tab-pane').next().attr("id");
      $('button[data-target='+nextId+']').tab('show');
      return false;          
    })
    
    $('button[data-toggle="tab"]').on('shown.bs.tab', function (e) {     
      var step = $(e.target).data('step');
      var percent = (parseInt(step) / 2) * 100;          
      $('.progress-bar').css({width: percent + '%'});
      $('.progress-bar').text("Step " + step + " of 2");   
    })        

    $('.first').click(function(){        
      $('#market-wizard button:first').tab('show');        
    });        

    $('#inputQuantity{{$file->id}}').keyup( function(){
        var a = $('#price{{$file->id}}').val();
        var b = $('#inputQuantity{{$file->id}}').val();
        var total = a * b;
        $('#total{{$file->id}}').html(total);
    });

    function onOverlay() {
        document.getElementById("overlay").style.display = "block";
    }
    function offOverlay() {
        document.getElementById("overlay").style.display = "none";
    }

    $('#market-form{{$file->id}} input').on('keyup blur', function() {
       if ($("#market-form{{$file->id}}").valid()) {
           $('#nextBTN{{$file->id}}').prop('disabled', false);  
           $('#a-step2{{$file->id}}').prop('disabled', false); 

       } else {
           $('#nextBTN{{$file->id}}').prop('disabled', 'disabled');
           $('#a-step2{{$file->id}}').prop('disabled', 'disabled');
       }
    });

    $('.checkout-btn{{$file->id}}').prop('disabled',true);
    $('#inputQuantity{{$file->id}}').keyup(function(){
        $('.checkout-btn{{$file->id}}').prop('disabled', this.value == "" ? true : false);     
    });

    $('.checkout-btn{{$file->id}}').click( function(){
        // var checkout = new FormData($("#market-form{{$file->id}}")[0]);
        var buyer    = $('#buyer{{$file->id}}').val();
        var city     = $('#inputCity{{$file->id}}').val();
        var province = $('#inputProvince{{$file->id}}').val();
        var street   = $('#inputStreet{{$file->id}}').val();
        var contact  = $('#inputContactNumber{{$file->id}}').val();
        var prodName = $('#inputProduct{{$file->id}}').text();
        var i_price  = $('#price{{$file->id}}').val();
        var quantity = $('#inputQuantity').val();
        $.ajax({
            url: "{{ url('/buy-item') }}",
            type: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },            
            success:function(data) 
            {
                $("#market-form{{$file->id}}")[0].reset();
            },
            data: { i_buyer : buyer, i_province : province, i_city : city, 
                    i_street : street, i_itemname : prodName, i_price : i_price, i_quantity : quantity, i_contact : contact},
            cache:false,
            contentType: false,
            processData: false,
        });
    });

    $("#market-form{{$file->id}}").validate({
        rules: {
            inputCity{{$file->id}}: {
                required: true,
                minlength: 5
            },
            inputProvince{{$file->id}}: {
                required: true,
                minlength: 5
            },
            inputQuantity{{$file->id}}: {
                required: true,
                minlength: 1
            },
            inputStreet{{$file->id}}: {
                required: true,
                minlength: 5
            },
            inputContactNumber{{$file->id}}: {
                required: true,
                minlength: 11
            },
        }
    });                                  
});
</script>
@endforeach
@endsection
