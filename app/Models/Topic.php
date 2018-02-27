<?php

namespace App\Models;

class Topic extends Model
{
    protected $fillable = ['title', 'body', 'category_id', 'excerpt', 'slug'];

    public function belongsToCategory() {
    	return $this->belongsTo(Category::class, 'category_id');
    }

    public function belongsToUser() {
    	return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeWithOrder($query, $order) {
    	//不同的排序，需要使用不同的数据读取逻辑
    	switch($order) {
    		case 'recent':
    			$query = $this->recent();
    			break;
    		default:
    			$query = $this->recentReplied();
    			break;
    	}

    	//预加载防止 N+1 问题
    	return $query->with('belongsToUser', 'belongsToCategory');
    }

    public function scopeRecentReplied($query) {
    	//当话题有新回复时，我们将编写逻辑来更新话题模型的 reply_count 属性
    	//此时会自动触发框架对数据模型 updated_at 时间戳的更新
    	return $query->orderBy('updated_at', 'desc');
    }

    public function scopeRecent($query) {
    	//按照创建时间排序
    	return $query->orderBy('created_at', 'desc');
    }
}
