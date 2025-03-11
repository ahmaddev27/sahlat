@extends('dashboard.layouts.master',['title'=>trans('dashboard_aside.companies')])


@section('left')
    <div class="content-header-right text-md-right col-md-3 col-12 d-md-block d-none">
        <div class="form-group breadcrumb-right">
            <button class="btn btn-primary waves-effect waves-float waves-light btn-sm"
                    data-toggle="modal" data-target="#inlineForm"
                    title="{{trans('company.new-company')}}"><i data-feather="plus"></i></button>
        </div>
    </div>

@endsection

@section('content')

    <div class="content-body">
        <div class="card">

            <div class="card-datatable table-responsive p-2 ">

                <div class="row">


                    <div class="card-header p-1 col-sm-3" style="margin-bottom: -60px">
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

                <table class="table" id="table">

                    <thead class="thead-light ">
                    <tr>
                        <th>#</th>


                        <th style="width: 30%">{{trans('company.name')}}</th>
                        <th>{{trans('company.email')}} </th>

                        <th>{{trans('company.address')}} </th>
                        {{--                        <th>{{trans('company.phone')}} </th>--}}
                        <th class="w-5">{{trans('company.workers')}} </th>
                        <th>{{trans('company.action')}}</th>
                        <th>{{trans('company.action')}}</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>

    </div>




    <!-- Map Modal -->
    <div id="mapModal" class="modal" tabindex="-1" role="dialog" aria-labelledby="mapModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content ">
                <div class="modal-header">
                    <h5 class="modal-title" id="mapModalLabel">{{ trans('main.Select Location') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="map" style="height: 600px;"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="saveLocation"> {{ trans('main.Save Location') }}</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('main.close') }}</button>
                </div>
            </div>
        </div>
    </div>


    @push('js')

{{--  map--}}
            <script>
                // Global variables to hold the map instance and markers, plus info about the calling modal.
                var map;
                var marker;
                var returnModal = null;          // The modal to return to (either '#inlineForm' or '#editModal')
                var latInputSelector = null;     // The selector for the latitude input to update
                var longInputSelector = null;    // The selector for the longitude input to update

                // Function to initialize the Leaflet map.
                function initializeMap(latitude = 51.505, longitude = -0.09) {
                    // Remove any existing map instance to avoid "Map container is already initialized" error.
                    if (map) {
                        map.remove();
                        map = null;
                    }
                    map = L.map('map').setView([latitude, longitude], 13);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                    }).addTo(map);

                    // When the map is clicked, update the corresponding latitude and longitude inputs.
                    map.on('click', function(e) {
                        var lat = e.latlng.lat;
                        var lng = e.latlng.lng;
                        if (marker) {
                            marker.setLatLng(e.latlng);
                        } else {
                            marker = L.marker(e.latlng).addTo(map);
                        }
                        // Update the inputs using jQuery for convenience.
                        $(latInputSelector).val(lat.toFixed(6));
                        $(longInputSelector).val(lng.toFixed(6));
                    });
                }

                // Generic function to open the map modal from a calling modal.
                // modalSelector: the modal to hide (e.g. '#inlineForm' or '#editModal')
                // latSelector & longSelector: the selectors for the latitude/longitude inputs to update.
                function openMapForModal(modalSelector, latSelector, longSelector) {
                    returnModal = modalSelector;
                    latInputSelector = latSelector;
                    longInputSelector = longSelector;

                    // Hide the calling modal.
                    $(modalSelector).modal('hide');

                    // Once the calling modal is fully hidden, initialize and show the map modal.
                    $(modalSelector).one('hidden.bs.modal', function() {
                        // If the input already has a value, use it as the starting point.
                        var initLat = parseFloat($(latSelector).val()) || 51.505;
                        var initLng = parseFloat($(longSelector).val()) || -0.09;

                        // If geolocation is available and the inputs are empty, try to use the user's location.
                        if (navigator.geolocation && !$(latSelector).val() && !$(longSelector).val()) {
                            navigator.geolocation.getCurrentPosition(function(position) {
                                initLat = position.coords.latitude;
                                initLng = position.coords.longitude;
                                $(latSelector).val(initLat.toFixed(6));
                                $(longSelector).val(initLng.toFixed(6));
                                initializeMap(initLat, initLng);
                            }, function() {
                                initializeMap(initLat, initLng);
                            });
                        } else {
                            initializeMap(initLat, initLng);
                        }
                        // Show the map modal.
                        $('#mapModal').modal('show');
                    });
                }

                // When the map modal is fully shown, refresh the map size (fixes rendering issues).
                $('#mapModal').on('shown.bs.modal', function() {
                    if (map) {
                        map.invalidateSize();
                    }
                });

                // When the map modal is hidden, remove the map instance and re-open the calling modal.
                $('#mapModal').on('hidden.bs.modal', function() {
                    if (map) {
                        map.remove();
                        map = null;
                    }
                    marker = null;
                    if (returnModal) {
                        $(returnModal).modal('show');
                    }
                });

                // Attach event listeners for the add form.
                // These assume the add form inputs use name="lat" and name="long".
                document.querySelector('input[name="lat"]').addEventListener('click', function() {
                    openMapForModal('#inlineForm', 'input[name="lat"]', 'input[name="long"]');
                });
                document.querySelector('input[name="long"]').addEventListener('click', function() {
                    openMapForModal('#inlineForm', 'input[name="lat"]', 'input[name="long"]');
                });

                // Attach event listeners for the edit form.
                // These assume the edit form inputs have IDs: #edit-lat and #edit-long.
                document.querySelector('#edit-lat').addEventListener('click', function() {
                    openMapForModal('#editModal', '#edit-lat', '#edit-long');
                });
                document.querySelector('#edit-long').addEventListener('click', function() {
                    openMapForModal('#editModal', '#edit-lat', '#edit-long');
                });

                // Optionally: when "Save Location" is clicked in the map modal, simply hide the map modal.
                $('#saveLocation').click(function() {
                    $('#mapModal').modal('hide');
                });
            </script>


        {{--datatable--}}
        <script>
            $(document).ready(function () {
                var table = $('#table').DataTable({
                    processing: false,
                    serverSide: true,

                    order: [[5, 'desc']],


                    ajax: {
                        url: "{{ route('companies.list') }}",
                        data: function (d) {
                            d.city = $('#city-filter').val();

                        }
                    },


                    columns: [
                        {data: 'DT_RowIndex', name: 'id'},
                        {data: 'name', name: 'name'},
                        {data: 'email', name: 'email'},
                        {data: 'address', name: 'address', "className": "text-center"},
                        // {data: 'phone', name: 'phone'},
                        {data: 'housekeepers_count', name: 'housekeepers_count'},
                        {data: 'created_at', name: 'created_at', visible: false}, // Hidden column for ordering

                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false,

                        },

                    ],
                    dom: 'frtilp',


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

            $(document).ready(function () {
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


    @endpush



    @include('dashboard.companies.add')
    @include('dashboard.companies.edit')
    @include('dashboard.companies.housekeeper')

@stop
