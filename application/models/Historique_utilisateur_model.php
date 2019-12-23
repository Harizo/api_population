<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Historique_utilisateur_model extends CI_Model
{
    protected $table = 'historique_utilisateur';

	// Table concernée : historique_utilisateur
	// Ajout historique
    public function add($historique_utilisateur)
    {
        $this->db->set($this->_set($historique_utilisateur))
                            // ->set('date_action', 'NOW()', false)
                            ->insert($this->table);
        if($this->db->affected_rows() === 1)
        {
            return $this->db->insert_id();
        }else{
            return null;
        }                    
    }

	// Mise à jour historique : inutile pour le moment (voir commentaire controlers : index_post)

    public function update($id, $historique_utilisateur)
    {
        $this->db->set($this->_set($historique_utilisateur))
                            ->where('id', (int) $id)
                            ->update($this->table);
        if($this->db->affected_rows() === 1)
        {
            return true;
        }else{
            return null;
        }                      
    }
	// Affectation colonne de la table
    public function _set($historique_utilisateur)
    {
        return array(
            'action'         => $historique_utilisateur['action'],
            'date_action'    => $historique_utilisateur['date_action'],
            'id_utilisateur' => $historique_utilisateur['id_utilisateur']                       
        );
    }
	// Suppression : inutilie pour le moment (voir commentaire controlers : index_post)
    public function delete($id)
    {
        $this->db->where('id', (int) $id)->delete($this->table);
        if($this->db->affected_rows() === 1)
        {
            return true;
        }else{
            return null;
        }  
    }
	// Récupération de tous les enregistrements de la table
    public function findAll()
    {
        $result =  $this->db->select('*')
                        ->from($this->table)
                        ->order_by('date_action','desc')
                        ->get()
                        ->result();
        if($result)
        {
            return $result;
        }else{
            return null;
        }                 
    }


	// Récupération par id
    public function findById($id)
    {
        $this->db->where("id", $id);
        $q = $this->db->get($this->table);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return null;
    }
	// Récupération par date et par utilis
	// Paramètre $requete : voir la définition dans controlers (function : generer_requete_filtre)
    public function findByDateUtilisateur($requete)
    {
        $result =  $this->db->select('*')
                        ->from($this->table)
                        ->order_by('date_action','desc')
                        ->where($requete)
                        ->get()
                        ->result();
        if($result)
        {
            return $result;
        }else{
            return null;
        }                 
    }

}
