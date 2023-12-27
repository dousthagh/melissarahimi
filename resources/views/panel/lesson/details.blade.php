@extends('panel.layout.layout.main')
@section('content')
    <div class="no-select">

        <div class="panel panel-white">
            <div class="panel-heading ">
                <div class="panel-title ">{{ $details->title }}</div>
            </div>
            <div class="panel-body">
                <div id="content-container"></div>
                @if (count($details->files) > 0 || strlen(strip_tags(trim($details->description))) > 0)
                    <div class="white-box m-b-10">
                        <div class="container-fluid">
                            <div id="carousel-example-captions-3" data-ride="carousel" class="carousel slide">
                                <ol class="carousel-indicators">
                                    @for ($i = 0; $i < count($details->files); $i++)
                                        @if (in_array(strtolower($details->files[$i]->postfix), ['.png', '.jpeg', '.jpg']))
                                            <li data-target="#carousel-example-captions-3"
                                                data-slide-to="{{ $i }}"
                                                class="@if ($i == 0) active @endif"></li>
                                        @endif
                                    @endfor
                                </ol>
                                <div role="listbox" class="carousel-inner">
                                    @for ($i = 0; $i < count($details->files); $i++)
                                        @if (in_array(strtolower($details->files[$i]->postfix), ['.png', '.jpeg', '.jpg']))
                                            <div class="item @if ($i == 0) active @endif">
                                                <img src="{{ route('user_level_category.lesson.files.address', ['key' => $details->files[$i]->secret_key, 'userLevelCategoryId' => $userLevelCategoryId, 'private_key' => $key]) }}"
                                                    alt="{{ $details->files[$i]->title }}">
                                            </div>
                                        @endif
                                    @endfor
                                </div>
                            </div>
                        </div>
                        <span>
                            {!! $details->description !!}
                        </span>
                        <hr />
                    </div>
                @endif
                @if ($details->lessonContents != null)
                    @php
                        $contentIndex = 1;
                    @endphp
                    @foreach ($details->lessonContents as $content)
                        <div class="white-box p-0">
                            {!! $content->content !!}
                        </div>
                        @for ($i = 0; $i < count($content->files); $i++)
                            <div class="white-box">
                                <div class="row">
                                    <div class="col-lg-12">
                                        @if (in_array(strtolower($content->files[$i]->postfix), ['mp4', 'mpeg', 'mov']))
                                            {{-- <video id="video_player" controls="" controlsList="nodownload"
                                               style="height: auto; width:100%;" preload="metadata">
                                            <source
                                                src="{{route('lesson.content.files.address', ['key'=>$content->files[$i]->secret_key, 'lesson_content_id'=>$content->id, 'private_key'=>$key])}}"
                                                type="video/mp4"/>
                                        </video> --}}


                                            <div class="panel">
                                                <div class="panel-body">

                                                    <a class="text-danger"
                                                        href="{{ $content->files[$i]->arvan_video_id != null
                                                            ? route('user_level_category.lesson.video.show', ['video_id' => $content->files[$i]->arvan_video_id])
                                                            : route('lesson.content.files.address', [
                                                                'key' => $content->files[$i]->secret_key,
                                                                'lesson_content_id' => $content->id,
                                                                'private_key' => $key,
                                                            ]) }}">
                                                        <i
                                                            class="fa
                                                        fa-play-circle"></i>
                                                        نمایش ویدئو شماره {{ $contentIndex }}
                                                    </a>
                                                </div>
                                            </div>
                                            @php
                                                $contentIndex++;
                                            @endphp
                                        @elseif(in_array(strtolower($content->files[$i]->postfix), ['png', 'jpeg', 'jpg', 'tif']))
                                            <a
                                                href="{{ route('lesson.content.files.address', ['key' => $content->files[$i]->secret_key, 'lesson_content_id' => $content->id, 'private_key' => $key]) }}">
                                                <img alt="{{ $content->files[$i]->title }}"
                                                    src="{{ route('lesson.content.files.address', ['key' => $content->files[$i]->secret_key, 'lesson_content_id' => $content->id, 'private_key' => $key]) }}"
                                                    class="img-responsive model_img" id="sa-image" />
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endfor
                    @endforeach
                @endif
            </div>
            @if ($is_passed && $details->with_sample_work)
                <div class="panel-footer">
                    <div class="white-box">
                        <a class="btn btn-info btn-rounded"
                            href="{{ route('user_level_category.lesson.sample_work', ['lessonId' => $details->id, 'userLevelCategoryId' => $userLevelCategoryId]) }}">
                            <i class="fa fa-send"></i>
                            نمونه کار مربوط به درس
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@section('script')
    <script>
        document.addEventListener('contextmenu', function(e) {
            e.preventDefault();
        });
        $(document).ready(function() {
            var textToDisplay = "{{ $user_name }}";
            var $textContainer = $('#content-container');
            displayText(textToDisplay, $textContainer);

            setInterval(() => {
                if (!checkWatermark())
                    window.location = "{{ route('logout') }}";
            }, 500);
        })

        function displayText(text, $container) {
            var $text = $('<label class="random-text">' + text + '</label>');
            $container.append($text);


        }

        function checkWatermark() {
            var radnomTextContainer = $(".random-text").text();
            if (radnomTextContainer == undefined || radnomTextContainer != "{{ $user_name }}" || !$('.random-text').is(
                    ':visible') || $('.random-text').css('color') != 'rgb(0, 0, 0)') {
                return false;
            }
            return true;
        }
    </script>
@endsection
