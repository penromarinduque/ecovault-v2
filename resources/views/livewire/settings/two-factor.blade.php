<?php

use Laravel\Fortify\Actions\ConfirmTwoFactorAuthentication;
use Laravel\Fortify\Actions\DisableTwoFactorAuthentication;
use Laravel\Fortify\Actions\EnableTwoFactorAuthentication;
use Laravel\Fortify\Features;
use Laravel\Fortify\Fortify;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;
use Symfony\Component\HttpFoundation\Response;

new class extends Component {
    #[Locked]
    public bool $twoFactorEnabled;

    #[Locked]
    public bool $requiresConfirmation;

    #[Locked]
    public string $qrCodeSvg = '';

    #[Locked]
    public string $manualSetupKey = '';

    public bool $showModal = false;

    public bool $showVerificationStep = false;

    #[Validate('required|string|size:6', onUpdate: false)]
    public string $code = '';

    /**
     * Mount the component.
     */
    public function mount(DisableTwoFactorAuthentication $disableTwoFactorAuthentication): void
    {
        abort_unless(Features::enabled(Features::twoFactorAuthentication()), Response::HTTP_FORBIDDEN);

        if (Fortify::confirmsTwoFactorAuthentication() && is_null(auth()->user()->two_factor_confirmed_at)) {
            $disableTwoFactorAuthentication(auth()->user());
        }

        $this->twoFactorEnabled = auth()->user()->hasEnabledTwoFactorAuthentication();
        $this->requiresConfirmation = Features::optionEnabled(Features::twoFactorAuthentication(), 'confirm');
    }

    /**
     * Enable two-factor authentication for the user.
     */
    public function enable(EnableTwoFactorAuthentication $enableTwoFactorAuthentication): void
    {
        $enableTwoFactorAuthentication(auth()->user());

        if (! $this->requiresConfirmation) {
            $this->twoFactorEnabled = auth()->user()->hasEnabledTwoFactorAuthentication();
        }

        $this->loadSetupData();

        $this->showModal = true;
    }

    /**
     * Load the two-factor authentication setup data for the user.
     */
    private function loadSetupData(): void
    {
        $user = auth()->user();

        try {
            $this->qrCodeSvg = $user?->twoFactorQrCodeSvg();
            $this->manualSetupKey = decrypt($user->two_factor_secret);
        } catch (Exception) {
            $this->addError('setupData', 'Failed to fetch setup data.');

            $this->reset('qrCodeSvg', 'manualSetupKey');
        }
    }

    /**
     * Show the two-factor verification step if necessary.
     */
    public function showVerificationIfNecessary(): void
    {
        if ($this->requiresConfirmation) {
            $this->showVerificationStep = true;

            $this->resetErrorBag();

            return;
        }

        $this->closeModal();
    }

    /**
     * Confirm two-factor authentication for the user.
     */
    public function confirmTwoFactor(ConfirmTwoFactorAuthentication $confirmTwoFactorAuthentication): void
    {
        $this->validate();

        $confirmTwoFactorAuthentication(auth()->user(), $this->code);

        $this->closeModal();

        $this->twoFactorEnabled = true;
    }

    /**
     * Reset two-factor verification state.
     */
    public function resetVerification(): void
    {
        $this->reset('code', 'showVerificationStep');

        $this->resetErrorBag();
    }

    /**
     * Disable two-factor authentication for the user.
     */
    public function disable(DisableTwoFactorAuthentication $disableTwoFactorAuthentication): void
    {
        $disableTwoFactorAuthentication(auth()->user());

        $this->twoFactorEnabled = false;
    }

    /**
     * Close the two-factor authentication modal.
     */
    public function closeModal(): void
    {
        $this->reset(
            'code',
            'manualSetupKey',
            'qrCodeSvg',
            'showModal',
            'showVerificationStep',
        );

        $this->resetErrorBag();

        if (! $this->requiresConfirmation) {
            $this->twoFactorEnabled = auth()->user()->hasEnabledTwoFactorAuthentication();
        }
    }

    /**
     * Get the current modal configuration state.
     */
    public function getModalConfigProperty(): array
    {
        if ($this->twoFactorEnabled) {
            return [
                'title' => __('Two-Factor Authentication Enabled'),
                'description' => __('Two-factor authentication is now enabled. Scan the QR code or enter the setup key in your authenticator app.'),
                'buttonText' => __('Close'),
            ];
        }

        if ($this->showVerificationStep) {
            return [
                'title' => __('Verify Authentication Code'),
                'description' => __('Enter the 6-digit code from your authenticator app.'),
                'buttonText' => __('Continue'),
            ];
        }

        return [
            'title' => __('Enable Two-Factor Authentication'),
            'description' => __('To finish enabling two-factor authentication, scan the QR code or enter the setup key in your authenticator app.'),
            'buttonText' => __('Continue'),
        ];
    }
} ?>

