@extends('dashboard.layouts.master',['title'=>trans('dashboard_aside.contacts')])


@section('left')




@endsection

@section('content')

    <div class="content-body">
        <div class="card">

            <div class="card-datatable table-responsive p-2 ">

                <table class="table" id="table">

                    <thead class="thead-light ">
                    <tr>
                        <th>#</th>


                        <th>{{trans('contacts.user')}}</th>
                        <th>{{trans('contacts.title')}} </th>
                        <th>{{trans('contacts.text')}} </th>
                        <th>{{trans('contacts.date')}} </th>
                        <th>{{trans('contacts.status')}} </th>
                        <th>{{trans('contacts.action')}}</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>

    </div>


@include('dashboard.contact.view')


    @push('js')




        {{--datatable--}}
        <script>
            $(document).ready(function () {
                $('#table').DataTable({
                    processing: false,
                    serverSide: true,


                    ajax: "{{ route('contacts.list') }}",
                    columns: [
                        {data: 'DT_RowIndex', name: 'id'},
                        {data: 'user', name: 'user'},
                        {data: 'title', name: 'title'},
                        {data: 'text', name: 'text'},
                        {data: 'created_at', name: 'created_at'},
                        {data: 'status', name: 'status'},


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


@stop
