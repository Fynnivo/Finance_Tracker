<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Finance Tracker')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @keyframes shimmer {
            0% { background-position: -1000px 0; }
            100% { background-position: 1000px 0; }
        }
        .skeleton {
            animation: shimmer 2s infinite;
            background: linear-gradient(to right, #f0f0f0 8%, #e0e0e0 18%, #f0f0f0 33%);
            background-size: 1000px 100%;
        }
        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Sidebar -->
    <aside class="fixed left-0 top-0 h-full w-64 bg-gradient-to-b from-indigo-600 to-indigo-800 text-white shadow-2xl z-50">
        <div class="p-6">
            <h1 class="text-2xl font-bold flex items-center gap-2">
                <i class="fas fa-wallet"></i>
                Finance Tracker
            </h1>
        </div>
        
        <nav class="mt-6">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-6 py-3 hover:bg-white/10 transition {{ request()->routeIs('dashboard') ? 'bg-white/20 border-l-4 border-white' : '' }}">
                <i class="fas fa-home w-5"></i>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('transactions.index') }}" class="flex items-center gap-3 px-6 py-3 hover:bg-white/10 transition {{ request()->routeIs('transactions.*') ? 'bg-white/20 border-l-4 border-white' : '' }}">
                <i class="fas fa-exchange-alt w-5"></i>
                <span>Transaksi</span>
            </a>
            <a href="{{ route('categories.index') }}" class="flex items-center gap-3 px-6 py-3 hover:bg-white/10 transition {{ request()->routeIs('categories.*') ? 'bg-white/20 border-l-4 border-white' : '' }}">
                <i class="fas fa-tags w-5"></i>
                <span>Kategori</span>
            </a>
            <a href="{{ route('budgets.index') }}" class="flex items-center gap-3 px-6 py-3 hover:bg-white/10 transition {{ request()->routeIs('budgets.*') ? 'bg-white/20 border-l-4 border-white' : '' }}">
                <i class="fas fa-chart-pie w-5"></i>
                <span>Budget</span>
            </a>
        </nav>
        
        <div class="absolute bottom-0 w-full p-6 border-t border-white/20">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center">
                    <i class="fas fa-user"></i>
                </div>
                <div>
                    <p class="font-semibold">Admin</p>
                    <p class="text-xs text-white/70">user@example.com</p>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="ml-64 min-h-screen">
        <!-- Top Bar -->
        <header class="bg-white shadow-sm sticky top-0 z-40">
            <div class="px-8 py-4 flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">@yield('header', 'Dashboard')</h2>
                    <p class="text-sm text-gray-500">@yield('subtitle', 'Kelola keuangan Anda dengan mudah')</p>
                </div>
                <div class="flex items-center gap-4">
                    <div class="text-right">
                        <p class="text-xs text-gray-500">Hari ini</p>
                        <p class="font-semibold" id="currentDate"></p>
                    </div>
                </div>
            </div>
        </header>

        <!-- Flash Messages -->
        @if(session('success'))
        <div class="mx-8 mt-4 p-4 bg-green-50 border-l-4 border-green-500 text-green-800 rounded fade-in">
            <div class="flex items-center gap-2">
                <i class="fas fa-check-circle"></i>
                <span>{{ session('success') }}</span>
            </div>
        </div>
        @endif

        @if(session('error'))
        <div class="mx-8 mt-4 p-4 bg-red-50 border-l-4 border-red-500 text-red-800 rounded fade-in">
            <div class="flex items-center gap-2">
                <i class="fas fa-exclamation-circle"></i>
                <span>{{ session('error') }}</span>
            </div>
        </div>
        @endif

        <!-- Content -->
        <div class="p-8">
            @yield('content')
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            // Update current date
            function updateDate() {
                const el = document.getElementById('currentDate');
                if (!el) return;

                el.textContent = new Date().toLocaleDateString('id-ID', {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });
            }

            updateDate();

            // Auto hide ONLY flash messages
            setTimeout(() => {
                document.querySelectorAll('.flash-message').forEach(alert => {
                    alert.style.transition = 'opacity 0.5s';
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 500);
                });
            }, 5000);

        });
    </script>


    @stack('scripts')

</body>
</html>