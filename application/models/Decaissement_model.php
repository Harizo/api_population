<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Decaissement_model extends CI_Model {
    protected $table = 'decaissement';

    public function add($decaissement)  {
        $this->db->set($this->_set($decaissement))
                            ->insert($this->table);
        if($this->db->affected_rows() === 1)  {
            return $this->db->insert_id();
        }else{
            return null;
        }                    
    }
    public function update($id, $decaissement)  {
        $this->db->set($this->_set($decaissement))
                            ->where('id', (int) $id)
                            ->update($this->table);
        if($this->db->affected_rows() === 1)  {
            return true;
        }else{
            return null;
        }                      
    }
    public function _set($decaissement) {
        return array(
            'id_financement_intervention'               => $decaissement['id_financement_intervention'],
            'nom_informateur'                           => $decaissement['nom_informateur'],
            'prenom_informateur'                        => $decaissement['prenom_informateur'],
            'telephone_informateur'                     => $decaissement['telephone_informateur'],
            'email_informateur'                         => $decaissement['email_informateur'],
            'id_acteur'                                 => $decaissement['id_acteur'],
            'montant_initial'                           => $decaissement['montant_initial'],
            'montant_revise'                            => $decaissement['montant_revise'],
            'date_revision'                             => $decaissement['date_revision'],
            'montant_mesure_accompagnement'             => $decaissement['montant_mesure_accompagnement'],
            'decaissement_prevu'                        => $decaissement['decaissement_prevu'],
            'decaissement_effectif'                     => $decaissement['decaissement_effectif'],
            'decaissement_prevu_cumule'                 => $decaissement['decaissement_prevu_cumule'],
            'decaissement_cumule'                       => $decaissement['decaissement_cumule'],
            'decaissement_effectif_beneficiaire'        => $decaissement['decaissement_effectif_beneficiaire'],
            'decaissement_effectif_beneficiaire_cumule' => $decaissement['decaissement_effectif_beneficiaire_cumule'],
            'nombre_beneficiaire'                       => $decaissement['nombre_beneficiaire'],
            'nombre_beneficiaire_cumule'                => $decaissement['nombre_beneficiaire_cumule'],
            'nombre_beneficiaire_sortant'               => $decaissement['nombre_beneficiaire_sortant'],
            'nombre_beneficiaire_sortant_cumule'        => $decaissement['nombre_beneficiaire_sortant_cumule'],
            'transfert_direct_beneficiaire'             => $decaissement['transfert_direct_beneficiaire'],
            'date_debut_periode'                        => $decaissement['date_debut_periode'],
            'date_fin_periode'                          => $decaissement['date_fin_periode'],
            'flag_integration_donnees'                  => $decaissement['flag_integration_donnees'],
            'nouvelle_integration'                      => $decaissement['nouvelle_integration'],
            'commentaire'                               => $decaissement['commentaire'],
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
    public function findAllByFinancementintervention($id_financement_intervention)  {
        $result =  $this->db->select('*')
                        ->from($this->table)
                        ->order_by('id')
                        ->where("id_financement_intervention", $id_financement_intervention)
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