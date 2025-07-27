<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ $setting->nama_perusahaan }} | @yield('title')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <link rel="icon" href="{{ url($setting->path_logo) }}" type="image/png">

    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{ asset('/AdminLTE-2/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
    {{-- <link href="/css/style.css" rel="stylesheet"> --}}
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('/AdminLTE-2/bower_components/font-awesome/css/font-awesome.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('/AdminLTE-2/dist/css/AdminLTE.min.css') }}">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="{{ asset('/AdminLTE-2/dist/css/skins/_all-skins.min.css') }}">
    <!-- DataTables -->
    <link rel="stylesheet"
        href="{{ asset('/AdminLTE-2/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    {{-- [if lt IE 9]> --}}
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    {{-- <![endif] --}}

    <!-- Google Font -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

    <!-- Custom Sidebar Responsive CSS -->
    <link rel="stylesheet" href="{{ asset('css/sidebar-responsive.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sidebar-override.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sidebar-animations.css') }}">

    <!-- Custom Navbar Responsive CSS -->
    <link rel="stylesheet" href="{{ asset('css/navbar-responsive.css') }}">

    <!-- Custom Table Modern CSS -->
    <link rel="stylesheet" href="{{ asset('css/table-modern.css') }}">

    <!-- Custom Page Title CSS -->
    <link rel="stylesheet" href="{{ asset('css/page-title.css') }}">

    <!-- Animate.css for smooth animations -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

    <!-- Custom Alert Modern CSS -->
    <link rel="stylesheet" href="{{ asset('css/alert-modern.css') }}">

    <!-- Universal Alert System CSS -->
    <link rel="stylesheet" href="{{ asset('css/universal-alerts.css') }}">

    <!-- Confirm Delete Override CSS -->
    <link rel="stylesheet" href="{{ asset('css/confirm-delete-override.css') }}">

    <!-- Toasty Modern CSS - Stable Layout -->
    <link rel="stylesheet" href="{{ asset('css/toasty-modern.css') }}">

    <!-- Modal Form Modern CSS -->
    <link rel="stylesheet" href="{{ asset('css/modal-form-modern.css') }}">

    <!-- Modal Form Enhanced CSS - Layout improvements -->
    <link rel="stylesheet" href="{{ asset('css/modal-form-enhanced.css') }}">

    <!-- Jasa Form Fix CSS - Dropdown and form styling fixes -->
    <link rel="stylesheet" href="{{ asset('css/jasa-form-fix.css') }}">

    <!-- DataTables Fix CSS - Minimal fixes untuk masalah spesifik -->
    <link rel="stylesheet" href="{{ asset('css/datatables-fix.css') }}">

    <!-- Toastify CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">

    @stack('css')
</head>

<body class="hold-transition skin-purple-light sidebar-mini">
    @include('sweetalert::alert', ['cdn' => 'https://cdn.jsdelivr.net/npm/sweetalert2@9'])
    <div class="wrapper">

        @includeIf('layouts.header')

        @includeIf('layouts.sidebar')

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1 class="page-title-@yield('page-identifier', 'default')">
                    @yield('title')
                </h1>
                <div class="page-identifier">@yield('page-identifier', 'PAGE')</div>
                <ol class="breadcrumb">
                    @section('breadcrumb')
                        <li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                    @show
                </ol>
            </section>

            <!-- Main content -->
            <section class="content">

                @yield('content')

            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        @includeIf('layouts.footer')
    </div>
    <!-- ./wrapper -->

    <!-- jQuery 3 -->
    <script src="{{ asset('AdminLTE-2/bower_components/jquery/dist/jquery.min.js') }}"></script>
    <!-- Bootstrap 3.3.7 -->
    <script src="{{ asset('AdminLTE-2/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <!-- Moment -->
    <script src="{{ asset('AdminLTE-2/bower_components/moment/min/moment.min.js') }}"></script>

    <!-- DataTables -->
    <script src="{{ asset('AdminLTE-2/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('AdminLTE-2/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('AdminLTE-2/dist/js/adminlte.min.js') }}"></script>
    <!-- Validator -->
    <script src="{{ asset('js/validator.min.js') }}"></script>

    <!-- Custom Alert Modern JS -->
    <script src="{{ asset('js/alert-modern.js') }}"></script>

    <!-- SweetAlert2 for Universal Alert System -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Toastify JS -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

    <!-- Universal Alert System JS -->
    <script src="{{ asset('js/universal-alerts.js') }}"></script>

    <!-- Enhanced Confirm Delete JS -->
    <script src="{{ asset('js/confirm-delete-enhanced.js') }}"></script>

    <!-- Toasty Stable JS - Layout Stable Toast System -->
    <script src="{{ asset('js/toasty-stable.js') }}"></script>

    <!-- Delete Function Override JS dengan Toasty - SOLUSI UTAMA -->
    <script src="{{ asset('js/delete-function-override-toasty.js') }}"></script>

    <!-- Modal Form Enhanced JS -->
    <script src="{{ asset('js/modal-form-enhanced.js') }}"></script>

    <!-- DataTables Fix JS - Minimal JavaScript untuk fixes -->
    <script src="{{ asset('js/datatables-fix.js') }}"></script>

    <!-- Jasa Global Functions JS - Ensure functions are globally accessible -->
    <script src="{{ asset('js/jasa-global-functions.js') }}"></script>

    <!-- Jasa Form Fix JS - Enhanced form submission and dropdown handling -->
    <script src="{{ asset('js/jasa-form-fix.js') }}"></script>

    <!-- Jasa Controller Debug JS - Debugging tools for data loading issues -->
    <script src="{{ asset('js/jasa-controller-debug.js') }}"></script>

    <!-- Jasa Table Display Fix JS - Fix for data not displaying in table -->
    <script src="{{ asset('js/jasa-table-display-fix.js') }}"></script>

    <!-- Enhanced Confirm Delete System -->
    <script src="{{ asset('js/enhanced-confirm-delete.js') }}"></script>

    <script>
        < script src = "{{ asset('js/datatable-features-restore.js') }}" >
    </script>

    <script>
        function preview(selector, temporaryFile, width = 200) {
            $(selector).empty();
            $(selector).append(`<img src="${window.URL.createObjectURL(temporaryFile)}" width="${width}">`);
        }

        // Initialize Universal Alert System dengan Toasty dan Modal Forms
        $(document).ready(function() {

            // Override semua toast error functions untuk menghindari konflik
            window.toastError = function() {
                return null;
            };
            window.showToastError = function() {
                return null;
            };
            window.showErrorToast = function() {
                return null;
            };

            // Override AJAX error handler yang mungkin menampilkan toast
            $(document).ajaxError(function(event, xhr, settings, thrownError) {
                // Tidak menampilkan toast error otomatis
                console.log('AJAX Error intercepted and suppressed:', xhr.status, thrownError);
            });
        });

        function showToastSuccess(message) {
            if (typeof Toastify !== 'undefined') {
                Toastify({
                    text: message,
                    duration: 3000,
                    close: true,
                    gravity: "top",
                    position: "right",
                    style: {
                        background: "linear-gradient(135deg, #27ae60, #2ecc71)",
                        borderRadius: "10px",
                        fontWeight: "500"
                    }
                }).showToast();
            }
        }

        function showToastError(message) {
            if (typeof Toastify !== 'undefined') {
                Toastify({
                    text: message,
                    duration: 5000,
                    close: true,
                    gravity: "top",
                    position: "right",
                    style: {
                        background: "linear-gradient(135deg, #e74c3c, #c0392b)",
                        borderRadius: "10px",
                        fontWeight: "500"
                    }
                }).showToast();
            }
        }

        function showToastInfo(message) {
            if (typeof Toastify !== 'undefined') {
                Toastify({
                    text: message,
                    duration: 2000,
                    close: true,
                    gravity: "top",
                    position: "right",
                    style: {
                        background: "linear-gradient(135deg, #3498db, #2980b9)",
                        borderRadius: "10px",
                        fontWeight: "500"
                    }
                }).showToast();
            }
        }

        function showToastLoading(message) {
            if (typeof Toastify !== 'undefined') {
                return Toastify({
                    text: `<i class="fas fa-spinner fa-spin" style="margin-right: 8px;"></i> ${message}`,
                    duration: -1,
                    close: false,
                    gravity: "top",
                    position: "center",
                    escapeMarkup: false,
                    style: {
                        background: "linear-gradient(135deg, #34495e, #2c3e50)",
                        borderRadius: "10px",
                        fontWeight: "500"
                    }
                }).showToast();
            }
        }

        function hideAllToasts() {
            if (typeof Toastify !== 'undefined') {
                document.querySelectorAll('.toastify').forEach(toast => {
                    toast.remove();
                });
            }
        }

        function alertSuccess(title, message) {
            showToastSuccess(message || title);
        }

        function alertError(title, message) {
            showToastError(message || title);
        }

        function alertWarning(title, message) {
            showToastInfo(message || title);
        }

        function alertInfo(title, message) {
            showToastInfo(message || title);
        }

        // function showCreateSuccess(message) {
        //     showToastSuccess(message || 'Data berhasil dibuat!');
        // }

        // function showUpdateSuccess(message) {
        //     showToastSuccess(message || 'Data berhasil diupdate!');
        // }

        // Logout confirmation function
        function confirmLogout() {
            Swal.fire({
                title: 'Konfirmasi Logout',
                text: 'Apakah Anda yakin ingin keluar dari sistem?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Keluar',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('logout-form').submit();
                }
            });
        }

    </script>

    <!-- Modern Confirmation System -->
    @include('components.confirmation-system')

    @stack('scripts')
</body>

</html>
