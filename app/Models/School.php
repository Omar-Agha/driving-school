<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class School extends Model
{
    use HasFactory;

    protected $guarded = ['id'];




    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


    public function packages(): BelongsToMany
    {
        return $this->belongsToMany(Package::class)
            ->using(SchoolPackageSubscriptionsPivot::class)
            ->as("subscription")
            ->withPivot(['cost', "duration", "starts_at", "expires_at"]);

    }

    public function activePackage(): BelongsToMany
    {
        return $this->packages()
            ->where('expires_at', '>=', now())
            ->where('starts_at', '<=', now())
            ->limit(1); // Limit to one valid subscription

    }

    public function instructors(): HasMany{
        return $this->hasMany(Instructor::class);
    }

    public function vehicles(): HasMany{
        return $this->hasMany(Vehicle::class);
    }
}