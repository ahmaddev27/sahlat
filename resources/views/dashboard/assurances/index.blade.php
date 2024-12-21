@extends('dashboard.layouts.master',['title'=>trans('dashboard_aside.assurances')])



@section('left')
    <div class="content-header-right text-md-right col-md-3 col-12 d-md-block d-none">
        <div class="form-group breadcrumb-right">
            <button class="btn btn-primary waves-effect waves-float waves-light btn-sm"
                    data-toggle="modal" data-target="#inlineForm"
                    title="{{trans('assurances.new-service')}}"><i data-feather="plus"></i></button>
        </div>
    </div>

@endsection

@section('content')

    <div class="content-body">

        <div class="card">



            <div class="card-datatable table-responsive p-2 ">


                <div class="row">
                    <div class="card-header p-1 col-sm-3" style="margin-bottom: -60px">
                        <div class="">{{trans('assurances.status')}}</div>

                        <select id="status" class="select2 form-control d-inline-block">
                            <option selected disabled>{{ trans('main.change') }}</option>
                            <option value="">{{ trans('main.all') }}</option>
                            <option value="1">{{ trans('main.active') }}</option>
                            <option value="0">{{ trans('main.not-active') }}</option>
                        </select>

                    </div>

                </div>



                <table class="table" id="table">
                    <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>{{trans('assurances.title')}}</th>
                        <th>{{trans('assurances.description')}}</th>
                        <th>{{trans('assurances.price')}}</th>
                        <th>{{trans('assurances.status')}}</th>
                        <th>{{trans('assurances.action')}}</th>
                        <th>{{trans('assurances.action')}}</th>

                    </tr>
                    </thead>
                </table>
            </div>
        </div>

    </div>


    @include('dashboard.assurances.add')
    @include('dashboard.assurances.edit')


    @push('js')

        {{--datatable--}}
        <script>
            $(document).ready(function () {
                let table = $('#table').DataTable({
                    processing: false,
                    serverSide: true,
                    dom: 'frtilp',
                    ajax: {
                        url: "{{ route('assurances.list') }}",
                        data: function (d) {
                            d.status = $('#status').val(); // Pass status filter value
                        }
                    },
                    order: [[5, 'desc']],
                    columns: [
                        { data: 'DT_RowIndex', name: 'id'},
                        { data: 'title', name: 'title'},
                        { data: 'description', name: 'description'},
                        { data: 'price', name: 'price'},
                        { data: 'status', name: 'status' },
                        {data: 'created_at', name: 'created_at', visible: false}, // Hidden column for ordering

                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false,

                        },
                    ],
                    @if(App::getLocale() == 'ar')
                    language: {
                        url: "https://cdn.datatables.net/plug-ins/2.1.8/i18n/ar.json"
                    },
                    @endif
                });



                // Event listener for status filter
                $('#status').on('change', function () {
                    table.ajax.reload();
                });
            });

        </script>


        {{-- images preview--}}
        <script>
            // Reusable function for image change preview
            function previewImage(inputElement, targetImgElement) {
                if (inputElement.files && inputElement.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        $(targetImgElement).attr('src', e.target.result);
                    };
                    reader.readAsDataURL(inputElement.files[0]);
                } else {
                    $(targetImgElement).attr('src', '/blank.png'); // Default image
                }
            }

            $(document).ready(function () {
                // Use event delegation to handle dynamically loaded elements
                $(document).on('change', '#add-avatar', function () {
                    previewImage(this, '#add-avatar-img');
                });

                $(document).on('change', '#add-logo', function () {
                    previewImage(this, '#add-logo-img');
                });

                $(document).on('change', '#edit-avatar', function () {
                    previewImage(this, '#edit-avatar-img');
                });

                $(document).on('change', '#edit-logo', function () {
                    previewImage(this, '#edit-logo-img');
                });

            });
        </script>



        {{--status--}}
        <script>
            $(document).on("click", '.status-toggle', function (e) {
                e.preventDefault();
                var $this = $(this);
                var model_id = $this.attr('model_id');
                var route = $this.attr('route');

                Swal.fire({
                    title: '{{ trans('messages.confirm-update') }}',
                    text: '{{ trans('messages.sure?') }}',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: '{{ trans('messages.yes') }}',
                    cancelButtonText: '{{ trans('messages.cancel') }}',
                    customClass: {
                        confirmButton: 'btn btn-primary',
                        cancelButton: 'btn btn-secondary ml-1'
                    },
                    buttonsStyling: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Disable the button and show a loading spinner
                        $this.prop('disabled', true);
                        $('.spinner-border', $this).show();

                        $.ajax({
                            url: route,
                            type: 'POST',
                            dataType: 'json',
                            data: {
                                "_token": "{{ csrf_token() }}",
                                "id": model_id
                            },
                            success: function (response) {
                                // Check if the status was toggled successfully
                                if (response.success) {
                                    var newStatus = response.new_status;
                                    var newBadgeClass = newStatus === 1 ? 'badge-primary' : 'badge-danger';
                                    var newStatusText = response.status_text;

                                    $this.removeClass('badge-primary badge-danger').addClass(newBadgeClass);
                                    $this.text(newStatusText);
                                }

                                Swal.fire({
                                    title: '{{ trans('messages.updated') }}!',
                                    text: '{{ trans('messages.success-update') }}.',
                                    icon: 'success',
                                    confirmButtonText: '{{ trans('messages.close') }}',
                                    customClass: {
                                        confirmButton: 'btn btn-success'
                                    }
                                });

                                $('#table').DataTable().ajax.reload();

                                // Re-render Feather icons
                                feather.replace();
                            },
                            error: function () {
                                Swal.fire({
                                    title: '{{ trans('messages.not-updated') }}!',
                                    text: '{{ trans('messages.not-update-error') }}.',
                                    icon: 'error',
                                    confirmButtonText: '{{ trans('messages.close') }}',
                                });
                            },
                            complete: function () {
                                // Re-enable the button and hide the spinner after the AJAX call is complete
                                $this.prop('disabled', false);
                                $('.spinner-border', $this).hide();
                            }
                        });
                    }
                });
            });
        </script>

    @endpush

@stop
