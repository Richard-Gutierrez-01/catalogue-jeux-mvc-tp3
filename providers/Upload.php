<?php
namespace App\Providers;

// Nouvelle fonctionnalité : téléversement d'images.
// Le fichier est validé (taille), renommé puis déplacé dans public/uploads/.
// Seul le nom du fichier est enregistré dans la base de données (colonne jeu.image).
class Upload {

    static public function hasFile($field){
        return isset($_FILES[$field]) && $_FILES[$field]['error'] != 4;
    }

    // Valide et enregistre l'image.
    // Retourne ['filename' => nom du fichier, 'error' => null] ou ['filename' => null, 'error' => message]
    static public function image($field){
        $file = $_FILES[$field];
        $folder = 'public/uploads/';
        $extensions = ['jpg', 'jpeg', 'png', 'webp'];

        if($file['error'] != 0){
            return ['filename' => null, 'error' => "Erreur lors du téléversement de l'image (2 Mo maximum)"];
        }
        if($file['size'] > 2097152){
            return ['filename' => null, 'error' => "L'image dépasse la taille maximale (2 Mo)"];
        }

        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if(!in_array($extension, $extensions)){
            return ['filename' => null, 'error' => 'Format non accepté (JPG, PNG ou WebP seulement)'];
        }

        // Nom unique : le nom envoyé par l'utilisateur n'est jamais utilisé
        $filename = uniqid().'.'.$extension;
        if(move_uploaded_file($file['tmp_name'], $folder.$filename)){
            return ['filename' => $filename, 'error' => null];
        }
        return ['filename' => null, 'error' => "Impossible d'enregistrer l'image sur le serveur"];
    }

    // Supprime un fichier image du dossier
    static public function delete($filename){
        if(!empty($filename) && file_exists('public/uploads/'.$filename)){
            unlink('public/uploads/'.$filename);
        }
    }
}

?>
