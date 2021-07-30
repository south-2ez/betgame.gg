<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    @if(App::environment('prod'))
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-156919416-1"></script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'UA-156919416-1');
    </script>
    @endif
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('images/favicon.ico') }}" rel="shortcut icon" type="image/x-icon">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}" type="text/css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" type="text/css">
    <link rel="stylesheet" href="{{ mix('css/all-styles.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}?id=0a4cecac713e0ecec97d" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/buttons.css') }}?id=0a4cecac713e0ecec97d" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/nprogress.css') }}?id=0a4cecac713e0ecec97d" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}" type="text/css">
    <style>
        /*!
 * Waves v0.6.0
 * http://fian.my.id/Waves
 *
 * Copyright 2014 Alfiana E. Sibuea and other contributors
 * Released under the MIT license
 * https://github.com/fians/Waves/blob/master/LICENSE
 */
    .waves-effect {
        position: relative;
        cursor: pointer;
        display: inline-block;
        overflow: hidden;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
        -webkit-tap-highlight-color: transparent;
        vertical-align: middle;
        z-index: 1;
        will-change: opacity, transform;
        transition: all .3s ease-out; }
    .waves-effect .waves-ripple {
        position: absolute;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        margin-top: -10px;
        margin-left: -10px;
        opacity: 0;
        background: rgba(0, 0, 0, 0.2);
        transition: all 0.7s ease-out;
        transition-property: transform, opacity;
        transform: scale(0);
        pointer-events: none; }
    .waves-effect.waves-light .waves-ripple {
        background-color: rgba(255, 255, 255, 0.45); }
    .waves-effect.waves-red .waves-ripple {
        background-color: rgba(244, 67, 54, 0.7); }
    .waves-effect.waves-yellow .waves-ripple {
        background-color: rgba(255, 235, 59, 0.7); }
    .waves-effect.waves-orange .waves-ripple {
        background-color: rgba(255, 152, 0, 0.7); }
    .waves-effect.waves-purple .waves-ripple {
        background-color: rgba(156, 39, 176, 0.7); }
    .waves-effect.waves-green .waves-ripple {
        background-color: rgba(76, 175, 80, 0.7); }
    .waves-effect.waves-teal .waves-ripple {
        background-color: rgba(0, 150, 136, 0.7); }
    .waves-effect input[type="button"], .waves-effect input[type="reset"], .waves-effect input[type="submit"] {
        border: 0;
        font-style: normal;
        font-size: inherit;
        text-transform: inherit;
        background: none; }

    .waves-notransition {
        transition: none !important; }

    .waves-circle {
        transform: translateZ(0);
        -webkit-mask-image: -webkit-radial-gradient(circle, white 100%, black 100%); }

    .waves-input-wrapper {
        border-radius: 0.2em;
        vertical-align: bottom; }
    .waves-input-wrapper .waves-button-input {
        position: relative;
        top: 0;
        left: 0;
        z-index: 1; }

    .waves-circle {
        text-align: center;
        width: 2.5em;
        height: 2.5em;
        line-height: 2.5em;
        border-radius: 50%;
        -webkit-mask-image: none; }

    .waves-block {
        display: block; }

    /* Firefox Bug: link not triggered */
    a.waves-effect .waves-ripple {
        z-index: -1; }
    .grey.darken-3 {
  background-color: #424242 !important; }
    
    .login .btn {
        text-decoration: none;
        color: #fff;
        text-align: center;
        border: none;
        border-radius: 2px;
        height: 36px;
        line-height: 36px;
        outline: 0;
        text-transform: uppercase;
    }
    
    .credits_top{
        zoom: 1;
        padding: 1px 4px 2px 4px;
        font-size: 13px;
        color: greenyellow;
        background: #717171;
        margin-top: 5px;
    }
    .carousel {
        margin-bottom: 0;
        padding: 0;
    }
    /* Reposition the controls slightly */
/*    .carousel-control {
        left: -12px;
    }
    .carousel-control.right {
        right: -12px;
    }*/
    /* Changes the position of the indicators */
    .carousel-indicators {
        right: 50%;
        top: auto;
        bottom: 0px;
        margin-right: -19px;
    }
    /* Changes the colour of the indicators */
    .carousel-indicators li {
        background: #c0c0c0;
    }
    .carousel-indicators .active {
        background: #333333;
    }
    .thumbnail {
        background-color: inherit;
        border: none;
        margin-bottom: 0;
        padding: 0;
    }
    .sweet-overlay {
        z-index: 2000!important;
    }
    .button-recent{
        border: none;
        opacity: 0.5;
    }
    .button-recent-hover:hover {
      background-color: #2D2D2D;
      opacity: 1.0;
    }
    .focus-recent-category.selected{
        color: #F39C12 !important;
        border-bottom: 2px solid #F39C12;
        opacity: 1.0;
    }
    @media only screen and (max-width: 1540px){
        .recent-text{
            white-space: nowrap; 
            width: 100%; 
            overflow: hidden;
            text-overflow: ellipsis;
            padding-left: 8px;
        }
    }

    .faq-text{
            white-space: nowrap; 
            width: 100%; 
            overflow: hidden;
            text-overflow: ellipsis;
        }

    .fb_dialog_mobile{
        bottom: 45pt !important;
    }
    
    </style>
    @yield('styles')
</head>
<body>
 

@if(!Auth()->check() || !hasMatchManagementAccess(Auth()->user()))
        <!--fb messenger -->
<!-- Load Facebook SDK for JavaScript -->
      <div id="fb-root"></div>
      <script>
        var showHideChatBubble = false;
        var fbChatLoaded = false;
        

        window.fbAsyncInit = function() {
            if(screen.width > 760){
                fbChatLoaded = true;
                FB.init({
                    xfbml            : true,
                    version          : 'v6.0'
                });          
            }else{
                FB.Event.subscribe('customerchat.dialogHide', function(eventResponse){
                    console.log('testing plugin fb dialogHide');
                    FB.CustomerChat.hide();
                });   
            }
            // FB.Event.subscribe('customerchat.load', function(eventResponse){
            //     console.log('testing plugin fb loaded');
            // });

            // FB.Event.subscribe('customerchat.show', function(eventResponse){
            //     console.log('testing plugin fb show')
            // });

            // FB.Event.subscribe('customerchat.hide', function(eventResponse){
            //     console.log('testing plugin fb hide')
            // });

            // FB.Event.subscribe('customerchat.dialogShow', function(eventResponse){
            //     console.log('testing plugin fb dialogShow')
            // });

            // FB.Event.subscribe('customerchat.dialogHide', function(eventResponse){
            //     console.log('testing plugin fb dialogHide');
            //     FB.CustomerChat.hide();
            // });
        



        };


            function toggleChatBubble(){

                if(!fbChatLoaded){
                    fbChatLoaded = true;
                    FB.init({
                        xfbml            : true,
                        version          : 'v6.0'
                    });
                }
        
                if(!showHideChatBubble){
                    console.log('toggleChatBubble: show');
                    FB.CustomerChat.showDialog();
                    showHideChatBubble = true;
                }else{
                    console.log('toggleChatBubble: hide');
                    FB.CustomerChat.hideDialog();
                    showHideChatBubble = false;
                }

                return false;
                
                
            }


        (function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = 'https://connect.facebook.net/en_US/sdk/xfbml.customerchat.js';
        fjs.parentNode.insertBefore(js, fjs);
      }(document, 'script', 'facebook-jssdk'));</script>

      <!-- Your customer chat code -->
      <div class="fb-customerchat" size="standard" page_id="1885297161723846"></div>
    <!-- /fb messenger -->
