<!DOCTYPE html>
<html>
    <head>
        <title>Erreur 404 | E-EVALUATION</title>
        <meta charset="UTF-8">
        <!-- <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css"> -->
        <link href="{{ asset('css/bootstrap.min.css')}}" rel="stylesheet" >
        <style>
            html, body {
                height: 100%;
            }
        </style>
    </head>
    <body>
        <a href="{{url()->previous()}}">
            <div class="content">
                <img src="{{asset('img/error404.png')}}" class="img-responsive" alt="">
            </div>
        </a>
    </body>
</html>
