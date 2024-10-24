<div class="flash-message">
    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
        @if (session()->has('alert-' . $msg))
            <div class="alert alert-{{ $msg }}">
                <a href="javascript:void(0)" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                {{ session('alert-' . $msg) }}
            </div>
        @endif
    @endforeach
    @if (session()->has('status'))
        <div class="alert alert-info">
            <a href="javascript:void(0)" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            {{ session('status') }}
        </div>
    @endif
</div>

<script>
    $(document).ready(function() {
    setTimeout(function() {
        $('.flash-message .alert').fadeOut('slow', function() {
            $(this).remove();
        });
    }, 5000);
});
</script>