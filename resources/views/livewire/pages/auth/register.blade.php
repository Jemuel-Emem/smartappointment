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
    public string $name = '';
    public string $email = '';
    public string $password = '';
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
            'name' => ['required', 'string', 'max:255'],
             'barangay' => ['required', 'string', 'max:255'],
              'language' => ['required', 'string', 'max:255'],
                'phone_number' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered($user = User::create($validated)));

        Auth::login($user);

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    <form wire:submit="register">
        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input wire:model="name" id="name" class="block mt-1 w-full" type="text" name="name" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

            <div>
            <x-input-label for="phone_number" :value="__('phone_number')" />
            <x-text-input wire:model="phone_number" id="phone_number" class="block mt-1 w-full" type="text" phone_number="phone_number" required autofocus autocomplete="phone_number" />
            <x-input-error :messages="$errors->get('phone_number')" class="mt-2" />
        </div>

       <div>
    <x-input-label for="barangay" :value="__('Barangay')" />
    <select wire:model="barangay" id="barangay" name="barangay"
        class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
        required>
        <option value="">-- Select Barangay --</option>
        <option value="Asinan">Asinan</option>
        <option value="Baguio">Baguio</option>
        <option value="Bilabao">Bilabao</option>
        <option value="Bonotbonot">Bonotbonot</option>
        <option value="Bugaong">Bugaong</option>
        <option value="Buenavista (Poblacion)">Buenavista (Poblacion)</option>
        <option value="Buenos Aires">Buenos Aires</option>
        <option value="Candagas">Candagas</option>
        <option value="Candaling">Candaling</option>
        <option value="Cangawa">Cangawa</option>
        <option value="Cangmangao">Cangmangao</option>
        <option value="Cantagay">Cantagay</option>
        <option value="Cantamuac">Cantamuac</option>
        <option value="Cantores">Cantores</option>
        <option value="Cantuba">Cantuba</option>
        <option value="Catigbian">Catigbian</option>
        <option value="Cawag">Cawag</option>
        <option value="Cruz">Cruz</option>
        <option value="Dait">Dait</option>
        <option value="Eastern Cabul-an">Eastern Cabul-an</option>
        <option value="Hunan">Hunan</option>
        <option value="Lapacan">Lapacan</option>
        <option value="Lubang">Lubang</option>
        <option value="Lusong">Lusong</option>
        <option value="Magkaya">Magkaya</option>
        <option value="Merryland">Merryland</option>
        <option value="Nueva Esperanza">Nueva Esperanza</option>
        <option value="Nueva Montana">Nueva Montana</option>
        <option value="Overland">Overland</option>
        <option value="Panghagban">Panghagban</option>
        <option value="Puting Bato">Puting Bato</option>
        <option value="Riverside">Riverside</option>
        <option value="Tanghaligue">Tanghaligue</option>
        <option value="Taslan">Taslan</option>
        <option value="Tuboran">Tuboran</option>
        <option value="Western Cabul-an">Western Cabul-an</option>
    </select>

    <x-input-error :messages="$errors->get('barangay')" class="mt-2" />
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
</div>