<div class="container-fluid">
    <section class="container mt-4">
        @include('partials.settings-heading')

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">{{ __('Two Factor Authentication') }}</h5>
                <small class="text-muted">{{ __('Manage your two-factor authentication settings') }}</small>
            </div>

            <div class="card-body" wire:cloak>
                @if ($twoFactorEnabled)
                    <div>
                        <span class="badge badge-success mb-3">{{ __('Enabled') }}</span>

                        <p class="text-muted">
                            {{ __('With two-factor authentication enabled, you will be prompted for a secure, random pin during login, which you can retrieve from the TOTP-supported application on your phone.') }}
                        </p>

                        <livewire:settings.two-factor.recovery-codes :$requiresConfirmation />

                        <button type="button" class="btn btn-danger mt-3" wire:click="disable">
                            <i class="fa fa-shield"></i> {{ __('Disable 2FA') }}
                        </button>
                    </div>
                @else
                    <div>
                        <span class="badge badge-danger mb-3">{{ __('Disabled') }}</span>

                        <p class="text-muted">
                            {{ __('When you enable two-factor authentication, you will be prompted for a secure pin during login. This pin can be retrieved from a TOTP-supported application on your phone.') }}
                        </p>

                        <button type="button" class="btn btn-primary mt-3" wire:click="enable">
                            <i class="fa fa-shield"></i> {{ __('Enable 2FA') }}
                        </button>
                    </div>
                @endif
            </div>
        </div>

        <!-- 2FA Setup Modal -->
        <div wire:ignore.self class="modal fade open" id="twoFactorSetupModal" tabindex="-1" role="dialog" aria-labelledby="twoFactorModalLabel" aria-hidden="true" wire:model="showModal">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content border-0">
                    <div class="modal-header">
                        <h5 class="modal-title" id="twoFactorModalLabel">{{ $this->modalConfig['title'] ?? __('Setup 2FA') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <p class="text-muted text-center mb-4">
                            {{ $this->modalConfig['description'] ?? __('Follow the instructions to set up Two-Factor Authentication.') }}
                        </p>

                        @if ($showVerificationStep)
                            <div class="text-center mb-4">
                                <x-input-otp :digits="6" name="code" wire:model="code" autocomplete="one-time-code" />
                                @error('code')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-between">
                                <button type="button" class="btn btn-outline-secondary" wire:click="resetVerification">
                                    {{ __('Back') }}
                                </button>

                                <button type="button" class="btn btn-primary" wire:click="confirmTwoFactor" :disabled="$wire.code.length < 6">
                                    {{ __('Confirm') }}
                                </button>
                            </div>
                        @else
                            @error('setupData')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror

                            <div class="d-flex justify-content-center mb-4">
                                <div class="border rounded p-3 bg-light" style="width: 250px; height: 250px;">
                                    @empty($qrCodeSvg)
                                        <div class="d-flex justify-content-center align-items-center h-100 text-muted">
                                            <div class="spinner-border text-secondary" role="status"></div>
                                        </div>
                                    @else
                                        <div class="d-flex justify-content-center align-items-center h-100">
                                            {!! $qrCodeSvg !!}
                                        </div>
                                    @endempty
                                </div>
                            </div>

                            <button type="button" class="btn btn-primary btn-block mb-3" wire:click="showVerificationIfNecessary" :disabled="$errors->has('setupData')">
                                {{ $this->modalConfig['buttonText'] ?? __('Continue') }}
                            </button>

                            <div class="text-center mb-2">
                                <small class="text-muted">{{ __('or, enter the code manually') }}</small>
                            </div>

                            <div class="input-group">
                                <input type="text" readonly value="{{ $manualSetupKey }}" class="form-control">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" x-data="{ copied: false }" @click="navigator.clipboard.writeText('{{ $manualSetupKey }}').then(() => { copied = true; setTimeout(() => copied = false, 1500); })">
                                        <i class="fa fa-copy"></i>
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- <section class="w-full">
        @include('partials.settings-heading')

        <x-settings.layout
            :heading="__('Two Factor Authentication')"
            :subheading="__('Manage your two-factor authentication settings')"
        >
            <div class="flex flex-col w-full mx-auto space-y-6 text-sm" wire:cloak>
                @if ($twoFactorEnabled)
                    <div class="mb-4">
                        <div class="d-flex align-items-center mb-3">
                            <span class="badge badge-success">{{ __('Enabled') }}</span>
                        </div>

                        <p class="text-muted">
                            {{ __('With two-factor authentication enabled, you will be prompted for a secure, random pin during login, which you can retrieve from the TOTP-supported application on your phone.') }}
                        </p>

                        <livewire:settings.two-factor.recovery-codes :$requiresConfirmation />

                        <div class="mt-3">
                            <button type="button" class="btn btn-danger" wire:click="disable">
                                <i class="fa fa-shield"></i> {{ __('Disable 2FA') }}
                            </button>
                        </div>
                    </div>
                @else
                    <div class="mb-4">
                        <div class="d-flex align-items-center mb-3">
                            <span class="badge badge-danger">{{ __('Disabled') }}</span>
                        </div>

                        <p class="text-muted">
                            {{ __('When you enable two-factor authentication, you will be prompted for a secure pin during login. This pin can be retrieved from a TOTP-supported application on your phone.') }}
                        </p>

                        <button type="button" class="btn btn-primary" wire:click="enable">
                            <i class="fa fa-shield-check"></i> {{ __('Enable 2FA') }}
                        </button>
                    </div>
                @endif
            </div>
        </x-settings.layout>

        <!-- Two-Factor Setup Modal -->
        <div
            wire:model="showModal"
            class="modal fade"
            id="twoFactorSetupModal"
            tabindex="-1"
            role="dialog"
            aria-labelledby="twoFactorSetupLabel"
            aria-hidden="true"
        >
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content border-0 shadow-lg">
                    <div class="modal-header">
                        <h5 class="modal-title" id="twoFactorSetupLabel">
                            {{ $this->modalConfig['title'] }}
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" wire:click="$set('showModal', false)">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        @if ($showVerificationStep)
                            <!-- Verification Step -->
                            <div class="text-center mb-4">
                                <p>{{ $this->modalConfig['description'] }}</p>
                                <x-input-otp :digits="6" name="code" wire:model="code" autocomplete="one-time-code" />
                                @error('code')
                                    <div class="text-danger small mt-2">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-between">
                                <button type="button" class="btn btn-outline-secondary btn-block mr-2" wire:click="resetVerification">
                                    Back
                                </button>
                                <button type="button" class="btn btn-primary btn-block ml-2" wire:click="confirmTwoFactor" @if(strlen($code) < 6) disabled @endif>
                                    Confirm
                                </button>
                            </div>
                        @else
                            <!-- Setup Step -->
                            @error('setupData')
                                <div class="alert alert-danger mb-3">
                                    {{ $message }}
                                </div>
                            @enderror

                            <div class="text-center mb-4">
                                @if (empty($qrCodeSvg))
                                    <div class="p-5 bg-light d-flex align-items-center justify-content-center">
                                        <div class="spinner-border text-secondary" role="status"></div>
                                    </div>
                                @else
                                    <div class="p-3 border rounded d-inline-block bg-white">
                                        {!! $qrCodeSvg !!}
                                    </div>
                                @endif
                            </div>

                            <button
                                type="button"
                                class="btn btn-primary btn-block mb-4"
                                wire:click="showVerificationIfNecessary"
                                @if($errors->has('setupData')) disabled @endif
                            >
                                {{ $this->modalConfig['buttonText'] }}
                            </button>

                            <div class="text-center position-relative my-3">
                                <hr>
                                <span class="bg-white px-2 position-absolute" style="top:-12px; left:50%; transform:translateX(-50%)">
                                    or enter the code manually
                                </span>
                            </div>

                            <div class="input-group">
                                @if (empty($manualSetupKey))
                                    <div class="form-control text-center bg-light">
                                        <div class="spinner-border spinner-border-sm text-secondary" role="status"></div>
                                    </div>
                                @else
                                    <input type="text" readonly class="form-control" value="{{ $manualSetupKey }}">
                                    <div class="input-group-append">
                                        <button
                                            class="btn btn-outline-secondary"
                                            type="button"
                                            x-data="{ copied: false }"
                                            x-on:click="
                                                navigator.clipboard.writeText('{{ $manualSetupKey }}');
                                                copied = true;
                                                setTimeout(() => copied = false, 1500);
                                            "
                                        >
                                            <span x-show="!copied">Copy</span>
                                            <span x-show="copied" class="text-success">Copied!</span>
                                        </button>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section> --}}
