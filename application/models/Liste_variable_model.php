<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Liste_variable_model extends CI_Model
{
    protected $table = 'liste_variable';

	// Ajout d'un enregistrement
    public function add($liste_variable) {
        $this->db->set($this->_set($liste_variable))
                            ->insert($this->table);
        if($this->db->affected_rows() === 1)  {
            return $this->db->insert_id();
        }else{
            return null;
        }                    
    }
	// Mise à jour d'un enregistrement
    public function update($id, $liste_variable)  {
        $this->db->set($this->_set($liste_variable))
                            ->where('id', (int) $id)
                            ->update($this->table);
        if($this->db->affected_rows() === 1)  {
            return true;
        }else{
            return null;
        }                      
    }
	// Affectation colonne de la table
    public function _set($liste_variable)  {
        return array(
            'code'        => $liste_variable['code'],
            'description' => $liste_variable['description'],
        );
    }
	// Suppression d'un enregistrement
    public function delete($id)  {
        $this->db->where('id', (int) $id)->delete($this->table);
        if($this->db->affected_rows() === 1)
        {
            return true;
        }else{
            return null;
        }  
    }
	// Récupération de tous les enregistrements de la table
    public function findAll()  {
        $result =  $this->db->select('*')
                        ->from($this->table)
                        ->order_by('code')
                        ->get()
                        ->result();
        if($result)  {
            return $result;
        }else{
            return null;
        }                 
    }
	// Récupération par id (clé primaire)
    public function findById($id)  {
        $this->db->where("id", $id);
        $q = $this->db->get($this->table);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return null;
    }
	// Récupération par id (clé primaire) : réponse : tableau
    public function findByIdArray($id)  {
        $result =  $this->db->select('*')
                        ->from($this->table)
                        ->where("id", $id)
                        ->order_by('id', 'asc')
                        ->get()
                        ->result();
        if($result) {
            return $result;
        }else{
            return array();
        }                 
    }
}
?>