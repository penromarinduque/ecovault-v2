<div class="" >
    <div class="modal fade" id="editFileModal" wire:ignore.self>
        <div class="modal-dialog modal-lg">
            <form class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editFileModalLabel">Edit File</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mb-2">
                        <label for="name" class="form-label">Document Title</label>
                        <input type="text" class="form-control" id="name" wire:model.live="name">
                        @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-2">
                        <label for="office_source" class="form-label">Office Source</label>
                        <input type="text" class="form-control" id="office_source" wire:model.live="office_source">
                        @error('office_source') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-2">
                        <label for="control_no" class="form-label">Control No.</label>
                        <input type="text" class="form-control" id="control_no" wire:model.live="control_no">
                        @error('control_no') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-2">
                        <label for="classification" class="form-label">Classification</label>
                        <select type="text" name="classification" class="form-control" id="classification" wire:model.live="classification">
                            <option value="">-Select Classification-</option>
                            @foreach ($classifications as $class)
                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                            @endforeach
                        </select>
                        @error('classification') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-2">
                        <label for="date_released" class="form-label">Date Released</label>
                        <input type="date" class="form-control" id="date_released" wire:model.live.debounce.300ms="date_released">
                        @error('date_released') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-2">
                        <label for="document" class="form-label">Replace Attachment</label>
                        <input type="file" accept="application/pdf,image/jpg,image/jpeg,image/png" class="form-control" id="document" wire:model="document">
                        @error('document') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" type="button" wire:loading.attr="disabled" wire:click="saveFile" wire:target="office_source,control_no,classification,date_released,document">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@script
<script>
    $wire.on('show-edit-file-modal', () => {
        $('#editFileModal').modal('show');
    });
    $wire.on('hide-edit-file-modal', () => {
        $('#editFileModal').modal('hide');
    });
</script>
@endscript
