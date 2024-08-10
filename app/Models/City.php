<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class City extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'province_id',
        'name',
        'island',
        'latitude',
        'longitude',
        'is_foreign_country',
    ];

    /**
     * Relation to `Province` model
     */
    public function province(): BelongsTo {
        return $this->belongsTo(Province::class, 'province_id', 'id');
    }
}
