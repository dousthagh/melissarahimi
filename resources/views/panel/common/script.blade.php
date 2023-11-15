<script>
    $("#btnGetUserInfo").click(function () {
        const email = $("#email").val();
        let url = "{{route('user_info', ['email' => '?email'])}}";
        url = url.replace('?email', email);
        $.ajax(url, {
            dataType: 'json',
            success: function (data) {
                if (data.id > 0) {
                    $("#user_id").val(data.id);
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
</script>
