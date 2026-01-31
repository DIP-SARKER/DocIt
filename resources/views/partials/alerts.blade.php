<div class="alert-container">
    @foreach (['success', 'error', 'warning', 'info'] as $type)
        @if (session($type))
            <div class="alert alert-{{ $type }}">
                <i
                    class="fas
                    @if ($type === 'success') fa-check-circle
                    @elseif ($type === 'error') fa-times-circle
                    @elseif ($type === 'warning') fa-exclamation-triangle
                    @else fa-info-circle @endif
                "></i>
                <span>{{ session($type) }}</span>
            </div>
        @endif
    @endforeach

    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <div class="alert alert-danger">

                <i class="fas fa-times-circle"></i>
                <span>{{ $error }}</span>
            </div>
        @endforeach
    @endif

</div>
<script>
    setTimeout(() => {
        document.querySelectorAll('.alert-container').forEach(alert => {
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 300);
        });
    }, 4000);
</script>
