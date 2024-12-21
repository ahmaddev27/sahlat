{{-- Edit Form --}}
<div class="modal fade text-left" id="editHousekeeperModal" tabindex="-1" role="dialog" aria-labelledby="editHousekeeperModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="editHousekeeperModalLabel">{{ trans('services.edit-service') }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="#" id="editForm">
                @csrf
                <div class="modal-body">
                    <!-- Similar structure to the add form, with input values populated -->
                    <input type="hidden" id="id" name="id" />
                    <div class="row">
                        <div class="col-12">
                            <label>{{ trans('services.title') }}</label>
                            <div class="form-group">
                                <input type="text" placeholder="{{ trans('services.title') }}" name="title" class="form-control" id="editTitle"/>
                            </div>
                        </div>

                    </div>

                    <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="editSubmit">
                        <div id="editSpinner" class="spinner-border spinner-border-sm text-light" role="status" style="display: none;"></div>
                        {{ trans('main.save') }}
                    </button>
                </div>
                </div>


            </form>
        </div>
    </div>
</div>


@push('js')

{{--    fetch--}}
   <script>
       $(document).on('click', '.edit-housekeeper', function () {
           const housekeeperId = $(this).data('id'); // Get the ID of the housekeeper

           $.ajax({
               url: `/company/services/fetch/${housekeeperId}`, // Adjust according to your routes
               method: 'GET',
               success: function (response) {
                   // Populate form fields
                   $('input[name="title"]').val(response.title);
                   $('input[name="id"]').val(housekeeperId);


                   // Show the edit modal
                   $('#editHousekeeperModal').modal('show');
               },
               error: function (xhr) {
                   if (xhr.status === 404) {
                       toastr.error('Services not found', 'Error');
                   } else {
                       toastr.error('An error occurred while fetching data', 'Error');
                   }
               }
           });
       });

   </script>



{{--    update--}}
    <script>
        $(document).ready(function () {
            var editForm = $('#editForm');

            // Validate the edit form
            editForm.validate({
                rules: {
                    title: {
                        required: true
                    },
                },

                submitHandler: function () {
                    // Show the spinner
                    $('#editSpinner').show();

                    // Get the form data
                    var formData = new FormData(editForm[0]);
                    $.ajax({
                        url: "{{ route('company.services.update') }}",
                        method: 'POST',
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function (response) {
                            $('#editSpinner').hide();
                            $('#editHousekeeperModal').modal('hide');
                            $('#table').DataTable().ajax.reload();
                            toastr.success(response.message, '{{ trans('messages.success') }}');
                        },
                        error: function (xhr) {
                            $('#editSpinner').hide();
                            toastr.error(xhr.responseJSON.message || '{{ trans('messages.error') }}');
                        }
                    });
                }
            });
        });
    </script>

@endpush
