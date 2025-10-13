<?php

namespace App\Livewire\Components;

use App\Models\MainFolder;
use Livewire\Component;

class MainFolderNav extends Component
{
    public function render()
    {
        $mainFolders = MainFolder::all();
        return view('livewire.components.main-folder-nav', compact('mainFolders'));
    }
}
