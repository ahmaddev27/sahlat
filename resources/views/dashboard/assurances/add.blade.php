{{-- Add Form --}}
<div class="modal fade text-left" id="inlineForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel33">{{ trans('assurances.new-assurance') }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="#" id="form">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <!-- Title Input -->
                        <div class="col-6">
                            <label>{{ trans('assurances.title') }}</label>
                            <div class="form-group">
                                <input type="text" placeholder="{{ trans('assurances.title') }}" name="title" class="form-control" />
                            </div>
                        </div>

                        <!-- Price Input -->
                        <div class="col-6">
                            <label>{{ trans('assurances.price') }}</label>
                            <div class="form-group">
                                <input type="number" placeholder="{{ trans('assurances.price') }}" name="price" class="form-control" />
                            </div>
                        </div>

                        <!-- Description Input -->
                        <div class="col-9">
                            <label>{{ trans('assurances.description') }}</label>
                            <div class="form-group">
                                <textarea placeholder="{{ trans('assurances.description') }}" rows="5" name="description" class="form-control"></textarea>
                            </div>
                        </div>

                        <!-- Avatar Upload -->
                        <div class="col-3 justify-content-center">
                            <div class="row justify-content-center">
                                <div class="col-12">
                                    <div class="media mb-2">
                                        <div class="media-body mt-50">
                                            <h4>{{ trans('housekeeper.avatar') }}</h4>
                                            <label class="btn btn-primary mr-75 mb-0" for="add-avatar">
                                                <span class="d-none d-sm-block">{{ trans('settings.change') }}</span>
                                                <input class="form-control" type="file" name="image" id="add-avatar" hidden accept="image/png, image/jpeg, image/jpg" />
                                            </label>
                                            <img src="{{ url('blank.png') }}" alt="avatar" id="add-avatar-img" class="user-avatar icon users-avatar-shadow rounded mr-2 my-25 cursor-pointer" height="90" width="90" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Company Input -->
                        <div class="col-9">
                            <label>{{ trans('assurances.company') }}</label>
                            <div class="form-group">
                                <input placeholder="{{ trans('assurances.company') }}"  name="company" class="form-control">
                            </div>
                        </div>

                        <!-- Add Form Section -->
                        <div class="col-3 justify-content-center">
                            <div class="row justify-content-center">
                                <div class="col-12">
                                    <div class="media mb-2">
                                        <div class="media-body mt-50">
                                            <h4>{{ trans('assurances.company-logo') }}</h4>
                                            <label class="btn btn-primary mr-75 mb-0" for="add-logo">
                                                <span class="d-none d-sm-block">{{ trans('settings.change') }}</span>
                                                <input class="form-control" type="file" name="company_logo" id="add-logo" hidden accept="image/png, image/jpeg, image/jpg" />
                                            </label>
                                            <img src="{{ url('blank.png') }}" alt="company logo" id="add-logo-img" class="user-avatar icon users-avatar-shadow rounded mr-2 my-25 cursor-pointer" height="90" width="90" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
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
{{--    submit--}}
    <script>
        $(document).ready(function () {
            var form = $('#form');

            // Validate the form
            form.validate({
                rules: {
                    title: {
                        required: true
                    },
                    image: {
                        required: true,
                    },
                    company_logo: {
                        required: true,
                    },
                      company: {
                        required: true,
                    },

                    price: {
                        required: true,
                        number: true,
                    },

                },


                submitHandler: function () {
                    // Show spinner when form is submitted
                    $('#spinner').show();
                    $('#submit').prop('disabled', true); // Disable the submit button to prevent multiple submissions

                    setTimeout(function () {
                        var formData = new FormData(form[0]);

                        $.ajax({
                            url: "{{ route('assurances.store') }}",
                            method: 'POST',
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function (response) {
                                $('#spinner').hide(); // Hide spinner on success
                                $('#submit').prop('disabled', false);
                                toastr.success(response.message, '{{ trans('messages.success') }}');

                                form[0].reset();

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
