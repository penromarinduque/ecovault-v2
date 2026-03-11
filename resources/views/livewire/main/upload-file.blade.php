<div class="" >
    <div class="modal fade " id="uploadFileModal" wire:ignore.self>
        <div class="modal-dialog modal-lg">
            <form class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadFileModalLabel">Upload File</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mb-2">
                        <label for="name" class="form-label">Document Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" wire:model.live="name">
                        @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-2">
                        <label for="office_source" class="form-label">Office Source <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="office_source" wire:model.live="office_source">
                        @error('office_source') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-2">
                        <label for="date_released" class="form-label">Date Released <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="date_released" wire:model.lazy="date_released">
                        @error('date_released') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-2">
                        <label for="released_to" class="form-label">Released To <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="released_to" wire:model.live="released_to">
                        @error('released_to') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-2">
                        <label for="barcode_no" class="form-label">Barcode No. <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="barcode_no" placeholder="Barcode No." wire:model.live="barcode_no">
                            <button class="input-group-text" id="basic-addon1" type="button" wire:click="generateBarcodeNo">
                                <i class="fa-solid fa-arrow-rotate-right"></i>
                            </button>
                        </div>
                        @error('barcode_no') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-2">
                        <label for="control_no" class="form-label">Control No.</label>
                        <input type="text" class="form-control" id="control_no" wire:model.live="control_no">
                        @error('control_no') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-2">
                        <label for="classification" class="form-label">Classification <span class="text-danger">*</span></label>
                        <select type="text" name="classification" class="form-control" id="classification" wire:model.live="classification">
                            <option value="">-Select Classification-</option>
                            @foreach ($classifications as $class)
                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                            @endforeach
                        </select>
                        @error('classification') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-2">
                        <label for="document" class="form-label">Select File <span class="text-danger">*</span></label>
                        <input type="file" accept="application/pdf,image/jpg,image/jpeg,image/png" class="form-control" id="document" wire:model.lazy="document">
                        @error('document') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-2">
                        <label for="action" class="form-label">Action <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="action" wire:model.live="action">
                        @error('action') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" type="button" wire:loading.attr="disabled" wire:click="saveFile">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@script
<script>
    $wire.on('show-upload-file-modal', () => {
        $('#uploadFileModal').modal('show');
    });
    $wire.on('hide-upload-file-modal', () => {
        $('#uploadFileModal').modal('hide');
    });
</script>
@endscript
