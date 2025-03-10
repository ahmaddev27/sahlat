@extends('company.layouts.master',['title'=>trans('dashboard_aside.profile')])

@push('css')
    <link rel="stylesheet" type="text/css" href="{{url('app-assets/css-rtl/plugins/forms/form-validation.css')}}">

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>


    <style>
        .float-end {
            display: flex;
            justify-content: flex-end;
        }
    </style>
@endpush

@php  $user=auth()->user();@endphp

@section('content')

    <!-- account setting page -->

    <section id="page-account-settings">
        <div class="row">

            <!-- left menu section -->
            <div class="col-md-3 mb-2 mb-md-0">
                <ul class="nav nav-pills flex-column nav-left">
                    <!-- general -->
                    <li class="nav-item">
                        <a class="nav-link active" id="account-pill-general" data-toggle="pill" href="#account-vertical-general" aria-expanded="true">
                            <i data-feather="user" class="font-medium-3 mr-1"></i>
                            <span class="font-weight-bold">{{trans('profile.personal')}}</span>
                        </a>
                    </li>
                    <!-- change password -->
                    <li class="nav-item">
                        <a class="nav-link" id="account-pill-password" data-toggle="pill" href="#account-vertical-password" aria-expanded="false">
                            <i data-feather="lock" class="font-medium-3 mr-1"></i>
                            <span class="font-weight-bold">{{trans('profile.password')}}</span>
                        </a>
                    </li>

                </ul>
            </div>
            <!--/ left menu section -->

            <!-- right content section -->
            <div class="col-md-9">
                <div class="card">
                    <div class="card-body">
                        <div class="tab-content">
                            <!-- general tab -->
                            <div role="tabpanel" class="tab-pane active" id="account-vertical-general" aria-labelledby="account-pill-general" aria-expanded="true">

                                <!-- form -->
                                <form class="validate-form mt-2" id="settingsForm">
                                    @csrf
                                    <div class="row">
                                        <!-- header media -->
                                        <div class="mb-2 col-12 float-end ">
                                            <img
                                                src="{{ $user->getAvatar() }}"
                                                alt="icon avatar"
                                                class="user-avatar icon users-avatar-shadow rounded mr-2 my-25 cursor-pointer"
                                                height="90" width="90"/>
                                            <div class="media-body mt-50">
                                                <div class="col-12 d-flex mt-1 px-0">
                                                    <label class="btn btn-primary mr-75 mb-0"
                                                           for="change-icon">
                                                        <span class="d-none d-sm-block"><i data-feather="edit"></i> {{trans('settings.change')}}</span>
                                                        <input class="form-control" type="file" name="avatar"
                                                               id="change-icon" hidden
                                                               accept="image/png, image/jpeg, image/jpg"/>
                                                        <span class="d-block d-sm-none">
                                                            <i class="mr-0" data-feather="edit"></i></span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <!--/ header media -->

                                        <div class="col-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="account-username">{{trans('profile.name')}}</label>
                                                <input type="text" class="form-control" id="account-username" name="name" placeholder="" value="{{auth()->user()->name}}" />
                                            </div>
                                        </div>

                                        <div class="col-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="account-e-mail">{{trans('profile.email')}}</label>
                                                <input type="email" class="form-control" id="account-e-mail" name="email" placeholder="Email" value="{{$user->email}}" />
                                            </div>
                                        </div>

                                        <div class="col-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="account-e-mail">{{trans('profile.phone')}}</label>
                                                <input type="number" class="form-control" id="account-e-mail" name="phone" placeholder="{{trans('profile.phone')}}" value="{{$user->phone}}" />
                                            </div>
                                        </div>

                                        <div class="col-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="account-e-mail">{{trans('company.experience')}}</label>
                                                <input type="number" class="form-control" id="experience" name="experience" placeholder="{{trans('company.experience')}}" value="{{$user->experience}}" />
                                            </div>
                                        </div>

                                        <!-- Latitude and Longitude input fields -->
                                        <div class="col-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="account-e-mail">{{trans('company.long')}}</label>
                                                <input type="text" class="form-control" id="long" name="long" placeholder="{{trans('company.long')}}" value="{{$user->long}}" onclick="openMap('long')" readonly />
                                            </div>
                                        </div>

                                        <div class="col-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="account-e-mail">{{trans('company.lat')}}</label>
                                                <input type="text" class="form-control" id="lat" name="lat" placeholder="{{trans('company.lat')}}" value="{{$user->lat}}" onclick="openMap('lat')" readonly />
                                            </div>
                                        </div>

                                        <!-- Modal for the map -->
                                        <div id="mapModal" class="modal" tabindex="-1" role="dialog" aria-labelledby="mapModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content ">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="mapModalLabel">{{trans('main.Select Location')}}</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div id="map" style="height: 600px;"></div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-primary" id="saveLocation">{{trans('main.Save Location')}}</button>
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{trans('main.close')}}</button>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>




                                        <div class="col-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="account-e-mail">{{trans('company.hourly_price')}}</label>

                                                <input type="number" placeholder="{{trans('company.hourly_price')}}" value="{{$user->hourly_price}}" id="hourly_price" name="hourly_price" class="form-control mb-1"/>


                                            </div>
                                            <code class="mx-2" style="font-family: Tajawal;">{{trans('company.hourly_note')}}</code>

                                        </div>


                                        <div class="col-6 col-sm-6 ">
                                            <div class="form-group">
                                                <label>{{trans('company.address')}} </label>

                                                <select name="address" class="select2 form-control">
                                                        <option selected disabled>{{trans('company.address')}}</option>
                                                        @foreach(cities() as $key=>$city)
                                                            <option value="{{$key}}" @if($key==$user->address) selected @endif>{{$city}}</option>
                                                        @endforeach
                                                    </select>

                                            </div>

                                        </div>


                                         <div class="col-12 col-sm-12 mt-2">
                                            <div class="form-group">
                                                <label for="account-e-mail">{{trans('company.bio')}}</label>

                                                <textarea rows="5" placeholder="{{trans('company.bio')}}" name="bio"  class="form-control">{{$user->bio}}</textarea>

                                            </div>

                                        </div>






                                        <div class="col-12 float-end">
                                            <button type="button" class="btn btn-primary mb-1 mb-sm-0 mr-0 mr-sm-1" id="submitBtn">
                                                <div id="spinner" class="spinner-border spinner-border-sm text-light" role="status" style="display: none;">
                                                    <span class="sr-only"></span>
                                                </div>
                                                {{ trans('main.update') }}
                                            </button>
                                        </div>


                                    </div>
                                </form>
                                <!--/ form -->
                            </div>
                            <!--/ general tab -->

                            <!-- change password -->
                            <div class="tab-pane fade" id="account-vertical-password" role="tabpanel" aria-labelledby="account-pill-password" aria-expanded="false">
                                <!-- form -->
                                <form class="validate-form" id="passwordForm">
                                    @csrf
                                    <div class="row">
                                        <div class="col-12 col-sm-6">
                                            <div class="form-group">
                                                <label for="account-new-password">{{trans('profile.new-password')}}</label>
                                                <div class="input-group form-password-toggle input-group-merge">
                                                    <input type="password" id="account-new-password" name="password" class="form-control" placeholder="{{trans('profile.new-password')}}" />
                                                    <div class="input-group-append">
                                                        <div class="input-group-text cursor-pointer">
                                                            <i data-feather="eye"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6">
                                            <div class="form-group">
                                                <label for="account-retype-new-password">{{trans('profile.conf-password')}}</label>
                                                <div class="input-group form-password-toggle input-group-merge">
                                                    <input type="password" class="form-control" id="account-retype-new-password" name="password_confirmation" placeholder="{{trans('profile.conf-password')}}" />
                                                    <div class="input-group-append">
                                                        <div class="input-group-text cursor-pointer"><i data-feather="eye"></i></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 float-end">
                                            <div class="col-12 float-end">
                                                <button type="button" class="btn btn-primary mb-1 mb-sm-0 mr-0 mr-sm-1" id="submitBtn-password">
                                                    <div id="spinner-password" class="spinner-border spinner-border-sm text-light" role="status" style="display: none;">
                                                        <span class="sr-only"></span>
                                                    </div>
                                                    {{ trans('main.update') }}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <!--/ form -->
                            </div>
                            <!--/ change password -->


                        </div>
                    </div>
                </div>
            </div>
            <!--/ right content section -->
        </div>
    </section>
    <!-- / account setting page -->



