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


    <link href="{{ asset('css/style.css') }}" rel="stylesheet">

    <!-- Styles -->
    <style>
        body {
            background-color: #2F3242 !important;
        }

        svg {
            position: absolute;
            top: 50%;
            left: 50%;
            margin-top: -250px;
            margin-left: -400px;
        }

        .message-box {
            height: 200px;
            width: 380px;
            position: absolute;
            top: 50%;
            left: 50%;
            margin-top: -100px;
            margin-left: 50px;
            color: #FFF;
            font-family: Roboto;
            font-weight: 300;
        }

        .message-box h1 {
            font-size: 60px;
            line-height: 46px;
            margin-bottom: 40px;
        }

        .buttons-con .action-link-wrap {
            margin-top: 40px;
        }

        .buttons-con .action-link-wrap a {
            background: #68c950;
            padding: 8px 25px;
            border-radius: 4px;
            color: #FFF;
            font-weight: bold;
            font-size: 14px;
            transition: all 0.3s linear;
            cursor: pointer;
            text-decoration: none;
            margin-right: 10px
        }

        .buttons-con .action-link-wrap a:hover {
            background: #5A5C6C;
            color: #fff;
        }

        #Polygon-1,
        #Polygon-2,
        #Polygon-3,
        #Polygon-4,
        #Polygon-4,
        #Polygon-5 {
            animation: float 1s infinite ease-in-out alternate;
        }

        #Polygon-2 {
            animation-delay: .2s;
        }

        #Polygon-3 {
            animation-delay: .4s;
        }

        #Polygon-4 {
            animation-delay: .6s;
        }

        #Polygon-5 {
            animation-delay: .8s;
        }

        @keyframes float {
            100% {
                transform: translateY(20px);
            }
        }

        @media (max-width: 450px) {
            svg {
                position: absolute;
                top: 50%;
                left: 50%;
                margin-top: -250px;
                margin-left: -190px;
            }

            .message-box {
                top: 50%;
                left: 50%;
                margin-top: -100px;
                margin-left: -190px;
                text-align: center;
            }
        }
    </style>

    @yield('styles')

</head>

<body class="hold-transition sidebar-mini sidebar-collapse">

    <div class="wrapper" id="app">


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
                    <svg width="380px" height="500px" viewBox="0 0 837 1045" version="1.1"
                        xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                        xmlns:sketch="http://www.bohemiancoding.com/sketch/ns">
                        <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"
                            sketch:type="MSPage">
                            <path
                                d="M353,9 L626.664028,170 L626.664028,487 L353,642 L79.3359724,487 L79.3359724,170 L353,9 Z"
                                id="Polygon-1" stroke="#007FB2" stroke-width="6" sketch:type="MSShapeGroup"></path>
                            <path
                                d="M78.5,529 L147,569.186414 L147,648.311216 L78.5,687 L10,648.311216 L10,569.186414 L78.5,529 Z"
                                id="Polygon-2" stroke="#EF4A5B" stroke-width="6" sketch:type="MSShapeGroup"></path>
                            <path
                                d="M773,186 L827,217.538705 L827,279.636651 L773,310 L719,279.636651 L719,217.538705 L773,186 Z"
                                id="Polygon-3" stroke="#795D9C" stroke-width="6" sketch:type="MSShapeGroup"></path>
                            <path
                                d="M639,529 L773,607.846761 L773,763.091627 L639,839 L505,763.091627 L505,607.846761 L639,529 Z"
                                id="Polygon-4" stroke="#F2773F" stroke-width="6" sketch:type="MSShapeGroup"></path>
                            <path
                                d="M281,801 L383,861.025276 L383,979.21169 L281,1037 L179,979.21169 L179,861.025276 L281,801 Z"
                                id="Polygon-5" stroke="#36B455" stroke-width="6" sketch:type="MSShapeGroup"></path>
                        </g>
                    </svg>
                    <div class="message-box">
                        <h1>@yield('code')</h1>
                        <p>@yield('message')</p>
                        <div class="buttons-con">
                            <div class="action-link-wrap">
                                <a href="{{ \RalphJSmit\Livewire\Urls\Facades\Url::current() }}"
                                    class="link-button link-back-button">Close</a>
                                {{-- <button id="close" class="link-button link-back-button">Close</button> --}}
                                {{-- <a href="{{ route('home')}}" class="link-button">Go to Home Page</a> --}}
                            </div>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->


    </div>
    <!-- ./wrapper -->

    <!-- REQUIRED SCRIPTS -->
    @yield('scripts')
</body>

</html>
