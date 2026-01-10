@extends('layouts.app')

@section('title', 'Transaksi - Finance Tracker')
@section('header', 'Transaksi')
@section('subtitle', 'Kelola semua transaksi Anda')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div class="flex gap-2">
        <a href="{{ route('transactions.index', ['type' => 'all']) }}" 
           class="px-4 py-2 rounded-lg {{ !request('type') || request('type') == 'all' ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700' }} shadow">
            Semua
        </a>
        <a href="{{ route('transactions.index', ['type' => 'income']) }}" 
           class="px-4 py-2 rounded-lg {{ request('type') == 'income' ? 'bg-green-600 text-white' : 'bg-white text-gray-700' }} shadow">
            Pemasukan
        </a>
        <a href="{{ route('transactions.index', ['type' => 'expense']) }}" 
           class="px-4 py-2 rounded-lg {{ request('type') == 'expense' ? 'bg-red-600 text-white' : 'bg-white text-gray-700' }} shadow">
            Pengeluaran
        </a>
    </div>
    
    <a href="{{ route('transactions.create') }}" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition shadow-lg">
        <i class="fas fa-plus mr-2"></i>Tambah Transaksi
    </a>
</div>

<!-- Filter by Category -->
<div class="bg-white rounded-xl shadow-lg p-4 mb-6">
    <div class="flex items-center gap-2 overflow-x-auto pb-2">
        <span class="text-sm font-semibold text-gray-600 whitespace-nowrap">Filter Kategori:</span>
        <a href="{{ route('transactions.index', ['type' => request('type')]) }}" 
           class="px-3 py-1 rounded-full text-sm {{ !request('category_id') ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} transition whitespace-nowrap">
            Semua
        </a>
        @foreach($categories as $category)
        <a href="{{ route('transactions.index', ['type' => request('type'), 'category_id' => $category->id]) }}" 
           class="px-3 py-1 rounded-full text-sm flex items-center gap-1 {{ request('category_id') == $category->id ? 'text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} transition whitespace-nowrap"
           style="{{ request('category_id') == $category->id ? 'background-color: '.$category->color : '' }}">
            <span>{{ $category->icon }}</span>
            <span>{{ $category->name }}</span>
        </a>
        @endforeach
    </div>
</div>

@if($transactions->count() > 0)
<!-- Transactions List -->
<div class="bg-white rounded-xl shadow-lg overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Tanggal</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Kategori</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Deskripsi</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Tipe</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase">Jumlah</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($transactions as $transaction)
                <tr class="hover:bg-gray-50 transition fade-in">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">
                            {{ $transaction->transaction_date->format('d M Y') }}
                        </div>
                        <div class="text-xs text-gray-500">
                            {{ $transaction->relative_time }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center" 
                                 style="background-color: {{ $transaction->category->color }}20">
                                <span>{{ $transaction->category->icon }}</span>
                            </div>
                            <span class="font-medium">{{ $transaction->category->name }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900 max-w-xs truncate">
                            {{ $transaction->description ?: '-' }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $transaction->type == 'income' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $transaction->type == 'income' ? 'Pemasukan' : 'Pengeluaran' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right">
                        <div class="text-sm font-bold {{ $transaction->type == 'income' ? 'text-green-600' : 'text-red-600' }}">
                            {{ $transaction->type == 'income' ? '+' : '-' }} Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('transactions.edit', $transaction) }}" class="text-indigo-600 hover:text-indigo-800">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('transactions.destroy', $transaction) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination -->
<div class="mt-6">
    {{ $transactions->links() }}
</div>

@else
<!-- Empty State -->
<div class="bg-white rounded-xl shadow-lg p-12 text-center">
    <div class="text-gray-400 mb-4">
        <i class="fas fa-inbox text-8xl"></i>
    </div>
    <h3 class="text-2xl font-bold text-gray-700 mb-2">Belum Ada Transaksi</h3>
    <p class="text-gray-500 mb-6">Mulai catat transaksi pertama Anda untuk tracking keuangan yang lebih baik</p>
    <a href="{{ route('transactions.create') }}" class="inline-block bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition">
        <i class="fas fa-plus mr-2"></i>Tambah Transaksi Pertama
    </a>
</div>
@endif
@endsection