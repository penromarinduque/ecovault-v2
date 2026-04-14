<?php

namespace App\Livewire\Main;

use App\Models\FileLog;
use Livewire\Component;

class ViewLogs extends Component
{
    protected $listeners = ['viewLogs' => 'viewLogs'];
    public $file_id;
    public $logs = [];

    public function viewLogs($file_id)
    {
        $this->dispatch('show-view-logs-modal');
        $this->file_id = $file_id;
        $this->logs = FileLog::where('file_id', $file_id)->orderBy('created_at', 'desc')->get();
    }

    public function render()
    {
        return view('livewire.main.view-logs');
    }
}
