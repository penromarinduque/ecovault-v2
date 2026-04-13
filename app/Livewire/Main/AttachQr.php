<?php

namespace App\Livewire\Main;

use App\Models\File;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class AttachQr extends Component
{
    public $main_file_id;
    public $file;
    public $paper_size   = 'A4';
    public $document_meta = [];
    public $qr_src       = null;
    public $barcode_src  = null;
    public $barcode_code = null;
    public $showResizeHandle = false;

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
        if (!$this->file) {
            return;
        }

        $barcodeNo = $this->file->barcode_no ?? $this->file->doc_control_no ?? $this->file->id;
        $validateUrl = route('validate-qr', ['id' => $barcodeNo]);

        $this->qr_src = 'https://api.qrcode-monkey.com/qr/custom?data=' . urlencode($validateUrl);
        $this->barcode_src = 'https://barcodeapi.org/api/128/' . urlencode($barcodeNo);
        $this->barcode_code = "DENR-" . now()->year . "-" . $barcodeNo;
        $this->showResizeHandle = true;
    }

    public function render()
    {
        return view('livewire.main.attach-qr');
    }
}