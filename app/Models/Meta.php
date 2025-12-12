<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Meta extends Model
{
    use HasFactory;

    protected $fillable = [
        'metable_id',
        'metable_type',
        'meta_key',
        'meta_value',
    ];

    /**
     * Get the parent metable model.
     */
    public function metable(): MorphTo
    {
        return $this->morphTo();
    }
}
