@extends('layouts.app')

@section('title', 'Dashboard - Finance Tracker')
@section('header', 'Dashboard')
@section('subtitle', 'Overview keuangan Anda')

@section('content')
<!-- Filter Buttons -->
<div class="flex gap-2 mb-6">
    <a href="{{ route('dashboard', ['filter' => 'today']) }}"
       class="px-4 py-2 rounded-lg {{ $filter == 'today' ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700' }} shadow hover:shadow-md transition">
        Hari Ini
    </a>
    <a href="{{ route('dashboard', ['filter' => 'week']) }}"
       class="px-4 py-2 rounded-lg {{ $filter == 'week' ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700' }} shadow hover:shadow-md transition">
        Minggu Ini
    </a>
    <a href="{{ route('dashboard', ['filter' => 'month']) }}"
       class="px-4 py-2 rounded-lg {{ $filter == 'month' ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700' }} shadow hover:shadow-md transition">
        Bulan Ini
    </a>
    <a href="{{ route('dashboard', ['filter' => 'year']) }}"
       class="px-4 py-2 rounded-lg {{ $filter == 'year' ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700' }} shadow hover:shadow-md transition">
        Tahun Ini
    </a>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white fade-in">
        <div class="flex items-center justify-between mb-2">
            <span class="text-green-100">Total Pemasukan</span>
            <i class="fas fa-arrow-up text-2xl"></i>
        </div>
        <p class="text-3xl font-bold">Rp {{ number_format($totalIncome, 0, ',', '.') }}</p>
    </div>

    <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-xl shadow-lg p-6 text-white fade-in">
        <div class="flex items-center justify-between mb-2">
            <span class="text-red-100">Total Pengeluaran</span>
            <i class="fas fa-arrow-down text-2xl"></i>
        </div>
        <p class="text-3xl font-bold">Rp {{ number_format($totalExpense, 0, ',', '.') }}</p>
    </div>

    <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl shadow-lg p-6 text-white fade-in">
        <div class="flex items-center justify-between mb-2">
            <span class="text-indigo-100">Saldo</span>
            <i class="fas fa-wallet text-2xl"></i>
        </div>
        <p class="text-3xl font-bold">Rp {{ number_format($balance, 0, ',', '.') }}</p>
    </div>
</div>

<!-- Financial Health Alert -->
<div class="mb-8 p-6 rounded-xl shadow-lg fade-in flash-message
    {{ $healthStatus['status'] == 'danger' ? 'bg-red-50 border-l-4 border-red-500' : '' }}
    {{ $healthStatus['status'] == 'warning' ? 'bg-yellow-50 border-l-4 border-yellow-500' : '' }}
    {{ $healthStatus['status'] == 'success' ? 'bg-green-50 border-l-4 border-green-500' : '' }}">
    <div class="flex items-center gap-3">
        <span class="text-3xl">{{ $healthStatus['icon'] }}</span>
        <div>
            <h3 class="font-bold text-lg
                {{ $healthStatus['status'] == 'danger' ? 'text-red-800' : '' }}
                {{ $healthStatus['status'] == 'warning' ? 'text-yellow-800' : '' }}
                {{ $healthStatus['status'] == 'success' ? 'text-green-800' : '' }}">
                Status Keuangan
            </h3>
            <p class="
                {{ $healthStatus['status'] == 'danger' ? 'text-red-700' : '' }}
                {{ $healthStatus['status'] == 'warning' ? 'text-yellow-700' : '' }}
                {{ $healthStatus['status'] == 'success' ? 'text-green-700' : '' }}">
                {{ $healthStatus['message'] }}
            </p>
        </div>
    </div>
</div>

<!-- Budget Progress -->
@if($budgets->count() > 0)
<div class="bg-white rounded-xl shadow-lg p-6 mb-8 fade-in flash-message">
    <h3 class="text-xl font-bold mb-4 flex items-center gap-2">
        <i class="fas fa-bullseye text-indigo-600"></i>
        Budget Progress Bulan Ini
    </h3>
    <div class="space-y-4">
        @foreach($budgets as $budget)
        <div>
            <div class="flex items-center justify-between mb-2">
                <div class="flex items-center gap-2">
                    <span class="text-2xl">{{ $budget->category->icon }}</span>
                    <span class="font-semibold">{{ $budget->category->name }}</span>
                </div>
                <div class="text-right">
                    <span class="font-bold {{ $budget->percentage >= 100 ? 'text-red-600' : 'text-gray-700' }}">
                        {{ number_format($budget->percentage, 0) }}%
                    </span>
                    <p class="text-xs text-gray-500">
                        Rp {{ number_format($budget->spent, 0, ',', '.') }} /
                        Rp {{ number_format($budget->amount, 0, ',', '.') }}
                    </p>
                </div>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                <div class="h-full rounded-full transition-all duration-500
                    {{ $budget->percentage >= 100 ? 'bg-red-500' : ($budget->percentage >= 80 ? 'bg-yellow-500' : 'bg-green-500') }}"
                    style="width: {{ min($budget->percentage, 100) }}%">
                </div>
            </div>
            @if($budget->percentage >= 100)
                <p class="text-xs text-red-600 mt-1">⚠️ Budget sudah terlampaui!</p>
            @elseif($budget->percentage >= 80)
                <p class="text-xs text-yellow-600 mt-1">⚡ Hampir mencapai limit!</p>
            @endif
        </div>
        @endforeach
    </div>
