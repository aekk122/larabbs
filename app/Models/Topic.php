<?php

namespace App\Models;

class Topic extends Model
{
    protected $fillable = ['title', 'body', 'user_id', 'category_id', 'reply_count', 'view_count', 'last_reply_user_id', 'order', 'excerpt', 'slug'];

    public function belongsToCategory() {
    	return $this->belongsTo(Category::class, 'category_id');
    }

    public function belongsToUser() {
    	return $this->belongsTo(User::class, 'user_id');
    }
}
