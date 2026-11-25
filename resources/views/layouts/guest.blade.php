<!DOCTYPE html>
<html lang="ar" dir="rtl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $title ?? 'متجرنا' }}</title>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;900&display=swap" rel="stylesheet">
        
        <script src="https://cdn.tailwindcss.com"></script>
        
        @livewireStyles
        <style>
            body { font-family: 'Cairo', sans-serif; }
            .glow-effect {
                box-shadow: 0 0 40px -5px rgba(220, 38, 38, 0.2);
            }
        </style>
    </head>
    <body class="bg-white text-zinc-900 min-h-screen antialiased">

        {{ $slot }}
        
        @livewireScripts
    </body>
</html>