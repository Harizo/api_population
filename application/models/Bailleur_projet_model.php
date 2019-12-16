<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bailleur_projet_model extends CI_Model {
    protected $table = 'bailleur_projet';

    public function add($bailleur_projet) {
		// Ajout d'un enregitrement
        $this->db->set($this->_set($bailleur_projet))
                            ->insert($this->table);
        if($this->db->affected_rows() === 1)
        {
            return $this->db->insert_id();
        }else{
            return null;
        }                    
    }
    public function update($id, $bailleur_projet) {
		// Mise à jour d'un enregitrement
        $this->db->set($this->_set($bailleur_projet))
                            ->where('id', (int) $id)
                            ->update($this->table);
        if($this->db->affected_rows() === 1) {
            return true;
        }else{
            return null;
        }                      
    }
    public function _set($bailleur_projet) {
		// Affectation des valeurs
        return array(
            'id_projet'           => $bailleur_projet['id_projet'],
            'id_bailleur'         => $bailleur_projet['id_bailleur'],
            'id_type_financement' => $bailleur_projet['id_type_financement'],
            'type_transfert' => $bailleur_projet['type_transfert'],
            'monnaie' => $bailleur_projet['monnaie'],
            'cout' => $bailleur_projet['cout'],
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
		// Suppression par id projet
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
	// Selection par id_projet
    public function findByIdParent($id_projet) {
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