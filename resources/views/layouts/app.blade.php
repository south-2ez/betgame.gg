<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('images/favicon.ico') }}" rel="shortcut icon" type="image/x-icon">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/materialize.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/bright.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" type="text/css">
    <script src="https://code.jquery.com/jquery-3.1.0.min.js"></script>
    @if( !empty(Route::current()) && Route::current()->getName() != 'admin' && Route::current()->getName() != 'agent')
        @if(!empty($hasMatchManagementAccess))
            <script src="/js/2ez-202010-secured.f1214495fb1fb6e883a5.js"></script>
        @else
            <script src="{{ mix('/js/app.js') }}"></script>
        @endif
        
    @endif
    <style>
    
    .fb_dialog_mobile{
        bottom: 45pt !important;
    }
    </style>
</head>
<body>

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

    <header>
        <a href="{{ url('/') }}/"><img id="logo" src="{{ asset('images/logo.png') }}" alt="Dota 2 Marketplace"></a>
        <nav id="menu">
            <a href="{{ url('/') }}/myprofile"><img src="{{ asset('images/profile.png') }}" alt="Profile">my profile</a>
            <a href="{{ url('/') }}/matches"><img src="{{ asset('images/search.png') }}" alt="Search">matches</a>
            <a href="{{ url('/') }}/mybets"><img src="{{ asset('images/my_bets.png') }}" alt="My Bet History">my bet history</a>
            <a onclick="ThrowModalError(&#39;error=csa&#39;)"><img src="{{ asset('images/add_trade.png') }}" alt="Deposit">deposit</a>
        </nav>
        <div id="status" style="float:right">
            <a onclick="LogInSignUpModal('signUp');" class="waves-effect btn grey darken-3" style="padding: 0 16px;margin: 0.75em 0.2em;letter-spacing: 0;"><span>Sign</span><b>Up</b></a>
            <a onclick="LogInSignUpModal('logIn');" class="waves-effect btn grey darken-3" style="padding: 0 16px;margin: 0.75em 0.2em;letter-spacing: 0;"><span>Log</span><b>In</b></a>
        </div>
        <li><a href="#" onclick="toggleChatBubble()"><i class="fa fa-commenting-o" aria-hidden="true"></i> <span>Chat with Us</span></a></li>
    </header>
    
    @yield('content')
    
    <aside id="submenu"><ul id="dropdown-notifications" class="dropdown-content-notifications z-depth-2" style="display: none"></ul>
        <nav>
            <a href="http://steamcommunity.com/groups/discussions">Forum</a>
            <a href="http://steamcommunity.com/groups/">Steam group</a>
            <a href="{{ url('/') }}/contact">Contact</a>
            <a href="{{ url('/') }}/rules">Rules</a>
            <a href="{{ url('/') }}/tradesguide">Trades Guide</a>
        </nav>
        
        <div id="follow">
            <a href="https://twitter.com/" id="ftw"></a>
            <a href="http://www.facebook.com/" id="ffb"></a>
            <a href="http://vk.com/" id="fvk"></a>
        </div>
        <footer>
            <br><br>
            <span>© 2017 by <a href="http://steamcommunity.com/">lempax</a><br>
                <a href="{{ url('/') }}/tos" style="color:#888">Terms of Service</a> | <a href="{{ url('/') }}/legal" style="color:#888">Privacy</a><br>
                <a href="{{ url('/') }}/rg" style="color:#888">Responsible Betting</a>
            </span>

            <a class="dropdown-button" data-activates="dropdown-language">Language: English</a>
            <ul id="dropdown-language" class="dropdown-content">
                <li><a href="{{ url('/') }}/ajax/setLanguage?pjs=fc2807dcea603059105d7a0e9f5a163a&amp;lang=0">English</a></li>
                <li><a href="{{ url('/') }}/ajax/setLanguage?pjs=fc2807dcea603059105d7a0e9f5a163a&amp;lang=1">Russian</a></li>
                <li><a href="{{ url('/') }}/ajax/setLanguage?pjs=fc2807dcea603059105d7a0e9f5a163a&amp;lang=2">Chinese</a></li>
                <li><a href="{{ url('/') }}/ajax/setLanguage?pjs=fc2807dcea603059105d7a0e9f5a163a&amp;lang=3">Portuguese</a></li>
                <li><a href="{{ url('/') }}/ajax/setLanguage?pjs=fc2807dcea603059105d7a0e9f5a163a&amp;lang=4">Spanish</a></li>
            </ul>&nbsp;
        </footer>
    </aside>
</body>
</html>