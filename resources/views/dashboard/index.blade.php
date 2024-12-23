@extends('dashboard.layouts.master',['title'=>trans('dashboard_aside.dashboard')])

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


    <div class="row  ">

        <!-- orders Services Card -->
        <div class="col-lg-3 col-md-6 col-12">

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">{{trans('main.AssuranceOrders')}}</h4>
                    <a href="{{route('assurances.orders.index')}}"> <i data-feather="eye"
                                                                       class="font-medium-3 text-muted cursor-pointer"></i></a>
                </div>

                <div class="card-body p-0">
                    <div id="goal-overview-radial-bar-chart" class="my-2"></div>
                    <div class="row border-top text-center mx-0">

                        <div class="col-4 border-right py-1">
                            <p class="card-text text-muted mb-0">{{StatusesAssurance(0)}}</p>
                            <h3 class="font-weight-bolder mb-0">{{AssuranceOrdersNew()}}</h3>
                        </div>

                        <div class="col-4 border-right py-1">
                            <p class="card-text text-muted mb-0">{{StatusesAssurance(1)}}</p>
                            <h3 class="font-weight-bolder mb-0">{{AssuranceOrdersByStatus(1)}}</h3>
                        </div>

                        <div class="col-4 border-right py-1">
                            <p class="card-text text-muted mb-0">{{StatusesAssurance(2)}}</p>
                            <h3 class="font-weight-bolder mb-0">{{AssuranceOrdersByStatus(2)}}</h3>
                        </div>


                        <div class="col-6 border-right py-1">
                            <p class="card-text text-muted mb-0">{{StatusesAssurance(3)}}</p>
                            <h3 class="font-weight-bolder mb-0">{{AssuranceOrdersByStatus(3)}}</h3>
                        </div>
                        <div class="col-6 py-1">
                            <p class="card-text text-muted mb-0">{{StatusesAssurance(4)}}</p>
                            <h3 class="font-weight-bolder mb-0">{{AssuranceOrdersDone()}}</h3>
                        </div>
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
                            <div class="col-4 border-right py-1">
                                <p class="card-text text-muted mb-0">{{StatusesViolations(0)}}</p>
                                <h3 class="font-weight-bolder mb-0">{{violationsPendding()}}</h3>
                            </div>

                            <div class="col-4 border-right py-1">
                                <p class="card-text text-muted mb-0">{{StatusesViolations(1)}}</p>
                                <h3 class="font-weight-bolder mb-0">{{violationsByStatus(1)}}</h3>
                            </div>
                            <div class="col-4 border-right py-1">
                                <p class="card-text text-muted mb-0">{{StatusesViolations(2)}}</p>
                                <h3 class="font-weight-bolder mb-0">{{violationsByStatus(2)}}</h3>
                            </div>
                            <div class="col-6 border-right py-1">
                                <p class="card-text text-muted mb-0">{{StatusesViolations(3)}}</p>
                                <h3 class="font-weight-bolder mb-0">{{violationsByStatus(3)}}</h3>
                            </div>
                            <div class="col-6 border-right py-1">
                                <p class="card-text text-muted mb-0">{{StatusesViolations(4)}}</p>
                                <h3 class="font-weight-bolder mb-0">{{violationsByStatus(4)}}</h3>
                            </div>



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
                    <a href="{{route('housekeepers.orders.index')}}"> <i data-feather="eye"
                                                                         class="font-medium-3 text-muted cursor-pointer"></i></a>
                </div>

                <div class="card-body p-0">
                    <div id="houseKeeper" class="my-2"></div>
                    <div class="row border-top text-center mx-0">
                        <div class="col-4 border-right py-1">
                            <p class="card-text text-muted mb-0">{{HouseKeeperStatuses(0)}}</p>
                            <h3 class="font-weight-bolder mb-0">{{HouseKeeperOrdersByStatus(0)}}</h3>
                        </div>
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

                        <div class="col-4 py-1">
                            <p class="card-text text-muted mb-0">{{HouseKeeperStatuses(4)}}</p>
                            <h3 class="font-weight-bolder mb-0">{{HouseKeeperOrdersByStatus(4)}}</h3>
                        </div>

                        <div class="col-4 py-1">
                            <p class="card-text text-muted mb-0">{{HouseKeeperStatuses(5)}}</p>
                            <h3 class="font-weight-bolder mb-0">{{HouseKeeperOrdersByStatus(5)}}</h3>
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
                    <a href="{{route('housekeepers.HourlyOrders.index')}}"> <i data-feather="eye"
                                                                               class="font-medium-3 text-muted cursor-pointer"></i></a>
                </div>

                <div class="card-body p-0">
                    <div id="houseKeeperHourly" class="my-2"></div>
                    <div class="row border-top text-center mx-0">
                        <div class="col-6 border-right py-1">
                            <p class="card-text text-muted mb-0">{{HouseKeeperHourlyStatuses(0)}}</p>
                            <h3 class="font-weight-bolder mb-0">{{HouseKeeperHourlyOrdersPendding()}}</h3>
                        </div>
                        <div class="col-6 py-1">
                            <p class="card-text text-muted mb-0">{{HouseKeeperHourlyStatuses(1)}}</p>
                            <h3 class="font-weight-bolder mb-0">{{HouseKeeperHourlyOrdersDone()}}</h3>
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
                            <div class="font-weight-bolder text-success">{{ \App\Models\AppUser::where('location', $id)->count() }}</div>
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


    @push('js')

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
                    series: [{{payment_assurances()}}, {{payment_housekeeper()}}, {{payment_housekeeper_hourly()}}, {{payment_violations()}}],
                    legend: {show: false},
                    comparedResult: [2, -3, 8],
                    labels: ['{{trans('dashboard_aside.assurances')}}', '{{trans('dashboard_aside.housekeepers')}}','{{trans('dashboard_aside.housekeepers_hourly')}}' ,'{{trans('dashboard_aside.violations')}}'],
                    stroke: {width: 0},
                    colors: [$earningsStrokeColor2, $earningsStrokeColor3, window.colors.solid.success,$earningsStrokeColor3],
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
