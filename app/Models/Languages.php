<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Languages extends Model
{
    use HasFactory, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->useLogName('Languages')
        ->logOnly([
            'name',
            'proficiency_id',
            'user_id',
            'created_at',
            'updated_at',
            'profile_id',
        ]);
    } 
    protected $table = 'module_languages';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'proficiency_id',
        'user_id',
        'profile_id',
    ];

    public function proficiency()
    {
        return $this->hasOne(ProficiencyLevel::class,'id','proficiency_id');
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
