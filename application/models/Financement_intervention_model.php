<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Financement_intervention_model extends CI_Model {
    protected $table = 'financement_intervention';

    public function add($fininterv)  {
		// Ajout d'un enregitrement
        $this->db->set($this->_set($fininterv))
                            ->insert($this->table);
        if($this->db->affected_rows() === 1)  {
            return $this->db->insert_id();
        }else{
            return null;
        }                    
    }
    public function update($id, $fininterv)  {
		// Mise à jour d'un enregitrement
        $this->db->set($this->_set($fininterv))
                            ->where('id', (int) $id)
                            ->update($this->table);
        if($this->db->affected_rows() === 1)  {
            return true;
        }else{
            return null;
        }                      
    }
    public function _set($fininterv) {
		// Affectation des valeurs
        return array(
            'id_intervention' => $fininterv['id_intervention'],
            'id_source_financement' => $fininterv['id_source_financement'],
            'id_action_strategique' => $fininterv['id_action_strategique'],
            'id_devise' => $fininterv['id_devise'],
            'id_type_secteur' => $fininterv['id_type_secteur'],
            'budget_initial' => $fininterv['budget_initial'],
            'budget_modifie' => $fininterv['budget_modifie'],
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