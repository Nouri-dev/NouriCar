<h1 style="font-weight: bold;" class="container text-center">Listes des annonces des véhicules</h1>


<table class="table table-striped">
    <thead>
        <th>Titre</th>
        <th>Contenu</th>
        <th>Actif</th>
        <th>Actions</th>
    </thead>
    <tbody>
    <?php foreach ($annonces as $annonce) : ?>
        <tr>
            <td><?= $annonce->titre ?></td>
            <td><?= $annonce->description ?></td>
            <td>
                
                <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" class="jsusers" id="customSwitch<?= $annonce->id ?>" <?= $annonce->actif ? 'checked' : '' ?> data-id="<?= $annonce->id ?>">
                    <label class="custom-control-label" class="jsusers" for="customSwitch<?= $annonce->id ?>"></label>
                </div>
            </td>
            <td>
                <a href="/profil/modifier/<?= $annonce->id ?>" class="btn btn-dark">Modifier</a>
                <a href="/profil/supprimeAnnonce/<?= $annonce->id ?>" class="btn btn-danger">Supprimer</a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

        <div>
            <?php if (!empty($_SESSION['annoncesVide'])) : ?>
                <p class="container text-center" style="font-size: 24px; font-weight: bold;">
                <?= $_SESSION['annoncesVide'];
                 unset($_SESSION['annoncesVide']); ?>
                </p>
            <?php endif; ?>
       </div>


<a style="margin-bottom: 100px;" href="profil/ajouter" class="btn btn-dark">Ajouter une annonce</a>

