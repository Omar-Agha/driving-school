<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class JoinSchoolRequest extends Model
{
    use HasFactory;

    protected $guarded = ['id'];


    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }


    public function accept(): bool
    {


        DB::transaction(function () {
            if ($this->student->school_id != null) {
                $this->status = 'canceled';
            } else {
                JoinSchoolRequest::where('id', '!=', $this->id)->where('student_id', $this->student_id)->update(['status' => 'canceled']);
                $this->status = 'accepted';
                $this->student->school_id = $this->school_id;
            }

            $this->save();
            $this->student->save();
        });
        return true;
    }

    public function refuse()
    {
        $this->status = 'refused';
        $this->save();
    }

    public function scopeWithNullableStatus(Builder $query): void
    {
        $query->whereNull('status');
    }
}
