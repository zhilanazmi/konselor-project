<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Storage;

class CounselingDocument extends Model
{
    use HasFactory;

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'counseling_type',
        'counseling_id',
        'file_name',
        'file_path',
        'file_type',
        'file_size',
    ];

    public function counseling(): MorphTo
    {
        return $this->morphTo();
    }

    public function getUrlAttribute(): string
    {
        return Storage::url($this->file_path);
    }

    protected static function booted(): void
    {
        static::deleting(function (CounselingDocument $document) {
            Storage::delete($document->file_path);
        });
    }
}
