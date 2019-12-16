<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Action_strategique_model extends CI_Model {
    protected $table = 'action_strategique';

    public function add($actionstrategique)  {
		// Ajout d'un enregitrement
        $this->db->set($this->_set($actionstrategique))
                            ->insert($this->table);
        if($this->db->affected_rows() === 1)  {
            return $this->db->insert_id();
        }else{
            return null;
        }                    
    }
    public function update($id, $actionstrategique)  {
		// Mise à jour d'un enregitrement
        $this->db->set($this->_set($actionstrategique))
                            ->where('id', (int) $id)
                            ->update($this->table);
        if($this->db->affected_rows() === 1)  {
            return true;
        }else{
            return null;
        }                      
    }
    public function _set($actionstrategique) {
		// Affectation des valeurs
        return array(
            'action'        => $actionstrategique['action'],
            'code'               => $actionstrategique['code'],
            'id_axe_strategique' => $actionstrategique['id_axe_strategique'],
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
                        ->order_by('action')
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