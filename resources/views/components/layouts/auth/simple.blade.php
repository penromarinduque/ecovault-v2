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
