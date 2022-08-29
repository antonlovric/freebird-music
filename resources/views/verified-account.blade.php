<!DOCTYPE html>
<html>
    <head>
        <title>Free Bird Music</title>
        <meta name="description" content="Web stranica za verifikaciju e-maila nakon registracije na web mjestu Free Bird Music">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="Saquib" content="Blade">
        <link rel="stylesheet" href="{{asset('css/app.css')}}">
        <style>
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            body {
                font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont,
                    "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif,
                    "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol",
                    "Noto Color Emoji";
                overflow-x: hidden;
                background-color: #17191e;
                min-height: 100vh;
                color: white;
            }

            .container {
                display: flex;
                justify-content: center;
                align-items: center;
                text-align: center;
                height: 100vh;
            }

            .heading {
                font-size: 4rem;
            }

            .visit-link {
                display: block;
                margin-top: 2rem;
                padding: 0.8rem;
                border-radius: 10px;
                background-color: #f97316;
            }

            @media only screen and (max-width: 600px) {
                .heading {
                    font-size: 3rem;
                }
                .visit-link {
                    width: 80%;
                    margin: 2rem auto;
                }
            }

        </style>
    </head>
    <body>
        <div class="container">
            <div class="text-container">
                <h1 class="heading">Uspješno potvrđen račun!</h1>
                <a class="visit-link" href="https://freebird-music.vercel.app/">
                    Posjeti Free Bird Music dućan
                </a>
            </div>
        </div>
    </body>
</html>

@section("css")