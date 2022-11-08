<!-- Navbar -->
@php 
  $users = Auth::user();
  $user_role = [
    1 => 'Administrator',
    2 => 'Player',
    3 => 'Declarator',
  ];
@endphp
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <!-- <a href="/home" class="nav-link">Home</a> -->
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      

    <li class="nav-item dropdown">
        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
            {{ $users->username }}  ({{ $user_role[$users->user_type_id] }})
        </a>

        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown" style="right: 0 !important;">
          @if($users->user_type_id == 5)
            <a class="dropdown-item" href='/update-password'><i class="fa fa-cog"></i>
                Change Password
            </a>
          @endif
            <a class="dropdown-item" href="{{ route('logout') }}"
                onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();"><i class="fa fa-sign-out-alt"></i>
                {{ __('Logout') }}
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </div>
        <div class="dropdown">
    </li>
      
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link text-center">
      <!-- <img src="dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8"> -->
      <h3 class="brand-text font-weight-light">TRIODIRETSO</h3>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{ asset('dist/img/default-150x150.png') }}" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">{{ $users->first_name . ' ' . $users->last_name }}</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
         
          <li class="nav-item">
            <a href="/home" class="nav-link {{ (request()->is('home')) ? 'active' : '' }}">
              <i class="nav-icon fas fa-th"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>
          
          <li class="nav-item">
            <a href="/users" class="nav-link {{ (request()->is('users*')) ? 'active' : '' }}">
              <i class="nav-icon fa fa-users"></i>
              <p>
                Users
              </p>
            </a>
          </li>
          
          <li class="nav-item">
            <a href="/admin/schedule" class="nav-link {{ (request()->is('admin/schedule*')) ? 'active' : '' }}">
              <i class="nav-icon fa fa-calendar"></i>
              <p>
                Schedule
              </p>
            </a>
          </li>

          <li class="nav-item {{ (request()->is('helpdesk*')) ? 'menu-is-opening menu-open' : 'menu-close' }}">
            <a href="#" class="nav-link {{ (request()->is('helpdesk*')) ? 'active' : '' }}">
              <i class="nav-icon fas fa-file"></i>
              <p>Reports<i class="right fas fa-angle-left"></i></p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="/helpdesk/for-review" class="nav-link {{ (request()->is('helpdesk/for-review*')) ? 'custom-active' : '' }}">
                <i class="far fa-address-card  nav-icon"></i>
                <p>Details</p>
                </a>
              </li>
            </ul>
          </li>
          
          <li class="nav-item">
            <a href="/activity-logs" class="nav-link {{ (request()->is('activity-logs*')) ? 'active' : '' }}">
              <i class="nav-icon fa fa-list-ol"></i>
              <p>
                Activity Logs
              </p>
            </a>
          </li>
          
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>