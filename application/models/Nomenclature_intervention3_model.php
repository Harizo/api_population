<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Nomenclature_intervention3_model extends CI_Model {
    protected $table = 'nomenclature_intervention3';

    public function add($nomenclature) {
		// Ajout d'un enregitrement
        $this->db->set($this->_set($nomenclature))
                            ->insert($this->table);
        if($this->db->affected_rows() === 1) {
            return $this->db->insert_id();
        }else{
            return null;
        }                    
    }
    public function update($id, $nomenclature) {
		// Mise à jour d'un enregitrement
        $this->db->set($this->_set($nomenclature))
                            ->where('id', (int) $id)
                            ->update($this->table);
        if($this->db->affected_rows() === 1)
        {
            return true;
        }else{
            return null;
        }                      
    }
    public function _set($nomenclature) {
		// Affectation des valeurs
        return array(
            'code'             => $nomenclature['code'],
            'description'      => $nomenclature['description'],
            'id_nomenclature2' => $nomenclature['id_nomenclature2']                       
        );
    }
    public function delete($id) {
		// Suppression d'un enregitrement
        $this->db->where('id', (int) $id)->delete($this->table);
        if($this->db->affected_rows() === 1)
        {
            return true;
        }else{
            return null;
        }  
    }
    public function findAll() {
		// Selection de tous les enregitrements
        $result =  $this->db->select('*')
                        ->from($this->table)
                        ->order_by('description')
                        ->get()
                        ->result();
        if($result)
        {
            return $result;
        }else{
            return null;
        }                 
    }
    public function findAllByNomenclature2($id_nomenclature2) {
		// Selection nomenclature par id_nomenclature2
        $result =  $this->db->select('*')
                        ->from($this->table)
                        ->order_by('description')
                        ->where("id_nomenclature2", $id_nomenclature2)
                        ->get()
                        ->result();
        if($result) {
            return $result;
        }else{
            return null;
        }                 
    }
    public function findById($id) {
		// Selection par id résultat dans un tableau
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
    public function findByIdOLD($id)  {
		// Selection par id
        $this->db->where("id", $id);
        $q = $this->db->get($this->table);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
    }
}
