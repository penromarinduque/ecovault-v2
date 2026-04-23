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
    <canvas id="qrBarcodeCanvas" width="226.77" height="151.18"></canvas>
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
        const qrCode = new Image(80, 80);
        const barcode = new Image(146, 50);
        const imageText = new Image(146, 40);
        qrCode.src = 'https://api.qrcode-monkey.com/qr/custom?data={{ route("validate-qr", ["id" => strtr(base64_encode($file->barcode_no), '+/=', '-_,')]) }}&config={%22logo%22:%22c7e3dafa91a9a2806b38cf5992939868cefc6171.svg%22}';
        barcode.src = 'https://barcodeapi.org/api/128/{{ $file->barcode_no }}';
        imageText.src = '{{ asset("assets/images/barcode-text.png") }}';
        qrCode.onload = function() {
            ctx.drawImage(qrCode, 140, 65, 80, 80);
        };
        barcode.onload = function() {
            ctx.drawImage(barcode, 0, 78, 146, 64);
        };
        imageText.onload = function() {
            ctx.drawImage(imageText, 0, 0, 226, 65);
        };
        ctx.font = "50px Arial";
        ctx.fillText(
            "Department of Environment and Natural Resources", 
            0, 
            0,
            226
        );
        ctx.fillStyle = 'white';
        ctx.fillRect(0, 0, canvas.width, canvas.height);

    </script>
    {{-- @endscript --}}
</body>
</html>