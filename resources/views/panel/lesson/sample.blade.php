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
    

@foreach ($samples as $sample)
    
            <div class="panel panel-default" style="width: 100%">
                <div class="panel-heading">
                    <span class="label @if($sample->status == 'new') label-primary @elseif($sample->status == 'accepted') label-success @else label-warning @endif m-l-5">
                        {{$sample->status_title}}
                    </span>
                    <div class="pull-right"><a href="panels-wells.html#" data-perform="panel-collapse"><i class="ti-minus text-danger"></i></a> </div>
                </div>
                <div class="panel-wrapper collapse in" aria-expanded="true">
                    <div class="panel-body">
                        <div class="row">
                            <div class="media">
                                <div class="media-left">
                                    <a href="{{route('user_level_category.lesson.sample_work.image', ["id"=>$sample->id, "isThumbnail"=>0])}}"
                                        target="_blank">
                                         <img class="media-object"
                                              src="{{route('user_level_category.lesson.sample_work.image', ["id"=>$sample->id, "isThumbnail" => true])}}" data-holder-rendered="true" style="width: 64px; height: 64px;"/>
                                     </a>
                                </div>
                                <div class="media-body">
                                    <p class="media-heading">{{$sample->description}}</p>
                                </div>
                            </div>  
                            @if($sample->master_description != null)  
                                <div class="media bg-light">
                                    @if(!empty($sample->master_file_path))
                                        <div class="media-left">
                                            <a href="{{route('user_level_category.lesson.sample_work.master_image', ["id"=>$sample->id, "isThumbnail"=>0])}}"
                                                target="_blank">
                                                <img class="media-object"
                                                    src="{{route('user_level_category.lesson.sample_work.master_image', ["id"=>$sample->id, "isThumbnail" => true])}}" data-holder-rendered="true" style="width: 64px; height: 64px;"/>
                                            </a>
                                        </div>
                                    @endif
                                    <div class="media-body">
                                        <p class="media-heading">{{$sample->master_description}}</p>
                                    </div>
                                </div>                 
                            @endif
                        </div>
                    </div>
                    @if($sample->status == 'new' && $sample->master_user_id == auth()->id())
                        @php($sampleWorkId = $sample->id)
                        <div class="panel-footer">
                        <div class="row">
                            <div class="col-lg-12">
                                <button type="button" data-toggle="modal" data-target="#comment-modal"
                                                        class="model_img btn btn-primary btn-rounded bold pull-left waves-effect waves-light">
                                                    <i class="fa fa-eye"></i>
                                                    اعمال نظر
                                                </button>
                            </div>
                        </div>
                        
                        </div>
                    @endif

                </div>
            </div>

    
            @endforeach

    @endsection

@section('script')
    <script>
        $(document).ready(function () {
            $("#file-container").hide();

            $("#status").change(function(){
                var value=$(this).val()
                if(value == "rejected"){
                    $("#file-container").show();
                }
                else{
                    $("#file-container").hide();
                }
            });
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
                      method="post" enctype="multipart/form-data">
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
                            <select name="status" class="form-control" id="status">
                                <option selected disabled value="-1">انتخاب کنید</option>
                                <option value="accepted">
                                    تایید
                                </option>
                                <option value="rejected">
                                    نیاز به ارسال مجدد
                                </option>
                            </select>
                        </div>
                        <div class="form-group" id="file-container">
                            <label class="col-sm-12">انتخاب فایل</label>
                            <div class="col-sm-12">
                                <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                    <input type="file" name="file" id="input-file-now"
                                           class="form-control dropify" accept=".jpg, .jpeg, .png"/>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="message-text" class="control-label">توضیحات:
                                <span class="text-danger">*</span>
                            </label>
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
