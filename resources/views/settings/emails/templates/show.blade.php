<x-app-layout>
    <x-slot name="header">
        <div class="header-container">
            <div class="header-left">
                <a href="{{ route('settings.emails.templates.index') }}" class="back-button" title="Back to Templates">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <h2 class="header-title">{{ $emailTemplate->name }}</h2>
            </div>
            <div class="header-actions">
                @can('sendTest', $emailTemplate)
                    <button class="btn-test" onclick="openTestModal()">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                        </svg>
                        Send Test
                    </button>
                @endcan
            </div>
        </div>
    </x-slot>

    <style>
        /* Header */
        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
            padding: 0.5rem 0;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex: 1;
            min-width: 0;
        }

        .back-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 2.5rem;
            height: 2.5rem;
            color: #6b7280;
            background: white;
            border: 1px solid #d1d5db;
            border-radius: 0.75rem;
            text-decoration: none;
            transition: all 0.2s ease;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .back-button:hover {
            color: white;
            background: linear-gradient(135deg, var(--primary-colour), var(--secondary-colour));
            border-color: var(--primary-colour);
            transform: translateX(-2px);
        }

        @media (prefers-color-scheme: dark) {
            .back-button {
                color: #9ca3af;
                background: #1f2937;
                border-color: #4b5563;
            }
        }

        .header-title {
            font-size: 1.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, var(--primary-colour), var(--secondary-colour));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin: 0;
            word-break: break-word;
            color: #1f2937; /* Fallback color for light mode */
        }

        @media (prefers-color-scheme: dark) {
            .header-title {
                color: white; /* Solid white color for dark mode */
                background: none;
                -webkit-background-clip: unset;
                -webkit-text-fill-color: unset;
                background-clip: unset;
            }
        }

        .header-actions {
            display: flex;
            gap: 0.75rem;
        }

        .btn-test {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            font-size: 0.875rem;
            font-weight: 600;
            color: white;
            background: linear-gradient(135deg, #10b981, #059669);
            border: none;
            border-radius: 0.75rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-test:hover {
            background: linear-gradient(135deg, #059669, #047857);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        /* Main Container */
        .main-container {
            min-height: 100vh;
            background: #f8fafc;
            padding: 1.5rem;
        }

        @media (prefers-color-scheme: dark) {
            .main-container {
                background: #111827;
            }
        }

        .content-wrapper {
            max-width: 80rem;
            margin: 0 auto;
        }

        /* Cards */
        .card {
            background: white;
            border-radius: 1rem;
            padding: 2rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1), 0 1px 2px rgba(0, 0, 0, 0.06);
            margin-bottom: 2rem;
            border: 1px solid #e5e7eb;
            position: relative;
            overflow: hidden; /* Ensure the line doesn't exceed box bounds */
        }

        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(135deg, var(--primary-colour), var(--secondary-colour));
            border-radius: 1rem 1rem 0 0;
        }

        @media (prefers-color-scheme: dark) {
            .card {
                background: #1f2937;
                border-color: #374151;
            }
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #e5e7eb;
        }

        @media (prefers-color-scheme: dark) {
            .card-header {
                border-color: #374151;
            }
        }

        .card-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #1f2937;
            margin: 0;
        }

        @media (prefers-color-scheme: dark) {
            .card-title {
                color: #e5e7eb;
            }
        }

        /* Template Details */
        .detail-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
        }

        .detail-item {
            padding: 1rem;
            background: #f8fafc;
            border-radius: 0.5rem;
            border-left: 3px solid var(--primary-colour);
            transition: transform 0.2s ease;
        }

        .detail-item:hover {
            transform: translateY(-2px);
        }

        @media (prefers-color-scheme: dark) {
            .detail-item {
                background: #374151;
            }
        }

        .detail-label {
            font-size: 0.75rem;
            font-weight: 600;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.5rem;
        }

        @media (prefers-color-scheme: dark) {
            .detail-label {
                color: #9ca3af;
            }
        }

        .detail-value {
            color: #1f2937;
            font-weight: 600;
        }

        @media (prefers-color-scheme: dark) {
            .detail-value {
                color: #e5e7eb;
            }
        }

        /* Status Badge */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.375rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-active {
            background: #10b981;
            color: white;
        }

        .status-active::before {
            content: '●';
            animation: pulse 2s infinite;
        }

        .status-inactive {
            background: #6b7280;
            color: white;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        /* Preview Controls */
        .preview-controls {
            display: flex;
            background: #f3f4f6;
            padding: 0.25rem;
            border-radius: 0.5rem;
            gap: 0.25rem;
        }

        @media (prefers-color-scheme: dark) {
            .preview-controls {
                background: #374151;
            }
        }

        .btn-toggle {
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
            font-weight: 500;
            border-radius: 0.375rem;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
            background: transparent;
            color: #6b7280;
        }

        .btn-toggle.active {
            background: var(--primary-colour);
            color: white;
        }

        .btn-toggle:hover:not(.active) {
            background: white;
            color: #1f2937;
        }

        @media (prefers-color-scheme: dark) {
            .btn-toggle {
                color: #9ca3af;
            }

            .btn-toggle:hover:not(.active) {
                background: #1f2937;
                color: #e5e7eb;
            }
        }

        /* Preview Mode Toggle */
        .preview-mode-toggle {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1rem;
        }

        .mode-switch {
            position: relative;
            display: inline-block;
            width: 3rem;
            height: 1.5rem;
        }

        .mode-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .mode-slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #d1d5db;
            transition: 0.3s;
            border-radius: 1.5rem;
        }

        .mode-slider:before {
            position: absolute;
            content: "";
            height: 1.125rem;
            width: 1.125rem;
            left: 0.1875rem;
            bottom: 0.1875rem;
            background-color: white;
            transition: 0.3s;
            border-radius: 50%;
        }

        input:checked + .mode-slider {
            background-color: var(--primary-colour);
        }

        input:checked + .mode-slider:before {
            transform: translateX(1.5rem);
        }

        @media (prefers-color-scheme: dark) {
            .mode-slider {
                background-color: #4b5563;
            }
        }

        .mode-label {
            font-size: 0.875rem;
            font-weight: 500;
            color: #374151;
        }

        @media (prefers-color-scheme: dark) {
            .mode-label {
                color: #d1d5db;
            }
        }

        /* Email Preview */
        .email-preview {
            border: 1px solid #d1d5db;
            border-radius: 0.75rem;
            overflow: hidden;
            background: white;
            margin-top: 1rem;
        }

        @media (prefers-color-scheme: dark) {
            .email-preview {
                background: #374151;
                border-color: #4b5563;
            }
        }

        .email-subject {
            padding: 1.5rem;
            background: #f8fafc;
            border-bottom: 1px solid #e5e7eb;
            font-weight: 600;
            color: #1f2937;
        }

        @media (prefers-color-scheme: dark) {
            .email-subject {
                background: #111827;
                border-color: #4b5563;
                color: #e5e7eb;
            }
        }

        .email-subject::before {
            content: '📧 ';
            margin-right: 0.5rem;
        }

        .email-body {
            padding: 1.5rem;
            min-height: 200px;
            line-height: 1.6;
            color: #1f2937;
            background: white; /* Ensure white background for email content */
        }

        @media (prefers-color-scheme: dark) {
            .email-body {
                background: white; /* Keep email content on white background */
            }
        }

        /* Variables Section */
        .variables-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }

        .variable-item {
            background: white;
            padding: 1rem;
            border-radius: 0.5rem;
            border: 1px solid #d1d5db;
            font-family: 'Monaco', 'Menlo', monospace;
            transition: all 0.2s ease;
        }

        .variable-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        @media (prefers-color-scheme: dark) {
            .variable-item {
                background: #374151;
                border-color: #4b5563;
            }
        }

        .variable-name {
            font-weight: 700;
            color: var(--primary-colour);
            font-size: 0.875rem;
            margin-bottom: 0.25rem;
        }

        .variable-sample {
            font-size: 0.75rem;
            color: #6b7280;
        }

        @media (prefers-color-scheme: dark) {
            .variable-sample {
                color: #9ca3af;
            }
        }

        /* No Variables State */
        .no-variables {
            text-align: center;
            padding: 2rem;
            color: #6b7280;
            font-style: italic;
        }

        @media (prefers-color-scheme: dark) {
            .no-variables {
                color: #9ca3af;
            }
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: white;
            margin: 5% auto;
            padding: 2rem;
            border-radius: 1rem;
            width: 90%;
            max-width: 600px;
            position: relative;
            max-height: 80vh;
            overflow-y: auto;
        }

        @media (prefers-color-scheme: dark) {
            .modal-content {
                background-color: #1f2937;
                color: #e5e7eb;
            }
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #e5e7eb;
        }

        @media (prefers-color-scheme: dark) {
            .modal-header {
                border-color: #374151;
            }
        }

        .modal-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #1f2937;
            margin: 0;
        }

        @media (prefers-color-scheme: dark) {
            .modal-title {
                color: #f9fafb;
            }
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 1.5rem;
            color: #6b7280;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 0.5rem;
            transition: all 0.2s ease;
        }

        .modal-close:hover {
            background: #f3f4f6;
            color: #374151;
        }

        @media (prefers-color-scheme: dark) {
            .modal-close:hover {
                background: #374151;
                color: #d1d5db;
            }
        }

        /* Form */
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
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

        .form-input, .form-textarea {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid #d1d5db;
            border-radius: 0.5rem;
            background: white;
            color: #111827;
            font-size: 1rem;
            transition: all 0.15s ease;
        }

        .form-input:focus, .form-textarea:focus {
            outline: none;
            border-color: var(--primary-colour);
            box-shadow: 0 0 0 3px rgba(167, 98, 44, 0.1);
        }

        @media (prefers-color-scheme: dark) {
            .form-input, .form-textarea {
                background: #374151;
                border-color: #4b5563;
                color: #e5e7eb;
            }
        }

        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
            margin-top: 2rem;
            padding-top: 1rem;
            border-top: 1px solid #e5e7eb;
        }

        @media (prefers-color-scheme: dark) {
            .modal-footer {
                border-color: #374151;
            }
        }

        .btn {
            padding: 0.75rem 1.5rem;
            font-size: 0.875rem;
            font-weight: 600;
            border-radius: 0.5rem;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background-color: var(--primary-colour);
            color: white;
        }

        .btn-primary:hover {
            background-color: var(--secondary-colour);
            transform: translateY(-1px);
        }

        .btn-secondary {
            background-color: #6b7280;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #4b5563;
        }

        /* Loading */
        .loading {
            opacity: 0.6;
            pointer-events: none;
        }

        .spinner {
            display: inline-block;
            width: 1rem;
            height: 1rem;
            border: 2px solid transparent;
            border-top: 2px solid currentColor;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Alert */
        .alert {
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 0.5rem;
            font-size: 0.875rem;
        }

        .alert-success {
            background-color: #ecfdf5;
            border: 1px solid #10b981;
            color: #065f46;
        }

        .alert-error {
            background-color: #fef2f2;
            border: 1px solid #ef4444;
            color: #991b1b;
        }

        @media (prefers-color-scheme: dark) {
            .alert-success {
                background-color: rgba(16, 185, 129, 0.1);
                color: #6ee7b7;
            }

            .alert-error {
                background-color: rgba(239, 68, 68, 0.1);
                color: #fca5a5;
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .header-container {
                flex-direction: column;
                align-items: stretch;
                gap: 0.75rem;
            }

            .header-title {
                font-size: 1.25rem;
            }

            .main-container {
                padding: 1rem;
            }

            .card {
                padding: 1.5rem;
            }

            .detail-grid {
                grid-template-columns: 1fr;
            }

            .variables-list {
                grid-template-columns: 1fr;
            }

            .modal-content {
                margin: 2% auto;
                width: 95%;
                padding: 1.5rem;
            }

            .modal-footer {
                flex-direction: column;
            }

            .btn {
                width: 100%;
            }

            .preview-mode-toggle {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }
        }
    </style>

    <div class="main-container">
        <div class="content-wrapper">
            <!-- Template Details Card -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Template Details</h3>
                    <span class="status-badge status-{{ $emailTemplate->is_active ? 'active' : 'inactive' }}">
                        {{ $emailTemplate->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>

                <div class="detail-grid">
                    <div class="detail-item">
                        <div class="detail-label">Template Key</div>
                        <div class="detail-value">{{ $emailTemplate->key }}</div>
                    </div>

                    @if($emailTemplate->category)
                        <div class="detail-item">
                            <div class="detail-label">Category</div>
                            <div class="detail-value">{{ ucfirst($emailTemplate->category) }}</div>
                        </div>
                    @endif

                    <div class="detail-item">
                        <div class="detail-label">Created</div>
                        <div class="detail-value">{{ $emailTemplate->created_at?->format('M j, Y') ?? 'Unknown' }}</div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-label">Last Updated</div>
                        <div class="detail-value">{{ $emailTemplate->updated_at?->format('M j, Y') ?? 'Unknown' }}</div>
                    </div>
                </div>

                @if($emailTemplate->description)
                    <div style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid #e5e7eb;">
                        <div class="detail-label">Description</div>
                        <div class="detail-value" style="margin-top: 0.5rem;">{{ $emailTemplate->description }}</div>
                    </div>
                @endif
            </div>

            <!-- Email Preview Card -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Email Preview</h3>
                    <div class="preview-controls">
                        <button class="btn-toggle active" onclick="showPreview('html', this)">HTML</button>
                        <button class="btn-toggle" onclick="showPreview('text', this)">Text</button>
                    </div>
                </div>

                <!-- Preview Mode Toggle -->
                <div class="preview-mode-toggle">
                    <span class="mode-label">Show Variables</span>
                    <label class="mode-switch">
                        <input type="checkbox" id="previewModeToggle" onchange="togglePreviewMode()" checked>
                        <span class="mode-slider"></span>
                    </label>
                    <span class="mode-label">Show Sample Data</span>
                </div>

                <div class="email-preview">
                    <div class="email-subject" id="emailSubject">Loading...</div>
                    <div class="email-body">
                        <!-- Isolated email HTML in iframe to prevent CSS clashes -->
                        <iframe id="html-preview-iframe"
                                style="width: 100%; min-height: 500px; border: none; background: white; display: block;"
                                sandbox="allow-same-origin">
                        </iframe>
                        <div id="text-preview" style="display: none; white-space: pre-wrap; font-family: monospace; padding: 1rem; background: #f8f9fa; border-radius: 0.5rem;">
                            Loading...
                        </div>
                    </div>
                </div>
            </div>

            <!-- Variables Card -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Available Variables</h3>
                </div>

                @php
                    $variables = $emailTemplate->getVariablesList();
                @endphp

                @if(count($variables) > 0)
                    <div class="variables-list">
                        @foreach($variables as $variable)
                            <div class="variable-item">
                                <div class="variable-name">{{{ $variable }}}</div>
                                <div class="variable-sample">
                                    {{ $emailTemplate->getSampleDataForVariable($variable) ?: 'Dynamic content' }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="no-variables">
                        <p>No variables found in this template.</p>
                        <p>This template uses static content only.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Test Email Modal -->
    <div class="modal" id="testEmailModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Send Test Email</h3>
                <button type="button" class="modal-close" onclick="closeTestModal()">&times;</button>
            </div>

            <div id="testEmailAlert"></div>

            <form id="testEmailForm" onsubmit="sendTestEmail(event)">
                <div class="form-group">
                    <label for="recipientEmail" class="form-label">Recipient Email *</label>
                    <input type="email" class="form-input" id="recipientEmail" required
                           placeholder="Enter email address">
                </div>

                <div id="variablesSection" style="display: none;">
                    <h4 class="form-label" style="margin-bottom: 1rem;">Variable Overrides (Optional)</h4>
                    <div id="variablesList"></div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeTestModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="sendTestBtn">
                        <span class="btn-text">Send Test Email</span>
                        <div class="spinner" style="display: none;"></div>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let currentTemplateId = {{ $emailTemplate->id }};
        let currentPreviewMode = 'sample'; // 'sample' or 'placeholders'

        // Load preview on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadPreview();
        });

        function loadPreview() {
            fetch(`/settings/emails/templates/${currentTemplateId}/preview?mode=${currentPreviewMode}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Subject
                        document.getElementById('emailSubject').textContent = data.subject;

                        // HTML preview (iframe isolation)
                        const iframe = document.getElementById('html-preview-iframe');
                        const iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
                        iframeDoc.open();
                        iframeDoc.write(data.body);
                        iframeDoc.close();

                        // Try to auto-resize iframe to content height
                        setTimeout(() => {
                            try {
                                const body = iframeDoc.body;
                                const html = iframeDoc.documentElement;
                                const height = Math.max(
                                    body.scrollHeight, body.offsetHeight,
                                    html.clientHeight, html.scrollHeight, html.offsetHeight
                                );
                                iframe.style.height = Math.max(height, 500) + 'px';
                            } catch (_) {
                                // ignore sizing errors
                            }
                        }, 50);

                        // Text preview (strip tags)
                        document.getElementById('text-preview').textContent = data.body.replace(/<[^>]*>/g, '');
                    }
                })
                .catch(error => {
                    console.error('Error loading preview:', error);
                });
        }

        function togglePreviewMode() {
            const toggle = document.getElementById('previewModeToggle');
            currentPreviewMode = toggle.checked ? 'sample' : 'placeholders';
            loadPreview();
        }

        function showPreview(type, btn) {
            // Update button states
            document.querySelectorAll('.btn-toggle').forEach(b => b.classList.remove('active'));
            if (btn) btn.classList.add('active');

            // Show/hide preview content
            const htmlIframe = document.getElementById('html-preview-iframe');
            const textPreview = document.getElementById('text-preview');

            if (type === 'html') {
                htmlIframe.style.display = 'block';
                textPreview.style.display = 'none';
            } else {
                htmlIframe.style.display = 'none';
                textPreview.style.display = 'block';
            }
        }

        function openTestModal() {
            const modal = document.getElementById('testEmailModal');
            const alert = document.getElementById('testEmailAlert');

            // Clear any previous alerts
            alert.innerHTML = '';

            // Reset form
            document.getElementById('testEmailForm').reset();

            // Fetch template variables
            fetch(`/settings/emails/templates/${currentTemplateId}/preview`, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
                .then(response => response.json())
                .then(data => {
                    const variablesSection = document.getElementById('variablesSection');
                    const variablesList = document.getElementById('variablesList');

                    variablesList.innerHTML = '';

                    if (data.success && data.variables && data.variables.length > 0) {
                        data.variables.forEach(variable => {
                            const div = document.createElement('div');
                            div.className = 'form-group';
                            div.innerHTML = `
                                <label class="form-label">${variable}</label>
                                <input type="text" class="form-input" name="variables[${variable}]"
                                       placeholder="Override value for ${variable}">
                            `;
                            variablesList.appendChild(div);
                        });
                        variablesSection.style.display = 'block';
                    } else {
                        variablesSection.style.display = 'none';
                    }

                    modal.style.display = 'block';
                })
                .catch(error => {
                    console.error('Error fetching template variables:', error);
                    showAlert('error', 'Could not load template variables. Please try again.');
                    modal.style.display = 'block';
                });
        }

        function closeTestModal() {
            document.getElementById('testEmailModal').style.display = 'none';
            document.getElementById('testEmailForm').reset();
            document.getElementById('testEmailAlert').innerHTML = '';
        }

        function sendTestEmail(event) {
            event.preventDefault();

            const sendBtn = document.getElementById('sendTestBtn');
            const btnText = sendBtn.querySelector('.btn-text');
            const spinner = sendBtn.querySelector('.spinner');
            const recipientEmail = document.getElementById('recipientEmail').value;

            // Collect variables
            const variables = {};
            const variableInputs = document.querySelectorAll('#variablesList input');
            variableInputs.forEach(input => {
                const match = input.name.match(/variables\[(.*?)\]/);
                if (match && input.value.trim()) {
                    variables[match[1]] = input.value.trim();
                }
            });

            // Show loading state
            sendBtn.classList.add('loading');
            sendBtn.disabled = true;
            btnText.textContent = 'Sending...';
            spinner.style.display = 'inline-block';

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch(`/settings/emails/templates/${currentTemplateId}/send-test`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    recipient_email: recipientEmail,
                    variables: variables
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showAlert('success', data.message);
                        // Clear form on success
                        document.getElementById('testEmailForm').reset();
                    } else {
                        showAlert('error', data.message || 'An unknown error occurred.');
                    }
                })
                .catch(error => {
                    console.error('Error sending test email:', error);
                    showAlert('error', 'Failed to send test email. Please try again.');
                })
                .finally(() => {
                    // Reset button state
                    sendBtn.classList.remove('loading');
                    sendBtn.disabled = false;
                    btnText.textContent = 'Send Test Email';
                    spinner.style.display = 'none';
                });
        }

        function showAlert(type, message) {
            const alert = document.getElementById('testEmailAlert');
            alert.innerHTML = `
                <div class="alert alert-${type}">
                    ${message}
                </div>
            `;
        }

        // Close modal when clicking outside
        window.addEventListener('click', function(event) {
            const modal = document.getElementById('testEmailModal');
            if (event.target === modal) {
                closeTestModal();
            }
        });

        // Close modal with escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                const modal = document.getElementById('testEmailModal');
                if (modal.style.display === 'block') {
                    closeTestModal();
                }
            }
        });
    </script>
</x-app-layout>
