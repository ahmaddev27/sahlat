@extends('dashboard.layouts.master',['title'=>trans('dashboard_aside.violations-orders')])

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



@section('path')

    <li class="breadcrumb-item active"><a
            href="{{route('violations.index')}}">{{trans('dashboard_aside.violations')}} </a></li>

@endsection

@section('content')

    <!-- BEGIN: Content-->
    <div class="content-body">
        <section class="invoice-preview-wrapper ">
            <div class="row invoice-preview">
                <!-- Invoice -->
                <div class="col-xl-9 col-md-8 col-12">
                    <div class="card invoice-preview-card p-2">
                        <div class="card-body invoice-padding pb-0">
                            {{--                            orderDetials--}}
                            <div class="d-flex justify-content-between flex-md-row flex-column invoice-spacing mt-0">
                                <div class="col-xl-8 p-3">
                                    <h6 class="mb-3">{{trans('housekeeper.order-details')}}</h6>

                                    <table class="table table-responsive">
                                        <tbody>


                                        <tr>
                                            <td class="pr-2 pb-2 pt-2 ">{{trans('housekeeper.name')}}</td>
                                            <td class="font-weight-bold">{{$order->name}}</td>

                                            <td class="pr-2 pb-2 pt-2">{{trans('housekeeper.number_id')}}</td>
                                            <td class="font-weight-bold">{{$order->number_id}}</td>
                                        </tr>


                                        <tr>
                                            <td class="pr-2 pb-2 pt-2">{{trans('violations.violation_number')}}</td>

                                            <td colspan="" class="font-weight-bold pt-1">

                                                {{$order->violation_number}}
                                            </td>


                                        </tr>

                                        <tr>
                                            <td class="pr-2 pt-4">{{trans('housekeeper.details')}}</td>
                                            <td colspan="3" class="pt-3">{{$order->details ?? '-'}}</td>
                                        </tr>

                                        </tbody>
                                    </table>
                                </div>

                                <div class="col-xl-4 p-3 mt-5">
                                    <div class="row invoice-spacing">
                                        <div class="w-100">
                                            <h4 class="invoice-title mb-3">
                                                {{trans('assurances.order_id')}}
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

                        </div>

                        <hr class="invoice-spacing"/>

                        <!-- Address and Contact starts -->

                        <div class="card-body invoice-padding pt-0">
                            <div class="row invoice-spacing">
                                <div class="col-xl-8 p-0">
                                    <h6 class="mb-2">{{trans('assurances.user')}}</h6>
                                    <h6 class="mb-25">{{$order->user->name}}</h6>
                                    <div class="card-text mb-25">
                                        <img src="{{ $order->user->getAvatar() }}" alt="avatar" id="add-avatar-img"
                                             class="user-avatar icon users-avatar-shadow rounded-circle cursor-pointer"
                                             height="60" width="60"/>

                                        {{$order->user->phone}} -
                                        {{$order->user->email}}

                                    </div>
                                </div>


                                <div class="col-xl-4 p-0 mt-xl-0 mt-2">
                                    <h6 class="mb-2">{{trans('assurances.payment')}}</h6>

                                    @if($order->payment)

                                        <table>
                                            <tbody>

                                            @php
                                                $statusText = paymentStatus($order->payment->status);
                                                $badgeClass = OrdorClass($order->payment->status);
                                                                                                    // Combine the status badge and the select dropdown inline
                                                $div = '<div class="d-inline-block m-1"><span class="badge badge-glow ' . $badgeClass . '">' . $statusText . '</span></div>';
                                            @endphp


                                            <tr>
                                                <td class="pr-1 ">{{trans('assurances.status')}}</td>
                                                <td><span class="font-weight-bold">{!! $div!!}</span></td>
                                            </tr>


                                            <tr>
                                                <td class="pr-1">{{trans('assurances.payment-type')}}</td>
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
                                            <p class="invoice-total-title d-inline">{{trans('assurances.price')}}</p>
                                            <p class="invoice-total-amount d-inline p-2">
                                                ADE {{$order->value}}</p>

                                        </div>
                                        <div class="invoice-total-item mt-2 ">
                                            <p class="invoice-total-title d-inline">{{trans('main.discount')}}</p>
                                            <p class="invoice-total-amount d-inline p-2">  {{$order->discount??'-'}}</p>
                                        </div>

                                        <div class="invoice-total-item mt-2">
                                            <p class="invoice-total-title d-inline">{{trans('main.commission')}}</p>
                                            <p class="invoice-total-amount d-inline p-2">
                                                ADE {{setting('commission')}}</p>
                                        </div>

                                        <hr class="my-50"/>

                                        <div class="invoice-total-item">
                                            <p class="invoice-total-title d-inline">{{trans('main.total')}}</p>
                                            <p class="invoice-total-amount d-inline p-2">
                                                ADE {{$order->value + setting('commission') - $order->discount }}</p>
                                        </div>
                                    </div>
                                </div>


                            </div>

                            @if($order->orderattAchments->count()>0 || $order->attachments->count()>0)
                                <!-- Invoice Attachments -->
                                <div class="attachments-section mt-4">
                                    <h6 class="mb-3">{{trans('assurances.attachments')}}</h6>
                                    <ul class="list-unstyled d-flex flex-wrap">

                                        @foreach($order->orderattAchments as $a)
                                            <li class="d-flex align-items-center mr-4 mb-2">
                                                {{--                                                    <div class="attachment-title mr-2">--}}
                                                {{--                                                        <p class="mb-0 font-weight-bold">{{$a->title}}</p>--}}
                                                {{--                                                    </div>--}}

                                                <div class="attachment-file">
                                                    <a href="{{$a->getFile()}}" target="_blank" class="text-body">
                                                        <i class="font-large-1" data-feather="file"></i>
                                                    </a>
                                                </div>
                                                <div class="d-inline-block m-1">
                                                    <span class="badge badge-glow badge-info">{{ trans('main.' . $a->type) }}</span>
                                                </div>
                                            </li>
                                        @endforeach

                                            @foreach($order->attachments as $att)
                                            <li class="d-flex align-items-center mr-4 mb-2">
                                                {{--                                                    <div class="attachment-title mr-2">--}}
                                                {{--                                                        <p class="mb-0 font-weight-bold">{{$a->title}}</p>--}}
                                                {{--                                                    </div>--}}
                                                <div class="attachment-file">
                                                    <a href="{{$att->getFile()}}" target="_blank" class="text-body">
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
                        <hr class="invoice-spacing"/>

                        <!-- Invoice Note starts -->
                        {{--                                <div class="card-body invoice-padding pt-0">--}}
                        {{--                                    <div class="row">--}}
                        {{--                                        <div class="col-12">--}}
                        {{--                                            <span class="font-weight-bold">Note:</span>--}}
                        {{--                                            <span>It was a pleasure working with you and your team. We hope you will keep us in mind for future freelance--}}
                        {{--                                                projects. Thank You!</span>--}}
                        {{--                                        </div>--}}
                        {{--                                    </div>--}}
                        {{--                                </div>--}}
                        <!-- Invoice Note ends -->
                    </div>
                </div>
                <!-- /Invoice -->

                <!-- Invoice Actions -->
                <div class="col-xl-3 col-md-4 col-12 invoice-actions mt-md-0 mt-2">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-inline-block "><span
                                    class="badge badge-glow {{OrdorClass($order->status)}}"> {{StatusesViolations($order->status)}}</span>
                            </div>


                            @php    // Create the select dropdown for status change
                                                    $statusSelect = '<select class="status-select form-control d-inline-block status-font" data-id="' . $order->id . '" data-old-status="' . $order->status . '" style="width: 100%;">';

                                                    $statusSelect .= '<option selected disabled>' . trans('main.change') . '</option>';

                                                    foreach (StatusesViolations() as $key => $value) {
                    // Check if the key matches the current status
                    $selected = ($key == $order->status) ? 'selected' : '';

                    // Check if the key is less than the current status, and disable it
                    $disabled = ($key < $order->status) ? 'disabled' : '';

                    $statusSelect .= '<option value="' . $key . '" ' . $selected . ' ' . $disabled . '>' . $value . '</option>';
                }
                                                    $statusSelect .= '</select>';  @endphp

                            <div class="p-1">{{trans('violations.change-status')}}</div>

                            <div class="mb-2"> {!! $statusSelect!!}</div>


                        </div>
                    </div>

                    @if($order->note)
                        <div class="card">
                            <div class="card-body">

                                <div class="p-1">{{trans('violations.note')}}</div>

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
                        // Handle specific cases for status 1, 4, and 3
                        if (newStatus == 1 || newStatus == 4) {
                            let inputPlaceholder = newStatus == 1
                                ? '{{trans('messages.enter-price-placeholder')}}'
                                : '{{trans('messages.enter-note-placeholder')}}';
                            let inputTitle = newStatus == 1
                                ? '{{trans('messages.enter-price')}}'
                                : '{{trans('messages.enter-note')}}';
                            let inputValidationMessage = newStatus == 1
                                ? '{{trans('messages.price-required')}}'
                                : '{{trans('messages.note-required')}}';

                            // Prompt for input (price for 1, note for 4)
                            Swal.fire({
                                title: inputTitle,
                                input: 'text',
                                inputPlaceholder: inputPlaceholder,
                                inputAttributes: {
                                    'aria-label': inputPlaceholder,
                                    'aria-required': 'true'
                                },
                                showCancelButton: true,
                                confirmButtonText: '{{trans('messages.submit')}}',
                                cancelButtonText: '{{trans('messages.cancel')}}',
                                customClass: {
                                    confirmButton: 'btn btn-success',
                                    cancelButton: 'btn btn-secondary'
                                },
                                preConfirm: function (inputValue) {
                                    if (!inputValue) {
                                        Swal.showValidationMessage(inputValidationMessage);
                                        return false; // Prevent submission
                                    }
                                    return inputValue;
                                }
                            }).then((inputResult) => {
                                if (inputResult.isConfirmed) {
                                    var inputValue = inputResult.value;
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
                                        url: '{{route('violations.updateStatus')}}',
                                        type: 'POST',
                                        data: {
                                            _token: $('meta[name="csrf-token"]').attr('content'),
                                            order_id: orderId,
                                            status: newStatus,
                                            value: inputValue
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
                                                window.location.reload(); // Recarga la página completa
                                            });
                                        },
                                        error: function (data) {
                                            Swal.fire({
                                                title: '{{trans('messages.not-updated')}}!',
                                                text: '{{trans('messages.not-update-error')}}.',
                                                icon: 'error',
                                                confirmButtonText: '{{trans('messages.close')}}',
                                            }).then(() => {
                                                window.location.reload(); // Recarga la página completa
                                            });
                                        }
                                    });
                                }
                            });
                        } else if (newStatus == 2) {
                            // If status is 3, show file upload input
                            Swal.fire({
                                title: '{{trans('messages.upload-attachment')}}',
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
                                        url: '{{route('violations.updateStatus')}}',
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
                                                window.location.reload(); // Recarga la página completa
                                            });
                                        },
                                        error: function (data) {
                                            Swal.fire({
                                                title: '{{trans('messages.not-updated')}}!',
                                                text: '{{trans('messages.not-update-error')}}.',
                                                icon: 'error',
                                                confirmButtonText: '{{trans('messages.close')}}',
                                            }).then(() => {
                                                window.location.reload(); // Recarga la página completa
                                            });
                                        }
                                    });
                                }
                            });
                        } else {

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
                                url: '{{route('violations.updateStatus')}}',
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
                                        window.location.reload(); // Recarga la página completa
                                    });
                                },
                                error: function (data) {
                                    Swal.fire({
                                        title: '{{trans('messages.not-updated')}}!',
                                        text: '{{trans('messages.not-update-error')}}.',
                                        icon: 'error',
                                        confirmButtonText: '{{trans('messages.close')}}',
                                    }).then(() => {
                                        window.location.reload(); // Recarga la página completa
                                    });
                                }
                            });
                        }
                    } else {
                        $(this).val($(this).data('old-status'));
                        $('#table').DataTable().ajax.reload();
                    }
                });
            });
        </script>

    @endpush

@stop
