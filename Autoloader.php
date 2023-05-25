<?php

namespace Apps;

class Autoloader
{
    static function register()
    {
        spl_autoload_register([
            __CLASS__,
            'autoload'
        ]);
    }

    static function autoload($class)
    {
        // Je récupère dans $class la totalité du namespace de la classe concernée (App\Client\Compte)
        // Je retire App\ (Client\Compte)
        $class = str_replace(__NAMESPACE__ . '\\', '', $class);
        
        // Je remplace les \ par des /
        $class = str_replace('\\', '/', $class);

        $fichier = __DIR__ . '/' . $class . '.php';
        // Je vérifie si le fichier existe
        if(file_exists($fichier)){
            require_once $fichier;
        }
    }
}