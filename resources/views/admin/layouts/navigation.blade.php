<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->

    <a href="{{ route('admin.dashboard') }}" class="brand-link">
      <img src="{{ asset('storage/images/AdminLTELogo.png') }}" alt="{{ config('app.name', 'ISMO') }}" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">{{ config('app.name', 'ISMO') }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{url( config('filepaths.userProfileImagePath.public_path').Auth::user()->image ) }}" class="img-circle elevation-2" alt="User Image">
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
            <a href="{{ Route('admin.dashboard') }}" class="nav-link {{ Request()->is('admin/dashboard') ? 'active' : '' }}">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>Dashboard</p>
            </a>
          </li>

          @if( Auth::user()->role->hasPermission('users', 'list') )
              <li class="nav-item">
                <a href="{{ Route('admin.users.index') }}" class="nav-link {{ Request::is('admin/users*') ? 'active' : '' }}">
                  <i class="nav-icon fa fa-users"></i>
                  <p>Users</p>
                </a>
              </li>
          @endif

          @if( Auth::user()->role->hasPermission('news', 'list') )
          <li class="nav-item">
            <a href="{{ Route('admin.news.index') }}" class="nav-link {{ Request::is('admin/news*') ? 'active' : '' }}">
              <i class="nav-icon fa fa-newspaper"></i>
              <p>News</p>
            </a>
          </li>
          @endif

          <li class="nav-item">
                <a href="{{ Route('admin.jobs.index') }}" class="nav-link {{ Request()->is('admin/jobs*') ? 'active' : '' }}">
                    <i class="nav-icon fa fa-newspaper"></i>
                    <p>Jobs</p>
                </a>
          </li>
          <li class="nav-item">
          <a href="{{ Route('admin.documents.index') }}" class="nav-link {{ Request()->is('admin/documents/*') ? 'active' : '' }}">
              <i class="nav-icon fa fa-file"></i>
              <p>
                ISMO Library
              </p>
            </a>
          </li>
          @if( Auth::user()->role->hasPermission('pages', 'list') )
          <li class="nav-item">
            <a href="{{ Route('admin.pages.index') }}" class="nav-link {{ Request()->is('admin/pages/*') ? 'active' : '' }}">
              <i class="nav-icon fa fa-pager"></i>
              <p>
                Pages
              </p>
            </a>
          </li>
          @endif

          @if( Auth::user()->role->hasPermission('faqs', 'list') )
          <li class="nav-item">
            <a href="{{ Route('admin.faqs.index') }}" class="nav-link {{ Request()->is('admin/faqs/*') ? 'active' : '' }}">
              <i class="nav-icon fa fa-question-circle"></i>
              <p>
                FAQ
              </p>
            </a>
          </li>
          @endif

          <li class="nav-item">
            <a href="#" class="nav-link">
                <i class="nav-icon fas fa-cogs"></i>
                <p>Settings <i class="fas fa-angle-left right"></i></p>
            </a>
            <ul class="nav nav-treeview">
                @if( Auth::user()->role->hasPermission('roles', 'list') )
                    <li class="nav-item">
                        <a href="{{ Route('admin.roles.index') }}" class="nav-link {{ Request()->is('admin/roles*') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Roles</p>
                        </a>
                    </li>
                @endif

                @if( Auth::user()->role->hasPermission('permissions', 'view') )
                    <li class="nav-item">
                        <a href="{{ Route('admin.permissions.index') }}" class="nav-link {{ Request()->is('admin/permissions*') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Permissions</p>
                        </a>
                    </li>
                @endif

                @if( Auth::user()->role->hasPermission('menus', 'list') )
                    <li class="nav-item">
                        <a href="{{ Route('admin.menus.index') }}" class="nav-link {{ Request()->is('admin/menus*') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Menus</p>
                        </a>
                    </li>
                @endif
            </ul>
          </li>


        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
