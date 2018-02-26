<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\UserRequest;
use App\Handlers\ImageUploadHandler;
use Auth;

class UsersController extends Controller
{

    public function __construct() {
        $this->middleware('auth', ['except' => 'show']);
    }
    
    public function show(User $user) {

    	return view('users.show', compact('user'));
    }

    public function edit(User $user) {
        if (Auth::user()->can('update', $user)) {
        	return view('users.edit', compact('user'));
        } else {
            return "Don't";
        }

    }

    public function update(UserRequest $request, User $user, ImageUploadHandler $uploader) {

        if (Auth::user()->can('update', $user)) {
        	$data = $request->all();

        	if ($request->avatar) {
        		$result = $uploader->save($request->avatar, 'avatars', $user->id, 362);
        		if ($result) {
        			$data['avatar'] = $result['path'];
        		}
        	}

        	// dd($request->avatar);
        	$user->update($data);
        	return redirect()->route('users.show', $user->id)->with('success', '个人资料更新成功！');
        } else {
            return "Don't";
        }
    }
}
