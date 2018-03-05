@if (count($follows))
	
		@foreach ($follows as $follow)
			<a href="{{ route('users.show', $follow->id) }}" class="media">
					<div class="media-left media-middle">
						<img src="{{ $follow->avatar }}" width="24px" height="24px" class="img-circle media-object">
					</div>

					<div class="media-body">
						<span class="media-heading">{{ $follow->name }}</span>
					</div>
			</a>
			
		@endforeach
	
@endif