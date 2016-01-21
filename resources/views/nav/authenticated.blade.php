<nav class="navbar navbar-default">
	<div class="container">
		<div class="navbar-header">

			<!-- Collapsed Hamburger -->
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#auth-navbar-collapse">
				<span class="sr-only">Toggle Navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>

			<!-- Branding Image -->
			<a class="navbar-brand" href="/" style="padding-top: 19px;">
				<i class="fa fa-btn fa-sun-o"></i>Auth
			</a>
		</div>

		<div class="collapse navbar-collapse" id="auth-navbar-collapse">
			<!-- Left Side Of Navbar -->
			<ul class="nav navbar-nav">
				<li><a href="/home">Home</a></li>

				@if ( ! kAuth::isDisplayingSettingsScreen())
					<!-- Additional User Defined Navbar Items -->
					<!-- Best To Leave Left Side Nav Empty On Settings To Avoid Vue.js Conflicts -->
				@endif
			</ul>

			<!-- Right Side Of Navbar -->
			<ul class="nav navbar-nav navbar-right">

				<!-- Settings Dropdown -->
				@if (kAuth::isDisplayingSettingsScreen())
					{{-- This Dropdown Is For Auth Settings Sreens - Vue Based --}}
					{{-- Vue Based Dropdown Provides Better UX Experience On Settings Screens --}}
					@include('auth::nav.auth.dropdown')
				@else
					{{-- This Dropdown Is For Other User Constructed App Screens - Blade Based --}}
					@include('auth::nav.app.dropdown')
				@endif
			</ul>
		</div>
	</div>
</nav>
