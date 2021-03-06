<?php

namespace App\Models;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Auth;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable implements JWTSubject
{

    use Traits\ActiveUserHelper;
    use Traits\LastActivedAtHelper;
    use HasApiTokens;

    use Notifiable {
        notify as protected laravelNotify;
    }

    use HasRoles;

    public function notify($instance) {
        //如果要通知的人是当前用户，就不必通知了
        if ($this->id === Auth::id()) {
            return;
        }

        $this->increment('notification_count');
        $this->laravelNotify($instance);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'introduction', 'avatar', 'phone', 'weixin_openid', 'weixin_unionid', 'registration_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function hasManyTopics() {
        return $this->hasMany(Topic::class);
    }

    public function isAuthorOf($model) {
        return $this->id === $model->user_id;
    }

    public function hasManyReplies() {
        return $this->hasMany(Reply::class, 'user_id');
    }

    public function markAsRead() {
        $this->notification_count = 0;
        $this->save();
        $this->unreadNotifications->markAsRead();
    }


    public function setPasswordAttribute($value) {
        //如果值的长度等于 60， 即认为是已经做过加密的情况
        if (strlen($value) != 60) {
            //不等于 60， 做密码加密处理
            $value = bcrypt($value);
        }

        $this->attributes['password'] = $value;
    }

    public function setAvatarAttribute($path) {
        //如果不是 'http' 子串开头，那就是从后台上传的，需要补全 URL
        if ( !starts_with($path, 'http')) {
            //拼接完整 url
            $path = config('app.url') . "/uploads/images/avatars/$path";
        }

        $this->attributes['avatar'] = $path;
    }

    public function hasManyFollowers() {
        return $this->belongsToMany(User::class, 'followers', 'user_id', 'follower_id');
    }

    public function hasManyFollows() {
        return $this->belongsToMany(User::class, 'followers', 'follower_id', 'user_id');
    }

    public function follow($user_ids) {
        if (! is_array($user_ids)) {
            $user_ids = compact('user_ids');
        }
        $this->hasManyFollows()->sync($user_ids, false);
    }

    public function unfollow($user_ids) {
        if ( ! is_array($user_ids)) {
            $user_ids = compact('user_ids');
        }

        $this->hasManyFollows()->detach($user_ids);
    }

    public function isFollowing($user_id) {
        return $this->hasManyFollows->contains($user_id);
    }

    // Rest omitted for brevity

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function findForPassport($username) {
        filter_var($username, FILTER_VALIDATE_EMAIL) ?
            $credentials['email'] = $username :
            $credentials['phone'] = $username;

        return self::where($credentials)->first();
    }

}
