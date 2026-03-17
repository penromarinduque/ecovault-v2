<?php

namespace App\Livewire\Main;

use App\Models\File;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class AttachQr extends Component
{
    public $main_file_id;
    public $file;
    public $paper_size = 'A4';
    public $document_meta = [];

    public function mount($main_file_id)
    {
        $this->main_file_id = $main_file_id;
        $this->file = File::findOrFail($main_file_id);
        
        
        // Load document metadata
        $this->document_meta = [
            'title' => $this->file->name,
            'control_no' => $this->file->doc_control_no,
            'office_source' => $this->file->office_source,
            'classification' => 'Standard',
            'date_released' => $this->file->date_released?->format('M d, Y'),
        ];
    }

    public function print()
    {
        // Placeholder for print functionality
        session()->flash('message', 'Print functionality coming soon!');
    }

    public function generateQr()
    {
        if ($this->file) {
            $this->file->generateQrCode();
            session()->flash('message', 'QR Code generated successfully!');
        }
    }

    public function generateBarcode()
    {
        if ($this->file) {
            $this->file->generateBarcode();
            session()->flash('message', 'Barcode generated successfully!');
        }
    }

    public function render()
    {
        return view('livewire.main.attach-qr');
    }
}
