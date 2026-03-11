<div class="main" style="padding: 20px;">
    <div class="content-container" style=" display: flex">
        <!-- Left Sidebar Panel -->
        <div class="col-12 col-lg-3" style="height: fit-content;">
        <!-- Document Information -->
        <div class="document-information-container mb-5" 
            style="background-color: #ebebeb; padding: 20px; border-radius: 5px; height: fit-content;">
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

            <button wire:click="print" class="btn btn-primary btn-block mt-3">
                <i class="fas fa-print mr-2"></i> Print
            </button>
        </div>

        <!-- Main Content Area -->
        <div class="col-12 col-lg-9">
            <div style="background-color: #f9f9f9; padding: 40px; border-radius: 5px; min-height: 600px; display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center;">
                
                <!-- Content Display Area -->
                <div style="margin-bottom: 60px; width:100%;">
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
                            <embed src="{{ $url }}" type="application/pdf" width="100%" height="400px">
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

                <!-- QR and Barcode Section -->
                <div style="width: 100%; max-width: 500px;">
                    <div class="card border-light" style="box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                        <div class="card-body p-4">
                            <h5 class="card-title font-weight-bold mb-4">Bar Code and QR Code</h5>
                            
                            <div class="row">
                                <!-- Barcode Column -->
                                <div class="col-md-6 text-center mb-md-0 mb-3">
                                    <div style="background-color: #e9ecef; padding: 20px; margin-bottom: 10px; border-radius: 4px; min-height: 100px; display: flex; align-items: center; justify-content: center;">
                                        @if($file && $file->barcode)
                                            <img src="{{ $file->barcode }}" alt="Barcode" style="max-height: 70px; max-width: 100%;">
                                        @else
                                            <p class="text-muted" style="font-size: 12px;">Barcode will appear here</p>
                                        @endif
                                    </div>
                                    <button class="btn btn-sm btn-outline-primary" wire:click="generateBarcode">
                                        <i class="fas fa-plus mr-1"></i> Generate Barcode
                                    </button>
                                </div>

                                <!-- QR Code Column -->
                                <div class="col-md-6 text-center">
                                    <div style="background-color: #e9ecef; padding: 20px; margin-bottom: 10px; border-radius: 4px; min-height: 100px; display: flex; align-items: center; justify-content: center;">
                                        @if($file && $file->qr_code)
                                            <img src="{{ $file->qr_code }}" alt="QR Code" style="max-height: 70px; max-width: 100%;">
                                        @else
                                            <p class="text-muted" style="font-size: 12px;">QR Code will appear here</p>
                                        @endif
                                    </div>
                                    <button class="btn btn-sm btn-outline-primary" wire:click="generateQr">
                                        <i class="fas fa-plus mr-1"></i> Generate QR Code
                                    </button>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="mt-4">
                                <button class="btn btn-success btn-block mb-2">
                                    <i class="fas fa-save mr-1"></i> Save Changes
                                </button>
                                <a href="{{ route('main.folders.show', ['main_folder_id' => $file->folder_id]) }}" class="btn btn-secondary btn-block">
                                    <i class="fas fa-arrow-left mr-1"></i> Back to Folder
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
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
