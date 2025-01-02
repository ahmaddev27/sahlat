<!-- BEGIN: Main Menu-->
<div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true">
    <div class="navbar-header">
        <ul class="nav navbar-nav flex-row">
            <li class="nav-item mr-auto">
                <a class="navbar-brand" href="{{route('company.home')}}">
                    <span class="brand-logo">
                        <img style="max-width: 182px" src="{{url('storage/'. setting('logo'))}}">
                    </span>
{{--                    <h1 class="brand-text">{{setting('name')}}</h1>--}}
                </a>
            </li>
            <li class="nav-item nav-toggle"><a class="nav-link modern-nav-toggle pr-0" data-toggle="collapse"><i
                        class="d-block d-xl-none text-primary toggle-icon font-medium-4" data-feather="x"></i><i
                        class="d-none d-xl-block collapse-toggle-icon font-medium-4  text-primary" data-feather="disc"
                        data-ticon="disc"></i></a></li>
        </ul>
    </div>
    <div class="shadow-bottom"></div>

    <div class="main-menu-content">
        <ul class="navigation navigation-main mt-1" id="main-menu-navigation" data-menu="menu-navigation">

            <li class=" nav-item {{ request()->routeIs('company.home') ? 'active':''}}">
                <a class="d-flex align-items-center" href="{{route('company.home')}}">
                    <i data-feather="home"></i>
                    <span class="menu-title text-truncate"
                          data-i18n="home">{{trans('dashboard_aside.dashboard')}}</span>
                </a>
            </li>


            <li class=" nav-item {{ request()->routeIs('company.services.index')?'active':''}}">
                <a class="d-flex align-items-center" href="{{route('company.services.index')}}">
                    <i data-feather="heart"></i>
                    <span class="menu-title text-truncate"
                          data-i18n="home">{{trans('dashboard_aside.services')}}</span>
                </a>
            </li>



            <li class=" nav-item has-sub sidebar-group-{{ request()->routeIs('company.housekeepers.*')?'active open':''}}">

                <a class="d-flex align-items-center" href="#">
                    <i data-feather='home'></i><span
                        class="menu-title text-truncate"
                        data-i18n="Email">{{trans('dashboard_aside.housekeepers')}}</span>
                </a>

                <ul class="menu-content">
                    <li class="{{ request()->routeIs('company.housekeepers.index') ||  request()->routeIs('company.housekeepers.view')?'active':''}}">
                        <a class="d-flex align-items-center " href="{{route('company.housekeepers.index')}}">
                            <i data-feather="circle"></i>
                            <span class="menu-item text-truncate"
                                  data-i18n="Shop">{{trans('dashboard_aside.housekeepers')}}</span></a>
                    </li>


                    <li class="{{ request()->routeIs('company.housekeepers.orders.index') || request()->routeIs('company.housekeepers.orders.view')? 'active':''}}">
                        <a class="d-flex align-items-center " href="{{route('company.housekeepers.orders.index')}}">
                            <i data-feather="circle"></i>
                            <span class="menu-item text-truncate"
                                  data-i18n="Shop">{{trans('dashboard_aside.housekeepers-orders')}}</span></a>
                    </li>

                    <li class="{{ request()->routeIs('company.housekeepers.HourlyOrders.*')?'active':''}}">
                        <a class="d-flex align-items-center " href="{{route('company.housekeepers.HourlyOrders.index')}}">
                            <i data-feather="circle"></i>
                            <span class="menu-item text-truncate"
                                  data-i18n="Shop">{{trans('dashboard_aside.housekeepers-HourlyOrders')}}</span></a>
                    </li>

                </ul>



            </li>


        </ul>
    </div>
</div>
<!-- END: Main Menu-->
