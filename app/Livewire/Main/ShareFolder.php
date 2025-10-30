<?php

namespace App\Livewire\Main;

use App\Models\Folder;
use Livewire\Component;

class ShareFolder extends Component
{
    protected $listeners = [
        'shareFolder' => 'shareFolder'
    ];

    public $folder;

    public function shareFolder($folder_id)
    {
        $this->folder = Folder::find($folder_id);
        $this->dispatch("show-share-folder-modal");
    }

    public function render()
    {
        return view('livewire.main.share-folder');
    }
}
