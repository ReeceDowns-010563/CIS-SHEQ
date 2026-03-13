<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Add New Customer</h2>
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
                    ring: 2px solid #6366f1;
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
            </style>

            <form method="POST" action="{{ route('settings.customers.store') }}" class="space-y-6">
                @csrf

                {{-- Customer Name --}}
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Customer Name *
                    </label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name') }}"
                        class="form-input"
                        required
                    />
                </div>

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Email Address *
                    </label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        class="form-input"
                        required
                    />
                </div>

                {{-- Phone --}}
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Phone Number
                    </label>
                    <input
                        type="text"
                        id="phone"
                        name="phone"
                        value="{{ old('phone') }}"
                        class="form-input"
                    />
                </div>

                {{-- Company --}}
                <div>
                    <label for="company" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Company
                    </label>
                    <input
                        type="text"
                        id="company"
                        name="company"
                        value="{{ old('company') }}"
                        class="form-input"
                    />
                </div>

                {{-- Contact Person --}}
                <div>
                    <label for="contact_person" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Contact Person
                    </label>
                    <input
                        type="text"
                        id="contact_person"
                        name="contact_person"
                        value="{{ old('contact_person') }}"
                        class="form-input"
                    />
                </div>

                {{-- Address --}}
                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Address
                    </label>
                    <textarea
                        id="address"
                        name="address"
                        rows="3"
                        class="form-input"
                    >{{ old('address') }}</textarea>
                </div>

                {{-- Postcode --}}
                <div>
                    <label for="postcode" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Postcode
                    </label>
                    <input
                        type="text"
                        id="postcode"
                        name="postcode"
                        value="{{ old('postcode') }}"
                        class="form-input"
                    />
                </div>

                {{-- Notes --}}
                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Notes
                    </label>
                    <textarea
                        id="notes"
                        name="notes"
                        rows="3"
                        class="form-input"
                    >{{ old('notes') }}</textarea>
                </div>

                {{-- Active Checkbox --}}
                <input type="hidden" name="active" value="0" />
                <div class="flex items-center">
                    <input
                        id="active"
                        name="active"
                        type="checkbox"
                        value="1"
                        {{ old('active', true) ? 'checked' : '' }}
                        class="form-checkbox"
                    />
                    <label for="active" class="ml-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Active Customer
                    </label>
                </div>

                {{-- Action Buttons --}}
                <div class="flex justify-center space-x-8">
                    <a href="{{ route('settings.customers.index') }}" class="cancel-button">
                        <strong>Cancel</strong>
                    </a>
                    <button type="submit" class="custom-button">
                        <strong>Create Customer</strong>
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
