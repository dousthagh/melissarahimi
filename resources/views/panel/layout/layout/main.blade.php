<!DOCTYPE html>
<!--
   This is a starter template page. Use this page to start your new project from
   scratch. This page gets rid of all links and provides the needed markup only.
   -->
<html lang="en" dir="rtl">

@include('panel.layout.partials.header')

<body class="fix-sidebar">
<!-- Preloader -->
<div class="preloader">
    <svg class="circular" viewBox="25 25 50 50">
        <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/>
    </svg>
</div>
<div id="wrapper">

    @include('panel.layout.partials.top_navigation')

    @include('panel.layout.partials.left_sidebar')


    <div id="page-wrapper">

        <div class="container-fluid">
            <!-- .row -->
            <div class="row">
                <div class="col-md-12">
                    @yield('content')
                </div>
            </div>
            <!-- .row -->

        </div>

        @include('panel.layout.partials.page_footer')

    </div>
    @yield('modal')


    @include('panel.layout.partials.scripts')
    @include('panel.common.script')

</div>
</body>

</html>
<script>

$(".en-input").keypress(function(event){
    alert();
        if ((event.charCode >= 48 && event.charCode <= 57) || // 0-9
            (event.charCode >= 65 && event.charCode <= 90) || // A-Z
            (event.charCode >= 97 && event.charCode <= 122))  // a-z
            alert("0-9, a-z or A-Z");
    });


    </script>
