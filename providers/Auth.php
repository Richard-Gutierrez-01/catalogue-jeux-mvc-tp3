<?php
namespace App\Providers;
use App\Providers\View;

// Authentification et privilèges
// Privilèges : 1 = admin, 2 = client.
class Auth {

    // Page réservée aux utilisateurs connectés
    static public function session(){
        if(isset($_SESSION['fingerPrint']) and $_SESSION['fingerPrint'] == md5($_SERVER['HTTP_USER_AGENT'].$_SERVER['REMOTE_ADDR'])){
            return true;
        }else{
            return View::redirect('login');
        }
    }

    // Page réservée à un privilège (1 = admin)
    static public function privilege(int $id){
        if(isset($_SESSION['user_privilege']) and $_SESSION['user_privilege'] == $id){
            return true;
        }else{
            return View::redirect('login');
        }
    }
}

?>
