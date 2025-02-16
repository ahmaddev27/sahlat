@extends('dashboard.layouts.master',['title'=>trans('dashboard_aside.housekeepers-orders')])

@section('left')
    {{--    <div class="content-header-right text-md-right col-md-3 col-12 d-md-block d-none">--}}
    {{--        <div class="form-group breadcrumb-right">--}}
    {{--            <button class="btn btn-primary waves-effect waves-float waves-light btn-sm"--}}
    {{--                    data-toggle="modal" data-target="#inlineForm"--}}
    {{--                    title="{{trans('housekeeper.new-housekeeper')}}"><i data-feather="plus"></i></button>--}}
    {{--        </div>--}}
    {{--    </div>--}}

@endsection

@section('content')

    <div class="content-body">
        <div class="card">

            <div class="card-datatable table-responsive p-2 ">

                <div class="row">
{{--                    <div class="card-header p-1 col-sm-3" style="margin-bottom: -5px">--}}
{{--                        <label class="pt-1">{{trans('housekeeper.status')}}</label>--}}

{{--                        <select id="status" class="select2 form-control">--}}
{{--                            <option selected disabled>{{ trans('main.change') }}</option>--}}
{{--                            <option value="">{{ trans('main.all') }}</option>--}}
{{--                            @foreach(HouseKeeperStatuses() as $key=>$orderStatus)--}}
{{--                                <option value="{{$key}}">{{$orderStatus }}</option>--}}
{{--                            @endforeach--}}

{{--                        </select>--}}

{{--                    </div>--}}


                    <div class="card-header p-1 col-sm-3" style="margin-bottom: -5px">

                        <label class="pt-1">{{trans('housekeeper.payment')}}</label>

                        <select id="payment_status" class="select2 form-control">
                            <option selected disabled>{{ trans('main.change') }}</option>
                            <option value="">{{ trans('main.all') }}</option>
                            {{--                            <option value="0">{{ trans('main.not-payed') }}</option>--}}
                            <option value="1">{{ trans('main.partly-payed') }}</option>
                            <option value="2">{{ trans('main.completely-payed') }}</option>

                        </select>

                    </div>

                    <div class="card-header p-1 col-sm-3" style="margin-bottom: -5px">

                        <label class="pt-1">{{trans('housekeeper.company')}}</label>

                        <select id="companies" class="select2 form-control">
                            <option selected disabled>{{ trans('main.change') }}</option>
                            <option value="">{{ trans('main.all') }}</option>

                            @foreach($companies as $company)
                                <option value="{{$company->id}}">{{$company->name }}</option>

                            @endforeach


                        </select>

                    </div>


                    <div class="card-header p-1 col-sm-3" style="margin-bottom: -5px">

                        <label class="pt-1">{{trans('housekeeper.housekeeper')}}</label>

                        <select id="housekeepers" class="select2 form-control" disabled>
                            <option selected disabled>{{ trans('main.change') }}</option>
                            <option value="">{{ trans('main.all') }}</option>
                        </select>

                    </div>

                </div>

                <table class="table" id="table">


                    <thead class="thead-light ">
                    <tr>
                        <th>#</th>
                        <th>{{trans('assurances.order_id')}}</th>

                        <th>{{trans('housekeeper.housekeeper')}}</th>
                        <th >{{trans('housekeeper.user')}}</th>
                        <th>{{trans('housekeeper.date')}}</th>
{{--                        <th>{{trans('housekeeper.details')}}</th>--}}
                        <th class="width-150">{{trans('housekeeper.status')}}</th>
{{--                        <th class="width-100">{{trans('housekeeper.payment')}}</th>--}}
                        <th>{{trans('housekeeper.action')}}</th>

                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>



    @include('dashboard.housekeepers.stripe-link')

    @push('js')




@include('dashboard.housekeepers.js')

    @endpush

@stop
