<?php

use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Features;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    #[Validate('required|string|email')]
    public string $email = '';

    #[Validate('required|string')]
    public string $password = '';

    public bool $remember = false;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->ensureIsNotRateLimited();

        $user = $this->validateCredentials();

        if (Features::canManageTwoFactorAuthentication() && $user->hasEnabledTwoFactorAuthentication()) {
            Session::put([
                'login.id' => $user->getKey(),
                'login.remember' => $this->remember,
            ]);

            $this->redirect(route('two-factor.login'), navigate: true);

            return;
        }

        Auth::login($user, $this->remember);

        RateLimiter::clear($this->throttleKey());
        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }

    /**
     * Validate the user's credentials.
     */
    protected function validateCredentials(): User
    {
        $user = Auth::getProvider()->retrieveByCredentials(['email' => $this->email, 'password' => $this->password]);

        if (! $user || ! Auth::getProvider()->validateCredentials($user, ['password' => $this->password])) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        return $user;
    }

    /**
     * Ensure the authentication request is not rate limited.
     */
    protected function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => __('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the authentication rate limiting throttle key.
     */
    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->email).'|'.request()->ip());
    }
}; ?>

<div class="d-flex flex-column">
  <!-- Header -->
  <div class="text-center mb-4">
    <h4 class="mb-1">{{ __('Log in to your account') }}</h4>
    <p class="text-muted mb-0">{{ __('Enter your email and password below to log in') }}</p>
  </div>

  <!-- Session Status -->
  @if (session('status'))
      <div class="alert alert-success text-center" role="alert">
          {{ session('status') }}
      </div>
  @endif

  <form method="POST" wire:submit="login">
      @csrf

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
                 autofocus
                 autocomplete="email">
          @error('email')
              <div class="invalid-feedback">{{ $message }}</div>
          @enderror
      </div>

      <!-- Password -->
      <div class="form-group position-relative">
          <label for="password">{{ __('Password') }}</label>
          <input wire:model="password"
                 type="password"
                 id="password"
                 name="password"
                 class="form-control @error('password') is-invalid @enderror"
                 placeholder="{{ __('Password') }}"
                 required
                 autocomplete="current-password">
          @error('password')
              <div class="invalid-feedback">{{ $message }}</div>
          @enderror

          @if (Route::has('password.request'))
              <a href="{{ route('password.request') }}"
                 wire:navigate
                 class="small position-absolute"
                 style="top: 0; right: 0;">
                 {{ __('Forgot your password?') }}
              </a>
          @endif
      </div>

      <!-- Remember Me -->
      <div class="form-group form-check">
          <input wire:model="remember" type="checkbox" class="form-check-input" id="remember">
          <label class="form-check-label" for="remember">{{ __('Remember me') }}</label>
      </div>

      <div class="text-right">
          <button type="submit" class="btn btn-primary btn-block" data-test="login-button">
              {{ __('Log in') }}
          </button>
      </div>
  </form>

  @if (Route::has('register'))
      <div class="text-center mt-3">
          <span class="text-muted small">{{ __('Don\'t have an account?') }}</span>
          <a href="{{ route('register') }}" wire:navigate>{{ __('Sign up') }}</a>
      </div>
  @endif
</div>

{{-- <div class="flex flex-col gap-6">
    <x-auth-header :title="__('Log in to your account')" :description="__('Enter your email and password below to log in')" />

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <form method="POST" wire:submit="login" class="flex flex-col gap-6">
        <!-- Email Address -->
        <flux:input
            wire:model="email"
            :label="__('Email address')"
            type="email"
            required
            autofocus
            autocomplete="email"
            placeholder="email@example.com"
        />

        <!-- Password -->
        <div class="relative">
            <flux:input
                wire:model="password"
                :label="__('Password')"
                type="password"
                required
                autocomplete="current-password"
                :placeholder="__('Password')"
                viewable
            />

            @if (Route::has('password.request'))
                <flux:link class="absolute top-0 text-sm end-0" :href="route('password.request')" wire:navigate>
                    {{ __('Forgot your password?') }}
                </flux:link>
            @endif
        </div>

        <!-- Remember Me -->
        <flux:checkbox wire:model="remember" :label="__('Remember me')" />

        <div class="flex items-center justify-end">
            <flux:button variant="primary" type="submit" class="w-full" data-test="login-button">
                {{ __('Log in') }}
            </flux:button>
        </div>
    </form>

    @if (Route::has('register'))
        <div class="space-x-1 text-sm text-center rtl:space-x-reverse text-zinc-600 dark:text-zinc-400">
            <span>{{ __('Don\'t have an account?') }}</span>
            <flux:link :href="route('register')" wire:navigate>{{ __('Sign up') }}</flux:link>
        </div>
    @endif
</div> --}}
