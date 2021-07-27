@extends('layouts.main')
@section('styles')
<style>
    .faq-item{
        padding: 0px 10px 10px;
    }
    #faq-list-view{
        margin-top: 30px;
    }
    .faq-item-title{
        margin: 10px 0px 7px 0px;
    }
    .faq-item-text{
        margin: 0px 0px -3px;
    }
    .last-modified-text{
        color: gray;
    }
    .checkbox-container {
        display: block;
        position: relative;
        padding-left: 35px;
        margin-bottom: 0px;
        cursor: pointer;
        font-size: 14px;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }
    .checkbox-container .checkbox-item {
        position: absolute;
        opacity: 0;
        cursor: pointer;
    }
    .checkmark {
        position: absolute;
        top: 0;
        left: 3px;
        height: 22px;
        width: 22px;
        border-radius: 4px;
        background-color: #eee;
    }
    .checkbox-container:hover .checkbox-item ~ .checkmark {
        background-color: #ccc;
    }
    .checkbox-container .checkbox-item:checked ~ .checkmark {
        background-color: #2196F3;
    }
    .checkmark:after {
        content: "";
        position: absolute;
        display: none;
    }
    .checkbox-container .checkbox-item:checked ~ .checkmark:after {
        display: block;
    }
    .checkbox-container .checkmark:after {
        left: 7px;
        top: 1px;
        width: 8px;
        height: 16px;
        border: solid white;
        border-width: 0 3px 3px 0;
        -webkit-transform: rotate(45deg);
        -ms-transform: rotate(45deg);
        transform: rotate(45deg);
    }
    .faq-container{
        width: 100%; 
        border-radius: 6px; 
        margin: 5px 0px;
        padding: 12px; 
        background-color: #e3e5e8; 
        cursor: pointer;
    }
    .error-label{
        color: red;
        display: none;
    }
</style>
<link rel="stylesheet" href="{{ asset('bower_components/bootstrap-sweetalert/dist/sweetalert.css') }}">
<link rel="stylesheet" href="{{ asset('css/fileinput.css') }}">
<link rel="stylesheet" href="{{ asset('css/datatables.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/viewer.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/select2/select2.min.css') }}">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.1/css/responsive.dataTables.min.css">
<link rel="stylesheet" href="{{ asset('css/partner-mobile-view.css') }}">

@endsection

@section('content')

