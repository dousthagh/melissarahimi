@extends("panel.layout.layout.main")
@section("content")
{{--    @if($userLevelCategoryDetails->expired)--}}
{{--        <div--}}
{{--            style="position: absolute; text-align:center; vertical-align: center; z-index: 99; color: white; background-color: rgba(0,0,0,0.79); width: 100%; height: 100%">--}}
{{--            <div style="position: relative; top: 50%">--}}
{{--                اعتبار شما به پایان رسیده است. برای پیگیری با آکادمی تماس بگیرید--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    @endif--}}
    <div class="container-fluid p-t-10">
        <div class="row">
            @foreach($lessons as $value)
                <div class="col-lg-4">
                    <div class="well well-lg">
                        <h4 class="text-center">{{$value->title}}</h4>
                        <div class="row">
                            <div class="col-lg-4 text-center">
{{--                                @if(!$userLevelCategoryDetails->expired)--}}

                                    <a
                                        @if($value->show_video)
                                            href="{{route('user_level_category.lesson.details', ["userLevelCategoryId"=>$value->user_level_category_id, "lessonId"=>$value->id])}}"
                                        @else
                                            disabled=""
                                        href="#"
                                        @endif
                                        class="btn btn-1e btn-rounded @if(!$value->show_video) disabled @endif">
                                        نمایش
                                    </a>
{{--                                @endif--}}
                            </div>
                            <div class="col-lg-8">
                                <div class="text-right">
                                    @if($value->is_passed)
                                        <i class="mdi mdi-check text-success"></i>
                                    @else
                                        <i class="mdi mdi-circle text-dark"></i>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
