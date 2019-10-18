<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Projet_model extends CI_Model {
    protected $table = 'projet';

    public function add($projet)  {
        $this->db->set($this->_set($projet))
                            ->insert($this->table);
        if($this->db->affected_rows() === 1)  {
            return $this->db->insert_id();
        }else{
            return null;
        }                    
    }
    public function update($id, $projet)  {
        $this->db->set($this->_set($projet))
                            ->where('id', (int) $id)
                            ->update($this->table);
        if($this->db->affected_rows() === 1)  {
            return true;
        }else{
            return null;
        }                      
    }
    public function _set($projet) {
        return array(
            'intitule'         => $projet['intitule'],
            'objectif_general' => $projet['objectif_general'],
            'observation'      => $projet['observation'],
            'type_intervention' => $projet['type_intervention'],
            // 'id_programme'     => $projet['id_programme'],
            'date_debut'       => $projet['date_debut'],
            'date_fin'         => $projet['date_fin']
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
                        ->order_by('intitule')
                        ->get()
                        ->result();
        if($result) {
            return $result;
        }else{
            return null;
        }                 
    }
    public function findById($id)  {
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