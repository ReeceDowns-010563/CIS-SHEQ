<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Add New Guide</h2>
    </x-slot>

    <div class="py-10 px-4">
        <div class="max-w-3xl mx-auto mt-6 bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">

            {{-- Validation Errors --}}
            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-100 dark:bg-red-500/10 border border-red-400 dark:border-red-500 text-red-700 dark:text-red-300 rounded">
                    <strong class="block mb-2">Please fix the following:</strong>
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <style>
                .form-input {
                    width: 100%;
                    padding: 0.5rem 0.75rem;
                    background-color: white;
                    border: 1px solid #e5e7eb;
                    border-radius: 0.375rem;
                    color: #111827;
                    margin-top: 0.25rem;
                }

                .dark .form-input {
                    background-color: #374151;
                    border-color: #4b5563;
                    color: #e5e7eb;
                }

                .form-input:focus {
                    outline: none;
                    border-color: #6366f1;
                }

                .custom-button {
                    background-color: var(--primary-colour);
                    border: 1px solid var(--primary-colour);
                    color: white;
                    padding: 0.5rem 1rem;
                    border-radius: 0.375rem;
                    font-weight: bold;
                    transition: opacity 0.2s ease;
                }

                .custom-button:hover {
                    opacity: 0.9;
                }

                .cancel-button {
                    background-color: #6b7280;
                    border: 1px solid #6b7280;
                    color: white;
                    padding: 0.5rem 1rem;
                    border-radius: 0.375rem;
                    font-weight: bold;
                    transition: opacity 0.2s ease;
                    text-decoration: none;
                    display: inline-block;
                }

                .cancel-button:hover {
                    opacity: 0.9;
                    color: white;
                    text-decoration: none;
                }

                .form-checkbox {
                    width: 1rem;
                    height: 1rem;
                    border-radius: 0.25rem;
                    border: 1px solid #e5e7eb;
                    background-color: white;
                }

                .dark .form-checkbox {
                    border-color: #4b5563;
                    background-color: #374151;
                }

                .file-input-wrapper {
                    position: relative;
                    display: flex;
                    flex-direction: column;
                }

                .note {
                    font-size: 0.75rem;
                    color: #6b7280;
                    margin-top: 0.25rem;
                }

                @media (prefers-color-scheme: dark) {
                    .note {
                        color: #9ca3af;
                    }
                }
            </style>

            <form method="POST" action="{{ route('settings.guides.store') }}" class="space-y-6" enctype="multipart/form-data">
                @csrf

                {{-- Title --}}
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Guide Title *
                    </label>
                    <input
                        type="text"
                        id="title"
                        name="title"
                        value="{{ old('title') }}"
                        class="form-input"
                        required
                    />
                </div>

                {{-- Description --}}
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Description
                    </label>
                    <textarea
                        id="description"
                        name="description"
                        rows="3"
                        class="form-input"
                    >{{ old('description') }}</textarea>
                </div>

                {{-- PDF Upload --}}
                <div>
                    <label for="file" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Upload PDF *
                    </label>
                    <div class="file-input-wrapper">
                        <input
                            type="file"
                            id="file"
                            name="file"
                            accept="application/pdf"
                            required
                            class="form-input"
                        />
                        <div class="note">Only PDF files are allowed. Max size: 10MB.</div>
                    </div>
                </div>

                {{-- Visibility --}}
                <div class="flex items-start space-x-3">
                    <div>
                        <input
                            id="is_public"
                            name="is_public"
                            type="checkbox"
                            value="1"
                            {{ old('is_public') ? 'checked' : '' }}
                            class="form-checkbox"
                        />
                    </div>
                    <div>
                        <label for="is_public" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Public Guide
                        </label>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            If checked, anyone with the link can view it. If unchecked, only authenticated users (or those with a shared token) can access it; you can generate a private share link after creation.
                        </p>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex justify-center space-x-8">
                    <a href="{{ route('settings.guides.index') }}" class="cancel-button">
                        <strong>Cancel</strong>
                    </a>
                    <button type="submit" class="custom-button">
                        <strong>Create Guide</strong>
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
