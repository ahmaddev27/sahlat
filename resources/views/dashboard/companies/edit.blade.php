{{--edit form --}}
<div class="modal fade text-left" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content ">
            <div class="modal-header">
                <h4 class="modal-title" id="editModalLabel">{{ trans('company.edit-company') }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="#" id="editForm">
                @csrf
                <div class="modal-body">

                    <div class="row">


                        <input type="hidden" name="id" id="edit-id">

                        <div class="col-4">


                            <label>{{ trans('company.name') }} </label>
                            <div class="form-group">
                                <input type="text" placeholder="{{ trans('company.name') }}" name="name"
                                       class="form-control" id="edit-name"/>
                            </div>

                        </div>


                        <div class="col-4">

                            <label>{{ trans('company.email') }} </label>
                            <div class="form-group">
                                <input type="text" placeholder="{{ trans('company.email') }}" name="email"
                                       class="form-control" id="edit-email"/>
                            </div>
                        </div>

                        <div class="col-4">

                            <label>{{ trans('company.password') }} </label>

                            <div class="form-group mb-2">
                                <div class="input-group input-group-merge form-password-toggle">
                                    <input class="form-control form-control-merge" id="password" type="password"
                                           name="password" placeholder="*********"
                                           aria-describedby="password" tabindex="2"/>
                                    <div class="input-group-append"><span class="input-group-text cursor-pointer"><i
                                                data-feather="eye"></i></span></div>
                                </div>
                                <span class="text-danger mt-2" id="passwordError"></span>
                            </div>
                        </div>


                        <div class="col-6">

                            <label>{{ trans('user.phone') }} </label>
                            <div class="input-group input-group-merge mb-1">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">AUE (+971)</span>
                                </div>
                                <input type="text" placeholder="{{ trans('user.phone') }}" maxlength="9" name="phone"
                                       class="form-control" id="edit-phone"/>
                            </div>
                        </div>


                        <div class="col-6">
                            <label>{{trans('company.address')}} </label>
                            <div class="form-group">
                                <select id="edit-address" name="address" class="select2 form-control">
                                    <option selected disabled>{{trans('company.address')}}</option>
                                    @foreach(cities() as $key=>$city)
                                        <option value="{{$key}}">{{$city}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-6">

                            <label>{{trans('company.hourly_price')}} </label>
                            <div class="form-group">
                                <input type="number" placeholder="{{trans('company.hourly_price')}}" id="edit-hourly_price"
                                       name="hourly_price" class="form-control mb-1"/>
                                <code style="font-family: Tajawal;">{{trans('company.hourly_note')}}</code>
                            </div>
                        </div>


                        <div class="col-6">
                            <label>{{trans('company.experience')}} </label>
                            <div class="form-group">
                                <input type="number" placeholder="{{trans('company.experience')}}" id="edit-experience"
                                       name="experience" class="form-control"/>
                            </div>
                        </div>

                        <div class="col-4">
                            <label>{{trans('company.long')}} </label>
                            <div class="form-group">
                                <input type="text" placeholder="{{trans('company.long')}}" name="long" id="edit-long"
                                       class="form-control"/>
                            </div>
                        </div>

                        <div class="col-4">
                            <label>
                                {{trans('company.lat')}} </label>
                            <div class="form-group">
                                <input type="text" placeholder="{{trans('company.lat')}}" name="lat" id="edit-lat"
                                       class="form-control"/>
                            </div>
                        </div>


                        <div class="col-4">
                            <div class=" justify-content-center">
                                <div class="col-4">
                                    <div class="media mb-2">
                                        <div class="media-body mt-50">
                                            <h4>{{ trans('company.avatar') }}</h4>
                                            <label class="btn btn-primary mr-75 mb-0" for="edit-avatar">
                                                <span>{{ trans('settings.change') }}</span>
                                                <input type="file" name="avatar" id="edit-avatar" hidden
                                                       accept="image/png, image/jpeg, image/jpg"/>
                                            </label>
                                            <img id="edit-avatar-preview"
                                                 class="user-avatar icon users-avatar-shadow rounded mr-2 my-25 cursor-pointer"
                                                 height="90" width="90" src="{{ url('blank.png') }}"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <label>
                                {{trans('company.bio')}} </label>
                            <div class="form-group">
                        <textarea placeholder="{{trans('company.bio')}}" rows="5" name="bio" id="edit-bio"
                                  class="form-control"></textarea>
                            </div>
                        </div>


                    </div>
                </div>


                <input type="hidden" name="id" id="id">
                <div class="modal-footer">


                    <button type="submit" class="btn btn-primary mb-1 mb-sm-0 mr-0 mr-sm-1" id="edit-submit">
                        <div id="spinner-edit" class="spinner-border spinner-border-sm text-light" role="status"
                             style="display: none;">
                            <span class="sr-only"></span>
                        </div>
                        {{ trans('company.edit') }}
                    </button>

                </div>

            </form>
        </div>
    </div>
</div>


@push('js')
    {{-- edit --}}
    <script>
        $(document).ready(function () {

            $.validator.addMethod(
                "regex",
                function (value, element, regexp) {
                    let re = new RegExp(regexp);
                    return this.optional(element) || re.test(value);
                },
                "Invalid format." // Default message if none is provided
            );

            // Initialize validation for the edit form
            var editForm = $('#editForm');

            // Validate the form
            editForm.validate({
                rules: {
                    name: {
                        required: true
                    },
                    email: {
                        required: true,
                        email: true
                    },
                    // address: {
                    //     required: true
                    // },


                    lat: {
                        required: true
                    },
                    long: {
                        required: true
                    },
                    address: {
                        required: true
                    },

                    experience: {
                        required: true,
                        number: true,
                    },
                    phone: {
                        required: true,
                        regex: /^[5][0-9]{8}$/ // 9-digit number starting with '5'
                    },
                    // Add additional validation rules as needed, e.g., password if it's editable
                    password: {
                        required: false // Set to false if not editing the password
                    },
                    avatar: {
                        required: false // Adjust this based on whether the avatar is optional during editing
                    }
                },

                messages: {
                    phone: {
                        regex: "{{ trans('main.phone-error') }}" // Custom error message for invalid phone format
                    }
                },

                submitHandler: function () {
                    var formData = new FormData(editForm[0]);

                    // Show spinner when form is submitted
                    $('#spinner-edit').show();
                    $('#edit-submit').prop('disabled', true);

                    $.ajax({
                        url: '{{ route('companies.update') }}',
                        method: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (response) {
                            $('#spinner-edit').hide(); // Hide spinner on success
                            $('#edit-submit').prop('disabled', false);
                            editForm[0].reset();
                            toastr.success(response.message, '{{ trans('messages.success-update') }}');

                            $('#editModal').modal('hide'); // Close the edit modal
                            $('#table').DataTable().ajax.reload();
                        },
                        error: function (xhr) {
                            $('#spinner-edit').hide(); // Hide spinner on error
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

            // Handle button click to trigger form submission
            $('#edit-submit').click(function (e) {
                e.preventDefault();
                editForm.submit(); // Trigger validation and submission
            });
        });

        // Existing click event for the edit button
        $(document).on('click', '#edit', function () {
            var modelId = $(this).attr('model_id');
            $.ajax({
                url: '/admin/companies/fetch/' + modelId, // Adjust this to match your route
                method: 'GET',
                success: function (data) {
                    // Populate form fields
                    $('#edit-id').val(data.id);
                    $('#id').val(data.id);
                    $('#edit-name').val(data.name);
                    $('#edit-bio').val(data.bio);
                    $('#edit-email').val(data.email);
                    $('#edit-phone').val(data.phone);
                    $('#edit-address').val(data.address);

                    $('#edit-experience').val(data.experience);
                    $('#edit-long').val(data.long);
                    $('#edit-lat').val(data.lat);
                    $('#edit-hourly_price').val(data.hourly_price);
                    $('#edit-avatar-preview').attr('src', data.avatar ? '/storage/' + data.avatar : '/blank.png');

                    // Open the modal
                    $('#editModal').modal('show');
                }
            });
        });
    </script>

@endpush
