<?php
namespace App\Controllers;

use App\Models\User;
use App\Providers\View;
use App\Providers\Validator;
use App\Providers\Auth;

// Contrôleur d'authentification : connexion et déconnexion
class AuthController {

    public function create(){
        if(isset($_SESSION['fingerPrint'])){
            return View::redirect('');
        }
        return View::render('auth/login');
    }

    public function store(array $data){
        $val = new Validator;
        $val->field('username', $data['username'] ?? '', 'courriel')->required()->email();
        $val->field('password', $data['password'] ?? '', 'mot de passe')->required();

        if($val->isSuccess()){
            $user = new User;
            if($user->checkUser($data['username'], $data['password'])){
                return View::redirect('');
            }
            // Même message que le compte existe ou non : révèle pas quels courriels sont inscrits
            $errors = ['login' => 'Courriel ou mot de passe incorrect'];
            return View::render('auth/login', ['errors' => $errors, 'user' => ['username' => $data['username']]]);
        }else{
            return View::render('auth/login', ['errors' => $val->getErrors(), 'user' => ['username' => $data['username'] ?? '']]);
        }
    }

    // Déconnexion : détruit la session 
    public function delete(){
        session_destroy();
        return View::redirect('login');
    }
}

?>
