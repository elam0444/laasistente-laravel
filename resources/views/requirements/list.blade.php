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
        <div class="row">

            <h1>Requirements</h1>

            <div class="top1 form-group">
                <div id="alert"></div>
                <table class="table table-bordered hover" id="requirements-table" href="{{ route('requirement.list.data') }}">
                    <thead>
                    <tr>
                        <th>{{trans('views.requirements.list.table.created_at')}}</th>
                        <th>{{trans('views.requirements.list.table.user')}}</th>
                        <th>{{trans('views.requirements.list.table.description')}}</th>
                        <th>{{trans('views.requirements.list.table.services')}}</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('additionalCSS')
    <link href="{{ URL::asset('css/vendor/jquery.dataTables.css') }}" rel="stylesheet" type="text/css">
@endsection

@section('scripts')
    <script src="{{ elixir('js/vendor/jquery.dataTables.js') }}"></script>
    <script src="{{ elixir('js/RequirementsList.js') }}"></script>
    <script>
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })
    </script>
    <script src="https://cdn.datatables.net/responsive/2.2.1/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/rowreorder/1.2.3/js/dataTables.rowReorder.min.js"></script>
@endsection