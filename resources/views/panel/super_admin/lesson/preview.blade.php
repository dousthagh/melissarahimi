@extends('panel.layout.layout.main')
@section('content')

    <div class="panel panel-white">
        <div class="panel-heading ">
            <div class="panel-title ">{{ $details->title }}</div>
        </div>
        <div class="panel-body">
            @if (count($details->files) > 0 || strlen(strip_tags(trim($details->description))) > 0)
                <div class="white-box m-b-10">
                    <div class="container-fluid">
                        <div id="carousel-example-captions-3" data-ride="carousel" class="carousel slide">
                            <ol class="carousel-indicators">
                                @for ($i = 0; $i < count($details->files); $i++)
                                    @if (in_array(strtolower($details->files[$i]->postfix), ['.png', '.jpeg', '.jpg']))
                                        <li data-target="#carousel-example-captions-3" data-slide-to="{{ $i }}"
                                            class="@if ($i == 0) active @endif"></li>
                                    @endif
                                @endfor
                            </ol>
                            <div role="listbox" class="carousel-inner">
                                @for ($i = 0; $i < count($details->files); $i++)
                                    @if (in_array(strtolower($details->files[$i]->postfix), ['.png', '.jpeg', '.jpg']))
                                        <div class="item @if ($i == 0) active @endif">
                                            <img src="{{ route('super_admin.lesson.files.address', ['key' => $details->files[$i]->secret_key, 'private_key' => $key]) }}"
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
                        <span class="label-rounded label-warning"> {{ $content->id }}</span>
                        {!! $content->content !!}
                    </div>
                    @for ($i = 0; $i < count($content->files); $i++)
                        <div class="white-box">
                            <div class="row">
                                <div class="col-lg-12">
                                    @if (in_array(strtolower($content->files[$i]->postfix), ['mp4', 'mpeg']))
                                        <div class="panel">
                                            <div class="panel-body">
                                                <a class="text-danger"
                                                    href="{{ route('super_admin.lesson.files.address', ['key' => $content->files[$i]->secret_key, 'lesson_content_id' => $content->id, 'private_key' => $key]) }}">
                                                    <i class="fa fa-play-circle"></i>
                                                    نمایش ویدئو شماره {{ $contentIndex }}
                                                </a>
                                                @php
                                                    $contentIndex++;
                                                @endphp
                                            </div>
                                        </div>
                                    @elseif(in_array(strtolower($content->files[$i]->postfix), ['png', 'jpeg', 'jpg', 'tif']))
                                        <a
                                            href="{{ route('super_admin.lesson.content.files.address', ['key' => $content->files[$i]->secret_key, 'lesson_content_id' => $content->id, 'private_key' => $key]) }}">
                                            <img alt="{{ $content->files[$i]->title }}"
                                                src="{{ route('super_admin.lesson.content.files.address', ['key' => $content->files[$i]->secret_key, 'lesson_content_id' => $content->id, 'private_key' => $key]) }}"
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
    </div>
@endsection

@section('script')
    <script>
        document.addEventListener('contextmenu', function(e) {
            e.preventDefault();
        });
    </script>
@endsection
