<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Type_beneficiaire_model extends CI_Model {
    protected $table = 'type_beneficiaire';

    public function add($type_beneficiaire)  {
		// Ajout d'un enregitrement
        $this->db->set($this->_set($type_beneficiaire))
                            ->insert($this->table);
        if($this->db->affected_rows() === 1)  {
            return $this->db->insert_id();
        }else{
            return null;
        }                    
    }
    public function update($id, $type_beneficiaire)  {
		// Mise à jour d'un enregitrement
        $this->db->set($this->_set($type_beneficiaire))
                            ->where('id', (int) $id)
                            ->update($this->table);
        if($this->db->affected_rows() === 1)  {
            return true;
        }else{
            return null;
        }                      
    }
    public function _set($type_beneficiaire) {
		// Affectation des valeurs
        return array(
            'description' => $type_beneficiaire['description'],
        );
    }
    public function delete($id) {
		// Affectation des valeurs
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
                        ->order_by('description')
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