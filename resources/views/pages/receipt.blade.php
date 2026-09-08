<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acuse de entrega - {{ $elemento->nombre }}</title>
    <style>
        @page {
            size: letter;
            margin: 8mm;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #e2e8f0;
            color: #000;
            font-family: Arial, Helvetica, sans-serif;
        }

        .actions {
            display: flex;
            gap: 12px;
            justify-content: center;
            padding: 24px;
        }

        .actions a,
        .actions button {
            border: 0;
            border-radius: 8px;
            cursor: pointer;
            font: 600 14px Arial, sans-serif;
            padding: 12px 18px;
            text-decoration: none;
        }

        .actions a {
            background: #0f172a;
            color: #fff;
        }

        .actions button {
            background: #2563eb;
            color: #fff;
        }

        #receipt {
            background: #fff;
            margin: 0 auto 24px;
            max-width: 195mm;
            min-height: 245mm;
            padding: 0 1mm;
        }

        .document-header {
            align-items: flex-start;
            display: flex;
            justify-content: space-between;
            min-height: 21mm;
        }

        .institution {
            flex: 1;
            padding-top: 1mm;
        }

        .institution-title {
            font-size: 13.5pt;
            font-weight: bold;
            line-height: 1.1;
            white-space: nowrap;
        }

        .document-title {
            font-size: 11pt;
            font-weight: bold;
            margin-top: 4mm;
        }

        .seals {
            height: 17mm;
            object-fit: contain;
            object-position: right top;
            width: 62mm;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        td {
            padding: 1.5mm;
            vertical-align: middle;
        }

        .label {
            font-size: 8.5pt;
            font-weight: bold;
            text-align: right;
            white-space: nowrap;
        }

        .value {
            background: #efefef;
            border: 1px solid #999;
            font-size: 9pt;
            height: 6mm;
            line-height: 4mm;
            overflow: hidden;
            padding: 0 1.5mm;
            white-space: nowrap;
        }

        .name-row .label {
            width: 18mm;
        }

        .compact-row .label {
            width: 45mm;
        }

        .compact-row .value-cell {
            width: 36mm;
        }

        .compact-row .gender-label {
            width: 24mm;
        }

        .compact-row .gender-cell {
            width: 38mm;
        }

        .section-title {
            border-bottom: 1px solid #000;
            font-size: 10pt;
            font-weight: bold;
            margin-top: 3mm;
            padding-bottom: 2mm;
            text-align: center;
        }

        .color-row .label {
            width: 43mm;
        }

        .color-row .value-cell {
            width: 90mm;
        }

        .garments {
            margin: 2mm auto 0;
            width: 92mm;
        }

        .garments th,
        .garments td {
            font-size: 8.5pt;
            font-weight: bold;
            padding: 1mm 2mm;
        }

        .garments th {
            text-align: center;
        }

        .garments .garment-name {
            text-align: left;
            width: 38mm;
        }

        .size {
            background: #efefef;
            border: 1px solid #999;
            display: inline-block;
            height: 6mm;
            min-width: 27mm;
            padding: 1mm 2mm;
            text-align: center;
        }

        .quantity {
            padding-left: 8mm !important;
            text-align: left;
            width: 30mm;
        }

        .legal {
            font-size: 7.2pt;
            line-height: 1.18;
            margin-top: 4mm;
            text-align: justify;
        }

        .legal p {
            margin: 0 0 2mm;
        }

        .legal .care-title {
            font-weight: bold;
            margin-top: 3mm;
        }

        .signature {
            border-top: 1px solid #000;
            font-size: 9pt;
            font-weight: bold;
            margin: 13mm auto 0;
            padding-top: 2mm;
            text-align: center;
            width: 72mm;
        }

        .identification {
            font-size: 7.5pt;
            font-weight: bold;
            line-height: 1.15;
            margin: 8mm 0 0 auto;
            text-align: left;
            width: 62mm;
        }

        @media (max-width: 600px) {
            .actions {
                flex-direction: column;
            }

            .actions a,
            .actions button {
                text-align: center;
            }

            #receipt {
                margin: 0;
                min-height: auto;
                padding: 12px;
                width: 100%;
            }

            .institution-title {
                font-size: 9pt;
                white-space: normal;
            }

            .document-title {
                font-size: 8pt;
            }

            .seals {
                height: 13mm;
                width: 45mm;
            }

            .label {
                font-size: 7pt;
            }

            .value {
                font-size: 7.5pt;
            }

            .garments {
                width: 100%;
            }
        }

        @media print {
            body {
                background: #fff;
            }

            .actions {
                display: none;
            }

            #receipt {
                margin: 0;
                max-width: none;
                min-height: auto;
                padding: 0;
            }
        }
    </style>
</head>

