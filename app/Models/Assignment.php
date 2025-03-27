<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'file_path',
        'due_date',
        'teacher_id',
    ];

    protected $casts = [
        'due_date' => 'datetime',
    ];

    /**
     * Get the teacher who created this assignment
     */
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    /**
     * Get the submissions for this assignment
     */
    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }
}
