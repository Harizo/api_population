<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Historique_utilisateur_model extends CI_Model
{
    protected $table = 'historique_utilisateur';


    public function add($historique_utilisateur)
    {
        $this->db->set($this->_set($historique_utilisateur))
                            ->set('date_action', 'NOW()', false)
                            ->insert($this->table);
        if($this->db->affected_rows() === 1)
        {
            return $this->db->insert_id();
        }else{
            return null;
        }                    
    }


    public function update($id, $historique_utilisateur)
    {
        $this->db->set($this->_set($historique_utilisateur))
                            ->where('id', (int) $id)
                            ->update($this->table);
        if($this->db->affected_rows() === 1)
        {
            return true;
        }else{
            return null;
        }                      
    }

    public function _set($historique_utilisateur)
    {
        return array(
            'action'           =>      $historique_utilisateur['action'],
            'id_utilisateur'    =>      $historique_utilisateur['id_utilisateur']                       
        );
    }


    public function delete($id)
    {
        $this->db->where('id', (int) $id)->delete($this->table);
        if($this->db->affected_rows() === 1)
        {
            return true;
        }else{
            return null;
        }  
    }

    public function findAll()
    {
        $result =  $this->db->select('*')
                        ->from($this->table)
                        ->order_by('date_action','desc')
                        ->get()
                        ->result();
        if($result)
        {
            return $result;
        }else{
            return null;
        }                 
    }



    public function findById($id)
    {
        $this->db->where("id", $id);
        $q = $this->db->get($this->table);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return null;
    }

    public function findByDateUtilisateur($requete)
    {
        $result =  $this->db->select('*')
                        ->from($this->table)
                        ->order_by('date_action','desc')
                        ->where($requete)
                        ->get()
                        ->result();
        if($result)
        {
            return $result;
        }else{
            return null;
        }                 
    }

}
