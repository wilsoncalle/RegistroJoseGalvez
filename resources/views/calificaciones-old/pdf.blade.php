<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $titulo }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #000;
            padding: 5px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .vertical-text {
            writing-mode: vertical-rl;
            text-orientation: mixed;
            transform: rotate(180deg);
            min-height: 120px;
            margin: auto;
        }
        .aprobado {
            color: green;
            font-weight: bold;
        }
        .pendiente {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>{{ $titulo }}</h2>
        <p>Trimestre: {{ $trimestre->nombre }} - Año Académico: {{ $año }}</p>
        <p>Fecha: {{ $fecha }}</p>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>N° Orden</th>
                <th>N° Matrícula</th>
                <th>Condición</th>
                <th>Apellidos y Nombres</th>
                @foreach($materias as $materia)
                    <th>{{ $materia->nombre }}</th>
                @endforeach
                <th>Comportamiento</th>
                <th>Asig. Desaprob.</th>
                <th>Situación Final</th>
            </tr>
        </thead>
        <tbody>
            @foreach($estudiantes as $index => $estudiante)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $estudiante->codigo }}</td>
                    <td>{{ $estudiante->condicion ?? 'Regular' }}</td>
                    <td>{{ $estudiante->apellido }}, {{ $estudiante->nombre }}</td>
                    
                    @php
                        $desaprobadas = 0;
                    @endphp
                    
                    @foreach($materias as $materia)
                        @php
                            $calificacion = $calificaciones->where('id_estudiante', $estudiante->id_estudiante)
                                ->where('id_asignacion', $materia->id_materia)
                                ->first();
                                
                            if ($calificacion && $calificacion->nota < 11) {
                                $desaprobadas++;
                            }
                        @endphp
                        
                        <td>{{ $calificacion ? $calificacion->nota : '-' }}</td>
                    @endforeach
                    
                    <td>
                        @php
                            $comportamiento = $calificaciones->where('id_estudiante', $estudiante->id_estudiante)->first();
                        @endphp
                        {{ $comportamiento ? $comportamiento->comportamiento : '-' }}
                    </td>
                    <td>{{ $desaprobadas }}</td>
                    <td class="{{ $desaprobadas > 0 ? 'pendiente' : 'aprobado' }}">
                        {{ $desaprobadas > 0 ? 'P' : 'A' }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
