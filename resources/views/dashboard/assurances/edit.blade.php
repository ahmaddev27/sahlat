{{-- Edit Form --}}
<div class="modal fade text-left" id="editServiceModal" tabindex="-1" role="dialog" aria-labelledby="editServiceModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel33">{{ trans('assurances.edit-assurance') }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="#" id="editForm">
                @csrf
                <div class="modal-body">


                  <div class="row">
                      <div class="col-6">   <label>{{ trans('assurances.title') }}</label>
                          <div class="form-group">
                              <input type="text" placeholder="{{ trans('assurances.title') }}" id="title" name="title" class="form-control"/>
                          </div>
                      </div>

                      <div class="col-6">
                          <label>{{ trans('assurances.price') }}</label>
                          <div class="form-group">
                              <input type="number" placeholder="{{ trans('assurances.price') }}" id="price"  name="price" class="form-control">
                          </div>
                      </div>

                      <div class="col-9">
                          <label>{{ trans('assurances.description') }}</label>
                          <div class="form-group">
                              <textarea placeholder="{{ trans('assurances.description') }}" id="description" rows="5" name="description" class="form-control"></textarea>
                          </div>
                      </div>


                      <div class="col-3 justify-content-center">

                          {{-- Avatar --}}
                          <div class="row justify-content-center ">
                              <div class="col-12">
                                  <div class="media mb-2">
                                      <div class="media-body mt-50">
                                          <h4>{{ trans('housekeeper.avatar') }}</h4>
                                          <label class="btn btn-primary mr-75 mb-0" for="edit-avatar">
                                              <span class="d-none d-sm-block">{{ trans('settings.change') }}</span>
                                              <input class="form-control" type="file" name="image" id="edit-avatar" hidden accept="image/png, image/jpeg, image/jpg"/>
                                          </label>
                                          <img src="{{ setting('icon') ? url('storage').'/'.setting('icon') : url('blank.png') }}" alt="avatar" id="edit-avatar-img"
                                               class="user-avatar icon users-avatar-shadow rounded mr-2 my-25 cursor-pointer"
                                               height="90" width="90"/>
                                      </div>
                                  </div>
                              </div>
                          </div>

                      </div>


                      <!-- Company Input -->
                      <div class="col-9">
                          <label>{{ trans('assurances.company') }}</label>
                          <div class="form-group">
                              <input placeholder="{{ trans('assurances.company') }}" name="company" id="company" class="form-control">
                          </div>
                      </div>

                      <!-- Company Logo Upload -->
                      <!-- Edit Form Section -->
                      <div class="col-3 justify-content-center">
                          <div class="row justify-content-center">
                              <div class="col-12">
                                  <div class="media mb-2">
                                      <div class="media-body mt-50">
                                          <h4>{{ trans('assurances.company-logo') }}</h4>
                                          <label class="btn btn-primary mr-75 mb-0" for="edit-logo">
                                              <span class="d-none d-sm-block">{{ trans('settings.change') }}</span>
                                              <input class="form-control" type="file" name="company_logo" id="edit-logo" hidden accept="image/png, image/jpeg, image/jpg" />
                                          </label>
                                          <img src="{{ url('blank.png') }}" alt="company logo" id="edit-logo-img" class="user-avatar icon users-avatar-shadow rounded mr-2 my-25 cursor-pointer" height="90" width="90" />
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>

                </div>


                <input type="hidden" id="id" name="id">
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="edit-submit">
                        <div id="edit-spinner" class="spinner-border spinner-border-sm text-light" role="status" style="display: none;"></div>
                        {{ trans('main.save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


@push('js')
    <script src="{{url('app-assets/vendors/js/forms/repeater/jquery.repeater.min.js')}}"></script>

{{--  fetch--}}


    <script>
        $(document).ready(function () {
            // Open edit modal and populate form
            $(document).on('click', '.edit-service', function () {
                const assuranceId = $(this).data('id'); // Get the ID of the service

                $.ajax({
                    url: `/admin/assurances/fetch/${assuranceId}`, // Adjust according to your routes
                    method: 'GET',
                    success: function (response) {
                        // Populate form fields
                        $('#title').val(response.title);
                        $('#id').val(response.id);
                        $('#price').val(response.price);
                        $('#description').val(response.description);
                        $('#company').val(response.company);

                        if (response.image_url) {
                            $('#edit-avatar-img').attr('src', response.image_url);
                        }
                        if (response.company_logo) {
                            $('#edit-logo-img').attr('src', response.company_logo);
                        }

                        // Show the edit modal
                        $('#editServiceModal').modal('show');
                    },
                    error: function (xhr) {
                        if (xhr.status === 404) {
                            toastr.error('Service not found', 'Error');
                        } else {
                            toastr.error('An error occurred while fetching data', 'Error');
                        }
                    }
                });
            });

            // Submit the edit form
            $('#editForm').validate({
                rules: {
                    title: {
                        required: true
                    },
                    company: {
                        required: true
                    },
                    price: {
                        required: true,
                        number: true,
                    },
                },
                submitHandler: function () {
                    // Show spinner when form is submitted
                    $('#edit-spinner').show();
                    $('#edit-submit').prop('disabled', true); // Disable the submit button to prevent multiple submissions

                    var formData = new FormData($('#editForm')[0]);

                    $.ajax({
                        url: "{{ route('assurances.update') }}",
                        method: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (response) {
                            // Hide spinner on success
                            $('#edit-spinner').hide();
                            $('#edit-submit').prop('disabled', false);

                            toastr.success(response.message, '{{ trans('messages.success') }}');

                            // Reset the form
                            $('#editForm')[0].reset();

                            // Hide the edit modal
                            $('#editServiceModal').modal('hide');

                            // Reload DataTable
                            $('#table').DataTable().ajax.reload();
                        },
                        error: function (xhr) {
                            // Hide spinner on error
                            $('#edit-spinner').hide();
                            $('#edit-submit').prop('disabled', false);

                            if (xhr.responseJSON.errors) {
                                $.each(xhr.responseJSON.errors, function (key, value) {
                                    toastr.error(value[0], '{{ trans('messages.error') }}');
                                });
                            } else {
                                toastr.error('{{ trans('messages.error') }}');
                            }
                        }
                    });
                }
            });

            // Trigger form submission when clicking the submit button
            $('#edit-submit').click(function (e) {
                e.preventDefault();
                $('#editForm').submit(); // Trigger validation and submission
            });
        });

    </script>
@endpush
