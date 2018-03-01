<?php

namespace App\Observers;

use App\Models\Reply;
use App\Notifications\TopicReplied;


// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class ReplyObserver
{
    public function creating(Reply $reply)
    {
        //
        $reply->content = clean($reply->content, 'user_topic_body');
    }

    public function updating(Reply $reply)
    {
        //
    }

    public function created(Reply $reply) {
    	$reply->belongsToTopic->increment('reply_count', 1);

    	$topic = $reply->belongsToTopic;
    	//通知作者话题被回复
    	$topic->belongsToUser->notify(new TopicReplied($reply));
    }
}