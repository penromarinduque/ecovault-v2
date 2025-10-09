<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    @include('partials.head')
  </head>
  <body class="bg-light text-dark">
    <div class="container d-flex min-vh-100 align-items-center justify-content-center py-5">
      <div class="w-100" style="max-width: 400px;">
        <div class="text-center mb-4">
          <a href="{{ route('home') }}" class="text-decoration-none" wire:navigate>
            <div class="d-flex flex-column align-items-center">
              <span class="d-flex align-items-center justify-content-center rounded-circle bg-light mb-2" style="width: 60px; height: 60px;">
                <x-app-logo-icon class="text-dark" style="width: 36px; height: 36px;" />
              </span>
              <span class="sr-only">{{ config('app.name', 'Laravel') }}</span>
            </div>
          </a>
        </div>

        <div>
          {{ $slot }}
        </div>
      </div>
    </div>

    @include('partials.scripts')
  </body>
</html>

{{-- <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white antialiased dark:bg-linear-to-b dark:from-neutral-950 dark:to-neutral-900">
        <div class="bg-background flex min-h-svh flex-col items-center justify-center gap-6 p-6 md:p-10">
            <div class="flex w-full max-w-sm flex-col gap-2">
                <a href="{{ route('home') }}" class="flex flex-col items-center gap-2 font-medium" wire:navigate>
                    <span class="flex h-9 w-9 mb-1 items-center justify-center rounded-md">
                        <x-app-logo-icon class="size-9 fill-current text-black dark:text-white" />
                    </span>
                    <span class="sr-only">{{ config('app.name', 'Laravel') }}</span>
                </a>
                <div class="flex flex-col gap-6">
                    {{ $slot }}
                </div>
            </div>
        </div>
        @include('partials.scripts')
        {{-- @fluxScripts --}}
    </body>
</html> --}}
