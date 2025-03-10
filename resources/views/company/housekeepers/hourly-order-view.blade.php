@extends('company.layouts.master',['title'=>trans('dashboard_aside.housekeepers_hourly')])

@push('css')
    <style>
        .select2-container {
            z-index: 9999 !important; /* Ensure it has the highest priority */
        }


        .swal2-container {
            z-index: 10000; /* SweetAlert z-index */
        }


        .modal {
            z-index: 1040; /* Ensure the modal stays below the dropdown */
        }

        #sendSmsModal {
            z-index: 9999 !important; /* Ensure it has the highest priority */
        }
    </style>
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
                            <div class="row invoice-spacing mt-0">
                                <div class="col-lg-8 col-md-12 p-3">
                                    <h6 class="mb-3">{{ trans('housekeeper.order-details') }}</h6>

                                    <div class="table-responsive">
                                        <table class="table">
                                            <tbody>
                                            <tr>
                                                <td class="pr-2 pb-1">{{ trans('housekeeper.from') }}</td>
                                                <td class="font-weight-bold">{{ $order->from->format('d-M-Y') }}</td>
                                                <td class="pr-2">{{ trans('housekeeper.to') }}</td>
                                                <td class="font-weight-bold">{{ $order->to->format('d-M-Y') }}</td>
                                            </tr>
                                            <tr>
                                                <td class="pr-2 pb-1">{{ trans('housekeeper.hours') }}</td>
                                                <td class="font-weight-bold pb-1">{{ $order->hours }}</td>
                                                <td class="pr-2">{{ trans('housekeeper.date') }}</td>
                                                <td class="font-weight-bold">{{ $order->date->format('Y M d') }}</td>
                                            </tr>
                                            <tr>
                                                <td class="pr-2 pb-2">{{ trans('housekeeper.name') }}</td>
                                                <td class="font-weight-bold pb-2">{{ $order->user->name }}</td>
                                                <td class="pr-2 pb-2">{{ trans('housekeeper.number_id') }}</td>
                                                <td class="font-weight-bold pb-2">{{ $order->user->number_id }}</td>
                                            </tr>
                                            @if($order->housekeeper)
                                                <tr>
                                                    <td class="pr-2 pb-2">{{ trans('housekeeper.housekeeper') }}</td>
                                                    <td class="d-flex align-items-center pb-2">
                                                        <img width="60" height="60"
                                                             class="user-avatar mr-3 rounded-circle img-fluid"
                                                             src="{{ $order->housekeeper->getAvatar() }}"
                                                             alt="Housekeeper Avatar">
                                                        <span
                                                            class="font-weight-bold">{{ $order->housekeeper->name }}</span>
                                                    </td>
                                                </tr>
                                            @endif
                                            <tr>
                                                <td class="pr-2 pt-3">{{ trans('housekeeper.company') }}</td>
                                                <td colspan="2"
                                                    class="font-weight-bold pt-3">{{ $order->company->name }}</td>
                                            </tr>
                                            <tr>
                                                <td class="pr-2 pt-4">{{ trans('housekeeper.details') }}</td>
                                                <td colspan="3" class="pt-4">{{ $order->details ?? '-' }}</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-12 p-3 mt-lg-0 mt-5">
                                    <div class="invoice-title mb-3">
                                        <h4>
                                            {{ trans('housekeeper.order_id') }}
                                            <span class="invoice-number">{{ $order->n_id }}</span>
                                        </h4>
                                    </div>
                                    <div class="invoice-date-wrapper">
                                        <p class="invoice-date-title mb-1">{{ trans('housekeeper.date') }}</p>
                                        <p class="invoice-date font-weight-bold">{{ $order->created_at->format('d/m/Y') }}</p>
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
                                    @if($order->payment()->count() > 0)

                                        @php
                                            $statusText = paymentStatus($order->payment->status);
                                            $badgeClass = OrdorClass($order->payment->status);
                                            $div = '<div class="d-inline-block m-1"><span class="badge badge-light-' . $badgeClass . '">' . $statusText . '</span></div>';
                                        @endphp
                                        <tr>
                                            <td class="pr-1"><h6>{{ trans('housekeeper.payment') }}</h6></td>
                                            <td><span class="font-weight-bold">{!! $div !!}</span></td>
                                        </tr>
                                        <tr>
                                            {{--                                                <td class="pr-1">{{ trans('housekeeper.payment-type') }}</td>--}}
                                            {{--                                                <td><span class="font-weight-bold">--}}
                                            {{--                                                        <div class="d-inline-block m-1">--}}
                                            {{--                                                            {{$order->payment->is_tabby ? $order->payment->is_tabby?'Tabby' : ($order->payment->is_stripe ? 'stripe' : $order->payment->payment_type): $order->payment->payment_type}}</div>--}}
                                            {{--                                                    </span></td>--}}
                                        </tr>

                                    @else
                                        <tr>
                                            <td class="pr-1"><h6>{{ trans('housekeeper.payment') }}</h6></td>
                                            <td><span class="badge badge-light-{{ OrdorClass('0') }}">{{ paymentStatus(0) }}</span></td>
                                        </tr>

                                    @endif
                                </div>


                            </div>
                        </div>


                        @if($order->payment && ($order->payment->tabby || $order->payment->stripe ||  $order->payment->ddashboard ))
                            <div class="row" id="table-hover-animation">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-title">{{ trans('main.payments') }}</h4>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-hover-animation">
                                                <thead>
                                                <tr>
                                                    <th>{{ trans('main.date') }}</th>
                                                    <th>{{ trans('main.value') }}</th>
                                                    <th>{{ trans('main.pay') }}</th>
                                                    {{--                                                    <th>{{ trans('main.remaining') }}</th>--}}
                                                    <th>{{ trans('main.type') }}</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @if($order->payment->tabby)
                                                    @foreach($order->payment->tabby as $tabby)
                                                        <tr>
                                                            <td><span class="font-weight-bold">{{ $tabby->created_at->format('d/m/Y') }}</span></td>
                                                            <td>ADE {{ $order->payment->order_value }}</td>
                                                            <td>ADE {{ $tabby->amount }}</td>
                                                            {{--                                                            <td>ADE {{ $order->payment->order_value - $tabby->amount }}</td>--}}
                                                            <td><span class="badge badge-pill badge-light-success mr-1">Tabby</span></td>
                                                        </tr>
                                                    @endforeach
                                                @endif

                                                @if($order->payment->stripe)
                                                    @foreach($order->payment->stripe as $stripe)
                                                        <tr>
                                                            <td><span class="font-weight-bold">{{ $stripe->created_at->format('d/m/Y') }}</span></td>
                                                            <td>ADE {{ $order->payment->order_value }}</td>
                                                            <td>ADE {{ $stripe->amount }}</td>
                                                            {{--                                                            <td>ADE {{ $order->payment->order_value - $stripe->amount }}</td>--}}
                                                            <td><span class="badge badge-pill badge-light-info mr-1">Stripe</span></td>
                                                        </tr>
                                                    @endforeach
                                                @endif

                                                @if($order->payment->dashboard)
                                                    @foreach($order->payment->dashboard as $dashboard)
                                                        <tr>
                                                            <td><span class="font-weight-bold">{{ $dashboard->created_at->format('d/m/Y') }}</span></td>
                                                            <td>ADE {{ $order->payment->order_value }}</td>
                                                            <td>ADE {{ $dashboard->amount }}</td>
                                                            {{--                                                            <td>ADE {{ $order->payment->order_value - $dashboard->amount }}</td>--}}
                                                            <td><span class="badge badge-pill badge-light-primary mr-1">Dashboard</span></td>
                                                        </tr>
                                                    @endforeach
                                                @endif

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Invoice Total Section -->
                        <div class="card-body invoice-padding pb-0">
                            <div class="row invoice-sales-total-wrapper">
                                <div class="col-md-9 d-flex justify-content-end order-md-2 order-1">
                                    <div class="invoice-total-wrapper">
                                        <div class="invoice-total-item mt-2">
                                            <p class="invoice-total-title d-inline">{{ trans('company.hourly_price') }}</p>
                                            <p class="invoice-total-amount d-inline p-2">
                                                ADE {{ $order->company->hourly_price }}</p>
                                        </div>

                                        <div class="invoice-total-item mt-2">
                                            <p class="invoice-total-title d-inline">{{ trans('main.hours') }}</p>
                                            <p class="invoice-total-amount d-inline p-2">{{ $order->hours }}</p>
                                        </div>

                                        <div class="invoice-total-item mt-2">
                                            <p class="invoice-total-title d-inline">{{ trans('main.total') }}</p>
                                            <p class="invoice-total-amount d-inline p-2">
                                                ADE {{ $order->hours *$order->company->hourly_price  }}</p>
                                        </div>

                                        <div class="invoice-total-item mt-2">
                                            <p class="invoice-total-title d-inline">{{ trans('main.total-payment') }}</p>
                                            <p class="invoice-total-amount d-inline p-2">
                                                ADE {{ $order->payment->payment_value ?? '0' }}
                                            </p>
                                        </div>

                                        <hr class="my-50"/>

                                        <div class="invoice-total-item mt-2">
                                            <p class="invoice-total-title d-inline">{{ trans('main.remain') }}</p>
                                            <p class="invoice-total-amount d-inline p-2">
                                                ADE {{ $order->payment->remaining_amount ?? $order->hours* $order->company->hourly_price}}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Invoice Attachments -->
                            @if($order->orderAttachments->count() > 0)
                                <div class="attachments-section mt-4">
                                    <h6 class="mb-3">{{ trans('assurances.attachments') }}</h6>
                                    <ul class="list-unstyled d-flex flex-wrap">
                                        @foreach($order->orderAttachments as $attachment)
                                            <li class="d-flex align-items-center mr-4 mb-2">
                                                <div class="attachment-title mr-2">
                                                    <p class="mb-0 font-weight-bold">{{ $attachment->title }}</p>
                                                </div>
                                                <div class="attachment-file">
                                                    <a href="{{ $attachment->getFile() }}" target="_blank"
                                                       class="text-body">
                                                        <i class="font-large-1" data-feather="file"></i>
                                                    </a>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                        <!-- Invoice Description Ends -->


                    </div>
                </div>
                <!-- /Invoice -->


                <!-- Invoice Actions -->
                <div class="col-xl-3 col-md-4 col-12 invoice-actions mt-md-0 mt-2">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-inline-block "><span
                                    class="badge badge-light-{{OrdorClass($order->status)}}"> {{HouseKeeperHourlyStatuses($order->status)}}</span>
                            </div>


                            @php
                                // Create the select dropdown for status change
                                $statusSelect = '<select class="status-select form-control d-inline-block status-font" data-id="' . $order->id . '" data-old-status="' . $order->status . '" style="width: 100%;">';

                                $statusSelect .= '<option selected disabled>' . trans('main.change') . '</option>';

                                $statuses = HouseKeeperHourlyStatuses();
                                $keys = array_keys($statuses);
                                $currentStatusIndex = array_search($order->status, $keys);

                                // Check if the current status is the last one
                                $isLastStatus = ($currentStatusIndex === count($keys) - 1);

                                // Loop through statuses, disabling all if last status is selected
                                foreach ($statuses as $key => $value) {
                                    $selected = ($key == $order->status) ? 'selected' : '';
                                    $disabled = $isLastStatus ? 'disabled' : (($key != $keys[$currentStatusIndex + 1] ?? null) ? 'disabled' : '');

                                    $statusSelect .= '<option value="' . $key . '" ' . $selected . ' ' . $disabled . '>' . $value . '</option>';
                                }

                                $statusSelect .= '</select>';
                            @endphp


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

        {{-- Change status --}}
        <script>
            $(document).on('change', '.status-select', function () {
                var orderId = $(this).data('id');
                var newStatus = $(this).val();

                // Store the old value to revert if the user cancels
                $(this).data('old-status', $(this).val());

                // Show confirmation dialog with SweetAlert
                Swal.fire({
                    title: '{{ trans("messages.sure?") }}',
                    text: "{{ trans('messages.change-status') }}",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: '{{ trans("messages.change") }}!',
                    cancelButtonText: '{{ trans("messages.cancel") }}',
                    customClass: {
                        confirmButton: 'btn btn-danger',
                        cancelButton: 'btn btn-secondary ml-1'
                    },
                }).then((result) => {
                    if (result.isConfirmed) {
                        if (newStatus == 1) {
                            // Prompt user to enter a payment value with client‑side validation
                            Swal.fire({
                                title: '{{ trans("messages.enter-payment") }}',
                                input: 'number',
                                html: `<p>{{trans('main.order_value')}}: ADE{{$order->value}}</p>`,
                                inputAttributes: {
                                    min: 0,
                                    step:1
                                },
                                showCancelButton: true,
                                confirmButtonText: '{{ trans("messages.submit") }}',
                                cancelButtonText: '{{ trans("messages.cancel") }}',
                                customClass: {
                                    confirmButton: 'btn btn-success',
                                    cancelButton: 'btn btn-secondary'
                                },
                                preConfirm: (paymentValue) => {
                                    let value = parseFloat(paymentValue);
                                    // Get the order value from a Blade variable
                                    const orderValue = parseFloat("{{ $order->value }}");

                                    if (!value || value <= 0) {
                                        Swal.showValidationMessage('{{ trans("messages.invalid-payment") }}');
                                    } else if (value > orderValue) {
                                        Swal.showValidationMessage('{{ trans("messages.Payment value cannot exceed the order value")}}');
                                    }
                                    return value;
                                }
                            }).then((paymentResult) => {
                                if (paymentResult.isConfirmed) {
                                    var paymentValue = paymentResult.value;

                                    Swal.fire({
                                        icon: 'info',
                                        title: '{{ trans("messages.loading") }}',
                                        text: '{{ trans("messages.processing-request") }}',
                                        allowOutsideClick: false,
                                        didOpen: () => {
                                            Swal.showLoading();
                                        }
                                    });

                                    // Make AJAX request to update the status with payment value
                                    $.ajax({
                                        url: '{{ route("company.housekeepers.HourlyOrders.updateStatus") }}',
                                        type: 'POST',
                                        data: {
                                            _token: $('meta[name="csrf-token"]').attr('content'),
                                            order_id: orderId,
                                            status: newStatus,
                                            payment_value: paymentValue
                                        },
                                        success: function (data) {
                                            Swal.fire({
                                                title: '{{ trans("messages.updated") }}!',
                                                text: '{{ trans("messages.change-success") }}.',
                                                icon: 'success',
                                                confirmButtonText: '{{ trans("messages.close") }}',
                                                customClass: {
                                                    confirmButton: 'btn btn-success'
                                                }
                                            }).then(() => {
                                                window.location.reload();
                                            });
                                        },
                                        error: function (data) {
                                            Swal.fire({
                                                title: '{{ trans("messages.not-updated") }}!',
                                                text: '{{ trans("messages.not-update-error") }}.',
                                                icon: 'error',
                                                confirmButtonText: '{{ trans("messages.close") }}',
                                            }).then(() => {
                                                window.location.reload();
                                            });
                                        }
                                    });
                                }
                            });
                        } else if (newStatus == 3) {
                            // Fetch housekeepers dynamically based on company_id for status 3
                            var companyId = '{{ $order->company_id }}';

                            $.ajax({
                                url: '{{ route("company.housekeepers.HourlyOrders.get-housekeepers", "") }}/' + companyId,
                                type: 'GET',
                                success: function (data) {
                                    var housekeepers = data.housekeepers;
                                    var options = '';

                                    housekeepers.forEach(function (housekeeper) {
                                        options += `<option value="${housekeeper.id}">${housekeeper.name}</option>`;
                                    });

                                    Swal.fire({
                                        title: '{{ trans("messages.select-housekeeper") }}',
                                        html: `<select id="housekeeper-select" class="form-control">${options}</select>`,
                                        showCancelButton: true,
                                        confirmButtonText: '{{ trans("messages.submit") }}',
                                        cancelButtonText: '{{ trans("messages.cancel") }}',
                                        customClass: {
                                            confirmButton: 'btn btn-success',
                                            cancelButton: 'btn btn-secondary'
                                        },
                                        didOpen: () => {
                                            $('#housekeeper-select').select2();
                                        }
                                    }).then((selectResult) => {
                                        if (selectResult.isConfirmed) {
                                            var housekeeperId = $('#housekeeper-select').val();

                                            if (!housekeeperId) {
                                                Swal.fire({
                                                    title: '{{ trans("messages.error") }}',
                                                    text: '{{ trans("messages.select-housekeeper-error") }}',
                                                    icon: 'error',
                                                });
                                                return;
                                            }

                                            Swal.fire({
                                                icon: 'info',
                                                title: '{{ trans("messages.loading") }}',
                                                text: '{{ trans("messages.processing-request") }}',
                                                allowOutsideClick: false,
                                                didOpen: () => {
                                                    Swal.showLoading();
                                                }
                                            });

                                            $.ajax({
                                                url: '{{ route("company.housekeepers.HourlyOrders.updateStatus") }}',
                                                type: 'POST',
                                                data: {
                                                    _token: $('meta[name="csrf-token"]').attr('content'),
                                                    order_id: orderId,
                                                    status: newStatus,
                                                    housekeeper_id: housekeeperId
                                                },
                                                success: function (data) {
                                                    Swal.fire({
                                                        title: '{{ trans("messages.updated") }}!',
                                                        text: '{{ trans("messages.change-success") }}.',
                                                        icon: 'success',
                                                        confirmButtonText: '{{ trans("messages.close") }}',
                                                        customClass: {
                                                            confirmButton: 'btn btn-success'
                                                        }
                                                    }).then(() => {
                                                        window.location.reload();
                                                    });
                                                },
                                                error: function (data) {
                                                    Swal.fire({
                                                        title: '{{ trans("messages.not-updated") }}!',
                                                        text: '{{ trans("messages.not-update-error") }}.',
                                                        icon: 'error',
                                                        confirmButtonText: '{{ trans("messages.close") }}',
                                                    }).then(() => {
                                                        window.location.reload();
                                                    });
                                                }
                                            });
                                        }
                                    });
                                },
                                error: function () {
                                    Swal.fire({
                                        title: '{{ trans("messages.error") }}',
                                        text: '{{ trans("messages.housekeeper-fetch-error") }}',
                                        icon: 'error',
                                    }).then(() => {
                                        window.location.reload();
                                    });
                                }
                            });
                        } else {
                            // For other statuses, simply update via AJAX
                            Swal.fire({
                                icon: 'info',
                                title: '{{ trans("messages.loading") }}',
                                text: '{{ trans("messages.processing-request") }}',
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });

                            $.ajax({
                                url: '{{ route("company.housekeepers.HourlyOrders.updateStatus") }}',
                                type: 'POST',
                                data: {
                                    _token: $('meta[name="csrf-token"]').attr('content'),
                                    order_id: orderId,
                                    status: newStatus
                                },
                                success: function (data) {
                                    Swal.fire({
                                        title: '{{ trans("messages.updated") }}!',
                                        text: '{{ trans("messages.change-success") }}.',
                                        icon: 'success',
                                        confirmButtonText: '{{ trans("messages.close") }}',
                                        customClass: {
                                            confirmButton: 'btn btn-success'
                                        }
                                    }).then(() => {
                                        window.location.reload();
                                    });
                                },
                                error: function (data) {
                                    Swal.fire({
                                        title: '{{ trans("messages.not-updated") }}!',
                                        text: '{{ trans("messages.not-update-error") }}.',
                                        icon: 'error',
                                        confirmButtonText: '{{ trans("messages.close") }}',
                                    }).then(() => {
                                        window.location.reload();
                                    });
                                }
                            });
                        }
                    } else {
                        // If user cancels, revert to the old status and reload the table
                        $(this).val($(this).data('old-status'));
                        $('#table').DataTable().ajax.reload();
                    }
                });
            });
        </script>


    @endpush

@stop
