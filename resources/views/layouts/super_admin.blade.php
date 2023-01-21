<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Favicons -->
    <link href="{{ asset('images/edu-smart-favicon.png') }}" rel="icon" type="image/ico" sizes="32x32">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <title>{{ config('app.name', 'Starter') }}</title>

    <!-- Fonts -->
    {{-- <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet"> --}}
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link
        href="https://fonts.googleapis.com/css2?family=Josefin+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;1,200;1,300;1,400;1,500;1,600&display=swap"
        rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">

    <style>
        body {
            background: #f7f9ff;
            font-family: 'Josefin Sans', sans-serif;
            font-size: 16px;
            font-size: 1rem;
            font-weight: 600;
            line-height: 1.4;
            color: #555;
        }
    </style>

    @livewireStyles
    @stack('styles')

    <script src="{{ mix('js/app.js') }}" defer></script>
    @livewireScripts
    <script src="https://cdn.jsdelivr.net/gh/livewire/turbolinks@v0.1.x/dist/livewire-turbolinks.js"
        data-turbolinks-eval="false"></script>

</head>

<body class="sidebar-mini layout-fixed control-sidebar-slide-open sidebar-collapse">
    <div class="wrapper" id="app">
        <x-adminTopNav />
        <x-superAdminMenu />
        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid"></div>
            </div>
            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            {{ $slot }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <x-adminFooter />
    </div>
    <!-- Scripts -->
    {{-- <script src="{{ mix('js/app.js') }}" defer></script> --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    {{-- @livewireScripts
    <script src="https://cdn.jsdelivr.net/gh/livewire/turbolinks@v0.1.x/dist/livewire-turbolinks.js"
        data-turbolinks-eval="false"></script> --}}

    <script>
        $(document).ready(function() {
            toastr.options = {
                "positionClass": "toast-top-right",
                //"progressBar": true
            }

            window.addEventListener('showToast', event => {
                toastr.success(event.detail.message);
            })
        });
    </script>
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
        window.addEventListener('swal:confirm', event => {
            var title = event.detail.title ?? 'Are you sure want to delete?';
            var text = event.detail.text ?? "You won't be able to revert this!";
            var type = event.detail.type ?? 'warning';
            swal({
                    title: title,
                    text: text,
                    icon: type,
                    buttons: true,
                    dangerMode: true,
                    showClass: {
                        popup: 'animate__animated animate__fadeInDown'
                    },
                    hideClass: {
                        popup: 'animate__animated animate__fadeOutUp'
                    }
                })
                .then((willDelete) => {
                    if (willDelete) {
                        window.livewire.emit('destroy', event.detail.id);
                    }
                });
        })
        window.addEventListener('swal:confirm-approve', event => {
            var title = event.detail.title ?? 'Are you sure want to approve?';
            var text = event.detail.text ?? '';
            var type = event.detail.type ?? 'warning';
            swal({
                    title: title,
                    text: text,
                    icon: type,
                    buttons: true,
                    dangerMode: true
                })
                .then((willApprove) => {
                    if (willApprove) {
                        window.livewire.emit('approve');
                    }
                });
        })
    </script>
    <script>
        window.addEventListener('scrollToTop', event => {
            $("html, body").animate({
                scrollTop: 0
            }, "slow");
            return false;
        })
        window.addEventListener('printPage', event => {
            window.print();
        })
    </script>
    @stack('scripts')
</body>

</html>
