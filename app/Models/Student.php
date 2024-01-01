<?php

namespace App\Models;

use App\Helpers\FileHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Student extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $appends = ['full_name', 'avatar_url'];

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }





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
        return $this->belongsTo(School::class);
    }

    public function joinSchoolRequest(): HasMany
    {
        return $this->hasMany(JoinSchoolRequest::class);
    }


    public function joinSchoolRequestWithSchool(): BelongsToMany
    {
        return $this->belongsToMany(School::class, 'join_school_requests')->withPivot(['status']);
    }



    public function requestJoinSchool(int $school_id, int $student_id): JoinSchoolRequest
    {
        return JoinSchoolRequest::create([
            'school_id' => $school_id,
            'student_id' => $student_id
        ]);
    }


    public function appointments(): HasMany
    {
        return $this->hasMany(Event::class);
    }
}
