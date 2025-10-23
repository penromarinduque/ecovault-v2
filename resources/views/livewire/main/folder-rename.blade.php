<div class="modal fade" id="renameFolderModal" wire:ignore.self>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="renameFolderModalLabel">Rename Folder</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="mb-2">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" wire:model.live.debounce.300ms="name">
                    @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" type="button" wire:loading.attr="disabled" wire:click="saveFolder">Save</button>
            </div>
        </div>
    </div>
</div>
@script
<script>
    $wire.on('show-rename-folder-modal', () => {
        $('#renameFolderModal').modal('show');
    });
    $wire.on('hide-rename-folder-modal', () => {
        $('#renameFolderModal').modal('hide');
    });
</script>
@endscript
