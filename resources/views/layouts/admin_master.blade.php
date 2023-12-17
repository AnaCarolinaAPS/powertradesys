<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>@yield('titulo', 'PowerTrade.Py')</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
        <meta content="Themesdesign" name="author" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{ asset('backend/assets/images/favicon.ico') }}">

        <!-- jquery.vectormap css -->
        <link href="{{ asset('backend/assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.css') }}" rel="stylesheet" type="text/css" />

        <!-- DataTables -->
        <link href="{{ asset('backend/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('backend/assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('backend/assets/libs/datatables.net-select-bs4/css/select.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />

        <!-- Responsive datatable examples -->
        <link href="{{ asset('backend/assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />

        {{-- TOASTR --}}
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

        {{-- Selectpicker --}}
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">

        <!-- Bootstrap Css -->
        <link href="{{ asset('backend/assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
        <link href="{{ asset('backend/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
        <!-- App Css-->
        <link href="{{ asset('backend/assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
    </head>

    <body data-topbar="dark">

    <!-- <body data-layout="horizontal" data-topbar="dark"> -->

        <!-- Begin page -->
        <div id="layout-wrapper">

            @include('layouts.body.header')

            <!-- ========== Left Sidebar Start ========== -->
            @include('layouts.body.sidebar')
            <!-- Left Sidebar End -->

            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->
            <div class="main-content">

                @yield('admin')

                @include('layouts.body.footer')

            </div>
            <!-- end main content-->

        </div>
        <!-- END layout-wrapper -->

        <!-- JAVASCRIPT -->
        <script src="{{ asset('backend/assets/libs/jquery/jquery.min.js') }}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
        <!-- <script src="{{ asset('backend/assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script> -->
        <script src="{{ asset('backend/assets/libs/metismenu/metisMenu.min.js') }}"></script>
        <script src="{{ asset('backend/assets/libs/simplebar/simplebar.min.js') }}"></script>
        <script src="{{ asset('backend/assets/libs/node-waves/waves.min.js') }}"></script>

        <!-- apexcharts -->
        {{-- <script src="{{ asset('backend/assets/libs/apexcharts/apexcharts.min.js') }}"></script> --}}

        <!-- jquery.vectormap map -->
        <script src="{{ asset('backend/assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.min.js') }}"></script>
        <script src="{{ asset('backend/assets/libs/admin-resources/jquery.vectormap/maps/jquery-jvectormap-us-merc-en.js') }}"></script>

        <!-- Required datatable js -->
        <script src="{{ asset('backend/assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('backend/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>

        <!-- Buttons examples -->
        <script src="{{ asset('backend/assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
        <script src="{{ asset('backend/assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js') }}"></script>
        <script src="{{ asset('backend/assets/libs/jszip/jszip.min.js') }}"></script>
        <script src="{{ asset('backend/assets/libs/pdfmake/build/pdfmake.min.js') }}"></script>
        <script src="{{ asset('backend/assets/libs/pdfmake/build/vfs_fonts.js') }}"></script>
        <script src="{{ asset('backend/assets/libs/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
        <script src="{{ asset('backend/assets/libs/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
        <script src="{{ asset('backend/assets/libs/datatables.net-buttons/js/buttons.colVis.min.js') }}"></script>
        <script src="{{ asset('backend/assets/libs/datatables.net-keytable/js/dataTables.keyTable.min.js') }}"></script>
        <script src="{{ asset('backend/assets/libs/datatables.net-select/js/dataTables.select.min.js') }}"></script>

        <!-- Responsive examples -->
        <script src="{{ asset('backend/assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
        <script src="{{ asset('backend/assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>

        <script src="{{ asset('backend/assets/js/pages/dashboard.init.js') }}"></script>

        <!-- Datatable init js -->
        {{-- <script src="{{ asset('backend/assets/js/pages/datatables.init.js') }}"></script> --}}

        <!-- TOAST -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

        <!-- Select-picker -->
        <!-- Latest compiled and minified JavaScript -->
        {{-- <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script> --}}
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>

        <!-- App js -->
        <script src="{{ asset('backend/assets/js/app.js') }}"></script>

        <!-- Adicione o script JavaScript para exibir os toasts -->
        <script>
            @if(Session::has('toastr'))
                var toastrData = {!! json_encode(Session::get('toastr')) !!};
                // Defina a opção showMethod como 'slideDown'
                toastr.options.showMethod = 'slideDown';
                toastr.options.progressBar = true;
                toastr[toastrData.type](toastrData.message, toastrData.title);
            @endif
            // To style all selects
            $('select').selectpicker();

            // $(document).ready(function(){$("#datatable").DataTable({language:{paginate:{previous:"<i class='mdi mdi-chevron-left'>",next:"<i class='mdi mdi-chevron-right'>"}},drawCallback:function(){$(".dataTables_paginate > .pagination").addClass("pagination-rounded")}});var a=$("#datatable-buttons").DataTable({lengthChange:!1,language:{paginate:{previous:"<i class='mdi mdi-chevron-left'>",next:"<i class='mdi mdi-chevron-right'>"}},drawCallback:function(){$(".dataTables_paginate > .pagination").addClass("pagination-rounded")},buttons:["copy","excel","pdf"]});a.buttons().container().appendTo("#datatable-buttons_wrapper .col-md-6:eq(0)"),$(".dataTables_length select").addClass("form-select form-select-sm"),$("#selection-datatable").DataTable({select:{style:"multi"},language:{paginate:{previous:"<i class='mdi mdi-chevron-left'>",next:"<i class='mdi mdi-chevron-right'>"}},drawCallback:function(){$(".dataTables_paginate > .pagination").addClass("pagination-rounded")}}),$("#key-datatable").DataTable({keys:!0,language:{paginate:{previous:"<i class='mdi mdi-chevron-left'>",next:"<i class='mdi mdi-chevron-right'>"}},drawCallback:function(){$(".dataTables_paginate > .pagination").addClass("pagination-rounded")}}),a.buttons().container().appendTo("#datatable-buttons_wrapper .col-md-6:eq(0)"),$(".dataTables_length select").addClass("form-select form-select-sm"),$("#alternative-page-datatable").DataTable({pagingType:"full_numbers",drawCallback:function(){$(".dataTables_paginate > .pagination").addClass("pagination-rounded"),$(".dataTables_length select").addClass("form-select form-select-sm")}}),$("#scroll-vertical-datatable").DataTable({scrollY:"350px",scrollCollapse:!0,paging:!1,language:{paginate:{previous:"<i class='mdi mdi-chevron-left'>",next:"<i class='mdi mdi-chevron-right'>"}},drawCallback:function(){$(".dataTables_paginate > .pagination").addClass("pagination-rounded")}}),$("#complex-header-datatable").DataTable({language:{paginate:{previous:"<i class='mdi mdi-chevron-left'>",next:"<i class='mdi mdi-chevron-right'>"}},drawCallback:function(){$(".dataTables_paginate > .pagination").addClass("pagination-rounded"),$(".dataTables_length select").addClass("form-select form-select-sm")},columnDefs:[{visible:!1,targets:-1}]}),$("#state-saving-datatable").DataTable({stateSave:!0,language:{paginate:{previous:"<i class='mdi mdi-chevron-left'>",next:"<i class='mdi mdi-chevron-right'>"}},drawCallback:function(){$(".dataTables_paginate > .pagination").addClass("pagination-rounded"),$(".dataTables_length select").addClass("form-select form-select-sm")}})});
            $(document).ready(function() {
                function initializeDataTableWithButtons(idName) {
                    var dataTable = $("#" + idName).DataTable({
                        lengthChange: false,
                        language: {
                            paginate: {
                                previous: "<i class='mdi mdi-chevron-left'>",
                                next: "<i class='mdi mdi-chevron-right'>"
                            }
                        },
                        drawCallback: function() {
                            $(".dataTables_paginate > .pagination").addClass("pagination-rounded");
                        },
                        buttons: ["copy", "excel", "pdf"]
                    });

                    dataTable.buttons().container().appendTo($("#" + idName + '_wrapper .col-md-6:eq(0)'));
                    $(".dataTables_length select").addClass("form-select form-select-sm");
                }

                initializeDataTableWithButtons("datatable-buttons");
                initializeDataTableWithButtons("datatable-totals");
            });
        </script>
    </body>

</html>
