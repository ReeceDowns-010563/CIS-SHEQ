<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Edit Branch</h2>
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

        /* Dark mode container */
        @media (prefers-color-scheme: dark) {
            .form-container {
                background-color: #1f2937;
                color: #e5e7eb;
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.4), 0 4px 6px -2px rgba(0, 0, 0, 0.25);
            }
        }

        /* Error styling */
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

        /* Form group styling */
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

        /* Input styling */
        .form-input {
            width: 100%;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            border: 2px solid #d1d5db;
            border-radius: 0.5rem;
            background-color: white;
            color: #111827;
            transition: all 0.15s ease-in-out;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary-colour);
            box-shadow: 0 0 0 3px rgba(167, 98, 44, 0.1);
        }

        .form-input:hover {
            border-color: #9ca3af;
        }

        /* Dark mode inputs */
        @media (prefers-color-scheme: dark) {
            .form-input {
                background-color: #374151;
                border-color: #4b5563;
                color: #e5e7eb;
                box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.3);
            }

            .form-input:focus {
                border-color: var(--primary-colour);
                box-shadow: 0 0 0 3px rgba(167, 98, 44, 0.2);
            }

            .form-input:hover {
                border-color: #6b7280;
            }
        }

        /* Branch code input styling */
        .branch-code-input {
            font-family: monospace;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        /* Helper text */
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

        /* Info box for current branch */
        .info-box {
            background-color: #eff6ff;
            border: 1px solid #bfdbfe;
            color: #1e40af;
            padding: 0.75rem;
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
            font-size: 0.875rem;
        }

        @media (prefers-color-scheme: dark) {
            .info-box {
                background-color: rgba(59, 130, 246, 0.1);
                border-color: rgba(59, 130, 246, 0.3);
                color: #60a5fa;
            }
        }

        /* Button container */
        .button-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 2rem;
            gap: 1rem;
        }

        /* Cancel button */
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

        /* Update button */
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
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .update-btn:active {
            transform: translateY(0);
        }

        /* Form title enhancement */
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

        /* Responsive adjustments */
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
                <h3 style="font-size: 1.25rem; font-weight: 700; margin: 0;">Edit Branch</h3>
            </div>

            <div class="info-box">
                <strong>Editing:</strong> {{ $branch->display_name }} (Created {{ $branch->created_at->format('M j, Y') }})
                @if($branch->sites()->count() > 0)
                    <br><strong>Sites:</strong> This branch is assigned to {{ $branch->sites()->count() }} site(s)
                @endif
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

            <form method="POST" action="{{ route('settings.branches.update', $branch) }}">
                @csrf
                @method('PUT')

                <!-- Branch Code -->
                <div class="form-group">
                    <label for="branch_code" class="form-label">Branch Code</label>
                    <input
                        id="branch_code"
                        name="branch_code"
                        type="text"
                        required
                        maxlength="10"
                        value="{{ old('branch_code', $branch->branch_code) }}"
                        class="form-input branch-code-input"
                        placeholder="Enter branch code"
                    />
                    <div class="helper-text">Maximum 10 characters, will be converted to uppercase</div>
                </div>

                <!-- Branch Name -->
                <div class="form-group">
                    <label for="branch_name" class="form-label">Branch Name</label>
                    <input
                        id="branch_name"
                        name="branch_name"
                        type="text"
                        required
                        maxlength="255"
                        value="{{ old('branch_name', $branch->branch_name) }}"
                        class="form-input"
                        placeholder="Enter full branch name"
                    />
                    <div class="helper-text">The full descriptive name of the branch</div>
                </div>

                <!-- Buttons -->
                <div class="button-container">
                    <a href="{{ route('settings.branches.index') }}" class="cancel-btn">
                        Cancel
                    </a>
                    <button type="submit" class="update-btn">
                        Update Branch
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Auto-uppercase branch code input
        document.getElementById('branch_code').addEventListener('input', function(e) {
            e.target.value = e.target.value.toUpperCase();
        });
    </script>
</x-app-layout>
