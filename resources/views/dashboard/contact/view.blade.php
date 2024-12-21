
<div class="modal fade text-left" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content ">
            <div class="modal-header">
                <h4 class="modal-title" id="editModalLabel">{{ trans('contacts.view') }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>


            <div class="card-body">
                <h4 class="card-title" id="title"></h4>
                <div class="media">
                    <div class="avatar mr-50">
                        <img id="user-avatar" src="" alt="Avatar" width="50" height="50">
                    </div>
                    <div class="media-body mt-2">
                        <small><a href="javascript:void(0);" class="text-body" id="user-name"></a></small>
                        <span class="text-muted ml-50 mr-25">|</span>
                        <small class="text-muted" id="data"></small>
                    </div>
                </div>


                <h6 class="card-text mb-1 p-2" id="text">


                </h6>






                </div>
            </div>

        </div>
    </div>



@push('js')

    {{--        fetch--}}
    <script>
        $(document).on('click', '#view', function() {
            var modelId = $(this).attr('model_id');
            $.ajax({
                url: '/admin/contacts/fetch/' + modelId, // Adjust this to match your route
                method: 'GET',
                success: function(data) {
                    if (data) {
                        // Populate modal fields
                        $('#title').text(data.title || 'No Title');
                        $('#user-avatar').attr('src', data.avatar);
                        $('#user-name').text(data.user_name || 'Unknown');
                        $('#data').text(data.date || 'Unknown Date');
                        $('#text').text(data.text || 'No details available.');

                        // Open the modal
                        $('#viewModal').modal('show');
                    }
                },
            });
        });
    </script>


@endpush

