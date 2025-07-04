<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') - {{ config('app.name') }}</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Sweet Alert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <style>
        #sidebar {
            min-height: 100vh;
            width: 250px;
            transition: all 0.3s;
            background-color: #f8f9fa;
            border-right: 1px solid #dee2e6;
        }

        #content {
            width: calc(100% - 250px);
            min-height: 100vh;
            transition: all 0.3s;
        }

        .sidebar-link {
            padding: 10px 15px;
            color: #495057;
            text-decoration: none;
            display: block;
            transition: 0.3s;
            border-radius: 5px;
            margin: 2px 8px;
        }

        .sidebar-link:hover {
            background-color: #e9ecef;
            color: #212529;
        }

        .sidebar-link.active {
            background-color: #e9ecef;
            color: #0d6efd;
        }

        .nav-header {
            font-size: 0.8rem;
            text-transform: uppercase;
            color: #6c757d;
            padding: 10px 15px;
            margin-top: 10px;
            font-weight: 600;
        }

        .sidebar-brand {
            color: #212529;
            text-decoration: none;
        }

        .sidebar-divider {
            border-top: 1px solid #dee2e6;
            margin: 1rem 0;
        }

        @media (max-width: 768px) {
            #sidebar {
                margin-left: -250px;
            }

            #sidebar.active {
                margin-left: 0;
            }

            #content {
                width: 100%;
            }

            #content.active {
                width: calc(100% - 250px);
            }
        }
    </style>
</head>

<body class="bg-light">
    <div class="d-flex">
        <!-- Sidebar -->
        <div id="sidebar">
            <div class="p-3">
                <h4 class="text-center sidebar-brand font-bold"><i class="fa-solid fa-ticket me-1"></i> JelajahEvent
                </h4>
                <hr class="sidebar-divider">
                <div class="nav-header">Menu Utama</div>
                <a href="{{ route('admin.dashboard') }}"
                    class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                </a>
                <div class="nav-header">Manajemen Event</div>
                <a href="{{ route('admin.events.index') }}"
                    class="sidebar-link {{ request()->routeIs('admin.events.*') ? 'active' : '' }}">
                    <i class="fas fa-calendar-alt me-2"></i> Event
                </a>
                <div class="nav-header">Manajemen Kategori</div>
                <a href="{{ route('admin.categories.index') }}"
                    class="sidebar-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                    <i class="fas fa-tags me-2"></i> Kategori
                </a>
                <div class="nav-header">Transaksi</div>
                <a href="{{ route('admin.orders.index') }}"
                    class="sidebar-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                    <i class="fas fa-receipt me-2"></i> Pesanan
                </a>
                <a href="{{ route('admin.transactions.index') }}"
                    class="sidebar-link {{ request()->routeIs('admin.transactions.*') ? 'active' : '' }}">
                    <i class="fas fa-credit-card me-2"></i> Transaksi
                </a>
                <div class="nav-header">Laporan</div>
                <a href="{{ route('admin.reports.index') }}"
                    class="sidebar-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                    <i class="fas fa-file-alt me-2"></i> Laporan
                </a>
                <div class="nav-header">Pengaturan</div>
                <a href="{{ route('admin.users.index') }}"
                    class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i>
                    <span>Users</span>
                </a>

            </div>
        </div>

        <!-- Main Content -->
        <div id="content">
            <!-- Top Navbar -->
            <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
                <div class="container-fluid">
                    <button class="btn btn-link text-dark" id="menu-toggle">
                        <i class="fas fa-bars"></i>
                    </button>
                    <div class="ms-auto d-flex align-items-center">
                        <div class="dropdown">
                            <a class="nav-link dropdown-toggle text-dark" href="#" role="button"
                                data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle me-1"></i> {{ Auth::user()->name ?? 'Admin' }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i> Profil</a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button class="dropdown-item text-danger" type="submit">
                                            <i class="fas fa-sign-out-alt me-2"></i> Keluar
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Content -->
            <div class="container-fluid py-4">
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Menu toggle script
        document.getElementById('menu-toggle').addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('sidebar').classList.toggle('active');
            document.getElementById('content').classList.toggle('active');
        });

        // Sweet Alert untuk flash messages
        @if (session('success'))
            Swal.fire({
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                icon: 'success',
                confirmButtonText: 'OK',
                confirmButtonColor: '#3085d6'
            });
        @endif

        @if (session('error'))
            Swal.fire({
                title: 'Gagal!',
                text: "{{ session('error') }}",
                icon: 'error',
                confirmButtonText: 'OK',
                confirmButtonColor: '#d33'
            });
        @endif

        @if ($errors->any())
            Swal.fire({
                title: 'Error!',
                html: `@foreach ($errors->all() as $error)
                    <p class="mb-0">{{ $error }}</p>
                @endforeach`,
                icon: 'error',
                confirmButtonText: 'OK',
                confirmButtonColor: '#d33'
            });
        @endif

        // Sweet Alert untuk konfirmasi hapus
        function confirmDelete(event) {
            event.preventDefault();
            const form = event.target.closest('form');

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }
    </script>
    @livewireScripts
</body>

</html>
