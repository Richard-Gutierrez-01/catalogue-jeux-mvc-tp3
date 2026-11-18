<?php
namespace App\Controllers;

use App\Models\User;
use App\Providers\View;
use App\Providers\Validator;

// Contrôleur des utilisateurs : inscription publique (toujours un client)
class UserController {

    public function register(){
        if(isset($_SESSION['fingerPrint'])){
            return View::redirect('');
        }
        return View::render('user/register');
    }

    public function storeRegister(array $data){
        $val = new Validator;
        $val->field('nom', $data['nom'] ?? '')->required()->min(2)->max(100);
        $val->field('username', $data['username'] ?? '', 'courriel')->required()->email()->max(150)->unique('User');
        $val->field('password', $data['password'] ?? '', 'mot de passe')->required()->min(8)->max(72);

        if($val->isSuccess()){
            $user = new User;
            $insert = $user->insert([
                'nom' => $data['nom'],
                'username' => $data['username'],
                'password' => $user->hashPassword($data['password']),
                'privilege_id' => 2  
            ]);
            if($insert){
                $user->checkUser($data['username'], $data['password']);
                return View::redirect('');
            }
            return View::render('error', ['msg' => "Erreur lors de la création du compte"]);
        }else{
            return View::render('user/register', ['errors' => $val->getErrors(), 'user' => $data]);
        }
    }
}

?>
