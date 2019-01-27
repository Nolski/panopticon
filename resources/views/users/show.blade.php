@extends('layouts.layout')
@section('content')
    <breadcrumbs
            :crumbs="[{name:'{{ trans('irc.users') }}', link:'{{route('users')}}'},{name:'{{trans('irc.viewing')}} [{{$user->name}}]', link:''}]"
    ></breadcrumbs>
    <div class="" id="all-users">
        <user-view
                :user="{{$user->toJson()}}"
                :roles="{{$roles->values()->toJson()}}"
                :view-only="true"
        ></user-view>
    </div>
@endsection



{{--<script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>--}}
{{--<script src="{{ asset('js/app.js') }}" type="text/javascript"></script>--}}

@section('script')
@endsection
