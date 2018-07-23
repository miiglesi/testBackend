<?php
/**
 *  Control de usuario y permisos
 *
 */
namespace App\Controllers;
use App\Lib\Session;
class Auth
{
    protected $vars;
    public function __construct($vars)
    {
        $this->vars = $vars;
    }
    // Comprobación de permisos de pagina y admin
    public function hasPermisission($route)
    {
        $userRoles = explode(",", $this->vars["auth"]["roles"]);
        $pageRoles = $this->vars["pagesRoles"][$route]["permission"];
        $permission = array();
        // extraigo permisos de página
        if (array_intersect($userRoles, $pageRoles)) {
            $permission["check"] = true;
        }
        // si es un admin role
        if (in_array(1, $userRoles)) {
            $permission["isAdmin"] = true;
            $permission["check"] = true;
        }
        return $permission;
    }
    // detectar pagina
    public function foundUrl($route)
    {
        return $this->vars["pagesRoles"][$route];
    }
    // COMPROBAR SESSION
    public function auth()
    {
        return Session::exists("USER");
    }
    // DESTRUIR SESSION
    public function destroy()
    {
        Session::destroy("USER");
    }
}
