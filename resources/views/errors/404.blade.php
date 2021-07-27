@extends('layouts.main')

@section('content')
<style>
    .error-template {padding: 40px 15px;text-align: center;}
    .error-actions {margin-top:15px;margin-bottom:15px;}
    .error-actions .btn { margin-right:10px; }
</style>
<div class="main-container dark-grey">
    <div class="m-container1" style="width: 98% !important;">
        <div class="main-ct">
            <div class="title">Page not found!</div>
            <div class="clearfix"></div>
            <div class="error-template">
                <h1>
                    Oops!</h1>
                <h2>
                    404 Not Found</h2>
                <div class="error-details">
                    Sorry, an error has occured, Requested page not found!
                </div>
                <div class="error-actions">
                    <a href="{{url('/')}}" class="btn btn-warning btn-lg"><span class="glyphicon glyphicon-home"></span>
                        Go to Home Page </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection