<?php

namespace Apps\App;

class ImageUtils
{

    /**
     * Bibliotheque GD , Réduit le poids d'une image en la redimensionnant et en ajustant sa qualité.
     *
     * @param string $cheminSource Le chemin de l'image source à réduire.
     * @param string $cheminDestination Le chemin où enregistrer l'image réduite.
     * @param int $largeurMax La largeur maximale souhaitée pour l'image réduite.
     * @param int $hauteurMax La hauteur maximale souhaitée pour l'image réduite.
     * @param int $qualite La qualité de l'image réduite (de 0 à 100).
     * @return bool Indique si la réduction de poids de l'image a été effectuée avec succès.
     */
    public static function reduirePoidsImage($cheminSource, $cheminDestination, $largeurMax, $hauteurMax, $qualite)
    {
        // Obtenir les informations sur l'image source
        $infoImage = getimagesize($cheminSource);
        $largeurOriginale = $infoImage[0];
        $hauteurOriginale = $infoImage[1];
        $typeImage = $infoImage[2];

        // Calculer les nouvelles dimensions de l'image en conservant le ratio d'aspect
        $ratio = min($largeurMax / $largeurOriginale, $hauteurMax / $hauteurOriginale);
        $nouvelleLargeur = $largeurOriginale * $ratio;
        $nouvelleHauteur = $hauteurOriginale * $ratio;

        // Créer une image vide aux nouvelles dimensions
        $imageReduite = imagecreatetruecolor($nouvelleLargeur, $nouvelleHauteur);

        // Charger l'image source selon son type
        switch ($typeImage) {
            case IMAGETYPE_JPEG:
                $imageSource = imagecreatefromjpeg($cheminSource);
                break;
                // Ajoutez d'autres cas pour les autres types d'images supportés si nécessaire

            default:
                // Type d'image non supporté
                return false;
        }

        // Redimensionner l'image source vers l'image réduite
        imagecopyresampled($imageReduite, $imageSource, 0, 0, 0, 0, $nouvelleLargeur, $nouvelleHauteur, $largeurOriginale, $hauteurOriginale);

        // Enregistrer l'image réduite dans le fichier de destination avec la qualité spécifiée
        switch ($typeImage) {
            case IMAGETYPE_JPEG:
                imagejpeg($imageReduite, $cheminDestination, $qualite);
                break;
                // Ajoutez d'autres cas pour les autres types d'images supportés si nécessaire

            default:
                // Type d'image non supporté
                return false;
        }

        // Supprimer l'image temporaire dans le dossier public/gd
        unlink($cheminSource);

        // Libérer la mémoire utilisée par les images
        imagedestroy($imageSource);
        imagedestroy($imageReduite);

        return true;
    }
    
}
