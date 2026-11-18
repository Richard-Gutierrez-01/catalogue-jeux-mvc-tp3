<?php
namespace App\Controllers;

use App\Models\Jeu;
use App\Models\Developpeur;
use App\Providers\View;
use App\Providers\Validator;
use App\Providers\Auth;
use App\Providers\Upload;

// Contrôleur Jeu : CRUD complet (index, show, create, store, edit, update, delete)
// Consultation publique, gestion réservée à l'admin, image téléversée pour chaque jeu
class JeuController {

    public function index(){
        $jeu = new Jeu;
        $jeux = $jeu->selectAvecDeveloppeur();
        if($jeux){
            return View::render('jeu/index', ['jeux' => $jeux]);
        }else{
            return View::render('error', ['msg' => 'Aucun jeu trouvé']);
        }
    }

    public function show($data = []){
        if(isset($data['id'])){
            $id = $data['id'];
            $jeu = new Jeu;
            $jeuInfo = $jeu->selectId($id);
            if($jeuInfo){
                $developpeurNom = null;
                if(!empty($jeuInfo['developpeur_id'])){
                    $developpeur = new Developpeur;
                    $dev = $developpeur->selectId($jeuInfo['developpeur_id']);
                    $developpeurNom = $dev ? $dev['nom'] : null;
                }
                $plateformes = $jeu->plateformes($id);
                return View::render('jeu/show', [
                    'jeu' => $jeuInfo,
                    'developpeurNom' => $developpeurNom,
                    'plateformes' => $plateformes
                ]);
            }else{
                return View::render('error', ['msg' => 'Jeu introuvable']);
            }
        }else{
            return View::render('error', ['msg' => 'Code 404 - Page introuvable']);
        }
    }

    public function create(){
        Auth::privilege(1);
        $developpeur = new Developpeur;
        $developpeurs = $developpeur->select('nom');
        return View::render('jeu/create', ['developpeurs' => $developpeurs]);
    }

    public function store(array $data){
        Auth::privilege(1);
        $val = new Validator;
        $val->field('nom', $data['nom'] ?? '')->required()->min(2)->max(100);
        $val->field('description', $data['description'] ?? '')->max(1000);
        $val->field('genre', $data['genre'] ?? '')->max(50);
        $val->field('note', $data['note'] ?? '')->float();
        $val->field('date_sortie', $data['date_sortie'] ?? '')->max(10);
        $val->field('developpeur_id', $data['developpeur_id'] ?? '', 'Développeur')->int();

        // L'image ne vient jamais du formulaire texte : on retire toute valeur envoyée à la main
        unset($data['image']);
        $erreurImage = null;
        if($val->isSuccess() && Upload::hasFile('image')){
            $upload = Upload::image('image');
            if($upload['error']){
                $erreurImage = $upload['error'];
            }else{
                $data['image'] = $upload['filename'];
            }
        }

        if($val->isSuccess() && !$erreurImage){
            $data['date_ajout'] = date('Y-m-d');
            foreach(['developpeur_id', 'date_sortie', 'note'] as $champ){
                if(!isset($data[$champ]) || $data[$champ] === ''){
                    $data[$champ] = null;
                }
            }
            $jeu = new Jeu;
            $insert = $jeu->insert($data);
            if($insert){
                return View::redirect('jeu/show?id='.$insert);
            }else{
                Upload::delete($data['image'] ?? null);
                return View::render('error', ['msg' => "Erreur lors de l'ajout du jeu"]);
            }
        }else{
            $errors = $val->getErrors() ?? [];
            if($erreurImage){
                $errors['image'] = $erreurImage;
            }
            $developpeur = new Developpeur;
            $developpeurs = $developpeur->select('nom');
            return View::render('jeu/create', ['errors' => $errors, 'jeu' => $data, 'developpeurs' => $developpeurs]);
        }
    }

    public function edit($data = []){
        Auth::privilege(1);
        if(isset($data['id'])){
            $id = $data['id'];
            $jeu = new Jeu;
            $jeuInfo = $jeu->selectId($id);
            if($jeuInfo){
                $developpeur = new Developpeur;
                $developpeurs = $developpeur->select('nom');
                return View::render('jeu/edit', ['jeu' => $jeuInfo, 'developpeurs' => $developpeurs]);
            }else{
                return View::render('error', ['msg' => 'Jeu introuvable']);
            }
        }else{
            return View::render('error', ['msg' => 'Code 404 - Page introuvable']);
        }
    }

    public function update(array $data, $get = []){
        Auth::privilege(1);
        if(!empty($get['id'])){
            $jeu = new Jeu;
            $ancien = $jeu->selectId($get['id']);
            if(!$ancien){
                return View::render('error', ['msg' => 'Jeu introuvable']);
            }

            $val = new Validator;
            $val->field('nom', $data['nom'] ?? '')->required()->min(2)->max(100);
            $val->field('description', $data['description'] ?? '')->max(1000);
            $val->field('genre', $data['genre'] ?? '')->max(50);
            $val->field('note', $data['note'] ?? '')->float();
            $val->field('date_sortie', $data['date_sortie'] ?? '')->max(10);
            $val->field('developpeur_id', $data['developpeur_id'] ?? '', 'Développeur')->int();

            // Sans nouvelle image, la colonne image n'est pas touchée (absente de $data)
            unset($data['image']);
            $erreurImage = null;
            $nouvelleImage = null;
            if($val->isSuccess() && Upload::hasFile('image')){
                $upload = Upload::image('image');
                if($upload['error']){
                    $erreurImage = $upload['error'];
                }else{
                    $nouvelleImage = $upload['filename'];
                    $data['image'] = $nouvelleImage;
                }
            }

            if($val->isSuccess() && !$erreurImage){
                foreach(['developpeur_id', 'date_sortie', 'note'] as $champ){
                    if(!isset($data[$champ]) || $data[$champ] === ''){
                        $data[$champ] = null;
                    }
                }
                $updated = $jeu->update($data, $get['id']);
                if($updated){
                    if($nouvelleImage){
                        Upload::delete($ancien['image']);   
                    }
                    return View::redirect('jeu/show?id='.$get['id']);
                }else{
                    Upload::delete($nouvelleImage);
                    return View::render('error', ['msg' => 'Erreur lors de la modification du jeu']);
                }
            }else{
                $errors = $val->getErrors() ?? [];
                if($erreurImage){
                    $errors['image'] = $erreurImage;
                }
                $data['id'] = $get['id'];
                $data['image'] = $ancien['image'];
                $developpeur = new Developpeur;
                $developpeurs = $developpeur->select('nom');
                return View::render('jeu/edit', ['errors' => $errors, 'jeu' => $data, 'developpeurs' => $developpeurs]);
            }
        }else{
            return View::render('error', ['msg' => 'Code 404 - Page introuvable']);
        }
    }

    public function delete($data){
        Auth::privilege(1);
        if(!empty($data['id'])){
            $jeu = new Jeu;
            $jeuInfo = $jeu->selectId($data['id']);
            $deleted = $jeu->deleteJeu($data['id']);
            if($deleted){
                if($jeuInfo){
                    Upload::delete($jeuInfo['image']);
                }
                return View::redirect('jeu');
            }else{
                return View::render('error', ['msg' => 'Erreur lors de la suppression du jeu']);
            }
        }else{
            return View::render('error', ['msg' => 'Code 404 - Page introuvable']);
        }
    }
}

?>
