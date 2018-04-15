<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Topic;
use App\Transformers\TopicTransformer;
use App\Http\Requests\Api\TopicRequest;
use App\Models\User;

class TopicsController extends Controller
{
    public function store(TopicRequest $request, Topic $topic) {
    	$topic->fill($request->all());

    	$topic->user_id = $this->user()->id;
    	$topic->save();

    	return $this->response->item($topic, new TopicTransformer())->setStatusCode(201);
    }


    public function update(TopicRequest $request, Topic $topic) {
    	$this->authorize('update', $topic);

    	$topic->update($request->all());

    	return $this->response->item($topic, new TopicTransformer());
    }

    public function destroy(Topic $topic) {
    	$this->authorize('destroy', $topic);

    	$topic->delete();
    	return $this->response->noContent();
    }

    public function index(Request $request, Topic $topic) {
    	$query = $topic->query();

    	// 判断是否给出分类 ID
    	if ($categoryId = $request->category_id) {
    		$query->where('category_id', $categoryId);
    	}

    	// 判断是否需要排序
    	$query->withOrder($request->order);

    	$topics = $query->paginate(20);

    	return $this->response->paginator($topics, new TopicTransformer());
    }

    public function userIndex(Request $request, User $user) {
    	$topics = $user->hasManyTopics()->recent()->paginate(20);

    	return $this->response->paginator($topics, new TopicTransformer());
    }

    public function show(Topic $topic) {
    	return $this->response->item($topic, new TopicTransformer());
    }
}
