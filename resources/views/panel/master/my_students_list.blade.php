@extends("panel.layout.layout.main")
@section("content")
<div class="white-box">
    <div class="row">
        <div class="col-lg-2 col-sm-4 col-xs-4">
            <a type="button" class="btn btn-primary btn-circle btn-sm" href="{{route('master.level_category.current_master_level_categories', ["userLevelCategoryParentId" => $parentUserLevelCategoryId])}}">
                <i class="fa fa-list"></i> 
            </a>        
        </div>
    </div>
</div>
</div>
    <div class="white-box">
        <h3 class="box-title">تغییر سطح هنرجو</h3>
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
        <form method="post"
              action="{{route('user_level_category.master.my_student.sample_work.set_user_level_without_category_id')}}">
            @csrf
            <input type="hidden" name="user_id" value="0" id="user_id">
            <input type="hidden" name="parent_user_level_category_id" value="{{$parentUserLevelCategoryId}}"
                   id="parent_user_level_category_id">
            <div class="form-group">
                <label class="col-sm-12">کاربر</label>
                <div class="input-group">
                    <input type="email" id="email" name="email" class="form-control"
                           placeholder="@lang('general.email')"/>
                    <span class="input-group-btn">
                    <button type="button" id="btnGetUserInfo" class="btn waves-effect waves-light btn-info"><i
                            class="fa fa-search"></i></button>
                </span>
                </div>
            </div>


            <div class="form-group">
                <label class="col-sm-12">@lang('general.level')</label>
                <div class="input-group col-md-12">
                    <select class="form-control" name="level_id" id="level_id">
                        <option value="0" disabled selected>انتخاب کنید</option>
                        @foreach($allowedLevels as $allowedLevel)
                            <option value="{{$allowedLevel->key}}">{{$allowedLevel->title}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group">
                <button type="submit" class="form-control btn btn-success waves-effect waves-light m-r-10">
                    <i class="fa fa-save"></i>
                    ذخیره
                </button>
            </div>

        </form>
    </div>
    <div class="white-box">
        <h3 class="box-title">لیست هنرجو های شما</h3>

        <div class="table-responsive">
            <table class="table color-table inverse-table">
                <thead>
                <tr>
                    <th>شناسه</th>
                    <th>نام و نام خانوادگی</th>
                    <th>ایمیل</th>
                    <th>سطح</th>
                    <th>عملیات</th>
                </tr>
                </thead>
                <tbody>
                <form
                    action="{{route('user_level_category.master.my_student', ["userLevelCategoryParentId"=>$parentUserLevelCategoryId])}}">
                    <tr>
                        <td><input class="form-control" type="number" name="filter_id" value="{{$filter['id']}}"/></td>
                        <td><input class="form-control" type="text" name="filter_name" value="{{$filter['name']}}"/></td>
                        <td><input class="form-control" type="text" name="filter_email" value="{{$filter['email']}}"/></td>
                        <td>
                            <select class="form-control" name="filter_level" id="filter_level">
                                <option value="0" selected>همه</option>
                                @foreach($allowedLevels as $allowedLevel)
                                    <option value="{{$allowedLevel->key}}" @if($filter['level'] == $allowedLevel->key) selected @endif>{{$allowedLevel->title}}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <button type="submit" class="btn btn-rounded btn-default"><i class="fa fa-search"></i>
                            </button>
                        </td>
                    </tr>
                </form>
                @foreach($students as $student)
                    <tr>
                        <td>{{$student->id}}</td>
                        <td>{{$student->name}}</td>
                        <td>{{$student->email}}</td>
                        <td>{{$student->level_title}}</td>
                        <td>
                            @if($student->level_order == 1)
                                <a href="{{route('user_level_category.master.my_student.sample_work_lesson_list', ["userLevelCategoryId"=>$student->user_level_category_child_id])}}"
                                   class="btn btn-primary">نمونه کارها</a>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="dataTables_paginate paging_simple_numbers" id="example23_paginate">
                {{$students->links("pagination::bootstrap-4")}}
            </div>
        </div>
    </div>
@endsection
