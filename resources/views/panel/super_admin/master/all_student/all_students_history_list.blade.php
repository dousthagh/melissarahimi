@extends("panel.layout.layout.main")
@section("content")
</div>
    <div class="white-box">
        <h3 class="box-title">سوابق </h3>

        <div class="table-responsive">
            <table class="table color-table inverse-table">
                <thead>
                    <tr>
                        <th>نام و نام خانوادگی</th>
                        <th>سطح</th>
                        <th>تاریخ شروع</th>
                        <th>تاریخ پایان اعتبار</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($students as $student)
                    <tr>
                        <td>
                            {{$student->user_full_name}}
                            <br/>
                            <span class="text-muted small">
                                {{$student->user_email}}
                            </span>
                        </td>
                        <td>{{$student->level_title}}</td>
                        <td>{{verta($student->user_level_category_create_date)->format("Y/m/d")}}</td>
                        <td>{{verta($student->user_level_category_expire_date)->format("Y/m/d")}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
