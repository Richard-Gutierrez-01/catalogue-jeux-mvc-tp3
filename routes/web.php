<?php
use App\Routes\Route;

// Table de routage de l'application Catalogue de jeux vidéo
// Accès : (public) visiteurs (client) connecté (admin) administrateur seulement

// Pages publiques
Route::get('/', 'HomeController@index');
Route::get('/home', 'HomeController@index');

// Jeu : consultation publique, gestion admin
Route::get('/jeu', 'JeuController@index');
Route::get('/jeu/show', 'JeuController@show');
Route::get('/jeu/create', 'JeuController@create');          
Route::post('/jeu/create', 'JeuController@store');          
Route::get('/jeu/edit', 'JeuController@edit');              
Route::post('/jeu/edit', 'JeuController@update');           
Route::post('/jeu/delete', 'JeuController@delete');         

// Developpeur : consultation publique, gestion admin
Route::get('/developpeur', 'DeveloppeurController@index');
Route::get('/developpeur/show', 'DeveloppeurController@show');
Route::get('/developpeur/create', 'DeveloppeurController@create');   
Route::post('/developpeur/create', 'DeveloppeurController@store');   
Route::get('/developpeur/edit', 'DeveloppeurController@edit');       
Route::post('/developpeur/edit', 'DeveloppeurController@update');    
Route::post('/developpeur/delete', 'DeveloppeurController@delete');  

// Plateforme : consultation publique, gestion admin
Route::get('/plateforme', 'PlateformeController@index');
Route::get('/plateforme/show', 'PlateformeController@show');
Route::get('/plateforme/create', 'PlateformeController@create');     
Route::post('/plateforme/create', 'PlateformeController@store');     
Route::get('/plateforme/edit', 'PlateformeController@edit');        
Route::post('/plateforme/edit', 'PlateformeController@update');      
Route::post('/plateforme/delete', 'PlateformeController@delete');    

// Authentification
Route::get('/login', 'AuthController@create');
Route::post('/login', 'AuthController@store');
Route::get('/logout', 'AuthController@delete');

// Inscription publique (crée toujours un compte client)
Route::get('/register', 'UserController@register');
Route::post('/register', 'UserController@storeRegister');

// Journal de bord : admin
Route::get('/log', 'LogController@index');

Route::dispatch();
