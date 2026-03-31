<!DOCTYPE html>
<html lang="en">

<head>
    <title>@yield('title') - {{ \App\Helpers\Helper::getCompanyName() }}</title>
    @include('frontend.layouts.meta')
    @include('frontend.layouts.css')
    @yield('css')
</head>

<body>

    <!-- **************** MAIN CONTENT START **************** -->
    <main>

        @yield('content')

    </main>
    <!-- **************** MAIN CONTENT END **************** -->

    <!-- Bootstrap JS -->
    @include('frontend.layouts.script')
    @yield('script')

</body>

</html>
