<div class="" >
    <style>
        .nav-item:hover{
            border-radius: 5px;
            background-color: aliceblue;
        }
    </style>
    <div class="modal fade " id="moveFileModal" wire:ignore.self>
        <div class="modal-dialog">
            <form class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="moveFileModalLabel">Move File</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
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
                                <a class="nav-link " href="#" wire:click="openFolder({{ $item->id }})">{{ $item->name }}</a>
                            </li>
                        @empty
                            <li>No folders found</li>
                        @endforelse
                    </ul>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" type="button" wire:loading.attr="disabled" wire:click="saveFileLocation" wire:target="openFolder" wire:confirm="Are you sure you want to move this file?">Move</button>
                </div>
            </form>
        </div>
    </div>
</div>
@script
<script>
    $wire.on('show-move-file-modal', () => {
        $('#moveFileModal').modal('show');
    });
    $wire.on('hide-move-file-modal', () => {
        $('#moveFileModal').modal('hide');
    });
</script>
@endscript
