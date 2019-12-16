<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Intervention_model extends CI_Model {
    protected $table = 'intervention';

    public function add($intervention)  {
		// Ajout d'un enregitrement
        $this->db->set($this->_set($intervention))
                            ->insert($this->table);
        if($this->db->affected_rows() === 1)  {
            return $this->db->insert_id();
        }else{
            return null;
        }                    
    }
    public function update($id, $intervention)  {
		// Mise à jour d'un enregitrement
        $this->db->set($this->_set($intervention))
                            ->where('id', (int) $id)
                            ->update($this->table);
        if($this->db->affected_rows() === 1)  {
            return true;
        }else{
            return null;
        }                      
    }
    public function _set($intervention) {
		// Affectation des valeurs
        return array(
            'identifiant' => $intervention['identifiant'],
            'id_programme' => $intervention['id_programme'],
            'nom_informateur' => $intervention['nom_informateur'],
            'prenom_informateur' => $intervention['prenom_informateur'],
            'telephone_informateur' => $intervention['telephone_informateur'],
            'email_informateur' => $intervention['email_informateur'],
            'ministere_tutelle' => $intervention['ministere_tutelle'],
            'intitule' => $intervention['intitule'],
            'id_acteur' => $intervention['id_acteur'],
            'categorie_intervention' => $intervention['categorie_intervention'],
            'id_type_action' => $intervention['id_type_action'],
            'id_frequence_transfert' => $intervention['id_frequence_transfert'],
            'inscription_budgetaire' => $intervention['inscription_budgetaire'],
            'programmation' => $intervention['programmation'],
            'duree' => $intervention['duree'],
            'unite_duree' => $intervention['unite_duree'],
            'id_type_transfert' => $intervention['id_type_transfert'],
            'flag_integration_donnees' => $intervention['flag_integration_donnees'],
            'nouvelle_integration' => $intervention['nouvelle_integration'],
            'commentaire' => $intervention['commentaire'],
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
    public function findByIntitule($intitule) {
		// Selection par intitule intervention
        $result =  $this->db->select('*')
                        ->from($this->table)
                        ->where("intitule", $intitule)
                        ->order_by('intitule', 'asc')
                        ->get()
                        ->result();
        if($result) {
            return $result;
        }else{
            return null;
        }                 
    }
    public function findByProgramme($id_programme) {
		// Selection par id_programme
        $result =  $this->db->select('*')
                        ->from($this->table)
                        ->where("id_programme", $id_programme)
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