<?php

defined('BASEPATH') OR exit('No direct script access allowed');
// require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/PHPMailer/PHPMailerAutoload.php';

class Importationbeneficiaire extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('importationbeneficiaire_model', 'ImportationbeneficiaireManager');
        $this->load->model('validationbeneficiaire_model', 'ValidationbeneficiaireManager');
        $this->load->model('listevalidationbeneficiaire_model', 'ListevalidationbeneficiaireManager');
        $this->load->model('region_model', 'RegionManager');
        $this->load->model('district_model', 'DistrictManager');
        $this->load->model('commune_model', 'CommuneManager');
        $this->load->model('fokontany_model', 'FokontanyManager');        
        $this->load->model('acteur_model', 'ActeurManager');        
        $this->load->model('intervention_model', 'InterventionManager');        
        $this->load->model('menage_model', 'MenageManager');        
        $this->load->model('menage_beneficiaire_model', 'MenagebeneficiaireManager');        
        $this->load->model('individu_model', 'IndividuManager');        
        $this->load->model('individu_beneficiaire_model', 'IndividubeneficiaireManager');        
        $this->load->model('variable_intervention_model', 'VariableinterventionManager');
        $this->load->model('utilisateurs_model', 'UtilisateursManager');        
    }
	// Fonction qui récupère le fichier envoyé par l'acteur pour l'neregistrer dans le repertoire dédié dans le serveur
	// Structure repertoire donneesimportees/beneficiaire/'nom_acteur'/'nom de fichier.sxlsx'
	public function upload_importationdonneesbeneficiaire() {	
		$erreur="aucun";
		$replace=array('e','e','e','a','o','c','_','_','_');
		$search= array('é','è','ê','à','ö','ç',' ','&','°');
		$repertoire= $_POST['repertoire'];
		$raison_sociale= $_POST['raison_sociale'];
		$repertoire=str_replace($search,$replace,$repertoire);
		$raison_sociale=str_replace($search,$replace,$raison_sociale);
		$raison_sociale=strtolower($raison_sociale);
		//The name of the directory that we need to create.
		$directoryName = dirname(__FILE__) ."/../../../../" .$repertoire.$raison_sociale;
		//Check if the directory already exists.
		if(!is_dir($directoryName)){
			//Directory does not exist, so lets create it.
			mkdir($directoryName, 0777,true);
		}				

		$emplacement=array();
		$emplacement[0]=dirname(__FILE__) ."/../../../../" .$repertoire.$raison_sociale.'/';
		$config['upload_path']          = dirname(__FILE__) ."/../../../../".$repertoire.$raison_sociale.'/';
		$config['allowed_types'] = 'gif|jpg|png|xls|xlsx|doc|docx|pdf|txt';
		$config['max_size'] = 222048;
		$config['overwrite'] = TRUE;
		$retour =$emplacement;
		if (isset($_FILES['file']['tmp_name'])) {
			$name=$_FILES['file']['name'];
			$name1=str_replace($search,$replace,$name);
			$emplacement[1]=$name1;
			$emplacement[2]=$repertoire.$raison_sociale.'/';
			$config['file_name'] = $name1;
			$this->load->library('upload', $config);
			$this->upload->initialize($config);
			$ff=$this->upload->do_upload('file');
			// Contrôler les données envoyés par l'acteur
			$retour = $this->controler_donnees_beneficiaire($emplacement[1],$emplacement[2]);
			$valeur_retour=array();
			$valeur_retour["nom_fichier"] = $emplacement[1];
			$valeur_retour["repertoire"] = $emplacement[2];
			$valeur_retour["reponse"] = $retour["reponse"];
			$valeur_retour["nombre_erreur"] = $retour["nombre_erreur"];
		} else {
			$valeur_retour=array();
			$valeur_retour["nom_fichier"] = "inexistant";
			$valeur_retour["repertoire"] = "introuvable";
			$valeur_retour["reponse"] = "ERREUR";
			$valeur_retour["nombre_erreur"] = 9999999;
			echo json_encode($valeur_retour);
            // echo 'File upload not found';
		} 
		echo json_encode($valeur_retour);
	}  
	// Récupération nombre fichier non importés : bénéficiaire et intervention en même temps : pour affichage SUR le MENU
	public function recuperer_nombre_liste_fichier_non_importes() {	
		$total_non_validees =0;
		$retour_1 = $this->ImportationbeneficiaireManager->recuperer_nombre_liste_fichier_non_importes_beneficiaire();
		$retour_2 = $this->ImportationbeneficiaireManager->recuperer_nombre_liste_fichier_non_importes_intervention();
		foreach($retour_1 as $k=>$v) {
			$total_non_validees = $total_non_validees + $v->nombre_beneficiaire_non_importes;
		}
		foreach($retour_2 as $k=>$v) {
			$total_non_validees = $total_non_validees + $v->nombre_intervention_non_importes;
		}
		echo json_encode($total_non_validees);
	}
	// Récupération nombre fichier non importés bénéficiaire  : pour affichage SUR le MENU
	public function recuperer_nombre_liste_beneficiaire_non_importes() {	
		$total_non_validees =0;
		$retour_1 = $this->ImportationbeneficiaireManager->recuperer_nombre_liste_fichier_non_importes_beneficiaire();
		foreach($retour_1 as $k=>$v) {
			$total_non_validees = $total_non_validees + $v->nombre_beneficiaire_non_importes;
		}
		echo json_encode($total_non_validees);
	}
	// Récupération nombre fichier non importés intervention  : pour affichage SUR le MENU
	public function recuperer_nombre_liste_intervention_non_importes() {	
		$total_non_validees =0;
		$retour_1 = $this->ImportationbeneficiaireManager->recuperer_nombre_liste_fichier_non_importes_intervention();
		foreach($retour_1 as $k=>$v) {
			$total_non_validees = $total_non_validees + $v->nombre_intervention_non_importes;
		}
		echo json_encode($total_non_validees);
	}
	public function importer_donnees_beneficiaire() {	
		require_once 'Classes/PHPExcel.php';
		require_once 'Classes/PHPExcel/IOFactory.php';
		set_time_limit(0);
		$repertoire= $_POST['repertoire'];
		$nomfichier= $_POST['nom_fichier'];
		$id_utilisateur= $_POST['id_utilisateur'];
		$id_liste_validation_beneficiaire= $_POST['id_liste_validation_beneficiaire'];
		//The name of the directory that we need to create.
		$chemin=dirname(__FILE__) . "/../../../../".$repertoire;
		$lien_vers_mon_document_excel = $chemin . $nomfichier;
		$array_data = array();
		if(strpos($lien_vers_mon_document_excel,"xlsx") >0) {
			$objet_read_write = PHPExcel_IOFactory::createReader('Excel2007');
			$excel = $objet_read_write->load($lien_vers_mon_document_excel);			 
			$sheet = $excel->getSheet(0);
			$XLSXDocument = new PHPExcel_Reader_Excel2007();
		} else {
			$objet_read_write = PHPExcel_IOFactory::createReader('Excel2007');
			$excel = $objet_read_write->load($lien_vers_mon_document_excel);			 
			$sheet = $excel->getSheet(0);
			$XLSXDocument = new PHPExcel_Reader_Excel5();
		}
		$Excel = $XLSXDocument->load($lien_vers_mon_document_excel);
		// get all the row of my file
		$rowIterator = $Excel->getActiveSheet()->getRowIterator();
		$premier=0; 
		$id_menage=null; // clé primaire ménage
		$nombre_erreur=0;
		$remplacer=array('&eacute;','e','e','a','o','c','_');
		$trouver= array('é','è','ê','à','ö','ç',' ');
		$id_fokontany = null;
		$search=array("'");
		$replace= array("’");		
		foreach($rowIterator as $row) {
			$ligne = $row->getRowIndex ();
			if($ligne >=2) {
				if($ligne ==2) {
					// Contrôle partenaire / intitulé intervention / Date 
					 $cellIterator = $row->getCellIterator();
					 $cellIterator->setIterateOnlyExistingCells(false);
					 $rowIndex = $row->getRowIndex ();
					foreach ($cellIterator as $cell) {
						if('B' == $cell->getColumn()) {
							$nom_partenaire =$cell->getValue();
						} else if('D' == $cell->getColumn()) {
							$intitule_intervention =$cell->getValue();	
						} else if('F' == $cell->getColumn()) {
							$date_enquete = $cell->getValue();
							if(isset($date_enquete) && $date_enquete>"") {
								if(PHPExcel_Shared_Date::isDateTime($cell)) {
									 $date_enquete = date($format='Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($date_enquete)); 
									 $date_inscription =$date_enquete;
								}
							} else {
								$date_enquete=null;
								$date_inscription =null;
							}	
						} else if('H' == $cell->getColumn()) {
							$menage_ou_individu = $cell->getValue();
							$menage_ou_groupe = $cell->getValue();
						}	 
					}
					// récupération id_acteur dans la BDD
					$nom_partenaire=strtolower($nom_partenaire);
					$retour = $this->ActeurManager->findByNom($nom_partenaire);
					if(count($retour) >0) {
						// $id_acteur : à utiliser ultérieurement si tout est OK pour Deuxième vérification
						foreach($retour as $k=>$v) {
							$id_acteur = $v->id;
						}	
					} else {
						$id_acteur=null;
					}
					// récupération id_intervention  dans la BDD
					$trouve= array("é","è","ê","à","ö","ç","'","ô"," ");
					$remplace=array("e","e","e","a","o","c","","o","");
					$intitule_intervention=str_replace($trouve,$remplace,$intitule_intervention);
					$retour = $this->InterventionManager->findByIntitule($intitule_intervention);
					if(count($retour) >0) {
						foreach($retour as $k=>$v) {
							$id_intervention = $v->id;
						}	
					} else {
						$id_intervention=null;
					}					
					// A utliser ultérieurement si tout est OK pour la deuxième vérification doublon :
					// c'est-à-dire : recherche dans la table menage ou table individu
					$menage_ou_individu = strtolower($menage_ou_individu);
					// $menage_ou_individu = substr($menage_ou_individu,3);
					$menage_ou_groupe = strtolower($menage_ou_groupe);	
					// $menage_ou_groupe = substr($menage_ou_groupe,3);
					$etat_groupe =0;					
					if($menage_ou_groupe=="groupe") {
						$etat_groupe =1;
					}
					if($menage_ou_groupe=="ménage" || $menage_ou_groupe=="menage" || $menage_ou_groupe=="groupe") {
						$menage_ou_individu="menage";
					} else {
						$menage_ou_individu="individu";
					}
				}	
				if($ligne ==3) {
					// Contrôle découpage administratif
					 $cellIterator = $row->getCellIterator();
					 // Loop all cells, even if it is not set
					 $cellIterator->setIterateOnlyExistingCells(false);
					 $rowIndex = $row->getRowIndex ();
					 $a_inserer =0;
					foreach ($cellIterator as $cell) {
						if('B' == $cell->getColumn()) {
							$nom_region = $cell->getValue();
							$nom_region_original = $cell->getValue();
						 } else if('D' == $cell->getColumn()) {
							$nom_district = $cell->getValue();
							$nom_district_original = $cell->getValue();
						 } else if('F' == $cell->getColumn()) {
								$nom_commune =$cell->getValue();	
								$nom_commune_original =$cell->getValue();	
						 }	else if('H' == $cell->getColumn()) {
								$nom_fokontany =$cell->getValue();
								$nom_fokontany_original =$cell->getValue();
						 }	else if('I' == $cell->getColumn()) {
								$fokontany_id =$cell->getValue();
						 }
					}
					// Controle region,district,commune : si tout est ok =>
					$amoron_mania=false;
					$nom_fokontany = strtolower($nom_fokontany);
					$nom_commune = strtolower($nom_commune);
					$nom_district = strtolower($nom_district);
					$nom_region = strtolower($nom_region);
					$x= strpos($nom_region,'mania');
					if($x > 0) {
						$amoron_mania=true;
					} else {
						$amoron_mania=false;
					}
					$nom_fokontany=str_replace($trouver,$remplacer,$nom_fokontany);
					$nom_commune=str_replace($trouver,$remplacer,$nom_commune);
					$nom_district=str_replace($trouver,$remplacer,$nom_district);
					$nom_region=str_replace($trouver,$remplacer,$nom_region);
					$region_ok = false;
					$district_ok = false;
					$commune_ok = false;
					$insert_commune=false;
					$insert_district=false;
					$insert_region=false;
					$id_region=null;
					$id_district=null;
					$id_commune=null;
					$code_fokontany = "";
					$code_commune='';
					$reg=array();
					$place_espace = strpos($nom_region," ");
					$place_apostrophe = strpos($nom_region,"'");
					/*---------affectation direct id_fokontany : pas de recherche carc'est déjà fait lors de la validation-------------*/
					$id_fokontany=$fokontany_id;
					$code_precedent="";
					$retour=$this->ImportationbeneficiaireManager->recuperer_code_region_district_commune_fokontany($id_fokontany);
					if($retour) {
						foreach($retour as $k=>$v) {
							$code_precedent=$v->code_precedent;
						}
					}
				}	
				if($ligne >=5) {
					// Contrôle de toutes les cellules à partir de la ligne 5
					// Contrôle partenaire / intitulé intervention / Date 
					 $cellIterator = $row->getCellIterator();
					 $cellIterator->setIterateOnlyExistingCells(false);
					 $rowIndex = $row->getRowIndex ();
					foreach ($cellIterator as $cell) {
						 if('B' == $cell->getColumn()) {
							$identifiant_appariement =$cell->getValue();
						 } else if('C' == $cell->getColumn()) {
							 $nom =$cell->getValue();
						 } else if('A' == $cell->getColumn()) {
							$numero_ordre =$cell->getValue();
						 } else if('C' == $cell->getColumn()) {
							$nom =$cell->getValue();	
						 } else if('D' == $cell->getColumn()) {
							$prenom = $cell->getValue();
						 } else if('E' == $cell->getColumn()) {
							$chef_menage = $cell->getValue();
						 } else if('F' == $cell->getColumn()) {
							$date_naissance = $cell->getValue();
							if(isset($date_naissance) && $date_naissance>"") {
								if(PHPExcel_Shared_Date::isDateTime($cell)) {
									 $date_naissance = date($format='Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($date_naissance)); 
								}
							} else {
								$date_naissance=null;
							}								 
						 } else if('G' == $cell->getColumn()) {
							 $age = $cell->getValue();
						 } else if('H' == $cell->getColumn()) {
							$sexe = $cell->getValue(); 
						 } else if('I' == $cell->getColumn()) {
							 $situation_matrimonale = $cell->getValue();
						 } else if('J' == $cell->getColumn()) {
							$cin = $cell->getValue(); 
						 } else if('K' == $cell->getColumn()) {
							$profession = $cell->getValue(); 
						 } else if('L' == $cell->getColumn()) {
							 $adresse = $cell->getValue();
						 } else if('M' == $cell->getColumn()) {
							$surnom = $cell->getValue(); 
						 } else if('N' == $cell->getColumn()) {
							$lien_de_parente = $cell->getValue(); 
						 } else if('O' == $cell->getColumn()) {
							 
						 } else if('P' == $cell->getColumn()) {
							$niveau_classe = $cell->getValue(); 
						 } else if('Q' == $cell->getColumn()) {
							$langue = $cell->getValue(); 
						 } else if('R' == $cell->getColumn()) {
							$revenu = $cell->getValue(); 
						 } else if('S' == $cell->getColumn()) {
							$depense = $cell->getValue(); 
						 } else if('T' == $cell->getColumn()) {
							$date_inscription_detail_beneficiaire = $cell->getValue();
							if(isset($date_inscription_detail_beneficiaire) && $date_inscription_detail_beneficiaire>"") {
								if(PHPExcel_Shared_Date::isDateTime($cell)) {
									 $date_inscription_detail_beneficiaire = date($format='Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($date_inscription_detail_beneficiaire)); 
								}
							} else {
								$date_inscription_detail_beneficiaire=null;
							}								 							 
						 } else if('U' == $cell->getColumn()) {
							$telephone = $cell->getValue();  
						 } else if('V' == $cell->getColumn()) {
							$handicap_visuel = $cell->getValue();  
						 } else if('W' == $cell->getColumn()) {
							$handicap_auditif = $cell->getValue();  
						 } else if('X' == $cell->getColumn()) {
							$handicap_parole = $cell->getValue();  
						 } else if('Y' == $cell->getColumn()) {
							$handicap_moteur = $cell->getValue();  
						 } else if('Z' == $cell->getColumn()) {
							$handicap_mental = $cell->getValue();  
						 } else if('AA' == $cell->getColumn()) {
							$nom_enqueteur = $cell->getValue();  
						 } else if('AB' == $cell->getColumn()) {
							$date_enquete_detail = $cell->getValue();
							if(isset($date_enquete_detail) && $date_enquete_detail>"") {
								if(PHPExcel_Shared_Date::isDateTime($cell)) {
									 $date_enquete_detail = date($format='Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($date_enquete_detail)); 
								}
							} else {
								$date_enquete_detail=null;
							}								 							 
						 } else if('AC' == $cell->getColumn()) {
							$indice_vulnerabilite = $cell->getValue();  
						 }
					}
					// Début Formatage des données 
					$nom=str_replace($search,$replace,$nom);
					$prenom=str_replace($search,$replace,$prenom);					
					$id_situation_matrimonale=null;
					if($situation_matrimonale >"" && $situation_matrimonale!="-") {
						$retour=$this->ImportationbeneficiaireManager->recuperer_id_situation_matrimoniale($situation_matrimonale);
						if($retour) {
							foreach($retour as $k=>$v) {
								$id_situation_matrimonale=$v->id_situation_matrimoniale;
							}
						}
					}
					if($cin =="" || $cin=="-") {
						$cin=null;
					}
					if($profession =="" || $profession=="-") {
						$profession=null;
					}
					if($adresse =="" || $adresse=="-") {
						$adresse=null;
					}
					if($surnom =="" || $surnom=="-") {
						$surnom=null;
					}
					$id_niveau_de_classe=null;
					if($niveau_classe >"" && $niveau_classe!="-") {
						$retour=$this->ImportationbeneficiaireManager->recuperer_id_niveau_de_classe($niveau_classe);
						if($retour) {
							foreach($retour as $k=>$v) {
								$id_niveau_de_classe=$v->id_niveau_de_classe;
							}
						}
					}
					if($langue =="" || $langue=="-") {
						$langue=null;
					}
					if(intval($revenu) ==0) {
						$revenu=null;
					}
					if(intval($depense) ==0) {
						$depense=null;
					}
					if(intval($telephone) ==0) {
						$telephone=null;
					}
					if($handicap_visuel =="" || $handicap_visuel=="-") {
						$handicap_visuel="non";
					}
					if($handicap_auditif =="" || $handicap_auditif=="-") {
						$handicap_auditif="non";
					}
					if($handicap_parole =="" || $handicap_parole=="-") {
						$handicap_parole="non";
					}
					if($handicap_moteur =="" || $handicap_moteur=="-") {
						$handicap_moteur="non";
					}
					if($handicap_mental =="" || $handicap_mental=="-") {
						$handicap_mental="non";
					}
					if($nom_enqueteur =="" || $nom_enqueteur=="-") {
						$nom_enqueteur=null;
					}
					$id_indice_vulnerabilite=null;
					$indice_vulnerabilite=strtolower($indice_vulnerabilite);					
					if($indice_vulnerabilite >"") {
						$retour=$this->ImportationbeneficiaireManager->recuperer_id_indice_vulnerabilite($indice_vulnerabilite);
						if($retour) {
							foreach($retour as $k=>$v) {
								$id_indice_vulnerabilite=$v->id_indice_vulnerabilite;
							}
						}
					}
					// Fin Formatage des données
					/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
					// Si bénéficiaire déjà existant das la table menage ou individu 
					// Alors insertion seulement dans la table menage_beneficiaire ou individu_beneficiaire avec id_intervention
					// Sinon DEUX insertion : dans la table (menage_beneficiaire ou individu_beneficiaire avec id_intervention )
					// ET dans la table (menage ou individu) selon le cas du fichier envoyé : menage ou individu (voir ci-bas)
					$beneficiaire_existant=false;
					$menage_ou_individu = strtolower($menage_ou_individu);
					if($menage_ou_individu=="ménage") {
						$menage_ou_individu=="menage";
					}
					if($menage_ou_individu=="individu") {
						// Individu tout court
						$parametre_table="individu";
						$table ="individu";
					} else if(strtolower($chef_menage) =="o" && ($menage_ou_individu=="menage" || $menage_ou_individu=="groupe" )) {
						// Si chef ménage
						$parametre_table="menage";
						$table ="menage";
					} else {
						// Individu appartenant à un ménage ou un groupe
						$parametre_table="individu";
						$table ="individu";
					}
					if($menage_ou_individu=="menage" || $menage_ou_individu=="groupe") {
						if(strtolower($chef_menage) =="o") {
							// 1- Recherche par identifiant_appariement = $identifiant_appariement et $id_acteur stocké auparavant CHEF MENAGE
							$retour=$this->ValidationbeneficiaireManager->RechercheParIdentifiantActeur($table,$identifiant_appariement,$id_acteur);
							$nombre=0;
							foreach($retour as $k=>$v) {
								$nombre = $v->nombre;
							}
							// Ménage déjà existant => id_menage à stocker
							if($nombre >0) {								
								$retour=$this->ValidationbeneficiaireManager->RechercheFokontanyMenageParIdentifiantActeur($identifiant_appariement,$id_acteur);
								$code_region="????";
								$code_district="????";
								$code_commune="????";
								$code_fokontany="????";
								$id_menage=null;
								if($retour) {
									 foreach($retour as $k=>$v) {
										 $code_region=$v->code_region;
										 $code_district=$v->code_district;
										 $code_commune=$v->code_commune;
										 $code_fokontany=$v->code_fokontany;							 
										 $identifiant_unique=$v->identifiant_unique;
										$id_menage=$v->id_menage; 
									 }
									$sheet->setCellValue("AD".$ligne, $code_region."-".$code_district."-".$code_commune."-".$code_fokontany."-".$identifiant_unique);
									$beneficiaire_existant=true;
								}	 
							}
						} else {
							// 2- Recherche individu appartenant à un ménage
							if(intval($fokontany_id) >0) {
								$id_fokontany=$fokontany_id;
							}
							$retour=$this->ValidationbeneficiaireManager->RechercheFokontanyParNomPrenomCIN_Fokontany_Acteur($parametre_table,$identifiant_appariement,$id_acteur,$nom,$prenom,$cin,$id_fokontany,$id_menage);
							$nombre=0;
							if($retour) {
								$nombre = 1; // déja existant
							}							
						}	
					} else {
						// 3- Recherche individu sans attache ménage
						$retour=$this->ValidationbeneficiaireManager->RechercheParIdentifiantActeur($table,$identifiant_appariement,$id_acteur);
						$nombre=0;
						foreach($retour as $k=>$v) {
							$nombre = $v->nombre;
						}
					}	
					if($nombre >0) {
						if( $menage_ou_individu=="menage" || $menage_ou_individu=="groupe") {
							if(strtolower($chef_menage) =="o") {
								// Chef ménage
								$retour=$this->ValidationbeneficiaireManager->RechercheFokontanyMenageParIdentifiantActeur($identifiant_appariement,$id_acteur);
								$code_region="????";
								$code_district="????";
								$code_commune="????";
								$code_fokontany="????";
								$id_menage=null;
								if($retour) {
									 foreach($retour as $k=>$v) {
										 $code_region=$v->code_region;
										 $code_district=$v->code_district;
										 $code_commune=$v->code_commune;
										 $code_fokontany=$v->code_fokontany;							 
										 $identifiant_unique=$v->identifiant_unique;
										$id_menage=$v->id_menage; 
									 }
									$sheet->setCellValue("AD".$ligne, $code_region."-".$code_district."-".$code_commune."-".$code_fokontany."-".$identifiant_unique);
									$beneficiaire_existant=true;
								}	 
							} else {
								// Individu membre ménage
								$retour=$this->ValidationbeneficiaireManager->RechercheFokontanyIndividuParMenageNomPrenomActeur($id_menage,$nom,$prenom,$id_acteur);
								 $code_region="????";
								 $code_district="????";
								 $code_commune="????";
								 $code_fokontany="????";
								 $id_individu=null;
								if($retour) {
									 foreach($retour as $k=>$v) {
										$code_region=$v->code_region;
										$code_district=$v->code_district;
										$code_commune=$v->code_commune;
										$code_fokontany=$v->code_fokontany;							 
										$identifiant_unique=$v->identifiant_unique;
										$id_individu=$v->id_individu; 
									 }
									$sheet->setCellValue("AD".$ligne, $code_region."-".$code_district."-".$code_commune."-".$code_fokontany."-".$identifiant_unique);
									$beneficiaire_existant=true;
								}	 								
							}	
						} else {
							$retour=$this->ValidationbeneficiaireManager->RechercheFokontanyIndividuParIdentifiantActeur($identifiant_appariement,$id_acteur);
							$code_region="????";
							$code_district="????";
							$code_commune="????";
							$code_fokontany="????";
							$id_individu=null;
							if($retour) {
								 foreach($retour as $k=>$v) {
									$code_region=$v->code_region;
									$code_district=$v->code_district;
									$code_commune=$v->code_commune;
									$code_fokontany=$v->code_fokontany;							 
									$identifiant_unique=$v->identifiant_unique;
									$id_individu=$v->id_individu; 
								 }
								$sheet->setCellValue("AD".$ligne, $code_region."-".$code_district."-".$code_commune."-".$code_fokontany."-".$identifiant_unique);
								$beneficiaire_existant=true;
							}	 
						}	
					} else {
						// 2- Recherche par nom , prenom , CIN, id_fokontany , id_acteur
						// Recherche selon le cas : liste par ménage ou individu
						// De plus si la liste est ménage; il faut chercher dans la table menage si chef_menage = "O"
						// sinon recherche dans la table individu
						if($menage_ou_individu=="menage" || $menage_ou_individu=="groupe") {
							if(strtolower($chef_menage) =="o") {
								// 1- CHEF MENAGE
								if(intval($fokontany_id) >0) {
									$id_fokontany=$fokontany_id;
								}
								$retour=$this->ValidationbeneficiaireManager->RechercheMenageParNomPrenomCIN_Fokontany_Acteur($identifiant_appariement,$id_acteur,$nom,$prenom,$cin,$id_fokontany);
								$code_region="????";
								$code_district="????";
								$code_commune="????";
								$code_fokontany="????";
								$id_menage=null;
								if($retour) {
									 foreach($retour as $k=>$v) {
										$code_region=$v->code_region;
										$code_district=$v->code_district;
										$code_commune=$v->code_commune;
										$code_fokontany=$v->code_fokontany;							 
										$identifiant_unique=$v->identifiant_unique;
										$id_menage=$v->id_menage; 
									 }
									$sheet->setCellValue("AD".$ligne, $code_region."-".$code_district."-".$code_commune."-".$code_fokontany."-".$identifiant_unique);
									$beneficiaire_existant=true;
								}	 
							} else {
								// 2- Recherche individu appartenant à un ménage
								$retour=$this->ValidationbeneficiaireManager->RechercheIndividuMenageParNomPrenomCIN_Fokontany_Acteur($identifiant_appariement,$id_acteur,$nom,$prenom,$cin,$id_fokontany,$id_menage);
								$code_region="????";
								$code_district="????";
								$code_commune="????";
								$code_fokontany="????";
								$id_individu=null;
								
								if($retour) {
									 foreach($retour as $k=>$v) {
										$code_region=$v->code_region;
										$code_district=$v->code_district;
										$code_commune=$v->code_commune;
										$code_fokontany=$v->code_fokontany;							 
										$identifiant_unique=$v->identifiant_unique;
										$id_menage=$v->id_menage; 
										$id_individu=$v->id_individu; 
									 }
									$sheet->setCellValue("AD".$ligne, $code_region."-".$code_district."-".$code_commune."-".$code_fokontany."-".$identifiant_unique);
									$beneficiaire_existant=true;
								}	 
							}	
						} else {
							// 3- Recherche individu sans attache ménage
							$retour=$this->ValidationbeneficiaireManager->RechercheIndividuParNomPrenomCIN_Fokontany_Acteur($identifiant_appariement,$id_acteur,$nom,$prenom,$cin,$id_fokontany);
							$code_region="????";
							$code_district="????";
							$code_commune="????";
							$code_fokontany="????";
							$id_individu=null;
							if($retour) {
								 foreach($retour as $k=>$v) {
									$code_region=$v->code_region;
									$code_district=$v->code_district;
									$code_commune=$v->code_commune;
									$code_fokontany=$v->code_fokontany;							 
									$identifiant_unique=$v->identifiant_unique;
									$id_individu=$v->id_individu; 
								 }
								$sheet->setCellValue("AD".$ligne, $code_region."-".$code_district."-".$code_commune."-".$code_fokontany."-".$identifiant_unique);
								$beneficiaire_existant=true;
							}	 
						}							
					} 					
					/////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
					// La variable $id_menage est retenu mais son usage diffère selon le cas : elle peut être id_individu si 
					// le fichier envoyé concerne seulement des individus
					if(!$beneficiaire_existant) {	
						// Veut dire : pas encore bénéficiaire et il faut l'insérer dans la table menage ou indidividu
						// Insértion Chef ménage	
						if($menage_ou_individu=="menage" || $menage_ou_individu=="groupe") {
							if(strtolower($chef_menage)=='o') {
								// Attribution identifiant unique
								$retour = $this->ImportationbeneficiaireManager->AttributionIdentifiantUniqueMenage();
								foreach($retour as $k=>$v) {
									$valeur=$v->nombre;
								}
								// identifiant_unique : 8 caractères
								if(strlen($valeur)==1) {
									$identifiant_unique ="0000000".$valeur;
								} else if(strlen($valeur)==2) {
									$identifiant_unique ="000000".$valeur;							
								} else if(strlen($valeur)==3) {
									$identifiant_unique ="00000".$valeur;							
								} else if(strlen($valeur)==4) {
									$identifiant_unique ="0000".$valeur;							
								} else if(strlen($valeur)==5) {
									$identifiant_unique ="000".$valeur;							
								} else if(strlen($valeur)==6) {
									$identifiant_unique ="00".$valeur;							
								} else if(strlen($valeur)==7) {
									$identifiant_unique ="0".$valeur;							
								} else {
									$identifiant_unique =$valeur;
								}	 
								$data = array(
									'identifiant_unique'     => $identifiant_unique,
									'identifiant_appariement'=> $identifiant_appariement,
									'numero_sequentiel'      => null,
									'lieu_residence'         => null,
									'surnom_chefmenage'      => $surnom,
									'nom'                    => $nom,
									'prenom'                 => $prenom,
									'cin'                    => $cin,
									'chef_menage'            => 'O',
									'adresse'                => $adresse,
									'date_naissance'         => $date_naissance,
									'profession'             => $profession,
									'id_situation_matrimoniale' => $id_situation_matrimonale,
									'sexe'                   => $sexe,
									'date_inscription'       => $date_inscription_detail_beneficiaire,
									'nom_prenom_pere'         => null,
									'nom_prenom_mere'         => null,
									'telephone'               => $telephone,
									'statut'                  => null,
									'date_sortie'            => null,
									'nom_enqueteur'            => $nom_enqueteur,
									'date_enquete'            => $date_enquete_detail,
									'nom_superviseur_enquete' => null,
									'date_supervision' => null,
									'flag_integration_donnees' => 1,
									'nouvelle_integration' => true,
									'commentaire' => null,
									'revenu_mensuel'         => $revenu,
									'depense_mensuel'        => $depense,
									'id_fokontany'           => $id_fokontany,
									'id_acteur'              => $id_acteur,
									'id_type_beneficiaire'   => 1,
									'etat_groupe'            => $etat_groupe,
									'decede'                    => 0,
									'date_deces'                => null,
									'id_indice_vulnerabilite'  => $id_indice_vulnerabilite,
								);
								$id_menage = $this->MenageManager->addchefmenage($data);
								$code_unique_chef_menage=$identifiant_unique;
								// $sheet->setCellValue("AD".$ligne, $code_precedent."-".$identifiant_unique);
								// Insértion chef ménage en tant qu'individu
								// Attribution identifiant unique
								$retour = $this->ImportationbeneficiaireManager->AttributionIdentifiantUniqueIndividu();
								foreach($retour as $k=>$v) {
									$valeur=$v->nombre;
								}
								// identifiant_unique : 8 caractères
								if(strlen($valeur)==1) {
									$identifiant_unique ="0000000".$valeur;
								} else if(strlen($valeur)==2) {
									$identifiant_unique ="000000".$valeur;							
								} else if(strlen($valeur)==3) {
									$identifiant_unique ="00000".$valeur;							
								} else if(strlen($valeur)==4) {
									$identifiant_unique ="0000".$valeur;							
								} else if(strlen($valeur)==5) {
									$identifiant_unique ="000".$valeur;							
								} else if(strlen($valeur)==6) {
									$identifiant_unique ="00".$valeur;							
								} else if(strlen($valeur)==7) {
									$identifiant_unique ="0".$valeur;							
								} else {
									$identifiant_unique =$valeur;
								}
								$code_unique_chef_menage=$code_unique_chef_menage." / ".$identifiant_unique;		
								$data= array(
									'id_menage'                => $id_menage,
									'identifiant_unique'       => $identifiant_unique,
									'identifiant_appariement'  => $identifiant_appariement,
									'date_enregistrement'      => null,
									'numero_ordre'             => $numero_ordre,
									'numero_ordre_pere'        => null,
									'numero_ordre_mere'        => null,
									'inscription_etatcivil'    => null,
									'numero_extrait_naissance' => null,
									'id_groupe_appartenance'   => null,
									'frequente_ecole'          => null,
									'avait_frequente_ecole'    => null,
									'nom_ecole'                => null,
									'occupation'                => null,
									'statut'                   => null,
									'date_sortie'              => null,
									'flag_integration_donnees' => 1,
									'nouvelle_integration'     => true,
									'commentaire'              => null,
									'possede_cin'              => null,
									'nom'                      => $nom,
									'prenom'                   => $prenom,
									'cin'                      => $cin,
									'date_naissance'           => $date_naissance,
									'sexe'                     => $sexe,
									'id_liendeparente'         => null,
									'id_handicap_visuel'       => null,
									'id_handicap_parole'       => null,
									'id_handicap_auditif'      => null,
									'id_handicap_mental'       => null,
									'id_handicap_moteur'       => null,
									'id_type_ecole'            => null,
									'id_niveau_de_classe'      => $id_niveau_de_classe,
									'langue'                   => $langue,
									'id_situation_matrimoniale' => $id_situation_matrimonale,
									'id_fokontany'              => $id_fokontany,
									'id_acteur'                 => $id_acteur,
									'decede'                    => 0,
									'date_deces'                => null,
									'chef_menage'               => "O",
									'handicap_visuel'           => $handicap_visuel,
									'handicap_parole'           => $handicap_parole,
									'handicap_auditif'          => $handicap_auditif,
									'handicap_moteur'           => $handicap_moteur,
									'handicap_mental'           => $handicap_mental,
									'id_indice_vulnerabilite'  => $id_indice_vulnerabilite,
								);
								$id_individu = $this->IndividuManager->add($data);
								$sheet->setCellValue("AD".$ligne, $code_precedent."-".$code_unique_chef_menage);																
							} else {
								// Insértion Individu rattaché à un ménage
								// Attribution identifiant unique
								$retour = $this->ImportationbeneficiaireManager->AttributionIdentifiantUniqueIndividu();
								foreach($retour as $k=>$v) {
									$valeur=$v->nombre;
								}
								// identifiant_unique : 8 caractères
								if(strlen($valeur)==1) {
									$identifiant_unique ="0000000".$valeur;
								} else if(strlen($valeur)==2) {
									$identifiant_unique ="000000".$valeur;							
								} else if(strlen($valeur)==3) {
									$identifiant_unique ="00000".$valeur;							
								} else if(strlen($valeur)==4) {
									$identifiant_unique ="0000".$valeur;							
								} else if(strlen($valeur)==5) {
									$identifiant_unique ="000".$valeur;							
								} else if(strlen($valeur)==6) {
									$identifiant_unique ="00".$valeur;							
								} else if(strlen($valeur)==7) {
									$identifiant_unique ="0".$valeur;							
								} else {
									$identifiant_unique =$valeur;
								}	 
								$data= array(
									'id_menage'                => $id_menage,
									'identifiant_unique'       => $identifiant_unique,
									'identifiant_appariement'  => $identifiant_appariement,
									'date_enregistrement'      => null,
									'numero_ordre'             => $numero_ordre,
									'numero_ordre_pere'        => null,
									'numero_ordre_mere'        => null,
									'inscription_etatcivil'    => null,
									'numero_extrait_naissance' => null,
									'id_groupe_appartenance'   => null,
									'frequente_ecole'          => null,
									'avait_frequente_ecole'    => null,
									'nom_ecole'                => null,
									'occupation'                => null,
									'statut'                   => null,
									'date_sortie'              => null,
									'flag_integration_donnees' => 1,
									'nouvelle_integration'     => true,
									'commentaire'              => null,
									'possede_cin'              => null,
									'nom'                      => $nom,
									'prenom'                   => $prenom,
									'cin'                      => $cin,
									'date_naissance'           => $date_naissance,
									'sexe'                     => $sexe,
									'id_liendeparente'         => null,
									'id_handicap_visuel'       => null,
									'id_handicap_parole'       => null,
									'id_handicap_auditif'      => null,
									'id_handicap_mental'       => null,
									'id_handicap_moteur'       => null,
									'id_type_ecole'            => null,
									'id_niveau_de_classe'      => $id_niveau_de_classe,
									'langue'                   => $langue,
									'id_situation_matrimoniale' => $id_situation_matrimonale,
									'id_fokontany'              => $id_fokontany,
									'id_acteur'                 => $id_acteur,
									'decede'                    => 0,
									'date_deces'                => null,
									'chef_menage'               => "N",
									'handicap_visuel'           => $handicap_visuel,
									'handicap_parole'           => $handicap_parole,
									'handicap_auditif'          => $handicap_auditif,
									'handicap_moteur'           => $handicap_moteur,
									'handicap_mental'           => $handicap_mental,
									'id_indice_vulnerabilite'  => $id_indice_vulnerabilite,
								);
								$id_individu = $this->IndividuManager->add($data);
								$sheet->setCellValue("AD".$ligne, $code_precedent."-".$identifiant_unique);								
							}	
						} else {
							// Insértion Individu tout court sans ménage apparenté
							// Attribution identifiant unique
							$retour = $this->ImportationbeneficiaireManager->AttributionIdentifiantUniqueIndividu();
							foreach($retour as $k=>$v) {
								$valeur=$v->nombre;
							}
							// identifiant_unique : 8 caractères
							if(strlen($valeur)==1) {
								$identifiant_unique ="0000000".$valeur;
							} else if(strlen($valeur)==2) {
								$identifiant_unique ="000000".$valeur;							
							} else if(strlen($valeur)==3) {
								$identifiant_unique ="00000".$valeur;							
							} else if(strlen($valeur)==4) {
								$identifiant_unique ="0000".$valeur;							
							} else if(strlen($valeur)==5) {
								$identifiant_unique ="000".$valeur;							
							} else if(strlen($valeur)==6) {
								$identifiant_unique ="00".$valeur;							
							} else if(strlen($valeur)==7) {
								$identifiant_unique ="0".$valeur;							
							} else {
								$identifiant_unique =$valeur;
							}	 
							$data= array(
								'id_menage'                => null,
								'identifiant_unique'       => $identifiant_unique,
								'identifiant_appariement'  => $identifiant_appariement,
								'date_enregistrement'      => null,
								'numero_ordre'             => $numero_ordre,
								'numero_ordre_pere'        => null,
								'numero_ordre_mere'        => null,
								'inscription_etatcivil'    => null,
								'numero_extrait_naissance' => null,
								'id_groupe_appartenance'   => null,
								'frequente_ecole'          => null,
								'avait_frequente_ecole'    => null,
								'nom_ecole'                => null,
								'occupation'                => $profession,
								'statut'                   => null,
								'date_sortie'              => null,
								'flag_integration_donnees' => 1,
								'nouvelle_integration'     => true,
								'commentaire'              => null,
								'possede_cin'              => null,
								'nom'                      => $nom,
								'prenom'                   => $prenom,
								'cin'                      => $cin,
								'date_naissance'           => $date_naissance,
								'sexe'                     => $sexe,
								'id_liendeparente'         => null,
								'id_handicap_visuel'       => null,
								'id_handicap_parole'       => null,
								'id_handicap_auditif'      => null,
								'id_handicap_mental'       => null,
								'id_handicap_moteur'       => null,
								'id_type_ecole'            => null,
								'id_niveau_de_classe'      => $id_niveau_de_classe,
								'langue'                   => $langue,
								'id_situation_matrimoniale' => $id_situation_matrimonale,
								'id_fokontany'              => $id_fokontany,
								'id_acteur'                 => $id_acteur,
								'decede'                    => 0,
								'date_deces'                => null,
								'chef_menage'               => "N",
								'handicap_visuel'           => $handicap_visuel,
								'handicap_parole'           => $handicap_parole,
								'handicap_auditif'          => $handicap_auditif,
								'handicap_moteur'           => $handicap_moteur,
								'handicap_mental'           => $handicap_mental,
								'id_indice_vulnerabilite'  => $id_indice_vulnerabilite,
							);
							$id_menage = $this->IndividuManager->add($data);
							$sheet->setCellValue("AD".$ligne, $code_precedent."-".$identifiant_unique);
						}
					}
					// TOUJOURS : Insertion dans la table menage_beneficiaire ou individu_beneficiaire
					// La valeur de la variable $id_menage est donnée par : le controle ci-dessus OU BIEN après insertion 
					// dans la table menage ou individu si la variable $beneficiaire_existant==false
						if($menage_ou_individu=="menage" || $menage_ou_individu=="groupe") {
							// Insérer dans la BDD le chef de ménage seulement (les membres sont ignorés)
							if(strtolower($chef_menage)=='o') {
								$data= array(
									'id_menage'       => $id_menage,
									'id_intervention' => $id_intervention,
									'date_sortie' => null,
									'date_inscription' => $date_inscription_detail_beneficiaire,
								);
								// Insertion dans la table menage_beneficiaire
								$id_menage_intervention = $this->MenagebeneficiaireManager->add($data);
							}	
						} else {
							$data= array(
								'id_individu'     => $id_menage,
								'id_intervention' => $id_intervention,
								'date_sortie' => null,
								'date_inscription' => $date_inscription_detail_beneficiaire,
							);
							// Insertion dans la table individu_beneficiaire
							$id_individu_intervention = $this->IndividubeneficiaireManager->add($data);
						}	
				}	
				$ligne = $ligne + 1;
			}		
		}
		$val_ret = array();
		// Fermer fichier Excel
		$sender = "ndrianaina.aime.bruno@gmail.com";
		$mdpsender = "finaritra";
		$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$objWriter->save(dirname(__FILE__) . "/../../../../" .$repertoire. $nomfichier);
		unset($excel);
		unset($objWriter);
		// Mise à jour liste validation bénéficiaire
		$date_validation = new DateTime; 
		$date_validation->add(new DateInterval('PT1H'));
		$date_validation =$date_validation->format('Y-m-d H:i:s');		
		$retour = $this->ImportationbeneficiaireManager->MiseAJourListeValidationBeneficiaire($id_liste_validation_beneficiaire,$date_validation,$id_utilisateur,$id_fokontany,$id_intervention);
		$date_inscription = new DateTime($date_inscription); 
		$date_inscription =$date_inscription->format('d/m/Y');				
		if($nombre_erreur > 0) {
			// Signaler les erreurs par mail
			$val_ret["reponse"] = "ERREUR";
			$val_ret["region"] = $nom_region_original;
			$val_ret["district"] = $nom_district_original;
			$val_ret["commune"] = $nom_commune_original;
			$val_ret["fokontany"] = $nom_fokontany_original;
			$val_ret["intervention"] = $intitule_intervention;
			$val_ret["date_inscription"] = $date_inscription;
			$val_ret["nombre_erreur"] = $nombre_erreur;
			// Fermer fichier Excel
		} else {
			$val_ret["reponse"] = "OK";
			$val_ret["region"] = $nom_region_original;
			$val_ret["district"] = $nom_district_original;
			$val_ret["commune"] = $nom_commune_original;
			$val_ret["fokontany"] = $nom_fokontany_original;
			$val_ret["intervention"] = $intitule_intervention;
			$val_ret["date_inscription"] = $date_inscription;
			$val_ret["nombre_erreur"] = 0;			
		}
		echo json_encode($val_ret);
	}	
	// Envoi email vers acteur pour signaler que les données sont intégrées dans la BDD
	public function envoyer_mail_integration_donnees() {
		$id_utilisateur=$_POST['id_utilisateur'];
		$adresse_mail=$_POST['adresse_mail'];
		$region=$_POST['region'];
		$district=$_POST['district'];
		$commune=$_POST['commune'];
		$fokontany=$_POST['fokontany'];
		$intervention=$_POST['intervention'];
		$date_inscription=$_POST['date_inscription'];
		// $date_inscription = new DateTime($date_inscription); 
		// $date_inscription =$date_inscription->format('d/m/Y');			
		$id_liste_validation_beneficiaire=$_POST['id_liste_validation_beneficiaire'];
		$retour=$this->ListevalidationbeneficiaireManager->findById($id_liste_validation_beneficiaire);
		$date_reception="";
		$id_utilisateur_proprietaire =null;
		$adresse_mail_proprietaire=null;
		$adresse_mail_proprietaire_hote=null;
		if($retour) {
			foreach($retour as $k=>$v) {
				$date_reception=$v->date_reception;
				$id_utilisateur_proprietaire =$v->id_utilisateur;
			}
			$date_reception = new DateTime($date_reception); 
			$date_reception =$date_reception->format('d/m/Y H:m:s');	
			$retour=$this->UtilisateursManager->findByIdtab($id_utilisateur_proprietaire);
			// Récupération adresse mail utlisateur qui avait envoyé le fichier auparavant avec en copie l'organisma hote
			if($retour) {
				foreach($retour as $k=>$v) {
					$adresse_mail_proprietaire=$v->email;
					$adresse_mail_proprietaire_hote=$v->email_hote;
				}	
			}		
		}
		// DEBUT ENVOI MAIL SIGNALANT QUE TOUT EST INTEGRE DANS LA BDD
		$data["type_fichier"] = " bénéficiaire intervention ".($retour !="" ? "(envoyé le ".$date_reception.")" : "");
		$data["region"] =$region;
		$data["district"] =$district;
		$data["commune"] =$commune;
		$data["fokontany"] =$fokontany;
		$data["intervention"] =$intervention;
		$data["date_inscription"] =$date_inscription;
		$sender = "ndrianaina.aime.bruno@gmail.com";
		$mdpsender = "finaritra";
		$sujet = "Intégration des données : fichier excel bénéficiaire";
		$corps = $this->load->view('mail/signaler_import_donnees.php', $data, true);
		$mail = new PHPMailer;
		$mail->isSMTP();
		$mail->Host = 'smtp.gmail.com';
		$mail->SMTPAuth = true;
		$mail->Username = $sender;
		$mail->Password = $mdpsender;
		$mail->From = "ndrianaina.aime.bruno@gmail.com"; // adresse mail de l’expéditeur
		$mail->FromName = "Ministère de la population Malagasy"; // nom de l’expéditeur	
		$mail->addReplyTo('ndrianaina.aime.bruno@gmail.com', 'Ministère de la population');
		$mail->SMTPSecure = 'tls';
		$mail->Port = 587;
		$mail->SMTPOptions = array(
			'ssl' => array(
				'verify_peer' => false,
				'verify_peer_name' => false,
				'allow_self_signed' => true
			)
		);
		$mail->setFrom($sender);
		$mail->addAddress($adresse_mail_proprietaire);
		$mail->AddCC($adresse_mail_proprietaire_hote);
		$mail->isHTML(true);
		$mail->Subject = $sujet;
		$mail->Body = $corps;
		if (!$mail->send()) {
			$data["reponse"] = 0;
			$data["email"] = $adresse_mail_proprietaire;
			$data["email_hote"] = $adresse_mail_proprietaire_hote;
		} else {
			$data["reponse"] = 1;
			$data["email"] = $adresse_mail_proprietaire;
			$data["email_hote"] = $adresse_mail_proprietaire_hote;
		}	
		echo json_encode($data);
		// FIN ENVOI MAIL SIGNALANT QUE TOUT EST OK
	}
} ?>	
