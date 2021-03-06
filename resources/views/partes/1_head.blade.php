<head>
    <meta http-equiv="Content-Type" content="text/html;charset=ISO-8859-1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>@yield('title')</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon.png')}}">
    <link href="{{ asset('plugins/toastr/css/toastr.min.css')}}" rel="stylesheet">
    <!-- Custom Stylesheet -->
    <link rel="stylesheet" href="{{ asset('./plugins/highlightjs/styles/darkula.css')}}">
    <link href="{{ asset('plugins/sweetalert2/dist/sweetalert2.min.css')}}" rel="stylesheet">
    <link href="{{ asset('plugins/sweetalert/css/sweetalert.css')}}" rel="stylesheet">
    <link href="{{ asset('plugins/tables/css/datatable/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
    <link href="{{ asset('css/style.css')}}" rel="stylesheet">
        <!--ccs head por paginas-->
        @yield('css')

</head>
