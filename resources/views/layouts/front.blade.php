<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <link rel="preconnect" href="https://fonts.gstatic.com" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet" />

    <link href="{{ asset('images/edu-smart-favicon.png') }}" rel="icon" type="image/ico" sizes="32x32">

    <title>{{ config('app.name', 'Smart Admission') }}</title>

    {{-- CSRF Token --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap core CSS -->
    {{-- <link rel="stylesheet" href="{{ asset('frontend/vendor/bootstrap/css/bootstrap.min.css') }}" /> --}}

    <!-- Additional CSS Files -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    {{-- <link rel="stylesheet" href="{{ asset('frontend/assets/css/fontawesome.css') }}" /> --}}
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/front.css') }}" />
    {{-- <link rel="stylesheet" href="{{ asset('frontend/assets/css/animated.css') }}" /> --}}
    {{-- <link rel="stylesheet" href="{{ asset('frontend/assets/css/owl.css') }}" /> --}}


    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    {{-- <script src="{{ asset('frontend/assets/js/animation.js') }}"></script> --}}

    <style>
        body {
            width: 100%;
            height: 100vh;
        }
    </style>
    @stack('styles')

    <script src="{{ mix('js/app.js') }}" defer></script>
    {{-- <script src="https://cdn.jsdelivr.net/gh/livewire/turbolinks@v0.1.x/dist/livewire-turbolinks.js"
        data-turbolinks-eval="false"></script> --}}


</head>

<body id="my-scrollbar">
    <!-- ***** Preloader Start ***** -->
    {{-- <div id="js-preloader" class="js-preloader">
        <div class="preloader-inner">
            <span class="dot"></span>
            <div class="dots">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </div> --}}
    <!-- ***** Preloader End ***** -->

    <!-- ***** Header Area Start ***** -->
    <x-frontMenu />
    <!-- ***** Header Area End ***** -->

    <div class="main-banner" id="top">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    <x-frontContact />

    <x-frontFooter />


    <!-- Scripts -->

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    {{-- <script src="https://code.jquery.com/jquery-1.8.3.min.js"
        integrity="sha256-YcbK69I5IXQftf/mYD8WY0/KmEDCv1asggHpJk1trM8=" crossorigin="anonymous"></script> --}}
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    {{-- <script src="{{ asset('frontend/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script> --}}
    {{-- <script src="{{ asset('js/smooth-scrollbar.js') }}"></script>
    <script>
        var Scrollbar = window.Scrollbar;
        Scrollbar.init(document.querySelector('#my-scrollbar'));
    </script> --}}


    {{-- <script src="{{ asset('frontend/assets/js/owl-carousel.js') }}"></script> --}}
    {{-- <script src="{{ asset('frontend/assets/js/animation.js') }}"></script> --}}
    {{-- <script src="{{ asset('frontend/assets/js/imagesloaded.js') }}"></script> --}}
    <script src="{{ asset('frontend/assets/js/front.js') }}"></script>

    <script>
        window.addEventListener('swal:warning', event => {
            var title = event.detail.title ?? 'Select';
            var text = event.detail.text ?? '';
            var type = event.detail.type ?? 'warning';
            swal({
                title: title,
                text: text,
                icon: type,
                buttons: true,
                dangerMode: true
            });
        })
    </script>

    @stack('scripts')
</body>

</html>
