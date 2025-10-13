<?php

namespace App\Livewire\Main;

use App\Models\Folder;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Validate;
use Livewire\Component;

class FolderCreate extends Component
{
    protected $listeners = [
        'addFolder' => 'addFolder'
    ];

    public $main_folder_id;
    public $parent_folder_id;
    
    #[Validate('required|max:5000')]
    public $name;
    #[Validate('required|max:20')]
    public $color;

    public function addFolder($main_folder_id, $parent_folder_id) 
    {
        Log::info('addFolder', [$main_folder_id, $parent_folder_id]);
        $this->main_folder_id = $main_folder_id;
        $this->parent_folder_id = $parent_folder_id;
        $this->reset(['name', 'color']);
        $this->resetErrorBag();
        $this->dispatch('show-create-folder-modal');
    }

    public function saveFolder(){
        $this->validate();
        Folder::create([
            'name' => $this->name,
            'color' => $this->color,
            'main_folder_id' => $this->main_folder_id,
            'parent_folder_id' => $this->parent_folder_id ? $this->parent_folder_id : null
        ]);
        notyf()->position('y', 'top')->success('Folder created successfully!');
        $this->dispatch('hide-create-folder-modal');
        $this->dispatch('refresh-folders');
    }

    public function render()
    {
        return view('livewire.main.folder-create');
    }
}