<div class="mobile-sidebar-overlay">
    <div class="mobile-sidebar">
        <div class="mobile-retreat">
            <i class="fa fa-angle-double-left" aria-hidden="true"></i>
        </div>
        @if (!Auth::guest())
        <div class="mobile-profile-tab">
            <div class="mobile-profile-img" 
            @if(Auth::user()->provider == 'local')
            style="background-image: url('{{ url('/').'/'.Auth::user()->avatar }}')"
            @else
            style="background-image: url('{{ Auth::user()->avatar }}')"
            @endif
            ></div>
            <p class="mobile-profile-name">{{ Auth::user()->name }}</p>
            <p class="mobile-profile-credits">EZ Credits: <span>{{ Auth::user()->credits }}</span></p>
        </div>
        @endif
        <div class="mobile-menu-options">
            <a href="{{ url('home') }}"><div class="mobile-menu-item">&nbsp;&nbsp;<i class="fa fa-trophy" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;&nbsp;<span>Matches</span></div></a>
            <a href="{{ url('/profile') }}"><div class="mobile-menu-item">&nbsp;&nbsp;<i class="fa fa-user" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;&nbsp;<span>My Profile</span></div></a>
            <a href="{{ url('/partner') }}"><div class="mobile-menu-item">&nbsp;&nbsp;<i class="fa fa-handshake-o" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;&nbsp;<span>Partners</span></div></a>
            <a href="{{ url('/market') }}"><div class="mobile-menu-item">&nbsp;&nbsp;<i class="fa fa-tag" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;&nbsp;<span>Market</span></div></a>
            <a href="{{ url('careers') }}"><div class="mobile-menu-item">&nbsp;&nbsp;<i class="fa fa-at" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;&nbsp;<span>Matches</span></div></a>
            @if (!Auth::guest() && Auth::user()->isAdmin())
            <a href="{{ url('/matchmanager') }}"><div class="mobile-menu-item">&nbsp;&nbsp;<i class="fa fa-bolt" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;&nbsp;<span>Match Manager</span></div></a>
            <a href="{{ url('/admin') }}"><div class="mobile-menu-item">&nbsp;&nbsp;<i class="fa fa-cogs" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;&nbsp;<span>Admin</span></div></a>
            @else
                @if (!Auth::guest() && Auth::user()->isMatchManager())
                <a href="{{ url('/matchmanager') }}"><div class="mobile-menu-item">&nbsp;&nbsp;<i class="fa fa-bolt" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;&nbsp;<span>Match Manager</span></div></a>
                @endif
            @endif
            @if (!Auth::guest() && (Auth::user()->isAgent()) && (Auth::user()->userPartner && Auth::user()->userPartner->verified == 1 && Auth::user()->userPartner->active == 1) )
            <a href="{{ url('/agent') }}"><div class="mobile-menu-item">&nbsp;&nbsp;<i class="fa fa-tachometer" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;&nbsp;<span>Partner Dashboard</span></div></a>
            @endif
            @if (!Auth::guest())
            <a href="{{ url('/logout') }}"><div class="mobile-menu-item">&nbsp;&nbsp;<i class="fa fa-sign-out" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;&nbsp;<span>Logout</span></div></a>
            @endif
        </div>
        <div class="mobile-sidebar-footer">
            <div>
                <span><a href="{{ url('/faq')}}" data-pjax>Frequently Asked Question (FAQ)</a> | <a href="https://2ez.freshservice.com/support/tickets/new" data-pjax>Submit A Ticket</a> | <a href="https://www.facebook.com/groups/2ez.bet/" target="_blank">Join our Community</a></span><br>
                <span>© 2017 Powered by <a href="{{ url('/') }}">2ez.bet</a></span>
            </div>
        </div>
    </div>
</div>

<div class="header-exclusive-mobile">
    <div class="header-mobile-menu">
        <span class="open-mobile-sidebar-menu"><i class="fa fa-bars" aria-hidden="true"></i></span>
    </div>
    <a href="{{ url('home')}}" data-pjax><img src="{{ asset('images/ezbet_logo_main.png') }}" /></a>
</div>

<div class="main-container dark-grey">
    <div class="m-container-lg">
        <div class="main-ct">
            <div class="title2">General Frequently Asked Questions</div>
            <div class="clearfix"></div>
            <div class="user-info pd-blk">
                <p class="last-modified-text">Last Modified: Sat, 9 Jun, 2018 at 3:44 AM</p>
                @if (!Auth::guest() && Auth::user()->isAdmin())
                <div class="admin-functions">
                    <button class="btn btn-primary add_new_faq" data-toggle="modal"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;&nbsp;Add new FAQ</button>
                    <button class="btn btn-danger delete_faq" data-toggle="modal"><i class="fa fa-trash-o" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;&nbsp;Delete FAQs</button>
                </div>
                @endif
                <div id="faq-list-view"></div>
            </div>
        </div>
    </div>
</div>

