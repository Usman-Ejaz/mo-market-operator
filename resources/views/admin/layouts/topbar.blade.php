<nav class="main-header navbar navbar-expand navbar-white navbar-light">
	<!-- Left navbar links -->
	<ul class="navbar-nav">
		@yield('breadcrumbs')
	</ul>

	<!-- Navbar Search -->
	<!--div class="form-inline col-lg-8">
      <div class="input-group col-lg-12" data-widget="sidebar-search">
        <input class="form-control form-control-sidebar col-lg-12" type="search" placeholder="Search" aria-label="Search">
        <div class="input-group-append">
          <button class="btn btn-sidebar">
            <i class="fas fa-search fa-fw"></i>
          </button>
        </div>
      </div><div class="sidebar-search-results"><div class="list-group"><a href="#" class="list-group-item"><div class="search-title"><strong class="text-light"></strong>N<strong class="text-light"></strong>o<strong class="text-light"></strong> <strong class="text-light"></strong>e<strong class="text-light"></strong>l<strong class="text-light"></strong>e<strong class="text-light"></strong>m<strong class="text-light"></strong>e<strong class="text-light"></strong>n<strong class="text-light"></strong>t<strong class="text-light"></strong> <strong class="text-light"></strong>f<strong class="text-light"></strong>o<strong class="text-light"></strong>u<strong class="text-light"></strong>n<strong class="text-light"></strong>d<strong class="text-light"></strong>!<strong class="text-light"></strong></div><div class="search-path"></div></a></div></div>
    </div-->


	<!-- Right navbar links -->
	<ul class="navbar-nav ml-auto">

		<!-- Notifications Dropdown Menu -->
		@if (auth()->user()->show_notifications === 1)
			<li class="nav-item dropdown">
				<a class="nav-link" data-toggle="dropdown" href="#">
					<i class="far fa-bell"></i>
					<span class="badge badge-warning navbar-badge">{{ auth()->user()->unreadNotifications()->count() }}</span>
				</a>
				<div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
					<span class="dropdown-item dropdown-header">{{ auth()->user()->unreadNotifications()->count() }} {{ __("Notifications") }}</span>

					@forelse (auth()->user()->unreadNotifications as $notification)
						<div class="dropdown-divider"></div>
						<a href="{{ $notification->data['link'] }}?notification={{ $notification->id }}" class="dropdown-item">
							<i class="fas fa-envelope mr-2"></i>
							{{ truncateWords($notification->data['title'], 15) }}
							<span class="float-right text-muted text-sm">{{ $notification->created_at->diffForHumans() }}</span>
						</a>
						@if ($loop->last)
							<div class="dropdown-divider"></div>
							<a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
						@endif
					@empty
						<div class="dropdown-divider"></div>
						<a href="javascript:void(0);" class="dropdown-item">
							<i class="fas fa-envelope mr-2"></i> No new unread notifications
							<span class="float-right text-muted text-sm"></span>
						</a>
					@endforelse				
				</div>
			</li>
			<li class="nav-item">
				<a class="nav-link" data-widget="fullscreen" href="#" role="button">
					<i class="fas fa-expand-arrows-alt"></i>
				</a>
			</li>
		@endif		
		<!-- <li class="nav-item">
        <button type="submit" class="btn btn-default mr-2">
          <i class="fas fa-sign-out-alt"></i> Change Password
        </button>
      </li> -->
		<li class="nav-item">
			<a href="{{ route('admin.update-password') }}" class="btn btn-default mr-2">
				<i class="fas fa-key"></i> Change Password
			</a>
		</li>
		<li class="nav-item">

			<form method="POST" action="{{ route('admin.logout') }}">
				@csrf
				<button type="submit" class="btn btn-default">
					<i class="fas fa-sign-out-alt"></i> Logout
				</button>
			</form>
		</li>
	</ul>
</nav>