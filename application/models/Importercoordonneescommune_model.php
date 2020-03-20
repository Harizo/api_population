<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Importercoordonneescommune_model extends CI_Model
{
    protected $table = 'region';
	// Selection région par nom
	public function selectionregion($nom) {
		$requete="select id,nom,code from region where lower(nom)='".$nom."' limit 1";
		$query = $this->db->query($requete);
        return $query->result();				
	}
	// Selection region par id
	public function selectionregionparid($id) {
		$requete="select id,nom,code from region where id='".$id."'";
		$query = $this->db->query($requete);
        return $query->result();				
	}
	// Selection region par code
	public function selectionregionparcode($code) {
		$requete="select id,nom,code from region where code='".$code."'";
		$query = $this->db->query($requete);
        return $query->result();				
	}
	// Selection district par nom et par id_region
	public function selectiondistrict($nom,$id_region) {
		if(intval($id_region)==5) {
			$requete="select id,nom,code from district where region_id ='".$id_region."' limit 1";
		} else {
			$requete="select id,nom,code from district where lower(nom)='".$nom."' and region_id ='".$id_region."' limit 1";
		}	
		$query = $this->db->query($requete);
        return $query->result();				
	}
	// Selection district par id
	public function selectiondistrictparid($id) {
			$requete="select id,nom,code,region_id from district where id ='".$id."'";
		$query = $this->db->query($requete);
        return $query->result();				
	}
	// Selection district par code
	public function selectiondistrictparcode($code) {
			$requete="select id,nom,code,region_id from district where code='".$code."'";
		$query = $this->db->query($requete);
        return $query->result();				
	}
	// Selection commune par nom et par id_district
	public function selectioncommune($nom,$id_district) {
		$requete="select id,nom,code from commune where lower(nom)='".$nom."' and district_id ='".$id_district."' limit 1";
		$query = $this->db->query($requete);
        return $query->result();				
	}
	// Selection commune par id
	public function selectioncommuneparid($id) {
		$requete="select id,nom,code,district_id from commune where id='".$id."'";
		$query = $this->db->query($requete);
        return $query->result();				
	}
	// Selection commune par code
	public function selectioncommuneparcode($code) {
		$requete="select id,nom,code,district_id from commune where code='".$code."'";
		$query = $this->db->query($requete);
        return $query->result();				
	}
	// Selection fokontany par nom et par id_commune
	public function selectionfokontany($nom,$id_commune) {
		$requete="select id,nom,code from fokontany where lower(nom)='".$nom."' and id_commune ='".$id_commune."' limit 1";
		$query = $this->db->query($requete);
        return $query->result();				
	}
	// Selection fokontany par code
	public function selectionfokontanyparcode($code) {
		$requete="select id,nom,code,id_commune from fokontany where code='".$code."'";
		$query = $this->db->query($requete);
        return $query->result();				
	}
	// Selection données referentielles par designation et par nom de table differents
	public function selectionparametres($libelle,$nomdetable) {
		$requete="select id,libelle from ".$nomdetable." where lower(libelle)='".$libelle."'";
		$query = $this->db->query($requete);
        return $query->result();				
	}
	// Récupération contenu table region
	public function Recuperer_Region() {
		$requete="select * from region";
		$query = $this->db->query($requete);
        return $query->result();				
		
	}
	// Récupération contenu table region
	public function Recuperer_District() {
		$requete="select r.code  as code_region,d.code,d.nom from district as d"
				." join region as r on r.id=d.region_id"
				." order by r.code,d.code";
		$query = $this->db->query($requete);
        return $query->result();				
		
	}
	// Récupération contenu table commune
	public function Recuperer_Commune() {
		$requete="select d.code  as code_district,c.code,c.nom from commune as c"
				." join district as d on d.id=c.district_id"
				." order by d.code,c.code";
		$query = $this->db->query($requete);
        return $query->result();				
		
	}
	// Récupération contenu table fokontany
	public function Recuperer_Fokontany() {
		$requete="select c.code  as code_commune,f.code,f.nom from fokontany as f"
				." join commune as c on c.id=f.id_commune"
				." order by c.code,f.code";
		$query = $this->db->query($requete);
        return $query->result();						
	}
	// Selection région par nom
	public function selectionregion_avec_espace($nom1,$nom2) {
		$requete="select id,nom,code from region where lower(nom) like '%".$nom1."%' and lower(nom) like '%".$nom2."%' limit 1";
		$query = $this->db->query($requete);
        return $query->result();				
	}
	// Selection district par nom et id_region
	public function selectiondistrict_avec_espace($nom1,$nom2,$id_region) {
		$requete="select id,nom,code from district where lower(nom) like '%".$nom1."%' and lower(nom) like'%".$nom2."%' and region_id ='".$id_region."' limit 1";
		$query = $this->db->query($requete);
        return $query->result();				
	}	
	// Selection commune par nom et id_district
	public function selectioncommune_avec_espace($nom1,$nom2,$id_district) {
		$requete="select id,nom,code from commune where lower(nom) like '%".$nom1."%' and lower(nom) like'%".$nom2."%' and district_id ='".$id_district."' limit 1";
		$query = $this->db->query($requete);
        return $query->result();				
	}
	public function selection_coordonnees_commune($id_commune) {
		$requete="select * from limite_commune where id_commune ='".$id_commune."'";
		$query = $this->db->query($requete);
        return $query->result();						
	}
	public function miseajour_coordonnees_commune($id_commune,$coordonnees_text) {
		$requete="update commune set coordonnees='".$coordonnees_text."' where id='".$id_commune."'";
		$query = $this->db->query($requete);
        if($this->db->affected_rows() === 1)  
        {
            return true;
        }
        else
        {
            return false;
        }    		
	}
}
?>