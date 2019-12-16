<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Importerdecoupageadministratif_model extends CI_Model
{
    protected $table = 'region';
	// Selection région par nom
	public function selectionregion($nom) {
		$requete="select id,nom,code from region where lower(nom)='".$nom."' limit 1";
		$query = $this->db->query($requete);
        return $query->result();				
	}
	// Selection region par id
	public function selectionregionparid($nom) {
		$requete="select id,nom,code from region where nom like'%".$nom."%'";
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
}
?>