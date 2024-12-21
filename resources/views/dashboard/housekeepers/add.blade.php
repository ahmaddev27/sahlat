{{-- Add Form --}}
<div class="modal fade text-left" id="inlineForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel33">{{ trans('housekeeper.new-housekeeper') }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="#" id="form">
                @csrf
                <div class="modal-body">


                    <div class="row">
                        <div class="col-6"><label>{{ trans('housekeeper.name') }}</label>
                            <div class="form-group">
                                <input type="text" placeholder="{{ trans('housekeeper.name') }}" name="name"
                                       class="form-control"/>
                            </div>
                        </div>

                        <div class="col-6">


                            <label>{{ trans('housekeeper.phone') }}</label>
                            <div class="input-group input-group-merge mb-1">
                                <div class="input-group-prepend">
                                    <span  class="input-group-text">AUE (+971)</span>
                                </div>
                                <input type="text" maxlength="9" placeholder="{{trans('user.phone')}}" name="phone" class="form-control"/>

                            </div>
                        </div>

                        <div class="col-6">
                            <label>{{ trans('housekeeper.company') }}</label>
                            <div class="form-group">
                                <select class="select2 form-control" name="company">
                                    <option selected disabled>{{ trans('housekeeper.select-company') }}</option>
                                    @foreach($companies as $company)
                                        <option value="{{ $company->id }}">{{ $company->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-6">
                            <label>{{ trans('company.experience') }}</label>
                            <div class="form-group">
                                <input type="number" placeholder="{{ trans('company.experience') }}" name="experience"
                                       class="form-control"/>
                            </div>
                        </div>




                        <div class="col-6">
                            <label>{{ trans('housekeeper.salary') }}</label>
                            <div class="form-group">
                                <input type="number" placeholder="{{ trans('housekeeper.salary') }}" name="salary"
                                       class="form-control"/>
                            </div>
                        </div>

                        <div class="col-6">
                            <label>{{trans('user.gender')}} </label>
                            <div class="demo-inline-spacing">
                                <div class="custom-control custom-radio">
                                    <input type="radio" value="1" id="customRadio1" name="gender"
                                           class="custom-control-input">
                                    <label class="custom-control-label"
                                           for="customRadio1">{{trans('user.male')}}</label>
                                </div>
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="customRadio2" value="0" name="gender"
                                           class="custom-control-input">
                                    <label class="custom-control-label"
                                           for="customRadio2">{{trans('user.female')}}</label>
                                </div>

                            </div>

                        </div>






                        <div class="col-4">
                            <label>{{ trans('housekeeper.nationality') }}</label>
                            <div class="form-group">
                                <select class="select2 form-control" name="nationality">
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
                                <select class="select2 form-control" name="language">
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
                                <select class="select2 form-control" name="religion">
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
                              <textarea cols="3" placeholder="{{ trans('housekeeper.description') }}" name="description"
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
                                            <label class="btn btn-primary mr-75 mb-0" for="add-avatar">
                                                <span class="d-none d-sm-block">{{ trans('settings.change') }}</span>
                                                <input class="form-control" type="file" name="avatar" id="add-avatar"
                                                       hidden accept="image/png, image/jpeg, image/jpg"/>
                                            </label>
                                            <img
                                                src="{{  url('blank.png') }}"
                                                alt="avatar" id="add-avatar-img"
                                                class="user-avatar icon users-avatar-shadow rounded mr-2 my-25 cursor-pointer"
                                                height="90" width="90"/>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>


                    </div>

                </div>


                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="submit">
                        <div id="spinner" class="spinner-border spinner-border-sm text-light" role="status"
                             style="display: none;"></div>
                        {{ trans('main.save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


@push('js')

    @push('js')


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

                var form = $('#form');

                // Initialize form validation
                form.validate({
                    rules: {
                        name: { required: true },
                        phone: {
                            required: true,
                            regex: /^[5][0-9]{8}$/ // 9-digit number starting with '5'
                        },
                        company: { required: true },
                        description: { required: true },
                        religion: { required: true },
                        nationality: { required: true },
                        language: { required: true },
                        experience: { required: true, number: true },
                        salary: { required: true, number: true },
                        avatar: { required: true }
                    },
                    messages: {
                        phone: {
                            regex: "{{ trans('main.phone-error') }}" // Custom error message for invalid phone format
                        }
                    },
                    submitHandler: function () {
                        // Show spinner and disable submit button
                        $('#spinner').show();
                        $('#submit').prop('disabled', true);

                        var formData = new FormData(form[0]);

                        $.ajax({
                            url: "{{ route('housekeepers.store') }}",
                            method: 'POST',
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function (response) {
                                $('#spinner').hide(); // Hide spinner
                                $('#submit').prop('disabled', false); // Re-enable submit button
                                toastr.success(response.message, '{{ trans('messages.success') }}');
                                form[0].reset();
                                $('#inlineForm').modal('hide');
                                $('#table').DataTable().ajax.reload(); // Reload DataTable
                            },
                            error: function (xhr) {
                                $('#spinner').hide(); // Hide spinner
                                $('#submit').prop('disabled', false); // Re-enable submit button

                                if (xhr.responseJSON && xhr.responseJSON.errors) {
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

                // Handle manual submit button click
                $('#submit').click(function (e) {
                    e.preventDefault();
                    form.submit(); // Trigger validation and submission
                });
            });
        </script>
    @endpush

@endpush
