{{--    add form--}}
<div class="modal fade text-left" id="inlineForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content ">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel33">{{trans('user.new-user')}}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="#" id="form">
                @csrf
                <div class="modal-body">
                    <label>{{trans('user.name')}} </label>
                    <div class="form-group">
                        <input type="text" placeholder="{{trans('user.name')}}" name="name" class="form-control"/>
                    </div>


                    {{--                    <label>{{trans('user.email')}} </label>--}}
                    {{--                    <div class="form-group">--}}
                    {{--                        <input type="text" placeholder="{{trans('user.email')}}" name="email" class="form-control"/>--}}
                    {{--                    </div>--}}





                    <label>{{trans('user.phone')}} </label>
                    <div class="input-group input-group-merge mb-1">
                        <div class="input-group-prepend">
                            <span  class="input-group-text">AUE (+971)</span>
                        </div>
                        <input type="text" maxlength="9" placeholder="{{trans('user.phone')}}" name="phone" class="form-control"/>

                    </div>


                    <label>{{ trans('company.email') }} </label>
                    <div class="form-group">
                        <input type="text" placeholder="{{ trans('company.email') }}" name="email" class="form-control"  />
                    </div>


                    <label>{{trans('user.number_id')}} </label>
                    <div class="form-group">
                        <input id="add-number_id" type="text" placeholder="{{trans('user.number_id')}}" name="number_id"
                               class="form-control" maxlength="20"/>
                    </div>


                    <div class="row">
                        <div class="col-6">
                            <label>{{trans('user.gender')}} </label>
                            <div class="form-group">

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
                        </div>


                        <div class="col-6">
                            <label>{{trans('user.location')}} </label>
                            <div class="form-group mt-2">
                                <select class="select2 form-control" name="city">
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
                                    <div class="col-12 d-flex mt-1 px-0">
                                        <label class="btn btn-primary mr-75 mb-0" for="add-avatar">
                                            <span class="d-none d-sm-block">{{ trans('settings.change') }}</span>
                                            <input class="form-control" type="file" name="avatar" id="add-avatar" hidden
                                                   accept="image/png, image/jpeg, image/jpg"/>
                                            <span class="d-block d-sm-none"><i class="mr-0"
                                                                               data-feather="edit"></i></span>
                                        </label>
                                    </div>
                                    <img
                                        src="{{url('blank.png') }}"
                                        alt="avatar" id="add-avatar-img"
                                        class="user-avatar icon users-avatar-shadow rounded mr-2 my-25 cursor-pointer"
                                        height="90" width="90"/>
                                </div>
                            </div>
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
            </form>
        </div>
    </div>
</div>



@push('js')
    <script>
        document.getElementById('add-number_id').addEventListener('input', function (e) {
            let value = e.target.value.replace(/[^0-9]/g, ''); // إزالة الحروف غير الرقمية

            if (value.length > 3) value = value.slice(0, 3) + '-' + value.slice(3);
            if (value.length > 8) value = value.slice(0, 8) + '-' + value.slice(8);
            if (value.length > 16) value = value.slice(0, 16) + '-' + value.slice(16);

            // تقييد الطول الأقصى بما في ذلك الفواصل
            if (value.length > 18) value = value.slice(0, 18);

            e.target.value = value;
        });
    </script>


    {{-- add--}}
    <script>

        $(document).ready(function () {
            $.validator.addMethod(
                "regex",
                function (value, element, regexp) {
                    console.log("Validating:", value); // Log the value being validated
                    let re = new RegExp(regexp);
                    return this.optional(element) || re.test(value);
                },
                "Invalid format."
            );


            var form = $('#form');

            // Validate the form
            form.validate({
                rules: {
                    name: {
                        required: true
                    },

                    city: {
                        required: true
                    },
                    email: {
                        required: true,
                        email: true
                    },

                    number_id: {
                        required: true,
                        regex: /^784-\d{4}-\d{7}-\d{1}$/,
                    },



                    phone: {
                        required: true,
                        regex: /^[5][0-9]{8}$/ // 9-digit number starting with '5'
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
                    // Show spinner when form is submitted
                    $('#spinner').show();
                    $('#submit').prop('disabled', true); // Disable the submit button to prevent multiple submissions

                    setTimeout(function () {
                        var formData = new FormData(form[0]);

                        $.ajax({
                            url: "{{ route('users.store') }}",
                            method: 'POST',
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function (response) {
                                $('#spinner').hide(); // Hide spinner on success
                                $('#submit').prop('disabled', false);
                                toastr.success(response.message, '{{ trans('messages.success') }}');

                                $('#inlineForm').modal('hide');
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
