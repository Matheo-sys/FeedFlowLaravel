<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Survey extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'organization_id',
        'title',
        'description',
        'start_date',
        'end_date',
        'is_anonymous',
        'token',
    ];

    /**
     * Définit la relation "un sondage a plusieurs questions".
     */
    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }
}