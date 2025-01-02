@extends('dashboard.layouts.master',['title'=>trans('dashboard_aside.violations')])



{{--@section('left')--}}
{{--    --}}
{{--@endsection--}}

@section('content')

    <div class="content-body">
        <div class="card">

            <div class="card-datatable table-responsive p-2 ">


                <div class="row">
{{--                    <div class="card-header p-1 col-sm-3" style="margin-bottom: -60px">--}}
{{--                        <label class="pt-1">{{trans('housekeeper.status')}}</label>--}}

{{--                        <select id="status" class="select2 form-control">--}}
{{--                            <option selected disabled>{{ trans('main.change') }}</option>--}}
{{--                            <option value="">{{ trans('main.all') }}</option>--}}
{{--                            @foreach(Statuses() as $key=>$orderStatus)--}}
{{--                                <option value="{{$key}}">{{$orderStatus }}</option>--}}
{{--                            @endforeach--}}

{{--                        </select>--}}

{{--                    </div>--}}


                    <div class="card-header p-1 col-sm-3" style="margin-bottom: -60px">
                        <label class="pt-1">{{trans('housekeeper.payment')}}</label>
                        <select id="payment_status" class="select2 form-control">
                            <option selected disabled>{{ trans('main.change') }}</option>
                            <option value="">{{ trans('main.all') }}</option>
{{--                            <option value="0">{{ trans('main.not-payed') }}</option>--}}
                            <option value="1">{{ trans('main.partly-payed') }}</option>
                            <option value="2">{{ trans('main.completely-payed') }}</option>

                        </select>

                    </div>

                </div>


                <table class="table" id="table">

                    <thead class="thead-light ">
                    <tr>

                        <th>#</th>
                        <th>{{trans('violations.order_id')}}</th>
{{--                        <th>{{trans('violations.violation_number')}}</th>--}}
                        <th>{{trans('violations.user')}}</th>
                        <th>{{trans('violations.date')}}</th>
                        <th>{{trans('violations.phone')}}</th>
                        <th class="width-150">{{trans('violations.status')}}</th>
{{--                        <th class="width-100">{{trans('violations.payment')}}</th>--}}
                        <th>{{trans('violations.action')}}</th>

                    </tr>
                    </thead>
                </table>
            </div>
        </div>

    </div>


    @include('dashboard.violations.stripe-link')


    @push('js')




        {{-- Change status --}}
        @include('dashboard.violations.js')




    @endpush




@stop
