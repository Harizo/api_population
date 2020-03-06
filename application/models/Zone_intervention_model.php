<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Zone_intervention_model extends CI_Model {
    protected $table = 'zone_intervention';

    public function add($zoneinterv)  {
		// Ajout d'un enregitrement
        $this->db->set($this->_set($zoneinterv))
                            ->insert($this->table);
        if($this->db->affected_rows() === 1)  {
            return $this->db->insert_id();
        }else{
            return null;
        }                    
    }
    public function update($id, $zoneinterv)  {
		// Mise à jour d'un enregitrement
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
		// Affectation des valeurs
        return array(
            'id_intervention'             => $zoneinterv['id_intervention'],
            'id_fokontany'                => $zoneinterv['id_fokontany'],
            'menage_beneficiaire_prevu'   => $zoneinterv['menage_beneficiaire_prevu'],
            'individu_beneficiaire_prevu' => $zoneinterv['individu_beneficiaire_prevu'],
            'groupe_beneficiaire_prevu'   => $zoneinterv['groupe_beneficiaire_prevu'],
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
    public function findByIdIntervention($id) {
		// Selection par id_intervention
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