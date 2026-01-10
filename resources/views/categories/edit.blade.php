@extends('layouts.app')

@section('title', 'Edit Kategori - Finance Tracker')
@section('header', 'Edit Kategori')
@section('subtitle', 'Ubah data kategori')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl shadow-lg p-8">
        <form action="{{ route('categories.update', $category) }}" method="POST">
            @csrf
            @method('PUT')
            
            <!-- Type Selection -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-3">Tipe Kategori</label>
                <div class="grid grid-cols-2 gap-4">
                    <label class="cursor-pointer">
                        <input type="radio" name="type" value="income" class="peer hidden" 
                               {{ old('type', $category->type) == 'income' ? 'checked' : '' }} required>
                        <div class="p-4 border-2 rounded-lg peer-checked:border-green-500 peer-checked:bg-green-50 hover:bg-gray-50 transition text-center">
                            <i class="fas fa-arrow-up text-3xl text-green-600 mb-2"></i>
                            <p class="font-semibold">Pemasukan</p>
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="type" value="expense" class="peer hidden" 
                               {{ old('type', $category->type) == 'expense' ? 'checked' : '' }} required>
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

            <!-- Name -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Kategori</label>
                <input type="text" name="name" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('name') border-red-500 @enderror"
                       placeholder="Contoh: Makanan, Transport, dll" required value="{{ old('name', $category->name) }}">
                @error('name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Icon Selection -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-3">Pilih Icon</label>
                <div class="grid grid-cols-8 gap-2" id="iconGrid">
                    @php
                        $icons = ['💰', '💵', '💳', '🏦', '💼', '💻', '📱', '🏠', 
                                  '🚗', '🍔', '☕', '🎮', '🎬', '📚', '⚽', '🏋️',
                                  '🛒', '👕', '💊', '🏥', '✈️', '🎓', '🎨', '🎵',
                                  '📊', '📈', '🎁', '🍕', '🍜', '🥗', '🚕', '🚌'];
                    @endphp
                    @foreach($icons as $emoji)
                    <label class="cursor-pointer">
                        <input type="radio" name="icon" value="{{ $emoji }}" class="peer hidden" 
                               {{ old('icon', $category->icon) == $emoji ? 'checked' : '' }} required>
                        <div class="w-12 h-12 flex items-center justify-center text-2xl border-2 rounded-lg peer-checked:border-indigo-500 peer-checked:bg-indigo-50 hover:bg-gray-50 transition">
                            {{ $emoji }}
                        </div>
                    </label>
                    @endforeach
                </div>
                @error('icon')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Color Selection -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-3">Pilih Warna</label>
                <div class="grid grid-cols-8 gap-2">
                    @php
                        $colors = ['#ef4444', '#f97316', '#f59e0b', '#eab308', '#84cc16', '#22c55e', 
                                   '#10b981', '#14b8a6', '#06b6d4', '#0ea5e9', '#3b82f6', '#6366f1', 
                                   '#8b5cf6', '#a855f7', '#d946ef', '#ec4899'];
                    @endphp
                    @foreach($colors as $color)
                    <label class="cursor-pointer">
                        <input type="radio" name="color" value="{{ $color }}" class="peer hidden" 
                               {{ old('color', $category->color) == $color ? 'checked' : '' }} required>
                        <div class="w-12 h-12 rounded-lg border-2 peer-checked:ring-4 peer-checked:ring-offset-2 transition"
                             style="background-color: {{ $color }}; peer-checked:ring-color: {{ $color }}"></div>
                    </label>
                    @endforeach
                </div>
                @error('color')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Preview -->
            <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                <label class="block text-sm font-semibold text-gray-700 mb-3">Preview</label>
                <div class="flex items-center gap-3 p-4 bg-white rounded-lg border" id="preview">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center text-2xl" id="previewIcon" 
                         style="background-color: {{ $category->color }}20">
                        {{ $category->icon }}
                    </div>
                    <span class="font-semibold" id="previewName">{{ $category->name }}</span>
                </div>
            </div>

            <!-- Info -->
            @if($category->transactions()->count() > 0)
            <div class="mb-6 p-4 bg-blue-50 border-l-4 border-blue-500">
                <p class="text-sm text-blue-700">
                    <i class="fas fa-info-circle mr-1"></i>
                    Kategori ini memiliki {{ $category->transactions()->count() }} transaksi terkait.
                </p>
            </div>
            @endif

            <!-- Buttons -->
            <div class="flex gap-3">
                <button type="submit" class="flex-1 bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition font-semibold">
                    <i class="fas fa-save mr-2"></i>Update Kategori
                </button>
                <a href="{{ route('categories.index') }}" class="px-6 py-3 border border-gray-300 rounded-lg hover:bg-gray-50 transition font-semibold">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
// Live Preview
const nameInput = document.querySelector('input[name="name"]');
const iconInputs = document.querySelectorAll('input[name="icon"]');
const colorInputs = document.querySelectorAll('input[name="color"]');
const previewIcon = document.getElementById('previewIcon');
const previewName = document.getElementById('previewName');

nameInput?.addEventListener('input', function() {
    previewName.textContent = this.value || 'Nama Kategori';
});

iconInputs.forEach(input => {
    input.addEventListener('change', function() {
        if (this.checked) {
            previewIcon.textContent = this.value;
        }
    });
});

colorInputs.forEach(input => {
    input.addEventListener('change', function() {
        if (this.checked) {
            previewIcon.style.backgroundColor = this.value + '20';
        }
    });
});
</script>
@endpush
@endsection