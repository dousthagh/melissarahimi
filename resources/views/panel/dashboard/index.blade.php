@extends('panel.layout.layout.main')
@section('content')
    <div class="white-box">
        <div class="table-responsive">
            <table class="table table-hover manage-u-table">
                <tbody>
                    @foreach(GetUserCategoriesWithLevel(auth()->user()) as $key=>$value)
                        <tr>
                            <td>
                                <h5>
                                    <i class="fa fa-check"></i>
                                    {{$value['level']["title"]}}
                                </h5>
                            </td>
                        </tr>

                        @foreach($value['categories'] as $categoryKey=>$categoryValue)
                            <tr>
                                <td>
                                    <h5>
                                        <a class="text-danger" href="@if($value['level']['order'] == 5 || $value['level']['order'] == 6)
                                                {{route('user_level_category.master.my_student', ["userLevelCategoryParentId" => $categoryValue['user_level_category_id']])}}
                                            @else
                                                {{route('user_level_category.lesson.list', ["user_level_category_id" => $categoryValue['user_level_category_id']])}}
                                            @endif">
                                                {{$categoryValue['title']}}
                                        </a>
                                    </h5>
                                    <small>
                                        <a class="text-muted" href="{{route('master.details', ['user_level_category_id'=>$categoryValue['parent_user_level_category_id']])}}">
                                            <i class="fa fa-circle-thin text-muted"></i>
                                            {{$categoryValue['parent_user_name']}}
                                        </a>
                                    </small>
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
@endsection
