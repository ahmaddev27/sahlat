{{-- Add Form --}}
<div class="modal fade text-left" id="inlineForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33" aria-hidden="true">
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
                      <div class="col-6">  <label>{{ trans('housekeeper.name') }}</label>
                          <div class="form-group">
                              <input type="text" placeholder="{{ trans('housekeeper.name') }}" name="name" class="form-control"/>
                          </div></div>
                      <div class="col-6">
                          <label>{{ trans('housekeeper.phone') }}</label>
                          <div class="form-group">
                              <input type="number" placeholder="{{ trans('housekeeper.phone') }}" name="phone" class="form-control"/>
                          </div>
                      </div>

                      <div class="col-4">
                          <label>{{ trans('company.experience') }}</label>
                          <div class="form-group">
                              <input type="number" placeholder="{{ trans('company.experience') }}" name="experience"
                                     class="form-control"/>
                          </div>
                      </div>







                      <div class="col-4">

                          <label>{{ trans('housekeeper.salary') }}</label>
                          <div class="form-group">
                              <input type="number" placeholder="{{ trans('housekeeper.salary') }}" name="salary" class="form-control"/>
                          </div>
                      </div>


                      <div class="col-4">
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

                      {{-- Avatar --}}

                              <div class="col-4 text-center justify-content-start">
                                  <div class="media mb-2">
                                      <div class="media-body mt-50">
                                          <h4>{{ trans('housekeeper.avatar') }}</h4>
                                          <label class="btn btn-primary mr-75 mb-0" for="add-avatar">
                                              <span class="d-none d-sm-block">{{ trans('settings.change') }}</span>
                                              <input class="form-control" type="file" name="avatar" id="add-avatar" hidden accept="image/png, image/jpeg, image/jpg"/>
                                          </label>
                                          <img src="{{  url('blank.png') }}" alt="avatar" id="add-avatar-img" class="user-avatar icon users-avatar-shadow rounded mr-2 my-25 cursor-pointer" height="90" width="90"/>
                                      </div>
                                  </div>
                              </div>





                  </div>



                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="submit">
                        <div id="spinner" class="spinner-border spinner-border-sm text-light" role="status" style="display: none;"></div>
                        {{ trans('main.save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


@push('js')
    <script src="{{url('app-assets/vendors/js/forms/repeater/jquery.repeater.min.js')}}"></script>


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

                    name: { required: true },
                    phone: {
                        required: true,
                        regex: /^[5][0-9]{8}$/ // 9-digit number starting with '5'
                    },
                    description: { required: true },
                    religion: { required: true },
                    nationality: { required: true },
                    language: { required: true },
                    experience: { required: true, number: true },
                    salary: { required: true, number: true },
                    avatar: { required: true },



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
                            url: "{{ route('company.housekeepers.store') }}",
                            method: 'POST',
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function (response) {
                                $('#spinner').hide(); // Hide spinner on success
                                $('#submit').prop('disabled', false);
                                toastr.success(response.message, '{{ trans('messages.success') }}');


                                form[0].reset();
                                $('.invoice-repeater').repeater('setList', []);

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
