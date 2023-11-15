@extends('panel.layout.layout.login_register')
@section('form')
    <form class="form-horizontal form-material" id="loginform" action="{{route('do_login')}}" method="post">
        @csrf
        <a href="javascript:void(0)" class="text-center db"><img src="{{route('logo')}}"
                                                                 width="46"
                                                                 height="46"
                                                                 alt="Home"/></a>

        <div class="form-group m-t-40">
            <div class="col-xs-12">
                <input class="form-control" type="email" required="required" placeholder="@lang('general.email')" name="email">
            </div>
        </div>
        <div class="form-group">
            <div class="col-xs-12">
                <input class="form-control" type="password" required="required" placeholder="@lang('general.password')" name="password">
            </div>
        </div>
        <div class="form-group text-center m-t-20">
            <div class="col-xs-12">
                <button class="btn btn-info btn-lg btn-block text-uppercase waves-effect waves-light"
                        type="submit">@lang('general.login')</button>
            </div>
        </div>

        <div class="form-group m-b-0">
            <div class="col-sm-12 text-center">
                <p>@lang('general.donot_have_any_account_question') <a href="{{route('register')}}" class="text-primary m-l-5"><b>@lang('general.register')</b></a></p>
            </div>
        </div>
    </form>
@endsection
