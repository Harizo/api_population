<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Commune_model extends CI_Model {
    protected $table = 'commune';

    public function add($commune) {
        $this->db->set($this->_set($commune))
                            ->insert($this->table);
        if($this->db->affected_rows() === 1)
        {
            return $this->db->insert_id();
        }else{
            return null;
        }                    
    }
    public function update($id, $commune) {
        $this->db->set($this->_set($commune))
                            ->where('id', (int) $id)
                            ->update($this->table);
        if($this->db->affected_rows() === 1) {
            return true;
        }else{
            return null;
        }                      
    }
    public function _set($commune) {
        return array(
            'code'           =>      $commune['code'],
            'nom'            =>      $commune['nom'],
            'district_id'    =>      $commune['district_id']                       
        );
    }
    public function delete($id) {
        $this->db->where('id', (int) $id)->delete($this->table);
        if($this->db->affected_rows() === 1) {
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
        if($result)
        {
            return $result;
        }else{
            return null;
        }                 
    }
	public function find_Commune_avec_District_et_Region($id_district) {
		$requete='select c.id,c.nom,c.code,c.district_id,d.nom as nomdistrict,d.region_id,r.nom as region'
				.' from commune as c'
				.' left outer join district as d on d.id=c.district_id'
				.' left outer join region as r on r.id=d.region_id'
				.' where c.district_id='.$id_district
				.' order by c.nom,d.nom,r.nom	';				
		$query= $this->db->query($requete);		
		if($query->result()) {
			return $query->result();
        }else{
            return null;
        }  
	}
	public function find_Fokontany_avec_District_et_Region($id_commune) {
		$requete='select f.id,f.nom,f.code,f.id_commune,c.nom as commune,d.nom as nomdistrict,d.region_id,r.nom as region'
				.' from fokontany as f'
				.' left outer join commune as c on c.id=f.id_commune'
				.' left outer join district as d on d.id=c.district_id'
				.' left outer join region as r on r.id=d.region_id'
				.' where f.id_commune='.$id_commune
				.' order by f.nomc.nom,,d.nom,r.nom	';				
		$query= $this->db->query($requete);		
		if($query->result()) {
			return $query->result();
        }else{
            return null;
        }  
	}
    public function findAllByDistrict($district_id) {
        $result =  $this->db->select('*')
                        ->from($this->table)
                        ->order_by('nom')
                        ->where("district_id", $district_id)
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
    /*public function findById($id)  {
        $this->db->where("id", $id);
        $q = $this->db->get($this->table);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return null;
		if(isset($id)) {
			$requete='select c.id,c.nom,c.code,c.district_id,d.nom as nomdistrict,r.nom as region,r.site_id,s.nom as nom_site
			from commune as c
			left outer join district as d on d.id=c.district_id
			left outer join region as r on r.id=d.region_id
			left outer join site as s on s.id=r.site_id where c.id='.$id
			.' order by r.site_id,c.nom,d.nom	';				
			$query= $this->db->query($requete);
			if($query->result())
			{
				return $query->row();
				// return $result;
			}else{
				return null;
			}   
		} else {
			return null;
		}	
    }*/
}
