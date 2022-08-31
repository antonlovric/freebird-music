<!DOCTYPE html>
<html>
    <head>
        <title>Račun</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="Saquib" content="Blade">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
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
                position: relative;
            }

            img {
                width: 500px;
            }
            
            .container {
                width: 85%;
                height: 100%;
                margin: 0 auto;
            }

            .center-container {
                display: flex;
                justify-content: center;
                width: 100%;
            }

            h1 {
                text-align: center;
                font-size: 2rem;
                margin: 2rem auto;
            }

            p {
                font-size: 1.5rem;
            }

            .invoice-link {
                display: block;
                margin-top: 2rem;
                padding: 0.8rem;
                border-radius: 10px;
                background-color: #f97316;
                text-align: center;
                color: white;
                font-size: 1.3rem;
                width: 10rem;
                margin: 0 auto;
                text-decoration: none;
            }

            @media only screen and (max-width: 600px) {
                h1 {
                    font-size: 3rem;
                }

                img {
                    width: 350px;
                }
                .invoice-link {
                    width: 80%;
                    margin: 2rem auto;
                }
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="center-container">
                <img src="https://i.imgur.com/de3R3kk.png" alt="FreeBirdMusic logo" />
            </div>
            <h1>Free Bird Music - Račun {{ $order_id }}</h1>
            <p>Poštovanje {{$first_name}} {{$last_name}}U nastavku maila možete pronaći račun za svoju narudžbu.</p>
            <p>Lijep pozdrav, Free Bird Music tim.</p>
            <a class="invoice-link" href="{{$file_path}}">Račun</a>
        </div>
    </body>
</html>