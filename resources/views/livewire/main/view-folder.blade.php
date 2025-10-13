<div>
    @livewire('main.folder-create')
    <h3>{{ $main_folder->name }}</h3>
    <div class="row mb-3 justify-content-end ">
        @if (auth()->user()->isAdmin())
        <div class="col-auto pr-0">
            <button class="btn btn-primary " wire:click="addFolder({{ $main_folder->id, $folder_id }})">
                <i class="fas fa-plus"></i> Create New Folder
            </button>
        </div>
        @endif
        <div class="col-auto pr-0">
            <button class="btn btn-primary " >
                <i class="fas fa-upload"></i> Updload File
            </button>
        </div>
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
                <div class="card-body">
                    <div class="row align-items-center justify-content-between">
                        <a class="col" href="{{ route('main.folders.show', ['folder_id' => $folder->id, 'main_folder_id' => $main_folder_id]) }}" wire:navigate>
                            <i class="fas fa-folder mr-2" style="color: {{$folder->color}};"></i>
                            {{$folder->name}}
                        </a>
                        @if (auth()->user()->isAdmin())
                        <div class="dropdown col-auto">
                            <button class="btn btn-light dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-ellipsis-h"></i>
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="#"><i class="fas fa-pen mr-2"></i>Rename</a>
                                <a class="dropdown-item text-danger" href="#" wire:confirm="Are you sure you want to delete this folder? All of the contents will be deleted." wire:loading.attr="disabled" wire:click="deleteFolder({{ $folder->id }})"><i class="fas fa-trash-alt mr-2"></i>Delete</a>
                            </div>
                        </div>
                        @endif
                    </div>
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
</div>
