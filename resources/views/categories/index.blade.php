@extends('layouts.app')

@section('title', 'Kategori - Finance Tracker')
@section('header', 'Kategori')
@section('subtitle', 'Kelola kategori transaksi')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div class="flex gap-2">
        <button class="px-4 py-2 bg-indigo-600 text-white rounded-lg">Semua</button>
    </div>
    
    <a href="{{ route('categories.create') }}" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition shadow-lg">
        <i class="fas fa-plus mr-2"></i>Tambah Kategori
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Income Categories -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h3 class="text-xl font-bold mb-4 text-green-600 flex items-center gap-2">
            <i class="fas fa-arrow-up"></i>
            Kategori Pemasukan
        </h3>
        <div class="space-y-3">
            @forelse($categories->where('type', 'income') as $category)
            <div class="flex items-center justify-between p-4 hover:bg-gray-50 rounded-lg transition group">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center text-2xl"
                         style="background-color: {{ $category->color }}20">
                        {{ $category->icon }}
                    </div>
                    <div>
                        <p class="font-semibold">{{ $category->name }}</p>
                        <p class="text-xs text-gray-500">{{ $category->transactions_count ?? 0 }} transaksi</p>
                    </div>
                </div>
                <div class="flex gap-2 opacity-0 group-hover:opacity-100 transition">
                    <a href="{{ route('categories.edit', $category) }}" 
                       class="w-8 h-8 flex items-center justify-center bg-indigo-100 text-indigo-600 rounded-lg hover:bg-indigo-200">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form action="{{ route('categories.destroy', $category) }}" method="POST" class="inline" 
                          onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="w-8 h-8 flex items-center justify-center bg-red-100 text-red-600 rounded-lg hover:bg-red-200">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
            @empty
            <div class="text-center py-8 text-gray-400">
                <i class="fas fa-inbox text-4xl mb-2"></i>
                <p>Belum ada kategori pemasukan</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Expense Categories -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h3 class="text-xl font-bold mb-4 text-red-600 flex items-center gap-2">
            <i class="fas fa-arrow-down"></i>
            Kategori Pengeluaran
        </h3>
        <div class="space-y-3">
            @forelse($categories->where('type', 'expense') as $category)
            <div class="flex items-center justify-between p-4 hover:bg-gray-50 rounded-lg transition group">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center text-2xl"
                         style="background-color: {{ $category->color }}20">
                        {{ $category->icon }}
                    </div>
                    <div>
                        <p class="font-semibold">{{ $category->name }}</p>
                        <p class="text-xs text-gray-500">{{ $category->transactions_count ?? 0 }} transaksi</p>
                    </div>
                </div>
                <div class="flex gap-2 opacity-0 group-hover:opacity-100 transition">
                    <a href="{{ route('categories.edit', $category) }}" 
                       class="w-8 h-8 flex items-center justify-center bg-indigo-100 text-indigo-600 rounded-lg hover:bg-indigo-200">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form action="{{ route('categories.destroy', $category) }}" method="POST" class="inline" 
                          onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="w-8 h-8 flex items-center justify-center bg-red-100 text-red-600 rounded-lg hover:bg-red-200">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
            @empty
            <div class="text-center py-8 text-gray-400">
                <i class="fas fa-inbox text-4xl mb-2"></i>
                <p>Belum ada kategori pengeluaran</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection