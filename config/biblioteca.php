<?php

return [
    'dias_prestamo' => (int) env('DIAS_PRESTAMO', 7),
    'multa_por_dia' => (float) env('MULTA_POR_DIA', 1.50),
    'moneda' => env('MONEDA', 'S/'),
    'dias_alerta_mora' => 3,
];
