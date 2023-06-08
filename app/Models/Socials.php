<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Socials extends Model
{
    use HasFactory, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->useLogName('Socials')
        ->logOnly([
            'name',
            'link',
            'icon_id',
            'user_id',
            'created_at',
            'updated_at',
            'profile_id',
        ]);
    } 
    protected $table = 'module_socials';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'link',
        'icon_id',
        'user_id',
        'profile_id',
    ];

    public function icons()
    {
        return $this->hasOne(Icons::class,'id','icon_id');
    }

    public function profile()
    {
        return $this->belongsTo(Profiles::class,'id','profile_id');
    }

    public function user()
    {
        return $this->hasOne('Mushmero\Lapdash\Models\User','id','user_id')->withTrashed();
    }
}
