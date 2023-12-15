<!DOCTYPE html>
<html lang="en" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/png" sizes="16x16" href="{{route('logo')}}">
    <title>@lang('general.page_title')</title>
    <!-- Bootstrap Core CSS -->
    <link href="{{asset('panel/lib/bootstrap-rtl-master/dist/css/bootstrap-rtl.min.css')}}" rel="stylesheet">
    <!-- animation CSS -->
    <link href="{{asset('panel/css/animate.css')}}" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{asset('panel/css/newstyle.css')}}" rel="stylesheet">
    <!-- color CSS -->
    {{-- <link href="{{asset('panel/css/colors/blue.css')}}" id="theme"  rel="stylesheet"> --}}
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<!-- Preloader -->
<div class="preloader">
    <div class="cssload-speeding-wheel"></div>
</div>
<section id="wrapper" class="login-register" >
    <div class="login-box login-sidebar">
        <div class="white-box bg-dark">
            @yield('form')
        </div>
    </div>
</section>
<!-- jQuery -->
<script src="{{asset('panel/lib/jquery/dist/jquery.min.js')}}"></script>
<!-- Bootstrap Core JavaScript -->
<script src="{{asset('panel/lib/bootstrap-rtl-master/dist/js/bootstrap-rtl.min.js')}}"></script>
<!-- Menu Plugin JavaScript -->
<script src="{{asset('panel/lib/sidebar-nav/dist/sidebar-nav.min.js')}}"></script>

<!--slimscroll JavaScript -->
<script src="{{asset('panel/js/jquery.slimscroll.js')}}"></script>
<!--Wave Effects -->
<script src="{{asset('panel/js/waves.js')}}"></script>
<!-- Custom Theme JavaScript -->
<script src="{{asset('panel/js/custom.min.js')}}"></script>
<!--Style Switcher -->
<script src="{{asset('panel/lib/styleswitcher/jQuery.style.switcher.js')}}"></script>
</body>


<script>

    $(".en-input").keypress(function(event){
            if ((event.charCode >= 65 && event.charCode <= 90) || // A-Z
                (event.charCode >= 97 && event.charCode <= 122)) {
                    return true
                }
                else{
                    alert("شما تنها مجاز به وارد کردن حروف انگلیسی می باشید")
                    return false
                }
        });
</script>

</html>


