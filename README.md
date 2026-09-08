# EntregaExpress

Sistema web para administrar la entrega de uniformes a elementos de una institución de seguridad. Permite buscar registros pendientes, generar e imprimir acuses, marcar entregas como recibidas, reimprimir acuses y descargar reportes compatibles con Excel.

La aplicación está construida con Laravel, Blade, Alpine.js, Tailwind CSS y Flowbite. Su estructura separa vistas, componentes, servicios de negocio y módulos frontend para facilitar el mantenimiento y crecimiento del proyecto.

## Funcionalidades

- Búsqueda progresiva de elementos pendientes por nombre o clave SP.
- Selección de un elemento y visualización de sus datos y tallas.
- Generación e impresión del acuse de entrega.
- Actualización del estado `recibio` dentro de una transacción con bloqueo de fila.
- Prevención de doble recepción cuando dos operadores intentan entregar el mismo uniforme.
- Consulta de elementos que ya recibieron uniforme.
- Reimpresión de acuses únicamente para registros recibidos.
- Reportes CSV compatibles con Excel:
    - Todos los registros.
    - Solo recibidos.
    - Solo pendientes.
- Carga masiva inicial desde archivos CSV mediante Artisan.
- Sidebar responsive con estado persistente por navegador/dispositivo.
- Skeletons, spinners y estados de carga para consultas y acciones.
- Acuse imprimible con identidad institucional y escudos ubicados en `public/`.

## Requisitos

- PHP 8.3 o superior.
- Composer 2.
- Node.js y npm.
- Extensión PDO de la base de datos utilizada.
- Laravel 13.

Dependencias principales:

- `laravel/framework` `^13.17`
- `alpinejs` `^3.17.2`
- `tailwindcss` `^4.3.3`
- `flowbite` `^4.0.2`
- Vite mediante `laravel-vite-plugin`.

## Instalación

1. Clona el repositorio y entra al proyecto:

```bash
git clone https://github.com/ADJHz/EntregaExpress.git
cd EntregaExpress
```

2. Instala las dependencias PHP:

```bash
composer install
```

3. Crea el archivo de entorno y genera la clave:

```bash
copy .env.example .env
php artisan key:generate
```

En macOS/Linux, usa:

```bash
cp .env.example .env
php artisan key:generate
```

4. Configura la conexión de base de datos en `.env`.

Para SQLite local:

```env
DB_CONNECTION=sqlite
DB_DATABASE=C:/ruta/al/proyecto/database/database.sqlite
```

Crea el archivo si todavía no existe:

```bash
php -r "file_exists('database/database.sqlite') || touch('database/database.sqlite');"
```

Para MySQL, configura también `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME` y `DB_PASSWORD`.

5. Ejecuta las migraciones:

```bash
php artisan migrate
```

6. Instala las dependencias frontend y compila los assets:

```bash
npm install
npm run build
```

También puedes usar el script de preparación del proyecto:

```bash
composer run setup
```

## Desarrollo local

Inicia Laravel:

```bash
php artisan serve
```

En otra terminal inicia Vite para recarga en caliente:

```bash
npm run dev
```

La aplicación estará disponible normalmente en:

```text
http://127.0.0.1:8000
```

Para ejecutar los procesos definidos por Composer:

```bash
composer run dev
```

## Carga de elementos desde CSV

El proyecto incluye el comando `carga:elementos`:

```bash
php artisan carga:elementos DESFILE.csv
```

También acepta una ruta absoluta:

```bash
php artisan carga:elementos "C:\\datos\\DESFILE.csv"
```

El comando procesa los campos del archivo en este orden:

1. `nombre`
2. `csp`
3. `ubicacion`
4. `coordinacion`
5. `genero`
6. `tipo_uniforme`
7. `color_franja`
8. `camisola`
9. `pantalon`
10. `chamarra`
11. `bota`
12. `cinturon`
13. `recibio`

Para `recibio`, el comando interpreta como recibido valores como `SI`, `SÍ`, `1`, `TRUE` y `V`. Las incidencias se registran en el canal de log de carga.

> Recomendación: conserva una copia del CSV original y valida el resumen de registros insertados y errores después de cada carga.

## Rutas principales

