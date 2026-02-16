<?php

namespace App\Models;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use setasign\Fpdi\Fpdi;

class File extends Model
{
    //
    protected $guarded = [];
    protected $casts = [
        'date_released' => 'date'
    ];

    public function folder() 
    {
        return $this->belongsTo(Folder::class);
    }

    public function getIconAttribute()
    {
        if($this->file_type == "pdf"){
            return "fas fa-file-pdf";
        }
        if(in_array($this->file_type, ["jpeg", "png", "jpg", "gif", "svg", "bmp", "tiff", "tif"])){
            return "fas fa-file-image";
        }
        return "fas fa-file";
    }

    public function storeAndEmbedQr($document)
    {
        try {
            // Generate the QR validation link
            $url = route('validate-qr', $this->id);

            // Prepare filenames and extensions
            $extension = strtolower($document->getClientOriginalExtension());
            $file_name = Str::random(10) . uniqid() . '.' . $extension;

            // Store uploaded file first
            $filePath = $document->storeAs('uploads', $file_name);

            // Generate QR code image
            $qr_builder = new Builder(
                writer: new PngWriter(),
                data: $url,
                size: 300,
                margin: 10
            );
            $qr = $qr_builder->build();

            // Save QR to storage
            $qr_path = 'temp_qrs/' . $this->id . '.png';
            Storage::put($qr_path, $qr->getString());

            // Embed QR based on file type
            if (in_array($extension, ['jpg', 'jpeg', 'png'])) {
                $this->embedQrCodeInImage($filePath, $qr_path);
            } elseif ($extension === 'pdf') {
                $this->embedQrCodeInPdf($filePath, $qr_path);
            } elseif ($extension === 'zip') {
                $this->processZipFile($filePath, $qr_path);
            }

            // Update document record only after processing succeeds
            $this->file_name = $file_name;
            $this->file_type = $extension;
            $this->save();

            // Optionally clean up temp QR after embedding
            Storage::delete($qr_path);

        } catch (Exception $e) {
            Log::error('Error storing or embedding QR: ' . $e->getMessage());
            throw $e; // optional: rethrow if you want transaction rollback
        }
    }

    /**
     * Embed QR Code in image (jpg/png)
     */
    private function embedQrCodeInImage($filePath, $qrCodeFilePath)
    {
        try {
            $manager = ImageManager::gd();

            $absoluteFilePath = Storage::path($filePath);
            $absoluteQrPath = Storage::path($qrCodeFilePath);

            $image = $manager->read($absoluteFilePath);
            $qrCode = $manager->read($absoluteQrPath);

            // Resize QR to ~150x150px
            $qrCode->resize(150, 150);

            // Place QR at bottom-right corner with 10px margin
            // $image->place($qrCode, 'bottom-right', 10, 10);

            // Save image back in place
            $image->save($absoluteFilePath);

            return $filePath;
        } catch (Exception $e) {
            Log::error('Error embedding QR into image: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Embed QR Code in a PDF file
     */
    // private function embedQrCodeInPdf($filePath, $qrCodePath)
    // {
    //     $absolutePdfPath = Storage::path($filePath);
    //     $absoluteQrPath = Storage::path($qrCodePath);

    //     if (!file_exists($absolutePdfPath)) {
    //         throw new Exception("PDF file not found at: {$absolutePdfPath}");
    //     }

    //     if (!file_exists($absoluteQrPath)) {
    //         throw new Exception("QR Code file not found at: {$absoluteQrPath}");
    //     }

    //     try {
    //         $pdf = new Fpdi();
    //         $pageCount = $pdf->setSourceFile($absolutePdfPath);

    //         for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
    //             $templateId = $pdf->importPage($pageNo);
    //             $size = $pdf->getTemplateSize($templateId);

    //             $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
    //             $pdf->useTemplate($templateId);

    //             // Scale QR relative to page size (12% width)
    //             $qrWidth = min($size['width'], $size['height']) * 0.12;
    //             $qrHeight = $qrWidth;
    //             $margin = 10;

    //             $x = $size['width'] - $qrWidth - $margin;
    //             $y = $size['height'] - $qrHeight - $margin;

    //             $pdf->Image($absoluteQrPath, $x, $y, $qrWidth, $qrHeight);
    //         }

    //         // Overwrite the original PDF
    //         $pdf->Output('F', $absolutePdfPath);

    //         return $filePath;
    //     } catch (Exception $e) {
    //         Log::error('Error embedding QR into PDF: ' . $e->getMessage());
    //         return null;
    //     }
    // }
    private function embedQrCodeInPdf($filePath, $qrCodePath)
    {
        try {
            // 1. Create temporary local paths
            $tempPdf = tempnam(sys_get_temp_dir(), 'pdf_');
            $tempQr = tempnam(sys_get_temp_dir(), 'qr_');

            // 2. Download contents from MinIO to the temp files
            file_put_contents($tempPdf, Storage::disk('s3')->get($filePath));
            file_put_contents($tempQr, Storage::disk('s3')->get($qrCodePath));

            $pdf = new Fpdi();
            // 3. Point FPDI to the local temp file
            $pageCount = $pdf->setSourceFile($tempPdf);

            for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                $templateId = $pdf->importPage($pageNo);
                $size = $pdf->getTemplateSize($templateId);

                $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
                $pdf->useTemplate($templateId);

                $qrWidth = min($size['width'], $size['height']) * 0.12;
                $margin = 10;
                $x = $size['width'] - $qrWidth - $margin;
                $y = $size['height'] - $qrHeight - $margin;

                // $pdf->Image($tempQr, $x, $y, $qrWidth, $qrWidth);
            }

            // 4. Save the modified PDF back to the temp local file
            $pdf->Output('F', $tempPdf);

            // 5. Upload the modified file back to MinIO
            Storage::disk('s3')->put($filePath, file_get_contents($tempPdf));

            // 6. Cleanup temp files
            unlink($tempPdf);
            unlink($tempQr);

            return $filePath;
        } catch (Exception $e) {
            Log::error('Error embedding QR into PDF: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * (Optional) Handle ZIP files if you need special logic
     */
    private function processZipFile($filePath, $qrCodePath)
    {
        Log::info("Processing ZIP file {$filePath} with QR {$qrCodePath}");
        // Add logic here if needed
        return $filePath;
    }
}
