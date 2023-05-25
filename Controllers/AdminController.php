<?php

namespace Apps\Controllers;

use Apps\Models\UsersModel;
use Apps\Models\ImagesModel;
use Apps\Models\AnnoncesModel;



class AdminController extends Controller
{


    public function index()
    {
        if ($this->isAdmin()) {
            $this->render('admin/index');
        }
    }

    /**
     * Affiche la liste des annonces sous forme de tableau
     * @return void 
     */
    public function annonces()
    {
        if ($this->isAdmin()) {
            $annoncesModel = new AnnoncesModel;

            $annonces = $annoncesModel->findAll();
            if (!$annonces) {
                $_SESSION['annoncesVide'] = "Aucune annonce n'a été publiée.";
            }

            $this->render('admin/annonces', compact('annonces'));
        }
    }

    /**
     * Fonction qui Affiche la liste des utilisateurs
     *
     * @return void
     */
    public function utilisateurs()
    {
        if ($this->isAdmin()) {
            $usersModel = new UsersModel;

            $users = $usersModel->findAll();

            $this->render('admin/utilisateurs', compact('users'));
        }
    }

    /**
     * Fonction qui verifie si l'utilisateur est admin
     *
     * @return boolean
     */
    private function isAdmin()
    {

        if (!isset($_SESSION['user']['roles'])) {
            $_SESSION['user']['roles'] = array();
        }

        if (isset($_SESSION['user']) && in_array('ROLE_ADMIN', $_SESSION['user']['roles'])) {
            return true;
        } else {
            $_SESSION['erreur'] = "Vous n'avez pas accés a cette zone";
            header('Location: /');
            exit;
        }
    }


    /**
     * Supprime un utilisateur par son id et ses annonces si est admin
     *
     * @param integer $id
     * @return void
     */
    public function supprimeUser(int $id)
    {
        if ($this->isAdmin()) {
            $user = new UsersModel;

            // Je récupère les annonces liées à l'utilisateur
            $annonces = new AnnoncesModel;
            $annoncesList = $annonces->findby(['users_id' => $id]);

            // Je supprime les annonces
            foreach ($annoncesList as $annonce) {
                $annonces->delete($annonce->id);
            }

            // Enfin je supprime l'utilisateur
            $user->delete($id);

            $_SESSION['erreur'] = "L'utilisateur a bien été supprimé, ainsi que toutes ses annonces.";
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        }
    }


    /**
     * Supprime une annonce et les images associées(dans le dossier aussi) si admin.
     *
     * @param integer $id L'identifiant de l'annonce à supprimer.
     * @return void
     */
    public function supprimeAnnonce(int $id)
    {
        if ($this->isAdmin()) {
            $annonce = new AnnoncesModel;
            $image = new ImagesModel;

            // Récupérer le nom des images associées à l'annonce
            $images = $image->findNameAnnonce($id);
            // Supprimer l'annonce
            $annonce->delete($id);
            // Vérifier si des images sont associées à l'annonce
            if (!empty($images)) {
                foreach ($images as $imageItem) {
                    $nomImage = $imageItem['nom'];
                    $cheminImage = ROOT . '/public/uploads/' . $nomImage;
                    // Supprimer les images du dossier "public/uploads"
                    if (file_exists($cheminImage)) {
                        unlink($cheminImage);
                    }
                }
            }
            $_SESSION['erreur'] = "Votre annonce a bien été supprimée";
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        }
    }



    /**
     * Active ou désactive une annonce
     * @param int $id 
     * @return void 
     */
    public function activeAnnonce(int $id)
    {
        if ($this->isAdmin()) {
            $annoncesModel = new AnnoncesModel;

            $annonceArray = $annoncesModel->find($id);

            if ($annonceArray) {
                $annonce = $annoncesModel->hydrate($annonceArray);

                $annonce->setActif($annonce->getActif() ? 0 : 1);

                $annonce->update();
            }
        }
    }
}
