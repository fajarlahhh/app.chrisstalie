<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    @section('title', ucwords(str_replace('/', ' ', request()->getRequestUri())))
    @include('includes.head')
    @livewireStyles
</head>

<body class="pace-top">
    @include('includes.component.page-loader')

    <div id="app" class="app">

        @livewire('login')

    </div>

    @yield('outside-content')

    @include('includes.page-js')
    @livewireScripts
</body>

</html>
