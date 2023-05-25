<?php


namespace Apps\Controllers;

use Apps\App\Form;
use Apps\App\ImageUtils;
use Apps\Models\ImagesModel;
use Apps\Models\AnnoncesModel;


class ProfilController extends Controller
{

    /**
     * Methode affichant les annonces de la db
     *
     * @return void
     */
    public function index()
    {

        $annoncesModel = new AnnoncesModel;

        if (isset($_SESSION['user']['id'])) {
            $userId = $_SESSION['user']['id'];
            $annonces = $annoncesModel->findBy(['users_id' => $userId]);
            if (!$annonces) {
                $_SESSION['annoncesVide'] = "Vous n'avez pas encore publié d'annonce.";
            }
        } else {
            $_SESSION['erreur'] = "Vous devez être connecté";
            header('Location: /users/login');
            exit;
        }


        //Je genere la vue
        $this->render('profil/index', compact('annonces'));
    }



    /**
     * Permet d'ajouter une annonce avec image si user.
     *
     * @return void
     */
    public function ajouter()
    {
        // Je vérifie si l'utilisateur est connecté
        if (isset($_SESSION['user']) && !empty($_SESSION['user']['id'])) {
            // L'utilisateur est connecté
            // Je vérifie si le formulaire est complet
            if (Form::validate($_POST, ['titre', 'description', 'prix'], $_FILES, ['images1', 'images2', 'images3'])) {
                // Protection contre les failles XSS
                // strip_tags, htmlentities, htmlspecialchars
                $titre = isset($_POST['titre']) ? htmlspecialchars($_POST['titre']) : '';
                $description = isset($_POST['description']) ? htmlspecialchars($_POST['description']) : '';
                $prix = isset($_POST['prix']) ? $_POST['prix'] : '';

                // Vérifier si au moins une image a été téléchargée
                if (!empty($_FILES['images1']['name']) || !empty($_FILES['images2']['name']) || !empty($_FILES['images3']['name'])) {
                    // J'instancie
                    $annonce = new AnnoncesModel;

                    // J'hydrate
                    $annonce->setTitre($titre)
                        ->setDescription($description)
                        ->setPrix($prix)
                        ->setUsers_id($_SESSION['user']['id']);

                    $images = [];
                    $totalSize = 0; // Variable pour stocker la taille totale des images en octets
                    $allFilesValid = true; // Variable pour vérifier si toutes les images sont valides
                    // Augmenter la limite de mémoire à 1024M
                    ini_set('memory_limit', '1024M');

                    // Boucle pour traiter chaque champ d'image
                    for ($i = 1; $i <= 3; $i++) {
                        $fieldName = 'images' . $i;

                        if (isset($_FILES[$fieldName])) {
                            $file = $_FILES[$fieldName];

                            // Vérifier si un fichier a été téléchargé
                            if (!empty($file['name'])) {
                                $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                                $extensionAutorise = ['jpg', 'jpeg'];

                                // Vérifier l'extension du fichier
                                if (!in_array($extension, $extensionAutorise)) {
                                    $allFilesValid = false; // Une des images n'est pas valide
                                    break; // Sortir de la boucle
                                }

                                // Vérifier si le fichier est une image en utilisant la bibliothèque GD
                                if (!getimagesize($file['tmp_name'])) {
                                    $allFilesValid = false; // Une des images n'est pas valide
                                    break; // Sortir de la boucle
                                }
                                // Vérifier si le contenu est bien une image 
                                if (!exif_imagetype($file['tmp_name'])) {
                                    $allFilesValid = false; // Une des images n'est pas valide
                                    break; // Sortir de la boucle
                                }

                                // Ajouter la taille du fichier à la taille totale
                                $totalSize += filesize($file['tmp_name']);

                                // Générer un nom de fichier unique
                                $uniqueName = uniqid('', true);
                                $fileName = $uniqueName . '.' . $extension;

                                // Chemin de l'image temporaire
                                $cheminSource = $file['tmp_name'];
                                // Chemin de l'image dans le dossier public/gd
                                $cheminDestinationGD = ROOT . '/public/gd/' . $fileName;
                                // Chemin de l'image redimensionnée dans le dossier public/uploads
                                $cheminDestinationUploads = ROOT . '/public/uploads/' . $fileName;
                                // Déplacer l'image vers le dossier public/gd
                                move_uploaded_file($cheminSource, $cheminDestinationGD);
                                // Réduire le poids de l'image
                                ImageUtils::reduirePoidsImage($cheminDestinationGD, $cheminDestinationUploads, 500, 500, 97);

                                // Ajouter le nom de fichier à la liste des images
                                $images[] = $fileName;
                            }
                        }
                    }

                    if (!$allFilesValid) {
                        // Une des images n'est pas au format autorisé, supprimer toutes les images précédemment téléchargées
                        foreach ($images as $fileName) {
                            $cheminImage = ROOT . '/public/uploads/' . $fileName;
                            if (file_exists($cheminImage)) {
                                unlink($cheminImage); // Supprimer l'image du dossier public/uploads
                            }
                        }
                        $_SESSION['erreur'] = "Veuillez vous assurer que toutes les images sont au format 'jpg' ou 'jpeg'.";
                        header('Location: /profil');
                        exit;
                    }

                    // Vérifier la taille totale des images 
                    $maxSize = 8 * 1024 * 1024; // 
                    if ($totalSize > $maxSize) {
                        // La taille totale des images dépasse la limite autorisée, supprimer toutes les images précédemment téléchargées
                        foreach ($images as $fileName) {
                            $cheminImage = ROOT . '/public/uploads/' . $fileName;
                            if (file_exists($cheminImage)) {
                                unlink($cheminImage); // Supprimer l'image du dossier public/uploads
                            }
                        }
                        $_SESSION['erreur'] = "Veuillez vous assurer que la taille totale des images ne dépasse pas " . $maxSize . "Mo";
                        header('Location: /profil');
                        exit;
                    }
                } else {
                    $_SESSION['erreur'] = "Veuillez ajouter au moins une image";
                    header('Location: /profil');
                    exit;
                }
                // Vérifier si au moins une image a été téléchargée
                if (!empty($images)) {
                    // J'enregistre l'annonce
                    $id = $annonce->create();
                    // Enregistrer les images dans la base de données
                    $image = new ImagesModel();

                    foreach ($images as $fileName) {
                        $image = new ImagesModel;
                        $image->setAnnonces_id($id);
                        $image->setNom($fileName);
                        // Enregistrer les images
                        $image->create();
                    }
                }
                // Redirection après l'enregistrement réussi
                $_SESSION['success'] = "Votre annonce a été enregistrée avec succès";
                header('Location: /profil/ajouter');
                exit;
            } else {
                // Le formulaire est incomplet
                $_SESSION['erreur'] = !empty($_POST) ? "Le formulaire est incomplet" : '';
                $titre = isset($_POST['titre']) ? htmlspecialchars($_POST['titre']) : '';
                $prix = isset($_POST['prix']) ? $_POST['prix'] : '';
                $description = isset($_POST['description']) ? htmlspecialchars($_POST['description']) : '';
            }
            $form = new Form;
            $form->debutForm('post', '#', ['enctype' => 'multipart/form-data'])
                ->ajoutLabelFor('titre', 'Titre de l\'annonce :')
                ->ajoutInput('text', 'titre', [
                    'id' => 'titre',
                    'class' => 'form-control',
                    'value' => $titre
                ])
                ->ajoutLabelFor('description', 'Texte de l\'annonce')
                ->ajoutTextarea('description', $description, ['id' => 'description', 'class' => 'form-control'])
                ->ajoutLabelFor('prix', 'Prix :')
                ->ajoutInput('number', 'prix', [
                    'id' => 'prix',
                    'class' => 'form-control',
                    'value' => $prix
                ])
                ->ajoutLabelFor('images', 'Images (Format JPG ou JPEG):')
                ->ajoutInput('file', 'images1', [
                    'id' => 'images1',
                    'class' => 'form-control',
                ])
                ->ajoutLabelFor('images', 'Images (Facultatif):')
                ->ajoutInput('file', 'images2', [
                    'id' => 'images2',
                    'class' => 'form-control',
                ])
                ->ajoutLabelFor('images', 'Images (Facultatif):')
                ->ajoutInput('file', 'images3', [
                    'id' => 'images3',
                    'class' => 'form-control',
                ])
                ->ajoutBouton('Ajouter', ['class' => 'btn btn-dark'])
                ->finForm();

            $this->render('profil/ajouter', ['form' => $form->create()]);
        } else {
            // L'utilisateur n'est pas connecté
            $_SESSION['erreur'] = "Vous devez être connecté(e) pour accéder à cette page";
            header('Location: /users/login');
            exit;
        }
    }






