@extends('layouts.app')

@section('title', 'Edit Orang Tua - KonselorKita')

@section('content')
<div class="card">
    <div class="card-header">
        <h6 class="text-lg font-semibold mb-0">Edit Orang Tua: {{ $guardian->full_name }}</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.guardians.update', $guardian) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="full_name" class="form-label">Nama Lengkap <span class="text-danger-600">*</span></label>
                    <input type="text" id="full_name" name="full_name" value="{{ old('full_name', $guardian->full_name) }}" class="form-control @error('full_name') !border-danger-600 @enderror">
                    @error('full_name')
                        <p class="text-danger-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="email" class="form-label">Email <span class="text-danger-600">*</span></label>
                    <input type="email" id="email" name="email" value="{{ old('email', $guardian->user->email) }}" class="form-control @error('email') !border-danger-600 @enderror">
                    @error('email')
                        <p class="text-danger-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div>
                    <label for="phone" class="form-label">Telepon</label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone', $guardian->phone) }}" class="form-control">
                </div>
                <div>
                    <label for="occupation" class="form-label">Pekerjaan</label>
                    <input type="text" id="occupation" name="occupation" value="{{ old('occupation', $guardian->occupation) }}" class="form-control">
                </div>
            </div>

            <div class="mb-4">
                <label for="address" class="form-label">Alamat</label>
                <textarea id="address" name="address" rows="3" class="form-control">{{ old('address', $guardian->address) }}</textarea>
            </div>

            {{-- Hubungan dengan Siswa --}}
            @php
                $existingStudents = old('students', $guardian->students->map(fn ($s) => [
                    'student_id' => $s->id,
                    'relationship' => $s->pivot->relationship,
                ])->toArray());
            @endphp
            <div class="border border-neutral-200 dark:border-neutral-600 rounded-lg p-4 mb-6">
                <h6 class="text-base font-semibold mb-3">Hubungan dengan Siswa</h6>
                <div id="student-relations">
                    @foreach($existingStudents as $i => $entry)
                        <div class="student-row grid grid-cols-1 md:grid-cols-12 gap-3 mb-3 items-end">
                            <div class="md:col-span-6">
                                <label class="form-label">Siswa</label>
                                <select name="students[{{ $i }}][student_id]" class="form-control">
                                    <option value="">-- Pilih Siswa --</option>
                                    @foreach($students as $student)
                                        <option value="{{ $student->id }}" {{ ($entry['student_id'] ?? '') == $student->id ? 'selected' : '' }}>
                                            {{ $student->full_name }} ({{ $student->nis }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="md:col-span-4">
                                <label class="form-label">Hubungan</label>
                                <select name="students[{{ $i }}][relationship]" class="form-control">
                                    <option value="">-- Pilih --</option>
                                    <option value="ayah" {{ ($entry['relationship'] ?? '') === 'ayah' ? 'selected' : '' }}>Ayah</option>
                                    <option value="ibu" {{ ($entry['relationship'] ?? '') === 'ibu' ? 'selected' : '' }}>Ibu</option>
                                    <option value="wali" {{ ($entry['relationship'] ?? '') === 'wali' ? 'selected' : '' }}>Wali</option>
                                </select>
                            </div>
                            <div class="md:col-span-2">
                                <button type="button" onclick="this.closest('.student-row').remove()" class="btn btn-outline-danger w-full flex items-center justify-center gap-1">
                                    <iconify-icon icon="solar:trash-bin-trash-bold"></iconify-icon> Hapus
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
                <button type="button" id="add-student-btn" class="btn btn-outline-primary flex items-center gap-2 mt-2">
                    <iconify-icon icon="ic:baseline-plus"></iconify-icon> Tambah Siswa
                </button>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="btn btn-primary-600 flex items-center gap-2">
                    <iconify-icon icon="solar:diskette-bold" class="text-xl"></iconify-icon>
                    Perbarui
                </button>
                <a href="{{ route('admin.guardians.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    let studentIndex = {{ count($existingStudents) }};
    const studentsJson = @json($students);

    document.getElementById('add-student-btn').addEventListener('click', function () {
        let optionsHtml = '<option value="">-- Pilih Siswa --</option>';
        studentsJson.forEach(s => {
            optionsHtml += `<option value="${s.id}">${s.full_name} (${s.nis})</option>`;
        });

        const html = `
            <div class="student-row grid grid-cols-1 md:grid-cols-12 gap-3 mb-3 items-end">
                <div class="md:col-span-6">
                    <label class="form-label">Siswa</label>
                    <select name="students[${studentIndex}][student_id]" class="form-control">${optionsHtml}</select>
                </div>
                <div class="md:col-span-4">
                    <label class="form-label">Hubungan</label>
                    <select name="students[${studentIndex}][relationship]" class="form-control">
                        <option value="">-- Pilih --</option>
                        <option value="ayah">Ayah</option>
                        <option value="ibu">Ibu</option>
                        <option value="wali">Wali</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <button type="button" onclick="this.closest('.student-row').remove()" class="btn btn-outline-danger w-full flex items-center justify-center gap-1">
                        <iconify-icon icon="solar:trash-bin-trash-bold"></iconify-icon> Hapus
                    </button>
                </div>
            </div>`;

        document.getElementById('student-relations').insertAdjacentHTML('beforeend', html);
        studentIndex++;
    });
</script>
@endpush
@endsection
