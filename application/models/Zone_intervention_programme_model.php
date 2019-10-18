<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Zone_intervention_programme_model extends CI_Model {
    protected $table = 'zone_intervention_programme';

    public function add($ziprg)  {
        $this->db->set($this->_set($ziprg))
                            ->insert($this->table);
        if($this->db->affected_rows() === 1)  {
            return $this->db->insert_id();
        }else{
            return null;
        }                    
    }
    public function update($id, $ziprg)  {
        $this->db->set($this->_set($ziprg))
                            ->where('id', (int) $id)
                            ->update($this->table);
        if($this->db->affected_rows() === 1)  {
            return true;
        }else{
            return null;
        }                      
    }
    public function _set($ziprg) {
        return array(
            'id_programme' => $ziprg['id_programme'],
            'id_district' => $ziprg['id_district'],
            'id_region' => $ziprg['id_region'],
            'menage_beneficiaire_prevu' => $ziprg['menage_beneficiaire_prevu'],
            'individu_beneficiaire_prevu' => $ziprg['individu_beneficiaire_prevu'],
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