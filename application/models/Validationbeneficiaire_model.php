<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Validationbeneficiaire_model extends CI_Model
{
    protected $table = 'region';

	public function selectionregion($nom) {
		$requete="select id,nom,code from region where lower(nom)='".$nom."' limit 1";
		$query = $this->db->query($requete);
        return $query->result();				
	}
	public function selectionregionparid($id) {
		$requete="select id,nom,code from region where id='".$id."'";
		$query = $this->db->query($requete);
        return $query->result();				
	}
	public function selectiondistrict($nom,$id_region) {
		if(intval($id_region)==5) {
			$requete="select id,nom,code from district where region_id ='".$id_region."' limit 1";
		} else {
			$requete="select id,nom,code from district where lower(nom)='".$nom."' and region_id ='".$id_region."' limit 1";
		}	
		$query = $this->db->query($requete);
        return $query->result();				
	}
	public function selectiondistrictparid($id) {
			$requete="select id,nom,code,region_id from district where id ='".$id."'";
		$query = $this->db->query($requete);
        return $query->result();				
	}
	public function Coderegionsuivant() {
		$requete="select count(*) as nombreregion from region";
		$query = $this->db->query($requete);
        return $query->result();				
	}
	public function selectioncommune($nom,$id_district) {
		$requete="select id,nom,code from commune where lower(nom)='".$nom."' and district_id ='".$id_district."' limit 1";
		$query = $this->db->query($requete);
        return $query->result();				
	}
	public function selectioncommuneparid($id) {
		$requete="select id,nom,code,district_id from commune where id='".$id."'";
		$query = $this->db->query($requete);
        return $query->result();				
	}
	public function selectionfokontany($nom,$id_commune) {
		$requete="select id,nom,code from fokontany where lower(nom)='".$nom."' and id_commune ='".$id_commune."' limit 1";
		$query = $this->db->query($requete);
        return $query->result();				
	}
	public function RAZ_Fokontany() {
		$requete="delete from fokontany;
			ALTER SEQUENCE fokontany_id_seq RESTART WITH 1	";
		$query = $this->db->query($requete);
        return 'OK';				
	}
	public function selectionparametres($libelle,$nomdetable) {
		$requete="select id,libelle from ".$nomdetable." where lower(libelle)='".$libelle."'";
		$query = $this->db->query($requete);
        return $query->result();				
	}
    public function RechercheParIdentifiantPartenaire($identifiant_appariement,$id_acteur) {
		$requete= "select count(*) as nombre from menage where identifiant_appariement='".$identifiant_appariement."' and id_acteur=".$id_acteur;
		$query = $this->db->query($requete);
        return $query->result();				
    }
	public function RechercheParNomPrenomCIN_Fokontany_Acteur($parametre_table,$identifiant_appariement,$id_acteur,$nom,$prenom,$cin,$id_fokontany) {
		if($parametre_table=="individu") {
			// Individu tout court : sans considération clé étrangère id_menage
			$requete="select count(*) as nombre from individu where identifiant_appariement='".$identifiant_appariement."' and id_acteur=".$id_acteur
					." and nom='".$nom."' and prenom='".$prenom."' and cin='".$cin."' and id_fokontany=".$id_fokontany;
			$query = $this->db->query($requete);
			return $query->result();				
		} else if($parametre_table=="menage") {
			// Chef ménage
			$requete="select count(*) as nombre from menage where identifiant_appariement='".$identifiant_appariement."' and id_acteur=".$id_acteur
					." and nom='".$nom."' and prenom='".$prenom."' and cin='".$cin."' and id_fokontany=".$id_fokontany;
			$query = $this->db->query($requete);
			return $query->result();				
		} else if($parametre_table=="individu_menage") {
			// Individu appartenant à un ménage
			$requete="select count(*) as nombre from individu where identifiant_appariement='".$identifiant_appariement."' and id_acteur=".$id_acteur
					." and nom='".$nom."' and prenom='".$prenom."' and cin='".$cin."' and id_fokontany=".$id_fokontany;
			$query = $this->db->query($requete);
			return $query->result();				
		}
	}
}
