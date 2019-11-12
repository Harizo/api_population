<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Acteur_model extends CI_Model {
    protected $table = 'acteur';

    public function add($acteur)  //Fonction pour l'ajout
    {
        $this->db->set($this->_set($acteur))
                            ->insert($this->table);
        if($this->db->affected_rows() === 1)  
        {
            return $this->db->insert_id();
        }
        else
        {
            return null;
        }                    
    }

    public function update($id, $acteur)  //Fonction pour la mise à jour
    {
        $this->db->set($this->_set($acteur))
                            ->where('id', (int) $id)
                            ->update($this->table);
        if($this->db->affected_rows() === 1)  
        {
            return true;
        }
        else
        {
            return null;
        }                      
    }

    public function _set($acteur) //Initialisation du tableau pour l'ajout et la modification
    {
        return array(
            'nom' => $acteur['nom'],
            'nif' => $acteur['nif'],
            'stat' => $acteur['stat'],
            'adresse' => $acteur['adresse'],
            'id_fokontany' => $acteur['id_fokontany'],
            'representant' => $acteur['representant'],
            'fonction' => $acteur['fonction'],
            'telephone' => $acteur['telephone'],
            'email' => $acteur['email'],
            'rcs' => $acteur['rcs'],
            'id_type_acteur' => $acteur['id_type_acteur'],
        );
    }

    public function delete($id) //Fonction pour suppression de données
    {
        $this->db->where('id', (int) $id)->delete($this->table);
        if($this->db->affected_rows() === 1)  
        {
            return true;
        }
        else
        {
            return null;
        }  
    }

    public function findAll() //Fonction pour la récupération de tous les enregistrement de la table "acteur"
    {
        $result =  $this->db->select('*')
                        ->from($this->table)
                        ->order_by('nom')
                        ->get()
                        ->result();
        if($result) 
        {
            return $result;
        }
        else
        {
            return null;
        }                 
    }

    public function findById($id) //Fonction pour la récupération d'un enregistrement par Id
    {
        $result =  $this->db->select('*')
                        ->from($this->table)
                        ->where("id", $id)
                        ->order_by('id', 'asc')
                        ->get()
                        ->result();
        if($result) {
            return $result;
        }else{
            return null;
        }                 
    }

    public function findByNom($nom) //Fonction pour la récupération d'un enregistrement par nom
    {
		$requete= "select * from acteur where lower(nom) like '%".$nom."%'";
		$query = $this->db->query($requete);
        $result= $query->result();				
        /*$result =  $this->db->select('*')
                        ->from($this->table)
                        ->where('lower(nom)', $nom)
                        ->order_by('nom', 'asc')
                        ->get()
                        ->result();*/
        if($result) {
            return $result;
        }else{
            return array();
        }                 
    }
}
?>