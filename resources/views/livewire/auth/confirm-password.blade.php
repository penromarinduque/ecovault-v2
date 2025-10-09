<x-layouts.auth>
    <div class="flex flex-col gap-6">
        <x-auth-header
            :title="__('Confirm password')"
            :description="__('This is a secure area of the application. Please confirm your password before continuing.')"
        />

        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('password.confirm.store') }}">
            @csrf

            <div class="form-group">
                <label for="password">{{ __('Password') }}</label>
                <div class="input-group">
                    <input
                        id="password"
                        type="password"
                        name="password"
                        class="form-control"
                        placeholder="{{ __('Password') }}"
                        required
                        autocomplete="current-password"
                    >
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="button" id="togglePassword" onclick="togglePasswordVisibility('password', event)">
                            <i class="fa fa-eye"></i>
                        </button>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-block" data-test="confirm-password-button">
                {{ __('Confirm') }}
            </button>
        </form>
    </div>
</x-layouts.auth>
