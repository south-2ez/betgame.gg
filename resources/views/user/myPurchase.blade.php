@extends('layouts.main')

<link rel="stylesheet" href="{{ asset("css/select2/select2.min.css") }}">
<link rel="stylesheet" href="{{ asset("css/fileinput.css") }}">
<link rel="stylesheet" href="{{ asset('bower_components/bootstrap-sweetalert/dist/sweetalert.css') }}">
<style>
    .title2 span {
        color: #d4af37;
    }

    .margin-25 {
        padding: 25px;
    }

    .capitalize {
        text-transform: capitalize;
    }

    .width-150 {
        width: 150px;
    }

    .side-margin-15 {
        margin-left: 15px;
        margin-right: 15px;
    }
</style>

@section('content')
<div class="main-container dark-grey">
    <div class="m-container1" style="width: 98% !important;">
        <div class="main-ct">
            <div class="title2">Purchase: <span class="text-primary">{{ $purchase->code ?? '' }}</span></div>

            <div class="row">
                <div class="col-md-12 margin-25">
                    <form class="form-horizontal" id="purchase-form" enctype="multipart/form-data">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-sm-2">Code:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" value="{{ $purchase->code ?? '' }}" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2">Voucher Code:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" value="{{ $purchase->voucher_code ?? '' }}" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2">Amount:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control text-right" value="{{ (!empty($purchase->amount)) ? number_format($purchase->amount, 2) : '' }}" readonly>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <?php
                                    $status = '';

                                    if(isset($purchase->status) && is_numeric($purchase->status)) {
                                        if($purchase->status == 0) {
                                            $status = 'Processing';
                                        } 

                                        if($purchase->status == 1) {
                                            $status = 'Accepted';
                                        }
                                    } else {
                                        $status = $purchase->status;
                                    }
                                ?>

                                <label class="control-label col-sm-2">Status:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control capitalize" value="{{ $status }}" readonly>
                                </div>
                            </div>

                            @if($purchase->deposit_type == 'partner')
                            <div class="form-group">
                                <label class="control-label col-sm-2">Partner:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" value="{{ $purchase->partner->partner_name ?? '' }}" readonly>
                                </div>
                            </div>
                            @endif

                            <input type="hidden" name="id" value="{{ $purchase->id ?? '' }}"/>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="side-margin-15">Upload Receipt</label>
                                <div class="input-group side-margin-15">
                                    <span class="input-group-btn">
                                        <span class="btn btn-default btn-file">
                                            Browse… <input name="receipt" type="file" id="imgInp">
                                        </span>
                                    </span>
                                    <input type="text" class="form-control" readonly>
                                </div>
                                <br/>
                                <div class="side-margin-15 text-center">
                                    <img class=" img-responsive " id='img-upload'/>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 text-right">
                            <br/>
                            <button type="submit" class="btn btn-info width-150" id="btn-submit">Submit</button>
                        </div>
                    <form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script type="text/javascript" src="{{ asset('js/select2/select2.full.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/fileinput.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/theme.js')}}"></script>
<script type="text/javascript" src="{{ asset('bower_components/bootstrap-sweetalert/dist/sweetalert.min.js')}}"></script>
<script src="{{ asset('js/mustache.min.js') }}"></script>

<script type="text/javascript">
$(document).ready( function() {
    $(document).on('change', '.btn-file :file', function() {
        var input = $(this),
            label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
        input.trigger('fileselect', [label]);
    });

    $('.btn-file :file').on('fileselect', function(event, label) {

        var input = $(this).parents('.input-group').find(':text'),
            log = label;

        if( input.length ) {
            input.val(log);
        } else {
            if( log ) alert(log);
        }

    });
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#img-upload').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#imgInp").change(function(){
        readURL(this);
    });

    $('#purchase-form').submit(function(e) {
        e.preventDefault();

        let purchase_id   = {{ $purchase->id ?? 0 }};
        let purchase_type = '{{ $purchase->deposit_type ?? '' }}';
        let url           = "{{ url('/my-purchase')}}" + "/" + purchase_type + '/' + purchase_id;

        $.ajax({
            url        : url,
            type       : "POST",
            headers    : { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            data       : new FormData(this),
            contentType: false,
            processData: false,
            success    : function(result) {
                swal({
                    title: 'Receipt Upload',
                    type: 'success',
                    text: result.message
                }, function() {
                    window.location.href = "{{ url('/')}}"
                });
            },
            statusCode: {
                422: function(result) {
                    for(let response in result.responseJSON) {
                        swal(result.responseJSON[response][0], "", "warning");
                    }
                },
                500: function() {
                    alert('Error occured while uploading image');
                }
            }
        });
    });

    // $(document).on("click", ".sweet-alert .confirm", function() {
    //     window.location.href = "{{ url('/')}}";
    // })
});
</script>


@endsection