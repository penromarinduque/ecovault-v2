<div class="main" style="padding: 20px;">
    <!-- Global Styles -->
    <style>
        :root {
            --paper-width: 794px;
            --paper-height: 1123px;
            --paper-width-number: 794;
            --paper-height-number: 1123;
        }

        .content-container {
            display: flex;
            flex-direction: row;
            gap: 20px;
        }

        .document-information-container {
            background-color: #ebebeb;
            padding: 20px; 
            border-radius: 5px; 
            height: fit-content;
        }

        .document-information-container div {
            margin-bottom: 12px;
            word-break: break-word;
        }

        .document-information-container label {
            font-weight: bold;
            margin: 0;
        }

        .document-information-container p {
            margin: 4px 0 0 0;
        }

        /* ====== PAPER & PDF CONTAINER ====== */
        #pdf-paper {
            position: relative;
            background: white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.4);
            transition: all 0.3s ease;
            width: var(--paper-width);
            height: var(--paper-height);
            margin: 0 auto 20px;
            overflow: hidden;
            contain: layout style paint;
        }

        #pdf-canvas {
            display: block;
            width: 100%;
            height: 100%;
        }

        /* ====== QR & BARCODE CONTAINER ====== */
        #qr-barcode-container {
            position: absolute;
            visibility: visible;
            width: fit-content;
            height: fit-content;
            cursor: grab;
            user-select: none;
            transform-origin: top left;
            left: 20px;
            top: 20px;
            z-index: 10;
            scale: 0.6
        }

        #qr-barcode-container:active {
            cursor: grabbing;
        }

        .qr-barcode-container__flex {
            display: flex;
            gap: 10px;
            align-items: flex-end;
            background: rgba(255, 255, 255, 0.95);
            padding: 8px;
            border-radius: 4px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .bar-code-logo-container {
            display: flex;
            gap: 8px;
            flex-direction: column;
        }

        .denr-logo {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .denr-logo__img {
            --size: 70px;
            height: var(--size);
            width: var(--size);
            flex-shrink: 0;
        }

        .denr-logo__txt {
            color: black;
            text-align: left;
            margin: 0;
            font-family: 'Tahoma', Arial, sans-serif;
            font-size: 14px;
            line-height: 1.2em;
            font-weight: 600;
            white-space: nowrap;
        }

        .denr-logo__txt span {
            font-weight: 900;
            font-size: 12px;
        }

        .barcode {
            display: inline-block;
            overflow-x: auto;
            max-width: 100%;
        }

        .barcode-txt {
            color: black;
            font-size: 12px;
            font-weight: 600;
            text-align: center;
            margin: 4px 0 0 0;
            word-break: break-word;
        }

        .qr-code {
            width: 120px;
            height: 120px;
            object-fit: contain;
            flex-shrink: 0;
        }

        /* ====== PAGE CONTROLS ====== */
        .pdf-controls {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            margin-top: 20px;
        }

        /* ====== SIDEBAR ====== */
        #left-side-panel {
            flex: 0 0 300px;
            align-self: flex-start;
        }

        #main-content {
            flex: 1 1 auto;
            min-width: 0;
        }

        /* ====== RESPONSIVE ====== */
        @media (max-width: 1100px) {
            .content-container {
                flex-direction: column;
                gap: 40px;
            }

            #left-side-panel {
                flex: 1 1 auto;
            }

            #pdf-paper {
                width: 100%;
                max-width: 100%;
            }
        }

        /* ====== PRINT STYLES ====== */
        @media print {
            @page {
                size: A4;
                margin: 0;
                padding: 0;
            }

            html, body {
                width: 100%;
                height: 100%;
                margin: 0;
                padding: 0;
                overflow: hidden;
            }

            * {
                visibility: hidden;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            #pdf-paper,
            #pdf-paper * {
                visibility: visible;
            }

            #pdf-paper {
                position: fixed;
                top: 0;
                left: 0;
                width: 100vw;
                height: 100vh;
                margin: 0;
                padding: 0;
                box-shadow: none;
                border-radius: 0;
            }

            #pdf-canvas {
                width: 100%;
                height: 100%;
            }

            #qr-barcode-container {
                scale: 0.6;
                visibility: visible;
                position: absolute;
            }

            .qr-barcode-container__flex {
                background: transparent;
                box-shadow: none;
                padding: 0;
            }

            .denr-logo {
                display: flex;
                gap: 10px;
                align-items: center;
            }

            .denr-logo__img {
                height: 70px;
                width: 70px;
            }

            .denr-logo__txt {
                color: black;
                text-align: left;
                margin: 0;
                font-family: 'Tahoma', Arial, sans-serif;
                font-size: 16px;
                line-height: 1.2em;
                font-weight: 600;
                white-space: normal;
            }

            .denr-logo__txt span {
                font-weight: 900;
                font-size: 14px;
            }

            .qr-code {
                width: 150px;
                height: 150px;
            }

            .barcode-txt {
                font-size: 14px;
            }
        }
    </style>

    <div class="content-container">
        <!-- Left Sidebar Panel -->
        <div id="left-side-panel" class="col-12 col-lg-3">
            <button onclick="history.back()" class="btn btn-secondary mb-3">
                &larr; Back
            </button>
            
            <!-- Document Information -->
            <div class="document-information-container mb-5">
                <div>
                    <label>Document Title:</label>
                    <p>{{ $document_meta['title'] ?? 'N/A' }}</p>
                </div>
                
                <div>
                    <label>Office Source:</label>
                    <p>{{ $document_meta['office_source'] ?? 'N/A' }}</p>
                </div>
                
                <div>
                    <label>Control Number:</label>
                    <p>{{ $document_meta['control_no'] ?? 'N/A' }}</p>
                </div>
                
                <div>
                    <label>Classification:</label>
                    <p>{{ $document_meta['classification'] ?? 'N/A' }}</p>
                </div>
                
                <div>
                    <label>Date Released:</label>
                    <p>{{ $document_meta['date_released'] ?? 'N/A' }}</p>
                </div>
            </div>

            <!-- Paper Size and Print Options -->
            <div class="mb-3">
                <label for="paperSize" class="small font-weight-bold d-block mb-2">Select Paper Size</label>
                <select wire:model="paper_size" id="paperSize" class="form-control form-control-sm">
                    <option value="A4">A4</option>
                    <option value="Short">Short</option>
                    <option value="Long">Long</option>
                </select>
            </div>

            <button wire:click="generateQrBarcode" 
                id="generate-qr-barcode-btn"
                class="btn btn-success btn-block mt-2">
                <i class="fas fa-qrcode mr-2"></i> Generate QR & Barcode
            </button>

            <button id="print-btn" class="btn btn-primary btn-block mt-2">
                <i class="fas fa-print mr-2"></i> Print
            </button>
        </div>

        <!-- Main Content Area -->
        <div id="main-content" class="col-12 col-lg-9">
            @if($file)
                @php
                    $path = 'uploads/'.$file->file_name;
                    $url = Storage::disk('public')->url($path);
                    $mime = Storage::disk('public')->mimeType($path) ?: 'application/octet-stream';
                @endphp

                @if(str_starts_with($mime, 'image/'))
                    @php
                        try {
                            $imageData = Storage::disk('public')->get($path);
                            $base64 = 'data:' . $mime . ';base64,' . base64_encode($imageData);
                        } catch (\Exception $e) {
                            $base64 = '';
                        }
                    @endphp
                    @if($base64)
                        <img src="{{ $base64 }}" alt="File Preview" style="max-width: 100%; max-height: 400px; object-fit: contain;">
                    @else
                        <p class="text-muted">Error loading image.</p>
                    @endif

                @elseif($mime === 'application/pdf')
                    @php
                        $pdfUrl = url('storage/' . $path);
                    @endphp
                    
                    <div id="pdf-paper">
                        {{-- PDF Canvas --}}
                        <canvas wire:ignore id="pdf-canvas"></canvas>

                        {{-- QR & Barcode Container --}}
                        <div id="qr-barcode-container">
                            @if($qr_base64 || $barcode_html)
                                <div class="qr-barcode-container__flex">
                                    <div class="bar-code-logo-container">
                                        <div class="denr-logo">
                                            <img class="denr-logo__img" src="{{ asset('LOGO.png') }}" alt="DENR Logo">
                                            <p class="denr-logo__txt">
                                                Department of Environment <br>
                                                and Natural Resources <br>
                                                <span>PENRO - Marinduque</span>
                                            </p>
                                        </div>

                                        @if($barcode_html)
                                            <div>
                                                <div class="barcode">{!! $barcode_html !!}</div>
                                                <p class="barcode-txt">
                                                    {{ $barcode_code ?? $file->barcode_no ?? $file->doc_control_no }}
                                                </p>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- QR Code --}}
                                    @if($qr_base64)
                                        <img class="qr-code" src="data:image/png;base64,{{ $qr_base64 }}" alt="QR Code" />
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Page Control Buttons --}}
                    <div class="pdf-controls">
                        <button id="pdf-prev" class="btn btn-sm btn-outline-secondary" disabled>&#8592; Prev</button>
                        <span id="pdf-page-info" class="small text-muted">
                            Page <span id="pdf-page-num">1</span> of <span id="pdf-page-count">–</span>
                        </span>
                        <button id="pdf-next" class="btn btn-sm btn-outline-secondary">Next &#8594;</button>
                    </div>

                    <script>
                        (function () {
                            const PDF_URL = @json($pdfUrl);
                            const root = document.documentElement;

                            const PAPER_SIZES = {
                                'A4':    { width: 794,  height: 1123 },
                                'Short': { width: 816,  height: 1056 },
                                'Long':  { width: 816,  height: 1344 },
                            };

                            // DOM elements
                            const canvas             = document.getElementById('pdf-canvas');
                            const ctx                = canvas.getContext('2d');
                            const paper              = document.getElementById('pdf-paper');
                            const pageNumEl          = document.getElementById('pdf-page-num');
                            const pageCountEl        = document.getElementById('pdf-page-count');
                            const prevBtn            = document.getElementById('pdf-prev');
                            const nextBtn            = document.getElementById('pdf-next');
                            const paperSelect        = document.getElementById('paperSize');
                            const printButton        = document.getElementById('print-btn');
                            const qrBarcodeContainer = document.getElementById('qr-barcode-container');

                            // State
                            let pdfDoc      = null;
                            let currentPage = 1;
                            let renderTask  = null;
                            let currentSize = paperSelect ? paperSelect.value : 'A4';

                            // ====== APPLY PAPER SIZE ====== //
                            function applyPaperSize(size) {
                                const dim = PAPER_SIZES[size] || PAPER_SIZES['A4'];
                                
                                // Use CSS custom properties instead of direct style manipulation
                                root.style.setProperty('--paper-width', dim.width + 'px');
                                root.style.setProperty('--paper-height', dim.height + 'px');
                                root.style.setProperty('--paper-width-number', dim.width);
                                root.style.setProperty('--paper-height-number', dim.height);

                                // Render the page with new dimensions
                                renderPage(currentPage);
                            }

                            // ====== RENDER PAGE ====== //
                            function renderPage(num) {
                                if (!pdfDoc) return;

                                pdfDoc.getPage(num).then(function (page) {
                                    const dim     = PAPER_SIZES[currentSize] || PAPER_SIZES['A4'];
                                    const paperW  = dim.width  - 16;
                                    const paperH  = dim.height - 16;
                                    const pageVp  = page.getViewport({ scale: 1 });

                                    let scale     = paperW / pageVp.width;
                                    const scaledH = pageVp.height * scale;

                                    if (scaledH > paperH) {
                                        scale = paperH / pageVp.height;
                                    }

                                    const viewport = page.getViewport({ scale });
                                    canvas.width   = viewport.width;
                                    canvas.height  = viewport.height;

                                    if (renderTask) {
                                        renderTask.cancel();
                                    }

                                    renderTask = page.render({ canvasContext: ctx, viewport });
                                    renderTask.promise
                                        .then(function () {
                                            renderTask = null;
                                            pageNumEl.textContent = num;
                                            prevBtn.disabled = num <= 1;
                                            nextBtn.disabled = num >= pdfDoc.numPages;
                                        })
                                        .catch(function (err) {
                                            if (err.name !== 'RenderingCancelledException') {
                                                console.error('PDF render error:', err);
                                            }
                                        });
                                });
                            }

                            // ====== INIT PDF ====== //
                            function initPdf() {
                                if (typeof window.pdfjsLib === 'undefined') {
                                    setTimeout(initPdf, 100);
                                    return;
                                }

                                window.pdfjsLib.getDocument(PDF_URL).promise
                                    .then(function (pdf) {
                                        pdfDoc = pdf;
                                        pageCountEl.textContent = pdf.numPages;
                                        applyPaperSize(currentSize);
                                        renderPage(currentPage);
                                    })
                                    .catch(function (err) {
                                        paper.innerHTML =
                                            '<p class="text-danger small p-3">Failed to load PDF: ' + err.message + '</p>';
                                    });
                            }

                            // ====== PAPER SIZE CHANGE ====== //
                            if (paperSelect) {
                                paperSelect.addEventListener('change', function () {
                                    currentSize = this.value;
                                    applyPaperSize(currentSize);
                                });
                            }

                            // ====== DRAGGABLE QR/BARCODE CONTAINER ====== //
                            function enableDraggableQrBarcode() {
                                if (!qrBarcodeContainer) return;

                                let isDragging = false;
                                let dragOffsetX = 0;
                                let dragOffsetY = 0;

                                qrBarcodeContainer.addEventListener('mousedown', function (e) {
                                    isDragging = true;
                                    
                                    const rect  = qrBarcodeContainer.getBoundingClientRect();
                                    const paperRect = paper.getBoundingClientRect();
                                    
                                    dragOffsetX = e.clientX - rect.left;
                                    dragOffsetY = e.clientY - rect.top;

                                    e.preventDefault();
                                });

                                document.addEventListener('mousemove', function (e) {
                                    if (!isDragging) return;

                                    const paperRect     = paper.getBoundingClientRect();
                                    const containerRect = qrBarcodeContainer.getBoundingClientRect();

                                    let newLeft = e.clientX - paperRect.left - dragOffsetX;
                                    let newTop  = e.clientY - paperRect.top  - dragOffsetY;

                                    // Clamp to paper bounds
                                    const maxLeft = paperRect.width  - containerRect.width;
                                    const maxTop  = paperRect.height - containerRect.height;

                                    newLeft = Math.max(0, Math.min(newLeft, maxLeft));
                                    newTop  = Math.max(0, Math.min(newTop,  maxTop));

                                    qrBarcodeContainer.style.left = newLeft + 'px';
                                    qrBarcodeContainer.style.top  = newTop  + 'px';
                                });

                                document.addEventListener('mouseup', function () {
                                    isDragging = false;
                                });
                            }

                            enableDraggableQrBarcode();

                            // ====== PAGE NAVIGATION ====== //
                            prevBtn.addEventListener('click', function () {
                                if (currentPage > 1) {
                                    renderPage(--currentPage);
                                }
                            });

                            nextBtn.addEventListener('click', function () {
                                if (pdfDoc && currentPage < pdfDoc.numPages) {
                                    renderPage(++currentPage);
                                }
                            });

                            // ====== PRINT ====== //
                            printButton.addEventListener('click', function () {
                                window.print();
                            });

                            // Initialize
                            applyPaperSize(currentSize);
                            initPdf();
                        })();
                    </script>

                @elseif(str_starts_with($mime, 'text/'))
                    @php
                        try {
                            $content = Storage::disk('public')->get($path);
                        } catch (\Exception $e) {
                            $content = 'Error loading file content.';
                        }
                    @endphp
                    <pre style="white-space: pre-wrap; word-wrap: break-word; max-height: 400px; overflow-y: auto; background: white; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">{{ $content }}</pre>

                @else
                    <object data="{{ $url }}" type="{{ $mime }}" width="100%" height="400px">
                        <p class="text-muted">Cannot display preview. <a href="{{ $url }}" target="_blank">Download the file</a></p>
                    </object>
                @endif
            @else
                <p style="font-size: 36px; color: #555; font-weight: 300; margin: 0;">No file selected</p>
            @endif
        </div>
    </div>

    @if (session()->has('message'))
        <div class="alert alert-info alert-dismissible fade show mt-3" role="alert">
            {{ session('message') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
</div>
