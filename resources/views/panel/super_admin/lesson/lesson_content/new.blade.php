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
            <div class="alert alert-success"><p>عملیات با موفقیت انجام شد
                    <span class="m-l-5"></span>
                    <a href="{{route('super_admin.lesson.preview', ['lesson_id'=>$lesson_id])}}"
                       class="btn btn-default btn-rounded waves-effect waves-light m-r-10">
                        <i class="fa fa-solid fa-eye"></i>
                        نمایش
                    </a>
                </p></div>
        @elseif(Session::get("state") == "0")
            <div class="alert alert-danger"><p>متاسفانه عملیات با موفقیت انجام نشد</p></div>
        @endif

        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <form class="form-horizontal" action="{{route('super_admin.lesson.content.save')}}" method="post" enctype="multipart/form-data">
                        <input type="hidden" value="{{$lesson_id}}" name="lesson_id"/>
                        @csrf
                        <div class="form-group">
                            <div id="editor" style="min-height:200px;">
                            </div>
                            <input type="hidden" name="description" id="description"
                                   value=""/>
                        </div>
                        <div class="panel panel-darkblue">
                            <div class="panel-heading">
                                <div class="panel-title">فایل ها</div>
                            </div>
                            <div class="panel-body" >
                                <div class="container-fluid row" id="file-container">
                                    <div class="col-md-6 p-10" id="container0" >
                                        <div class="col-md-1">
                                            <i class="fa fa-close text-danger remove" id="0" onclick="doRemove(this)" index="0" ></i>
                                        </div>
                                        <div class="col-md-5">
                                            <input type="file" name="file[]" id="file0"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-footer">
                                <button class="btn btn-info btn-rounded waves-effect waves-light" type="button"
                                        id="add-file">
                                    <i class="fa fa-plus"></i>
                                    افزودن
                                </button>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-success waves-effect waves-light m-r-10">ذخیره</button>
                            <span class="text-muted">
                                <a type="submit"
                                   class=" btn btn-outline waves-effect waves-light m-r-10"
                                   href="{{route('super_admin.lesson.content.index', ['lesson_id'=>$lesson_id])}}">
                                بازگشت
                            </a>
                                </span>
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
            let newId = 1;
            let quill = initEditor();

            quill.on('text-change', function (delta, oldDelta, source) {
                $("#description").val(quill.container.firstChild.innerHTML);
            });

            $("#add-file").click(function () {
                let html = '<div class="col-md-6 p-10" id="container'+newId+'"><div class="col-md-1"><i onclick="doRemove(this);" class="fa fa-close text-danger remove" index="'+newId+'" ></i></div> <div class="col-md-5"><input type="file" name="file[]" id="file'+newId+'"/></div></div>';
                $("#file-container").append(html)
                newId++;
            });
        })


        function doRemove(e) {
            let index = $(e).attr('index');
            $("#file-container").find("#container"+index).remove();
        }

    </script>
@endsection
