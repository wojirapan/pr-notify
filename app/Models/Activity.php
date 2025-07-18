<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $table = 'activity';
    protected $primaryKey = 'act_id';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'act_type_id',
        'act_type_detail',
        'act_date',
        'act_time',
        'act_location',
        'act_name',
        'act_objective',
        'act_description',
        'num_participants',
        'participating_agencies',
        'act_status_in_progress',
        'act_status_completed',
        'act_status',
        'status',
        'create_date',
        'update_date',
    ];

    protected $casts = [
        'act_date' => 'date',
        'act_time' => 'datetime',
        'act_status_in_progress' => 'datetime',
        'act_status_completed' => 'datetime',
        'create_date' => 'datetime',
        'update_date' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function activityType()
    {
        return $this->belongsTo(ActivityType::class, 'act_type_id');
    }

    public function images()
    {
        return $this->hasMany(ActivityImg::class, 'act_id');
    }
}