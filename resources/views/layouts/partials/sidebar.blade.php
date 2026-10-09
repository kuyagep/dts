<nav id="sidebar" class="sidebar js-sidebar">
    <div class="sidebar-content js-simplebar">
        <a class="sidebar-brand" href="{{ route('dashboard') }}">
            <span class="align-middle">DDTS Admin</span>
        </a>

        <ul class="sidebar-nav">
            <li class="sidebar-header">
                Main Navigation
            </li>

            <li class="sidebar-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('dashboard') }}">
                    <i class="align-middle" data-feather="sliders"></i>
                    <span class="align-middle">Dashboard</span>
                </a>
            </li>

            <li class="sidebar-header">
                Document Management
            </li>

            <li class="sidebar-item {{ request()->routeIs('documents.*') ? 'active' : '' }}">
                <a class="sidebar-link" href="#">
                    <i class="align-middle" data-feather="file-text"></i>
                    <span class="align-middle">All Documents</span>
                </a>
            </li>

            @can('documents.create')
                <li class="sidebar-item">
                    <a class="sidebar-link" href="#">
                        <i class="align-middle" data-feather="file-plus"></i>
                        <span class="align-middle">New Document</span>
                    </a>
                </li>
            @endcan

            @can('documents.receive')
                <li class="sidebar-item">
                    <a class="sidebar-link" href="#">
                        <i class="align-middle" data-feather="corner-down-right"></i>
                        <span class="align-middle">Receive Incoming</span>
                    </a>
                </li>
            @endcan

            @role('Admin|SuperAdmin')
                <li class="sidebar-header">
                    System Administration
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link" href="#">
                        <i class="align-middle" data-feather="users"></i>
                        <span class="align-middle">User Management</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link" href="#">
                        <i class="align-middle" data-feather="shield"></i>
                        <span class="align-middle">Roles & Permissions</span>
                    </a>
                </li>
            @endrole
        </ul>
    </div>
</nav>
