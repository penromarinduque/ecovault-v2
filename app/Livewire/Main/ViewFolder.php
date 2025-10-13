<?php

namespace App\Livewire\Main;

use App\Models\Folder;
use App\Models\MainFolder;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Url;
use Livewire\Component;

class ViewFolder extends Component
{
    // use Withou
    protected $listeners = ['refresh-folders'];

    public $main_folder_id;
    public $main_folder;

    #[Url]
    public $folder_id = '';
    public $folder;

    public function navigateTo($folder_id) {
        $this->folder_id = $folder_id;
        $this->folder = Folder::where('id', $folder_id)->with('parentFolder')->first();
        Log::info($this->folder);
    }

    public function addFolder($main_folder_id, ) 
    {
        $this->dispatch('addFolder', main_folder_id: $main_folder_id, parent_folder_id: $this->folder_id);
    }

    public function mount($main_folder_id)
    {
        $this->main_folder_id = $main_folder_id;
        $this->main_folder = MainFolder::find($main_folder_id);
        $this->folder = $this->folder_id ? Folder::where('id', $this->folder_id)->with('parentFolder')->first() : null;
        Log::info($this->folder);

    }
    
    public function render()
    {
        // add gate here
        $folders = $this->folder_id ? Folder::query()->where('parent_folder_id', $this->folder_id)->get() : Folder::query()->where('parent_folder_id', null)->where('main_folder_id', $this->main_folder_id)->get();
        return view('livewire.main.view-folder', compact('folders'));
    }
}
