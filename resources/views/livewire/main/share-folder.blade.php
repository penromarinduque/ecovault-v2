<div class="modal fade" id="shareFolderModal" wire:ignore.self>
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="shareFolderModalLabel">Share Folder</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="mb-2">
                    <label for="share_to" class="form-label">Share to</label>
                    <select name="share_to" id="share_to" class="form-control">
                        <option value="everyone">Everyone</option>
                        <option value="individual">Selected Viewer</option>
                    </select>
                    @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="modal-footer">
                <p class="mb-0 mr-1">{{ __('Sharing this folder will allow other users to access all the files and folders inside this folder.') }}</p>
                <button class="btn btn-primary" type="button" wire:loading.attr="disabled" wire:click="shareFolder">Share</button>
            </div>
        </div>
    </div>
</div>
@script
<script>
    $wire.on('show-share-folder-modal', () => {
        $('#shareFolderModal').modal('show');
    });
    $wire.on('hide-share-folder-modal', () => {
        $('#shareFolderModal').modal('hide');
    });
</script>
@endscript
