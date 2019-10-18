<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Zone_intervention_model extends CI_Model {
    protected $table = 'zone_intervention';

    public function add($zoneinterv)  {
        $this->db->set($this->_set($zoneinterv))
                            ->insert($this->table);
        if($this->db->affected_rows() === 1)  {
            return $this->db->insert_id();
        }else{
            return null;
        }                    
    }
    public function update($id, $zoneinterv)  {
        $this->db->set($this->_set($zoneinterv))
                            ->where('id', (int) $id)
                            ->update($this->table);
        if($this->db->affected_rows() === 1)  {
            return true;
        }else{
            return null;
        }                      
    }
    public function _set($zoneinterv) {
        return array(
            'id_intervention'             => $zoneinterv['id_intervention'],
            'id_fokontany'                => $zoneinterv['id_fokontany'],
            'menage_beneficiaire_prevu'   => $zoneinterv['menage_beneficiaire_prevu'],
            'individu_beneficiaire_prevu' => $zoneinterv['individu_beneficiaire_prevu'],
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
                        ->order_by('intitule')
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
    public function findByIdIntervention($id) {
        $result =  $this->db->select('*')
                        ->from($this->table)
                        ->where("id_intervention", $id)
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