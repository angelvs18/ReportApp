<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Reporte {{ $tarea->folio }}</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 11px; line-height: 1.4; color: #333; }
        .container { width: 100%; margin: 0 auto; padding: 20px; }
        .header { text-align: center; margin-bottom: 20px; } 
        .footer { text-align: center; margin-bottom: 20px; }
        .header img { max-width: 150px; height: auto; }
        .report-title { font-size: 18px; font-weight: bold; margin-bottom: 20px; text-align: center; }
        .section { margin-bottom: 25px; padding: 15px; border: 1px solid #eee; border-radius: 5px; }
        .section-title { font-size: 14px; font-weight: bold; margin-bottom: 10px; border-bottom: 1px solid #eee; padding-bottom: 5px; color: #555; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; vertical-align: top; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .details-grid { display: block; margin-bottom: 15px; } /* Simplificado para dompdf */
        .details-grid div { margin-bottom: 8px; }
        .label { font-weight: bold; color: #555; }
        .photos-grid { display: block; } /* Simplificado para dompdf */
        .photo-item { display: inline-block; margin: 5px; text-align: center; }
        .photo-item img { max-width: 150px; max-height: 100px; border: 1px solid #ddd; margin-bottom: 5px; }
        .signatures { display: block; margin-top: 30px; } /* Simplificado para dompdf */
        .signature-box { display: inline-block; width: 45%; margin: 10px; text-align: center; vertical-align: top; }
        .signature-box img { max-height: 80px; width: auto; border: 1px solid #eee; background-color: #fff; padding: 5px; margin-bottom: 5px; }
        .signature-line { border-top: 1px solid #555; margin-top: 40px; padding-top: 5px; }
        .footer { font-size: 9px; color: #777; margin-top: 30px; }
        .capitalize { text-transform: capitalize; }
        .page-break { page-break-after: always; } /* Para saltos de página si es necesario */
        .page-break-before { page-break-before: always; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            @if(isset($logoBase64))
                <img src="{{ $logoBase64 }}" alt="Logo Kuantiva" style="max-width: 150px; height: auto;">
            @else
                <h2>Kuantiva</h2>
            @endif
        </div>

        <div class="report-title">Reporte de Servicio - Folio: {{ $tarea->folio }}</div>

        {{-- Sección Datos Generales --}}
        <div class="section">
            <div class="section-title">Datos Generales</div>
            <table>
                <tr>
                    <th>Folio</th><td>{{ $tarea->folio }}</td>
                    <th>Tipo</th><td class="capitalize">{{ str_replace('_', ' ', $tarea->tipo) }}</td>
                </tr>
                <tr>
                    <th>Estado</th><td class="capitalize">{{ $tarea->estado }}</td>
                    <th>Fecha Creación</th><td>{{ $tarea->created_at->format('d/m/Y H:i') }}</td>
                </tr>
                 <tr>
                    <th>Autor</th><td colspan="3">{{ $tarea->user->name ?? 'N/A' }}</td>
                </tr>
            </table>
        </div>

        {{-- Sección Descripción y Actividades --}}
        <div class="section">
            <div class="section-title">Detalles del Reporte</div>
            <div class="details-grid">
                <div><span class="label">Descripción:</span> {{ $tarea->descripcion }}</div>
                <div><span class="label">Actividades Realizadas:</span> {!! nl2br(e($tarea->actividades)) !!}</div>
                @if($tarea->observaciones)
                <div><span class="label">Observaciones:</span> {{ $tarea->observaciones }}</div>
                @endif
            </div>
        </div>

        {{-- Sección Específica del Tipo (Vehículo) --}}
        @if ($tarea->tipo === 'vehiculos' && $tarea->vehiculoDetalle)
            <div class="section">
                <div class="section-title">Detalles de Vehículo y GPS</div>
                 <table>
                    <tr><th colspan="2">Datos del GPS</th><th colspan="2">Datos del Vehículo</th></tr>
                    <tr><th>Marca GPS</th><td>{{ $tarea->vehiculoDetalle->gps_marca ?? 'N/A' }}</td><th>Marca Vehículo</th><td>{{ $tarea->vehiculoDetalle->vehiculo_marca ?? 'N/A' }}</td></tr>
                    <tr><th>Modelo GPS</th><td>{{ $tarea->vehiculoDetalle->gps_modelo ?? 'N/A' }}</td><th>Modelo Vehículo</th><td>{{ $tarea->vehiculoDetalle->vehiculo_modelo ?? 'N/A' }}</td></tr>
                    <tr><th>IMEI GPS</th><td>{{ $tarea->vehiculoDetalle->gps_imei ?? 'N/A' }}</td><th>Matrícula</th><td>{{ $tarea->vehiculoDetalle->vehiculo_matricula ?? 'N/A' }}</td></tr>
                    <tr><td colspan="2"></td><th>No. Económico</th><td>{{ $tarea->vehiculoDetalle->vehiculo_numero_economico ?? 'N/A' }}</td></tr>
                 </table>
            </div>
        @endif

        {{-- Sección Específica del Tipo (Generador) --}}
        @if ($tarea->tipo === 'generadores' && $tarea->generadorDetalle && !empty($tarea->generadorDetalle->numeros_economicos))
            <div class="section">
                <div class="section-title">Números Económicos de Generadores</div>
                 <ol style="margin-left: 20px;">
                    @foreach ($tarea->generadorDetalle->numeros_economicos as $numero)
                        <li>{{ $numero }}</li>
                    @endforeach
                 </ol>
            </div>
        @endif
        
        {{-- Sección Evidencia Fotográfica --}}
        @if ($fotosBase64 && !$fotosBase64->isEmpty())
            <div class="section page-break-before">
                <div class="section-title">Evidencia Fotográfica</div>
                <div class="photos-grid0" style="margin-top: 35px;">
                     @foreach ($fotosBase64 as $fotoBase64)
                        <div class="photo-item">
                            <img src="{{ $fotoBase64 }}" alt="Evidencia">
                        </div>
                     @endforeach
                </div>
            </div>
        @endif

        {{-- Sección Firmas --}}
        <div class="signatures">
            <div class="signature-box">
                @if ($instaladorFirmaBase64)
                    <img src="{{ $instaladorFirmaBase64 }}" alt="Firma Instalador">
                @else
                    <div style="height: 80px; border: 1px dashed #ccc; display: flex; align-items: center; justify-content: center;"><span style="color: #999; font-size: 10px;">Sin Firma</span></div>
                @endif
                <div class="signature-line">{{ $tarea->instalador_nombre ?? 'N/A' }}</div>
                <div>Instalador</div>
            </div>

            <div class="signature-box">
                @if ($clienteFirmaBase64)
                    <img src="{{ $clienteFirmaBase64 }}" alt="Firma Cliente">
                @else
                    <div style="height: 80px; border: 1px dashed #ccc; display: flex; align-items: center; justify-content: center;"><span style="color: #999; font-size: 10px;">Sin Firma</span></div>
                @endif
                <div class="signature-line">{{ $tarea->cliente_nombre ?? 'N/A' }}</div>
                <div>Cliente</div>
            </div>
        </div>

        <div class="footer">
            Reporte generado el {{ now()->format('d/m/Y H:i') }}
        </div>
    </div>
</body>
</html>