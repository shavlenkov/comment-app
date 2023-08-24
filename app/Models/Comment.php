<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'user_id', 'text', 'file'];

    public function scopeSort($query, $param) {
        if($param == 'true') {
            return $query->orderBy('created_at', 'desc');
        }
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function answers()
    {
        return $this->hasMany(Answer::class, 'comment_id');
    }
}