@endif


    <div id="app">
    <div class="header" id="webtop">
        <div id="mt"><div onclick="goTo(0)">up <i class="fa fa-arrow-up"></i></div><div onclick="goTo(1)">matches</div></div>
        

        <div class="container home-container">
            <div class="row">

                <div class="col-md-3">
                    <div class='logo'>
                        <a href="/" class="main-logo">
                            <img src="{{ asset('images/betgame-icon.png') }}"/>
                            <img src="{{ asset('images/betgame-word.png') }}"/>
                        </a>
                        <!--
                        <a href="/" class="main-logo">
                            <img src="{{ asset('images/2ez_logo.png') }}"/>
                        </a>
                        {{-- <a href="#"  data-pjax data-toggle="modal" data-target="#trick-or-treat-event-modal" data-pjax  class="main-logo">
                            <img src="{{ asset('images/LogoCNY.png') }}" width="295px;" style="margin-top:10px; margin-left:10px;"/>
                        </a> --}}
                        {{-- <a href="#" data-pjax data-toggle="modal" data-target="#trick-or-treat-event-modal"><img class="halloween-pumpkin-logo" src="{{ asset('images/halloween/pumpkin-logo_100x100.png') }}" /></a> --}}
                        -->
                    </div>
                </div>
                <div class="col-md-9">

                    <!--
                    <div class="menu pull-left">
                        <ul style="list-style: none">
                            <li><a href="{{ url('home') }}" data-pjax><i class="fa fa-trophy" aria-hidden="true"></i> <span>Matches</span></a></li>
                            <li><a href="{{ url('/profile') }}"><i class="fa fa-user" aria-hidden="true"></i><span> My Profile</span></a></li>
                            <li><a href="{{ url('/market') }}"><i class="fa fa-tag" aria-hidden="true"></i><span> Market</span></a></li>
                            {{-- <li><a href="{{ url('careers') }}" data-pjax><i class="fa fa-at" aria-hidden="true"></i> <span>Careers</span></a></li> --}}
                            @if (!Auth::guest() && Auth::user()->isAdmin())
                            <li><a href="{{ url('/matchmanager') }}"><i class="fa fa-bolt" aria-hidden="true"></i><span> Match Manager</span></a></li>
                            <li><a href="{{ url('/admin') }}"><i class="fa fa-cogs" aria-hidden="true"></i><span> Admin</span></a></li>
                            @else
                                @if (!Auth::guest() && Auth::user()->isMatchManager())
                                <li><a href="{{ url('/matchmanager') }}"><i class="fa fa-bolt" aria-hidden="true"></i><span> Match Manager</span></a></li>
                                @endif
                            @endif
                            @if (!Auth::guest() && hasPartnerDashboardAccess( Auth::user()) )
                                <li><a href="{{ url('/agent') }}"><i class="fa fa-tachometer" aria-hidden="true"></i><span> Partner Dashboard</span></a></li>
                            @endif
                            <li><a href="#" onclick="toggleChatBubble()"><i class="fa fa-commenting-o" aria-hidden="true"></i> <span>Chat with Us</span></a></li>
                        </ul>
                    </div>
                        -->
                    
                    <div class="menu pull-right">
                        <button type="button" class="btn btn-link">Shop</button>
                        <button type="button" class="btn btn-link">Support</button>
                        <div class="btn-group">
                            
                            @if (Auth::guest())
                            <button type="button" class="btn btn-info"  data-toggle="modal" data-target="#signupm"><span><b>SignIn / SignUp</b></span></button>
                            @else
                            <span class="user_details">
                                <span class="user_name">{{ Auth::user()->name }}</span><br/>
                                <span class="credits_top">EZ Credits: <span data-number>&#8369; {{ number_format(Auth::user()->credits, 2, '.', ',') }}</span></span>
                            </span>
                            <image src="{{ Auth::user()->provider == 'local' ? url('/') . '/' . Auth::user()->avatar : Auth::user()->avatar }}" />
                            <a href="{{ url('/logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="waves-effect btn grey darken-3" style="padding: 0 16px;margin: 0.75em 0.2em;letter-spacing: 0;"><span>Log</span><b>Out</b></a>
                            <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                            @endif
               


                        </div>


                    </div>





                
                </div>


            </div>
        </div>



    </div>
    
    <div id="load">
        @yield('content')

        @if( !empty(Route::current()) && Route::current()->getName() != 'admin' && Route::current()->getName() != 'agent' && Route::current()->getName() != 'bnd.matches.active' && Route::current()->getName() != 'bnd.match.view' && Route::current()->getName() != 'matchmaker' )
        
            @if( Route::current()->getName() == 'match.view')
                @if(Auth::guest() || (Auth::guest() == false && Auth::user()->type == 'user'))
                    <recent-matches></recent-matches>
                @endif
            @else 
                <recent-matches></recent-matches>
            @endif
            
            <user-messages logged-in="{{ Auth::guest() ? 0 : 1 }}"></user-messages>
            {{-- <chinese-new-year-flip-cards logged-in="{{ Auth::guest() ? 0 : 1 }}"></chinese-new-year-flip-cards> --}}
            @if(!Auth::guest() && Auth::user()->type == 'user' &&  Auth::user()->credits >= 100)
                <easter-egg-event logged-in="{{ Auth::guest() ? 0 : 1 }}" user-id="{{ Auth::id() }}"></easter-egg-event>
            @endif
           
        @endif
    </div>

    
    <div id="signupm" class="modal fade" role="dialog">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <ul class="nav nav-tabs">
                        <li><a href="#signup" data-toggle="tab">Sign Up</a></li>
                        <li class="active"><a href="#login" data-toggle="tab">Log In</a></li>
                    </ul>
                </div>
                <div class="modal-body">
                    <div class="tab-content">
                        <div class="tab-pane fade" id="signup">
                            <form id="registerForm" class="form-horizontal">

                                <div class="form-group">
                                    <div class="col-md-12">
                                        <label for="name" class="control-label">Name</label>

                                        <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>

                                        <span class="help-block" style="display: none"></span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-md-12">
                                        <label for="email1" class="control-label">E-Mail Address</label>
                                        <input id="email1" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

                                        <span class="help-block" style="display: none"></span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-md-12">
                                        <label for="password1" class="control-label">Password</label>
                                        <input id="password1" type="password" class="form-control" name="password" required>

                                        <span class="help-block" style="display: none"></span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-md-12">
                                        <label for="password-confirm" class="control-label">Confirm Password</label>
                                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-md-12">
                                        <label for="password-confirm" class="control-label">Voucher Code</label>
                                        <input id="redeem-voucher-code" type="text" class="form-control" name="redeem_voucher_code">

                                        <span class="help-block" style="display: none"></span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary" style="width: 100%">
                                            Sign Up
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane fade in active" id="login">
                            <form id="loginForm" class="form-horizontal">

                                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                    <div class="col-md-12">
                                        <label for="email2" class="control-label">E-Mail Address</label>

                                        <input id="email2" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>

                                        <span class="help-block" style="display: none">
                                            
                                        </span>
                                    </div>
                                </div>

                                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                    <div class="col-md-12">
                                        <label for="password2" class="control-label">Password</label>

                                        <input id="password2" type="password" class="form-control" name="password" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-md-12">
                                        <a class="btn btn-link" href="{{ route('password.request') }}" style="padding-left: 0">
                                            Forgot Your Password?
                                        </a>
                                        <button type="submit" class="btn btn-success" style="width: 100%">
                                            Login
                                        </button>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="col-md-5"><hr style="border-top-width: 3px"/></div>
                                        <div class="col-md-2"><p style="margin-top: 10px; font-size: 15px">OR</p></div>
                                        <div class="col-md-5"><hr style="border-top-width: 3px"/></div>
                                    </div>                                            
                                    <div class="col-md-12">
                                        <a href="{{ url('/auth/facebook') }}" class="btn btn-primary" style="width: 100%; margin-bottom: 7px">
                                            Facebook
                                        </a>
