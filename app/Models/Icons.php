<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Icons extends Model
{
    use HasFactory, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->useLogName('Icons')
        ->logOnly([
            'fullname',
            'name',
            'type',
            'user_id',
            'created_at',
            'updated_at',
        ]);
    } 

    protected $table = 'icons';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'fullname',
        'name',
        'type',
        'user_id',
    ];

    public function user()
    {
        return $this->hasOne(User::class,'id','user_id')->withTrashed();
    }
}
