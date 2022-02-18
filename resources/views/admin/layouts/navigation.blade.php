<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->

    <a href="{{ route('admin.dashboard') }}" class="brand-link">
      <img src="{{ asset('Ismo-designstyle-boots-m.png') }}" alt="{{ config('app.name', 'ISMO') }}" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">{{ config('app.name', 'ISMO') }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{ Auth::user()->image }}" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">{{ Auth::user()->name }}</a>
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

          @if( Auth::user()->role->hasPermission('users', 'list') )
              <li class="nav-item">
                <a href="{{ route('admin.users.index') }}" class="nav-link {{ Request::is('admin/users*') ? 'active' : '' }}">
                  <i class="nav-icon fa fa-users"></i>
                  <p>Users</p>
                </a>
              </li>
          @endif

          @if( Auth::user()->role->hasPermission('news', 'list') )
          <li class="nav-item">
            <a href="{{ route('admin.news.index') }}" class="nav-link {{ (Request::is('admin/news/*') || Request::is('admin/news') ) ? 'active' : '' }}">
              <i class="nav-icon fa fa-newspaper"></i>
              <p>News</p>
            </a>
          </li>
          @endif

          @if( Auth::user()->role->hasPermission('jobs', 'list') )
          <li class="nav-item">
                <a href="{{ route('admin.jobs.index') }}" class="nav-link {{ (Request::is('admin/jobs*') || Request::is('admin/applications*')) ? 'active' : '' }}">
                    <i class="nav-icon fa fa-newspaper"></i>
                    <p>Jobs</p>
                </a>
          </li>
          @endif

          @if( Auth::user()->role->hasPermission('documents', 'list') )
          <li class="nav-item">
          <a href="{{ route('admin.documents.index') }}" class="nav-link {{ (Request()->is('admin/documents*') || Request()->is('admin/document-categories*')) ? 'active' : '' }}">
              <i class="nav-icon fa fa-file"></i>
              <p>ISMO Library</p>
            </a>
          </li>
          @endif

          @if( Auth::user()->role->hasPermission('pages', 'list') )
          <li class="nav-item">
            <a href="{{ route('admin.pages.index') }}" class="nav-link {{ Request()->is('admin/pages*') ? 'active' : '' }}">
              <i class="nav-icon fa fa-pager"></i>
              <p>
                Pages
              </p>
            </a>
          </li>
          @endif

          @if( Auth::user()->role->hasPermission('newsletters', 'list') )
          <li class="nav-item">
            <a href="{{ route('admin.newsletters.index') }}" class="nav-link {{ (Request::is('admin/newsletters*') || Request::is('admin/subscribers*')) ? 'active' : '' }}">
              <i class="nav-icon fa fa-envelope-open"></i>
              <p>Newsletters</p>
            </a>
          </li>
          @endif

          @if( Auth::user()->role->hasPermission('faqs', 'list') )
          <li class="nav-item">
            <a href="{{ route('admin.faqs.index') }}" class="nav-link {{ Request()->is('admin/faqs*') ? 'active' : '' }}">
              <i class="nav-icon fa fa-question-circle"></i>
              <p>
                FAQ
              </p>
            </a>
          </li>
          @endif

          @if( Auth::user()->role->hasPermission('contact-page-queries', 'list') )
          <li class="nav-item">
            <a href="{{ route('admin.contact-page-queries.index') }}" class="nav-link {{ Request()->is('admin/contact-page-queries*') ? 'active' : '' }}">
              <i class="nav-icon fa fa-question-circle"></i>
              <p>
                Contact Page Queries
              </p>
            </a>
          </li>
          @endif
          
          @if(Auth::user()->role->hasPermission('roles', 'list') || Auth::user()->role->hasPermission('permissions', 'view') || Auth::user()->role->hasPermission('menus', 'list') || Auth::user()->role->hasPermission('settings', 'list'))
          <li class="nav-item {{ (request()->is('admin/roles*') || request()->is('admin/permissions*') || request()->is('admin/menus*') || request()->is('admin/settings*')) ? 'menu-is-opening menu-open' : '' }}">
            <a href="#" class="nav-link">
                <i class="nav-icon fas fa-cogs"></i>
                <p>Management <i class="fas fa-angle-left right"></i></p>
            </a>
            <ul class="nav nav-treeview">
                @if( Auth::user()->role->hasPermission('roles', 'list') )
                    <li class="nav-item">
                        <a href="{{ route('admin.roles.index') }}" class="nav-link {{ Request()->is('admin/roles*') ? 'active' : '' }}">
                            <i class="fa fa-user-tag nav-icon"></i>
                            <p>Roles</p>
                        </a>
                    </li>
                @endif

                @if( Auth::user()->role->hasPermission('permissions', 'view') )
                    <li class="nav-item">
                        <a href="{{ route('admin.permissions.index') }}" class="nav-link {{ Request()->is('admin/permissions*') ? 'active' : '' }}">
                            <i class="fa fa-lock nav-icon"></i>
                            <p>Permissions</p>
                        </a>
                    </li>
                @endif

                @if( Auth::user()->role->hasPermission('menus', 'list') )
                    <li class="nav-item">
                        <a href="{{ route('admin.menus.index') }}" class="nav-link {{ Request()->is('admin/menus*') ? 'active' : '' }}">
                            <i class="fa fa-compass nav-icon"></i>
                            <p>Menus</p>
                        </a>
                    </li>
                @endif

                @if( Auth::user()->role->hasPermission('settings', 'list') )
                    <li class="nav-item">
                        <a href="{{ route('admin.settings.index') }}" class="nav-link {{ Request()->is('admin/settings*') ? 'active' : '' }}">
                            <i class="fa fa-cog nav-icon"></i>
                            <p>Settings</p>
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
