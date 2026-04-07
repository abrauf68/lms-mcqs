<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{ route('dashboard') }}" class="app-brand-link">
            <span class="app-brand-logo">
                <img height="40px" src="{{ asset(\App\Helpers\Helper::getLogoLight()) }}" alt="{{env('APP_NAME')}}">
            </span>
            {{-- <span class="app-brand-text demo menu-text fw-bold">{{\App\Helpers\Helper::getCompanyName()}}</span> --}}
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="ti menu-toggle-icon d-none d-xl-block align-middle"></i>
            <i class="ti ti-x d-block d-xl-none ti-md align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <!-- Dashboards -->
        <li class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <a href="{{ route('dashboard') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-smart-home"></i>
                <div>{{__('Dashboard')}}</div>
            </a>
        </li>

        <!-- Apps & Pages -->
        <li class="menu-header small">
            <span class="menu-header-text">{{__('Apps & Pages')}}</span>
        </li>
        @can(['view product'])
            <li class="menu-item {{ request()->routeIs('dashboard.products.*') ? 'active' : '' }}">
                <a href="{{ route('dashboard.products.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-package"></i>
                    <div>{{__('Products')}}</div>
                </a>
            </li>
        @endcan
        @canany(['view domain', 'view process group', 'view topic', 'view approach'])
            <li class="menu-item {{ request()->routeIs('dashboard.domains.*') || request()->routeIs('dashboard.process-groups.*') || request()->routeIs('dashboard.topics.*') || request()->routeIs('dashboard.approaches.*') ? 'open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons ti ti-sitemap"></i>
                    <div>{{__('Question Types')}}</div>
                </a>
                <ul class="menu-sub">
                    @can(['view domain'])
                        <li class="menu-item {{ request()->routeIs('dashboard.domains.*') ? 'active' : '' }}">
                            <a href="{{route('dashboard.domains.index')}}" class="menu-link">
                                <div>{{__('Domains')}}</div>
                            </a>
                        </li>
                    @endcan
                    @can(['view process group'])
                        <li class="menu-item {{ request()->routeIs('dashboard.process-groups.*') ? 'active' : '' }}">
                            <a href="{{route('dashboard.process-groups.index')}}" class="menu-link">
                                <div>{{__('Process Groups')}}</div>
                            </a>
                        </li>
                    @endcan
                    @can(['view topic'])
                        <li class="menu-item {{ request()->routeIs('dashboard.topics.*') ? 'active' : '' }}">
                            <a href="{{route('dashboard.topics.index')}}" class="menu-link">
                                <div>{{__('Topics')}}</div>
                            </a>
                        </li>
                    @endcan
                    @can(['view approach'])
                        <li class="menu-item {{ request()->routeIs('dashboard.approaches.*') ? 'active' : '' }}">
                            <a href="{{route('dashboard.approaches.index')}}" class="menu-link">
                                <div>{{__('Approaches')}}</div>
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan
        @can(['view question'])
            <li class="menu-item {{ request()->routeIs('dashboard.questions.*') ? 'active' : '' }}">
                <a href="{{ route('dashboard.questions.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-help-circle"></i>
                    <div>{{__('Questions')}}</div>
                </a>
            </li>
        @endcan
        @can(['view exam'])
            <li class="menu-item {{ request()->routeIs('dashboard.exams.*') ? 'active' : '' }}">
                <a href="{{ route('dashboard.exams.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-clipboard-check"></i>
                    <div>{{__('Exams')}}</div>
                </a>
            </li>
        @endcan
        @canany(['view user', 'view archived user'])
            <li class="menu-item {{ request()->routeIs('dashboard.user.*') || request()->routeIs('dashboard.archived-user.*') ? 'open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons ti ti-users"></i>
                    <div>{{__('Users')}}</div>
                </a>
                <ul class="menu-sub">
                    @can(['view user'])
                        <li class="menu-item {{ request()->routeIs('dashboard.user.*') ? 'active' : '' }}">
                            <a href="{{route('dashboard.user.index')}}" class="menu-link">
                                <div>{{__('All Users')}}</div>
                            </a>
                        </li>
                    @endcan
                    @can(['view archived user'])
                        <li class="menu-item {{ request()->routeIs('dashboard.archived-user.*') ? 'active' : '' }}">
                            <a href="{{route('dashboard.archived-user.index')}}" class="menu-link">
                                <div>{{__('Archived Users')}}</div>
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan
        @canany(['view role', 'view permission'])
            <li class="menu-item {{ request()->routeIs('dashboard.roles.*') || request()->routeIs('dashboard.permissions.*') ? 'open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    {{-- <i class="menu-icon tf-icons ti ti-settings"></i> --}}
                    <i class="menu-icon tf-icons ti ti-shield-lock"></i>
                    <div>{{__('Roles & Permissions')}}</div>
                </a>
                <ul class="menu-sub">
                    @can(['view role'])
                        <li class="menu-item {{ request()->routeIs('dashboard.roles.*') ? 'active' : '' }}">
                            <a href="{{route('dashboard.roles.index')}}" class="menu-link">
                                <div>{{__('Roles')}}</div>
                            </a>
                        </li>
                    @endcan
                    @can(['view permission'])
                        <li class="menu-item {{ request()->routeIs('dashboard.permissions.*') ? 'active' : '' }}">
                            <a href="{{route('dashboard.permissions.index')}}" class="menu-link">
                                <div>{{__('Permissions')}}</div>
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan
        @can(['view setting'])
            <li class="menu-item {{ request()->routeIs('dashboard.setting.*') ? 'active' : '' }}">
                <a href="{{ route('dashboard.setting.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-settings"></i>
                    <div>{{__('Settings')}}</div>
                </a>
            </li>
        @endcan
    </ul>
</aside>
