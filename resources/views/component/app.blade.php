<!doctype html>
<html lang="en">
<head>
    @include('component.head')
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">

    <!--begin::App Wrapper-->
    <div class="app-wrapper">
        <!--begin::Header-->
        @include('component.navbar')
        <!--end::Header-->

        <!--begin::Sidebar-->
        @include('component.sidebar')
        <!--end::Sidebar-->

        <!--begin::App Main-->
        <main class="app-main">
            @stack('header')

            <!--begin::App Content-->
            <div class="app-content">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </div>
            <!--end::App Content-->
        </main>
        <!--end::App Main-->

        <!--begin::Footer-->
        @include('component.footer')
        <!--end::Footer-->
    </div>
    <!--end::App Wrapper-->

    <!--begin::Script-->
    @include('component.script')

</body>
</html>