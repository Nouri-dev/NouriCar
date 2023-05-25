<?php

namespace Apps\Models;


use Apps\App\Db;



class Model  extends Db
{
    // Table de la base de données
    protected $table;

    // Instance de Db
    private $db;




    public function __construct()
    {
        $this->db = Db::getInstance();
    }


    public function findAll()
    {
        $query = $this->requete('SELECT * FROM ' . $this->table);
        return $query->fetchAll();
    }



    public function findBy(array $criteres)
    {
        $champs = [];
        $valeurs = [];

        // Je boucle pour éclater le tableau
        foreach ($criteres as $champ => $valeur) {
            // SELECT * FROM annonces WHERE actif = ? AND signale = 0
            // bindValue(1, valeur)
            $champs[] = "$champ = ?";
            $valeurs[] = $valeur;
        }

        // Je transforme le tableau "champs" en une chaine de caractères
        $liste_champs = implode(' AND ', $champs);
        // J'exécute la requête
        return $this->requete('SELECT * FROM ' . $this->table . ' WHERE ' . $liste_champs, $valeurs)->fetchAll();
    }


    public function find(int $id)
    {
        return $this->requete("SELECT * FROM {$this->table} WHERE id = $id")->fetch();
    }


    public function create()
    {
        $champs = [];
        $inter = [];
        $valeurs = [];

        // Je boucle pour éclater le tableau
        foreach ($this as $champ => $valeur) {
            // INSERT INTO annonces (titre, description, actif) VALUES (?, ?, ?)
            if ($valeur !== null && $champ != 'db' && $champ != 'table') {
                $champs[] = $champ;
                $inter[] = "?";
                $valeurs[] = $valeur;
            }
        }
        // Je transforme le tableau "champs" en une chaine de caractères
        $liste_champs = implode(', ', $champs);
        $liste_inter = implode(', ', $inter);

        // J'exécute la requête
        return $this->requete('INSERT INTO ' . $this->table . ' (' . $liste_champs . ')VALUES(' . $liste_inter . ')', $valeurs, $create = 'string');
    }


    public function update()
    {
        $champs = [];
        $valeurs = [];

        // Je boucle pour éclater le tableau
        foreach ($this as $champ => $valeur) {
            // UPDATE annonces SET titre = ?, description = ?, actif = ? WHERE id= ?
            if ($valeur !== null && $champ != 'db' && $champ != 'table') {
                $champs[] = "$champ = ?";
                $valeurs[] = $valeur;
            }
        }
        $valeurs[] = $this->id;

        // Je transforme le tableau "champs" en une chaine de caractères
        $liste_champs = implode(', ', $champs);

        // J'exécute la requête
        return $this->requete('UPDATE ' . $this->table . ' SET ' . $liste_champs . ' WHERE id = ?', $valeurs);
    }



    public function delete(int $id)
    {
        return $this->requete("DELETE FROM {$this->table} WHERE id = ?", [$id]);
    }



    public function requete(string $sql, array $attributs = null, string $create = '')
    {
        // Je récupère l'instance de Db
        $this->db = Db::getInstance();

        // Je vérifie si j'ai a des attributs
        if ($attributs !== null) {
            // Requête préparée
            $pdo = $this->db;
            $query = $pdo->prepare($sql);

            if ($create == 'string') {
                $query->execute($attributs);
                return $pdo->lastInsertId();
            } else {
                $query = $this->db->prepare($sql);
                $query->execute($attributs);
                return $query;
            }
        } else {
            // Requête simple
            return $this->db->query($sql);
        }
    }


    public function hydrate($donnees)
    {
        foreach ($donnees as $key => $value) {
            // Je récupère le nom du setter correspondant à la clé (key)
            // titre -> setTitre
            $setter = 'set' . ucfirst($key);

            // Je vérifie si le setter existe
            if (method_exists($this, $setter)) {
                // J'appelle le setter
                $this->$setter($value);
            }
        }
        return $this;
    }



    public function findNameAnnonce(int $annonces_id)
    {
        $query = "SELECT nom FROM {$this->table} WHERE annonces_id = $annonces_id";

        $db = Db::getInstance();
        $db->setFetchMode('assoc'); // Définir le mode de récupération sur tableau associatif

        return $this->requete($query)->fetchAll();
    }



    public function findAllOrderByCreatedAtDesc()
    {
        $query = "SELECT * FROM " . $this->table . " WHERE actif = 1 ORDER BY created_at DESC";
        return $this->requete($query)->fetchAll();
    }


   

}
