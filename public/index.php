<?php
/**
 *  Parte pública, APP init
 *
 */
error_reporting(E_ALL ^ E_NOTICE);
ini_set('display_errors', 1);

// Cargo aplicación
require __DIR__ . '/../bootstrap/app.php';
// Ejecutar aplicación 
$app->run();