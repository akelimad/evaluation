<!DOCTYPE html>
<html>
    <head>
        <title>Accès interdit | E-EVALUATION</title>

        <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
        <link href="{{ App\Asset::path('app.css') }}" rel="stylesheet">

        <style>
            html, body {
                height: 100%;
            }

            body {
                margin: 0;
                padding: 0;
                width: 100%;
                color: #B0BEC5;
                display: table;
                font-weight: 100;
                font-family: 'Lato';
            }

            .container {
                text-align: center;
                display: table-cell;
                vertical-align: middle;
            }

            .content {
                text-align: center;
                display: inline-block;
            }

            .title {
                font-size: 72px;
                margin-bottom: 40px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="content p-xxs-10">
                <div class="title">Vous n'avez pas les permissions</div>

                <a href="{{ route('home') }}" class="btn btn-primary">Revenir à la page d'accueil</a>
            </div>
        </div>
    </body>
</html>
