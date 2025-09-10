<!DOCTYPE html>
<html lang="en">
@include('auth.template.head')
@stack('css')
<style>
    .toast-container {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 1055;
    }
</style>

</head>

<body>
    @include('message.index')

    @yield('content')
</body>
@include('auth.template.footer')
@stack('scripts')

</html>
