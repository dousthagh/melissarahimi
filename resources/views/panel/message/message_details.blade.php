@extends('panel.layout.layout.main')
@section('content')
    <div class="white-box">
        <div class="row">
            <div class="col-lg-10 col-md-9 col-sm-8 col-xs-12 mail_listing">
                <div class="media m-b-30 p-t-20">
                    <h4 class="font-bold m-t-0">{{$details->title}}</h4>
                    <hr>

                    <div class="media-body">
                        <h4 class="text-danger m-0">{{$details->user_name}}</h4> <small class="text-muted">از جانب:
                            {{$details->user_email}}</small></div>
                </div>
                <p>
                    {!!  $details->content !!}
                </p>
                @if($details->link)
                    <span class="text-muted">
                        <a href="{{$details->link}}">@lang('message.click_on_message_link')</a>
                    </span>
                @endif
            </div>
        </div>
    </div>
@endsection
