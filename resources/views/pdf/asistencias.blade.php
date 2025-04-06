<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Asistencia</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        .title {
            font-size: 18px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 10px;
        }
        .subtitle {
            font-size: 14px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #000;
            padding: 4px;
            text-align: center;
            
        }
        .text-left {
            text-align: left;
            background-color:#ccc ;
        }
        .text-order {
            background-color:#ccc ;
        }
        /* Estilos para encabezados */
        .header-week {
            background-color: #000;
            color: #fff;
            font-weight: bold;
        }
        .header-day {
            background-color: #ccc;
        }
        .header-totals {
            background-color: #AFDCEC;
            font-weight: bold;
        }
        .student-name {
            text-align: left;
            font-weight: normal;
        }
        /* Estilos para asistencias */
        .attendance-P {
            background-color: #C6EFCE;
        }
        .attendance-T {
            background-color: #FFEB9C;
        }
        .attendance-F {
            background-color: #FFC7CE;
        }
        .attendance-J {
            background-color: #BDD7EE;
        }
    </style>
</head>
<body>
    <div class="title">Control de Asistencia - {{ $aula->nivel->nombre }} {{ $aula->grado->nombre }} "{{ $aula->seccion->nombre }}"</div>
    <div class="subtitle">
        Materia: {{ $materia->nombre }} | Docente: {{ $docente->nombre }} {{ $docente->apellido }} | Mes: {{ $mesNombre }} | Año: {{ $año }}
    </div>
    
    <table>
        <thead>
            <tr>
                <th rowspan="3" class="text-order">N° Orden</th>
                <th rowspan="3" class="text-left">Apellidos y Nombres</th>
                @foreach($weeks as $index => $week)
                    <th colspan="{{ count($week) }}" class="header-week">Semana {{ $index + 1 }}</th>
                @endforeach
                <th rowspan="3" class="header-totals">Totales</th>
            </tr>
            <tr>
                @foreach($schoolDays as $day)
                    <th class="header-day">{{ $day['initial'] }}</th>
                @endforeach
            </tr>
            <tr>
                @foreach($schoolDays as $day)
                    <th class="header-day">{{ $day['day'] }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($data as $index => $row)
                <tr>
                    <td>{{ $row[0] }}</td>
                    <td class="student-name">{{ $row[1] }}</td>
                    @for($i = 2; $i < count($row) - 1; $i++)
                        <td class="attendance-{{ $row[$i] }}">{{ $row[$i] }}</td>
                    @endfor
                    <td>{{ $row[count($row) - 1] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>