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
                    <form class="form-horizontal" action="{{route('super_admin.course.details.save')}}" method="post">
                        <input type="hidden" value="{{$level_category_id}}" name="level_category_id"/>
                        @csrf
                        <div class="form-group">
                            <label class="col-md-12">عنوان</label>
                            <div class="col-md-12">
                                <input type="text" class="form-control" name="title" required></div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12">توضیحات</label>
                            <div id="editor" style="min-height:200px;">
                            </div>
                            <input type="hidden" name="description" id="description"
                                   value=""/>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-success waves-effect waves-light m-r-10">ذخیره</button>
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
