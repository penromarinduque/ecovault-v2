<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered(($user = User::create($validated))));

        Auth::login($user);

        Session::regenerate();

        $this->redirectIntended(route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="d-flex flex-column">
  <!-- Header -->
  <div class="text-center mb-4">
      <h4 class="mb-1">{{ __('Create an account') }}</h4>
      <p class="text-muted mb-0">{{ __('Enter your details below to create your account') }}</p>
  </div>

  <!-- Session Status -->
  @if (session('status'))
      <div class="alert alert-success text-center" role="alert">
          {{ session('status') }}
      </div>
  @endif

  <form method="POST" wire:submit="register">
      @csrf

      <!-- Name -->
      <div class="form-group">
          <label for="name">{{ __('Name') }}</label>
          <input wire:model="name"
                 type="text"
                 id="name"
                 name="name"
                 class="form-control @error('name') is-invalid @enderror"
                 placeholder="{{ __('Full name') }}"
                 required
                 autofocus
                 autocomplete="name">
          @error('name')
              <div class="invalid-feedback">{{ $message }}</div>
          @enderror
      </div>

      <!-- Email Address -->
      <div class="form-group">
          <label for="email">{{ __('Email address') }}</label>
          <input wire:model="email"
                 type="email"
                 id="email"
                 name="email"
                 class="form-control @error('email') is-invalid @enderror"
                 placeholder="email@example.com"
                 required
                 autocomplete="email">
          @error('email')
              <div class="invalid-feedback">{{ $message }}</div>
          @enderror
      </div>

      <!-- Password -->
      <div class="form-group">
          <label for="password">{{ __('Password') }}</label>
          <input wire:model="password"
                 type="password"
                 id="password"
                 name="password"
                 class="form-control @error('password') is-invalid @enderror"
                 placeholder="{{ __('Password') }}"
                 required
                 autocomplete="new-password">
          @error('password')
              <div class="invalid-feedback">{{ $message }}</div>
          @enderror
      </div>

      <!-- Confirm Password -->
      <div class="form-group">
          <label for="password_confirmation">{{ __('Confirm password') }}</label>
          <input wire:model="password_confirmation"
                 type="password"
                 id="password_confirmation"
                 name="password_confirmation"
                 class="form-control"
                 placeholder="{{ __('Confirm password') }}"
                 required
                 autocomplete="new-password">
      </div>

      <button type="submit"
              class="btn btn-primary btn-block"
              data-test="register-user-button">
          {{ __('Create account') }}
      </button>
  </form>

  <div class="text-center mt-3 small text-muted">
      <span>{{ __('Already have an account?') }}</span>
      <a href="{{ route('login') }}" wire:navigate>{{ __('Log in') }}</a>
  </div>
</div>


{{-- <div class="flex flex-col gap-6">
    <x-auth-header :title="__('Create an account')" :description="__('Enter your details below to create your account')" />

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <form method="POST" wire:submit="register" class="flex flex-col gap-6">
        <!-- Name -->
        <flux:input
            wire:model="name"
            :label="__('Name')"
            type="text"
            required
            autofocus
            autocomplete="name"
            :placeholder="__('Full name')"
        />

        <!-- Email Address -->
        <flux:input
            wire:model="email"
            :label="__('Email address')"
            type="email"
            required
            autocomplete="email"
            placeholder="email@example.com"
        />

        <!-- Password -->
        <flux:input
            wire:model="password"
            :label="__('Password')"
            type="password"
            required
            autocomplete="new-password"
            :placeholder="__('Password')"
            viewable
        />

        <!-- Confirm Password -->
        <flux:input
            wire:model="password_confirmation"
            :label="__('Confirm password')"
            type="password"
            required
            autocomplete="new-password"
            :placeholder="__('Confirm password')"
            viewable
        />

        <div class="flex items-center justify-end">
            <flux:button type="submit" variant="primary" class="w-full" data-test="register-user-button">
                {{ __('Create account') }}
            </flux:button>
        </div>
    </form>

    <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-600 dark:text-zinc-400">
        <span>{{ __('Already have an account?') }}</span>
        <flux:link :href="route('login')" wire:navigate>{{ __('Log in') }}</flux:link>
    </div>
</div> --}}
