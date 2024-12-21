{{-- filesModal --}}
<div class="modal fade text-left" id="filesModal" tabindex="-1" role="dialog" aria-labelledby="filesModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel33">{{ trans('assurances.attachments') }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="files-display" class="mt-2"></div> <!-- Display area for files -->
            </div>
        </div>
    </div>
</div>

@push('js')
    <script src="{{url('app-assets/vendors/js/forms/repeater/jquery.repeater.min.js')}}"></script>

    {{--  fetch--}}

    <script>
        $(document).ready(function () {
            $(document).on('click', '.files-orders', function () {
                const orderId = $(this).attr('model_id'); // Get model ID from button

                console.log('Fetching files for order ID:', orderId); // Debug log

                $.ajax({
                    url: `/admin/assurances/orders/files/${orderId}`, // Adjust according to your routes
                    method: 'GET',
                    success: function (response) {
                        $('#files-display').empty(); // Clear previous files

                        if (response.files && response.files.length > 0) {
                            response.files.forEach(function (file) {
                                const fileElement = `
                                <div class="file-item mb-2">
                                    <a href="${file.file}" target="_blank" class="text-body">
                                        <i class="fa fa-file mr-1"></i> ${file.title}
                                    </a>
                                </div>
                            `;
                                $('#files-display').append(fileElement); // Append new files
                            });
                        } else {
                            $('#files-display').html('<p class="text-muted">{{trans('assurances.no-files')}}</p>');
                        }

                        $('#filesModal').modal('show'); // Show the modal
                    },
                    error: function (xhr) {
                        if (xhr.status === 404) {
                            toastr.error('{{trans('messages.file-not')}}', 'Error');
                        } else {
                            toastr.error('An error occurred while fetching data', 'Error');
                        }
                    }
                });
            });
        });
    </script>
@endpush
