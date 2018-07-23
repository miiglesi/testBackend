<?php
/**
 *  Archivo de configuración
 *
 */
return [
    'settings' => [
        'displayErrorDetails' => true,
        /* SLIM - TWIG */
        'view' => [
            'path' => __DIR__ . '/Views',
            'debug' => true,
            'twig' => [
                'cache' => false,
            ],
        ],
        // DATOS SITIO, TITULO, DESCRIPCION...
        'page' => [
            'title' => 'Test Backend',
            'description' => 'Prueba técnica para backends (PHP)',
        ],
        // Conexión de BBDD
        "db" => [
            "host" => "127.0.0.1",
            "dbname" => "testbackend",
            "user" => "root",
            "pass" => "root",
            "port" => "8889",
        ],
        // Tipos de roles del sistema
        "roles" => [
            1 => "ADMIN",
            2 => "PAGE_1",
            3 => "PAGE_2",
            4 => "PAGE_3",
        ],
        //  PAGINAS - PERMISOS - RUTAS
        "pagesRoles" => [
            "home" => [
                "path" => "/",
                "permission" => [2, 3, 4],
            ],
            "pagina_1" => [
                "path" => "/pagina_1",
                "permission" => [2],
            ],
            "pagina_2" => [
                "path" => "/pagina_2",
                "permission" => [3],
            ],
            "pagina_3" => [
                "path" => "/pagina_3",
                "permission" => [4],
            ],
            "admin" => [
                "path" => "/admin",
                "permission" => [1, 2, 3, 4]
            ],
            "logout" => [
                "path" => "/logout"],
        ],
    ],
];
