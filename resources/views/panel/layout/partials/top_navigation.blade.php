<!-- Top Navigation -->
<nav class="navbar navbar-default navbar-static-top m-b-0">
    <div class="navbar-header">
        <div class="top-left-part">
            <!-- Logo -->
            <a class="logo" href="{{route('panel.dashboard')}}">
                <!-- Logo icon image, you can use font-icon also --><b>
                    <!--This is dark logo icon--><img src="{{route('logo')}}" alt="home"
                                                      class="dark-logo"/><!--This is light logo icon--><img
                        src="{{route('logo')}}" alt="home" class="light-logo"/></a>
        </div>
        <!-- /Logo -->
        <!-- Search input and Toggle icon -->
        @php($newMessages= GetUserUnreadMessages())
        <ul class="nav navbar-top-links navbar-left">
            <li><a href="javascript:void(0)" class="open-close waves-effect waves-light visible-xs"><i
                        class="ti-close ti-menu"></i></a></li>
            <li class="dropdown">
                <a class="dropdown-toggle waves-effect waves-light" data-toggle="dropdown" href="index.html#"> <i
                        class="mdi mdi-gmail"></i>
                    @if($newMessages != null && count($newMessages) > 0)
                    <div class="notify">
                            <span class="heartbit"></span>
                            <span class="point"></span>
                        </div>
                    @endif
                </a>
                <ul class="dropdown-menu mailbox animated bounceInDown">
                    <li>
                        <div
                            class="drop-title">
                            @if($newMessages)
                                {!! str_replace(":number", count($newMessages), __('general.have_new_message')) !!}
                            @else
                                شما پیام جدیدی ندارید
                            @endif
                        </div>
                    </li>
                    <li>
                        <div class="message-center">
                            @foreach($newMessages as $newMessage)
                                <a href="{{route('message.details', ["message_id" => $newMessage->id])}}">
                                    <div class="mail-contnet">
                                        <h5>{{$newMessage->title}}</h5>
                                        <span class="mail-desc">{{strip_tags($newMessage->content)}}</span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </li>
                    {{--                    <li>--}}
                    {{--                        <a class="text-center" href="javascript:void(0);"> <strong>دیدن همه اعلان ها</strong> <i--}}
                    {{--                                class="fa fa-angle-right"></i> </a>--}}
                    {{--                    </li>--}}
                </ul>
                <!-- /.dropdown-messages -->
            </li>
            <!-- .Task dropdown -->
        </ul>

    </div>
    <!-- /.navbar-header -->
    <!-- /.navbar-top-links -->
    <!-- /.navbar-static-side -->
</nav>
<!-- End Top Navigation -->
