{{-- Add Form --}}
<div class="modal fade text-left" id="inlineForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel33">{{ trans('services.new-service') }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="#" id="form">
                @csrf
                <div class="modal-body">

                  <div class="row">
                      <div class="col-12">  <label>{{ trans('services.title') }}</label>
                          <div class="form-group">
                              <input type="text" placeholder="{{ trans('services.title') }}" name="title" class="form-control"/>
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
            var form = $('#form');

            // Validate the form
            form.validate({
                rules: {
                    title: {
                        required: true
                    },

                },

                submitHandler: function () {
                    // Show spinner when form is submitted
                    $('#spinner').show();
                    $('#submit').prop('disabled', true); // Disable the submit button to prevent multiple submissions

                    setTimeout(function () {
                        var formData = new FormData(form[0]);

                        $.ajax({
                            url: "{{ route('company.services.store') }}",
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
