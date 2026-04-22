<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Kas Kelas')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    @if(auth()->check())
        @include('components.navbar')
    @endif

    <main class="@if(auth()->check()) with-navbar @else without-navbar @endif">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade-in">
                <span>{{ session('success') }}</span>
                <button type="button" class="btn-close" onclick="this.parentElement.remove();"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error alert-dismissible fade-in">
                <span>{{ session('error') }}</span>
                <button type="button" class="btn-close" onclick="this.parentElement.remove();"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <script>
        // Auto-remove alerts after 5 seconds
        document.querySelectorAll('.alert-dismissible').forEach(alert => {
            setTimeout(() => {
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 300);
            }, 5000);
        });

        // Loading button effect
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function() {
                const btn = this.querySelector('button[type="submit"]');
                if (btn) {
                    btn.classList.add('loading');
                    btn.disabled = true;
                }
            });
        });
    </script>
</body>
</html>
