<?php

namespace App\Livewire\Main;

use App\Models\File;
use App\Models\Folder;
use App\Models\MainFolder;
use Livewire\Component;

class MoveFile extends Component
{
    protected $listeners = ['moveFile' => 'moveFile'];
    public $file = null;
    public $folder = null;
    public $main_folder = null;
    public $folders = [];

    public function moveFile($file_id) 
    {
        $this->file = File::find($file_id);    
        $this->authorize('update', $this->file);
        $this->main_folder = MainFolder::find($this->file->folder->main_folder_id);
        $this->folders = Folder::query()->where(['parent_folder_id' => null, 'main_folder_id' => $this->main_folder->id])->get();
        $this->dispatch('show-move-file-modal');
    }

    public function openFolder($folderId) 
    {
        if($folderId == null) 
        {
            $this->folder = null;
            $this->folders = Folder::query()->where(['parent_folder_id' => null, 'main_folder_id' => $this->main_folder->id])->get();
            return;
        }
        $this->folder = Folder::find($folderId);
        $this->authorize('view', $this->folder);
        $this->folders = Folder::query()->where(['parent_folder_id' => $folderId])->get();
    }

    public function saveFileLocation()
    {
        if($this->folder == null) {
            notyf()->position('y', 'top')->error('No Folder Selected!');
            return;
        }
        $this->authorize('update', $this->file);
        $this->file->folder_id = $this->folder->id;
        $this->file->save();
        $this->dispatch('hide-move-file-modal');
        $this->dispatch('refresh-folders');
        notyf()->position('y', 'top')->success('File moved successfully!');
    }

    public function render()
    {
        return view('livewire.main.move-file');
    }
}
