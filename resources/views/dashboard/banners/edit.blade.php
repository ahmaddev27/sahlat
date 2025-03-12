<!-- Edit Banner Modal -->
<div class="modal fade" id="editBannerModal" tabindex="-1" role="dialog" aria-labelledby="editBannerModalLabel" aria-hidden="true">
    <div class="modal-dialog " role="document">
        <form id="editBannerForm" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="editBannerModalLabel">{{trans('settings.edit-banner')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <input type="hidden" id="bannerId" name="id">
                    <div class="form-group">
                        <label for="bannerTitle">{{trans('settings.title')}}</label>
                        <input type="text" class="form-control" id="bannerTitle" name="title" required>
                    </div>


                    <div class="mb-2 col-12 justify-content-center text-center">
                        <img
                            id="avatarPreview-edit-banner"
                            src="{{ url('blank.png') }}"
                            alt="icon avatar"
                            class="user-avatar icon users-avatar-shadow rounded mr-2 my-25 cursor-pointer"
                            height="175"
                        />
                        <div class="media-body mt-50">
                            <div class="col-12 d-flex mt-1 px-0 justify-content-center ">
                                <!-- File input (this will be triggered by the label) -->
                                <input
                                    type="file"
                                    class="form-control"
                                    name="image"
                                    id="change-image-avatar"
                                    accept="image/png, image/jpeg, image/jpg"
                                    onchange="previewAvatar()"
                                    hidden
                                />
                                <label class="btn btn-primary mr-75 mb-0" for="change-image-avatar">
    <span class="d-none d-sm-block">
        <i data-feather="edit"></i> {{ trans('settings.change') }}
    </span>
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
                                id="banner-edit">
                            <div id="banner_spinner" class="spinner-border spinner-border-sm text-light"
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
    function editBanner(bannerId) {
        // Fetch the banner data via AJAX or DOM (example shown with AJAX)
        $.ajax({
            url: `banners/${bannerId}`,
            method: 'GET',
            success: function(banner) {
                $('#bannerId').val(banner.id);
                $('#bannerTitle').val(banner.title);

                // Set the preview image to the current avatar
                $('#avatarPreview-edit-banner').attr('src', banner.image_url); // Use the correct image URL property

                $('#editBannerModal').modal('show');
            },
            error: function() {
                console.log('Failed to retrieve banner data.');
            }
        });
    }


</script>

{{--        edit banner--}}

<script>
    $('#editBannerForm').on('submit', function(event) {
        event.preventDefault();

        // Show spinner and disable button
        $('#banner_spinner').show();
        $('#banner-edit').prop('disabled', true);

        // Initialize FormData with the form
        var formData = new FormData(this);

        $.ajax({
            url: `update/banners/${$('#bannerId').val()}`,
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                $('#banner_spinner').hide();
                $('#banner-edit').prop('disabled', false);
                toastr.success(response.message, '{{ trans('messages.success-update') }}');
                $('#editBannerModal').modal('hide'); // Hide modal on success
                location.reload(); // Reload the page
            },
            error: function(xhr) {
                $('#banner_spinner').hide();
                $('#banner-edit').prop('disabled', false);
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



{{--        edit-bannerPreview--}}
<script>
    function previewAvatar() {
        var file = document.getElementById("change-image-avatar").files[0];
        if (file) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById("avatarPreview-edit-banner").src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    }

</script>

@endpush
