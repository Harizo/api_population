<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Acteur_projet_model extends CI_Model {
    protected $table = 'acteur_projet';

    public function add($acteur_projet) {
		// Ajout d'un enregitrement
        $this->db->set($this->_set($acteur_projet))
                            ->insert($this->table);
        if($this->db->affected_rows() === 1)
        {
            return $this->db->insert_id();
        }else{
            return null;
        }                    
    }
    public function update($id, $acteur_projet) {
		// Mise à jour d'un enregitrement
        $this->db->set($this->_set($acteur_projet))
                            ->where('id', (int) $id)
                            ->update($this->table);
        if($this->db->affected_rows() === 1) {
            return true;
        }else{
            return null;
        }                      
    }
    public function _set($acteur_projet) {
		// Affectation des valeurs
        return array(
            'id_projet'          => $acteur_projet['id_projet'],
            'id_acteur'          => $acteur_projet['id_acteur'],
            'id_acteur_regional' => $acteur_projet['id_acteur_regional'],
        );
    }
    public function delete($id) {
		// Suppression d'un enregitrement
        $this->db->where('id', (int) $id)->delete($this->table);
        if($this->db->affected_rows() === 1) {
            return true;
        }else{
            return null;
        }  
    }
    public function deleteByParentId($id) {
		// Suppression par id parent
        $this->db->where('id_projet', (int) $id)->delete($this->table);
        if($this->db->affected_rows() >= 1) {
            return true;
        }else{
            return null;
        }  
    }
    public function findAll() {
		// Selection de tous les enregitrements
        $result =  $this->db->select('*')
                        ->from($this->table)
                        ->order_by('id')
                        ->get()
                        ->result();
        if($result)
        {
            return $result;
        }else{
            return null;
        }                 
    }
    public function findByIdParent($id_projet) {
		// Selection par id parent
        $result =  $this->db->select('*')
                        ->from($this->table)
						->where("id_projet", $id_projet)
                        ->order_by('id')
                        ->get()
                        ->result();
        if($result)
        {
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
                        ->order_by('id', 'asc')
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