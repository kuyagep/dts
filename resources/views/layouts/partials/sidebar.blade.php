<nav id="sidebar" class="sidebar js-sidebar">
    <div class="sidebar-content js-simplebar">
        <!-- Brand / Logo -->
        <a class="sidebar-brand" href="{{ route('dashboard') }}">

            <span class="align-middle">DOC TRACKER</span>

        </a>

        <ul class="sidebar-nav">
            <!-- Section: Core -->
            <li class="sidebar-header">
                Core
            </li>

            <li class="sidebar-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('dashboard') }}">
                    <i class="align-middle" data-feather="sliders"></i>
                    <span class="align-middle">Dashboard</span>
                </a>
            </li>

            <!-- Section: Document Processing -->
            <li class="sidebar-header">
                Document Tracking
            </li>

            <li class="sidebar-item {{ request()->routeIs('documents.index') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('documents.index') }}">
                    <i class="align-middle" data-feather="file-text"></i>
                    <span class="align-middle">All Documents</span>
                </a>
            </li>


            <li class="sidebar-item {{ request()->routeIs('documents.create') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('documents.create') }}">
                    <i class="align-middle" data-feather="file-plus"></i>
                    <span class="align-middle">Create Document</span>
                </a>
            </li>



            <li class="sidebar-item {{ request()->routeIs('documents.incoming') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('documents.incoming') }}">
                    <i class="align-middle" data-feather="inbox"></i>
                    <span class="align-middle">Incoming Queue</span>
                    @if (isset($incomingCount) && $incomingCount > 0)
                        <span class="badge bg-danger float-end">{{ $incomingCount }}</span>
                    @endif
                </a>
            </li>

            <li class="sidebar-item {{ request()->routeIs('documents.archived') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('documents.archived') }}">
                    <i class="align-middle" data-feather="archive"></i>
                    <span class="align-middle">Archived Vault</span>
                </a>
            </li>

            <!-- Section: Master References & System Settings -->

            <li class="sidebar-header">
                Master References
            </li>

            <li class="sidebar-item {{ request()->routeIs('departments.*') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('departments.index') }}">
                    <i class="align-middle" data-feather="briefcase"></i>
                    <span class="align-middle">Departments</span>
                </a>
            </li>

            <li class="sidebar-item {{ request()->routeIs('offices.*') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('offices.index') }}">
                    <i class="align-middle" data-feather="building"></i>
                    <span class="align-middle">Offices</span>
                </a>
            </li>

            <li class="sidebar-item {{ request()->routeIs('document-types.*') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('document-types.index') }}">
                    <i class="align-middle" data-feather="layers"></i>
                    <span class="align-middle">Document Types</span>
                </a>
            </li>

            <li class="sidebar-header">
                System Administration
            </li>

            <li class="sidebar-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('users.index') }}">
                    <i class="align-middle" data-feather="users"></i>
                    <span class="align-middle">User Accounts</span>
                </a>
            </li>

            <li class="sidebar-item {{ request()->routeIs('roles.*') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('roles.index') }}">
                    <i class="align-middle" data-feather="shield"></i>
                    <span class="align-middle">Roles & Permissions</span>
                </a>
            </li>

        </ul>
    </div>
</nav>
