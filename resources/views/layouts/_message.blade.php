@foreach (['info', 'success', 'danger'] as $msg)
	@if (Session::has($msg))
		<div class="alert alert-{{ $msg }}">
			<button class="close" type="button" data-dismiss="alert" aria-hidden="true">x</button>
			{{ Session::get($msg) }}
		</div>
	@endif
@endforeach