<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<meta name="description" content="Vuexy admin is super flexible, powerful, clean &amp; modern responsive bootstrap 4 admin template with unlimited possibilities.">
<meta name="keywords" content="admin template, Vuexy admin template, dashboard template, flat admin template, responsive admin template, web app">
<meta name="author" content="PIXINVENT">


<link rel="apple-touch-icon"  href="{{url('storage/'.setting('icon'))}}">
<link rel="shortcut icon" type="image/x-icon" href="{{url('storage/'.setting('icon'))}}">
<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400;1,500;1,600" rel="stylesheet">

<!-- BEGIN: Vendor CSS-->
<link rel="stylesheet" type="text/css" href="{{url('app-assets/vendors/css/charts/apexcharts.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('app-assets/vendors/css/extensions/toastr.min.css')}}">
<!-- END: Vendor CSS-->

<style>
    .dt-buttons{
        float: inline-end;
        padding: 9px;
    }
</style>
<link rel="stylesheet" href="{{url('app-assets/fonts/font-awesome/css/font-awesome.css')}}" />
<link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/pages/app-ecommerce-details.css')}}">

{{--<link rel="stylesheet" href="{{url('app-assets/fonts/tabler-icons.css')}}" />--}}
{{--<link rel="stylesheet" href="{{url('app-assets/fonts/flag-icon-css/css/flag-icons.css')}}"/>--}}


<link rel="stylesheet" type="text/css" href="{{url('app-assets/vendors/css/forms/select/select2.min.css')}}">



<link rel="stylesheet" type="text/css" href="{{url('app-assets/css-rtl/plugins/extensions/ext-component-sweet-alerts.css')}}">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@200;300;400;500;700;800;900&display=swap" rel="stylesheet">


@if(App::getLocale() == 'ar')
    <link rel="stylesheet" type="text/css" href="{{url('app-assets/vendors/css/vendors-rtl.min.css')}}">


    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="{{url('app-assets/css-rtl/bootstrap.css')}}">
    <link rel="stylesheet" type="text/css" href="{{url('app-assets/css-rtl/bootstrap-extended.css')}}">
    <link rel="stylesheet" type="text/css" href="{{url('app-assets/css-rtl/colors.css')}}">
    <link rel="stylesheet" type="text/css" href="{{url('app-assets/css-rtl/components.css')}}">
    <link rel="stylesheet" type="text/css" href="{{url('app-assets/css-rtl/themes/dark-layout.css')}}">
    <link rel="stylesheet" type="text/css" href="{{url('app-assets/css-rtl/themes/bordered-layout.css')}}">
    <link rel="stylesheet" type="text/css" href="{{url('app-assets/css-rtl/themes/semi-dark-layout.css')}}">

    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="{{url('app-assets/css-rtl/core/menu/menu-types/vertical-menu.css')}}">
    <link rel="stylesheet" type="text/css" href="{{url('app-assets/css-rtl/pages/dashboard-ecommerce.css')}}">
    <link rel="stylesheet" type="text/css" href="{{url('app-assets/css-rtl/plugins/charts/chart-apex.css')}}">
    <link rel="stylesheet" type="text/css" href="{{url('app-assets/css-rtl/plugins/extensions/ext-component-toastr.css')}}">
    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="{{url('app-assets/css-rtl/custom-rtl.css')}}">


    <link rel="stylesheet" type="text/css" href="{{url('assets/css/style-rtl.css')}}">

    <style>

        .toast-top-left {
            top: 12px;
            left: 20px;
            right: auto;
            bottom: auto;
        }

    </style>


@else


    <link rel="stylesheet" type="text/css" href="{{url('app-assets/vendors/css/vendors.min.css')}}">


    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="{{url('app-assets/css/bootstrap.css')}}">
    <link rel="stylesheet" type="text/css" href="{{url('app-assets/css/bootstrap-extended.css')}}">
    <link rel="stylesheet" type="text/css" href="{{url('app-assets/css/colors.css')}}">
    <link rel="stylesheet" type="text/css" href="{{url('app-assets/css/components.css')}}">
    <link rel="stylesheet" type="text/css" href="{{url('app-assets/css/themes/dark-layout.css')}}">
    <link rel="stylesheet" type="text/css" href="{{url('app-assets/css/themes/bordered-layout.css')}}">
    <link rel="stylesheet" type="text/css" href="{{url('app-assets/css/themes/semi-dark-layout.css')}}">

    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="{{url('app-assets/css/core/menu/menu-types/vertical-menu.css')}}">
    <link rel="stylesheet" type="text/css" href="{{url('app-assets/css/pages/dashboard-ecommerce.css')}}">
    <link rel="stylesheet" type="text/css" href="{{url('app-assets/css/plugins/charts/chart-apex.css')}}">
    <link rel="stylesheet" type="text/css" href="{{url('app-assets/css/plugins/extensions/ext-component-toastr.css')}}">
    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->


    <link rel="stylesheet" type="text/css" href="{{url('assets/css/style.css')}}">


@endif


<link rel="stylesheet" type="text/css" href="{{url('app-assets/css-rtl/plugins/forms/form-validation.css')}}">

<link rel="stylesheet" type="text/css"
      href="{{url('app-assets/vendors/css/tables/datatable/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css"
      href="{{url('app-assets/vendors/css/tables/datatable/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css"
      href=" {{url('app-assets/vendors/css/tables/datatable/buttons.bootstrap4.min.css')}}">


<link rel="stylesheet" type="text/css" href="{{url('app-assets/css-rtl/pages/app-user.css')}}">

<link rel="stylesheet" type="text/css" href="{{url('app-assets/vendors/css/extensions/sweetalert2.min.css')}}">


<style>



    /* Hide the page content while JavaScript determines the theme */
    body {
        visibility: hidden;
    }

    body,html, *{
        font-family: 'Tajawal', sans-serif;

    }




</style>
@stack('css')

