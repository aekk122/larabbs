@if (count($topics))
	<ul class="media-list">
		@foreach ($topics as $topic)
			<li class="media">
				<div class="media-left">
					<a href="{{ route('users.show', $topic->user_id) }}">
						<img src="{{ $topic->belongsToUser->avatar }}" style="width:52px;height: 52px;" alt="" class="media-object img-thumbnail" title="{{ $topic->belongsToUser->name }}">
					</a>
				</div>

				<div class="media-body">
					
					<div class="media-heading">
						<a href="{{ route('topics.show', $topic->id) }}" title="{{ $topic->title }}">
							{{ $topic->title }}
						</a>
						<a href="{{ route('topics.show', $topic->id) }}" class="pull-right">
							<span class="badge">{{ $topic->reply_count }}</span>
						</a>
					</div>

					<div class="media-body meta">
						<a href="{{ route('categories.show', $topic->belongsToCategory->id)}}" title="{{ $topic->belongsToCategory->name }}">
							<span class="glyphicon glyphicon-folder-open" aria-hidden="true"></span>
							{{ $topic->belongsToCategory->name}}
						</a>

						<span>•</span>

						<a href="{{ route('users.show', $topic->user_id) }}" title="{{ $topic->belongsToUser->name }}">
							<span class="glyphicon glyphicon-user" aria-hidden="true"></span>
							{{ $topic->belongsToUser->name }}
						</a>
						<span>•</span>
						<span class="glyphicon glyphicon-time" aria-hidden="true"></span>
						<span class="timestamp" title="最后活跃于">{{ $topic->updated_at->diffForHumans() }}</span>
					</div>
				</div>
			</li>

			@if ( !$loop->last)
				<hr>
			@endif

		@endforeach
	</ul>
@else 
	<div class="empty-block">暂无数据 。。</div>
@endif