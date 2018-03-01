@if (count($replies))

<ul class="list-group">
	@foreach ($replies as $reply)
		<li class="list-group-item">
			<a href="{{ $reply->belongsToTopic->link(['#reply' . $reply->id]) }}">
				{{ $reply->belongsToTopic->title }}
			</a>

			<div class="reply-content" style="margin: 6px 0;">
				{!! $reply->content !!}
			</div>

			<div class="meta">
				<span class="glyphicon glyphcion-time" aria-hidden="true"></span> 回复于 {{ $reply->created_at->diffForHumans() }}
			</div>
		</li>
	@endforeach
</ul>

@else

<div class="empty-block">暂无数据~~~~</div>

@endif

{{-- 分页 --}}
{!! $replies->appends(Request::except('page'))->render() !!}