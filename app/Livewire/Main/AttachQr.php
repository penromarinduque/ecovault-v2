<?php

namespace App\Livewire\Main;

use App\Models\File;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Milon\Barcode\DNS1D;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class AttachQr extends Component
{
    public $main_file_id;
    public $file;
    public $paper_size = 'A4';
    public $document_meta = [];
    public $qr_base64    = null;
    public $barcode_html = null;
    public $barcode_code = null; // ← add this

    public function mount($main_file_id)
    {
        $this->main_file_id = $main_file_id;
        $this->file = File::findOrFail($main_file_id);

        $this->document_meta = [
            'title'          => $this->file->name,
            'control_no'     => $this->file->doc_control_no,
            'office_source'  => $this->file->office_source,
            'classification' => 'Standard',
            'date_released'  => $this->file->date_released?->format('M d, Y'),
        ];
    }

    public function print()
    {
        session()->flash('message', 'Print functionality coming soon!');
    }

    public function generateQrBarcode()
    {
        if (!$this->file) return;

        $currentYear = now()->year;
        $controlNo   = $this->file->doc_control_no ?? $this->file->id;
        $fileName    = $this->file->name;

        // QR Code — includes file name
        $qrCode_string = "DENR-{$currentYear}-{$controlNo}-{$fileName}";

        // Barcode — no file name
        $this->barcode_code = "DENR-{$currentYear}-{$controlNo}";

        // Generate QR Code
        $qrCode = new QrCode($qrCode_string);
        $writer = new PngWriter();
        $result = $writer->write($qrCode);
        $this->qr_base64 = base64_encode($result->getString());

        // Generate Barcode
        $dns = new DNS1D();
        $this->barcode_html = $dns->getBarcodeHTML($this->barcode_code, 'C128');
    }

    public function render()
    {
        return view('livewire.main.attach-qr');
    }
}