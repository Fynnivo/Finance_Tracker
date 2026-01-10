@extends('layouts.app')

@section('title', 'Budget - Finance Tracker')
@section('header', 'Budget Management')
@section('subtitle', 'Kelola budget bulanan Anda')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div class="flex gap-2">
        @php
            $months = [
                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
            ];
        @endphp
        <select onchange="window.location.href='?month='+this.value+'&year={{ $year }}'" 
                class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
            @foreach($months as $num => $name)
                <option value="{{ $num }}" {{ $month == $num ? 'selected' : '' }}>{{ $name }}</option>
            @endforeach
        </select>
        <select onchange="window.location.href='?month={{ $month }}&year='+this.value" 
                class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
            @for($y = date('Y') - 2; $y <= date('Y') + 1; $y++)
                <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
            @endfor
        </select>
    </div>
    
    <a href="{{ route('budgets.create') }}" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition shadow-lg">
        <i class="fas fa-plus mr-2"></i>Set Budget Baru
    </a>
</div>

@if($budgets->count() > 0)
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @foreach($budgets as $budget)
    <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition fade-in">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-full flex items-center justify-center text-2xl"
                     style="background-color: {{ $budget->category->color }}20">
                    {{ $budget->category->icon }}
                </div>
                <div>
                    <h3 class="font-bold text-lg">{{ $budget->category->name }}</h3>
                    <p class="text-xs text-gray-500">{{ $months[$budget->month] }} {{ $budget->year }}</p>
                </div>
            </div>
        </div>

        <!-- Progress Bar -->
        <div class="mb-4">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-semibold">Progress</span>
                <span class="text-sm font-bold {{ $budget->percentage >= 100 ? 'text-red-600' : 'text-gray-700' }}">
                    {{ number_format($budget->percentage, 0) }}%
                </span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-4 overflow-hidden">
                <div class="h-full rounded-full transition-all duration-500 {{ $budget->percentage >= 100 ? 'bg-red-500' : ($budget->percentage >= 80 ? 'bg-yellow-500' : 'bg-green-500') }}" 
                    style="width: {{ min($budget->percentage, 100) }}%">
                </div>
            </div>
        </div>

        <!-- Stats -->
        <div class="space-y-2 mb-4">
            <div class="flex justify-between text-sm">
                <span class="text-gray-600">Budget:</span>
                <span class="font-semibold">Rp {{ number_format($budget->amount, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-600">Terpakai:</span>
                <span class="font-semibold text-red-600">Rp {{ number_format($budget->spent, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-600">Sisa:</span>
                <span class="font-semibold text-green-600">Rp {{ number_format($budget->remaining, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Status Alert -->
        @if($budget->percentage >= 100)
        <div class="bg-red-50 border-l-4 border-red-500 p-3 mb-4">
            <p class="text-xs text-red-700">⚠️ Budget sudah terlampaui!</p>
        </div>
        @elseif($budget->percentage >= 80)
        <div class="bg-yellow-50 border-l-4 border-yellow-500 p-3 mb-4">
            <p class="text-xs text-yellow-700">⚡ Mendekati limit budget!</p>
        </div>
        @endif

        <!-- Actions -->
        <div class="flex gap-2 pt-4 border-t">
            <a href="{{ route('budgets.edit', $budget) }}" 
               class="flex-1 px-4 py-2 bg-indigo-100 text-indigo-600 rounded-lg hover:bg-indigo-200 text-center text-sm font-semibold">
                <i class="fas fa-edit mr-1"></i>Edit
            </a>
            <form action="{{ route('budgets.destroy', $budget) }}" method="POST" class="flex-1" 
                  onsubmit="return confirm('Yakin ingin menghapus budget ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="w-full px-4 py-2 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 text-sm font-semibold">
                    <i class="fas fa-trash mr-1"></i>Hapus
                </button>
            </form>
        </div>
    </div>
    @endforeach
</div>
@else
<!-- Empty State -->
<div class="bg-white rounded-xl shadow-lg p-12 text-center">
    <div class="text-gray-400 mb-4">
        <i class="fas fa-bullseye text-8xl"></i>
    </div>
    <h3 class="text-2xl font-bold text-gray-700 mb-2">Belum Ada Budget</h3>
    <p class="text-gray-500 mb-6">Mulai atur budget bulanan untuk kontrol keuangan yang lebih baik</p>
    <a href="{{ route('budgets.create') }}" class="inline-block bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition">
        <i class="fas fa-plus mr-2"></i>Set Budget Pertama
    </a>
</div>
@endif

<!-- Budget Tips -->
<div class="mt-8 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
    <h3 class="text-xl font-bold mb-3 flex items-center gap-2">
        <i class="fas fa-lightbulb"></i>
        Tips Budget Management
    </h3>
    <ul class="space-y-2 text-sm">
        <li class="flex items-start gap-2">
            <i class="fas fa-check-circle mt-1"></i>
            <span>Gunakan aturan 50/30/20: 50% kebutuhan, 30% keinginan, 20% tabungan</span>
        </li>
        <li class="flex items-start gap-2">
            <i class="fas fa-check-circle mt-1"></i>
            <span>Review budget setiap bulan untuk penyesuaian</span>
        </li>
        <li class="flex items-start gap-2">
            <i class="fas fa-check-circle mt-1"></i>
            <span>Set budget lebih rendah 10% dari estimasi untuk buffer darurat</span>
        </li>
    </ul>
</div>
@endsection