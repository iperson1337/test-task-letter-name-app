<?php

namespace App\Models;

use App\Filters\ApplicationFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    const APPROVED = 1;
    const REJECTED = 2;

    protected $fillable = [
        'name',
        'comment',
        'user_id',
        'status_id',
    ];

    public function scopeFilter($query, $request)
    {
        return (new ApplicationFilter($request))->apply($query);
    }

    public function status(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ApplicationStatus::class, 'status_id');
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
