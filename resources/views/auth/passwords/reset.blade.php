@extends('layouts.layout')

@section('content')

    <div class="w-full max-w-24 mx-auto">
        <div class="image-top">
            <div class="image-container p-4 rounded-t text-center " style="background: rgba(64, 94, 128, 1);">
                {!! switch_url(true) !!}
                <img style="height: 140px;" src="{{ asset('img/logo_big.png') }}" alt="">
            </div>
        </div>
        <form method="POST" action="{{ route('password.update') }}"
              class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">


            <div class="mb-4">
                <label class="block text-grey-darker text-sm font-bold mb-2" for="username">
                    {{trans('irc.email')}}
                </label>
                <input id="email" type="email"
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-grey-darker leading-tight focus:outline-none focus:shadow-outline form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                       name="email" value="{{ $email ?? old('email') }}" required autofocus>

                @if ($errors->has('email'))
                    <span class="invalid-feedback" role="alert">
                                        <strong class="text-xs text-red">{{ $errors->first('email') }}</strong>
                                    </span>
                @endif
            </div>
            <div class="mb-4">
                <label class="block text-grey-darker text-sm font-bold mb-2" for="password">
                    {{trans('irc.password')}}
                </label>
                <input id="password" type="password"
                       class="shadow appearance-none border  rounded w-full py-2 px-3 text-grey-darker mb-3 leading-tight focus:outline-none focus:shadow-outline form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                       name="password" required>

                @if ($errors->has('password'))
                    <span class="invalid-feedback" role="alert">
                                        <strong class="text-xs text-red">{{ $errors->first('password') }}</strong>
                                    </span>
                @endif


                @if ($errors->has('deactivated_user'))
                    <span class="text-red-dark"><strong class="text-xs text-red">{{$errors->first('deactivated_user')}}</strong></span>
                @endif
            </div>

            <div class="mb-6">


                <label for="password-confirm"
                       class="block text-grey-darker text-sm font-bold mb-2">{{ __(trans('irc.confirm_password')) }}</label>

                <input id="password-confirm" type="password"
                       class="shadow appearance-none border  rounded w-full py-2 px-3 text-grey-darker mb-3 leading-tight focus:outline-none focus:shadow-outline form-control form-control"
                       name="password_confirmation" required>

            </div>


            <div class="flex items-center justify-between">
                <button type="submit"
                        class="bg-blue hover:bg-blue-dark text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    {{ __(trans('irc.reset_password'))}}
                </button>

            </div>
        </form>
    </div>
@endsection
