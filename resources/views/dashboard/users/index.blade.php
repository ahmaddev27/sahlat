@extends('dashboard.layouts.master',['title'=>trans('dashboard_aside.users')])

@push('css')
    <link rel="stylesheet" type="text/css" href="{{url('app-assets/css-rtl/pages/page-auth.css')}}">


@endpush
@section('left')
    <div class="content-header-right text-md-right col-md-3 col-12 d-md-block d-none">
        <div class="form-group breadcrumb-right">
            <button class="btn btn-primary waves-effect waves-float waves-light btn-sm"
                    data-toggle="modal" data-target="#inlineForm"
                    title="{{trans('user.new-user')}}"><i data-feather="plus"></i></button>
        </div>
    </div>



@endsection

@section('content')

    <div class="content-body">
        <div class="card">

            <div class="card-datatable table-responsive p-2 ">
                <div class="row">
                    <div class="col-sm-3">
                        <code  style="font-family: 'Tajawal';" class="text-body p-1 m-2">{{trans('main.send-notifications')}}</code>
                        <button type="button"  title="{{trans('notifications.new_notification')}}" id="notify-users-btn" disabled class="btn btn-icon btn-icon rounded-circle btn-warning waves-effect waves-float waves-light">
                            <i  class="font-medium-3"  data-feather='bell'></i>

                        </button>


                    </div>

                    <div class="col-sm-3" style="margin-bottom: -60px">
                        <label>{{trans('company.address')}} </label>

                        <select id="city-filter" class="select2 form-control ">
                            <option selected disabled>{{ trans('main.change') }}</option>
                            <option value="">{{trans('main.all')}}</option>
                            @foreach(cities() as $key=>$city)
                                <option value="{{$key}}">{{$city}}</option>
                            @endforeach
                        </select>

                    </div>





                </div>



                <!-- Notification Modal -->
                <div class="modal fade text-left" id="notificationModal" tabindex="-1" role="dialog" aria-labelledby="notificationModalLabel"
                     aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="myModalLabel33">{{ trans('notifications.new_notification') }}</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="{{ trans('buttons.close') }}">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-2">
                                    <label for="notification-title" class="form-label">{{ trans('notifications.title') }}</label>
                                    <input type="text" id="notification-title" class="form-control" placeholder="{{ trans('notifications.enter_title') }}">
                                    <div class="invalid-feedback">
                                        {{ trans('notifications.title_required') }}
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="notification-text" class="form-label">{{ trans('notifications.message') }}</label>
                                    <textarea id="notification-text" class="form-control" rows="4" placeholder="{{ trans('notifications.enter_message') }}"></textarea>
                                    <div class="invalid-feedback">
                                        {{ trans('notifications.message_required') }}
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary mb-1 mb-sm-0 mr-0 mr-sm-1" id="send-notification-btn">
                                    <div id="spinner_notification" class="spinner-border spinner-border-sm text-light" role="status" style="display: none;">
                                        <span class="sr-only"></span>
                                    </div>
                                    {{ trans('main.save') }}
                                </button>

                            </div>
                        </div>
                    </div>
                </div>




                <table class="table" id="table">

                    <thead class="thead-light">
                    <tr>
                        <th>
                            <input type="checkbox" id="select-all"></th>


                        <th style="width: 30%">{{trans('user.name')}}</th>
                        <th>{{trans('user.phone')}}</th>
{{--                        <th>{{trans('user.email')}} </th>--}}
{{--                        <th style="direction: ltr">{{trans('user.phone')}} </th>--}}
                        <th style="width: 15%;">{{trans('user.location')}} </th>
                        <th>{{trans('user.gender')}} </th>
                        <th>{{trans('user.created_at')}}</th>
                        <th>{{trans('user.action')}}</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>

    </div>




    @push('js')


            {{--datatable--}}
            <script>
                $(document).ready(function () {
                   var table= $('#table').DataTable({
                        processing: false,
                        serverSide: true,


                       dom: 'frtilp',

                       ajax: {
                           url: "{{ route('users.list') }}",
                           data: function (d) {
                               d.city = $('#city-filter').val();
                           }
                       },

                        order: [[6, 'desc']],
                        columns: [
                            {data: 'checkbox', name: 'checkbox', orderable: false, searchable: false},
                            {data: 'name', name: 'name'},
                            {data: 'phone', name: 'phone'},
                            // {data: 'email', name: 'email'},
                            // {data: 'phone', name: 'phone'},
                            {data: 'location', name: 'location'},
                            {data: 'gender', name: 'gender'},
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
                            "url": "https://cdn.datatables.net/plug-ins/2.1.8/i18n/ar.json"
                        },
                        @endif
                    });



                    $('#city-filter').on('change', function () {
                        table.ajax.reload();
                    });
                });
            </script>

        {{-- images preview--}}
        <script>
            // Reusable function for image change preview
            function previewImage(inputElement, targetImgElement) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    targetImgElement.attr('src', e.target.result);
                };

                if (inputElement.files && inputElement.files[0]) {
                    reader.readAsDataURL(inputElement.files[0]);
                } else {
                    // If no file is selected, show the default image
                    targetImgElement.attr('src', '/blank.png'); // Adjust this path if necessary
                }
            }

            $(document).ready(function() {
                var changeLogo = $('#add-avatar'),
                    logoAvatar = $('#add-avatar-img');

                // Change preview for add avatar
                if (changeLogo.length) {
                    changeLogo.on('change', function () {
                        previewImage(this, logoAvatar);
                    });
                }

                var changeIcon = $('#edit-avatar'),
                    iconAvatar = $('#edit-avatar-preview');

                // Change preview for edit avatar
                if (changeIcon.length) {
                    changeIcon.on('change', function () {
                        previewImage(this, iconAvatar);
                    });
                }
            });
        </script>


