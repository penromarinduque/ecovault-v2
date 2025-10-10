<!-- Toast Container -->
{{-- <div class="toast-container position-fixed top-0 end-0 p-3">
    <div id="customToast" class="toast align-items-center text-bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                Update failed! Please try again.
            </div>
            <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div> --}}

<div class="alert alert-success d-none" id="customToast"> 
    <span class="toast-message"></span>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
</div>
<!-- JavaScript to Show Toast -->
<script type="text/javascript">
    document.addEventListener('livewire:load', function () {
        Livewire.on('show-toast', (type, message) => {
            console.log(type, message);
            showToast(type, message);

        });
    });

    function showToast(color, message) {
        var $toast = document.getElementById('customToast');
        if (!$toast) return; // Prevent errors if the toast element is missing

        $toast.attr('class') = `alert alert-${color}`;
        $('#customToast .toast-message').text(message);
        $('#customToast').show();
    }
</script>