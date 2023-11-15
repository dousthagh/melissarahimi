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
                    <form class="form-horizontal" action="{{route('super_admin.level_category.logo.save')}}" method="post"  enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" value="{{$level_category_id}}" name="id"/>
                        <div class="form-group">
                            <label class="col-sm-12">انتخاب لوگو</label>
                            <div class="col-sm-12">
                                <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                    <input type="file" required name="file" id="input-file-now"
                                           class="form-control dropify"/>
                                </div>
                            </div>
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
        $(document).ready(function () {
            // Basic
            $('.dropify').dropify();
            // Translated
            $('.dropify-fr').dropify({
                messages: {
                    default: 'Glissez-déposez un fichier ici ou cliquez'
                    , replace: 'Glissez-déposez un fichier ou cliquez pour remplacer'
                    , remove: 'Supprimer'
                    , error: 'Désolé, le fichier trop volumineux'
                }
            });
            // Used events
            var drEvent = $('#input-file-events').dropify();
            drEvent.on('dropify.beforeClear', function (event, element) {
                return confirm("Do you really want to delete \"" + element.file.name + "\" ?");
            });
            drEvent.on('dropify.afterClear', function (event, element) {
                alert('File deleted');
            });
            drEvent.on('dropify.errors', function (event, element) {
                console.log('Has Errors');
            });
            var drDestroy = $('#input-file-to-destroy').dropify();
            drDestroy = drDestroy.data('dropify')
            $('#toggleDropify').on('click', function (e) {
                e.preventDefault();
                if (drDestroy.isDropified()) {
                    drDestroy.destroy();
                } else {
                    drDestroy.init();
                }
            })
        });
    </script>
@endsection
