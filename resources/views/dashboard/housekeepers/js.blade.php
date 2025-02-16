<script>
    $(document).on('change', '.status-select', function () {
        var $select = $(this);
        var orderId = $select.data('id');
        var newStatus = $select.val();
        var orderValue = $select.data('order-value');
        var oldStatus = $select.data('old-status', $select.val());

        Swal.fire({
            title: '{{trans("messages.sure?")}}',
            text: "{{trans('messages.change-status')}}",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: '{{trans("messages.change")}}!',
            cancelButtonText: '{{trans('messages.cancel')}}',
            customClass: {
                confirmButton: 'btn btn-danger',
                cancelButton: 'btn btn-secondary ml-1'
            },
        }).then((result) => {
            if (!result.isConfirmed) {
                $select.val(oldStatus);
                return;
            }

            if (newStatus == 1) {
                handlePaymentUpdate(orderId, orderValue, newStatus);
            } else if (newStatus == 3) {
                handleFileUpload(orderId, newStatus);
            } else if (newStatus == 5) {
                handleNoteUpdate(orderId, newStatus);
            } else {
                updateStatus(orderId, newStatus);
            }
        });
    });

    function handlePaymentUpdate(orderId, orderValue, newStatus) {
        Swal.fire({
            title: '{{ trans("messages.enter-payment") }}',
            html: `<p>{{trans('main.order_value')}}: ADE ${orderValue}</p>`,
            input: 'number',
            inputAttributes: { min: 0, step: 0.01 },
            showCancelButton: true,
            confirmButtonText: '{{ trans("messages.submit") }}',
            cancelButtonText: '{{ trans("messages.cancel") }}',
            preConfirm: (paymentValue) => {
                let value = parseFloat(paymentValue);
                if (!value || value <= 0 || value > parseFloat(orderValue)) {
                    return Swal.showValidationMessage('{{ trans("messages.invalid-payment") }}');
                }
                return value;
            }
        }).then((paymentResult) => {
            if (paymentResult.isConfirmed) {
                updateStatus(orderId, newStatus, { payment_value: paymentResult.value });
            }
        });
    }

    function handleFileUpload(orderId, newStatus) {
        Swal.fire({
            html: `
            <label for="payment-attachment">{{trans('messages.upload-attachment')}}</label>
            <input type="file" id="payment-attachment" class="swal2-input" required><br><br>
            <label for="contract-attachment">{{trans('messages.upload-contract')}}</label>
            <input type="file" id="contract-attachment" class="swal2-input" required>
        `,
            showCancelButton: true,
            confirmButtonText: '{{trans('messages.submit')}}',
            cancelButtonText: '{{trans('messages.cancel')}}',
            preConfirm: () => {
                var paymentFile = document.getElementById('payment-attachment').files[0];
                var contractFile = document.getElementById('contract-attachment').files[0];
                if (!paymentFile || !contractFile) {
                    return Swal.showValidationMessage('{{trans('messages.required-files')}}');
                }
                return { paymentFile, contractFile };
            }
        }).then((fileResult) => {
            if (fileResult.isConfirmed) {
                var formData = new FormData();
                formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
                formData.append('order_id', orderId);
                formData.append('status', newStatus);
                formData.append('payment_attachment', fileResult.value.paymentFile);
                formData.append('contract_attachment', fileResult.value.contractFile);
                sendAjaxRequest('{{route("housekeepers.orders.updateStatus")}}', formData, true);
            }
        });
    }

    function handleNoteUpdate(orderId, newStatus) {
        Swal.fire({
            title: '{{trans('messages.enter-note')}}',
            input: 'text',
            inputPlaceholder: '{{trans('messages.enter-note-placeholder')}}',
            showCancelButton: true,
            confirmButtonText: '{{trans('messages.submit')}}',
            preConfirm: (note) => {
                if (!note) {
                    return Swal.showValidationMessage('{{trans('messages.note-required')}}');
                }
                return note;
            }
        }).then((noteResult) => {
            if (noteResult.isConfirmed) {
                updateStatus(orderId, newStatus, { note: noteResult.value });
            }
        });
    }

    function updateStatus(orderId, newStatus, additionalData = {}) {
        var data = {
            _token: $('meta[name="csrf-token"]').attr('content'),
            order_id: orderId,
            status: newStatus,
            ...additionalData
        };
        sendAjaxRequest('{{route("housekeepers.orders.updateStatus")}}', data);
    }

    function sendAjaxRequest(url, data, isFormData = false) {
        Swal.fire({
            icon: 'info',
            title: '{{ trans("messages.loading") }}',
            text: '{{ trans("messages.processing-request") }}',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        $.ajax({
            url: url,
            type: 'POST',
            data: data,
            contentType: isFormData ? false : 'application/x-www-form-urlencoded; charset=UTF-8',
            processData: !isFormData,
            success: function () {
                Swal.fire({
                    title: '{{ trans("messages.updated") }}!',
                    text: '{{ trans("messages.change-success") }}.',
                    icon: 'success',
                    confirmButtonText: '{{ trans("messages.close") }}',
                    customClass: { confirmButton: 'btn btn-success' }
                });
                $('#table').DataTable().ajax.reload();
            },
            error: function () {
                Swal.fire({
                    title: '{{ trans("messages.not-updated") }}!',
                    text: '{{ trans("messages.not-update-error") }}.',
                    icon: 'error',
                    confirmButtonText: '{{ trans("messages.close") }}'
                });
                $('#table').DataTable().ajax.reload();
            }
        });
    }

</script>


{{--        get-housekeepers--}}
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
                url: "{{ route('housekeepers.orders.list') }}",
                data: function (d) {
                    d.company_id = $('#companies').val();
                    d.housekeeper_id = $('#housekeepers').val();
                    d.payment_status = $('#payment_status').val();
                    // d.status = [0,1,2];
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

            order: [[0, 'desc']],
            columns: [
                {data: 'DT_RowIndex', name: 'id'},
                {data: 'n_id', name: 'n_id'},
                {data: 'housekeeper', name: 'housekeeper'},
                {data: 'user', name: 'user'},
                {data: 'created_at', name: 'created_at'},
                // {data: 'details', name: 'details'},
                {data: 'status', name: 'status'},


                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    "className": "text-center",
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
