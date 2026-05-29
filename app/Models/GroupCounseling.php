<?php

namespace App\Models;

use App\Enums\CounselingServiceType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class GroupCounseling extends Model
{
    use HasFactory;

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'academic_year_id',
        'counselor_id',
        'topic',
        'description',
        'method',
        'scheduled_at',
        'status',
        'service_type',
        'result',
        'evaluation',
        'follow_up',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'scheduled_at' => 'datetime',
            'service_type' => CounselingServiceType::class,
        ];
    }

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function counselor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'counselor_id');
    }

    public function participants(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'group_counseling_participants')
            ->withPivot('notes')
            ->withTimestamps();
    }

    public function documents(): MorphMany
    {
        return $this->morphMany(CounselingDocument::class, 'counseling');
    }
}
