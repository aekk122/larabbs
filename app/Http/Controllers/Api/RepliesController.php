<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Reply;
use App\Http\Requests\ReplyRequest;
use App\Transformers\ReplyTransformer;
use App\Models\Topic;
use App\Models\User;

class RepliesController extends Controller
{
    public function store(ReplyRequest $request, Reply $reply, Topic $topic) {
    	$reply->content = $request->content;
    	$reply->topic_id = $topic->id;
    	$reply->user_id = $this->user()->id;
    	$reply->save();

    	return $this->response->item($reply, new ReplyTransformer())->setStatusCode(201);
    }

    public function destroy(Topic $topic, Reply $reply) {
    	if ($reply->topic_id != $topic->id) {
    		return $this->response->errorBadRequest();
    	}

    	$this->authorize('destroy', $reply);
    	$reply->delete();

    	return $this->response->noContent();
    }

    public function index(Topic $topic) {
    	$replies = $topic->hasManyReplies()->paginate(20);
    	return $this->response->paginator($replies, new ReplyTransformer());
    }

    public function userIndex(User $user) {
    	$replies = $user->hasManyReplies()->paginate(20);
    	return $this->response->paginator($replies, new ReplyTransformer());
    }
}
