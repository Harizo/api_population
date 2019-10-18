<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Detail_type_transfert_model extends CI_Model {
    protected $table = 'detail_type_transfert';

    public function add($detailtypetransfert)  {
        $this->db->set($this->_set($detailtypetransfert))
                            ->insert($this->table);
        if($this->db->affected_rows() === 1)  {
            return $this->db->insert_id();
        }else{
            return null;
        }                    
    }
    public function update($id, $detailtypetransfert)  {
        $this->db->set($this->_set($detailtypetransfert))
                            ->where('id', (int) $id)
                            ->update($this->table);
        if($this->db->affected_rows() === 1)  {
            return true;
        }else{
            return null;
        }                      
    }
    public function _set($detailtypetransfert) {
        return array(
            'code'              => $detailtypetransfert['code'],
            'description'       => $detailtypetransfert['description'],
            'id_unite_mesure'   => $detailtypetransfert['id_unite_mesure'],
            'id_type_transfert' => $detailtypetransfert['id_type_transfert'],
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
                        ->order_by('description')
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
    public function findByTypetransfert($id_type_transfert) {
        $result =  $this->db->select('*')
                        ->from($this->table)
                        ->where("id_type_transfert", $id_type_transfert)
                        ->order_by('id', 'asc')
                        ->get()
                        ->result();
        if($result) {
            return $result;
        }else{
            return null;
        }                 
    }
    public function findByIntervention($id_intervention) {
		$query = "select dtti.id_detail_type_transfert as id,dtt.description,dtt.id_unite_mesure,dtt.id_type_transfert,dtt.code,dtti.valeur_quantite"
				." from detail_type_transfert_intervention as dtti"
				." left outer join detail_type_transfert as dtt on dtt.id=dtti.id_detail_type_transfert" 
				." where dtti.id_intervention=".$id_intervention
				." UNION "
				." select id,description,id_unite_mesure,id_type_transfert,code,null as valeur_quantite from detail_type_transfert"
				." order by id_type_transfert,code";
				
        $result =  $this->db->select('*')
                        ->from($this->table)
                        ->where("id_intervention", $id_intervention)
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