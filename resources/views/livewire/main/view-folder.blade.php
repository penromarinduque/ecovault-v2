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
    @livewire('main.share-folder')
    @livewire('main.view-logs')
    <h3>{{ $main_folder->name }}</h3>
    <div class="row mb-3 justify-content-end ">
        @if (auth()->user()->isAdmin())
        <div class="col-auto pr-0">
            <button class="btn btn-primary " wire:click="addFolder({{ $main_folder->id, $folder_id }})">
                <i class="fas fa-plus"></i> Create New Folder
            </button>
        </div>
        @endif
        @if ($folder_id && auth()->user()->can("upload-file", [App\Models\Folder::class, $main_folder->id]))
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

    <div class="d-flex justify-content-end mb-3">
        <form class="form-inline">
            <label for="fileSortBy" class="form-label mr-2">Sort:</label>
            <select name="folderSortOrder" id="folderSortOrder" class="form-control"  wire:model.live="folderSortOrder">
                <option value="desc">-Sort Order-</option>
                <option value="asc">Ascending</option>
                <option value="desc">Descending</option>
            </select>
        </form>
    </div>

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
                    <div x-data="{ open: false }" class="p-2" wire:ignore.self>
                        <!-- Button -->
                        <button @click="open = !open" class=" btn btn-light ">
                            <i class="fas fa-ellipsis-h"></i>
                        </button>

                        <!-- Dropdown menu -->
                        <div
                            style="z-index: 6;"
                            x-show="open"
                            @click.away="open = false"
                            x-transition
                            class="position-absolute right-0 mt-2 w-48 bg-white border rounded shadow-lg"
                        >
                            <a class="dropdown-item" href="#" wire:click="renameFolder({{ $folder->id }})" ><i class="fa fa-i-cursor mr-2" aria-hidden="true"></i>Rename</a>
                            <a class="dropdown-item" href="#" wire:click="moveFolder({{ $folder->id }}, {{ $main_folder_id }})" ><i class="fas fa-expand-arrows-alt mr-2" aria-hidden="true"></i>Move</a>
                            <a class="dropdown-item" href="#" wire:click="shareFolder({{ $folder->id }})"><i class="fa fa-share mr-2" aria-hidden="true"></i>Share</a>
                            <a class="dropdown-item text-danger" href="#" wire:confirm="Are you sure you want to delete this folder? All of the files and subfolders will be deleted." wire:loading.attr="disabled" wire:click="deleteFolder({{ $folder->id }})"><i class="fas fa-trash-alt mr-2"></i>Delete</a>
                        </div>
                    </div>

                    {{-- <div class="dropdown col-auto">
                        <button class="btn btn-light dropdown-toggle text-center" type="button" data-toggle="dropdown" aria-expanded="false">
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="#" wire:click="renameFolder({{ $folder->id }})" ><i class="fa fa-i-cursor mr-2" aria-hidden="true"></i>Rename</a>
                            <a class="dropdown-item" href="#" wire:click="moveFolder({{ $folder->id }}, {{ $main_folder_id }})" ><i class="fas fa-expand-arrows-alt mr-2" aria-hidden="true"></i>Move</a>
                            <a class="dropdown-item" href="#" wire:click="shareFolder({{ $folder->id }})"><i class="fa fa-share mr-2" aria-hidden="true"></i>Share</a>
                            <a class="dropdown-item text-danger" href="#" wire:confirm="Are you sure you want to delete this folder? All of the files and subfolders will be deleted." wire:loading.attr="disabled" wire:click="deleteFolder({{ $folder->id }})"><i class="fas fa-trash-alt mr-2"></i>Delete</a>
                        </div>
                    </div> --}}
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
    @if ($folder_id)
    <div class="card" >
        <div class="card-body">
            <div class="d-flex justify-content-end mb-3">
                <form class="form-inline">
                    <label for="fileSortBy" class="form-label mr-2">Sort by:</label>
                    <select name="fileSortBy" id="fileSortBy" class="form-control" wire:model.live="fileSortBy">
                        <option value="name">-Sort By-</option>
                        <option value="name">Title</option>
                        <option value="date_released">Date Released</option>
                    </select>
                    &nbsp;&nbsp;&nbsp;
                    <select name="fileSortOrder" id="fileSortOrder" class="form-control"  wire:model.live="fileSortOrder">
                        <option value="asc">-Sort Order-</option>
                        <option value="asc">Ascending</option>
                        <option value="desc">Descending</option>
                    </select>
                </form>
            </div>

            <input type="text" class="form-control mb-3" placeholder="Search files..." wire:model.live.debounce.300ms="search" />
        
            <div class="table-responsive" style="min-height: 800px !important;">
                <h6 class="font-weight-bold">Files </h6>
                <table class="table table-hover" >
                    <thead>
                        <tr>
                            <th width="30px"></th>
                            <th>File</th>
                            <th>Date Released</th>
                            <th>QR & Barcode</th>
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
                                    <div class="d-flex align-items-center" wire:ignore.self>
                                        <div class="btn-group" role="group" >
                                            <a type="button" class="btn btn-primary" title="QR Code" data-toggle="tooltip" target="_blank" href="https://api.qrcode-monkey.com/qr/custom?data={{ route("validate-qr", ["id" => strtr(base64_encode($file->barcode_no), '+/=', '-_,')]) }}&config={%22logo%22:%22c7e3dafa91a9a2806b38cf5992939868cefc6171.svg%22}">
                                                <i class="fa-solid fa-qrcode"></i>
                                            </a>
                                            <a type="button" class="btn btn-primary" title="Barcode" data-toggle="tooltip" href="https://barcodeapi.org/api/128/{{ $file->barcode_no }}" target="_blank">
                                                <i class="fa-solid fa-barcode"></i>
                                            </a>
                                            <a type="button" class="btn btn-primary" title="Barcode & QR Code" data-toggle="tooltip" href="{{ route('main.get-qr-and-barcode', ['id' => $file->id]) }}" target="_blank">
                                                <i class="fa-solid fa-barcode"></i> +
                                                <i class="fa-solid fa-qrcode"></i>
                                            </a>
                                            <a type="button" class="btn btn-primary" title="Barcode & QR Code" data-toggle="tooltip" href="{{ route('main.folders.attachqr', ['main_file_id' => $file->id]) }}" target="_blank">
                                                <i class="fa-solid fa-qrcode"></i> Attach QR Code
                                            </a>
                                        </div>
                                    </div>
                                </td>
                                <td >
                                    <div class="p-0">
                                        <button class=" btn-outline-primary btn btn-sm" type="button" wire:click="downloadFile({{ $file->id }})" title="Download" data-toggle="tooltip" >
                                            <i class="fa-solid fa-download"></i>
                                        </button>
                                        <a class="btn btn-outline-primary btn-sm" target="_blank" href="{{ route('validate-qr', ['id' => strtr(base64_encode($file->barcode_no), '+/=', '-_,') ])}}" title="Preview" data-toggle="tooltip" >
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        @can('upload-file', [App\Models\Folder::class, $main_folder_id])
                                            <button class=" btn btn-outline-primary btn-sm" type="button" wire:click="moveFile({{ $file->id }})" title="Move" data-toggle="tooltip" >
                                                <i class="fa-solid fa-arrows-up-down-left-right"></i>
                                            </button>
                                        @endcan
                                        <button class="btn btn-outline-primary btn-sm" type="button" wire:click="editFile({{ $file->id }})" title="Edit" data-toggle="tooltip" >
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>
                                        <button class="btn btn-outline-primary btn-sm" type="button" wire:click="viewLogs({{ $file->id }})" title="View Logs" data-toggle="tooltip" >
                                            <i class="fa-solid fa-timeline"></i>
                                        </button>
                                        {{-- <a class="dropdown-item" href="{{ route('main.folders.attachqr', ['main_file_id' => $file->id]) }}">
                                            <i class="fa-solid fa-qrcode"></i> Attach QR Code
                                        </a> --}}
                                        @can('upload-file', [App\Models\Folder::class, $main_folder_id])
                                            <button class="btn btn-danger btn-sm" type="button" wire:confirm="Are you sure you want to delete this file?" wire:loading.attr="disabled" wire:click="deleteFile({{ $file->id }})" title="Delete" data-toggle="tooltip" >
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        @endcan 
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No files found for this folder.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                {{ $files->links() }}
            </div>
        </div>
    </div>
    @endif
</div>
