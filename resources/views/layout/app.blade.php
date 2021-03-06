<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="A tool to visualise the palette of your CSS files(s)"/>
    <title>Palette</title>

    <link href="//fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
</head>
<body>
@include('google-analytics')
<nav class="navbar navbar-default">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="/">Palette</a>
        </div>
    </div>
</nav>

<div class="container">
    <div class="content">
        @yield('content')
    </div>
</div>
<footer class="footer">
    <div class="container">
        <p>Created by <a href="http://www.votemike.co.uk/">Michael Gwynne</a></p>
    </div>
</footer>

</body>
</html>
