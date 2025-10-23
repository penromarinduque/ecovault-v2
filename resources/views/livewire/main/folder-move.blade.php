<div class="" >
    <style>
        .nav-item:hover{
            border-radius: 5px;
            background-color: aliceblue;
        }
    </style>
    <div class="modal fade " id="moveFolderModal" wire:ignore.self>
        <div class="modal-dialog">
            <form class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="moveFolderModalLabel">Move Folder</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" wire:model="folder_id">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            @if ($main_folder)
                                <li class="breadcrumb-item " ><a href="#" wire:click="openFolder(null)">{{ $main_folder->name }}</a></li>
                            @endif
                            @if ($folder)
                                @php
                                    $breadcrumbs = $folder->getBreadcrumbData();
                                @endphp
                                @foreach ($breadcrumbs as $key => $breadcrumb)
                                    @if ($key == count($breadcrumbs) - 1 )
                                    <li class="breadcrumb-item active" >{{ $breadcrumb->name }}</li>
                                    @else
                                    <li class="breadcrumb-item " ><a href="#" wire:click="openFolder({{ $breadcrumb->id }})">{{ $breadcrumb->name }}</a></li>
                                    @endif
                                @endforeach
                            @endif
                        </ol>
                    </nav>
                    <ul class="nav flex-column nav-pills">
                        @forelse ($folders as $item)
                            <li class="nav-item">
                                <a class="nav-link {{ $item->id == $to_move->id ? 'disabled' : '' }}" href="#" @if($item->id != $to_move->id) wire:click="openFolder({{ $item->id }})" @endif>{{ $item->name }}</a>
                            </li>
                        @empty
                            <li>No folders found</li>
                        @endforelse
                    </ul>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" type="button" wire:loading.attr="disabled" wire:click="saveFolderLocation" wire:target="openFolder" wire:confirm="Are you sure you want to move this folder?">Move</button>
                </div>
            </form>
        </div>
    </div>
</div>
@script
<script>
    $wire.on('show-move-folder-modal', () => {
        $('#moveFolderModal').modal('show');
    });
    $wire.on('hide-move-folder-modal', () => {
        $('#moveFolderModal').modal('hide');
    });
</script>
@endscript
