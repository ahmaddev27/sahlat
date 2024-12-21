<!-- BEGIN: Vendor JS-->

<script src="{{url('app-assets/vendors/js/vendors.min.js')}}"></script>
<!-- BEGIN Vendor JS-->

<!-- BEGIN: Page Vendor JS-->
<script src="{{url('app-assets/vendors/js/charts/apexcharts.min.js')}}"></script>
<script src="{{url('app-assets/vendors/js/extensions/toastr.min.js')}}"></script>
<!-- END: Page Vendor JS-->

<!-- BEGIN: Theme JS-->
<script src="{{url('app-assets/js/core/app-menu.js')}}"></script>
<script src="{{url('app-assets/js/core/app.js')}}"></script>
<!-- END: Theme JS-->

<!-- BEGIN: Page JS-->
{{--<script src="{{url('app-assets/js/scripts/pages/dashboard-ecommerce.js')}}"></script>--}}
<!-- END: Page JS-->




<script>
    @if ($errors->has('error'))
    setTimeout(function () {
        toastr.warning('{{ $errors->first('error') }}', '{{trans('messages.error')}}', {
            closeButton: true,
            tapToDismiss: false
        });
    }, 300);
    @endif
</script>

<script src="{{url('app-assets/vendors/js/extensions/sweetalert2.all.min.js')}}"></script>

<script src="{{url('app-assets/vendors/js/forms/select/select2.full.min.js')}}"></script>

<script src="{{url('app-assets/js/scripts/extensions/ext-component-sweet-alerts.js')}}"></script>

<script src="{{url('app-assets/vendors/js/tables/datatable/jquery.dataTables.min.js')}}"></script>
<script src="{{url('app-assets/vendors/js/forms/validation/jquery.validate.min.js')}}"></script>


<script src="{{url('app-assets/vendors/js/tables/datatable/datatables.bootstrap4.min.js')}}"></script>
<script src="{{url('app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js')}}"></script>
<script src="{{url('app-assets/vendors/js/tables/datatable/responsive.bootstrap4.js')}}"></script>
<script src="{{url('app-assets/vendors/js/tables/datatable/datatables.checkboxes.min.js')}}"></script>
<script src="{{url('app-assets/vendors/js/tables/datatable/datatables.buttons.min.js')}}"></script>
<script src="{{url('app-assets/vendors/js/tables/datatable/jszip.min.js')}}"></script>
<script src="{{url('app-assets/vendors/js/tables/datatable/pdfmake.min.js')}}"></script>
<script src="{{url('vfs_fonts.js')}}"></script>
<script src="{{url('app-assets/vendors/js/tables/datatable/buttons.html5.min.js')}}"></script>
<script src="{{url('app-assets/vendors/js/tables/datatable/buttons.print.min.js')}}"></script>





@if(app()->getLocale() === 'ar')
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/localization/messages_ar.js"></script>
@endif




@stack('js')


<script>
    document.getElementById('language-toggle').addEventListener('click', function (event) {
        event.preventDefault(); // Prevent default action

        // Get the current locale from the document's HTML tag or any other source
        var currentLocale = document.documentElement.lang; // Assuming <html lang="en"> or <html lang="ar">

        // Toggle locale
        var newLocale = currentLocale === 'en' ? 'ar' : 'en';

        // Update the language setting
        // Assuming you have a route or URL pattern to handle locale changes
        var newUrl = window.location.href.replace(currentLocale, newLocale);

        // Redirect to the new URL
        window.location.href = newUrl;
    });
</script>


{{--feather--}}
<script>
    $(window).on('load', function() {
        if (feather) {
            feather.replace({
                width: 14,
                height: 14
            });

            document.body.style.visibility = 'visible';


        }
    })




</script>



{{-- delete--}}
<script>

    $(document).on("click", '#delete', function (e) {
        e.preventDefault();
        var $this = $(this);
        var model_id = $this.attr('model_id');
        var route = $this.attr('route');
        var reload = $this.attr('reload');

        // Disable the submit button to prevent multiple submissions
        $this.prop('disabled', true);
        $('.spinner-border', this).show();

        Swal.fire({
            title: '{{trans('messages.sure?')}}',
            text: "{{trans('messages.wont')}}!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: '{{trans('messages.delete')}}!',
            cancelButtonText: '{{trans('messages.cancel')}}',
            customClass: {
                confirmButton: 'btn btn-danger',
                cancelButton: 'btn btn-secondary ml-1'
            },
            buttonsStyling: false
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                    data: {
                        "_token": "{{ csrf_token() }}",
                        'id': model_id,
                    },
                    url: route,
                    type: "json",
                    method: "post",
                    success: function (data) {
                        Swal.fire({
                            title: '{{trans('messages.deleted')}}!',
                            text: '{{trans('messages.delete-success')}}.',
                            icon: 'success',
                            confirmButtonText: '{{trans('messages.cancel')}}',
                            customClass: {
                                confirmButton: 'btn btn-success'
                            }
                        });

                        if(reload){
                            setTimeout(function() {
                                location.reload();
                            }, 300);
                        }

                        $('#table').DataTable().ajax.reload();
                    },
                    error: function (data) {
                        Swal.fire({
                            title: '{{trans('messages.not-delete')}}!',
                            text: '{{trans('messages.not-delete-error')}}.',
                            icon: 'error',
                            confirmButtonText: '{{trans('messages.cancel')}}',

                        });

                        $('#table').DataTable().ajax.reload();

                    },
                    complete: function() {
                        // Re-enable the button after the AJAX call is complete
                        $this.prop('disabled', false);
                        $('.spinner-border', $this).hide();
                    }
                });
            } else {
                // Re-enable the button if the user cancels the SweetAlert
                $this.prop('disabled', false);
                $('.spinner-border', $this).hide();
            }
        });
    });


</script>

<script>
    $('.select2').select2();

</script>


@if(App::getLocale() == 'ar')
    <script>

        toastr.options = {
            "closeButton": false,
            "debug": false,
            "newestOnTop": false,
            "progressBar": false,
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut",
            "tapToDismiss": false,
            "positionClass":"toast-top-left",
            "rtl": true

        };
    </script>

@else
    <script>
        toastr.options = {
            "closeButton": false,
            "debug": false,
            "newestOnTop": false,
            "progressBar": false,
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut",
            "tapToDismiss": false,
            "positionClass":"toast-top-right",
            "rtl": false
        };

    </script>
@endif


{{--lang--}}
<script>
    document.getElementById('language-toggle').addEventListener('click', function (event) {
        event.preventDefault(); // Prevent default action

        // Get the current locale from the HTML tag's lang attribute or default to 'en'
        var currentLocale = document.documentElement.lang || 'en';
        var newLocale = currentLocale === 'en' ? 'ar' : 'en';

        // Get the current URL and split it to manage the locale in the path
        var currentUrl = window.location.href;
        var url = new URL(currentUrl);
        var pathSegments = url.pathname.split('/');

        // Check if the URL contains a locale as the first path segment
        if (pathSegments[1] === currentLocale) {
            // Replace the current locale with the new locale
            pathSegments[1] = newLocale;
        } else {
            // Insert the new locale as the first segment
            pathSegments.splice(1, 0, newLocale);
        }

        // Rebuild the URL with the new locale in the path
        url.pathname = pathSegments.join('/');
        window.location.href = url.href;
    });


</script>


