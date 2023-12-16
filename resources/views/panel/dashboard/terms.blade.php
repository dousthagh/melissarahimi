@extends('panel.layout.layout.block')
@section('content')
    <div class="white-box">
       <div class="panel">
            <div class="panel-heading">
                <h5 class="panel-title">
                    @lang('general.terms_and_conditions')
                </h5>
            </div>
            <div class="panel-body">
                <h5>
                    @lang('general.term_condition_title')
                </h5>
                <ul>
                    <li>@lang('general.term_condition_1')</li>
                    <li>@lang('general.term_condition_2')</li>
                    <li>@lang('general.term_condition_3')</li>
                    <li>@lang('general.term_condition_4')</li>
                    <li>@lang('general.term_condition_5')</li>
                    <li>@lang('general.term_condition_6')</li>

                </ul>
            </div>
            <div class="panel-footer">
                <a href="{{route('user.terms.accept')}}" class="btn btn-success btn-rounded btn-lg">قبول قوانین</a>
            </div>
       </div>
    </div>
@endsection
