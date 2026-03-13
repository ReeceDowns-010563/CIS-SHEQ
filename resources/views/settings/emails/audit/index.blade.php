<x-app-layout>
    <x-slot name="header">
        <div class="header-container">
            <div class="header-left">
                <a href="{{ route('settings.emails.templates.index') }}" class="back-button" title="Back to Templates">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <h2 class="header-title">Email Audit Log</h2>
            </div>
            <div class="header-actions">
                @can('export', \App\Models\EmailAuditLog::class)
                    <a href="{{ route('settings.emails.audit.export', request()->query()) }}" class="btn btn-success">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Export CSV
                    </a>
                @endcan
            </div>
        </div>
    </x-slot>

    <style>
        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .back-button {
            display: inline-flex;
            align-items: center;
            padding: 0.5rem;
            color: #6b7280;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            text-decoration: none;
            transition: all 0.15s ease-in-out;
            background-color: white;
        }

        .back-button:hover {
            color: var(--primary-colour);
            border-color: var(--primary-colour);
            background-color: #fef7f0;
            transform: translateX(-2px);
        }

        @media (prefers-color-scheme: dark) {
            .back-button {
                color: #9ca3af;
                border-color: #4b5563;
                background-color: #1f2937;
            }

            .back-button:hover {
                color: var(--primary-colour);
                border-color: var(--primary-colour);
                background-color: rgba(167, 98, 44, 0.1);
            }
        }

        .header-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: #1f2937;
            margin: 0;
        }

        @media (prefers-color-scheme: dark) {
            .header-title {
                color: #d1d5db;
            }
        }

        .main-container {
            padding: 2.5rem 1rem;
            color: #374151;
            background-color: #f8fafc;
            min-height: 100vh;
        }

        @media (prefers-color-scheme: dark) {
            .main-container {
                color: #d1d5db;
                background-color: #111827;
            }
        }

        .content-wrapper {
            max-width: 80rem;
            margin: 0 auto;
        }

        .filters-section {
            background: white;
            border-radius: 0.75rem;
            padding: 1.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            margin-bottom: 2rem;
            border: 1px solid #e5e7eb;
        }

        @media (prefers-color-scheme: dark) {
            .filters-section {
                background: #1f2937;
                border-color: #374151;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.4), 0 2px 4px -1px rgba(0, 0, 0, 0.25);
            }
        }

        .filters-form {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            align-items: end;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-label {
            font-size: 0.875rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.5rem;
        }

        @media (prefers-color-scheme: dark) {
            .form-label {
                color: #d1d5db;
            }
        }

        .form-input, .form-select {
            padding: 0.75rem 1rem;
            border: 2px solid #d1d5db;
            border-radius: 0.5rem;
            background: white;
            color: #111827;
            font-size: 1rem;
            transition: all 0.15s ease-in-out;
        }

        .form-input:focus, .form-select:focus {
            outline: none;
            border-color: var(--primary-colour);
            box-shadow: 0 0 0 3px rgba(167, 98, 44, 0.1);
        }

        @media (prefers-color-scheme: dark) {
            .form-input, .form-select {
                background: #374151;
                border-color: #4b5563;
                color: #e5e7eb;
            }

            .form-input:focus, .form-select:focus {
                border-color: var(--primary-colour);
                box-shadow: 0 0 0 3px rgba(167, 98, 44, 0.2);
            }
        }

        .btn {
            padding: 0.75rem 1.5rem;
            font-size: 0.875rem;
            font-weight: 600;
            border-radius: 0.375rem;
            border: 1px solid transparent;
            cursor: pointer;
            transition: all 0.15s ease-in-out;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-primary {
            background-color: var(--primary-colour);
            color: white;
            border-color: var(--primary-colour);
        }

        .btn-primary:hover {
            background-color: var(--secondary-colour);
            border-color: var(--secondary-colour);
        }

        .btn-success {
            background-color: #10b981;
            color: white;
            border-color: #10b981;
        }

        .btn-success:hover {
            background-color: #059669;
            border-color: #059669;
        }

        .audit-table {
            background: white;
            border-radius: 0.75rem;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            border: 1px solid #e5e7eb;
        }

        @media (prefers-color-scheme: dark) {
            .audit-table {
                background: #1f2937;
                border-color: #374151;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.4), 0 2px 4px -1px rgba(0, 0, 0, 0.25);
            }
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th {
            padding: 1rem 1.5rem;
            text-align: left;
            font-weight: 700;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            background-color: #f8fafc;
            color: #374151;
            border-bottom: 1px solid #e5e7eb;
        }

        @media (prefers-color-scheme: dark) {
            .table th {
                background-color: #111827;
                color: #d1d5db;
                border-bottom-color: #4b5563;
            }
        }

        .table td {
            padding: 1rem 1.5rem;
            font-size: 0.875rem;
            color: #374151;
            border-bottom: 1px solid #f1f5f9;
        }

        @media (prefers-color-scheme: dark) {
            .table td {
                color: #d1d5db;
                border-bottom-color: #374151;
            }
        }

        .table tbody tr:hover {
            background-color: #f8fafc;
        }

        @media (prefers-color-scheme: dark) {
            .table tbody tr:hover {
                background-color: #374151;
            }
        }

        .status-badge {
            padding: 0.25rem 0.75rem;
            font-size: 0.75rem;
            font-weight: 600;
            border-radius: 9999px;
            text-transform: capitalize;
        }

        .status-completed {
            background-color: #ecfdf5;
            color: #065f46;
            border: 1px solid #10b981;
        }

        .status-closed {
            background-color: #fee2e2;
            color: #991b1b;
            border: 1px solid #f87171;
        }

        .status-pending {
            background-color: #fef3c7;
            color: #92400e;
            border: 1px solid #f59e0b;
        }

        @media (prefers-color-scheme: dark) {
            .status-completed {
                background-color: rgba(16, 185, 129, 0.2);
                color: #6ee7b7;
                border-color: #10b981;
            }
            .status-closed {
                background-color: rgba(239, 68, 68, 0.2);
                color: #fca5a5;
                border-color: #f87171;
            }
            .status-pending {
                background-color: rgba(245, 158, 11, 0.2);
                color: #fcd34d;
                border-color: #f59e0b;
            }
        }

        .origin-badge {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
            font-weight: 600;
            border-radius: 0.375rem;
            text-transform: capitalize;
        }

        .origin-test {
            background-color: #fef3c7;
            color: #92400e;
        }

        .origin-system {
            background-color: #dbeafe;
            color: #1e40af;
        }

        @media (prefers-color-scheme: dark) {
            .origin-test {
                background-color: rgba(245, 158, 11, 0.2);
                color: #fcd34d;
            }
            .origin-system {
                background-color: rgba(59, 130, 246, 0.2);
                color: #93c5fd;
            }
        }

        .btn-sm {
            padding: 0.375rem 0.75rem;
            font-size: 0.75rem;
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
        }

        .empty-icon {
            width: 4rem;
            height: 4rem;
            color: #9ca3af;
            margin: 0 auto 1rem;
        }

        .empty-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 0.5rem;
        }

        @media (prefers-color-scheme: dark) {
            .empty-title {
                color: #f9fafb;
            }
        }

        .empty-description {
            color: #6b7280;
        }

        @media (prefers-color-scheme: dark) {
            .empty-description {
                color: #9ca3af;
            }
        }
    </style>

    <div class="main-container">
        <div class="content-wrapper">
            <!-- Filters -->
            <div class="filters-section">
                <form method="GET" action="{{ route('settings.emails.audit.index') }}" class="filters-form">
                    <div class="form-group">
                        <label class="form-label">Search</label>
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Search by subject, template, or recipient..."
                               class="form-input">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">All Statuses</option>
                            @foreach($filterOptions['statuses'] as $value => $label)
                                <option value="{{ $value }}" {{ request('status') === $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Origin</label>
                        <select name="origin" class="form-select">
                            <option value="">All Origins</option>
                            @foreach($filterOptions['origins'] as $value => $label)
                                <option value="{{ $value }}" {{ request('origin') === $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">From Date</label>
                        <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-input">
                    </div>

                    <div class="form-group">
                        <label class="form-label">To Date</label>
                        <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-input">
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </div>
                </form>
            </div>

            <!-- Audit Table -->
            <div class="audit-table">
                @if($logs->count() > 0)
                    <table class="table">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Template</th>
                            <th>Subject</th>
                            <th>Recipients</th>
                            <th>Origin</th>
                            <th>Status</th>
                            <th>Sent At</th>
                            <th>Duration</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($logs as $log)
                            <tr>
                                <td><span class="font-mono text-sm">#{{ $log->id }}</span></td>
                                <td>
                                    @if($log->template_key)
                                        <code class="text-primary text-xs">{{ $log->template_key }}</code>
                                    @else
                                        <span class="text-muted text-xs">Direct</span>
                                    @endif
                                </td>
                                <td>
                                    <div style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"
                                         title="{{ $log->subject }}">
                                        {{ $log->subject }}
                                    </div>
                                </td>
                                <td>
                                    <small>{{ count($log->getRecipientsList()) }} recipient(s)</small>
                                </td>
                                <td>
                                        <span class="origin-badge origin-{{ $log->origin }}">
                                            {{ ucfirst($log->origin) }}
                                        </span>
                                </td>
                                <td>
                                        <span class="status-badge {{ $log->getStatusBadgeClass() }}">
                                            {{ ucfirst($log->status) }}
                                        </span>
                                </td>
                                <td>
                                    @if($log->sent_at)
                                        <small>{{ $log->sent_at->format('M d, Y H:i') }}</small>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($log->duration_ms)
                                        <small>{{ number_format($log->getDurationInSeconds(), 2) }}s</small>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-primary btn-sm" onclick="openLogDetails({{ $log->id }})">
                                        View
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <!-- Pagination -->
                    @if($logs->hasPages())
                        <div style="padding: 1rem; text-align: center;">
                            {{ $logs->appends(request()->query())->links() }}
                        </div>
                    @endif
                @else
                    <div class="empty-state">
                        <svg class="empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                  d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                        </svg>
                        <h3 class="empty-title">No Email Logs Found</h3>
                        <p class="empty-description">No email audit logs match your current filters.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        function openLogDetails(logId) {
            // You can implement a modal or redirect to details page
            window.location.href = `/settings/emails/audit/${logId}`;
        }
    </script>
</x-app-layout>
