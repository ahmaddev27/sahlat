@extends('dashboard.layouts.master',['title'=>trans('dashboard_aside.settings')])

@push('css')
    <link rel="stylesheet" type="text/css" href="{{url('app-assets/css-rtl/plugins/forms/form-validation.css')}}">
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet"/>


    <style>


        #about, #conditions, #policy {
            height: 300px; /* Example height */
            overflow-y: auto;
        }

    </style>

@endpush



@section('content')

    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="content-body">
                    <!-- settings edit start -->
                    <section class="app-user-edit">
                        <div class="card">
                            <div class="card-body">
                                <ul class="nav nav-pills" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link d-flex align-items-center active" id="account-tab"
                                           data-toggle="tab" href="#account" aria-controls="account" role="tab"
                                           aria-selected="true">
                                            <i data-feather="info"></i><span
                                                class="d-none d-sm-block">{{trans('settings.info')}}</span>
                                        </a>
                                    </li>

                                    <li class="nav-item">
                                        <a class="nav-link d-flex align-items-center" id="social-tab" data-toggle="tab"
                                           href="#social" aria-controls="social" role="tab" aria-selected="false">
                                            <i data-feather="share-2"></i><span
                                                class="d-none d-sm-block">{{trans('settings.social')}}</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link d-flex align-items-center" id="social-tab" data-toggle="tab"
                                           href="#app" aria-controls="app" role="tab" aria-selected="false">
                                            <i data-feather="smartphone"></i><span
                                                class="d-none d-sm-block">{{trans('settings.app')}}</span>
                                        </a>
                                    </li>

                                    <li class="nav-item">
                                        <a class="nav-link d-flex align-items-center" id="credentials-tab"
                                           data-toggle="tab"
                                           href="#credentials" aria-controls="credentials" role="tab"
                                           aria-selected="false">
                                            <i data-feather="key"></i><span
                                                class="d-none d-sm-block">{{trans('settings.credentials')}}</span>
                                        </a>
                                    </li>

                                </ul>

                                <form id="settingsForm" class="form-validate" enctype="multipart/form-data">
                                    @csrf
                                    <div class="tab-content">

                                        <!-- Account Tab starts -->
                                        <div class="tab-pane active" id="account" aria-labelledby="account-tab"
                                             role="tabpanel">
                                            <!-- users edit media object start -->
                                            <div class="row m-3  text-center">
                                                <!-- Icon Section -->
                                                <div class="col-12 col-md-6 justify-content-center  ">

                                                    <h4>{{trans('settings.icon')}}</h4>
                                                    <div
                                                        class="col-12 d-flex mt-1 px-0 text-center justify-content-center ">
                                                        <label class="btn btn-primary mr-75 mb-0"
                                                               for="change-icon">
                                                                    <span
                                                                        class="d-none d-sm-block">{{trans('settings.change')}}</span>
                                                            <input class="form-control" type="file" name="icon"
                                                                   id="change-icon" hidden
                                                                   accept="image/png, image/jpeg, image/jpg"/>
                                                            <span class="d-block d-sm-none">
                        <i class="mr-0" data-feather="edit"></i>
                    </span>
                                                        </label>
                                                    </div>

                                                    <img
                                                        src="{{ setting('icon') !='' ? url('storage').'/'.setting('icon') :  url('blank.png') }}"
                                                        alt="icon avatar"
                                                        class="img-fluid user-avatar icon users-avatar-shadow rounded mr-2 mt-2 cursor-pointer"
                                                        style="max-width: 90%; height: 120px"/>
                                                </div>


                                                <!-- Logo Section -->
                                                <div class="col-12 col-md-6 justify-content-center">

                                                    <h4>{{trans('settings.logo')}}</h4>
                                                    <div class="col-12 d-flex mt-1 px-0 justify-content-center">
                                                        <label class="btn btn-primary mr-75 mb-0"
                                                               for="change-logo">
                                                                    <span
                                                                        class="d-none d-sm-block">{{trans('settings.change')}}</span>
                                                            <input class="form-control" type="file" name="logo"
                                                                   id="change-logo" hidden
                                                                   accept="image/png, image/jpeg, image/jpg"/>
                                                            <span class="d-block d-sm-none">
                                                                        <i class="mr-0" data-feather="edit"></i>
                                                                    </span>


                                                        </label>


                                                    </div>
                                                    <img
                                                        src="{{ setting('logo') !='' ?  url('storage').'/'.setting('logo') :  url('blank.png') }}"
                                                        alt="logo avatar"
                                                        class="img-fluid rounded logo mt-1"
                                                        style="max-width: 90%; height: 120px;"/>
                                                </div>
                                            </div>


                                            <!-- users edit account form start -->
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="name">{{trans('settings.name')}}</label>
                                                        <input type="text" class="form-control"
                                                               placeholder="{{trans('settings.name')}}"
                                                               value="{{ old('name', setting('name')) }}" name="name"
                                                               id="name"/>
                                                        @error('name')
                                                        <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="email">{{trans('settings.email')}}</label>
                                                        <input type="text" class="form-control"
                                                               placeholder="{{trans('settings.email')}}"
                                                               value="{{ old('email', setting('email')) }}" name="email"
                                                               id="email"/>
                                                        @error('email')
                                                        <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="whatsapp">{{trans('settings.whatsapp')}}</label>
                                                        <input type="number" class="form-control"
                                                               placeholder="{{trans('settings.whatsapp')}}"
                                                               value="{{ old('whatsapp', setting('whatsapp')) }}"
                                                               name="whatsapp" id="whatsapp"/>
                                                        @error('whatsapp')
                                                        <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

