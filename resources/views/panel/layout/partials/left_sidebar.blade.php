<!-- ============================================================== -->
<!-- Left Sidebar - style you can find in sidebar.scss  -->
<!-- ============================================================== -->

<div class="navbar-default sidebar" role="navigation">
        <div class="sidebar-nav slimscrollsidebar">
            <div class="sidebar-head">
                <h3><span class="fa-fw open-close"><i class="ti-menu hidden-xs"></i><i
                            class="ti-close visible-xs"></i></span>
                    <span class="hide-menu">@lang('menu.title')</span>
                </h3>
            </div>
            <ul class="nav" id="side-menu">
                <li><a href="#" class="waves-effect"><i class="mdi mdi-av-timer fa-user" data-icon="v"></i> <span
                            class="hide-menu"> {{ \Illuminate\Support\Facades\Auth::user()->name }} <p
                                class="text-muted">
                                {{ \Illuminate\Support\Facades\Auth::user()->email }}</p> <span
                                class="fa arrow"></span></span></a>
                    <ul class="nav nav-second-level">
                        <li><a href="{{ route('logout') }}"><i class="fa fa-power-off"></i> خروج</a></li>
                    </ul>
                </li>

                @if (auth()->user()->hasRole('SuperAdmin'))
                    <li><a href="#" class="waves-effect"><i class="mdi mdi-av-timer fa-fw" data-icon="v"></i>
                            <span class="hide-menu"> @lang('menu.super_admin') <span class="fa arrow"></span></span></a>
                        <ul class="nav nav-second-level">
                            <li><a href="{{ route('super_admin.add_new_user') }}"><span
                                        class="hide-menu">@lang('menu.add_new_user')</span></a></li>
                            <li><a href="{{ route('super_admin.level_category.index') }}"><span
                                        class="hide-menu">@lang('menu.level_categories')</span></a></li>
                            <li><a href="{{ route('super_admin.master.list') }}"><span
                                        class="hide-menu">@lang('menu.master_list')</span></a></li>
                            <li><a href="{{ route('super_admin.setting') }}"><span
                                        class="hide-menu">@lang('menu.setting')</span></a></li>
                        </ul>
                    </li>
                @endif

                @foreach (GetUserCategoriesWithLevel(auth()->user()) as $key => $value)
                    <li>
                        <a href="#" class="waves-effect"><i class="mdi mdi-account-star fa-fw" data-icon="v"></i>
                            <span class="hide-menu"> {{ $value['level']['title'] }} <span
                                    class="fa arrow"></span></span></a>
                        @foreach ($value['categories'] as $categoryKey => $categoryValue)
                            <ul class="nav nav-second-level">
                                <li>
                                    <a
                                        href="
                                        @if ($value['level']['order'] == 5 || $value['level']['order'] == 6) {{ route('user_level_category.master.my_student', ['userLevelCategoryParentId' => $categoryValue['user_level_category_id']]) }}
                                            @else
                                                {{ route('user_level_category.lesson.list', ['user_level_category_id' => $categoryValue['user_level_category_id']]) }} @endif
                                    "><span
                                            class="hide-menu">{{ $categoryValue['title'] }}</span></a>
                                </li>
                            </ul>
                        @endforeach
                    </li>
                @endforeach

            </ul>
        </div>
</div>
<!-- Left navbar-header end -->
