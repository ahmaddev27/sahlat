<!-- BEGIN: Main Menu-->
<div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true">
    <div class="navbar-header">
        <ul class="nav navbar-nav flex-row">
            <li class="nav-item mr-auto">
                <a class="navbar-brand" href="{{route('admin.home')}}">
                    <span class="brand-logo">
                        <img style="max-width: 182px" src="{{url('storage/'. setting('logo'))}}">
                    </span>
                    {{--                    <h2 class="brand-text">{{setting('name')}}</h2>--}}
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

            <li class=" nav-item {{ request()->routeIs('admin.home')?'active':''}}">
                <a class="d-flex align-items-center" href="{{route('admin.home')}}">
                    <i data-feather="home"></i>
                    <span class="menu-title text-truncate"
                          data-i18n="home">{{trans('dashboard_aside.dashboard')}}</span>
                </a>

            </li>


            <li class="nav-item {{ request()->routeIs('users.index') || request()->routeIs('users.view')?'active':''}} mt-2">
                <a class="d-flex align-items-center" href="{{route('users.index')}}">
                    <i data-feather='users'></i>
                    <span
                        class="menu-title text-truncate">{{trans('dashboard_aside.users')}}</span></a>
            </li>


            <li class="nav-item {{ request()->routeIs('companies*')?'active':''}}">
                <a class="d-flex align-items-center" href="{{route('companies.index')}}">
                    <i data-feather='briefcase'></i>
                    <span
                        class="menu-title text-truncate" data-i18n="Email">{{trans('dashboard_aside.companies')}}</span></a>
            </li>


            <li class=" nav-item has-sub sidebar-group-{{ request()->routeIs('assurances.*')?'active open':''}}">

                <a class="d-flex align-items-center" href="#">
                    <i data-feather="heart"></i><span
                        class="menu-title text-truncate"
                        data-i18n="Email">{{trans('dashboard_aside.assurances')}}</span>
                </a>


                <ul class="menu-content">
                    <li class="{{ request()->routeIs('assurances.index')?'active':''}}">
                        <a class="d-flex align-items-center " href="{{route('assurances.index')}}">
                            <i data-feather="circle"></i>
                            <span class="menu-item text-truncate"
                                  data-i18n="Shop">{{trans('dashboard_aside.assurances')}}</span></a>
                    </li>


                    {{--                    <li class="{{ request()->routeIs('assurances.orders.index')||request()->routeIs('assurances.orders.view') ?'active':''}}">--}}
                    {{--                        <a class="d-flex align-items-center" href="{{route('assurances.orders.index')}}">--}}
                    {{--                            <i data-feather="circle"></i>--}}
                    {{--                            <span class="menu-item text-truncate" data-i18n="Shop">{{trans('dashboard_aside.assurances-orders')}}</span></a>--}}
                    {{--                    </li>--}}


                    <li class="{{ request()->routeIs('assurances.orders.index')?'active':''}}">
                        <a class="d-flex align-items-center" href="{{route('assurances.orders.index')}}">
                            <i data-feather="circle"></i>
                            <span class="menu-item text-truncate" data-i18n="Shop">{{StatusesAssurance(0)}}</span></a>
                    </li>


                    <li class="{{ request()->routeIs('assurances.orders.negotiation')?'active':''}}">
                        <a class="d-flex align-items-center" href="{{route('assurances.orders.negotiation')}}">
                            <i data-feather="circle"></i>
                            <span class="menu-item text-truncate" data-i18n="Shop">{{StatusesAssurance(1)}}</span></a>
                    </li>


                    <li class="{{ request()->routeIs('assurances.orders.under_payment')?'active':''}}">
                        <a class="d-flex align-items-center" href="{{route('assurances.orders.under_payment')}}">
                            <i data-feather="circle"></i>
                            <span class="menu-item text-truncate" data-i18n="Shop">{{StatusesAssurance(2)}}</span></a>
                    </li>


                    <li class="{{ request()->routeIs('assurances.orders.payment')?'active':''}}">
                        <a class="d-flex align-items-center" href="{{route('assurances.orders.payment')}}">
                            <i data-feather="circle"></i>
                            <span class="menu-item text-truncate" data-i18n="Shop">{{StatusesAssurance(3)}}</span></a>
                    </li>


                    <li class="{{ request()->routeIs('assurances.orders.complete*')?'active':''}}">
                        <a class="d-flex align-items-center " href="{{route('assurances.orders.completed')}}">
                            <i data-feather="circle"></i>
                            <span class="menu-item text-truncate"
                                  data-i18n="Shop">{{trans('dashboard_aside.orders-completed')}}</span></a>
                    </li>


                    <li class="{{ request()->routeIs('assurances.orders.cancelled*')?'active':''}}">
                        <a class="d-flex align-items-center " href="{{route('assurances.orders.cancelled')}}">
                            <i data-feather="circle"></i>
                            <span class="menu-item text-truncate"
                                  data-i18n="Shop">{{trans('dashboard_aside.orders-cancelled')}}</span></a>
                    </li>


                </ul>


            </li>


            <li class=" nav-item has-sub sidebar-group-{{ request()->routeIs('housekeepers.*')?'active open':''}}">

                <a class="d-flex align-items-center" href="#">
                    <i data-feather='home'></i><span
                        class="menu-title text-truncate"
                        data-i18n="Email">{{trans('dashboard_aside.housekeepers')}}</span>
                </a>


                <ul class="menu-content">
                    <li class="{{ request()->routeIs('housekeepers.index') ||  request()->routeIs('housekeepers.view')?'active':''}}">
                        <a class="d-flex align-items-center " href="{{route('housekeepers.index')}}">
                            <i data-feather="circle"></i>
                            <span class="menu-item text-truncate"
                                  data-i18n="Shop">{{trans('dashboard_aside.housekeepers')}}</span></a>
                    </li>


                    <li class="{{ request()->routeIs('housekeepers.orders.index') ?'active':''}}">
                        <a class="d-flex align-items-center " href="{{route('housekeepers.orders.index')}}">
                            <i data-feather="circle"></i>
                            <span class="menu-item text-truncate"
                                  data-i18n="Shop">{{trans('dashboard_aside.housekeepers-orders')}}</span></a>
                    </li>


                    <li class="{{ request()->routeIs('housekeepers.orders.active')||  request()->routeIs('housekeepers.orders.view') ?'active':''}}">
                        <a class="d-flex align-items-center " href="{{route('housekeepers.orders.active')}}">
                            <i data-feather="circle"></i>
                            <span class="menu-item text-truncate" data-i18n="Shop">{{HouseKeeperStatuses(3)}}</span></a>
                    </li>


                    <li class="{{ request()->routeIs('housekeepers.orders.complete')?'active':''}}">
                        <a class="d-flex align-items-center " href="{{route('housekeepers.orders.complete')}}">
                            <i data-feather="circle"></i>
                            <span class="menu-item text-truncate" data-i18n="Shop">{{HouseKeeperStatuses(4)}}</span></a>
                    </li>


                    <li class="{{ request()->routeIs('housekeepers.orders.expiring')?'active':''}}">
                        <a class="d-flex align-items-center " href="{{route('housekeepers.orders.expiring')}}">
                            <i data-feather="circle"></i>
                            <span class="menu-item text-truncate" data-i18n="Shop">{{trans('main.Expiring_Contracts')}}</span>
                            <span class="badge badge-light-warning badge-pill ml-auto">   {{houseKeeperExpiringOrdersCounts()}} </span>
                          </a>



                    </li>


                    <li class="{{ request()->routeIs('housekeepers.orders.close')?'active':''}}">
                        <a class="d-flex align-items-center " href="{{route('housekeepers.orders.close')}}">
                            <i data-feather="circle"></i>
                            <span class="menu-item text-truncate" data-i18n="Shop">{{HouseKeeperStatuses(5)}}</span></a>
                    </li>


                    <li class="{{ request()->routeIs('housekeepers.HourlyOrders.*')?'active':''}}">
                        <a class="d-flex align-items-center " href="{{route('housekeepers.HourlyOrders.index')}}">
                            <i data-feather="circle"></i>
                            <span class="menu-item text-truncate"
                                  data-i18n="Shop">{{trans('dashboard_aside.housekeepers-HourlyOrders')}}</span></a>
                    </li>

                </ul>


            </li>


            <li class=" nav-item has-sub sidebar-group-{{ request()->routeIs('violations.*')?'active open':''}}">

                <a class="d-flex align-items-center" href="#">
                    <i data-feather="alert-circle"></i><span
                        class="menu-title text-truncate"
                        data-i18n="Email">{{trans('dashboard_aside.violations')}}</span>
                </a>


                <ul class="menu-content">


                    <li class="{{ request()->routeIs('violations.index')||request()->routeIs('violations.view') ?'active':''}}">
                        <a class="d-flex align-items-center" href="{{route('violations.index')}}">
                            <i data-feather="circle"></i>
                            <span class="menu-item text-truncate"
                                  data-i18n="Shop">{{trans('dashboard_aside.violations-orders')}}</span></a>
                    </li>


                    <li class="{{ request()->routeIs('violations.under_payed*')?'active':''}}">
                        <a class="d-flex align-items-center" href="{{route('violations.under_payed')}}">
                            <i data-feather="circle"></i>
                            <span class="menu-item text-truncate" data-i18n="Shop">{{StatusesViolations(1)}}</span></a>
                    </li>


                    <li class="{{ request()->routeIs('violations.payed*')?'active':''}}">
                        <a class="d-flex align-items-center" href="{{route('violations.payed')}}">
                            <i data-feather="circle"></i>
                            <span class="menu-item text-truncate" data-i18n="Shop">{{StatusesViolations(2)}}</span></a>
                    </li>


                    <li class="{{ request()->routeIs('violations.complete*')?'active':''}}">
                        <a class="d-flex align-items-center " href="{{route('violations.completed')}}">
                            <i data-feather="circle"></i>
                            <span class="menu-item text-truncate"
                                  data-i18n="Shop">{{trans('dashboard_aside.orders-completed')}}</span></a>
                    </li>


                    <li class="{{ request()->routeIs('violations.cancelled*')?'active':''}}">
                        <a class="d-flex align-items-center " href="{{route('violations.cancelled')}}">
                            <i data-feather="circle"></i>
                            <span class="menu-item text-truncate"
                                  data-i18n="Shop">{{trans('dashboard_aside.orders-cancelled')}}</span></a>
                    </li>


                </ul>


            </li>


            <li class="nav-item {{ request()->routeIs('contacts*')?'active':''}} ">
                <a class="d-flex align-items-center" href="{{route('contacts.index')}}">
                    <i data-feather='inbox'></i>
                    <span
                        class="menu-title text-truncate">{{trans('dashboard_aside.contacts')}}</span>
                    <span class="badge badge-light-info badge-pill ml-auto"> {{NewContacts()}}</span>

                </a>
            </li>


            <li class=" nav-item has-sub sidebar-group-{{ request()->routeIs('settings.*')?'active open':''}}">

                <a class="d-flex align-items-center" href="#">
                    <i data-feather='settings'></i><span
                        class="menu-title text-truncate"
                        data-i18n="Email">{{trans('dashboard_aside.settings')}}</span>
                </a>


                <ul class="menu-content">
                    <li class="{{ request()->routeIs('settings.index')?'active':''}}">
                        <a class="d-flex align-items-center " href="{{route('settings.index')}}">
                            <i data-feather="circle"></i>
                            <span class="menu-item text-truncate"
                                  data-i18n="Shop">{{trans('dashboard_aside.settings')}}</span></a>
                    </li>


                    <li class="{{ request()->routeIs('settings.banner*')?'active':''}}">
                        <a class="d-flex align-items-center " href="{{route('settings.banner')}}">
                            <i data-feather="circle"></i>
                            <span class="menu-item text-truncate" data-i18n="Shop">{{trans('settings.banners')}}</span></a>
                    </li>

                </ul>


            </li>

        </ul>
    </div>
</div>
<!-- END: Main Menu-->