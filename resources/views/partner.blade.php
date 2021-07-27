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
</style>
<link rel="stylesheet" href="{{ asset('bower_components/bootstrap-sweetalert/dist/sweetalert.css') }}">
@endsection

@section('content')
<div class="main-container dark-grey">
    <div class="m-container1" style="width: 98% !important;">
        <div class="main-ct">
            <div class="title2">Become a Partner</div>
            <div class="clearfix"></div>

            <div class="blk-1">
                <div class="col-md-8">
                    <h3 style="margin-top: 0">
                        <p>We are looking for partners.</p>

                        <p>Internet cafe owners, agents, streamers, content creators, skins merchant and others.</p>

                        <p>Partners can:</p>
                        <ul>
                            <li>Get 50% of earnings in all transactions (deposits and cashouts by users)</li>
                            <li>Get free promotional items for give aways.</li>
                            <li>Get free access to website tools and reporting.</li>
                            <li>24/7 direct technical support</li>
                        </ul>
                    </h3>
                </div>
                <div class="col-md-4">
                    <span class="pull-right">
                        <button class="btn btn-lg btn-warning partnerBtn" style="font-size: 200%; color: grey" data-toggle="modal" data-target="#partnerModal"><i class="fa fa-handshake-o" aria-hidden="true"></i> BECOME A PARTNER</button>
                    </span>
                </div>
                <br/>
                <div class="col-md-12">
                    <div class="row" style="padding-top: 50px">
                        <div class="col-xs-6 col-md-4">
                            <a href="https://www.facebook.com/Jaredkeith123123123/" target="_blank" class="thumbnail">
                                <img src="{{ asset('/images/kuya_jops.png') }}" alt="Kuya-Jop">
                            </a>
                        </div>
                        <div class="col-xs-6 col-md-4">
                            <a href="#" class="thumbnail">
                                <img src="{{ asset('/images/partner_image.png') }}" alt="Partner 2">
                            </a>
                        </div>
                        <div class="col-xs-6 col-md-4">
                            <a href="#" class="thumbnail">
                                <img src="{{ asset('/images/partner_image.png') }}" alt="Partner 3">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="partnerModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Partner Information</h4>
            </div>
            <div class="modal-body">
                <form id="partnerForm">
                    <div class="row">
                        <div class="form-group col-xs-6">
                            <label>First Name</label>
                            <input type="text" name="fname" class="form-control" placeholder="First Name">
                            <span class="error-label"></span>
                        </div>
                        <div class="form-group col-xs-6">
                            <label>Last Name</label>
                            <input type="text" name="lname" class="form-control" placeholder="Last Name">
                            <span class="error-label"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-xs-6">
                            <label>Email Address</label>
                            <input type="text" name="email" class="form-control" placeholder="Email Address">
                            <span class="error-label"></span>
                        </div>
                        <div class="form-group col-xs-6">
                            <label>Mobile Number</label>
                            <input type="text" name="mobile_number" class="form-control" placeholder="Mobile Number">
                            <span class="error-label"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Address / Location</label>
                        <textarea name="address" class="form-control"></textarea>
                        <span class="error-label"></span>
                    </div>
                    <div class="form-group">
                        <label>I am a:</label>
                        <select name="type-iam" class="form-control" id="partner-type-iam">
                            <option value="Internet Cafe Owner">Internet Cafe Owner</option>
                            <option value="Streamer">Streamer</option>
                            <option value="Group Moderator">Group Moderator</option>
                            <option value="Content Creator">Content Creator</option>
                            <option value="Virtual Item's Merchant">Virtual Item's Merchant</option>
                            <option value="eSports Personality">eSports Personality</option>
                            <option value="eSports Advocate">eSports Advocate</option>
                            <option value="eSports Event Organizer">eSports Event Organizer</option>
                            <option value="Betting Agents">Betting Agents</option>
                            <option value="Media Personality / Company">Media Personality / Company</option>
                            <option value="Reseller">Reseller</option>
                            <option value="Just a Fan">Just a Fan</option>
                            <option value="Other">Other</option>
                        </select>
                        <span class="error-label"></span>
                    </div>


                    <div class="form-group">
                        <label>I want to be:</label>
                        <select name="type" class="form-control" id="partner-type">
                            {{-- <option value="Internet Cafe Owner">Internet Cafe Owner</option> --}}
                            <option value="Streamer">Streamer</option>
                            <option value="Group Moderator">Group Moderator</option>
                            <option value="Content Creator">Content Creator</option>
                            <option value="Virtual Item's Merchant">Virtual Item's Merchant</option>
                            <option value="eSports Personality">eSports Personality</option>
                            <option value="eSports Advocate">eSports Advocate</option>
                            <option value="eSports Event Organizer">eSports Event Organizer</option>
                            <option value="Betting Agents">Betting Agents</option>
                            <option value="Media Personality / Company">Media Personality / Company</option>
                            <option value="Reseller">Reseller</option>
                            {{-- <option value="Just a Fan">Just a Fan</option>
                            <option value="Other">Other</option> --}}
                        </select>
                        <span class="error-label"></span>
                    </div>                    

                    <div id="partnerFormFollowUpBettingAgent" class="follow-up-qestions" style="display:none;">
                        <div class="row">
                            <div class="form-group col-xs-12">
                                <label>Do you manage a community?</label>
                                <select name="has-community" id="has-community" class="form-control" value=''>
                                    <option value="" disabled selected></option>
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                </select>
                                <span class="error-label"></span>
                            </div>
                            <div class="form-group col-xs-12">
                                <label>What region/country do you think most of your members are from?</label>
                                <input type="text" name="region" class="form-control" placeholder="e.g. Cebu / Rizal ">
                                <span class="error-label"></span>
                            </div>
                        </div>
    
                        <div class="row">
                            <div class="form-group col-xs-12">
                                <label>
                                    Is the community composed of 18+ members that can be potential bettors?
                                </label>
                                <select name="legal-members"  class="form-control" value="">
                                    <option value="" disabled selected></option>
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                </select>
                                <span class="error-label"></span>
                            </div>
                            <div class="form-group col-xs-12">
                                <label>
                                    How many members do you have?
                                </label>
                                <input type="text" name="total-members" class="form-control" placeholder="Number of members">
                                <span class="error-label"></span>
                            </div>
                        </div>
    
                        <div class="row">
                            <div class="form-group col-xs-12">
                                <label>
                                    How many active bettors do you think you can manage to use 2ez.bet?
                                </label>
                                <input type="text" name="total-bettors" class="form-control" placeholder="Number of bettors">
                                <span class="error-label"></span>
                            </div>
                            <div class="form-group col-xs-12">
                                <label>
                                    Have you tried being an agent for a betting website?
                                </label>
                                <select name="agent-before" id="agent-before" class="form-control" value="">
                                    <option value="" disabled selected></option>
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                </select>
                                <span class="error-label"></span>
                            </div>                            
                        </div>
    
                        <div class="row">
                            <div class="form-group col-xs-12">
                                <label>
                                    How many years have you been an agent and for which websites?
                                </label>
                                <input type="text" name="total-years-website" class="form-control" placeholder="# of years & website">
                                <span class="error-label"></span>                              
                            </div>
                            <div class="form-group col-xs-12">
                                <label>
                                    What mode of payments do you currently offer? 
                                </label>
                                <input type="text" name="payment-methods" class="form-control" placeholder="Payment Methods">
                                <span class="error-label"></span>
                            </div>                              
                        </div>          
                        
                        <div class="row">
                            <div class="form-group col-xs-12">
                                <label>
                                    How much are you currently able to handle in daily transactions?
                                </label>
                                <input type="text" name="amount-transactions" class="form-control" placeholder="e.g (100k PHP/day)">
                                <span class="error-label"></span>
                            </div>
                            <div class="form-group col-xs-12">
                                <label>
                                    Can you ensure a 24/7 processing of transactions?
                                </label>
                                <select name="can-process-247" class="form-control" value="">
                                    <option value="" disabled selected></option>
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                </select>
                                <span class="error-label"></span>
                            </div>                            
                        </div>     
                        
                        <div class="row">

                            <div class="form-group col-xs-12">
                                <label>
                                    What other business do you have? 
                                </label>
                                <input type="text" name="other-businesses" class="form-control" placeholder="Ohter businesses">
                                <span class="error-label"></span>
                            </div>

                            <div class="form-group col-xs-12">
                                <label>
                                    What is your profession?
                                </label>
                                <input type="text" name="profession" class="form-control" placeholder="Ohter businesses">
                                <span class="error-label"></span>
                            </div>                            
                        </div>       
                                                   
                    </div>



                    <div id="partnerFormFollowUpReseller" class="follow-up-qestions" style="display:none;">
                        <div class="row">
                            <div class="form-group col-xs-12">
                                <label>Do you have a capital of at least 5k PHP?</label>
                                <select name="has-capital-5k" id="has-community" class="form-control" value=''>
                                    <option value="" disabled selected></option>
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                </select>
                                <span class="error-label"></span>
                            </div>
                            <div class="form-group col-xs-12">
                                <label>Do you have friends/contacts that are interested in e betting in eSports?</label>
                                <select name="have-friends-interested" id="has-community" class="form-control" value=''>
                                    <option value="" disabled selected></option>
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                </select>
                                <span class="error-label"></span>
                            </div>
                        </div>
    
                        <div class="row">
                            <div class="form-group col-xs-12">
                                <label>
                                    What other business do you have? 
                                </label>
                                <input type="text" name="other-businesses-reseller" class="form-control" placeholder="Other businesses">
                                <span class="error-label"></span>
                            </div>
                            <div class="form-group col-xs-12">
                                <label>
                                    Are you planning on doing this full-time or part-time?
                                </label>
                                <select name="full-part-time-reseller" id="has-community" class="form-control" value=''>
                                    <option value="" disabled selected></option>
                                    <option value="Full-time">Full-time</option>
                                    <option value="Part-time">Part-time</option>
                                </select>
                                <span class="error-label"></span>
                            </div>
                        </div>
    
                        <div class="row">
                            <div class="form-group col-xs-12">
                                <label>
                                    What mode of payments do you currently offer? 
                                </label>
                                <input type="text" name="payment-methods-reseller" class="form-control" placeholder="Payment Methods">
                                <span class="error-label"></span>
                            </div>   

                            <div class="form-group col-xs-12">
                                <label>Do you manage a community?</label>
                                <select name="has-community-reseller" id="has-community-reseller" class="form-control" value=''>
                                    <option value="" disabled selected></option>
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                </select>
                                <span class="error-label"></span>
                            </div>
                        
                        </div>

                        <div class="row">
                            <div class="form-group col-xs-12">
                                <label>What region/country do you think most of your members are from?</label>
                                <input type="text" name="region-reseller" class="form-control" placeholder="e.g. Cebu / Rizal ">
                                <span class="error-label"></span>
                            </div>

                            <div class="form-group col-xs-12">
                                <label>
                                    Is the community composed of 18+ members that can be potential bettors?
                                </label>
                                <select name="legal-members-reseller"  class="form-control" value="">
                                    <option value="" disabled selected></option>
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                </select>
                                <span class="error-label"></span>
                            </div>                            
                        </div>                       

                        <div class="row">

                            <div class="form-group col-xs-12">
                                <label>
                                    How many members do you have?
                                </label>
                                <input type="text" name="total-members-reseller" class="form-control" placeholder="Number of members">
                                <span class="error-label"></span>
                            </div>     

                        </div>  

                        
                                                   
                    </div>                    


                    <div id="partnerFormFollowUpStreamer" class="follow-up-qestions" style="display:none;">

    
                        <div class="row">
                            <div class="form-group col-xs-12">
                                <label>
                                    What platform do you stream?
                                </label>
                                <input type="text" name="streamer-platforms" class="form-control" placeholder="e.g. Twitch, Facebook, Youtube">
                                <span class="error-label"></span>
                            </div>

                            <div class="form-group col-xs-12">
                                <label>
                                    How many hours do you stream in a month?
                                </label>
                                <input type="text" name="streamer-hours-in-month" class="form-control" placeholder="Hours Streaming in a month">
                                <span class="error-label"></span>
                            </div>

                        </div>

                        <div class="row">
                            <div class="form-group col-xs-12">
                                <label>
                                    Provide link to your channel or page
                                </label>
                                <input type="text" name="streamer-page-link" class="form-control" placeholder="Provide link to your channel or page">
                                <span class="error-label"></span>
                            </div>

                            <div class="form-group col-xs-12">
                                <label>
                                    How many followers/subscribers you currently have?
                                </label>
                                <input type="text" name="streamer-followers" class="form-control" placeholder="Number of followers/subscribers">
                                <span class="error-label"></span>
                            </div>

                        </div>
                        
                        <div class="row">
                            <div class="form-group col-xs-12">
                                <label>
                                    What games do you stream?
                                </label>
                                <input type="text" name="streamer-games" class="form-control" placeholder="Games streaming">
                                <span class="error-label"></span>
                            </div>   

                            <div class="form-group col-xs-12">
                                <label>
                                    How many hours do you stream for Dota 2 related content?
                                </label>
                                <input type="text" name="streamer-hours-dota2" class="form-control" placeholder="Dota 2 hours">
                                <span class="error-label"></span>
                            </div>  


                            <div class="form-group col-xs-12">
                                <label>
                                    How many hours do you stream for CS:GO related content?
                                </label>
                                <input type="text" name="streamer-hours-csgo" class="form-control" placeholder="CS:GO Hours">
                                <span class="error-label"></span>
                            </div>                            
                        
                        </div>

                        <div class="row">

                            <div class="form-group col-xs-12">
                                <label>
                                    Do you cast tournament matches in your stream?
                                </label>
                                <select name="cast-tournaments"  class="form-control" value="">
                                    <option value="" disabled selected></option>
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                </select>
                                <span class="error-label"></span>
                            </div>

                            <div class="form-group col-xs-12">
                                <label>
                                    How many hours do you cast tournament matches in your stream for Dota 2  related content?
                                </label>
                                <input type="text" name="cast-tournaments-dota2-hours" class="form-control" placeholder="Hours casting Dota 2 tournaments">
                                <span class="error-label"></span>
                            </div>      
                            
                        </div>                       

                        <div class="row">
                            <div class="form-group col-xs-12">
                                <label>
                                    Do you have existing real money betting website sponsors? 
                                </label>
                                <select name="have-existing-betting-sponsor"  class="form-control" value="">
                                    <option value="" disabled selected></option>
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                </select>
                            </div>

                            <div class="form-group col-xs-12">
                                <label>
                                    How much earnings do you get from your current real money betting website sponsor?
                                </label>
                                <input type="text" name="earnings-from-betting-sponsor" class="form-control" placeholder="Earnings">
                                <span class="error-label"></span>
                            </div>     

                            <div class="form-group col-xs-12">
                                <label>
                                    How much compensation do you expect from us for sponsoring your stream?
                                </label>
                                <input type="text" name="expected-compensation" class="form-control" placeholder="Expected compensation">
                                <span class="error-label"></span>
                            </div>     
                        </div>                          
                        
                        
                    </div>    

                    
                    <!-- group moderator follow up questions -->
                    <div id="partnerFormFollowUpGroupModerator" class="follow-up-qestions" style="display:none;">

    
                        <div class="row">
                            <div class="form-group col-xs-12">
                                <label>
                                    Have you tried moderating an online community before?
                                </label>
                                <select name="moderated-community-before"  class="form-control" value="">
                                    <option value="" disabled selected></option>
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                </select>
                                <span class="error-label"></span>
                            </div>
                            <div class="form-group col-xs-12">
                                <label>
                                    What online community have you managed?
                                </label>
                                <input type="text" name="community-managed" class="form-control" placeholder="Online Community Managed">
                                <span class="error-label"></span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-xs-12">
                                <label>
                                    How many members does it have?
                                </label>
                                <input type="text" name="community-members" class="form-control" placeholder="Number of members">
                                <span class="error-label"></span>
                            </div>

                            <div class="form-group col-xs-12">
                                <label>
                                    How long have you been managing it?
                                </label>
                                <input type="text" name="community-how-long-managing" class="form-control" placeholder="e.g. weeks, months, years">
                                <span class="error-label"></span>
                            </div>

                        </div>
                        
                        <div class="row">
                            <div class="form-group col-xs-12">
                                <label>
                                    Can you provide a link of the group you manage?
                                </label>
                                <input type="text" name="community-managed-link" class="form-control" placeholder="Group link">
                                <span class="error-label"></span>
                            </div>   

                            <div class="form-group col-xs-12">
                                <label>
                                    What should we do to manage our group properly?
                                </label>
                                <textarea name="community-management-suggestion" class="form-control" rows="5"></textarea>
                                <span class="error-label"></span>
                            </div>  


                            <div class="form-group col-xs-12">
                                <label>
                                    Please have a detailed suggestion of what we should do to ensure our community is happy
                                </label>
                                <textarea name="community-management-suggestion-detailed" class="form-control" rows="5"></textarea>
                                <span class="error-label"></span>
                            </div>                          
                        
                        </div>

                        <div class="row">

                            <div class="form-group col-xs-12">
                                <label>
                                    How many group moderators should we have to ensure we have 24/7 active community support?
                                </label>
                                <input type="text" name="number-group-moderators" class="form-control" placeholder="Number of group moderators for support">
                                <span class="error-label"></span>
                            </div>   
                            

                            <div class="form-group col-xs-12">
                                <label>
                                    How many months have you been betting in 2ez.bet?
                                </label>
                                <input type="text" name="group-mod-months-betting" class="form-control" placeholder="Months betting in 2ez.bet">
                                <span class="error-label"></span>
                            </div>      
                            
                        </div>                       

                        <div class="row">
                            <div class="form-group col-xs-12">
                                <label>
                                    Do you stream?
                                </label>
                                <select name="group-mod-do-you-stream"  class="form-control" value="">
                                    <option value="" disabled selected></option>
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                </select>
                            </div>

                            <div class="form-group col-xs-12">
                                <label>
                                    Are you also an aspiring streamer?
                                </label>
                                <select name="group-mod-aspiring-streamer"  class="form-control" value="">
                                    <option value="" disabled selected></option>
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                </select>
                            </div>  

                            <div class="form-group col-xs-12">
                                <label>
                                    Do you want to stream and do give aways?
                                </label>
                                <select name="group-mod-stream-and-giveaways"  class="form-control" value="">
                                    <option value="" disabled selected></option>
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                </select>
                            </div>     
                        </div>                          

                        <div class="row">
                            <div class="form-group col-xs-12">
                                <label>
                                    Are you ready to work from home?
                                </label>
                                <select name="group-mod-work-from-home"  class="form-control" value="">
                                    <option value="" disabled selected></option>
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                </select>
                            </div>

                            <div class="form-group col-xs-12">
                                <label>
                                    What is the specs of your PC?
                                </label>
                                <textarea name="group-mod-pc-specs" class="form-control" rows="5"></textarea>
                                <span class="error-label"></span>
                            </div>  

                            <div class="form-group col-xs-12">
                                <label>
                                    What is the speed of your internet connection?
                                </label>
                                <input type="text" name="group-mod-internet-speed" class="form-control" placeholder="e.g. 10mbs, 20mbps, etc">
                                <span class="error-label"></span>
                            </div>  

                            <div class="form-group col-xs-12">
                                <label>
                                    Are you of legal age? 
                                </label>
                                <select name="group-mod-legal-age"  class="form-control" value="">
                                    <option value="" disabled selected></option>
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                </select>
                            </div>                            
                         
                            <div class="form-group col-xs-12">
                                <label>
                                    Are you willing to be employed and give your best always to help 2ez.bet?
                                </label>
                                <select name="group-mod-get-employed"  class="form-control" value="">
                                    <option value="" disabled selected></option>
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                </select>
                            </div>     

                        </div>  

                        
                    </div>    
                    <!-- end group moderator follow up questions -->
                    <div class="form-group">
                        <label>What other details can you add?</label>
                        <textarea name="other_details" class="form-control" rows="5"></textarea>
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <label class="col-xs-10">Security challenge: </label>
                            <div class="col-xs-4">
                                <input type="text" name="captcha" class="form-control">
                                <div class="captcha_image" style="padding-top: 5px">{!!captcha_img('flat')!!}</div>
                            </div>
                            <div class="col-xs-3">
                                <button type="button" class="btn btn-default" onClick="refreshCaptcha();"><i class="fa fa-refresh" aria-hidden="true"></i></button>
                            </div>
                        </div>
                    </div>
                </form>


            </div>
            <div class="modal-footer">
                <button id="sendPartnerForm" type="button" class="btn btn-warning">Submit</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script type="text/javascript" src="{{ asset('bower_components/bootstrap-sweetalert/dist/sweetalert.min.js')}}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#sendPartnerForm').click(function() {
            var formData = new FormData($("#partnerForm")[0]);
            $.ajax({
                url:"{{route('sendpartnerform')}}",
                type:'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                success:function(data) {
                    if(data.success) {
                        $('#partnerModal').modal('hide');
                        swal({
                            title: "Successfully sent form.",
                            text: 'Thank you for your interest in partnering with us. We will get back to you as soon as we can.',
                            type: "success",
                            html: true
                        });
                    } else {
                        $.each( data.errors, function( key, value ) {
                            $('#partnerForm').find(':input[name='+ key +']').parent().addClass('has-error');
                            $('#partnerForm').find(':input[name='+ key +']').parent().find('.error-label').text(value[0]);
                        });
                        $('#partnerForm').find('.captcha_image').html(data.new_captcha_image);
                    }
                },
                error: function(xhr, status, error){},
                data: formData,
                cache:false,
                contentType: false,
                processData: false,
            });
        });
        
        $('#partnerModal').on('hidden.bs.modal', function() {
            $("#partnerForm")[0].reset();
            $('#partnerForm').find(':input').parent().removeClass('has-error');
            $('#partnerForm').find(':input').parent().find('.error-label').text('');
            refreshCaptcha();
        });

        $('#partner-type').change(function(e){
            const currentVal = $(this).val();
            $('.follow-up-qestions').hide();
            switch(currentVal){
                case 'Betting Agents':

                    $('#partnerFormFollowUpBettingAgent').show();

                    break;

                case 'Reseller':

                    $('#partnerFormFollowUpReseller').show();

                    break;

                case 'Streamer': 

                    $('#partnerFormFollowUpStreamer').show();

                    break;

                case 'Group Moderator':
                    $('#partnerFormFollowUpGroupModerator').show();

                    break;
                    
                default: 

                    $('.follow-up-qestions').hide();

                    break;
            }

        })
    });
    
    function refreshCaptcha() {
        $.get('/partner/new-captcha-image', function(data) {
            $('#partnerForm').find('.captcha_image').html(data);
        });
    }
</script>
@endsection