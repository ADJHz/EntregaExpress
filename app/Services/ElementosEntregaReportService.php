<?php

namespace App\Services;

use App\Models\ElementosEntrega;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ElementosEntregaReportService
{
    public function download(string $status): StreamedResponse
    {
        $query = ElementosEntrega::query()->orderBy('nombre');

        if ($status === 'recibidos') {
            $query->where('recibio', true);
        } elseif ($status === 'pendientes') {
            $query->where('recibio', false);
        }

        return response()->streamDownload(function () use ($query): void {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Nombre', 'CSP', 'Ubicacion', 'Coordinacion', 'Genero', 'Tipo de uniforme', 'Estado']);

            foreach ($query->cursor() as $employee) {
                fputcsv($handle, [
                    $employee->nombre,
                    $employee->csp,
                    $employee->ubicacion,
                    $employee->coordinacion,
                    $employee->genero,
                    $employee->tipo_uniforme,
                    $employee->recibio ? 'Recibido' : 'Pendiente',
                ]);
            }

            fclose($handle);
        }, "reporte-entregas-{$status}.csv", [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
