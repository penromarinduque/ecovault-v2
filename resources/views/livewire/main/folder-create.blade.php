<div class="" >
    <div class="modal fade " id="createFolderModal" wire:ignore.self>
        <div class="modal-dialog modal-lg">
            <form class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createUserModalLabel">Create Folder</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mb-2">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" wire:model.lazy="name">
                        @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-2" style="max-width: 50px">
                        <label for="color" class="form-label">Color</label>
                        <input type="color" class="" id="name" wire:model.lazy="color" value="#abcbff">
                        @error('color') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" type="button" wire:loading.attr="disabled" wire:click="saveFolder">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@script
<script>
    $wire.on('show-create-folder-modal', () => {
        $('#createFolderModal').modal('show');
    });
    $wire.on('hide-create-folder-modal', () => {
        $('#createFolderModal').modal('hide');
    });
</script>
@endscript
