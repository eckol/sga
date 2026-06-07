<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333333;
            line-height: 1.6;
        }

        .header {
            background-color: #f8fafc;
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid #e2e8f0;
        }

        .content {
            padding: 20px;
        }

        .btn-container {
            text-align: center;
            margin: 30px 0;
        }

        .btn {
            background-color: #2563eb;
            color: #ffffff !important;
            padding: 12px 24px;
            text-style: none;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            display: inline-block;
        }

        .footer {
            font-size: 12px;
            color: #718096;
            margin-top: 4px;
            border-top: 1px solid #e2e8f0;
            padding-top: 20px;
        }
    </style>
</head>

<body>

    <div class="header">
        <a href="{{ config('app.url') }}">
            <img src="cid:logo_cst_login.png" alt="Colegio Santa Teresita" style="max-height: 75px;">
        </a>
    </div>

    <div class="content">
        <h2>Estimado/a Padre, Madre o Encargado/a:</h2>

        <p>Le informamos que se ha registrado una novedad en el Sistema de Gestión de Alumnos (SGA) del Colegio Santa
            Teresita para el alumno/a: <strong>{{ $alumnoNom }}</strong>.</p>

        <p><strong>Tipo de Evento:</strong> {{ $tipoEvento }}</p>

        <p><strong>Detalles registrados:</strong><br>
            {!! nl2br(e($detalleEvento)) !!}</p>

        <p>Para más detalles, puede iniciar sesión en su Dashboard del SGA.</p>

        <div class="btn-container">
            <a href="{{ rtrim(config('app.url'), '/') }}/dashboard" class="btn">Ingresar al SGA</a>
        </div>

        <div class="footer">
            Atentamente,<br>
            <strong>Colegio Privado Santa Teresita</strong>
        </div>
    </div>

</body>

</html>