@if (!Auth::guest() && Auth::user()->isAdmin())
<div id="make-faq-modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close close-partner-modal-btn" data-dismiss="modal">&times;</button>
                <h5 class="modal-title transaction-title">New FAQ</h5>
            </div>
            <div class="modal-body">
                <form id="add_faq_form" autocomplete="off" action="" method="POST" role="form"> 
                    <div class="form-group">
                        <label> Question </label>
                        <input id="faq_title" name="title" class="form-control" style="resize: none;" value="" placeholder="Frequently Asked Question" data-toggle="tooltip" title="Your partnership name will be the default profile title if this field is empty.">
                        <label class="error-label">Invalid Input</label>
                    </div>    
                    <div class="form-group">
                        <label> Answer </label>
                        <textarea class="form-control" id="section_content_ckedit"></textarea>
                        <label class="error-label">Invalid Input</label>
                    </div>                    
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary save-btn">Save</button>
                <button type="button" class="btn btn-default close-partner-modal-btn" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<div id="update-faq-modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close close-partner-modal-btn" data-dismiss="modal">&times;</button>
                <h5 class="modal-title transaction-title">Update FAQ</h5>
            </div>
            <div class="modal-body">
                <form id="update_faq_form" autocomplete="off" action="" method="POST" role="form"> 
                    <div class="form-group">
                        <label> Question </label>
                        <input id="faq_title_updated" name="title" class="form-control" style="resize: none;" value="" placeholder="Frequently Asked Question" data-toggle="tooltip" title="Your partnership name will be the default profile title if this field is empty.">
                        <label class="error-label">Invalid Input</label>
                    </div>    
                    <div class="form-group">
                        <label> Answer </label>
                        <textarea class="form-control" id="section_content_updated_ckedit"></textarea>
                        <label class="error-label">Invalid Input</label>
                    </div>                    
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary save-btn">Save</button>
                <button type="button" class="btn btn-default close-partner-modal-btn" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<div id="delete-faq-modal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close close-partner-modal-btn" data-dismiss="modal">&times;</button>
                    <h5 class="modal-title transaction-title">Delete existing FAQs</h5>
                </div>
                <div class="modal-body">
                    <div style="width: 100%; border-bottom: 1px solid #cac6c6; position: relative; padding: 10px 15px;">
                        <p class="dps-15"><i class="fa fa-info-circle" aria-hidden="true"></i>&nbsp;&nbsp;Select one or more FAQs to be deleted and then press Delete</p>
                    </div>
                    <div id="shinobu" style="max-height: 300px; overflow-y: auto; width: 100%; padding: 10px 15px;">
                        <div style="width: 100%; border-radius: 6px; padding: 12px; background-color: #e3e5e8; cursor: pointer;">
                            <label class="checkbox-container">Sample Question?
                                <input type="checkbox" name="partner_choice" value="BPI" class="checkbox-item" name="radio">
                                <span class="checkmark"></span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger save-btn">Delete</button>
                    <button type="button" class="btn btn-default close-partner-modal-btn" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
@endif
@endsection

