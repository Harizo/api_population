<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Enquete_sur_individu_model extends CI_Model {
    protected $table = 'enquete_individu';
    public function add($enquete_sur_individu)  {
        $this->db->set($this->_set($enquete_sur_individu))
                            ->insert($this->table);
        if($this->db->affected_rows() === 1)
        {
            return $this->db->insert_id();
        }else{
            return null;
        }                    
    }
    public function update($id, $enquete_sur_individu)   {
        $this->db->set($this->_set($enquete_sur_individu))
                            ->where('id', (int) $id)
                            ->update($this->table);
        if($this->db->affected_rows() === 1)  {
            return true;
        }else{
            return null;
        }                      
    }
    public function _set($enquete_sur_individu) {
        return array(
            'id_individu'             => $enquete_sur_individu['id_individu'],
            'id_lien_de_parente'      => $enquete_sur_individu['id_lien_de_parente'],                       
            // 'situation_matrimoniale'  => $enquete_sur_individu['situation_matrimoniale'],                       
            'id_handicap_visuel'      => $enquete_sur_individu['id_handicap_visuel'],                       
            'id_handicap_parole'      => $enquete_sur_individu['id_handicap_parole'],                       
            'id_handicap_auditif'     => $enquete_sur_individu['id_handicap_auditif'],                       
            'id_handicap_mental'      => $enquete_sur_individu['id_handicap_mental'],                       
            'id_handicap_moteur'      => $enquete_sur_individu['id_handicap_moteur'] ,
            'id_type_ecole'              => $enquete_sur_individu['id_type_ecole'],           
            'langue'                  => $enquete_sur_individu['langue'],           
            'id_niveau_de_classe'     => $enquete_sur_individu['id_niveau_de_classe'],           
            'id_groupe_appartenance'  => $enquete_sur_individu['id_groupe_appartenance'],           
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
    public function findAllByindividu($id_individu) {      
        $this->db->where("id_individu", $id_individu);
        $q = $this->db->get($this->table);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return null;  
    }
    public function findById($id) {
        $this->db->where("id", $id);
        $q = $this->db->get($this->table);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return null;
    }
}
?>