
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
                // Handle specific cases for status 1, 4, and 3
                if (newStatus == 1 || newStatus == 4) {
                    let inputPlaceholder = newStatus == 1
                        ? '{{trans('messages.enter-price-placeholder')}}'
                        : '{{trans('messages.enter-note-placeholder')}}';
                    let inputTitle = newStatus == 1
                        ? '{{trans('messages.enter-price')}}'
                        : '{{trans('messages.enter-note')}}';
                    let inputValidationMessage = newStatus == 1
                        ? '{{trans('messages.price-required')}}'
                        : '{{trans('messages.note-required')}}';

                    // Prompt for input (price for 1, note for 4)
                    Swal.fire({
                        title: inputTitle,
                        input: 'text',
                        inputPlaceholder: inputPlaceholder,
                        inputAttributes: {
                            'aria-label': inputPlaceholder,
                            'aria-required': 'true'
                        },
                        showCancelButton: true,
                        confirmButtonText: '{{trans('messages.submit')}}',
                        cancelButtonText: '{{trans('messages.cancel')}}',
                        customClass: {
                            confirmButton: 'btn btn-success',
                            cancelButton: 'btn btn-secondary'
                        },
                        preConfirm: function (inputValue) {
                            if (!inputValue) {
                                Swal.showValidationMessage(inputValidationMessage);
                                return false; // Prevent submission
                            }
                            return inputValue;
                        }
                    }).then((inputResult) => {
                        if (inputResult.isConfirmed) {
                            var inputValue = inputResult.value;

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
                                    status: newStatus,
                                    value: inputValue
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
                        }
                    });
                } else if (newStatus == 2) {
                    // If status is 3, show file upload input
                    Swal.fire({
                        title: '{{trans('messages.upload-attachment')}}',
                        html: `<input type="file" id="attachment" name="attachment" class="swal2-input" required>`,
                        showCancelButton: true,
                        confirmButtonText: '{{trans('messages.submit')}}',
                        cancelButtonText: '{{trans('messages.cancel')}}',
                        customClass: {
                            confirmButton: 'btn btn-success',
                            cancelButton: 'btn btn-secondary'
                        },
                        preConfirm: function () {
                            var fileInput = document.getElementById('attachment');
                            if (!fileInput.files.length) {
                                Swal.showValidationMessage('{{trans('messages.attachment-required')}}');
                                return false;
                            }
                            return fileInput.files[0];
                        }
                    }).then((fileResult) => {
                        if (fileResult.isConfirmed) {
                            var file = fileResult.value;

                            var formData = new FormData();
                            formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
                            formData.append('order_id', orderId);
                            formData.append('status', newStatus);
                            formData.append('attachment', file);

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
                                data: formData,
                                contentType: false,
                                processData: false,
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
                        }
                    });
                } else {
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
                }
            } else {
                $(this).val($(this).data('old-status'));
                $('#table').DataTable().ajax.reload();
            }
        });
    });
</script>

