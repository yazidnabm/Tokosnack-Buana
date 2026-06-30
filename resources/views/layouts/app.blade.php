<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard') | Toko Snack Bu Ana</title>

    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

    <style>
        :root {
            --primary-color: #28a745;
            --secondary-color: #20c997;
        }

        body {
            font-family: 'Public Sans', sans-serif;
            background-color: #f8f9fa;
            color: #444;
        }

        /* Navbar Styling */
        .navbar {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)) !important;
            padding: 0.8rem 1rem;
        }

        .navbar-brand {
            font-size: 1.25rem;
            letter-spacing: -0.5px;
        }

        .navbar-brand small {
            display: block;
            font-size: 10px;
            font-weight: 400;
            text-transform: uppercase;
            letter-spacing: 1px;
            opacity: 0.9;
            margin-top: -2px;
        }

        .nav-link {
            font-weight: 500;
            padding: 0.5rem 1rem !important;
            transition: all 0.3s ease;
            border-radius: 8px;
            margin: 0 2px;
            display: flex;
            align-items: center;
        }

        .nav-link i {
            font-size: 1.1rem;
            margin-right: 6px;
        }

        .nav-link:hover {
            background: rgba(255, 255, 255, 0.15);
        }

        .nav-link.active {
            background: rgba(255, 255, 255, 0.25) !important;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        /* Card Customization */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            margin-bottom: 1.5rem;
        }

        .btn-logout {
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            transition: all 0.3s;
        }

        .btn-logout:hover {
            background: #dc3545;
            border-color: #dc3545;
            color: white;
        }

        /* Container adjustment */
        .content-wrapper {
            padding-bottom: 3rem;
        }
    </style>
    @yield('page-style')
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold d-flex align-items-center" href="/dashboard">
                <div class="bg-white rounded-circle d-flex align-items-center justify-content-center me-2"
                    style="width: 35px; height: 35px;">
                    <i class="bx bx-store text-success fs-4"></i>
                </div>
                <div>
                    Toko Snack Bu Ana
                    <small>Sistem Arus Kas</small>
                </div>
            </a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarApp">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarApp">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}" href="/dashboard">
                            <i class="bx bxs-dashboard"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('kas-masuk*') ? 'active' : '' }}" href="/kas-masuk">
                            <i class="bx bx-trending-up"></i> Kas Masuk
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('kas-keluar*') ? 'active' : '' }}" href="/kas-keluar">
                            <i class="bx bx-trending-down"></i> Kas Keluar
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('laporan*') ? 'active' : '' }}" href="/laporan">
                            <i class="bx bxs-file-pdf"></i> Laporan
                        </a>
                    </li>
                </ul>

                <div class="d-flex align-items-center">
                    <div class="text-white me-3 d-none d-lg-block text-end
