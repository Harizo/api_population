<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Commune_model extends CI_Model {
    protected $table = 'commune';

    public function add($commune) {
		// Ajout d'un enregitrement
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
		// Mise à jour d'un enregitrement
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
		// Affectation des valeurs
        return array(
            'code'           =>      $commune['code'],
            'nom'            =>      $commune['nom'],
            'coordonnees'    =>      $commune['coordonnees'],
            'district_id'    =>      $commune['district_id']                       
        );
    }
    public function delete($id) {
		// Suppression d'un enregitrement
        $this->db->where('id', (int) $id)->delete($this->table);
        if($this->db->affected_rows() === 1) {
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
	public function find_Commune_avec_District_et_Region($id_district) {
		// Selection commune avec description district et région correspondant
		$requete='select c.id,c.nom,c.code,c.district_id,d.nom as nomdistrict,d.region_id,r.nom as region,c.coordonnees'
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
		// Selection fokontany avec description commune,district et région correspondant
		$requete='select f.id,f.nom,f.code,f.id_commune,c.nom as commune,d.nom as nomdistrict,d.region_id,r.nom as region,c.coordonnees'
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
		// Selection commune par district
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

    public function get_analyse_strategique_by_district($district_id)
    {
        $sql =
        "
            select 
                com.id as id,
                com.nom as nom,
                com.coordonnees as coordonnees,

                (
                    select
                        count(menage_beneficiaire.id) 
                    from
                        menage,
                        menage_beneficiaire,
                        fokontany,
                        commune,
                        district
                    where
                        menage.id = menage_beneficiaire.id_menage
                        and fokontany.id = menage.id_fokontany
                        and commune.id = fokontany.id_commune
                        and district.id = commune.district_id
                        and district.id = ".$district_id."
                ) as total_nbr_menage_beneficiaire_district,
                (
                    select
                        count(menage_beneficiaire.id) 
                    from
                        menage,
                        menage_beneficiaire,
                        fokontany,
                        commune
                    where
                        menage.id = menage_beneficiaire.id_menage
                        and fokontany.id = menage.id_fokontany
                        and commune.id = fokontany.id_commune
                        and commune.id = com.id
                ) as nbr_menage_beneficiaire_commune,


                (
                    select
                        count(individu_beneficiaire.id) 
                    from
                        individu,
                        individu_beneficiaire,
                        fokontany,
                        commune
                    where
                        individu.id = individu_beneficiaire.id_individu
                        and fokontany.id = individu.id_fokontany
                        and commune.id = fokontany.id_commune
                        and commune.id = com.id
                ) as nbr_individu_beneficiaire_commune

            from
                commune as com,
                district as dist
            where
                com.district_id = dist.id 
                and dist.id = ".$district_id."
        " ;

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
    public function findByIdOLD($id) 
    {	// Selection par id
        $this->db->where("id", $id);
        $q = $this->db->get($this->table);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
    }	
    public function findAllByDistrictObjet($district_id) {
		// Selection commune par district
        $this->db->where("district_id", $district_id);
        $q = $this->db->get($this->table);
        if ($q->num_rows() > 0) {
            return $q->row(); 
        }                
    }
}
