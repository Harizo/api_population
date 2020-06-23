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
					// Contrôle de toutes les cellules à partir de la ligne 5
					// Contrôle partenaire / intitulé intervention / Date 
					 $cellIterator = $row->getCellIterator();
					 $cellIterator->setIterateOnlyExistingCells(false);
					 $rowIndex = $row->getRowIndex ();
					foreach ($cellIterator as $cell) {
						if('A' == $cell->getColumn()) {
							$nom_region = $cell->getValue();
							$nom_region_original = $cell->getValue();
						 } else if('B' == $cell->getColumn()) {
							$nom_district = $cell->getValue();
							$nom_district_original = $cell->getValue();
						 } else if('C' == $cell->getColumn()) {
								$nom_commune =$cell->getValue();	
								$nom_commune_original =$cell->getValue();	
						 }	else if('D' == $cell->getColumn()) {
								$nom_fokontany =$cell->getValue();
								$nom_fokontany_original =$cell->getValue();
						} else if('G' == $cell->getColumn()) {
							$identifiant_appariement =$cell->getValue();
						 } else if('I' == $cell->getColumn()) {
							$nom =$cell->getValue();	
						 } else if('J' == $cell->getColumn()) {
							$prenom = $cell->getValue();
						 } else if('K' == $cell->getColumn()) {
							$chef_menage = $cell->getValue();
						 } else if('L' == $cell->getColumn()) {
							$date_naissance = $cell->getValue();
							$date_naissance_chaine = $cell->getValue();
							if(isset($date_naissance) && $date_naissance>"") {
								// $sheet->setCellValue("F".$ligne, PHPExcel_Shared_Date::PHPToExcel( '2014-10-16' ));
								if(PHPExcel_Shared_Date::isDateTime($cell)) {
									 $date_naissance = date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($date_naissance)); 
								}
							} else {
								$date_naissance=null;
							}								 
						 } else if('M' == $cell->getColumn()) {
							 $age = $cell->getValue();
						 } else if('N' == $cell->getColumn()) {
							$sexe = $cell->getValue(); 
						 } else if('O' == $cell->getColumn()) {
							 $situation_matrimonale = $cell->getValue();
						 } else if('P' == $cell->getColumn()) {
							$cin = $cell->getValue(); 
						 } else if('Q' == $cell->getColumn()) {
							$profession = $cell->getValue(); 
						 } else if('R' == $cell->getColumn()) {
							$surnom = $cell->getValue(); 
						 } else if('S' == $cell->getColumn()) {
							$lien_de_parente = $cell->getValue(); 
						 } else if('O' == $cell->getColumn()) {
							 
						 } else if('U' == $cell->getColumn()) {
							$niveau_classe = $cell->getValue(); 
						 } else if('V' == $cell->getColumn()) {
							$langue = $cell->getValue(); 
						 } else if('W' == $cell->getColumn()) {
							$revenu = $cell->getValue(); 
						 } else if('X' == $cell->getColumn()) {
							$depense = $cell->getValue(); 
						 } else if('Z' == $cell->getColumn()) {
							$telephone = $cell->getValue();  
						 } else if('AB' == $cell->getColumn()) {
							$handicap_visuel = $cell->getValue();  
						 } else if('AC' == $cell->getColumn()) {
							$handicap_auditif = $cell->getValue();  
						 } else if('AA' == $cell->getColumn()) {
							$handicap_moteur = $cell->getValue();  
						 } else if('AD' == $cell->getColumn()) {
							$handicap_mental = $cell->getValue();  
						 } else if('AE' == $cell->getColumn()) {
							$nom_enqueteur = $cell->getValue();  
						 } else if('AF' == $cell->getColumn()) {
							$date_enquete = $cell->getValue();
							if(isset($date_enquete) && $date_enquete>"") {
								if(PHPExcel_Shared_Date::isDateTime($cell)) {
									 $date_enquete = date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($date_enquete)); 
									 $date_enquete_detail = $date_enquete ;
								}
							} else {
								$date_enquete=null;
								$date_enquete_detail=null;
							}								 							 
						 } else if('AG' == $cell->getColumn()) {
							$indice_vulnerabilite = $cell->getValue();  
						 } else if('E' == $cell->getColumn()) {
							$nom_acteur =$cell->getValue();
						} else if('F' == $cell->getColumn()) {
							$intitule_intervention =$cell->getValue();	
							$intitule_intervention_original =$cell->getValue();	
						} else if('Y' == $cell->getColumn()) {
							$date_inscription = $cell->getValue();
							$date_envoi_fichier = $cell->getValue();
							$date_inscription_detail_chaine = $cell->getValue();
							if(isset($date_inscription) && $date_inscription>"") {
								if(PHPExcel_Shared_Date::isDateTime($cell)) {
									 $date_inscription = date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($date_inscription)); 
									 $date_inscription_beneficiaire = $date_inscription;
									 $date_envoi_fichier=$date_inscription;
									 $date_inscription_detail_beneficiaire=$date_inscription;
								}
							} else {
								$date_inscription=null;
								$date_inscription_beneficiaire =null;
								$date_envoi_fichier=null;
								$date_inscription_detail_beneficiaire=null;
							}	
						} else if('AI' == $cell->getColumn()) {
							$menage_ou_individu = $cell->getValue();
							$menage_ou_groupe = $cell->getValue();
						} else if('AJ' == $cell->getColumn()) {
							$fokontany_id = $cell->getValue();
						} 
						 // } else if('AA' == $cell->getColumn()) {
							// $handicap_parole = $cell->getValue();  
						 $handicap_parole='Non';
					}
					
					
					// récupération id_acteur dans la BDD
					$nom_acteur=strtolower($nom_acteur);
					$retour = $this->ActeurManager->findByNom($nom_acteur);
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
					} else if(strlen($cin) >12) {
						$cin=substr($cin,0,12);
					}
					if($profession =="" || $profession=="-") {
						$profession=null;
					}
					// if($adresse =="" || $adresse=="-") {
						// $adresse=null;
					// }
					if($surnom =="" || $surnom=="-") {
						$surnom=null;
					} else if(strlen($surnom) >=30) {
						$surnom=substr($surnom,0,28);
					}
					if($nom >'' && strlen($nom) >=80) {
						$nom=substr($nom,0,78);
					}	
					if($prenom >'' && strlen($prenom) >=80) {
						$prenom=substr($prenom,0,78);
					}						
					$id_liendeparente=null;
					if($lien_de_parente >"" && $lien_de_parente!="-") {
						$lien_de_parente=strtolower($lien_de_parente);
						$retour=$this->ImportationbeneficiaireManager->recuperer_id_liendeparente($lien_de_parente);
						if($retour) {
							foreach($retour as $k=>$v) {
								$id_liendeparente=$v->id_liendeparente;
							}
						}
					}
					$id_niveau_de_classe=null;
					if($niveau_classe >"" && $niveau_classe!="-") {
						$niveau_classe=strtolower($niveau_classe);
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
					if(strtolower($handicap_visuel) <>"oui") {
						$handicap_visuel="non";
					}
					if(strtolower($handicap_auditif)  <>"oui") {
						$handicap_auditif="non";
					}
					if(strtolower($handicap_parole)  <>"oui") {
						$handicap_parole="non";
					}
					if(strtolower($handicap_moteur)  <>"oui") {
						$handicap_moteur="non";
					}
					if(strtolower($handicap_mental)  <>"oui") {
						$handicap_mental="non";
					}
					if($nom_enqueteur =="" || $nom_enqueteur=="-") {
						$nom_enqueteur=null;
					}
					if($sexe=='Feminin') {
						$sexe ='F';
					} else {
						$sexe='H';
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
					if($menage_ou_individu=="ménage" || $menage_ou_individu=="menage") {
						$menage_ou_individu=="menage";
					}
					if($menage_ou_individu=="individu") {
						// Individu tout court
						$parametre_table="individu";
						$table ="individu";
					} else if(strtolower($chef_menage) =="oui" && ($menage_ou_individu=="menage" || $menage_ou_individu=="groupe" )) {
						// Si chef ménage
						$parametre_table="menage";
						$table ="menage";
					} else {
						// Individu appartenant à un ménage ou un groupe
						$parametre_table="individu";
						$table ="individu";
					}
					if($menage_ou_individu=="menage" || $menage_ou_individu=="groupe") {
						if(strtolower($chef_menage) =="oui") {
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
							if(strtolower($chef_menage) =="oui") {
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
							if(strtolower($chef_menage)=='oui') {
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
									'adresse'                => null,
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
									'numero_ordre'             => null,
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
									'id_liendeparente'         => 2,
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
									'numero_ordre'             => null,
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
									'id_liendeparente'         => $id_liendeparente,
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
								'numero_ordre'             => null,
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
							if(strtolower($chef_menage)=='oui') {
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
				
				$ligne = $ligne + 1;
			}		
		}
		$val_ret = array();
		// Fermer fichier Excel
		$sender = "registrebeneficiaire@gmail.com";
		$mdpsender = "Registre2020";
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
		$sender = "registrebeneficiaire@gmail.com";
		$mdpsender = "Registre2020";
		$sujet = "Intégration des données : fichier excel bénéficiaire";
		$corps = $this->load->view('mail/signaler_import_donnees.php', $data, true);
		$mail = new PHPMailer;
		$mail->isSMTP();
		$mail->Host = 'smtp.gmail.com';
		$mail->SMTPAuth = true;
		$mail->Username = $sender;
		$mail->Password = $mdpsender;
		$mail->From = "registrebeneficiaire@gmail.com"; // adresse mail de l’expéditeur
		$mail->FromName = "Ministère de la population Malagasy"; // nom de l’expéditeur	
		$mail->addReplyTo('registrebeneficiaire@gmail.com', 'Ministère de la population');
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
	public function importfid() {
// ini_set ('memory_limit', '2048M');		
		set_time_limit(0);
		$retour = $this->ImportationbeneficiaireManager->RecupererTableTemporaire();
		$nombre_erreur=0;
		$ajout_individu = 0;
		$ajout_individu_court = 0;
		$ajout_menage = 0;
		$nandalo=0;
		foreach($retour as $k=>$v) {
			// if($v->id >15000 && $v->id<=30000) {
					$id_temporaire=$v->id;
					$controle_nb_erreur_par_ligne=0;
							$nom_region = $v->region;
							$nom_region_original = $v->region;
							$nom_district = $v->district;
							$nom_district_original = $v->district;
							$nom_commune =$v->commune;	
							$nom_commune_original =$v->commune;	
							$nom_fokontany =$v->fokontany;
							$nom_fokontany_original =$v->fokontany;
							// $identifiant_appariement =$v->code_menage;
							$identifiant_appariement =$v->identifiant_unique;
							$identifiant_individu =$v->identifiant_unique;
							$nom =$v->nom;	
							$prenom = $v->prenom;
							$chef_menage = $v->chef_menage;
							$date_naissance = $v->date_naissance;
							$date_naissance_chaine = $v->date_naissance;
							 $age = $v->age;
							$sexe = $v->sexe; 
							 $situation_matrimonale = $v->situation_matrimoniale;
							$cin = $v->cin; 
							$profession = $v->profession; 
							 $adresse = null;
							$surnom = $v->surnom; 
							$lien_de_parente = $v->lien_de_parente; 
							$niveau_classe = $v->niveau_classe; 
							$langue = $v->langue; 
							$revenu = $v->revenu; 
							$depense = $v->depense; 
							$telephone = $v->telephone;  
							$handicap_visuel = $v->handicap_visuel;  
							$handicap_auditif = $v->handicap_auditif;  
							$handicap_moteur = $v->handicap_physique;  
							$handicap_mental = $v->handicap_mental;  
							$nom_enqueteur = $v->nom_enqueteur;  
							$date_enquete =$v->date_enquete;
							$date_enquete_detail =$v->date_enquete;
							$indice_vulnerabilite = $v->vulnerabilite;  
							$nom_acteur =$v->acteur;
							$intitule_intervention =$v->intervention;	
							$intitule_intervention_original =$v->intervention;	
							$date_inscription = $v->date_inscription;
							$date_envoi_fichier = $v->date_inscription;
							$date_inscription_detail_chaine = $v->date_inscription;
							$date_inscription_beneficiaire = $v->date_inscription;
							$date_inscription_detail_beneficiaire = $v->date_inscription;
							$menage_ou_individu = $v->menage_individu;
							$menage_ou_groupe = $v->menage_individu;
						 // } else if('AA' == $cell->getColumn()) {
							// $handicap_parole = $cell->getValue();  
						 $handicap_parole='Non';
					// Controle ligne par ligne	
					// Si donnée incorrect : coleur cellule en rouge
					$observation="";
					$etat_groupe =0;					
					if($menage_ou_groupe=="groupe") {
						$etat_groupe =1;
					}					
					if($nom_acteur=="") {
						$controle_nb_erreur_par_ligne = $controle_nb_erreur_par_ligne + 1;
						$nombre_erreur = $nombre_erreur + 1;
						$observation=$observation."Acteur;";
					} else {
						// Vérifier si nom_acteur existe dans la BDD
						$id_acteur = 9999999; // initialisation valeur id_acteur
						$nom_acteur=strtolower($nom_acteur);
						$retour = $this->ActeurManager->findByNom($nom_acteur);
						if(count($retour) >0) {
							// $id_acteur : à utiliser ultérieurement si tout est OK pour Deuxième vérification
							foreach($retour as $k=>$v) {
								$id_acteur = $v->id;
							}	
						} else {
							$nombre_erreur = $nombre_erreur + 1; 
							$controle_nb_erreur_par_ligne=$controle_nb_erreur_par_ligne + 1;
							$observation=$observation."Acteur;";
						}
					} 
					if($intitule_intervention=="") {
						$nombre_erreur = $nombre_erreur + 1;
						$controle_nb_erreur_par_ligne=$controle_nb_erreur_par_ligne + 1;
						$observation=$observation."Intervention;";
					} else {
						// Vérifier si intitule_intervention existe dans la BDD
						$id_intervention = null;  // A utliser ultérieurement pour controle doublon bénéficiaire intervention
						$trouve= array("é","è","ê","à","ö","ç","'","ô"," ");
						$remplace=array("e","e","e","a","o","c","","o","");
						$intitule_intervention=str_replace($trouve,$remplace,$intitule_intervention);
						$retour = $this->InterventionManager->findByIntitule($intitule_intervention);
						if(!$retour) {
							$nombre_erreur = $nombre_erreur + 1; 
							$controle_nb_erreur_par_ligne=$controle_nb_erreur_par_ligne + 1;
							$observation=$observation."Intervention;";
						} else {
							foreach($retour as $k=>$v) {
								$id_intervention = $v->id;
							}
						}
					}
					if(!$date_inscription) {
						$nombre_erreur = $nombre_erreur + 1;
						$controle_nb_erreur_par_ligne=$controle_nb_erreur_par_ligne + 1;
						$observation=$observation."Date inscription;";	
					} 
					if($menage_ou_individu=="") {
						$nombre_erreur = $nombre_erreur + 1;	
						$controle_nb_erreur_par_ligne = $controle_nb_erreur_par_ligne + 1;	
						$observation=$observation."Fichier ménage ou individu;";
					} else {
						// A utliser ultérieurement si tout est OK pour la deuxième vérification doublon :
						// c'est-à-dire : recherche dans la table menage ou table individu
						$menage_ou_individu = strtolower($menage_ou_individu);
						// $menage_ou_individu = substr($menage_ou_individu,3);
						// $menage_ou_groupe = substr($menage_ou_groupe,3);						
						if($menage_ou_individu=="ménage" || $menage_ou_individu=="menage" || $menage_ou_individu=="groupe") {
							$menage_ou_individu="menage";
						} else {
							$menage_ou_individu="individu";
						}
					}
					// Controle region,district,commune : si tout est ok =>
					$amoron_mania=false;
					$fokontany_test = $nom_fokontany;
					$nom_fokontany = strtolower($nom_fokontany);
					$commune_test = $nom_commune;
					$nom_commune = strtolower($nom_commune);
					$district_test = $nom_district;
					$nom_district = strtolower($nom_district);
					$nom_region = strtolower($nom_region);
					$x= strpos($nom_region,'mania');
					if($x > 0) {
						$amoron_mania=true;
					} else {
						$amoron_mania=false;
					}
					$remplacer=array('&eacute;','e','e','a','o','c','_');
					$trouver= array('é','è','ê','à','ö','ç',' ');					
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
					$id_fokontany = null;
					$code_fokontany = "";
					$code_commune='';
					$reg=array();
					$place_espace = strpos($nom_region," ");
					$place_apostrophe = strpos($nom_region,"'");
					$observation="";
					
					// Début Formatage des données 
					$search=array("'");
					$replace= array("’");
					$nom=str_replace($search,$replace,$nom);
					$prenom=str_replace($search,$replace,$prenom);	
					if($nom >'' && strlen($nom) >=80) {
						$nom=substr($nom,0,78);
					}	
					if($prenom >'' && strlen($prenom) >=80) {
						$prenom=substr($prenom,0,78);
					}	
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
					} else if(strlen($cin) >12) {
						$cin=substr($cin,0,12);
					}
					if($profession =="" || $profession=="-") {
						$profession=null;
					}
					// if($adresse =="" || $adresse=="-") {
						// $adresse=null;
					// }
					if($surnom =="" || $surnom=="-") {
						$surnom=null;
					} else if(strlen($surnom) >=30) {
						$surnom=substr($surnom,0,28);
					}
					$id_liendeparente=null;
					if($lien_de_parente >"" && $lien_de_parente!="-") {
						if($lien_de_parente=='Chéf de ménage') {
							$id_liendeparente=null; 
						} else if($lien_de_parente=='Père ou mère du chef de ménage') {
							$id_liendeparente=2; 
						} else {	
							$lien_de_parente=strtolower($lien_de_parente);
							$retour=$this->ImportationbeneficiaireManager->recuperer_id_liendeparente($lien_de_parente);
							if($retour) {
								foreach($retour as $k=>$v) {
									$id_liendeparente=$v->id_liendeparente;
								}
							}
						}	
					}
					$id_niveau_de_classe=null;
					if($niveau_classe >"" && $niveau_classe!="-") {
						if(substr($id_niveau_de_classe,0,6)=='Exempt' || $id_niveau_de_classe=='Niveau zéro') {
							$id_niveau_de_classe=null;
						} else if($id_niveau_de_classe=='Prescolaire') {
							$id_niveau_de_classe=2;
						} else if($id_niveau_de_classe=='6eme') {
							$id_niveau_de_classe=8;
						} else if($id_niveau_de_classe=='5eme') {
							$id_niveau_de_classe=9;
						} else if($id_niveau_de_classe=='4eme') {
							$id_niveau_de_classe=10;
						} else if($id_niveau_de_classe=='3eme') {
							$id_niveau_de_classe=11;
						} else if($id_niveau_de_classe=='1ere') {
							$id_niveau_de_classe=13;
						} else {		
							$niveau_classe=strtolower($niveau_classe);
							$retour=$this->ImportationbeneficiaireManager->recuperer_id_niveau_de_classe($niveau_classe);
							if($retour) {
								foreach($retour as $k=>$v) {
									$id_niveau_de_classe=$v->id_niveau_de_classe;
								}
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
					if(strtolower($handicap_visuel) <>"oui") {
						$handicap_visuel="non";
					}
					if(strtolower($handicap_auditif)  <>"oui") {
						$handicap_auditif="non";
					}
					if(strtolower($handicap_parole)  <>"oui") {
						$handicap_parole="non";
					}
					if(strtolower($handicap_moteur)  <>"oui") {
						$handicap_moteur="non";
					}
					if(strtolower($handicap_mental)  <>"oui") {
						$handicap_mental="non";
					}
					if($nom_enqueteur =="" || $nom_enqueteur=="-") {
						$nom_enqueteur=null;
					}
					if($sexe=='Feminin') {
						$sexe ='F';
					} else {
						$sexe='H';
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
					
					
					if($nom_region >'') {
						if($amoron_mania==false) {
							if($place_espace >0) {
								$region_temporaire1 = substr ( $nom_region , 0 ,($place_espace - 1));
								$region_temporaire2 = substr ( $nom_region , ($place_espace + 1));
								$reg = $this->ValidationbeneficiaireManager->selectionregion_avec_espace($region_temporaire1,$region_temporaire2);
							} else if($place_apostrophe >0) {
								$region_temporaire1 = substr ( $nom_region , 0 ,($place_apostrophe - 1));
								$region_temporaire2 = substr ( $nom_region , ($place_apostrophe + 1));
							} else {	
								$reg = $this->ValidationbeneficiaireManager->selectionregion($nom_region);
							}	
						} else {
							$reg = $this->ValidationinterventionManager->selectionregionparid(6);
						}	
						if(count($reg) >0) {
							foreach($reg as $indice=>$v) {
								$id_region = $v->id;
								$code_region=$v->code;
							} 						
						} else {
							// Pas de région : marquer tous les découpages administratif 
							$nombre_erreur = $nombre_erreur + 1;
							$controle_nb_erreur_par_ligne = $controle_nb_erreur_par_ligne + 1;	
							$observation=$observation."Découp REGION;";
						}	
						if(intval($id_region) >0) {
							if($nom_district >'') {
								$region_ok = true;
								$place_espace = strpos($nom_district," ");
								$place_apostrophe = strpos($nom_district,"'"); 	
								if($district_test=="VATOMANDRY") {
									$dis = $this->ValidationbeneficiaireManager->selectiondistrictparid(49);
								} else if($district_test=="TOAMASINA II") {
									$dis = $this->ValidationbeneficiaireManager->selectiondistrictparid(52);
								} else if($district_test=="MAHANORO") {
									$dis = $this->ValidationbeneficiaireManager->selectiondistrictparid(50);
								} else if($place_espace >0) {
									$district_temporaire1 = substr ( $nom_district , 0 ,($place_espace - 1));
									$district_temporaire2 = substr ( $nom_district , ($place_espace + 1));
									$dis = $this->ValidationbeneficiaireManager->selectiondistrict_avec_espace($district_temporaire1,$district_temporaire2,$id_region);
								} else if($place_apostrophe >0) {
									$district_temporaire1 = substr ( $nom_district , 0 ,($place_apostrophe - 1));
									$district_temporaire2 = substr ( $nom_district , ($place_apostrophe + 1));
									$dis = $this->ValidationbeneficiaireManager->selectiondistrict_avec_espace($district_temporaire1,$district_temporaire2,$id_region);
								} else {
									$dis = $this->ValidationbeneficiaireManager->selectiondistrict($nom_district,$id_region);
								}	
								if(count($dis) >0) {
									foreach($dis as $indice=>$v) {
										$id_district = $v->id;
										$codedistrict= $v->code;
									}
								} else {
									// Pas de district : marquer district,commune,fokontany 
									$nombre_erreur = $nombre_erreur + 1;	
									$controle_nb_erreur_par_ligne = $controle_nb_erreur_par_ligne + 1;	
									$observation=$observation."Découp DISTRICT;";
								}
								if(intval($id_district) >0) {
									if($nom_commune >'') {
										$district_ok = true;
										$place_espace = strpos($nom_commune," ");
										$place_apostrophe = strpos($nom_commune,"'");
										if($commune_test=="MAHAVELONA FOULPOINTE") {
											$comm = $this->ValidationbeneficiaireManager->selectioncommuneparid(833);
										} else if($commune_test=="AMPASIMBE ONIBE") {
											$comm = $this->ValidationbeneficiaireManager->selectioncommuneparid(836);
										} else if($place_espace >0) {
											$commune_temporaire1 = substr ( $nom_commune , 0 ,($place_espace - 1));
											$commune_temporaire2 = substr ( $nom_commune , ($place_espace + 1));
											$comm = $this->ValidationbeneficiaireManager->selectioncommune_avec_espace($commune_temporaire1,$commune_temporaire2,$id_district);
										} else if($place_apostrophe >0) {
											$commune_temporaire1 = substr ( $nom_commune , 0 ,($place_apostrophe - 1));
											$commune_temporaire2 = substr ( $nom_commune , ($place_apostrophe + 1));
											$comm = $this->ValidationbeneficiaireManager->selectioncommune_avec_espace($commune_temporaire1,$commune_temporaire2,$id_district);
										} else {
											$comm = $this->ValidationbeneficiaireManager->selectioncommune($nom_commune,$id_district);
										}	
										if(count($comm) >0) {
											foreach($comm as $indice=>$v) {
												$id_commune = $v->id;
												$code_commune = $v->code;
											}
										} else {
											// Pas de commune : marquer commune,fokontany 
											$nombre_erreur = $nombre_erreur + 1;
											$controle_nb_erreur_par_ligne = $controle_nb_erreur_par_ligne + 1;	
											$observation=$observation."Découp COMMUNE;";
										}	
										if(intval($id_commune) >0) {
											if($nom_fokontany >'') {
												$place_espace = strpos($nom_fokontany," ");
												$place_apostrophe = strpos($nom_fokontany,"'");
												if($fokontany_test=='ATSINANAN I BEVOKA') {
													$fkt = $this->ValidationbeneficiaireManager->selectionfokontanyparid(2634);
												} else if($place_espace >0) {
													$fokontany_temporaire1 = substr ( $nom_fokontany , 0 ,($place_espace - 1));
													$fokontany_temporaire2 = substr ( $nom_fokontany , ($place_espace + 1));
													$fkt = $this->ValidationbeneficiaireManager->selectionfokontany_avec_espace($fokontany_temporaire1,$fokontany_temporaire2,$id_commune);
												} else if($place_apostrophe >0){
													$fokontany_temporaire1 = substr ( $nom_fokontany , 0 ,($place_apostrophe - 1));
													$fokontany_temporaire2 = substr ( $nom_fokontany , ($place_apostrophe + 1));
													$fkt = $this->ValidationbeneficiaireManager->selectionfokontany_avec_espace($fokontany_temporaire1,$fokontany_temporaire2,$id_commune);
												} else {
													$fkt = $this->ValidationbeneficiaireManager->selectionfokontany($nom_fokontany,$id_commune);
												}	
												if(count($fkt) >0) {
													foreach($fkt as $indice=>$v) {
														// A utliser ultérieurement lors de la deuxième vérification : id_fokontany
														$id_fokontany = $v->id;
														$code_fokontany = $v->code;
													}
												} else {													
													// Pas de fokontany : marquer fokontany 
													$nombre_erreur = $nombre_erreur + 1;
													$controle_nb_erreur_par_ligne = $controle_nb_erreur_par_ligne + 1;	
													$observation=$observation."Découp FOKONTANY;";
												}												
											} else {
												// Pas de fokontany : marquer fokontany 
												$nombre_erreur = $nombre_erreur + 1;
												$controle_nb_erreur_par_ligne = $controle_nb_erreur_par_ligne + 1;	
												$observation=$observation."Découp FOKONTANY;";
											}
										} 
									} else {										
										// Pas de commune : marquer commune,fokontany 
										$nombre_erreur = $nombre_erreur + 1;
										$controle_nb_erreur_par_ligne = $controle_nb_erreur_par_ligne + 1;	
										$observation=$observation."Découp COMMUNE;";
									}		
								}
							} else {
								// Pas de district : marquer district,commune,fokontany 
								$nombre_erreur = $nombre_erreur + 1;
								$controle_nb_erreur_par_ligne = $controle_nb_erreur_par_ligne + 1;	
								$observation=$observation."Découp DISTRICT;";
							}		
						}
					} else {
						// Pas de région : marquer tous les découpages administratif 
						$nombre_erreur = $nombre_erreur + 1;
						$controle_nb_erreur_par_ligne = $controle_nb_erreur_par_ligne + 1;	
						$observation=$observation."Découp REGION;";
					}
					// Controle ligne par ligne	
				
					if($identifiant_appariement=='') {
						$nombre_erreur = $nombre_erreur + 1;
						$controle_nb_erreur_par_ligne=$controle_nb_erreur_par_ligne + 1;	
						$observation=$observation."Identifiant appariement;";
					}
					if($nom=='') {
						$nombre_erreur = $nombre_erreur + 1;
						$controle_nb_erreur_par_ligne=$controle_nb_erreur_par_ligne + 1;	
						$observation=$observation."Nom;";
					}
					if($date_naissance_chaine=="0000-00-00" || $date_naissance_chaine=="00-00-0000" || $date_naissance_chaine=="00/00/0000") {
						$nombre_erreur = $nombre_erreur + 1;	
						$controle_nb_erreur_par_ligne=$controle_nb_erreur_par_ligne + 1;	
					}
					if(intval($age) >120 || intval($age) <0) {
						$nombre_erreur = $nombre_erreur + 1;
						$controle_nb_erreur_par_ligne=$controle_nb_erreur_par_ligne + 1;	
						$observation=$observation."Age;";
					}
					if($sexe=='') {
						$nombre_erreur = $nombre_erreur + 1;						
						$controle_nb_erreur_par_ligne=$controle_nb_erreur_par_ligne + 1;
						$observation=$observation."Sexe;";
					}
					if($date_inscription_detail_beneficiaire=='') {
						$nombre_erreur = $nombre_erreur + 1;						
						$controle_nb_erreur_par_ligne=$controle_nb_erreur_par_ligne + 1;
						$observation=$observation."Date inscription;";
					}
					if($indice_vulnerabilite>'') {
						$indice_vulnerabilite=strtolower($indice_vulnerabilite);
						$retour=$this->ValidationbeneficiaireManager->recuperer_id_indice_vulnerabilite($indice_vulnerabilite);
						if(!$retour) {
							$nombre_erreur = $nombre_erreur + 1; 
							$controle_nb_erreur_par_ligne=$controle_nb_erreur_par_ligne + 1;
							$observation=$observation."Indice de vulnerabilité;";
						}
					}	
					// DEUXIEME VERIFICATION
					$handicap_parole ='Non';
					$nom=str_replace($search,$replace,$nom);
					$prenom=str_replace($search,$replace,$prenom);					
					$beneficiaire_existant=false;
					$individu_existant=false;
					$beneficiaire_intervention=false;
					if($menage_ou_individu=="individu") {
						// Individu tout court
						$parametre_table="individu";
						$table ="individu";
						$table_controle ="individu_beneficiaire"; // Pour controler si un individu est déjà bénéficiaire de l'intervention
					} else if(strtolower($chef_menage) =="oui") {
						// Si chef ménage
						$parametre_table="menage";
						$table ="menage";
						$table_controle ="menage_beneficiaire"; // Pour controler si un ménage est déjà bénéficiaire de l'intervention
					} else {
						// Individu apprtenant à un ménage
						$parametre_table="individu";
						$table ="individu";
						$table_controle ="menage_beneficiaire"; // Pour controler si un individu est déjà bénéficiaire de l'intervention
					}					
					if($menage_ou_individu=="menage" || $menage_ou_individu=="groupe") {
						if(strtolower($chef_menage) =="oui") {
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
									// $sheet->setCellValue("AD".$ligne, $code_region."-".$code_district."-".$code_commune."-".$code_fokontany."-".$identifiant_unique);
									$beneficiaire_existant=true;
								}	 
							}
						} else {
							// 2- Recherche individu appartenant à un ménage
							$nombre=0;
							$retour=$this->ValidationbeneficiaireManager->RechercheIndividuParIdentifiantAppariementActeur($identifiant_appariement,$id_acteur);
							if($retour) {
								foreach($retour as $k=>$v) {
									$nombre = $v->nombre;
								}																							
							}	
							// if($id_fokontany >0 && $id_menage) {
								// $retour=$this->ValidationbeneficiaireManager->RechercheNombreIndividuFokontanyParNomPrenomCIN_Fokontany_Acteur($parametre_table,$identifiant_appariement,$id_acteur,$nom,$prenom,$cin,$id_fokontany,$id_menage);
								// foreach($retour as $k=>$v) {
									// $nombre = $v->nombre;
								// }															
							// }
						}	
					} else {
						// 3- Recherche individu sans attache ménage
						$id_menage=null;
						$retour=$this->ValidationbeneficiaireManager->RechercheParIdentifiantActeur($table,$identifiant_appariement,$id_acteur);
						$nombre=0;
						foreach($retour as $k=>$v) {
							$nombre = $v->nombre;
						}
					}	
					if($nombre >0) {
						if( $menage_ou_individu=="menage" || $menage_ou_individu=="groupe") {
							if(strtolower($chef_menage) =="oui") {
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
									// $sheet->setCellValue("AD".$ligne, $code_region."-".$code_district."-".$code_commune."-".$code_fokontany."-".$identifiant_unique);
									$beneficiaire_existant=true;
								}	 
								// Vérification si déjà bénéficiaire de l'intervention
								$nombre=0;
								$retour=$this->ValidationbeneficiaireManager->ControlerSiBeneficiaireIntervention($table_controle,$id_menage,$id_intervention);
								foreach($retour as $k=>$v) {
									 $nombre=$v->nombre;
								}
								// Bénéficiaire existant et bénéficie déjà de l'intervention =>ERREUR DOUBLON
								if($nombre >0) {
									// Doublon : ERREUR Marquage colonne AE par Doublon de couleur Jaune
									// MAJ FICHIER TEMPORAIRE : Observation ='Doublon';
									// $sheet->setCellValue("AE".$ligne, "Doublon : Déjà bénéficiaire de l'intervention");
									$nombre_erreur = $nombre_erreur + 1;	
									$controle_nb_erreur_par_ligne = $controle_nb_erreur_par_ligne + 1;	
									$observation=$observation."Bénéficiaire doublon;";
									$beneficiaire_intervention=true;
								} 						
							} else {
								// Individu membre ménage
								// $retour=$this->ValidationbeneficiaireManager->RechercheFokontanyIndividuParMenageNomPrenomActeur($id_menage,$nom,$prenom,$id_acteur);
								$retour=$this->ValidationbeneficiaireManager->RechercheIndividuParIdentifiantAppariementActeur($identifiant_appariement,$id_acteur);
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
									// $sheet->setCellValue("AD".$ligne, $code_region."-".$code_district."-".$code_commune."-".$code_fokontany."-".$identifiant_unique);
									$nombre_erreur = $nombre_erreur + 1;	
									$controle_nb_erreur_par_ligne = $controle_nb_erreur_par_ligne + 1;	
									$observation=$observation."Individu doublon;";
									$individu_existant=true;
								}	 								
							}	
						} else {
							// Bénéficiaire individu tout court
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
								// $sheet->setCellValue("AD".$ligne, $code_region."-".$code_district."-".$code_commune."-".$code_fokontany."-".$identifiant_unique);
								$beneficiaire_existant=true;
							}	 
							// Vérification si déjà bénéficiaire de l'intervention
							$nombre=0;
							$retour=$this->ValidationbeneficiaireManager->ControlerSiBeneficiaireIntervention($table_controle,$id_individu,$id_intervention);
							foreach($retour as $k=>$v) {
								 $nombre=$v->nombre;
							}
							// Bénéficiaire existant et bénéficie déjà de l'intervention =>ERREUR DOUBLON
							if($nombre >0) {
								// Doublon : ERREUR Marquage colonne AE par Doublon de couleur Jaune
								// MAJ FICHIER TEMPORAIRE : Observation ='Doublon';
								$nombre_erreur = $nombre_erreur + 1;	
								$controle_nb_erreur_par_ligne = $controle_nb_erreur_par_ligne + 1;	
								$observation=$observation."Bénéficiaire doublon;";
							}						
						}	
					} else {
						// 2- Recherche par nom , prenom , CIN, id_fokontany , id_acteur
						// Recherche selon le cas : liste par ménage ou individu
						// De plus si la liste est ménage; il faut chercher dans la table menage si chef_menage = "O"
						// sinon recherche dans la table individu
						if($menage_ou_individu=="menage" || $menage_ou_individu=="groupe") {
							if(strtolower($chef_menage) =="oui") {
								// 1- CHEF MENAGE
								$retour=null;
								if($id_fokontany) {
									$retour=$this->ValidationbeneficiaireManager->RechercheMenageParNomPrenomCIN_Fokontany_Acteur($identifiant_appariement,$id_acteur,$nom,$prenom,$cin,$id_fokontany);
								}	
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
									// $sheet->setCellValue("AD".$ligne, $code_region."-".$code_district."-".$code_commune."-".$code_fokontany."-".$identifiant_unique);
									$beneficiaire_existant=true;
									// Vérification si déjà bénéficiaire de l'intervention
									$nombre=0;
									$retour=$this->ValidationbeneficiaireManager->ControlerSiBeneficiaireIntervention($table_controle,$id_menage,$id_intervention);
									foreach($retour as $k=>$v) {
										 $nombre=$v->nombre;
									}
									// Bénéficiaire existant et bénéficie déjà de l'intervention =>ERREUR DOUBLON
									if($nombre >0) {
										// Doublon : ERREUR Marquage colonne AE par Doublon de couleur Jaune
										 // MAJ FICHIER TEMPORAIRE : Observation ='Doublon';
										// $sheet->setCellValue("AE".$ligne, "Doublon : Déjà bénéficiaire de l'intervention");
										$nombre_erreur = $nombre_erreur + 1;	
										$controle_nb_erreur_par_ligne = $controle_nb_erreur_par_ligne + 1;	
										$observation=$observation."Bénéficiaire doublon;";
										$beneficiaire_intervention=true;
									}						
								}	 
							} else {
								// 2- Recherche individu appartenant à un ménage
								$code_region="????";
								$code_district="????";
								$code_commune="????";
								$code_fokontany="????";
								$id_individu=null;
								if($id_fokontany>0) {
									$retour=null;
									if($id_menage) {
										// $retour=$this->ValidationbeneficiaireManager->RechercheIndividuMenageParNomPrenomCIN_Fokontany_Acteur($identifiant_appariement,$id_acteur,$nom,$prenom,$cin,$id_fokontany,$id_menage);								
										$retour=$this->ValidationbeneficiaireManager->RechercheIndividuParIdentifiantAppariementActeur($identifiant_appariement,$id_acteur);								
									}	
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
										// $sheet->setCellValue("AD".$ligne, $code_region."-".$code_district."-".$code_commune."-".$code_fokontany."-".$identifiant_unique);
										$nombre_erreur = $nombre_erreur + 1;	
										$controle_nb_erreur_par_ligne = $controle_nb_erreur_par_ligne + 1;	
										$observation=$observation."Individu doublon;";
										$individu_existant=true;
									}	
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
								// $sheet->setCellValue("AD".$ligne, $code_region."-".$code_district."-".$code_commune."-".$code_fokontany."-".$identifiant_unique);
								$beneficiaire_existant=true;
								// Vérification si déjà bénéficiaire de l'intervention
								$nombre=0;
								$retour=$this->ValidationbeneficiaireManager->ControlerSiBeneficiaireIntervention($table_controle,$id_individu,$id_intervention);
								foreach($retour as $k=>$v) {
									 $nombre=$v->nombre;
								}
								// Bénéficiaire existant et bénéficie déjà de l'intervention =>ERREUR DOUBLON
								if($nombre >0) {
									// Doublon : ERREUR Marquage colonne AE par Doublon de couleur Jaune
									// MAJ FICHIER TEMPORAIRE : Observation ='Doublon';
									// $sheet->setCellValue("AE".$ligne, "Doublon : Déjà bénéficiaire de l'intervention");
									$nombre_erreur = $nombre_erreur + 1;	
									$controle_nb_erreur_par_ligne = $controle_nb_erreur_par_ligne + 1;	
									$observation=$observation."Bénéficiaire doublon;";
									$beneficiaire_intervention=true;
								}						
							}	 
						}							
					} 					
					if(null==$date_naissance) {
						// Calcul date_naissance par défaut
						$date_actuelle  = new DateTime();
						$annee_actuelle= $date_actuelle->format("Y");
						$age=intval($age);
						$date_par_defaut = $annee_actuelle."-01-01";	
						$date_par_defaut = new DateTime($date_par_defaut);
						
						$now = date('Y-m-d');
						$date_naissance = date('d/m/Y', strtotime($now. ' -'.$age.' years +1 days'));
						
						// $date_naissance = $date_par_defaut->sub(DateInterval::createFromDateString("'".$age." year'"));
						// $sheet->setCellValue('F'.$ligne, $date_naissance);
						// $sheet->setCellValue('L'.$ligne, $date_naissance);
						// $sheet->setCellValue('I3', $id_fokontany);
						// MAJ FICHIER TEMPORAIRE : date_naissance;
					}
					// INSERTION DANS LES DIFFERENTES TABLE SI TOUT EST OK
					if($controle_nb_erreur_par_ligne==0) {						
						// La variable $id_menage est retenu mais son usage diffère selon le cas : elle peut être id_individu si 
						// le fichier envoyé concerne seulement des individus
							// Veut dire : pas encore bénéficiaire et il faut l'insérer dans la table menage ou indidividu
							// Insértion Chef ménage	
							if($menage_ou_individu=="menage" || $menage_ou_individu=="groupe") {
								if(strtolower($chef_menage)=='oui') {
									if($beneficiaire_existant==false) {	
										$identifiant_appariement=$identifiant_individu;										
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
											'adresse'                => null,
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
										$ajout_menage = $ajout_menage + 1;
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
											'numero_ordre'             => null,
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
											'id_liendeparente'         => 2,
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
										$retour=$this->ImportationbeneficiaireManager->MiseajourIdmenageIdIndividuTableTemporaire($id_temporaire,$id_menage,$id_individu);
										$ajout_individu = $ajout_individu + 1;
										// $sheet->setCellValue("AD".$ligne, $code_precedent."-".$code_unique_chef_menage);
									} else {
										$nombre_erreur = $nombre_erreur + 1;	
										$controle_nb_erreur_par_ligne = $controle_nb_erreur_par_ligne + 1;	
										$observation=$observation."(Bénéficiaire doublon);";										
									}	
								} else {
									if($individu_existant==false) {
										$identifiant_appariement=$identifiant_individu;
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
											'numero_ordre'             => null,
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
											'id_liendeparente'         => $id_liendeparente,
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
										if($id_menage) {
											$id_individu = $this->IndividuManager->add($data);
											$ajout_individu = $ajout_individu + 1;
											$retour=$this->ImportationbeneficiaireManager->MiseajourIdmenageIdIndividuTableTemporaire($id_temporaire,$id_menage,$id_individu);
										// $sheet->setCellValue("AD".$ligne, $code_precedent."-".$identifiant_unique);
										} else {
											$nombre_erreur = $nombre_erreur + 1;	
											$controle_nb_erreur_par_ligne = $controle_nb_erreur_par_ligne + 1;	
											$observation=$observation."(En attente correction Chef ménage);";										
										}
									} else {
										$nombre_erreur = $nombre_erreur + 1;	
										$controle_nb_erreur_par_ligne = $controle_nb_erreur_par_ligne + 1;	
										$observation=$observation."(Individu doublon);";	
										if($beneficiaire_existant==true && $beneficiaire_intervention==false) {
											$observation=$observation."(Déjà bénéficiaire d autre intervention : OK);";	
										}	
									}									
								}	
							} else {
								// Insértion Individu tout court sans ménage apparenté
								// Attribution identifiant unique
								$ajout_individu_court = $ajout_individu_court + 1;
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
								$identifiant_appariement=$identifiant_individu;								
								$data= array(
									'id_menage'                => null,
									'identifiant_unique'       => $identifiant_unique,
									'identifiant_appariement'  => $identifiant_appariement,
									'date_enregistrement'      => null,
									'numero_ordre'             => null,
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
								$id_individu = $this->IndividuManager->add($data);
								// $sheet->setCellValue("AD".$ligne, $code_precedent."-".$identifiant_unique);
							}
						// TOUJOURS : Insertion dans la table menage_beneficiaire ou individu_beneficiaire
						// La valeur de la variable $id_menage est donnée par : le controle ci-dessus OU BIEN après insertion 
						// dans la table menage ou individu si la variable $beneficiaire_existant==false
							if($menage_ou_individu=="menage" || $menage_ou_individu=="groupe") {
								if($beneficiaire_existant==false || ($beneficiaire_existant==true && $beneficiaire_intervention==false)) {
									// Insérer dans la BDD le chef de ménage seulement (les membres sont ignorés)
									if(strtolower($chef_menage)=='oui') {
										$data= array(
											'id_menage'       => $id_menage,
											'id_intervention' => $id_intervention,
											'date_sortie' => null,
											'date_inscription' => $date_inscription_detail_beneficiaire,
										);
										// Insertion dans la table menage_beneficiaire
										$id_menage_intervention = $this->MenagebeneficiaireManager->add($data);
										if($beneficiaire_existant==true && $beneficiaire_intervention==false) {
											$nombre_erreur = $nombre_erreur + 1;	
											$controle_nb_erreur_par_ligne = $controle_nb_erreur_par_ligne + 1;	
											$observation=$observation."(Déjà bénéficiaire d autre intervention : OK);";																																
										}
									}	
								} 	
							} else {
								if($beneficiaire_existant==false || ($beneficiaire_existant==true && $beneficiaire_intervention==false)) {
									$data= array(
										'id_individu'     => $id_individu,
										'id_intervention' => $id_intervention,
										'date_sortie' => null,
										'date_inscription' => $date_inscription_detail_beneficiaire,
									);
									// Insertion dans la table individu_beneficiaire
									$id_individu_intervention = $this->IndividubeneficiaireManager->add($data);
								}	
							}	
						if($controle_nb_erreur_par_ligne >0) {
							// MAJ FICHIER TEMPORAIRE							
							$retour=$this->ImportationbeneficiaireManager->MiseajourTableTemporaire($id_temporaire,$observation);							
						}							
					} else {
						// MAJ FICHIER TEMPORAIRE
						$retour=$this->ImportationbeneficiaireManager->MiseajourTableTemporaire($id_temporaire,$observation);
					}
					// INSERTION DANS LES DIFFERENTES TABLE SI TOUT EST OK										
					// DEUXIEME VERIFICATION
			// }		
		}		
		echo json_encode($data);
	}
} ?>	
