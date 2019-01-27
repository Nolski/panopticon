<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ config('laravellocalization.supportedLocales.' . app()->getLocale() . '.' . 'dir', 'ltr') }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Tashbeek</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @if(auth()->check())
        <meta name="api-token" content="{{ auth()->user()->api_token }}">
    @endif

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://unpkg.com/vue-multiselect@2.1.0/dist/vue-multiselect.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css"
          integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Work+Sans" rel="stylesheet">

    <link rel="stylesheet" href="{{asset('./css/app.css')}}">
    <link rel="shortcut icon" href="{{{ asset('img/logo_big.png') }}}">
</head>
<body class="bg-grey-light">
<div class="wrapper bg-grey-lighter ">

    @if(auth()->check())
        @include('layouts.sidebar')
    @endif
        {{--<div class="bg-red ">--}}
            {{--{!! switch_url(true) !!}--}}
        {{--</div>--}}
    <div class="p-10 container body-container mx-auto pr-2 sm:pl-16 md:pl-10  {{!auth()->check() ? ' flex justify-center flex-col login' :''}}" id="app">
        @if (session('status') && auth()->check())
            <alert type="success" message="{{ session('status') }}"></alert>
        @endif
        @yield('content')
    </div>
</div>
</body>
</html>

<script type="text/javascript">
  window.appURL = '{{ url('/') }}';
  window.homeUrl = '{{ route('home') }}'
  @auth
      window.userRoles = {!! auth()->user()->roles->toJson() !!}
    @endauth
</script>

<script
        src="https://code.jquery.com/jquery-3.3.1.min.js"
        integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
        crossorigin="anonymous"></script>

<script type="text/javascript">
  $(document).ready(function () {
    $('#sidebarCollapse').on('click', function () {
      $('#sidebar').toggleClass('minified');
      $('.wrapper ').toggleClass('collapsed');
    });
  });
</script>

<script src="{{asset('./js/messages.js')}}"></script>
<script src="{{asset('./js/app.js')}}"></script>

@section('script')

@show