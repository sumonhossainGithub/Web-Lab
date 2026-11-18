<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Result extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'course_code',
        'marks',
        'grade',
    ];

    /**
     * Many-to-One inverse relationship: A result belongs to a student.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Automatically calculates the letter grade from numerical marks.
     */
    public static function calculateGrade(float $marks): string
    {
        if ($marks >= 80) return 'A+';
        if ($marks >= 75) return 'A';
        if ($marks >= 70) return 'A-';
        if ($marks >= 65) return 'B+';
        if ($marks >= 60) return 'B';
        if ($marks >= 55) return 'B-';
        if ($marks >= 50) return 'C+';
        if ($marks >= 45) return 'C';
        if ($marks >= 40) return 'D';
        return 'F';
    }

    /**
     * Calculates the Grade Point Average (GPA scale 4.00) from numerical marks.
     */
    public static function calculateGradePoint(float $marks): float
    {
        if ($marks >= 80) return 4.00;
        if ($marks >= 75) return 3.75;
        if ($marks >= 70) return 3.50;
        if ($marks >= 65) return 3.25;
        if ($marks >= 60) return 3.00;
        if ($marks >= 55) return 2.75;
        if ($marks >= 50) return 2.50;
        if ($marks >= 45) return 2.25;
        if ($marks >= 40) return 2.00;
        return 0.00;
    }

    /**
     * Boot method to ensure grade is automatically computed before saving if not provided.
     */
    protected static function booted(): void
    {
        static::saving(function (Result $result) {
            if (isset($result->marks)) {
                $result->grade = self::calculateGrade((float) $result->marks);
            }
        });
    }
}
