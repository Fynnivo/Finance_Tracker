@extends('layouts.app')

@section('title', 'Set Budget - Finance Tracker')
@section('header', 'Set Budget Baru')
@section('subtitle', 'Tentukan target budget bulanan')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl shadow-lg p-8">
        <form action="{{ route('budgets.store') }}" method="POST">
            @csrf
            
            <!-- Category Selection -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-3">Kategori Pengeluaran</label>
                @if($categories->count() > 0)
                <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                    @foreach($categories as $category)
                    <label class="cursor-pointer">
                        <input type="radio" name="category_id" value="{{ $category->id }}" class="peer hidden" required>
                        <div class="p-4 border-2 rounded-lg peer-checked:ring-2 hover:bg-gray-50 transition text-center"
                             style="peer-checked:border-color: {{ $category->color }}; peer-checked:background-color: {{ $category->color }}20">
                            <span class="text-3xl block mb-2">{{ $category->icon }}</span>
                            <p class="text-sm font-medium">{{ $category->name }}</p>
                        </div>
                    </label>
                    @endforeach
                </div>
                @else
                <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4">
                    <p class="text-yellow-700">Semua kategori pengeluaran sudah memiliki budget bulan ini.</p>
                    <a href="{{ route('categories.create') }}" class="text-yellow-800 font-semibold hover:underline">
                        Tambah kategori baru →
                    </a>
                </div>
                @endif
                @error('category_id')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Amount -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Target Budget (Rp)</label>
                <input type="number" name="amount" step="0.01" min="0" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('amount') border-red-500 @enderror"
                       placeholder="1000000" required value="{{ old('amount') }}">
                @error('amount')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
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
                            <option value="{{ $num }}" {{ old('month', date('n')) == $num ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tahun</label>
                    <select name="year" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500" required>
                        @for($y = date('Y') - 1; $y <= date('Y') + 2; $y++)
                            <option value="{{ $y }}" {{ old('year', date('Y')) == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>
            </div>

            <!-- Info Box -->
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6">
                <div class="flex items-start gap-2">
                    <i class="fas fa-info-circle text-blue-600 mt-1"></i>
                    <div class="text-sm text-blue-700">
                        <p class="font-semibold mb-1">Tips Budget:</p>
                        <ul class="list-disc list-inside space-y-1">
                            <li>Set budget realistis berdasarkan pengeluaran bulan lalu</li>
                            <li>Sisakan 10-15% untuk pengeluaran tak terduga</li>
                            <li>Review dan adjust setiap bulan</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex gap-3">
                <button type="submit" class="flex-1 bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition font-semibold">
                    <i class="fas fa-save mr-2"></i>Set Budget
                </button>
                <a href="{{ route('budgets.index') }}" class="px-6 py-3 border border-gray-300 rounded-lg hover:bg-gray-50 transition font-semibold">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection