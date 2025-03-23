@extends('dashboard.layouts.master',['title'=>trans('dashboard_aside.dashboard')])

@section('right')
    <div class="content-header-right text-md-right col-md-3 col-12 d-md-block d-none">
        <div class="form-group breadcrumb-right">
            <button type="button"
                    title="{{ trans('notifications.new_notification') }}"
                    id="notify-users-btn"
                    class="btn btn-warning btn-icon rounded-circle waves-effect"
                    data-toggle="modal"
                    data-target="#notificationModal">
                <i class="font-medium-3" data-feather="bell"></i>
            </button>


        </div>
    </div>

    @stop
@section('content')

    @push('css')


    @endpush



    <div class="row match-height">


        <div class="col-xl-2 col-md-4 col-sm-6">
            <div class="card text-center">
                <div class="card-body">
                    <div class="avatar bg-light-primary p-50 mb-1">
                        <div class="avatar-content">

                            <i class="font-medium-5" data-feather="heart"></i>
                        </div>
                    </div>

                    <h2 class="font-weight-bolder">{{Assurance()}}</h2>
                    <p class="card-text">{{trans('dashboard_aside.assurances')}}</p>

                </div>
            </div>
        </div>


        <div class="col-xl-2 col-md-4 col-sm-6">
            <div class="card text-center">
                <div class="card-body">
                    <div class="avatar bg-light-success p-50 mb-1">
                        <div class="avatar-content">

                            <i class="font-medium-5" data-feather="shield"></i>
                        </div>
                    </div>
                    <h4 class="font-weight-bolder">{{AssuranceOrders()}}</h4>
                    <p class="card-text">{{trans('dashboard_aside.assurances-orders')}}</p>

                </div>
            </div>
        </div>


        <div class="col-xl-2 col-md-4 col-sm-6">
            <div class="card text-center">
                <div class="card-body">
                    <div class="avatar bg-light-info p-50 mb-1">
                        <div class="avatar-content">
                            <i class="font-medium-5" data-feather="home"></i>
                        </div>
                    </div>

                    <h2 class="font-weight-bolder">{{HouseKeepers()}}</h2>
                    <p class="card-text">{{trans('dashboard_aside.housekeepers')}}</p>

                </div>
            </div>
        </div>


        <div class="col-xl-2 col-md-4 col-sm-6">
            <div class="card text-center">
                <div class="card-body">
                    <div class="avatar bg-light-warning p-50 mb-1">
                        <div class="avatar-content">
                            <i class="font-medium-5" data-feather="hexagon"></i>
                        </div>
                    </div>

                    <h2 class="font-weight-bolder">{{HouseKeepersOrders()+ HouseKeepersHourlyOrders() }}</h2>
                    <p class="card-text">{{trans('dashboard_aside.housekeepers-orders')}}</p>

                </div>
            </div>
        </div>


        <div class="col-xl-2 col-md-4 col-sm-6">
            <div class="card text-center">
                <div class="card-body">
                    <div class="avatar bg-light-danger p-50 mb-1">
                        <div class="avatar-content">

                            <i data-feather="users" class="font-medium-5"></i>
                        </div>
                    </div>
                    <h2 class="font-weight-bolder">{{users()}}</h2>
                    <p class="card-text">{{trans('dashboard_aside.users')}}</p>
                </div>
            </div>
        </div>


        <div class="col-xl-2 col-md-4 col-sm-6">
            <div class="card text-center">
                <div class="card-body">
                    <div class="avatar bg-light-secondary p-50 mb-1">
                        <div class="avatar-content">
                            <i data-feather="briefcase" class="font-medium-5"></i>
                        </div>
                    </div>
                    <h2 class="font-weight-bolder">{{companies()}}</h2>
                    <p class="card-text">{{trans('dashboard_aside.companies')}}</p>
                </div>
            </div>
        </div>


    </div>


    <div class="row">

        <!-- orders Services Card -->
        <div class="col-lg-3 col-md-6 col-12">

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">{{trans('main.AssuranceOrders')}}</h4>
                    <a href="{{route('assurances.orders.index')}}">
                        <i data-feather="eye"
                           class="font-medium-3 text-muted cursor-pointer"></i>
                    </a>
                </div>

                <div class="card-body p-0">
                    <div id="goal-overview-radial-bar-chart" class="my-2"></div>
                    <div class="row border-top text-center mx-0">

                        <div class="col-4 border-right py-1">
                            <p class="card-text text-muted mb-0">{{StatusesAssurance(1)}}</p>
                            <h3 class="font-weight-bolder mb-0">{{AssuranceOrdersByStatus(1)}}</h3>
                        </div>

                        <div class="col-4 border-right py-1">
                            <p class="card-text text-muted mb-0">{{StatusesAssurance(2)}}</p>
                            <h3 class="font-weight-bolder mb-0">{{AssuranceOrdersByStatus(2)}}</h3>
                        </div>

                        <div class="col-4 border-right py-1">
                            <p class="card-text text-muted mb-0">{{StatusesAssurance(3)}}</p>
                            <h3 class="font-weight-bolder mb-0">{{AssuranceOrdersByStatus(3)}}</h3>
                        </div>


                        {{--                        <div class="col-6 border-right py-1">--}}
                        {{--                            <p class="card-text text-muted mb-0">{{StatusesAssurance(3)}}</p>--}}
                        {{--                            <h3 class="font-weight-bolder mb-0">{{AssuranceOrdersByStatus(3)}}</h3>--}}
                        {{--                        </div>--}}
                        {{--                        <div class="col-6 py-1">--}}
                        {{--                            <p class="card-text text-muted mb-0">{{StatusesAssurance(4)}}</p>--}}
                        {{--                            <h3 class="font-weight-bolder mb-0">{{AssuranceOrdersDone()}}</h3>--}}
                        {{--                        </div>--}}
                    </div>
                </div>
            </div>
        </div>
        <!--/ orders Card -->


        <!-- violations  Card -->
        <div class="col-lg-3 col-md-6 col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">{{trans('dashboard_aside.violations')}}</h4>
                    <a href="{{route('violations.index')}}">
                        <i data-feather="eye" class="font-medium-3 text-muted cursor-pointer"></i></a>
                </div>


                <div class="card-body p-0">
                    <div id="violations" class="my-2"></div>
                    <div class="row border-top text-center mx-0">
                        {{--                            <div class="col-4 border-right py-1">--}}
                        {{--                                <p class="card-text text-muted mb-0">{{StatusesViolations(0)}}</p>--}}
                        {{--                                <h3 class="font-weight-bolder mb-0">{{violationsPendding()}}</h3>--}}
                        {{--                            </div>--}}

                        <div class="col-4 border-right py-1">
                            <p class="card-text text-muted mb-0">{{StatusesViolations(1)}}</p>
                            <h3 class="font-weight-bolder mb-0">{{violationsByStatus(1)}}</h3>
                        </div>

                        <div class="col-4 border-right py-1">
                            <p class="card-text text-muted mb-0">{{StatusesViolations(2)}}</p>
                            <h3 class="font-weight-bolder mb-0">{{violationsByStatus(2)}}</h3>
                        </div>

                        <div class="col-4 border-right py-1">
                            <p class="card-text text-muted mb-0">{{StatusesViolations(3)}}</p>
                            <h3 class="font-weight-bolder mb-0">{{violationsByStatus(3)}}</h3>
                        </div>

                        {{--                            <div class="col-4 border-right py-1">--}}
                        {{--                                <p class="card-text text-muted mb-0">{{StatusesViolations(2)}}</p>--}}
                        {{--                                <h3 class="font-weight-bolder mb-0">{{violationsByStatus(2)}}</h3>--}}
                        {{--                            </div>--}}
                        {{--                            <div class="col-6 border-right py-1">--}}
                        {{--                                <p class="card-text text-muted mb-0">{{StatusesViolations(3)}}</p>--}}
                        {{--                                <h3 class="font-weight-bolder mb-0">{{violationsByStatus(3)}}</h3>--}}
                        {{--                            </div>--}}
                        {{--                            <div class="col-6 border-right py-1">--}}
                        {{--                                <p class="card-text text-muted mb-0">{{StatusesViolations(4)}}</p>--}}
                        {{--                                <h3 class="font-weight-bolder mb-0">{{violationsByStatus(4)}}</h3>--}}
                        {{--                            </div>--}}


                    </div>
                </div>
            </div>
        </div>
        <!--/ violations Card -->


        <!-- HouseKeepers Overview Card -->
        <div class="col-lg-3 col-md-6 col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">{{trans('main.HouseKeepersOrders')}}</h4>
                    <a href="{{route('housekeepers.orders.index')}}">
                        <i data-feather="eye"
                           class="font-medium-3 text-muted cursor-pointer"></i>
                    </a>
                </div>

                <div class="card-body p-0">
                    <div id="houseKeeper" class="my-2"></div>
                    <div class="row border-top text-center mx-0">
                        <div class="col-4 border-right py-1">
                            <p class="card-text text-muted mb-0">{{HouseKeeperStatuses(1)}}</p>
                            <h3 class="font-weight-bolder mb-0">{{HouseKeeperOrdersByStatus(1)}}</h3>
                        </div>
                        <div class="col-4 border-right py-1">
                            <p class="card-text text-muted mb-0">{{HouseKeeperStatuses(2)}}</p>
                            <h3 class="font-weight-bolder mb-0">{{HouseKeeperOrdersByStatus(2)}}</h3>
                        </div>


                        <div class="col-4 py-1">
                            <p class="card-text text-muted mb-0">{{HouseKeeperStatuses(3)}}</p>
                            <h3 class="font-weight-bolder mb-0">{{HouseKeeperOrdersByStatus(3)}}</h3>
                        </div>


                    </div>
                </div>
            </div>
        </div>
        <!--/ HouseKeepers Overview Card -->


        <!-- HouseKeepers Overview Card -->
        <div class="col-lg-3 col-md-6 col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">{{trans('main.HouseKeepersOrders-hourly')}}</h4>
                    <a href="{{route('housekeepers.HourlyOrders.index')}}">
                        <i data-feather="eye"
                           class="font-medium-3 text-muted cursor-pointer"></i>
                    </a>
                </div>

                <div class="card-body p-0">
                    <div id="houseKeeperHourly" class="my-2"></div>
                    <div class="row border-top text-center mx-0">

                        <div class="col-4 border-right py-1">
                            <p class="card-text text-muted mb-0">{{HouseKeeperHourlyStatuses(1)}}</p>
                            <h3 class="font-weight-bolder mb-0">{{HouseKeeperHourlyOrdersByStatus(1)}}</h3>
                        </div>

                        <div class="col-4 border-right py-1">
                            <p class="card-text text-muted mb-0">{{HouseKeeperHourlyStatuses(2)}}</p>
                            <h3 class="font-weight-bolder mb-0">{{HouseKeeperHourlyOrdersByStatus(2)}}</h3>
                        </div>

                        <div class="col-4 border-right py-1">
                            <p class="card-text text-muted mb-0">{{HouseKeeperHourlyStatuses(3)}}</p>
                            <h3 class="font-weight-bolder mb-0">{{HouseKeeperHourlyOrdersByStatus(3)}}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--/ HouseKeepers Overview Card -->


    </div>


    <div class="row ">


        <!-- Transaction Card -->
        <div class="col-lg-4 col-md-6 col-12">
            <div class="card card-transaction">


                <div class="card-body">

                    <div class="media-body">
                        <h6 class="transaction-title">{{trans('main.users_count')}}</h6>
                        {{--                                    <small>Users</small>--}}
                    </div>
                    @foreach(cities() as $id => $city)

                        <div class="transaction-item">
                            <div class="media">
                                <div class="avatar bg-light-success rounded">
                                    <div class="avatar-content">
                                        <i data-feather="map-pin" class="avatar-icon font-medium-3"></i>
                                    </div>
                                </div>
                                <div class="media-body">
                                    <h6 class="transaction-title">{{$city}}</h6>
                                    {{--                                    <small>Users</small>--}}
                                </div>
                            </div>
                            <div
                                class="font-weight-bolder text-success">{{ \App\Models\AppUser::where('location', $id)->count() }}</div>
                        </div>

                    @endforeach


                </div>
            </div>
        </div>
        <!--/ Transaction Card -->


        <!-- Transaction Card -->
        <div class="col-lg-4 col-md-6 col-12">
            <div class="card card-transaction">


                <div class="card-body">
                    <div class="transaction-item">
                        <div class="media">
                            <div class="avatar bg-light-primary rounded">
                                <div class="avatar-content">
                                    <i data-feather="pocket" class="avatar-icon font-medium-3"></i>
                                </div>
                            </div>
                            <div class="media-body">
                                <h6 class="transaction-title">Wallet</h6>
                                <small>Starbucks</small>
                            </div>
                        </div>
                        <div class="font-weight-bolder text-danger">- $74</div>
                    </div>
                    <div class="transaction-item">
                        <div class="media">
                            <div class="avatar bg-light-success rounded">
                                <div class="avatar-content">
                                    <i data-feather="check" class="avatar-icon font-medium-3"></i>
                                </div>
                            </div>
                            <div class="media-body">
                                <h6 class="transaction-title">Bank Transfer</h6>
                                <small>Add Money</small>
                            </div>
                        </div>
                        <div class="font-weight-bolder text-success">+ $480</div>
                    </div>
                    <div class="transaction-item">
                        <div class="media">
                            <div class="avatar bg-light-danger rounded">
                                <div class="avatar-content">
                                    <i data-feather="dollar-sign" class="avatar-icon font-medium-3"></i>
                                </div>
                            </div>
                            <div class="media-body">
                                <h6 class="transaction-title">Paypal</h6>
                                <small>Add Money</small>
                            </div>
                        </div>
                        <div class="font-weight-bolder text-success">+ $590</div>
                    </div>
                    <div class="transaction-item">
                        <div class="media">
                            <div class="avatar bg-light-warning rounded">
                                <div class="avatar-content">
                                    <i data-feather="credit-card" class="avatar-icon font-medium-3"></i>
                                </div>
                            </div>
                            <div class="media-body">
                                <h6 class="transaction-title">Mastercard</h6>
                                <small>Ordered Food</small>
                            </div>
                        </div>
                        <div class="font-weight-bolder text-danger">- $23</div>
                    </div>
                    <div class="transaction-item">
                        <div class="media">
                            <div class="avatar bg-light-info rounded">
                                <div class="avatar-content">
                                    <i data-feather="trending-up" class="avatar-icon font-medium-3"></i>
                                </div>
                            </div>
                            <div class="media-body">
                                <h6 class="transaction-title">Transfer</h6>
                                <small>Refund</small>
                            </div>
                        </div>
                        <div class="font-weight-bolder text-success">+ $98</div>
                    </div>
                </div>
            </div>
        </div>
        <!--/ Transaction Card -->

        <!-- earnings Card -->
        <div class="col-lg-4 col-md-6 col-12 mt-5 pt-5">
            <div class="card earnings-card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <h4 class="card-title mb-1">{{trans('main.earnings')}}</h4>
                            <div class="font-small-2">{{trans('main.total_earnings')}}</div>
                            <h5 class="mb-1">ADE {{total_payments()}}</h5>

                        </div>
                        <div class="col-6">
                            <div id="earnings-donut-chart"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--/ earnings Card -->

    </div>


    <!-- Notification Modal -->
    <div class="modal fade text-left" id="notificationModal" tabindex="-1" role="dialog"
         aria-labelledby="notificationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"
                        id="myModalLabel33">{{ trans('notifications.new_notification') }}</h4>
                    <button type="button" class="close" data-dismiss="modal"
                            aria-label="{{ trans('buttons.close') }}">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mb-2">
                        <label for="notification-title"
                               class="form-label">{{ trans('notifications.title') }}</label>
                        <input type="text" id="notification-title" class="form-control"
                               placeholder="{{ trans('notifications.enter_title') }}">
                        <div class="invalid-feedback">
                            {{ trans('notifications.title_required') }}
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="notification-text"
                               class="form-label">{{ trans('notifications.message') }}</label>
                        <textarea id="notification-text" class="form-control" rows="4"
                                  placeholder="{{ trans('notifications.enter_message') }}"></textarea>
                        <div class="invalid-feedback">
                            {{ trans('notifications.message_required') }}
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary mb-1 mb-sm-0 mr-0 mr-sm-1"
                            id="send-notification-btn">
                        <div id="spinner_notification" class="spinner-border spinner-border-sm text-light"
                             role="status" style="display: none;">
                            <span class="sr-only"></span>
                        </div>
                        {{ trans('main.save') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
    @push('js')




            {{--        notify--}}
            <script>
                $('#send-notification-btn').on('click', function () {
                    const title = $('#notification-title').val().trim();
                    const message = $('#notification-text').val().trim();
                    const type = 'general'; // You can change this based on the type of notification

                    if (!title || !message) {
                        toastr.error('Title and Message are required!');
                        return;
                    }

                    $('#spinner_notification').show();
                    $('#send-notification-btn').prop('disabled', true);

                    $.ajax({
                        url: '{{route('users.sendNotificationToUsers')}}',
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        },
                        data: { title, message, type },
                        success: function (response) {
                            toastr.success(response.message);
                            $('#notificationModal').modal('hide');
                            $('#notification-title').val('');
                            $('#notification-text').val('');
                        },
                        error: function (xhr) {
                            toastr.error('Error sending notification!');
                        },
                        complete: function () {
                            $('#spinner_notification').hide();
                            $('#send-notification-btn').prop('disabled', false);
                        },
                    });
                });

            </script>



        <script src="{{url('app-assets/vendors/js/charts/apexcharts.min.js')}}"></script>
        {{--        chatrs--}}
        <script>
            /*=========================================================================================

==========================================================================================*/

            $(window).on('load', function () {
                'use strict';
                var $goalStrokeColor2 = '#615ac7';
                var $strokeColor = '#ebe9f1';
                var $textHeadingColor = '#5e5873';


                var $goalOverviewChart = document.querySelector('#goal-overview-radial-bar-chart');

                var goalOverviewChartOptions;

                var goalOverviewChart;

                var doneOrdersPercentage = {{ getDoneOrdersPercentage() }};


                //------------ Goal Overview Chart ------------
                //---------------------------------------------
                goalOverviewChartOptions = {
                    chart: {
                        height: 245,
                        type: 'radialBar',
                        sparkline: {
                            enabled: true
                        },
                        dropShadow: {
                            enabled: true,
                            blur: 3,
                            left: 1,
                            top: 1,
                            opacity: 0.1
                        }
                    },

                    colors: [$goalStrokeColor2],
                    plotOptions: {
                        radialBar: {
                            offsetY: -10,
                            startAngle: -150,
                            endAngle: 150,
                            hollow: {
                                size: '80%'
                            },
                            track: {
                                background: $strokeColor,
                                strokeWidth: '12%'
                            },
                            dataLabels: {
                                name: {
                                    show: false
                                },
                                value: {
                                    color: $textHeadingColor,
                                    fontSize: '2.86rem',
                                    fontWeight: '600'
                                }
                            }
                        }
                    },

                    fill: {
                        type: 'gradient',
                        gradient: {
                            shade: 'dark',
                            type: 'horizontal',
                            shadeIntensity: 0.5,
                            gradientToColors: [window.colors.solid.success],
                            inverseColors: true,
                            opacityFrom: 1,
                            opacityTo: 1,
                            stops: [0, 100]
                        }
                    },

                    series: [doneOrdersPercentage],
                    stroke: {
                        lineCap: 'round'
                    },

                    grid: {
                        padding: {
                            bottom: 30
                        }
                    }

                };
                goalOverviewChart = new ApexCharts($goalOverviewChart, goalOverviewChartOptions);
                goalOverviewChart.render();


                var $houseKeeper = document.querySelector('#houseKeeper');

                var HouseChartOptions;

                var HouseviewChart;

                var doneHousePercentage = {{ getDoneHouseKeeperOrdersPercentage() }};


                //------------ Goal Overview Chart ------------
                //---------------------------------------------
                HouseChartOptions = {
                    chart: {
                        height: 245,
                        type: 'radialBar',
                        sparkline: {
                            enabled: true
                        },
                        dropShadow: {
                            enabled: true,
                            blur: 3,
                            left: 1,
                            top: 1,
                            opacity: 0.1
                        }
                    },

                    colors: [$goalStrokeColor2],
                    plotOptions: {
                        radialBar: {
                            offsetY: -10,
                            startAngle: -150,
                            endAngle: 150,
                            hollow: {
                                size: '80%'
                            },
                            track: {
                                background: $strokeColor,
                                strokeWidth: '12%'
                            },
                            dataLabels: {
                                name: {
                                    show: false
                                },
                                value: {
                                    color: $textHeadingColor,
                                    fontSize: '2.86rem',
                                    fontWeight: '600'
                                }
                            }
                        }
                    },

                    fill: {
                        type: 'gradient',
                        gradient: {
                            shade: 'dark',
                            type: 'horizontal',
                            shadeIntensity: 0.5,
                            gradientToColors: [window.colors.solid.success],
                            inverseColors: true,
                            opacityFrom: 1,
                            opacityTo: 1,
                            stops: [0, 100]
                        }
                    },

                    series: [doneHousePercentage],
                    stroke: {
                        lineCap: 'round'
                    },

                    grid: {
                        padding: {
                            bottom: 30
                        }
                    }

                };

                HouseviewChart = new ApexCharts($houseKeeper, HouseChartOptions);
                HouseviewChart.render();


                var $violations = document.querySelector('#violations');

                var violationsOptions;

                var violationsChart;

                var DoneviolationsPercentage = {{ getDoneviolationsPercentage() }};


                //------------ Goal Overview Chart ------------
                //---------------------------------------------
                violationsOptions = {
                    chart: {
                        height: 245,
                        type: 'radialBar',
                        sparkline: {
                            enabled: true
                        },
                        dropShadow: {
                            enabled: true,
                            blur: 3,
                            left: 1,
                            top: 1,
                            opacity: 0.1
                        }
                    },

                    colors: [$goalStrokeColor2],
                    plotOptions: {
                        radialBar: {
                            offsetY: -10,
                            startAngle: -150,
                            endAngle: 150,
                            hollow: {
                                size: '80%'
                            },
                            track: {
                                background: $strokeColor,
                                strokeWidth: '12%'
                            },
                            dataLabels: {
                                name: {
                                    show: false
                                },
                                value: {
                                    color: $textHeadingColor,
                                    fontSize: '2.86rem',
                                    fontWeight: '600'
                                }
                            }
                        }
                    },

                    fill: {
                        type: 'gradient',
                        gradient: {
                            shade: 'dark',
                            type: 'horizontal',
                            shadeIntensity: 0.5,
                            gradientToColors: [window.colors.solid.success],
                            inverseColors: true,
                            opacityFrom: 1,
                            opacityTo: 1,
                            stops: [0, 100]
                        }
                    },

                    series: [DoneviolationsPercentage],
                    stroke: {
                        lineCap: 'round'
                    },

                    grid: {
                        padding: {
                            bottom: 30
                        }
                    }

                };

                violationsChart = new ApexCharts($violations, violationsOptions);
                violationsChart.render();


                var $earningsStrokeColor2 = '#28c76f66';
                var $earningsStrokeColor3 = '#28c76f33';
                var earningsChart;
                var earningsChartOptions;

                var $earningsChart = document.querySelector('#earnings-donut-chart');

                // Earnings Chart
                // -----------------------------
                earningsChartOptions = {
                    chart: {
                        type: 'donut',
                        height: 120,
                        toolbar: {
                            show: false
                        }
                    },
                    dataLabels: {
                        enabled: false
                    },
                    series: [{{ payment_assurances() }}, {{ payment_housekeeper() }}, {{ payment_housekeeper_hourly() }}, {{ payment_violations() }}],

                    legend: {show: false},
                    comparedResult: [2, -3, 8],
                    labels: ['{{trans('dashboard_aside.assurances')}}', '{{trans('dashboard_aside.housekeepers')}}', '{{trans('dashboard_aside.housekeepers_hourly')}}', '{{trans('dashboard_aside.violations')}}'],
                    stroke: {width: 0},
                    colors: [$earningsStrokeColor2, $earningsStrokeColor3, window.colors.solid.success, $earningsStrokeColor3],
                    grid: {
                        padding: {
                            right: -20,
                            bottom: -8,
                            left: -20
                        }
                    },
                    plotOptions: {
                        pie: {
                            startAngle: -10,
                            donut: {
                                labels: {
                                    show: true,
                                    name: {
                                        offsetY: 15
                                    },
                                    value: {
                                        offsetY: -15,
                                        formatter: function (val) {
                                            return parseInt(val) + '%';
                                        }
                                    },
                                    total: {
                                        show: true,
                                        offsetY: 15,
                                        label: '{{trans('dashboard_aside.payments')}}',
                                        formatter: function (w) {
                                            return '{{paymentsPercentage()}}%';
                                        }
                                    }
                                }
                            }
                        }
                    },
                    responsive: [
                        {
                            breakpoint: 1325,
                            options: {
                                chart: {
                                    height: 100
                                }
                            }
                        },
                        {
                            breakpoint: 1200,
                            options: {
                                chart: {
                                    height: 120
                                }
                            }
                        },
                        {
                            breakpoint: 1065,
                            options: {
                                chart: {
                                    height: 100
                                }
                            }
                        },
                        {
                            breakpoint: 992,
                            options: {
                                chart: {
                                    height: 120
                                }
                            }
                        }
                    ]
                };
                earningsChart = new ApexCharts($earningsChart, earningsChartOptions);
                earningsChart.render();


                var $houseKeeperHourly = document.querySelector('#houseKeeperHourly');

                var HouseHourlyChartOptions;

                var HouseHourlyviewChart;

                var doneHouseHourlyPercentage = {{ getDoneHouseKeeperHourlyOrdersPercentage() }};

//------------ Goal Overview Chart ------------
//---------------------------------------------
                HouseHourlyChartOptions = {
                    chart: {
                        height: 245,
                        type: 'radialBar',
                        sparkline: {
                            enabled: true
                        },
                        dropShadow: {
                            enabled: true,
                            blur: 3,
                            left: 1,
                            top: 1,
                            opacity: 0.1
                        }
                    },

                    colors: [$goalStrokeColor2],
                    plotOptions: {
                        radialBar: {
                            offsetY: -10,
                            startAngle: -150,
                            endAngle: 150,
                            hollow: {
                                size: '80%'
                            },
                            track: {
                                background: $strokeColor,
                                strokeWidth: '12%'
                            },
                            dataLabels: {
                                name: {
                                    show: false
                                },
                                value: {
                                    color: $textHeadingColor,
                                    fontSize: '2.86rem',
                                    fontWeight: '600'
                                }
                            }
                        }
                    },

                    fill: {
                        type: 'gradient',
                        gradient: {
                            shade: 'dark',
                            type: 'horizontal',
                            shadeIntensity: 0.5,
                            gradientToColors: [window.colors.solid.success],
                            inverseColors: true,
                            opacityFrom: 1,
                            opacityTo: 1,
                            stops: [0, 100]
                        }
                    },

                    series: [doneHouseHourlyPercentage],
                    stroke: {
                        lineCap: 'round'
                    },

                    grid: {
                        padding: {
                            bottom: 30
                        }
                    }
                };

// Use the correct variable (HouseHourlyChartOptions) when creating the chart
                HouseHourlyviewChart = new ApexCharts($houseKeeperHourly, HouseHourlyChartOptions);
                HouseHourlyviewChart.render();

            });


        </script>
    @endpush

@stop
