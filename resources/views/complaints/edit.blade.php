<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Edit Complaint</h2>
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
            </style>

            <form method="POST" action="{{ route('complaints.update', $complaint) }}" class="space-y-6">
                @csrf
                @method('PUT')

                {{-- Date Complaint Received --}}
                <div>
                    <label for="date_received" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Date Complaint Received
                    </label>
                    <input
                        type="date"
                        id="date_received"
                        name="date_received"
                        value="{{ old('date_received', $complaint->date_received_for_input) }}"
                        class="form-input"
                        required
                    />
                </div>

                {{-- Name of Complainant --}}
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Name of Complainant
                    </label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name', $complaint->name) }}"
                        class="form-input"
                        required
                    />
                </div>

                {{-- PCN Number --}}
                <div>
                    <label for="pcn_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        PCN Number
                    </label>
                    <input
                        type="text"
                        id="pcn_number"
                        name="pcn_number"
                        value="{{ old('pcn_number', $complaint->pcn_number) }}"
                        class="form-input"
                    />
                </div>

                {{-- Site Selection --}}
                <div>
                    <label for="site_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Site
                    </label>
                    <select id="site_id" name="site_id" required class="form-input">
                        <option value="">Select a site...</option>
                        @foreach($sites as $site)
                            <option value="{{ $site->id }}" {{ old('site_id', $complaint->site_id) == $site->id ? 'selected' : '' }}>
                                {{ $site->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Nature of Complaint --}}
                <div>
                    <label for="nature" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Nature of Complaint
                    </label>
                    <textarea
                        id="nature"
                        name="nature"
                        rows="3"
                        class="form-input"
                        required
                    >{{ old('nature', $complaint->nature) }}</textarea>
                </div>

                {{-- Date Complaint Acknowledged --}}
                <div>
                    <label for="date_acknowledged" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Date Complaint Acknowledged
                    </label>
                    <input
                        type="date"
                        id="date_acknowledged"
                        name="date_acknowledged"
                        value="{{ old('date_acknowledged', $complaint->date_acknowledged_for_input) }}"
                        class="form-input"
                    />
                </div>

                {{-- Status --}}
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Status
                    </label>
                    <select id="status" name="status" required class="form-input">
                        <option value="open" {{ old('status', $complaint->status) === 'open' ? 'selected' : '' }}>Open</option>
                        <option value="pending" {{ old('status', $complaint->status) === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="closed" {{ old('status', $complaint->status) === 'closed' ? 'selected' : '' }}>Closed</option>
                    </select>
                </div>

                {{-- Conclusion --}}
                <div>
                    <label for="conclusion" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Conclusion
                    </label>
                    <textarea
                        id="conclusion"
                        name="conclusion"
                        rows="3"
                        class="form-input"
                    >{{ old('conclusion', $complaint->conclusion) }}</textarea>
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
                        value="{{ old('date_concluded', $complaint->date_concluded_for_input) }}"
                        class="form-input"
                    />
                </div>

                {{-- ico Complaint --}}
                <div>
                    <label for="ico_complaint" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        ICO Complaint
                    </label>
                    <input
                        type="text"
                        id="ico_complaint"
                        name="ico_complaint"
                        value="{{ old('ico_complaint', $complaint->ico_complaint) }}"
                        class="form-input"
                    />
                </div>

                {{-- Action Buttons --}}
                <div class="flex justify-center space-x-8">
                    <a href="{{ route('complaints.manage') }}" class="cancel-button">
                        <strong>Cancel</strong>
                    </a>
                    <button type="submit" class="custom-button">
                        <strong>Update Complaint</strong>
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
