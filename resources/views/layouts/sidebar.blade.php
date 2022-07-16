  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-info elevation-4 custom-siderbar-dark">

	<a class="brand-link" href="{{ route('dashboard') }}">
		<img src="{{ asset('images/logo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
           style="opacity: .8">
		<span class="visiblilty-hidden"> {{ config('app.name', 'Revage Solution') }} </span>

	</a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel d-none mt-3 pb-3 mb-3 d-flexd">
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
{{--            {{ dd(auth()->user())}}--}}


{{--            @if(auth()->user()->can('view-dashboard')  || auth()->user()->hasRole('super-admin'))--}}
          <li class="nav-item has-treeview  {{ ( Request::segment(2) == 'dashboard' || Request::segment(2) == '' ) ? 'menu-open' : '' }} ">
            <a href="{{ route('dashboard') }}" class="nav-link  {{ ( Request::segment(2) == 'dashboard' || Request::segment(2) == '' ) ? 'active' : '' }} ">
                <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>
{{--        @endif--}}


            @if(auth()->user()->can('view-users') OR auth()->user()->can('view-roles') OR auth()->user()->can('view-permissions') || auth()->user()->hasRole('super-admin'))
            <li class="nav-item   {{(Request::segment(2) == 'users' || Request::segment(2) == 'roles' || Request::segment(2) == 'permissions')  ? 'menu-open' : 'hello'}}">

                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-users "></i>
                    <p>
                        User Administrator
                        <i class="fas fa-angle-left right"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview {{ ( Request::segment(2) == 'users' || Request::segment(2) == 'roles' ) ? 'menu-open' : '' }}">

                    @if(auth()->user()->can('view-users') || auth()->user()->hasRole('super-admin'))
                    <li class="nav-item">
                        <a class="nav-link {{ (Request::segment(2) == 'users') ? 'active' : '' }}" href="{{ url('admin/users') }}">
                            <i class="nav-icon fa fa-minus"></i>
                            <p>
                                Users
                            </p>
                        </a>
                    </li>
                    @endif
                        @if(auth()->user()->can('view-roles') || auth()->user()->hasRole('super-admin'))
                    <li class="nav-item">
                        <a class="nav-link {{ (Request::segment(2) == 'roles') ? 'active' : '' }}" href="{{ url('admin/roles') }}">
                            <i class="nav-icon fa fa-minus"></i>
                            <p>
                                Roles
                            </p>
                        </a>
                    </li>
                        @endif
                            @if(auth()->user()->can('view-permissions') || auth()->user()->hasRole('super-admin'))
                    <li class="nav-item">
                        <a class="nav-link {{ (Request::segment(2) == 'permissions') ? 'active' : '' }}" href="{{ url('admin/permissions') }}">
                            <i class="nav-icon fa fa-minus"></i>
                            <p>
                                Permissions
                            </p>
                        </a>
                    </li>
                        @endif

                </ul>
            </li>
            @endif

			@if(auth()->user()->can('view-sports') || auth()->user()->can('view-leagues') || auth()->user()->can('view-teams') || auth()->user()->can('view-schedules') || auth()->user()->can('view-servers') || auth()->user()->hasRole('super-admin'))
            <li class="nav-header py-3">SPORTS MANAGEMENT </li>
                @if(auth()->user()->can('view-sports')  || auth()->user()->hasRole('super-admin'))
                    <li class="nav-item">
                        <a href="{{ url('admin/sports') }}" class="nav-link {{ (Request::segment(2) == 'sports') ? 'active' : '' }}">
                            <!-- <i class="far fa fa-life-ring nav-icon"></i> -->
							<img src="{{ asset('dist/img/sidebar-icons/sports.png') }}" class="elevation-2 "/>
                            <p>Sports</p>
                        </a>
                    </li>
                @endif
                @if(auth()->user()->can('view-leagues')  || auth()->user()->hasRole('super-admin'))
                    <li class="nav-item">
                        <a href="{{ url('admin/leagues') }}" class="nav-link {{ (Request::segment(2) == 'leagues') ? 'active' : '' }}">
							<img src="{{ asset('dist/img/sidebar-icons/league.png') }}" class="elevation-2 "/>
                            <p>Leagues</p>
                        </a>
                    </li>
                @endif
                @if(auth()->user()->can('view-teams') ||  auth()->user()->hasRole('super-admin'))
                   <li class="nav-item custom {{Request::segment(2) == 'teams' ? 'menu-open' : ''}}">
                <a href="#" class="nav-link">
				<img src="{{ asset('dist/img/sidebar-icons/team.png') }}" class="elevation-2 "/>
                    <p>
                        Teams
                        <i class="fas fa-angle-left right"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    @php $sportsList = \App\Models\Sports::all(); @endphp
                    @foreach($sportsList as $sport)
                        <li class="nav-item">
                            <a href="{{ url('admin/teams/'.$sport->id) }}" class="nav-link custom {{Request::segment(3) == $sport->id ? 'active' : ''}}">
                                <i class="far fa fa-minus nav-icon text-sm"></i>
                                <p class="text-capitalize">{{$sport->name}}</p>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </li>
                @endif

				@if(auth()->user()->can('view-schedules')  || auth()->user()->hasRole('super-admin'))
                    <li class="nav-item custom {{Request::segment(2) == 'schedules' ? 'menu-open' : ''}}">
                        <a href="#" class="nav-link">
							<img src="{{ asset('dist/img/sidebar-icons/schedule.png') }}" class="elevation-2 "/>
                            <p>
                                Schedule
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
						<ul class="nav nav-treeview">
							@php $sportsList = \App\Models\Sports::all(); @endphp
							@foreach($sportsList as $sport)
								<li class="nav-item">
									<a href="{{ url('admin/schedules/'.$sport->id) }}" class="nav-link  custom {{Request::segment(3) == $sport->id ? 'active' : ''}}">
										<i class="far fa fa-minus nav-icon text-sm"></i>
										<p class="text-capitalize">{{$sport->name}}</p>
									</a>
								</li>
							@endforeach
						</ul>
					</li>
				@endif


            @endif

				@if(auth()->user()->can('view-servers')  || auth()->user()->hasRole('super-admin'))
					<li class="nav-item">
						<a href="{{ url('admin/servers') }}" class="nav-link {{ (Request::segment(2) == 'servers') ? 'active' : '' }}">
							<i class="far fa fa-server nav-icon"></i>
							<p>Live Servers</p>
						</a>
					</li>
				@endif

                @if(auth()->user()->can('view-applications') || auth()->user()->can('view-sponsors') || auth()->user()->can('view-admob_ads'))

                <li class="nav-header py-3">CONFIGURATION </li>

                <li class="nav-item">
                    <a href="{{ url('admin/app') }}" class="nav-link {{ (Request::segment(2) == 'app') ? 'active' : '' }}">
                        <i class="fa fa-mobile nav-icon"></i>
                        <p>Applications</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('admin/sponsors') }}" class="nav-link {{ (Request::segment(2) == 'sponsors') ? 'active' : '' }}">
                        <i class="fa fa-ad nav-icon"></i>
                        <p>Sponsors</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('admin/admob_ads') }}" class="nav-link {{ (Request::segment(2) == 'admob_ads') ? 'active' : '' }}">
                        <i class="fa fa-ad nav-icon"></i>
                        <p>Admob Ads</p>
                    </a>
                </li>

            @endif




            <!-- Authentication Links -->
		@guest
			<li class="nav-item"><a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a></li>
			<li><a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a></li>
		@else
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

