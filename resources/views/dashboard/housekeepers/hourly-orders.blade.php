@extends('dashboard.layouts.master',['title'=>trans('dashboard_aside.housekeepers-HourlyOrders')])
@push('css')

@endpush
@section('left')
    {{--    <div class="content-header-right text-md-right col-md-3 col-12 d-md-block d-none">--}}
    {{--        <div class="form-group breadcrumb-right">--}}
    {{--            <button class="btn btn-primary waves-effect waves-float waves-light btn-sm"--}}
    {{--                    data-toggle="modal" data-target="#inlineForm"--}}
    {{--                    title="{{trans('housekeeper.new-housekeeper')}}"><i data-feather="plus"></i></button>--}}
    {{--        </div>--}}
    {{--    </div>--}}

@endsection

@section('content')

    <div class="content-body">
        <div class="card">

            <div class="card-datatable table-responsive p-2 ">

                <div class="row">


                    <div class="card-header p-1 col-sm-3" style="margin-bottom: -5px">

                        <label class="pt-1">{{trans('housekeeper.payment')}}</label>
                        <select id="payment_status" class="select2 form-control">
                            <option selected disabled>{{ trans('main.change') }}</option>
                            <option value="">{{ trans('main.all') }}</option>
                            {{--                            <option value="0">{{ trans('main.not-payed') }}</option>--}}
                            <option value="1">{{ trans('main.partly-payed') }}</option>
                            <option value="2">{{ trans('main.completely-payed') }}</option>

                        </select>

                    </div>

                    <div class="card-header p-1 col-sm-3" style="margin-bottom: -5px">

                        <label class="pt-1">{{trans('housekeeper.company')}}</label>

                        <select id="companies" class="select2 form-control">
                            <option selected disabled>{{ trans('main.change') }}</option>
                            <option value="">{{ trans('main.all') }}</option>

                            @foreach($companies as $company)
                                <option value="{{$company->id}}">{{$company->name }}</option>

                            @endforeach


                        </select>

                    </div>


                    <div class="card-header p-1 col-sm-3" style="margin-bottom: -5px">

                        <label class="pt-1">{{trans('housekeeper.housekeeper')}}</label>

                        <select id="housekeepers" class="select2 form-control" disabled>
                            <option selected disabled>{{ trans('main.change') }}</option>
                            <option value="">{{ trans('main.all') }}</option>
                        </select>

                    </div>

                </div>

                <table class="table" id="table">


                    <thead class="thead-light ">
                    <tr>
                        <th>#</th>
                        <th>{{trans('assurances.order_id')}}</th>

                        <th>{{trans('housekeeper.company')}}</th>
                        <th>{{trans('housekeeper.user')}}</th>
                        <th>{{trans('main.in')}}</th>
                        {{--                        <th>{{trans('housekeeper.from')}}</th>--}}
                        {{--                        <th>{{trans('housekeeper.to')}}</th>--}}
                        <th>{{trans('housekeeper.hours')}}</th>
                        <th>{{trans('housekeeper.housekeeper')}}</th>
                        <th class="width-150">{{trans('housekeeper.status')}}</th>
                        {{--                        <th class="width-100">{{trans('housekeeper.payment')}}</th>--}}
                        <th>{{trans('housekeeper.action')}}</th>
                        <th>{{trans('housekeeper.action')}}</th>

                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>



    @include('dashboard.housekeepers.hourly-stripe-link')


    @push('js')

        {{--  get-housekeepers--}}
        <script>
            $(document).ready(function () {
                // On company change
                $('#companies').on('change', function () {
                    var companyId = $(this).val();

                    // Fetch housekeepers for the selected company
                    $.ajax({
                        url: 'orders/get-housekeepers/' + companyId, // Your route for fetching housekeepers
                        method: 'GET',
                        success: function (response) {
                            // Clear previous options
                            $('#housekeepers').empty().prop('disabled', false);

                            // Append the default option
                            $('#housekeepers').append('<option selected disabled>{{ trans("main.change") }}</option>');
                            $('#housekeepers').append('<option value="" >{{ trans("main.all") }}</option>');

                            // Append housekeepers based on the selected company
                            $.each(response.housekeepers, function (index, housekeeper) {
                                $('#housekeepers').append('<option value="' + housekeeper.id + '">' + housekeeper.name + '</option>');
                            });

                            // Trigger the datatable update with the selected filters
                            $('#housekeepers').trigger('change');
                        }
                    });
                });

                // On housekeeper change (for the datatable filtering)
                $('#housekeepers').on('change', function () {
                    var housekeeperId = $(this).val();

                    // Reload the datatable with the selected filters
                    $('#housekeeper-orders-table').DataTable().ajax.reload();
                });
            });

        </script>


        {{--datatable--}}
        <script>
            $(document).ready(function () {
                var table = $('#table').DataTable({
                    processing: false,
                    serverSide: true,

                    ajax: {
                        url: "{{ route('housekeepers.HourlyOrders.list') }}",
                        data: function (d) {

                            d.company_id = $('#companies').val();
                            d.housekeeper_id = $('#housekeepers').val();
                            d.payment_status = $('#payment_status').val();
                            // d.status = $('#status').val();
                        }
                    },


                    dom: 'Bfrtilp',
                    buttons: [
                        {
                            extend: 'collection',
                            className: 'btn btn-outline-secondary dropdown-toggle mr-2',
                            text: feather.icons['share'].toSvg({class: 'font-small-4 mr-50'}) + '{{trans('main.export')}}',
                            buttons: [

                                {
                                    extend: 'excel',
                                    text: feather.icons['file'].toSvg({class: 'font-small-4 mr-50'}) + 'Excel',
                                    className: 'dropdown-item',
                                    exportOptions: {
                                        columns: [0, 1, 2, 3, 4, 5, 6, 7],  // Specify the columns to export, including the status column (5)

                                        format: {
                                            body: function (data, row, column, node) {
                                                // Check if the data contains a <select> element (status column)
                                                if (typeof data === 'string' && data.includes('<select')) {
                                                    // Create a temporary div to parse the HTML content
                                                    const tempDiv = $('<div>').html(data);
                                                    // Extract the selected option's text (status text)
                                                    const selectedOption = tempDiv.find('option:selected').text().trim();
                                                    return selectedOption; // Return only the selected text
                                                }

                                                // If data contains an image (in columns where image tags are present, like assurance or user avatars)
                                                if (typeof data === 'string' && data.includes('<img')) {
                                                    // For image columns, just return the relevant text (you can modify this logic as needed)
                                                    const tempDiv = $('<div>').html(data);
                                                    return tempDiv.text().trim(); // Extract and return the textual content
                                                }
                                                // If data contains an image (in columns where image tags are present, like assurance or user avatars)
                                                if (typeof data === 'string' && data.includes('<span')) {
                                                    // For image columns, just return the relevant text (you can modify this logic as needed)
                                                    const tempDiv = $('<div>').html(data);
                                                    return tempDiv.text().trim(); // Extract and return the textual content
                                                }


                                                // For other data, return it as is
                                                return data;
                                            }
                                        }
                                    },
                                },
                                {
                                    extend: 'pdf',
                                    text: feather.icons['clipboard'].toSvg({class: 'font-small-4 mr-50'}) + 'Pdf',
                                    className: 'dropdown-item',
                                    exportOptions: {
                                        columns: [0, 1, 2, 3, 4, 5, 6, 7], // Specify the columns to export, including the status column (5)
                                        format: {
                                            body: function (data, row, column, node) {
                                                // Check if the data contains a <select> element (status column)
                                                if (typeof data === 'string' && data.includes('<select')) {
                                                    // Create a temporary div to parse the HTML content
                                                    const tempDiv = $('<div>').html(data);
                                                    // Extract the selected option's text (status text)
                                                    const selectedOption = tempDiv.find('option:selected').text().trim();
                                                    return selectedOption; // Return only the selected text
                                                }

                                                // If data contains an image (in columns where image tags are present, like assurance or user avatars)
                                                if (typeof data === 'string' && data.includes('<img')) {
                                                    // For image columns, just return the relevant text (you can modify this logic as needed)
                                                    const tempDiv = $('<div>').html(data);
                                                    return tempDiv.text().trim(); // Extract and return the textual content
                                                }

                                                // If data contains an image (in columns where image tags are present, like assurance or user avatars)
                                                if (typeof data === 'string' && data.includes('<span')) {
                                                    // For image columns, just return the relevant text (you can modify this logic as needed)
                                                    const tempDiv = $('<div>').html(data);
                                                    return tempDiv.text().trim(); // Extract and return the textual content
                                                }


                                                // For other data, return it as is
                                                return data;
                                            }
                                        }
                                    },
                                    customize: function (doc) {
                                        // إعداد الخطوط ودعم اللغة العربية
                                        pdfMake.fonts = {
                                            Tajawal: {
                                                normal: 'Tajawal.ttf',
                                                bold: 'Tajawal.ttf',
                                            },
                                        };

                                        doc.defaultStyle.font = 'Tajawal';

                                        // ضبط الإعدادات العامة للاتجاه
                                        doc.defaultStyle.alignment = 'center';
                                        doc.defaultStyle.direction = 'rtl';

                                        doc.content.forEach(function (contentItem) {
                                            // إذا كان العنصر نص ويحتوي على نص عربي، نعكس الكلمات لضمان عرض النصوص بشكل صحيح
                                            if (contentItem.text && /[\u0600-\u06FF]/.test(contentItem.text)) {
                                                contentItem.alignment = 'center';
                                                contentItem.direction = 'rtl';
                                                contentItem.text = contentItem.text.split(' ').reverse().join('  ');
                                            }

                                            // إذا كان العنصر جدول، نضبط المحاذاة لليمين مع تعديل محتوى الخلايا
                                            if (contentItem.table) {
                                                contentItem.alignment = 'center'; // محاذاة الجدول بالكامل لليمين
                                                // ضبط الخلايا داخل الجدول لمحاذاة النصوص العربية
                                                contentItem.table.body.forEach(function (row) {
                                                    row.forEach(function (cell) {
                                                        if (typeof cell.text === 'string' && /[\u0600-\u06FF]/.test(cell.text)) {
                                                            cell.alignment = 'center';
                                                            cell.direction = 'rtl';
                                                            cell.text = cell.text.split(' ').reverse().join('  ');
                                                        }
                                                        cell.margin = [5, 5, 5, 5]; // [الهوامش العلوية، اليسرى، السفلية، اليمنى]
                                                    });

                                                });
                                            }
                                        });

                                        // ضبط الهوامش والتنسيق العام
                                        doc.pageMargins = [20, 20, 20, 20];
                                    }

                                }
                            ],
                            init: function (api, node, config) {
                                $(node).removeClass('btn-secondary');
                                $(node).parent().removeClass('btn-group');
                                setTimeout(function () {
                                    $(node).closest('.dt-buttons').removeClass('btn-group').addClass('d-inline-flex');
                                }, 50);
                            }
                        },
                    ],

                    order: [[8, 'desc']],
                    columns: [
                        {data: 'DT_RowIndex', name: 'id'},
                        {data: 'n_id', name: 'n_id'},
                        {data: 'company', name: 'company'},
                        {data: 'user', name: 'user'},
                        {data: 'date', name: 'date'},
                        // {data: 'from', name: 'from',"className": "text-center",},
                        // {data: 'to', name: 'to',"className": "text-center",},
                        {data: 'hours', name: 'hours'},
                        {data: 'houseKeeper', name: 'houseKeeper'},
                        {data: 'status', name: 'status'},
                        // {data: 'payment', name: 'payment'},
                        {data: 'created_at', name: 'created_at', searchable: false, visible: false},


                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false,

                        },

                    ],


                    @if(App::getLocale() == 'ar')

                    language: {
                        "url": "https://cdn.datatables.net/plug-ins/2.1.8/i18n/ar.json"
                    },
                    @endif


                    "initComplete": function (settings, json) {
                        // Apply select2 after the table has been initialized
                        $('.select2').select2();
                    },
                    "drawCallback": function (settings) {
                        // Reinitialize select2 on every redraw
                        $('.select2').select2();
                    }

                });


                $('#housekeepers').on('change', function () {
                    table.ajax.reload();
                });
                $('#companies').on('change', function () {
                    table.ajax.reload();
                });
                $('#payment_status').on('change', function () {
                    table.ajax.reload();
                });
                $('#status').on('change', function () {
                    table.ajax.reload();
                });


            });


        </script>




        {{-- Change status --}}
        <script>
            $(document).on('change', '.status-select', function () {
                var $select = $(this);
                var orderId = $select.data('id');
                var newStatus = $select.val();
                var orderValue = $select.data('order-value'); // Retrieve the order value from the data attribute

                // Store the old value to revert if the user cancels
                $select.data('old-status', newStatus);

                // Show confirmation dialog with SweetAlert
                Swal.fire({
                    title: '{{ trans("messages.sure?") }}',
                    text: "{{ trans('messages.change-status') }}",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: '{{ trans("messages.change") }}!',
                    cancelButtonText: '{{ trans("messages.cancel") }}',
                    customClass: {
                        confirmButton: 'btn btn-danger',
                        cancelButton: 'btn btn-secondary ml-1'
                    },
                }).then((result) => {
                    if (result.isConfirmed) {
                        if (newStatus == 1) {
                            // Status 1: Payment update – display order value above input
                            Swal.fire({
                                title: '{{ trans("messages.enter-payment") }}',
                                html: `<p>{{trans('main.order_value')}}: ADE ${orderValue}</p>`,
                                input: 'number',
                                inputAttributes: {
                                    min: 0,
                                    step: 0.01
                                },
                                showCancelButton: true,
                                confirmButtonText: '{{ trans("messages.submit") }}',
                                cancelButtonText: '{{ trans("messages.cancel") }}',
                                customClass: {
                                    confirmButton: 'btn btn-success',
                                    cancelButton: 'btn btn-secondary'
                                },
                                preConfirm: (paymentValue) => {
                                    let value = parseFloat(paymentValue);
                                    const orderValueFloat = parseFloat(orderValue);

                                    if (!value || value <= 0) {
                                        Swal.showValidationMessage('{{ trans("messages.invalid-payment") }}');
                                    } else if (value > orderValueFloat) {
                                        Swal.showValidationMessage('{{trans('messages.Payment value cannot exceed the order value')}}');
                                    }
                                    return value;
                                }
                            }).then((paymentResult) => {
                                if (paymentResult.isConfirmed) {
                                    var paymentValue = paymentResult.value;

                                    // Show loading indicator
                                    Swal.fire({
                                        icon: 'info',
                                        title: '{{ trans("messages.loading") }}',
                                        text: '{{ trans("messages.processing-request") }}',
                                        allowOutsideClick: false,
                                        didOpen: () => {
                                            Swal.showLoading();
                                        }
                                    });

                                    // AJAX request for payment update
                                    $.ajax({
                                        url: '{{ route("housekeepers.HourlyOrders.updateStatus") }}',
                                        type: 'POST',
                                        data: {
                                            _token: $('meta[name="csrf-token"]').attr('content'),
                                            order_id: orderId,
                                            status: newStatus,
                                            payment_value: paymentValue
                                        },
                                        success: function () {
                                            Swal.fire({
                                                title: '{{ trans("messages.updated") }}!',
                                                text: '{{ trans("messages.change-success") }}.',
                                                icon: 'success',
                                                confirmButtonText: '{{ trans("messages.close") }}',
                                                customClass: {
                                                    confirmButton: 'btn btn-success'
                                                }
                                            });
                                            $('#table').DataTable().ajax.reload();
                                        },
                                        error: function () {
                                            Swal.fire({
                                                title: '{{ trans("messages.not-updated") }}!',
                                                text: '{{ trans("messages.not-update-error") }}.',
                                                icon: 'error',
                                                confirmButtonText: '{{ trans("messages.close") }}',
                                            });
                                            $('#table').DataTable().ajax.reload();
                                        }
                                    });
                                }
                            });
                        }

                        else if (newStatus == 3) {
                            // Status 3: Housekeeper selection
                            var companyId = $select.data('company-id');

                            // Show loading indicator
                            Swal.fire({
                                icon: 'info',
                                title: '{{ trans("messages.loading") }}',
                                text: '{{ trans("messages.processing-request") }}',
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });

                            // Fetch housekeepers dynamically
                            $.ajax({
                                url: '{{ route("housekeepers.HourlyOrders.get-housekeepers", "") }}/' + companyId,
                                type: 'GET',
                                success: function (data) {
                                    var housekeepers = data.housekeepers;
                                    var options = '';

                                    housekeepers.forEach(function (housekeeper) {
                                        options += `<option value="${housekeeper.id}">${housekeeper.name}</option>`;
                                    });

                                    // Show select2 dropdown in SweetAlert
                                    Swal.fire({
                                        title: '{{ trans("messages.select-housekeeper") }}',
                                        html: `<select id="housekeeper-select" class="form-control">${options}</select>`,
                                        showCancelButton: true,
                                        confirmButtonText: '{{ trans("messages.submit") }}',
                                        cancelButtonText: '{{ trans("messages.cancel") }}',
                                        customClass: {
                                            confirmButton: 'btn btn-success',
                                            cancelButton: 'btn btn-secondary'
                                        },
                                        didOpen: () => {
                                            $('#housekeeper-select').select2({
                                                dropdownParent: $('.swal2-popup')
                                            });
                                        }
                                    }).then((selectResult) => {
                                        if (selectResult.isConfirmed) {
                                            var housekeeperId = $('#housekeeper-select').val();

                                            if (!housekeeperId) {
                                                Swal.fire({
                                                    title: '{{ trans("messages.error") }}',
                                                    text: '{{ trans("messages.select-housekeeper-error") }}',
                                                    icon: 'error',
                                                });
                                                return;
                                            }

                                            // Show processing request alert
                                            Swal.fire({
                                                icon: 'info',
                                                title: '{{ trans("messages.loading") }}',
                                                text: '{{ trans("messages.processing-request") }}',
                                                allowOutsideClick: false,
                                                didOpen: () => {
                                                    Swal.showLoading();
                                                }
                                            });

                                            // AJAX request with housekeeper ID
                                            $.ajax({
                                                url: '{{ route("housekeepers.HourlyOrders.updateStatus") }}',
                                                type: 'POST',
                                                data: {
                                                    _token: $('meta[name="csrf-token"]').attr('content'),
                                                    order_id: orderId,
                                                    status: newStatus,
                                                    housekeeper_id: housekeeperId
                                                },
                                                success: function () {
                                                    Swal.fire({
                                                        title: '{{ trans("messages.updated") }}!',
                                                        text: '{{ trans("messages.change-success") }}.',
                                                        icon: 'success',
                                                        confirmButtonText: '{{ trans("messages.close") }}',
                                                        customClass: {
                                                            confirmButton: 'btn btn-success'
                                                        }
                                                    });
                                                    $('#table').DataTable().ajax.reload();
                                                },
                                                error: function () {
                                                    Swal.fire({
                                                        title: '{{ trans("messages.not-updated") }}!',
                                                        text: '{{ trans("messages.not-update-error") }}.',
                                                        icon: 'error',
                                                        confirmButtonText: '{{ trans("messages.close") }}',
                                                    });
                                                    $('#table').DataTable().ajax.reload();
                                                }
                                            });
                                        }
                                    });
                                },
                                error: function () {
                                    Swal.fire({
                                        title: '{{ trans("messages.error") }}',
                                        text: '{{ trans("messages.housekeeper-fetch-error") }}',
                                        icon: 'error',
                                    });
                                }
                            });
                        } else {
                            // For all other statuses: Regular status update
                            Swal.fire({
                                icon: 'info',
                                title: '{{ trans("messages.loading") }}',
                                text: '{{ trans("messages.processing-request") }}',
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });

                            $.ajax({
                                url: '{{ route("housekeepers.HourlyOrders.updateStatus") }}',
                                type: 'POST',
                                data: {
                                    _token: $('meta[name="csrf-token"]').attr('content'),
                                    order_id: orderId,
                                    status: newStatus
                                },
                                success: function () {
                                    Swal.fire({
                                        title: '{{ trans("messages.updated") }}!',
                                        text: '{{ trans("messages.change-success") }}.',
                                        icon: 'success',
                                        confirmButtonText: '{{ trans("messages.close") }}',
                                        customClass: {
                                            confirmButton: 'btn btn-success'
                                        }
                                    });
                                    $('#table').DataTable().ajax.reload();
                                },
                                error: function () {
                                    Swal.fire({
                                        title: '{{ trans("messages.not-updated") }}!',
                                        text: '{{ trans("messages.not-update-error") }}.',
                                        icon: 'error',
                                        confirmButtonText: '{{ trans("messages.close") }}',
                                    });
                                    $('#table').DataTable().ajax.reload();
                                }
                            });
                        }
                    } else {
                        // If canceled, revert to the old status and reload the table
                        $select.val($select.data('old-status'));
                        $('#table').DataTable().ajax.reload();
                    }
                });
            });
        </script>




    @endpush

@stop
