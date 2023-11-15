@extends('panel.layout.layout.main')
@section('content')
    <div class="white-box">
        <table class="tablesaw table-bordered table-hover table tablesaw-swipe tablesaw-sortable"
               data-tablesaw-mode="swipe" data-tablesaw-sortable="" data-tablesaw-sortable-switch=""
               data-tablesaw-minimap="" data-tablesaw-mode-switch="" id="table-2621" style="">
            <tbody>
            @foreach(GetUserCategoriesWithLevel(auth()->user()) as $key=>$value)
                <tr>
                    <td colspan="3">
                        <h5>
                            <i class="fa fa-check"></i>
                            {{$value['level']["title"]}}
                        </h5>
                    </td>
                </tr>
                @foreach($value['categories'] as $categoryKey=>$categoryValue)
                    <tr>
                        <td>{{$categoryValue['title']}}</td>
                        <td>
                            <a href="{{route('master.details', ['user_level_category_id'=>$categoryValue['parent_user_level_category_id']])}}">{{$categoryValue['parent_user_name']}}</a>
                        </td>
                        <td>
                            <a class="btn btn-danger btn-rounded" href="@if($value['level']['order'] == 5 || $value['level']['order'] == 6)
                                            {{route('user_level_category.master.my_student', ["userLevelCategoryParentId" => $categoryValue['user_level_category_id']])}}
                                        @else
                                            {{route('user_level_category.lesson.list', ["user_level_category_id" => $categoryValue['user_level_category_id']])}}
                                        @endif">
                                <i class="fa fa-eye"></i>
                                مشاهده
                            </a>
                        </td>
                    </tr>
                @endforeach
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
