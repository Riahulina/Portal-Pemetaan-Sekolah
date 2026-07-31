@auth
    <form id="auto-logout-form" method="POST" action="{{ route('logout') }}" style="display: none;">
        @csrf
    </form>
    <script>
        (function () {
            setTimeout(function () {
                var form = document.getElementById('auto-logout-form');
                if (form) {
                    form.submit();
                }
            }, 30 * 60 * 1000);
        })();
    </script>
@endauth
