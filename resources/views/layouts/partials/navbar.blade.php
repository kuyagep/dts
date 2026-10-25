<nav class="navbar navbar-expand navbar-light navbar-bg">
    <a class="sidebar-toggle js-sidebar-toggle">
        <i class="hamburger align-self-center"></i>
    </a>
    <img src="{{ asset('static/img/favicon.png') }}" alt="DDTS Logo" class="me-2 rounded-circle"
        style="width: 32px; height: 32px; object-fit: cover;">
    <!-- System Branding Title -->
    <div class="d-none d-sm-inline-block ms-2">
        <h2 class="h4 mb-0 fw-bold text-dark align-middle">Division Document Tracking System</h2>
    </div>
    <div class="d-inline-block d-sm-none ms-2">
        <span class="h4 mb-0 fw-bold text-dark align-middle">DDTS</span>
    </div>

    <div class="navbar-collapse collapse">

        <ul class="navbar-nav navbar-align align-items-center">

            <li class="nav-item dropdown">
                <!-- Notification Bell Icon with Unread Counter Badge -->
                <a class="nav-icon dropdown-toggle" href="#" id="alertsDropdown" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    <div class="position-relative">
                        <i class="align-middle" data-feather="bell"></i>
                        @if (auth()->user()->unreadNotifications->count() > 0)
                            <span class="indicator bg-danger">{{ auth()->user()->unreadNotifications->count() }}</span>
                        @endif
                    </div>
                </a>

                <!-- Notification Dropdown Menu -->
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end py-0" aria-labelledby="alertsDropdown">
                    <div
                        class="dropdown-menu-header border-bottom d-flex justify-content-between align-items-center py-2 px-3">
                        <span class="fw-semibold text-dark">
                            {{ auth()->user()->unreadNotifications->count() }} New Notifications
                        </span>
                        @if (auth()->user()->unreadNotifications->count() > 0)
                            <form action="{{ route('notifications.markAllRead') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-link btn-sm text-decoration-none p-0 text-muted"
                                    style="font-size: 0.75rem;">
                                    Mark all as read
                                </button>
                            </form>
                        @endif
                    </div>

                    <!-- Notification List -->
                    <div class="list-group list-group-flush" style="max-height: 320px; overflow-y: auto;">
                        @forelse(auth()->user()->unreadNotifications->take(5) as $notification)
                            @php
                                $type = $notification->data['type'] ?? '';
                                // Dynamic styling based on notification action type
                                $icon = match ($type) {
                                    'document_incoming' => 'inbox',
                                    'document_completed' => 'check-circle',
                                    'user_registered' => 'user-plus',
                                    default => 'bell',
                                };

                                $badgeClass = match ($type) {
                                    'document_incoming' => 'text-primary bg-primary-subtle',
                                    'document_completed' => 'text-success bg-success-subtle',
                                    'user_registered' => 'text-warning bg-warning-subtle',
                                    default => 'text-secondary bg-light',
                                };
                            @endphp

                            <a href="{{ route('notifications.read', $notification->id) }}"
                                class="list-group-item list-group-item-action py-2">
                                <div class="row g-0 align-items-center">
                                    <!-- Icon Column -->
                                    <div class="col-2 text-center">
                                        <span
                                            class="p-2 rounded-circle d-inline-flex align-items-center justify-content-center {{ $badgeClass }}"
                                            style="width: 36px; height: 36px;">
                                            <i data-feather="{{ $icon }}" class="feather-sm"></i>
                                        </span>
                                    </div>

                                    <!-- Content Column -->
                                    <div class="col-10 ps-2">
                                        <div
                                            class="text-dark small fw-semibold d-flex justify-content-between align-items-center">
                                            <span>{{ $notification->data['title'] ?? 'Notification' }}</span>
                                            <span class="text-muted extra-small" style="font-size: 0.7rem;">
                                                {{ $notification->created_at->diffForHumans(null, true) }}
                                            </span>
                                        </div>
                                        <div class="text-muted small text-truncate mt-1" style="max-width: 230px;">
                                            {{ $notification->data['message'] ?? '' }}
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="text-center text-muted py-4 small">
                                <i data-feather="bell-off" class="feather-sm mb-1 d-block mx-auto text-secondary"></i>
                                No unread notifications
                            </div>
                        @endforelse
                    </div>

                    <!-- Footer -->
                    <div class="dropdown-menu-footer text-center border-top py-2">
                        <a href="{{ route('notifications.index') }}"
                            class="text-muted small text-decoration-none fw-semibold">
                            View all notifications
                        </a>
                    </div>
                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-icon dropdown-toggle d-inline-block d-sm-none" href="#" data-bs-toggle="dropdown">
                    <i class="align-middle" data-feather="settings"></i>
                </a>

                <a class="nav-link dropdown-toggle d-none d-sm-inline-block" href="#" data-bs-toggle="dropdown">
                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center shadow-sm avatar img-fluid me-1"
                        style="width: 32px; height: 32px; font-size: 1rem; font-weight: bold;">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    {{-- <img src="{{ asset('static/img/avatars/avatar.jpg') }}" class="avatar img-fluid rounded me-1" /> --}}
                    <span class="text-dark">{{ auth()->user()->name }}</span>
                    <span class="badge bg-primary ms-1">{{ auth()->user()->getRoleNames()->first() }}</span>
                </a>

                <div class="dropdown-menu dropdown-menu-end">
                    <a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="align-middle me-1"
                            data-feather="user"></i>
                        Profile</a>
                    <div class="dropdown-divider"></div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger">
                            <i class="align-middle me-1" data-feather="log-out"></i> Log out
                        </button>
                    </form>
                </div>
            </li>
        </ul>
    </div>
</nav>
