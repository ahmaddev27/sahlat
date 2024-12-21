{{--    add form--}}
<div class="modal fade text-left" id="inlineForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered  modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel33">{{trans('company.new-company')}}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="#" id="form">
                @csrf


                <div class="modal-body">

                    <div class="row">


                        <div class="col-4">
                            <label>{{trans('company.name')}} </label>
                            <div class="form-group">
                                <input type="text" placeholder="{{trans('company.name')}}" name="name"
                                       class="form-control"/>
                            </div>
                        </div>

                        <div class="col-4">


                            <label>{{trans('company.email')}} </label>
                            <div class="form-group">
                                <input type="text" placeholder="{{trans('company.email')}}" name="email"
                                       class="form-control"/>
                            </div>
                        </div>

                        <div class="col-4">
                            <label>{{trans('company.password')}} </label>


                            <div class="form-group mb-2">
                                <div class="input-group input-group-merge form-password-toggle">
                                    <input class="form-control form-control-merge" type="password"
                                           name="password" placeholder="{{trans('auth.password_filed')}}"
                                           aria-describedby="password" tabindex="2"/>
                                    <div class="input-group-append"><span class="input-group-text cursor-pointer"><i
                                                data-feather="eye"></i></span></div>
                                </div>
                                <span class="text-danger mt-2" id="passwordError"></span>
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

                            <label>{{trans('company.address')}} </label>
                            <div class="form-group">
                                <select name="address" class="select2 form-control">
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
                                <input type="number" placeholder="{{trans('company.hourly_price')}}" name="hourly_price"
                                       class="form-control mb-1"/>
                                <code style="font-family: Tajawal;">{{trans('company.hourly_note')}}</code>

                            </div>
                        </div>
                        <div class="col-6">
                            <label>{{trans('company.experience')}} </label>
                            <div class="form-group">
                                <input type="number" placeholder="{{trans('company.experience')}}" name="experience"
                                       class="form-control"/>
                            </div>
                        </div>

                        <div class="col-4">
                            <label>{{trans('company.long')}} </label>
                            <div class="form-group">
                                <input type="text" placeholder="{{trans('company.long')}}" name="long"
                                       class="form-control"/>
                            </div>
                        </div>

                        <div class="col-4">

                            <label>
                                {{trans('company.lat')}} </label>
                            <div class="form-group">
                                <input type="text" placeholder="{{trans('company.lat')}}" name="lat"
                                       class="form-control"/>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="justify-content-center">
                                <div class="col-4">
                                    <div class="media mb-2">
                                        <div class="media-body mt-50">
                                            <h4>{{ trans('company.avatar') }}</h4>
                                            <div class="col-12 d-flex mt-1 px-0">
                                                <label class="btn btn-primary mr-75 mb-0" for="add-avatar">
                                                    <span
                                                        class="d-none d-sm-block">{{ trans('settings.change') }}</span>
                                                    <input class="form-control" type="file" name="avatar"
                                                           id="add-avatar"
                                                           hidden accept="image/png, image/jpeg, image/jpg"/>
                                                    <span class="d-block d-sm-none"><i class="mr-0"
                                                                                       data-feather="edit"></i></span>
                                                </label>
                                            </div>
                                            <img src="{{ url('blank.png') }}" alt="avatar" id="add-avatar-img"
                                                 class="user-avatar icon users-avatar-shadow rounded mr-2 my-25 cursor-pointer"
                                                 height="90" width="90"/>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="col-12">
                            <label>
                                {{trans('company.bio')}} </label>
                            <div class="form-group">
                                <textarea placeholder="{{trans('company.bio')}}" name="bio" rows="5"
                                          class="form-control"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">


                        <button type="submit" class="btn btn-primary mb-1 mb-sm-0 mr-0 mr-sm-1" id="submit">
                            <div id="spinner" class="spinner-border spinner-border-sm text-light" role="status"
                                 style="display: none;">
                                <span class="sr-only"></span>
                            </div>
                            {{ trans('main.save') }}
                        </button>

                    </div>
                </div>
            </form>
        </div>
    </div>
</div>



@push('js')

    {{-- add--}}
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

            // Validate the form
            form.validate({
                rules: {
                    name: {
                        required: true
                    },

                    email: {
                        required: true,
                        email: true
                    },
                    username: {
                        required: true
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
                    experience: {
                        required: true,
                        number: true
                    },
                    phone: {
                        required: true,
                        regex: /^[5][0-9]{8}$/ // 9-digit number starting with '5'
                    },
                    address: {
                        required: true
                    },
                    password: {
                        required: true
                    },
                    avatar: {
                        required: true
                    }
                },
                messages: {
                    phone: {
                        regex: "{{ trans('main.phone-error') }}" // Custom error message for invalid phone format
                    }
                },

                submitHandler: function () {
                    // Show spinner when form is submitted
                    $('#spinner').show();
                    $('#submit').prop('disabled', true); // Disable the submit button to prevent multiple submissions

                    setTimeout(function () {
                        var formData = new FormData(form[0]);

                        $.ajax({
                            url: "{{ route('companies.store') }}",
                            method: 'POST',
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function (response) {
                                $('#spinner').hide(); // Hide spinner on success
                                $('#submit').prop('disabled', false);
                                toastr.success(response.message, '{{ trans('messages.success') }}');


                                $('#inlineForm').modal('hide');
                                form[0].reset();
                                $('#table').DataTable().ajax.reload();
                            },
                            error: function (xhr) {
                                $('#spinner').hide(); // Hide spinner on error
                                $('#submit').prop('disabled', false);
                                if (xhr.responseJSON.errors) {
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

            // Handle button click to trigger form submission
            $('#submit').click(function (e) {
                e.preventDefault();
                form.submit(); // Trigger validation and submission
            });
        });
    </script>

@endpush
