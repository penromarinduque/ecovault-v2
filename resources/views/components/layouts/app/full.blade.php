
<!DOCTYPE html>
<html dir="ltr" lang="en">
    <head>
        @include('partials.head')
    </head>
    <body >
        <x-preloader />

        <div class="container bg-light">
            <div>
            {{ $slot }}
            </div>
        </div>
        <footer class="footer text-center">
            All Rights Reserved by DENR-PENRO Marinduque.
        </footer>
        <script src="https://kit.fontawesome.com/48b9baa16e.js" crossorigin="anonymous"></script>
        @include('partials.scripts')
        
    </body>
</html>