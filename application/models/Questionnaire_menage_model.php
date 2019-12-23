<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Enquete_menage_model extends CI_Model {
    protected $table = 'enquete_menage';

    public function add($enquete_menage)  {
		// Ajout d'un enregitrement
        $this->db->set($this->_set($enquete_menage))
                            ->insert($table);
        if($this->db->affected_rows() === 1)  {
            return $this->db->insert_id();
        }else{
            return null;
        }                    
    }
    public function update($id, $enquete_menage)  {
		// Mise à jour d'un enregitrement
        $this->db->set($this->_set($enquete_menage))
                            ->where('id', (int) $id)
                            ->update($table);
        if($this->db->affected_rows() === 1)  {
            return true;
        }else{
            return null;
        }                      
    }
    public function _set($enquete_menage) {
		// Affectation des valeurs
        return array(
            'id_menage' => $enquete_menage['id_menage'],
            'type_logement' => $enquete_menage['type_logement'],
            'occupation_logement' => $enquete_menage['occupation_logement'],
            'revetement_toit' => $enquete_menage['revetement_toit'],
            'revetement_sol' => $enquete_menage['revetement_sol'],
            'revetement_mur' => $enquete_menage['revetement_mur'],
            'eclairage' => $enquete_menage['eclairage'],
            'combustible' => $enquete_menage['combustible'],
            'toilette' => $enquete_menage['toilette'],
            'source_eau' => $enquete_menage['source_eau'],
            'type_bien_equipement' => $enquete_menage['type_bien_equipement'],
            'moyen_production' => $enquete_menage['moyen_production'],
            'source_revenu' => $enquete_menage['source_revenu'],
            'type_elevage' => $enquete_menage['type_elevage'],
            'type_culture' => $enquete_menage['type_culture'],
            'type_aliment' => $enquete_menage['type_aliment'],
            'type_difficulte_alimentaire' => $enquete_menage['type_difficulte_alimentaire'],
            'type_strategie_face_probleme' => $enquete_menage['type_strategie_face_probleme'],
            'type_engagement_activite' => $enquete_menage['type_engagement_activite'],
        );
    }
    public function delete($id) {
		// Suppression d'un enregitrement
        $this->db->where('id', (int) $id)->delete($table);
        if($this->db->affected_rows() === 1)  {
            return true;
        }else{
            return null;
        }  
    }
    public function findAll() {
		// Selection de tous les enregitrements
        $result =  $this->db->select('*')
                        ->from($table)
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
                        ->from($table)
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