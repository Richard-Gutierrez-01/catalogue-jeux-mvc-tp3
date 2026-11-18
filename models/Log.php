<?php
namespace App\Models;
use App\Models\CRUD;

// Modèle Log : journal de bord des visites (enregistré dans la base de données).
class Log extends CRUD {
    protected $table = 'log';
    protected $primaryKey = 'id';
    protected $fillable = ['ip', 'utilisateur', 'page'];

    // Enregistre une visite : IP, utilisateur, visiteur connecté et page demandée.
    // La date et l'heure sont posées par MySQL
    public function enregistrer($page){
        $utilisateur = 'Visiteur';
        if(isset($_SESSION['fingerPrint'])){
            $utilisateur = $_SESSION['user_username'];
        }
        $this->insert([
            'ip' => $_SERVER['REMOTE_ADDR'],
            'utilisateur' => $utilisateur,
            'page' => $page
        ]);
    }
}

?>
