<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $aviso->titulo }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 30px auto;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .header {
            background-color: #1e3a5f;
            padding: 20px 30px;
            text-align: center;
        }

        .header img {
            height: 60px;
        }

        .header h2 {
            color: #ffffff;
            margin: 10px 0 0;
            font-size: 18px;
        }

        .body {
            padding: 30px;
            color: #333333;
            font-size: 15px;
            line-height: 1.6;
        }

        .body p {
            margin: 0 0 16px;
        }

        .adjunto-aviso {
            margin-top: 20px;
            text-align: center;
        }

        .adjunto-aviso img {
            max-width: 100%;
            border-radius: 6px;
            border: 1px solid #ddd;
        }

        .btn-portal {
            display: inline-block;
            margin-top: 24px;
            padding: 12px 28px;
            background-color: #1e3a5f;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
        }

        .footer {
            background-color: #f0f0f0;
            padding: 16px 30px;
            text-align: center;
            font-size: 12px;
            color: #888888;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <img src="{{ asset('img/logo_cst.png') }}" alt="Colegio Santa Teresita">
            <h2>{{ $aviso->titulo }}</h2>
        </div>

        <div class="body">
            <p>Estimado/a responsable,</p>

            @if($aviso->mensaje)
                <p>{!! nl2br(e($aviso->mensaje)) !!}</p>
            @endif

            @if($aviso->archivo_adjunto)
                @php $ext = strtolower(pathinfo($aviso->archivo_adjunto, PATHINFO_EXTENSION)); @endphp
                @if(in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                    <div class="adjunto-aviso">
                        <img src="{{ Storage::url($aviso->archivo_adjunto) }}" alt="Adjunto">
                    </div>
                @else
                    <p>📎 Se adjunta un archivo a este correo.</p>
                @endif
            @endif

            <div style="text-align:center;">
                <a href="{{ url('/portal-responsables') }}" class="btn-portal">Ingresar al Portal</a>
            </div>
        </div>

        <div class="footer">
            Colegio Privado Santa Teresita &mdash; Luque, Paraguay<br>
            Este es un mensaje automático, por favor no responda este correo.
        </div>
    </div>
</body>

</html>