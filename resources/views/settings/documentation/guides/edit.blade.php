@php use Illuminate\Support\Str; @endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Edit Guide</h2>
    </x-slot>

    <style>
        /* Professional form styling */
        .form-container {
            max-width: 32rem;
            margin: 0 auto;
            margin-top: 1.5rem;
            background-color: white;
            padding: 2rem;
            border-radius: 0.75rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            color: #374151;
        }

        @media (prefers-color-scheme: dark) {
            .form-container {
                background-color: #1f2937;
                color: #e5e7eb;
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.4), 0 4px 6px -2px rgba(0, 0, 0, 0.25);
            }
        }

        .error-container {
            margin-bottom: 1.5rem;
            padding: 1rem;
            background-color: #fef2f2;
            border: 1px solid #fecaca;
            color: #b91c1c;
            border-radius: 0.5rem;
        }

        @media (prefers-color-scheme: dark) {
            .error-container {
                background-color: rgba(185, 28, 28, 0.1);
                border-color: #dc2626;
                color: #f87171;
            }
        }

        .error-list {
            list-style-type: disc;
            padding-left: 1.25rem;
            margin-top: 0.5rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #374151;
        }

        @media (prefers-color-scheme: dark) {
            .form-label {
                color: #d1d5db;
            }
        }

        .form-input, .form-select, .form-textarea {
            width: 100%;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            border: 2px solid #d1d5db;
            border-radius: 0.5rem;
            background-color: white;
            color: #111827;
            transition: all 0.15s ease-in-out;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            resize: vertical;
        }

        .form-input:focus, .form-select:focus, .form-textarea:focus {
            outline: none;
            border-color: var(--primary-colour);
            box-shadow: 0 0 0 3px rgba(167, 98, 44, 0.1);
        }

        .form-input:hover, .form-select:hover, .form-textarea:hover {
            border-color: #9ca3af;
        }

        @media (prefers-color-scheme: dark) {
            .form-input, .form-select, .form-textarea {
                background-color: #374151;
                border-color: #4b5563;
                color: #e5e7eb;
                box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.3);
            }

            .form-input:focus, .form-select:focus, .form-textarea:focus {
                border-color: var(--primary-colour);
                box-shadow: 0 0 0 3px rgba(167, 98, 44, 0.2);
            }

            .form-input:hover, .form-select:hover, .form-textarea:hover {
                border-color: #6b7280;
            }
        }

        .button-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 2rem;
            gap: 1rem;
        }

        .cancel-btn {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            font-size: 0.875rem;
            font-weight: 600;
            text-decoration: none;
            color: white;
            background-color: #6b7280;
            border: 2px solid #6b7280;
            border-radius: 0.5rem;
            transition: all 0.15s ease-in-out;
            cursor: pointer;
        }

        .cancel-btn:hover {
            background-color: #4b5563;
            border-color: #4b5563;
            transform: translateY(-1px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .update-btn {
            padding: 0.75rem 2rem;
            font-size: 0.875rem;
            font-weight: 700;
            color: white;
            background-color: var(--primary-colour);
            border: 2px solid var(--primary-colour);
            border-radius: 0.5rem;
            cursor: pointer;
            transition: all 0.15s ease-in-out;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .update-btn:hover {
            background-color: var(--secondary-colour);
            border-color: var(--secondary-colour);
            transform: translateY(-1px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .update-btn:active {
            transform: translateY(0);
        }

        .helper-text {
            font-size: 0.75rem;
            color: #6b7280;
            margin-top: 0.25rem;
        }

        @media (prefers-color-scheme: dark) {
            .helper-text {
                color: #9ca3af;
            }
        }

        .form-title {
            text-align: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #f3f4f6;
        }

        @media (prefers-color-scheme: dark) {
            .form-title {
                border-bottom-color: #374151;
            }
        }

        .share-section {
            background-color: #f9fafb;
            padding: 1rem;
            border-radius: 0.5rem;
            border: 1px solid #d1d5db;
            margin-bottom: 1.5rem;
        }

        @media (prefers-color-scheme: dark) {
            .share-section {
                background-color: #1f2937;
                border-color: #4b5563;
            }
        }

        .copy-input {
            width: 100%;
            padding: 0.5rem 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            font-size: 0.75rem;
            margin-right: 0.5rem;
            background: white;
        }

        @media (prefers-color-scheme: dark) {
            .copy-input {
                background: #111827;
                border-color: #374151;
                color: #d1d5eb;
            }
        }

        .small-btn {
            padding: 0.4rem 0.75rem;
            font-size: 0.65rem;
            border-radius: 0.375rem;
            border: none;
            cursor: pointer;
            font-weight: 600;
        }

        .regen-btn {
            background: #2563eb;
            color: white;
        }

        .revoke-btn {
            background: #dc2626;
            color: white;
        }

        .view-link {
            display: inline-block;
            margin-top: 0.25rem;
            font-size: 0.75rem;
            color: #2563eb;
            text-decoration: none;
        }

        .visibility-toggle {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .visibility-label {
            font-weight: 600;
            margin-right: 0.5rem;
        }
    </style>

    <div class="py-10 px-4">
        <div class="form-container">
            <div class="form-title">
                <h3 style="font-size: 1.25rem; font-weight: 700; margin: 0;">Edit Guide</h3>
            </div>

            @if ($errors->any())
                <div class="error-container">
                    <strong>Please fix these errors:</strong>
                    <ul class="error-list">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('settings.guides.update', $guide) }}" enctype="multipart/form-data" id="edit-guide-form">
                @csrf
                @method('PUT')

                <!-- Title -->
                <div class="form-group">
                    <label for="title" class="form-label">Title</label>
                    <input
                        id="title"
                        name="title"
                        type="text"
                        required
                        value="{{ old('title', $guide->title) }}"
                        class="form-input"
                        placeholder="Guide title"
                    />
                </div>

                <!-- Description -->
                <div class="form-group">
                    <label for="description" class="form-label">Description</label>
                    <textarea
                        id="description"
                        name="description"
                        rows="4"
                        class="form-textarea"
                        placeholder="Brief description"
                    >{{ old('description', $guide->description) }}</textarea>
                </div>

                <!-- Visibility -->
                <div class="form-group">
                    <label class="form-label">Visibility</label>
                    <div class="visibility-toggle">
                        <label>
                            <input type="radio" name="is_public" value="1" {{ old('is_public', $guide->is_public) ? 'checked' : '' }}>
                            <span class="ml-1">Public</span>
                        </label>
                        <label>
                            <input type="radio" name="is_public" value="0" {{ ! old('is_public', $guide->is_public) ? 'checked' : '' }}>
                            <span class="ml-1">Private</span>
                        </label>
                    </div>
                    <div class="helper-text">
                        Public guides are accessible to anyone; private require login. <strong>Switching from private to public will revoke any existing shared link.</strong>
                    </div>
                </div>

                <!-- File replacement -->
                <div class="form-group">
                    <label for="file" class="form-label">Replace PDF</label>
                    <input id="file" name="file" type="file" accept="application/pdf" class="form-input" />
                    <div class="helper-text">Leave blank to keep existing file. Only PDFs up to 10MB.</div>
                    <div style="margin-top:0.5rem;">
                        <strong>Current file:</strong>
                        <div>
                            @if($guide->is_public)
                                <a href="{{ route('guides.show', $guide->uuid) }}" target="_blank" class="view-link">View (public)</a>
                            @elseif($guide->share_token)
                                <a href="{{ route('guides.shared', ['uuid' => $guide->uuid, 'token' => $guide->share_token]) }}" target="_blank" class="view-link">View (shared)</a>
                            @else
                                <span class="text-sm">Private - login required to view</span>
                                <br>
                                <a href="{{ route('guides.show', $guide->uuid) }}" target="_blank" class="view-link">View (requires auth)</a>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Share link / token management -->
                <div class="share-section">
                    <div class="form-group">
                        <div class="form-label">Share Links</div>

                        @if($guide->is_public)
                            <div class="mb-2">
                                <div class="font-medium">Public URL</div>
                                <div style="display:flex; gap:8px; align-items:center; margin-top:4px;">
                                    <input type="text" readonly value="{{ route('guides.show', $guide->uuid) }}" class="copy-input" id="public-link">
                                    <button type="button" class="small-btn" onclick="navigator.clipboard.writeText('{{ route('guides.show', $guide->uuid) }}')">Copy</button>
                                </div>
                                <div class="helper-text">Accessible by anyone.</div>
                            </div>
                        @else
                            <div class="mb-2">
                                <div class="font-medium">Private URL</div>
                                <div class="helper-text">Requires login to view.</div>
                                <div style="display:flex; gap:8px; align-items:center; margin-top:4px;">
                                    <input type="text" readonly value="{{ route('guides.show', $guide->uuid) }}" class="copy-input" id="private-link">
                                    <button type="button" class="small-btn" onclick="navigator.clipboard.writeText('{{ route('guides.show', $guide->uuid) }}')">Copy</button>
                                </div>
                            </div>

                            <div class="mb-2">
                                <div class="font-medium">Shared Link Token</div>
                                @if($guide->share_token)
                                    <div style="display:flex; gap:8px; align-items:center; margin-top:4px;">
                                        <input type="text" readonly value="{{ route('guides.shared', ['uuid' => $guide->uuid, 'token' => $guide->share_token]) }}" class="copy-input" id="shared-link">
                                        <button type="button" class="small-btn" onclick="navigator.clipboard.writeText('{{ route('guides.shared', ['uuid' => $guide->uuid, 'token' => $guide->share_token]) }}')">Copy</button>
                                        <form method="POST" action="{{ route('settings.guides.revokeShareToken', $guide) }}" style="display:inline;">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="small-btn revoke-btn">Revoke</button>
                                        </form>
                                    </div>
                                @else
                                    <div style="display:flex; gap:8px; align-items:center; margin-top:4px;">
                                        <span class="text-sm">No shared link generated.</span>
                                        <form method="POST" action="{{ route('settings.guides.regenerateShareToken', $guide) }}" style="display:inline;">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="small-btn regen-btn">Generate Link</button>
                                        </form>
                                    </div>
                                @endif
                                <div class="helper-text">
                                    Shared link grants access if the token matches; private guides still require login even with a shared link.
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Buttons -->
                <div class="button-container">
                    <a href="{{ route('settings.guides.index') }}" class="cancel-btn">
                        Cancel
                    </a>
                    <button type="submit" class="update-btn">
                        Update Guide
                    </button>
                </div>
            </form>

            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    const form = document.getElementById('edit-guide-form');
                    const publicRadio = form.querySelector('input[name="is_public"][value="1"]');
                    const privateRadio = form.querySelector('input[name="is_public"][value="0"]');
                    const initialPublic = {{ $guide->is_public ? 'true' : 'false' }};
                    const hasShareToken = {!! $guide->share_token ? 'true' : 'false' !!};

                    form.addEventListener('submit', (e) => {
                        const nowPublic = publicRadio.checked;
                        if (!initialPublic && nowPublic && hasShareToken) {
                            const confirmed = confirm('You are making this guide public. This will revoke the existing shared link. Continue?');
                            if (!confirmed) {
                                e.preventDefault();
                            }
                        }
                    });
                });
            </script>
        </div>
    </div>
</x-app-layout>
