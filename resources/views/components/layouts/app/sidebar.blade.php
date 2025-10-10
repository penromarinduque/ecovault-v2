<!DOCTYPE html>
<html dir="ltr" lang="en">
    <head>
        @include('partials.head')
    </head>
    <body >
        <x-preloader />

        <div id="main-wrapper">
            <x-layouts.app.sidebar.topbar />
            <x-layouts.app.sidebar.sidenav />
            
            <div class="page-wrapper">
                <div class="container-fluid">
                    @livewire('components.toast')
                    {{ $slot }}
                </div>
                <footer class="footer text-center">
                    All Rights Reserved by DENR-PENRO Marinduque.
                </footer>
            </div>
        </div>



        @include('partials.scripts')
        
    </body>
</html>