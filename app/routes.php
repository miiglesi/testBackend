<?php
/**
 *  Gestión de Rutas
 *
 */
// HOME
$app->get('/', 'HomeController:index')->add('HomeController:Middleware')->setName('home');
// Páginas dinamicas: /pagina_1, /pagina_2  ....
$app->get('/pagina_{id}', 'HomeController:index')->add('HomeController:Middleware')->setName('paginas');
// LOGIN
$app->get('/login', 'LoginController:loginGet')->setName('login');
$app->post('/login', 'LoginController:loginPost')->setName('login');
// LOGOUT
$app->get('/logout', 'LoginController:logout')->setName('logout');
// GRUPO ADMIN
$app->group('', function() {

    // Admin Home
    $this->get( '/admin', 'AdminController:index')->setName('admin');
    // insert user
    $this->get( '/admin/{crud}', 'AdminController:index')->setName('admin');
    $this->post( '/admin/{crud}', 'AdminController:index')->setName('admin');
    // Delete - Update user
    $this->get( '/admin/{crud}/{userId}', 'AdminController:index')->setName('admin'); 
    // POST PARA UPDATE
    $this->post( '/admin/{crud}/{userId}', 'AdminController:index')->setName('admin');


})->add('AdminController:Middleware');