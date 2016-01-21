@extends('auth::layouts.auth')

<!-- Scripts -->
@section('scripts')
	<script src="//cdnjs.cloudflare.com/ajax/libs/URI.js/1.15.2/URI.min.js"></script>
@endsection

<!-- Main Content -->
@section('content')
<auth-simple-registration-screen inline-template>
	<div id="auth-register-screen" class="container-fluid auth-screen">
		<!-- Invitation -->
		<div class="row">
			<div class="col-md-6 col-md-offset-3">
				@include('auth::auth.registration.subscription.invitation')
			</div>
		</div>

		<!-- Basic Information -->
		<div class="row">
			<div class="col-md-6 col-md-offset-3">
				@include('auth::auth.registration.simple.basics')
			</div>
		</div>
	</div>
</auth-simple-registration-screen>
@endsection
