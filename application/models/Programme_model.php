<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Programme_model extends CI_Model {
    protected $table = 'programme';

    public function add($programme)  {
		// Ajout d'un enregitrement
        $this->db->set($this->_set($programme))
                            ->insert($this->table);
        if($this->db->affected_rows() === 1)  {
            return $this->db->insert_id();
        }else{
            return null;
        }                    
    }
    public function update($id, $programme)  {
		// Mise à jour d'un enregitrement
        $this->db->set($this->_set($programme))
                            ->where('id', (int) $id)
                            ->update($this->table);
        if($this->db->affected_rows() === 1)  {
            return true;
        }else{
            return null;
        }                      
    }
    public function _set($programme) {
		// Affectation des valeurs
        return array(
            'nom'                      => $programme['nom'],
            'prenom'                   => $programme['prenom'],
            'telephone'                => $programme['telephone'],
            'email'                    => $programme['email'],
            'id_tutelle'               => $programme['id_tutelle'],
            'id_type_action'           => $programme['id_type_action'],
            'intitule'                 => $programme['intitule'],
            'situation_intervention'   => $programme['situation_intervention'],
            'date_debut'               => $programme['date_debut'],
            'date_fin'                 => $programme['date_fin'],
            'description'              => $programme['description'],
            'flag_integration_donnees' => $programme['flag_integration_donnees'],
            'nouvelle_integration'     => $programme['nouvelle_integration'],
            'commentaire'              => $programme['commentaire'],
            'identifiant'              => $programme['identifiant'],
            'inscription_budgetaire'   => $programme['inscription_budgetaire'],
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
}
?>