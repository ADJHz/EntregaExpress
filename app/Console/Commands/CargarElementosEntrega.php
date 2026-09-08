<?php

namespace App\Console\Commands;

use App\Models\ElementosEntrega;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CargarElementosEntrega extends Command
{
    /**
     * El nombre y firma del comando en la terminal.
     * Recibe la ruta del archivo CSV como argumento.
     */
    protected $signature = 'carga:elementos {archivo : La ruta absoluta o relativa al archivo CSV}';

    /**
     * Descripción del comando.
     */
    protected $description = 'Carga masiva de elementos de entrega desde un archivo CSV con logs detallados.';

    /**
     * Ejecuta el comando.
     */
    public function handle()
    {
        $archivo = $this->argument('archivo');

        if (! file_exists($archivo) || ! is_readable($archivo)) {
            $this->error("El archivo no existe o no se puede leer: {$archivo}");

            return Command::FAILURE;
        }

        $this->info("Iniciando lectura del archivo: {$archivo}");
        Log::channel('CargaxComando')->info('=== INICIO DE CARGA MASIVA ===');
        Log::channel('CargaxComando')->info("Archivo: {$archivo}");

        // Leer el archivo en memoria (ideal para archivos medianos)
        $lineas = file($archivo, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        // Quitar la fila de encabezados
        $encabezados = array_shift($lineas);
        $totalRegistros = count($lineas);

        if ($totalRegistros === 0) {
            $this->warn('El archivo parece estar vacío o solo contiene encabezados.');

            return Command::SUCCESS;
        }

        // Inicializar barra de progreso en la terminal
        $this->output->progressStart($totalRegistros);

        $creados = 0;
        $errores = 0;

        foreach ($lineas as $index => $linea) {
            // Analizar la línea CSV
            $datos = str_getcsv($linea, ',');

            // Validar que tengamos las 13 columnas esperadas
            if (count($datos) < 13) {
                $errores++;
                Log::channel('CargaxComando')->warning('Fila '.($index + 2).' ignorada por formato incorrecto.', ['linea' => $linea]);
                $this->output->progressAdvance();

                continue;
            }

            try {
                ElementosEntrega::create([
                    'nombre' => trim($datos[0]),
                    'csp' => trim($datos[1]) ?: null,
                    'ubicacion' => trim($datos[2]) ?: null,
                    'coordinacion' => trim($datos[3]) ?: null,
                    'genero' => trim($datos[4]) ?: null,
                    'tipo_uniforme' => trim($datos[5]) ?: null,
                    'color_franja' => trim($datos[6]) ?: null,
                    'camisola' => trim($datos[7]) ?: null,
                    'pantalon' => trim($datos[8]) ?: null,
                    'chamarra' => trim($datos[9]) ?: null,
                    'bota' => trim($datos[10]) ?: null,
                    'cinturon' => trim($datos[11]) ?: null,
                    // Parseo del booleano (Asumimos que en el Excel viene como 'SI', '1', o 'TRUE')
                    'recibio' => in_array(strtoupper(trim($datos[12])), ['SI', 'SÍ', '1', 'TRUE', 'V']),
                ]);

                $creados++;
            } catch (\Exception $e) {
                $errores++;
                // Guardamos el error específico en el log del sistema, pero no detenemos la ejecución
                Log::channel('CargaxComando')->error('Error al guardar fila '.($index + 2).': '.$e->getMessage(), ['datos' => $datos]);
            }

            // Avanzar la barra de progreso
            $this->output->progressAdvance();
        }

        // Finalizar barra de progreso
        $this->output->progressFinish();

        // Resumen en terminal
        $this->table(
            ['Total Procesados', 'Insertados Exitosamente', 'Errores'],
            [[$totalRegistros, $creados, $errores]]
        );

        if ($errores > 0) {
            $this->warn("La carga finalizó con {$errores} errores. Revisa el log 'CargaxComando.log' para más detalles.");
        } else {
            $this->info('¡Carga masiva completada exitosamente sin errores!');
        }

        // Resumen en Log
        Log::channel('CargaxComando')->info('=== FIN DE CARGA MASIVA ===');
        Log::channel('CargaxComando')->info("Total: {$totalRegistros} | Éxitos: {$creados} | Errores: {$errores}\n");

        return Command::SUCCESS;
    }
}
