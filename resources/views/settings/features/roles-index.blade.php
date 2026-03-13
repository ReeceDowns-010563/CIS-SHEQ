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

            .create-feature-btn {
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

            .create-feature-btn:hover {
                background-color: var(--secondary-colour);
                border-color: var(--secondary-colour);
                transform: translateY(-1px);
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            }

            .create-feature-btn:active {
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
                <a href="{{ route('settings.features.index') }}" class="back-button" title="Back to Feature Access Control">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <h2 class="header-title">View and Manage Roles</h2>
            </div>
            <a href="{{ route('settings.roles.create') }}" class="create-feature-btn">
                Create New Role
            </a>
        </div>
    </x-slot>

    <style>
        .main-container {
            padding: 2.5rem 1rem;
            color: #374151;
            background-color: #f8fafc;
            min-height: 100vh;
        }
        @media (prefers-color-scheme: dark) {
            .main-container { color: #d1d5db; background-color: #111827; }
        }
        .content-wrapper { max-width: 64rem; margin: 0 auto; }
        .table-container {
            background-color: white;
            border-radius: 0.75rem;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06);
            overflow: hidden;
            margin-top: 1.5rem;
        }
        @media (prefers-color-scheme: dark) {
            .table-container {
                background-color: #1f2937;
                box-shadow: 0 4px 6px -1px rgba(0,0,0,0.4), 0 2px 4px -1px rgba(0,0,0,0.25);
            }
        }
        .users-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }
        .users-table th {
            padding: 1.25rem 1.5rem;
            text-align: left;
            font-weight: 700;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            background-color: #e5e7eb;
            color: #374151;
            border-bottom: 2px solid #d1d5db;
        }
        @media (prefers-color-scheme: dark) {
            .users-table th { background-color: #374151; color: #d1d5db; border-bottom-color: #4b5563; }
        }
        .users-table td {
            padding: 1rem 1.5rem;
            font-size: 0.875rem;
            color: #374151;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: top;
        }
        @media (prefers-color-scheme: dark) {
            .users-table td { color: #d1d5db; border-bottom-color: #374151; }
        }
        .action-links {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .edit-link {
            color: #2563eb;
            border: 1px solid transparent;
            padding: 0.5rem 0.75rem;
            border-radius: 0.375rem;
            text-decoration: none;
            font-weight: 600;
            transition: all .15s ease-in-out;
        }
        .edit-link:hover { color: #1d4ed8; background-color: #eff6ff; border-color: #bfdbfe; }
        .delete-btn {
            background: none;
            color: #dc2626;
            border: 1px solid transparent;
            padding: 0.5rem 0.75rem;
            border-radius: 0.375rem;
            font-weight: 600;
            cursor: pointer;
            transition: all .15s ease-in-out;
        }
        .delete-btn:hover { color: #b91c1c; background-color: #fef2f2; border-color: #fecaca; }
        .empty-message {
            text-align: center;
            padding: 3rem 2rem;
            color: #6b7280;
            font-size: 1rem;
        }
        @media (prefers-color-scheme: dark) {
            .empty-message { color: #9ca3af; }
        }
    </style>

    <div class="main-container">
        <div class="content-wrapper">
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-100 dark:bg-green-500/10 border border-green-400 dark:border-green-500 text-green-700 dark:text-green-300 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <div class="table-container">
                <table class="users-table">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Key</th>
                        <th>Description</th>
                        <th>Users</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($roles as $role)
                        <tr>
                            <td>{{ $role->display_name ?? ucfirst($role->name) }}</td>
                            <td>{{ $role->name }}</td>
                            <td>{{ $role->description ?? '—' }}</td>
                            <td>
                                {{ $role->users_count ?? $role->users->count() }}
                            </td>
                            <td>
                                <div class="action-links">
                                    <a href="{{ route('settings.roles.edit', $role) }}" class="edit-link">Edit</a>
                                    <form method="POST" action="{{ route('settings.roles.destroy', $role) }}" style="display:inline;" onsubmit="return confirm('Delete this role?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="delete-btn">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="empty-message">
                                <div class="text-center">
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">No roles defined</h3>
                                    <p class="text-gray-500 dark:text-gray-400 mb-4">
                                        Get started by creating a role.
                                    </p>
                                    <a href="{{ route('settings.roles.create') }}" class="create-role-btn">
                                        Add Role
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
