@extends('dashboard.layouts.master',['title'=>trans('settings.banners')])

@push('css')
    <link rel="stylesheet" type="text/css" href="{{url('app-assets/css-rtl/plugins/forms/form-validation.css')}}">

@endpush


@section('left')
    <div class="content-header-right text-md-right col-md-3 col-12 d-md-block d-none">
        <div class="form-group breadcrumb-right">
            <button class="btn btn-primary waves-effect waves-float waves-light btn-sm"
                    data-toggle="modal" data-target="#inlineForm"
                    title="{{trans('settings.new-banner')}}">
                <i data-feather="plus"></i>
            </button>
        </div>
    </div>



@endsection




@section('content')



    <div class="content-body">

    <div class="card">

            <div class="card-datatable table-responsive p-2 ">

                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>{{trans('settings.image')}}</th>

                            <th>{{trans('settings.title')}}</th>
                            <th>{{trans('settings.actions')}}</th>

                        </tr>
                        </thead>
                        <tbody>

                        @if(\App\Models\Banner::count()>0)
                            @foreach(\App\Models\Banner::all() as $banner)
                                <tr>

                                    <td>
                                        <img src="{{$banner->getAvatar()}}" class="mr-75" height="120" width="200" alt="{{$banner->title}} ">

                                    </td>

                                    <td>
                                        <span class="font-weight-bold"> {{$banner->title}}   </span>
                                    </td>


                                    <td>
                                        <button title="{{trans('main.edit')}}" class="btn btn-icon btn-outline-secondary rounded-circle waves-effect waves-float waves-light"  onclick="editBanner({{ $banner->id }})">
                                            <i data-feather="edit"></i>

                                        </button>
                                        <a  title="{{trans('main.delete')}}" class="btn btn-icon btn-outline-secondary rounded-circle waves-effect waves-float waves-light" id="delete" model_id="{{$banner->id}}" reload="true" route="{{route('settings.banners.delete')}}" >
                                            <i data-feather="trash"></i>

                                        </a>


                                    </td>
                                </tr>

                            @endforeach


                        @else

                            <tr>

                                <td colspan="3">
                                    <h3 class="text-center text-body p-2">{{trans('main.no-banners')}}</h3>

                                </td>

                            </tr>
                        @endif

                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>



    @include('dashboard.banners.edit')
    @include('dashboard.banners.create')




    @push('js')

        <script src="{{url('app-assets/vendors/js/forms/validation/jquery.validate.min.js')}}"></script>

        @if(app()->getLocale() === 'ar')
            <script
                src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/localization/messages_ar.js"></script>
        @endif





    @endpush

@stop
