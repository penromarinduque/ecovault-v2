<div class="">
    <div class="alert alert-success d-none" id="customToast"> 
        <span class="toast-message"></span>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
    </div>
</div>
<!-- JavaScript to Show Toast -->
@section('scripts')
<script type="text/javascript">
    function showToast(color, message) {
        var $toast = $('#customToast');
        if (!$toast) return; // Prevent errors if the toast element is missing

        $toast.attr('class', `alert alert-${color}`);
        $('#customToast .toast-message').text(message);
        $('#customToast').show();
    }
</script>
@endsection