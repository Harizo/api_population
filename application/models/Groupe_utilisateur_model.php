<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Groupe_utilisateur_model extends CI_Model {
    protected $table = 'groupe_utilisateur';

    public function add($typetransfert)  {
		// Ajout d'un enregitrement
        $this->db->set($this->_set($typetransfert))
                            ->insert($this->table);
        if($this->db->affected_rows() === 1)  {
            return $this->db->insert_id();
        }else{
            return null;
        }                    
    }
    public function update($id, $typetransfert)  {
		// Mise à jour d'un enregitrement
        $this->db->set($this->_set($typetransfert))
                            ->where('id', (int) $id)
                            ->update($this->table);
        if($this->db->affected_rows() === 1)  {
            return true;
        }else{
            return null;
        }                      
    }
    public function _set($typetransfert) {
		// Affectation des valeurs
        return array(
            'nom'        => $typetransfert['nom']
        );
    }
    public function delete($id) {
		// Suppression d'un enregitrement
        $this->db->where('id', (int) $id)->delete($this->table);
        if($this->db->affected_rows() === 1)  {
            return true;
        }else{
            return null;
        }  
    }
    public function findAll() {
		// Selection de tous les enregitrements
        $result =  $this->db->select('*')
                        ->from($this->table)
                        ->order_by('nom')
                        ->get()
                        ->result();
        if($result) {
            return $result;
        }else{
            return null;
        }                 
    }
    public function findById($id) {
		// Selection par id
        $result =  $this->db->select('*')
                        ->from($this->table)
                        ->where("id", $id)
                        ->get()
                        ->result();
        if($result) {
            return $result;
        }else{
            return null;
        }                 
    }
}
?>