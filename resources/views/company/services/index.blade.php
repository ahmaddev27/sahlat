@extends('company.layouts.master',['title'=>trans('dashboard_aside.services')])

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
                    title="{{trans('services.new-service')}}"><i data-feather="plus"></i></button>
        </div>
    </div>



@endsection

@section('content')

    <div class="content-body">
        <div class="card">

            <div class="card-datatable table-responsive p-2 ">

                <table class="table" id="table">

                    <thead class="thead-light">
                    <tr>
                        <th>#</th>

                        <th>{{trans('services.title')}}</th>
                        <th>{{trans('services.action')}}</th>

                    </tr>
                    </thead>
                </table>
            </div>
        </div>

    </div>




    @push('js')

        <script src="{{url('app-assets/js/scripts/extensions/ext-component-sweet-alerts.js')}}"></script>


        @if(app()->getLocale() === 'ar')
            <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/localization/messages_ar.js"></script>
        @endif


        {{--datatable--}}
        <script>
            $(document).ready(function () {
                $('#table').DataTable({
                    processing: false,
                    serverSide: true,


                    ajax: "{{ route('company.services.list') }}",
                    columns: [
                        {data: 'DT_RowIndex', name: 'id'},
                        {data: 'title', name: 'title'},
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
            });
        </script>



    @endpush



    @include('company.services.add')
    @include('company.services.edit')


@stop
