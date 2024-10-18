<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cuenta Registrada Exitosamente</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            color: #333333;
            font-size: 24px;
            margin-bottom: 10px;
        }

        .content {
            margin-bottom: 20px;
        }

        .content p {
            color: #555555;
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 10px;
        }

        .code {
            font-size: 24px;
            font-weight: bold;
            color: #007bff;
        }

        .footer {
            text-align: center;
            color: #999999;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>{{ $mailData['title'] ?? '' }}</h1>
        </div>
        <div class="content">
            <p>{{ $mailData['title2'] ?? '' }}</p>
            <p>{{ $mailData['body' ?? 'No content'] }}</p>
        </div>
        <div class="footer">
            <p>Este correo es automático, por favor no respondas.</p>
            <p>© {{ date('Y') }} Control Escolar 2024. Todos los derechos reservados.</p>
        </div>
    </div>
</body>

</html>