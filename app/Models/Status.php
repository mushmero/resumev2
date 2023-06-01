<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Status extends Model
{
    use HasFactory, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->useLogName('Status')
        ->logOnly([
            'status',
            'user_id',
            'created_at',
            'updated_at',
        ]);
    } 

    protected $table = 'statuses';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'status',
        'user_id',
    ];

    public function user()
    {
        return $this->hasOne('Mushmero\Lapdash\Models\User','id','user_id')->withTrashed();
    }
}
