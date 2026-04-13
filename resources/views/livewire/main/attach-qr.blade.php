<div class="main" style="padding: 20px;">
    <!--
    ============================================================
    ATTACH QR CODE COMPONENT
    ============================================================
    Purpose: Display PDF file preview with draggable, resizable
             QR code and barcode overlay for document verification
    
    Features:
    - PDF viewer with page navigation
    - Dynamic QR code generation from document metadata
    - Draggable container within paper boundaries
    - Resizable QR/barcode section with proportional scaling
    - Paper size selection (A4, Short, Long)
    - Print-optimized styling
    - Document metadata display in sidebar
    ============================================================
    -->

    <!-- ====== STYLES ====== -->
    <style>
        /* ====== CSS CUSTOM PROPERTIES ====== */
        :root {
            /* Paper dimensions (pixels) */
            --paper-width: 794px;
            --paper-height: 1123px;
            --paper-width-number: 794;
            --paper-height-number: 1123;
            
            /* QR/Barcode container base dimensions */
            --qr-container-base-width: 350px;
            --qr-container-base-height: 180px;
            
            /* Dynamic scaling factor (1 = 100%) */
            --qr-scale: 1;
        }

        /* ====== LAYOUT: MAIN CONTAINERS ====== */
        .content-container {
            display: flex;
            flex-direction: row;
            gap: 20px;
        }

        /* ====== SIDEBAR: DOCUMENT INFORMATION ====== */
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

        /* ========================================
           SECTION: PAPER & PDF DISPLAY
           Simulates a physical paper page
           ======================================== */
        #pdf-paper {
            position: relative;
            background: white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.4);
            transition: all 0.3s ease;
            margin: 0 auto 20px;
            overflow: hidden;
            contain: layout style paint; /* Performance optimization */
            width: var(--paper-width);
        }

        #pdf-canvas {
            display: block;
            width: var(--paper-width);
            height: var(--paper-height);
        }

        /* ========================================
           SECTION: QR & BARCODE CONTAINER
           Features:
           - Draggable within paper bounds
           - Resizable with proportional scaling
           - Contains DENR logo, barcode, QR code
           ======================================== */
        #qr-barcode-container {
            position: absolute;
            visibility: visible;
            left: 20px;
            top: 20px;
            z-index: 10;
            cursor: grab;
            user-select: none;
            overflow: hidden;
            min-width: calc(var(--qr-container-base-width) * 0.1);
            min-height: calc(var(--qr-container-base-height) * 0.1);
            max-width: calc(var(--paper-width-number) * 0.7);
            max-height: calc(var(--paper-height-number) * 0.45);
            width: calc(var(--qr-container-base-width) * var(--qr-scale));
            height: calc(var(--qr-container-base-height) * var(--qr-scale));
        }

        #qr-barcode-container:active {
            cursor: grabbing;
        }

        /* Inner wrapper - handles scale transformation */
        .qr-barcode-inner {
            width: var(--qr-container-base-width);
            height: var(--qr-container-base-height);
            transform-origin: top left;
            transform: scale(var(--qr-scale));
            display: flex;
            flex-wrap: wrap;
            align-items: flex-start;
            justify-content: flex-start;
            min-width: 0;
            min-height: 0;
        }

        /* Flex wrapper for layout: logo + barcode (vertical) and QR code (horizontal) */
        .qr-barcode-container__flex {
            display: flex;
            gap: 10px;
            align-items: flex-end;
            background: rgba(255, 255, 255, 0.95);
            padding: 8px;
            padding-top: 0px; 
            border-radius: 4px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            width: 100%;
            min-height: 100%;
            box-sizing: border-box;
            overflow: hidden;
        }

        /* Container for DENR logo + barcode (vertical stack) */
        .bar-code-logo-container {
            display: flex;
            gap: 8px;
            flex-direction: column;
            min-width: 0;
        }

        /* ====== BARCODE STYLING ====== */
        .barcode {
            display: block;
            max-width: 100%;
            width: 100%;
            height: auto;
        }

        .barcode img {
            display: block;
            max-width: 100%;
            height: auto;
        }

        .barcode-txt {
            color: black;
            font-size: 12px;
            font-weight: 600;
            text-align: center;
            margin: 4px 0 0 0;
            word-break: break-word;
        }

        /* ====== DENR LOGO & TEXT ====== */
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

        /* Organization name and details text */
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

        /* ====== QR CODE STYLING ====== */
        .qr-code-container {
            position: relative;
            width: 110px;
            max-width: 110px;
            height: auto;
            object-fit: contain;
            flex-shrink: 0;
        }

        /* Centered DENR logo overlay on QR code (for branding) */
        .qr-code-container__logo {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 30%;
            height: 30%;
        }

        .qr-code {
            width: 100%;
            height: 100%
        }

        /* ========================================
           SECTION: RESIZE HANDLE
           - Positioned at bottom-right of container
           - Indicates resize capability
           - Visible only after QR generation
           ======================================== */
        .qr-barcode-resize-handle {
            position: absolute;
            width: 14px;
            height: 14px;
            right: 6px;
            bottom: 6px;
            background: rgba(0, 0, 0, 0.35);
            border-radius: 4px;
            cursor: se-resize;
            z-index: 12;
        }

        /* Hide resize handle by default until QR/barcode is generated */
        .qr-barcode-resize-handle.hide {
            display: none;
        }

        /* Visual corner indicator on resize handle */
        .qr-barcode-resize-handle::before {
            content: '';
            position: absolute;
            right: 3px;
            bottom: 3px;
            width: 8px;
            height: 8px;
            border-right: 2px solid white;
            border-bottom: 2px solid white;
        }

        /* ====== PAGE NAVIGATION CONTROLS ====== */
        .pdf-controls {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            margin-top: 20px;
        }

        /* ====== LAYOUT: SIDEBAR & MAIN CONTENT ====== */
        #left-side-panel {
            flex: 0 0 300px;
            align-self: flex-start;
        }

        #main-content {
            flex: 1 1 auto;
            min-width: 0;
        }

        /* ====== RESPONSIVE: TABLET & BELOW ====== */
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

        /* ========================================
           PRINT MODE STYLES
           Optimized for physical printing
           - Full-page layout without margins
           - Hides UI elements (resize handle, controls)
           - Color preservation for accurate output
           ======================================== */
        @media print {
            @page {
                size: A4 portrait;
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

            /* Hide all elements by default, show only PDF content */
            * {
                visibility: hidden;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            /* QR-only print mode: show only the QR/barcode container and paper background */
            body.print-qr-only #pdf-paper,
            body.print-qr-only #qr-barcode-container,
            body.print-qr-only #qr-barcode-container * {
                visibility: visible !important;
            }

            body.print-qr-only #pdf-canvas {
                display: none !important;
            }

            /* Ensure PDF paper and all children are visible during print */
            #pdf-paper,
            #pdf-paper * {
                visibility: visible;
            }

            #pdf-paper {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                margin: 0;
                padding: 0;
                box-shadow: none;
                border-radius: 0;
                overflow: hidden;
            }

            #pdf-canvas {
                width: 100%;
                height: 100%;
            }

            /* Keep QR/barcode container visible and positioned during print */
            #qr-barcode-container {
                visibility: visible;
                left: var(--print-left, 20px);
                top: var(--print-top, 20px);
                /* width: calc(var(--qr-container-base-width) * var(--qr-scale));
                height: calc(var(--qr-container-base-height) * var(--qr-scale)); */
                /* width: var(--qr-container-base-width * var(--qr-scale)); */
                /* height: var(--qr-container-base-height ); */
            }

            /* Hide resize handle during print mode */
            .qr-barcode-resize-handle {
                display: none;
            }
        }
    </style>

    <!-- ====== HTML: PAGE LAYOUT ====== -->
    <div class="content-container">
        <!-- ====== LEFT SIDEBAR: DOCUMENT INFO & CONTROLS ====== -->
        <div id="left-side-panel" class="col-12 col-lg-3">
            <!-- Back button -->
            <button onclick="history.back()" class="btn btn-secondary mb-3">
                &larr; Back
            </button>
            
            <!-- Document metadata display -->
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

            <!-- Paper size selector -->
            <div class="mb-3">
                <label for="paperSize" class="small font-weight-bold d-block mb-2">Select Paper Size</label>
                <select wire:model="paper_size" id="paperSize" class="form-control form-control-sm">
                    <option value="A4">A4</option>
                    <option value="Short">Short</option>
                    <option value="Long">Long</option>
                </select>
            </div>

            <!-- Generate QR & Barcode button (Livewire action) -->
            <button wire:click="generateQrBarcode" 
                id="generate-qr-barcode-btn"
                class="btn btn-success btn-block mt-2 text-left">
                <i class="fas fa-qrcode mr-2"></i> Generate QR & Barcode
            </button>

            <!-- Print Page button -->
            <button id="print-page-btn" class="btn btn-primary btn-block mt-2 text-left">
                <i class="fas fa-print mr-2"></i> Print
            </button>

            <!-- Print QR and Barcode button -->
            <button id="print-qr-barcode-btn" class="btn btn-primary btn-block mt-2 text-left">
                <i class="fas fa-print mr-2"></i> Print QR and Barcode
            </button>
        </div>

        <!-- ====== MAIN CONTENT: FILE PREVIEW ====== -->
        <div id="main-content" class="col-12 col-lg-9">
            @if($file)
                @php
                    $path = 'uploads/'.$file->file_name;
                    $url = Storage::temporaryUrl($path, now()->addMinutes(60));
                    $mime = Storage::mimeType($path) ?: 'application/octet-stream';
                @endphp

                {{-- ====== IMAGE PREVIEW ====== --}}
                @if(str_starts_with($mime, 'image/'))
                    @php
                        try {
                            $imageData = Storage::get($path);
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

                {{-- ====== PDF PREVIEW WITH QR/BARCODE OVERLAY ====== --}}
                @elseif($mime === 'application/pdf')
                    @php
                        $pdfUrl = Storage::temporaryUrl($path, now()->addMinutes(60));
                    @endphp
                    
                    <!-- Paper/Page container -->
                    <div id="pdf-paper">
                        <!-- Canvas for rendering PDF pages -->
                        <canvas wire:ignore id="pdf-canvas"></canvas>

                        <!-- QR & Barcode overlay container (draggable & resizable) -->
                        <div id="qr-barcode-container">
                            <div class="qr-barcode-inner">
                                <!-- Only show QR/barcode content if generated -->
                                @if($qr_src || $barcode_src)
                                    <div class="qr-barcode-container__flex">
                                        <!-- Left section: DENR logo + barcode -->
                                        <div class="bar-code-logo-container">
                                            <!-- DENR organization logo and name -->
                                            <div class="denr-logo">
                                                <img class="denr-logo__img" src="{{ asset('LOGO.svg') }}" alt="DENR Logo">
                                                <p class="denr-logo__txt">
                                                    Department of Environment <br>
                                                    and Natural Resources <br>
                                                    <span>PENRO - Marinduque</span>
                                                </p>
                                            </div>

                                            <!-- Document barcode (if generated) -->
                                            @if($barcode_src)
                                                <div>
                                                    <img class="barcode" src="{{ $barcode_src }}" alt="Barcode" />
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Right section: QR code with centered logo overlay -->
                                        @if($qr_src)
                                            <div class="qr-code-container">
                                                <img class="qr-code-container__logo" src="{{ asset('LOGO.svg') }}" alt="DENR Logo">
                                                <img class="qr-code" src="{{ $qr_src }}" alt="QR Code" />
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Resize handle (visible only after generation) -->
                            <div class="qr-barcode-resize-handle {{ $showResizeHandle ? '' : 'hide' }}" aria-label="Resize QR container"></div>
                        </div>
                    </div>

                    <!-- PDF page navigation controls -->
                    <div class="pdf-controls">
                        <button id="pdf-prev" class="btn btn-sm btn-outline-secondary" disabled>&#8592; Prev</button>
                        <span id="pdf-page-info" class="small text-muted">
                            Page <span id="pdf-page-num">1</span> of <span id="pdf-page-count">–</span>
                        </span>
                        <button id="pdf-next" class="btn btn-sm btn-outline-secondary">Next &#8594;</button>
                    </div>

                    <!-- PDF.js Library & JavaScript Engine -->
                    <script>
                        /**
                         * PDF Viewer with QR Code Overlay
                         * 
                         * Features:
                         * 1. Renders PDF pages using PDF.js library
                         * 2. Supports page navigation (prev/next)
                         * 3. Paper size selection (A4, Short, Long)
                         * 4. Draggable QR/barcode container
                         * 5. Resizable container with proportional scaling
                         * 6. Print-optimized rendering
                         */
                        (function () {
                            // Configuration
                            const PDF_URL = @json($pdfUrl);
                            const root = document.documentElement;

                            // Supported paper sizes with dimensions (in pixels)
                            const PAPER_SIZES = {
                                'A4':    { width: 794,  height: 1123 },
                                'Short': { width: 816,  height: 1056 },
                                'Long':  { width: 816,  height: 1344 },
                            };

                            // DOM Element References
                            const canvas             = document.getElementById('pdf-canvas');
                            const ctx                = canvas.getContext('2d');
                            const paper              = document.getElementById('pdf-paper');
                            const pageNumEl          = document.getElementById('pdf-page-num');
                            const pageCountEl        = document.getElementById('pdf-page-count');
                            const prevBtn            = document.getElementById('pdf-prev');
                            const nextBtn            = document.getElementById('pdf-next');
                            const paperSelect        = document.getElementById('paperSize');
                            const printButton        = document.getElementById('print-page-btn');
                            const printQrButton      = document.getElementById('print-qr-barcode-btn');
                            const qrBarcodeContainer = document.getElementById('qr-barcode-container');
                            const qrResizeHandle     = document.querySelector('.qr-barcode-resize-handle');

                            // Extract base QR container dimensions from CSS variables
                            const QR_BASE = {
                                width: parseFloat(getComputedStyle(root).getPropertyValue('--qr-container-base-width')) || 350,
                                height: parseFloat(getComputedStyle(root).getPropertyValue('--qr-container-base-height')) || 180,
                            };

                            // State Variables
                            let pdfDoc      = null;          // Loaded PDF document
                            let currentPage = 1;             // Current page number
                            let renderTask  = null;          // Current rendering task
                            let currentSize = paperSelect ? paperSelect.value : 'A4';
                            let isResizing  = false;         // Track active resize operation
                            let resizeStartX = 0, resizeStartY = 0;
                            let resizeStartWidth = 0, resizeStartHeight = 0;

                            /**
                             * Apply paper size and update rendering
                             * Updates CSS variables for paper dimensions
                             */
                            function applyPaperSize(size) {
                                const dim = PAPER_SIZES[size] || PAPER_SIZES['A4'];
                                root.style.setProperty('--paper-width', dim.width + 'px');
                                root.style.setProperty('--paper-height', dim.height + 'px');
                                root.style.setProperty('--paper-width-number', dim.width);
                                root.style.setProperty('--paper-height-number', dim.height);
                                renderPage(currentPage);
                            }

                            /**
                             * Render a specific PDF page to canvas
                             * Scales PDF to fit within paper bounds
                             */
                            function renderPage(num) {
                                if (!pdfDoc) return;

                                pdfDoc.getPage(num).then(function (page) {
                                    const dim     = PAPER_SIZES[currentSize] || PAPER_SIZES['A4'];
                                    const paperW  = dim.width  - 16;
                                    const paperH  = dim.height - 16;
                                    const pageVp  = page.getViewport({ scale: 1 });

                                    // Calculate scale to fit page within paper dimensions
                                    let scale     = paperW / pageVp.width;
                                    const scaledH = pageVp.height * scale;
                                    if (scaledH > paperH) {
                                        scale = paperH / pageVp.height;
                                    }

                                    const viewport = page.getViewport({ scale });
                                    canvas.width   = viewport.width;
                                    canvas.height  = viewport.height;

                                    // Cancel previous render task if still running
                                    if (renderTask) {
                                        renderTask.cancel();
                                    }

                                    // Render page to canvas
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

                            /**
                             * Initialize PDF.js and load document
                             * Waits for PDF.js library to be loaded
                             */
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
                                        paper.innerHTML = '<p class="text-danger small p-3">Failed to load PDF: ' + err.message + '</p>';
                                    });
                            }

                            /**
                             * Paper size change handler
                             */
                            if (paperSelect) {
                                paperSelect.addEventListener('change', function () {
                                    currentSize = this.value;
                                    applyPaperSize(currentSize);
                                });
                            }

                            /**
                             * Enable drag functionality for QR/barcode container
                             * Constrains movement within paper bounds
                             */
                            function enableDraggableQrBarcode() {
                                if (!qrBarcodeContainer) return;

                                let isDragging = false;
                                let dragOffsetX = 0, dragOffsetY = 0;

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

                                    // Constrain to paper bounds
                                    const maxLeft = paperRect.width  - containerRect.width;
                                    const maxTop  = paperRect.height - containerRect.height;
                                    newLeft = Math.max(0, Math.min(newLeft, maxLeft));
                                    newTop  = Math.max(0, Math.min(newTop,  maxTop));

                                    qrBarcodeContainer.style.left = `${newLeft}px`;
                                    qrBarcodeContainer.style.top  = `${newTop}px`;
                                });

                                document.addEventListener('mouseup', function () {
                                    isDragging = false;
                                });
                            }

                            enableDraggableQrBarcode();

                            /**
                             * Enable resize functionality for QR/barcode container
                             * Maintains proportional scaling with min/max constraints
                             */
                            function enableResizableQrBarcode() {
                                if (!qrBarcodeContainer || !qrResizeHandle) return;

                                qrResizeHandle.addEventListener('mousedown', function (e) {
                                    isResizing = true;
                                    resizeStartX = e.clientX;
                                    resizeStartY = e.clientY;
                                    resizeStartWidth = qrBarcodeContainer.getBoundingClientRect().width;
                                    resizeStartHeight = qrBarcodeContainer.getBoundingClientRect().height;
                                    e.preventDefault();
                                    e.stopPropagation();
                                });

                                document.addEventListener('mousemove', function (e) {
                                    if (!isResizing) return;

                                    const paperRect = paper.getBoundingClientRect();
                                    const deltaX = e.clientX - resizeStartX;
                                    const deltaY = e.clientY - resizeStartY;

                                    let newWidth = resizeStartWidth + deltaX;
                                    let newHeight = resizeStartHeight + deltaY;

                                    // Apply size constraints
                                    const minWidth = QR_BASE.width * 0.1;
                                    const minHeight = QR_BASE.height * 0.1;
                                    const maxWidth = Math.max(minWidth, paperRect.width - qrBarcodeContainer.offsetLeft - 16);
                                    const maxHeight = Math.max(minHeight, paperRect.height - qrBarcodeContainer.offsetTop - 16);

                                    newWidth = Math.max(minWidth, Math.min(newWidth, maxWidth));
                                    newHeight = Math.max(minHeight, Math.min(newHeight, maxHeight));

                                    // Calculate proportional scale
                                    const scaleX = newWidth / QR_BASE.width;
                                    const scaleY = newHeight / QR_BASE.height;
                                    const scale = Math.max(0.1, Math.min(scaleX, scaleY));

                                    qrBarcodeContainer.style.width = `${QR_BASE.width * scale}px`;
                                    qrBarcodeContainer.style.height = `${QR_BASE.height * scale}px`;
                                    qrBarcodeContainer.style.setProperty('--qr-scale', scale.toString());
                                });

                                document.addEventListener('mouseup', function () {
                                    if (isResizing) {
                                        isResizing = false;
                                    }
                                });
                            }

                            enableResizableQrBarcode();

                            /**
                             * Sync current on-screen QR container position to print-ready values.
                             * Uses the current paper preview dimensions and converts pixels to millimeters.
                             */
                            function syncQrBarcodePositionForPrint() {
                                if (!qrBarcodeContainer || !paper) return;

                                const paperRect = paper.getBoundingClientRect();
                                const computed = getComputedStyle(qrBarcodeContainer);
 
                                const scale = parseFloat(computed.getPropertyValue('--qr-scale')) || 1;

                                const widthPercent = (codeWidth / paperRect.width) * 100;
                                const heightPercent = (codeHeight / paperRect.height) * 100;

                                const leftPercent = (leftPx / paperRect.width) * 100;
                                const topPercent = (topPx / paperRect.height) * 100;

                                qrBarcodeContainer.style.setProperty("--qr-container-base-width", `${widthPercent * scale}%`);
                                qrBarcodeContainer.style.setProperty("--qr-container-base-height", `${heightPercent * scale}%`);
                                qrBarcodeContainer.style.setProperty('--print-left', `${leftPercent}%`);
                                qrBarcodeContainer.style.setProperty('--print-top', `${topPercent}%`);
                            }
                            // function syncQrBarcodePositionForPrint() {
                            //     if (!qrBarcodeContainer || !paper) return;

                            //     const paperRect = paper.getBoundingClientRect();
                            //     const computed = getComputedStyle(qrBarcodeContainer);
                            //     const leftPx = parseFloat(computed.left) || 0;
                            //     const topPx = parseFloat(computed.top) || 0;
 
                            //     // Keep pixel positions for print (since print page is also in pixels)
                            //     const codeWidth = qrBarcodeContainer.style.width ? parseFloat(qrBarcodeContainer.style.width) : computed.getPropertyValue('--qr-container-base-width');
                            //     const codeHeight = qrBarcodeContainer.style.height ? parseFloat(qrBarcodeContainer.style.height) : computed.getPropertyValue('--qr-container-base-height');
                            //     console.log(codeWidth, codeHeight, computed.getPropertyValue('--qr-scale'));
                            //     qrBarcodeContainer.style.setProperty("--qr-container-base-width", `${((codeWidth/paperRect.width) * 100) * computed.getPropertyValue('--qr-scale')}vw`);
                            //     qrBarcodeContainer.style.setProperty("--qr-container-base-height", `${((codeHeight/paperRect.height) * 100) * computed.getPropertyValue('--qr-scale')}vh`);
                            //     qrBarcodeContainer.style.setProperty('--print-left', `${(qrBarcodeContainer.offsetLeft/paperRect.width) * 100}vw`);
                            //     qrBarcodeContainer.style.setProperty('--print-top', `${(qrBarcodeContainer.offsetTop/paperRect.height) * 100}vh`);
                            // }

                            /**
                             * Page navigation handlers
                             */
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

                            /**
                             * Print handler
                             */
                            printButton.addEventListener('click', function () {
                                syncQrBarcodePositionForPrint();
                                document.body.classList.remove('print-qr-only');
                                window.print();
                            });

                            if (printQrButton) {
                                printQrButton.addEventListener('click', function () {
                                    syncQrBarcodePositionForPrint();
                                    document.body.classList.add('print-qr-only');
                                    window.print();
                                });
                            }

                            window.addEventListener('beforeprint', syncQrBarcodePositionForPrint);
                            window.addEventListener('afterprint', function () {
                                if (!qrBarcodeContainer) return;
                                qrBarcodeContainer.style.removeProperty('--print-left');
                                qrBarcodeContainer.style.removeProperty('--print-top');
                                document.body.classList.remove('print-qr-only');
                            });

                            // Initialize PDF viewer
                            applyPaperSize(currentSize);
                            initPdf();
                        })();
                    </script>

                {{-- ====== TEXT FILE PREVIEW ====== --}}
                @elseif(str_starts_with($mime, 'text/'))
                    @php
                        try {
                            $content = Storage::get($path);
                        } catch (\Exception $e) {
                            $content = 'Error loading file content.';
                        }
                    @endphp
                    <pre style="white-space: pre-wrap; word-wrap: break-word; max-height: 400px; overflow-y: auto; background: white; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">{{ $content }}</pre>

                {{-- ====== OTHER FILE TYPES ====== --}}
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

    {{-- Session status message --}}
    @if (session()->has('message'))
        <div class="alert alert-info alert-dismissible fade show mt-3" role="alert">
            {{ session('message') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
</div>
