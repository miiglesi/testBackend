<?php
/**
 *  Añadir dependencias de SlIM
 *
 */
// Contenedor Slim
$container = $app->getContainer();
// Defino las vistas de Twig
$container['view'] = function ($container) {
    $cf = $container->get('settings')['view'];
    $view = new \Slim\Views\Twig($cf['path'], $cf['twig']);
    $view->addExtension(new Twig_Extension_Debug());
    $view->addExtension(new \Slim\Views\TwigExtension(
        $container->router,
        $container->request->getUri()
    ));
    return $view;
};
// Controladores 
$container['HomeController'] = function ($container) {
    return new \App\Controllers\HomeController($container);
};
$container['LoginController'] = function ($container) {
    return new \App\Controllers\LoginController($container);
};
$container['AdminController'] = function ($container) {
    return new \App\Controllers\AdminController($container);
};
// 404 template - Error de páginas no enontrass
$container['notFoundHandler'] = function ($container) {
    return function ($request, $response) use ($container) {
        $settings = $container->get('settings')["page"];
        return $container['view']->render($response->withStatus(404), '404.twig', array("page" => $settings));
    };
};
// BBDD -> Conexion de Base de datos. Configuracion en /app/setting
$container['db'] = function ($container) {
    try {
        $settings = $container->get('settings')['db'];
        $conn = new PDO(
            'mysql:host=' . $settings['host'] . ';port=' . $settings['port'] . ';dbname=' . $settings['dbname'],
            $settings['user'],
            $settings['pass'],
            //array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES UTF8, lc_time_names = 'es_ES'"),
            array(
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES UTF8, lc_time_names = 'es_ES'",
                PDO::ATTR_TIMEOUT => 15,
            )
        );
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch (PDOException $e) {
        // Excepción en caso de estar mal la configuración
        die('La conexión a bbdd no se ha podido realizar. Compruebe la configuración.');
    }
};
