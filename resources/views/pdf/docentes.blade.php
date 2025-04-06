<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Docentes</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }
        .container {
            width: 100%;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .subtitle {
            font-size: 14px;
            margin-bottom: 20px;
        }
        .date {
            font-size: 11px;
            margin-bottom: 20px;
            text-align: right;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
            padding: 8px;
        }
        td {
            padding: 8px;
            text-align: left;
        }
        .text-center {
            text-align: center;
        }
        .page-number {
            text-align: right;
            font-size: 10px;
            position: fixed;
            bottom: 20px;
            right: 20px;
        }
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 10px;
            border-top: 1px solid #ddd;
            padding-top: 5px;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="title">LISTADO DE DOCENTES</div>
            <div class="subtitle">Nivel: {{ $nombreNivel }}</div>
        </div>
        
        <div class="date">Fecha de generación: {{ $fechaActual }}</div>
        
        <table>
            <thead>
                <tr>
                    <th width="5%">N°</th>
                    <th width="20%">Nombre</th>
                    <th width="20%">Apellido</th>
                    <th width="15%">DNI</th>
                    <th width="20%">Nivel</th>
                    <th width="20%">Teléfono</th>
                </tr>
            </thead>
            <tbody>
                @forelse($docentes as $docente)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $docente->nombre }}</td>
                        <td>{{ $docente->apellido }}</td>
                        <td>{{ $docente->dni ?: 'No registrado' }}</td>
                        <td>{{ $docente->nivel ? $docente->nivel->nombre : 'No especificado' }}</td>
                        <td>{{ $docente->telefono ?? 'No registrado' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">No se encontraron docentes con los criterios especificados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        
        <div class="footer">
            Sistema de Gestión Escolar - Listado de Docentes
        </div>
        
        <script type="text/php">
            if (isset($pdf)) {
                $text = "Página {PAGE_NUM} de {PAGE_COUNT}";
                $size = 10;
                $font = $fontMetrics->getFont("Helvetica");
                $width = $fontMetrics->get_text_width($text, $font, $size) / 2;
                $x = ($pdf->get_width() - $width) - 20;
                $y = $pdf->get_height() - 20;
                $pdf->page_text($x, $y, $text, $font, $size);
            }
        </script>
    </div>
</body>
</html>