<?php

namespace App\Transformers;

use App\Models\Reply;
use League\Fractal\TransformerAbstract;

class ReplyTransformer extends TransformerAbstract {

	protected $availableIncludes = ['belongsToUser', 'belongsToTopic'];

	public function transform(Reply $reply) {
		return [
			'id' => $reply->id,
			'topic_id' => $reply->topic_id,
			'user_id' => $reply->user_id,
			'content' => $reply->content,
			'created_at' => $reply->created_at->toDateTimeString(),
		];
	}

	public function includeBelongsToUser(Reply $reply) {
		return $this->item($reply->belongsToUser, new UserTransformer());
	}

	public function includeBelongsToTopic(Reply $reply) {
		return $this->item($reply->belongsToTopic, new TopicTransformer());
	}
}