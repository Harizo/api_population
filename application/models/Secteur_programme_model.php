<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Secteur_programme_model extends CI_Model {
	// Table concerné secteur_programme
    protected $table = 'secteur_programme';
	// Ajout d'un enregistrement
    public function add($secteur_programme)  {
        $this->db->set($this->_set($secteur_programme))
                            ->insert($this->table);
        if($this->db->affected_rows() === 1)  {
            return $this->db->insert_id();
        }else{
            return null;
        }                    
    }
	// Mise à jour d'un enregistrement
    public function update($id, $secteur_programme)  {
        $this->db->set($this->_set($secteur_programme))
                            ->where('id', (int) $id)
                            ->update($this->table);
        if($this->db->affected_rows() === 1)  {
            return true;
        }else{
            return null;
        }                      
    }
	// Affectatio de valeur avant de passer en paramètre dans la fonction add ou update
    public function _set($secteur_programme) {
        return array(
            'id_programme' => $secteur_programme['id_programme'],
            'id_financement_programme' => $secteur_programme['id_financement_programme'],
            'id_type_secteur' => $secteur_programme['id_type_secteur'],
        );
    }
	// Suppression d'un enregistrement
    public function delete($id) {
        $this->db->where('id', (int) $id)->delete($this->table);
        if($this->db->affected_rows() === 1)  {
            return true;
        }else{
            return null;
        }  
    }
	// Suppression des fils d'un programme et id_financement_programme combiné
    public function deleteByIdprogrammeAndIdfinancementprogramme($id_programme,$id_financement_programme) {
		$requete ="delete from secteur_programme where id_programme=".$id_programme
				." and id_financement_programme=".$id_financement_programme;
		$query = $this->db->query($requete);				
        if($this->db->affected_rows() >= 1)  {
            return true;
        }else{
            return null;
        }  
    }
	// Récupération de tous les enregistrements de la table 
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
	// Récupération par id (id = clé primaire)
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
	// Récupération par id_programme et id_financement_programme
    public function findByIdprogrammeAndIdfinancementprogramme($id_programme,$id_financement_programme) {
        $result =  $this->db->select('*')
                        ->from($this->table)
                        ->where("id_programme", $id_programme)
                        ->where("id_financement_programme", $id_financement_programme)
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