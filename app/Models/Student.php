<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'name','email','phone','age','gender','skills','country','languages',
        'dob','preferred_time','hours','fav_color','photo','about'
        ];

        protected $casts = [
        'skills' => 'array',
        'languages' => 'array',
        'dob' => 'date'
        ];

    public function qualifications()
    {
        return $this->hasMany(StudentQualification::class)->orderBy('id', 'asc');
    }

}