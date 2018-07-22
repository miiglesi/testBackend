<?php


// Creamos las RUTAS
$app->get('/', 'HomeController:pages')->add('HomeController:Middleware')->setName('home');
$app->get('/pagina_{id}', 'HomeController:pages')->add('HomeController:Middleware')->setName('paginas');


$app->get('/login', 'LoginController:loginGet')->setName('login');
$app->post('/login', 'LoginController:loginPost')->setName('login');
$app->get('/logout', 'LoginController:logout')->setName('logout');

$app->group('', function() {
    $this->get( '/admin', 'HomeController:pages')->setName('admin');
    $this->get( '/admin/add', 'HomeController:pages')->setName('admin');
    $this->get( '/admin/edit/{userId}', 'HomeController:pages')->setName('admin');
})->add('HomeController:Middleware');