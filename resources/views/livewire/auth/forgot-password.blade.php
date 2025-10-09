<?php

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    public string $email = '';

    /**
     * Send a password reset link to the provided email address.
     */
    public function sendPasswordResetLink(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        Password::sendResetLink($this->only('email'));

        session()->flash('status', __('A reset link will be sent if the account exists.'));
    }
}; ?>

<div class="d-flex flex-column">
  <!-- Header -->
  <div class="text-center mb-4">
      <h4 class="mb-1">{{ __('Forgot password') }}</h4>
      <p class="text-muted mb-0">{{ __('Enter your email to receive a password reset link') }}</p>
  </div>

  <!-- Session Status -->
  @if (session('status'))
      <div class="alert alert-success text-center" role="alert">
          {{ session('status') }}
      </div>
  @endif

  <form method="POST" wire:submit="sendPasswordResetLink">
      @csrf

      <!-- Email Address -->
      <div class="form-group">
          <label for="email">{{ __('Email Address') }}</label>
          <input wire:model="email"
                 type="email"
                 id="email"
                 name="email"
                 class="form-control @error('email') is-invalid @enderror"
                 placeholder="email@example.com"
                 required
                 autofocus>
          @error('email')
              <div class="invalid-feedback">{{ $message }}</div>
          @enderror
      </div>

      <button type="submit"
              class="btn btn-primary btn-block"
              data-test="email-password-reset-link-button">
          {{ __('Email password reset link') }}
      </button>
  </form>

  <div class="text-center mt-3 small text-muted">
      <span>{{ __('Or, return to') }}</span>
      <a href="{{ route('login') }}" wire:navigate>{{ __('log in') }}</a>
  </div>
</div>


{{-- <div class="flex flex-col gap-6">
    <x-auth-header :title="__('Forgot password')" :description="__('Enter your email to receive a password reset link')" />

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <form method="POST" wire:submit="sendPasswordResetLink" class="flex flex-col gap-6">
        <!-- Email Address -->
        <flux:input
            wire:model="email"
            :label="__('Email Address')"
            type="email"
            required
            autofocus
            placeholder="email@example.com"
        />

        <flux:button variant="primary" type="submit" class="w-full" data-test="email-password-reset-link-button">
            {{ __('Email password reset link') }}
        </flux:button>
    </form>

    <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-400">
        <span>{{ __('Or, return to') }}</span>
        <flux:link :href="route('login')" wire:navigate>{{ __('log in') }}</flux:link>
    </div>
</div> --}}
