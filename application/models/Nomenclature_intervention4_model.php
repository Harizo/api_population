<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Nomenclature_intervention4_model extends CI_Model {
    protected $table = 'nomenclature_intervention4';

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
            'id_nomenclature3' => $nomenclature['id_nomenclature3']                       
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
		$requete = "select n1.code as code1,n1.description as description1,n2.code as code2,n2.description as description2"
					.",n3.code as code3,n3.description as description3,n4.id,n4.code,n4.description,"
					."n2.id_nomenclature1,n3.id_nomenclature2,n4.id_nomenclature3 "
					." from nomenclature_intervention4 as n4"
					." join nomenclature_intervention3 as n3 on n3.id=n4.id_nomenclature3"
					." join nomenclature_intervention2 as n2 on n2.id=n3.id_nomenclature2"
					." join nomenclature_intervention1 as n1 on n1.id=n2.id_nomenclature1"
					." order by n1.code,n2.code,n3.code,n4.code";
 		$query = $this->db->query($requete);
        $result =   $query->result();			
		/*$result =  $this->db->select('*')
                        ->from($this->table)
                        ->order_by('description')
                        ->get()
                        ->result();*/
        if($result)
        {
            return $result;
        }else{
            return null;
        }                 
    }
    public function findAllByNomenclature3($id_nomenclature3) {
		// Selection nomenclature par id_nomenclature3
        $result =  $this->db->select('*')
                        ->from($this->table)
                        ->order_by('description')
                        ->where("id_nomenclature3", $id_nomenclature3)
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
		$requete = "select n1.code as code1,n1.description as description1,n2.code as code2,n2.description as description2"
					.",n3.code as code3,n3.description as description3,n4.id,n4.code,n4.description,"
					."n2.id_nomenclature1,n3.id_nomenclature2,n4.id_nomenclature3 "
					." from nomenclature_intervention4 as n4"
					." join nomenclature_intervention3 as n3 on n3.id=n4.id_nomenclature3"
					." join nomenclature_intervention2 as n2 on n2.id=n3.id_nomenclature2"
					." join nomenclature_intervention1 as n1 on n1.id=n2.id_nomenclature1"
					." where n4.id=".$id
					." order by n1.code,n2.code,n3.code,n4.code";
 		$query = $this->db->query($requete);
        $result =   $query->result();			
        /*$result =  $this->db->select('*')
                        ->from($this->table)
                        ->where("id", $id)
                        ->order_by('id', 'asc')
                        ->get()
                        ->result();*/
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
