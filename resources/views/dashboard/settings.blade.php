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


                                </ul>

                                <form id="settingsForm" class="form-validate" enctype="multipart/form-data">
                                    @csrf
                                    <div class="tab-content">

                                        <!-- Account Tab starts -->
                                        <div class="tab-pane active" id="account" aria-labelledby="account-tab"
                                             role="tabpanel">
                                            <!-- users edit media object start -->
                                            <div class="row mt-3 justify-content-center">
                                                <!-- Icon Section -->
                                                <div class="col-3 col-md-6 ">
                                                    <div class="media mb-2">

                                                        <div class="media-body mt-50">
                                                            <h4>{{trans('settings.icon')}}</h4>
                                                            <div class="col-12 d-flex mt-1 px-0">
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
                                                                class="img-fluid user-avatar icon users-avatar-shadow rounded mr-2 my-25 cursor-pointer"
                                                                style="max-width: 90%; height: auto;"/>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Logo Section -->
                                                <div class="col-8 col-md-4 justify-content-end">
                                                    <div class="media mb-2">

                                                        <div class="media-body mt-50">
                                                            <h4>{{trans('settings.logo')}}</h4>
                                                            <div class="col-12 d-flex mt-1 px-0">
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
                                                                class="img-fluid rounded logo"
                                                                style="max-width: 90%; height: auto;"/>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>


                                            <!-- users edit account form start -->
                                            <div class="row">
                                                <div class="col-md-3">
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
                                                <div class="col-md-3">
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
                                                <div class="col-md-3">
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

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="whatsapp">{{trans('settings.commission')}}</label>
                                                        <input type="number" class="form-control"
                                                               placeholder="{{trans('settings.commission')}}"
                                                               value="{{ old('commission', setting('commission')) }}"
                                                               name="commission" id="commission"/>
                                                        @error('commission')
                                                        <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>


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

                            commission: {
                                required: true
                            },

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
