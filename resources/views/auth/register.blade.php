@extends('layouts.main')

@section('content')
<div class="main-container dark-grey">
    <div class="m-container1" style="width: 98% !important;">
        <div class="main-ct">

            <div class="title2">User Registration</div>
            <div class="clearfix"></div>
            <div class="general">
                <form class="form-horizontal" method="POST" action="{{ route('register') }}">
                    {{ csrf_field() }}

                    <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                        <label for="name" class="col-md-4 control-label">Name</label>

                        <div class="col-md-6">
                            <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>

                            @if ($errors->has('name'))
                            <span class="help-block">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                        <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                        <div class="col-md-6">
                            <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

                            @if ($errors->has('email'))
                            <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                        <label for="password" class="col-md-4 control-label">Password</label>

                        <div class="col-md-6">
                            <input id="password" type="password" class="form-control" name="password" required>

                            @if ($errors->has('password'))
                            <span class="help-block">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password-confirm" class="col-md-4 control-label">Confirm Password</label>

                        <div class="col-md-6">
                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                        </div>
                    </div>

                    @if (Route::currentRouteName() == 'register.referral' && !empty($referral_code))
                        <div class="form-group{{ $errors->has('redeem_voucher_code') ? ' has-error' : '' }}">
                            <label for="referral-code" class="col-md-4 control-label">Referral Code</label>

                            <div class="col-md-6">
                                <input id="referral-code" type="referral-code" class="form-control" name="referral_code" value="{{ $referral_code ?? '' }}" {{ $referral_code ? 'readonly' : '' }}>
                            </div>
                        </div>
                    @else 
                        <div class="form-group{{ $errors->has('redeem_voucher_code') ? ' has-error' : '' }}">
                            <label for="redeem-voucher-code" class="col-md-4 control-label">Voucher Code</label>

                            <div class="col-md-6">
                                <input id="redeem-voucher-code" type="redeem-voucher-code" class="form-control" name="redeem_voucher_code" value="{{ app('request')->input('vcode') ?? '' }}" {{ app('request')->input('vcode') ? 'readonly' : '' }}>

                                @if ($errors->has('redeem_voucher_code'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('redeem_voucher_code') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                    @endif


                    <div class="form-group">
                        <div class="col-md-6 col-md-offset-4">
                            <button type="submit" class="btn btn-primary">
                                Register
                            </button>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-6 col-md-offset-4">
                            <div class="col-md-5"><hr style="border-top-width: 3px"/></div>
                            <div class="col-md-2"><p style="margin-top: 10px; font-size: 15px; text-align: center;
                            }">OR</p></div>
                            <div class="col-md-5"><hr style="border-top-width: 3px"/></div>
                        </div>
                        <div class="col-md-6 col-md-offset-4">
                            <a href="{{ url('/auth/facebook?vcode=' . (app('request')->input('vcode') ?? '')) }}" class="btn btn-primary" style="width: 100%; margin-bottom: 7px">
                                Facebook
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
