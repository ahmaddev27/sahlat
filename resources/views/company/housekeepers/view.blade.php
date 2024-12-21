@extends('company.layouts.master',['title'=>$housekeeper->name])


@push('css')
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/css/extensions/swiper.min.css')}}">


    <style>
        /* Set the height of the map container */
        #map {
            height: 400px;
            width: 100%;
        }

        .product-features2 {
            display: flex; /* Align items horizontally */
            justify-content: center; /* Center the items */
            align-items: center; /* Center items vertically */
            padding: 15px; /* Add padding around the list */
            margin: 0 auto; /* Center the entire list horizontally */
            list-style: none; /* Remove bullet points */
        }


        .product-features2 li {
            display: inline-block; /* Ensure items are inline */
            margin: 0 0.5px; /* Add spacing between list items */
            text-align: center; /* Center text within each list item */
        }

        .product-features2 p {
            margin: 0; /* Reset default margin for <p> */
            padding: 5px; /* Add padding inside each <p> */
        }

        /* General star styles */

        /* Style for filled stars (golden color or similar) */
        .filled-star {
            color: #ffbc00; /* Gold color */
        }

        /* Style for unfilled stars (gray color or similar) */
        .unfilled-star {
            color: #dcdcdc; /* Light gray color */
            opacity: 0.7; /* Optional: makes the unfilled star slightly transparent */
        }

    </style>
