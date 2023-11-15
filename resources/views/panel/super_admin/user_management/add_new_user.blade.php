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
        <form method="post" action="{{route('super_admin.add_new_user_level_category')}}">
            @csrf
            <input type="hidden" name="user_id" value="0" id="user_id">
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
                <label class="col-sm-12">لاین</label>
                <div class="input-group col-md-12">
                    <select class="form-control" name="line" id="line">
                        <option value="0" disabled selected>انتخاب کنید</option>
                        @foreach($lines as $line)
                            <option value="{{$line->id}}">{{$line->title}}</option>
                        @endforeach
                    </select>
                </div>
            </div>


            <div class="form-group" id="category_containner">
                <label class="col-sm-12">دسته بندی</label>
                <div class="input-group col-md-12">
                    <select class="form-control" name="category" id="category">
                        <option value="0" disabled selected>انتخاب کنید</option>
                    </select>
                </div>
            </div>

            <div class="form-group" id="master_container">
                <label class="col-sm-12">مستر</label>
                <div class="input-group col-md-12">
                    <select class="form-control" name="master" id="master">
                        <option value="0" disabled selected>انتخاب کنید</option>
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
@endsection

@section('script')
    <script>
        $(document).ready(function () {
            $("#category_containner").hide(1);
            $("#master_container").hide(1);



            $("#btnGetMasterUserInfo").click(function () {
                const email = $("#master_email").val();
                let url = "{{route('user_info', ['email' => '?email'])}}";
                url = url.replace('?email', email);
                $.ajax(url, {
                    dataType: 'json',
                    success: function (data) {
                        if (data.id > 0) {
                            $("#master_id").val(data.id);
                            alert("ایمیل وارد شده مربوط به کاربر " + data.name + " می باشد.");
                        } else {
                            alert("کاربر با این مشخصات ثبت نشده است");
                        }
                    },
                    error: function () {
                        alert("کاربر با این مشخصات ثبت نشده است");
                    }
                });
            })

            $("#line").change(function () {
                getCategories($(this).val())
            })
            $("#category").change(function () {
                getMastersOfCategory($(this).val())
            })
        });

        function getCategories(lineId) {
            $("#category_containner").slideUp(1);

            let url = "{{route('categories_by_line_id', ['line_id' => '?line'])}}";
            url = url.replace('?line', lineId);
            $.ajax(url, {
                dataType: 'json',
                success: function (data) {
                    $("#category").find('option').not(':first').remove();
                    for (const item in data) {
                        $("#category").append("<option value='" + data[item].id + "'>" + data[item].title + "</option>");
                    }
                    $("#category").prop("selectedIndex", 0);

                    $("#category_containner").slideDown();
                },
                error: function () {
                    $("#category_containner").slideDown();
                }
            });
        }

        function getMastersOfCategory(categoryId) {
            $("#master_container").slideUp(1);

            let url = "{{route('get_master_of_category', ['categoryId' => '?id'])}}";
            url = url.replace('?id', categoryId);
            $.ajax(url, {
                dataType: 'json',
                success: function (data) {
                    $("#master").find('option').not(':first').remove();
                    for (const item in data) {
                        $("#master").append("<option value='" + data[item].user_level_category_id + "'>" + data[item].user_name + " (" + data[item].level_title + ")" + "</option>");
                    }
                    $("#master").prop("selectedIndex", 0);

                    $("#master_container").slideDown();
                },
                error: function () {
                    $("#master_container").slideDown();
                }
            });
        }
    </script>
@endsection
