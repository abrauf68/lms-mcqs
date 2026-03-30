<!DOCTYPE html>
<html lang="en">

<head>
    <title>@yield('title') - {{ \App\Helpers\Helper::getCompanyName() }}</title>
    @include('frontend.layouts.meta')
    @include('frontend.layouts.css')
    @yield('css')
</head>

<body>

    @include('frontend.layouts.header')

    <!-- **************** MAIN CONTENT START **************** -->
    <main>

        @yield('content')

    </main>
    <!-- **************** MAIN CONTENT END **************** -->

    @include('frontend.layouts.footer')

    <!-- Bootstrap JS -->
    @include('frontend.layouts.script')
    @yield('js')

</body>

</html>
