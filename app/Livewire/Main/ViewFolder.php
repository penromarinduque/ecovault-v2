<?php

namespace App\Livewire\Main;

use App\Models\File;
use App\Models\Folder;
use App\Models\MainFolder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class ViewFolder extends Component
{
    use WithoutUrlPagination, WithPagination;
    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['refresh-folders'];

    public $main_folder_id;
    public $main_folder;

    #[Url]
    public $folder_id = '';
    public $folder;
    public $search = '';

    public function navigateTo($folder_id) {
        $this->folder_id = $folder_id;
        $this->folder = Folder::where('id', $folder_id)->with('parentFolder')->first();
        $this->authorize('view', $this->folder);
        Log::info($this->folder);
        
    }

    public function downloadFile($file_id) {
        $file = File::find($file_id);
        $this->authorize('view', $file);
        return Storage::download('/uploads/'.$file->file_name, $file->name);
    }

    public function deleteFile($file_id) {
        $file = File::find($file_id);
        $this->authorize('delete', $file);
        Storage::delete('/uploads/'.$file->file_name);
        $file->delete();
    }

    public function previewFile($file_id)
    {
        $file = File::findOrFail($file_id);
        $this->authorize('view', $file);
        $path = 'uploads/' . $file->file_name;

        if (!Storage::exists($path)) {
            abort(404, 'File not found.');
        }

        $mimeType = Storage::mimeType($path);

        return response()->file(
            Storage::path($path),
            [
                'Content-Type' => $mimeType,
                'Content-Disposition' => 'inline; filename="' . $file->name . '"',
            ]
        );
    }

    public function addFolder($main_folder_id, ) 
    {
        $this->dispatch('addFolder', main_folder_id: $main_folder_id, parent_folder_id: $this->folder_id);
    }

    public function deleteFolder($folder_id) {
        try {
            DB::transaction(function () use ($folder_id) {
                $folder = Folder::find($folder_id);
                $this->authorize('delete', $folder);
                $folder->deleteWithChildren();
                notyf()->position('y', 'top')->success('Folder deleted successfully!');
            });
        } catch (\Throwable $th) {
            notyf()->position('y', 'top')->error('An unexpected error occured while deleting the folder. Please try again.');
            Log::error($th);
            return;
        }
    }

    public function uploadFile($folder_id)
    {
        $this->dispatch('uploadFile', folder_id: $folder_id);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function renameFolder($folder_id)
    {
        $this->dispatch('renameFolder', folder_id: $folder_id);
    }

    public function editFile($file_id)
    {
        $this->dispatch('editFile', file_id: $file_id);
    }

    public function moveFolder($folder_id, $main_folder_id) 
    {
        $this->main_folder_id = $main_folder_id;
        $this->dispatch('moveFolder', folder_id: $folder_id, main_folder_id: $main_folder_id);
    }

    public function moveFile($file_id) 
    {
        $this->dispatch('moveFile', file_id: $file_id);
    }

    public function shareFile($file_id)
    {

    }

    public function shareFolder($folder_id)
    {
        $this->dispatch('shareFolder', folder_id: $folder_id);
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
        if($this->folder) {
            $this->authorize('view', $this->folder);
        }
        $folders = $this->folder_id ? Folder::query()->where('parent_folder_id', $this->folder_id)->get() : Folder::query()->where('parent_folder_id', null)->where('main_folder_id', $this->main_folder_id)->get();
        $files = $this->folder_id ? File::query()->where('folder_id', $this->folder_id)->where('name', 'like', '%' . $this->search . '%')->paginate(10) : [];
        return view('livewire.main.view-folder', compact('folders', 'files'));
    }
}