<body>
    <div class="actions">
        <a href="{{ route('dashboard') }}">Volver al buscador</a>
        <button type="button" onclick="window.print()">Imprimir o guardar como PDF</button>
    </div>

    <main id="receipt">
        <header class="document-header">
            <div class="institution">
                <div class="institution-title">SECRETARÍA DE SEGURIDAD DEL ESTADO DE MÉXICO</div>
                <div class="document-title">RECIBO DE ENTREGA DE UNIFORME OPERATIVO</div>
            </div>
            <img class="seals" src="{{ asset('Escudos.png') }}"
                alt="Escudos oficiales del Estado de México y Seguridad">
        </header>

        <table>
            <tr class="name-row">
                <td class="label">NOMBRE</td>
                <td colspan="3">
                    <div class="value">{{ $elemento->nombre }}</div>
                </td>
            </tr>
            <tr class="compact-row">
                <td class="label">CLAVE SERVIDOR PÚBLICO</td>
                <td class="value-cell">
                    <div class="value">{{ $elemento->csp ?: ' ' }}</div>
                </td>
                <td class="label gender-label">GENERO</td>
                <td class="gender-cell">
                    <div class="value">{{ $elemento->genero ?: ' ' }}</div>
                </td>
            </tr>
            <tr class="name-row">
                <td class="label">UBICACIÓN</td>
                <td colspan="3">
                    <div class="value">{{ $elemento->ubicacion ?: ' ' }}</div>
                </td>
            </tr>
            <tr class="name-row">
                <td class="label">COORDINACIÓN</td>
                <td colspan="3">
                    <div class="value">{{ $elemento->coordinacion ?: ' ' }}</div>
                </td>
            </tr>
            <tr class="name-row">
                <td class="label">TIPO DE UNIFORME</td>
                <td colspan="3">
                    <div class="value">{{ $elemento->tipo_uniforme ?: ' ' }}</div>
                </td>
            </tr>
        </table>

        <div class="section-title">PRENDAS ASIGNADAS</div>
        <table class="color-row">
            <tr>
                <td class="label">COLOR/ FLANJA</td>
                <td class="value-cell">
                    <div class="value">{{ $elemento->color_franja ?: ' ' }}</div>
                </td>
            </tr>
        </table>

        <table class="garments">
            <thead>
                <tr>
                    <th class="garment-name">PRENDA</th>
                    <th>TALLAS</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="garment-name">CAMISOLA</td>
                    <td><span class="size">{{ $elemento->camisola ?: ' ' }}</span></td>
                    <td class="quantity">GORRA&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1</td>
                </tr>
                <tr>
                    <td class="garment-name">PANTALÓN</td>
                    <td><span class="size">{{ $elemento->pantalon ?: ' ' }}</span></td>
                    <td></td>
                </tr>
                <tr>
                    <td class="garment-name">BOTA</td>
                    <td><span class="size">{{ $elemento->bota ?: ' ' }}</span></td>
                    <td></td>
                </tr>
                <tr>
                    <td class="garment-name">CINTURÓN</td>
                    <td><span class="size">{{ $elemento->cinturon ?: ' ' }}</span></td>
                    <td></td>
                </tr>
                <tr>
                    <td class="garment-name">CHAMARRA</td>
                    <td><span class="size">{{ $elemento->chamarra ?: ' ' }}</span></td>
                    <td></td>
                </tr>
            </tbody>
        </table>

        <div class="legal">
            <p>RECIBÍ UN UNIFORME OPERATIVO POR PARTE DE LA SECRETARÍA DE SEGURIDAD DEL ESTADO DE MÉXICO, PARA EFECTO DE
                PARTICIPAR EN EL DESFILE CÍVICO DEL 16 DE SEPTIEMBRE DE 2026.</p>
            <p><strong>USARÉ MI VESTUARIO OFICIAL:</strong></p>
            <p>. SIEMPRE LIMPIO Y SIN RUPTURAS, NO REALIZARÉ ALTERACIÓN O MODIFICACIÓN ALGUNA Y ENTIENDO QUE ESTÁ
                ESTRICTAMENTE PROHIBIDO BORDAR O PEGAR CUALQUIER INSIGNIA O NOMBRE.</p>
            <p>. DEBIDO A QUE LOS UNIFORMES SON PERSONALIZADOS, POR NINGÚN MOTIVO PODRÉ TRANSFERIR O INTERCAMBIAR LAS
                PRENDAS, SU RESGUARDO Y ADECUADO EMPLEO ES RESPONSABILIDAD DE USTED.</p>
            <p>. EN CASO DE ROBO O EXTRAVÍO DE ALGUNA PRENDA, DEBERÁN INFORMAR A LA BREVEDAD A SU INMEDIATO SUPERIOR,
                DEBIENDO DE LEVANTAR EL ACTA CORRESPONDIENTE ANTE EL MINISTERIO PÚBLICO, LO ANTERIOR, TODA VEZ QUE USTED
                ES EL ÚNICO RESPONSABLE DEL MAL USO DE ESTE, QUEDANDO SUJETO A LAS SANCIONES ADMINISTRATIVAS Y LEGALES
                APLICABLES.</p>
            <p><strong>UNA VEZ CAUSANDO BAJA POR ALGÚN MOTIVO, ENTREGARÉ COMPLETO ESTE UNIFORME AL DEPARTAMENTO DE
                    ADQUISICIÓN Y SUMINISTROS DE LA OFICIALÍA MAYOR.</strong></p>
            <p class="care-title">INSTRUCCIONES DE CUIDADO:</p>
            <p>. LAVAR LAS PRENDAS POR SEPARADO, LAVAR EN MÁQUINA DE LAVADO CONVENCIONAL O A MANO (NO TALLAR CON FIBRAS
                LOS EMBLEMAS) UTILIZAR AGUA FRÍA A 30° C. NO USAR BLANQUEADOR, NO EXPRIMIR, SECAR COLGADO A LA SOMBRA,
                PLANCHAR A TEMPERATURA BAJA A 110° C. (NO PLANCHAR DIRECTAMENTE SOBRE LOS CÓDIGOS)</p>
        </div>

        <div class="signature">FIRMA DEL SERVIDOR PÚBLICO</div>
        <div class="identification">IDENTIFICACIÓN REALIZADA POR PERSONAL DEL ÁREA DE UNIFORMES.</div>
    </main>

    @if ($autoPrint)
        <script>
            window.addEventListener('load', () => window.print());
        </script>
    @endif
</body>

</html>
