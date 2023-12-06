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
            <div class="media">
                <div class="media-left">
                    <a href="javascript:void(0)"> <img alt="{{$userLevelCategoryDetails->category_title}}" class="media-object"
                                                       src="{{route('super_admin.level_category.logo.thumb', ['level_category_id' => $userLevelCategoryDetails->level_category_id])}}"
                                                       data-holder-rendered="true" style="width: 64px; height: 64px;">
                    </a>
                </div>
                <span class="media-body">
                    <h5 class="media-heading text-blue">{{$userLevelCategoryDetails->category_title}}</h5>
                    <span class="text-muted"> {!! $userLevelCategoryDetails->category_description !!}</span>
                </div>
            </div>


            <div class="list-group">

                @foreach($lessons as $value)
                    <a href="{{(!$userLevelCategoryDetails->expired) ? route('user_level_category.lesson.details', ["userLevelCategoryId"=>$value->user_level_category_id, "lessonId"=>$value->id]) : ''}}" class="list-group-item">
                        <h4 class="list-group-item-heading ">{{$value->title}}</h4>
                        <p class="list-group-item-text ">
                            @if($value->is_passed)
                                <i class="mdi mdi-check text-success"></i>
                            @else
                                &nbsp;
                            @endif
                        </p>
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
