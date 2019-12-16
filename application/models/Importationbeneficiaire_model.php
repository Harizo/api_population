<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Importationbeneficiaire_model extends CI_Model
{
    protected $table_menage = 'menage';
    protected $table_individu = 'individu';
	// Séléction region par nom
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
    public function RechercheParIdentifiantActeur($identifiant_appariement,$id_acteur) {
		$requete= "select id as id_menage from menage where identifiant_appariement='".$identifiant_appariement."' and id_acteur=".$id_acteur;
		$query = $this->db->query($requete);
        return $query->result();				
    }
	// Test la présence d'un ménage ou individu selon les critères cités en paramètres (LIRE)
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
	// Attribution Identifiant Unique Menage
	public function AttributionIdentifiantUniqueMenage() {
			$requete="select (count(*) + 1) as nombre from menage";
			$query = $this->db->query($requete);
			return $query->result();						
	}
	// Attribution Identifiant Unique Individu
	public function AttributionIdentifiantUniqueIndividu() {
			// Identifiant unique individu : départ à partir 10 000 001
			$requete="select (count(*) + 10000001) as nombre from individu";
			$query = $this->db->query($requete);
			return $query->result();						
	}
	// Marquage liste bénéficiaire validés : c'est-à-dire déjà importés dans la BDD (aucune manipulation n'est permise,seulement
	// download le fichier si l'utilisateur veut voir les contenus)
	public function MiseAJourListeValidationBeneficiaire($id_liste_validation_beneficiaire,$date_validation,$id_utilisateur_validation,$id_fokontany,$id_intervention) {
			$requete="update liste_validation_beneficiaire set donnees_validees=1,date_validation='".$date_validation."',"
					."id_utilisateur_validation=".$id_utilisateur_validation.",id_fokontany=".$id_fokontany.",id_intervention=".$id_intervention
					." where id=".$id_liste_validation_beneficiaire;
			$query = $this->db->query($requete);
			return "OK";						
	}
	// Nombre liste fichier beneficiaire non importes : pour affichage dans le menu
	public function recuperer_nombre_liste_fichier_non_importes_beneficiaire() {
		$requete="select count(*) as nombre_beneficiaire_non_importes from liste_validation_beneficiaire where date_validation IS NULL";
		$query = $this->db->query($requete);
		return $query->result();				
	}
	// Nombre liste fichier intervention non importes : pour affichage dans le menu
	public function recuperer_nombre_liste_fichier_non_importes_intervention() {
		$requete="select count(*) as nombre_intervention_non_importes from liste_validation_intervention where date_validation IS NULL";
		$query = $this->db->query($requete);
		return $query->result();				
	}
	// Recherche id liste variable
	public function selectionlistevariable($description) {
		$requete="select id,description,code from liste_variable where lower(description) like '%".$description."%' limit 1";
		$query = $this->db->query($requete);
        return $query->result();				
	}
	// Récupération d'un enregistrement par id liste variable et par description
    public function findByIdlistevariableAndDescription($id_liste_variable,$description) {
		$requete ="select id as id_variable from variable where id_liste_variable=".$id_liste_variable
				." and lower(description) like '%".$description."%' limit 1";
		$query = $this->db->query($requete);
        $result= $query->result();				
        if($result) {
            return $result;
        }else{
            return null;
        }                 
    }			
}
?>