@extends('panel.layout.layout.main')
@section('content')
<div class="white-box">
    <div class="row">
        <div class="col-lg-2 col-sm-4 col-xs-4">
            <a type="button" class="btn btn-primary btn-circle btn-sm" href="{{route('super_admin.course.new', ['level_category_id'=>$level_category_id])}}">
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

        <div class="table-responsive">
            <table class="table table-hover manage-u-table">

                <tbody>
                @foreach($courses as $course)
                    <tr>
                        <td class="title">{{$course->title}}</td>
                        <td class="tablesaw-priority-1">
                           <div class="row">
                            <div class="col-lg-2 col-sm-4 col-xs-4">
                                <a class="btn btn-default btn-circle"
                                href="{{route('super_admin.course.details', ['course_id' => $course->id])}}">
                                 <i class="fa fa-pencil"></i>
                             </a>
                            </div>
                            <div class="col-lg-2 col-sm-4 col-xs-4">
                                
                             <a class="btn btn-default btn-circle"
                             href="{{route('super_admin.course.files', ['course_id' => $course->id])}}">
                              <i class="fa fa-file"></i>
                          </a>
                            </div>
                           </div>
                        </td>
                    </tr>
                @endforeach
    
                </tbody>
            </table>
        </div>
    </div>
@endsection