| Método | Ruta                           | Nombre                | Descripción                                           |
| ------ | ------------------------------ | --------------------- | ----------------------------------------------------- |
| `GET`  | `/`                            | `dashboard`           | Renderiza la aplicación principal.                    |
| `GET`  | `/api/entregas/pendientes`     | `entregas.pendientes` | Consulta elementos no recibidos.                      |
| `GET`  | `/api/entregas/recibidos`      | `entregas.recibidos`  | Consulta elementos recibidos.                         |
| `POST` | `/entregas/{elemento}/recibir` | `entregas.recibir`    | Marca una entrega como recibida y renderiza el acuse. |
| `GET`  | `/entregas/{elemento}/acuse`   | `entregas.reimprimir` | Reimprime el acuse de un elemento recibido.           |
| `GET`  | `/reportes/entregas.csv`       | `reportes.entregas`   | Descarga un reporte filtrado.                         |

El endpoint de reportes acepta el parámetro `estado`:

```text
/reportes/entregas.csv?estado=recibidos
/reportes/entregas.csv?estado=pendientes
/reportes/entregas.csv?estado=ambos
```

## Arquitectura

### Backend

```text
app/
├── Console/Commands/
│   └── CargarElementosEntrega.php
├── Http/Controllers/
│   └── ElementosEntregaController.php
├── Models/
│   └── ElementosEntrega.php
└── Services/
    ├── ElementosEntregaReceptionService.php
    ├── ElementosEntregaReportService.php
    └── ElementosEntregaSearchService.php
```

- `ElementosEntregaController`: coordina solicitudes HTTP, validación y respuestas.
- `ElementosEntregaSearchService`: búsquedas, conteos y serialización de empleados.
- `ElementosEntregaReceptionService`: actualización transaccional con `lockForUpdate()`.
- `ElementosEntregaReportService`: generación de reportes mediante streaming.
- `ElementosEntrega`: modelo Eloquent de la tabla `elementos_entregas`.

### Frontend

```text
resources/
├── js/
│   ├── app.js
│   └── delivery/
│       ├── api.js
│       ├── application.js
│       └── sidebar.js
├── css/
│   └── app.css
└── views/
    ├── components/
    │   ├── delivery/
    │   ├── layouts/
    │   └── navigation/
    └── pages/
        ├── EntregaExpress.blade.php
        └── receipt.blade.php
```

- `app.js`: punto de entrada de Alpine, Flowbite y módulos de EntregaExpress.
- `delivery/api.js`: peticiones `fetch` a los endpoints JSON.
- `delivery/application.js`: estado y acciones de la aplicación Alpine.
- `delivery/sidebar.js`: apertura, cierre y persistencia del sidebar.
- `components/delivery`: componentes de carga, búsqueda, recibidos, reportes y alertas.
- `components/navigation`: sidebar y encabezado institucional.
- `receipt.blade.php`: formato de acuse listo para imprimir o guardar como PDF.

## Modelo de datos

La tabla `elementos_entregas` contiene:

- Datos del elemento: nombre, CSP, ubicación, coordinación y género.
- Datos del uniforme: tipo, color/franja y tallas.
- Estado de entrega: `recibio` como booleano.
- Fechas de creación y actualización.

El valor `recibio = false` representa un pendiente. El valor `recibio = true` representa una entrega completada.

## Pruebas y calidad

Ejecuta toda la suite:

```bash
php artisan test --compact
```

Ejecuta únicamente las pruebas de entregas:

```bash
php artisan test --compact tests/Feature/ElementosEntregaTest.php
```

Formatea el código PHP:

```bash
vendor/bin/pint --format agent
```

Compila y valida las vistas:

```bash
php artisan view:cache
npm run build
```

Las pruebas de feature cubren actualmente:

- Exclusión de elementos recibidos en la consulta de pendientes.
- Actualización de una entrega.
- Reimpresión solo para elementos recibidos.
- Reportes filtrados.
- Prevención de una segunda recepción.

## Assets institucionales

Las imágenes institucionales se encuentran en `public/`:

- `Escudos.png`: composición de escudos y logotipos institucionales.
- `Escudo.png`: asset alternativo utilizado en algunas vistas.

El acuse utiliza los assets públicos mediante `asset(...)` para que funcionen en desarrollo y producción.

## Consideraciones de operación

- La recepción está protegida contra actualizaciones duplicadas mediante transacción y bloqueo de fila.
- El acuse se abre con la impresión del navegador; desde el diálogo se puede elegir “Guardar como PDF”.
- La descarga de reportes utiliza CSV compatible con Excel y streaming para evitar cargar todos los registros en memoria.
- Las consultas progresivas limitan los resultados para mantener una interfaz rápida.
- El estado del sidebar se guarda en el navegador mediante `localStorage` y puede variar por equipo o navegador.

## Licencia

Este proyecto se distribuye bajo la licencia MIT, salvo que el propietario del repositorio establezca condiciones adicionales.
