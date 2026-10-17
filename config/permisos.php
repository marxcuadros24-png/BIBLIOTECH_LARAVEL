<?php

return [
    'administrador' => [
        'libros'        => ['ver', 'crear', 'editar', 'eliminar'],
        'categorias'    => ['ver', 'crear', 'editar', 'eliminar'],
        'usuarios'      => ['ver', 'crear', 'editar', 'eliminar'],
        'prestamos'     => ['ver', 'crear', 'devolver'],
        'multas'        => ['ver', 'pagar', 'condonar'],
        'reservas'      => ['ver', 'crear', 'gestionar', 'cancelar'],
        'configuracion' => ['ver', 'editar'],
    ],

    'bibliotecario' => [
        'libros'     => ['ver'],
        'categorias' => ['ver'],
        'prestamos'  => ['ver', 'crear', 'devolver'],
        'multas'     => ['ver', 'pagar'],
        'reservas'   => ['ver', 'gestionar', 'cancelar'],
    ],

    'estudiante' => [
        'libros'     => ['ver'],
        'categorias' => [],
        'prestamos'  => ['ver_propios'],
        'multas'     => ['ver_propias'],
        'reservas'   => ['ver_propias', 'crear', 'cancelar_propia'],
    ],
];