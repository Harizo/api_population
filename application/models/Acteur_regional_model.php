<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Acteur_regional_model extends CI_Model {
    protected $table = 'acteur_regional';

    public function add($acteur_regional)  {
        $this->db->set($this->_set($acteur_regional))
                            ->insert($this->table);
        if($this->db->affected_rows() === 1)  {
            return $this->db->insert_id();
        }else{
            return null;
        }                    
    }
    public function update($id, $acteur_regional)  {
        $this->db->set($this->_set($acteur_regional))
                            ->where('id', (int) $id)
                            ->update($this->table);
        if($this->db->affected_rows() === 1)  {
            return true;
        }else{
            return null;
        }                      
    }
    public function _set($acteur_regional) {
        return array(
            'nom'            => $acteur_regional['nom'],
            'representant'   => $acteur_regional['representant'],
            'contact'        => $acteur_regional['contact'],
            'adresse'        => $acteur_regional['adresse'],
            'id_region'      => $acteur_regional['id_region'],
            'id_type_acteur' => $acteur_regional['id_type_acteur'],
       );
    }
    public function delete($id) {
        $this->db->where('id', (int) $id)->delete($this->table);
        if($this->db->affected_rows() === 1)  {
            return true;
        }else{
            return null;
        }  
    }
    public function findAll() {
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