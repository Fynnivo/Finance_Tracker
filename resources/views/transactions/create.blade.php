@extends('layouts.app')

@section('title', 'Tambah Transaksi - Finance Tracker')
@section('header', 'Tambah Transaksi')
@section('subtitle', 'Catat transaksi baru')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl shadow-lg p-8">
        <form action="{{ route('transactions.store') }}" method="POST">
            @csrf
            
            <!-- Type Selection -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-3">Tipe Transaksi</label>
                <div class="grid grid-cols-2 gap-4">
                    <label class="cursor-pointer">
                        <input type="radio" name="type" value="income" class="peer hidden" required>
                        <div class="p-4 border-2 rounded-lg peer-checked:border-green-500 peer-checked:bg-green-50 hover:bg-gray-50 transition text-center">
                            <i class="fas fa-arrow-up text-3xl text-green-600 mb-2"></i>
                            <p class="font-semibold">Pemasukan</p>
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="type" value="expense" class="peer hidden" required>
                        <div class="p-4 border-2 rounded-lg peer-checked:border-red-500 peer-checked:bg-red-50 hover:bg-gray-50 transition text-center">
                            <i class="fas fa-arrow-down text-3xl text-red-600 mb-2"></i>
                            <p class="font-semibold">Pengeluaran</p>
                        </div>
                    </label>
                </div>
                @error('type')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Category Selection -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-3">Kategori</label>
                <div class="grid grid-cols-3 gap-3" id="categoryGrid">
                    @foreach($categories as $category)
                    <label class="cursor-pointer category-option" data-type="{{ $category->type }}">
                        <input type="radio" name="category_id" value="{{ $category->id }}" class="peer hidden" required>
                        <div class="p-3 border-2 rounded-lg peer-checked:ring-2 hover:bg-gray-50 transition text-center"
                             style="peer-checked:border-color: {{ $category->color }}; peer-checked:background-color: {{ $category->color }}20">
                            <span class="text-2xl block mb-1">{{ $category->icon }}</span>
                            <p class="text-xs font-medium">{{ $category->name }}</p>
                        </div>
                    </label>
                    @endforeach
                </div>
                @error('category_id')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Amount -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Jumlah (Rp)</label>
                <input type="number" name="amount" step="0.01" min="0" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('amount') border-red-500 @enderror"
                       placeholder="0" required value="{{ old('amount') }}">
                @error('amount')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Date -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal</label>
                <input type="date" name="transaction_date" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('transaction_date') border-red-500 @enderror"
                       required value="{{ old('transaction_date', date('Y-m-d')) }}">
                @error('transaction_date')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi (Opsional)</label>
                <textarea name="description" rows="3" 
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('description') border-red-500 @enderror"
                          placeholder="Catatan tambahan...">{{ old('description') }}</textarea>
                @error('description')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Buttons -->
            <div class="flex gap-3">
                <button type="submit" class="flex-1 bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition font-semibold">
                    <i class="fas fa-save mr-2"></i>Simpan Transaksi
                </button>
                <a href="{{ route('transactions.index') }}" class="px-6 py-3 border border-gray-300 rounded-lg hover:bg-gray-50 transition font-semibold">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
// Filter categories based on transaction type
const typeRadios = document.querySelectorAll('input[name="type"]');
const categoryOptions = document.querySelectorAll('.category-option');

typeRadios.forEach(radio => {
    radio.addEventListener('change', function() {
        const selectedType = this.value;
        
        categoryOptions.forEach(option => {
            const categoryType = option.dataset.type;
            if (categoryType === selectedType) {
                option.style.display = 'block';
            } else {
                option.style.display = 'none';
                // Uncheck hidden categories
                const radioInput = option.querySelector('input[type="radio"]');
                if (radioInput) radioInput.checked = false;
            }
        });
    });
});

// Hide all categories initially
categoryOptions.forEach(option => {
    option.style.display = 'none';
});
</script>
@endpush
@endsection