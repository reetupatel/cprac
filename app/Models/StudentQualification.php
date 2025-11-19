<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentQualification extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id', 'course', 'passing_year', 'percentage'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
