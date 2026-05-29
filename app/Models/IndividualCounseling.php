<?php

namespace App\Models;

use App\Enums\CounselingServiceType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class IndividualCounseling extends Model
{
    use HasFactory;

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'academic_year_id',
        'counselor_id',
        'student_id',
        'scheduled_at',
        'status',
        'service_type',
        'category',
        'problem_description',
        'approach',
        'result',
        'evaluation',
        'follow_up',
        'follow_up_plan',
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

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function documents(): MorphMany
    {
        return $this->morphMany(CounselingDocument::class, 'counseling');
    }
}
