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

    .m-container3 {
        /* margin: 15px 0.5% 15px 15%; */
        background: none repeat scroll 0% 0% #DEDEDE;
        width: 98%;
        padding-bottom: 0px;
        box-shadow: 0px 1px 2px #000;
        /* margin-left: 10px; */
        /* margin-right: 0px; */
        margin: 20px;
    }

</style>
<link rel="stylesheet" href="{{ asset("css/select2/select2.min.css") }}">
<link rel="stylesheet" href="{{ asset("css/fileinput.css") }}">
<link rel="stylesheet" href="{{ asset('bower_components/bootstrap-sweetalert/dist/sweetalert.css') }}">
<link rel="stylesheet" href="{{ asset('/bower_components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css') }}"/>
<link rel="stylesheet" href="{{ asset('css/datatables.min.css') }}">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.1/css/responsive.dataTables.min.css">

@endsection

@section('content')

<user-credit-logs
    :logs="{{ $logs }}"
    :user="{{ $user }}"
    url  ="{{ htmlentities(url('/bet-log/' . $user->id)) }}"
    start_date = "{{ $start_date }}"
    end_date="{{ $end_date }}"
></user-credit-logs>
@endsection

@section('script')
<script type="text/javascript" src="{{ asset('js/moment.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('/bower_components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js') }}"></script>

<script>
$(document).ready(function() {
    $('.datepicker').datetimepicker({
        format: 'YYYY-MM-DD'
    });
})
</script>
@endsection
