<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Frequence_transfert_model extends CI_Model {
    protected $table = 'frequence_transfert';

    public function add($frequencetransfert)  {
		// Ajout d'un enregitrement
        $this->db->set($this->_set($frequencetransfert))
                            ->insert($this->table);
        if($this->db->affected_rows() === 1)  {
            return $this->db->insert_id();
        }else{
            return null;
        }                    
    }
    public function update($id, $frequencetransfert)  {
		// Mise à jour d'un enregitrement
        $this->db->set($this->_set($frequencetransfert))
                            ->where('id', (int) $id)
                            ->update($this->table);
        if($this->db->affected_rows() === 1)  {
            return true;
        }else{
            return null;
        }                      
    }
    public function _set($frequencetransfert) {
		// Affectation des valeurs
        return array(
            'code'              => $frequencetransfert['code'],
            'description'       => $frequencetransfert['description'],
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