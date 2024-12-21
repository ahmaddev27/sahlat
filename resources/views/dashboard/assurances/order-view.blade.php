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
                                    <h6 class="mb-2">{{ trans('assurances.payment') }}</h6>

                                    @if ($order->payment)
                                        <table>
                                            <tbody>
                                            @php
                                                $statusText = paymentStatus($order->payment->status);
                                                $badgeClass = OrdorClass($order->payment->status);
                                                $div = '<div class="d-inline-block m-1"><span class="badge badge-glow ' . $badgeClass . '">' . $statusText . '</span></div>';
                                            @endphp

                                            <tr>
                                                <td class="pr-1 ">{{ trans('assurances.status') }}</td>
                                                <td><span class="font-weight-bold">{!! $div !!}</span></td>
                                            </tr>

                                            <tr>
                                                <td class="pr-1">{{ trans('assurances.payment-type') }}</td>
                                                <td><span class="font-weight-bold"><div class="d-inline-block m-1"> {{ $order->payment?->type ?: '-' }} </div></span></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    @else
                                        <div class="d-inline-block m-1">
                                            <span class="badge badge-glow {{ OrdorClass('0') }}">{{ paymentStatus(0) }} </span>
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
                                            <p class="invoice-total-title d-inline">{{ trans('assurances.price') }}</p>
                                            <p class="invoice-total-amount d-inline p-2">ADE {{ $order->assurance->price }}</p>
                                        </div>
                                        <div class="invoice-total-item mt-2 ">
                                            <p class="invoice-total-title d-inline">{{ trans('main.discount') }}</p>
                                            <p class="invoice-total-amount d-inline p-2">  {{ $order->discount ?? '-' }}</p>
                                        </div>

                                        <div class="invoice-total-item mt-2">
                                            <p class="invoice-total-title d-inline">{{ trans('main.commission') }}</p>
                                            <p class="invoice-total-amount d-inline p-2">ADE {{ setting('commission') }}</p>
                                        </div>

                                        <hr class="my-50" />

                                        <div class="invoice-total-item">
                                            <p class="invoice-total-title d-inline">{{ trans('main.total') }}</p>
                                            <p class="invoice-total-amount d-inline p-2">
                                                ADE {{ $order->assurance->price + setting('commission') - $order->discount }}</p>
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
                                                        <span class="badge badge-glow badge-info">{{ trans('main.' . $attachment->type) }}</span>
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
                            <div class="d-inline-block ">
                                <span class="badge badge-glow {{ OrdorClass($order->status) }}"> {{ StatusesAssurance($order->status) }}</span>
                            </div>

                            @php
                                $statusSelect = '<select class="status-select form-control d-inline-block status-font" data-id="' . $order->id . '" data-old-status="' . $order->status . '" style="width: 100%;">';
                                $statusSelect .= '<option selected disabled>' . trans('main.change') . '</option>';

                                foreach (StatusesAssurance() as $key => $value) {
                                    $selected = ($key == $order->status) ? 'selected' : '';
                                    $disabled = ($key < $order->status) ? 'disabled' : '';
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

        <script>
            $(document).on('change', '.status-select', function () {
                var orderId = $(this).data('id');
                var newStatus = $(this).val();

                // Store the old value to revert if the user cancels
                var oldStatus = $(this).data('old-status');
                $(this).data('old-status', oldStatus || $(this).val());

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
                        if (newStatus == 2 || newStatus == 3) {
                            // Determine the dynamic label for the file input
                            let inputLabel = newStatus == 2
                                ? '{{trans('messages.upload-contract')}}'
                                : '{{trans('messages.upload-attachment')}}';

                            Swal.fire({
                                title: inputLabel,
                                html: `<input type="file" id="attachment" name="attachment" class="swal2-input" required>`,
                                showCancelButton: true,
                                confirmButtonText: '{{trans('messages.submit')}}',
                                cancelButtonText: '{{trans('messages.cancel')}}',
                                customClass: {
                                    confirmButton: 'btn btn-success',
                                    cancelButton: 'btn btn-secondary'
                                },
                                preConfirm: function () {
                                    var fileInput = document.getElementById('attachment');
                                    if (!fileInput.files.length) {
                                        Swal.showValidationMessage('{{trans('messages.attachment-required')}}');
                                        return false;
                                    }
                                    return fileInput.files[0];
                                }
                            }).then((fileResult) => {
                                if (fileResult.isConfirmed) {
                                    var file = fileResult.value;

                                    var formData = new FormData();
                                    formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
                                    formData.append('order_id', orderId);
                                    formData.append('status', newStatus);
                                    formData.append('attachment', file);

                                    Swal.fire({
                                        icon: 'info',
                                        title: '{{trans('messages.loading')}}',
                                        text: '{{trans('messages.processing-request')}}',
                                        allowOutsideClick: false,
                                        didOpen: () => {
                                            Swal.showLoading();
                                        }
                                    });

                                    $.ajax({
                                        url: '{{route('assurances.orders.updateStatus')}}',
                                        type: 'POST',
                                        data: formData,
                                        contentType: false,
                                        processData: false,
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
                                                location.reload();
                                            });
                                        },
                                        error: function (data) {
                                            Swal.fire({
                                                title: '{{trans('messages.not-updated')}}!',
                                                text: '{{trans('messages.not-update-error')}}.',
                                                icon: 'error',
                                                confirmButtonText: '{{trans('messages.close')}}',
                                            });
                                            location.reload();
                                        }
                                    });
                                }
                            });
                        } else {
                            // For other statuses, proceed without additional input
                            Swal.fire({
                                icon: 'info',
                                title: '{{trans('messages.loading')}}',
                                text: '{{trans('messages.processing-request')}}',
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });

                            $.ajax({
                                url: '{{route('assurances.orders.updateStatus')}}',
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
                                        location.reload();
                                    });
                                },
                                error: function (data) {
                                    Swal.fire({
                                        title: '{{trans('messages.not-updated')}}!',
                                        text: '{{trans('messages.not-update-error')}}.',
                                        icon: 'error',
                                        confirmButtonText: '{{trans('messages.close')}}',
                                    });
                                    location.reload();
                                }
                            });
                        }
                    } else {
                        // Revert the dropdown to the old value if cancelled
                        $(this).val(oldStatus);
                    }
                });
            });
        </script>


    @endpush

@stop
