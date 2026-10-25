<nav id="sidebar" class="sidebar js-sidebar">
    <div class="sidebar-content js-simplebar">
        <!-- Brand / Logo -->
        <a class="sidebar-brand" href="{{ route('dashboard') }}">

            <span class="align-middle">DRMS</span>

        </a>

        <ul class="sidebar-nav">
            <!-- Section: Core -->
            <li class="sidebar-header">
                Menu
            </li>

            <!-- Create New Document -->
            <!-- Action Buttons Section -->
            <li class="sidebar-item px-3 my-2">
                <div class="d-grid gap-2">
                    <!-- New Document Button -->
                    <a href="{{ route('documents.create') }}"
                        class="btn btn-primary w-100 text-white d-flex align-items-center justify-content-center gap-2 shadow-sm py-2 {{ request()->routeIs('documents.create') ? 'active' : '' }}"
                        style="border-radius: 6px; font-weight: 600;" title="Shortcut: Alt + N">
                        <i data-feather="plus-circle" class="feather-sm"></i>
                        <span>New Document</span>
                    </a>
                    @hasanyrole('Clerk|Admin')
                        <!-- Scan QR Code Modal Trigger Button -->
                        <button type="button"
                            class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center gap-2 shadow-sm py-2"
                            accesskey="r" title="Shortcut: Alt + R" data-bs-toggle="modal" data-bs-target="#qrScanModal"
                            style="border-radius: 6px; font-weight: 600;">
                            <i class="fa-solid fa-qrcode"></i>
                            <span>Scan QR Code</span>
                        </button>
                    @endhasanyrole
                </div>
            </li>

            <li class="sidebar-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('dashboard') }}" accesskey="h" alt="Alt + H">
                    <i class="align-middle" data-feather="sliders"></i>
                    <span class="align-middle">Dashboard</span>
                </a>
            </li>

            <!-- Section: Document Processing -->
            <li class="sidebar-header">
                Document Tracking
            </li>



            <!-- In Transit Queue -->
            <li class="sidebar-item {{ request()->routeIs('documents.transit') ? 'active' : '' }}">
                <a class="sidebar-link d-flex justify-content-between align-items-center"
                    href="{{ route('documents.transit') }}" accesskey="t" alt="Alt + T">
                    <div>
                        <i class="align-middle" data-feather="send"></i>
                        <span class="align-middle">In Transit Queue</span>
                    </div>
                    @php
                        $officeId = auth()->user()->office_id ?? null;

                        $transitCount = $officeId
                            ? \App\Models\Document::where('status', 'In Transit')
                                ->whereHas('routes', function ($q) use ($officeId) {
                                    $q->where('from_office_id', $officeId)->where('status', 'In Transit');
                                })
                                ->count()
                            : 0;
                    @endphp
                    @if ($transitCount > 0)
                        <span class="badge bg-info text-dark rounded-pill">{{ $transitCount }}</span>
                    @endif
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
                            'destination_office_id',
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
            <!-- My Created Documents -->
            <li class="sidebar-item {{ request()->routeIs('documents.my-documents') ? 'active' : '' }}">
                <a class="sidebar-link d-flex justify-content-between align-items-center"
                    href="{{ route('documents.my-documents') }}">
                    <div>
                        <i class="align-middle" data-feather="folder"></i>
                        <span class="align-middle">My Documents</span>
                    </div>
                    @php
                        $myDocsCount = \App\Models\Document::where('created_by', auth()->id())->count();
                    @endphp
                    @if ($myDocsCount > 0)
                        <span class="badge bg-secondary rounded-pill">{{ $myDocsCount }}</span>
                    @endif
                </a>
            </li>
            <!-- Master Document Index -->
            <li class="sidebar-item {{ request()->routeIs('documents.index') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('documents.index') }}">
                    <i class="align-middle" data-feather="file-text"></i>
                    <span class="align-middle">All Documents</span>
                </a>
            </li>
            <!-- Rejected Documents Sidebar Item -->
            <li class="sidebar-item {{ request()->routeIs('documents.rejected') ? 'active' : '' }}">
                <a class="sidebar-link d-flex justify-content-between align-items-center"
                    href="{{ route('documents.rejected') }}">
                    <div>
                        <i class="align-middle text-danger" data-feather="alert-octagon"></i>
                        <span class="align-middle">Rejected Docs</span>
                    </div>
                    @php
                        $rejectedCount = \App\Models\Document::where('created_by', auth()->id())
                            ->where('status', 'Rejected')
                            ->count();
                    @endphp
                    @if ($rejectedCount > 0)
                        <span class="badge bg-danger rounded-pill">{{ $rejectedCount }}</span>
                    @endif
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
                @role('SuperAdmin')
                    <li class="sidebar-item {{ request()->routeIs('roles.*') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('roles.index') }}">
                            <i class="align-middle" data-feather="shield"></i>
                            <span class="align-middle">Roles & Permissions</span>
                        </a>
                    </li>
                @endrole
            @endhasanyrole
        </ul>
    </div>
</nav>
