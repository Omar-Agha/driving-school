<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgressItemUserRate extends Model
{
    use HasFactory;
    protected $guarded = ['id'];


    function progressItem():BelongsTo{
        return $this->belongsTo(ProgressItem::class);
    }
}
