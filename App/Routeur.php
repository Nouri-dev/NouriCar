<?php

namespace Apps\App;



use Apps\Controllers\MainController;
use Apps\Controllers\NotFoundController;


/**
 * Routeur principal
 */
class Routeur
{
   

    public function start()
    {
        session_start();

        // RETIRE LE DERNIER SLASH DE L'URL
        $uri = $_SERVER['REQUEST_URI'];

        // Vérification si uri n'est pas vide et se termine par un /
        if (!empty($uri) && $uri != '/' && $uri[-1] === "/") {
            $uri = substr($uri, 0, -1);

            // J'envoie un code de redirection permanente
            http_response_code(301);

            // Je redirige vers l'url sans le slash
            header('location: ' . $uri);
        }

        // Vérification de l'extension dans l'URL
         $extension = pathinfo($uri, PATHINFO_EXTENSION);
        if (!empty($extension)) {
            // Redirection vers la page d'accueil
            $notFoundController = new NotFoundController();
            $notFoundController->index();
        }
 
        // Je gère les paramètres de l'URL
        $params = isset($_GET['p']) ? explode('/', $_GET['p']) : [];

        if (isset($params[0]) && $params[0] != '') {
            // Au moins 1 paramètre
            // Je récupère le nom du contrôleur à instancier
            $controllerName = ucfirst(array_shift($params)) . 'Controller';
            $controllerClass = "\\Apps\\Controllers\\{$controllerName}";

            // Vérifie si le contrôleur existe
            if (!class_exists($controllerClass)) {
                $notFoundController = new NotFoundController();
                $notFoundController->index();
            } else {
                // J'instancie le contrôleur automatiquement
                $controller = new $controllerClass();

                // Je récupère le 2ème paramètre d'URL
                $action = (isset($params[0])) ? array_shift($params) : 'index';

                if (method_exists($controller, $action)) {
                    $ref = new \ReflectionMethod($controller, $action);
                    $numArgs = count($ref->getParameters());

                    // Vérifie si la méthode a besoin d'arguments et qu'aucun n'a été fourni
                    if ($numArgs > 0 && empty($params)) {
                        $notFoundController = new NotFoundController();
                        $notFoundController->index();
                    } elseif ($numArgs > 0 && !is_numeric($params[0])) {
                        $notFoundController = new NotFoundController();
                        $notFoundController->index();
                    } else {
                        (isset($params[0])) ? call_user_func_array([$controller, $action], $params) : $controller->$action();
                    }
                } else {
                    $notFoundController = new NotFoundController();
                    $notFoundController->index();
                }
            }
        } else {
            // J'instancie le contrôleur par défaut
            $controller = new MainController;
            // J'appelle la méthode index
            $controller->index();
        }
    }
}
