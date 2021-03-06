<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\TopicRequest;
use App\Models\Category;
use Auth;
use App\Handlers\ImageUploadHandler;
use App\Models\User;
use App\Models\Link;

class TopicsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

    //此处的 User $user，只是便捷的 $user = new User 的写法。
	public function index(Request $request, Topic $topic, User $user, Link $link)
	{		
		
		$topics = $topic->withOrder($request->order)->paginate(30);

		//活跃用户
		$active_users = $user->getActiveUsers();
		//推荐资源
		$links = $link->getAllCached();
		// dd($active_users);
		return view('topics.index', compact('topics', 'active_users', 'links'));
	}

    public function show(Request $request, Topic $topic)
    {
    	// URL 矫正，即访问连接话题有 slug 时，一直带有 slug
    	
    	if ( !empty($topic->slug) && $topic->slug != $request->slug) {
    		return redirect($topic->link(), 301);
    	}

    	$msg = \Session::get('success');
        return view('topics.show', compact('topic'));
    }

	public function create(Topic $topic)
	{
		$categories = Category::all();
		return view('topics.create_and_edit', compact('topic', 'categories'));
	}

	public function store(TopicRequest $request, Topic $topic)
	{	
		$topic->fill($request->all());
		$topic->user_id = Auth::id();
		$topic->save();
		
		return redirect()->to($topic->link())->with('success', '创建成功');
	}

	public function edit(Topic $topic)
	{	
        $this->authorize('update', $topic);
        $categories = Category::all();
		return view('topics.create_and_edit', compact('topic', 'categories'));
	}

	public function update(TopicRequest $request, Topic $topic)
	{
		$this->authorize('update', $topic);
		$topic->update($request->all());

		return redirect()->to($topic->link())->with('success', '更新成功！');
	}

	public function destroy(Topic $topic)
	{
		$this->authorize('destroy', $topic);
		$topic->delete();

		return redirect()->route('topics.index')->with('success', '成功删除');
	}

	public function uploadImage(Request $request, ImageUploadHandler $uploader) {
		//初始化返回数据，默认失败
		$data = [
			'success' => false,
			'msg' => '上传失败！',
			'file_path' => '',
		];

		//判断是否有上传文件，并赋值给 $file
		if ($file = $request->upload_file) {
			//保存图片到本地
			$result = $uploader->save($request->upload_file, 'topics', \Auth::id(), 1024);
			//图片保存成功的话
			if ($result) {
				$data['file_path'] = $result['path'];
				$data['success'] = true;
				$data['msg'] = '上传成功！';
			}
		}
		return $data;
	}

	
}