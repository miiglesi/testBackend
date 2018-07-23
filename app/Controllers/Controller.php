<?php
namespace App\Controllers;
use App\Lib\Session;
use App\Controllers\Auth;
/**
 * Todos los controladoes extienden de esta clase
 */
class Controller
{
    protected $container;
    protected $response;
    public function __construct($container)
    {
        $this->container = $container;
        $this->vars = $this->combine($this->getSettingVars("page"), $this->getSettingVars("pagesRoles"));
    }
    public function __get($property)
    {
        if ($this->container->{$property}) {
            return $this->container->{$property};
        }
    }
    // Middleware
    public function Middleware($request, $response, $next)
    {
        $this->response=$response;
        // Coprobación de session
        if (!$this->authentication()->auth()) { // devuelvo al login al no tener session creada
            //$this->printArray($request->getUri()->getPath());die();
            //return $response->withHeader('Location', $this->router->pathFor("login"));
            $referer=ltrim($request->getUri()->getPath(), '/');
            $referer=(!empty($referer)) ? "?referer=".$referer:"";
            return $response->withHeader('Location', "/login".$referer);
        }
        // Defino los parametros de usuario
        $this->vars = $this->combine($this->vars, array("auth" => Session::get("USER")));
        $response = $next($request, $response);
        return $response;
    }
    // Auntentificación de usuarios
    public function authentication()
    {
        return new Auth($this->vars);
    }
    // HELPERS
    // extraer variables de entorno
    public function getSettingVars($vars) 
    {
        return array($vars => $this->container->get('settings')[$vars]);
    }
    // combinar arrays
    public function combine($arrayDeault, $arrayAdd) 
    {
        return array_merge($arrayDeault, $arrayAdd);
    }
    // DEBUG
    public function printArray($r)
    {
        echo "<pre>";
        print_r($r);
        echo "</pre>";
    }
}
