<?php

namespace App\Livewire\Main;

use App\Models\DocClassification;
use App\Models\File;
use App\Models\Folder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;

class UploadFile extends Component
{
    use WithFileUploads;

    protected $listeners = ['uploadFile' => 'uploadFile'];

    public $folder_id = null;
    public $folder = null;
    public $classifications = [];
    #[Validate('required|max:5000', as: 'document title')]
    public $name = null;
    #[Validate('required|max:1000')]
    public $office_source = "DENR - PENRO Marinduque";
    #[Validate('required|max:255')]
    public $released_to = null;
    #[Validate('nullable|max:100')]
    public $control_no = null;
    #[Validate('required|max:255')]
    public $barcode_no = null;
    #[Validate('required|max:255')]
    public $action = null;
    #[Validate('required|exists:doc_classifications,id')]
    public $classification = null;
    #[Validate('required|date')]
    public $date_released = null;
    #[Validate('required|file')]
    public $document = null;
    public $order_no = 0;

    public function uploadFile($folder_id)
    {
        $this->reset(['name', 'control_no', 'classification', 'date_released', 'document', 'office_source', 'released_to', 'action']);
        $this->resetErrorBag();
        $this->folder_id = $folder_id;
        $this->folder = Folder::find($folder_id);
        $this->authorize('upload-file', [Folder::class, $this->folder->main_folder_id]);
        $this->dispatch('show-upload-file-modal');
    }

    public function mount() {
        $this->classifications = DocClassification::all();
        Log::info($this->classifications);
    }

    public function updatingDateReleased($value) {
        $year = Carbon::parse($value)->year;
        $month = Carbon::parse($value)->month;
        $file = File::whereYear("date_released", $year)->orderBy("order_no", "desc")->first();
        if($file) {
            $this->order_no = $file->order_no + 1;
        } else {
            $this->order_no = 1;
        }
        $barcode_no = "MAR{$year}" . str_pad($month, 2, '0', STR_PAD_LEFT) . str_pad($this->order_no, 4, '0', STR_PAD_LEFT);
        $this->barcode_no = $barcode_no;
    }

    public function generateBarcodeNo() {
        $this->validate([
            'date_released' => 'required|date'
        ]);
        $year = Carbon::parse($this->date_released)->year;
        $month = Carbon::parse($this->date_released)->month;
        $file = File::whereYear("date_released", $year)->orderBy("order_no", "desc")->first();
        if($file) {
            $this->order_no = $file->order_no + 1;
        } else {
            $this->order_no = 1;
        }
        $barcode_no = "MAR{$year}" . str_pad($month, 2, '0', STR_PAD_LEFT) . str_pad($this->order_no, 4, '0', STR_PAD_LEFT);
        $this->barcode_no = $barcode_no;
    }

    public function saveFile() {
        $this->authorize('upload-file', [Folder::class, $this->folder->main_folder_id]);
        $this->validate();
        if(File::where(['name' => $this->name, 'folder_id' => $this->folder_id])->exists())
        {
            notyf()->position('y', 'top')->error('File already exists!');
            return;
        }
        $file_name = Str::random(10).uniqid().".".$this->document->getClientOriginalExtension();
        $file = File::create([
            'name' => $this->name,
            'folder_id' => $this->folder_id,
            'released_to' => $this->released_to,
            'action' => $this->action,
            'doc_control_no' => $this->control_no,
            'office_source' => $this->office_source,
            'date_released' => $this->date_released,
            'doc_classification_id' => $this->classification,
            'barcode_no' => $this->barcode_no,
            "released_by" => auth()->user()->id,
            'file_type' => $this->document->getClientOriginalExtension(),
            'file_name' => $file_name,
            'order_no' => $this->order_no
        ]);
        $file->createLog(auth()->user()->name.' created the file: '.$file->name, auth()->user()->id);
        $file->storeAndEmbedQr($this->document);
        notyf()->position('y', 'top')->success('File uploaded successfully!');
        $this->dispatch('hide-upload-file-modal');
        $this->dispatch('refresh-folders');
    }
    

    public function render()
    {
        return view('livewire.main.upload-file');
    }
}
