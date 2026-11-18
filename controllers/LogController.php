<?php
namespace App\Controllers;

use App\Models\Log;
use App\Providers\View;
use App\Providers\Auth;

// Journal de bord : consultation réservée à l'administrateur
class LogController {

    public function index(){
        Auth::privilege(1);
        $log = new Log;
        $logs = $log->select('id', 'DESC');
        return View::render('log/index', ['logs' => $logs]);
    }
}

?>
