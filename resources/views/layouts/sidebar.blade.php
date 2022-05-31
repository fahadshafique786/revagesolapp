  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-maroon elevation-4 custom-siderbar-dark">

	<a class="brand-link" href="{{ url('/') }}">
		<img src="{{ asset('images/logo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
           style="opacity: .8">
		<span class="visiblilty-hidden"> {{ config('app.name', 'Revage Solution') }} </span>

	</a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{ asset('dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2" alt="User Image">
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
          <li class="nav-item has-treeview  {{ ( Request::segment(2) == 'dashboard' || Request::segment(2) == '' ) ? 'menu-open' : '' }} ">
            <a href="{{ route('dashboard') }}" class="nav-link  {{ ( Request::segment(2) == 'dashboard' || Request::segment(2) == '' ) ? 'active' : '' }} ">
                <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>
		<!-- Authentication Links -->
		@guest
			<li class="nav-item"><a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a></li>
			<li><a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a></li>
		@else
{{--			<li class="nav-item"><a class="nav-link {{ (Request::segment(2) == 'users') ? 'active' : '' }}" href="{{ url('admin/users') }}">Users</a></li>--}}
{{--			<li class="nav-item"><a class="nav-link {{ (Request::segment(2) == 'categories') ? 'active' : '' }}" href="{{ url('admin/categories') }}">Categories</a></li>--}}
{{--			<li class="nav-item"><a class="nav-link {{ (Request::segment(2) == 'faqs') ? 'active' : '' }}" href="{{ url('admin/faqs') }}">FAQS</a></li>--}}
{{--			<li class="nav-item"><a class="nav-link {{ (Request::segment(2) == 'tutorials') ? 'active' : '' }}" href="{{ url('admin/tutorials') }}">Tutorials</a></li>--}}
{{--			<li class="nav-item"><a class="nav-link {{ (Request::segment(2) == 'blogs') ? 'active' : '' }}" href="{{ url('admin/blogs') }}">Blogs</a></li>--}}
{{--			<li class="nav-item"><a class="nav-link {{ (Request::segment(2) == 'questions') ? 'active' : '' }}" href="{{ url('admin/questions') }}">Questions</a></li>--}}
		@endguest


          </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

