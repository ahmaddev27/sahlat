
{{-- Change status --}}
{{--<script>--}}
{{--    $(document).on('change', '.status-select', function() {--}}
{{--        var orderId = $(this).data('id');--}}
{{--        var newStatus = $(this).val();--}}

{{--        // Store the old value to revert if the user cancels--}}
{{--        $(this).data('old-status', $(this).val());--}}

{{--        // Show confirmation dialog with SweetAlert--}}
{{--        Swal.fire({--}}
{{--            title: '{{trans('messages.sure?')}}',--}}
{{--            text: "{{trans('messages.change-status')}}",--}}
{{--            icon: 'warning',--}}
{{--            showCancelButton: true,--}}
{{--            confirmButtonText: '{{trans('messages.change')}}!',--}}
{{--            cancelButtonText: '{{trans('messages.cancel')}}',--}}
{{--            customClass: {--}}
{{--                confirmButton: 'btn btn-danger',--}}
{{--                cancelButton: 'btn btn-secondary ml-1'--}}
{{--            },--}}
{{--        }).then((result) => {--}}
{{--            if (result.isConfirmed) {--}}
{{--                // Handle specific cases for status 1, 4, and 3--}}
{{--                if (newStatus == 1 || newStatus == 4) {--}}
{{--                    let inputPlaceholder = newStatus == 1--}}
{{--                        ? '{{trans('messages.enter-price-placeholder')}}'--}}
{{--                        : '{{trans('messages.enter-note-placeholder')}}';--}}
{{--                    let inputTitle = newStatus == 1--}}
{{--                        ? '{{trans('messages.enter-price')}}'--}}
{{--                        : '{{trans('messages.enter-note')}}';--}}
{{--                    let inputValidationMessage = newStatus == 1--}}
{{--                        ? '{{trans('messages.price-required')}}'--}}
{{--                        : '{{trans('messages.note-required')}}';--}}

{{--                    // Prompt for input (price for 1, note for 4)--}}
{{--                    Swal.fire({--}}
{{--                        title: inputTitle,--}}
{{--                        input: 'text',--}}
{{--                        inputPlaceholder: inputPlaceholder,--}}
{{--                        inputAttributes: {--}}
{{--                            'aria-label': inputPlaceholder,--}}
{{--                            'aria-required': 'true'--}}
{{--                        },--}}
{{--                        showCancelButton: true,--}}
{{--                        confirmButtonText: '{{trans('messages.submit')}}',--}}
{{--                        cancelButtonText: '{{trans('messages.cancel')}}',--}}
{{--                        customClass: {--}}
{{--                            confirmButton: 'btn btn-success',--}}
{{--                            cancelButton: 'btn btn-secondary'--}}
{{--                        },--}}
{{--                        preConfirm: function (inputValue) {--}}
{{--                            if (!inputValue) {--}}
{{--                                Swal.showValidationMessage(inputValidationMessage);--}}
{{--                                return false; // Prevent submission--}}
{{--                            }--}}
{{--                            return inputValue;--}}
{{--                        }--}}
{{--                    }).then((inputResult) => {--}}
{{--                        if (inputResult.isConfirmed) {--}}
{{--                            var inputValue = inputResult.value;--}}

{{--                            Swal.fire({--}}
{{--                                icon: 'info',--}}
{{--                                title: '{{trans('messages.loading')}}',--}}
{{--                                text: '{{trans('messages.processing-request')}}',--}}
{{--                                allowOutsideClick: false,--}}
{{--                                didOpen: () => {--}}
{{--                                    Swal.showLoading();--}}
{{--                                }--}}
{{--                            });--}}

{{--                            $.ajax({--}}
{{--                                url: '{{route('violations.updateStatus')}}',--}}
{{--                                type: 'POST',--}}
{{--                                data: {--}}
{{--                                    _token: $('meta[name="csrf-token"]').attr('content'),--}}
{{--                                    order_id: orderId,--}}
{{--                                    status: newStatus,--}}
{{--                                    value: inputValue--}}
{{--                                },--}}
{{--                                success: function (data) {--}}
{{--                                    Swal.fire({--}}
{{--                                        title: '{{trans('messages.updated')}}!',--}}
{{--                                        text: '{{trans('messages.change-success')}}.',--}}
{{--                                        icon: 'success',--}}
{{--                                        confirmButtonText: '{{trans('messages.close')}}',--}}
{{--                                        customClass: {--}}
{{--                                            confirmButton: 'btn btn-success'--}}
{{--                                        }--}}
{{--                                    });--}}

{{--                                    $('#table').DataTable().ajax.reload();--}}
{{--                                },--}}
{{--                                error: function (data) {--}}
{{--                                    Swal.fire({--}}
{{--                                        title: '{{trans('messages.not-updated')}}!',--}}
{{--                                        text: '{{trans('messages.not-update-error')}}.',--}}
{{--                                        icon: 'error',--}}
{{--                                        confirmButtonText: '{{trans('messages.close')}}',--}}
{{--                                    });--}}

{{--                                    $('#table').DataTable().ajax.reload();--}}
{{--                                }--}}
{{--                            });--}}
{{--                        }--}}
{{--                    });--}}
{{--                } else if (newStatus == 2) {--}}
{{--                    // If status is 3, show file upload input--}}
{{--                    Swal.fire({--}}
{{--                        title: '{{trans('messages.upload-attachment')}}',--}}
{{--                        html: `<input type="file" id="attachment" name="attachment" class="swal2-input" required>`,--}}
{{--                        showCancelButton: true,--}}
{{--                        confirmButtonText: '{{trans('messages.submit')}}',--}}
{{--                        cancelButtonText: '{{trans('messages.cancel')}}',--}}
{{--                        customClass: {--}}
{{--                            confirmButton: 'btn btn-success',--}}
{{--                            cancelButton: 'btn btn-secondary'--}}
{{--                        },--}}
{{--                        preConfirm: function () {--}}
{{--                            var fileInput = document.getElementById('attachment');--}}
{{--                            if (!fileInput.files.length) {--}}
{{--                                Swal.showValidationMessage('{{trans('messages.attachment-required')}}');--}}
{{--                                return false;--}}
{{--                            }--}}
{{--                            return fileInput.files[0];--}}
{{--                        }--}}
{{--                    }).then((fileResult) => {--}}
{{--                        if (fileResult.isConfirmed) {--}}
{{--                            var file = fileResult.value;--}}

{{--                            var formData = new FormData();--}}
{{--                            formData.append('_token', $('meta[name="csrf-token"]').attr('content'));--}}
{{--                            formData.append('order_id', orderId);--}}
{{--                            formData.append('status', newStatus);--}}
{{--                            formData.append('attachment', file);--}}

{{--                            Swal.fire({--}}
{{--                                icon: 'info',--}}
{{--                                title: '{{trans('messages.loading')}}',--}}
{{--                                text: '{{trans('messages.processing-request')}}',--}}
{{--                                allowOutsideClick: false,--}}
{{--                                didOpen: () => {--}}
{{--                                    Swal.showLoading();--}}
{{--                                }--}}
{{--                            });--}}

{{--                            $.ajax({--}}
{{--                                url: '{{route('violations.updateStatus')}}',--}}
{{--                                type: 'POST',--}}
{{--                                data: formData,--}}
{{--                                contentType: false,--}}
{{--                                processData: false,--}}
{{--                                success: function (data) {--}}
{{--                                    Swal.fire({--}}
{{--                                        title: '{{trans('messages.updated')}}!',--}}
{{--                                        text: '{{trans('messages.change-success')}}.',--}}
{{--                                        icon: 'success',--}}
{{--                                        confirmButtonText: '{{trans('messages.close')}}',--}}
{{--                                        customClass: {--}}
{{--                                            confirmButton: 'btn btn-success'--}}
{{--                                        }--}}
{{--                                    });--}}

{{--                                    $('#table').DataTable().ajax.reload();--}}
{{--                                },--}}
{{--                                error: function (data) {--}}
{{--                                    Swal.fire({--}}
{{--                                        title: '{{trans('messages.not-updated')}}!',--}}
{{--                                        text: '{{trans('messages.not-update-error')}}.',--}}
{{--                                        icon: 'error',--}}
{{--                                        confirmButtonText: '{{trans('messages.close')}}',--}}
{{--                                    });--}}

{{--                                    $('#table').DataTable().ajax.reload();--}}
{{--                                }--}}
{{--                            });--}}
{{--                        }--}}
{{--                    });--}}
{{--                } else {--}}
{{--                    Swal.fire({--}}
{{--                        icon: 'info',--}}
{{--                        title: '{{trans('messages.loading')}}',--}}
{{--                        text: '{{trans('messages.processing-request')}}',--}}
{{--                        allowOutsideClick: false,--}}
{{--                        didOpen: () => {--}}
{{--                            Swal.showLoading();--}}
{{--                        }--}}
{{--                    });--}}


{{--                    $.ajax({--}}
{{--                        url: '{{route('violations.updateStatus')}}',--}}
{{--                        type: 'POST',--}}
{{--                        data: {--}}
{{--                            _token: $('meta[name="csrf-token"]').attr('content'),--}}
{{--                            order_id: orderId,--}}
{{--                            status: newStatus--}}
{{--                        },--}}

{{--                        success: function (data) {--}}
{{--                            Swal.fire({--}}
{{--                                title: '{{trans('messages.updated')}}!',--}}
{{--                                text: '{{trans('messages.change-success')}}.',--}}
{{--                                icon: 'success',--}}
{{--                                confirmButtonText: '{{trans('messages.close')}}',--}}
{{--                                customClass: {--}}
{{--                                    confirmButton: 'btn btn-success'--}}
{{--                                }--}}
{{--                            });--}}

{{--                            $('#table').DataTable().ajax.reload();--}}
{{--                        },--}}

{{--                        error: function (data) {--}}
{{--                            Swal.fire({--}}
{{--                                title: '{{trans('messages.not-updated')}}!',--}}
{{--                                text: '{{trans('messages.not-update-error')}}.',--}}
{{--                                icon: 'error',--}}
{{--                                confirmButtonText: '{{trans('messages.close')}}',--}}
{{--                            });--}}

{{--                            $('#table').DataTable().ajax.reload();--}}
{{--                        }--}}
{{--                    });--}}
{{--                }--}}
{{--            } else {--}}
{{--                $(this).val($(this).data('old-status'));--}}
{{--                $('#table').DataTable().ajax.reload();--}}
{{--            }--}}
{{--        });--}}
{{--    });--}}
{{--</script>--}}



{{-- Change status --}}
<script>
    $(document).on('change', '.status-select', function() {
        var orderId = $(this).data('id');
        var newStatus = $(this).val();

        // Store the old value to revert if the user cancels
        $(this).data('old-status', $(this).val());

        // Show confirmation dialog with SweetAlert
        Swal.fire({
            title: '{{trans('messages.sure?')}}',
            text: "{{trans('messages.change-status')}}",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: '{{trans('messages.change')}}!',
            cancelButtonText: '{{trans('messages.cancel')}}',
            customClass: {
                confirmButton: 'btn btn-danger',
                cancelButton: 'btn btn-secondary ml-1'
            },
        }).then((result) => {
            if (result.isConfirmed) {

                    Swal.fire({
                        icon: 'info',
                        title: '{{trans('messages.loading')}}',
                        text: '{{trans('messages.processing-request')}}',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });


                    $.ajax({
                        url: '{{route('violations.updateStatus')}}',
                        type: 'POST',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            order_id: orderId,
                            status: newStatus
                        },

                        success: function (data) {
                            Swal.fire({
                                title: '{{trans('messages.updated')}}!',
                                text: '{{trans('messages.change-success')}}.',
                                icon: 'success',
                                confirmButtonText: '{{trans('messages.close')}}',
                                customClass: {
                                    confirmButton: 'btn btn-success'
                                }
                            });

                            $('#table').DataTable().ajax.reload();
                        },

                        error: function (data) {
                            Swal.fire({
                                title: '{{trans('messages.not-updated')}}!',
                                text: '{{trans('messages.not-update-error')}}.',
                                icon: 'error',
                                confirmButtonText: '{{trans('messages.close')}}',
                            });

                            $('#table').DataTable().ajax.reload();
                        }
                    });

            } else {
                $(this).val($(this).data('old-status'));
                $('#table').DataTable().ajax.reload();
            }
        });
    });
</script>



{{-- datatable --}}
<script>
    $(document).ready(function () {

        // Initialize the DataTable
        var table = $('#table').DataTable({

            processing: false,
            serverSide: true,
            dom: 'Bfrtlip',
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
                                columns: [0, 1, 2, 3 ,4, 5,6,7],  // Specify the columns to export, including the status column (5)
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
                                columns: [0, 1, 2, 3 ,4, 5,6,7],  // Specify the columns to export, including the status column (5)
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
                        $(node).removeClass('btn-secondary');
                        $(node).parent().removeClass('btn-group');
                        setTimeout(function () {
                            $(node).closest('.dt-buttons').removeClass('btn-group').addClass('d-inline-flex');
                        }, 50);
                    }
                },
            ],


            ajax: {
                url: "{{ route('violations.list') }}",
                data: function (d) {
                    // d.status = [0,2];
                    d.payment_status = $('#payment_status').val();
                }
            },

            columns: [
                {data: 'DT_RowIndex', name: 'id'},
                {data: 'n_id', name: 'n_id'},
                // {data: 'violation_number', name: 'violation_number'},
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


        $('#payment_status').on('change', function () {
            table.ajax.reload();
        });
        $('#status').on('change', function () {
            table.ajax.reload();
        });
    });
</script>


