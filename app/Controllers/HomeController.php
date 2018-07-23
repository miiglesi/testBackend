<?php
/**
 *  Controlador para home y paginas dinamicas
 *
 */
namespace App\Controllers;
class HomeController extends Controller
{
    // Inicio el controlador
    public function index($request, $response, $args)
    {
        // Capturo ruta y parametros de ruta
        $route = $request->getAttribute('route')->getName();
        $title = $route;
        // Si son páginas dinamicas creo un titulo asigno parametro
        if ($route == 'paginas' && isset($args["id"])) {
            $route = "pagina_" . $args["id"];
            $title = "PÁGINA " . $args["id"];
        }
        // Si no esta definida en /app/Setting -> error 404
        if (!$this->authentication()->foundUrl($route)) {
            return $this->view->render($response->withStatus(404), '404.twig', $this->vars);
        }
        // Compruebo los permisos de roles y páginas
        $permission = $this->authentication()->hasPermisission($route);
        $this->vars["permissions"] = $permission;
        // Error de permisos, lanzo mensaje
        if (!isset($permission["check"])) {
            $error["msg"] = 'Lo siento pero no tiene los permisos requeridos.';
            $this->vars = $this->combine($this->vars, array("error" => $error));
        }
        // Defino título de página para pasar a la vista
        $this->vars["page"]["active"]["name"] = strtoupper($title);
        // Estado http. Si no es autorizado asigno 403
        $status = (!isset($permission["check"])) ? $response->withStatus(403) : $response;
        // renderizo la plantilla
        return $this->view->render($status, 'home.twig', $this->vars);
    }
}