{{--        notify--}}
        <script>
            $(document).ready(function () {
                let selectedUsers = [];

                // "Select All" checkbox functionality
                $('#select-all').on('change', function () {
                    const isChecked = $(this).is(':checked');
                    $('.user-checkbox').prop('checked', isChecked);
                    updateSelectedUsers();
                });

                // Update selected users when individual checkboxes are toggled
                $(document).on('change', '.user-checkbox', function () {
                    if (!$(this).is(':checked')) {
                        // If any checkbox is unchecked, uncheck "Select All"
                        $('#select-all').prop('checked', false);
                    } else if ($('.user-checkbox:checked').length === $('.user-checkbox').length) {
                        // If all checkboxes are checked, check "Select All"
                        $('#select-all').prop('checked', true);
                    }
                    updateSelectedUsers();
                });

                // Function to update the selected users array and button state
                function updateSelectedUsers() {
                    selectedUsers = $('.user-checkbox:checked').map(function () {
                        return $(this).val();
                    }).get();
                    $('#notify-users-btn').prop('disabled', selectedUsers.length === 0);
                }


                // Open the modal
                $('#notify-users-btn').on('click', function () {
                    $('#notification-title').val('');
                    $('#notification-text').val('');
                    $('.invalid-feedback').hide();
                    $('#notificationModal').modal('show');
                });

                // Validate and send notification
                $('#send-notification-btn').on('click', function () {
                    const title = $('#notification-title').val().trim();
                    const message = $('#notification-text').val().trim();
                    let isValid = true;

                    // Spinner and button disabling
                    $('#spinner_notification').show();
                    $('#send-notification-btn').prop('disabled', true);

                    // Validate title
                    if (!title) {
                        $('#notification-title').addClass('is-invalid');
                        $('#notification-title').siblings('.invalid-feedback').show();
                        isValid = false;
                    } else {
                        $('#notification-title').removeClass('is-invalid');
                        $('#notification-title').siblings('.invalid-feedback').hide();
                    }

                    // Validate message
                    if (!message) {
                        $('#notification-text').addClass('is-invalid');
                        $('#notification-text').siblings('.invalid-feedback').show();
                        isValid = false;
                    } else {
                        $('#notification-text').removeClass('is-invalid');
                        $('#notification-text').siblings('.invalid-feedback').hide();
                    }

                    if (!isValid) {
                        $('#spinner_notification').hide();
                        $('#send-notification-btn').prop('disabled', false);
                        return;
                    }

                    // Ajax request
                    $.ajax({
                        url: '{{ route("users.notify") }}',
                        method: 'POST',
                        data: {
                            user_ids: selectedUsers,
                            title: title,
                            message: message,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function (response) {
                            $('#spinner_notification').hide(); // Hide spinner on success
                            $('#send-notification-btn').prop('disabled', false);
                            toastr.success(response.message, '{{ trans('messages.success') }}');

                            // Reset form and close modal
                            $('#notification-title').val('');
                            $('#notification-text').val('');
                            $('#notificationModal').modal('hide');

                            // Reload DataTable
                            $('#table').DataTable().ajax.reload();
                            $('#notify-users-btn').prop('disabled', true);
                            $('#select-all').prop('checked', false);

                            $('.user-checkbox').prop('checked', false);

                        },
                        error: function (xhr) {
                            $('#spinner_notification').hide(); // Hide spinner on error
                            $('#send-notification-btn').prop('disabled', false);
                            if (xhr.responseJSON.errors) {
                                $.each(xhr.responseJSON.errors, function (key, value) {
                                    toastr.error(value[0], '{{ trans('messages.error') }}');
                                });
                            } else {
                                toastr.error('{{ trans('messages.error') }}');
                            }
                        }
                    });
                });
            });
        </script>




    @endpush


    @include('dashboard.users.add')
    @include('dashboard.users.edit')

@stop
