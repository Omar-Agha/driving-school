<?php

namespace App\Models;

use App\Helpers\FileHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Instructor extends Model
{
    use HasFactory;


    protected $fillable = ['name', 'date_of_birth', 'avatar', 'school_id', 'first_name', 'last_name'];
    protected $appends = ['avatar_url'];



    public function getAvatarUrlAttribute($value)
    {
        return FileHelper::getFileUrl($this->avatar);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function school(): BelongsTo
    {
        return $this->BelongsTo(School::class);
    }

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}
