<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class MobileResetPassword extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    public $timestamps = false;


    public function scopeNotExpired(Builder $query): void
    {
        $query->where('expires_at', '>', now());
    }
}
