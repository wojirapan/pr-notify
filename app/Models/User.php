<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'user';
    protected $primaryKey = 'user_id';
    public $timestamps = false; // ปิดการใช้ timestamps ของ Laravel

    protected $fillable = [
        'username',
        'password',
        'title_id',
        'fname',
        'lname',
        'phone_number',
        'email',
        'dep_id',
        'role_id',
        'status',
        'create_date',
        'update_date',
    ];

    protected $hidden = [
        'password',
    ];

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    public function title()
    {
        return $this->belongsTo(Title::class, 'title_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'dep_id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function activities()
    {
        return $this->hasMany(Activity::class, 'user_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'user_id');
    }

    public function logs()
    {
        return $this->hasMany(Log::class, 'user_id');
    }

    public function getFullNameAttribute()
    {
        return $this->title->title_th . ' ' . $this->fname . ' ' . $this->lname;
    }

    public function isAdmin()
    {
        return $this->role_id === 1;
    }
}