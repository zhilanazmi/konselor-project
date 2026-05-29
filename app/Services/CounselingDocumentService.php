<?php

namespace App\Services;

use App\Models\CounselingDocument;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class CounselingDocumentService
{
    /**
     * @param  array<int, UploadedFile>  $files
     */
    public function storeDocuments(Model $counseling, array $files): void
    {
        foreach ($files as $file) {
            $path = $file->store('counseling-documents', 'public');

            $counseling->documents()->create([
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $path,
                'file_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
            ]);
        }
    }

    public function deleteDocument(CounselingDocument $document): void
    {
        Storage::disk('public')->delete($document->file_path);
        $document->delete();
    }

    public function deleteAllDocuments(Model $counseling): void
    {
        $counseling->documents->each(function (CounselingDocument $document) {
            Storage::disk('public')->delete($document->file_path);
        });

        $counseling->documents()->delete();
    }
}
