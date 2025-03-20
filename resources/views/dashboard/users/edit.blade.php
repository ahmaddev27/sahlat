{{--edit form --}}
<div class="modal fade text-left" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content ">
            <div class="modal-header">
                <h4 class="modal-title" id="editModalLabel">{{ trans('user.edit-user') }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="#" id="editForm">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="id" id="edit-id">
                    <label>{{ trans('user.name') }} </label>
                    <div class="form-group">
                        <input type="text" placeholder="{{ trans('user.name') }}" name="name" class="form-control" id="edit-name" />
                    </div>

{{--                    <label>{{ trans('user.email') }} </label>--}}
{{--                    <div class="form-group">--}}
{{--                        <input type="text" placeholder="{{ trans('user.email') }}" name="email" class="form-control" id="edit-email" />--}}
{{--                    </div>--}}


                    <label>{{ trans('user.phone') }} </label>
                    <div class="input-group input-group-merge mb-1">
                        <div class="input-group-prepend">
                            <span  class="input-group-text">AUE (+971)</span>
                        </div>
                        <input type="text" placeholder="{{ trans('user.phone') }}" maxlength="9" name="phone"  class="form-control" id="edit-phone" />
                    </div>






                    <label>{{ trans('company.email') }} </label>
                    <div class="form-group">
                        <input type="text" placeholder="{{ trans('company.email') }}" name="email" class="form-control" id="edit-email" />
                    </div>



                    <label>{{trans('user.number_id')}} </label>
                    <div class="form-group">
                        <input id="edit-number_id" type="text" placeholder="{{trans('user.number_id')}}" name="number_id"
                               class="form-control" maxlength="18"/>
                    </div>




                    <div class="row">
                        <div class="col-6">
                            <label>{{trans('user.gender')}} </label>
                            <div class="form-group">

                                <div class="demo-inline-spacing">
                                    <div class="custom-control custom-radio">
                                        <input type="radio" value="1" id="edit-male" name="gender"
                                               class="custom-control-input">
                                        <label class="custom-control-label"
                                               for="edit-male">{{trans('user.male')}}</label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" id="edit-female" value="0" name="gender"
                                               class="custom-control-input">
                                        <label class="custom-control-label"
                                               for="edit-female">{{trans('user.female')}}</label>
                                    </div>

                                </div>

                            </div>
                        </div>


                        <div class="col-6">
                            <label>{{trans('user.location')}} </label>
                            <div class="form-group mt-2">
                                <select class="select2 form-control" name="city" id="edit-city">
                                    <option selected disabled>{{ trans('user.select-city') }}</option>
                                    @foreach(cities() as $id => $city)
                                        <option value="{{ $id }}"> {{ $city }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>



                    <div class="row justify-content-center">
                        <div class="col-4">
                            <div class="media mb-2">
                                <div class="media-body mt-50">
                                    <h4>{{ trans('user.avatar') }}</h4>
                                    <label class="btn btn-primary mr-75 mb-0" for="edit-avatar">
                                        <span>{{ trans('settings.change') }}</span>
                                        <input type="file" name="avatar" id="edit-avatar" hidden accept="image/png, image/jpeg, image/jpg" />
                                    </label>
                                    <img id="edit-avatar-preview" class="user-avatar icon users-avatar-shadow rounded mr-2 my-25 cursor-pointer" height="90" width="90" src="{{ setting('icon') != '' ? url('storage').'/'.setting('icon') : url('blank.png') }}" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <input type="hidden" name="id" id="id">
                <div class="modal-footer">


                    <button type="submit" class="btn btn-primary mb-1 mb-sm-0 mr-0 mr-sm-1" id="edit-submit">
                        <div id="spinner-edit" class="spinner-border spinner-border-sm text-light" role="status" style="display: none;">
                            <span class="sr-only"></span>
                        </div>
                        {{ trans('user.edit') }}
                    </button>

                </div>

            </form>
        </div>
    </div>
</div>


@push('js')

    <script>

        // Existing click event for the edit button
        $(document).on('click', '.edit-user', function() {
            var modelId = $(this).data('model-id'); // Correct way to get the data attribute
            $.ajax({
                url: '/admin/users/fetch/' + modelId, // Adjust this to match your route
                method: 'GET',
                success: function(data) {
                    console.log(data)
                    // Populate form fields
                    $('#edit-id').val(data.id);
                    $('#id').val(data.id);
                    $('#edit-name').val(data.name);
                    $('#edit-number_id').val(data.number_id);
                    $('#edit-email').val(data.email);
                    $('#edit-phone').val(data.phone);
                    $('#edit-city').val(data.location);

                    if (data.gender == 1) {
                        $('#edit-male').prop('checked', true); // Male radio button
                    } else if (data.gender == 0) {
                        $('#edit-female').prop('checked', true); // Female radio button
                    }


                    $('#edit-avatar-preview').attr('src', data.avatar ? '/storage/' + data.avatar : '/blank.png');
                    $('#edit-city').select2();
                    // Open the modal
                    $('#editModal').modal('show');


                }
            });
        });



        document.getElementById('edit-number_id').addEventListener('input', function (e) {
            let value = e.target.value.replace(/[^0-9]/g, ''); // إزالة الحروف غير الرقمية

            if (value.length > 3) value = value.slice(0, 3) + '-' + value.slice(3);
            if (value.length > 8) value = value.slice(0, 8) + '-' + value.slice(8);
            if (value.length > 16) value = value.slice(0, 16) + '-' + value.slice(16);

            // تقييد الطول الأقصى بما في ذلك الفواصل
            if (value.length > 18) value = value.slice(0, 18);

            e.target.value = value;
        });
    </script>
    {{-- edit --}}
    <script>
        $(document).ready(function () {
            // Initialize validation for the edit form
            $.validator.addMethod(
                "regex",
                function (value, element, regexp) {
                    let re = new RegExp(regexp);
                    return this.optional(element) || re.test(value);
                },
                "Invalid format." // Default message if none is provided
            );

            var editForm = $('#editForm');

            // Validate the form
            editForm.validate({
                rules: {
                    name: {
                        required: true
                    },
                    city: {
                        required: true
                    },
                    number_id: {
                        required: true,
                        regex: /^784-\d{4}-\d{7}-\d{1}$/,
                    },


                    phone: {
                        required: true,
                        regex: /^[5][0-9]{8}$/ // 9-digit number starting with '5'
                    },
                    email: {
                        required: true,
                        email: true
                    },
                    gender: {
                        required: true
                    },
                    avatar: {
                        required: true
                    }
                },

                messages: {
                    phone: {
                        regex: "{{ trans('main.phone-error') }}" // Custom error message for invalid phone format
                    },
                    number_id: {
                        regex: "{{ trans('main.number_id-error') }}" // Custom error message for invalid phone format
                    },
                },

                submitHandler: function () {
                    var formData = new FormData(editForm[0]);

                    // Show spinner when form is submitted
                    $('#spinner-edit').show();
                    $('#edit-submit').prop('disabled', true);

                    $.ajax({
                        url: '{{ route('users.update') }}',
                        method: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (response) {
                            $('#spinner-edit').hide(); // Hide spinner on success
                            $('#edit-submit').prop('disabled', false);
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



    </script>

@endpush
