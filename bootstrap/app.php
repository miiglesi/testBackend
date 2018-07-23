<?php
/**
 *  FrameWork configuración
 *
 */

// Cargar dependencias de composer 
require __DIR__ . '/../vendor/autoload.php';

// Inicio Session
session_start();

// CERRAR SESION DESPUES DE 5 MINUTOS
$expire = 5; // MINUTOS PARA CERRR
//Checkear session de control
if (isset($_SESSION['last_action'])) {
    $secondsInactive = time() - $_SESSION['last_action'];
    //Minutos a segundos
    $expireAfterSeconds = $expire * 60;
    //Veririfico si ha pasado mucho tiempo
    if ($secondsInactive >= $expireAfterSeconds) {
        // destruyo las sesiones si ha pasado demasiado tiempo
        session_unset();
        session_destroy();
    }
}
//Ultima actividad
$_SESSION['last_action'] = time();


// Configuración de la app
$settings = require __DIR__ . '/../app/settings.php';


// Framework Slim
$app = new \Slim\App($settings);


// Dependencias del framework
require __DIR__ . '/../app/dependencies.php';


// Rutas
require __DIR__ . '/../app/routes.php';
