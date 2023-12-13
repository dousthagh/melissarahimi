@extends("panel.layout.layout.main")
@section("content")
    <div class="white-box">
        <h3 class="box-title">لیست مستر ها</h3>
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
    </div>
    <div class="white-box">
        <div class="table-responsive">
            <table class="table color-table inverse-table">
                <thead>
                <tr>
                    <th>شناسه ی کاربر</th>
                    <th>نام و نام خانوادگی</th>
                    <th>ایمیل</th>
                    <th>لاین/دسته بندی</th>
                    <th>عملیات</th>
                </tr>
                </thead>
                <tbody>
                @foreach($masters as $master)
                    <tr>
                        <td>{{$master->user_id}}</td>
                        <td>{{$master->user_full_name}}</td>
                        <td>{{$master->user_email}}</td>
                        <td>{{$master->category_title}} </td>
                        <td>
                            <a href="{{route('super_admin.master.update_details', ['user_level_category_id' => $master->user_level_category_id])}}"
                               class="btn btn-primary">فایل ها</a>
                            <a href="{{route('super_admin.master.all_student.list', ['user_level_category_id' => $master->user_level_category_id])}}"
                               class="btn btn-success">هنرجوها</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
