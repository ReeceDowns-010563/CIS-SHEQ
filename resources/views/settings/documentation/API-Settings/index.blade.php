<x-app-layout>
    <x-slot name="header">
        <style>
            /* Header styling */
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
                    background-color: rgba(255, 202, 161, 0.1);
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

            .create-customer-btn {
                display: inline-flex;
                align-items: center;
                padding: 0.75rem 1.5rem;
                font-size: 0.875rem;
                font-weight: 700;
                color: white;
                background-color: var(--primary-colour);
                border: 2px solid var(--primary-colour);
                border-radius: 0.5rem;
                text-decoration: none;
                transition: all 0.15s ease-in-out;
                text-transform: uppercase;
                letter-spacing: 0.05em;
            }

            .create-customer-btn:hover {
                background-color: var(--secondary-colour);
                border-color: var(--secondary-colour);
                transform: translateY(-1px);
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            }

            .create-customer-btn:active {
                transform: translateY(0);
            }

            @media (max-width: 768px) {
                .header-container {
                    flex-direction: column;
                    align-items: flex-start;
                }

                .header-left {
                    width: 100%;
                }
            }
        </style>
        <div class="header-container">
            <div class="header-left">
                <a href="{{ route('settings.index') }}" class="back-button" title="Back to Settings">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <h2 class="header-title">API Settings</h2>
            </div>
        </div>
    </x-slot>

    <style>
        .api-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 30px 15px;
        }

        .api-card {
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.08);
            padding: 25px 20px;
            margin-bottom: 25px;
        }

        .api-card-title {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid;
        }

        .token-message {
            line-height: 1.6;
            margin-bottom: 15px;
        }

        .token-container {
            position: relative;
            margin: 15px 0;
            display: flex;
            align-items: stretch;
            gap: 8px;
        }

        .token-display {
            flex: 1;
            border: 1px solid;
            border-radius: 6px;
            font-family: monospace;
            font-size: 14px;
            padding: 12px;
            word-break: break-word;
        }

        .copy-button {
            border: none;
            border-radius: 4px;
            width: 40px;
            min-width: 40px;
            height: auto;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .copy-button svg {
            width: 18px;
            height: 18px;
        }

        .token-warning {
            display: flex;
            align-items: center;
            font-size: 14px;
            margin-top: 10px;
        }

        .token-warning svg {
            width: 16px;
            height: 16px;
            margin-right: 8px;
            flex-shrink: 0;
        }

        .alert-success {
            border-left: 4px solid;
            padding: 20px;
            border-radius: 6px;
            margin-bottom: 25px;
        }

        .alert-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .alert-message {
            margin-bottom: 12px;
        }

        .button-container {
            display: flex;
            gap: 15px;
            margin-top: 20px;
            flex-wrap: wrap;
        }

        .primary-button {
            border: none;
            border-radius: 6px;
            padding: 12px 20px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.2s;
            color: white;
        }

        .secondary-button {
            border: none;
            border-radius: 6px;
            padding: 12px 20px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            display: flex;
            align-items: center;
            text-decoration: none;
        }

        .secondary-button svg {
            width: 18px;
            height: 18px;
            margin-right: 8px;
            flex-shrink: 0;
        }

        .helper-text {
            font-size: 14px;
            margin-top: 15px;
            line-height: 1.5;
        }

        .copy-success {
            background-color: #065f46 !important;
        }

        .copy-success svg {
            color: white !important;
        }

        /* Light Mode Styling */
        @media (prefers-color-scheme: light) {
            .api-card {
                background-color: #ffffff;
                border: 1px solid #e5e7eb;
            }

            .api-card-title {
                color: #111827;
                border-color: #d1d5db;
            }

            .token-message,
            .alert-message,
            .helper-text {
                color: #374151;
            }

            .token-display {
                background-color: #f9fafb;
                border-color: #d1d5db;
                color: #065f46;
            }

            .copy-button {
                background-color: #e5e7eb;
            }

            .copy-button:hover {
                background-color: #d1d5db;
            }

            .copy-button svg {
                color: #111827;
            }

            .token-warning {
                color: #92400e;
            }

            .alert-success {
                background-color: #f0fdf4;
                border-left-color: #10b981;
            }

            .alert-title {
                color: #065f46;
            }

            .primary-button {
                background-color: var(--primary-colour);
            }

            .primary-button:hover {
                background-color: var(--secondary-colour);
            }

            .secondary-button {
                background-color: #e5e7eb;
                color: #111827;
            }

            .secondary-button:hover {
                background-color: #d1d5db;
            }
        }

        /* Dark Mode Styling */
        @media (prefers-color-scheme: dark) {
            .api-card {
                background-color: #1f2937;
                border: none;
            }

            .api-card-title {
                color: #f3f4f6;
                border-color: #374151;
            }

            .token-message,
            .alert-message,
            .helper-text {
                color: #d1d5db;
            }

            .token-display {
                background-color: #111827;
                border-color: #374151;
                color: #34d399;
            }

            .copy-button {
                background-color: #374151;
            }

            .copy-button:hover {
                background-color: #4b5563;
            }

            .copy-button svg {
                color: #d1d5db;
            }

            .token-warning {
                color: #fbbf24;
            }

            .alert-success {
                background-color: #1f2937;
                border-left-color: #10b981;
            }

            .alert-title {
                color: #10b981;
            }

            .primary-button {
                background-color: var(--primary-colour);
            }

            .primary-button:hover {
                background-color: var(--secondary-colour);
            }

            .secondary-button {
                background-color: #4b5563;
                color: white;
            }

            .secondary-button:hover {
                background-color: #374151;
            }
        }

        @media (max-width: 640px) {
            .api-card {
                padding: 20px 15px;
            }

            .button-container {
                flex-direction: column;
            }

            .token-container {
                flex-direction: row;
            }

            .token-warning {
                align-items: flex-start;
            }

            .token-warning svg {
                margin-top: 3px;
            }

            .api-card-title {
                font-size: 18px;
            }
        }
    </style>


    <div class="api-container">
        <!-- API Token Card -->
        <div class="api-card">
            <h2 class="api-card-title">API Token</h2>

            @isset($alreadyGenerated)
                @if ($alreadyGenerated)
                    <p class="token-message">
                        A token has already been generated and cannot be shown again.
                    </p>
                    <p class="token-message">
                        If you lost your token, you can revoke and regenerate it using the button below.
                    </p>
                @elseif ($token)
                    <p class="token-message">Copy and save this token securely:</p>
                    <div class="token-container">
                        <code id="apiToken" class="token-display">{{ $token }}</code>
                        <button onclick="copyToken()" class="copy-button" title="Copy to clipboard">
                            <svg id="copyIcon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                            </svg>
                        </button>
                    </div>
                    <div class="token-warning">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        This token will only be displayed once. Store it somewhere safe.
                    </div>
                @endif
            @endisset
        </div>

        <!-- Token Regenerated Alert -->
        @if (session('regenerated') && session('token'))
            <div class="alert-success">
                <h3 class="alert-title">Token Successfully Regenerated</h3>
                <p class="alert-message">Copy and save this new token — it won't be shown again:</p>
                <div class="token-container">
                    <code id="alertToken" class="token-display">{{ session('token') }}</code>
                    <button onclick="copyAlertToken()" class="copy-button" title="Copy to clipboard">
                        <svg id="alertCopyIcon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                        </svg>
                    </button>
                </div>
            </div>
        @endif

        <!-- API Actions Card -->
        <div class="api-card">
            <h2 class="api-card-title">API Actions</h2>

            <div class="button-container">
                <form method="POST" action="{{ route('settings.api.settings.regenerate') }}">
                    @csrf
                    <button type="submit" class="primary-button">
                        Regenerate API Token
                    </button>
                </form>

                <a href="{{ url('/api/documentation') }}" class="secondary-button">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    View API Documentation
                </a>
            </div>

            <p class="helper-text">
                The API documentation provides details on available endpoints, request formats, and response structures.
            </p>
        </div>
    </div>

    <script>
        function copyToken() {
            const tokenText = document.getElementById('apiToken').textContent;
            copyToClipboard(tokenText, 'copyIcon');
        }

        function copyAlertToken() {
            const tokenText = document.getElementById('alertToken').textContent;
            copyToClipboard(tokenText, 'alertCopyIcon');
        }

        function copyToClipboard(text, iconId) {
            // Create a temporary textarea element to copy from
            const textarea = document.createElement('textarea');
            textarea.value = text;
            textarea.setAttribute('readonly', '');
            textarea.style.position = 'absolute';
            textarea.style.left = '-9999px';
            document.body.appendChild(textarea);

            // Select and copy the text
            textarea.select();
            document.execCommand('copy');

            // Remove the temporary element
            document.body.removeChild(textarea);

            // Show visual feedback
            const button = document.getElementById(iconId).parentElement;
            button.classList.add('copy-success');

            // Change the icon to a checkmark
            document.getElementById(iconId).innerHTML = `
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            `;

            // Reset after 2 seconds
            setTimeout(() => {
                button.classList.remove('copy-success');
                document.getElementById(iconId).innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                `;
            }, 2000);
        }
    </script>
</x-app-layout>
