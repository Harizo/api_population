<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Validationbeneficiaire_model extends CI_Model
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
	// Selection commune par nom et id_district
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
	// Selection fokontany par nom et id_commune
	public function selectionfokontany($nom,$id_commune) {
		$requete="select id,nom,code from fokontany where lower(nom)='".$nom."' and id_commune ='".$id_commune."' limit 1";
		$query = $this->db->query($requete);
        return $query->result();				
	}
    public function RechercheIndividuParMenageNomPrenomFokontanyActeur($id_menage,$nom,$prenom,$id_fokontany,$id_acteur) {
		$requete= "select count(*) as nombre from individu where id_menage='".$id_menage."' and nom='".$nom."' and prenom='".$prenom."'"
					." and id_fokontany=".$id_fokontany." and id_acteur=".$id_acteur;
		$query = $this->db->query($requete);
        return $query->result();				
    }
	// Comptage bénéficiaire par identifiant_appariement et id_acteur
    public function RechercheParIdentifiantActeur($table,$identifiant_appariement,$id_acteur) {
		$requete= "select count(*) as nombre from ".$table." where identifiant_appariement='".$identifiant_appariement."' and id_acteur=".$id_acteur;
		$query = $this->db->query($requete);
        return $query->result();				
    }
	// Recherche menage par id_appariement et par acteur
    public function RechercheFokontanyMenageParIdentifiantActeur($identifiant_appariement,$id_acteur) {
		$requete= "select m.id as id_menage,m.id_fokontany,f.code as code_fokontany,c.code as code_commune,d.code as code_district,r.code as code_region,m.identifiant_unique"
		." from menage as m "
		." left outer join fokontany as f on f.id=m.id_fokontany"
		." left outer join commune as c on c.id=f.id_commune"
		." left outer join district as d on d.id=c.district_id"
		." left outer join region as r on r.id=d.region_id"
		." where m.identifiant_appariement='".$identifiant_appariement."' and m.id_acteur=".$id_acteur;
		$query = $this->db->query($requete);
        return $query->result();				
    }
	// Recherche individu par id_appariement et par acteur
    public function RechercheFokontanyIndividuParIdentifiantActeur($identifiant_appariement,$id_acteur) {
		$requete= "select i.id as id_individu,i.id_fokontany,f.code as code_fokontany,c.code as code_commune,d.code as code_district,r.code as code_region,i.identifiant_unique"
		." from individu as i "
		." left outer join fokontany as f on f.id=i.id_fokontany"
		." left outer join commune as c on c.id=f.id_commune"
		." left outer join district as d on d.id=c.district_id"
		." left outer join region as r on r.id=d.region_id"
		." where i.identifiant_appariement='".$identifiant_appariement."' and i.id_acteur=".$id_acteur;			
		$query = $this->db->query($requete);
        return $query->result();				
    }
	// Recherche fokontant,code fokontant (découpage administratif) d'un individu
    public function RechercheFokontanyIndividuParMenageNomPrenomActeur($id_menage,$nom,$prenom,$id_acteur) {
			$requete= "select i.id as id_individu,i.id_menage,i.id_fokontany,f.code as code_fokontany,c.code as code_commune,d.code as code_district,r.code as code_region,i.identifiant_unique"
			." from individu as i "
			." left outer join fokontany as f on f.id=i.id_fokontany"
			." left outer join commune as c on c.id=f.id_commune"
			." left outer join district as d on d.id=c.district_id"
			." left outer join region as r on r.id=d.region_id"
			." where i.id_menage=".$id_menage." and nom='".$nom."' and i.prenom='".$prenom."' and i.id_acteur=".$id_acteur;
		$query = $this->db->query($requete);
        return $query->result();				
    }
	// Recherche id_fokontany , code fokontany d'un ménage  ...(découpage administratif) suivant les critères en paramètres (LIRE)
	public function RechercheMenageParNomPrenomCIN_Fokontany_Acteur($identifiant_appariement,$id_acteur,$nom,$prenom,$cin,$id_fokontany) {
			$requete= "select m.id as id_menage,m.id_fokontany,f.code as code_fokontany,c.code as code_commune,d.code as code_district,r.code as code_region,m.identifiant_unique"
			." from menage as m "
			." left outer join fokontany as f on f.id=m.id_fokontany"
			." left outer join commune as c on c.id=f.id_commune"
			." left outer join district as d on d.id=c.district_id"
			." left outer join region as r on r.id=d.region_id"
			." where m.identifiant_appariement='".$identifiant_appariement."' and m.id_acteur=".$id_acteur
			." and m.nom='".$nom."' and m.prenom='".$prenom."' and m.cin='".$cin."' and m.id_fokontany=".$id_fokontany;
			$query = $this->db->query($requete);
			return $query->result();				
	}
	// Recherche id_fokontany , code fokontany d'un individu apparenté à un ménage ...(découpage administratif) suivant les critères en paramètres (LIRE)
	public function RechercheIndividuMenageParNomPrenomCIN_Fokontany_Acteur($identifiant_appariement,$id_acteur,$nom,$prenom,$cin,$id_fokontany,$id_menage) {
			$requete= "select i.id as id_individu,i.id_menage,i.id_fokontany,f.code as code_fokontany,c.code as code_commune,d.code as code_district,r.code as code_region,i.identifiant_unique"
			." from individu as i "
			." left outer join fokontany as f on f.id=i.id_fokontany"
			." left outer join commune as c on c.id=f.id_commune"
			." left outer join district as d on d.id=c.district_id"
			." left outer join region as r on r.id=d.region_id"
			." where i.identifiant_appariement='".$identifiant_appariement."' and i.id_acteur=".$id_acteur	
			." and i.nom='".$nom."' and i.prenom='".$prenom."' and i.cin='".$cin."' and i.id_fokontany=".$id_fokontany
			.(intval($id_menage) > 0 ? " and i.id_menage=".$id_menage : "");
			$query = $this->db->query($requete);
			return $query->result();				
	}
	// Recherche id_fokontany , code fokontany d'un individu  ...(découpage administratif) suivant les critères en paramètres (LIRE)
	public function RechercheIndividuParNomPrenomCIN_Fokontany_Acteur($identifiant_appariement,$id_acteur,$nom,$prenom,$cin,$id_fokontany) {
			$requete= "select i.id as id_menage,i.id_fokontany,f.code as code_fokontany,c.code as code_commune,d.code as code_district,r.code as code_region,i.identifiant_unique"
			." from individu as i "
			." left outer join fokontany as f on f.id=i.id_fokontany"
			." left outer join commune as c on c.id=f.id_commune"
			." left outer join district as d on d.id=c.district_id"
			." left outer join region as r on r.id=d.region_id"
			." where i.identifiant_appariement='".$identifiant_appariement."' and i.id_acteur=".$id_acteur		
			." and i.nom='".$nom."' and i.prenom='".$prenom."' and i.cin='".$cin."' and i.id_fokontany=".$id_fokontany;
			$query = $this->db->query($requete);
			return $query->result();				
	}
	// Recherche id_fokontany,code fokontany ....(découpage administratif)
	public function RechercheFokontanyParNomPrenomCIN_Fokontany_Acteur($parametre_table,$identifiant_appariement,$id_acteur,$nom,$prenom,$cin,$id_fokontany,$id_menage) {
		if($parametre_table=="individu") {
			// Individu tout court : sans considération clé étrangère id_menage
			$requete= "select i.id_fokontany,f.code as code_fokontany,c.code as code_commune,d.code as code_district,r.code as code_region,i.identifiant_unique"
			." from individu as i "
			." left outer join fokontany as f on f.id=i.id_fokontany"
			." left outer join commune as c on c.id=f.id_commune"
			." left outer join district as d on d.id=c.district_id"
			." left outer join region as r on r.id=d.region_id"
			." where i.identifiant_appariement='".$identifiant_appariement."' and i.id_acteur=".$id_acteur
			." and i.nom='".$nom."' and i.prenom='".$prenom."' and i.cin='".$cin."' and i.id_fokontany=".$id_fokontany
			.(intval($id_menage) > 0 ? " and i.id_menage=".$id_menage : "");
			$query = $this->db->query($requete);
			return $query->result();				
		} else if($parametre_table=="menage") {
			// Chef ménage
			$requete= "select m.id_fokontany,f.code as code_fokontany,c.code as code_commune,d.code as code_district,r.code as code_region,m.identifiant_unique"
			." from menage as m "
			." left outer join fokontany as f on f.id=m.id_fokontany"
			." left outer join commune as c on c.id=f.id_commune"
			." left outer join district as d on d.id=c.district_id"
			." left outer join region as r on r.id=d.region_id"
			." where i.identifiant_appariement='".$identifiant_appariement."' and i.id_acteur=".$id_acteur
			." and i.nom='".$nom."' and i.prenom='".$prenom."' and i.cin='".$cin."' and i.id_fokontany=".$id_fokontany;
			$query = $this->db->query($requete);
			return $query->result();				
		} else if($parametre_table=="individu_menage") {
			// Individu appartenant à un ménage
			$requete= "select i.id_fokontany,f.code as code_fokontany,c.code as code_commune,d.code as code_district,r.code as code_region,i.identifiant_unique"
			." from individu as i "
			." left outer join fokontany as f on f.id=i.id_fokontany"
			." left outer join commune as c on c.id=f.id_commune"
			." left outer join district as d on d.id=c.district_id"
			." left outer join region as r on r.id=d.region_id"
			."where i.identifiant_appariement='".$identifiant_appariement."' and i.id_acteur=".$id_acteur
			." and i.nom='".$nom."' and i.prenom='".$prenom."' and i.cin='".$cin."' and i.id_fokontany=".$id_fokontany;
			$query = $this->db->query($requete);
			return $query->result();				
		}
	}
	// Fonction qui controle si un ménage ou individu bénéficie déjà de l'intervention
	public function ControlerSiBeneficiaireIntervention($table,$id_menage,$id_intervention) {
		$requete="select count(*) as nombre from ".$table." where id_intervention=".$id_intervention
		.($table=="menage_beneficiaire" ? " and id_menage=" : " and id_individu=").$id_menage; 
		$query = $this->db->query($requete);
		return $query->result();				
	}
	// Récupération nombre de fichier bénéficiaire non validés ou importés
	public function recuperer_nombre_liste_fichier_non_valides_beneficiaire() {
		$requete="select count(*) as nombre_beneficiaire_non_valides from liste_validation_beneficiaire where date_validation IS NULL";
		$query = $this->db->query($requete);
		return $query->result();				
	}
	// Récupération nombre de fichier intervention non validés ou importés
	public function recuperer_nombre_liste_fichier_non_valides_intervention() {
		$requete="select count(*) as nombre_intervention_non_valides from liste_validation_intervention where date_validation IS NULL";
		$query = $this->db->query($requete);
		return $query->result();				
	}
}
