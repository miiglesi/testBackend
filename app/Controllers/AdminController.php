<?php

/**
 *  Controlador para la parte admin
 *
 */

namespace App\Controllers;

use App\Lib\Session;
use App\Models\User;

class AdminController extends Controller
{
    // INIT CONTROLADOR
    public function index($request, $response, $args)
    {
        // Si es página dinamica capturo argumentos
        $route = $request->getAttribute('route')->getName();
        $title = $route;
        // Si no esta definida en setting -> error 404
        if (!$this->authentication()->foundUrl($route)) {
            return $this->view->render($response->withStatus(404), '404.twig', $this->vars);
        }
        // Compruebo los permisos de Admin y página
        $permission = $this->authentication()->hasPermisission($route);
        $this->vars["permissions"] = $permission;
        // Error de permisos
        if (!isset($permission["check"])) {
            $error["msg"] = 'Lo siento pero no tiene los permisos requeridos.';
            $this->vars = $this->combine($this->vars, array("error" => $error));
        }
        // Defino título de pagina
        $this->vars["page"]["active"]["name"] = strtoupper($title);
        // Actualizo Estado http si es denegado
        $status = (!isset($permission["check"])) ? $response->withStatus(403) : $response;
        $this->vars = $this->combine($this->vars, $this->crud($args, $request, $response));
        // renderizo la plantilla y corpruebo errores
        if (isset($this->vars["error"])) {
            return $this->view->render(
                (isset($this->vars["error"]['status'])) ? $response->withStatus($this->vars["error"]['status']) : $response->withStatus(404),
                (!isset($this->vars["error"]['template'])) ? 'home.twig' : $this->vars["error"]['template'],
                $this->vars
            );
        }
        // Extraigo variables post para la vista form
        $this->vars = $this->combine($this->vars, array("POST" => $request->getParsedBody()));
        // renderizo la plantilla
        return $this->view->render($status, 'home.twig', $this->vars);
    }

    // Enrutador funciones dinamicas read, update, insert, delete