    /**
     * Modifier une annonce si user.
     * @param int $id 
     * @return void 
     */
    public function modifier(int $id)
    {
        if (!isset($_SESSION['user']) || empty($_SESSION['user']['id'])) {
            $_SESSION['erreur'] = "Vous devez être connecté(e) pour accéder à cette page";
            header('Location: /users/login');
            exit;
        }

        $annoncesModel = new AnnoncesModel;
        $annonce = $annoncesModel->find($id);

        if (!$annonce) {
            http_response_code(404);
            $_SESSION['erreur'] = "L'annonce recherchée n'existe pas";
            header('Location: /');
            exit;
        }

        if ($annonce->users_id !== $_SESSION['user']['id'] && !in_array('ROLE_ADMIN', $_SESSION['user']['roles'])) {
            $_SESSION['erreur'] = "Vous n'avez pas accès à cette page";
            header('Location: /');
            exit;
        }

        if (Form::validate($_POST, ['titre', 'description', 'prix'])) {
            $titre = strip_tags($_POST['titre']);
            $description = strip_tags($_POST['description']);
            $prix = strip_tags($_POST['prix']);


            $annonceModif = new AnnoncesModel;
            $annonceModif->setId($annonce->id)
                ->setTitre($titre)
                ->setDescription($description)
                ->setPrix($prix)
                ->update();

            $_SESSION['success'] = "Votre annonce a été modifiée avec succès";
            header('Location: /');
            exit;
        }

        $form = new Form;
        $form->debutForm('post', '#')
            ->ajoutLabelFor('titre', 'Titre de l\'annonce :')
            ->ajoutInput('text', 'titre', [
                'id' => 'titre',
                'class' => 'form-control',
                'value' => $annonce->titre
            ])
            ->ajoutLabelFor('description', 'Texte de l\'annonce')
            ->ajoutTextarea('description', $annonce->description, [
                'id' => 'description',
                'class' => 'form-control'
            ])
            ->ajoutLabelFor('prix', 'Prix :')
            ->ajoutInput('number', 'prix', [
                'id' => 'prix',
                'class' => 'form-control',
                'value' => $annonce->prix
            ])
            ->ajoutBouton('Modifier', ['class' => 'btn btn-dark'])
            ->finForm();

        $this->render('profil/modifier', ['form' => $form->create()]);
    }



    /**
     * Supprime une annonce et les images associées(dans le dossier aussi) si user.
     *
     * @param integer $id L'identifiant de l'annonce à supprimer.
     * @return void
     */
    public function supprimeAnnonce(int $id)
    {
        if (isset($_SESSION['user']) && !empty($_SESSION['user']['id'])) {
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
     * Active ou désactive une annonce si user.
     * @param int $id 
     * @return void 
     */
    public function activeAnnonce(int $id)
    {
        if (isset($_SESSION['user']) && !empty($_SESSION['user']['id'])) {
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
