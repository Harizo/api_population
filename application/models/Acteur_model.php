<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Acteur_model extends CI_Model {
    protected $table = 'acteur';

    public function add($acteur)  {
        $this->db->set($this->_set($acteur))
                            ->insert($this->table);
        if($this->db->affected_rows() === 1)  {
            return $this->db->insert_id();
        }else{
            return null;
        }                    
    }
    public function update($id, $acteur)  {
        $this->db->set($this->_set($acteur))
                            ->where('id', (int) $id)
                            ->update($this->table);
        if($this->db->affected_rows() === 1)  {
            return true;
        }else{
            return null;
        }                      
    }
    public function _set($acteur) {
        return array(
            'nom' => $acteur['nom'],
            'nif' => $acteur['nif'],
            'stat' => $acteur['stat'],
            'adresse' => $acteur['adresse'],
            'id_fokontany' => $acteur['id_fokontany'],
            'representant' => $acteur['representant'],
            'fonction' => $acteur['fonction'],
            'telephone' => $acteur['telephone'],
            'email' => $acteur['email'],
            'rcs' => $acteur['rcs'],
            'id_type_acteur' => $acteur['id_type_acteur'],
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
                        ->order_by('nom')
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
    public function findByNom($nom) {
		$requete= "select * from acteur where lower(nom) like '%".$nom."%'";
		$query = $this->db->query($requete);
        $result= $query->result();				
        /*$result =  $this->db->select('*')
                        ->from($this->table)
                        ->where('lower(nom)', $nom)
                        ->order_by('nom', 'asc')
                        ->get()
                        ->result();*/
        if($result) {
            return $result;
        }else{
            return array();
        }                 
    }
}
?>