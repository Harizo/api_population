<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Enquete_menage_model extends CI_Model {
    protected $table = 'secteur';

    public function add($secteur,$nom_table)  {
        $this->db->set($this->_set($secteur))
                            ->insert($nom_table);
        if($this->db->affected_rows() === 1)  {
            return $this->db->insert_id();
        }else{
            return null;
        }                    
    }
    public function update($id, $secteur,$nom_table)  {
        $this->db->set($this->_set($secteur))
                            ->where('id', (int) $id)
                            ->update($nom_table);
        if($this->db->affected_rows() === 1)  {
            return true;
        }else{
            return null;
        }                      
    }
    public function _set($secteur) {
        return array(
            'code' => $secteur['code'],
            'description' => $secteur['description'],
        );
    }
    public function delete($id,$nom_table) {
        $this->db->where('id', (int) $id)->delete($nom_table);
        if($this->db->affected_rows() === 1)  {
            return true;
        }else{
            return null;
        }  
    }
    public function findAll($nom_table) {
        $result =  $this->db->select('*')
                        ->from($nom_table)
                        ->order_by('description')
                        ->get()
                        ->result();
        if($result) {
            return $result;
        }else{
            return null;
        }                 
    }
    public function findById($id,$nom_table) {
        $result =  $this->db->select('*')
                        ->from($nom_table)
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