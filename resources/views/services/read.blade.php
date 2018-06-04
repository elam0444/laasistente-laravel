@extends('layouts.app_default', ['title' => trans('views.page_titles.login.main')])

@section('content')

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-right">
            @if (Route::has('auth.login'))
                @auth
                <a href="{{ url('/home') }}">{{trans('views.home.home')}}</a>
            @else
                @if(Sentinel::getUser())
                    <a href="{{ route('auth.logout') }}">{{trans('views.home.logout')}}</a>
                @else
                    <a href="{{ route('auth.login') }}">{{trans('views.home.login')}}</a>
                @endif
                @endauth
            @endif
        </div>
    </div>

    <br>

    <div class="content">
        <form id="service" class="form-horizontal campaign-setup">
            <h1>Service</h1>
            <div class="row">

                <div class="top1">
                    <label for="country" class="col-lg-2 col-form-label line-label control-label">
                        Service <span>*</span>
                    </label>

                    <div class="col-lg-3 text-left">
                        <select id="company" name="company" class="form-line" required>
                            <option value="">Company</option>
                            @foreach ($companies as $company)
                                <option value="{{$company->id}}">{{$company->name}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-2">
                        <select id="serviceCategory" name="service_category" class="form-line"
                                data-url="{{ route('service.list') }}" required>
                            <option value="">Category</option>
                            @foreach ($serviceCategories as $serviceCategory)
                                <option value="{{$serviceCategory->id}}">{{$serviceCategory->name}}</option>
                            @endforeach
                        </select>
                    </div>


                    <div class="col-lg-4">
                        <input type="text" class="form-line"
                               placeholder="Service Name"
                               id="name" name="name" value="">
                    </div>

                </div>

            </div>

            <div class="row">

                <label for="description" class="col-lg-2 col-form-label line-label control-label">
                    Description <span>*</span>
                </label>
                <div class="col-lg-3">
                    <input type="text" class="form-line"
                           placeholder="Description"
                           id="description" name="description" value="">
                </div>
                <div class="col-lg-2">
                    <input type="text" class="form-line"
                           placeholder="e.g.: meters, hours, etc"
                           id="units" name="units" value="">
                </div>
                <div class="col-lg-2">
                    <input type="text" class="form-line"
                           placeholder="minium value"
                           id="minimum" name="minimum" value="1">
                </div>
                <div class="col-lg-2">
                    <input type="text" class="form-line"
                           placeholder="Cost Per Unit"
                           id="costPerUnit" name="cost_per_unit" value="5000">
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center">
                    <button type="button" class="btn btn-success" id="save-service"
                            data-url="{{ route("service.create") }}">
                        SAVE
                    </button>
                </div>
            </div>

            <div class="alert" id="alert">
            </div>
        </form>

    </div>

@endsection

@section('scripts')
    <script src="{{ elixir('js/Service.js') }}"></script>
@endsection