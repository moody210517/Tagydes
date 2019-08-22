<!-- begin #sidebar -->
<div id="sidebar" class="sidebar">
	<!-- begin sidebar scrollbar -->
	<div data-scrollbar="true" data-height="100%">
		<!-- begin sidebar user -->
		<ul class="nav">
			<li class="nav-profile">
				<a href="javascript:;" data-toggle="nav-profile">
					<div class="image">
						<img  class="rounded-circle img-responsive"
						width="40"
						src="{{ auth()->user()->present()->avatar }}"
						alt="{{ auth()->user()->first_name }}">
					</div>
					<div class="info">
						<b class="caret pull-right"></b>
						{{ auth()->user()->first_name }}
						<small>{{ auth()->user()->role()->first()->name }}</small>
					</div>
				</a>
			</li>
			<li>
				<ul class="nav nav-profile">
					<li><a href="javascript:;"><i class="fa fa-cog"></i> Settings</a></li>
					<li><a href="javascript:;"><i class="fa fa-pencil-alt"></i> Send Feedback</a></li>
					<li><a href="javascript:;"><i class="fa fa-question-circle"></i> Helps</a></li>
				</ul>
			</li>
		</ul>
		<!-- end sidebar user -->
		<!-- begin sidebar nav -->
		<ul class="nav">
			<li class="nav-header">Navigation</li>
			<li class="{{ Request::is('/') ? 'active' : ''  }}">
				<a href="{{ route('dashboard') }}">
					<i class="fa fa-th-large"></i> 
					<span>@lang('app.dashboard')</span> 
				</a>
			</li>

			<!-- begin operations menu -->
			<li class="has-sub {{ Request::is('reseller*') || Request::is('customer*') || Request::is('subscription*') || Request::is('order*') || Request::is('task*') || Request::is('news*')|| Request::is('branch*') ? 'active' : ''  }}">
				<a href="javascript:;">
					<b class="caret"></b>
					<i class="fa fa-cloud"></i>
					<span>@lang('app.operations')</span>
				</a>
				<ul class="sub-menu">
					@permission('reseller.manage')
					<li class="{{ Request::is('reseller*') ? 'active' : ''  }}">
						<a href="{{ route('reseller.list') }}">
							<i class="fas fa-user"></i>
							<span> @lang('app.resellers')</span>
						</a>
					</li>
					@endpermission

					
					<li class="{{ Request::is('customer*') ? 'active' : ''  }}">
						<a href="{{ route('customer.list') }}">
							<i class="fas fa-building"></i>
							<span> @lang('app.company')</span>
						</a>
					</li>
					

					<li class="{{ Request::is('subscription*') ? 'active' : ''  }}">
						<a href="javascript:;">
							<i class="fas fa-cloud"></i>
							<span>@lang('app.subscriptions')</span>
						</a>
					</li>
					<li class="has-sub {{ Request::is('reseller*') || Request::is('customer*') || Request::is('subscription*') || Request::is('order*') || Request::is('task*') || Request::is('news*')|| Request::is('branch*') ? 'active' : ''  }}">
							<a href="javascript:;">
								<b class="caret"></b>
								<i class="fa fa-cloud"></i>
								<span>@lang('app.branch_offices')</span>
							</a>
					<ul class="sub-menu">
					@permission('reseller.manage.branch')
					<li class="{{ Request::is('branch*') ? 'active' : ''  }}">
						<a href="{{ route('reseller.list') }}">
							<i class="fas fa-users"></i>
							<span>Lisbon</span>
						</a>
					</li>
					@endpermission

					@permission('reseller.manage.branch')
					<li class="{{ Request::is('branch*') ? 'active' : ''  }}">
						<a href="{{ route('branch.list') }}">
							<i class="fas fa-users"></i>
							<span>Madrid</span>
						</a>
					</li>
					@endpermission

					{{-- <li class="{{ Request::is('order*') ? 'active' : ''  }}">
						<a href="javascript:;">@lang('app.orders')</a>
					</li> --}}
				</ul>
					<li class="{{ Request::is('order*') ? 'active' : ''  }}">
							<a href="javascript:;">
								<i class="fas fa-laptop"></i>
								<span>@lang('app.orders')</span>
							</a>
						</li>

					<li class="{{ Request::is('task*') ? 'active' : ''  }}">
						<a href="javascript:;">
							<i class="fas fa-list-ul"></i>
							<span>@lang('app.tasks')</span>
						</a>
					</li>
					
					@if( auth()->user()->getRole() < 3 )
					<li class="{{ Request::is('news*') ? 'active' : ''  }}">
						<a href="{{ route('news.list') }}">
							<i class="fas fa-newspaper"></i>
							<span> @lang('app.news')</span>
						</a>
					</li>
					@endif


				</ul>
			</li>
			<!-- end operations menu -->

			<!-- begin Billing menu -->
			<li class="has-sub {{ Request::is('invoice*') || Request::is('product*') || Request::is('pricelist*') ? 'active' : ''  }}">
				<a href="javascript:;">
					<b class="caret"></b>
					<i class="fa fa-dollar-sign"></i>
					<span>@lang('app.Billing')</span>
				</a>
				<ul class="sub-menu">
					<li class="{{ Request::is('invoice*') ? 'active' : ''  }}">
						<a href="{{ route('reseller.list') }}">@lang('app.invoices')</a>
					</li>

					<li class="{{ Request::is('product*') ? 'active' : ''  }}">
						<a href="javascript:;">@lang('app.products')</a>
					</li>

					<li class="{{ Request::is('pricelist*') ? 'active' : ''  }}">
						<a href="javascript:;">@lang('app.price_list')</a>
					</li>
					
				</ul>
			</li>
			<!-- end Billing menu -->

			<!-- Begin Marketpalce menu -->
			<li class="{{ Request::is('marketplace*') ? 'active' : ''  }}">
				<a href="{{ route('dashboard') }}">
					<i class="fa fa-gift"></i> 
					<span>@lang('app.marketplace')</span> 
				</a>
			</li>
			<!-- end Marketpalce menu -->

			<!-- begin Configuration menu -->
			<li class="has-sub {{ Request::is('invoice*') || Request::is('product*') || Request::is('pricelist*') || Request::is('user*') || Request::is('activity*') || Request::is('permission*') || Request::is('role*') || Request::is('supervisor*') ? 'active' : ''  }}">
				<a href="javascript:;">
					<b class="caret"></b>
					<i class="fa fa-cogs"></i>
					<span>@lang('app.settings')</span>
				</a>
				<ul class="sub-menu">
					@permission('users.activity')
					<li class="{{ Request::is('activity*') ? 'active' : ''  }}">
						<a href="{{ route('activity.index') }}">
							<i class="fa fa-file"></i>
							<span> @lang('app.activity_log')</span>
						</a>
					</li>
					@endpermission

					<li class="has-sub">
						<a href="javascript:;">
							<b class="caret"></b>
							<i class="fab fa-buromobelexperte"></i>
							<span> @lang('app.packages')</span>
						</a>
						<ul class="sub-menu">
							<li><a href="javascript:;">
									<i class="fab fa-microsoft"></i><span> @lang('app.microsoft')</span></a></li>
							<li><a href="javascript:;">...</a></li>
						</ul>
					</li>

					<li class="has-sub {{ Request::is('user*') || Request::is('permission*') || Request::is('role*') || Request::is('supervisor*') ? 'active' : ''  }}">

						<a href="javascript:;">
							<b class="caret"></b>
							<i class="fas fa-users"></i>
							<span> @lang('app.users')</span>
						</a>
						<ul class="sub-menu">

							@permission('users.manage')
							<li class="{{ Request::is('user*') ? 'active' : ''  }}">
								<a href="{{ route('user.list') }}">
									<i class="fas fa-users"></i>
									<span> @lang('app.users')</span>
								</a>
							</li>
							@endpermission

							
							<li class="{{ Request::is('permission*') ? 'active' : ''  }}">
								<a href="{{ route('permission.index') }}">
									<i class="fas fa-users-cog"></i>
									<span> @lang('app.permissions')</span>
								</a>
							</li>
							<li class="{{ Request::is('role*') ? 'active' : ''  }}">
								<a href="{{ route('role.index') }}">
									<i class="fas fa-users-cog"></i>
									<span> @lang('app.roles')</span>
								</a>
							</li>


					
							
						</ul>
					</li>
					<li class="{{ Request::is('supervisor*') ? 'active' : ''  }}">
							<a href="{{ route('branch.list') }}">
								<i class="fas fa-building"></i>
								<span> @lang('app.branch_offices')</span>
							</a>
						</li>


					<li class="{{ Request::is('product*') ? 'active' : ''  }}">
						<a href="javascript:;">
							<i class="fab fa-simplybuilt"></i>
							<span> @lang('app.display_widgets')</span>
						</a>
					</li>
					
				</ul>
			</li>
			<!-- end Configuration menu -->





			<!-- begin sidebar minify button -->
			<li><a href="javascript:;" class="sidebar-minify-btn" data-click="sidebar-minify"><i class="fa fa-angle-double-left"></i></a></li>
			<!-- end sidebar minify button -->
		</ul>
		<!-- end sidebar nav -->
	</div>
	<!-- end sidebar scrollbar -->
</div>
<div class="sidebar-bg"></div>
        <!-- end #sidebar -->