</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Expense Chart -->
    <div class="bg-white rounded-xl shadow-lg p-6 fade-in">
        <h3 class="text-xl font-bold mb-4 flex items-center gap-2">
            <i class="fas fa-chart-pie text-indigo-600"></i>
            Pengeluaran per Kategori
        </h3>

        @if($expenseByCategory->count() > 0)
            <canvas id="expenseChart"></canvas>
        @else
            <div class="text-center py-12 text-gray-400">
                <i class="fas fa-chart-pie text-6xl mb-4"></i>
                <p>Belum ada data pengeluaran</p>
            </div>
        @endif
    </div>

    <!-- Recent Transactions -->
    <div class="bg-white rounded-xl shadow-lg p-6 fade-in">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-bold flex items-center gap-2">
                <i class="fas fa-history text-indigo-600"></i>
                Transaksi Terbaru
            </h3>
            <a href="{{ route('transactions.index') }}" class="text-indigo-600 hover:text-indigo-800 text-sm">
                Lihat Semua →
            </a>
        </div>

        @if($recentTransactions->count() > 0)
        <div class="space-y-3 max-h-96 overflow-y-auto">
            @foreach($recentTransactions as $transaction)
            <div class="flex items-center justify-between p-3 hover:bg-gray-50 rounded-lg transition">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center"
                         style="background-color: {{ $transaction->category->color }}20">
                        <span class="text-xl">{{ $transaction->category->icon }}</span>
                    </div>
                    <div>
                        <p class="font-semibold">{{ $transaction->category->name }}</p>
                        <p class="text-xs text-gray-500">{{ $transaction->relative_time }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="font-bold {{ $transaction->type == 'income' ? 'text-green-600' : 'text-red-600' }}">
                        {{ $transaction->type == 'income' ? '+' : '-' }}
                        Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                    </p>
                    @if($transaction->description)
                        <p class="text-xs text-gray-500 truncate max-w-xs">{{ $transaction->description }}</p>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-12 text-gray-400">
            <i class="fas fa-inbox text-6xl mb-4"></i>
            <p class="mb-2">Belum ada transaksi</p>
            <a href="{{ route('transactions.create') }}" class="text-indigo-600 hover:text-indigo-800">
                Tambah transaksi pertama →
            </a>
        </div>
        @endif
    </div>
</div>

<!-- Quick Actions -->
<div class="bg-white rounded-xl shadow-lg p-6 fade-in flash-message">
    <h3 class="text-xl font-bold mb-4">Quick Actions</h3>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <a href="{{ route('transactions.create') }}" class="p-4 bg-gradient-to-br from-green-500 to-green-600 text-white rounded-lg text-center">
            <i class="fas fa-plus-circle text-3xl mb-2"></i>
            <p class="font-semibold">Tambah Transaksi</p>
        </a>
        <a href="{{ route('budgets.create') }}" class="p-4 bg-gradient-to-br from-indigo-500 to-indigo-600 text-white rounded-lg text-center">
            <i class="fas fa-bullseye text-3xl mb-2"></i>
            <p class="font-semibold">Set Budget</p>
        </a>
        <a href="{{ route('categories.create') }}" class="p-4 bg-gradient-to-br from-purple-500 to-purple-600 text-white rounded-lg text-center">
            <i class="fas fa-tags text-3xl mb-2"></i>
            <p class="font-semibold">Kategori Baru</p>
        </a>
        <a href="{{ route('transactions.index') }}" class="p-4 bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-lg text-center">
            <i class="fas fa-list text-3xl mb-2"></i>
            <p class="font-semibold">Lihat Semua</p>
        </a>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    @if($expenseByCategory->count() > 0)
        const ctx = document.getElementById('expenseChart');

        if (!ctx || typeof Chart === 'undefined') {
            console.warn('Chart tidak dijalankan (canvas / Chart.js tidak siap)');
            return;
        }

        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($expenseByCategory->pluck('category.name')) !!},
                datasets: [{
                    data: {!! json_encode($expenseByCategory->pluck('total')) !!},
                    backgroundColor: {!! json_encode($expenseByCategory->pluck('category.color')) !!},
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { padding: 15, font: { size: 12 } }
                    },
                    tooltip: {
                        callbacks: {
                            label: ctx =>
                                ctx.label + ': Rp ' + ctx.parsed.toLocaleString('id-ID')
                        }
                    }
                }
            }
        });
    @endif
});
</script>
@endpush
