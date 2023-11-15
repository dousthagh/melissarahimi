@extends("panel.layout.layout.main")
@section("content")
    <div class="white-box">
        <h3 class="box-title">لیست درس های دارای نمونه کار</h3>
        <div class="table-responsive">
            <table class="table color-table inverse-table">
                <thead>
                <tr>
                    <th>شناسه</th>
                    <th>عنوان</th>
                    <th>عملیات</th>
                </tr>
                </thead>
                <tbody>
                @foreach($lessons as $lesson)
                    <tr>
                        <td>{{$lesson->lesson_id}}</td>
                        <td>{{$lesson->lesson_title}}</td>
                        <td>
                            <a href="{{route('user_level_category.master.my_student.sample_work.details', ['lessonId'=>$lesson->lesson_id, 'userLevelCategoryId'=>$userLevelCategoryId])}}"
                               class="btn btn-success">نمونه کارها</a>
                        </td>
                    </tr>
                @endforeach

                </tbody>
            </table>
        </div>
    </div>
@endsection
