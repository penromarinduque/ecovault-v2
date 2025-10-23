<?php

namespace App\Livewire\Main;

use App\Models\DocClassification;
use App\Models\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;

class EditFile extends Component
{
    use WithFileUploads;
    protected $listeners = ['editFile' => 'editFile'];
    public $classifications = [];
    public $file;
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
    #[Validate('nullable|file')]
    public $document = null;

    public function editFile($file_id)
    {
        $this->reset(['name', 'control_no', 'classification', 'date_released', 'document', 'office_source']);
        $this->file = File::find($file_id);
        $this->name = $this->file->name;
        $this->office_source = $this->file->office_source;
        $this->control_no = $this->file->doc_control_no;
        $this->classification = $this->file->doc_classification_id;
        $this->date_released = $this->file->date_released->format('Y-m-d');
        $this->name = $this->file->name;
        $this->dispatch('show-edit-file-modal');
    }

    public function saveFile() {
        try {
            $this->validate();
            if(File::where(['name' => $this->name, 'folder_id' => $this->file->folder_id])->whereNot('id', $this->file->id)->exists())   
            {
                notyf()->position('y', 'top')->error('File already exists!');
                return;
            }

            DB::transaction(function () {
                if($this->document) {
                    Storage::delete('/uploads/'.$this->file->file_name);
                    $this->file->storeAndEmbedQr($this->document);
                    notyf()->position('y', 'top')->success('New Attachment stored successfully!');
                }
                $this->file->name = $this->name;
                $this->file->office_source = $this->office_source;
                $this->file->doc_control_no = $this->control_no;
                $this->file->doc_classification_id = $this->classification;
                $this->file->date_released = $this->date_released;
                $this->file->save();
                notyf()->position('y', 'top')->success('File updated successfully!');
                $this->dispatch('hide-edit-file-modal');
                $this->dispatch('refresh-folders');
            });
        } catch (\Throwable $th) {
            notyf()->position('y', 'top')->error('An unexpected error occured while saving the file. Please try again.');
            Log::error($th);
        }
    }

    public function mount() {
        $this->classifications = DocClassification::all();
        Log::info($this->classifications);
    }

    public function render()
    {
        return view('livewire.main.edit-file');
    }
}
