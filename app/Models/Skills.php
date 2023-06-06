<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Skills extends Model
{
    use HasFactory, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->useLogName('Skills')
        ->logOnly([
            'name',
            'percentage',
            'user_id',
            'created_at',
            'updated_at',
            'profile_id',
            'icon_id',
        ]);
    } 
    protected $table = 'module_skills';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'percentage',
        'user_id',
        'profile_id',
        'icon_id'
    ];

    public function profile()
    {
        return $this->belongsTo(Profile::class,'id','profile_id');
    }

    public function icons()
    {
        return $this->hasOne(Icons::class,'id','icon_id');
    }

    public function user()
    {
        return $this->hasOne(User::class,'id','user_id')->withTrashed();
    }
}
