<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - Admin Panel</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Sidebar styles */
        .sidebar {
            @apply fixed top-0 left-0 h-full w-64 bg-gray-800 text-white;
        }
        .sidebar-link {
            @apply flex items-center px-6 py-3 text-gray-300 hover:bg-gray-700 hover:text-white;
        }
        .sidebar-link.active {
            @apply bg-gray-900 text-white;
        }
        .sidebar-icon {
            @apply mr-3 text-lg;
        }
        
        /* Content area styles */
        .main-content {
            @apply ml-64 p-8 bg-gray-100 min-h-screen;
        }

        /* Form styles */
        .form-input {
            @apply block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm;
        }
        .form-label {
            @apply block text-sm font-medium text-gray-700 mb-1;
        }
        .form-select {
            @apply block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm;
        }
        .form-checkbox {
            @apply h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded;
        }

        /* Button styles */
        .btn {
            @apply inline-flex items-center px-4 py-2 border rounded-md shadow-sm text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2;
        }
        .btn-primary {
            @apply border-transparent text-white bg-indigo-600 hover:bg-indigo-700 focus:ring-indigo-500;
        }
        .btn-secondary {
            @apply border-gray-300 text-gray-700 bg-white hover:bg-gray-50 focus:ring-indigo-500;
        }
        .btn-danger {
            @apply border-transparent text-white bg-red-600 hover:bg-red-700 focus:ring-red-500;
        }

        /* Card styles */
        .card {
            @apply bg-white rounded-lg border border-gray-200 shadow-sm;
        }
        .card-header {
            @apply px-6 py-4 border-b border-gray-200;
        }
        .card-body {
            @apply p-6;
        }

        /* Table styles */
        .table {
            @apply min-w-full divide-y divide-gray-200;
        }
        .table th {
            @apply px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider;
        }
        .table td {
            @apply px-6 py-4 whitespace-nowrap text-sm text-gray-500;
        }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <div class="fixed inset-y-0 left-0 w-64 bg-gray-900 text-white transition-transform duration-300 transform md:translate-x-0" 
            x-data="{ open: false }" 
            :class="{'translate-x-0': open, '-translate-x-full': !open}">
            <div class="flex items-center justify-between h-16 px-4 bg-gray-800">
                <div class="text-xl font-bold">Admin Panel</div>
                <button class="md:hidden" @click="open = !open">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <nav class="px-4 py-4 space-y-2">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-2 rounded-lg hover:bg-gray-800 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-800' : '' }}">
                    <i class="fas fa-home w-5 h-5 mr-3"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('admin.categories.index') }}" class="flex items-center px-4 py-2 rounded-lg hover:bg-gray-800 {{ request()->routeIs('admin.categories.*') ? 'bg-gray-800' : '' }}">
                    <i class="fas fa-list w-5 h-5 mr-3"></i>
                    <span>Danh mục</span>
                </a>
                <a href="{{ route('admin.products.index') }}" class="flex items-center px-4 py-2 rounded-lg hover:bg-gray-800 {{ request()->routeIs('admin.products.*') ? 'bg-gray-800' : '' }}">
                    <i class="fas fa-box w-5 h-5 mr-3"></i>
                    <span>Sản phẩm</span>
                </a>
                <a href="{{ route('admin.orders.index') }}" class="flex items-center px-4 py-2 rounded-lg hover:bg-gray-800 {{ request()->routeIs('admin.orders.*') ? 'bg-gray-800' : '' }}">
                    <i class="fas fa-shopping-cart w-5 h-5 mr-3"></i>
                    <span>Đơn hàng</span>
                </a>
                <a href="{{ route('admin.users.index') }}" class="flex items-center px-4 py-2 rounded-lg hover:bg-gray-800 {{ request()->routeIs('admin.users.*') ? 'bg-gray-800' : '' }}">
                    <i class="fas fa-users w-5 h-5 mr-3"></i>
                    <span>Người dùng</span>
                </a>
            </nav>
        </div>

        <!-- Main content -->
        <div class="flex-1 md:ml-64">
            <!-- Top bar -->
            <div class="bg-white shadow-sm">
                <div class="flex items-center justify-between h-16 px-4">
                    <button class="md:hidden" @click="open = !open">
                        <i class="fas fa-bars"></i>
                    </button>
                    <div class="flex items-center space-x-4">
                        <span class="text-gray-700">{{ auth()->user()->name }}</span>
                        <form action="{{ route('admin.logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="text-gray-700 hover:text-red-600">
                                <i class="fas fa-sign-out-alt"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Page content -->
            <div class="p-6">
                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>

    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html> 