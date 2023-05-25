<?php

namespace Apps\Controllers;

abstract class Controller
{

    public function render(string $fichier, array $donnees = [], string $template = 'default')
    {
        //J'extrait le contenu de $donnees
        extract($donnees);

        ob_start();


        //creation du chemin vers la vue
        require_once ROOT . '/Views/' . $fichier . '.php';

        $contenu = ob_get_clean();

        require_once ROOT . '/Views/' . $template . '.php';
    }

}
