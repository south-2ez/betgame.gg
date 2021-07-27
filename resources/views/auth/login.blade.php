@extends('layouts.main')

@section('content')
<div class="main-container dark-grey">
    <div class="m-container1" style="width: 98% !important;">
        <div class="main-ct">

            <div class="title2">User Login</div>
            <div class="clearfix"></div>
            <div class="general">
                @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
                @endif
                @if (session('warning'))
                <div class="alert alert-warning">
                    {{ session('warning') }}
                </div>
                @endif
                @if (session('message'))
                    <div class="alert alert-danger text-center">{!!session('message') !!}</div>
                    @php session()->forget('message'); @endphp
                @endif
                <form class="form-horizontal" method="POST" action="{{ route('login') }}">
                    {{ csrf_field() }}

                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                        <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                        <div class="col-md-6">
                            <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>

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
                        <div class="col-md-6 col-md-offset-4">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-3 col-md-offset-4">
                            <a class="btn btn-link" href="{{ route('password.request') }}">
                                Forgot Your Password?
                            </a>
                            <button type="submit" class="btn btn-success" style="width: 100%;">
                                Login
                            </button>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-3 col-md-offset-4">
                            <div class="col-md-5"><hr style="border-top-width: 3px"/></div>
                            <div class="col-md-2"><p style="margin-top: 10px; font-size: 15px">OR</p></div>
                            <div class="col-md-5"><hr style="border-top-width: 3px"/></div>
                        </div>
                        <div class="col-md-3 col-md-offset-4">
                            <a href="{{ url('/auth/facebook') }}" class="btn btn-primary" style="width: 100%; margin-bottom: 7px">
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
