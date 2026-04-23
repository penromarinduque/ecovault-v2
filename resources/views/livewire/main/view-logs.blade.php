<div class="" >
    <div class="modal fade" id="viewLogsModal" wire:ignore.self>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewLogsModalLabel">View Logs</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @forelse ($logs as $log)
                        <div class="card bg-light border-start mb-1">
                            <div class="card-body">
                                {{ $log->message }}
                                <div class="small text-muted">
                                    {{ $log->created_at->diffForHumans() }}
                                </div>
                            </div>
                        </div>
                    @empty
                        <div>No logs found</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

@script
<script>
    $wire.on('show-view-logs-modal', () => {
        $('#viewLogsModal').modal('show');
    });
    $wire.on('hide-view-logs-modal', () => {
        $('#viewLogsModal').modal('hide');
    });
</script>
@endscript
