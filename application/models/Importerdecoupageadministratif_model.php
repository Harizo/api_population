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
	// Récupération contenu table variable et liste_variable
	public function Recuperer_Variable() {
		$requete="select lv.code as code_liste_variable,lv.description as description_liste_variable,"
				 ."v.code,v.description"
				 ." from liste_variable as lv"
				 ." join variable as v on lv.id=v.id_liste_variable"
				 ." order by lv.code,v.code";		
		$query = $this->db->query($requete);
        return $query->result();						
	}
	// Récupération contenu table type de transfert,détail type de trasnfert et unité de mesure 
	public function Recuperer_Type_transfert() {
		$requete="select tt.code as code_type_transfert,tt.description as description_type_transfert,"
				."dtt.code,dtt.description,um.code as code_unite_mesure,um.description as description_unite_mesure"
				." from type_transfert as tt"
				." join detail_type_transfert as dtt on tt.id=dtt.id_type_transfert"
				." join unite_mesure as um on um.id=dtt.id_unite_mesure"
				." order by tt.code,dtt.code";
		$query = $this->db->query($requete);
        return $query->result();				
	}
	// Récupération contenu des tables nomenclature intervention
	public function Recuperer_Nomenclature_intervention() {
		$requete="select ni1.code as code1,ni1.description as description1,"
				."ni2.code as code2,ni2.description as description2,"
				."ni3.code as code3,ni3.description as description3,"
				."ni4.code,ni4.description"
				." from nomenclature_intervention1 as ni1"
				." join nomenclature_intervention2 as ni2 on ni1.id=ni2.id_nomenclature1"
				." join nomenclature_intervention3 as ni3 on ni2.id=ni3.id_nomenclature2"
				." join nomenclature_intervention4 as ni4 on ni3.id=ni4.id_nomenclature3"
				." order by ni1.code,ni2.code,ni3.code,ni4.code";				
		$query = $this->db->query($requete);
        return $query->result();				
	}
}
?>