<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
        'from_user_id',
        'to_user_id',
    ];

    /**
     * Get the sender of this message
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    /**
     * Get the recipient of this message
     */
    public function recipient()
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }
}