@endpush
@section('left')


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
                                <img style="max-width: 50%" src="{{$housekeeper->getAvatar()}}"
                                     class="img-fluid product-img rounded-sm" alt="product image"/>
                            </div>
                        </div>
                        <div class="col-12 col-md-7">
                            <?php $statusText = HouseKeepersStatus($housekeeper->status);
                            $badgeClass = $housekeeper->status == 1 ? 'badge-primary' : 'badge-success';

                            $div = '<div class="badge badge-glow mt-1 ' . $badgeClass . '">' . $statusText . '</div>';
                            ?>


                            <h4>{{$housekeeper->name}}</h4>
                            {!! $div!!}
                            <div class="ecommerce-details-price d-flex flex-wrap mt-1">
                                <h4 class="item-price mr-1">{{ trans('main.reviews') }}</h4>
                                <ul class="unstyled-list list-inline pl-1 border-left">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <li class="ratings-list-item">
                                            <i class="fa fa-star {{ $i <= round((float) $housekeeper->averageReview()) ? 'filled-star' : 'unfilled-star' }}"></i>
                                        </li>
                                    @endfor
                                    ({{$housekeeper->reviews->count()}})
                                </ul>
                            </div>



                            <ul class="product-features list-unstyled d-flex row">
                                @if( app()->getLocale() === 'ar')

                                    <li class="d-inline-block mr-2">
                                        <p style="direction: ltr">
                                            <span>{{ formatedPhone($housekeeper->phone) }}</span>
                                            <i data-feather="phone" class="font-medium-4"></i>

                                        </p>
                                    </li>
                                @else
                                    <li class="d-inline-block mr-2">
                                        <p style="direction: ltr">

                                            <i data-feather="phone" class="font-medium-4"></i>
                                            <span>{{ formatedPhone($housekeeper->phone) }}</span>
                                        </p>
                                    </li>
                                @endif

                                <li class="d-inline-block mr-2 ">
                                    <p>   {{Nationalities($housekeeper->nationality)}}</p>

                                </li>

                                <li class="d-inline-block mr-2">

                                    <p> {{getAllLangs($housekeeper->language)}}</p>

                                </li>


                                <li class="d-inline-block mr-2 ">
                                    <p>
                                        {{getAllReligions($housekeeper->religion)}}
                                    </p>
                                </li>

                            </ul>


                            <p class="text-body">
                                {{$housekeeper->description}}
                            </p>


                        </div>


                    </div>

                </div>
                <!-- company Details ends -->

                <!-- Item features starts -->
                <div class="item-features">
                    <div class="row text-center">
                        <div class="col-12 col-md-3 mb-4 mb-md-0">
                            <div class="w-75 mx-auto">
                                <i class="fa fa-thin fa-wallet font-medium-5"></i>
                                <h4 class="mt-2 mb-1"> {{trans('housekeeper.salary')}}
                                    ADE {{$housekeeper->salary}} </h4>

                            </div>
                        </div>
                        <div class="col-12 col-md-3 mb-4 mb-md-0">
                            <div class="w-75 mx-auto">
                                <i data-feather="star" class="font-medium-5"></i>
                                <h4 class="mt-2 mb-1">{{trans('housekeeper.reviews')}} {{ (int)$housekeeper->averageReview()}}  </h4>
                            </div>
                        </div>
                        <div class="col-12 col-md-3 mb-4 mb-md-0">
                            <div class="w-75 mx-auto">
                                <i data-feather="hexagon" class="font-medium-5"></i>
                                <h4 class="mt-2 mb-1">{{trans('housekeeper.orders_count')}} {{$housekeeper->Hourlyorderd->count()+$housekeeper->orderd->count()}} </h4>

                            </div>

                        </div>

                        <div class="col-12 col-md-3 mb-3 mb-md-0">
                            <div class="w-75 mx-auto">
                                <i data-feather="target" class="font-medium-5"></i>
                                <h4 class="mt-2 mb-1">{{trans('housekeeper.experiences')}} {{$housekeeper->experience}} </h4>

                            </div>
                        </div>

                    </div>
                </div>
                <!-- Item features ends -->

                <div class="row container">

                    <div class="col-12 col-sm-3">
                        <div class="card">
                            <div class="card-body">
                                <h5>{{trans('main.orders')}}</h5>

                                @if($housekeeper->orderd->count()>0)
                                    @foreach($housekeeper->orderd->take(5) as $order)

                                        <div class="d-flex justify-content-start align-items-center mt-2">
                                            <div class="avatar mr-75">
                                                <img src="{{$order->user->getAvatar()}}" alt="avatar"
                                                     height="40" width="40">
                                            </div>
                                            <div class="profile-user-info">
                                                <h6 class="mb-0">{{$order->user->name}}</h6>
                                                <small class="text-muted">{{$order->created_at->diffforhumans()}}</small>
                                            </div>
                                            <a href="{{route('company.housekeepers.orders.view',$order->id)}}"
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

                    <div class="col-12 col-sm-3">
                        <div class="card">
                            <div class="card-body">
                                <h5>{{trans('main.orders-hourly')}}</h5>
                                @if($housekeeper->Hourlyorderd->count()>0)

                                    @foreach($housekeeper->Hourlyorderd->take(5) as $horder)
                                        <div class="d-flex justify-content-start align-items-center mt-2">
                                            <div class="avatar mr-75">
                                                <img src="{{$horder->user->getAvatar()}}" alt="avatar"
                                                     height="40" width="40">
                                            </div>
                                            <div class="profile-user-info">
                                                <h6 class="mb-0">{{$horder->user->name}}</h6>
                                                <small class="text-muted">{{$horder->created_at->diffforhumans()}}</small>
                                            </div>
                                            <a href="{{route('company.housekeepers.HourlyOrders.view',$horder->id)}}"
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

                    <div class="col-12 col-sm-6">

                        <div class="card-body">
                            <div class="swiper-responsive-breakpoints swiper-container px-4 py-2">
                                <div class="swiper-wrapper">

                                    <div class="swiper-slide">
                                        <a href="javascript:void(0)"></a>
                                            <div class="item-heading">
                                              <h5
                                                        class="text-truncate mb-0">{{$housekeeper->company->name}}</h5>

                                            </div>
                                            <div class="img-container  mx-auto py-75" style="width: 40%">
                                                <img src="{{$housekeeper->company->getAvatar()}}"
                                                     class="img-fluid rounded-sm"
                                                     alt="image"/>
                                            </div>
                                            <div class="item-meta">
                                                <ul class="unstyled-list list-inline pl-1">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        <li class="ratings-list-item">
                                                            <i class="fa fa-star {{ $i <= round((float) $housekeeper->company->averageHousekeeperReview()) ? 'filled-star' : 'unfilled-star' }}"></i>
                                                        </li>
                                                    @endfor
                                                    {{--                                                    ({{$housekeeper->company->reviews()}})--}}
                                                </ul>



                                                <ul class="product-features2 list-unstyled d-flex text-center row">
                                                    <li class="d-inline-block">
                                                        <i data-feather="mail" class="font-medium-3"></i>
                                                        <span>{{$housekeeper->company->email}}</span>

                                                    </li>

                                                    @if( app()->getLocale() === 'ar')

                                                        <li class="d-inline-block">
                                                            <p style="direction: ltr">
                                                                <span>{{ formatedPhone($housekeeper->company->phone) }}</span>
                                                                <i data-feather="phone" class="font-medium-4"></i>

                                                            </p>
                                                        </li>
                                                    @else
                                                        <li class="d-inline-block ">
                                                            <p style="direction: ltr">

                                                                <i data-feather="phone" class="font-medium-4"></i>
                                                                <span>{{ formatedPhone($housekeeper->company->phone) }}</span>
                                                            </p>
                                                        </li>
                                                    @endif




                                                    <li class="d-inline-block ">

                                                        <i data-feather='map-pin' class="font-medium-3"></i>

                                                        <span>{{cities($housekeeper->company->address)}}</span>

                                                    </li>
                                                </ul>

                                            </div>

                                    </div>


                                </div>

                            </div>
                        </div>

                    </div>

                </div>

            </div>


        </section>
        <!-- Related Products ends -->
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
