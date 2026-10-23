<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'AdU eCare+ | Adamson University Digital Healthcare')</title>

    <!-- Immediate Theme Initializer to prevent theme flash (FOUC) -->
    <script>
        (function() {
            var savedTheme = localStorage.getItem('aduecare_theme');
            if (!savedTheme) {
                savedTheme = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            }
            document.documentElement.setAttribute('data-bs-theme', savedTheme);
        })();
    </script>

    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Custom Creative Styles -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    @stack('styles')
</head>
<body>
    @yield('body')

    <!-- Flash Messages / Alerts Modal or Toasts -->
    @if(session('success'))
        <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1080">
            <div class="toast align-items-center text-bg-success border-0 show shadow-lg" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body d-flex align-items-center gap-2">
                        <i class="bi bi-check-circle-fill fs-5"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1080">
            <div class="toast align-items-center text-bg-danger border-0 show shadow-lg" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body d-flex align-items-center gap-2">
                        <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        </div>
    @endif

    <!-- Bootstrap 5.3 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Auto dismiss toasts after 6 seconds
            var toastElList = [].slice.call(document.querySelectorAll('.toast'));
            toastElList.map(function (toastEl) {
                return new bootstrap.Toast(toastEl, { delay: 6000 });
            });

            // Global Dark Mode Handler
            function updateThemeIcons(theme) {
                document.querySelectorAll('.theme-toggle-btn').forEach(function(btn) {
                    var icon = btn.querySelector('i');
                    if (icon) {
                        if (theme === 'dark') {
                            icon.className = 'bi bi-sun-fill text-warning';
                            btn.setAttribute('title', 'Switch to Light Mode');
                        } else {
                            icon.className = 'bi bi-moon-stars-fill';
                            btn.setAttribute('title', 'Switch to Dark Mode');
                        }
                    }
                });
            }

            var currentTheme = document.documentElement.getAttribute('data-bs-theme') || 'light';
            updateThemeIcons(currentTheme);

            document.querySelectorAll('.theme-toggle-btn').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    var activeTheme = document.documentElement.getAttribute('data-bs-theme');
                    var newTheme = activeTheme === 'dark' ? 'light' : 'dark';
                    document.documentElement.setAttribute('data-bs-theme', newTheme);
                    localStorage.setItem('aduecare_theme', newTheme);
                    updateThemeIcons(newTheme);
                });
            });
        });
    </script>
    @stack('scripts')
</body>
</html>
