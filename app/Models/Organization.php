<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'user_id'];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function owner(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class, 'organization_user')
                    ->withPivot('role')
                    ->withTimestamps();
    }

    public function isAdmin(User $user): bool
    {
        return $this->users()
                    ->where('user_id', $user->id)
                    ->wherePivot('role', 'admin')
                    ->exists();
    }

    public function isMember(User $user): bool
    {
        return $this->users()
                    ->where('user_id', $user->id)
                    ->exists();
    }
}
