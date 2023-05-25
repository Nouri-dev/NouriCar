<section> 
  <h1 style="font-weight: bold;" class="container text-center">Découvrez les annonces de ventes de voitures</h1>

  <div class="row" style="margin-bottom: 50px;" > 
    <?php foreach  ($annonces as $annonce) : ?>
      <article class="col-md-6 container text-center"> 
        <h2><a style="text-decoration: none; color: currentColor; font-weight: bold;" href="main/lire/<?= $annonce->id ?>">Voir : <?=  $annonce->titre ?></a></h2>

        <?php if ($annonce->image) : ?>
          <div>
            <a href="main/lire/<?= $annonce->id ?>">
              <img style="height: 350px; width: 400px; object-fit: cover;" class="img-thumbnail" src="./uploads/<?=  $annonce->image->nom  ?>" alt="image">
            </a>
          </div>
        <?php endif; ?> 

        <div style="background-color: currentColor;">
          <p style="color: white;">Prix : <strong><?=  $annonce->prix ?>€</strong> </p>
          <p style="color: white;">Annonce postée par : <strong><?=  $annonce->user->pseudo ?></strong> </p>
          <p style="color: white;">Postée le : <strong><?=  $annonce->created_at ?></strong> </p>
        </div>
      
      </article> 
    <?php endforeach; ?> 
  </div>

  <?php if (!empty($_SESSION['annoncesVide'])) : ?>
    <div style="margin-bottom: 50px; height: 400px;" class="d-flex align-items-center justify-content-center">
      <p class="container text-center" style="font-size: 40px; font-weight: bold;">
        <?= $_SESSION['annoncesVide'];
        unset($_SESSION['annoncesVide']); ?>
      </p>
    </div>
  <?php endif; ?>

  <div class="container">
    <ul class="pagination justify-content-center">
      <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
        <?php if ($i == $pageActuelle) : ?>
          <li class="page-item"><span style="color: grey;" class="page-link bg-dark"><?= $i ?></span></li>
        <?php else : ?>
          <li class="page-item"><a style="color: grey;" class="page-link" href="?page=<?= $i ?>"><?= $i ?></a></li>
        <?php endif; ?>
      <?php endfor; ?>
    </ul>
  </div>
</section>



