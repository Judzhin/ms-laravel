<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    // protected $table = ''

    protected $fillable = [
        'title',
        'description',
        'preview',
        'thumbnail',
    ];

    // protected $guarded = [];

    public function comments()
    {
        $this
            ->hasMany(Comment::class)
            ->orderBy('created_by');
    }
}
