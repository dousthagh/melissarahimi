@extends("panel.layout.layout.main")
@section("content")
    @if($userLevelCategoryDetails->expired)
        <div
            style="position: absolute; text-align:center; vertical-align: center; z-index: 99; color: white; background-color: rgba(0,0,0,0.79); width: 100%; height: 100%">
            <div style="position: relative; top: 50%">
                اعتبار شما به پایان رسیده است. برای پیگیری با آکادمی تماس بگیرید
            </div>
        </div>
    @endif
    <div class="container-fluid p-t-10">
        <div class="row">
            <div class="panel">
                <div class="media">
                    <div class="media-left">
                        <a href="javascript:void(0)"> <img alt="{{$userLevelCategoryDetails->category_title}}"
                                                           class="media-object"
                                                           src="{{route('super_admin.level_category.logo.thumb', ['level_category_id' => $userLevelCategoryDetails->level_category_id])}}"
                                                           data-holder-rendered="true"
                                                           style="width: 64px; height: 64px;">
                        </a>
                    </div>
                    <div class="media-body">
                        <h5 class="media-heading text-blue">{{$userLevelCategoryDetails->category_title}}</h5>
                    </div>
                    <div class="media-bottom text-muted small">
                        <div class="grid-container">
                            <div class="media-footer-box">
                                <i class="fa fa-user"></i>
                                <span>{{$userLevelCategoryDetails->parent->parentUser->name}}</span>
                            </div>
                            <div class="media-footer-box">
                                تعداد هنرجوهای فعال:
                                <span>{{$count}}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel small">
                <div class="panel-body ">
                    <div class="grid-container">
                        <div class="media-footer-box">
                            شروع:
                            <span>{{verta($userLevelCategoryDetails->created_at)->format('Y/m/d')}}</span>
                        </div>
                        <div class="media-footer-box">
                            تاریخ انقضا:
                            <span>{{verta($userLevelCategoryDetails->expire_date)->format('Y/m/d')}}</span>
                        </div>
                    </div>
                </div>
                <div class="panel-footer">
                    کد شما:
                    <span class="text-danger small">{{$userLevelCategoryDetails->code}}</span>
                </div>
            </div>
        </div>
        <div class="list-group">
            @foreach($lessons as $value)
                <a href="{{(!$userLevelCategoryDetails->expired) ? route('user_level_category.lesson.details', ["userLevelCategoryId"=>$value->user_level_category_id, "lessonId"=>$value->id]) : ''}}"
                   class="list-group-item bg-dark">
                    <h4 class="list-group-item-heading text-light">{{$value->title}}</h4>
                    <h5 class="list-group-item-text">
                        @if($value->is_passed)
                            <i class="mdi mdi-check text-success"></i>
                        @else
                            &nbsp;
                        @endif
                    </h5>
                </a>
            @endforeach
        </div>

    </div>

@endsection

@section('script')
    <script>

        function getVideoAddress() {
            $("#responsive-modal").modal("show")
        }
    </script>
@endsection
