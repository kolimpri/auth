<div class="panel panel-default">
	<div class="panel-heading">Register</div>
	<div class="panel-body">
		@include('auth::common.errors', ['form' => 'default'])

		<form class="form-horizontal" role="form" method="POST" action="{{ url('/register') }}">
			{!! csrf_field() !!}

			<input type="hidden" name="invitation" value="{{ Input::get('invitation') }}">

			@if (kAuth::usingTeams())

				<div class="form-group" v-if=" ! invitation">
					<label class="col-md-4 control-label">Team Name</label>
					<div class="col-md-6">
						<input type="text" class="form-control auth-first-field" name="team_name">
					</div>
				</div>
			@endif

			<div class="form-group">
				<label class="col-md-4 control-label">Name</label>
				<div class="col-md-6">
					<input type="text" class="form-control auth-first-field" name="name" value="{{ old('name') }}">
				</div>
			</div>

			<div class="form-group">
				<label class="col-md-4 control-label">E-Mail Address</label>
				<div class="col-md-6">
					<input type="email" class="form-control" name="email" value="{{ old('email') }}">
				</div>
			</div>

			<div class="form-group">
				<label class="col-md-4 control-label">Password</label>
				<div class="col-md-6">
					<input type="password" class="form-control" name="password">
				</div>
			</div>

			<div class="form-group">
				<label class="col-md-4 control-label">Confirm Password</label>
				<div class="col-md-6">
					<input type="password" class="form-control" name="password_confirmation">
				</div>
			</div>

			<div class="form-group">
				<div class="col-sm-6 col-sm-offset-4">
					<div class="checkbox">
						<label>
							<input type="checkbox" name="terms"> I Accept The <a href="/terms" target="_blank">Terms Of Service</a>
						</label>
					</div>
				</div>
			</div>

			<div class="form-group">
				<div class="col-sm-6 col-sm-offset-4">
					<button type="submit" class="btn btn-primary">
						<i class="fa fa-btn fa-sign-in"></i>Register
					</button>
				</div>
			</div>
		</form>
	</div>
</div>
