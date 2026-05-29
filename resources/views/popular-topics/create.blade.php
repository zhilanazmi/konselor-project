@extends('layouts.app')

@section('title', 'Tambah Topik Populer - KonselorKita')

@section('content')

<div class="card">
    <div class="card-header">
        <div class="flex items-center gap-3">
            <a href="{{ route('guru-bk.popular-topics.index') }}" class="w-10 h-10 bg-neutral-100 dark:bg-neutral-700 rounded-lg flex items-center justify-center hover:bg-neutral-200 dark:hover:bg-neutral-600">
                <iconify-icon icon="solar:arrow-left-linear" class="text-xl"></iconify-icon>
            </a>
            <div>
                <h6 class="text-lg font-semibold mb-0">Tambah Topik Populer</h6>
                <p class="text-sm text-neutral-500 dark:text-neutral-400 mb-0">Tambahkan topik baru untuk ditampilkan di landing page</p>
            </div>
        </div>
    </div>

    <div class="card-body">
        <form action="{{ route('guru-bk.popular-topics.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- Left Column --}}
                <div class="space-y-4">
                    {{-- Title --}}
                    <div>
                        <label for="title" class="form-label">Judul Topik <span class="text-danger-600">*</span></label>
                        <input type="text" id="title" name="title" value="{{ old('title') }}" class="form-control @error('title') !border-danger-600 @enderror" placeholder="Contoh: Masalah Belajar" required>
                        @error('title')
                            <span class="text-sm text-danger-600 mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Description --}}
                    <div>
                        <label for="description" class="form-label">Deskripsi <span class="text-danger-600">*</span></label>
                        <textarea id="description" name="description" rows="4" class="form-control @error('description') !border-danger-600 @enderror" placeholder="Deskripsi singkat tentang topik ini..." required>{{ old('description') }}</textarea>
                        <small class="text-neutral-500 dark:text-neutral-400">Maksimal 500 karakter</small>
                        @error('description')
                            <span class="text-sm text-danger-600 mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Order --}}
                    <div>
                        <label for="order" class="form-label">Urutan Tampilan</label>
                        <input type="number" id="order" name="order" value="{{ old('order', 0) }}" min="0" class="form-control @error('order') !border-danger-600 @enderror" placeholder="0">
                        <small class="text-neutral-500 dark:text-neutral-400">Semakin kecil angka, semakin awal ditampilkan</small>
                        @error('order')
                            <span class="text-sm text-danger-600 mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Status --}}
                    <div>
                        <label class="form-label">Status</label>
                        <div class="flex items-center gap-2">
                            <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="form-check-input">
                            <label for="is_active" class="cursor-pointer">Aktif (tampilkan di landing page)</label>
                        </div>
                    </div>
                </div>

                {{-- Right Column --}}
                <div class="space-y-4">
                    {{-- Image Upload --}}
                    <div>
                        <label for="image" class="form-label">Gambar Topik</label>
                        <input type="file" id="image" name="image" accept="image/jpeg,image/jpg,image/png" class="form-control @error('image') !border-danger-600 @enderror" onchange="previewImage(event)">
                        <small class="text-neutral-500 dark:text-neutral-400">Format: JPG, JPEG, PNG. Maksimal 5MB</small>
                        @error('image')
                            <span class="text-sm text-danger-600 mt-1 block">{{ $message }}</span>
                        @enderror
                        
                        {{-- Image Preview --}}
                        <div id="imagePreview" class="mt-3 hidden">
                            <img src="" alt="Preview" class="w-full max-w-xs rounded-lg border border-neutral-200 dark:border-neutral-700">
                        </div>
                    </div>

                    {{-- Icon (Alternative to Image) --}}
                    <div class="p-4 bg-neutral-50 dark:bg-neutral-800 rounded-lg">
                        <p class="text-sm font-medium mb-3">Atau gunakan Icon (jika tidak upload gambar)</p>
                        
                        <div class="space-y-3">
                            <div>
                                <label for="icon" class="form-label text-sm">Nama Icon</label>
                                <input type="text" id="icon" name="icon" value="{{ old('icon') }}" class="form-control @error('icon') !border-danger-600 @enderror" placeholder="solar:book-bold">
                                <small class="text-neutral-500 dark:text-neutral-400">Cari icon di <a href="https://icon-sets.iconify.design/" target="_blank" class="text-primary-600 hover:underline">Iconify</a></small>
                                @error('icon')
                                    <span class="text-sm text-danger-600 mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label for="icon_color" class="form-label text-sm">Warna Icon</label>
                                <div class="flex items-center gap-2">
                                    <input type="color" id="icon_color" name="icon_color" value="{{ old('icon_color', '#3B82F6') }}" class="w-12 h-10 rounded border border-neutral-200 dark:border-neutral-700">
                                    <input type="text" id="icon_color_text" value="{{ old('icon_color', '#3B82F6') }}" class="form-control flex-1" readonly>
                                </div>
                                @error('icon_color')
                                    <span class="text-sm text-danger-600 mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Submit Buttons --}}
            <div class="flex items-center gap-3 mt-6 pt-6 border-t border-neutral-200 dark:border-neutral-700">
                <button type="submit" class="btn btn-primary-600 !rounded-lg">
                    <iconify-icon icon="solar:check-circle-bold" class="text-lg"></iconify-icon>
                    Simpan Topik
                </button>
                <a href="{{ route('guru-bk.popular-topics.index') }}" class="btn btn-outline-secondary !rounded-lg">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Image Preview
    function previewImage(event) {
        const file = event.target.files[0];
        const preview = document.getElementById('imagePreview');
        const img = preview.querySelector('img');
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                img.src = e.target.result;
                preview.classList.remove('hidden');
            }
            reader.readAsDataURL(file);
        } else {
            preview.classList.add('hidden');
        }
    }

    // Color Picker Sync
    const colorPicker = document.getElementById('icon_color');
    const colorText = document.getElementById('icon_color_text');
    
    colorPicker.addEventListener('input', function() {
        colorText.value = this.value;
    });
</script>
@endpush

@endsection
