@extends('panel.layout.layout.main')
@section('content')
    <div class="white-box">
        @if($errors->validator->any())
            <div class="alert alert-danger">
                <p><strong>لطفا خطاهای زیر را بررسی کنید</strong></p>
                <ul>
                    @foreach ($errors->validator->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if(Session::get("state") == "1")
            <div class="alert alert-success"><p>عملیات با موفقیت انجام شد</p></div>
        @elseif(Session::get("state") == "0")
            <div class="alert alert-danger"><p>متاسفانه عملیات با موفقیت انجام نشد</p></div>
        @endif
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <form class="form-horizontal" action="{{route('super_admin.lesson.details.save')}}" method="post">
                        @csrf
                        <input type="hidden" value="{{$lesson->id}}" name="id"/>
                        <div class="form-group">
                            <label class="col-md-12">نیاز به نمونه کار</label>
                            <div class="col-md-12">
                                <label class="switch">
                                    <input type="checkbox" @if($lesson->with_sample_work) checked @endif name="with_sample_work">
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-12">عنوان</label>
                            <div class="col-md-12">
                                <input type="text" class="form-control" name="title" value="{{$lesson->title}}"></div>
                        </div>
                        <div class="form-group">
                            <div id="editor" style="min-height:200px;">
                                {!! $lesson->description !!}
                            </div>
                            <input type="hidden" name="description" id="description"
                                   value="{{$lesson->description}}"/>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-success waves-effect waves-light m-r-10 btn-rounded">
                                ذخیره
                            </button>
                            <a href="{{route('super_admin.lesson.content.index', ['lesson_id'=>$lesson->id])}}"
                               class="btn btn-default btn-rounded waves-effect waves-light m-r-10">
                                <i class="fa fa-solid fa-book"></i>
                                محتوا
                            </a>
                            <a href="{{route('super_admin.lesson.preview', ['lesson_id'=>$lesson->id])}}" target="_blank"
                               class="btn btn-default btn-rounded waves-effect waves-light m-r-10">
                                <i class="fa fa-solid fa-eye"></i>
                                 نمایش
                            </a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

@endsection
@section('script')
    <script>
        const initEditor = () => {
            let editor = document.getElementById('editor');
            if (editor) {
                var toolbarOptions = [
                    [
                        {
                            'font': []
                        }],
                    [
                        {
                            'header': [1, 2, 3, 4, 5, 6, false]
                        }],
                    ['bold', 'italic', 'underline', 'strike'],
                    ['blockquote', 'code-block'],
                    [
                        {
                            'header': 1
                        },
                        {
                            'header': 2
                        }],
                    [
                        {
                            'list': 'ordered'
                        },
                        {
                            'list': 'bullet'
                        }],
                    [
                        {
                            'script': 'sub'
                        },
                        {
                            'script': 'super'
                        }],
                    [
                        {
                            'indent': '-1'
                        },
                        {
                            'indent': '+1'
                        }], // outdent/indent
                    [
                        {
                            'direction': 'rtl'
                        }], // text direction
                    [
                        {
                            'color': []
                        },
                        {
                            'background': []
                        }], // dropdown with defaults from theme
                    [
                        {
                            'align': []
                        }],
                    ['clean'] // remove formatting button
                ];
                var quill = new Quill(editor,
                    {
                        modules:
                            {
                                toolbar: toolbarOptions
                            },
                        theme: 'snow'
                    });
            }
            (function () {
                'use strict';
                window.addEventListener('load', function () {
                    var forms = document.getElementsByClassName('needs-validation');
                    var validation = Array.prototype.filter.call(forms, function (form) {
                        form.addEventListener('submit', function (event) {
                            if (form.checkValidity() === false) {
                                event.preventDefault();
                                event.stopPropagation();
                            }
                            form.classList.add('was-validated');
                        }, false);
                    });
                }, false);
            })();
            return quill;
        }

        $(document).ready(function () {

            let quill = initEditor();

            quill.on('text-change', function (delta, oldDelta, source) {
                $("#description").val(quill.container.firstChild.innerHTML);
            });
        })
    </script>
@endsection
