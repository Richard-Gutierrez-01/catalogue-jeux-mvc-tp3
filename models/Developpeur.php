<?php
namespace App\Models;
use App\Models\CRUD;

// Modèle Developpeur 
class Developpeur extends CRUD {
    protected $table = 'developpeur';
    protected $primaryKey = 'id';
    protected $fillable = ['nom', 'pays_origine', 'date_fondation'];

    // Tous les jeux créés par ce développeur.
    public function jeux($developpeurId){
        $sql = "SELECT * FROM jeu WHERE developpeur_id = :developpeur_id ORDER BY nom ASC";
        $stmt = $this->prepare($sql);
        $stmt->bindValue(':developpeur_id', $developpeurId);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Suppression refusée tant que le développeur a des jeux.
    // Retourne false dans ce cas, sinon le résultat de delete().
    public function deleteDeveloppeur($id){
        if(count($this->jeux($id)) > 0){
            return false;
        }
        return $this->delete($id);
    }
}

?>
