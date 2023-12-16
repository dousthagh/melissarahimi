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

    <div id="page-wrapper">

        <div class="container-fluid ">
            <!-- .row -->
            <div class="row">
                <div class="col-md-12">
                    <div class="m-t-10"></div>
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
