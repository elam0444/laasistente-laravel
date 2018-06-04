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
        <form id="requirement" class="form-horizontal campaign-setup">
            <div class="row">

                <h1>Requirement</h1>

                <div class="top1 form-group">
                    <label for="address" class="col-lg-2 col-form-label line-label control-label">
                        Select Address <span>*</span>
                    </label>
                    <div class="col-lg-10">
                        <select id="address" name="address" class="form-line"
                                data-url="{{ route('system.states') }}" required>
                            <option value="">Select Address</option>
                            @foreach ($addresses as $address)
                                <option value="{{$address->id}}"
                                @if(!empty($requirement)) @if ($requirement->address_id == $address->id) selected @endif @endif>
                                    {{$address->name}}: {{$address->address}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="top1 form-group">
                    <label for="description" class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">
                        Description
                    </label>
                    <div class="col-xs-12 col-sm-9 col-md-10 col-lg-10">
                        <input type="text" class="form-line" id="description" name="description"
                               @if(!empty($requirement))value="{{ $requirement->description }}"@endif
                        >
                    </div>
                </div>

                @if(empty($requirement))
                <div class="top1 form-group">
                    <label for="country" class="col-lg-2 col-form-label line-label control-label">
                        Select Service <span>*</span>
                    </label>
                    <div class="col-lg-2">
                        <select id="serviceCategory" name="service_category" class="form-line"
                                data-url="{{ route('service.list') }}" required>
                            <option value="">Category</option>
                            @foreach ($serviceCategories as $serviceCategory)
                                <option value="{{$serviceCategory->id}}">{{$serviceCategory->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-5">
                        <select id="service" name="service" class="form-line" required>
                            <option value="">Service</option>
                        </select>
                    </div>
                    <input type="hidden" class="form-line" id="costPerUnit" name="cost_per_unit" value="">
                    <div class="col-lg-1">
                        <input type="text" class="form-line" id="qty" name="qty" value="1">
                    </div>
                    <div class="col-lg-2 text-left">
                        <p id="totalCostLabel" class="line-label"></p>
                        <input type="hidden" class="form-line" id="totalCost" name="total_cost" value="">
                    </div>
                </div>
                @endif

                <div class="row">
                    <div class="col-lg-1"></div>
                    <div class="col-lg-10">
                        @if(!empty($requirement))
                            <table class="table table-bordered hover" id="requirements-table" href="{{ route('requirement.list.data') }}">
                                <thead>
                                <tr>
                                    <th>{{trans('views.requirements.list.table.created_at')}}</th>
                                    <th>{{trans('views.requirements.list.table.user')}}</th>
                                    <th>{{trans('views.requirements.list.table.user')}}</th>
                                    <th>{{trans('views.requirements.list.table.user')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($requirement->requirementService as $requirementService)
                                    <tr>
                                        <td>{{$requirementService->service_id}}</td>
                                        <td>{{$requirementService->service->name}}</td>
                                        <td>${{$requirementService->total_cost}}</td>
                                        <td>{{$requirementService->delivery_date_time}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                    <div class="col-lg-1"></div>
                </div>

            </div>

            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center">
                    <button type="button" class="btn btn-success" id="save-requirement"
                            data-url="{{ route("requirement.create") }}">
                        SAVE TO CART & REVIEW
                    </button>
                </div>
            </div>

            <div class="alert" id="alert">
            </div>
        </form>

    </div>

@endsection

@section('scripts')
    <script src="{{ elixir('js/Requirement.js') }}"></script>
@endsection