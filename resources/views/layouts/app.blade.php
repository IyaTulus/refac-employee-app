<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('app.name', 'Laravel') }}</title>
    @if (app()->environment('local'))
        @vite(['resources/assets/backend.ts'])
    @else
        @vite(['resources/assets/backend.ts'])
    @endif
</head>

<body>
    <div id="app">
        <main class="py-4">
            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>

</html>
