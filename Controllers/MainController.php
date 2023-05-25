<?php

namespace Apps\Controllers;


use Apps\Models\UsersModel;
use Apps\Models\ImagesModel;
use Apps\Models\AnnoncesModel;
use Apps\Controllers\NotFoundController;



class MainController extends Controller
{
    

    public function index()
    {
        $annoncesModel = new AnnoncesModel;
        $annonces = $annoncesModel->findAllOrderByCreatedAtDesc();
    
        // Pagination
        $articlesParPage = 4; // Nombre d'articles par page
        $pageActuelle = isset($_GET['page']) ? $_GET['page'] : 1;  // Récupère le numéro de la page actuelle depuis la requête GET
    
        // Vérifie si le numéro de page actuelle est vide ou non numérique
        if (empty($pageActuelle) || !is_numeric($pageActuelle)) {
            header('Location: /'); // Redirection vers la page d'accueil
            exit;
        } 
    
        $totalArticles = count($annonces);
        $totalPages = ceil($totalArticles / $articlesParPage); // Calcule le nombre total de pages
        $annonces = array_slice($annonces, ($pageActuelle - 1) * $articlesParPage, $articlesParPage); // Récupère les articles de la page actuelle
    
        // Vérifie si le numéro de page actuelle est valide
        if ($pageActuelle < 1 || ($pageActuelle > $totalPages && $totalPages > 0)) {
            header('Location: /'); // Redirection vers la page d'accueil
            exit;
        }

        // Récupère les données supplémentaires nécessaires
        $userModel = new UsersModel;
        $imagesModel = new ImagesModel;
    
        foreach ($annonces as $annonce) {
            $annonce->user = $userModel->find($annonce->users_id);
            $annonce->images = $imagesModel->findBy(['annonces_id' => $annonce->id]);
            $annonce->image = isset($annonce->images[0]) ? $annonce->images[0] : null;
        }
    
        if (!$annonces) {
            $_SESSION['annoncesVide'] = "Aucune annonce n'a été publiée 🥲";
        }
    
        $this->render('main/index', compact('annonces', 'totalPages', 'pageActuelle'));
    }
    



    /**
     * Affiche l'annonce
     *
     * @param integer $id
     * @return void
     */
    public function lire(int $id)
    {
        // J'instancie le modèle
        $annoncesModel = new AnnoncesModel;

        // Je récupère l'annonce correspondant à l'id
        $annonce = $annoncesModel->find($id);

        // Vérifie si l'annonce existe
        if (!$annonce) {
            // Si l'annonce n'existe pas, j'affiche la page d'erreur
            $notFoundController = new NotFoundController;
            $notFoundController->index();
        } else {
            // Si l'annonce existe, je récupère l'utilisateur qui a posté l'annonce
            $userModel = new UsersModel;
            $user = $userModel->find($annonce->users_id);

            // J'instancie le modèle ImagesModel
            $imagesModel = new ImagesModel;

            // Je récupère les images associées à l'annonce
            $images = $imagesModel->findBy(['annonces_id' => $annonce->id]);

            // Puis je génère la vue en passant l'annonce, l'utilisateur et les images
            $this->render('main/lire', compact('annonce', 'user', 'images'));
        }
    }
}
