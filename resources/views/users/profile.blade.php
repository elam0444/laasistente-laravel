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

    <!--<div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center">
            <div>
                <button type="button" class="btn btn-info" id="stop">
                    <span class="stop-watch"></span>
                </button>
            </div>
        </div>
    </div>-->

    <div class="content">
        <div class="col-lg-2"></div>
        <div class="col-lg-8">
            <form id="profile" class="form-horizontal user-profile">
                <div class="row">
                    <h1>Profile</h1>
                    <div class="form-group">
                        <label for="first-name" class="col-xs-12 col-sm-3 col-md-4 col-lg-4 line-label">
                            First Name
                        </label>
                        <div class="col-xs-12 col-sm-9 col-md-8 col-lg-8">
                            <input type="text" class="form-line" id="first-name" name="first_name"
                                   @if(!empty($user))value="{{ $user->first_name }}"@endif
                            >
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="last-name" class="col-xs-12 col-sm-3 col-md-4 col-lg-4 line-label">
                            Last Name
                        </label>
                        <div class="col-xs-12 col-sm-9 col-md-8 col-lg-8">
                            <input type="text" class="form-line" id="last-name" name="last_name"
                                   @if(!empty($user))value="{{ $user->last_name }}"@endif
                            >
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="title" class="col-xs-12 col-sm-3 col-md-4 col-lg-4 line-label">Title</label>

                        <div class="col-lg-8">
                            <input class="form-line" type="text" title="title" name="title"
                                   placeholder="My Title" value="{{ $user->title }}"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="phone_1" class="col-xs-12 col-sm-3 col-md-4 col-lg-4 line-label">Phone
                            <span>*</span></label>
                        <div class="col-xs-12 col-sm-9 col-md-8 col-lg-4">
                            <input class="form-line" type="text" title="phone-1" name="phone_1"
                                   placeholder="Phone 1" value="{{ $user->phone_1 }}" autocomplete="tel"/>
                        </div>

                        <div class="col-xs-12 col-sm-9 col-md-8 col-lg-4">
                            <input class="form-line" type="text" title="phone-2" name="phone_2"
                                   placeholder="Phone 2" value="{{ $user->phone_2 }}"/>
                        </div>
                    </div>

                    <div class="top1 form-group">
                        <label for="address" class="col-lg-4 col-form-label line-label line-label">
                            Select Address
                        </label>
                        <div class="col-lg-6">
                            <select id="address" name="address" class="form-line"
                                    data-url="{{ route('system.address') }}" required>
                                <option value="">Select Address</option>
                                @foreach ($addresses as $address)
                                    <option value="{{$address->id}}">{{$address->name}}: {{$address->address}}</option>
                                @endforeach
                            </select>
                        </div>
                        <span class="col-lg-2">
                            <input type="hidden" id="newAddressFlag" name="new_address_flag" value="">
                            <button id="newAddress" class="btn btn-info" type="button">+ New</button>
                        </span>
                    </div>

                    <div id="addressContent"></div>

                </div>

                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center">
                        <button type="button" class="btn btn-success" id="save-profile"
                                data-url="{{ route("user.details.update", [$user->getHashedId()]) }}">
                            UPDATE
                        </button>
                    </div>
                </div>

                <div class="alert" id="alert">
                </div>
            </form>
        </div>
        <div class="col-lg-2"></div>
    </div>

@endsection

@section('scripts')
    <script src="{{ elixir('js/UserProfile.js') }}"></script>
@endsection