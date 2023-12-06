@extends('panel.layout.layout.main')
@section('content')
    <div class="white-box">
        <div class="row">
            <div class="col-lg-2 col-sm-4 col-xs-4">
                <a type="button" class="btn btn-primary btn-circle btn-sm"
                   href="{{route('super_admin.lesson.content.new', ['lesson_id'=>$lesson_id])}}">
                    <i class="fa fa-plus"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="white-box">
        @if($errors->validator->any())
            <div class="alert alert-danger">
                <p><strong>لطفا خطاهای زیر را بررسی کنید</strong></p>
                <ul>
                    @foreach ($errors->validator->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if(Session::get("state") == "1")
            <div class="alert alert-success"><p>عملیات با موفقیت انجام شد</p></div>
        @elseif(Session::get("state") == "0")
            <div class="alert alert-danger"><p>متاسفانه عملیات با موفقیت انجام نشد</p></div>
        @endif


        <div class="row">
            <div class="col-sm-12">
                @foreach($contents as $content)
                    <div class="panel">
                        <div class="panel-body">
                            @if(!empty($content->content))
                                {!! $content->content !!}
                            @else
                                <span class="text-muted">[توضیحاتی ثبت نشده است]</span>
                            @endif
                        </div>
                        <div class="panel-footer">
                            <a class="btn btn-default btn-circle"
                               href="{{route('super_admin.lesson.content.details', ['content_id' => $content->id])}}">
                                <i class="fa fa-pencil"></i>
                            </a>
                            <a class="btn btn-default btn-circle"
                               href="{{route('super_admin.lesson.content.delete', ['id' => $content->id, 'lesson_id'=>$content->lesson_id])}}">
                                <i class="fa fa-trash text-danger"></i>
                            </a>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </div>
@endsection