<!--                                        <a href="{{ url('/auth/steam') }}" class="btn btn-default" style="width: 100%; color: #ffffff; background-color: #424242; border-color: #434242">
                                            Steam
                                        </a>-->
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="2ez_tosModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">2ez.bet Terms and Conditions</h4>
                </div>
                <div class="modal-body" style="max-height: 600px; overflow-y: auto">

                </div>
                <div class="modal-footer">
                    <span class="pull-left">By logging in to 2ez.bet, you agree to our Terms and Conditions. Click AGREE to proceed</span>
                    <div class="buttons">
                        <a class="btn btn-success" id="agree_tos_btn">
                            Agree
                        </a>
                        <a class="btn btn-danger" id="disagree_tos_btn">
                            Disagree
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>


    </div>
        
    @if( !empty(Route::current()) && Route::current()->getName() != 'admin' && Route::current()->getName() != 'agent')
    
        @if(Auth::guest() )
            <script src="{{ mix('/js/app.js') }}"></script>
        @elseif(hasMatchManagementAccess(Auth()->user()))
            <script src="/js/2ez-202010-secured.js?id=09b0972aeb075c2e3dda"></script>
        @else 
            <script src="{{ mix('/js/app.js') }}"></script>
        @endif

        
    @endif
    
    <script type="text/javascript" src="{{ asset('js/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/buttons.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/jquery-ui.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/nprogress.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/bootstrap.min.js') }}"></script>
    
    <script type="text/javascript">
        $(function(){
            $('preload').fadeOut(800, function(){
                $(".recent-box").fadeIn(700);
            });
        });
    </script>


    <script type="text/javascript">
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

        function numberWithCommas(x) {
            return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }
        
        $('[data-number]').each(function(index, el) {
            $(el).text(numberWithCommas($(el).text()));
        });

        $(document).ready(function(){
            @if(Auth::check() && !Auth::user()->accept_tos)
            var ackTOS = {{Auth::user()->accept_tos}};
            if(!ackTOS) {
                setTimeout(function() {
                    $('#2ez_tosModal').modal({backdrop: 'static', keyboard: false});
                }, 5000);
                $.get("{{ url('/2eztos') }}", function(data) {
                    $('#2ez_tosModal').find('.modal-body').html(data);
                });
            }
            
            $('#2ez_tosModal').on('hidden.bs.modal', function() {
                if(!ackTOS)
                    window.location.href = "{{url('/logout')}}";
            });

            $('#agree_tos_btn').click(function() {
                setTOSModal(1);
            });

            $('#disagree_tos_btn').click(function() {
                setTOSModal(0);
            });

            function setTOSModal(ack) {
                if(ack) {
                    $.ajax({
                        url: "{{url('/2eztos')}}",
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        type:'POST',
                        data: { ack: 1 },
                        success:function(data){
                            if(data.success) {
                                ackTOS = 1;
                                $('#2ez_tosModal').modal('hide');
                                swal("Success!", "Thank you for accepting our TOS.", "success");
                                window.setTimeout(function(){
                                    location.reload();
                                }, 2000);
                            }
                        },
                        error:function(){}
                    });
                } else {
                    swal({
                        title: "Are you sure?",
                        text: "You will be logged out from 2ez.bet!",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonClass: "btn-danger",
                        confirmButtonText: "Yes, I disagree!",
                        cancelButtonText: "Cancel",
                        showLoaderOnConfirm: true,
                        closeOnConfirm: false,
                        html: true
                    }, function(isConfirm) {
                        if(isConfirm) {
                            window.location.href = "{{url('/logout')}}";
                        }
                    });
                }
            }
            @endif
            
            $('#myCarousel').carousel();
            $("#loginForm").submit(function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                var formData = new FormData($("#loginForm")[0]);
                $.ajax({
                    url:"{{ route('login') }}",
                    type:'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    data: formData,
                    success:function(data){
                        if(data.warning) {
                            swal("Warning!", data.warning, "warning");
                        } else {
                            $('#loginModal').modal('hide');
                            location.reload(true);
                        }
                    },
                    error:function(data){
                        $.each(data.responseJSON, function(key, value) {
                            $("#loginForm").find(':input[name='+key+']').closest('.form-group').addClass('has-error');
                            $('#loginForm').find(':input[name='+key+']').parent().find('.help-block').html('<strong>'+value+'</strong>').show();
                        });
                    },
                    cache:false,
                    contentType: false,
                    processData: false,
                });
            });
            
            $("#registerForm").submit(function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                var formData = new FormData($("#registerForm")[0]);
                $.ajax({
                    url:"{{ route('register') }}",
                    type:'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    data: formData,
                    success:function(data){
                        if(data.warning) {
                            swal("Notice!", data.warning, "info");
                            $('.sweet-alert').on('click', '.confirm', function() {
                                window.location.reload();
                            });
                            window.setTimeout(function(){
                                location.reload();
                            }, 10000);
                        } else
                            location.reload(true);
                    },
                    error: function(data){
                        $.each(data.responseJSON, function(key, value) {
                            $("#registerForm").find(':input[name='+key+']').closest('.form-group').addClass('has-error');
                            $('#registerForm').find(':input[name='+key+']').parent().find('.help-block').html('<strong>'+value+'</strong>').show();
                        });
                    },
                    cache:false,
                    contentType: false,
                    processData: false,
                });
            });
            
            $(':input').on('change', function() {
                $(this).closest('.form-group').removeClass('has-error');
                $(this).parent().find('.help-block').hide();
                $(this).parent().find('.error-label').text('');
            });

            $(document).on('click', '.reactivate-account', function() {
                let user_id = $(this).data("uid");

                $.ajax({
                    url:"{{ route('user.reactivate-account') }}",
                    type:'PUT',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    data: {
                        'user_id' : user_id
                    },
                    success:function(data){
                        if(typeof(data.success) != 'undefined' && data.success == true) {
                            location.reload(true);
                        }
                    },
                    error: function(data){
                        alert('error on deactivating account.');
                    }
                });
            })
        });
        $(document).ajaxError(function( event, jqxhr, settings, exception ) {
            if ( jqxhr.status== 401 ) {
                swal("Warning!", "Your session has timed out! Please login again!", "warning");
                $('.sweet-alert').on('click', '.confirm', function() {
                    window.location.reload();
                });
                window.setTimeout(function(){
                    location.reload(true);
                }, 10000);
            }
        });
        function goTo(pageArea) {
            switch(pageArea) {
                case 0:
                    $('html, body').animate({
                        scrollTop: 0
                    }, 'slow');
                    break;
                case 1:
                    if($("#right").length) {
                        $('html, body').animate({
                            scrollTop: $("#right").offset().top
                        }, 'slow');
                    } else {
                        window.location.href = "{{url('/home')}}#right";
                    }
                    break;
                case 2:
                    $('html, body').animate({
                        scrollTop: $("#topbets").offset().top
                    }, 'slow');
                    break;
            }
        }
    </script>


    @yield('script')

    @auth
        @if( Auth::user()->isAdmin())
            <script src="{{ asset('js/enable-push.js') }}" defer></script>
        @endif
    @endauth

</body>
</html>
