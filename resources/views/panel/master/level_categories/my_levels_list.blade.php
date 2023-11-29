@extends("panel.layout.layout.main")
@section("content")
    <div class="white-box">
        <h3 class="box-title">لیست level های شما</h3>

        <div class="table-responsive">
            <table class="table color-table inverse-table">
                <thead>
                <tr>
                    <th>عنوان</th>
                    <th>عملیات</th>
                </tr>
                </thead>
                <tbody>
                @foreach($levels as $level)
                    <tr>
                        <td>{{$level->level_title}}</td>
                        <td>
                                <a href="{{route('master.course.index', ["level_category_id"=>$level->level_category_id])}}"
                                   class="btn btn-primary">مطالب</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{-- <div class="dataTables_paginate paging_simple_numbers" id="example23_paginate">
                {{$students->links("pagination::bootstrap-4")}}
            </div> --}}
        </div>
    </div>
@endsection
