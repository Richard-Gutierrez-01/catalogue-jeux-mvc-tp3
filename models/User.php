<?php
namespace App\Models;
use App\Models\CRUD;

// Modèle User : comptes admin et client, mots de passe hachés (bcrypt)
class User extends CRUD {
    protected $table = 'user';
    protected $primaryKey = 'id';
    protected $fillable = ['nom', 'username', 'password', 'privilege_id'];

    public function hashPassword(string $password, $cost = 10):string {
        $options = ['cost' => $cost];
        return password_hash($password, PASSWORD_BCRYPT, $options);
    }

    // Vérifie le login et crée la session. Message d'erreur volontairement identique
    // pour "utilisateur inconnu" et "mauvais mot de passe".
    public function checkUser(string $username, string $password):bool{
        $user = $this->unique('username', $username);
        if($user && password_verify($password, $user['password'])){
            session_regenerate_id();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['nom'];
            $_SESSION['user_username'] = $user['username'];
            $_SESSION['user_privilege'] = (int) $user['privilege_id'];
            $_SESSION['fingerPrint'] = md5($_SERVER['HTTP_USER_AGENT'].$_SERVER['REMOTE_ADDR']);
            return true;
        }
        return false;
    }
}

?>
