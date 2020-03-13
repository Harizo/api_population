<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Devise_model extends CI_Model {
    protected $table = 'devise';

    public function add($devise)  {
		// Ajout d'un enregitrement
        $this->db->set($this->_set($devise))
                            ->insert($this->table);
        if($this->db->affected_rows() === 1)  {
            return $this->db->insert_id();
        }else{
            return null;
        }                    
    }
    public function update($id, $devise)  {
		// Mise à jour d'un enregitrement
        $this->db->set($this->_set($devise))
                            ->where('id', (int) $id)
                            ->update($this->table);
        if($this->db->affected_rows() === 1)  {
            return true;
        }else{
            return null;
        }                      
    }
    public function _set($devise) {
		// Affectation des valeurs
        return array(
            'description'          => $devise['description'],
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
		// Selection de tous les enregitrements : modifie car le titre est utilisé dans la saisie cours de change
		// et necessaire pour l'affichage croisé dynamique (en colonne par date)
		$requete="select base.id,base.description,
			 case when base.position_caractere >0 then
				concat_ws('_',substring(lower(base.description) from 1 for (base.position_caractere - 1)),
				substring(lower(base.description) from (base.position_caractere + 1)))
			 else
				lower(base.description)
			 end as titre
			 from 
			 (
			 select id,description,position(' ' in description) as position_caractere from devise
			 order by id ) as base";
		$result = $this->db->query($requete)->result();
        /*$result =  $this->db->select('*')
                        ->from($this->table)
                        ->order_by('description')
                        ->get()
                        ->result();*/
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