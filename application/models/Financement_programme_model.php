<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Financement_programme_model extends CI_Model {
    protected $table = 'financement_programme';

    public function add($finprg)  {
        $this->db->set($this->_set($finprg))
                            ->insert($this->table);
        if($this->db->affected_rows() === 1)  {
            return $this->db->insert_id();
        }else{
            return null;
        }                    
    }
    public function update($id, $finprg)  {
        $this->db->set($this->_set($finprg))
                            ->where('id', (int) $id)
                            ->update($this->table);
        if($this->db->affected_rows() === 1)  {
            return true;
        }else{
            return null;
        }                      
    }
    public function _set($finprg) {
        return array(
            'id_programme' => $finprg['id_programme'],
            'id_source_financement' => $finprg['id_source_financement'],
            'id_axe_strategique' => $finprg['id_axe_strategique'],
            'id_devise' => $finprg['id_devise'],
            'id_type_secteur' => $finprg['id_type_secteur'],
            'budget_initial' => $finprg['budget_initial'],
            'budget_modifie' => $finprg['budget_modifie'],
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
                        ->order_by('id')
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
    public function findByIdProgramme($id) {
        $result =  $this->db->select('*')
                        ->from($this->table)
                        ->where("id_programme", $id)
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