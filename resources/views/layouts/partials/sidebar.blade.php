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

            <!-- Create New Document -->
            <li class="sidebar-item {{ request()->routeIs('documents.create') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('documents.create') }}">
                    <i class="align-middle" data-feather="plus-circle"></i>
                    <span class="align-middle">New Document</span>
                </a>
            </li>


            <!-- Incoming Queue -->
            <li class="sidebar-item {{ request()->routeIs('documents.incoming') ? 'active' : '' }}">
                <a class="sidebar-link d-flex justify-content-between align-items-center"
                    href="{{ route('documents.incoming') }}">
                    <div>
                        <i class="align-middle" data-feather="inbox"></i>
                        <span class="align-middle">Incoming Queue</span>
                    </div>
                    @php
                        $incomingCount = \App\Models\Document::where(
                            'current_office_id',
                            auth()->user()->office_id ?? null,
                        )
                            ->where('status', 'In Transit')
                            ->count();
                    @endphp
                    @if ($incomingCount > 0)
                        <span class="badge bg-danger rounded-pill">{{ $incomingCount }}</span>
                    @endif
                </a>
            </li>
            <!-- Pending Documents Queue -->
            <li class="sidebar-item {{ request()->routeIs('documents.pending') ? 'active' : '' }}">
                <a class="sidebar-link d-flex justify-content-between align-items-center"
                    href="{{ route('documents.pending') }}">
                    <div>
                        <i class="align-middle" data-feather="clock"></i>
                        <span class="align-middle">Pending Action</span>
                    </div>
                    @php
                        $pendingCount = \App\Models\Document::where(
                            'current_office_id',
                            auth()->user()->office_id ?? null,
                        )
                            ->whereIn('status', ['Received', 'In Review'])
                            ->count();
                    @endphp
                    @if ($pendingCount > 0)
                        <span class="badge bg-warning text-dark rounded-pill">{{ $pendingCount }}</span>
                    @endif
                </a>
            </li>

            <li class="sidebar-item {{ request()->routeIs('documents.forwarded') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('documents.forwarded') }}">
                    <i class="align-middle" data-feather="send"></i>
                    <span class="align-middle">Forwarded Queue</span>
                </a>
            </li>
            <!-- Master Document Index -->
            <li class="sidebar-item {{ request()->routeIs('documents.index') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('documents.index') }}">
                    <i class="align-middle" data-feather="file-text"></i>
                    <span class="align-middle">All Documents</span>
                </a>
            </li>
            <!-- Completed Documents -->
            <li class="sidebar-item {{ request()->routeIs('documents.completed') ? 'active' : '' }}">
                <a class="sidebar-link d-flex justify-content-between align-items-center"
                    href="{{ route('documents.completed') }}">
                    <div>
                        <i class="align-middle" data-feather="check-circle"></i>
                        <span class="align-middle">Completed Queue</span>
                    </div>
                    @php
                        $completedCount = \App\Models\Document::where(
                            'current_office_id',
                            auth()->user()->office_id ?? null,
                        )
                            ->where('status', 'Completed')
                            ->count();
                    @endphp
                    @if ($completedCount > 0)
                        <span class="badge bg-success rounded-pill">{{ $completedCount }}</span>
                    @endif
                </a>
            </li>
            <!-- Archived Vault -->
            <li class="sidebar-item {{ request()->routeIs('documents.archived') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('documents.archived') }}">
                    <i class="align-middle" data-feather="archive"></i>
                    <span class="align-middle">Archived Vault</span>
                    @php
                        $archivedCount = \App\Models\Document::where(
                            'current_office_id',
                            auth()->user()->office_id ?? null,
                        )
                            ->where('status', 'Archived')
                            ->count();
                    @endphp
                    @if ($archivedCount > 0)
                        <span class="badge bg-success rounded-pill">{{ $archivedCount }}</span>
                    @endif
                </a>
            </li>

            <!-- Section: Master References & System Settings -->
            @hasanyrole('SuperAdmin|Admin')
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
                        <i class="align-middle" data-feather="layers"></i>
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
            @endhasanyrole
        </ul>
    </div>
</nav>
