<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notification';
    protected $primaryKey = 'noti_id';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'message',
        'url',
        'is_read',
        'create_date',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'create_date' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}