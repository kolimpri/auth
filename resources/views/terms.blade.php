@extends('auth::layouts.auth')

<!-- Main Content -->
@section('content')
<div id="auth-terms-screen" class="container-fluid auth-screen">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel panel-default">
				<div class="panel-heading">
					Terms Of Service
				</div>

				<div class="panel-body">
					{!! $terms !!}
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
