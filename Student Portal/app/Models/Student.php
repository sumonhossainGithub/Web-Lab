<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'roll',
        'department',
        'email',
    ];

    /**
     * One-to-Many relationship: A student has many results.
     */
    public function results(): HasMany
    {
        return $this->hasMany(Result::class);
    }

    /**
     * Compute average marks of the student.
     */
    public function getAverageMarksAttribute(): float
    {
        return round($this->results->avg('marks') ?? 0, 2);
    }

    /**
     * Compute overall GPA.
     */
    public function getGpaAttribute(): float
    {
        if ($this->results->isEmpty()) {
            return 0.00;
        }

        $totalPoints = $this->results->sum(function ($result) {
            return Result::calculateGradePoint((float) $result->marks);
        });

        return round($totalPoints / $this->results->count(), 2);
    }
}
