<!-- Housekeeper Modal -->
<div class="modal fade" id="housekeeperModal" tabindex="-1" role="dialog" aria-labelledby="housekeeperModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="housekeeperModalLabel"> {{trans('housekeeper.Housekeepers')}}
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table" id="housekeeperTable">
                    <thead>
                    <tr>
                        <th>{{trans('housekeeper.name')}}</th>
                        <th>{{trans('housekeeper.avatar')}}</th>
{{--                        <th>{{trans('housekeeper.type')}}</th>--}}
                        <th>{{trans('housekeeper.salary')}}</th>
                        <th>{{trans('housekeeper.status')}}</th>

                    </tr>
                    </thead>
                    <tbody>


                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{trans('main.close')}}</button>
            </div>
        </div>
    </div>
</div>


@push('js')

    <script>
        $(document).on('click', '#housekeeper', function () {
            var modelId = $(this).attr('model_id');
            $.ajax({
                url: 'companies/housekeepers/' + modelId, // Adjust this to match your route
                method: 'GET',
                success: function (data) {
                    // Clear existing table data
                    $('#housekeeperTable tbody').empty();

                    data.forEach(function(housekeeper) {
                        $('#housekeeperTable tbody').append(`
                    <tr>
                        <td>${housekeeper.name}</td>
                        <td>
                            <img src="${housekeeper.avatar_url}" alt="${housekeeper.name}" style="width: 50px; height: 50px; border-radius: 50%;">
                        </td>

                        <td>${housekeeper.salary}</td>
                        <td><span class="badge ${housekeeper.status == 0 ? 'badge-success' : 'badge-primary'}">
                            ${housekeeper.status_text}
                            </span>
                        </td>
                    </tr>
                `);

                    });

                    // Show the modal
                    $('#housekeeperModal').modal('show'); // Adjust this to your modal's ID
                },
                error: function (xhr) {
                    console.error(xhr.responseText); // Log any errors
                }
            });
        });

    </script>

@endpush
