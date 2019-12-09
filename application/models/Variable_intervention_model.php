<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Variable_intervention_model extends CI_Model {
    protected $table = 'variable_intervention';
	// Ajout d'un enregistrement
    public function add($variable_intervention) {
        $this->db->set($this->_set($variable_intervention))
                            ->insert($this->table);
        if($this->db->affected_rows() === 1) {
            return $this->db->insert_id();
        }else{
            return null;
        }                    
    }
	// Mise à jour d'un enregistrement
    public function update($id, $variable_intervention) {
        $this->db->set($this->_set($variable_intervention))
                            ->where('id', (int) $id)
                            ->update($this->table);
        if($this->db->affected_rows() === 1) {
            return true;
        }else{
            return null;
        }                      
    }
	// Affectation colonne de la table
    public function _set($variable_intervention) {
        return array(
            'id_liste_variable'                => $variable_intervention['id_liste_variable'],                      
            'id_variable'                      => $variable_intervention['id_variable'],
            'id_liste_validation_beneficiaire' => $variable_intervention['id_liste_validation_beneficiaire'],
        );
    }
	// Suppression d'un enregistrement
    public function delete($id) {
        $this->db->where('id', (int) $id)->delete($this->table);
        if($this->db->affected_rows() === 1) {
            return true;
        }else{
            return null;
        }  
    }
	// Récupération de tous les enregistrements de la table
    public function findAll() {
        $result =  $this->db->select('*')
                        ->from($this->table)
                        ->order_by('id_variable')
                        ->get()
                        ->result();
        if($result) {
            return $result;
        }else{
            return null;
        }                 
    }
	// Récupération pa id_liste_variable : clé étrangère
    public function findAllByIdlistevariable($id_liste_variable) {
        $result =  $this->db->select('*')
                        ->from($this->table)
                        ->order_by('id_variable')
                        ->where("id_liste_variable", $id_liste_variable)
                        ->get()
                        ->result();
        if($result) {
            return $result;
        }else{
            return null;
        }                 
    }
	// Récupération par id (clé primaire)
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