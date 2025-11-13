<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Expense Manager')</title>
    <link rel="shortcut icon" type="image/png" href="{{ ASSET_PATH }}template/assets/images/logos/seodashlogo.png" />
    <link rel="stylesheet" href="{{ ASSET_PATH }}template/assets/libs/simplebar/dist/simplebar.min.css">
    <link rel="stylesheet" href="{{ ASSET_PATH }}template/assets/css/styles.min.css" />
    <link href="https://cdn.datatables.net/v/bs5/dt-2.3.4/r-3.0.7/datatables.min.css" rel="stylesheet"
        integrity="sha384-RaJlMsTv+nhuWA/3SQzc3dPVUOKfEb08YW4YZsaNK3UNFUhjvLkn/SwJEfKSavGD" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
</head>

<body>
    <!--  Body Wrapper -->
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">
        <!-- Sidebar Start -->
        @include('layouts.aside')
        <!--  Sidebar End -->
        <!--  Main wrapper -->
        <div class="body-wrapper">
            <!--  Header Start -->
            <header class="app-header">
                @include('layouts.nav')
            </header>
            <!--  Header End -->
            <div class="container-fluid">
                @yield('content')
            </div>
        </div>
    </div>
    <script src="{{ ASSET_PATH }}template/assets/libs/jquery/dist/jquery.min.js"></script>
    <script src="{{ ASSET_PATH }}template/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ ASSET_PATH }}template/assets/libs/simplebar/dist/simplebar.js"></script>
    <script src="{{ ASSET_PATH }}template/assets/js/sidebarmenu.js"></script>
    <script src="{{ ASSET_PATH }}template/assets/js/app.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="https://cdn.datatables.net/v/bs5/dt-2.3.4/r-3.0.7/datatables.min.js"
        integrity="sha384-O4V7rOTTcSRflQBTMk8URAYWhGGEMgmmLFrqu3e83FQtze3vmndvrH3GcRdrfXRu" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script>
        function successAlert(message) {
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: message,
                timer: 2000,
                showConfirmButton: false
            });
        }

        function errorAlert(messages) {
            let errorMessages = '';
            if (typeof messages === 'string') {
                errorMessages = messages;
            } else {
                for (let field in messages) {
                    errorMessages += messages[field].join('<br>') + '<br>';
                }
            }

            Swal.fire({
                icon: 'error',
                title: 'Error',
                html: errorMessages,
                showConfirmButton: true
            });
        }
    </script>
    @stack('scripts')
</body>

</html>
