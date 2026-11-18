<?php
namespace App\Models;
use App\Models\CRUD;

// Modèle Privilege : les rôles (1 = admin, 2 = client)
class Privilege extends CRUD {
    protected $table = 'privilege';
    protected $primaryKey = 'id';
    protected $fillable = ['privilege'];
}

?>
