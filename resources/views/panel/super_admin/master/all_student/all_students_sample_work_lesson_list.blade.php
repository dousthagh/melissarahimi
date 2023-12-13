@extends("panel.layout.layout.main")
@section("content")
    <div class="white-box">
        <div class="table-responsive">
            <table class="table color-table inverse-table">
                <tbody>
                @foreach($lessons as $lesson)
                    <tr>
                        <td>
                            <a href="{{route('user_level_category.master.my_student.sample_work.details', ['lessonId'=>$lesson->lesson_id, 'userLevelCategoryId'=>$userLevelCategoryId])}}"
                                >{{$lesson->lesson_title}}</a>
                            </td>
                    </tr>
                @endforeach

                </tbody>
            </table>
        </div>
    </div>
@endsection
