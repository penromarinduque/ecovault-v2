<?php

namespace App\Livewire\Main;

use App\Models\Folder;
use Livewire\Attributes\Validate;
use Livewire\Component;

class FolderRename extends Component
{
    protected $listeners = [
        'renameFolder' => 'renameFolder'
    ];
    public $folder = null;
    #[Validate('required|string|max:255')]
    public $name;

    public function renameFolder($folder_id) {
        $this->folder = Folder::find($folder_id);
        $this->authorize('update', $this->folder);
        $this->name = $this->folder->name;
        $this->dispatch('show-rename-folder-modal');    
    }

    public function saveFolder() {
        $this->validate();
        $this->authorize('update', $this->folder);
        $this->folder->name = $this->name;
        $this->folder->save();
        $this->dispatch('hide-rename-folder-modal');
        $this->dispatch('refresh-folders');
        notyf()->position('y', 'top')->success('Folder renamed successfully!');
    }

    public function render()
    {
        return view('livewire.main.folder-rename');
    }

}
