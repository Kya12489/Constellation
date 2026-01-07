<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title inertia>{{ config('app.name', 'Laravel') }}</title>
        
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        
        @routes
        @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body class="font-sans antialiased">
        <div class=" text-black/50 dark:bg-black bg-gray-200 dark:text-white/50">
            <div class="relative flex min-h-screen flex-col items-center">
                <div class="relative w-full max-w-2xl px-6 lg:max-w-7xl">
                    @inertia
                </div>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/js-confetti@latest/dist/js-confetti.browser.js"></script>
        <script>
            const jsConfetti = new JSConfetti()
            
            document.addEventListener('click', function() {
                jsConfetti.addConfettiAtPosition({
                    confettiRadius: 3,
                    confettiDispatchPosition: {
                        x: event.clientX,
                        y: event.clientY
                    }
                })
            })
        </script>
        
    </body>
</html>