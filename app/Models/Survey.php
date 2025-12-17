<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Survey extends Model
{
    use HasFactory;

    protected $table = 'surveys';
    public $timestamps = true;
    protected $fillable = [
        'id',
        'organization_id',
        'user_id',
        'title',
        'description',
        'start_date',
        'end_date',
        'is_anonymous',
        'created_at',
        'updated_at',
        'status',
        'receive_new_answer_notifications',
    ];
    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_anonymous' => 'boolean',
        'receive_new_answer_notifications' => 'boolean',
    ];

    protected $attributes = [
        'status' => 'active',
    ];

    /**
     * Get all questions for this survey
     */
    public function questions()
    {
        return $this->hasMany(SurveyQuestion::class, 'survey_id');
    }

    /**
     * Get all responses/answers for this survey
     */
    public function responses()
    {
        return $this->hasMany(SurveyAnswer::class, 'survey_id');
    }

    /**
     * Get the user who created this survey
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
