<x-app-layout>
    <x-slot name="header">
        <div class="header-container">
            <div class="header-left">
                <a href="{{ route('settings.emails.audit.index') }}" class="back-button" title="Back to Audit Log">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <h2 class="header-title">Email Log Details</h2>
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
            max-width: 60rem;
            margin: 0 auto;
        }

        .details-section {
            background: white;
            border-radius: 0.75rem;
            padding: 2rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            margin-bottom: 2rem;
            border: 1px solid #e5e7eb;
        }

        @media (prefers-color-scheme: dark) {
            .details-section {
                background: #1f2937;
                border-color: #374151;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.4), 0 2px 4px -1px rgba(0, 0, 0, 0.25);
            }
        }

        .section-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: #1f2937;
            margin: 0 0 1.5rem 0;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #e5e7eb;
        }

        @media (prefers-color-scheme: dark) {
            .section-title {
                color: #f9fafb;
                border-color: #4b5563;
            }
        }

        .detail-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
        }

        .detail-item {
            padding-bottom: 1rem;
            border-bottom: 1px solid #f3f4f6;
        }

        @media (prefers-color-scheme: dark) {
            .detail-item {
                border-color: #4b5563;
            }
        }

        .detail-label {
            font-size: 0.875rem;
            font-weight: 600;
            color: #6b7280;
            margin-bottom: 0.5rem;
        }

        @media (prefers-color-scheme: dark) {
            .detail-label {
                color: #9ca3af;
            }
        }

        .detail-value {
            color: #1f2937;
            font-weight: 500;
        }

        @media (prefers-color-scheme: dark) {
            .detail-value {
                color: #e5e7eb;
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
            padding: 0.25rem 0.75rem;
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

        .recipients-list {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-top: 0.5rem;
        }

        .recipient-badge {
            background-color: #e0e7ff;
            color: #3730a3;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        @media (prefers-color-scheme: dark) {
            .recipient-badge {
                background-color: rgba(99, 102, 241, 0.2);
                color: #a5b4fc;
            }
        }

        .code-block {
            background-color: #f8fafc;
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            padding: 1rem;
            margin-top: 0.5rem;
            font-family: monospace;
            font-size: 0.875rem;
            overflow-x: auto;
        }

        @media (prefers-color-scheme: dark) {
            .code-block {
                background-color: #111827;
                border-color: #4b5563;
                color: #e5e7eb;
            }
        }

        .error-section {
            background-color: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 0.5rem;
            padding: 1rem;
            margin-top: 0.5rem;
        }

        @media (prefers-color-scheme: dark) {
            .error-section {
                background-color: rgba(239, 68, 68, 0.1);
                border-color: #ef4444;
            }
        }

        .error-message {
            color: #dc2626;
            font-weight: 500;
        }

        @media (prefers-color-scheme: dark) {
            .error-message {
                color: #fca5a5;
            }
        }
    </style>

    <div class="main-container">
        <div class="content-wrapper">
            <!-- Basic Information -->
            <div class="details-section">
                <h3 class="section-title">Basic Information</h3>
                <div class="detail-grid">
                    <div class="detail-item">
                        <div class="detail-label">ID</div>
                        <div class="detail-value">#{{ $emailAuditLog->id }}</div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-label">Template Key</div>
                        <div class="detail-value">
                            @if($emailAuditLog->template_key)
                                <code style="background: #f3f4f6; padding: 0.25rem 0.5rem; border-radius: 0.25rem; font-size: 0.875rem;">
                                    {{ $emailAuditLog->template_key }}
                                </code>
                            @else
                                <span class="text-muted">Direct Email</span>
                            @endif
                        </div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-label">Origin</div>
                        <div class="detail-value">
                            <span class="origin-badge origin-{{ $emailAuditLog->origin }}">
                                {{ ucfirst($emailAuditLog->origin) }}
                            </span>
                        </div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-label">Status</div>
                        <div class="detail-value">
                            <span class="status-badge {{ $emailAuditLog->getStatusBadgeClass() }}">
                                {{ ucfirst($emailAuditLog->status) }}
                            </span>
                        </div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-label">Created At</div>
                        <div class="detail-value">{{ $emailAuditLog->created_at->format('M d, Y H:i:s') }}</div>
                    </div>

                    @if($emailAuditLog->sent_at)
                        <div class="detail-item">
                            <div class="detail-label">Sent At</div>
                            <div class="detail-value">{{ $emailAuditLog->sent_at->format('M d, Y H:i:s') }}</div>
                        </div>
                    @endif

                    @if($emailAuditLog->duration_ms)
                        <div class="detail-item">
                            <div class="detail-label">Duration</div>
                            <div class="detail-value">{{ number_format($emailAuditLog->getDurationInSeconds(), 2) }} seconds</div>
                        </div>
                    @endif

                    @if($emailAuditLog->user)
                        <div class="detail-item">
                            <div class="detail-label">Sent By</div>
                            <div class="detail-value">{{ $emailAuditLog->user->name }}</div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Email Content -->
            <div class="details-section">
                <h3 class="section-title">Email Content</h3>
                <div class="detail-item">
                    <div class="detail-label">Subject</div>
                    <div class="detail-value">{{ $emailAuditLog->subject }}</div>
                </div>
            </div>

            <!-- Recipients -->
            <div class="details-section">
                <h3 class="section-title">Recipients</h3>
                <div class="recipients-list">
                    @foreach($emailAuditLog->getRecipientsList() as $recipient)
                        <span class="recipient-badge">{{ $recipient }}</span>
                    @endforeach
                </div>
            </div>

            <!-- Variables (Redacted) -->
            @if($emailAuditLog->redacted_variables && count($emailAuditLog->redacted_variables) > 0)
                <div class="details-section">
                    <h3 class="section-title">Variables (Redacted)</h3>
                    <div class="code-block">
                        <pre>{{ json_encode($emailAuditLog->redacted_variables, JSON_PRETTY_PRINT) }}</pre>
                    </div>
                </div>
            @endif

            <!-- Error Information -->
            @if($emailAuditLog->error_message)
                <div class="details-section">
                    <h3 class="section-title">Error Information</h3>
                    <div class="error-section">
                        <div class="error-message">{{ $emailAuditLog->error_message }}</div>
                    </div>
                </div>
            @endif

            <!-- Technical Details -->
            <div class="details-section">
                <h3 class="section-title">Technical Details</h3>
                <div class="detail-grid">
                    @if($emailAuditLog->provider_id)
                        <div class="detail-item">
                            <div class="detail-label">Provider ID</div>
                            <div class="detail-value">
                                <code style="background: #f3f4f6; padding: 0.25rem 0.5rem; border-radius: 0.25rem; font-size: 0.875rem;">
                                    {{ $emailAuditLog->provider_id }}
                                </code>
                            </div>
                        </div>
                    @endif

                    @if($emailAuditLog->ip_address)
                        <div class="detail-item">
                            <div class="detail-label">IP Address</div>
                            <div class="detail-value">
                                <code style="background: #f3f4f6; padding: 0.25rem 0.5rem; border-radius: 0.25rem; font-size: 0.875rem;">
                                    {{ $emailAuditLog->ip_address }}
                                </code>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
