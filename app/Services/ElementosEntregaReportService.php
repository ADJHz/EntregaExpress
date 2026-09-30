<?php

namespace App\Services;

use App\Models\ElementosEntrega;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
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
            $spreadsheet = new Spreadsheet;
            $sheet = $spreadsheet->getActiveSheet();
            $headers = ['Nombre', 'CSP', 'Ubicacion', 'Coordinacion', 'Genero', 'Tipo de uniforme', 'Color / Franja', 'Estado', 'Fecha de entrega'];

            foreach ($headers as $column => $header) {
                $sheet->setCellValueExplicit(
                    Coordinate::stringFromColumnIndex($column + 1).'1',
                    $header,
                    DataType::TYPE_STRING,
                );
            }

            $row = 2;

            foreach ($query->cursor() as $employee) {
                $values = [
                    $employee->nombre,
                    $employee->csp,
                    $employee->ubicacion,
                    $employee->coordinacion,
                    $employee->genero,
                    $employee->tipo_uniforme,
                    $employee->color_franja,
                    $employee->recibio ? 'Recibido' : 'Pendiente',
                    $employee->updated_at ? $employee->updated_at->format('Y-m-d') : '',
                ];

                foreach ($values as $column => $value) {
                    $sheet->setCellValueExplicit(
                        Coordinate::stringFromColumnIndex($column + 1).$row,
                        (string) ($value ?? ''),
                        DataType::TYPE_STRING,
                    );
                }

                $row++;
            }

            $sheet->freezePane('A2');
            $sheet->getStyle('A1:H1')->getFont()->setBold(true);
            $sheet->getStyle('A1:H1')->getAlignment()->setHorizontal('center');

            foreach (range('A', 'H') as $column) {
                $sheet->getColumnDimension($column)->setAutoSize(true);
            }

            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
            $spreadsheet->disconnectWorksheets();
        }, "reporte-entregas-{$status}.xlsx", [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
}
