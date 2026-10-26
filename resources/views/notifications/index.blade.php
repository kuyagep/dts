@extends('layouts.main')

@section('title', 'Notifications')

@section('content')
    <div class="container-fluid p-0">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="h3 mb-0 text-gray-800">Notifications</h1>

            @if (auth()->user()->unreadNotifications->count() > 0)
                <form action="{{ route('notifications.markAllRead') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-outline-primary btn-sm">
                        <i data-feather="check-square" class="me-1 feather-sm"></i> Mark All as Read
                    </button>
                </form>
            @endif
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="fw-bold text-dark mb-0">
                        All Notifications ({{ $notifications->total() }})
                    </span>
                </div>
            </div>

            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @forelse($notifications as $notification)
                        @php
                            $type = $notification->data['type'] ?? '';
                            $isUnread = is_null($notification->read_at);

                            // Icon selection based on notification payload type
                            $icon = match ($type) {
                                'document_incoming' => 'inbox',
                                'document_completed' => 'check-circle',
                                'user_registered' => 'user-plus',
                                default => 'bell',
                            };

                            // Color badge selection
                            $badgeClass = match ($type) {
                                'document_incoming' => 'text-primary bg-primary-subtle',
                                'document_completed' => 'text-success bg-success-subtle',
                                'user_registered' => 'text-warning bg-warning-subtle',
                                default => 'text-secondary bg-light',
                            };
                        @endphp

                        <div
                            class="list-group-item list-group-item-action p-3 {{ $isUnread ? 'bg-light-subtle fw-semibold' : '' }}">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center gap-3">
                                    <!-- Status Icon -->
                                    <span
                                        class="p-2 rounded-circle d-inline-flex align-items-center justify-content-center {{ $badgeClass }}"
                                        style="width: 42px; height: 42px;">
                                        <i data-feather="{{ $icon }}" class="feather-md"></i>
                                    </span>

                                    <!-- Content -->
                                    <div>
                                        <div class="d-flex align-items-center gap-2">
                                            <a href="{{ route('notifications.read', $notification->id) }}"
                                                class="text-dark text-decoration-none fw-bold">
                                                {{ $notification->data['title'] ?? 'Notification' }}
                                            </a>
                                            @if ($isUnread)
                                                <span class="badge bg-danger rounded-pill"
                                                    style="font-size: 0.65rem;">Unread</span>
                                            @endif
                                        </div>
                                        <p class="text-muted small mb-0 mt-1">
                                            {{ $notification->data['message'] ?? '' }}
                                        </p>
                                        <span class="text-muted extra-small" style="font-size: 0.75rem;">
                                            <i data-feather="clock"
                                                class="feather-xs me-1"></i>{{ $notification->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Action Link -->
                                <div>
                                    <a href="{{ route('notifications.read', $notification->id) }}"
                                        class="btn btn-sm btn-outline-secondary">
                                        View Details <i data-feather="chevron-right" class="feather-xs"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-5">
                            <i data-feather="bell-off" class="feather-lg mb-2 text-secondary"></i>
                            <p class="mb-0 fs-5">No notifications found.</p>
                            <small class="text-muted">You're all caught up!</small>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Pagination Footer -->
            @if ($notifications->hasPages())
                <div class="card-footer bg-white border-top py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted small">
                            Showing {{ $notifications->firstItem() }} to {{ $notifications->lastItem() }} of
                            {{ $notifications->total() }} notifications
                        </span>
                        <div>
                            {{ $notifications->links() }}
                        </div>
                    </div>
                </div>
            @endif
        </div>

    </div>
@endsection
