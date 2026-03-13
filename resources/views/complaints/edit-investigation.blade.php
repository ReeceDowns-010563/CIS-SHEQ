<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Edit Investigation - {{ $complaint->pcn_number }}</h2>
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

                .readonly-field {
                    background-color: #f9fafb !important;
                    cursor: not-allowed;
                    border-style: dashed !important;
                }

                .dark .readonly-field {
                    background-color: #1f2937 !important;
                    color: #9ca3af !important;
                    border-color: #4b5563 !important;
                }
            </style>

            <form action="{{ route('complaints.update', $complaint) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                <input type="hidden" name="return_to" value="investigations">

                {{-- Add hidden fields for required data --}}
                <input type="hidden" name="date_received" value="{{ $complaint->date_received_for_input }}">
                <input type="hidden" name="name" value="{{ $complaint->name }}">
                <input type="hidden" name="pcn_number" value="{{ $complaint->pcn_number }}">
                <input type="hidden" name="site_id" value="{{ $complaint->site_id }}">
                <input type="hidden" name="nature" value="{{ $complaint->nature }}">

                {{-- Date Received --}}
                <div>
                    <label for="date_received_display" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Date Received
                    </label>
                    <input
                        type="text"
                        id="date_received_display"
                        value="{{ $complaint->date_received }}"
                        class="form-input readonly-field"
                        readonly
                    />
                </div>

                {{-- Name --}}
                <div>
                    <label for="name_display" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Name
                    </label>
                    <input
                        type="text"
                        id="name_display"
                        value="{{ $complaint->name }}"
                        class="form-input readonly-field"
                        readonly
                    />
                </div>

                {{-- PCN Number --}}
                <div>
                    <label for="pcn_number_display" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        PCN Number
                    </label>
                    <input
                        type="text"
                        id="pcn_number_display"
                        value="{{ $complaint->pcn_number }}"
                        class="form-input readonly-field"
                        readonly
                    />
                </div>

                {{-- Site --}}
                <div>
                    <label for="site_display" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Site
                    </label>
                    <input
                        type="text"
                        id="site_display"
                        value="{{ $complaint->site->name ?? 'N/A' }}"
                        class="form-input readonly-field"
                        readonly
                    />
                </div>

                {{-- Nature of Complaint --}}
                <div>
                    <label for="nature_display" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Nature of Complaint
                    </label>
                    <input
                        type="text"
                        id="nature_display"
                        value="{{ $complaint->nature }}"
                        class="form-input readonly-field"
                        readonly
                    />
                </div>

                {{-- Assigned To --}}
                <div>
                    <label for="assigned_to" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Assigned To
                    </label>
                    <select id="assigned_to" name="assigned_to" class="form-input">
                        <option value="">Select user...</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ $complaint->assigned_to == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Date Acknowledged --}}
                <div>
                    <label for="date_acknowledged" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Date Acknowledged
                    </label>
                    <input
                        type="date"
                        id="date_acknowledged"
                        name="date_acknowledged"
                        value="{{ $complaint->date_acknowledged_for_input }}"
                        class="form-input"
                    />
                </div>

                {{-- Status --}}
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Status
                    </label>
                    <select id="status" name="status" required class="form-input">
                        <option value="open" {{ $complaint->status === 'open' ? 'selected' : '' }}>Open</option>
                        <option value="closed" {{ $complaint->status === 'closed' ? 'selected' : '' }}>Closed</option>
                    </select>
                </div>

                {{-- Date Concluded --}}
                <div>
                    <label for="date_concluded" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Date Concluded
                    </label>
                    <input
                        type="date"
                        id="date_concluded"
                        name="date_concluded"
                        value="{{ $complaint->date_concluded_for_input }}"
                        class="form-input"
                    />
                </div>

                {{-- ico Complaint --}}
                <div>
                    <label for="ico_complaint" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        ICO Complaint
                    </label>
                    <select id="ico_complaint" name="ico_complaint" class="form-input">
                        <option value="">Select...</option>
                        <option value="yes" {{ $complaint->ico_complaint === 'yes' ? 'selected' : '' }}>Yes</option>
                        <option value="no" {{ $complaint->ico_complaint === 'no' ? 'selected' : '' }}>No</option>
                    </select>
                </div>

                {{-- Investigation Conclusion --}}
                <div>
                    <label for="conclusion" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Investigation Conclusion
                    </label>
                    <textarea
                        id="conclusion"
                        name="conclusion"
                        rows="4"
                        class="form-input"
                        placeholder="Enter detailed investigation conclusion and findings..."
                    >{{ $complaint->conclusion }}</textarea>
                </div>

                {{-- Action Buttons --}}
                <div class="flex justify-center space-x-8">
                    <a href="{{ route('complaints.my-investigations') }}" class="cancel-button">
                        <strong>Cancel</strong>
                    </a>
                    <button type="submit" class="custom-button">
                        <strong>Update Investigation</strong>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // This script modifies the form submission to redirect to the correct page
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');

            form.addEventListener('submit', function(e) {
                // Add the redirect route parameter
                const redirectInput = document.createElement('input');
                redirectInput.type = 'hidden';
                redirectInput.name = 'redirect_to';
                redirectInput.value = 'complaints.my-investigations';
                form.appendChild(redirectInput);
            });
        });
    </script>
</x-app-layout>
