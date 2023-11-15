@extends("panel.layout.layout.main")
@section("content")
    <style>
        .carousel-indicators {
            bottom: -3.4375rem;
        }

        .carousel-indicators li {
            background-color: rgba(177, 4, 4, 0.41);
        }

        .carousel-indicators .active {
            background-color: #b10404;
        }
    </style>
    <div class="container">
        <div class="white-box">
            <div data-example-id="togglable-tabs" class="bs-example bs-example-tabs">
                <ul role="tablist" class="nav nav-tabs" id="myTabs">
                    <li class="active" role="presentation"><a aria-expanded="false" aria-controls="desc"
                                                              data-toggle="tab"
                                                              role="tab" id="desc-tab" href="#desc">توضیحات</a></li>
                    <li class="" role="presentation"><a aria-controls="file" data-toggle="tab" id="file-tab"
                                                        role="tab" href="#file" aria-expanded="true">فایل ها</a>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div aria-labelledby="desc-tab" id="desc" class="tab-pane fade active in" role="tabpanel">
                        {!! $details->description !!}
                    </div>
                    <div aria-labelledby="file-tab" id="file" class="tab-pane fade" role="tabpanel">
                        @if(count($details->files) > 1)
                            <p class="text-muted">
                                این درس شامل
                                {{count($details->files)}}
                                فایل می باشد. برای مشاهده ی همه ی فایلها برروی dot های پایین فایل کلیک کنید
                            </p>
                        @endif
                        <div id="myCarousel" class="carousel slide" data-ride="carousel">

                            <!-- Indicators -->
                            <ol class="carousel-indicators">
                                @for($i= 0; $i<count($details->files); $i++)
                                    <li data-target="#myCarousel" data-slide-to="{{$i}}"
                                        @if($i==0) class="active" @endif></li>
                                @endfor
                            </ol>

                            <div class="carousel-inner">
                                @for($i= 0; $i<count($details->files); $i++)

                                    <div class="item @if($i==0) active @endif">
                                        <div class="embed-responsive embed-responsive-16by9">
                                            <div class="embed-responsive-item">
                                                @if(in_array($details->files[$i]->postfix, ['.mp4', '.mpeg']))
                                                    <video id="video_player" controls="" controlsList="nodownload">
                                                        <source
                                                            src="{{route('user_level_category.lesson.files.address', ['key'=>$details->files[$i]->secret_key, 'userLevelCategoryId'=>$userLevelCategoryId, 'private_key'=>$key])}}"/>
                                                    </video>
                                                @elseif(in_array($details->files[$i]->postfix, ['.png', '.jpeg', '.jpg']))
                                                    <img
                                                        class="img img-responsive"
                                                        alt="{{$details->files[$i]->title}}"
                                                        src="{{route('user_level_category.lesson.files.address', ['key'=>$details->files[$i]->secret_key, 'userLevelCategoryId'=>$userLevelCategoryId, 'private_key'=>$key])}}"/>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endfor
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
            <div class="white-box">
                <a class="btn btn-danger"
                   href="{{route('user_level_category.lesson.sample_work', ['lessonId'=>$details->id, 'userLevelCategoryId'=>$userLevelCategoryId])}}">
                    <i class="fa fa-send"></i>
                    نمونه کار مربوط به درس
                </a>
            </div>

    </div>

@endsection
@section("script")
    <script>
        $('#myCarousel').carousel({
            interval: false
        })
        document.addEventListener('contextmenu', event => event.preventDefault());
    </script>
@endsection
