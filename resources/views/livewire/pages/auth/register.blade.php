<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $firstname = '';
        public string $lastname = '';
            public string $middlename = '';

    public string $email = '';
    public string $password = '';
      public string $sition = '';
     public string $barangay = '';
      public string $language = '';
        public string $phone_number = '';
    public string $password_confirmation = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'firstname' => ['required', 'string', 'max:255'],
                'lastname' => ['required', 'string', 'max:255'],
                    'middlename' => ['required', 'string', 'max:255'],
             'barangay' => ['required', 'string', 'max:255'],
              'sition' => ['required', 'string', 'max:255'],
              'language' => ['required', 'string', 'max:255'],
                'phone_number' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
           'password' => [
    'required',
    'string',
    'confirmed',
    Rules\Password::min(8)
        ->mixedCase()
        ->numbers()
],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered($user = User::create($validated)));

        Auth::login($user);

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }
}; ?>

{{-- <div>
    <form wire:submit="register">
        <!-- Name -->
        <div>
            <x-input-label for="firstname" :value="__('Firstname')" />
            <x-text-input wire:model="firstname" id="firstname" class="block mt-1 w-full" type="text" firstname="firstname" required autofocus autocomplete="firstname" />
            <x-input-error :messages="$errors->get('firstname')" class="mt-2" />
        </div>

                 <div>
            <x-input-label for="lastname" :value="__('Lastname')" />
            <x-text-input wire:model="lastname" id="lastname" class="block mt-1 w-full" type="text" lastname="lastname" required autofocus autocomplete="lastname" />
            <x-input-error :messages="$errors->get('lastname')" class="mt-2" />
        </div>

            <div>
            <x-input-label for="middlename" :value="__('Middlename')" />
            <x-text-input wire:model="middlename" id="middlename" class="block mt-1 w-full" type="text" middlename="middlename" required autofocus autocomplete="middlename" />
            <x-input-error :messages="$errors->get('middlename')" class="mt-2" />
        </div>



            <div>
            <x-input-label for="phone_number" :value="__('phone_number')" />
            <x-text-input wire:model="phone_number" id="phone_number" class="block mt-1 w-full" type="text" phone_number="phone_number" required autofocus autocomplete="phone_number" />
            <x-input-error :messages="$errors->get('phone_number')" class="mt-2" />
        </div>

       <div>
    <x-input-label for="barangay" :value="__('Barangay')" />
     <x-text-input wire:model="barangay" id="barangay" class="block mt-1 w-full" type="text" barangay="barangay" required autofocus autocomplete="barangay" />
            <x-input-error :messages="$errors->get('barangay')" class="mt-2" />

    <x-input-error :messages="$errors->get('barangay')" class="mt-2" />
</div>

      <div>
    <x-input-label for="sition" :value="__('Sition (Optional)')" />
     <x-text-input wire:model="sition" id="sition" class="block mt-1 w-full" type="text" sition="sition" required autofocus autocomplete="sition" />
            <x-input-error :messages="$errors->get('sition')" class="mt-2" />

    <x-input-error :messages="$errors->get('sition')" class="mt-2" />
</div>

      <div>
    <x-input-label for="language" :value="__('Language')" />
    <select wire:model="language" id="language" class="block mt-1 w-full" required>
        <option value="">-- Select Language --</option>
        <option value="English">English</option>
        <option value="Tagalog">Tagalog</option>
        <option value="Bisaya">Bisaya</option>
    </select>
    <x-input-error :messages="$errors->get('language')" class="mt-2" />
</div>


        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input wire:model="email" id="email" class="block mt-1 w-full" type="email" name="email" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
      <div class="mt-4">
    <x-input-label for="password" :value="__('Password')" />

    <x-text-input wire:model="password" id="password" class="block mt-1 w-full"
                    type="password"
                    name="password"
                    required autocomplete="new-password" />

    <x-input-error :messages="$errors->get('password')" class="mt-2" />

    <!-- Password Reminder Note -->
    <p class="text-xs text-gray-500 mt-1">
        Password must be at least 8 characters long and include a mix of letters, numbers, and symbols.
    </p>
</div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input wire:model="password_confirmation" id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}" wire:navigate>
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</div> --}}

