<!doctype html>
<title>Site Maintenance</title>
<style>
  body { text-align: center; padding: 20px; }
  @media (min-width: 768px){
    body{ padding-top: 150px; }
  }
  h1 { font-size: 50px; margin-top: 10px; margin-bottom: 10px; }
  body { font: 20px Helvetica, sans-serif; color: #333; }
  article { display: block; text-align: left; max-width: 650px; margin: 0 auto; }
  a { color: #dc8100; text-decoration: none; }
  a:hover { color: #333; text-decoration: none; }
  img { margin-left: -30px; }
</style>

<article>
    <img src="/images/ezbet_logo_main.png" alt="2ez.bet logo"/>
    <h1>We&rsquo;ll be back soon!</h1>
    <div>
        <p>Sorry for the inconvenience but we&rsquo;re performing some maintenance at the moment. If you need to you can always <a href="https://www.facebook.com/2ez.bet/" target="_blank">contact us</a>, otherwise we&rsquo;ll be back online shortly!</p>
        @if(!empty($exception->getMessage()))
        <p>For more info <a href="{{  $exception->getMessage() }}">click here.</a></p>
        @endif
        <p>&mdash; 2ez.bet Team #BetResponsibly</p>
    </div>
</article>