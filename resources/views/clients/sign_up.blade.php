@extends('layouts.clients_auth')

@section('content')
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 top3 text-center">
            <h2>{{trans('views.sign_up.title')}}</h2>
        </div>
    </div>
    <div class="row">
        <form id="sign-up" class="top1" method="POST" action="{{route('clients.sign_up')}}"
              data-redirect-success="{{route('home')}}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">

            <div class="col-lg-2 col-md-2"></div>
            <div class="col-lg-8 col-md-8">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">

                        <div id="userForm">
                            <div class="top1 form-group">
                                <label for="first_name" class="col-lg-4 col-form-label line-label">My Name Is
                                    <span>*</span></label>
                                <div class="col-lg-4">
                                    <input class="form-line" type="text" title="first-name" name="first_name"
                                           placeholder="{{trans('views.sign_up.form.first_name')}}" required/>
                                </div>
                                <div class="col-lg-4">
                                    <input class="form-line" type="text" title="last-name" name="last_name"
                                           placeholder="{{trans('views.sign_up.form.last_name')}}" required/>
                                </div>

                            </div>

                            <div class="top1 form-group">
                                <label for="email" class="col-lg-4 col-form-label line-label">This is My Email
                                    <span>*</span></label>
                                <div class="col-lg-8">
                                    <input class="form-line" type="text" title="email" name="email"
                                           placeholder="{{trans('views.sign_up.form.email')}}" required/>
                                </div>
                            </div>

                            <div class="top1 form-group">
                                <label for="gender" class="col-lg-4 col-form-label line-label">And This is My Gender
                                    <span>*</span></label>

                                <div class="col-lg-8">
                                    <select id="gender" name="gender" class="form-line"
                                            data-url="{{ route('system.cities') }}"
                                            required>
                                        @foreach ($gender as $g)
                                            <option value="{{ $g->id }}">{{$g->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="top1 form-group">
                                <label for="title" class="col-lg-4 col-form-label line-label">My Title is</label>

                                <div class="col-lg-8">
                                    <input class="form-line" type="text" title="title" name="title"
                                           placeholder="My Title"/>
                                </div>
                            </div>

                            <div class="top1 form-group">
                                <label for="company_name" class="col-lg-4 col-form-label line-label">I Work for This
                                    Company</label>
                                <div class="col-lg-8">
                                    <input class="form-line" type="text" title="company-name" name="company_name"
                                           placeholder="Company Name"/>
                                </div>
                            </div>

                            <div class="top1 form-group">
                                <label for="phone_1" class="col-lg-4 col-form-label line-label">You Can Call Me
                                    <span>*</span></label>
                                <div class="col-lg-4">
                                    <input class="form-line" type="text" title="phone-1" name="phone_1"
                                           placeholder="Phone 1" required/>
                                </div>

                                <div class="col-lg-4">
                                    <input class="form-line" type="text" title="phone-2" name="phone_2"
                                           placeholder="Phone 2"/>
                                </div>
                            </div>

                            <div class="top1 form-group">
                                <label for="address" class="col-lg-4 col-form-label line-label">And This Is My
                                    Secret<span>*</span></label>
                                <div class="col-lg-4">
                                    <input class="form-line" type="password" title="password" name="password"
                                           placeholder="{{trans('views.sign_up.form.password')}}" required/>
                                </div>

                                <div class="col-lg-4">
                                    <input class="form-line" type="password" title="password-confirmation"
                                           name="password_confirmation"
                                           placeholder="{{trans('views.sign_up.form.password_confirmation')}}"
                                           required/>
                                </div>
                            </div>

                        </div>

                        @include('partials.users.address', ['countries' => $countries])

                    </div>
                </div>
                <br>

                <div class="col-lg-12 col-md-12 text-center">
                    This is overwhelming, we know but the use of this information is for giving you an awesome service
                    ;)
                </div>
                <br><br>
                <div class="row top1">
                    <div class="col-lg-12 col-md-12 text-center">
                        <input type="submit" id="submit" class="btn btn-success"
                               value="{{trans('views.sign_up.form.submit')}}"
                        >
                        <div id="alerts-section" class="alert"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-2"></div>
        </form>
    </div>
@endsection

@section('additionalCSS')
@endsection

@section('scripts')
    <script src="{{ elixir('js/SignUp.js') }}"></script>
@endsection
