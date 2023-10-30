<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class SchoolPackageSubscriptionsPivot extends Pivot
{
    use HasFactory;

    protected $table="package_school";

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    protected $casts = [
        'expires_at' => 'datetime',
        'starts_at'=>'datetime'
    ];

    public function school():BelongsTo{
        return $this->belongsTo(School::class);
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }


    public function getStatusAttribute()
    {
        if ($this->expires_at < Carbon::now()) {
            return 'expired';
        } else {
            return 'active';
        }
    }

    public function scopeActive(Builder $query): void
    {
        $query->where('expires_at', '>=', Carbon::now());
    }

    public function scopeInActive(Builder $query): void
    {
        $query->where('expires_at', '<', Carbon::now());
    }
}
