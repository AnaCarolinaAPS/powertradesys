<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>@yield('titulo', 'PowerTrade.Py')</title>

        <!-- Bootstrap Css -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <style>

            div #cabecalho {
                border: 0;
            }

            #cabecalho td {
                font-family: Arial, Helvetica, sans-serif;
                font-size: 0.75em;
                padding: 0px;
            }

            #cabecalho .align-right {
                text-align: right;
                padding-right: 10px;
            }

            #cabecalho h3 {
                !important font-family: Arial, Helvetica, sans-serif;
                font-size: 1.3em;
                padding: 0px;
                margin: 0px 0px 0px 80px;
            }

            #customers {
                font-family: Arial, Helvetica, sans-serif;
                font-size: 0.75em;
                border-collapse: collapse;
                width: 100%;
            }

            #customers th {
                border: 1px solid #ddd;
                padding: 3px;
            }

            #customers td {
                border-left: 1px solid #ddd;
                border-right: 1px solid #ddd;
                border-top: none;
                border-bottom: none;
                padding: 3px;
            }

            #customers .align-center {
                text-align: center;
            }

            #customers .font-white {
                color: white;
            }

            #customers th {
                /* padding-top: 12px;
                padding-bottom: 12px; */
                text-align: left;
                color: black;
                /* background-color: #04AA6D; */
                /* color: white; */
            }

            #customers tr:last-child td {
                border-bottom: 1px solid #ddd; /* Adicionar borda inferior na última linha */
            }

            #customers .dados {
                border-bottom: 1px solid #ddd; /* Adicionar borda inferior na última linha */
            }
        </style>
    </head>

    <body>

        <!-- Begin page -->
        <div id="layout-wrapper">

            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->
            <div class="main-content">

                @yield('view')

            </div>
            <!-- end main content-->

        </div>
        <!-- END layout-wrapper -->

        <!-- JAVASCRIPT -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

    </body>

</html>
