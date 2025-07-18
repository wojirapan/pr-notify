<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityImg extends Model
{
    use HasFactory;

    protected $table = 'activity_img';
    protected $primaryKey = 'act_img_id';
    public $timestamps = false;

    protected $fillable = [
        'act_id',
        'act_img',
        'act_img_path',
        'status',
        'create_date',
        'update_date',
    ];

    public function activity()
    {
        return $this->belongsTo(Activity::class, 'act_id');
    }
}