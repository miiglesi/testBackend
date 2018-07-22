<?php
namespace App\Models;

class User extends Model
{
    public function getuser($username)
    {
        try {
            $rst = $this->db->prepare("SELECT username, roles, password FROM users where username=:u");
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
    public function createHash()
    {
        // Plain-text password
        $password = 'Entrada00!!';
        $options = ['cost' => 12];
        $pass = password_hash($password, PASSWORD_DEFAULT, $options);
        if (password_verify('Entrada00!!', $pass)) {
            echo 'Password is valid!:' . $pass;
        } else {
            echo 'Invalid password.';
        }
    }
}
