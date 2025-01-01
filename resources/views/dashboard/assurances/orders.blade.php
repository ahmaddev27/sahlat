@extends('dashboard.layouts.master',['title'=>trans('dashboard_aside.assurances-orders')])

@push('css')


@endpush
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
{{--                <div class="card-header p-1 col-sm-3" style="margin-bottom: -20px">--}}
{{--                    <label class="pt-1">{{trans('assurances.status')}}</label>--}}

{{--                    <select id="status" class="select2 form-control">--}}
{{--                        <option selected disabled>{{ trans('main.change') }}</option>--}}
{{--                        <option value="">{{ trans('main.all') }}</option>--}}
{{--                        @foreach(Statuses() as $key=>$orderStatus)--}}
{{--                        <option value="{{$key}}">{{$orderStatus }}</option>--}}
{{--                        @endforeach--}}

{{--                    </select>--}}

{{--                </div>--}}




                <div class="card-header p-1 col-sm-3" style="margin-bottom: -20px">

                <label class="pt-1">{{trans('assurances.payment')}}</label>

                    <select id="payment_status" class="select2 form-control">
                        <option selected disabled>{{ trans('main.change') }}</option>
                        <option value="">{{ trans('main.all') }}</option>
                        {{--                            <option value="0">{{ trans('main.not-payed') }}</option>--}}
                        <option value="1">{{ trans('main.partly-payed') }}</option>
                        <option value="2">{{ trans('main.completely-payed') }}</option>

                    </select>

                </div>

                    <div class="card-header p-1 col-sm-3" style="margin-bottom: -20px">

                        <label class="pt-1">{{trans('assurances.assurance')}}</label>

                        <select id="assurance" class="select2 form-control">
                            <option selected disabled>{{ trans('main.change') }}</option>
                            <option value="">{{ trans('main.all') }}</option>

                            @foreach($assurance as $a)
                                <option value="{{$a->id}}">{{$a->title }}</option>

                            @endforeach


                        </select>

                    </div>


                </div>


                <table class="table" id="table">

                    <thead class="thead-light ">
                    <tr>
                        <th>#</th>
                        <th>{{trans('assurances.order_id')}}</th>
                        <th>{{trans('assurances.assurance')}}</th>
                        <th>{{trans('assurances.user')}}</th>
                        <th>{{trans('assurances.date')}}</th>
                        <th>{{trans('assurances.phone')}}</th>
                        <th class="width-150">{{trans('assurances.status')}}</th>
{{--                        <th class="width-100">{{trans('assurances.payment')}}</th>--}}
                        <th>{{trans('assurances.action')}}</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>

</div>



    @push('js')


        @include('dashboard.assurances.js')


    @endpush


{{--    @include('dashboard.assurances.attachment-orders')--}}

@stop
