<!-- Add Banner Modal (Create) -->
<div class="modal fade" id="inlineForm" tabindex="-1" role="dialog" aria-labelledby="inlineFormLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form id="createBannerForm" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="inlineFormLabel">{{trans('settings.new-banner')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="bannerTitle">{{trans('settings.title')}}</label>
                        <input type="text" class="form-control" id="bannerTitle" name="title" required>
                    </div>

                    <div class="mb-2 col-12 justify-content-center text-center">
                        <img
                            id="avatarPreview-create-banner"
                            src="{{url('blank-banner.png')}}"
                            alt="icon avatar"
                            class="user-avatar icon users-avatar-shadow rounded mr-2 my-25 cursor-pointer"
                            height="175"
                        />
                        <div class="media-body mt-50 ">
                            <div class="col-12 d-flex mt-1 px-0 justify-content-center ">
                                <label class="btn btn-primary mr-75 mb-0" for="change-create-icon">
                                    <span class="d-none d-sm-block">
                                        <i data-feather="edit"></i> {{ trans('settings.change') }}
                                    </span>
                                    <input
                                        type="file"
                                        class="form-control"
                                        name="image"
                                        id="change-create-icon"
                                        hidden
                                        accept="image/png, image/jpeg, image/jpg"
                                        onchange="previewAvatarCreate()"
                                    />
                                    <span class="d-block d-sm-none">
                                        <i class="mr-0" data-feather="edit"></i>
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="col-12 d-flex flex-sm-row flex-column mt-2 justify-content-end">
                        <button type="submit" class="btn btn-primary mb-1 mb-sm-0 mr-0 mr-sm-1"
                                id="banner-create">
                            <div id="banner_create_spinner" class="spinner-border spinner-border-sm text-light"
                                 role="status" style="display: none;">
                                <span class="sr-only"></span>
                            </div>
                            {{ trans('main.save') }}
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@push('js')

{{--        fetch banner--}}
<script>

    $('#createBannerForm').on('submit', function(event) {
        event.preventDefault();

        // Show spinner and disable button
        $('#banner_create_spinner').show();
        $('#banner-create').prop('disabled', true);

        // Initialize FormData with the form data (including file)
        var formData = new FormData(this);

        $.ajax({
            url: '{{ route("settings.banners.store") }}',  // Update the route to your store banner route
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                $('#banner_create_spinner').hide();
                $('#banner-create').prop('disabled', false);
                toastr.success(response.message, '{{ trans('messages.success-create') }}');
                $('#inlineForm').modal('hide'); // Hide modal on success
                location.reload(); // Reload the page to reflect changes
            },
            error: function(xhr) {
                $('#banner_create_spinner').hide();
                $('#banner-create').prop('disabled', false);
                if (xhr.responseJSON.errors) {
                    $.each(xhr.responseJSON.errors, function(key, value) {
                        toastr.error(value[0], '{{ trans('messages.error') }}');
                    });
                } else {
                    toastr.error('{{ trans('messages.error') }}');
                }
            }
        });
    });

</script>


    <script>
        function previewAvatarCreate() {
            var file = document.getElementById("change-create-icon").files[0];
            if (file) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById("avatarPreview-create-banner").src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        }

    </script>
@endpush
