<!DOCTYPE html>
<html class="loading dark-layout"  lang="{{ app()->getLocale() }}">
<!-- BEGIN: Head-->

<head>



    @include('dashboard.layouts.css')


    <title>{{setting('name') }}  | {{ @$title  }}</title>
    <!-- END: Custom CSS-->
    <meta name="csrf-token" content="{{ csrf_token() }}">

</head>
<!-- END: Head-->

<!-- BEGIN: Body-->

<body class="vertical-layout vertical-menu-modern  navbar-floating footer-static" data-open="click" data-menu="vertical-menu-modern" data-col="">

<!-- BEGIN: Header-->

@include('dashboard.layouts.nav')
<!-- END: Header-->


<!-- BEGIN: Main Menu-->
@include('dashboard.layouts.side')
<!-- END: Main Menu-->

<!-- BEGIN: Content-->
<div class="app-content content ecommerce-application ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row"></div>
        <div class="content-body">
            <!-- Dashboard Ecommerce Starts -->
            <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-left mb-0">{{setting('name')}}</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{route('admin.home')}}">{{trans('dashboard_aside.dashboard')}}</a></li>

                                  @if(!request()->routeIs('admin.home'))

                                        @yield('path')

                                        <li class="breadcrumb-item active"><a href="#">{{$title}} </a></li> @endif


                                </ol>
                            </div>


                        </div>
                    </div>
                </div>




                @yield('left')

                   @yield('right')
            </div>


            @yield('content')

            <!-- Dashboard Ecommerce ends -->

        </div>
    </div>
</div>
<!-- END: Content-->


<div class="sidenav-overlay"></div>
<div class="drag-target"></div>

<!-- BEGIN: Footer-->


@include('dashboard.layouts.footer')
<button class="btn btn-primary btn-icon scroll-top" type="button"><i data-feather="arrow-up"></i></button>
<!-- END: Footer-->



@include('dashboard.layouts.js')


@if(request()->routeIs('admin.home'))

@endif


</body>
<!-- END: Body-->

</html>
