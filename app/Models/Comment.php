<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'article_id',
        'user_id',
        'comment',
    ];


    public function user(): BelongsTo
    {
       return $this->belongsTo(User::class);
    }


    public function article(): BelongsTo
    {
       return $this->belongsTo(Article::class);
    }
}
