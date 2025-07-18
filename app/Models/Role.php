<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $table = 'role';
    protected $primaryKey = 'role_id';
    public $timestamps = false;

    protected $fillable = [
        'role_name',
        'status',
        'create_date',
        'update_date',
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'role_id');
    }
}