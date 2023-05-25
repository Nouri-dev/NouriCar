<?php

namespace Apps\Controllers;

use Apps\App\Form;
use Apps\Models\UsersModel;

class UsersController extends Controller
{


    /**
     * Connexion des utilisateurs
     *
     * @return void
     */
    public function login()
    {

        if (isset($_SESSION['user']['id']) && !empty($_SESSION['user']['id'])) {
            $_SESSION['erreur'] = "Vous êtes déjà connecté";
            header('Location: /');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Je vérifie si le formulaire est complet
            if (Form::validate($_POST, ['email', 'password'])) {
                // Le formulaire est complet
                // Je vais chercher dans la base de données l'utilisateur avec l'email entré
                $usersModel = new UsersModel;
                $userArray = $usersModel->findOneByEmail(strip_tags($_POST['email']));

                // Si l'utilisateur n'existe pas
                if (!$userArray) {
                    // J' envoie un message de session
                    $_SESSION['erreur'] = 'L\'adresse e-mail et/ou le mot de passe est incorrect !';
                    header('Location: /users/login');
                    exit;
                }

                // L'utilisateur existe
                $user = $usersModel->hydrate($userArray);

                // Je vérifie si le mot de passe est correct
                if (password_verify($_POST['password'], $user->getPassword())) {
                    // Le mot de passe est bon
                    // Je crée la session
                    $user->setSession();
                    $_SESSION['log'] = 'Bienvenue ' . $_SESSION['user']['pseudo'] . ' ! 😄';
                    header('Location: /');
                    exit;
                } else {
                    // Mauvais mot de passe
                    $_SESSION['erreur'] = 'L\'adresse e-mail et/ou le mot de passe est incorrect';
                    header('Location: /users/login');
                    exit;
                }
            } else {
                // Formulaire incomplet
                $_SESSION['erreur'] = 'Veuillez remplir tous les champs !';
                header('Location: /users/login');
                exit;
            }
        }

        // Le formulaire n'a pas encore été soumis ou n'est pas en méthode POST
        $form = new Form;

        $form->debutForm()
            ->ajoutLabelFor('email', 'E-mail :')
            ->ajoutInput('email', 'email', ['class' => 'form-control', 'id' => 'email'])
            ->ajoutLabelFor('pass', 'Mot de passe :')
            ->ajoutInput('password', 'password', ['id' => 'pass', 'class' => 'form-control'])
            ->ajoutBouton('Me connecter', ['class' => 'btn btn-dark'])
            ->finForm();

        $this->render('users/login', ['loginForm' => $form->create()]);

        if (isset($_SESSION['user'])) {
            header('Location: /');
            exit;
        }
    }





    /**
     * Inscription des utilisateurs
     * @return void 
     */
    public function register()
    {
        // Vérifier si l'utilisateur est déjà connecté
        if (isset($_SESSION['user']['id']) && !empty($_SESSION['user']['id'])) {
            $_SESSION['erreur'] = "Vous êtes déjà connecté";
            header('Location: /');
            exit;
        }

        $formSubmitted = !empty($_POST);

        if ($formSubmitted) {

            // Je vérifie si le formulaire est valide
            if (Form::validate($_POST, ['email', 'password', 'pseudo'])) {

                // Le formulaire est valide

                // Je "nettoie" le pseudo
                $pseudo = strip_tags($_POST['pseudo']);

                // Je "nettoie" l'adresse email
                $email = strip_tags($_POST['email']);

                // Je vérifie si l'adresse email existe déjà dans la base de données
                $userModel = new UsersModel;
                $emailExists = $userModel->emailExist($email);
                $pseudoExists = $userModel->pseudoExist($pseudo);
                if ($emailExists || $pseudoExists) {
                    $_SESSION['erreur'] = "Cette adresse e-mail ou ce pseudo est déjà utilisé(e) 😔";
                    header('Location: /users/register');
                    exit;
                }

                // Je chiffre le mot de passe
                $pass = password_hash($_POST['password'], PASSWORD_ARGON2I);

                // J'hydrate l'utilisateur
                $user = new UsersModel;

                $user->setEmail($email)
                    ->setPassword($pass)
                    ->setPseudo($pseudo);

                // Je stocke l'utilisateur
                $user->create();

                $_SESSION['log'] = 'Votre inscription a bien été enregistrée';
                header('Location: /users/login');
                exit;
            } else {
                // Le formulaire est invalide, j'affiche un message d'erreur
                $_SESSION['erreur'] = 'Les champs ne sont pas remplis';
                header('Location: /users/register');
                exit;
            }
        }

        $form = new Form;

        $form->debutForm()
            ->ajoutLabelFor('pseudo', 'Votre pseudo :')
            ->ajoutInput('pseudo', 'pseudo', ['id' => 'pseudo', 'class' => 'form-control'])
            ->ajoutLabelFor('email', 'E-mail :')
            ->ajoutInput('email', 'email', ['id' => 'email', 'class' => 'form-control'])
            ->ajoutLabelFor('pass', 'Mot de passe :')
            ->ajoutInput('password', 'password', ['id' => 'pass', 'class' => 'form-control'])
            ->ajoutBouton('M\'inscrire', ['class' => 'btn btn-dark'])
            ->finForm();

        $this->render('users/register', ['registerForm' => $form->create()]);

        if (isset($_SESSION['user'])) {
            header('Location: /');
            exit;
        }
    }




    /**
     * Déconnexion de l'utilisateur
     * @return exit 
     */
    public function logout()
    {
        unset($_SESSION['user']);
        header('Location: ' . $_SERVER['HTTP_REFERER']);

        if (empty($_SESSION['user'])) {
            $_SESSION['log'] = "Vous êtes deconnecté 👋";
            header('Location: /');
            exit;
        }

        exit;
    }
}
