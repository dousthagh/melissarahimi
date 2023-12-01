@extends("panel.layout.layout.main")
@section("content")
    <div class="white-box">

        <div class="panel panel-default">
            <div class="panel-wrapper collapse in">
                <table class="table table-hover">
                    <tbody>
                        @foreach($levels as $level)
                            <tr>
                                <td>
                                    <a a href="{{route('master.course.index', ["level_category_id"=>$level->level_category_id])}}">
                                    {{$level->level_title}}
                                </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
