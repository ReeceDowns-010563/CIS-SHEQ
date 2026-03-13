<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        @if($branding && $branding->light_logo)
                            <img src="{{ asset($branding->light_logo) }}" alt="Company Logo" class="h-9 w-auto block dark:hidden">
                        @else
                            <img src="{{ asset('images/logo-light.png') }}" alt="Company Logo" class="h-9 w-auto block dark:hidden">
                        @endif

                        @if($branding && $branding->dark_logo)
                            <img src="{{ asset($branding->dark_logo) }}" alt="Company Logo" class="h-9 w-auto hidden dark:block">
                        @else
                            <img src="{{ asset('images/logo-dark.png') }}" alt="Company Logo" class="h-9 w-auto hidden dark:block">
                        @endif
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <!-- Complaints Dropdown - Only show if user can access complaints feature -->
                    @canAccessFeature('complaints')
                    <div class="relative flex items-center" x-data="{ open: false, loaded: false }" x-init="$nextTick(() => loaded = true)">
                        <button @click="open = ! open"
                                class="inline-flex items-center px-4 py-2 text-sm font-medium leading-5 transition-all duration-200 ease-in-out focus:outline-none rounded-md
                                           {{ request()->routeIs('complaints.*')
                                              ? 'text-indigo-700 dark:text-indigo-300 bg-indigo-100 dark:bg-indigo-900/30 border border-indigo-300 dark:border-indigo-700'
                                              : 'text-gray-800 dark:text-gray-200 hover:text-indigo-700 dark:hover:text-indigo-300 hover:bg-gray-100 dark:hover:bg-gray-700 border border-transparent' }}">
                            <span class="font-semibold">{{ __('Complaints') }}</span>
                            <svg class="ms-2 -me-0.5 h-4 w-4 transition-transform duration-200"
                                 :class="{'rotate-180': open}"
                                 xmlns="http://www.w3.org/2000/svg"
                                 viewBox="0 0 20 20"
                                 fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>

                        <!-- Dropdown Menu -->
                        <div x-show="open && loaded"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                             x-transition:leave-end="opacity-0 scale-95 translate-y-2"
                             @click.outside="open = false"
                             class="absolute left-0 z-50 origin-top-left bg-white dark:bg-gray-800 shadow-xl ring-1 ring-gray-200 dark:ring-gray-700 rounded-lg focus:outline-none"
                             style="top: calc(100% + 0.5rem); width: 22rem; display: none;">
                            <div class="py-3" role="menu" aria-orientation="vertical">
                                <a href="{{ route('complaints.create') }}"
                                   class="flex flex-col px-5 py-3 text-gray-800 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-gray-100 transition-colors duration-150 ease-in-out
                                              {{ request()->routeIs('complaints.create') ? 'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-700 dark:text-indigo-300' : '' }}"
                                   role="menuitem">
                                    <span class="font-semibold text-sm">{{ __('New Complaint') }}</span>
                                    <span class="text-xs text-gray-600 dark:text-gray-400 mt-1">Create a new complaint record</span>
                                </a>

                                <a href="{{ route('complaints.my-investigations') }}"
                                   class="flex flex-col px-5 py-3 text-gray-800 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-gray-100 transition-colors duration-150 ease-in-out
                                    {{ request()->routeIs('complaints.my-investigations') ? 'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-700 dark:text-indigo-300' : '' }}"
                                   role="menuitem">
                                    <span class="font-semibold text-sm">{{ __('My Investigations') }}</span>
                                    <span class="text-xs text-gray-600 dark:text-gray-400 mt-1">View complaints assigned to me</span>
                                </a>

                                <a href="{{ route('complaints.manage') }}"
                                   class="flex flex-col px-5 py-3 text-gray-800 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-gray-100 transition-colors duration-150 ease-in-out
                                              {{ request()->routeIs('complaints.manage') ? 'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-700 dark:text-indigo-300' : '' }}"
                                   role="menuitem">
                                    <span class="font-semibold text-sm">{{ __('View & Manage Complaints') }}</span>
                                    <span class="text-xs text-gray-600 dark:text-gray-400 mt-1">Browse and manage existing complaints</span>
                                </a>

                                <a href="{{ route('complaints.download') }}"
                                   class="flex flex-col px-5 py-3 text-gray-800 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-gray-100 transition-colors duration-150 ease-in-out
                                              {{ request()->routeIs('complaints.download') ? 'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-700 dark:text-indigo-300' : '' }}"
                                   role="menuitem">
                                    <span class="font-semibold text-sm">{{ __('Export Complaints') }}</span>
                                    <span class="text-xs text-gray-600 dark:text-gray-400 mt-1">Download complaints data</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    @endCanAccessFeature

                    <!-- Incidents Dropdown - Only show if user can access incidents feature -->
                    @canAccessFeature('incidents')
                    <div class="relative flex items-center" x-data="{ open: false, loaded: false }" x-init="$nextTick(() => loaded = true)">
                        <button @click="open = ! open"
                                class="inline-flex items-center px-4 py-2 text-sm font-medium leading-5 transition-all duration-200 ease-in-out focus:outline-none rounded-md
                                           {{ request()->routeIs('incidents.*')
                                              ? 'text-indigo-700 dark:text-indigo-300 bg-indigo-100 dark:bg-indigo-900/30 border border-indigo-300 dark:border-indigo-700'
                                              : 'text-gray-800 dark:text-gray-200 hover:text-indigo-700 dark:hover:text-indigo-300 hover:bg-gray-100 dark:hover:bg-gray-700 border border-transparent' }}">
                            <span class="font-semibold">{{ __('Accidents') }}</span>
                            <svg class="ms-2 -me-0.5 h-4 w-4 transition-transform duration-200"
                                 :class="{'rotate-180': open}"
                                 xmlns="http://www.w3.org/2000/svg"
                                 viewBox="0 0 20 20"
                                 fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>

                        <!-- Dropdown Menu -->
                        <div x-show="open && loaded"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                             x-transition:leave-end="opacity-0 scale-95 translate-y-2"
                             @click.outside="open = false"
                             class="absolute left-0 z-50 origin-top-left bg-white dark:bg-gray-800 shadow-xl ring-1 ring-gray-200 dark:ring-gray-700 rounded-lg focus:outline-none"
                             style="top: calc(100% + 0.5rem); width: 22rem; display: none;">
                            <div class="py-3" role="menu" aria-orientation="vertical">
                                <a href="{{ route('incidents.create') }}"
                                   class="flex flex-col px-5 py-3 text-gray-800 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-gray-100 transition-colors duration-150 ease-in-out
                                              {{ request()->routeIs('incidents.create') ? 'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-700 dark:text-indigo-300' : '' }}"
                                   role="menuitem">
                                    <span class="font-semibold text-sm">{{ __('Report an Accident') }}</span>
                                    <span class="text-xs text-gray-600 dark:text-gray-400 mt-1">Log Accidents or near misses</span>
                                </a>

                                <a href="{{ route('incidents.my-investigations') }}"
                                   class="flex flex-col px-5 py-3 text-gray-800 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-gray-100 transition-colors duration-150 ease-in-out
                                              {{ request()->routeIs('incidents.my-investigations') ? 'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-700 dark:text-indigo-300' : '' }}"
                                   role="menuitem">
                                    <span class="font-semibold text-sm">{{ __('My Investigations') }}</span>
                                    <span class="text-xs text-gray-600 dark:text-gray-400 mt-1">Investigations raised by or assigned to you</span>
                                </a>

                                <a href="{{ route('incidents.register') }}"
                                   class="flex flex-col px-5 py-3 text-gray-800 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-gray-100 transition-colors duration-150 ease-in-out
                                              {{ request()->routeIs('incidents.register') ? 'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-700 dark:text-indigo-300' : '' }}"
                                   role="menuitem">
                                    <span class="font-semibold text-sm">{{ __('Accident Register') }}</span>
                                    <span class="text-xs text-gray-600 dark:text-gray-400 mt-1">All recorded accidents with filtering and export</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    @endCanAccessFeature

                    <!-- Reports Dropdown - Only show if user can access reports feature -->
                    @canAccessFeature('reports')
                    <div class="relative flex items-center" x-data="{ open: false, loaded: false }" x-init="$nextTick(() => loaded = true)">
                        <button @click="open = ! open"
                                class="inline-flex items-center px-4 py-2 text-sm font-medium leading-5 transition-all duration-200 ease-in-out focus:outline-none rounded-md
                                           {{ request()->routeIs('reports.*')
                                              ? 'text-indigo-700 dark:text-indigo-300 bg-indigo-100 dark:bg-indigo-900/30 border border-indigo-300 dark:border-indigo-700'
                                              : 'text-gray-800 dark:text-gray-200 hover:text-indigo-700 dark:hover:text-indigo-300 hover:bg-gray-100 dark:hover:bg-gray-700 border border-transparent' }}">
                            <span class="font-semibold">{{ __('Reports') }}</span>
                            <svg class="ms-2 -me-0.5 h-4 w-4 transition-transform duration-200"
                                 :class="{'rotate-180': open}"
                                 xmlns="http://www.w3.org/2000/svg"
                                 viewBox="0 0 20 20"
                                 fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>

                        <!-- Dropdown Menu -->
                        <div x-show="open && loaded"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                             x-transition:leave-end="opacity-0 scale-95 translate-y-2"
                             @click.outside="open = false"
                             class="absolute left-0 z-50 origin-top-left bg-white dark:bg-gray-800 shadow-xl ring-1 ring-gray-200 dark:ring-gray-700 rounded-lg focus:outline-none"
                             style="top: calc(100% + 0.5rem); width: 22rem; display: none;">
                            <div class="py-3" role="menu" aria-orientation="vertical">
                                <a href="{{ route('reports.export') }}"
                                   class="flex flex-col px-5 py-3 text-gray-800 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-gray-100 transition-colors duration-150 ease-in-out
                                              {{ request()->routeIs('reports.export') ? 'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-700 dark:text-indigo-300' : '' }}"
                                   role="menuitem">
                                    <span class="font-semibold text-sm">{{ __('Monthly Reports') }}</span>
                                    <span class="text-xs text-gray-600 dark:text-gray-400 mt-1">View and generate monthly reports</span>
                                </a>
                                <a href="{{ route('charts.index') }}"
                                   class="flex flex-col px-5 py-3 text-gray-800 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-gray-100 transition-colors duration-150 ease-in-out
                                      {{ request()->routeIs('charts.index') ? 'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-700 dark:text-indigo-300' : '' }}"
                                   role="menuitem">
                                    <span class="font-semibold text-sm">{{ __('Analytics & Charts') }}</span>
                                    <span class="text-xs text-gray-600 dark:text-gray-400 mt-1">Interactive data visualizations</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    @endCanAccessFeature
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        @auth
                            @canAccessFeature('settings')
                            <x-dropdown-link :href="route('settings.index')">
                                Settings
                            </x-dropdown-link>
                            @endCanAccessFeature
                        @endauth

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                             onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <!-- Complaints Dropdown for Mobile -->
            @canAccessFeature('complaints')
            <div x-data="{ complaintsOpen: false }" class="border-b border-gray-200 dark:border-gray-600">
                <button @click="complaintsOpen = ! complaintsOpen"
                        class="flex items-center justify-between w-full px-3 py-2 text-start font-medium text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:text-gray-800 dark:focus:text-gray-200 focus:bg-gray-50 dark:focus:bg-gray-700 transition duration-150 ease-in-out
                                   {{ request()->routeIs('complaints.*') ? 'text-indigo-700 dark:text-indigo-300 bg-indigo-50 dark:bg-indigo-900/20' : '' }}">
                    <span class="font-semibold">{{ __('Complaints') }}</span>
                    <svg class="h-4 w-4 transition-transform duration-200"
                         :class="{'rotate-180': complaintsOpen}"
                         xmlns="http://www.w3.org/2000/svg"
                         viewBox="0 0 20 20"
                         fill="currentColor">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>

                <div x-show="complaintsOpen"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="bg-gray-50 dark:bg-gray-800">
                    <x-responsive-nav-link :href="route('complaints.create')" :active="request()->routeIs('complaints.create')" class="pl-8">
                        {{ __('New Complaint') }}
                    </x-responsive-nav-link>

                    <x-responsive-nav-link :href="route('complaints.my-investigations')" :active="request()->routeIs('complaints.my-investigations')" class="pl-8">
                        {{ __('My Investigations') }}
                    </x-responsive-nav-link>

                    <x-responsive-nav-link :href="route('complaints.manage')" :active="request()->routeIs('complaints.manage')" class="pl-8">
                        {{ __('View & Manage Complaints') }}
                    </x-responsive-nav-link>

                    <x-responsive-nav-link :href="route('complaints.download')" :active="request()->routeIs('complaints.download')" class="pl-8">
                        {{ __('Export Complaints') }}
                    </x-responsive-nav-link>
                </div>
            </div>
            @endCanAccessFeature

            <!-- Incidents Dropdown for Mobile -->
            @canAccessFeature('incidents')
            <div x-data="{ incidentsOpen: false }" class="border-b border-gray-200 dark:border-gray-600">
                <button @click="incidentsOpen = ! incidentsOpen"
                        class="flex items-center justify-between w-full px-3 py-2 text-start font-medium text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:text-gray-800 dark:focus:text-gray-200 focus:bg-gray-50 dark:focus:bg-gray-700 transition duration-150 ease-in-out
                                   {{ request()->routeIs('incidents.*') ? 'text-indigo-700 dark:text-indigo-300 bg-indigo-50 dark:bg-indigo-900/20' : '' }}">
                    <span class="font-semibold">{{ __('Accidents') }}</span>
                    <svg class="h-4 w-4 transition-transform duration-200"
                         :class="{'rotate-180': incidentsOpen}"
                         xmlns="http://www.w3.org/2000/svg"
                         viewBox="0 0 20 20"
                         fill="currentColor">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>

                <div x-show="incidentsOpen"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="bg-gray-50 dark:bg-gray-800">
                    <x-responsive-nav-link :href="route('incidents.create')" :active="request()->routeIs('incidents.create')" class="pl-8">
                        {{ __('Report an Accident') }}
                    </x-responsive-nav-link>

                    <x-responsive-nav-link :href="route('incidents.my-investigations')" :active="request()->routeIs('incidents.my-investigations')" class="pl-8">
                        {{ __('My Investigations') }}
                    </x-responsive-nav-link>

                    <x-responsive-nav-link :href="route('incidents.register')" :active="request()->routeIs('incidents.register')" class="pl-8">
                        {{ __('Incident Register') }}
                    </x-responsive-nav-link>
                </div>
            </div>
            @endCanAccessFeature

            <!-- Reports Dropdown for Mobile -->
            @canAccessFeature('reports')
            <div x-data="{ reportsOpen: false }" class="border-b border-gray-200 dark:border-gray-600">
                <button @click="reportsOpen = ! reportsOpen"
                        class="flex items-center justify-between w-full px-3 py-2 text-start font-medium text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:text-gray-800 dark:focus:text-gray-200 focus:bg-gray-50 dark:focus:bg-gray-700 transition duration-150 ease-in-out
                                   {{ request()->routeIs('reports.*') ? 'text-indigo-700 dark:text-indigo-300 bg-indigo-50 dark:bg-indigo-900/20' : '' }}">
                    <span class="font-semibold">{{ __('Reports') }}</span>
                    <svg class="h-4 w-4 transition-transform duration-200"
                         :class="{'rotate-180': reportsOpen}"
                         xmlns="http://www.w3.org/2000/svg"
                         viewBox="0 0 20 20"
                         fill="currentColor">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>

                <div x-show="reportsOpen"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="bg-gray-50 dark:bg-gray-800">
                    <x-responsive-nav-link :href="route('reports.export')" :active="request()->routeIs('reports.export')" class="pl-8">
                        {{ __('Monthly Reports') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('charts.index')" :active="request()->routeIs('charts.index')" class="pl-8">
                        {{ __('Analytics & Charts') }}
                    </x-responsive-nav-link>
                </div>
            </div>
            @endCanAccessFeature
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                @auth
                    @canAccessFeature('settings')
                    <x-responsive-nav-link :href="route('settings.index')">
                        {{ __('Settings') }}
                    </x-responsive-nav-link>
                    @endCanAccessFeature
                @endauth

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                                           onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
