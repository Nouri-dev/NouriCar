<section>  
<h1 style="font-weight: bold;" class="container text-center">Détail de l'annonce : <?= $annonce->titre ?></h1>

<article class="container">
    <h2><?= $annonce->titre ?></h2>
    <div id="carouselExample" class="carousel slide container text-center">
        <div class="carousel-inner">
            <?php foreach ($images as $index => $image) : ?>
                <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                    <img src="/uploads/<?= $image->nom ?>" class="img-thumbnail" alt="<?= $annonce->titre ?>" width="100%" style="height: 500px; object-fit:cover;">
                </div>
            <?php endforeach; ?>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>

    <p><?= $annonce->description ?></p>

    <p>Prix : <strong><?= $annonce->prix ?>€</strong></p>
    <p>Annonce postée par : <strong><?= $user->pseudo ?></strong></p>
    <p>Mail : <strong><?= $user->email ?></strong></p>
    <p>Postée le : <strong><?= $annonce->created_at ?></strong></p>
</article>
</section>

