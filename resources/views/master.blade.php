<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>RSS Feed reader</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="apple-touch-icon" href="apple-touch-icon.png">

    {!! HTML::style('css/app.css') !!}

</head>

<body>

@yield('content')

{{ HTML::script('js/app.js') }}
{{ HTML::script('js/bootstrap.min.js') }}

@yield('scripts')

</body>
</html>