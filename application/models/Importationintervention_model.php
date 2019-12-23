<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Importationintervention_model extends CI_Model
{
    protected $table_menage = 'menage';
    protected $table_individu = 'individu';
	// Séléction region par nom
	public function selectionregion($nom) {
		$requete="select id,nom,code from region where lower(nom)='".$nom."' limit 1";
		$query = $this->db->query($requete);
        return $query->result();				
	}
	// Séléction region par id
	public function selectionregionparid($id) {
		$requete="select id,nom,code from region where id='".$id."'";
		$query = $this->db->query($requete);
        return $query->result();				
	}
	// Séléction district par nom et id_region
	public function selectiondistrict($nom,$id_region) {
		if(intval($id_region)==5) {
			$requete="select id,nom,code from district where region_id ='".$id_region."' limit 1";
		} else {
			$requete="select id,nom,code from district where lower(nom)='".$nom."' and region_id ='".$id_region."' limit 1";
		}	
		$query = $this->db->query($requete);
        return $query->result();				
	}
	// Séléction district par id
	public function selectiondistrictparid($id) {
			$requete="select id,nom,code,region_id from district where id ='".$id."'";
		$query = $this->db->query($requete);
        return $query->result();				
	}
	// Séléction commune par nom et id_district
	public function selectioncommune($nom,$id_district) {
		$requete="select id,nom,code from commune where lower(nom)='".$nom."' and district_id ='".$id_district."' limit 1";
		$query = $this->db->query($requete);
        return $query->result();				
	}
	// Séléction commune par id (clé primaire)
	public function selectioncommuneparid($id) {
		$requete="select id,nom,code,district_id from commune where id='".$id."'";
		$query = $this->db->query($requete);
        return $query->result();				
	}
	// Séléction fokontany par nom et id-commune
	public function selectionfokontany($nom,$id_commune) {
		$requete="select id,nom,code from fokontany where lower(nom)='".$nom."' and id_commune ='".$id_commune."' limit 1";
		$query = $this->db->query($requete);
        return $query->result();				
	}
	// Récupère id_menage par l'intermediaire de l'identifiant_appariement et id_acteur
    public function RechercheParIdentifiantActeur($parametre_table,$identifiant_appariement,$id_acteur) {
		if($parametre_table=="individu") {
			$requete= "select id as id_individu from individu where identifiant_appariement='".$identifiant_appariement."' and id_acteur=".$id_acteur;
			$query = $this->db->query($requete);
			return $query->result();
		} else {	
			$requete= "select id as id_menage from menage where identifiant_appariement='".$identifiant_appariement."' and id_acteur=".$id_acteur;
			$query = $this->db->query($requete);
			return $query->result();
		}	
    }
	// Test la présence d'un ménage ou individu selon les critères cités en paramètres (LIRE)
	public function RechercheParNomPrenom_Fokontany_Acteur($parametre_table,$identifiant_appariement,$id_acteur,$nom,$prenom,$id_fokontany) {
		if($parametre_table=="individu") {
			// Individu tout court : sans considération clé étrangère id_menage
			$requete="select id as id_individu from individu where identifiant_appariement='".$identifiant_appariement."' and id_acteur=".$id_acteur
					." and nom='".$nom."' and prenom='".$prenom."' and id_fokontany=".$id_fokontany;
			$query = $this->db->query($requete);
			return $query->result();				
		} else {
			// Chef ménage
			$requete="select id as id_menage from menage where identifiant_appariement='".$identifiant_appariement."' and id_acteur=".$id_acteur
					." and nom='".$nom."' and prenom='".$prenom."' and id_fokontany=".$id_fokontany;
			$query = $this->db->query($requete);
			return $query->result();				
		}
	}
	// Marquage liste intervention validés : c'est-à-dire déjà importés dans la BDD (aucune manipulation n'est permise,seulement 
	// download le fichier si l'utilisateur veut voir les contenus)
	public function MiseAJourListeValidationIntervention($id_liste_validation_beneficiaire,$date_validation,$id_utilisateur_validation) {
			$requete="update liste_validation_intervention set donnees_validees=1,date_validation='".$date_validation."',"
					."id_utilisateur_validation=".$id_utilisateur_validation." where id=".$id_liste_validation_beneficiaire;
			$query = $this->db->query($requete);
			return "OK";						
	}
}
?>