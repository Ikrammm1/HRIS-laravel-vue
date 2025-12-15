{{-- resources/views/index.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>HR Portal SPA</title>
        <link 
            rel="stylesheet" 
            href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" 
            crossorigin="anonymous" 
            referrerpolicy="no-referrer" 
        />
        @vite('resources/js/app.js') {{-- 💡 Memuat app.js Anda --}}
    </head>
    <body>
        <div id="app">
            {{-- App.vue akan di-mount di sini --}}
        </div>
    </body>
</html>