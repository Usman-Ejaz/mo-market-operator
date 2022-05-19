<aside class="main-sidebar sidebar-dark-primary elevation-4">
	<!-- Brand Logo -->

	<a href="{{ route('admin.dashboard') }}" class="brand-link">
		<!-- <img src="{{ asset('Ismo-designstyle-boots-m.png') }}" alt="{{ config('app.name', 'ISMO') }}" class="brand-image img-circle elevation-3" style="opacity: .8"> -->
		<span class="ml-5 brand-text font-weight-light">{{ config('app.name', 'ISMO') }}</span>
	</a>

	<!-- Sidebar -->
	<div class="sidebar">
		<!-- Sidebar user panel (optional) -->
		<div class="user-panel mt-3 pb-3 mb-3 d-flex">
			<div class="image">
				@if(auth()->user()->image !== null)
				<img src="{{ Auth::user()->image }}" class="img-circle elevation-2" alt="{{ Auth::user()->name }} avatar">
				@endif
			</div>
			<div class="info">
				<a href="{{ route('admin.profile.show') }}" class="d-block">{{ Auth::user()->name }}</a>
			</div>
		</div>

		<!-- Sidebar Menu -->
		<nav class="mt-2">
			<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
				<!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
				<li class="nav-item">
					<a href="{{ route('admin.dashboard') }}" class="nav-link {{ Request::is('admin/dashboard') ? 'active' : '' }}">
						<i class="nav-icon fas fa-tachometer-alt"></i>
						<p>Dashboard</p>
					</a>
				</li>

				@if( hasPermission('clients', 'list'))
				<li class="nav-item">
					<a href="{{ route('admin.clients.index') }}" class="nav-link {{ Request::is('admin/clients*') ? 'active' : '' }}">
						<i class="nav-icon fa fa-user"></i>
						<p>Clients</p>
					</a>
				</li>
				@endif

				@if( hasPermission('posts', 'list') )
				<li class="nav-item">
					<a href="{{ route('admin.posts.index') }}" class="nav-link {{ (Request::is('admin/posts/*') || Request::is('admin/posts') ) ? 'active' : '' }}">
						<i class="nav-icon fa fa-newspaper"></i>
						<p>Posts</p>
					</a>
				</li>
				@endif

				@if( hasPermission('jobs', 'list') )
				<li class="nav-item">
					<a href="{{ route('admin.jobs.index') }}" class="nav-link {{ (Request::is('admin/jobs*') || Request::is('admin/applications*')) ? 'active' : '' }}">
						<i class="nav-icon fa fa-tasks"></i>
						<p>Jobs</p>
					</a>
				</li>
				@endif

				@if( hasPermission('documents', 'list') )
				<li class="nav-item">
					<a href="{{ route('admin.documents.index') }}" class="nav-link {{ (Request()->is('admin/documents*') || Request()->is('admin/document-categories*')) ? 'active' : '' }}">
						<i class="nav-icon fa fa-file"></i>
						<p>ISMO Library</p>
					</a>
				</li>
				@endif

				@if( hasPermission('pages', 'list') )
				<li class="nav-item">
					<a href="{{ route('admin.pages.index') }}" class="nav-link {{ Request()->is('admin/cms-pages*') ? 'active' : '' }}">
						<i class="nav-icon fa fa-pager"></i>
						<p> {{ __("CMS Pages") }} </p>
					</a>
				</li>
				@endif

				@if( hasPermission('newsletters', 'list') )
				<li class="nav-item">
					<a href="{{ route('admin.newsletters.index') }}" class="nav-link {{ (Request::is('admin/newsletters*') || Request::is('admin/subscribers*')) ? 'active' : '' }}">
						<i class="nav-icon fa fa-envelope-open"></i>
						<p> {{ __("Newsletters") }} </p>
					</a>
				</li>
				@endif

				@if( hasPermission('faqs', 'list') )
				<li class="nav-item">
					<a href="{{ route('admin.faqs.index') }}" class="nav-link {{ (Request()->is('admin/faqs*') || Request()->is('admin/faq-categories*')) ? 'active' : '' }}">
						<i class="nav-icon fa fa-question-circle"></i>
						<p>
							FAQs
						</p>
					</a>
				</li>
				@endif

				@if( hasPermission('media_library', 'list') )
				<li class="nav-item">
					<a href="{{ route('admin.media-library.index') }}" class="nav-link {{ Request()->is('admin/media-library*') ? 'active' : '' }}">
						<i class="nav-icon fa fa-photo-video"></i>
						<p>
							{{ __("Media Library") }}
						</p>
					</a>
				</li>
				@endif

				@if( hasPermission('slider_images', 'list') )
				<li class="nav-item">
					<a href="{{ route('admin.slider-images.index') }}" class="nav-link {{ (Request()->is('admin/slider-images*') || Request()->is('admin/slider-settings*'))? 'active' : '' }}">
						<i class="nav-icon fa fa-images"></i>
						<p>
							{{ __("Slider Images") }}
						</p>
					</a>
				</li>
				@endif

				@if(hasPermission('our_teams', 'list'))
				<li class="nav-item">
					<a href="{{ route('admin.managers.index') }}" class="nav-link {{ (Request()->is('admin/managers*') || Request()->is('admin/team-members*'))? 'active' : '' }}">
						<i class="nav-icon fa fa-user-friends"></i>
						<p>
							{{ __("Our Teams") }}
						</p>
					</a>
				</li>
				@endif

				@if(hasPermission('trainings', 'list'))
				<li class="nav-item">
					<a href="{{ route('admin.trainings.index') }}" class="nav-link {{ Request()->is('admin/trainings*') ? 'active' : '' }}">
						<i class="nav-icon fa fa-graduation-cap"></i>
						<p>
							{{ __("Trainings") }}
						</p>
					</a>
				</li>
				@endif

				@if (hasPermission('contact_page_queries', 'list'))
				<li class="nav-item">
					<a href="{{ route('admin.contact-page-queries.index') }}" class="nav-link {{ Request()->is('admin/contact-page-queries*') ? 'active' : '' }}">
						<i class="nav-icon fa fa-question-circle"></i>
						<p>
							{{ __("Contact Page Queries") }}
						</p>
					</a>
				</li>
				@endif

				@if (hasPermission('knowledge_base', 'list'))
				<li class="nav-item">
					<a href="{{ route('admin.knowledge-base.index') }}" class="nav-link {{ Request()->is('admin/knowledge-base*') ? 'active' : '' }}">
						<i class="nav-icon fa fa-database"></i>
						<p>
							Chatbot Knowledge Base
						</p>
					</a>
				</li>
				@endif

				@if(hasPermission('search_statistics', 'list'))
				<li class="nav-item">
					<a href="{{ route('admin.search-statistics.index') }}" class="nav-link {{ Request()->is('admin/search-statistics*') ? 'active' : '' }}">
						<i class="nav-icon fa fa-chart-line"></i>
						<p>
							Search Statistics
						</p>
					</a>
				</li>
				@endif

				@if(hasPermission('static_block', 'list'))
				<li class="nav-item">
					<a href="{{ route('admin.static-block.index') }}" class="nav-link {{ Request()->is('admin/static-block*') ? 'active' : '' }}">
						<i class="nav-icon fa fa-info-circle"></i>
						<p>
							Static Block
						</p>
					</a>
				</li>
				@endif

				@if(hasPermission('roles_and_permissions', 'list') || hasPermission('menus', 'list') || hasPermission('settings', 'list') || hasPermission('users', 'list'))
				<li class="nav-item {{ (request()->is('admin/roles*') || request()->is('admin/permissions*') || request()->is('admin/menus*') || request()->is('admin/site-configuration*') || request()->is('admin/users*')) ? 'menu-is-opening menu-open' : '' }}">
					<a href="#" class="nav-link">
						<i class="nav-icon fas fa-cogs"></i>
						<p>Settings <i class="fas fa-angle-left right"></i></p>
					</a>

					<ul class="nav nav-treeview">
						@if( hasPermission('users', 'list') )
						<li class="nav-item">
							<a href="{{ route('admin.users.index') }}" class="nav-link {{ Request::is('admin/users*') ? 'active' : '' }}">
								<i class="nav-icon fa fa-users"></i>
								<p>Users</p>
							</a>
						</li>
						@endif
						@if( hasPermission('roles_and_permissions', 'list'))
						<li class="nav-item">
							<a href="{{ route('admin.roles.index') }}" class="nav-link {{ (Request()->is('admin/roles*') || Request()->is('admin/permissions*')) ? 'active' : '' }}">
								<i class="fa fa-user-tag nav-icon"></i>
								<p>Roles & Permissions</p>
							</a>
						</li>
						@endif

						<!-- @if( hasPermission('permissions', 'view') )
							<li class="nav-item">
								<a href="{{ route('admin.permissions.index') }}" class="nav-link {{ Request()->is('admin/permissions*') ? 'active' : '' }}">
									<i class="fa fa-lock nav-icon"></i>
									<p>Permissions</p>
								</a>
							</li>
						@endif -->

						@if( hasPermission('menus', 'list') )
						<li class="nav-item">
							<a href="{{ route('admin.menus.index') }}" class="nav-link {{ Request()->is('admin/menus*') ? 'active' : '' }}">
								<i class="fa fa-compass nav-icon"></i>
								<p>Menus</p>
							</a>
						</li>
						@endif

						@if( hasPermission('settings', 'list') )
						<li class="nav-item">
							<a href="{{ route('admin.site-configuration.index') }}" class="nav-link {{ Request()->is('admin/site-configuration*') ? 'active' : '' }}">
								<i class="fa fa-cog nav-icon"></i>
								<p>Site Configuration</p>
							</a>
						</li>
						@endif
					</ul>
				</li>
				@endif

			</ul>
		</nav>
		<!-- /.sidebar-menu -->
	</div>
	<!-- /.sidebar -->
</aside>