<div>
    <form wire:submit="register" class="space-y-6">
        <!-- Grid Layout -->
        <div class="grid grid-cols-2 gap-4">
            <!-- Firstname -->
            <div>
                <x-input-label for="firstname" :value="__('Firstname')" />
                <x-text-input wire:model="firstname" id="firstname" class="block mt-1 w-full" type="text" required autofocus autocomplete="firstname" />
                <x-input-error :messages="$errors->get('firstname')" class="mt-2" />
            </div>

            <!-- Lastname -->
            <div>
                <x-input-label for="lastname" :value="__('Lastname')" />
                <x-text-input wire:model="lastname" id="lastname" class="block mt-1 w-full" type="text" required autocomplete="lastname" />
                <x-input-error :messages="$errors->get('lastname')" class="mt-2" />
            </div>

            <!-- Middlename -->
            <div>
                <x-input-label for="middlename" :value="__('Middlename')" />
                <x-text-input wire:model="middlename" id="middlename" class="block mt-1 w-full" type="text" required autocomplete="middlename" />
                <x-input-error :messages="$errors->get('middlename')" class="mt-2" />
            </div>

            <!-- Phone Number -->
            <div>
                <x-input-label for="phone_number" :value="__('Phone Number')" />
                <x-text-input wire:model="phone_number" id="phone_number" class="block mt-1 w-full" type="text" required autocomplete="phone_number" />
                <x-input-error :messages="$errors->get('phone_number')" class="mt-2" />
            </div>

            <!-- Barangay -->
            <div>
                <x-input-label for="barangay" :value="__('Barangay')" />
                <x-text-input wire:model="barangay" id="barangay" class="block mt-1 w-full" type="text" required autocomplete="barangay" />
                <x-input-error :messages="$errors->get('barangay')" class="mt-2" />
            </div>

            <!-- Sition -->
            <div>
                <x-input-label for="sition" :value="__('Sition (Optional)')" />
                <x-text-input wire:model="sition" id="sition" class="block mt-1 w-full" type="text" autocomplete="sition" />
                <x-input-error :messages="$errors->get('sition')" class="mt-2" />
            </div>

            <!-- Language -->
            <div>
                <x-input-label for="language" :value="__('Language')" />
                <select wire:model="language" id="language" class="block mt-1 w-full" required>
                    <option value="">-- Select Language --</option>
                    <option value="English">English</option>
                    <option value="Tagalog">Tagalog</option>
                    <option value="Bisaya">Bisaya</option>
                </select>
                <x-input-error :messages="$errors->get('language')" class="mt-2" />
            </div>

            <!-- Email -->
            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input wire:model="email" id="email" class="block mt-1 w-full" type="email" required autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

       <!-- Password -->
<div x-data="{ show: false }">
    <x-input-label for="password" :value="__('Password')" />
    <div class="relative">
        <input :type="show ? 'text' : 'password'"
               wire:model="password"
               id="password"
               class="block mt-1 w-full pr-10 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
               required autocomplete="new-password" />

        <!-- Eye Icon -->
        <button type="button"
                @click="show = !show"
                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500">
            <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            </svg>
            <svg x-show="show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.956 9.956 0 012.112-3.592M6.343 6.343A9.956 9.956 0 0112 5c4.477 0 8.268 2.943 9.542 7a9.956 9.956 0 01-4.132 5.132M15 12a3 3 0 01-3 3m0-6a3 3 0 013 3m0 0L6.343 6.343" />
            </svg>
        </button>
    </div>
    <x-input-error :messages="$errors->get('password')" class="mt-2" />
    <p class="text-xs text-gray-500 mt-1">
        Must be at least 8 characters and include letters, numbers, and symbols.
    </p>
</div>

<!-- Confirm Password -->
<div x-data="{ show: false }">
    <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
    <div class="relative">
        <input :type="show ? 'text' : 'password'"
               wire:model="password_confirmation"
               id="password_confirmation"
               class="block mt-1 w-full pr-10 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
               required autocomplete="new-password" />

        <!-- Eye Icon -->
        <button type="button"
                @click="show = !show"
                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500">
            <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            </svg>
            <svg x-show="show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.956 9.956 0 012.112-3.592M6.343 6.343A9.956 9.956 0 0112 5c4.477 0 8.268 2.943 9.542 7a9.956 9.956 0 01-4.132 5.132M15 12a3 3 0 01-3 3m0-6a3 3 0 013 3m0 0L6.343 6.343" />
            </svg>
        </button>
    </div>
    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
</div>

        </div>

        <!-- Terms and Agreement -->
        <div class="mt-6 bg-gray-50 p-4 rounded-md border text-sm text-gray-700">
            <p>
                By registering, you agree to provide accurate information, including your full name, phone number, email address, and home address.
                You consent to the system using this information to manage appointments and communicate updates.
                Misuse or false information may result in account suspension.
                Your data will be handled according to our privacy policy.
            </p>
        </div>

        <!-- Submit -->
        <div class="flex items-center justify-end mt-6">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}" wire:navigate>
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</div>

