@extends('panel.layout.layout.main')
@section('content')
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
    <div class="white-box">
        <div class="panel">
            <div class="panel-title p-10 text-center bx-shadow">
                <h4>
                    {{$master_details->name}}
                </h4>
            </div>
            <div class="panel-body">
                <div id="myCarousel" class="carousel slide" data-ride="carousel">

                    <!-- Indicators -->
                    <ol class="carousel-indicators">
                        @for($i= 0; $i<count($master_files); $i++)
                            <li data-target="#myCarousel" data-slide-to="{{$i}}"
                                @if($i==0) class="active" @endif></li>
                        @endfor
                    </ol>
                    <div class="carousel-inner">
                        @for($i= 0; $i<count($master_files); $i++)
                            <div class="item @if($i==0) active @endif">
                                <div class="embed-responsive embed-responsive-16by9">
                                    <div class="embed-responsive-item">
                                        <video id="video_player" controls="" controlsList="nodownload">
                                            <source
                                                src="{{route('master.details.file.download', ['file_id'=>$master_files[$i]->id])}}"/>
                                        </video>
                                    </div>
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
