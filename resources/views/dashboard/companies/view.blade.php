@extends('dashboard.layouts.master',['title'=>$company->name])


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

@section('path')

    <li class="breadcrumb-item active"><a href="{{route('companies.index')}}">{{trans('dashboard_aside.companies')}} </a></li>

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
                                <img style="max-width: 50%" src="{{$company->getAvatar()}}"
                                     class="img-fluid product-img rounded-sm" alt="product image"/>
                            </div>
                        </div>
                        <div class="col-12 col-md-7">
                            <h4>{{$company->name}}</h4>
                            <div class="ecommerce-details-price d-flex flex-wrap mt-1">
                                <h4 class="item-price mr-1">{{ trans('main.reviews') }}</h4>
                                <ul class="unstyled-list list-inline pl-1 border-left">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <li class="ratings-list-item">
                                            <i class="fa fa-star {{ $i <= round((float) $company->averageHousekeeperReview()) ? 'filled-star' : 'unfilled-star' }}"></i>
                                        </li>
                                    @endfor
                                    ({{$company->reviews()}})
                                </ul>
                            </div>



                            <ul class="product-features list-unstyled d-flex row">
                                <li class="d-inline-block ">
                                    <p><i data-feather="mail" class="font-medium-4"></i>
                                        <span>{{$company->email}}</span>
                                    </p>
                                </li>
                                @if( app()->getLocale() === 'ar')

                                    <li class="d-inline-block mr-2">
                                        <p style="direction: ltr">
                                            <span>{{ formatedPhone($company->phone) }}</span>
                                            <i data-feather="phone" class="font-medium-4"></i>

                                        </p>
                                    </li>
                                @else
                                    <li class="d-inline-block mr-2">
                                        <p style="direction: ltr">

                                            <i data-feather="phone" class="font-medium-4"></i>
                                            <span>{{ formatedPhone($company->phone) }}</span>
                                        </p>
                                    </li>
                                @endif


                                <li class="d-inline-block ">
                                    <p>
                                        <i data-feather='map-pin' class="font-medium-4"></i>

                                        <span>{{cities($company->address)}}</span>
                                    </p>
                                </li>
                                @if($company->hourly_price)

                                    <li class="d-inline-block mx-1">

                                        <p>

                                            {{trans('company.hourly_price')}}

                                            <span> ADE {{$company->hourly_price}} </span>
                                        </p>


                                    </li>
                                @endif

                            </ul>


                            <p class="text-body">
                                {{$company->bio}}
                            </p>


                        </div>
                    </div>

                </div>
                <!-- company Details ends -->

                <!-- Item features starts -->
                <div class="item-features">
                    <div class="row text-center">
                        <div class="col-12 col-md-3 mb-3 mb-md-0">
                            <div class="w-75 mx-auto">
                                <i data-feather="home" class="font-medium-5"></i>
                                <h4 class="mt-2 mb-1">{{trans('housekeeper.Housekeepers')}} {{$company->housekeepers->count()}} </h4>

                            </div>
                        </div>
                        <div class="col-12 col-md-3 mb-3 mb-md-0">
                            <div class="w-75 mx-auto">
                                <i data-feather="star" class="font-medium-5"></i>
                                <h4 class="mt-2 mb-1">{{trans('housekeeper.reviews')}} {{ (int)$company->averageHousekeeperReview()}}  </h4>
                            </div>
                        </div>
                        <div class="col-12 col-md-3 mb-3 mb-md-0">
                            <div class="w-75 mx-auto">
                                <i data-feather="hexagon" class="font-medium-5"></i>
                                <h4 class="mt-2 mb-1">{{trans('housekeeper.orders_count')}} {{$company->HouseKeeperOrders->count()+$company->HouseKeeperHourlyOrders->count()}} </h4>

                            </div>
                        </div>

                        <div class="col-12 col-md-3 mb-3 mb-md-0">
                            <div class="w-75 mx-auto">
                                <i data-feather="target" class="font-medium-5"></i>
                                <h4 class="mt-2 mb-1">{{trans('housekeeper.experiences')}} {{$company->experience}} </h4>

                            </div>
                        </div>
                    </div>
                </div>
                <!-- Item features ends -->

                <!-- Map container starts -->
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div id="map"></div>
                        </div>
                    </div>
                </div>
                <!-- Map container ends -->

                <!-- Related housekeepers starts -->
                <div class="card-body col-12">
                    <div class="mt-4 mb-2 text-center">
                        <h4>{{trans('dashboard_aside.housekeepers')}}</h4>

                    </div>

                    <div class="swiper-responsive-breakpoints swiper-container px-4 py-2">
                        <div class="swiper-wrapper justify-content-center">
                            @foreach($company->housekeepers as $h)
                                <div class="swiper-slide">
                                    <a href="javascript:void(0)">
                                        <div class="item-heading">
                                            <a href="{{route('housekeepers.view',$h->id)}}"><h5
                                                    class="text-truncate mb-0">{{$h->name}}</h5></a>
                                        </div>
                                        <div class="img-container w-75 mx-auto py-75">
                                            <img src="{{$h->getAvatar()}}" class="img-fluid rounded-sm"
                                                 alt="image"/>
                                        </div>
                                        <div class="item-meta">
                                            <ul class="unstyled-list list-inline pl-1  ">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <li class="ratings-list-item">
                                                        <i class="fa fa-star {{ $i <= round((float) $h->averageReview()) ? 'filled-star' : 'unfilled-star' }}"></i>
                                                    </li>
                                                @endfor
                                                ({{$h->reviews()->count()}})


                                                <ul class="unstyled-list list-inline my-1 ">
                                                    {{trans('main.orders_count')}}
                                                    ({{$h->orderd()->count() + $h->Hourlyorderd()->count()}})
                                                </ul>


                                            </ul>

                                            <p class="card-text text-primary mb-0"> ADE {{$h->salary}}</p>

                                            <ul class="product-features2 list-unstyled d-flex text-center row">
                                                <li class="d-inline-block  ">
                                                    <p>    {{Nationalities($h->nationality)}}</p>

                                                </li>

                                                <li class="d-inline-block">

                                                    <p> {{getAllLangs($h->language)}}</p>

                                                </li>


                                                <li class="d-inline-block ">
                                                    <p>
                                                        {{getAllReligions($h->religion)}}
                                                    </p>
                                                </li>
                                            </ul>
                                        </div>
                                    </a>
                                </div>
                            @endforeach


                        </div>

                    </div>

                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                </div>


                <!-- Related housekeepers ends -->

            </div>


        </section>
        <!-- Related Products ends -->
    </div>









    @push('js')

        <script src="{{asset('app-assets/vendors/js/extensions/swiper.min.js')}}"></script>

        {{--       map--}}
        <script>
            // Coordinates for the location (replace with dynamic lat/lon values)
            var lat = '{{$company->lat}}';   // Example Latitude
            var lon = '{{$company->long}}';   // Example Longitude

            // Initialize the map centered on the coordinates (with zoom level 13)
            var map = L.map('map').setView([lat, lon], 15);

            // Add OpenStreetMap tile layer to the map
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            // Add a marker at the given latitude and longitude
            var marker = L.marker([lat, lon]).addTo(map);

            // Optionally, you can add a popup to the marker
            marker.bindPopup("<b>{{trans('company.address')}}</b><br>Lat: " + lat + "<br>Lon: " + lon).openPopup();
        </script>

        <script>

            var productsSwiper = $('.swiper-responsive-breakpoints');
            if (productsSwiper.length) {
                new Swiper('.swiper-responsive-breakpoints', {
                    slidesPerView: 5,
                    spaceBetween: 55,
                    // init: false,
                    navigation: {
                        nextEl: '.swiper-button-next',
                        prevEl: '.swiper-button-prev'
                    },
                    breakpoints: {
                        1600: {
                            slidesPerView: 4,
                            spaceBetween: 55
                        },
                        1300: {
                            slidesPerView: 3,
                            spaceBetween: 55
                        },
                        768: {
                            slidesPerView: 2,
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
