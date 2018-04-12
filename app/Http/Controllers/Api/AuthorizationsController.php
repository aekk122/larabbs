<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests\Api\SocialAuthorizationRequest;
use App\Models\User;

class AuthorizationsController extends Controller
{
	protected $allow_social = ['weixin'];

    public function socialStore($social_type, SocialAuthorizationRequest $request) {
    	if (!in_array($social_type, $this->allow_social)) {
    		return $this->response->errorBadRequest();
    	}

    	$driver = \Socialite::driver($social_type);

    	try {
    		if ($code = $request->code) {
    			$response = $driver->getAccessTokenResponse($code);
    			$token = $response['access_token'];
    		} else {
    			$token = $request->access_token;

    			if ($social_type = 'weixin') {
    				$driver->setOpenId($request->openid);
    			}
    		}

    		$oauthUser = $driver->userFromToken($token);
    	} catch (\Exception $e) {
    		return $this->response->errorUnauthorized('参数错误，未能获取用户信息');
    	}

    	switch ($social_type) {
    		case 'weixin':
    			$unionid = $oauthUser->offsetExists('unionid') ? $oauthUser->offsetGet('unionid') : null;

    			if (!empty($unionid)) {
    				$user = User::where('weixin_unionid', $unionid)->first();
    			} else {
    				$user = User::where('weixin_openid', $oauthUser->getId())->first();
    			}

    			// 如若未找到用户，则创建
    			if (!$user) {
    				$user = User::create([
    					'name' => $oauthUser->getNickName(),
    					'avatar' => $oauthUser->getAvatar(),
    					'weixin_openid' => $oauthUser->getId(),
    					'weixin_unionid' => $unionid,
    				]);
    			}

    			break;
    	}

    	return $this->response->array(['token' => $user->id]);
    }
}
