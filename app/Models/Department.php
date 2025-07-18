<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $table = 'department';
    protected $primaryKey = 'dep_id';
    public $timestamps = false;

    protected $fillable = [
        'dep_name',
        'status',
        'create_date',
        'update_date',
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'dep_id');
    }
}