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




    @push('js')

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
