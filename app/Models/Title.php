<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Title extends Model
{
    use HasFactory;

    protected $table = 'title';
    protected $primaryKey = 'title_id';
    public $timestamps = false;

    protected $fillable = [
        'title_th',
        'title_eng',
        'status',
        'create_date',
        'update_date',
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'title_id');
    }
}