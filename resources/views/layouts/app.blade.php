<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Perpustakaan')</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="{{ asset('template/css/styles.css') }}" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Playfair Display', serif;
        }

        /* ===== NAVBAR (ATAS) ===== */
        .sb-topnav {
            background-color: #5a3e2b !important;
        }

        /* ===== SIDEBAR ===== */
        .sb-sidenav-dark {
            background-color: #3e2c23 !important;
        }

        /* ===== TEXT MENU ===== */
        .sb-sidenav-dark .nav-link {
            color: #e6d3b3 !important;
        }

        /* ===== HOVER MENU ===== */
        .sb-sidenav-dark .nav-link:hover {
            background-color: #6b4f3a !important;
            color: #fff !important;
        }

        /* ===== ACTIVE MENU ===== */
        .sb-sidenav-dark .nav-link.active {
            background-color: #8b6b4a !important;
            color: #fff !important;
        }

        /* ===== ICON ===== */
        .sb-sidenav-dark .sb-nav-link-icon {
            color: #d2b48c !important;
        }

        /* ===== DROPDOWN NAVBAR ===== */
        .navbar-dark .navbar-nav .nav-link {
            color: #f5e6ca;
        }

        /* ===== BUTTON TOGGLE ===== */
        #sidebarToggle {
            color: #f5e6ca;
        }

        /* ===== BACKGROUND UTAMA ===== */
        body {
            background-color: #f4ecd8;
        }

        #layoutSidenav_content {
            background-color: #f4ecd8;
        }

        /* ===== CARD ===== */
        .card {
            background-color: #fff8e7;
            border: 1px solid #d2b48c;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(60, 40, 20, 0.2);
        }

        .card-header {
            background-color: #8b6b4a;
            color: #fff;
            font-weight: bold;
        }

        /* ===== INPUT ===== */
        .form-control {
            background-color: #fffdf5;
            border: 1px solid #cbb89d;
        }

        .form-control:focus {
            border-color: #8b6b4a;
            box-shadow: 0 0 5px rgba(139, 107, 74, 0.5);
        }

        .btn-primary {
            background-color: #6b4f3a;
            border: none;
        }

        .btn-primary:hover {
            background-color: #8b6b4a;
        }

        body {
            background: url('https://www.transparenttextures.com/patterns/aged-paper.png');
            background-color: #f4ecd8;
        }

        .datatable-top {
            display: flex;
            justify-content: flex-start;
        }

        /* ===== SIDEBAR FOOTER (BARU) ===== */
        .sb-sidenav-footer {
            background-color: #2b1e17 !important;
            color: #cbb89d;
            padding: 14px 16px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 12px;
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background-color: #6b4226;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 600;
            color: #ecdcc8;
            flex-shrink: 0;
        }

        .user-name {
            font-size: 13px;
            font-weight: 600;
            color: #e6d3b3;
            line-height: 1.3;
        }

        .user-role {
            font-size: 11px;
            color: #a0693a;
        }

        .footer-actions {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .btn-sidebar {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 7px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 400;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.2s;
            border: none;
            width: 100%;
            font-family: 'Playfair Display', serif;
        }

        .btn-profile {
            background: rgba(255, 255, 255, 0.07);
            color: #e6d3b3;
        }

        .btn-profile:hover {
            background: rgba(255, 255, 255, 0.13);
            color: #fff;
        }

        .btn-logout {
            background: rgba(180, 60, 40, 0.25);
            color: #f5a49a;
        }

        .btn-logout:hover {
            background: rgba(180, 60, 40, 0.38);
            color: #fff;
        }

        /* ===== SECTION LABEL MENU (BARU) ===== */
        .nav-section-label {
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: #a0693a;
            padding: 14px 16px 4px;
        }

        .nav-section-divider {
            border: none;
            border-top: 1px solid rgba(255, 255, 255, 0.07);
            margin: 6px 0;
        }
    </style>
</head>

<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <button class="btn btn-link btn-sm sidebar-toggle-btn" style="margin-left: 7px;" id="sidebarToggle">
            <i class="fa-solid fa-book-open"></i>
        </button>
        <a class="navbar-brand ps-1" href="{{ route('member.home') }}">Perpustakaan</a>

        <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
        </form>
    </nav>

    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">

                <div class="sb-sidenav-menu">
                    <div class="nav flex-column">

                        @role('admin')
                            <div class="nav-section-label">Menu</div>

                            <a class="nav-link" href="{{ route('member.home') }}">
                                <div class="sb-nav-link-icon"><i class="fa-solid fa-home"></i></div>
                                Home
                            </a>
                            <a class="nav-link" href="{{ route('borrowings.index') }}">
                                <div class="sb-nav-link-icon"><i class="fa-brands fa-stack-overflow"></i></div>
                                Peminjaman
                            </a>
                            <a class="nav-link" href="{{ route('fines.index') }}">
                                <div class="sb-nav-link-icon"><i class="fa-solid fa-money-bill"></i></div>
                                Denda
                            </a>

                            <hr class="nav-section-divider">
                            <div class="nav-section-label">Kelola</div>

                            <a class="nav-link" href="{{ route('categories.index') }}">
                                <div class="sb-nav-link-icon"><i class="fas fa-layer-group"></i></div>
                                Kategori
                            </a>
                            <a class="nav-link" href="{{ route('books.index') }}">
                                <div class="sb-nav-link-icon"><i class="fas fa-book"></i></div>
                                Buku
                            </a>
                            <a class="nav-link" href="{{ route('members.index') }}">
                                <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                                Member
                            </a>
                        @endrole

                        @role('member')
                            <div class="nav-section-label">Menu</div>

                            <a class="nav-link" href="{{ route('member.home') }}">
                                <div class="sb-nav-link-icon"><i class="fa-solid fa-home"></i></div>
                                Home
                            </a>
                            <a class="nav-link" href="{{ route('borrowings.index') }}">
                                <div class="sb-nav-link-icon"><i class="fa-solid fa-clock"></i></div>
                                Riwayat
                            </a>
                            <a class="nav-link" href="{{ route('fines.index') }}">
                                <div class="sb-nav-link-icon"><i class="fa-solid fa-money-bill"></i></div>
                                Denda
                            </a>
                        @endrole

                    </div>
                </div>

                <!-- FOOTER SIDEBAR (BARU) -->
                <div class="sb-sidenav-footer">
                    <div class="user-info">
                        <div class="user-avatar">
                            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                        </div>
                        <div>
                            <div class="user-name">{{ auth()->user()->name }}</div>
                            <div class="user-role">{{ auth()->user()->getRoleNames()->first() }}</div>
                        </div>
                    </div>
                    <div class="footer-actions">
                        <a href="{{ route('profile.edit') }}" class="btn-sidebar btn-profile">
                            <i class="fas fa-user" style="font-size:12px;"></i> Profil
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn-sidebar btn-logout">
                                <i class="fas fa-sign-out-alt" style="font-size:12px;"></i> Keluar
                            </button>
                        </form>
                    </div>
                </div>

            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>
                @yield('content')
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous">
    </script>
    <script src="{{ asset('template/js/scripts.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script src="{{ asset('template/assets/demo/chart-area-demo.js') }}"></script>
    <script src="{{ asset('template/assets/demo/chart-bar-demo.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"
        crossorigin="anonymous"></script>
    <script src="{{ asset('template/js/datatables-simple-demo.js') }}"></script>
</body>

</html>
