<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Importationintervention_model extends CI_Model
{
    protected $table_menage = 'menage';
    protected $table_individu = 'individu';
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
		$requete="select id,nom,code from fokontany where lower(nom) like '%".$nom."%' and id_commune ='".$id_commune."' limit 1";
		$query = $this->db->query($requete);
        return $query->result();				
	}
	// Selection fokontany par nom et id_commune
	public function selectionfokontany_avec_espace($nom1,$nom2,$id_commune) {
		$requete="select id,nom,code from fokontany where lower(nom) like '%".$nom1."%' and lower(nom) like'%".$nom2."%' and id_commune ='".$id_commune."' limit 1";
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
	public function recuperer_code_region_district_commune_fokontany($id_fokontany) {
		$requete ="select concat_ws('-',r.code,d.code,c.code,f.code) as code_precedent,r.nom as region,"
				." d.nom as district,c.nom as commune,f.nom as fokontany"
				." from fokontany as f"
				." join commune as c on f.id_commune=c.id"
				." join district as d on c.district_id=d.id"
				." join region as r on d.region_id=r.id"
				." where f.id=".$id_fokontany;
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