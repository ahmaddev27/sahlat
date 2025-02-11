<!DOCTYPE html>
<html class="loading dark-layout" lang="{{ app()->getLocale() }}">
<!-- BEGIN: Head-->

<head>
    @include('dashboard.layouts.css')

    <title> {{setting('name') }} | {{trans('auth.login')}}</title>

    <link rel="stylesheet" type="text/css" href="{{url('app-assets/css-rtl/pages/page-auth.css')}}">


</head>
<!-- END: Head-->
<nav class="header-navbar navbar navbar-expand-lg align-items-center floating-nav" style="background: #FFF0">
    <div class="navbar-container d-flex content">
        <div class="bookmark-wrapper d-flex align-items-center">
{{--            <ul class="nav navbar-nav d-xl-none">--}}
{{--                <li class="nav-item"><a class="nav-link menu-toggle" href="javascript:void(0);"><i class="ficon"--}}
{{--                                                                                                   data-feather="menu"></i></a>--}}
{{--                </li>--}}
{{--            </ul>--}}


        </div>
        <ul class="nav navbar-nav align-items-center ml-auto">

            <li class="nav-item  d-lg-block">
                <a class="nav-link" id="language-toggle">
                    <i class="fa fa-language fa-2x"></i></a>
            </li>

            <li class="nav-item d-lg-block"><a class="nav-link nav-link-style">
                    <i class="ficon" data-feather="moon"></i></a>
            </li>


        </ul>
    </div>
</nav>

<!-- BEGIN: Body-->
<body class="vertical-layout vertical-menu-modern blank-page navbar-floating footer-static  menu-collapsed"
      data-open="click" data-menu="vertical-menu-modern" data-col="blank-page">
<!-- BEGIN: Content-->
<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row ">


        </div>


        <div class="content-body">
            <div class="auth-wrapper auth-v2">
                <div class="auth-inner row m-0">
                    <!-- Brand logo-->

                    <!-- /Brand logo-->
                    <!-- Left Text-->
                    <div class="d-none d-lg-flex col-lg-8 align-items-center p-5">


                        <div class="w-100 d-lg-flex align-items-center justify-content-center px-5">

                            <img class="img-fluid"  style="width: 700px;" src="{{url('storage/'.setting('logo'))}}"
                                 alt="{{setting('name')}}"/></div>
                    </div>
                    <!-- /Left Text-->
                    <!-- Login-->
                    <div class="d-flex col-lg-4 align-items-center  px-2 p-lg-5">
                        <div class="col-12 col-sm-8 col-md-6 col-lg-12 px-xl-2 mx-auto">

                            <h2 class="card-title font-weight-bold mb-1">{{trans('auth.welcome')}} {{setting('name')}}! 👋</h2>
                            <div id="loginError" class="text-danger"></div>

                            <!-- Login Form -->
                            <form class="auth-login-form mt-2" id="loginForm" method="POST">
                                @csrf

                                <div class="form-group mb-2 justify-content-center">


                                    <div class="custom-control custom-switch custom-switch-primary">


                                        <b class="mb-50 text-danger mx-1" style="font-family: Tajawal;">{{trans('auth.login_company')}}</b>


                                        <input type="checkbox" name="company" class="custom-control-input " id="customSwitch15">
                                        <label class="custom-control-label" for="customSwitch15">
                                        <span class="switch-icon-left">
                                          <i data-feather='check'></i>

                                        </span>

                                            <span class="switch-icon-right">
                                            <i data-feather='x'></i>
                                        </span>
                                        </label>
                                    </div>
                                </div>



                                <div class="form-group mb-2">
                                    <input class="form-control" id="email" type="text" name="email"
                                           placeholder="{{trans('auth.email')}}" aria-describedby="email" autofocus=""
                                           tabindex="1"/>
                                    <span class="text-danger mt-2" id="emailError"></span>
                                </div>
                                <div class="form-group mb-2">
                                    <div class="input-group input-group-merge form-password-toggle">
                                        <input class="form-control form-control-merge" id="password" type="password"
                                               name="password" placeholder="{{trans('auth.password_filed')}}"
                                               aria-describedby="password" tabindex="2"/>
                                        <div class="input-group-append"><span class="input-group-text cursor-pointer"><i
                                                    data-feather="eye"></i></span></div>
                                    </div>
                                    <span class="text-danger mt-2" id="passwordError"></span>
                                </div>

                                <div class="form-group mb-2">

                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox"  name="remember" class="custom-control-input" id="customCheck1" >
                                        <label class="custom-control-label" for="customCheck1"> {{trans('auth.remember')}}</label>
                                    </div>


                        </div>

                                <button class="btn btn-primary btn-block" id="submit"
                                        tabindex="4">{{trans('auth.button')}}</button>

                            </form>

                        </div>
                    </div>
                    <!-- /Login-->
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END: Content-->


@include('dashboard.layouts.js')

<script>
    $('#loginForm').on('submit', function(e) {
        e.preventDefault();

        // Clear previous error messages
        $('#emailError').text('');
        $('#passwordError').text('');
        $('#loginError').text(''); // New line to clear generic login error

        // Get form data
        var formData = {
            email: $('#email').val(),
            password: $('#password').val(),
            _token: '{{ csrf_token() }}',
            company: $('#customSwitch15').is(':checked') ? 1 : 0,
            remember: $('#customCheck1').is(':checked') ? 1 : null
        };

        // AJAX request
        $.ajax({
            url: '{{ route("login") }}',
            type: 'POST',
            data: formData,
            success: function(response) {
                toastr.success(response.message, '{{ trans('messages.success') }}');
                setTimeout(function() {
                    const redirectUrl = formData.company ? '/company' : '/admin';
                    window.location.href = redirectUrl;
                }, 700);
            },
            error: function(xhr) {
                toastr.error('{{ trans('messages.error') }}', '{{ trans('messages.error') }}');

                if (xhr.responseJSON) {
                    var errors = xhr.responseJSON.errors;

                    // Show validation errors
                    if (errors.email) {
                        $('#emailError').text(errors.email[0]);
                    }
                    if (errors.password) {
                        $('#passwordError').text(errors.password[0]);
                    }
                    if (errors.login) {
                        $('#loginError').text(errors.login); // Display generic login error
                    }
                }
            }
        });
    });

</script>


</body>
<!-- END: Body-->

</html>
