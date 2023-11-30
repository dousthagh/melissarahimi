@extends("panel.layout.layout.main")
@section("content")
    <div class="container">
        <div class="white-box">
            {!! $details->description !!}

        </div>

            @for($i= 0; $i<count($details->files); $i++)
                <div class="row">
                    <div class="col-lg-12 col-xs-12">
                        <div class="white-box">
                            <h3 class="box-title">
                                {{$details->files[$i]->title}}    
                            </h3>
                            @if(in_array(strtolower($details->files[$i]->postfix), ['.mp4', '.mpeg']))
                                <video id="video_player" controls="" controlsList="nodownload" style="height: auto; width:100%;">
                                    <source
                                        src="{{route('user_level_category.lesson.files.address', ['key'=>$details->files[$i]->secret_key, 'userLevelCategoryId'=>$userLevelCategoryId, 'private_key'=>$key])}}" type="video/mp4"/>
                                </video>
                            @elseif(in_array(strtolower($details->files[$i]->postfix), ['.png', '.jpeg', '.jpg'])) 
                                <a href="{{route('user_level_category.lesson.files.address', ['key'=>$details->files[$i]->secret_key, 'userLevelCategoryId'=>$userLevelCategoryId, 'private_key'=>$key])}}">
                                    <img alt="{{$details->files[$i]->title}}" src="{{route('user_level_category.lesson.files.address', ['key'=>$details->files[$i]->secret_key, 'userLevelCategoryId'=>$userLevelCategoryId, 'private_key'=>$key])}}" class="img-responsive model_img" id="sa-image"/> 
                                </a>
                            @endif
                        </div>
                    </div>

                    
                </div>
            @endfor

        </div>
            <div class="white-box">
                <a class="btn btn-danger"
                   href="{{route('user_level_category.lesson.sample_work', ['lessonId'=>$details->id, 'userLevelCategoryId'=>$userLevelCategoryId])}}">
                    <i class="fa fa-send"></i>
                    نمونه کار مربوط به درس
                </a>
            </div>


@endsection

