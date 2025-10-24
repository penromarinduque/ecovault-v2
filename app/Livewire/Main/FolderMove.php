<?php

namespace App\Livewire\Main;

use App\Models\Folder;
use App\Models\MainFolder;
use Livewire\Component;

class FolderMove extends Component
{
    protected $listeners = [
        "moveFolder" => "moveFolder"
    ];
    public $main_folder_id;
    public $main_folder = null;
    public $folder = null;
    public $to_move;
    public $folders = [];

    public function moveFolder($folder_id, $main_folder_id) {
        $this->authorize('create', Folder::class);
        $this->to_move = Folder::find($folder_id);
        $this->folders = Folder::query()->where(['main_folder_id' => $main_folder_id, 'parent_folder_id' => null])->get();
        $this->main_folder_id = $main_folder_id;
        $this->main_folder = MainFolder::find($main_folder_id);
        $this->dispatch('show-move-folder-modal');
    }

    public function openFolder($folderId) 
    {
        $this->authorize('create', Folder::class);
        if($folderId == null) 
        {
            $this->folder = null;
            $this->folders = Folder::query()->where(['parent_folder_id' => null, 'main_folder_id' => $this->main_folder_id])->get();
            return;
        }
        $this->folder = Folder::find($folderId);
        $this->folders = Folder::query()->where(['parent_folder_id' => $folderId])->get();
    }

    public function saveFolderLocation() {
        $this->authorize('create', Folder::class);
        if($this->folder == null) {
            $this->to_move->parent_folder_id = null;
            $this->to_move->main_folder_id = $this->main_folder_id;
            $this->to_move->save();
            $this->dispatch('hide-move-folder-modal');
            $this->dispatch('refresh-folders');
            notyf()->position('y', 'top')->success('Folder moved successfully!');
            return;
        }
        $this->to_move->parent_folder_id = $this->folder->id;
        $this->to_move->save();
        $this->dispatch('hide-move-folder-modal');
        $this->dispatch('refresh-folders');
        notyf()->position('y', 'top')->success('Folder moved successfully!');
    }

    public function mount() {
        // dd($this->main_folder_id);
    }

    public function render()
    {
        return view('livewire.main.folder-move');
    }
}
