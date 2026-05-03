<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title inertia>{{ config('app.name', 'Laravel') }} — Admin</title>

    @vite(['resources/css/app.css', 'resources/js/admin.js'])
    @inertiaHead
</head>
<body class="min-h-screen antialiased bg-slate-50 text-slate-900">
    @inertia
</body>
</html>
