<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>{{ $title ?? config('app.name') }}</title>

    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">
    <link rel="stylesheet" href="{{ asset('assets/js/jquery-ui-1.14.2.custom/jquery-ui.css') }}" />
    <link rel="preconnect" href="https://fonts.bunny.net">
</head>
<body>
    <canvas id="qrBarcodeCanvas" width="288" height="192"></canvas>
    </canvas>
    {{-- @script --}}
    <script src="{{ asset('assets/js/jquery-4.js') }}"></script>
    <script src="{{ asset('assets/js/jquery-ui-1.14.2.custom/jquery-ui.js') }}"></script>
    <script>
        $( function() {
            $( "#qrBarcodeCanvas" ).draggable();
        } );
        const canvas = document.getElementById('qrBarcodeCanvas');
        
        const ctx = canvas.getContext('2d');
        const qrCode = new Image(96, 96);
        const barcode = new Image(192, 64);
        const imageText = new Image(192, 55);
        qrCode.src = 'https://api.qrcode-monkey.com/qr/custom?data={{ route("validate-qr", ["id" => $file->barcode_no]) }}&config={%22logo%22:%229e93e1292f5126d21955919229715d0bbd701294.png%22}';
        barcode.src = 'https://barcodeapi.org/api/128/{{ $file->barcode_no }}';
        imageText.src = '{{ asset("assets/images/barcode-text.png") }}';
        qrCode.onload = function() {
            ctx.drawImage(qrCode, 192, 68, 96, 96);
        };
        barcode.onload = function() {
            ctx.drawImage(barcode, 0, 95, 192, 64);
        };
        imageText.onload = function() {
            ctx.drawImage(imageText, 0, 40, 192, 55);
        };
        ctx.font = "50px Arial";
        ctx.fillText(
            "Department of Environment and Natural Resources", 
            0, 
            0,
            192
        );
        ctx.fillStyle = 'white';
        ctx.fillRect(0, 0, canvas.width, canvas.height);
    </script>
    {{-- @endscript --}}
</body>
</html>