@section('script')
<script type="text/javascript" src="{{ asset('js/select2/select2.full.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/fileinput.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/theme.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/datatables.min.js')}}"></script>
<script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.1/js/dataTables.responsive.min.js"></script>
<script type="text/javascript" src="{{ asset('js/viewer.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/moment.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('bower_components/bootstrap-sweetalert/dist/sweetalert.min.js')}}"></script>
<script src="{{ asset('js/datatables-datetime-moment.js') }}"></script>
<script src="{{ asset('js/mustache.min.js') }}"></script>
<script src="https://cdn.ckeditor.com/4.10.0/standard-all/ckeditor.js"></script>
<script type="text/javascript">
$(document).ready(function(){
    FAQLoadQuestions();
    CKEDITOR.replace('section_content_ckedit', {
        extraPlugins: 'embed,autoembed,image2',
			height: 300,
			contentsCss: [ CKEDITOR.basePath + 'contents.css', 'https://sdk.ckeditor.com/samples/assets/css/widgetstyles.css' ],
			embed_provider: '//ckeditor.iframe.ly/api/oembed?url={url}&callback={callback}',
			image2_alignClasses: [ 'image-align-left', 'image-align-center', 'image-align-right' ],
			image2_disableResizer: true,
            extraAllowedContent: 'iframe(*){*}[*]',
        });
    CKEDITOR.replace('section_content_updated_ckedit', {
        extraPlugins: 'embed,autoembed,image2',
			height: 300,
			contentsCss: [ CKEDITOR.basePath + 'contents.css', 'https://sdk.ckeditor.com/samples/assets/css/widgetstyles.css' ],
			embed_provider: '//ckeditor.iframe.ly/api/oembed?url={url}&callback={callback}',
			image2_alignClasses: [ 'image-align-left', 'image-align-center', 'image-align-right' ],
			image2_disableResizer: true,
            extraAllowedContent: 'iframe(*){*}[*]',
        });
    $('.open-mobile-sidebar-menu, .mobile-retreat').click(function () {
        $('.mobile-sidebar-overlay').toggle('slide', {
            direction: 'left'
        }, 300);
    });

    $('.add_new_faq').click(function(){
        $('#make-faq-modal').modal('show');
    });

    $('.delete_faq').click(function(){
        $('#delete-faq-modal').modal('show');
    });

    $('#make-faq-modal').on('click', '.save-btn', function(){
        var $this = $(this);
        $this.prop('disabled', true);
        $this.button('progress');
        $.ajax({
            url: "{{ route('addFaq') }}",
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            type: 'POST',
            data: {
                question: $('#faq_title').val(),
                answer: CKEDITOR.instances.section_content_ckedit.getData(),
            },
            success: function(data) {
                if(data.errors){
                    $.each( data.errors, function( key, value ) {
                        if (key == 'amount') {
                            $('#faq_title').siblings('.error-label').text('Don\'t leave the question field empty').css('display', 'block');
                        }
                        else{
                            $('#section_content_ckedit').siblings('.error-label').text('Don\'t leave the answer field empty.').css('display', 'block');
                        }
                    });
                }else{
                    if (!data.success) {
                        swal({
                            title: "Failed to add FAQ",
                            text: 'There\'s something wrong. Please try again later.',
                            type: "error",
                            html: true,
                        });
                    }
                    else{
                        swal({
                            title: "New FAQ added",
                            text: 'New FAQ has been added successfully.',
                            type: "success",
                            html: true,
                        });
                        $('#make-faq-modal').modal('hide');
                        FAQLoadQuestions()
                    }
                }
                $this.prop('disabled', false);
                $this.button('reset');
            }
        });
    });

    $('#update-faq-modal').on('click', '.save-btn', function(){
        var $this = $(this);
        $this.prop('disabled', true);
        $this.button('progress');
        $.ajax({
            url: "{{ route('updateFaq') }}",
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            type: 'POST',
            data: {
                id: $this.data('id'),
                question: $('#faq_title_updated').val(),
                answer: CKEDITOR.instances.section_content_updated_ckedit.getData(),
            },
            success: function(data) {
                if(data.errors){
                    $.each( data.errors, function( key, value ) {
                        if (key == 'amount') {
                            $('#faq_title_updated').siblings('.error-label').text('Don\'t leave the question field empty').css('display', 'block');
                        }
                        else{
                            $('#section_content_updated_ckedit').siblings('.error-label').text('Don\'t leave the answer field empty.').css('display', 'block');
                        }
                    });
                }else{
                    if (!data.success) {
                        swal({
                            title: "Failed to add FAQ",
                            text: 'There\'s something wrong. Please try again later.',
                            type: "error",
                            html: true,
                        });
                    }
                    else{
                        swal({
                            title: "FAQ updated!",
                            text: 'New FAQ has been updated successfully.',
                            type: "success",
                            html: true,
                        });
                        $('#update-faq-modal').modal('hide');
                        FAQLoadQuestions()
                    }
                }
                $this.prop('disabled', false);
                $this.button('reset');
            }
        });
    });

    $('#make-faq-modal').on('hidden.bs.modal', function(){
        $('#make-faq-modal').find('.save-btn').prop('disabled', false);
        $('#make-faq-modal').find('.save-btn').button('progress');
        $('#faq_title').val('');
        CKEDITOR.instances.section_content_ckedit.setData('')
    });

    $('#delete-faq-modal').on('click', '.save-btn', function(){
        var child = $('#shinobu').children(), new_item_order = [], tbd_order = [];
        for(var i = 0; i < child.length; i++){
            var checkbox = $(child[i]).find('.checkbox-item').is(':checked') ? 1 : 0;
            if(checkbox == 0){
                new_item_order.push( $(child[i]).find('.checkbox-item').val() )
            }
            else{
                tbd_order.push( $(child[i]).find('.checkbox-item').val() )
            }
        }
        if(new_item_order.length < child.length){
            var tbd = tbd_order.length, titles = tbd > 1 ? 'these FAQs' : 'this FAQ', texts = tbd > 1 ? 'These FAQs' : 'This FAQ';
            swal({
                title: "Delete "+ titles +"?",
                text: texts + " will be deleted and cannot be undone once confirmed.",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Yes, remove FAQ!",
                showLoaderOnConfirm: true,
                closeOnConfirm: true
            },
            function(){
                removeFAQ( tbd_order )
            });
        }
        else{
            swal({
                title: "Oops!",
                text: "Select at least one FAQ to be deleted.",
                type: "error",
                html: true,
            });
        }
    });

    function FAQLoadQuestions(){
        var fragment = document.createDocumentFragment();
        $.ajax({
            url: "{{ route('allFaq') }}",
            type: "GET",
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            success: function(data){
                var count = 0, fragment = document.createDocumentFragment(), items = document.createDocumentFragment();
                if(data.faq.length > 0){
                    $.each(data.faq, function(index, faq){
                        var div = document.createElement('div'), h4 = document.createElement('h4'), text = document.createElement('p'),
                            item = document.createElement('div'), label = document.createElement('label'), input = document.createElement('input'), 
                            span = document.createElement('span'), div2 = document.createElement('div'), button = document.createElement('button');
                        $(h4).addClass('faq-item-title').html('<b>'+faq.question+'</b>');
                        $(text).html(faq.answer).find('p').addClass('faq-item-text');
                        @if (!Auth::guest() && Auth::user()->isAdmin())
                        $(button).addClass('btn btn-success update_this_faq').attr({'data-toggle': 'modal', 'href': '#update-faq-modal'}).data({'faq-id': faq.id, 'title' : faq.question, 'desc': faq.answer}).html('<i class="fa fa-pencil" aria-hidden="true"></i>&nbsp;&nbspUpdate this FAQ').bind('click', function(e){
                            $('#update-faq-modal').find('#faq_title_updated').val($(this).data('title'))
                            $('#update-faq-modal').find('.save-btn').data('id', $(this).data('faq-id'))
                            CKEDITOR.instances.section_content_updated_ckedit.setData($(this).data('desc'))
                        });
                        $(div2).append(button)
                        $(div).addClass('faq-item').append(h4).append(text).append(div2);
                        @else
                        $(div).addClass('faq-item').append(h4).append(text);
                        @endif
                        $(input).attr('type', 'checkbox').val(faq.id).addClass('checkbox-item').bind('click', function(){
                            
                        });
                        $(span).addClass('checkmark');
                        $(label).addClass('checkbox-container').text(faq.question).append(input).append(span);
                        $(item).addClass('faq-container').append(label);
                        items.append(item)
                        fragment.append(div);
                        $('.last-modified-text').html('Last Modified: '+moment(faq.created_at).format('llll')+', '+moment(faq.created_at).fromNow());
                    });
                    $('#faq-list-view').html('').append(fragment);  
                    $('#shinobu').html('').append(items);  
                }           
            },
        });
    }

    function removeFAQ(order){
        $.ajax({
            url: "{{ route('deleteFaq') }}",
            type: "POST",
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            data: {
                list: order,
            },
            success: function(data){
                if(data.success){
                    var lengths = data.length;
                    swal({
                        title: "Deleted!",
                        text: data.message,
                        type: "success",
                        html: true,
                    });
                    FAQLoadQuestions()
                }
                else{
                    swal({
                        title: "Oops!",
                        text: data.message,
                        type: "error",
                        html: true,
                    });
                }
            },
        });
    }
});
</script>
@endsection
