{{-- Edit Form --}}
<div class="modal fade text-left" id="editHousekeeperModal" tabindex="-1" role="dialog" aria-labelledby="editHousekeeperModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="editHousekeeperModalLabel">{{ trans('housekeeper.edit-housekeeper') }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="#" id="editForm">
                @csrf
                <div class="modal-body">
                    <!-- Similar structure to the add form, with input values populated -->
                    <input type="hidden" id="editHousekeeperId" name="housekeeper_id" />
                    <div class="row">
                        <div class="col-6">
                            <label>{{ trans('housekeeper.name') }}</label>
                            <div class="form-group">
                                <input type="text" placeholder="{{ trans('housekeeper.name') }}" name="name" class="form-control" id="editName"/>
                            </div>
                        </div>
                        <div class="col-6">



                        <label>{{ trans('user.phone') }} </label>
                        <div class="input-group input-group-merge mb-1">
                            <div class="input-group-prepend">
                                <span  class="input-group-text">AUE (+971)</span>
                            </div>
                            <input type="text" placeholder="{{ trans('user.phone') }}" maxlength="9" name="phone"  class="form-control" id="edit-phone" />
                        </div>
                        </div>





                        <div class="col-6">
                            <label>{{ trans('company.experience') }}</label>
                            <div class="form-group">
                                <input type="number" placeholder="{{ trans('company.experience') }}"  id="editExperience" name="experience" class="form-control"/>
                            </div>
                        </div>

                        <div class="col-6">
                            <label>{{ trans('housekeeper.company') }}</label>
                            <div class="form-group">
                                <select class="select2 form-control" name="company" id="editCompany">
                                    <option selected disabled>{{ trans('housekeeper.select-company') }}</option>
                                    @foreach($companies as $company)
                                        <option value="{{ $company->id }}">{{ $company->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
{{--                        <div class="col-6">--}}
{{--                            <label>{{ trans('housekeeper.type') }}</label>--}}
{{--                            <div class="form-group">--}}
{{--                                <select class="select2 form-control" name="type" id="editType">--}}
{{--                                    <option selected disabled>{{ trans('housekeeper.select-type') }}</option>--}}
{{--                                    <option value="m">{{ trans('housekeeper.m') }}</option>--}}
{{--                                    <option value="h">{{ trans('housekeeper.h') }}</option>--}}
{{--                                </select>--}}
{{--                            </div>--}}
{{--                        </div>--}}
                        <div class="col-6">
                            <label>{{ trans('housekeeper.salary') }}</label>
                            <div class="form-group">
                                <input type="number" placeholder="{{ trans('housekeeper.salary') }}" name="salary" class="form-control" id="editSalary"/>
                            </div>
                        </div>


                        <div class="col-6">
                            <label>{{trans('user.gender')}} </label>
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



                        <div class="col-4">
                            <label>{{ trans('housekeeper.nationality') }}</label>
                            <div class="form-group">
                                <select class="select2 form-control" id="edit-nationality" name="nationality">
                                    <option selected disabled>{{ trans('housekeeper.select-nationality') }}</option>
                                    @foreach(Nationalities() as $id => $nationality)
                                        <option value="{{ $id }}">{{ $nationality }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>


                        <div class="col-4">
                            <label>{{ trans('housekeeper.language') }}</label>
                            <div class="form-group">
                                <select class="select2 form-control" id="edit-language" name="language">
                                    <option selected disabled>{{ trans('housekeeper.select-language') }}</option>
                                    @foreach(getAllLangs()  as $id => $name)
                                        <option value="{{ $id }}">{{ $name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>


                        <div class="col-4">
                            <label>{{ trans('housekeeper.religion') }}</label>
                            <div class="form-group">
                                <select class="select2 form-control" id="edit-religion" name="religion">
                                    <option selected disabled>{{ trans('housekeeper.select-religion') }}</option>
                                    @foreach(getAllReligions() as $id => $name)
                                        <option value="{{ $id }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>


                        <div class="col-8">

                            <label>{{ trans('housekeeper.description') }}</label>
                            <div class="form-group">
                              <textarea id="edit-description" cols="3" placeholder="{{ trans('housekeeper.description') }}" name="description"
                                        class="form-control"></textarea>
                            </div>
                        </div>


                        <div class="col-4">
                            {{-- Avatar --}}
                            <div class="row justify-content-start ">
                                <div class="col-4">
                                    <div class="media mb-2">
                                        <div class="media-body mt-50">
                                            <h4>{{ trans('housekeeper.avatar') }}</h4>
                                            <label class="btn btn-primary mr-75 mb-0" for="edit-avatar">
                                                <span class="d-none d-sm-block">{{ trans('settings.change') }}</span>
                                                <input class="form-control" type="file" name="avatar" id="edit-avatar" hidden accept="image/png, image/jpeg, image/jpg"/>
                                            </label>
                                            <img src="{{  url('blank.png') }}" alt="avatar" id="edit-avatar-img" class="user-avatar icon users-avatar-shadow rounded mr-2 my-25 cursor-pointer" height="90" width="90"/>
                                        </div>
                                    </div>
                                </div>
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
    $(document).ready(function () {
        // Store the original HTML structure of the repeater container
        const originalRepeaterHtml = $('#editFeaturesContainer').html();

        // Event handler for fetching housekeeper data
        $(document).on('click', '.edit-housekeeper', function () {
            const housekeeperId = $(this).data('id'); // Get the ID of the housekeeper

            $.ajax({
                url: `/admin/housekeepers/fetch/${housekeeperId}`, // Adjust according to your routes
                method: 'GET',
                success: function (response) {
                    // Populate form fields
                    $('input[name="name"]').val(response.name);
                    $('input[name="housekeeper_id"]').val(housekeeperId);
                    $('input[name="phone"]').val(response.phone);
                    $('input[name="experience"]').val(response.experience);
                    $('#editCompany').val(response.company_id).trigger('change');
                    $('#edit-religion').val(response.religion).trigger('change');
                    $('#edit-language').val(response.language).trigger('change');
                    $('#edit-nationality').val(response.nationality).trigger('change');
                    $('input[name="salary"]').val(response.salary);
                    $('textarea[name="description"]').val(response.description);
                    $('#edit-avatar-img').attr('src', response.avatar ? '/storage/' + response.avatar : '/blank.png');

                    if (response.gender == 1) {
                        $('#edit-male').prop('checked', true); // Male radio button
                    } else if (response.gender == 0) {
                        $('#edit-female').prop('checked', true); // Female radio button
                    }

                    // Reinitialize Select2 for the religion, language, and nationality dropdowns
                    $('#edit-religion').select2();
                    $('#edit-language').select2();
                    $('#edit-nationality').select2();

                    // Show the edit modal
                    $('#editHousekeeperModal').modal('show');
                },
                error: function (xhr) {
                    if (xhr.status === 404) {
                        toastr.error('Housekeeper not found', 'Error');
                    } else {
                        toastr.error('An error occurred while fetching data', 'Error');
                    }
                }
            });
        });
    });
</script>





{{--    update--}}
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


            var editForm = $('#editForm');

            // Validate the edit form
            editForm.validate({
                rules: {
                    name: {
                        required: true
                    },
                    phone: {
                        required: true,
                        regex: /^[5][0-9]{8}$/ // 9-digit number starting with '5'
                    },

                    company: {
                        required: true
                    },
                    description: {
                        required: true
                    },
                    religion: {
                        required: true
                    },
                    nationality: {
                        required: true
                    },
                    language: {
                        required: true
                    },
                    experience: {
                        required: true,
                        number: true
                    },
                    salary: {
                        required: true,
                        number: true
                    }
                },
                messages: {
                    phone: {
                        regex: "{{ trans('main.phone-error') }}" // Custom error message for invalid phone format
                    }
                },
                submitHandler: function () {
                    // Show spinner when form is submitted
                    $('#editSpinner').show();
                    $('#editSubmit').prop('disabled', true); // Disable the submit button to prevent multiple submissions

                    setTimeout(function () {
                        var formData = new FormData(editForm[0]);

                        $.ajax({
                            url: "/admin/housekeepers/update", // Adjust this URL as per your routes
                            method: 'POST',
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function (response) {
                                $('#editSpinner').hide(); // Hide spinner on success
                                $('#editSubmit').prop('disabled', false);
                                toastr.success(response.message, '{{ trans('messages.success') }}');
                                editForm[0].reset();
                                $('#editHousekeeperModal').modal('hide');
                                $('#table').DataTable().ajax.reload(); // Reload the DataTable
                            },
                            error: function (xhr) {
                                $('#editSpinner').hide(); // Hide spinner on error
                                $('#editSubmit').prop('disabled', false);
                                if (xhr.responseJSON && xhr.responseJSON.errors) {
                                    $.each(xhr.responseJSON.errors, function (key, value) {
                                        toastr.error(value[0], '{{ trans('messages.error') }}');
                                    });
                                } else {
                                    toastr.error('{{ trans('messages.error') }}');
                                }
                            }
                        });
                    }, 700);
                }
            });
        });

    </script>

@endpush
