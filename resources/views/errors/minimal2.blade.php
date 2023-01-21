
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <!-- Favicons -->
    <link href="{{ asset('frontend/img/favicon.ico') }}" rel="icon" type="image/ico" sizes="32x32">
    
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title')</title>

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <!-- iCheck for checkboxes and radio inputs -->
    <link rel="stylesheet" href="{{ asset('frontend/lib/iCheck/square/red.css') }}">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">

    <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .code {
                border-right: 2px solid;
                font-size: 26px;
                padding: 0 15px 0 15px;
                text-align: center;
            }

            .message {
                font-size: 18px;
                text-align: center;
            }
        </style>

    @yield('styles')
    
</head>
<body class="hold-transition sidebar-mini sidebar-collapse">
    <div class="wrapper" id="app">        
        <!-- Navbar -->
        @include('partials.admin-top-nav')        
        <!-- /.navbar -->
        
        <!-- Main Sidebar Container -->
        @include('partials.admin-menu') 
        
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->
            
            <!-- Main content -->
            <div class="content">
                <div class="container-fluid">
                       <div class="code">
                            @yield('code')
                        </div>

                        <div class="message" style="padding: 10px;">
                            @yield('message')
                        </div>
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
        
        <!-- Main Footer -->
        @include('partials.admin-footer') 
        
    </div>
    <!-- ./wrapper -->
    
    <!-- REQUIRED SCRIPTS -->
    @yield('scripts')
</body>
</html>
