@extends('company.layouts.master',['title'=>trans('dashboard_aside.housekeepers')])

@push('css')
    <link rel="stylesheet" type="text/css" href="{{url('app-assets/css-rtl/plugins/forms/form-validation.css')}}">

    <link rel="stylesheet" type="text/css"
          href="{{url('app-assets/vendors/css/tables/datatable/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" type="text/css"
          href="{{url('app-assets/vendors/css/tables/datatable/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" type="text/css"
          href=" {{url('app-assets/vendors/css/tables/datatable/buttons.bootstrap4.min.css')}}">


    <link rel="stylesheet" type="text/css" href="{{url('app-assets/css-rtl/pages/app-user.css')}}">

    <link rel="stylesheet" type="text/css" href="{{url('app-assets/vendors/css/extensions/sweetalert2.min.css')}}">

@endpush

@section('left')

    <div class="content-header-right text-md-right col-md-3 col-12 d-md-block d-none">
        <div class="form-group breadcrumb-right">
            <button class="btn btn-primary waves-effect waves-float waves-light btn-sm"
                    data-toggle="modal" data-target="#inlineForm"
                    title="{{trans('housekeeper.new-housekeeper')}}"><i data-feather="plus"></i></button>
        </div>
    </div>



@endsection

@section('content')

    <div class="content-body">
        <div class="card">

            <div class="card-datatable table-responsive p-2 ">

                <div class="row">
                    <div class="card-header p-1 col-sm-3" style="margin-bottom: -60px">
                        <div class="">{{trans('housekeeper.status')}}</div>

                        <select id="emp_status" class="select2 form-control ">
                            <option selected disabled>{{ trans('main.change') }}</option>
                            <option value="">{{ trans('main.all') }}</option>
                            <option value="1">{{ trans('main.employed') }}</option>
                            <option value="0">{{ trans('main.notEmployed') }}</option>
                        </select>

                    </div>

                    <div class="card-header p-1 col-sm-3" style="margin-bottom: -60px">
                        <div class="">{{trans('user.gender')}}</div>

                        <select id="gender" class="select2 form-control ">
                            <option selected disabled>{{ trans('main.change') }}</option>
                            <option value="">{{ trans('main.all') }}</option>
                            <option value="1">{{ trans('user.female') }}</option>
                            <option value="0">{{ trans('user.male') }}</option>
                        </select>

                    </div>







                </div>

                <table class="table" id="table">

                    <thead class="thead-light">
                    <tr>
                        <th>#</th>

                        <th>{{trans('housekeeper.name')}}</th>
{{--                        <th>{{trans('housekeeper.phone')}}</th>--}}
                        {{--                        <th>{{trans('housekeeper.type')}}</th>--}}
                        <th>{{trans('housekeeper.salary')}}</th>
                        <th>{{trans('housekeeper.status')}}</th>
                        <th>{{trans('user.gender')}}</th>
                        <th>{{trans('housekeeper.created_at')}}</th>
                        <th>{{trans('housekeeper.action')}}</th>


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
                var table=$('#table').DataTable({
                    processing: false,
                    serverSide: true,

                    dom: 'frtilp',

                    ajax: {
                        url: "{{ route('company.housekeepers.list') }}",
                        data: function (d) {
                            d.status = $('#emp_status').val();
                            // d.company = $('#company').val();
                            d.gender = $('#gender').val();
                        }
                    },

                    order: [[5, 'desc']],

                    columns: [
                        {data: 'DT_RowIndex', name: 'id', },
                        {data: 'name', name: 'name', searchable: true},
                        // {data: 'phone', name: 'phone', searchable: true},
                        // {data: 'type', name: 'type'},
                        {data: 'salary', name: 'salary', searchable: true},
                        {data: 'status', name: 'status', searchable: false},
                        {data: 'gender', name: 'gender', searchable: false},
                        {data: 'created_at', name: 'created_at', searchable: false,visible:false},


                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false,
                            "className": "text-center",
                        },

                    ],

                    @if(App::getLocale() == 'ar')

                    language: {
                        "url": "https://cdn.datatables.net/plug-ins/2.1.8/i18n/ar.json"
                    },
                    @endif
                });

                $('#emp_status').on('change', function () {
                    table.ajax.reload();
                });
                // $('#company').on('change', function () {
                //     table.ajax.reload();
                // });

                $('#gender').on('change', function () {
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
                    iconAvatar = $('#edit-avatar-img');

                // Change preview for edit avatar
                if (changeIcon.length) {
                    changeIcon.on('change', function () {
                        previewImage(this, iconAvatar);
                    });
                }
            });
        </script>



    @endpush


    @include('company.housekeepers.add')
    @include('company.housekeepers.edit')


@stop