{{--                                                <div class="col-md-3">--}}
{{--                                                    <div class="form-group">--}}
{{--                                                        <label for="whatsapp">{{trans('settings.commission')}}</label>--}}
{{--                                                        <input type="number" class="form-control"--}}
{{--                                                               placeholder="{{trans('settings.commission')}}"--}}
{{--                                                               value="{{ old('commission', setting('commission')) }}"--}}
{{--                                                               name="commission" id="commission"/>--}}
{{--                                                        @error('commission')--}}
{{--                                                        <div class="text-danger">{{ $message }}</div>--}}
{{--                                                        @enderror--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}


                                                <div class="col-12 mt-3">
                                                    <div class="form-group">
                                                        <label
                                                            for="textarea-counter">{{trans('settings.about')}}</label>

                                                        <div id="about"></div>

                                                    </div>
                                                </div>


                                                <div class="col-12 mt-3">
                                                    <div class="form-group">
                                                        <label
                                                            for="textarea-counter">{{trans('settings.conditions')}}</label>

                                                        <div id="conditions"></div>

                                                    </div>
                                                    <!-- users edit account form ends -->
                                                </div>

                                                <div class="col-12 mt-3">
                                                    <div class="form-group">
                                                        <label
                                                            for="textarea-counter">{{trans('settings.policy')}}</label>

                                                        <div id="policy"></div>

                                                    </div>
                                                    <!-- users edit account form ends -->
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Account Tab ends -->

                                        <!-- Social Tab starts -->
                                        <div class="tab-pane" id="social" aria-labelledby="social-tab"
                                             role="tabpanel">
                                            <!-- users edit social form start -->
                                            <div class="row">
                                                <div class="col-lg-4 col-md-6 form-group">
                                                    <label for="twitter-input">{{trans('settings.twitter')}}
                                                        (X)</label>
                                                    <div class="input-group input-group-merge">
                                                        <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon3">
                                            <i data-feather="twitter" class="font-medium-2"></i>
                                        </span>
                                                        </div>
                                                        <input id="twitter-input" type="text" name="twitter"
                                                               class="form-control"
                                                               value="{{ old('twitter', setting('x')) }}"
                                                               placeholder="https://www.twitter.com/"
                                                               aria-describedby="basic-addon3"/>
                                                        @error('twitter')
                                                        <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-6 form-group">
                                                    <label
                                                        for="facebook-input">{{trans('settings.facebook')}}</label>
                                                    <div class="input-group input-group-merge">
                                                        <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon4">
                                            <i data-feather="facebook" class="font-medium-2"></i>
                                        </span>
                                                        </div>
                                                        <input name="facebook" id="facebook-input" type="text"
                                                               class="form-control"
                                                               value="{{ old('facebook', setting('facebook')) }}"
                                                               placeholder="https://www.facebook.com/"
                                                               aria-describedby="basic-addon4"/>
                                                        @error('facebook')
                                                        <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-6 form-group">
                                                    <label
                                                        for="instagram-input">{{trans('settings.instagram')}}</label>
                                                    <div class="input-group input-group-merge">
                                                        <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon5">
                                            <i data-feather="instagram" class="font-medium-2"></i>
                                        </span>
                                                        </div>
                                                        <input name="instagram" id="instagram-input" type="text"
                                                               class="form-control"
                                                               value="{{ old('instagram', setting('instagram')) }}"
                                                               placeholder="https://www.instagram.com/"
                                                               aria-describedby="basic-addon5"/>
                                                        @error('instagram')
                                                        <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- users edit social form ends -->
                                        </div>
                                        <!-- Social Tab ends -->

                                        <!-- app Tab starts -->
                                        <div class="tab-pane" id="app" aria-labelledby="app-tab"
                                             role="tabpanel">
                                            <!-- users edit social form start -->
                                            <div class="row">

                                                <div class="col-lg-4 col-md-6 form-group mb-2">
                                                    <label for="twitter-input">{{trans('settings.Android Url')}}</label>
                                                    <div class="input-group input-group-merge">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="basic-addon3">
                                                                <i class="font-medium-2 fa fa-android"></i>
                                                            </span>
                                                        </div>

                                                        <input id="android_version-input" type="text" name="android_url"
                                                               class="form-control"
                                                               value="{{ old('android_url', setting('android_url')) }}"
                                                               placeholder="https://play.google.com/store/apps/details?id=com.example.app"
                                                               aria-describedby="basic-addon3"/>
                                                        @error('android_url')
                                                        <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-lg-4 col-md-6 form-group mb-2">
                                                    <label
                                                        for="twitter-input">{{trans('settings.Android Version')}}</label>
                                                    <div class="input-group input-group-merge">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="basic-addon3">
                                                                <i class="font-medium-2 fa fa-android"></i>
                                                            </span>
                                                        </div>

                                                        <input id="android_version-input" type="text"
                                                               name="android_version"
                                                               class="form-control"
                                                               value="{{ old('android_version', setting('android_version')) }}"
                                                               placeholder="1.0.0"
                                                               aria-describedby="basic-addon3"/>
                                                        @error('android_version')
                                                        <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-6 form-group mb-2">
                                                    <label
                                                        for="android_force_update">{{trans('settings.Ios Force Update')}}</label>

                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input"
                                                               id="android_force_update" name="android_force_update"
                                                               @if(setting('android_force_update')) checked @endif>
                                                        <label class="custom-control-label"
                                                               for="android_force_update">{{trans('settings.checked')}}</label>
                                                    </div>

                                                </div>


                                                <div class="col-lg-4 col-md-6 form-group mb-2">
                                                    <label
                                                        for="instagram-input">{{trans('settings.Ios Url')}}</label>
                                                    <div class="input-group input-group-merge">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="basic-addon5">
                                                                <i class="font-medium-2 fa fa-apple"></i>
                                                            </span>
                                                        </div>
                                                        <input name="Ios Url" id="Ios Url-input" type="text"
                                                               class="form-control"
                                                               value="{{ old('Ios Url', setting('ios_url')) }}"
                                                               placeholder="https://apps.apple.com/us/app/example/id4525252"
                                                               aria-describedby="basic-addon5"/>
                                                        @error('Ios Url')
                                                        <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-6 form-group mb-2">
                                                    <label
                                                        for="twitter-input">{{trans('settings.ios_version')}}</label>
                                                    <div class="input-group input-group-merge">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="basic-addon3">
                                                                <i class="font-medium-2 fa fa-apple"></i>
                                                            </span>
                                                        </div>

                                                        <input id="ios_version-input" type="text"
                                                               name="ios_version"
                                                               class="form-control"
                                                               value="{{ old('android_version', setting('ios_version')) }}"
                                                               placeholder="1.0.0"
                                                               aria-describedby="basic-addon3"/>
                                                        @error('ios_version')
                                                        <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-6 form-group mb-2">
                                                    <label
                                                        for="ios_force_update">{{trans('settings.Ios Force Update')}}</label>

                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input"
                                                               id="ios_force_update" name="ios_force_update"
                                                               @if(setting('ios_force_update')) checked @endif>
                                                        <label class="custom-control-label"
                                                               for="ios_force_update">{{trans('settings.checked')}}</label>
                                                    </div>

                                                </div>

                                                <div class="col-lg-6 col-md-6 form-group mb-2">
                                                    <label
                                                        for="update_title_en">{{trans('settings.update_title_en')}}</label>
                                                    <div class="input-group input-group-merge">

                                                        <input id="update_title_en-input" type="text"
                                                               name="update_title_en"
                                                               class="form-control"
                                                               value="{{ old('update_title_en', setting('update_title_en')) }}"
                                                               placeholder="{{trans('settings.update_title_en')}}"
                                                               aria-describedby="basic-addon3"/>
                                                        @error('update_title_en')
                                                        <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 form-group mb-2">
                                                    <label
                                                        for="twitter-update_body_en">{{trans('settings.update_body_en')}}</label>
                                                    <div class="input-group input-group-merge">

                                                        <textarea rows="3" id="update_body-input" name="update_body"
                                                                  class="form-control"
                                                                  placeholder="{{trans('settings.update_body_en')}}"
                                                                  aria-describedby="basic-addon3">{{ old('update_body_en', setting('update_body_en')) }}</textarea>
                                                        @error('update_body_en')
                                                        <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-lg-6 col-md-6 form-group mb-2">
                                                    <label
                                                        for="update_title_en">{{trans('settings.update_title_ar')}}</label>
                                                    <div class="input-group input-group-merge">

                                                        <input id="update_title_en-input" type="text"
                                                               name="update_title_ar"
                                                               class="form-control"
                                                               value="{{ old('update_title_ar', setting('update_title_ar')) }}"
                                                               placeholder="{{trans('settings.update_title_ar')}}"
                                                               aria-describedby="basic-addon3"/>
                                                        @error('update_title_ar')
                                                        <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 form-group mb-2">
                                                    <label
                                                        for="twitter-update_body_en">{{trans('settings.update_body_ar')}}</label>
                                                    <div class="input-group input-group-merge">

                                                        <textarea rows="3" id="update_body-input" name="update_body"
                                                                  class="form-control"
                                                                  placeholder="{{trans('settings.update_body_ar')}}"
                                                                  aria-describedby="basic-addon3">{{ old('update_body_ar', setting('update_body_ar')) }}</textarea>
                                                        @error('update_body_ar')
                                                        <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- users edit social form ends -->
                                        </div>
                                        <!-- app Tab ends -->

                                        <!-- credentials Tab starts -->
                                        <div class="tab-pane" id="credentials" aria-labelledby="credentials-tab"
                                             role="tabpanel">
                                            <!-- users edit social form start -->
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 form-group mb-2">
                                                    <label
                                                        for="twitter-input">{{trans('settings.Stripe Public test')}}</label>
                                                    <div class="input-group input-group-merge">

                                                        <input id="stripe_public_key_test-input" type="text"
                                                               name="stripe_public_key_test"
                                                               class="form-control"
                                                               value="{{ old('stripe_public_key_test', setting('stripe_public_key_test')) }}"
                                                               placeholder="stripe_public_key_test"
                                                               aria-describedby="basic-addon3"/>
                                                        @error('stripe_public_key_test')
                                                        <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-lg-12 col-md-12 form-group mb-2">
                                                    <label
                                                        for="twitter-input">{{trans('settings.Stripe Secret test')}}</label>
                                                    <div class="input-group input-group-merge">

                                                        <input id="stripe_secret_key_test-input" type="text"
                                                               name="stripe_secret_key_test"
                                                               class="form-control"
                                                               value="{{ old('stripe_secret_key_test', setting('stripe_secret_key_test')) }}"
                                                               placeholder="stripe_secret_key_test"
                                                               aria-describedby="basic-addon3"/>
                                                        @error('stripe_secret_key_test')
                                                        <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-lg-12 col-md-12 form-group mb-2">
                                                    <label
                                                        for="twitter-input">{{trans('settings.Stripe Public live')}}</label>
                                                    <div class="input-group input-group-merge">

                                                        <input id="stripe_public_key_live-input" type="text"
                                                               name="stripe_public_key_live"
                                                               class="form-control"
                                                               value="{{ old('stripe_public_key_live', setting('stripe_public_key_live')) }}"
                                                               placeholder="stripe_public_key_live"
                                                               aria-describedby="basic-addon3"/>
                                                        @error('stripe_public_key_live')
                                                        <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-lg-12 col-md-12 form-group mb-2">
                                                    <label
                                                        for="twitter-input">{{trans('settings.Stripe Secret live')}}</label>
                                                    <div class="input-group input-group-merge">

                                                        <input id="stripe_secret_key_live-input" type="text"
                                                               name="stripe_secret_key_live"
                                                               class="form-control"
                                                               value="{{ old('stripe_secret_key_live', setting('stripe_secret_key_live')) }}"
                                                               placeholder="stripe_secret_key_live"
                                                               aria-describedby="basic-addon3"/>
                                                        @error('stripe_secret_key_live')
                                                        <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>


                                                <div class="col-lg-12 col-md-12 form-group mb-2">
                                                    <label
                                                        for="twitter-input">{{trans('settings.Stripe Secret live')}}</label>
                                                    <div class="input-group input-group-merge">

                                                        <input id="stripe_secret_key_live-input" type="text"
                                                               name="stripe_secret_key_live"
                                                               class="form-control"
                                                               value="{{ old('stripe_secret_key_live', setting('stripe_secret_key_live')) }}"
                                                               placeholder="stripe_secret_key_live"
                                                               aria-describedby="basic-addon3"/>
                                                        @error('stripe_secret_key_live')
                                                        <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-lg-12 col-md-12 form-group mb-2 d-flex align-items-center">

                                                    <label for="payment_is_live " class="me-2">{{ trans('settings.payment_is_live') }}</label>
                                                    <div class="form-check m-0 p-1">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" name="payment_is_live"
                                                                   class="custom-control-input" id="payment_is_live"
                                                                   @if(setting('payment_is_live')==1) checked @endif>
                                                            <label class="custom-control-label"
                                                                   for="payment_is_live">{{trans('settings.yes')}}</label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- users edit social form ends -->
                                            </div>
                                            <!-- credentials Tab ends -->


                                        </div>

                                        <div class="col-12 d-flex flex-sm-row flex-column mt-2 justify-content-end">
                                            <button type="button" class="btn btn-primary mb-1 mb-sm-0 mr-0 mr-sm-1"
                                                    id="submitBtn">
                                                <div id="spinner"
                                                     class="spinner-border spinner-border-sm text-light"
                                                     role="status" style="display: none;">
                                                    <span class="sr-only"></span>
                                                </div>
                                                {{ trans('main.save') }}
                                            </button>
                                        </div>


                                </form>
                            </div>
                        </div>
                    </section>
                    <!-- settings edit ends -->
                </div>
            </div>
        </div>
    </div>




    @push('js')

        <script src="{{url('app-assets/vendors/js/forms/validation/jquery.validate.min.js')}}"></script>

        @if(app()->getLocale() === 'ar')
            <script
                src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/localization/messages_ar.js"></script>
        @endif


        {{--        validate--}}
        <script>

            var form = $('.form-validate');

            if (form.length) {
                $(form).each(function () {
                    var $this = $(this);
                    $this.validate({
                        submitHandler: function (form, event) {
                            event.preventDefault();
                        },
                        rules: {
                            name: {
                                required: true
                            },
                            email: {
                                required: true,
                                email: true
                            },
                            //
                            // commission: {
                            //     required: true
                            // },

                        },

                    });
                });
            }


        </script>


        <!-- Include the Quill library -->
        <script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>

        <script>
            let quillAbout;
            let quillConditions;
            let quillPolicy;

            document.addEventListener("DOMContentLoaded", function () {
                const toolbarOptions = [
                    ['bold', 'italic', 'underline', 'strike'],
                    ['blockquote', 'code-block'],
                    ['link', 'image', 'video', 'formula'],
                    [{'header': 1}, {'header': 2}],
                    [{'list': 'ordered'}, {'list': 'bullet'}, {'list': 'check'}],
                    [{'script': 'sub'}, {'script': 'super'}],
                    [{'indent': '-1'}, {'indent': '+1'}],
                    [{'direction': 'rtl'}],
                    [{'size': ['small', false, 'large', 'huge']}],
                    [{'header': [1, 2, 3, 4, 5, 6, false]}],
                    [{'color': []}, {'background': []}],
                    [{'font': []}],
                    [{'align': []}],
                    ['clean']
                ];

                // Assign to global variables instead of redeclaring
                quillAbout = new Quill('#about', {
                    modules: {toolbar: toolbarOptions},
                    theme: 'snow'
                });

                quillConditions = new Quill('#conditions', {
                    modules: {toolbar: toolbarOptions},
                    theme: 'snow'
                });

                quillPolicy = new Quill('#policy', {
                    modules: {toolbar: toolbarOptions},
                    theme: 'snow'
                });

                // Set initial value from Laravel settings
                const aboutValue = `{!! setting('about') !!}`; // Safely render HTML content
                const conditionsValue = `{!! setting('conditions') !!}`; // Safely render HTML content
                const policyValue = `{!! setting('policy') !!}`; // Safely render HTML content

                quillAbout.root.innerHTML = aboutValue; // Set initial content
                quillConditions.root.innerHTML = conditionsValue; // Set initial content
                quillPolicy.root.innerHTML = policyValue; // Set initial content

            });
        </script>


        {{--        images preview--}}
        <script>
            // Reusable function for image preview
            function previewImage(inputElement, targetImgElement) {
                var reader = new FileReader(),
                    files = inputElement.files;

                reader.onload = function () {
                    if (targetImgElement) {
                        targetImgElement.src = reader.result; // Directly use `src` for DOM elements
                    }
                };

                if (files && files[0]) {
                    reader.readAsDataURL(files[0]);
                }
            }

            // Logo preview
            var changeLogo = document.getElementById('change-logo'),
                logoAvatar = document.querySelector('.logo');

            if (changeLogo) {
                changeLogo.addEventListener('change', function () {
                    previewImage(this, logoAvatar);
                });
            }

            // Icon preview
            var changeIcon = document.getElementById('change-icon'),
                iconAvatar = document.querySelector('.icon');

            if (changeIcon) {
                changeIcon.addEventListener('change', function () {
                    previewImage(this, iconAvatar);
                });
            }
        </script>
        >




        {{-- Submit --}}
        <script>
            $(document).ready(function () {
                $('#submitBtn').on('click', function (e) {
                    e.preventDefault();

                    // Show spinner and disable button
                    $('#spinner').show();
                    $('#submitBtn').prop('disabled', true);

                    const formData = new FormData($('#settingsForm')[0]);

                    // Get the content from both Quill editors
                    const aboutContent = quillAbout?.root?.innerHTML || '';
                    formData.append('about', aboutContent);

                    const conditionsContent = quillConditions?.root?.innerHTML || '';
                    formData.append('conditions', conditionsContent);


                    const policyContent = quillPolicy?.root?.innerHTML || '';
                    formData.append('policy', policyContent);

                    // Submit via AJAX
                    $.ajax({
                        url: "{{ route('settings.update') }}",
                        type: 'POST',
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function (response) {
                            $('#spinner').hide();
                            $('#submitBtn').prop('disabled', false);
                            toastr.success(response.message, '{{ trans('messages.success') }}');
                            setTimeout(() => location.reload(), 700);
                        },
                        error: function (xhr) {
                            $('#spinner').hide();
                            $('#submitBtn').prop('disabled', false);
                            if (xhr.responseJSON.errors) {
                                $.each(xhr.responseJSON.errors, function (key, value) {
                                    toastr.error(value[0], '{{ trans('messages.error') }}');
                                });
                            } else {
                                toastr.error('{{ trans('messages.error') }}');
                            }
                        }
                    });
                });
            });
        </script>

    @endpush

@stop
