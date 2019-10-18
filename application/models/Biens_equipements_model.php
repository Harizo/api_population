<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Biens_equipements_model extends CI_Model {
    protected $table = 'biens_equipements';

    public function add($biens_equipements,$nom_table)  {
        $this->db->set($this->_set($biens_equipements))
                            ->insert($nom_table);
        if($this->db->affected_rows() === 1)  {
            return $this->db->insert_id();
        }else{
            return null;
        }                    
    }
    public function update($id, $biens_equipements,$nom_table)  {
        $this->db->set($this->_set($biens_equipements))
                            ->where('id', (int) $id)
                            ->update($nom_table);
        if($this->db->affected_rows() === 1)  {
            return true;
        }else{
            return null;
        }                      
    }
    public function _set($biens_equipements) {
        return array(
            'id_menage'            => $biens_equipements['id_menage'],
            'id_biens_equipements' => $biens_equipements['id_biens_equipements'],
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
    public function deleteByMenage($id_menage,$nom_table) {
        $this->db->where('id_menage', (int) $id_menage)->delete($nom_table);
        if($this->db->affected_rows() === 1)  {
            return true;
        }else{
            return null;
        }  
    }
    public function findAll($nom_table) {
        $result =  $this->db->select('*')
                        ->from($nom_table)
                        ->order_by('id')
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
    public function findAllByMenage($id_menage) {
        $result =  $this->db->select('*')
                        ->from($this->table)
                        ->order_by('id')
                        ->where("id_menage", $id_menage)
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