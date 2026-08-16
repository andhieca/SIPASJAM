<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block font-medium text-sm text-gray-200">{{ __('Email') }}</label>
            <input id="email" class="block mt-1 w-full bg-white/20 border border-white/30 rounded-xl text-white placeholder-gray-300 focus:border-pj-gold-400 focus:ring focus:ring-pj-gold-400/50 backdrop-blur-sm transition-all" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="Masukkan email Anda" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-300" />
        </div>

        <!-- Password -->
        <div x-data="{ showPassword: false }">
            <label for="password" class="block font-medium text-sm text-gray-200">{{ __('Password') }}</label>
            <div class="relative mt-1">
                <input id="password" 
                       class="block w-full bg-white/20 border border-white/30 rounded-xl text-white placeholder-gray-300 focus:border-pj-gold-400 focus:ring focus:ring-pj-gold-400/50 backdrop-blur-sm transition-all pr-10"
                       :type="showPassword ? 'text' : 'password'"
                       name="password"
                       required 
                       autocomplete="current-password" 
                       placeholder="••••••••" />
                <button type="button" 
                        @click="showPassword = !showPassword" 
                        class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-300 hover:text-white transition-colors focus:outline-none"
                        title="Tampilkan / Sembunyikan Password">
                    <!-- Eye Icon (Show) -->
                    <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    <!-- Eye Slash Icon (Hide) -->
                    <svg x-show="showPassword" style="display: none;" class="w-5 h-5 text-pj-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858-5.908a10.025 10.025 0 013.98-1.063c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21M3 3l18 18"/>
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-300" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                <input id="remember_me" type="checkbox" class="rounded border-white/30 bg-white/10 text-pj-gold-500 shadow-sm focus:ring-pj-gold-400/50" name="remember">
                <span class="ms-2 text-sm text-gray-300 group-hover:text-white transition-colors">{{ __('Ingat Saya') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-gray-300 hover:text-pj-gold-400 hover:underline transition-colors" href="{{ route('password.request') }}">
                    {{ __('Lupa password?') }}
                </a>
            @endif
        </div>

        <div>
            <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-lg text-sm font-bold text-pj-green-900 bg-gradient-to-r from-pj-gold-400 to-pj-gold-500 hover:from-pj-gold-500 hover:to-pj-gold-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pj-gold-500 transform hover:-translate-y-0.5 transition-all">
                {{ __('Masuk ke Dasbor') }}
            </button>
        </div>
    </form>
</x-guest-layout>
