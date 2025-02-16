@extends('dashboard.layouts.master',['title'=>$user->name])

{{--@section('left')--}}
{{--    <div class="content-header-right text-md-right col-md-3 col-12 d-md-block d-none">--}}
{{--        <div class="form-group breadcrumb-right">--}}
{{--            <button class="btn btn-primary waves-effect waves-float waves-light btn-sm"--}}
{{--                    data-toggle="modal" data-target="#inlineForm"--}}
{{--                    title="{{trans('user.new-user')}}"><i data-feather="plus"></i></button>--}}
{{--        </div>--}}
{{--    </div>--}}

{{--    --}}
{{--@endsection--}}

@section('path')

    <li class="breadcrumb-item active"><a href="{{route('users.index')}}">{{trans('dashboard_aside.users')}} </a></li>

@endsection


@section('content')





    <div class="content-body">
        <!-- app e-commerce details start -->
        <section class="app-ecommerce-details">

            <div class="card">
                <!-- company Details starts -->
                <div class="card-body">
                    <div class="row my-2">
                        <div class="col-12 col-md-5 d-flex align-items-center justify-content-center mb-2 mb-md-0">
                            <div class="d-flex align-items-center justify-content-center">
                                <img style="max-width: 40%" src="{{$user->getAvatar()}}"
                                     class="img-fluid product-img rounded-sm" alt="product image"/>
                            </div>

                        </div>


                        <div class="col-12 col-md-7">

                            <h4>{{$user->name}}</h4>
                            <ul class="product-features list-unstyled d-flex row">

                                <li class="d-inline-block mr-2">
                                    <p><i data-feather="mail" class="font-medium-4"></i>
                                        <span>{{$user->email}}</span>
                                    </p>
                                </li>



                                @if( app()->getLocale() === 'ar')

                                    <li class="d-inline-block mr-2">
                                        <p style="direction: ltr">
                                            <span>{{ formatedPhone($user->phone) }}</span>
                                            <i data-feather="phone" class="font-medium-4"></i>

                                        </p>
                                    </li>
                                @else
                                    <li class="d-inline-block mr-2">
                                        <p style="direction: ltr">

                                            <i data-feather="phone" class="font-medium-4"></i>
                                            <span>{{ formatedPhone($user->phone) }}</span>
                                        </p>
                                    </li>
                                @endif



                                <li class="d-inline-block mr-2">
                                    <p>

                                        <i data-feather="credit-card" class="font-medium-4"></i>
                                        <span>{{$user->number_id}}</span>
                                    </p>
                                </li>

                                @if($user->location)
                                <li class="d-inline-block mr-2 ">
                                    <p>
                                        <i data-feather='map-pin' class="font-medium-4"></i>
                                        {{cities($user->location)}}
                                    </p>
                                </li>
                                @endif
                            </ul>
                            <!-- Item features starts -->
                            <div class="item-features">
                                <div class="row text-center">
                                    <div class="col-12 col-md-3 mb-4 mb-md-0">
                                        <div class="w-75 mx-auto">
                                            <i class="fa  fa-wallet font-medium-5"></i>
                                            <h4 class="mb-1"> {{trans('main.earnings')}}
                                                <br>ADE {{$user->payments->sum('value')}} </h4>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-3 mb-4 mb-md-0">
                                        <div class="w-75 mx-auto">
                                            <i data-feather="alert-circle" class="font-medium-5"></i>
                                            <h4 class="mt-2 mb-1">{{trans('dashboard_aside.violations')}}
                                                <br>{{ $user->violation->count()}}  </h4>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-3 mb-4 mb-md-0">
                                        <div class="w-75 mx-auto">
                                            <i data-feather="hexagon" class="font-medium-5"></i>
                                            <h4 class="mt-2 mb-1">{{trans('dashboard_aside.assurances-orders')}}
                                                <br>{{$user->assuranceOrder->count()}} </h4>

                                        </div>

                                    </div>

                                    <div class="col-12 col-md-3 mb-3 mb-md-0">
                                        <div class="w-75 mx-auto">
                                            <i data-feather="home" class="font-medium-5"></i>

                                            <h4 class="mt-2 mb-1">{{trans('dashboard_aside.housekeepers-orders')}}
                                                <br>{{$user->houseKeeperOrder->count() + $user->houseKeeperHourlyOrder->count() }}
                                            </h4>

                                        </div>
                                    </div>

                                </div>
                            </div>
                            <!-- Item features ends -->


                            {{--                            <p class="text-body">--}}

                            {{--                                {!! $div !!}--}}
                            {{--                            </p>--}}


                        </div>

                    </div>

                </div>


                <div class="row container">


                    <div class="col-12 col-sm-3 col-xl-3">
                        <div class="card">
                            <div class="card-body">
                                <h5>{{trans('dashboard_aside.assurances-orders')}}</h5>
                                @if($user->assuranceOrder->count()>0)

                                    @foreach($user->assuranceOrder->take(5) as $a)
                                        <div class="d-flex justify-content-start align-items-center mt-2">
                                            <div class="avatar mr-75">
                                                <img src="{{$a->assurance->getAvatar()}}" alt="avatar"
                                                     height="40" width="40">
                                            </div>
                                            <div class="profile-user-info">
                                                <h6 class="mb-0">{{$a->assurance_number}}</h6>
                                                <small
                                                    class="text-muted">{{$a->created_at->diffforhumans()}}</small>
                                            </div>
                                            <a href="{{route('assurances.orders.view',$a->id)}}"
                                               class="btn btn-primary btn-icon btn-sm ml-auto waves-effect waves-float waves-light">
                                                <i data-feather="eye"></i>
                                            </a>
                                        </div>
                                    @endforeach

                                @else
                                    <h5 class="text-body pt-2">{{trans('main.no-orders')}}  </h5>
                                @endif

                            </div>
                        </div>
                    </div>



                    <div class="col-12 col-sm-3 col-xl-3">
                        <div class="card">
                            <div class="card-body">
                                <h5>{{trans('dashboard_aside.violations-orders')}}</h5>
                                @if($user->violation->count()>0)

                                    @foreach($user->violation->take(5) as $v)
                                        <div class="d-flex justify-content-start align-items-center mt-2">
                                            <div class="avatar mr-75">
                                                <img src="{{$v->user->getAvatar()}}" alt="avatar"
                                                     height="40" width="40">
                                            </div>
                                            <div class="profile-user-info">
                                                <h6 class="mb-0">{{$v->violation_number}}</h6>
                                                <small
                                                    class="text-muted">{{$v->created_at->diffforhumans()}}</small>
                                            </div>
                                            <a href="{{route('violations.view',$v->id)}}"
                                               class="btn btn-primary btn-icon btn-sm ml-auto waves-effect waves-float waves-light">
                                                <i data-feather="eye"></i>
                                            </a>
                                        </div>
                                    @endforeach

                                @else
                                    <h5 class="text-body pt-2">{{trans('main.no-orders')}}  </h5>
                                @endif

                            </div>
                        </div>
                    </div>


                    <div class="col-12 col-sm-3 col-xl-3">
                        <div class="card">
                            <div class="card-body">
                                <h5>{{trans('dashboard_aside.housekeepers-orders')}}</h5>
                                @if($user->houseKeeperOrder->count()>0)

                                    @foreach($user->houseKeeperOrder->take(5) as $h)
                                        <div class="d-flex justify-content-start align-items-center mt-2">
                                            <div class="avatar mr-75">
                                                <img src="{{$h->housekeeper->getAvatar()}}" alt="avatar"
                                                     height="40" width="40">
                                            </div>
                                            <div class="profile-user-info">
                                                <h6 class="mb-0">{{$h->housekeeper->name}}</h6>
                                                <small
                                                    class="text-muted">{{$h->created_at->diffforhumans()}}</small>
                                            </div>
                                            <a href="{{route('housekeepers.orders.view',$h->id)}}"
                                               class="btn btn-primary btn-icon btn-sm ml-auto waves-effect waves-float waves-light">
                                                <i data-feather="eye"></i>
                                            </a>
                                        </div>
                                    @endforeach

                                @else
                                    <h5 class="text-body pt-2">{{trans('main.no-orders')}}  </h5>
                                @endif

                            </div>
                        </div>
                    </div>


                    <div class="col-12 col-sm-3 col-xl-3">
                        <div class="card">
                            <div class="card-body">
                                <h5>{{trans('dashboard_aside.housekeepers_hourly')}}</h5>
                                @if($user->houseKeeperHourlyOrder->count()>0)

                                    @foreach($user->houseKeeperHourlyOrder->take(5) as $hh)
                                        <div class="d-flex justify-content-start align-items-center mt-2">
                                            <div class="avatar mr-75">
                                                <img src="{{$hh->user->getAvatar()}}" alt="avatar"
                                                     height="40" width="40">
                                            </div>
                                            <div class="profile-user-info">
                                                <h6 class="mb-0">{{$hh->from->format('H:i')}}- {{$hh->to->format('H:i')}} ({{$hh->hours}}) {{trans('main.hours')}}</h6>
                                                <small
                                                    class="text-muted">{{$hh->created_at->diffforhumans()}} - {{trans('main.in')}} {{$hh->date->format('d-M-Y')}} </small>
                                            </div>
                                            <a href="{{route('housekeepers.HourlyOrders.view',$hh->id)}}"
                                               class="btn btn-primary btn-icon btn-sm ml-auto waves-effect waves-float waves-light">
                                                <i data-feather="eye"></i>
                                            </a>
                                        </div>
                                    @endforeach

                                @else
                                    <h5 class="text-body pt-2">{{trans('main.no-orders')}}  </h5>
                                @endif

                            </div>
                        </div>
                    </div>

                </div>


            </div>
        </section>
    </div>












    @push('js')

        <script src="{{asset('app-assets/vendors/js/extensions/swiper.min.js')}}"></script>


        <script>

            var productsSwiper = $('.swiper-responsive-breakpoints');
            if (productsSwiper.length) {
                new Swiper('.swiper-responsive-breakpoints', {
                    slidesPerView: 1,
                    spaceBetween: 55,
                    // init: false,
                    navigation: {
                        nextEl: '.swiper-button-next',
                        prevEl: '.swiper-button-prev'
                    },
                    breakpoints: {
                        1600: {
                            slidesPerView: 1,
                            spaceBetween: 55
                        },
                        1300: {
                            slidesPerView: 1,
                            spaceBetween: 55
                        },
                        768: {
                            slidesPerView: 1,
                            spaceBetween: 55
                        },
                        320: {
                            slidesPerView: 1,
                            spaceBetween: 55
                        }
                    }
                });
            }
        </script>

    @endpush

@stop
