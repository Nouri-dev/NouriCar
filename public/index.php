<?php

use Apps\App\Routeur;
use Apps\Autoloader;



//Je definit la constante contenant le dossier racine du projet
define('ROOT', dirname(__DIR__));

//J'importe l'autoloader
require_once ROOT.'/Autoloader.php';
Autoloader::register();

// J' instaincie le routeur
$app = new Routeur();

// Je demarre l'application 
$app->start();