</div>
{{-- <section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout
        :heading="__('Two Factor Authentication')"
        :subheading="__('Manage your two-factor authentication settings')"
    >
        <div class="flex flex-col w-full mx-auto space-y-6 text-sm" wire:cloak>
            @if ($twoFactorEnabled)
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <flux:badge color="green">{{ __('Enabled') }}</flux:badge>
                    </div>

                    <flux:text>
                        {{ __('With two-factor authentication enabled, you will be prompted for a secure, random pin during login, which you can retrieve from the TOTP-supported application on your phone.') }}
                    </flux:text>

                    <livewire:settings.two-factor.recovery-codes :$requiresConfirmation/>

                    <div class="flex justify-start">
                        <flux:button
                            variant="danger"
                            icon="shield-exclamation"
                            icon:variant="outline"
                            wire:click="disable"
                        >
                            {{ __('Disable 2FA') }}
                        </flux:button>
                    </div>
                </div>
            @else
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <flux:badge color="red">{{ __('Disabled') }}</flux:badge>
                    </div>

                    <flux:text variant="subtle">
                        {{ __('When you enable two-factor authentication, you will be prompted for a secure pin during login. This pin can be retrieved from a TOTP-supported application on your phone.') }}
                    </flux:text>

                    <flux:button
                        variant="primary"
                        icon="shield-check"
                        icon:variant="outline"
                        wire:click="enable"
                    >
                        {{ __('Enable 2FA') }}
                    </flux:button>
                </div>
            @endif
        </div>
    </x-settings.layout>

    <flux:modal
        name="two-factor-setup-modal"
        class="max-w-md md:min-w-md"
        @close="closeModal"
        wire:model="showModal"
    >
        <div class="space-y-6">
            <div class="flex flex-col items-center space-y-4">
                <div class="p-0.5 w-auto rounded-full border border-stone-100 dark:border-stone-600 bg-white dark:bg-stone-800 shadow-sm">
                    <div class="p-2.5 rounded-full border border-stone-200 dark:border-stone-600 overflow-hidden bg-stone-100 dark:bg-stone-200 relative">
                        <div class="flex items-stretch absolute inset-0 w-full h-full divide-x [&>div]:flex-1 divide-stone-200 dark:divide-stone-300 justify-around opacity-50">
                            @for ($i = 1; $i <= 5; $i++)
                                <div></div>
                            @endfor
                        </div>

                        <div class="flex flex-col items-stretch absolute w-full h-full divide-y [&>div]:flex-1 inset-0 divide-stone-200 dark:divide-stone-300 justify-around opacity-50">
                            @for ($i = 1; $i <= 5; $i++)
                                <div></div>
                            @endfor
                        </div>

                        <flux:icon.qr-code class="relative z-20 dark:text-accent-foreground"/>
                    </div>
                </div>

                <div class="space-y-2 text-center">
                    <flux:heading size="lg">{{ $this->modalConfig['title'] }}</flux:heading>
                    <flux:text>{{ $this->modalConfig['description'] }}</flux:text>
                </div>
            </div>

            @if ($showVerificationStep)
                <div class="space-y-6">
                    <div class="flex flex-col items-center space-y-3">
                        <x-input-otp
                            :digits="6"
                            name="code"
                            wire:model="code"
                            autocomplete="one-time-code"
                        />
                        @error('code')
                            <flux:text color="red">
                                {{ $message }}
                            </flux:text>
                        @enderror
                    </div>

                    <div class="flex items-center space-x-3">
                        <flux:button
                            variant="outline"
                            class="flex-1"
                            wire:click="resetVerification"
                        >
                            {{ __('Back') }}
                        </flux:button>

                        <flux:button
                            variant="primary"
                            class="flex-1"
                            wire:click="confirmTwoFactor"
                            x-bind:disabled="$wire.code.length < 6"
                        >
                            {{ __('Confirm') }}
                        </flux:button>
                    </div>
                </div>
            @else
                @error('setupData')
                    <flux:callout variant="danger" icon="x-circle" heading="{{ $message }}"/>
                @enderror

                <div class="flex justify-center">
                    <div class="relative w-64 overflow-hidden border rounded-lg border-stone-200 dark:border-stone-700 aspect-square">
                        @empty($qrCodeSvg)
                            <div class="absolute inset-0 flex items-center justify-center bg-white dark:bg-stone-700 animate-pulse">
                                <flux:icon.loading/>
                            </div>
                        @else
                            <div class="flex items-center justify-center h-full p-4">
                                {!! $qrCodeSvg !!}
                            </div>
                        @endempty
                    </div>
                </div>

                <div>
                    <flux:button
                        :disabled="$errors->has('setupData')"
                        variant="primary"
                        class="w-full"
                        wire:click="showVerificationIfNecessary"
                    >
                        {{ $this->modalConfig['buttonText'] }}
                    </flux:button>
                </div>

                <div class="space-y-4">
                    <div class="relative flex items-center justify-center w-full">
                        <div class="absolute inset-0 w-full h-px top-1/2 bg-stone-200 dark:bg-stone-600"></div>
                        <span class="relative px-2 text-sm bg-white dark:bg-stone-800 text-stone-600 dark:text-stone-400">
                            {{ __('or, enter the code manually') }}
                        </span>
                    </div>

                    <div
                        class="flex items-center space-x-2"
                        x-data="{
                            copied: false,
                            async copy() {
                                try {
                                    await navigator.clipboard.writeText('{{ $manualSetupKey }}');
                                    this.copied = true;
                                    setTimeout(() => this.copied = false, 1500);
                                } catch (e) {
                                    console.warn('Could not copy to clipboard');
                                }
                            }
                        }"
                    >
                        <div class="flex items-stretch w-full border rounded-xl dark:border-stone-700">
                            @empty($manualSetupKey)
                                <div class="flex items-center justify-center w-full p-3 bg-stone-100 dark:bg-stone-700">
                                    <flux:icon.loading variant="mini"/>
                                </div>
                            @else
                                <input
                                    type="text"
                                    readonly
                                    value="{{ $manualSetupKey }}"
                                    class="w-full p-3 bg-transparent outline-none text-stone-900 dark:text-stone-100"
                                />

                                <button
                                    @click="copy()"
                                    class="px-3 transition-colors border-l cursor-pointer border-stone-200 dark:border-stone-600"
                                >
                                    <flux:icon.document-duplicate x-show="!copied" variant="outline"></flux:icon>
                                    <flux:icon.check
                                        x-show="copied"
                                        variant="solid"
                                        class="text-green-500"
                                    ></flux:icon>
                                </button>
                            @endempty
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </flux:modal>
</section> --}}
