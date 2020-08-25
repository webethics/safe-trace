@extends('layouts.app')
@section('content')
 <div class="row h-100">
                <div class="col-12 col-md-8 col-lg-6 mx-auto my-auto">
                    <div class="card auth-card">
                        <div class="form-side">
                           <span class="logo_image d-block mb-3"><img src="{{asset('img/logo.png')}}"></span>
					 @if($notwork)
                    <form method="POST" action="{{ $url_post }}">
                        {{ csrf_field() }}
                        <h6 class="mb-4">
                                    {{ trans('global.reset_password') }}
                              </h6>
						@include('flash-message')		
                        <div>
                            <input name="token" value="{{ $token }}" type="hidden">
                           
                            <div class="form-group has-feedback">
                                <input type="password" name="password" class="form-control"  placeholder="{{ trans('global.login_password') }}">
                                @if($errors->has('password'))
                                    <em class="invalid-feedback" style="display:block">
                                        {{ $errors->first('password') }}
                                    </em>
                                @endif
                            </div>
                            <div class="form-group has-feedback">
                                <input type="password" name="password_confirmation" class="form-control" placeholder="{{ trans('global.login_password_confirmation') }}">
                                @if($errors->has('password_confirmation'))
                                    <em class="invalid-feedback" style="display:block">
                                        {{ $errors->first('password_confirmation') }}
                                    </em>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 text-right">
                                <button type="submit" class="btn btn-primary btn-lg btn-shadow uppercase_button">
                                    {{ trans('global.reset_password') }}
                                </button>
                            </div>
                        </div>
                    </form>
					@else
						 <h1>
                           
                                <div class="" style="font-size:14px">This link is not working any more.Please click <strong><a href="{{url('/password/reset')}}" >Here</a></strong> to reset password </div>
                           
                        </h1>
					@endif
               </div>
                    </div>
                </div>
            </div>
@endsection