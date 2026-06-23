@php
    $alerts = [
        'success' => 'success',
        'error' => 'danger',
        'warning' => 'warning',
        'info' => 'info',
    ];
@endphp

@foreach ($alerts as $sessionKey => $alertClass)
    @if (session()->has($sessionKey))
        <div id="flashMessage" class="alert alert-{{ $alertClass }} alert-dismissible fade show " role="alert">
            {{ session($sessionKey) }}
            <button type="button" class="btn-close" data-bs-dismiss="alert">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
@endforeach
{{-- <script>
    document.addEventListener('DOMContentLoaded', function() {
        const flash = document.getElementById('flashMessage');
        if (flash) {
            setTimeout(function() {
                flash.classList.remove('show');
                flash.classList.add('fade');

                setTimeout(function() {
                    flash.remove();
                }, 500); // fade animation time

            }, 2000); // 3 seconds

        }

    });
</script> --}}
