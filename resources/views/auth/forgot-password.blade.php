<x-guest-layout>
    <!-- Company Logo -->
    <div class="flex justify-center mb-6">
        <img src="{{ asset('images/logo-light.png') }}"
             alt="Company Logo"
             class="h-16 w-auto">
    </div>

    <!-- Page Title -->
    <h2 class="text-2xl font-bold text-center mb-2" style="color: #131F34;">
        {{ __('Forgot Password?') }}
    </h2>

    <!-- Description -->
    <div class="mb-6 text-sm text-gray-600 dark:text-gray-400 text-center">
        {{ __('No problem. Just enter your email address and we will send you a password reset link.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email Address')" />
            <x-text-input
                id="email"
                class="block mt-1 w-full focus:border-[#131F34] focus:ring-[#131F34]"
                type="email"
                name="email"
                :value="old('email')"
                required
                autofocus
                placeholder="your.email@example.com"
            />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex flex-col items-center justify-center mt-6 space-y-4">
            <!-- Submit Button -->
            <button
                type="submit"
                class="w-full px-4 py-2 text-white font-semibold rounded-lg shadow-md transition duration-150 ease-in-out hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2"
                style="background-color: #131F34; border-color: #131F34;"
                onmouseover="this.style.backgroundColor='#1a2744'"
                onmouseout="this.style.backgroundColor='#131F34'"
            >
                {{ __('Email Password Reset Link') }}
            </button>

            <!-- Back to Login Link -->
            <a href="{{ route('login') }}"
               class="text-sm font-medium transition duration-150 ease-in-out hover:underline"
               style="color: #131F34;">
                {{ __('Back to Login') }}
            </a>
        </div>
    </form>
</x-guest-layout>
