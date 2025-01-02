<div class="modal fade text-left" id="sendSmsModal" tabindex="-1" role="dialog" aria-labelledby="smsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="smsModalLabel">{{trans('main.Send SMS Link')}}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="sendSmsForm">
                @csrf
                <input type="hidden" value="" name="order_id" id="order_id">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="orderLink">{{trans('main.stripe-link')}}</label>
                        <input  id="orderLink" name="orderLink" placeholder="{{trans('main.enter-stripe-link')}}" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
{{--                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{trans('main.close')}}</button>--}}
                    <button type="submit" class="btn btn-primary">{{trans('main.Send SMS')}}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function setOrderId(orderId) {
        // Set the order ID in the hidden input field
        document.getElementById('order_id').value = orderId;
    }

    // Handle form submission
    document.getElementById('sendSmsForm').addEventListener('submit', function (e) {
        e.preventDefault(); // Prevent default form submission

        const orderId = document.getElementById('order_id').value;
        const orderLink = document.getElementById('orderLink').value;

        // Validate the URL format
        if (!isValidUrl(orderLink)) {
            Swal.fire({
                title: '{{trans('messages.error')}}!',
                text: '{{trans('messages.invalid-url')}}',
                icon: 'error',
                confirmButtonText: '{{trans('messages.ok')}}'
            });
            return;
        }

        // Disable submit button to prevent multiple submissions
        const submitButton = this.querySelector('button[type="submit"]');
        submitButton.disabled = true;

        // Send the AJAX request
        $.ajax({
            data: {
                "_token": "{{ csrf_token() }}",
                "order_id": orderId,
                "orderLink": orderLink,
            },
            url: '{{ route('company.housekeepers.orders.sendSms') }}',
            type: "POST",
            success: function (data) {
                Swal.fire({
                    title: '{{trans('messages.success')}}!',
                    text: '{{trans('messages.sms-sent-success')}}',
                    icon: 'success',
                    confirmButtonText: '{{trans('messages.ok')}}',
                    customClass: {
                        confirmButton: 'btn btn-success'
                    }
                });

                // Clear the form after successful submission
                document.getElementById('sendSmsForm').reset();

                // Close the modal and reload the DataTable
                $('#sendSmsModal').modal('hide');
                $('#table').DataTable().ajax.reload();
            },
            error: function (xhr) {
                let errorMessage = '{{trans('messages.sms-sent-failed')}}';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }

                Swal.fire({
                    title: '{{trans('messages.error')}}!',
                    text: errorMessage,
                    icon: 'error',
                    confirmButtonText: '{{trans('messages.ok')}}'
                });
            },
            complete: function () {
                // Re-enable the submit button
                submitButton.disabled = false;
            }
        });
    });

    /**
     * Validates if the provided string is a valid URL.
     * @param {string} url - The URL to validate.
     * @returns {boolean} - True if valid, false otherwise.
     */
    function isValidUrl(url) {
        const urlRegex = /^(https?:\/\/)?([a-zA-Z0-9-]+\.)+[a-zA-Z]{2,}(\/\S*)?$/;
        return urlRegex.test(url);
    }
</script>
