@extends('layouts.app')

@section('title', 'Edit Budget - Finance Tracker')
@section('header', 'Edit Budget')
@section('subtitle', 'Ubah target budget')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl shadow-lg p-8">
        <form action="{{ route('budgets.update', $budget) }}" method="POST">
            @csrf
            @method('PUT')
            
            <!-- Category Info (Read-only) -->
            <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                <label class="block text-sm font-semibold text-gray-700 mb-3">Kategori</label>
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center text-2xl"
                         style="background-color: {{ $budget->category->color }}20">
                        {{ $budget->category->icon }}
                    </div>
                    <span class="font-semibold text-lg">{{ $budget->category->name }}</span>
                </div>
            </div>

            <!-- Amount -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Target Budget (Rp)</label>
                <input type="number" name="amount" step="0.01" min="0" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('amount') border-red-500 @enderror"
                       placeholder="1000000" required value="{{ old('amount', $budget->amount) }}">
                @error('amount')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                
                <!-- Current Stats -->
                <div class="mt-3 p-3 bg-blue-50 rounded-lg">
                    <div class="text-sm space-y-1">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Terpakai saat ini:</span>
                            <span class="font-semibold">Rp {{ number_format($budget->spent, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Progress:</span>
                            <span class="font-semibold {{ $budget->percentage >= 100 ? 'text-red-600' : 'text-green-600' }}">
                                {{ number_format($budget->percentage, 0) }}%
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Month & Year -->
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Bulan</label>
                    <select name="month" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500" required>
                        @php
                            $months = [
                                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                            ];
                        @endphp
                        @foreach($months as $num => $name)
                            <option value="{{ $num }}" {{ old('month', $budget->month) == $num ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tahun</label>
                    <select name="year" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500" required>
                        @for($y = date('Y') - 1; $y <= date('Y') + 2; $y++)
                            <option value="{{ $y }}" {{ old('year', $budget->year) == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>
            </div>

            <!-- Warning Box -->
            @if($budget->percentage >= 80)
            <div class="mb-6 p-4 {{ $budget->percentage >= 100 ? 'bg-red-50 border-l-4 border-red-500' : 'bg-yellow-50 border-l-4 border-yellow-500' }}">
                <p class="text-sm {{ $budget->percentage >= 100 ? 'text-red-700' : 'text-yellow-700' }}">
                    {{ $budget->percentage >= 100 ? '⚠️ Budget sudah terlampaui! Pertimbangkan untuk menaikkan limit.' : '⚡ Budget hampir habis! Perhatikan pengeluaran Anda.' }}
                </p>
            </div>
            @endif

            <!-- Buttons -->
            <div class="flex gap-3">
                <button type="submit" class="flex-1 bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition font-semibold">
                    <i class="fas fa-save mr-2"></i>Update Budget
                </button>
                <a href="{{ route('budgets.index') }}" class="px-6 py-3 border border-gray-300 rounded-lg hover:bg-gray-50 transition font-semibold">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection