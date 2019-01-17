@extends('layouts.layout')
@section('content')
    <breadcrumbs
            :crumbs="[{name:'{{ trans('irc.users') }}', link:'{{route('users')}}'},{name:'{{trans('irc.updating')}} [{{$user->name}}]', link:''}]"
    ></breadcrumbs>
    <div class="" id="all-users">
        <user-view
                :roles="{{$roles->toJson()}}"
                :user="{{$user->toJson() }}"
        ></user-view>
    </div>
@endsection



{{--<script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>--}}
{{--<script src="{{ asset('js/app.js') }}" type="text/javascript"></script>--}}

@section('script')
@endsection
