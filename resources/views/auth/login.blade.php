@extends('layouts.layout')

@section('content')

    <div class="w-full max-w-24 mx-auto">
        <div class="image-top">
            <div class="image-container p-4 rounded-t text-center " style="background: rgba(64, 94, 128, 1);">
                {!! switch_url(true) !!}

                <img style="height: 140px;" src="{{ asset('img/logo_big.png') }}" alt="">
            </div>
        </div>
        <form method="POST" action="{{ route('login') }}" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            @csrf
            <div class="mb-4">
                <label class="block text-grey-darker text-sm font-bold mb-2" for="username">
                    {{trans('irc.email')}}
                </label>
                <input id="email" type="email" style=""
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-grey-darker leading-tight focus:outline-none focus:shadow-outline{{ $errors->has('email') ? ' border-red is-invalid' : '' }}"
                       name="email" value="{{ old('email') }}" required autofocus>

                @if ($errors->has('email'))
                    <span class="invalid-feedback text-xs" role="alert">
                                        <strong class="text-xs text-red">{{ $errors->first('email') }}</strong>
                                    </span>
                @endif
            </div>
            <div class="mb-6">
                <label class="block text-grey-darker text-sm font-bold mb-2" for="password">
                    {{trans('irc.password')}}
                </label>
                <input id="password" type="password" style=""
                       class="shadow appearance-none border  rounded w-full py-2 px-3 text-grey-darker mb-3 leading-tight focus:outline-none focus:shadow-outline{{ $errors->has('password') ? ' is-invalid border-red' : '' }}"
                       name="password" required>

                @if ($errors->has('password'))
                    <span class="invalid-feedback text-xs" role="alert">
                                        <strong class="text-xs text-red">{{ $errors->first('password') }}</strong>
                                    </span>
                @endif


                @if ($errors->has('deactivated_user'))
                    <span class="text-red-dark"><strong>{{$errors->first('deactivated_user')}}</strong></span>
                @endif
            </div>
            <div class="md:flex md:items-center mb-6">
                <label class="block text-grey font-bold">


                    <input class="mr-2 leading-tight" type="checkbox" name="remember"
                           id="remember" {{ old('remember') ? 'checked' : '' }}>

                    <label class="form-check-label text-sm" for="remember">
                        {{ __(trans('irc.remember_me')) }}
                    </label>
                    {{--<span class="text-sm">--}}
                    {{--Send me your newsletter!--}}
                    {{--</span>--}}
                </label>
            </div>
            <div class="flex items-center justify-between">
                <button type="submit"
                        class="bg-blue hover:bg-blue-dark text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    {{ __(trans('irc.login')) }}
                </button>

                @if (Route::has('password.request'))
                    <a class="dec inline-block no-underline align-baseline font-bold text-sm text-blue hover:text-blue-darker"
                       href="{{ route('password.request') }}">
                        {{ __(trans('irc.forgot_password')) }}
                    </a>
                @endif
            </div>
        </form>
    </div>

@endsection
