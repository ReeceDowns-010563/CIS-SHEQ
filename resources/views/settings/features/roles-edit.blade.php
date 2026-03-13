<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Edit Role</h2>
    </x-slot>

    <style>
        .form-container {
            max-width: 32rem;
            margin: 0 auto;
            margin-top: 1.5rem;
            background-color: white;
            padding: 2rem;
            border-radius: 0.75rem;
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -2px rgba(0,0,0,0.05);
            color: #374151;
        }
        @media (prefers-color-scheme: dark) {
            .form-container {
                background-color: #1f2937;
                color: #e5e7eb;
                box-shadow: 0 10px 15px -3px rgba(0,0,0,0.4), 0 4px 6px -2px rgba(0,0,0,0.25);
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
        .form-input {
            width: 100%;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            border: 2px solid #d1d5db;
            border-radius: 0.5rem;
            background-color: white;
            color: #111827;
            transition: all 0.15s ease-in-out;
            box-shadow: 0 1px 2px 0 rgba(0,0,0,0.05);
        }
        .form-input:focus {
            outline: none;
            border-color: var(--primary-colour);
            box-shadow: 0 0 0 3px rgba(167,98,44,0.1);
        }
        .form-input:hover {
            border-color: #9ca3af;
        }
        @media (prefers-color-scheme: dark) {
            .form-input {
                background-color: #374151;
                border-color: #4b5563;
                color: #e5e7eb;
                box-shadow: 0 1px 2px 0 rgba(0,0,0,0.3);
            }
            .form-input:focus {
                border-color: var(--primary-colour);
                box-shadow: 0 0 0 3px rgba(167,98,44,0.2);
            }
            .form-input:hover {
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
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06);
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
            background-color: #924f25;
            border-color: #924f25;
            transform: translateY(-1px);
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06);
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
        @media (max-width: 640px) {
            .form-container {
                margin: 1rem;
                padding: 1.5rem;
            }
            .button-container {
                flex-direction: column;
                width: 100%;
            }
            .cancel-btn, .update-btn {
                width: 100%;
                text-align: center;
            }
        }
    </style>

    <div class="py-10 px-4">
        <div class="form-container">
            <div class="form-title">
                <h3 style="font-size: 1.25rem; font-weight: 700; margin: 0;">Edit Role</h3>
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

            <form method="POST" action="{{ route('settings.roles.update', $role) }}">
                @csrf
                @method('PUT')

                <!-- Role Key -->
                <div class="form-group">
                    <label for="name" class="form-label">Role Key <span style="font-weight:400;">(unique, e.g. "admin", "basic")</span></label>
                    <input
                        id="name"
                        name="name"
                        type="text"
                        required
                        value="{{ old('name', $role->name) }}"
                        class="form-input"
                        placeholder="E.g. admin, basic"
                    />
                    <div class="helper-text">Must be unique and lower case, no spaces.</div>
                </div>

                <!-- Display Name -->
                <div class="form-group">
                    <label for="display_name" class="form-label">Display Name</label>
                    <input
                        id="display_name"
                        name="display_name"
                        type="text"
                        required
                        value="{{ old('display_name', $role->display_name) }}"
                        class="form-input"
                        placeholder="E.g. Administrator"
                    />
                </div>

                <!-- Description -->
                <div class="form-group">
                    <label for="description" class="form-label">Description</label>
                    <input
                        id="description"
                        name="description"
                        type="text"
                        value="{{ old('description', $role->description) }}"
                        class="form-input"
                        placeholder="Short description (optional)"
                    />
                </div>

                <div class="button-container">
                    <a href="{{ route('settings.roles.index') }}" class="cancel-btn">
                        Cancel
                    </a>
                    <button type="submit" class="update-btn">
                        Update Role
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
