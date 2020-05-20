<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class District_model extends CI_Model {
    protected $table = 'district';

    public function add($district) {
		// Ajout d'un enregitrement
        $this->db->set($this->_set($district))
                            ->insert($this->table);
        if($this->db->affected_rows() === 1) {
            return $this->db->insert_id();
        }else{
            return null;
        }                    
    }
    public function update($id, $district) {
		// Mise à jour d'un enregitrement
        $this->db->set($this->_set($district))
                            ->where('id', (int) $id)
                            ->update($this->table);
        if($this->db->affected_rows() === 1)
        {
            return true;
        }else{
            return null;
        }                      
    }
    public function _set($district) {
		// Affectation des valeurs
        return array(
            'code'          =>      $district['code'],
            'nom'           =>      $district['nom'],
            'region_id'     =>      $district['region_id']                       
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
                        ->order_by('nom')
                        ->get()
                        ->result();
        if($result)
        {
            return $result;
        }else{
            return null;
        }                 
    }
    public function findAllByRegion($region_id) {
		// Selection district par région
        $result =  $this->db->select('*')
                        ->from($this->table)
                        ->order_by('nom')
                        ->where("region_id", $region_id)
                        ->get()
                        ->result();
        if($result) {
            return $result;
        }else{
            return null;
        }                 
    }

    public function findAllByRegion_filter($region_id)
    {
        $sql = "select

                    niv_1.id,
                    niv_1.code,
                    niv_1.nom

                from
                    (
                        select 

                            DISTINCT(dist.id) as id,
                            dist.nom as nom,
                            dist.code as code
                              
                        from 
                            menage as men

                            join fokontany as foko on foko.id=men.id_fokontany
                            join commune as com on com.id=foko.id_commune
                            join district as dist on dist.id=com.district_id
                            join region as reg on reg.id=dist.region_id
                        where 
                            reg.id = ".$region_id." 

                    UNION

                        select 

                            DISTINCT(dist.id) as id,
                            dist.nom as nom,
                            dist.code as code
                              
                        from 
                            individu as ind

                            join fokontany as foko on foko.id=ind.id_fokontany
                            join commune as com on com.id=foko.id_commune
                            join district as dist on dist.id=com.district_id
                            join region as reg on reg.id=dist.region_id
                        where 
                            reg.id = ".$region_id." 


                    ) niv_1

                order by niv_1.nom
                      
                ";              

        return $this->db->query($sql)->result();
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
    public function findByIdOLD($id)  {
		// Selection par id
        $this->db->where("id", $id);
        $q = $this->db->get($this->table);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
    }
}
