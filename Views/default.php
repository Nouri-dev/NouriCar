<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NOURICAR</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
</head>

<body style="background-color:lightgray;">

<nav class="navbar navbar-expand-lg bg-body-tertiary navbar bg-dark" data-bs-theme="dark">
        <div class="container-fluid">
            <a style="font-weight: bold;" class="navbar-brand" href="/">NOURICAR</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <div class="navbar-nav">
                    <ul class="navbar-nav ml-auto">
                        <?php if (isset($_SESSION['user']) && !empty($_SESSION['user']['id'])) : ?>
                            <?php if (isset($_SESSION['user']['roles']) && in_array('ROLE_ADMIN', $_SESSION['user']['roles'])) : ?>
                                <li class="nav-item">
                                    <a class="nav-link" aria-current="page" href="/admin">Admin</a>
                                </li>
                            <?php endif; ?>
                            <li class="nav-item">
                                <a class="nav-link" aria-current="page" href="/profil">Profil</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNavAltMarkup">
                <div class="navbar-nav">
                    <?php if (isset($_SESSION['user']) && !empty($_SESSION['user']['id'])) : ?>
                        <a class="nav-link text-danger" aria-current="page" href="/users/logout">Déconnexion</a>
                    <?php else : ?>
                        <a class="nav-link" aria-current="page" href="/users/login">Connexion</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>




    <?php if (!empty($_SESSION['log'])) : ?>
        <div class="alert alert-primary" role="alert">
            <?= $_SESSION['log'];
            unset($_SESSION['log']); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($_SESSION['erreur'])) : ?>
        <div class="alert alert-danger" role="alert">
            <?= $_SESSION['erreur'];
            unset($_SESSION['erreur']); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($_SESSION['success'])) : ?>
        <div class="alert alert-success" role="alert">
            <?= $_SESSION['success'];
            unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>





    <div class="container">
        <?= $contenu ?>
    </div>






    <footer class="text-bg-dark p-3">
        <div class="container text-center text-md-left">
            <div class="row text-center text-md-left">
                <div class="col-md-3 col-lg-3 col-xl-3 mx-auto mt-3">
                    <p class="text-uppercase mb-4 font-weight-bold" style="font-size: 20px; font-weight: bold; color:lightgray;">NouriCar</p>
                    <p style="font-size: 13px;">Lorem ipsum dolor sit amet consectetur adipisicing elit. Veniam voluptas mollitia
                        doloribus illum molestias laboriosam, voluptates incidunt quas dolores veritatis enim
                        aliquam neque deleniti sed provident voluptatibus? Inventore, dolor doloribus?</p>
                </div>

                <div class="col-md-2 col-lg-2 col-xl-2 mx-auto mt-2">
                    <p class="text-uppercase mb-4 font-weight-bold" style="font-size: 20px; font-weight: bold; color:lightgray;">F.A.Q</p>
                    <p style="font-size: 13px;">Lorem ipsum, dolor sit amet consectetur elit ?</p>
                    <p style="font-size: 13px;">Lorem ipsum, dolor sit amet consectetur elit ?</p>
                    <p style="font-size: 13px;">Lorem ipsum, dolor sit amet consectetur elit ?</p>
                </div>

                <div class="col-md-3 col-lg-3 col-xl-3 mx-auto mt-3">
                    <p class="text-uppercase mb-4 font-weight-bold" style="font-size: 20px; font-weight: bold; color:lightgray;">CONTACT</p>
                    <p style="font-size: 13px;">
                        <i>Paris-75000<br>FRANCE</i>
                    </p>
                    <p style="font-size: 13px;">
                        <i>Nourexemple@mail.com</i>
                    </p>
                    <p style="font-size: 13px;">
                        <i>01.00.00.00.00</i>
                    </p>

                </div>

                <div style="margin-top: 70px;" class="row align-items-center">

                    <div>
                        <p class="container text-center" style="color:lightgray; font-size: 13px"> <strong>Copyright © 2023 All rights reserved by: NOURICAR</strong></p>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js" integrity="sha384-zYPOMqeu1DAVkHiLqWBUTcbYfZ8osu1Nd6Z89ify25QV9guujx43ITvfi12/QExE" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.min.js" integrity="sha384-Y4oOpwW3duJdCWv5ly8SCFYWqFDsfob/3GkgExXKV4idmbt98QcxXYs9UoXAB7BZ" crossorigin="anonymous"></script>
    <script src="/js/scripts.js"></script>
</body>

</html>