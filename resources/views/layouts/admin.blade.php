<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description"
        content="Responsive sidebar template with sliding effect and dropdown menu based on bootstrap 3">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>window.Laravel = { csrfToken: "{{ csrf_token() }}" }</script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css">


    <!--css sidebar-->
    <link href="{{ asset('assets-admin/css/all.css') }}" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
        integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

    <!-- sweet alert-->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9.17.1/dist/sweetalert2.all.min.js"></script>

    <title>{{ @$title ? $title : 'Festa do livro' }}</title>
    @routes
</head>

<body>
    <div id="app">
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
    </div>
    
  <!-- page-wrapper -->
  <script src="https://cdn.tiny.cloud/1/957xh1332e4cnda4415vyfh0mmu9546pr8viekcnj5q0g1jd/tinymce/5.8.0-111/tinymce.min.js"
  referrerpolicy="origin"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
      integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
      crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"
      integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy"
      crossorigin="anonymous"></script>

      <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.min.js"></script>

    <!--meus js-->
    <script src="{{ asset('assets-admin/js/plugins/sidebar/sidebar.js') }}"></script>
    <script src="{{ asset('assets-admin/js/helper.js') }}"></script>

    <script src="{{ mix('js/app.js') }}" defer></script>

    @yield('js')

    <script>
        $.extend(true, $.fn.dataTable.defaults, {
            language: {
                "sEmptyTable": "Nenhum dado disponível na tabela",
                "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ entradas",
                "sInfoEmpty": "Mostrando 0 até 0 de 0 entradas",
                "sInfoFiltered": "(filtrado de _MAX_ entradas totais)",
                "sLengthMenu": "Mostrar _MENU_ entradas",
                "sLoadingRecords": "Carregando...",
                "sProcessing": "Processando...",
                "sSearch": "Pesquisar:",
                "sZeroRecords": "Nenhum registro encontrado",
                "oPaginate": {
                    "sFirst": "Primeiro",
                    "sLast": "Último",
                    "sNext": "Próximo",
                    "sPrevious": "Anterior"
                },
                "oAria": {
                    "sSortAscending": ": Ativar para classificar a coluna em ordem crescente",
                    "sSortDescending": ": Ativar para classificar a coluna em ordem decrescente"
                }
            }
        });
    </script>
</body>

</html>