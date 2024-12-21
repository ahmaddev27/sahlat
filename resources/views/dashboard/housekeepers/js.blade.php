
{{-- Change status --}}
<script>
    $(document).on('change', '.status-select', function () {
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
                if (newStatus == 3) {
                    // If status is 3, show file upload input for payment and contract
                    Swal.fire({

                        html: `
            <label for="payment-attachment">{{trans('messages.upload-attachment')}}</label>
            <input type="file" id="payment-attachment" name="payment-attachment" class="swal2-input" required><br><br>
            <label for="contract-attachment">{{trans('messages.upload-contract')}}</label>
            <input type="file" id="contract-attachment" name="contract-attachment" class="swal2-input" required>
        `,
                        showCancelButton: true,
                        confirmButtonText: '{{trans('messages.submit')}}',
                        cancelButtonText: '{{trans('messages.cancel')}}',
                        customClass: {
                            confirmButton: 'btn btn-success',
                            cancelButton: 'btn btn-secondary'
                        },
                        preConfirm: function () {
                            // Check if both files are selected
                            var paymentFileInput = document.getElementById('payment-attachment');
                            var contractFileInput = document.getElementById('contract-attachment');

                            if (!paymentFileInput.files.length) {
                                Swal.showValidationMessage('{{trans('messages.required-payment')}}');
                                return false; // Prevent submission if no payment file is selected
                            }

                            if (!contractFileInput.files.length) {
                                Swal.showValidationMessage('{{trans('messages.required-contract')}}');
                                return false; // Prevent submission if no contract file is selected
                            }

                            return { paymentFile: paymentFileInput.files[0], contractFile: contractFileInput.files[0] };
                        }
                    }).then((fileResult) => {
                        if (fileResult.isConfirmed) {
                            var paymentFile = fileResult.value.paymentFile; // Get the selected payment file
                            var contractFile = fileResult.value.contractFile; // Get the selected contract file

                            // Make the AJAX request to update the status and upload both files
                            var formData = new FormData();
                            formData.append('_token', $('meta[name="csrf-token"]').attr('content')); // CSRF Token
                            formData.append('order_id', orderId);
                            formData.append('status', newStatus);
                            formData.append('payment_attachment', paymentFile); // Attach the payment file
                            formData.append('contract_attachment', contractFile); // Attach the contract file

                            // Show loading indicator
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
                                url: '{{route('housekeepers.orders.updateStatus')}}', // Add the URL to update the status
                                type: 'POST',
                                data: formData,
                                contentType: false,
                                processData: false,
                                success: function (data) {
                                    // Success case with custom success message
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
                                    // Error case with custom error message
                                    Swal.fire({
                                        title: '{{trans('messages.not-updated')}}!',
                                        text: '{{trans('messages.not-update-error')}}.',
                                        icon: 'error',
                                        confirmButtonText: '{{trans('messages.close')}}',
                                    });

                                    // Reload the DataTable to reflect changes
                                    $('#table').DataTable().ajax.reload();
                                }
                            });
                        }
                    });
                }

                // If the status is 4, ask the user for a note after confirming the change
                else if (newStatus == 5) {
                    // Display the input for the note if status is 4
                    Swal.fire({
                        title: '{{trans('messages.enter-note')}}',
                        input: 'text',
                        inputPlaceholder: '{{trans('messages.enter-note-placeholder')}}',
                        inputAttributes: {
                            'aria-label': '{{trans('messages.enter-note-placeholder')}}',
                            'aria-required': 'true'
                        },
                        showCancelButton: true,
                        confirmButtonText: '{{trans('messages.submit')}}',
                        cancelButtonText: '{{trans('messages.cancel')}}',
                        customClass: {
                            confirmButton: 'btn btn-success',
                            cancelButton: 'btn btn-secondary'
                        },
                        preConfirm: function (note) {
                            // If the note is empty, show an error
                            if (!note) {
                                Swal.showValidationMessage('{{trans('messages.note-required')}}');
                                return false; // Prevent submission
                            }
                            return note;
                        }
                    }).then((noteResult) => {
                        if (noteResult.isConfirmed) {
                            var note = noteResult.value; // Get the entered note

                            // Show loading indicator
                            Swal.fire({
                                icon:'info',
                                title: '{{trans('messages.loading')}}',
                                text: '{{trans('messages.processing-request')}}',
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });

                            // Make the AJAX request to update the status
                            $.ajax({
                                url: '{{route('housekeepers.orders.updateStatus')}}', // Add the URL to update the status
                                type: 'POST',
                                data: {
                                    _token: $('meta[name="csrf-token"]').attr('content'), // CSRF Token
                                    order_id: orderId,
                                    status: newStatus,
                                    note: note // Send the note if status is 4
                                },
                                success: function (data) {
                                    // Success case with custom success message
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
                                    // Error case with custom error message
                                    Swal.fire({
                                        title: '{{trans('messages.not-updated')}}!',
                                        text: '{{trans('messages.not-update-error')}}.',
                                        icon: 'error',
                                        confirmButtonText: '{{trans('messages.close')}}',
                                    });

                                    // Reload the DataTable to reflect changes
                                    $('#table').DataTable().ajax.reload();
                                }
                            });
                        }
                    });
                } else {


                    // Show loading indicator
                    Swal.fire({
                        icon:'info',
                        title: '{{trans('messages.loading')}}',
                        text: '{{trans('messages.processing-request')}}',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });


                    // If the status is not 4, just proceed with the change without asking for a note
                    $.ajax({
                        url: '{{route('housekeepers.orders.updateStatus')}}', // Add the URL to update the status
                        type: 'POST',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content'), // CSRF Token
                            order_id: orderId,
                            status: newStatus
                        },
                        success: function (data) {
                            // Success case with custom success message
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
                            // Error case with custom error message
                            Swal.fire({
                                title: '{{trans('messages.not-updated')}}!',
                                text: '{{trans('messages.not-update-error')}}.',
                                icon: 'error',
                                confirmButtonText: '{{trans('messages.close')}}',
                            });

                            // Reload the DataTable to reflect changes
                            $('#table').DataTable().ajax.reload();
                        }
                    });
                }
            } else {
                // If the user cancels, revert the status
                $(this).val($(this).data('old-status')); // Revert to the old value
                $('#table').DataTable().ajax.reload();
            }
        });
    });
</script>