@stop


@push('js')

    <script src="{{url('app-assets/vendors/js/forms/validation/jquery.validate.min.js')}}"></script>

    @if(app()->getLocale() === 'ar')
        <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/localization/messages_ar.js"></script>
    @endif


{{--    map location--}}
    <script>
        var map;
        var marker;
        var currentInput = '';

        // Function to initialize the map
        function initializeMap(latitude = 51.505, longitude = -0.09) {
            // Create the map centered at the specified coordinates
            map = L.map('map').setView([latitude, longitude], 13); // Default zoom level 13

            // Add OpenStreetMap tile layer (you can try other tile providers if necessary)
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            // Listen for click on the map to place a marker
            map.on('click', function(e) {
                var lat = e.latlng.lat;
                var lng = e.latlng.lng;

                // Place a marker at the clicked position
                if (marker) {
                    marker.setLatLng(e.latlng);
                } else {
                    marker = L.marker(e.latlng).addTo(map);
                }

                // Update the input fields with the selected latitude and longitude
                document.getElementById('lat').value = lat.toFixed(6);
                document.getElementById('long').value = lng.toFixed(6);
            });
        }

        // Function to open the map modal
        function openMap(inputType) {
            currentInput = inputType;
            // Try to get the user's current location
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    var userLat = position.coords.latitude;
                    var userLng = position.coords.longitude;

                    // Initialize the map with the user's location
                    if (!map) {
                        initializeMap(userLat, userLng);
                    } else {
                        map.setView([userLat, userLng], 13); // Zoom level 13 is typically appropriate
                    }

                    // Place a marker at the user's location
                    if (marker) {
                        marker.setLatLng([userLat, userLng]);
                    } else {
                        marker = L.marker([userLat, userLng]).addTo(map);
                    }

                    // Populate the input fields with the current location
                    document.getElementById('lat').value = userLat.toFixed(6);
                    document.getElementById('long').value = userLng.toFixed(6);
                }, function() {
                    // If geolocation fails, fallback to a default location (e.g., London)
                    console.log("Geolocation failed, fallback to default location.");
                    if (!map) {
                        initializeMap(); // Default to London if geolocation is not available
                    }
                });
            } else {
                // If geolocation is not supported, fallback to a default location
                console.log("Geolocation not supported, fallback to default location.");
                if (!map) {
                    initializeMap(); // Default to London if geolocation is not supported
                }
            }

            // Open the modal
            $('#mapModal').modal('show');
        }

        // Function to save the location and close the modal
        $('#saveLocation').click(function() {
            $('#mapModal').modal('hide');
        });

        // Optionally, close the modal when clicking outside
        $('#mapModal').on('hidden.bs.modal', function() {
            // Reset the marker and map if needed
            if (marker) {
                marker.remove();
                marker = null;
            }
        });
    </script>


    {{--        images preview--}}
    <script>
        // Reusable function for image change preview
        function previewImage(inputElement, targetImgElement) {
            var reader = new FileReader(),
                files = inputElement.files;
            reader.onload = function () {
                if (targetImgElement.length) {
                    targetImgElement.attr('src', reader.result);
                }
            };
            if (files && files[0]) {
                reader.readAsDataURL(files[0]);
            }
        }

        // Logo preview
        var changeLogo = $('#change-logo'),
            logoAvatar = $('.logo');

        if (changeLogo.length) {
            $(changeLogo).on('change', function () {
                previewImage(this, logoAvatar);
            });
        }

        // Icon preview
        var changeIcon = $('#change-icon'),
            iconAvatar = $('.icon');

        if (changeIcon.length) {
            $(changeIcon).on('change', function () {
                previewImage(this, iconAvatar);
            });
        }
    </script>

    {{-- personal --}}
    <script>
        $(document).ready(function () {
            var form = $('#settingsForm');
            if (form.length) {
                $(form).each(function () {
                    var $this = $(this);
                    $this.validate({
                        submitHandler: function (form, event) {
                            event.preventDefault();
                            $('#submitBtn').trigger('click'); // Trigger custom submit button click
                        },
                        rules: {
                            name: {
                                required: true
                            },
                            lat: {
                                required: true
                            },
                            long: {
                                required: true
                            },
                            phone: {
                                required: true,
                                number:true,
                            },
                            experience: {
                                required: true,
                                number:true,
                            },
                            email: {
                                required: true,
                                email: true
                            },
                        },
                    });
                });
            }


            {{-- Submit --}}
            $('#submitBtn').on('click', function (e) {
                e.preventDefault(); // Prevent form default action

                // Show spinner and disable button
                $('#spinner').show();
                $('#submitBtn').prop('disabled', true);

                var formData = new FormData($('#settingsForm')[0]);

                $.ajax({
                    url: "{{ route('company.profile.update') }}",
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        $('#spinner').hide();
                        $('#submitBtn').prop('disabled', false);
                        toastr.success(response.message, '{{ trans('messages.success') }}');
                        setTimeout(function() {
                            location.reload();
                        }, 700);
                    },
                    error: function (xhr) {
                        $('#spinner').hide();
                        $('#submitBtn').prop('disabled', false);
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



    {{-- password --}}
    <script>
        $(document).ready(function () {
            var form = $('#passwordForm');

            // Validate the form
            if (form.length) {
                $(form).each(function () {
                    var $this = $(this);
                    $this.validate({
                        submitHandler: function (form, event) {
                            event.preventDefault();
                            $('#submitBtn-password').trigger('click'); // Trigger custom submit button click
                        },
                        rules: {
                            password: {
                                required: true,
                                minlength: 6
                            },
                            password_confirmation: {
                                required: true,
                                equalTo: "#account-new-password"
                            },
                        },
                    });
                });
            }

            {{-- Submit Password Update --}}
            $('#submitBtn-password').on('click', function (e) {
                e.preventDefault();

                // Show spinner and disable button
                $('#spinner-password').show();
                $('#submitBtn-password').prop('disabled', true);

                var formData = new FormData($('#passwordForm')[0]);

                $.ajax({
                    url: "{{ route('company.profile.password') }}",
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        $('#spinner-password').hide();
                        $('#submitBtn-password').prop('disabled', false);
                        toastr.success(response.message, '{{ trans('messages.success') }}');
                        setTimeout(function() {
                            location.reload();
                        }, 700);
                    },
                    error: function (xhr) {
                        $('#spinner-password').hide();
                        $('#submitBtn-password').prop('disabled', false);
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
