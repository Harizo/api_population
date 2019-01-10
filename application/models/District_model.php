<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class District_model extends CI_Model {
    protected $table = 'district';

    public function add($district) {
        $this->db->set($this->_set($district))
                            ->insert($this->table);
        if($this->db->affected_rows() === 1) {
            return $this->db->insert_id();
        }else{
            return null;
        }                    
    }
    public function update($id, $district) {
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
        return array(
            'code'          =>      $district['code'],
            'nom'           =>      $district['nom'],
            'region_id'     =>      $district['region_id']                       
        );
    }
    public function delete($id) {
        $this->db->where('id', (int) $id)->delete($this->table);
        if($this->db->affected_rows() === 1)
        {
            return true;
        }else{
            return null;
        }  
    }
    public function findAll() {
/*		$requete='select d.id,d.nom,d.code,d.region_id,r.nom as region,r.site_id,s.nom as nom_site
		from district as d
		left outer join region as r on r.id=d.region_id
		left outer join site as s on s.id=r.site_id
		order by r.site_id,d.nom,r.nom	';				
		$query= $this->db->query($requete);
        if($query->result()) {
			return $query->result();
            // return $result;
        }else{
            return null;
        }  */               
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
    public function findById($id)  {
        $this->db->where("id", $id);
        $q = $this->db->get($this->table);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        // return null;
	/*	$requete='select d.id,d.nom,d.code,d.region_id,r.nom as region,r.site_id,s.nom as nom_site
		from district as d
		left outer join region as r on r.id=d.region_id
		left outer join site as s on s.id=r.site_id where d.id='.$id
		.' order by r.site_id,d.nom,r.nom ';				
			$query= $this->db->query($requete);*/
			// if($query->result())
			// {
				// return $query->row();
				// return $result;
			// }else{
				// return null;
			// }   
    }
}
