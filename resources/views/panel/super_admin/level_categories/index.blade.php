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


        <div class="table-responsive">
            <table class="table table-hover manage-u-table">
            <tbody>
            @foreach($level_categories as $levelCategory)
                <tr>
                    <td >
                        @if($levelCategory->logo_file_address != null)
                        <a target="_blank"
                           href="{{route('super_admin.level_category.logo', ['level_category_id' => $levelCategory->id])}}">
                            <img
                                src="{{route('super_admin.level_category.logo.thumb', ['level_category_id' => $levelCategory->id])}}"
                                style="width: 50px; height:50px;"/>
                        </a>
                    @endif
                        {{$levelCategory->category_title}}

                        <p class="text-muted">{{$levelCategory->level_title}}</p>
                    </td>
                    <td >
                        <a type="button" class="btn btn-primary btn-circle btn-sm" href="{{route('super_admin.course.index', ['level_category_id' => $levelCategory->id])}}">
                            <i class="fa fa-solid fa-book"></i>
                        </a>

                        @if($levelCategory->level_sort_order == 1)
                            <a class="btn btn-info btn-rounded"
                               href="{{route('super_admin.lesson.index', ['level_category_id' => $levelCategory->id])}}">
                               <i class="fa fa-solid fa-list"></i> 
                            </a>
                        @endif
                        <a class="btn btn-success btn-rounded"
                           href="{{route('super_admin.level_category.logo.change', ['level_category_id' => $levelCategory->id])}}">
                                <i class="fa fa-solid fa-image"></i>
                            </a>
                    </td>
                </tr>
            @endforeach

            </tbody>
        </table>
        </div>
    </div>
@endsection
