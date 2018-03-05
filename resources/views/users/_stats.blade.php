<div class="stats">
  <a href="{{ route('users.show', [$user->id, 'tab' => 'follows']) }}">
    <strong id="following" class="stat">
    {{ count($user->hasManyFollows) }}
    </strong>
    关注
  </a>
  <a href="{{ route('users.show', [$user->id, 'tab' => 'replies']) }}">
    <strong id="followers" class="stat">
    <!-- 此为偷懒方法，数据量大时应该设一个字段记录 -->
    {{ count($user->hasManyReplies) }}
    </strong>
    评论
  </a>
  <a href="{{ route('users.show', $user->id) }}">
    <strong id="statuses" class="stat">
    {{ count($user->hasManyTopics) }}
    </strong>
    话题
  </a>
</div>