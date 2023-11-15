@extends('panel.layout.layout.main')
@section('content')
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

        <table class="tablesaw table-striped table-hover table-bordered table tablesaw-columntoggle"
               data-tablesaw-mode="columntoggle" id="table-977">
            <thead>
            <tr>
                <th scope="col" data-tablesaw-sortable-col="" data-tablesaw-sortable-default-col=""
                    data-tablesaw-priority="3" class="tablesaw-priority-3">شناسه
                </th>
                <th scope="col" data-tablesaw-sortable-col="" data-tablesaw-priority="persist">عنوان درس</th>
                <th scope="col" data-tablesaw-sortable-col="" data-tablesaw-sortable-default-col=""
                    data-tablesaw-priority="3" class="tablesaw-priority-3">عملیات
                </th>
            </tr>
            </thead>
            <tbody>
            @foreach($lessons as $lesson)
                <tr>
                    <td class="tablesaw-priority-4">{{$lesson->id}}</td>
                    <td class="title">{{$lesson->title}}</td>
                    <td class="tablesaw-priority-1">
                        <a class="btn btn-info btn-rounded"
                           href="{{route('super_admin.lesson.details', ['lesson_id' => $lesson->id])}}">
                            <i class="fa fa-eye"></i>
                            جزئیات
                        </a>

                        <a class="btn btn-success btn-rounded"
                           href="{{route('super_admin.lesson.files', ['lesson_id' => $lesson->id])}}">
                            <i class="fa fa-file"></i>
                            فایل ها
                        </a>
                    </td>
                </tr>
            @endforeach

            </tbody>
        </table>
    </div>
@endsection
