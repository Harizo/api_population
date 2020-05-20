<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Region_model extends CI_Model
{
    protected $table = 'region';


    public function add($region)
    {	// Ajout d'un enregitrement
        $this->db->set($this->_set($region))
                            ->insert($this->table);
        if($this->db->affected_rows() === 1)
        {
            return $this->db->insert_id();
        }else{
            return null;
        }                    
    }
    public function addImport($region)
    {	// Ajout d'un enregitrement
        $this->db->set($this->_setImport($region))
                            ->insert($this->table);
        if($this->db->affected_rows() === 1)
        {
            return $this->db->insert_id();
        }else{
            return null;
        }                    
    }


    public function update($id, $region)
    {	// Mise à jour d'un enregitrement
        $this->db->set($this->_set($region))
                            ->where('id', (int) $id)
                            ->update($this->table);
        if($this->db->affected_rows() === 1)
        {
            return true;
        }else{
            return null;
        }                      
    }

    public function _set($region)
    {	// Affectation des valeurs
        return array(
            'code'       =>      $region['code'],
            'nom'        =>      $region['nom'],
            //'superficie'    =>      $region['superficie']                       
        );
    }
    public function _setImport($region)
    {	// Affectation des valeurs
        return array(
            'code'       =>  $region['code'],
            'nom'        =>  $region['nom'],
            'chef_lieu'  =>  $region['chef_lieu']                       
        );
    }
    public function delete($id)
    {	// Suppression d'un enregitrement
        $this->db->where('id', (int) $id)->delete($this->table);
        if($this->db->affected_rows() === 1)
        {
            return true;
        }else{
            return null;
        }  
    }

    public function findAll()
    {	// Selection de tous les enregitrements
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

    public function findAll_filter()
    {   
        $sql = "


                select

                    niv_1.id,
                    niv_1.code,
                    niv_1.nom,
                    niv_1.chef_lieu

                from
                    (
                        select 

                            DISTINCT(reg.id) as id,
                            reg.nom as nom,
                            reg.code as code,
                            reg.chef_lieu as chef_lieu
                              
                        from 
                            menage as men

                            join fokontany as foko on foko.id=men.id_fokontany
                            join commune as com on com.id=foko.id_commune
                            join district as dist on dist.id=com.district_id
                            join region as reg on reg.id=dist.region_id

                    UNION

                        select 

                            DISTINCT(reg.id) as id,
                            reg.nom as nom,
                            reg.code as code,
                            reg.chef_lieu as chef_lieu
                              
                        from 
                            individu as ind

                            join fokontany as foko on foko.id=ind.id_fokontany
                            join commune as com on com.id=foko.id_commune
                            join district as dist on dist.id=com.district_id
                            join region as reg on reg.id=dist.region_id


                    ) niv_1

                order by niv_1.nom
                      
                ";              

        return $this->db->query($sql)->result();
    }

    public function findById($id)  {
		// Selection par id
        $this->db->where("id", $id);
        $q = $this->db->get($this->table);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return null;
    }
    public function findByIdArray($id)  {
		// Selection par id : résultat dans un tableau
        $result =  $this->db->select('*')
                        ->from($this->table)
                        ->where("id", $id)
                        ->order_by('id', 'asc')
                        ->get()
                        ->result();
        if($result) {
            return $result;
        }else{
            return array();
        }                 
    }
}
