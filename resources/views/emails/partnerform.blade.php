<!DOCTYPE html>
<html>
<head>
    <title>Partner Request Form</title>
</head>
 
<body>
<h2>Partner Request Form - 2ez.bet</h2>
<p>
<ul>
    <li>Name: <strong>{{$form['fname'] . ' ' . $form['lname']}}</strong></li>
    <li>Email Address: <strong>{{$form['email']}}</strong></li>
    <li>Mobile Number: <strong>{{$form['mobile_number']}}</strong></li>
    <li>Address / Location: <br/><strong>{{$form['address']}}</strong></li>
    <li>I am a: <strong>{{$form['type-iam']}}</strong></li>
    <li>I want to be a : <strong>{{$form['type']}}</strong></li>

    @if($form['type'] == 'Betting Agents')
        <li style="list-style-type: none;"> <strong>Answers to follow-up Questions (Betting Agents):</strong></li>
        <li>Do you manage a community? :  <strong>{{$form['has-community']}}</strong></li>
        <li>What region/country do you think most of your members are from? : <strong>{{$form['region']}}</strong></li>
        <li>Is the community composed of 18+ members that can be potential bettors? : <strong>{{$form['legal-members']}}</strong></li>
        <li>How many members do you have? : <strong>{{$form['total-members']}}</strong></li>
        <li>How many active bettors do you think you can manage to use 2ez.bet? : <strong>{{$form['total-bettors']}}</strong></li>
        <li>Have you tried being an agent for a betting website? :  <strong>{{$form['agent-before']}}</strong></li>
        <li>How many years have you been an agent and for which websites? :  <strong>{{$form['total-years-website']}}</strong></li>
        <li>What mode of payments do you currently offer? : <strong>{{$form['payment-methods']}}</strong></li>
        <li>How much are you currently able to handle in daily transactions? : <strong>{{$form['amount-transactions']}}</strong></li>
        <li>Can you ensure a 24/7 processing of transactions? : <strong>{{$form['can-process-247']}}</strong></li>
        <li>What other business do you have? : <strong>{{$form['other-businesses']}}</strong></li>
        <li>What is your profession? <strong>{{$form['profession']}}</strong></li>
    @endif

    @if($form['type'] == 'Reseller')
        <li style="list-style-type: none;"> <strong>Answers to follow-up Questions (Resellers):</strong></li>
        <li>Do you have a capital of at least 5k PHP? :  <strong>{{$form['has-capital-5k']}}</strong></li>
        <li>Do you have friends/contacts that are interested in e betting in eSports? : <strong>{{$form['have-friends-interested']}}</strong></li>
        <li>What other business do you have? : <strong>{{$form['other-businesses-reseller']}}</strong></li>
        <li>Are you planning on doing this full-time or part-time? : <strong>{{$form['full-part-time-reseller']}}</strong></li>
        <li>What mode of payments do you currently offer? : <strong>{{$form['payment-methods-reseller']}}</strong></li>
        <li>Do you manage a community? : <strong>{{$form['has-community-reseller']}}</strong></li>
        <li>What region/country do you think most of your members are from? : <strong>{{$form['region-reseller']}}</strong></li>
        <li>Is the community composed of 18+ members that can be potential bettors? : <strong>{{$form['legal-members-reseller']}}</strong></li>
        <li>How many members do you have? : <strong>{{$form['total-members-reseller']}}</strong></li>

    @endif


    @if($form['type'] == 'Streamer')
        <li style="list-style-type: none;"> <strong>Answers to follow-up Questions (Streamers):</strong></li>
        <li>What platform do you stream? : <strong>{{$form['streamer-platforms']}}</strong></li>
        <li>How many hours do you stream in a month? : <strong>{{$form['streamer-hours-in-month']}}</strong></li>
        <li>Provide link to your channel or page : <strong>{{$form['streamer-page-link']}}</strong></li>
        <li>How many followers/subscribers you currently have? : <strong>{{$form['streamer-followers']}}</strong></li>        
        <li>What games do you stream? : <strong>{{$form['streamer-games']}}</strong></li>
        <li>How many hours do you stream for Dota 2 related content? : <strong>{{$form['streamer-hours-dota2']}}</strong></li>
        <li>How many hours do you stream for CS:GO related content? : <strong>{{$form['streamer-hours-csgo']}}</strong></li>
        <li>Do you cast tournament matches in your stream? : <strong>{{$form['cast-tournaments']}}</strong></li>
        <li>How many hours do you cast tournament matches in your stream for Dota 2  related content? : <strong>{{$form['cast-tournaments-dota2-hours']}}</strong></li>
        <li>Do you have existing real money betting website sponsors? : <strong>{{$form['have-existing-betting-sponsor']}}</strong></li>
        <li>How much earnings do you get from your current real money betting website sponsor? : <strong>{{$form['earnings-from-betting-sponsor']}}</strong></li>
        <li>How much compensation do you expect from us for sponsoring your stream? : <strong>{{$form['expected-compensation']}}</strong></li>
    @endif

    @if($form['type'] == 'Group Moderator')
        <li style="list-style-type: none;"> <strong>Answers to follow-up Questions (Group Moderator):</strong></li>
        <li>Have you tried moderating an online community before? : <strong>{{$form['moderated-community-before']}}</strong></li>
        <li>What online community have you managed? : <strong>{{$form['community-managed']}}</strong></li>
        <li>How many members does it have? : <strong>{{$form['community-members']}}</strong></li>
        <li>How long have you been managing it? : <strong>{{$form['community-how-long-managing']}}</strong></li>        
        <li>Can you provide a link of the group you manage? : <strong>{{$form['community-managed-link']}}</strong></li>
        <li>What should we do to manage our group properly? : <strong>{{$form['community-management-suggestion']}}</strong></li>
        <li>Please have a detailed suggestion of what we should do to ensure our community is happy : <strong>{{$form['community-management-suggestion-detailed']}}</strong></li>
        <li>How many group moderators should we have to ensure we have 24/7 active community support? : <strong>{{$form['number-group-moderators']}}</strong></li>
        <li>How many months have you been betting in 2ez.bet? : <strong>{{$form['group-mod-months-betting']}}</strong></li>
        <li>Do you stream?  : <strong>{{$form['group-mod-do-you-stream']}}</strong></li>
        <li>Are you also an aspiring streamer? : <strong>{{$form['group-mod-aspiring-streamer']}}</strong></li>
        <li>Do you want to stream and do give aways? : <strong>{{$form['group-mod-stream-and-giveaways']}}</strong></li>
        <li>Are you ready to work from home? : <strong>{{$form['group-mod-work-from-home']}}</strong></li>
        <li>What is the specs of your PC? : <strong>{{$form['group-mod-pc-specs']}}</strong></li>
        <li>What is the speed of your internet connection? : <strong>{{$form['group-mod-internet-speed']}}</strong></li>
        <li>Are you of legal age? : <strong>{{$form['group-mod-legal-age']}}</strong></li>
        <li>Are you willing to be employed and give your best always to help 2ez.bet? : <strong>{{$form['group-mod-get-employed']}}</strong></li>
    @endif   

    <li>
        Other Details:<br/>
        <strong>{{$form['other_details']}}</strong>
    </li>
</ul>
</p>
</body>
 
</html>
