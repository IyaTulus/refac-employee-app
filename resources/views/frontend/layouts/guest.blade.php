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
    @yield('content')
</body>

</html>
