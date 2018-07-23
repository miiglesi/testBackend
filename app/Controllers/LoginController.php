<?php
/**
 *  Controlador para login
 *
 */
namespace App\Controllers;
use App\Lib\Session;
use App\Models\User;
class LoginController extends Controller
{
    // GET LOGIN
    public function loginGet($request, $response)
    {
        // SI YA HAY SESSION DEJO ENTRAR
        if ($this->authentication()->auth()) {
            return $response->withHeader('Location', $this->router->pathFor("home"));
        }
        $this->vars = $this->combine($this->vars, array("GET" => $request->getQueryParams()));
        return $this->view->render($response, 'login.twig', $this->vars);
    }
    // POST LOGIN
    public function loginPost($request, $response, $args)
    {
        // REQUEST FORM
        $username = trim($request->getParam('username'));
        $password = trim($request->getParam('password'));
        $referer = trim($request->getParam('referer'));
        // VALIDAR
        $error = array();
        if (empty($username) || (empty($password))) {
            $error["msg"] = 'Usuario / Password son requeridos.';
        } else {
            // MODELO USER
            $user = new User($this->db);
            // BUSCO EL USUARIO
            $user = $user->getUser($username);
            if (!$user) {
                $error["msg"] = 'El usuario no es válido.';
            } else {
                // VERIFICO LA PASSWORD
                if (!password_verify(trim($password), $user["password"])) {
                    $error["msg"] = 'Los datos de acceso no son correctos.';
                }
            }
        }
        // COPRUEBO QUE TODO ES OK
        if ($error["msg"]) {
            $this->vars = $this->combine($this->vars, array("GET" => $request->getQueryParams()));
            $this->vars = $this->combine($this->vars, array("POST" => $request->getParsedBody()));
            $this->vars = $this->combine($this->vars, array("error" => $error));
            return $this->view->render($response, 'login.twig', $this->vars);
        } else {
            // CREO LA SESSION DEL USUARIO
            Session::set("USER", array("iduser" => $user["iduser"], "username" => $user["username"], "roles" => $user["roles"]));
            // Si hay referer le devuelvo a su url
            if (!empty($referer)) {
                return $response->withHeader('Location', "/" . $referer);
            } else {
                return $response->withHeader('Location', $this->router->pathFor("home"));
            }
        }
    }
    // Cerrar session
    public function logout($request, $response)
    {
        $this->authentication()->destroy();
        return $response->withHeader('Location', $this->router->pathFor("login"));
    }
}
