<?php

namespace App\Enums;

enum CounselingServiceType: string
{
    case Individual = 'individual';
    case Group = 'group';
    case Classroom = 'classroom';
    case LargeClass = 'large_class';

    public function label(): string
    {
        return match ($this) {
            self::Individual => 'Bimbingan Individu',
            self::Group => 'Bimbingan Kelompok',
            self::Classroom => 'Bimbingan Klasikal',
            self::LargeClass => 'Bimbingan Kelas Besar',
        };
    }

    public function allowsMultipleStudents(): bool
    {
        return match ($this) {
            self::Individual => false,
            self::Group, self::Classroom, self::LargeClass => true,
        };
    }
}
