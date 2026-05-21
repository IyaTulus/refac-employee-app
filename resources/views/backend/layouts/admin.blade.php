<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'My App')</title>
    @vite('resources/assets/frontend.ts')
    @vite('resources/assets/frontend.less')
    @vite('resources/assets/backend.less')
    @vite('resources/assets/backend.ts')

</head>

<body>
    <div class="d-flex">
        @include('backend.layouts.partials.sidebar')

        <main class="flex-grow-1 p-4">
            @yield('content')
        </main>
    </div>
</body>

</html>
