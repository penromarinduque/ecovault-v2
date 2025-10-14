<?php

namespace App\Livewire\Main;

use App\Models\DocClassification;
use App\Models\File;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;

class UploadFile extends Component
{
    use WithFileUploads;

    protected $listeners = ['uploadFile' => 'uploadFile'];

    public $folder_id = null;
    public $classifications = [];
    #[Validate('required|max:5000', as: 'document title')]
    public $name = null;
    #[Validate('required|max:1000')]
    public $office_source = null;
    #[Validate('required|max:100')]
    public $control_no = null;
    #[Validate('required|exists:doc_classifications,id')]
    public $classification = null;
    #[Validate('required|date')]
    public $date_released = null;
    #[Validate('required|file')]
    public $document = null;

    public function uploadFile($folder_id)
    {
        $this->reset(['name', 'control_no', 'classification', 'date_released']);
        $this->resetErrorBag();
        $this->folder_id = $folder_id;
        $this->dispatch('show-upload-file-modal');
    }

    public function mount() {
        $this->classifications = DocClassification::all();
    }

    public function saveFile() {
        $this->validate();
        $file_name = Str::random(10).uniqid().".".$this->document->getClientOriginalExtension();
        File::create([
            'name' => $this->name,
            'folder_id' => $this->folder_id,
            'doc_control_no' => $this->control_no,
            'office_source' => $this->office_source,
            'date_released' => $this->date_released,
            'doc_classification_id' => $this->classification,
            'file_type' => $this->document->getClientOriginalExtension(),
            'file_name' => $file_name
        ]);
        $this->document->storeAs('/uploads/', $file_name);
        notyf()->position('y', 'top')->success('File uploaded successfully!');
        $this->dispatch('hide-upload-file-modal');
        $this->dispatch('refresh-folders');
    }

    public function render()
    {
        return view('livewire.main.upload-file');
    }
}
