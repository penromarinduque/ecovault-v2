<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

new class extends Component {
    public string $password = '';

    /**
     * Delete the currently authenticated user.
     */
    public function deleteUser(Logout $logout): void
    {
        $this->validate([
            'password' => ['required', 'string', 'current_password'],
        ]);

        tap(Auth::user(), $logout(...))->delete();

        $this->redirect('/', navigate: true);
    }
}; ?>

<section class="mt-5">
    <div class="mb-4">
        <h4 class="mb-1">{{ __('Delete Account') }}</h4>
        <p class="text-muted">{{ __('Delete your account and all of its resources.') }}</p>
    </div>

    <!-- Delete Account Button -->
    <button
        type="button"
        class="btn btn-danger"
        data-toggle="modal"
        data-target="#confirmUserDeletionModal"
        data-test="delete-user-button"
    >
        {{ __('Delete Account') }}
    </button>

    <!-- Confirm Delete Modal -->
    <div
        wire:ignore.self
        class="modal fade"
        id="confirmUserDeletionModal"
        tabindex="-1"
        role="dialog"
        aria-labelledby="confirmUserDeletionLabel"
        aria-hidden="true"
    >
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmUserDeletionLabel">
                        {{ __('Confirm Account Deletion') }}
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>

                <form method="POST" wire:submit.prevent="deleteUser">
                    @csrf
                    <div class="modal-body">
                        <p class="mb-3 font-weight-bold text-danger">
                            {{ __('Are you sure you want to delete your account?') }}
                        </p>
                        <p class="text-muted">
                            {{ __('Once your account is deleted, all of its resources and data will be permanently removed. Please enter your password to confirm deletion.') }}
                        </p>

                        <!-- Password Input -->
                        <div class="form-group mt-3">
                            <label for="password">{{ __('Password') }}</label>
                            <input
                                type="password"
                                id="password"
                                class="form-control @error('password') is-invalid @enderror"
                                wire:model="password"
                                placeholder="{{ __('Enter your password') }}"
                                required
                            >
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            {{ __('Cancel') }}
                        </button>
                        <button type="submit" class="btn btn-danger" data-test="confirm-delete-user-button">
                            {{ __('Delete Account') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>


{{-- <section class="mt-10 space-y-6">
    <div class="relative mb-5">
        <flux:heading>{{ __('Delete account') }}</flux:heading>
        <flux:subheading>{{ __('Delete your account and all of its resources') }}</flux:subheading>
    </div>

    <flux:modal.trigger name="confirm-user-deletion">
        <flux:button variant="danger" x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')" data-test="delete-user-button">
            {{ __('Delete account') }}
        </flux:button>
    </flux:modal.trigger>

    <flux:modal name="confirm-user-deletion" :show="$errors->isNotEmpty()" focusable class="max-w-lg">
        <form method="POST" wire:submit="deleteUser" class="space-y-6">
            <div>
                <flux:heading size="lg">{{ __('Are you sure you want to delete your account?') }}</flux:heading>

                <flux:subheading>
                    {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
                </flux:subheading>
            </div>

            <flux:input wire:model="password" :label="__('Password')" type="password" />

            <div class="flex justify-end space-x-2 rtl:space-x-reverse">
                <flux:modal.close>
                    <flux:button variant="filled">{{ __('Cancel') }}</flux:button>
                </flux:modal.close>

                <flux:button variant="danger" type="submit" data-test="confirm-delete-user-button">
                    {{ __('Delete account') }}
                </flux:button>
            </div>
        </form>
    </flux:modal>
</section> --}}
