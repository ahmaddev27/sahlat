@extends('company.layouts.master',['title'=>trans('dashboard_aside.housekeepers_hourly')])

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

{{--@section('path')--}}

{{--    <li class="breadcrumb-item active"><a--}}
{{--            href="{{route('housekeepers.index')}}">{{trans('dashboard_aside.housekeepers')}} </a></li>--}}

{{--@endsection--}}



@section('content')

    <!-- BEGIN: Content-->

    <div class="content-body">
        <section class="invoice-preview-wrapper ">
            <div class="row invoice-preview">
                <!-- Invoice -->
                <div class="col-xl-9 col-md-8 col-12">
                    <div class="card invoice-preview-card p-2">

                        <div class="card-body invoice-padding pb-0">
                            <!-- Header starts -->

                            {{--                            orderDetials--}}
                            <div class="d-flex justify-content-between flex-md-row flex-column invoice-spacing mt-0">
                                <div class="col-xl-8 p-3">
                                    <h6 class="mb-3">{{trans('housekeeper.order-details')}}</h6>

                                    <table class="table">
                                        <tbody>
                                        <tr>
                                            <td class="pr-2 pb-1">{{trans('housekeeper.from')}}</td>
                                            <td class="font-weight-bold">{{$order->from}}</td>
                                            <td class="pr-2">{{trans('housekeeper.to')}}</td>
                                            <td class="font-weight-bold">{{$order->to}}</td>
                                        </tr>

                                        <tr>
                                            <td class="pr-2 pb-1">{{trans('housekeeper.hours')}}</td>
                                            <td class="font-weight-bold pb-1">{{$order->hours}}</td>
                                            <td class="pr-2">{{trans('housekeeper.date')}}</td>
                                            <td class="font-weight-bold">{{$order->date->format('Y M d')}}</td>
                                        </tr>

                                        <tr>
                                            <td class="pr-2 pb-2">{{trans('housekeeper.name')}}</td>
                                            <td class="font-weight-bold pb-2">{{$order->user->name}}</td>

                                            <td class="pr-2 pb-2">{{trans('housekeeper.number_id')}}</td>
                                            <td class="font-weight-bold pb-2">{{$order->user->number_id}}</td>
                                        </tr>

                                        @if($order->housekeeper)
                                            <tr>
                                                <td class="pr-2 pb-2">{{trans('housekeeper.housekeeper')}}</td>
                                                <td class="d-flex align-items-center pb-2">
                                                    <img
                                                        width="60" height="60"
                                                        class="user-avatar mr-3 rounded-circle"
                                                        src="{{$order->housekeeper->getAvatar()}}"
                                                        alt="Housekeeper Avatar">
                                                    <span class="font-weight-bold">{{$order->housekeeper->name}}</span>
                                                </td>
                                            </tr>
                                        @endif

                                        <tr>
                                            <td class="pr-2 pt-3">{{trans('housekeeper.company')}}</td>
                                            <td class="font-weight-bold pt-3">{{$order->housekeeper?->company->name}}</td>
                                        </tr>

                                        <tr>
                                            <td class="pr-2 pt-4">{{trans('housekeeper.details')}}</td>
                                            <td colspan="3" class="pt-4">{{$order->details ?? '-'}}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-xl-4 p-3 mt-5">
                                    <div class="row invoice-spacing">
                                        <div class="w-100">
                                            <h4 class="invoice-title mb-3">
                                                {{trans('housekeeper.order_id')}}
                                                <span class="invoice-number">{{$order->n_id}}</span>
                                            </h4>

                                            <div class="invoice-date-wrapper">
                                                <p class="invoice-date-title mb-1">{{trans('housekeeper.date')}}</p>
                                                <p class="invoice-date font-weight-bold">{{$order->created_at->format('d/m/Y')}}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <!-- Header ends -->
                        </div>


                        <hr class="invoice-spacing"/>

                        <!-- Address and Contact starts -->

                        <div class="card-body invoice-padding pt-0">
                            <div class="row invoice-spacing">
                                <div class="col-xl-7 p-0">
                                    <h6 class="mb-2">{{trans('housekeeper.user')}}</h6>
                                    <h6 class="mb-25">{{$order->user->name}}</h6>
                                    @if( app()->getLocale() === 'ar')

                                        <div class="card-text mb-25"
                                             style="direction: ltr">

                                            {{formatedPhone($order->user->phone)}} -

                                            {{$order->user->email}}
                                            <img src="{{ $order->user->getAvatar() }}" alt="avatar" id="add-avatar-img"
                                                 class="user-avatar icon users-avatar-shadow rounded-circle cursor-pointer"
                                                 height="60" width="60"/>
                                        </div>
                                    @else
                                        <div class="card-text mb-25">
                                            <img src="{{ $order->user->getAvatar() }}" alt="avatar" id="add-avatar-img"
                                                 class="user-avatar icon users-avatar-shadow rounded-circle cursor-pointer"
                                                 height="60" width="60"/>


                                            {{formatedPhone($order->user->phone)}} -


                                            {{$order->user->email}}
                                        </div>
                                    @endif



                                </div>

                                <div class="col-xl-4 p-0 mt-xl-0 mt-2">
                                    <h6 class="mb-2">{{trans('housekeeper.payment')}}</h6>

                                    @if($order->payment()->count()>0)

                                        <table>
                                            <tbody>

                                            @php
                                                $statusText = paymentStatus($order->payment->status);
                                                $badgeClass = OrdorClass($order->payment->status);
                                                                                                    // Combine the status badge and the select dropdown inline
                                                $div = '<div class="d-inline-block m-1"><span class="badge badge-glow ' . $badgeClass . '">' . $statusText . '</span></div>';
                                            @endphp


                                            <tr>
                                                <td class="pr-1 ">{{trans('housekeeper.status')}}</td>
                                                <td><span class="font-weight-bold">{!! $div!!}</span></td>
                                            </tr>


                                            <tr>
                                                <td class="pr-1">{{trans('housekeeper.payment-type')}}</td>
                                                <td><span class="font-weight-bold"><div
                                                            class="d-inline-block m-1"> {{$order->payment?->type ?: '-'}} </div></span>
                                                </td>
                                            </tr>
                                            {{--                                                <tr>--}}
                                            {{--                                                    <td class="pr-1">Country:</td>--}}
                                            {{--                                                    <td>United States</td>--}}
                                            {{--                                                </tr>--}}

                                            </tbody>
                                        </table>
                                    @else

                                        <div class="d-inline-block m-1"><span
                                                class="badge badge-glow {{OrdorClass('0')}} '">{{paymentStatus(0)}} </span>
                                        </div>

                                    @endif
                                </div>


                            </div>
                        </div>
                        <!-- Address and Contact ends -->


                        <div class="card-body invoice-padding pb-0">
                            <div class="row invoice-sales-total-wrapper">

                                <div class="col-md-9 d-flex justify-content-end order-md-2 order-1">
                                    <div class="invoice-total-wrapper">


                                        <div class="invoice-total-item mt-2">
                                            <p class="invoice-total-title d-inline">{{trans('company.hourly_price')}}</p>
                                            <p class="invoice-total-amount d-inline p-2">
                                                ADE {{$order->company->hourly_price}}</p>

                                        </div>


                                        <div class="invoice-total-item mt-2">
                                            <p class="invoice-total-title d-inline">{{trans('main.hours')}}</p>
                                            <p class="invoice-total-amount d-inline p-2">
                                                {{$order->hours}}</p>
                                        </div>




                                        <div class="invoice-total-item mt-2 ">
                                            <p class="invoice-total-title d-inline">{{trans('main.total-payment')}}</p>
                                            <p class="invoice-total-amount d-inline p-2"> ADE {{$order->payment?->payment_value??'-'}}</p>
                                        </div>



                                        <hr class="my-50" />


                                        <div class="invoice-total-item mt-2 ">
                                            <p class="invoice-total-title d-inline">{{trans('main.remain')}}</p>
                                            <p class="invoice-total-amount d-inline p-2"> ADE {{$order->payment?->remaining_amount??'-'}}</p>
                                        </div>






                                    </div>




                                </div>


                            </div>

                            @if($order->orderattAchments->count() > 0)
                                <!-- Invoice Attachments -->
                                <div class="attachments-section mt-4">
                                    <h6 class="mb-3">{{trans('assurances.attachments')}}</h6>
                                    <ul class="list-unstyled d-flex flex-wrap">
                                        @foreach($order->orderattAchments as $attachment)
                                            <li class="d-flex align-items-center mr-4 mb-2">
                                                <div class="attachment-title mr-2">
                                                    <p class="mb-0 font-weight-bold">{{$attachment->title}}</p>
                                                </div>
                                                <div class="attachment-file">
                                                    <a href="{{$attachment->getFile()}}" target="_blank" class="text-body">
                                                        <i class="font-large-1" data-feather="file"></i>
                                                    </a>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif


                        </div>
                        <!-- Invoice Description ends -->


                    </div>
                </div>
                <!-- /Invoice -->


                <!-- Invoice Actions -->
                <div class="col-xl-3 col-md-4 col-12 invoice-actions mt-md-0 mt-2">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-inline-block "><span
                                    class="badge badge-glow {{OrdorClass($order->status)}}"> {{HouseKeeperHourlyStatuses($order->status)}}</span>
                            </div>


                            @php    // Create the select dropdown for status change
                                                    $statusSelect = '<select class="status-select form-control d-inline-block status-font" data-id="' . $order->id . '" data-old-status="' . $order->status . '" style="width: 100%;">';

                                                    $statusSelect .= '<option selected disabled>' . trans('main.change') . '</option>';

                                                        foreach (HouseKeeperHourlyStatuses() as $key => $value) {
                    // Check if the key matches the current status
                    $selected = ($key == $order->status) ? 'selected' : '';

                    // Check if the key is less than the current status, and disable it
                    $disabled = ($key < $order->status) ? 'disabled' : '';

                    $statusSelect .= '<option value="' . $key . '" ' . $selected . ' ' . $disabled . '>' . $value . '</option>';
                }
                                                    $statusSelect .= '</select>';  @endphp

                            <div class="p-1">{{trans('housekeeper.change-status')}}</div>

                            <div class="mb-2"> {!! $statusSelect!!}</div>

                            {{--                                                                <a class="btn btn-outline-primary btn-block mb-75" href="{{route('housekeeper.orders.print',$order->id)}}" target="_blank">--}}
                            {{--                                                                   <i data-feather="printer"></i> {{trans('main.print')}}--}}
                            {{--                                                                </a>--}}

                        </div>
                    </div>

                    @if($order->note)
                        <div class="card">
                            <div class="card-body">

                                <div class="p-1">{{trans('housekeeper.note')}}</div>

                                <div class="mb-2"> {!! $order->note!!}</div>


                            </div>
                        </div>
                    @endif

                </div>
                <!-- /Invoice Actions -->


            </div>
        </section>

    </div>

    <!-- END: Content-->

    @push('js')

        {{-- Change status --}}


        <script>
            $(document).on('change', '.status-select', function () {
                var orderId = $(this).data('id');
                var newStatus = $(this).val();

                // Store the old value to revert if the user cancels
                $(this).data('old-status', $(this).val());

                // Show confirmation dialog with SweetAlert
                Swal.fire({
                    title: '{{trans('messages.sure?')}}',
                    text: "{{trans('messages.change-status')}}",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: '{{trans('messages.change')}}!',
                    cancelButtonText: '{{trans('messages.cancel')}}',
                    customClass: {
                        confirmButton: 'btn btn-danger',
                        cancelButton: 'btn btn-secondary ml-1'
                    },
                }).then((result) => {
                    if (result.isConfirmed) {
                        if (newStatus == 3) {
                            // Fetch housekeepers dynamically based on company_id
                            var companyId = '{{$order->company_id}}'; // Assume you have company ID in the data attribute

                            $.ajax({
                                url: '{{route('company.housekeepers.HourlyOrders.get-housekeepers', '')}}/' + companyId,
                                type: 'GET',
                                success: function (data) {
                                    var housekeepers = data.housekeepers; // Adjust based on your API response
                                    var options = '';

                                    housekeepers.forEach(function (housekeeper) {
                                        options += `<option value="${housekeeper.id}">${housekeeper.name}</option>`;
                                    });

                                    // Show select2 dropdown in SweetAlert
                                    Swal.fire({
                                        title: '{{trans('messages.select-housekeeper')}}',
                                        html: `<select id="housekeeper-select" class="form-control">${options}</select>`,
                                        showCancelButton: true,
                                        confirmButtonText: '{{trans('messages.submit')}}',
                                        cancelButtonText: '{{trans('messages.cancel')}}',
                                        customClass: {
                                            confirmButton: 'btn btn-success',
                                            cancelButton: 'btn btn-secondary'
                                        },
                                        didOpen: () => {
                                            $('#housekeeper-select').select2(); // Initialize select2
                                        }
                                    }).then((selectResult) => {
                                        if (selectResult.isConfirmed) {
                                            var housekeeperId = $('#housekeeper-select').val();

                                            if (!housekeeperId) {
                                                Swal.fire({
                                                    title: '{{trans('messages.error')}}',
                                                    text: '{{trans('messages.select-housekeeper-error')}}',
                                                    icon: 'error',
                                                });
                                                return;
                                            }

                                            Swal.fire({
                                                icon: 'info',
                                                title: '{{trans('messages.loading')}}',
                                                text: '{{trans('messages.processing-request')}}',
                                                allowOutsideClick: false,
                                                didOpen: () => {
                                                    Swal.showLoading();
                                                }
                                            });
                                            // Make AJAX request to update the status with housekeeper ID
                                            $.ajax({
                                                url: '{{route('company.housekeepers.HourlyOrders.updateStatus')}}',
                                                type: 'POST',
                                                data: {
                                                    _token: $('meta[name="csrf-token"]').attr('content'),
                                                    order_id: orderId,
                                                    status: newStatus,
                                                    housekeeper_id: housekeeperId
                                                },
                                                success: function (data) {
                                                    Swal.fire({
                                                        title: '{{trans('messages.updated')}}!',
                                                        text: '{{trans('messages.change-success')}}.',
                                                        icon: 'success',
                                                        confirmButtonText: '{{trans('messages.close')}}',
                                                        customClass: {
                                                            confirmButton: 'btn btn-success'
                                                        }
                                                    }).then(() => {
                                                        window.location.reload(); // Reload the page after success
                                                    });
                                                },
                                                error: function (data) {
                                                    Swal.fire({
                                                        title: '{{trans('messages.not-updated')}}!',
                                                        text: '{{trans('messages.not-update-error')}}.',
                                                        icon: 'error',
                                                        confirmButtonText: '{{trans('messages.close')}}',
                                                    }).then(() => {
                                                        window.location.reload(); // Reload the page after error
                                                    });
                                                }
                                            });
                                        }
                                    });
                                },
                                error: function () {
                                    Swal.fire({
                                        title: '{{trans('messages.error')}}',
                                        text: '{{trans('messages.housekeeper-fetch-error')}}',
                                        icon: 'error',
                                    }).then(() => {
                                        window.location.reload(); // Reload the page after error
                                    });
                                }
                            });
                        }
                        else {
                            Swal.fire({
                                icon: 'info',
                                title: '{{trans('messages.loading')}}',
                                text: '{{trans('messages.processing-request')}}',
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });
                            // Regular status update
                            $.ajax({
                                url: '{{route('company.housekeepers.HourlyOrders.updateStatus')}}',
                                type: 'POST',
                                data: {
                                    _token: $('meta[name="csrf-token"]').attr('content'),
                                    order_id: orderId,
                                    status: newStatus
                                },
                                success: function (data) {
                                    Swal.fire({
                                        title: '{{trans('messages.updated')}}!',
                                        text: '{{trans('messages.change-success')}}.',
                                        icon: 'success',
                                        confirmButtonText: '{{trans('messages.close')}}',
                                        customClass: {
                                            confirmButton: 'btn btn-success'
                                        }
                                    }).then(() => {
                                        window.location.reload(); // Reload the page after success
                                    });
                                },
                                error: function (data) {
                                    Swal.fire({
                                        title: '{{trans('messages.not-updated')}}!',
                                        text: '{{trans('messages.not-update-error')}}.',
                                        icon: 'error',
                                        confirmButtonText: '{{trans('messages.close')}}',
                                    }).then(() => {
                                        window.location.reload(); // Reload the page after error
                                    });
                                }
                            });
                        }
                    } else {
                        // If canceled, revert status
                        $(this).val($(this).data('old-status'));
                        $('#table').DataTable().ajax.reload();
                    }
                });
            });
        </script>

    @endpush

@stop
