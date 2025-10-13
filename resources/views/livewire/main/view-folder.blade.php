<div>
    @livewire('main.folder-create')
    <h3>{{ $main_folder->name }}</h3>
    <div class="d-flex mb-3 justify-content-end">
        <button class="btn btn-primary" wire:click="addFolder({{ $main_folder->id, $folder_id }})">
            <i class="fas fa-plus"></i> Add Folder
        </button>
    </div>
    @if ($folder_id)
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            @php
                $breadcrumbs = $folder->getBreadcrumbData();
            @endphp
            @foreach ($breadcrumbs as $key => $breadcrumb)
            <li class="breadcrumb-item {{ $key == count($breadcrumbs) - 1 ? 'active' : '' }}" >{{ $breadcrumb->name }}</li>
            @endforeach
        </ol>
    </nav>
    @endif
    <div class="row">
        @forelse ($folders as $folder)
        <div class="col-12 col-lg-4 col-sm-6">
            <div class="card" wire:click="navigateTo({{ $folder->id }})">
                <div class="card-body">
                    <i class="fas fa-folder"></i>
                    {{$folder->name}}
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
