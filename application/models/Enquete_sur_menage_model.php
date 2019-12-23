<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Enquete_sur_menage_model extends CI_Model {
    protected $table = 'enquete_menage';

    public function add($enquete_menage)  {
		// Ajout d'un enregitrement
        $this->db->set($this->_set($enquete_menage))
                            ->insert($this->table);
        if($this->db->affected_rows() === 1) {
            return $this->db->insert_id();
        }else{
            return null;
        }                    
    }
    public function update($id, $enquete_menage)  {
		// Mise à jour d'un enregitrement
        $this->db->set($this->_set($enquete_menage))
                            ->where('id', (int) $id)
                            ->update($this->table);
        if($this->db->affected_rows() === 1)  {
            return true;
        }else{
            return null;
        }                      
    }
    public function _set($enquete_menage) {
		// Affectation des valeurs
        return array(
            'id_menage'                =>  $enquete_menage['id_menage'],
            'id_type_logement'         =>  $enquete_menage['id_type_logement'],                       
            'id_occupation_logement'   =>  $enquete_menage['id_occupation_logement'],                       
            'revetement_toit'          =>  $enquete_menage['revetement_toit'],                       
            'revetement_sol'           =>  $enquete_menage['revetement_sol'],                       
            'revetement_mur'           =>  $enquete_menage['revetement_mur'],                       
            'source_eclairage'         =>  $enquete_menage['source_eclairage'],                       
            'combustible'              =>  $enquete_menage['combustible'],                       
            'toilette'                 =>  $enquete_menage['toilette'],                       
            'source_eau'               =>  $enquete_menage['source_eau'],                       
            'bien_equipement'          =>  $enquete_menage['bien_equipement'],                       
            'moyen_production'         =>  $enquete_menage['moyen_production'],                       
            'source_revenu'            =>  $enquete_menage['source_revenu'],                       
            'elevage'                  =>  $enquete_menage['elevage'],                       
            'culture'                  =>  $enquete_menage['culture'],                       
            'aliment'                  =>  $enquete_menage['aliment'],                       
            'source_aliment'           =>  $enquete_menage['source_aliment'],                       
            'strategie_alimentaire'    =>  $enquete_menage['strategie_alimentaire'],                       
            'probleme_sur_revenu'      =>  $enquete_menage['probleme_sur_revenu'],                       
            'strategie_sur_revenu'     =>  $enquete_menage['strategie_sur_revenu'],                       
            'activite_recours'         =>  $enquete_menage['activite_recours'],                       
            'service_beneficie'        =>  $enquete_menage['service_beneficie'],                       
            'infrastructure_frequente' =>  $enquete_menage['infrastructure_frequente'],                       
        );
    }
    public function delete($id) {
		// Suppression d'un enregitrement
        $this->db->where('id', (int) $id)->delete($this->table);
        if($this->db->affected_rows() === 1) {
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
    public function findAllByMenage($id_menage)  {     
		// Selection par ménage
        $this->db->where("id_menage", $id_menage);
        $q = $this->db->get($this->table);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return null;  
    }
    public function findById($id) {
		// Selection par id
        $this->db->where("id", $id);
        $q = $this->db->get($this->table);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return null;
    }
}
?>