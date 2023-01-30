<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Shortener</title>
<!--        <script type="text/javascript" src="JS/jquery-3.6.3.js"></script>
        <script type="text/javascript" src="JS/ajaxquery.js"></script>-->
<!--        <link href="../resources/css/bootstrap.css" rel="stylesheet" type="text/css">
        <link href="../resources/css/style.css" rel="stylesheet" type="text/css">-->
        @vite('resources/js/jquery-3.6.3.js')
        @vite('resources/js/ajaxquery.js')
        @vite('resources/css/style.css')
        @vite('resources/css/bootstrap.css')        
    </head>
    <body>
        <div id="div-form">
            <h2>Укороти свой URL</h2>
            <form method="post" id="formToShort">
                @csrf
                <p><b>Длинный URL:</b><br><br>
                <input name="longURL" class="form-control" type="text" size="50" placeholder="Введи свой длинный URL"><br>
                <input type=submit class="btn btn-primary" value="УКОРОТИТЬ">
                <div id="result_form"></div>
            </form>
        </div>
    </body>
</html>