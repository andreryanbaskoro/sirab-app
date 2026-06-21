<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SIRAB') | Sistem RAB Bangunan</title>

    <!-- GLOBAL MAINLY STYLES-->
    <link href="{{ asset('themes/assets/vendors/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />

    <!-- PLUGINS STYLES-->
    <link href="{{ asset('themes/assets/vendors/DataTables/datatables.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('themes/assets/vendors/select2/dist/css/select2.min.css') }}" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

    <!-- THEME STYLES-->
    <link href="{{ asset('themes/assets/css/main.min.css') }}" rel="stylesheet" />

    <style>
        /* Fix for buttons and badges contrast */
        .btn-warning, .badge-warning, .bg-warning {
            color: #212529 !important;
        }
        .btn-info, .badge-info, .bg-info {
            color: #ffffff !important;
            text-shadow: 0px 1px 1px rgba(0,0,0,0.3); /* Tambahkan bayangan hitam sedikit agar teks putih terbaca di biru muda */
        }
        .btn-primary, .badge-primary, .bg-primary {
            color: #ffffff !important;
        }
        
        .sidebar-item-icon {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        .dropdown-menu-notif {
            width: 350px;
            max-height: 400px;
            overflow-y: auto;
        }

        .notif-item {
            padding: 10px 15px;
            border-bottom: 1px solid #f1f1f1;
            display: block;
            color: #333;
        }

        .notif-item:hover {
            background-color: #f9f9f9;
            text-decoration: none;
        }

        .notif-item.unread {
            background-color: #e9f7ff;
        }

        .notif-title {
            font-weight: bold;
            font-size: 13px;
            margin-bottom: 3px;
        }

        .notif-body {
            font-size: 12px;
            color: #666;
        }

        .notif-time {
            font-size: 11px;
            color: #999;
            margin-top: 5px;
            display: block;
        }
    </style>
    @stack('css')
</head>

<body class="fixed-navbar">
    <div class="page-wrapper">
        <header class="header">
            <div class="page-brand">
                <a class="link" href="{{ url('/home') }}">
                    <span class="brand">SI<span class="brand-tip">RAB</span></span>
                    <span class="brand-mini">SR</span>
                </a>
            </div>
            <div class="flexbox flex-1">
                <ul class="nav navbar-toolbar">
                    <li><a class="nav-link sidebar-toggler js-sidebar-toggler"><i class="fa-solid fa-bars"></i></a></li>
                </ul>

                <ul class="nav navbar-toolbar">
                    <!-- Notifications Dropdown -->
                    @php
                    $unreadNotifs = Auth::user() ? Auth::user()->unreadNotifications : collect();
                    @endphp
                    <li class="dropdown dropdown-notification">
                        <a class="nav-link dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                            <i class="fa-regular fa-bell"></i>
                            @if($unreadNotifs->count() > 0)
                            <span class="badge badge-primary envelope-badge">{{ $unreadNotifs->count() }}</span>
                            @endif
                        </a>
                        <ul class="dropdown-menu dropdown-menu-right dropdown-menu-notif">
                            <li class="dropdown-header d-flex justify-content-between align-items-center">
                                <strong>Notifikasi</strong>
                                @if($unreadNotifs->count() > 0)
                                <form action="{{ route('notifikasi.markAllRead') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-link btn-xs p-0 text-muted">Tandai sudah dibaca</button>
                                </form>
                                @endif
                            </li>
                            <li>
                                @forelse($unreadNotifs->take(5) as $notif)
                                <a href="{{ route('notifikasi.read', $notif->id) }}" class="notif-item unread">
                                    <div class="notif-title">{{ $notif->data['title'] ?? 'Pemberitahuan' }}</div>
                                    <div class="notif-body">{{ $notif->data['message'] ?? '' }}</div>
                                    <span class="notif-time"><i class="fa-regular fa-clock"></i> {{ $notif->created_at->diffForHumans() }}</span>
                                </a>
                                @empty
                                <div class="p-3 text-center text-muted">Tidak ada notifikasi baru</div>
                                @endforelse
                            </li>
                            <li class="dropdown-footer text-center p-2 bg-light">
                                <a href="{{ route('notifikasi.index') }}">Lihat Semua Notifikasi</a>
                            </li>
                        </ul>
                    </li>

                    <li class="dropdown dropdown-user">
                        <a class="nav-link dropdown-toggle link" data-toggle="dropdown">
                            @php
                                $fotoUrl = Auth::user()->foto_profil;
                            @endphp
                            <img src="{{ $fotoUrl }}" alt="Foto Profil" />
                            <span></span>{{ Auth::user()->name }}<i class="fa-solid fa-angle-down ml-2"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="{{ Auth::user()->hasRole('admin_pu') ? '#' : (Auth::user()->hasRole('kepala_tukang') ? route('tukang.profil') : route('konsumen.profil')) }}">
                                <i class="fa-solid fa-user dropdown-icon"></i>Profil Saya
                            </a>
                            <li class="dropdown-divider"></li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger"><i class="fa-solid fa-power-off dropdown-icon"></i>Keluar</button>
                            </form>
                        </ul>
                    </li>
                </ul>
            </div>
        </header>

        <nav class="page-sidebar" id="sidebar">
            <div id="sidebar-collapse">
                <div class="admin-block d-flex">
                    <div>
                        <img src="{{ $fotoUrl }}" width="45px" class="rounded-circle" style="aspect-ratio:1/1; object-fit:cover;" />
                    </div>
                    <div class="admin-info ml-2">
                        <div class="font-strong" style="line-height:1.2">{{ Auth::user()->name }}</div>
                        <small class="text-muted">{{ str_replace('_', ' ', strtoupper(Auth::user()->role)) }}</small>
                    </div>
                </div>

                <ul class="side-menu metismenu">
                    @role('admin_pu')
                    <li>
                        <a class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                            <i class="sidebar-item-icon fa-solid fa-gauge"></i><span class="nav-label">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a class="{{ request()->routeIs('admin.profil.*') ? 'active' : '' }}" href="{{ route('admin.profil') }}">
                            <i class="sidebar-item-icon fa-solid fa-user"></i><span class="nav-label">Profil</span>
                        </a>
                    </li>
                    <li class="heading">MASTER DATA USER</li>
                    <li>
                        <a class="{{ request()->routeIs('admin.konsumen.*') ? 'active' : '' }}" href="{{ url('/admin/konsumen') }}">
                            <i class="sidebar-item-icon fa-solid fa-users"></i><span class="nav-label">Konsumen</span>
                        </a>
                    </li>
                    <li>
                        <a class="{{ request()->routeIs('admin.tukang.*') ? 'active' : '' }}" href="{{ url('/admin/tukang') }}">
                            <i class="sidebar-item-icon fa fa-user"></i>
                            <span class="nav-label">Kepala Tukang</span>
                        </a>
                    </li>
                    <li class="heading">MASTER DATA BANGUNAN</li>
                    <li>
                        <a class="{{ request()->routeIs('admin.tipe-rumah.*') ? 'active' : '' }}" href="{{ url('/admin/tipe-rumah') }}">
                            <i class="sidebar-item-icon fa-solid fa-house-chimney"></i><span class="nav-label">Tipe Rumah</span>
                        </a>
                    </li>
                    <li>
                        <a class="{{ request()->routeIs('admin.material.*') ? 'active' : '' }}" href="{{ url('/admin/material') }}">
                            <i class="sidebar-item-icon fa-solid fa-cubes"></i><span class="nav-label">Material</span>
                        </a>
                    </li>
                    <li>
                        <a class="{{ request()->routeIs('admin.kategori-pekerjaan.*') ? 'active' : '' }}" href="{{ route('admin.kategori-pekerjaan.index') }}">
                            <i class="sidebar-item-icon fa-solid fa-layer-group"></i><span class="nav-label">Kategori Pekerjaan</span>
                        </a>
                    </li>
                    <li>
                        <a class="{{ request()->routeIs('admin.pekerjaan.*') ? 'active' : '' }}" href="{{ route('admin.pekerjaan.index') }}">
                            <i class="sidebar-item-icon fa-solid fa-helmet-safety"></i><span class="nav-label">Pekerjaan</span>
                        </a>
                    </li>
                    <li>
                        <a class="{{ request()->routeIs('admin.harga-jasa-tukang.*') ? 'active' : '' }}" href="{{ url('/admin/harga-jasa-tukang') }}">
                            <i class="sidebar-item-icon fa-solid fa-hand-holding-dollar"></i><span class="nav-label">Harga Jasa Tukang</span>
                        </a>
                    </li>
                    <li class="heading">TRANSAKSI & LAPORAN</li>
                    <li>
                        <a class="{{ request()->routeIs('admin.permintaan.*') ? 'active' : '' }}" href="{{ url('/admin/permintaan') }}">
                            <i class="sidebar-item-icon fa-solid fa-file-circle-plus"></i><span class="nav-label">Permintaan</span>
                        </a>
                    </li>
                    <li>
                        <a class="{{ request()->routeIs('admin.rab.*') ? 'active' : '' }}" href="{{ url('/admin/rab') }}">
                            <i class="sidebar-item-icon fa-solid fa-file-invoice-dollar"></i><span class="nav-label">RAB</span>
                        </a>
                    </li>
                    <li>
                        <a class="{{ request()->routeIs('admin.kontrak.*') ? 'active' : '' }}" href="{{ url('/admin/kontrak') }}">
                            <i class="sidebar-item-icon fa-solid fa-file-signature"></i><span class="nav-label">Kontrak</span>
                        </a>
                    </li>
                    <li>
                        <a class="{{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}" href="{{ url('/admin/laporan') }}">
                            <i class="sidebar-item-icon fa-solid fa-chart-column"></i><span class="nav-label">Laporan</span>
                        </a>
                    </li>
                    <li>
                        <a class="{{ request()->routeIs('notifikasi.index') ? 'active' : '' }}" href="{{ route('notifikasi.index') }}">
                            <i class="sidebar-item-icon fa-solid fa-bell"></i><span class="nav-label">Notifikasi</span>
                        </a>
                    </li>
                    @endrole

                    @role('kepala_tukang')
                    <li>
                        <a class="{{ request()->routeIs('tukang.dashboard') ? 'active' : '' }}" href="{{ route('tukang.dashboard') }}">
                            <i class="sidebar-item-icon fa-solid fa-gauge"></i><span class="nav-label">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a class="{{ request()->routeIs('tukang.profil.*') ? 'active' : '' }}" href="{{ route('tukang.profil') }}">
                            <i class="sidebar-item-icon fa-solid fa-user"></i><span class="nav-label">Profil</span>
                        </a>
                    </li>
                    <li>
                        <a class="{{ request()->routeIs('tukang.permintaan.*') ? 'active' : '' }}" href="{{ route('tukang.permintaan.index') }}">
                            <i class="sidebar-item-icon fa-solid fa-file-circle-plus"></i><span class="nav-label">Permintaan Masuk</span>
                        </a>
                    </li>
                    <li>
                        <a class="{{ request()->routeIs('tukang.anggaran.*') ? 'active' : '' }}" href="{{ route('tukang.anggaran.index') }}">
                            <i class="sidebar-item-icon fa-solid fa-money-check-dollar"></i><span class="nav-label">Data Anggaran</span>
                        </a>
                    </li>

                    <li>
                        <a class="{{ request()->routeIs('tukang.rab.index') ? 'active' : '' }}" href="{{ route('tukang.rab.index') }}">
                            <i class="sidebar-item-icon fa-solid fa-file-invoice-dollar"></i><span class="nav-label">Hasil RAB</span>
                        </a>
                    </li>
                    <li>
                        <a class="{{ request()->routeIs('tukang.proyek.*') ? 'active' : '' }}" href="{{ route('tukang.proyek.index') }}">
                            <i class="sidebar-item-icon fa-solid fa-person-digging"></i><span class="nav-label">Proyek Berjalan</span>
                        </a>
                    </li>
                    <li>
                        <a class="{{ request()->routeIs('tukang.riwayat.*') ? 'active' : '' }}" href="{{ url('/tukang/riwayat') }}">
                            <i class="sidebar-item-icon fa-solid fa-clock-rotate-left"></i><span class="nav-label">Riwayat</span>
                        </a>
                    </li>
                    <li>
                        <a class="{{ request()->routeIs('notifikasi.index') ? 'active' : '' }}" href="{{ route('notifikasi.index') }}">
                            <i class="sidebar-item-icon fa-solid fa-bell"></i><span class="nav-label">Notifikasi</span>
                        </a>
                    </li>
                    @endrole

                    @role('konsumen')
                    <li>
                        <a class="{{ request()->routeIs('konsumen.dashboard') ? 'active' : '' }}" href="{{ route('konsumen.dashboard') }}">
                            <i class="sidebar-item-icon fa-solid fa-gauge"></i><span class="nav-label">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a class="{{ request()->routeIs('konsumen.profil.*') ? 'active' : '' }}" href="{{ route('konsumen.profil') }}">
                            <i class="sidebar-item-icon fa-solid fa-user"></i><span class="nav-label">Profil</span>
                        </a>
                    </li>
                    <li>
                        <a class="{{ request()->routeIs('konsumen.cari-tukang.*') ? 'active' : '' }}" href="{{ route('konsumen.cari-tukang') }}">
                            <i class="sidebar-item-icon fa-solid fa-users-viewfinder"></i><span class="nav-label">Daftar Tukang</span>
                        </a>
                    </li>
                    <li>
                        <a class="{{ request()->routeIs('konsumen.permintaan.*') ? 'active' : '' }}" href="{{ route('konsumen.permintaan.index') }}">
                            <i class="sidebar-item-icon fa-solid fa-list-check"></i><span class="nav-label">Permintaan</span>
                        </a>
                    </li>
                    <li>
                        <a class="{{ request()->routeIs('konsumen.pembiayaan.*') ? 'active' : '' }}" href="{{ url('/konsumen/pembiayaan') }}">
                            <i class="sidebar-item-icon fa-solid fa-wallet"></i><span class="nav-label">Pembiayaan/RAB</span>
                        </a>
                    </li>
                    <li>
                        <a class="{{ request()->routeIs('konsumen.proyek.*') ? 'active' : '' }}" href="{{ route('konsumen.proyek.index') }}">
                            <i class="sidebar-item-icon fa-solid fa-person-digging"></i><span class="nav-label">Proyek Berjalan</span>
                        </a>
                    </li>
                    <li>
                        <a class="{{ request()->routeIs('konsumen.riwayat.*') ? 'active' : '' }}" href="{{ url('/konsumen/riwayat') }}">
                            <i class="sidebar-item-icon fa-solid fa-clock-rotate-left"></i><span class="nav-label">Riwayat</span>
                        </a>
                    </li>
                    <li>
                        <a class="{{ request()->routeIs('notifikasi.index') ? 'active' : '' }}" href="{{ route('notifikasi.index') }}">
                            <i class="sidebar-item-icon fa-solid fa-bell"></i><span class="nav-label">Notifikasi</span>
                        </a>
                    </li>
                    @endrole
                </ul>
            </div>
        </nav>

        <div class="content-wrapper">
            <div class="page-content fade-in-up">
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <i class="fa-solid fa-circle-check mr-2"></i>{{ session('success') }}
                </div>
                @endif
                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <i class="fa-solid fa-triangle-exclamation mr-2"></i>{{ session('error') }}
                </div>
                @endif

                @yield('content')
            </div>
            <footer class="page-footer">
                <div class="font-13">© 2026 <b>ARB</b>. All Rights Reserved.</div>
                <div class="to-top"><i class="fa-solid fa-angle-up"></i></div>
            </footer>
        </div>
    </div>

    <script src="{{ asset('themes/assets/vendors/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('themes/assets/vendors/popper.js/dist/umd/popper.min.js') }}"></script>
    <script src="{{ asset('themes/assets/vendors/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('themes/assets/vendors/metisMenu/dist/metisMenu.min.js') }}"></script>
    <script src="{{ asset('themes/assets/vendors/jquery-slimscroll/jquery.slimscroll.min.js') }}"></script>

    <script src="{{ asset('themes/assets/vendors/DataTables/datatables.min.js') }}"></script>
    <script src="{{ asset('themes/assets/vendors/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="{{ asset('themes/assets/js/app.min.js') }}"></script>

    <x-sweetalert />

    @stack('js')
</body>

</html>