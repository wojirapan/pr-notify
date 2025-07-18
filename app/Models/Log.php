<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;

    protected $table = 'log';
    protected $primaryKey = 'log_id';
    public $timestamps = false;

    protected $fillable = [
        'log',
        'datetime',
        'user_id',
        'status',
        'create_date',
        'update_date',
        'log_type',
    ];

    protected $casts = [
        'datetime' => 'datetime',
        'create_date' => 'datetime',
        'update_date' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}