<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Validationintervention_model extends CI_Model
{
    protected $table = 'region';
	// Selection région par nom
	public function selectionregion($nom) {
		$requete="select id,nom,code from region where lower(nom) like '%".$nom."%' limit 1";
		$query = $this->db->query($requete);
        return $query->result();				
	}
	// Selection région par id
	public function selectionregionparid($id) {
		$requete="select id,nom,code from region where id='".$id."'";
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
	public function selectiondistrict($nom,$id_region) {
		$requete="select id,nom,code from district where lower(nom) like '%".$nom."%' and region_id ='".$id_region."' limit 1";
		$query = $this->db->query($requete);
        return $query->result();				
	}
	// Selection district par id
	public function selectiondistrictparid($id) {
			$requete="select id,nom,code,region_id from district where id ='".$id."'";
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
	public function selectioncommune($nom,$id_district) {
		$requete="select id,nom,code from commune where lower(nom) like '%".$nom."%' and district_id ='".$id_district."' limit 1";
		$query = $this->db->query($requete);
        return $query->result();				
	}
	// Selection commune par id
	public function selectioncommuneparid($id) {
		$requete="select id,nom,code,district_id from commune where id='".$id."'";
		$query = $this->db->query($requete);
        return $query->result();				
	}
	// Selection commune par nom et id_district
	public function selectioncommune_avec_espace($nom1,$nom2,$id_district) {
		$requete="select id,nom,code from commune where lower(nom) like '%".$nom1."%' and lower(nom) like'%".$nom2."%' and district_id ='".$id_district."' limit 1";
		$query = $this->db->query($requete);
        return $query->result();				
	}
	// Selection fokontany par nom et id_commune
	public function selectionfokontany($nom,$id_commune) {
		$requete="select id,nom,code from fokontany where lower(nom) ='".$nom."' and id_commune ='".$id_commune."' limit 1";
		$query = $this->db->query($requete);
        return $query->result();				
	}
	// Selection fokontany par nom et id_commune
	public function selectionfokontany_avec_espace($nom1,$nom2,$id_commune) {
		$requete="select id,nom,code from fokontany where lower(nom) like '%".$nom1."%' and lower(nom) like'%".$nom2."%' and id_commune ='".$id_commune."' limit 1";
		$query = $this->db->query($requete);
        return $query->result();
	}	
	// Comptage bénéficiaire par identifiant_appariement et id_acteur
    public function RechercheParIdentifiantActeur($identifiant_appariement,$id_acteur) {
		$requete= "select count(*) as nombre from menage where identifiant_appariement='".$identifiant_appariement."' and id_acteur=".$id_acteur;
		$query = $this->db->query($requete);
        return $query->result();				
    }
	// Recherche l'existence d'un ménage ou un individu selon le cas : critères = lire paramètres de la focntion
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
	// Recherche si un intervention se déroule dans un fokontany donné
	public function RechercheFokontanyIntervention($id_fokontany,$id_intervention) {
		$requete="select count(*) as nombre from zone_intervention where id_fokontany=".$id_fokontany." and id_intervention=".$id_intervention;
		$query = $this->db->query($requete);
		return $query->result();						
	}
	// Recherche doublon si le fichier à controler se trouve déjà dans la BDD : critères = $parametre_table : individu ou menage,$date_intervention,$id_fokontany,$id_intervention
	public function RechercheDoublonInterventionParDateEtFokontany($parametre_table,$date_intervention,$id_fokontany,$id_intervention) {
		if($parametre_table=="ménage") {
			$requete="select count(*) as nombre from suivi_menage_entete where date_suivi='".$date_intervention."' and id_fokontany=".$id_fokontany." and id_intervention=".$id_intervention;
			$query = $this->db->query($requete);
		} else {
			$requete="select count(*) as nombre from suivi_individu_entete where date_suivi='".$date_intervention."' and id_fokontany=".$id_fokontany." and id_intervention=".$id_intervention;
			$query = $this->db->query($requete);			
		}	
		return $query->result();					
	}
}
