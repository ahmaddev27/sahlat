
{{-- datatable --}}
<script>
    $(document).ready(function () {
        var note = "{{ trans('main.note') }}";

        // Initialize the DataTable
        var table = $('#table').DataTable({

            processing: false,
            serverSide: true,
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
                                columns: [0, 1, 2, 3 ,4, 5,6],  // Specify the columns to export, including the status column (5)
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
                                columns: [0, 1, 2, 3 ,4, 5,6],  // Specify the columns to export, including the status column (5)
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
                                pdfMake.fonts = {
                                    Tajawal: {
                                        normal: 'Tajawal.ttf',
                                        bold: 'Tajawal.ttf',
                                    },
                                };

                                doc.defaultStyle.font = 'Tajawal';
                                doc.defaultStyle.alignment = 'center';
                                doc.defaultStyle.direction = 'rtl';
                                doc.content.forEach(function (contentItem) {
                                    if (contentItem.text && /[\u0600-\u06FF]/.test(contentItem.text)) {
                                        contentItem.alignment = 'center';
                                        contentItem.direction = 'rtl';
                                        contentItem.text = contentItem.text.split(' ').reverse().join('  ');
                                    }
                                    if (contentItem.table) {
                                        contentItem.table.alignment = 'center';
                                        contentItem.alignment = 'center';
                                        contentItem.table.body.forEach(function (row) {
                                            row.forEach(function (cell) {
                                                if (typeof cell.text === 'string' && /[\u0600-\u06FF]/.test(cell.text)) {
                                                    cell.alignment = 'center';
                                                    cell.direction = 'rtl';
                                                    cell.text = cell.text.split(' ').reverse().join('  ');
                                                }
                                                cell.margin = [5, 5, 5, 5]; // Margins
                                            });
                                        });
                                    }
                                });
                                doc.pageMargins = [20, 20, 20, 20];
                            }
                        }

                    ],
                    init: function (api, node, config) {
                        $(node).removeClass('btn-light');
                        $(node).parent().removeClass('btn-group');
                        setTimeout(function () {
                            $(node).closest('.dt-buttons').removeClass('btn-group').addClass('d-inline-flex');
                        }, 50);
                    }
                },
            ],

            ajax: {
                url: "{{ route('assurances.orders.list') }}",
                data: function (d) {
                    // d.status = [0];
                    d.payment_status = $('#payment_status').val();
                    d.assurance = $('#assurance').val();
                }
            },


            order: [[4, 'desc']],

            columns: [
                {data: 'DT_RowIndex', name: 'id'},
                {data: 'n_id', name: 'n_id'},
                {data: 'assurance', name: 'assurance'},
                {data: 'user', name: 'user'},
                {data: 'created_at', name: 'created_at'},
                {data: 'phone', name: 'phone'},

                {data: 'status', name: 'status'},
                // {data: 'payment', name: 'payment'},

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


            "initComplete": function(settings, json) {
                // Apply select2 after the table has been initialized
                $('.select2').select2();
            },
            "drawCallback": function(settings) {
                // Reinitialize select2 on every redraw
                $('.select2').select2();
            }



        });

        $('#status').on('change', function () {
            table.ajax.reload();
        });

        $('#payment_status').on('change', function () {
            table.ajax.reload();
        });

        $('#assurance').on('change', function () {
            table.ajax.reload();
        });
    });
</script>



{{--        change status--}}
{{-- Change status --}}
<script>
    $(document).on('change', '.status-select', function() {
        var $select = $(this);
        var orderId = $select.data('id');
        var newStatus = $select.val();
        var orderValue = $select.data('order-value'); // Retrieve the order value from the data attribute

        // Store the old value to revert if the user cancels
        $select.data('old-status', $select.val());

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
                // If the new status is 1, ask for a payment value
                if (newStatus == 1) {
                    Swal.fire({
                        title: '{{ trans("messages.enter-payment") }}',
                        // Display order value above the input
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

                            // Show loading indicator before making the AJAX call
                            Swal.fire({
                                icon: 'info',
                                title: '{{ trans("messages.loading") }}',
                                text: '{{ trans("messages.processing-request") }}',
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });

                            // AJAX call to update status with payment_value
                            $.ajax({
                                url: '{{ route("assurances.orders.updateStatus") }}',
                                type: 'POST',
                                data: {
                                    _token: $('meta[name="csrf-token"]').attr('content'),
                                    order_id: orderId,
                                    status: newStatus,
                                    payment_value: paymentValue
                                },
                                success: function (data) {
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
                                error: function (data) {
                                    Swal.fire({
                                        title: '{{ trans("messages.not-updated") }}!',
                                        text: '{{ trans("messages.not-update-error") }}.',
                                        icon: 'error',
                                        confirmButtonText: '{{ trans("messages.close") }}',
                                    });
                                    $('#table').DataTable().ajax.reload();
                                }
                            });
                        } else {
                            // If the user cancels the payment input, revert the select value
                            $select.val($select.data('old-status'));
                            $('#table').DataTable().ajax.reload();
                        }
                    });
                } else {
                    // For statuses other than 1, proceed with a regular update
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
                        url: '{{ route("assurances.orders.updateStatus") }}',
                        type: 'POST',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            order_id: orderId,
                            status: newStatus
                        },
                        success: function (data) {
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
                        error: function (data) {
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
                // If the user cancels the confirmation, revert the status
                $select.val($select.data('old-status'));
                $('#table').DataTable().ajax.reload();
            }
        });
    });
</script>


