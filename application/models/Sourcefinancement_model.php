<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sourcefinancement_model extends CI_Model {
    protected $table = 'source_financement';

    public function add($bailleur)  {
		// Ajout d'un enregitrement
        $this->db->set($this->_set($bailleur))
                            ->insert($this->table);
        if($this->db->affected_rows() === 1)  {
            return $this->db->insert_id();
        }else{
            return null;
        }                    
    }
    public function update($id, $bailleur)  {
		// Mise à jour d'un enregitrement
        $this->db->set($this->_set($bailleur))
                            ->where('id', (int) $id)
                            ->update($this->table);
        if($this->db->affected_rows() === 1)  {
            return true;
        }else{
            return null;
        }                      
    }
    public function _set($bailleur) {
		// Affectation des valeurs
        return array(
            'nom' => $bailleur['nom'],
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
    public function deleteByParentId($id) {
		// Suppression par id_projet
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
                        ->order_by('nom')
                        ->get()
                        ->result();
        if($result) {
            return $result;
        }else{
            return null;
        }                 
    }
    public function findByIdParent($id_projet) {
		// Selection par id_projet
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