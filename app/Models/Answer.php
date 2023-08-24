<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'user_id', 'text', 'file', 'comment_id', 'parent_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comment()
    {
        return $this->belongsTo(Comment::class);
    }

    public function answer()
    {
        return $this->belongsTo(Answer::class, 'parent_id');
    }

    public function childAnswers()
    {
        return $this->hasMany(Answer::class, 'parent_id');
    }

}
