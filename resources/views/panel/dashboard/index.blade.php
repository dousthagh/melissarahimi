@extends('panel.layout.layout.main')
@section('content')
    <div class="white-box">
        @foreach(GetUserCategoriesWithLevel(auth()->user()) as $key=>$value)
            @foreach($value['categories'] as $categoryKey=>$categoryValue)
                <a class="text-danger" href="@if($value['level']['order'] == 5 || $value['level']['order'] == 6)
                                                {{route('user_level_category.master.my_student', ["userLevelCategoryParentId" => $categoryValue['user_level_category_id']])}}
                                            @else
                                                {{route('user_level_category.lesson.list', ["user_level_category_id" => $categoryValue['user_level_category_id']])}}
                                            @endif">
                    <div class="panel">
                        <div class="panel-body">
                            <h5 class="text-danger">
                                {{$value['level']["title"] . "-" . $categoryValue['title']}}
                            </h5>
                            <span class="text-muted">{{$categoryValue['parent_user_name']}}</span>
                        </div>
                        <div class="panel-footer text-muted small">
                            <div class="grid-container">
                                <div class="media-footer-box">
                                    شروع:
                                    <span>
                                    {{verta($categoryValue['created_date'])->format('Y/m/d')}}
                                </span>
                                </div>

                                <div class="media-footer-box">
                                    انقضا:
                                    <span>
                                    {{verta($categoryValue['expire_date'])->format('Y/m/d')}}
                                </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        @endforeach
    </div>
@endsection
