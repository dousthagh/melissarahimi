@extends('panel.layout.layout.main')
@section('content')
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

    <div class="page-wrapper p-t-10">
        <div class="container-fluid">
            @if($canSendSampleWork)
                <div class="white-box">
                    <h3 class="box-title">ارسال نمونه کار</h3>
                    <form method="post" enctype="multipart/form-data"
                          action="{{route('user_level_category.lesson.sample_work.send')}}" class="floating-labels">
                        @csrf
                        <input type="hidden" value="{{$lessonId}}" name="lesson_id"/>
                        <input type="hidden" value="{{$userLevelCategoryId}}" name="user_level_category_id"/>
                        <div class="row">
                            <div class="col-lg-3 form-group">
                                <input type="file" required name="file" id="input-file-now"
                                       class="form-control dropify"/></div>
                            <div class="col-lg-7 form-group">
                                <textarea class="form-control" rows="4" id="input7" name="description"></textarea><span
                                    class="highlight"></span> <span class="bar"></span>
                                <label for="input7">توضیحات</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <div class="col-lg-12">
                                    <button type="submit" class="pull-right btn btn-success " style="width: 100%" >ارسال</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            @endif
<hr/>
            @foreach($samples as $sample)
                <div class="row">
                    <div class="col-lg-8 col-sm-4">
                        <div
                            class="panel @if($sample->status == 'new') panel-primary @elseif($sample->status == 'accepted') panel-success @else panel-warning @endif">
                            <div class="panel-heading">
                                {{$sample->status_title}}
                            </div>
                            <div class="panel-wrapper collapse in" aria-expanded="true">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <a href="{{route('user_level_category.lesson.sample_work.image', ["id"=>$sample->id, "isThumbnail"=>0])}}"
                                               target="_blank">
                                                <img class="img img-responsive img-rounded"
                                                     src="{{route('user_level_category.lesson.sample_work.image', ["id"=>$sample->id, "isThumbnail" => true])}}"/>
                                            </a>
                                        </div>
                                        <div class="col-lg-8">
                                            <p>
                                                {{$sample->description}}
                                            </p>
                                        </div>
                                    </div>
                                    @if($sample->status == 'new' && $sample->master_user_id == auth()->id())
                                        @php($sampleWorkId = $sample->id)
                                        <div class="row">
                                            <div class="col-lg-12">

                                                <button type="button" data-toggle="modal" data-target="#comment-modal"
                                                        class="model_img btn btn-rounded btn-default bold text-danger pull-right">
                                                    <i class="fa fa-eye"></i>
                                                    اعمال نظر
                                                </button>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                @if($sample->master_description != null)
                                    <div class="panel-footer">
                                        <p>
                                            {!! $sample->master_description !!}
                                        </p>
                                    </div>
                                @endif
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
@section('modal')
    <div id="comment-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">اعمال نظر</h4></div>
                <form action="{{route('user_level_category.master.my_student.sample_work.apply_comment')}}"
                      method="post">
                    @if(isset($sampleWorkId))
                        <input type="hidden" value="{{$sampleWorkId}}" name="sample_work_id"/>
                    @endif
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="recipient-name" class="control-label">
                                وضعیت:
                                <span class="text-danger">*</span>
                            </label>
                            <select name="status" class="form-control">
                                <option selected disabled value="-1">انتخاب کنید</option>
                                <option value="accepted">
                                    تایید
                                </option>
                                <option value="rejected">
                                    نیاز به ارسال مجدد
                                </option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="message-text" class="control-label">توضیحات:</label>
                            <textarea class="form-control" id="message-text" name="master_description"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success waves-effect waves-light" style="width:100%">اعمال</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection
