<div class="card shadow-sm border-0 rounded-3 mb-4">
    <div class="card-body p-4">
        <!-- Status Pill Badge -->
        <div class="mb-2">
            <span class="badge bg-primary px-3 py-1 rounded-pill fs-7 fw-semibold">
                {{ $document->status ?? 'Received / Pending' }}
            </span>
        </div>

        <!-- Document Title & Type -->
        <h4 class="fw-bold text-dark mb-1">{{ $document->title }}</h4>
        <p class="text-secondary small mb-3">
            Type: <span class="fw-normal text-secondary">{{ $document->documentType->name ?? 'Unclassified' }}</span>
        </p>

        <hr class="text-muted opacity-25 my-3">

        <!-- Originating Office & Current Custodian Row -->
        <div class="row g-3 mb-4">
            <div class="col-sm-6">
                <span class="text-muted small d-block mb-1">Originating Office:</span>
                <strong class="text-dark d-block fs-6">
                    {{ $document->originatingOffice->name ?? 'N/A' }}
                </strong>
            </div>
            <div class="col-sm-6">
                <span class="text-muted small d-block mb-1">Current Office:</span>
                <strong class="text-dark d-block fs-6">
                    {{ $document->currentOffice->name ?? 'N/A' }}
                </strong>
            </div>
        </div>

        <!-- Clean Vertical Timeline -->
        <div class="clean-timeline position-relative mt-4 ps-1">
            @forelse($document->routes->sortByDesc('created_at') as $route)
                @php
                    // Since order is reversed, $loop->first represents the latest/most recent active step
                    $isLatest = $loop->first;
                @endphp
                <div
                    class="clean-timeline-item position-relative d-flex justify-content-between align-items-start pb-4">
                    <!-- Dot Indicator (Green for completed steps, Blue Ring for active/latest step) -->
                    <div
                        class="clean-timeline-dot {{ $isLatest ? ($route->status == 'Completed' ? 'is-completed' : 'is-active') : 'is-completed' }}">
                    </div>

                    <!-- Action Name & Office -->
                    <div class="ps-4">
                        <div class="fw-bold text-dark lh-sm">
                            {{ $route->toOffice->name ?? $route->fromOffice->name }}
                        </div>
                        <div class="text-muted small mt-1">
                            {{ $route->status }}
                            {{ $route->action_taken ?? ($isLatest ? ($route->status == 'In Transit' ? ' - Waiting' : ($route->status == 'Completed' ? '' : ' - Under Review')) : ' - Dispatched') }}
                        </div>
                        @if ($route->remarks)
                            <div class="small text-secondary fst-italic mt-1">
                                "{{ $route->remarks }}"
                            </div>
                        @endif
                    </div>

                    <!-- Right-Aligned Date & Time (Monospace) -->
                    <div class="text-end text-nowrap ps-3">
                        <span class="font-monospace text-muted small fs-7">
                            {{ $route->created_at->format('M d, Y') }} &nbsp;{{ $route->created_at->format('h:i A') }}
                        </span>
                    </div>
                </div>
            @empty
                <!-- Fallback view when only initial creation exists -->
                <div
                    class="clean-timeline-item position-relative d-flex justify-content-between align-items-start pb-2">
                    <div class="clean-timeline-dot is-active"></div>
                    <div class="ps-4">
                        <div class="fw-bold text-dark lh-sm">Document Created</div>
                        <div class="text-muted small mt-1">
                            {{ $document->originatingOffice->name ?? 'Originating Office' }}
                        </div>
                    </div>
                    <div class="text-end text-nowrap ps-3">
                        <span class="font-monospace text-muted small fs-7">
                            {{ $document->created_at->format('M d, Y') }}
                            &nbsp;{{ $document->created_at->format('h:i A') }}
                        </span>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>
