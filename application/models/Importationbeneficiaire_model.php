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
    public function RechercheParIdentifiantActeur($table,$identifiant_appariement,$id_acteur) {
		if($table=="menage") {
			$requete= "select id as id_menage from menage where identifiant_appariement='".$identifiant_appariement."' and id_acteur=".$id_acteur;
		} else {
			$requete= "select id as id_individu from individu where identifiant_appariement='".$identifiant_appariement."' and id_acteur=".$id_acteur;			
		}	
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
	public function recuperer_id_situation_matrimoniale($description) {
		$requete ="select id as id_situation_matrimoniale from situation_matrimoniale"
				." where lower(description) like '%".$description."%'";
		$query = $this->db->query($requete);
        $result= $query->result();				
        if($result) {
            return $result;
        }else{
            return null;
        }                 		
		
	}
	// Récupération id de lien de parenté
	public function recuperer_id_liendeparente($lien_de_parente) {
		$requete ="select id as id_liendeparente from liendeparente"
				." where lower(description) like '%".$lien_de_parente."%'";
		$query = $this->db->query($requete);
        $result= $query->result();				
        if($result) {
            return $result;
        }else{
            return null;
        }                 		
		
	}
	// Récupération id niveau de classe
	public function recuperer_id_niveau_de_classe($niveau_classe) {
		$requete ="select id as id_niveau_de_classe from niveau_de_classe"
				." where lower(description) like '%".$niveau_classe."%'";
		$query = $this->db->query($requete);
        $result= $query->result();				
        if($result) {
            return $result;
        }else{
            return null;
        }                 		
		
	}
	// Récupération id de l'indice de vulnérabilité
	public function recuperer_id_indice_vulnerabilite($description) {
		$requete="select id as id_indice_vulnerabilite from indice_vulnerabilite where lower(description) like '%".$description."%'";
		$query = $this->db->query($requete);
		return $query->result();				
	}	
	// Récupération contenu fichier temporaire
	public function RecupererTableTemporaire() {
		// $requete="select * from temporaire where district='VATOMANDRY' order by id";
		$requete="select * from temporaire "
		// ."where id<=60001"
		// ."where id >=60002 and id <=120000"
		// ."where id >=120001 and id <=180002"
		// ."where id >=180003 and id <=240000"
		// ."where id >=240001 and id <=249999"
		// ."where id >=250000 and id <=265000"
		// ."where id >=265001 and id <=300000"
		// ."where id >=300001 and id <=350000"
		// ."where id >=350001 and id <=355000"
		// ."where id >=355001 and id <=375003" // 12-05-2020
		// ."where id >=375004 and id <=378002" // 12-05-2020
		// ."where id >=378003 and id <=420005" // 12-05-2020
		// ."where id >=420006 and id <=469911" // Tapaka départ vaovao = 457470
		// ."where id >=457470 and id <=490001" // 13-05-2020
		// ."where id >=490002 and id <=502001" // 13-05-2020
		// ."where id >=502002 and id <=505004" // 13-05-2020
		// ."where id >=502005 and id <=508006" // 13-05-2020
		// ."where id >=508007 and id <=512009"
		// ."where id >=512010 and id <=516002"
		// ."where id >=516003 and id <=520001"
		// ."where id >=520002 and id <=524000"
		// ."where id >=524001 and id <=528004"
		// ."where id >=528005 and id <=532001"
		// ."where id >=532002 and id<=536005"
		// ."where id >=536006 and id<=540500"
		// ."where id >=540501 and id<=545503"
		// ."where id >=545504 and id<=549502"
		// ."where id >=549503"
		// ." where lower(region) like '%androy%' and lower(district) like '%tsihombe%' "
		// ." and lower(commune) in ('tsihombe','nikoly','anjampaly') and id < 457470"
		// Tsihombe  34821 lignes : 14852 individu et 3791 menage
		." where lower(region) like '%atsimo andrefana%' and lower(district) like '%ankazoabo%' "
		." and lower(commune) in ('fotivolo','berenty','ankeriky','ankazoabo sud') "		
		." order by id";
		$query = $this->db->query($requete);
		return $query->result();				
	}	
	// Mise à jour observation : ERREUR
	public function MiseajourTableTemporaire($id_temporaire,$observation) {
		$requete="update temporaire set observation='".$observation."' where id=".$id_temporaire;
		$query = $this->db->query($requete);
		return "OK";				
	}	
	public function MiseajourIdmenageIdIndividuTableTemporaire($id_temporaire,$id_menage,$id_individu) {
		$requete="update temporaire set id_menage=".$id_menage.",id_individu=".$id_individu." where id=".$id_temporaire;
		$query = $this->db->query($requete);
		return "OK";				
	}	
}
?>