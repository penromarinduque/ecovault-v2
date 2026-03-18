<div class="main" style="padding: 20px;">
    <style>
        .content-container {
            display: flex;
            flex-direction: row;
        }

        .document-information-container{
            background-color: #ebebeb;
            padding: 20px; 
            border-radius: 5px; 
            height: fit-content;
        }

        #qr-barcode-container {
                visibility: visible !important;
                position: absolute !important;
                width: fit-content !important;
                height: 50px !important;
                top: 50% !important;
                left: 50% !important;
                scale: 0.5;
        }

        @media (max-width: 1100px) {
            .content-container {
                flex-direction: column;
                gap: 70px;
            }

            #left-side-panel {
                width: 100% !important;
                max-width: 100% !important;
                position: static !important; 
            }

            #main-content {
                width: 100% !important;
                max-width: 100% !important;
            }
        }
    </style>

    <div class="content-container">
        <!-- Left Sidebar Panel -->
        <div id="left-side-panel" class="col-12 col-lg-3" style="fit-content; align-self: flex-start;">
            <!-- Document Information -->
            <div class="document-information-container mb-5">
                <div class="mb-2">
                    <label class="m-0 font-weight-bold">Document Title:</label>
                    <p class="m-0" style="word-break: break-word;">
                        {{ $document_meta['title'] ?? 'N/A' }}
                    </p>
                </div>
                
                <div class="mb-2">
                    <label class="m-0 font-weight-bold">Office Source:</label>
                    <p class="m-0" style="word-break: break-word;">
                        {{ $document_meta['office_source'] ?? 'N/A' }}
                    </p>
                </div>
                
                <div class="mb-2">
                    <label class="m-0 font-weight-bold">Control Number:</label>
                    <p class="m-0" style="word-break: break-word;">
                        {{ $document_meta['control_no'] ?? 'N/A' }}
                    </p>
                </div>
                
                <div class="mb-2">
                    <label class="m-0 font-weight-bold">Classification:</label>
                    <p class="m-0" style="word-break: break-word;">
                        {{ $document_meta['classification'] ?? 'N/A' }}
                    </p>
                </div>
                
                <div>
                    <label class="m-0 font-weight-bold">Date Released:</label>
                    <p class="m-0 flex-grow-1 text-wrap">
                        {{ $document_meta['date_released'] ?? 'N/A' }}
                    </p>
                </div>
            </div>

            <!-- Paper Size and Print Options -->
            <div class="mb-3">
                <label for="paperSize" class="small font-weight-bold">Select Paper Size</label>
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
                <!-- Content Display Area -->
                <div style="margin-bottom: 60px; width:100%;" >
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
                            $path = 'uploads/' . $file->file_name;
                            $pdfUrl = url('storage/' . $path);
                        @endphp

                        
                        <div id="pdf-paper" style="background:white; box-shadow: 0 2px 8px rgba(0,0,0,0.4); transition: all 0.3s ease;">
                            {{-- PDF Container --}}
                            <canvas wire:ignore id="pdf-canvas" style="display:block; width:100%; height:100%;"></canvas>

                            {{-- QR & Barcode Container --}}
                            <div id="qr-barcode-container" class="mt-3">
                                @if($qr_base64 || $barcode_html)
                                    <div style="display:flex; padding: 15px; border-radius: 5px; border: 1px solid #ddd; text-align: center;">

                                        {{-- Barcode --}}
                                        @if($barcode_html)
                                            <div>
                                                <div style="display: inline-block; overflow-x: auto; max-width: 100%;">
                                                    {!! $barcode_html !!}
                                                </div>
                                                <p class="mt-1 small">
                                                    {{ $barcode_code ?? $file->barcode_no ?? $file->doc_control_no }}
                                                </p>
                                            </div>
                                        @endif

                                        {{-- QR Code --}}
                                        @if($qr_base64)
                                            <div class="mb-3">
                                                <img src="data:image/png;base64,{{ $qr_base64 }}" 
                                                    alt="QR Code"
                                                    style="width: 150px; height: 150px; object-fit: contain;">
                                            </div>
                                        @endif
                                        
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Pagination --}}
                        <div class="d-flex justify-content-center align-items-center mt-3" style="gap:8px;">
                            <button id="pdf-prev" class="btn btn-sm btn-outline-secondary" disabled>&#8592; Prev</button>
                            <span id="pdf-page-info" class="small text-muted">
                                Page <span id="pdf-page-num">1</span> of <span id="pdf-page-count">–</span>
                            </span>
                            <button id="pdf-next" class="btn btn-sm btn-outline-secondary">Next &#8594;</button>
                        </div>

                        {{-- Print Styles --}}
                        <style id="print-styles">
                            @media print {
                                @page {
                                    margin: 0; 
                                    padding: 0;
                                }

                                body * {
                                    visibility: hidden;
                                    /* ← Force browser to print background colors */
                                    -webkit-print-color-adjust: exact !important;
                                    print-color-adjust: exact !important;
                                }

                                #pdf-paper, #pdf-paper * { 
                                    visibility: visible; 
                                }

                                #pdf-paper {
                                    position: fixed;
                                    top: 0;
                                    left: 0;
                                    width: 100% !important;
                                    height: 100% !important;
                                    margin: 0 !important;
                                    padding: 0 !important;
                                    box-shadow: none !important;
                                }

                                #pdf-canvas {
                                    width: 100% !important;
                                    height: 100% !important;
                                    margin: 0 !important;
                                    padding: 0 !important;
                                }
                                #qr-barcode-container {
                                    visibility: visible !important;
                                    position: absolute !important;
                                    width: fit-content !important;
                                    height: 50px !important;
                                    top: 50% !important;
                                    left: 50% !important;
                                    scale: 0.5
                                }
                            }
                        </style>

                    <script>
                        (function () {
                            const PDF_URL = @json($pdfUrl);

                            const PAPER_SIZES = {
                                'A4':    { width: 794,  height: 1123 }, // 210mm x 297mm
                                'Short': { width: 816,  height: 1056 }, // 215.9mm x 279.4mm (Letter)
                                'Long':  { width: 816,  height: 1344 }, // 215.9mm x 355.6mm (Legal)
                            };

                            const canvas      = document.getElementById('pdf-canvas');
                            const ctx         = canvas.getContext('2d');
                            const paper       = document.getElementById('pdf-paper');
                            const pageNumEl   = document.getElementById('pdf-page-num');
                            const pageCountEl = document.getElementById('pdf-page-count');
                            const prevBtn     = document.getElementById('pdf-prev');
                            const nextBtn     = document.getElementById('pdf-next');
                            const paperSelect = document.getElementById('paperSize');
                            const printButton = document.querySelector('#print-btn');

                            let pdfDoc      = null;
                            let currentPage = 1;
                            let renderTask  = null;
                            let currentSize = paperSelect ? paperSelect.value : 'A4';

                            // ====== APPLY PAPER SIZE ====== //
                           function applyPaperSize(size) {
                                const dim = PAPER_SIZES[size] || PAPER_SIZES['A4'];
                                paper.style.width  = dim.width  + 'px';
                                paper.style.height = dim.height + 'px';

                                const pageSizeMap = { 'A4': 'A4', 'Short': 'letter', 'Long': 'legal' };
                                const printStyle  = document.getElementById('print-styles');
                                printStyle.innerHTML = `
                                    @media print {
                                        @page {
                                            size: ${pageSizeMap[size]};
                                            margin: 0;
                                            padding: 0;
                                        }
                                        body * {
                                            visibility: hidden;
                                            /* ← Force browser to print background colors */
                                            -webkit-print-color-adjust: exact !important;
                                            print-color-adjust: exact !important;
                                        }
                                    
                                        #pdf-paper, #pdf-paper * { visibility: visible; }
                                        #pdf-paper {
                                            position: fixed;
                                            top: 0; left: 0;
                                            width: 100% !important;
                                            height: 100% !important;
                                            margin: 0 !important;
                                            padding: 0 !important;
                                            box-shadow: none !important;
                                        }
                                        #pdf-canvas {
                                            width: 100% !important;
                                            height: 100% !important;
                                            margin: 0 !important;
                                            padding: 0 !important;
                                        }
                                        #qr-barcode-container {
                                            visibility: visible !important;
                                            position: absolute !important;
                                            width: fit-content !important;
                                            height: 50px !important;
                                            top: 50% !important;
                                            left: 50% !important;
                                            scale: 0.5
                                        }
                                    }
                                `;
                            }
                            
                            
                            // ====== RENDER PAGE ====== //
                            function renderPage(num) {
                                if (!pdfDoc) return;
                                pdfDoc.getPage(num).then(function (page) {
                                    const dim        = PAPER_SIZES[currentSize] || PAPER_SIZES['A4'];
                                    const paperW     = dim.width  - 16;
                                    const paperH     = dim.height - 16;
                                    const pageVp     = page.getViewport({ scale: 1 });

                                    // Scale to fit width first, then check if height fits too
                                    let scale        = paperW / pageVp.width;
                                    const scaledH    = pageVp.height * scale;

                                    // If scaled height exceeds paper height, scale down to fit height instead
                                    if (scaledH > paperH) {
                                        scale = paperH / pageVp.height;
                                    }

                                    const viewport   = page.getViewport({ scale });
                                    canvas.width     = viewport.width;
                                    canvas.height    = viewport.height;

                                    //  No longer overriding paper height here — applyPaperSize() controls it
                                    if (renderTask) { renderTask.cancel(); }

                                    renderTask = page.render({ canvasContext: ctx, viewport });
                                    renderTask.promise.then(function () {
                                        renderTask = null;
                                        pageNumEl.textContent = num;
                                        prevBtn.disabled = num <= 1;
                                        nextBtn.disabled = num >= pdfDoc.numPages;
                                    }).catch(function (err) {
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
                                        document.getElementById('pdf-paper').innerHTML =
                                            '<p class="text-danger small p-3">Failed to load PDF: ' + err.message + '</p>';
                                    });
                            }

                            // Listen for paper size change
                            if (paperSelect) {
                                paperSelect.addEventListener('change', function(){
                                    currentSize = this.value;
                                    applyPaperSize(currentSize);
                                    renderPage(currentPage);
                                });
                            }

                            prevBtn.addEventListener('click', function (){
                                if (currentPage > 1) { renderPage(--currentPage); }
                            });

                            nextBtn.addEventListener('click', function (){
                                if (pdfDoc && currentPage < pdfDoc.numPages) { renderPage(++currentPage); }
                            });


                            printButton.addEventListener('click', () => {
                                window.print();
                            });

                            // Set initial paper size and load PDF
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