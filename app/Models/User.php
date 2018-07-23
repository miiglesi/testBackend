<?php
namespace App\Models;

class User extends Model
{
    public function getUser($username)
    {
        try {
            $rst = $this->db->prepare("SELECT iduser, username, roles, password FROM users where username=:u");
            $rst->execute(array(":u" => $username));
            $row = $rst->fetch(\PDO::FETCH_ASSOC);
            if (count($row) == 0) {
                return false;
            }
            return $row;
        } catch (PDOException $e) {
            die('Error BBDD');
        }
    }
    public function getAllUser()
    {
        try {
            $rst = $this->db->prepare("SELECT iduser, username, roles FROM users");
            $rst->execute(array());
            $row = $rst->fetchall(\PDO::FETCH_ASSOC);
            if (count($row) == 0) {
                return false;
            }
            return $row;
        } catch (PDOException $e) {
            die('Error BBDD');
        }
    }
    public function updateUser($u, $p, $r, $s)
    {
        $r = implode(",", $r);
        $options = ['cost' => 12];
        $pass = password_hash($p, PASSWORD_DEFAULT, $options);
        $params = array(
            ":u" => $u,
            ":p" => $pass,
            ":r" => $r,
            ":iduser" => $s,
        );
        // Evito duplicar nombres
        $user = $this->getUser($u);
        if ($user) {
            if ($user['iduser'] !== $s) {
                return -1;
            }
        }
        try {
            if (empty($p)) {
                unset($params[":p"]);
                $rst = $this->db->prepare("UPDATE users SET username = :u, roles = :r WHERE iduser = :iduser");
                $rst->execute($params);
            } else {
                $rst = $this->db->prepare("UPDATE users SET username = :u, password = :p, roles = :r WHERE iduser = :iduser");
                $rst->execute($params);
            }
        } catch (Exception $e) {
            echo 'Excepción capturada: ', $e->getMessage(), "\n";
        }
        return $rst->rowCount();
        $rst = null;
    }
    public function insertUser($u, $p, $r)
    {
        $r = implode(",", $r);
        $pass = password_hash(trim($p), PASSWORD_DEFAULT);
        $params = array(
            ":u" => $u,
            ":p" => $pass,
            ":r" => $r,
        );
        // Evito duplicar nombres
        $user = $this->getUser($u);
        if ($user) {
            return -1;
        }
        try {
            $rst = $this->db->prepare("INSERT INTO users (username, password, roles ) VALUES (:u, :p, :r)");
            $rst->execute($params);
            return $rst->rowCount();
        } catch (Exception $e) {
            echo 'Excepción capturada: ', $e->getMessage(), "\n";
        }
    }
    public function deletetUser($u)
    {
        // Compruebo que existe en la BBDD
        $user = $this->getUser($u);
        if (!$user) {
            return -1;
        }
        try {
            $rst = $this->db->prepare("DELETE FROM users where username = :u");
            $rst->execute($params = array(
                ":u" => $u           
            ));
            return $rst->rowCount();
        } catch (Exception $e) {
            echo 'Excepción capturada: ', $e->getMessage(), "\n";
        }
    }
    

}
