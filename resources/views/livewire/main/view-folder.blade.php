<div>
    <style>
        .folder:hover {
            transition: 0.3s;
            background-color: rgba(0, 0, 0, 0.1);
        }
    </style>
    @livewire('main.move-file')
    @livewire('main.upload-file')
    @livewire('main.edit-file')
    @livewire('main.folder-create')
    @livewire('main.folder-rename')
    @livewire('main.folder-move')
    <h3>{{ $main_folder->name }}</h3>
    <div class="row mb-3 justify-content-end ">
        @if (auth()->user()->isAdmin())
        <div class="col-auto pr-0">
            <button class="btn btn-primary " wire:click="addFolder({{ $main_folder->id, $folder_id }})">
                <i class="fas fa-plus"></i> Create New Folder
            </button>
        </div>
        @endif
        @if ($folder_id)
        <div class="col-auto pr-0">
            <button class="btn btn-primary " wire:click="uploadFile({{ $folder_id }})">
                <i class="fas fa-upload"></i> Upload File
            </button>
        </div>
        @endif
    </div>
    @if ($folder_id)
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            @php
                $breadcrumbs = $folder->getBreadcrumbData();
            @endphp
            @foreach ($breadcrumbs as $key => $breadcrumb)
                @if ($key == count($breadcrumbs) - 1 )
                <li class="breadcrumb-item active" >{{ $breadcrumb->name }}</li>
                @else
                <li class="breadcrumb-item " ><a href="{{ route('main.folders.show', ['folder_id' => $breadcrumb->id, 'main_folder_id' => $main_folder_id]) }}" wire:navigate>{{ $breadcrumb->name }}</a></li>
                @endif
            @endforeach
        </ol>
    </nav>
    @endif
    <div class="row">
        @forelse ($folders as $folder)
        <div class="col-12 col-lg-4 col-sm-6">
            <div class="card" >
                <div class="row align-items-center justify-content-between m-0 flex-nowrap">
                    <a class="card-body col folder" href="{{ route('main.folders.show', ['folder_id' => $folder->id, 'main_folder_id' => $main_folder_id]) }}" wire:navigate>
                        <i class="fas fa-folder mr-2" style="color: {{$folder->color}};"></i>
                        {{$folder->name}}
                    </a>
                    @if (auth()->user()->isAdmin())
                    <div class="dropdown col-auto">
                        <button class="btn btn-light dropdown-toggle text-center" type="button" data-toggle="dropdown" aria-expanded="false">
                            {{-- <i class="fas fa-ellipsis-h"></i> --}}
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="#" wire:click="renameFolder({{ $folder->id }})" ><i class="fa fa-i-cursor mr-2" aria-hidden="true"></i>Rename</a>
                            <a class="dropdown-item" href="#" wire:click="moveFolder({{ $folder->id }}, {{ $main_folder_id }})" ><i class="fas fa-expand-arrows-alt mr-2" aria-hidden="true"></i>Move</a>
                            <a class="dropdown-item text-danger" href="#" wire:confirm="Are you sure you want to delete this folder? All of the files and subfolders will be deleted." wire:loading.attr="disabled" wire:click="deleteFolder({{ $folder->id }})"><i class="fas fa-trash-alt mr-2"></i>Delete</a>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    No Folders
                </div>
            </div>
        </div>
        @endforelse
    </div>
    <div class="card" >
        <div class="card-body">
            <input type="text" class="form-control mb-3" placeholder="Search files..." wire:model.live.debounce.300ms="search">
            @if ($folder_id)
                <div class="table-responsive" style="min-height: 800px !important;">
                    <h6 class="font-weight-bold">Files </h6>
                    <table class="table table-hover" >
                        <thead>
                            <tr>
                                <th width="30px"></th>
                                <th>File</th>
                                <th>Date Released</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($files as $file)
                                <tr>
                                    <td>
                                        <i class="{{ $file->icon }}"></i>
                                    </td>
                                    <td>{{ $file->name }}</td>
                                    <td>{{ $file->date_released->format('M d, Y') }}</td>
                                    <td>
                                        @persist("file-dropdown-{{ $file->id }}")
                                        <div class="btn-group" wire:ignore.self>
                                            <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" data-display="static" aria-expanded="false">
                                                Options
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-left">
                                                <button class="dropdown-item" type="button" wire:click="downloadFile({{ $file->id }})">Download</button>
                                                <a class="dropdown-item" target="_blank" href="{{ route('preview', ['id' => $file->id ])}}">Preview</a>
                                                <button class="dropdown-item" type="button" wire:click="moveFile({{ $file->id }})">Move</button>
                                                <button class="dropdown-item" type="button" wire:click="editFile({{ $file->id }})">Edit</button>
                                                <button class="dropdown-item text-danger" type="button" wire:confirm="Are you sure you want to delete this file?" wire:loading.attr="disabled" wire:click="deleteFile({{ $file->id }})">Delete</button>
                                            </div>
                                        </div>
                                        @endpersist
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">No files found for this folder.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    {{ $files->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
