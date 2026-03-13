<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
            Submit Complaint
        </h2>
    </x-slot>

    <div class="py-6 px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
            @if(session('success'))
                <div class="mb-4 text-green-500 font-semibold">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-500/10 border border-red-500 text-red-700 rounded">
                    <strong class="block mb-2">Form Error:</strong>
                    <ul class="list-disc pl-5 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-500/10 border border-green-500 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('complaints.store') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label for="date_received" class="block font-medium text-sm text-gray-800 dark:text-gray-200">
                        Date Complaint Received <span class="text-red-500">*</span>
                    </label>
                    <x-text-input id="date_received" type="date" name="date_received" value="{{ old('date_received') }}" required class="mt-1 block w-full" />
                    @error('date_received')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label for="name" class="block font-medium text-sm text-gray-800 dark:text-gray-200">
                        Name of Complainant <span class="text-red-500">*</span>
                    </label>
                    <x-text-input id="name" name="name" value="{{ old('name') }}" required class="mt-1 block w-full" />
                    @error('name')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label for="pcn_number" class="block font-medium text-sm text-gray-800 dark:text-gray-200">
                        PCN Number <span class="text-red-500">*</span>
                    </label>
                    <x-text-input id="pcn_number" name="pcn_number" value="{{ old('pcn_number') }}" required class="mt-1 block w-full" />
                    @error('pcn_number')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label for="site_id" class="block font-medium text-sm text-gray-800 dark:text-gray-200">
                        Site <span class="text-red-500">*</span>
                    </label>
                    <select id="site_id" name="site_id" required class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Select a site...</option>
                        @foreach($sites as $site)
                            <option value="{{ $site->id }}" {{ old('site_id') == $site->id ? 'selected' : '' }}>
                                {{ $site->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('site_id')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label for="nature" class="block font-medium text-sm text-gray-800 dark:text-gray-200">
                        Nature of Complaint <span class="text-red-500">*</span>
                    </label>
                    <textarea id="nature" name="nature" required rows="4"
                              class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">{{ old('nature') }}</textarea>
                    @error('nature')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <x-input-label for="date_acknowledged" value="Date Complaint Acknowledged" />
                    <x-text-input id="date_acknowledged" type="date" name="date_acknowledged" value="{{ old('date_acknowledged') }}" class="mt-1 block w-full" />
                    @error('date_acknowledged')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <x-input-label for="conclusion" value="Conclusion" />
                    <textarea id="conclusion" name="conclusion" rows="3"
                              class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">{{ old('conclusion') }}</textarea>
                    @error('conclusion')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <x-input-label for="date_concluded" value="Date Concluded" />
                    <x-text-input id="date_concluded" type="date" name="date_concluded" value="{{ old('date_concluded') }}" class="mt-1 block w-full" />
                    @error('date_concluded')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <x-input-label for="ico_complaint" value="ICO Complaint" />
                    <x-text-input id="ico_complaint" name="ico_complaint" value="{{ old('ico_complaint') }}" class="mt-1 block w-full" />
                    @error('ico_complaint')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="flex justify-center">
                    <button type="submit"
                            style="background-color: var(--primary-colour); border: 1px solid var(--primary-colour); color: white; padding: 0.5rem 1rem; border-radius: 0.375rem;">
                        <strong>Submit</strong>
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