    public function crud($args, $request, $response)
    {
        if (isset($args["crud"])) {
            $nombre = $args["crud"];
            if (method_exists($this, $nombre)) {
                return $this->$nombre($args, $request, $response);
            } else {
                $error["status"] = 404;
                $error["template"] = '404.twig';
                $error["msg"] = 'Lo siento pero no tiene los permisos requeridos.';
                return array("error" => $error);
            }
        }
        return $this->read($args);
    }
    // INSERT
    public function add($args, $request, $response)
    {
        // COMPRUEBO PERMISOS
        if (!$this->vars["permissions"]["isAdmin"]) {
            $error["status"] = 403;
            $error["msg"] = 'Lo siento pero no tiene los permisos requeridos.';
            return array("error" => $error);
        }
        // Si hay actualización
        $s = $request->getParam('send'); // iduser y control de formulario
        if (isset($s)) {
            $u = $request->getParam('username');
            $p = $request->getParam('password');
            $r = $request->getParam('roles');
            $validate = $this->validate($u, $p, $r, $s);
            if (!$validate) {
                $users = new User($this->db);
                $insert = $users->insertUser($u, $p, $r);
                if ($insert === -1) {
                    $crud["error"]["msg"] = "Operación no válida";
                }
                if ($insert > 0) {
                    header('Location: /admin');
                    exit;
                }
                if ($update === 0) {
                    header('Location: /admin');
                    exit;
                }
            } else {
                $crud["error"] = $validate;
            }
        }
        // DEFINO EL TIPO EN LA VISTA Y LOS ROLES
        $crud["tipo"] = "add";
        $crud["rolesform"] = $this->rolesForm();
        return array("crud" => $crud);
    }
    // UPDATE
    public function edit($args, $request, $response)
    {
        // COMPRUEBO PERMISOS
        // compruebo que hay parametros y que es administrador
        if (!isset($args["userId"]) || !$this->vars["permissions"]["isAdmin"]) {
            $error["status"] = 403;
            $error["msg"] = 'Lo siento pero no tiene los permisos requeridos.';
            return array("error" => $error);
        }
        // EL MDOELO
        $users = new User($this->db);
        // saco usuario
        $user = $users->getUser($args["userId"]);
        // si no existe lanzo error
        if (!$user) {
            $error["status"] = 404;
            $error["template"] = '404.twig';
            return array("error" => $error);
        }
        // Si hay parametros, hago el update
        $s = $request->getParam('send'); // iduser y control de formulario
        if (isset($s)) {
            $u = $request->getParam('username');
            $p = $request->getParam('password');
            $r = $request->getParam('roles');
            $validate = $this->validate($u, $p, $r, $s);
            if (!$validate) {
                $update = $users->updateUser($u, $p, $r, $s);
                if ($update === -1) {
                    $crud["error"]["msg"] = "Operación no válida";
                }
                if ($update > 0) {
                    if ($this->vars["auth"]["username"] === $args["userId"]) {
                        $user = $users->getUser($u);
                        Session::set("USER", array("iduser" => $user["iduser"], "username" => $user["username"], "roles" => $user["roles"]));
                    }
                    header('Location: /admin');
                    exit;
                }
                if ($update === 0) {
                    header('Location: /admin');
                    exit;
                }
            } else {
                $crud["error"] = $validate;
            }
        }
        // paso los datos a la vista
        $crud["tipo"] = "edit";
        $crud["user"] = $user;
        $crud["rolesform"] = $this->rolesForm();
        return array("crud" => $crud);
    }
    // LECTURA
    public function read($args)
    {
        $model = new User($this->db);
        $user = $model->getUser($this->vars["auth"]["username"]);
        $crud["tipo"] = "read";
        $crud["user"] = $user;
        $crud["rolesform"] = $this->rolesForm();
        // si es admin saco los usuarios
        if ($this->vars["permissions"]["isAdmin"]) {
            $allUser = $model->getAllUser();
            $crud["allUsers"] = $allUser;
        }
        return array("crud" => $crud);
    }
    // DELETE
    public function delete($args, $request, $response)
    {
        // COMPRUEBO PERMISOS
        // compruebo que hay parametros y que es administrador
        if (!isset($args["userId"]) || !$this->vars["permissions"]["isAdmin"]) {
            $error["status"] = 403;
            $error["msg"] = 'Lo siento pero no tiene los permisos requeridos.';
            return array("error" => $error);
        }
        // EL MDOELO
        $users = new User($this->db);
        // get usuario de la bbdd
        $user = $users->getUser($args["userId"]);
        // si no existe lanzo error
        if (!$user) {
            $crud["error"]["msg"] = "El usuario no existe.";
        }
        // me aseguro que no puede borrar su propio usuario
        if ($args["userId"] == $this->vars["auth"]["username"]) {
            $crud["error"]["msg"] = "No puedes borrar tu usuario.";
        }
        // si no hay errores
        if (!$crud["error"]["msg"]) {
            // Delete
            $delete = $users->deletetUser($args["userId"]);
            if ($delete === -1) {
                $crud["error"]["msg"] = "Operación no válida";
            }
            if ($delete > 0) {
                $crud["success"]["msg"] = "Usuario borrado correctamente";
            }
        }
        // paso los datos a la vista
        $crud["tipo"] = "delete";
        $crud["rolesform"] = $this->rolesForm();
        // listado
        // si es admin saco los usuarios para el listado
        if ($this->vars["permissions"]["isAdmin"]) {
            $allUser = $users->getAllUser();
            $crud["allUsers"] = $allUser;
        }
        return array("crud" => $crud);
    }

    // LISTA DE ROLES PARA VISTA
    public function rolesForm()
    {
        $roles = $this->getSettingVars("roles");
        return $roles["roles"];
    }

    // VALIDAR FORMRMULARIOS
    public function validate($u, $p, $r, $s)
    {
        // VALIDAR NOMBRE DE USUARIO,SI ESPACIOS
        if (preg_match('/[\s\t]+/', $u) || empty($u) || (strlen($u) < 3 || strlen($u) > 100)) {
            $error["msg"] = 'El nombre de usuario no es correctoss.';
            return array("msg" => $error["msg"]);
        }
        // VALIDAR PASSWORD, SOLO SI ES INSERT Y NO ESTA VACIO
        if (empty($p) && $s == 0) {
            $error["msg"] = 'La password es requerida.';
            return array("msg" => $error["msg"]);
        }
        // EXPRESIÓN REGULAR PARA ASEGURAR UNA STRONG - PASSWORD
        if (!empty($p)) { // update pero no quiere cambiar de password
            if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[$@$!%*?&])[A-Za-z\d$@$!%*?&]{8,10}/', $p)) {
                $error["msg"] = 'La password no es correcta<br>' .
                    'Debería tener al menos una letra en mayúscula.<br>' .
                    'Al menos una letra minúscula.<br>' .
                    'Además, al menos un valor numérico.' .
                    'Y un caracter especial.<br>' .
                    'Debe tener más de 6 caracteres de largo.';
                return array("msg" => $error["msg"]);
            }
        }
        // COMPRUEBO QUE AL MENOS TENGA UN ROL
        if (empty($r)) {
            $error["msg"] = 'Debe asignar al menos un Rol.';
            return array("msg" => $error["msg"]);
        } else {
            return false;
        }
    }
}
