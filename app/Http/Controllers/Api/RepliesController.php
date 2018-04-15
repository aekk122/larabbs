<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Reply;
use App\Http\Requests\ReplyRequest;
use App\Transformers\ReplyTransformer;
use App\Models\Topic;

class RepliesController extends Controller
{
    public function store(ReplyRequest $request, Reply $reply, Topic $topic) {
    	$reply->content = $request->content;
    	$reply->topic_id = $topic->id;
    	$reply->user_id = $this->user()->id;
    	$reply->save();

    	return $this->response->item($reply, new ReplyTransformer())->setStatusCode(201);
    }
}
