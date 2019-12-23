<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Detail_type_transfert_intervention_model extends CI_Model {
    protected $table = 'detail_type_transfert_intervention';

    public function add($detailtypetransfertintervention)  {
		// Ajout d'un enregitrement
        $this->db->set($this->_set($detailtypetransfertintervention))
                            ->insert($this->table);
        if($this->db->affected_rows() === 1)  {
            return $this->db->insert_id();
        }else{
            return null;
        }                    
    }
    public function update($id, $detailtypetransfertintervention)  {
		// Mise à jour d'un enregitrement
        $this->db->set($this->_set($detailtypetransfertintervention))
                            ->where('id', (int) $id)
                            ->update($this->table);
        if($this->db->affected_rows() === 1)  {
            return true;
        }else{
            return null;
        }                      
    }
    public function _set($detailtypetransfertintervention) {
		// Affectation des valeurs
        return array(
            'id_intervention'     => $detailtypetransfertintervention['id_intervention'],
            'id_detail_type_transfert' => $detailtypetransfertintervention['id_detail_type_transfert'],
            'valeur_quantite' => $detailtypetransfertintervention['valeur_quantite'],
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
    public function deleteByIntervention($id_intervention) {
		// Suppression par id_intervention
        $this->db->where('id_intervention', (int) $id_intervention)->delete($this->table);
        if($this->db->affected_rows() >= 1)  {
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
    public function findByIntervention($id_intervention) {
		// Selection par id_intervention
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
    public function findByInterventionIdtypetransfert($id_intervention,$id_detail_type_transfert) {
		$query ="select valeur_quantite from detail_type_transfert_intervention where id_intervention=".$id_intervention ." and id_detail_type_transfert=".$id_detail_type_transfert;
		$temp = $this->db->query($query)->result();
		if(count($temp) >0) {
			return $temp;
		} else {
			return null;
		}
    }
	public function findByInterventionParConcatenation($id_intervention) {
		$requete="select concat_ws(' ',dtt.description,dtintv.valeur_quantite,um.description) as detail_transfert"
			." from detail_type_transfert_intervention as dtintv"
			." left outer join detail_type_transfert as dtt on dtt.id=dtintv.id_detail_type_transfert"
			." left outer join unite_mesure as um on um.id=dtt.id_unite_mesure"
			." where dtintv.id_intervention=".$id_intervention;
			$query = $this->db->query($requete)->result();
			if($query) {
			return $query;
			} else {
				return null;
			}
	}
}
?>