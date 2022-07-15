<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="{{ mix('/css/antd.css') }}" rel="stylesheet">
        <link href="{{ mix('/css/app.css') }}" rel="stylesheet">
        <title>Rezet Test Task</title>
    </head>
    <body>
        <div id="container"></div>
        <script charset="utf-8" src="{{ mix('/js/app.js') }}"></script>
    </body>
</html>
