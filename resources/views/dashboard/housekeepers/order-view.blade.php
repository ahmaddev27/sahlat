@extends('dashboard.layouts.master',['title'=>trans('dashboard_aside.housekeepers-orders')])

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

    <!-- BEGIN: Content-->
<div class="content-body">
    <section class="invoice-preview-wrapper">
        <div class="row invoice-preview">
            <!-- Invoice -->
            <div class="col-xl-9 col-md-8 col-12">
                <div class="card invoice-preview-card p-2">
                    <div class="card-body invoice-padding pb-0">
                        <!-- Header starts -->
                        <div class="d-flex flex-column flex-md-row invoice-spacing mt-0">
                            <!-- Left column for order details -->
                            <div class="col-12 col-md-8 p-3 scrollable-container">
                                <h6 class="mb-3">{{ trans('housekeeper.order-details') }}</h6>
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <td class="pr-2">{{ trans('housekeeper.name') }}</td>
                                            <td class="font-weight-bold">{{ $order->user->name }}</td>
                                            <td class="pr-2">{{ trans('housekeeper.number_id') }}</td>
                                            <td class="font-weight-bold">{{ $order->user->number_id }}</td>
                                        </tr>
                                        @if($order->housekeeper)
                                            <tr>
                                                <td class="pr-2 pb-2">{{ trans('housekeeper.housekeeper') }}</td>
                                                <td colspan="3" class="d-flex align-items-center">
                                                    <img width="60" height="60" class="user-avatar mr-3 rounded-circle" src="{{ $order->housekeeper->getAvatar() }}" alt="Housekeeper Avatar">
                                                    <span class="font-weight-bold">{{ $order->housekeeper->name }}</span>
                                                </td>
                                            </tr>
                                        @endif
                                        <tr>
                                            <td class="pr-2 pt-3">{{ trans('housekeeper.company') }}</td>
                                            <td class="font-weight-bold pt-3">{{ $order->housekeeper?->company->name }}</td>
                                        </tr>
                                        <tr>
                                            <td class="pr-2 pt-4">{{ trans('housekeeper.details') }}</td>
                                            <td colspan="3" class="pt-4">{{ $order->details ?? '-' }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- Right column for order ID and date -->
                            <div class="col-12 col-md-4 p-3 mt-4 mt-md-0">
                                <div class="row invoice-spacing">
                                    <div class="w-100">
                                        <h4 class="invoice-title mb-3">
                                            {{ trans('housekeeper.order_id') }}
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
                            <div class="col-xl-7 p-0">
                                <h6 class="mb-2">{{ trans('housekeeper.user') }}</h6>
                                <h6 class="mb-25">{{ $order->user->name }}</h6>
                                @if(app()->getLocale() === 'ar')
                                    <div class="card-text mb-25" style="direction: ltr">
                                        {{ formatedPhone($order->user->phone) }} - {{ $order->user->email }}
                                        <img src="{{ $order->user->getAvatar() }}" alt="avatar" id="add-avatar-img" class="user-avatar icon users-avatar-shadow rounded-circle cursor-pointer" height="60" width="60" />
                                    </div>
                                @else
                                    <div class="card-text mb-25">
                                        <img src="{{ $order->user->getAvatar() }}" alt="avatar" id="add-avatar-img" class="user-avatar icon users-avatar-shadow rounded-circle cursor-pointer" height="60" width="60" />
                                        {{ formatedPhone($order->user->phone) }} - {{ $order->user->email }}
                                    </div>
                                @endif
                            </div>
                            <div class="col-xl-4 p-0 mt-xl-0 mt-2">
                                <h6 class="mb-2">{{ trans('housekeeper.payment') }}</h6>
                                @if($order->payment()->count() > 0)
                                    <table>
                                        <tbody>
                                            @php
                                                $statusText = paymentStatus($order->payment->status);
                                                $badgeClass = OrdorClass($order->payment->status);
                                                $div = '<div class="d-inline-block m-1"><span class="badge badge-glow ' . $badgeClass . '">' . $statusText . '</span></div>';
                                            @endphp
                                            <tr>
                                                <td class="pr-1">{{ trans('housekeeper.status') }}</td>
                                                <td><span class="font-weight-bold">{!! $div !!}</span></td>
                                            </tr>
                                            <tr>
                                                <td class="pr-1">{{ trans('housekeeper.payment-type') }}</td>
                                                <td><span class="font-weight-bold">
                                                        <div class="d-inline-block m-1">
                                                            {{$order->payment->is_tabby ? $order->payment->is_tabby?'Tabby' : ($order->payment->is_stripe ? 'stripe' : $order->payment->payment_type): $order->payment->payment_type}}</div>
                                                    </span></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                @else
                                    <div class="d-inline-block m-1"><span class="badge badge-glow {{ OrdorClass('0') }}">{{ paymentStatus(0) }}</span></div>
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
                                        <p class="invoice-total-title d-inline">{{ trans('housekeeper.salary') }}</p>
                                        <p class="invoice-total-amount d-inline p-2">ADE {{ $order->housekeeper->salary }}</p>
                                    </div>
                                    <div class="invoice-total-item mt-2">
                                        <p class="invoice-total-title d-inline">{{ trans('main.total-payment') }}</p>
                                        <p class="invoice-total-amount d-inline p-2">ADE {{ $order->payment?->payment_value ?? 0 }}</p>
                                    </div>
                                    <hr class="my-50" />
                                    <div class="invoice-total-item mt-2">
                                        <p class="invoice-total-title d-inline">{{ trans('main.remain') }}</p>
                                        <p class="invoice-total-amount d-inline p-2">ADE {{ $order->payment?->remaining_amount ?? $order->value }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if($order->orderattAchments->count() > 0)
                            <!-- Invoice Attachments -->
                            <div class="attachments-section mt-4">
                                <h6 class="mb-3">{{ trans('assurances.attachments') }}</h6>
                                <ul class="list-unstyled d-flex flex-wrap">
                                    @foreach($order->orderattAchments as $attachment)
                                        <li class="d-flex align-items-center mr-4 mb-2">
                                            <div class="attachment-title mr-2">
                                                <p class="mb-0 font-weight-bold">{{ $attachment->title }}</p>
                                            </div>
                                            <div class="attachment-file">
                                                <a href="{{ $attachment->getFile() }}" target="_blank" class="text-body">
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
                        <div class="d-inline-block"><span class="badge badge-glow {{ OrdorClass($order->status) }}">{{ HouseKeeperStatuses($order->status) }}</span></div>
                        @php
                            $statusSelect = '<select class="status-select form-control d-inline-block status-font" data-id="' . $order->id . '" data-old-status="' . $order->status . '" style="width: 100%;">';
                            $statusSelect .= '<option selected disabled>' . trans('main.change') . '</option>';
                            foreach (HouseKeeperStatuses() as $key => $value) {
                                $selected = ($key == $order->status) ? 'selected' : '';
                                $disabled = ($key < $order->status) ? 'disabled' : '';
                                $statusSelect .= '<option value="' . $key . '" ' . $selected . ' ' . $disabled . '>' . $value . '</option>';
                            }
                            $statusSelect .= '</select>';
                        @endphp
                        <div class="p-1">{{ trans('housekeeper.change-status') }}</div>
                        <div class="mb-2">{!! $statusSelect !!}</div>
                    </div>
                </div>
                @if($order->note)
                    <div class="card">
                        <div class="card-body">
                            <div class="p-1">{{ trans('housekeeper.note') }}</div>
                            <div class="mb-2">{!! $order->note !!}</div>
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

        <script>
            $(document).on('change', '.status-select', function () {
                let $select = $(this);
                let orderId = {{$order->id}};
                let newStatus = $select.val();
                let orderValue = {{$order->value}};
                let oldStatus = $select.data('old-status') || $select.val();

                Swal.fire({
                    title: '{{trans("messages.sure?")}}',
                    text: "{{trans('messages.change-status')}}",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: '{{trans("messages.change")}}',
                    cancelButtonText: '{{trans("messages.cancel")}}',
                    customClass: {
                        confirmButton: 'btn btn-danger',
                        cancelButton: 'btn btn-secondary ml-1'
                    },
                }).then((result) => {
                    if (!result.isConfirmed) {
                        $select.val(oldStatus); // Revert to old status if canceled
                        return;
                    }
                    handleStatusUpdate(orderId, newStatus, orderValue);
                });
            });

            function handleStatusUpdate(orderId, newStatus, orderValue) {
                switch (parseInt(newStatus)) {
                    case 1:
                        promptForPayment(orderId, orderValue, newStatus);
                        break;
                    case 3:
                        promptForFileUpload(orderId, newStatus);
                        break;
                    case 5:
                        promptForNoteUpdate(orderId, newStatus);
                        break;
                    default:
                        showLoadingAndUpdate(orderId, newStatus);
                }
            }

            function promptForPayment(orderId, orderValue, newStatus) {
                Swal.fire({
                    title: '{{ trans("messages.enter-payment") }}',
                    html: `<p>{{trans('main.order_value')}}: ADE ${orderValue}</p>
                   <input type="number" id="payment-value" class="swal2-input" min="0" step="0.01" required>`,
                    showCancelButton: true,
                    confirmButtonText: '{{ trans("messages.submit") }}',
                    cancelButtonText: '{{trans("messages.cancel")}}',
                    preConfirm: () => {
                        let value = parseFloat(document.getElementById('payment-value').value);
                        if (!value || value <= 0 || value > parseFloat(orderValue)) {
                            Swal.showValidationMessage('{{ trans("messages.invalid-payment") }}');
                            return false;
                        }
                        return value;
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        showLoadingAndUpdate(orderId, newStatus, { payment_value: result.value });
                    }
                });
            }

            function promptForFileUpload(orderId, newStatus) {
                Swal.fire({
                    html: `
                <label for="payment-attachment">{{trans('messages.upload-attachment')}}</label>
                <input type="file" id="payment-attachment" class="swal2-input" required><br><br>
                <label for="contract-attachment">{{trans('messages.upload-contract')}}</label>
                <input type="file" id="contract-attachment" class="swal2-input" required>
            `,
                    showCancelButton: true,
                    confirmButtonText: '{{trans('messages.submit')}}',
                    cancelButtonText: '{{trans('messages.cancel')}}',
                    preConfirm: () => {
                        let paymentFile = document.getElementById('payment-attachment').files[0];
                        let contractFile = document.getElementById('contract-attachment').files[0];
                        if (!paymentFile || !contractFile) {
                            Swal.showValidationMessage('{{trans('messages.required-files')}}');
                            return false;
                        }
                        return { paymentFile, contractFile };
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        let formData = new FormData();
                        formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
                        formData.append('order_id', orderId);
                        formData.append('status', newStatus);
                        formData.append('payment_attachment', result.value.paymentFile);
                        formData.append('contract_attachment', result.value.contractFile);
                        showLoadingAndUpdate(orderId, newStatus, formData, true);
                    }
                });
            }

            function promptForNoteUpdate(orderId, newStatus) {
                Swal.fire({
                    title: '{{trans('messages.enter-note')}}',
                    input: 'text',
                    inputPlaceholder: '{{trans('messages.enter-note-placeholder')}}',
                    showCancelButton: true,
                    confirmButtonText: '{{trans('messages.submit')}}',
                    cancelButtonText: '{{trans('messages.cancel')}}',
                    preConfirm: (note) => {
                        if (!note) {
                            Swal.showValidationMessage('{{trans('messages.note-required')}}');
                            return false;
                        }
                        return note;
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        showLoadingAndUpdate(orderId, newStatus, { note: result.value });
                    }
                });
            }

            function showLoadingAndUpdate(orderId, newStatus, additionalData = {}, isFormData = false) {
                let data = isFormData ? additionalData : {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    order_id: orderId,
                    status: newStatus,
                    ...additionalData
                };

                Swal.fire({
                    title: '{{ trans("messages.loading") }}',
                    text: '{{ trans("messages.processing-request") }}',
                    icon: 'info',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                        sendAjaxRequest('{{route("housekeepers.orders.updateStatus")}}', data, isFormData);
                    }
                });
            }

            function sendAjaxRequest(url, data, isFormData = false) {
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: data,
                    contentType: isFormData ? false : 'application/x-www-form-urlencoded; charset=UTF-8',
                    processData: !isFormData,
                    success: () => {
                        Swal.fire({
                            title: '{{ trans("messages.updated") }}!',
                            text: '{{ trans("messages.change-success") }}.',
                            icon: 'success',
                            confirmButtonText: '{{ trans("messages.close") }}',
                            customClass: { confirmButton: 'btn btn-success' }
                        }).then(() => location.reload());
                    },
                    error: () => {
                        Swal.fire({
                            title: '{{ trans("messages.not-updated") }}!',
                            text: '{{ trans("messages.not-update-error") }}.',
                            icon: 'error',
                            confirmButtonText: '{{ trans("messages.close") }}'
                        }).then(() => location.reload());
                    }
                });
            }
        </script>


    @endpush

@stop
