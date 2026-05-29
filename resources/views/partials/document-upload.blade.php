{{-- Reusable: Upload Foto Dokumentasi --}}
{{-- Params: $documents (collection), $deleteRoute (string), $deleteParams (array) --}}
<div class="card mt-4">
    <div class="card-header">
        <h6 class="font-semibold mb-0 flex items-center gap-2">
            <iconify-icon icon="solar:camera-bold" class="text-primary-600"></iconify-icon>
            Dokumentasi Foto
        </h6>
    </div>
    <div class="card-body">
        {{-- Upload Form --}}
        <div class="mb-4">
            <label class="form-label">Upload Foto Baru (JPG/PNG, maks. 5 MB per file)</label>
            <input type="file" name="documents[]" multiple accept=".jpg,.jpeg,.png"
                class="form-control @error('documents.*') !border-danger-600 @enderror">
            @error('documents.*')
                <p class="text-danger-600 text-sm mt-1">{{ $message }}</p>
            @enderror
            <p class="text-xs text-neutral-400 mt-1">Bisa memilih lebih dari satu file sekaligus.</p>
        </div>

        {{-- Existing Documents --}}
        @if(isset($documents) && $documents->count() > 0)
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                @foreach($documents as $doc)
                    <div class="relative group rounded-lg overflow-hidden border border-neutral-200 dark:border-neutral-700">
                        <img src="{{ Storage::url($doc->file_path) }}" alt="{{ $doc->file_name }}"
                            class="w-full h-28 object-cover">
                        <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                            <a href="{{ Storage::url($doc->file_path) }}" target="_blank"
                                class="w-8 h-8 bg-white/20 hover:bg-white/40 rounded-lg flex items-center justify-center text-white" title="Lihat">
                                <iconify-icon icon="solar:eye-bold" class="text-sm"></iconify-icon>
                            </a>
                            <form action="{{ route($deleteRoute, array_merge($deleteParams, [$doc])) }}" method="POST"
                                onsubmit="return confirm('Hapus foto ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="w-8 h-8 bg-danger-500/80 hover:bg-danger-600 rounded-lg flex items-center justify-center text-white" title="Hapus">
                                    <iconify-icon icon="solar:trash-bin-trash-bold" class="text-sm"></iconify-icon>
                                </button>
                            </form>
                        </div>
                        <p class="text-xs text-neutral-500 truncate px-2 py-1 bg-neutral-50 dark:bg-neutral-800">{{ $doc->file_name }}</p>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-neutral-400 text-sm italic">Belum ada foto dokumentasi.</p>
        @endif
    </div>
</div>
