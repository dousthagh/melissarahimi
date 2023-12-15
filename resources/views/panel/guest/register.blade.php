@extends('panel.layout.layout.login_register')
@section('form')
    @if ($errors->validator->any())
        <div class="alert alert-danger">
            <p><strong>لطفا خطاهای زیر را بررسی کنید</strong></p>
            <ul>
                @foreach ($errors->validator->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if (Session::get('state') == '1')
        <div class="alert alert-success">
            <p>عملیات با موفقیت انجام شد</p>
        </div>
    @elseif(Session::get('state') == '0')
        <div class="alert alert-danger">
            @if (Session::get('message'))
                <p>{{ Session::get('message') }}</p>
            @else
                <p>متاسفانه عملیات با موفقیت انجام نشد</p>
            @endif
        </div>
    @endif


    <form class="form-horizontal form-material" id="loginform" action="{{ route('do_register') }}" method="post">
        @csrf
        <a href="javascript:void(0)" class="text-center db"><img src="{{ route('logo') }}" alt="Home"></a>
        <h3 class="box-title m-t-40 m-b-0 text-light">@lang('general.register')</h3>
        <div class="form-group m-t-20">
            <div class="col-xs-12">
                <input class="en-input form-control" type="text" name="first_name" required=""
                    placeholder="@lang('general.first_name')">
            </div>
        </div>
        <div class="form-group m-t-20">
            <div class="col-xs-12">
                <input class="en-input form-control" type="text" required="" name="last_name"
                    placeholder="@lang('general.last_name')">
            </div>
        </div>
        <div class="form-group ">
            <div class="col-xs-12">
                <input class="form-control" type="text" required="" name="email" placeholder="@lang('general.email')">
            </div>
        </div>
        <div class="form-group ">
            <div class="col-xs-12">
                <input class="form-control" type="password" required="" name="password" placeholder="@lang('general.password')">
            </div>
        </div>
        <div class="form-group">
            <div class="col-xs-12">
                <input class="form-control" type="password" required="" name="re_password"
                    placeholder="@lang('general.re_password')">
            </div>
        </div>
        <div class="form-group text-center m-t-20">
            <div class="col-xs-12">
                <span class="form-control">
                    <input type="checkbox" required="required" name="accept_term" />
                    <span>
                        <a href="{{route('terms')}}" target="_blank" class="text-danger bold"> @lang('general.accept_rules') </a>
                    </span>
                </span>

            </div>
        </div>
        <div class="form-group text-center m-t-20">
            <div class="col-xs-12">
                <button class="btn btn-rounded btn-info btn-lg btn-block text-uppercase waves-effect waves-light"
                    type="submit">@lang('general.register')</button>
            </div>
        </div>
        <div class="form-group m-b-0">
            <div class="col-sm-12 text-center text-light">
                <p class="text-small">@lang('general.have_any_account_question') <a href="{{ route('login') }}"
                        class="text-bold text-primary m-l-5"><b>@lang('general.login')</b></a></p>
            </div>
        </div>
    </form>
@endsection
