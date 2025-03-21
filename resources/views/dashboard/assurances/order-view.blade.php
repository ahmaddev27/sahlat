@extends('dashboard.layouts.master',['title'=>trans('dashboard_aside.assurances-orders')])

@push('css')
<style>
    @media (max-width: 768px) {
        .invoice-preview {
            flex-direction: column;
        }

        .invoice-total-wrapper {
            flex-direction: column;
        }

        .invoice-total-item {
            margin-bottom: 10px;
        }
    }

</style>
@endpush

@section('left')



@endsection




@section('content')

    <!-- BEGIN: Content-->
    <div class="content-body">
        <section class="invoice-preview-wrapper">
            <div class="row invoice-preview">
                <!-- Invoice -->
                <div class="col-xl-9 col-md-8 col-12">
                    <div class="card invoice-preview-card p-2">
                        <div class="card-body invoice-padding pb-0">
                            <!-- Header starts -->
                            {{-- orderDetails --}}
                            <div class="d-flex justify-content-between flex-md-row flex-column invoice-spacing mt-0">
                                <div class="col-xl-8 p-3">
                                    <h6 class="mb-3">{{ trans('housekeeper.order-details') }}</h6>

                                    <table class="table table-responsive">
                                        <tbody>
                                        <tr>
                                            <td class="pr-2 pb-2 pt-2">{{ trans('housekeeper.name') }}</td>
                                            <td class="font-weight-bold">{{ $order->user->name }}</td>
                                            <td class="pr-2 pb-2 pt-2">{{ trans('housekeeper.number_id') }}</td>
                                            <td class="font-weight-bold">{{ $order->user->number_id }}</td>
                                        </tr>

                                        <tr>
                                            <td colspan="2" class="font-weight-bold pt-3">
                                                <img src="{{ $order->assurance->getAvatar() }}" alt="avatar"
                                                     class="user-avatar icon users-avatar-shadow rounded-circle cursor-pointer mx-1"
                                                     height="50" width="50" />
                                                {{ $order->assurance?->title }}
                                            </td>
                                            <td class="pr-2 pt-3">{{ trans('assurances.assurance_number') }}</td>
                                            <td class="font-weight-bold pt-3">
                                                {{ $order->assurance_number }}
                                            </td>
                                        </tr>

                                        <tr>
                                            <td class="pr-2 pt-4">{{ trans('housekeeper.details') }}</td>
                                            <td colspan="3" class="pt-4">{{ $order->details ?? '-' }}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="col-xl-4 p-3 mt-5">
                                    <div class="row invoice-spacing">
                                        <div class="w-100">
                                            <h4 class="invoice-title mb-3">
                                                {{ trans('assurances.order_id') }}
                                                <span class="invoice-number">{{ $order->n_id }}</span>
                                            </h4>

                                            <div class="invoice-date-wrapper">
                                                <p class="invoice-date-title mb-1">{{ trans('housekeeper.date') }}</p>
                                                <p class="invoice-date font-weight-bold">{{ $order->created_at->format('d/m/Y') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Header ends -->
                        </div>

                        <hr class="invoice-spacing" />

                        <!-- Address and Contact starts -->
                        <div class="card-body invoice-padding pt-0">
                            <div class="row invoice-spacing">
                                <div class="col-xl-8 p-0">
                                    <h6 class="mb-2">{{ trans('assurances.user') }}</h6>
                                    <h6 class="mb-25">{{ $order->user->name }}</h6>
                                    <div class="card-text mb-25">
                                        <img src="{{ $order->user->getAvatar() }}" alt="avatar"
                                             class="user-avatar icon users-avatar-shadow rounded-circle cursor-pointer"
                                             height="60" width="60" />
                                        {{ $order->user->phone }} -
                                        {{ $order->user->email }}
                                    </div>
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
                        <!-- Address and Contact ends -->

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




                        <div class="card-body invoice-padding pb-0">
                            <div class="row invoice-sales-total-wrapper">
                                <div class="col-md-9 d-flex justify-content-end order-md-2 order-1">
                                    <div class="invoice-total-wrapper">
                                        <div class="invoice-total-item mt-2">
                                            <p class="invoice-total-title d-inline">{{ trans('assurances.price') }}</p>
                                            <p class="invoice-total-amount d-inline p-2">ADE {{ $order->assurance->price }}</p>
                                        </div>



                                        <div class="invoice-total-item mt-2 ">
                                            <p class="invoice-total-title d-inline">{{trans('main.total-payment')}}</p>
                                            <p class="invoice-total-amount d-inline p-2"> ADE {{$order->payment?->payment_value??0}}</p>
                                        </div>


                                        <hr class="my-50" />



                                        <div class="invoice-total-item mt-2">
                                            <p class="invoice-total-title d-inline">{{ trans('main.remain') }}</p>
                                            <p class="invoice-total-amount d-inline p-2">
                                                @php
                                                    $remaining = $order->payment?->remaining_amount ?? $order->value;
                                                @endphp

                                                @if($remaining > 0)
                                                    <span class="badge badge-pill badge-light-danger mr-1">ADE {{ $remaining }}</span>
                                                @else
                                                    ADE {{ $remaining }}
                                                @endif
                                            </p>
                                        </div>



                                    </div>
                                </div>
                            </div>
                        </div>

                        @if ($order->orderattAchments->count() > 0 || $order->attachments->count() > 0)
                            <!-- Invoice Attachments -->
                            <div class="attachments-section mt-4">
                                <h6 class="mb-3">{{ trans('assurances.attachments') }}</h6>
                                <ul class="list-unstyled d-flex flex-wrap">
                                    @foreach($order->orderattAchments as $attachment)
                                        <li class="d-flex align-items-center mr-4 mb-2">
                                            <div class="attachment-file">
                                                <a href="{{ $attachment->getFile() }}" target="_blank" class="text-body">
                                                    <i class="font-large-1" data-feather="file"></i>
                                                    <span class="font-weight-bold">
                                                    <div class="d-inline-block m-1">
                                                        <span class="badge badge-light-info">{{ trans('main.' . $attachment->type) }}</span>
                                                    </div>
                                                </span>
                                                </a>
                                            </div>
                                        </li>
                                    @endforeach

                                    @foreach($order->attachments as $a)
                                        <li class="d-flex align-items-center mr-4 mb-2">
                                            <div class="attachment-file">
                                                <a href="{{ $a->getFile() }}" target="_blank" class="text-body">
                                                    <i class="font-large-1" data-feather="file"></i>
                                                </a>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>
                <!-- /Invoice -->

                <!-- Invoice Actions -->
                <div class="col-xl-3 col-md-4 col-12 invoice-actions mt-md-0 mt-2">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-inline-block">
                                <span class="badge badge-light-{{ OrdorClass($order->status) }}"> {{ StatusesAssurance($order->status) }}</span>
                            </div>

                            @php
                                // Initialize the select dropdown
                                $statusSelect = '<select class="status-select form-control d-inline-block status-font" data-id="' . $order->id . '" data-old-status="' . $order->status . '" style="width: 100%;">';
                                $statusSelect .= '<option selected disabled>' . trans('main.change') . '</option>';

                                $statuses = StatusesAssurance();
                                $keys = array_keys($statuses);
                                $currentStatusIndex = array_search($order->status, $keys);

                                // Check if current status is the last one
                                $isLastStatus = ($currentStatusIndex === count($keys) - 1);

                                // Populate dropdown options
                                foreach ($statuses as $key => $value) {
                                    $selected = ($key == $order->status) ? 'selected' : '';
                                    $disabled = $isLastStatus ? 'disabled' : (($key != $keys[$currentStatusIndex + 1] ?? null) ? 'disabled' : '');

                                    $statusSelect .= '<option value="' . $key . '" ' . $selected . ' ' . $disabled . '>' . $value . '</option>';
                                }

                                $statusSelect .= '</select>';
                            @endphp


                            <div class="p-1">{{ trans('assurances.change-status') }}</div>
                            <div class="mb-2"> {!! $statusSelect !!}</div>
                        </div>

                    </div>

                    @if ($order->note)
                        <div class="card">
                            <div class="card-body">
                                <div class="p-1">{{ trans('assurances.note') }}</div>
                                <div class="mb-2"> {!! $order->note !!}</div>
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

{{--        <script>--}}
{{--            $(document).on('change', '.status-select', function () {--}}
{{--                var orderId = $(this).data('id');--}}
{{--                var newStatus = $(this).val();--}}

{{--                // Store the old value to revert if the user cancels--}}
{{--                var oldStatus = $(this).data('old-status');--}}
{{--                $(this).data('old-status', oldStatus || $(this).val());--}}

{{--                // Show confirmation dialog with SweetAlert--}}
{{--                Swal.fire({--}}
{{--                    title: '{{trans('messages.sure?')}}',--}}
{{--                    text: "{{trans('messages.change-status')}}",--}}
{{--                    icon: 'warning',--}}
{{--                    showCancelButton: true,--}}
{{--                    confirmButtonText: '{{trans('messages.change')}}!',--}}
{{--                    cancelButtonText: '{{trans('messages.cancel')}}',--}}
{{--                    customClass: {--}}
{{--                        confirmButton: 'btn btn-danger',--}}
{{--                        cancelButton: 'btn btn-secondary ml-1'--}}
{{--                    },--}}
{{--                }).then((result) => {--}}
{{--                    if (result.isConfirmed) {--}}
{{--                        if (newStatus == 2 || newStatus == 3) {--}}
{{--                            // Determine the dynamic label for the file input--}}
{{--                            let inputLabel = newStatus == 2--}}
{{--                                ? '{{trans('messages.upload-contract')}}'--}}
{{--                                : '{{trans('messages.upload-attachment')}}';--}}

{{--                            Swal.fire({--}}
{{--                                title: inputLabel,--}}
{{--                                html: `<input type="file" id="attachment" name="attachment" class="swal2-input" required>`,--}}
{{--                                showCancelButton: true,--}}
{{--                                confirmButtonText: '{{trans('messages.submit')}}',--}}
{{--                                cancelButtonText: '{{trans('messages.cancel')}}',--}}
{{--                                customClass: {--}}
{{--                                    confirmButton: 'btn btn-success',--}}
{{--                                    cancelButton: 'btn btn-secondary'--}}
{{--                                },--}}
{{--                                preConfirm: function () {--}}
{{--                                    var fileInput = document.getElementById('attachment');--}}
{{--                                    if (!fileInput.files.length) {--}}
{{--                                        Swal.showValidationMessage('{{trans('messages.attachment-required')}}');--}}
{{--                                        return false;--}}
{{--                                    }--}}
{{--                                    return fileInput.files[0];--}}
{{--                                }--}}
{{--                            }).then((fileResult) => {--}}
{{--                                if (fileResult.isConfirmed) {--}}
{{--                                    var file = fileResult.value;--}}

{{--                                    var formData = new FormData();--}}
{{--                                    formData.append('_token', $('meta[name="csrf-token"]').attr('content'));--}}
{{--                                    formData.append('order_id', orderId);--}}
{{--                                    formData.append('status', newStatus);--}}
{{--                                    formData.append('attachment', file);--}}

{{--                                    Swal.fire({--}}
{{--                                        icon: 'info',--}}
{{--                                        title: '{{trans('messages.loading')}}',--}}
{{--                                        text: '{{trans('messages.processing-request')}}',--}}
{{--                                        allowOutsideClick: false,--}}
{{--                                        didOpen: () => {--}}
{{--                                            Swal.showLoading();--}}
{{--                                        }--}}
{{--                                    });--}}

{{--                                    $.ajax({--}}
{{--                                        url: '{{route('assurances.orders.updateStatus')}}',--}}
{{--                                        type: 'POST',--}}
{{--                                        data: formData,--}}
{{--                                        contentType: false,--}}
{{--                                        processData: false,--}}
{{--                                        success: function (data) {--}}
{{--                                            Swal.fire({--}}
{{--                                                title: '{{trans('messages.updated')}}!',--}}
{{--                                                text: '{{trans('messages.change-success')}}.',--}}
{{--                                                icon: 'success',--}}
{{--                                                confirmButtonText: '{{trans('messages.close')}}',--}}
{{--                                                customClass: {--}}
{{--                                                    confirmButton: 'btn btn-success'--}}
{{--                                                }--}}
{{--                                            }).then(() => {--}}
{{--                                                location.reload();--}}
{{--                                            });--}}
{{--                                        },--}}
{{--                                        error: function (data) {--}}
{{--                                            Swal.fire({--}}
{{--                                                title: '{{trans('messages.not-updated')}}!',--}}
{{--                                                text: '{{trans('messages.not-update-error')}}.',--}}
{{--                                                icon: 'error',--}}
{{--                                                confirmButtonText: '{{trans('messages.close')}}',--}}
{{--                                            });--}}
{{--                                            location.reload();--}}
{{--                                        }--}}
{{--                                    });--}}
{{--                                }--}}
{{--                            });--}}
{{--                        } else {--}}
{{--                            // For other statuses, proceed without additional input--}}
{{--                            Swal.fire({--}}
{{--                                icon: 'info',--}}
{{--                                title: '{{trans('messages.loading')}}',--}}
{{--                                text: '{{trans('messages.processing-request')}}',--}}
{{--                                allowOutsideClick: false,--}}
{{--                                didOpen: () => {--}}
{{--                                    Swal.showLoading();--}}
{{--                                }--}}
{{--                            });--}}

{{--                            $.ajax({--}}
{{--                                url: '{{route('assurances.orders.updateStatus')}}',--}}
{{--                                type: 'POST',--}}
{{--                                data: {--}}
{{--                                    _token: $('meta[name="csrf-token"]').attr('content'),--}}
{{--                                    order_id: orderId,--}}
{{--                                    status: newStatus--}}
{{--                                },--}}
{{--                                success: function (data) {--}}
{{--                                    Swal.fire({--}}
{{--                                        title: '{{trans('messages.updated')}}!',--}}
{{--                                        text: '{{trans('messages.change-success')}}.',--}}
{{--                                        icon: 'success',--}}
{{--                                        confirmButtonText: '{{trans('messages.close')}}',--}}
{{--                                        customClass: {--}}
{{--                                            confirmButton: 'btn btn-success'--}}
{{--                                        }--}}
{{--                                    }).then(() => {--}}
{{--                                        location.reload();--}}
{{--                                    });--}}
{{--                                },--}}
{{--                                error: function (data) {--}}
{{--                                    Swal.fire({--}}
{{--                                        title: '{{trans('messages.not-updated')}}!',--}}
{{--                                        text: '{{trans('messages.not-update-error')}}.',--}}
{{--                                        icon: 'error',--}}
{{--                                        confirmButtonText: '{{trans('messages.close')}}',--}}
{{--                                    });--}}
{{--                                    location.reload();--}}
{{--                                }--}}
{{--                            });--}}
{{--                        }--}}
{{--                    } else {--}}
{{--                        // Revert the dropdown to the old value if cancelled--}}
{{--                        $(this).val(oldStatus);--}}
{{--                    }--}}
{{--                });--}}
{{--            });--}}
{{--        </script>--}}



        {{-- Change status --}}

        <script>
            $(document).on('change', '.status-select', function () {
                var $select = $(this);
                var orderId = $select.data('id');
                var newStatus = $select.val();
                var orderValue = {{$order->value}}; // Make sure this is set on the element
                var oldStatus = $select.data('old-status') || $select.val();
                $select.data('old-status', oldStatus);

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
                }).then(function (result) {
                    if (result.isConfirmed) {
                        // If the new status is 1, prompt for payment input
                        if (newStatus == 1) {
                            Swal.fire({
                                title: '{{ trans("messages.enter-payment") }}',
                                html: `<p>{{trans('main.order_value')}}: ADE ${orderValue}</p>`,
                                input: 'number',
                                inputAttributes: {
                                    min: 0,
                                    step: 0.01
                                },
                                showCancelButton: true,
                                confirmButtonText: '{{ trans("messages.submit") }}',
                                cancelButtonText: '{{ trans("messages.cancel") }}',
                                customClass: {
                                    confirmButton: 'btn btn-success',
                                    cancelButton: 'btn btn-secondary'
                                },
                                preConfirm: function (paymentValue) {
                                    var value = parseFloat(paymentValue);
                                    var orderValueFloat = parseFloat(orderValue);
                                    if (!value || value <= 0) {
                                        Swal.showValidationMessage('{{ trans("messages.invalid-payment") }}');
                                    } else if (value > orderValueFloat) {
                                        Swal.showValidationMessage('{{trans('messages.Payment value cannot exceed the order value')}}');
                                    }
                                    return value;
                                }
                            }).then(function (paymentResult) {
                                if (paymentResult.isConfirmed) {
                                    var paymentValue = paymentResult.value;
                                    Swal.fire({
                                        icon: 'info',
                                        title: '{{ trans("messages.loading") }}',
                                        text: '{{ trans("messages.processing-request") }}',
                                        allowOutsideClick: false,
                                        didOpen: function () {
                                            Swal.showLoading();
                                        }
                                    });
                                    $.ajax({
                                        url: '{{ route("assurances.orders.updateStatus") }}',
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
                                            }).then(function () {
                                                location.reload();
                                            });
                                        },
                                        error: function (data) {
                                            Swal.fire({
                                                title: '{{ trans("messages.not-updated") }}!',
                                                text: '{{ trans("messages.not-update-error") }}.',
                                                icon: 'error',
                                                confirmButtonText: '{{ trans("messages.close") }}',
                                            });
                                            location.reload();
                                        }
                                    });
                                } else {
                                    // If payment input is cancelled, revert the dropdown value
                                    $select.val(oldStatus);
                                }
                            });
                        } else {
                            // For statuses other than 1, proceed without additional input
                            Swal.fire({
                                icon: 'info',
                                title: '{{ trans("messages.loading") }}',
                                text: '{{ trans("messages.processing-request") }}',
                                allowOutsideClick: false,
                                didOpen: function () {
                                    Swal.showLoading();
                                }
                            });
                            $.ajax({
                                url: '{{ route("assurances.orders.updateStatus") }}',
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
                                    }).then(function () {
                                        location.reload();
                                    });
                                },
                                error: function (data) {
                                    Swal.fire({
                                        title: '{{ trans("messages.not-updated") }}!',
                                        text: '{{ trans("messages.not-update-error") }}.',
                                        icon: 'error',
                                        confirmButtonText: '{{ trans("messages.close") }}',
                                    });
                                    location.reload();
                                }
                            });
                        }
                    } else {
                        // Revert the dropdown to the old value if cancelled
                        $select.val(oldStatus);
                    }
                });
            });
        </script>


    @endpush

@stop
