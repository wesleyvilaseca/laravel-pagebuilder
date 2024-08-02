<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description"
        content="Responsive sidebar template with sliding effect and dropdown menu based on bootstrap 3">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css">


    <!--css sidebar-->
    <link href="{{ asset('assets-admin/css/all.css') }}" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
        integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

    <!-- sweet alert-->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9.17.1/dist/sweetalert2.all.min.js"></script>

    <script src="{{ mix('js/sitebuilder-app.js') }}" defer></script>


    <title>{{ @$title ? $title : 'ACL' }}</title>

</head>

<body>

    <div id="side-menu" class="page-wrapper chiller-theme toggled">
        <a id="show-sidebar" class="btn btn-sm btn-dark" style="cursor:pointer;">
            <i class="fas fa-bars"></i>
        </a>

        @include('layouts.topbar')
        @include('layouts.sidemenu')

        <main class="page-content">
            <div class="container-fluid">
                @include('includes.alerts')
                @if (@isset($toptitle))
                    <h5>{{ $toptitle }}</h5>
                @endif
                @if (@isset($breadcrumb))
                    <div id="breadcrumb" class="mt-2">
                        <ol class="breadcrumb">
                            @foreach ($breadcrumb as $bread)
                                @if (@$bread['active'])
                                    <li class="breadcrumb-item active">
                                        {{ $bread['title'] }}
                                    </li>
                                @else
                                    <li class="breadcrumb-item">
                                        <a href="{{ $bread['route'] }}">{{ $bread['title'] }}</a>
                                    </li>
                                @endif
                            @endforeach
                        </ol>
                    </div>
                @endif

                @if (@isset($toptitle) || @isset($breadcrumb))
                    <hr>
                @endif

                @yield('content')

            </div>
        </main>
        <div class="modal"></div>
    </div>
    
    <!-- page-wrapper -->
  <!-- page-wrapper -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
      integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
      crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"
      integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy"
      crossorigin="anonymous"></script>

    <!--meus js-->
    <script src="{{ asset('assets-admin/js/plugins/sidebar/sidebar.js') }}"></script>

    @yield('js')

</body>

</html>