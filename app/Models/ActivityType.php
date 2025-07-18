<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityType extends Model
{
    use HasFactory;

    protected $table = 'activity_type';
    protected $primaryKey = 'act_type_id';
    public $timestamps = false;

    protected $fillable = [
        'act_type',
        'status',
        'create_date',
        'update_date',
    ];

    public function activities()
    {
        return $this->hasMany(Activity::class, 'act_type_id');
    }
}