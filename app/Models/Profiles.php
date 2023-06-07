<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Profiles extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->useLogName('Profiles')
        ->logOnly([
            'fullname',
            'tagline',
            'email',
            'phone',
            'website',
            'status',
            'attachment_id',
            'user_id',
            'created_at',
            'updated_at',
            'deleted_at',
            'deleted_by',
        ]);
    } 
    protected $table = 'module_profiles';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'fullname',
        'tagline',
        'email',
        'phone',
        'website',
        'status',
        'attachment_id',
        'user_id',
        'deleted_by',
    ];

    public function attachments()
    {
        return $this->hasOne(Attachments::class,'id','attachment_id');
    }

    public function educations()
    {
        return $this->hasMany(Educations::class, 'profile_id','id');
    }

    public function experiences()
    {
        return $this->hasMany(Experiences::class,'profile_id','id');
    }

    public function interests()
    {
        return $this->hasMany(Interests::class,'profile_id','id');
    }

    public function languages()
    {
        return $this->hasMany(Languages::class,'profile_id','id');
    }

    public function projects()
    {
        return $this->hasMany(Projects::class,'profile_id','id');
    }

    public function skills()
    {
        return $this->hasMany(Skills::class,'profile_id','id');
    }

    public function socials()
    {
        return $this->hasMany(Socials::class,'profile_id','id');
    }

    public function user()
    {
        return $this->hasOne('Mushmero\Lapdash\Models\User','id','user_id')->withTrashed();
    }
}
