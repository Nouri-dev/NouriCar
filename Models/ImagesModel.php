<?php

namespace Apps\Models;

class ImagesModel extends Model
{
    protected $id;
    protected $nom;
    protected $annonces_id;


    public function __construct()
    {
        $this->table = 'images';
    }


    /**
     * Get the value of id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }


    /**
     * Get the value of nom
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set the value of nom
     *
     * @return  self
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }


    /**
     * Get the value of annonces_id
     */
    public function getAnnonces_id()
    {
        return $this->annonces_id;
    }

    /**
     * Set the value of annonces_id
     *
     * @return  self
     */
    public function setAnnonces_id($annonces_id)
    {
        $this->annonces_id = $annonces_id;

        return $this;
    }
}
