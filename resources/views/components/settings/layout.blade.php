<div class="card">
    <div class="card-body">
        <div class="">
            <!-- Sidebar -->
            <div class="pr-md-4 pb-4 w-100" >
                <ul class="nav  nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('profile.edit') ? 'active' : '' }}"
                           href="{{ route('profile.edit') }}"
                           wire:navigate>
                           {{ __('Profile') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('password.edit') ? 'active' : '' }}"
                           href="{{ route('password.edit') }}"
                           wire:navigate>
                           {{ __('Password') }}
                        </a>
                    </li>
                    {{-- @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('two-factor.show') ? 'active' : '' }}"
                               href="{{ route('two-factor.show') }}"
                               wire:navigate>
                               {{ __('Two-Factor Auth') }}
                            </a>
                        </li>
                    @endif --}}
                    {{-- <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('appearance.edit') ? 'active' : '' }}"
                           href="{{ route('appearance.edit') }}"
                           wire:navigate>
                           {{ __('Appearance') }}
                        </a>
                    </li> --}}
                </ul>
            </div>
        
            <!-- Divider for small screens -->
            <hr class="w-100 d-md-none">
        
            <!-- Main content -->
            <div class="flex-grow-1 w-100 pt-md-0 pt-3">
                <h4 class="mb-1">{{ $heading ?? '' }}</h4>
                <p class="text-muted mb-4">{{ $subheading ?? '' }}</p>
        
                <div class="mt-3 w-100" style="max-width: 600px;">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>
</div>

{{-- <div class="flex items-start max-md:flex-col">
    <div class="me-10 w-full pb-4 md:w-[220px]">
        <flux:navlist>
            <flux:navlist.item :href="route('profile.edit')" wire:navigate>{{ __('Profile') }}</flux:navlist.item>
            <flux:navlist.item :href="route('password.edit')" wire:navigate>{{ __('Password') }}</flux:navlist.item>
            @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                <flux:navlist.item :href="route('two-factor.show')" wire:navigate>{{ __('Two-Factor Auth') }}</flux:navlist.item>
            @endif
            <flux:navlist.item :href="route('appearance.edit')" wire:navigate>{{ __('Appearance') }}</flux:navlist.item>
        </flux:navlist>
    </div>

    <flux:separator class="md:hidden" />

    <div class="flex-1 self-stretch max-md:pt-6">
        <flux:heading>{{ $heading ?? '' }}</flux:heading>
        <flux:subheading>{{ $subheading ?? '' }}</flux:subheading>

        <div class="mt-5 w-full max-w-lg">
            {{ $slot }}
        </div>
    </div>
</div> --}}
