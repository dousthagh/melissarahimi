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
                <th scope="col" data-tablesaw-sortable-col="" data-tablesaw-priority="persist">دسته بندی لاین</th>
                <th scope="col" data-tablesaw-sortable-col="" data-tablesaw-sortable-default-col=""
                    data-tablesaw-priority="3" class="tablesaw-priority-3">سطح
                </th>
                <th scope="col" data-tablesaw-sortable-col="" data-tablesaw-sortable-default-col=""
                    data-tablesaw-priority="3" class="tablesaw-priority-4">لوگو
                </th>
                <th scope="col" data-tablesaw-sortable-col="" data-tablesaw-priority="2" class="tablesaw-priority-2">
                    عملیات
                </th>
            </tr>
            </thead>
            <tbody>
            @foreach($level_categories as $levelCategory)
                <tr>
                    <td class="title">{{$levelCategory->category_title}}</td>
                    <td class="tablesaw-priority-3">{{$levelCategory->level_title}}</td>
                    <td class="tablesaw-priority-4">
                        @if($levelCategory->logo_file_address != null)
                            <a target="_blank"
                               href="{{route('super_admin.level_category.logo', ['level_category_id' => $levelCategory->id])}}">
                                <img
                                    src="{{route('super_admin.level_category.logo.thumb', ['level_category_id' => $levelCategory->id])}}"
                                    class="img img-responsive img-rounded"/>
                            </a>
                        @else
                            تعریف نشده
                        @endif
                    </td>
                    <td class="tablesaw-priority-2">
                        @if($levelCategory->level_sort_order == 1)
                            <a class="btn btn-info btn-rounded"
                               href="{{route('super_admin.lesson.index', ['level_category_id' => $levelCategory->id])}}">دروس</a>
                        @endif
                        <a class="btn btn-success btn-rounded"
                           href="{{route('super_admin.level_category.logo.change', ['level_category_id' => $levelCategory->id])}}">تغییر
                            لوگو</a>
                    </td>
                </tr>
            @endforeach

            </tbody>
        </table>
    </div>
@endsection
