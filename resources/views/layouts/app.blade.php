<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
    
    (function () {
        window.Laravel = {
            csrfToken: '{{ csrf_token() }}'
        };
        localStorage.setItem('data','asdasd')
        @if(Auth::check())
            window.authUser={!! Auth::user() !!}
            @else
            window.authUser=false
            @endif
    })();
    </script>
    <title>{{ config('app.name', 'Al-Jadwal') }}</title>

    <!-- Scripts -->
{{-- <script src="{{ asset('js/app.js') }}" defer></script> --}}

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
</head>
<body>
    <div class="row align-content-center pt">        
        <div class="container bg-white roundd " style="width: 360px;">
            @yield('content')
        </div>
    </div>
</body>
</html>
