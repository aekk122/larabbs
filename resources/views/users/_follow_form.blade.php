@if (Auth::user()->id !== $user->id)
	<div class="follow_form">
		@if (Auth::user()->isFollowing($user->id))
			<form action="{{ route('unfollow', $user->id) }}" method="POST">
				{{ csrf_field() }}
				{{ method_field('DELETE') }}
				<button class="btn btn-sm" type="submit">取消关注</button>
			</form>
		@else
			<form action="{{ route('follow', $user->id) }}" method="POST">
				{{ csrf_field() }}
				<button class="btn btn-sm btn-primary" type="submit">关注</button>
			</form>
		@endif
	</div>

@endif		