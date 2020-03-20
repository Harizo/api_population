<?php

defined('BASEPATH') OR exit('No direct script access allowed');
// require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/PHPMailer/PHPMailerAutoload.php';
require APPPATH . '/libraries/chiffreenlettre.php';
// require_once("/libraries/chiffreenlettre.php");
class Validationbeneficiaire extends CI_Controller {
    public function __construct() {
        parent::__construct();
		// Modèle utilisés lor de la validation des données
        $this->load->model('validationbeneficiaire_model', 'ValidationbeneficiaireManager');
        $this->load->model('region_model', 'RegionManager');
        $this->load->model('district_model', 'DistrictManager');
        $this->load->model('commune_model', 'CommuneManager');
        $this->load->model('fokontany_model', 'FokontanyManager');        
        $this->load->model('acteur_model', 'ActeurManager');        
        $this->load->model('intervention_model', 'InterventionManager');        
        $this->load->model('listevalidationbeneficiaire_model', 'ListevalidationbeneficiaireManager');
    }
	// Récupération nombre fichier non validées : bénéficiaire et intervention en même temps : pour affichage SUR le MENU
	public function recuperer_nombre_liste_fichier_non_valides() {	
		$total_non_validees =0;
		$retour_1 = $this->ValidationbeneficiaireManager->recuperer_nombre_liste_fichier_non_valides_beneficiaire();
		$retour_2 = $this->ValidationbeneficiaireManager->recuperer_nombre_liste_fichier_non_valides_intervention();
		foreach($retour_1 as $k=>$v) {
			$total_non_validees = $total_non_validees + $v->nombre_beneficiaire_non_valides;
		}
		foreach($retour_2 as $k=>$v) {
			$total_non_validees = $total_non_validees + $v->nombre_intervention_non_valides;
		}
		echo json_encode($total_non_validees);
	}
	// Récupération nombre fichier non validées bénéficiaire  : pour affichage SUR le MENU
	public function recuperer_nombre_liste_beneficiaire_non_valides() {	
		$total_non_validees =0;
		$retour_1 = $this->ValidationbeneficiaireManager->recuperer_nombre_liste_fichier_non_valides_beneficiaire();
		foreach($retour_1 as $k=>$v) {
			$total_non_validees = $total_non_validees + $v->nombre_beneficiaire_non_valides;
		}
		echo json_encode($total_non_validees);
	}
	// Récupération nombre fichier non validées intervention  : pour affichage SUR le MENU
	public function recuperer_nombre_liste_intervention_non_valides() {	
		$total_non_validees =0;
		$retour_1 = $this->ValidationbeneficiaireManager->recuperer_nombre_liste_fichier_non_valides_intervention();
		foreach($retour_1 as $k=>$v) {
			$total_non_validees = $total_non_validees + $v->nombre_intervention_non_valides;
		}
		echo json_encode($total_non_validees);
	}
	// Fonction qui récupère le fichier envoyé par l'acteur pour l'neregistrer dans le repertoire dédié dans le serveur
	// Structure repertoire validationdonnees/beneficiaire/'nom_acteur'/'nom de fichier.sxlsx'
	// APPEL DE LA FONCTION controler_donnees_beneficiaire  : pour controler les données envoyées
	public function upload_validationdonneesbeneficiaire() {	
		$erreur="aucun";
		$replace=array('e','e','e','a','o','c','_','_','_');
		$search= array('é','è','ê','à','ö','ç',' ','&','°');
		$repertoire= $_POST['repertoire'];
		$raison_sociale= $_POST['raison_sociale'];
		$repertoire=str_replace($search,$replace,$repertoire);
		$raison_sociale=str_replace($search,$replace,$raison_sociale);
		$raison_sociale=strtolower($raison_sociale);
		$adresse_mail=$_POST['adresse_mail'];
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
			// UNE FOIS LE FICHIER ENREGISTRE DANS LE SERVEUR => Controler les données
			// Contrôler les données envoyés par l'acteur
			$retour = $this->controler_donnees_beneficiaire($emplacement[1],$emplacement[2],$adresse_mail);
			$valeur_retour=array();
			$valeur_retour["nom_fichier"] = $emplacement[1];
			$valeur_retour["repertoire"] = $emplacement[2];
			$valeur_retour["reponse"] = $retour["reponse"];
			$valeur_retour["region"] = $retour["region"];
			$valeur_retour["district"] = $retour["district"];
			$valeur_retour["commune"] = $retour["commune"];
			$valeur_retour["fokontany"] = $retour["fokontany"];
			$valeur_retour["intervention"] = $retour["intervention"];
			$valeur_retour["date_inscription"] = $retour["date_inscription"];
			$valeur_retour["nombre_erreur"] = $retour["nombre_erreur"];
		} else {
			$valeur_retour=array();
			$valeur_retour["nom_fichier"] = "inexistant";
			$valeur_retour["repertoire"] = "introuvable";
			$valeur_retour["reponse"] = "ERREUR";
			$valeur_retour["region"] = "";
			$valeur_retour["district"] = "";
			$valeur_retour["commune"] = "";
			$valeur_retour["fokontany"] = "";
			$valeur_retour["intervention"] = "";
			$valeur_retour["date_inscription"] = "";
			$valeur_retour["nombre_erreur"] = "";
			$valeur_retour["nombre_erreur"] = 9999999;
			echo json_encode($valeur_retour);
            // echo 'File upload not found';
		} 
		echo json_encode($valeur_retour);
	}  
	public function controler_donnees_beneficiaire($filename,$directory,$adresse_mail) {	
		require_once 'Classes/PHPExcel.php';
		require_once 'Classes/PHPExcel/IOFactory.php';
		// La vérification se fait en DEUX étapes :
		// 1- Vérification de toutes les cellules : s'il n'y a aucune information
		// 2- Vérification doublon : recherche par id_acteur,,nom,prenom,id_fokontany,cin
		// Les colonnes sont toutes controlées : s'il n'y a pas de donnée ou donnée incorrect dans une cellule à contrôler :
		// Marquer en rouge l'information incorrect 
		// le nombre d'erreur est compté dans la variable $nombre_erreur et si $nombre_erreur > 0 : le fichier sera 
		// renvoyé vers l'expéditeur avec les cellules marquées en ROUGE afin qu'il puisse les corriger
		// Chaque cellule de toutes les lignes sont contrôlées mais s'il y a des commentaires concernant une cellule donnée : 
		// décommentez seulement les lignes concernées pour que le contrôle marche de nouveau
		// Exemple : le nom du bénéficiaire n'est pas obligatoire car il se peut qu'un individu ne possède pas de prénom
		//  alors si on aura de nouveau besoin de contrôler le prénom du bénéficiaire; il suffit de décommenter les lignes concernées
		// Notons que les variables $id_acteur , $id_fokontany seront utilisées ultérieurement pour la deuxième vérification
		set_time_limit(0);
		$replace=array('e','e','e','a','o','c','_');
		$search= array('é','è','ê','à','ö','ç',' ');
		$repertoire= $directory;
		$nomfichier = $filename;		
		$repertoire=str_replace($search,$replace,$repertoire);
		//The name of the directory that we need to create.
		$directoryName = dirname(__FILE__) ."/../../../../" .$repertoire;
		//Check if the directory already exists.
		if(!is_dir($directoryName)){
			//Directory does not exist, so lets create it.
			mkdir($directoryName, 0777,true);
		}	
		$chemin=dirname(__FILE__) . "/../../../../".$repertoire;
		$lien_vers_mon_document_excel = $chemin . $nomfichier;
		$array_data = array();
		if(strpos($lien_vers_mon_document_excel,"xlsx") >0) {
			// pour mise à jour après : G4 = id_fiche_paiement <=> déjà importé => à ignorer
			$objet_read_write = PHPExcel_IOFactory::createReader('Excel2007');
			$excel = $objet_read_write->load($lien_vers_mon_document_excel);			 
			$sheet = $excel->getSheet(0);
			// pour lecture début - fin seulement
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
		$remplacer=array("'");
		$trouver= array("’");
		$remplacer=array('&eacute;','e','e','a','o','c','_');
		$trouver= array('é','è','ê','à','ö','ç',' ');
		$nombre_erreur=0; // compter le nombre d'erreur afin de pouvoir renvoyer le fichier à l'envoyeur
		// Variable pour le nombre d'erreur par colonne
		$erreur_nom_acteur=0;
		$erreur_intitule_intervention=0;
		$erreur_date_envoi_fichier=0;
		$erreur_menage_ou_individu=0;
		$erreur_nom_region=0;
		$erreur_nom_district=0;
		$erreur_nom_commune=0;
		$erreur_nom_fokontany=0;
		$erreur_identifiant_appariement =0;
		$erreur_nom =0;
		$erreur_prenom =0;
		$erreur_date_naissance_age=0;
		$erreur_sexe=0;
		$erreur_situation_matrimonale=0;
		$erreur_cin=0;
		$erreur_profession=0;
		$erreur_adresse=0;
		$erreur_surnom=0;
		$erreur_lien_de_parente=0;
		$erreur_niveau_classe=0;
		$erreur_langue=0;
		$erreur_revenu=0;
		$erreur_depense=0;
		$erreur_date_inscription_detail_beneficiaire=0;
		$erreur_telephone=0;
		$erreur_handicap_visuel=0;
		$erreur_handicap_auditif=0;
		$erreur_handicap_parole=0;
		$erreur_handicap_moteur=0;
		$erreur_handicap_mental=0;
		$erreur_nom_enqueteur=0;
		$erreur_date_inscription=0;
		$erreur_indice_vulnerabilite=0;
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
							$nom_acteur =$cell->getValue();
						} else if('D' == $cell->getColumn()) {
							$intitule_intervention =$cell->getValue();	
							$intitule_intervention_original =$cell->getValue();	
						} else if('F' == $cell->getColumn()) {
							$date_inscription = $cell->getValue();
							$date_envoi_fichier = $cell->getValue();
							if(isset($date_inscription) && $date_inscription>"") {
								if(PHPExcel_Shared_Date::isDateTime($cell)) {
									 $date_inscription = date($format='Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($date_inscription)); 
									 $date_inscription_beneficiaire = $date_inscription;
									 $date_envoi_fichier=$date_inscription;
								}
							} else {
								$date_inscription=null;
								$date_inscription_beneficiaire =null;
								$date_envoi_fichier=null;
							}	
						} else if('H' == $cell->getColumn()) {
							$menage_ou_individu = $cell->getValue();
							$menage_ou_groupe = $cell->getValue();
						}	 
					}
					// Si donnée incorrect : coleur cellule en rouge
					if($nom_acteur=="") {
						$nombre_erreur = $nombre_erreur + 1;
						$erreur_nom_acteur=$erreur_nom_acteur +1;	
						$sheet->getStyle("B2")->getFill()->applyFromArray(
								 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
									 'startcolor' => array('rgb' => 'FF0000'),
									 'endcolor'   => array('argb' => 'FF0000')
								 )
						 );													
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
							$sheet->getStyle("B2")->getFill()->applyFromArray(
									 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
										 'startcolor' => array('rgb' => 'FF0000'),
										 'endcolor'   => array('argb' => 'FF0000')
									 )
							 );		
							$nombre_erreur = $nombre_erreur + 1; 
							$erreur_nom_acteur=$erreur_nom_acteur + 1;
						}
					} 
					if($intitule_intervention=="") {
						$nombre_erreur = $nombre_erreur + 1;
						$erreur_intitule_intervention=$erreur_intitule_intervention + 1;
						$sheet->getStyle("D2")->getFill()->applyFromArray(
								 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
									 'startcolor' => array('rgb' => 'FF0000'),
									 'endcolor'   => array('argb' => 'FF0000')
								 )
						 );													
					} else {
						// Vérifier si intitule_intervention existe dans la BDD
						$id_intervention = null;  // A utliser ultérieurement pour controle doublon bénéficiaire intervention
						$trouve= array("é","è","ê","à","ö","ç","'","ô"," ");
						$remplace=array("e","e","e","a","o","c","","o","");
						$intitule_intervention=str_replace($trouve,$remplace,$intitule_intervention);
						$retour = $this->InterventionManager->findByIntitule($intitule_intervention);
						if(!$retour) {
							$sheet->getStyle("D2")->getFill()->applyFromArray(
									 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
										 'startcolor' => array('rgb' => 'FF0000'),
										 'endcolor'   => array('argb' => 'FF0000')
									 )
							 );		
							$nombre_erreur = $nombre_erreur + 1; 
							$erreur_intitule_intervention=$erreur_intitule_intervention + 1;
						} else {
							foreach($retour as $k=>$v) {
								$id_intervention = $v->id;
							}
						}
					}
					if(!$date_inscription) {
						$nombre_erreur = $nombre_erreur + 1;
						$erreur_date_envoi_fichier=$erreur_date_envoi_fichier + 1;	
						$sheet->getStyle("F2")->getFill()->applyFromArray(
								 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
									 'startcolor' => array('rgb' => 'FF0000'),
									 'endcolor'   => array('argb' => 'FF0000')
								 )
						 );													
					} 
					if($menage_ou_individu=="") {
						$nombre_erreur = $nombre_erreur + 1;	
						$erreur_menage_ou_individu = $erreur_menage_ou_individu + 1;	
						$sheet->getStyle("H2")->getFill()->applyFromArray(
								 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
									 'startcolor' => array('rgb' => 'FF0000'),
									 'endcolor'   => array('argb' => 'FF0000')
								 )
						);													
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
					$id_fokontany = null;
					$code_fokontany = "";
					$code_commune='';
					$reg=array();
					$place_espace = strpos($nom_region," ");
					$place_apostrophe = strpos($nom_region,"'");
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
							$sheet->getStyle("B3")->getFill()->applyFromArray(
									 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
										 'startcolor' => array('rgb' => 'FF0000'),
										 'endcolor'   => array('argb' => 'FF0000')
									 )
							 );	
							$sheet->getStyle("D3")->getFill()->applyFromArray(
									 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
										 'startcolor' => array('rgb' => 'FF0000'),
										 'endcolor'   => array('argb' => 'FF0000')
									 )
							 );	
							$sheet->getStyle("F3")->getFill()->applyFromArray(
									 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
										 'startcolor' => array('rgb' => 'FF0000'),
										 'endcolor'   => array('argb' => 'FF0000')
									 )
							 );	
							$sheet->getStyle("H3")->getFill()->applyFromArray(
									 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
										 'startcolor' => array('rgb' => 'FF0000'),
										 'endcolor'   => array('argb' => 'FF0000')
									 )
							 );	
							$nombre_erreur = $nombre_erreur + 1;
							$erreur_nom_region = $erreur_nom_region + 1;	
						}	
						if(intval($id_region) >0) {
							if($nom_district >'') {
								$region_ok = true;
								$place_espace = strpos($nom_district," ");
								$place_apostrophe = strpos($nom_district,"'");
								if($place_espace >0) {
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
									$sheet->getStyle("D3")->getFill()->applyFromArray(
											 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
												 'startcolor' => array('rgb' => 'FF0000'),
												 'endcolor'   => array('argb' => 'FF0000')
											 )
									 );	
									$sheet->getStyle("F3")->getFill()->applyFromArray(
											 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
												 'startcolor' => array('rgb' => 'FF0000'),
												 'endcolor'   => array('argb' => 'FF0000')
											 )
									 );	
									$sheet->getStyle("H3")->getFill()->applyFromArray(
											 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
												 'startcolor' => array('rgb' => 'FF0000'),
												 'endcolor'   => array('argb' => 'FF0000')
											 )
									 );	
									$nombre_erreur = $nombre_erreur + 1;	
									$erreur_nom_district = $erreur_nom_district + 1;	
								}
								if(intval($id_district) >0) {
									if($nom_commune >'') {
										$district_ok = true;
										$place_espace = strpos($nom_commune," ");
										$place_apostrophe = strpos($nom_commune,"'");
										if($place_espace >0) {
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
											$sheet->getStyle("F3")->getFill()->applyFromArray(
													 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
														 'startcolor' => array('rgb' => 'FF0000'),
														 'endcolor'   => array('argb' => 'FF0000')
													 )
											 );	
											$sheet->getStyle("H3")->getFill()->applyFromArray(
													 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
														 'startcolor' => array('rgb' => 'FF0000'),
														 'endcolor'   => array('argb' => 'FF0000')
													 )
											 );	
											$nombre_erreur = $nombre_erreur + 1;
											$erreur_nom_commune = $erreur_nom_commune + 1;	
										}	
										if(intval($id_commune) >0) {
											if($nom_fokontany >'') {
												$place_espace = strpos($nom_fokontany," ");
												$place_apostrophe = strpos($nom_fokontany,"'");
												if($place_espace >0) {
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
													$sheet->setCellValue("I3", $id_fokontany);
												} else {													
													// Pas de fokontany : marquer fokontany 
													$sheet->getStyle("H3")->getFill()->applyFromArray(
															 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
																 'startcolor' => array('rgb' => 'FF0000'),
																 'endcolor'   => array('argb' => 'FF0000')
															 )
													 );	
													$nombre_erreur = $nombre_erreur + 1;
													$erreur_nom_fokontany = $erreur_nom_fokontany + 1;	
												}												
											} else {
												// Pas de fokontany : marquer fokontany 
												$sheet->getStyle("H3")->getFill()->applyFromArray(
														 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
															 'startcolor' => array('rgb' => 'FF0000'),
															 'endcolor'   => array('argb' => 'FF0000')
														 )
												 );	
												$nombre_erreur = $nombre_erreur + 1;
												$erreur_nom_fokontany = $erreur_nom_fokontany + 1;	
											}
										} 
									} else {										
										// Pas de commune : marquer commune,fokontany 
										$sheet->getStyle("F3")->getFill()->applyFromArray(
												 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
													 'startcolor' => array('rgb' => 'FF0000'),
													 'endcolor'   => array('argb' => 'FF0000')
												 )
										 );	
										$sheet->getStyle("H3")->getFill()->applyFromArray(
												 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
													 'startcolor' => array('rgb' => 'FF0000'),
													 'endcolor'   => array('argb' => 'FF0000')
												 )
										 );	
										$nombre_erreur = $nombre_erreur + 1;
										$erreur_nom_commune = $erreur_nom_commune + 1;	
									}		
								}
							} else {
								// Pas de district : marquer district,commune,fokontany 
								$sheet->getStyle("D3")->getFill()->applyFromArray(
										 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
											 'startcolor' => array('rgb' => 'FF0000'),
											 'endcolor'   => array('argb' => 'FF0000')
										 )
								 );	
								$sheet->getStyle("F3")->getFill()->applyFromArray(
										 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
											 'startcolor' => array('rgb' => 'FF0000'),
											 'endcolor'   => array('argb' => 'FF0000')
										 )
								 );	
								$sheet->getStyle("H3")->getFill()->applyFromArray(
										 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
											 'startcolor' => array('rgb' => 'FF0000'),
											 'endcolor'   => array('argb' => 'FF0000')
										 )
								 );	
								$nombre_erreur = $nombre_erreur + 1;
								$erreur_nom_district = $erreur_nom_district + 1;	
							}		
						}
					} else {
						// Pas de région : marquer tous les découpages administratif 
						$sheet->getStyle("B3")->getFill()->applyFromArray(
								 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
									 'startcolor' => array('rgb' => 'FF0000'),
									 'endcolor'   => array('argb' => 'FF0000')
								 )
						 );	
						$sheet->getStyle("D3")->getFill()->applyFromArray(
								 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
									 'startcolor' => array('rgb' => 'FF0000'),
									 'endcolor'   => array('argb' => 'FF0000')
								 )
						 );	
						$sheet->getStyle("F3")->getFill()->applyFromArray(
								 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
									 'startcolor' => array('rgb' => 'FF0000'),
									 'endcolor'   => array('argb' => 'FF0000')
								 )
						 );	
						$sheet->getStyle("H3")->getFill()->applyFromArray(
								 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
									 'startcolor' => array('rgb' => 'FF0000'),
									 'endcolor'   => array('argb' => 'FF0000')
								 )
						 );	
						$nombre_erreur = $nombre_erreur + 1;
						$erreur_nom_region = $erreur_nom_region + 1;	
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
						 } else if('D' == $cell->getColumn()) {
							$prenom = $cell->getValue();
						 } else if('E' == $cell->getColumn()) {
							$chef_menage = $cell->getValue();
						 } else if('F' == $cell->getColumn()) {
							$date_naissance = $cell->getValue();
							$date_naissance_chaine = $cell->getValue();
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
							$date_inscription_detail_chaine = $cell->getValue();
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
							$date_inscription = $cell->getValue();
							if(isset($date_inscription) && $date_inscription>"") {
								if(PHPExcel_Shared_Date::isDateTime($cell)) {
									 $date_inscription = date($format='Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($date_inscription)); 
								}
							} else {
								$date_inscription=null;
							}								 							 
						 } else if('AC' == $cell->getColumn()) {
							$indice_vulnerabilite = $cell->getValue();  
						 }
					}
					if($identifiant_appariement=='') {
						$sheet->getStyle("B".$ligne)->getFill()->applyFromArray(
								 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
									 'startcolor' => array('rgb' => 'FF0000'),
									 'endcolor'   => array('argb' => 'FF0000')
								 )
						 );	
						$nombre_erreur = $nombre_erreur + 1;
						$erreur_identifiant_appariement=$erreur_identifiant_appariement + 1;	
					}
					if($nom=='') {
						$sheet->getStyle("C".$ligne)->getFill()->applyFromArray(
								 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
									 'startcolor' => array('rgb' => 'FF0000'),
									 'endcolor'   => array('argb' => 'FF0000')
								 )
						 );	
						$nombre_erreur = $nombre_erreur + 1;
						$erreur_nom=$erreur_nom + 1;	
					}
				/*	if($prenom=='') {
						$sheet->getStyle("D".$ligne)->getFill()->applyFromArray(
								 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
									 'startcolor' => array('rgb' => 'FF0000'),
									 'endcolor'   => array('argb' => 'FF0000')
								 )
						 );	
						$nombre_erreur = $nombre_erreur + 1;
						$erreur_prenom=$erreur_prenom + 1;	
					} */
				/*	if($chef_menage=='') {
						$sheet->getStyle("E".$ligne)->getFill()->applyFromArray(
								 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
									 'startcolor' => array('rgb' => 'FF0000'),
									 'endcolor'   => array('argb' => 'FF0000')
								 )
						 );	
						$nombre_erreur = $nombre_erreur + 1;						
					} */
				/*	if($date_naissance==null && intval($age)==0) {
						$sheet->getStyle("F".$ligne)->getFill()->applyFromArray(
								 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
									 'startcolor' => array('rgb' => 'FF0000'),
									 'endcolor'   => array('argb' => 'FF0000')
								 )
						 );	
						$sheet->getStyle("G".$ligne)->getFill()->applyFromArray(
								 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
									 'startcolor' => array('rgb' => 'FF0000'),
									 'endcolor'   => array('argb' => 'FF0000')
								 )
						 );	
						$nombre_erreur = $nombre_erreur + 1;	
						$erreur_date_naissance_age=$erreur_date_naissance_age + 1;
					} else if(intval($age)>0) {
						// Calcul par défaut date naissance au 01/01/AAAA
						
					} */
					if($date_naissance_chaine=="0000-00-00" || $date_naissance_chaine=="00-00-0000" || $date_naissance_chaine=="00/00/0000") {
						$sheet->getStyle("F".$ligne)->getFill()->applyFromArray(
								 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
									 'startcolor' => array('rgb' => 'FF0000'),
									 'endcolor'   => array('argb' => 'FF0000')
								 )
						 );	
						$nombre_erreur = $nombre_erreur + 1;	
						$erreur_date_naissance_age=$erreur_date_naissance_age + 1;	
					}
					if(intval($age) >120) {
						$sheet->getStyle("G".$ligne)->getFill()->applyFromArray(
								 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
									 'startcolor' => array('rgb' => 'FF0000'),
									 'endcolor'   => array('argb' => 'FF0000')
								 )
						 );	
						$nombre_erreur = $nombre_erreur + 1;
						$erreur_date_naissance_age=$erreur_date_naissance_age + 1;	
					}
					if($sexe=='') {
						$sheet->getStyle("H".$ligne)->getFill()->applyFromArray(
								 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
									 'startcolor' => array('rgb' => 'FF0000'),
									 'endcolor'   => array('argb' => 'FF0000')
								 )
						 );	
						$nombre_erreur = $nombre_erreur + 1;						
						$erreur_sexe=$erreur_sexe + 1;
					}
				/*	if($situation_matrimonale=='') {
						$sheet->getStyle("I".$ligne)->getFill()->applyFromArray(
								 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
									 'startcolor' => array('rgb' => 'FF0000'),
									 'endcolor'   => array('argb' => 'FF0000')
								 )
						 );	
						$nombre_erreur = $nombre_erreur + 1;						
						$erreur_situation_matrimonale=$erreur_situation_matrimonale + 1;
					}
					if($cin=='') {
						$sheet->getStyle("J".$ligne)->getFill()->applyFromArray(
								 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
									 'startcolor' => array('rgb' => 'FF0000'),
									 'endcolor'   => array('argb' => 'FF0000')
								 )
						 );	
						$nombre_erreur = $nombre_erreur + 1;						
						$erreur_cin=$erreur_cin + 1;
					} */
				/*	if($profession=='') {
						$sheet->getStyle("K".$ligne)->getFill()->applyFromArray(
								 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
									 'startcolor' => array('rgb' => 'FF0000'),
									 'endcolor'   => array('argb' => 'FF0000')
								 )
						 );	
						$nombre_erreur = $nombre_erreur + 1;						
						$erreur_profession=$erreur_profession + 1;
					}*/
				/*	if($adresse=='') {
						$sheet->getStyle("L".$ligne)->getFill()->applyFromArray(
								 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
									 'startcolor' => array('rgb' => 'FF0000'),
									 'endcolor'   => array('argb' => 'FF0000')
								 )
						 );	
						$nombre_erreur = $nombre_erreur + 1;						
						$erreur_adresse=$erreur_adresse + 1;
					}
					if($surnom=='') {
						$sheet->getStyle("M".$ligne)->getFill()->applyFromArray(
								 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
									 'startcolor' => array('rgb' => 'FF0000'),
									 'endcolor'   => array('argb' => 'FF0000')
								 )
						 );	
						$nombre_erreur = $nombre_erreur + 1;						
						$erreur_surnom=$erreur_surnom + 1;
					}
					if($lien_de_parente=='') {
						$sheet->getStyle("N".$ligne)->getFill()->applyFromArray(
								 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
									 'startcolor' => array('rgb' => 'FF0000'),
									 'endcolor'   => array('argb' => 'FF0000')
								 )
						 );	
						$nombre_erreur = $nombre_erreur + 1;						
						$erreur_lien_de_parente=$erreur_lien_de_parente + 1;
					}
					if($niveau_classe=='') {
						$sheet->getStyle("P".$ligne)->getFill()->applyFromArray(
								 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
									 'startcolor' => array('rgb' => 'FF0000'),
									 'endcolor'   => array('argb' => 'FF0000')
								 )
						 );	
						$nombre_erreur = $nombre_erreur + 1;						
						$erreur_niveau_classe=$erreur_niveau_classe + 1;
					}
					if($langue=='') {
						$sheet->getStyle("Q".$ligne)->getFill()->applyFromArray(
								 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
									 'startcolor' => array('rgb' => 'FF0000'),
									 'endcolor'   => array('argb' => 'FF0000')
								 )
						 );	
						$nombre_erreur = $nombre_erreur + 1;						
						$erreur_langue=$erreur_langue + 1;
					}
					if($revenu=='') {
						$sheet->getStyle("R".$ligne)->getFill()->applyFromArray(
								 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
									 'startcolor' => array('rgb' => 'FF0000'),
									 'endcolor'   => array('argb' => 'FF0000')
								 )
						 );	
						$nombre_erreur = $nombre_erreur + 1;						
						$erreur_revenu=$erreur_revenu + 1;
					}
					if($depense=='') {
						$sheet->getStyle("S".$ligne)->getFill()->applyFromArray(
								 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
									 'startcolor' => array('rgb' => 'FF0000'),
									 'endcolor'   => array('argb' => 'FF0000')
								 )
						 );	
						$nombre_erreur = $nombre_erreur + 1;						
						$erreur_depense=$erreur_depense + 1;
					}*/
					if($date_inscription_detail_beneficiaire=='') {
						$sheet->getStyle("T".$ligne)->getFill()->applyFromArray(
								 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
									 'startcolor' => array('rgb' => 'FF0000'),
									 'endcolor'   => array('argb' => 'FF0000')
								 )
						 );	
						$nombre_erreur = $nombre_erreur + 1;						
						$erreur_date_inscription_detail_beneficiaire=$erreur_date_inscription_detail_beneficiaire + 1;
					}
					if($date_inscription_detail_chaine=="0000-00-00" || $date_inscription_detail_chaine=="00-00-0000" || $date_inscription_detail_chaine=="00/00/0000") {
						$sheet->getStyle("T".$ligne)->getFill()->applyFromArray(
								 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
									 'startcolor' => array('rgb' => 'FF0000'),
									 'endcolor'   => array('argb' => 'FF0000')
								 )
						 );								
					}
					/*if($telephone=='') {
						$sheet->getStyle("U".$ligne)->getFill()->applyFromArray(
								 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
									 'startcolor' => array('rgb' => 'FF0000'),
									 'endcolor'   => array('argb' => 'FF0000')
								 )
						 );	
						$nombre_erreur = $nombre_erreur + 1;						
						$erreur_telephone=$erreur_telephone + 1;
					}
					if($handicap_visuel=='') {
						$sheet->getStyle("V".$ligne)->getFill()->applyFromArray(
								 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
									 'startcolor' => array('rgb' => 'FF0000'),
									 'endcolor'   => array('argb' => 'FF0000')
								 )
						 );	
						$nombre_erreur = $nombre_erreur + 1;						
						$erreur_handicap_visuel=$erreur_handicap_visuel + 1;
					}
					if($handicap_auditif=='') {
						$sheet->getStyle("W".$ligne)->getFill()->applyFromArray(
								 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
									 'startcolor' => array('rgb' => 'FF0000'),
									 'endcolor'   => array('argb' => 'FF0000')
								 )
						 );	
						$nombre_erreur = $nombre_erreur + 1;						
						$erreur_handicap_auditif=$erreur_handicap_auditif + 1;
					}
					if($handicap_parole=='') {
						$sheet->getStyle("X".$ligne)->getFill()->applyFromArray(
								 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
									 'startcolor' => array('rgb' => 'FF0000'),
									 'endcolor'   => array('argb' => 'FF0000')
								 )
						 );	
						$nombre_erreur = $nombre_erreur + 1;						
						$erreur_handicap_parole=$erreur_handicap_parole + 1;
					}
					if($handicap_moteur=='') {
						$sheet->getStyle("Y".$ligne)->getFill()->applyFromArray(
								 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
									 'startcolor' => array('rgb' => 'FF0000'),
									 'endcolor'   => array('argb' => 'FF0000')
								 )
						 );	
						$nombre_erreur = $nombre_erreur + 1;						
						$erreur_handicap_moteur=$erreur_handicap_moteur + 1;
					}
					if($handicap_mental=='') {
						$sheet->getStyle("Z".$ligne)->getFill()->applyFromArray(
								 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
									 'startcolor' => array('rgb' => 'FF0000'),
									 'endcolor'   => array('argb' => 'FF0000')
								 )
						 );	
						$nombre_erreur = $nombre_erreur + 1;						
						$erreur_handicap_mental=$erreur_handicap_mental + 1;
					}
					if($nom_enqueteur=='') {
						$sheet->getStyle("AA".$ligne)->getFill()->applyFromArray(
								 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
									 'startcolor' => array('rgb' => 'FF0000'),
									 'endcolor'   => array('argb' => 'FF0000')
								 )
						 );	
						$nombre_erreur = $nombre_erreur + 1;						
						$erreur_nom_enqueteur=$erreur_nom_enqueteur + 1;
					}
					if($date_inscription=='') {
						$sheet->getStyle("AB".$ligne)->getFill()->applyFromArray(
								 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
									 'startcolor' => array('rgb' => 'FF0000'),
									 'endcolor'   => array('argb' => 'FF0000')
								 )
						 );	
						$nombre_erreur = $nombre_erreur + 1;						
						$erreur_date_inscription=$erreur_date_inscription + 1;
					} */
					if($indice_vulnerabilite>'') {
						$indice_vulnerabilite=strtolower($indice_vulnerabilite);
						$retour=$this->ValidationbeneficiaireManager->recuperer_id_indice_vulnerabilite($indice_vulnerabilite);
						if(!$retour) {
							$sheet->getStyle("AC".$ligne)->getFill()->applyFromArray(
									 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
										 'startcolor' => array('rgb' => 'FF0000'),
										 'endcolor'   => array('argb' => 'FF0000')
									 )
							 );		
							$nombre_erreur = $nombre_erreur + 1; 
							$erreur_indice_vulnerabilite=$erreur_indice_vulnerabilite + 1;
						}
					}	
				}	
				$ligne = $ligne + 1;
			}		
		}
		$date_inscription_beneficiaire = new DateTime($date_inscription_beneficiaire); 
		$date_inscription_beneficiaire =$date_inscription_beneficiaire->format('d/m/Y');				
		$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$objWriter->save(dirname(__FILE__) . "/../../../../" .$repertoire. $nomfichier);
		$val_ret = array();
			$sender = "ndrianaina.aime.bruno@gmail.com";
			$mdpsender = "finaritra";
		/*if($nombre_erreur > 0) {
			// Signaler les erreurs par mail
			$val_ret["reponse"] = "ERREUR";
			$val_ret["region"] = $nom_region_original;
			$val_ret["district"] = $nom_district_original;
			$val_ret["commune"] = $nom_commune_original;
			$val_ret["fokontany"] = $nom_fokontany_original;
			$val_ret["intervention"] = $intitule_intervention_original;
			$val_ret["date_inscription"] = $date_inscription_beneficiaire;
			$val_ret["nombre_erreur"] = $nombre_erreur;
			$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
			$objWriter->save(dirname(__FILE__) . "/../../../../" .$repertoire. $nomfichier);
			// Fermer fichier Excel
			unset($excel);
			unset($objWriter);
			// DEBUT ENVOI MAIL SIGNALANT LES ERREURS
			$data["type_fichier"] = " bénéficiaire ";
			$data["region"] = $nom_region_original;
			$data["district"] = $nom_district_original;
			$data["commune"] = $nom_commune_original;
			$data["fokontany"] = $nom_fokontany_original;
			$data["intervention"] = $intitule_intervention_original;
			$data["date_inscription"] = $date_inscription_beneficiaire;
			$newchiffrelettre = new chiffreEnLettre;
			$nombre_erreur_en_lettre= $newchiffrelettre->ConvNumberLetter($nombre_erreur,0,0);
			$data["nombre_erreur"] = $nombre_erreur;				
			$data["nombre_erreur_en_lettre"] = $nombre_erreur_en_lettre;				
			$sujet = 'Erreur lors de la validation de la liste des bénéficiaire';
			$corps = $this->load->view('mail/signaler_erreur_import.php', $data, true);
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
			$mail->addAttachment(dirname(__FILE__) . "/../../../../" .$repertoire. $nomfichier);
			$mail->setFrom($sender);
			$mail->addAddress($adresse_mail);
			$mail->isHTML(true);
			$mail->Subject = $sujet;
			$mail->Body = $corps;
			if (!$mail->send()) {
				$data = 0;
			} else {
				$data = 1;
			}		
			// FIN ENVOI MAIL SIGNALANT LES ERREURS
		} else { */
			// DEUXIEME VERIFICATION : vérification doublon			
			$chemin=dirname(__FILE__) . "/../../../../".$repertoire;
			$lien_vers_mon_document_excel = $chemin . $nomfichier;
			$array_data = array();
			if(strpos($lien_vers_mon_document_excel,"xlsx") >0) {
				$objet_read_write = PHPExcel_IOFactory::createReader('Excel2007');
				$excel = $objet_read_write->load($lien_vers_mon_document_excel);			 
				$sheet = $excel->getSheet(0);
				// pour lecture début - fin seulement
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
			$remplacer=array("'");
			$trouver= array("’");
			$remplacer=array('&eacute;','e','e','a','o','c','_');
			$trouver= array('é','è','ê','à','ö','ç',' ');
			//$nombre_erreur=0; // compter le nombre d'erreur afin de pouvoir renvoyer le fichier à l'envoyeur
			$id_menage=null;
			$search=array("'");
			$replace= array("’");
			foreach($rowIterator as $row) {
				// Contrôle détail s'il y a des doublons
				// Le controle se fait en 2 étapes
				// 1- Par identifiant_appariement
				// 2- Par nom,prenom CIN / Fokontany
				$beneficiaire_existant = false; // Si bénéficiaire déjà repertorié => Vérifier si déjà bénéficiaire de l'intervention =>Doublon
				$ligne = $row->getRowIndex ();
				if($ligne >=5) {
					// Contrôle de toutes les cellules à partir de la ligne 5
					 $cellIterator = $row->getCellIterator();
					 $cellIterator->setIterateOnlyExistingCells(false);
					 $rowIndex = $row->getRowIndex ();
					foreach ($cellIterator as $cell) {
						if('A' == $cell->getColumn()) {
							$numero_ordre=$cell->getValue();
						} else if('B' == $cell->getColumn()) {
							$identifiant_appariement =$cell->getValue();
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
							$date_inscription = $cell->getValue();
							if(isset($date_inscription) && $date_inscription>"") {
								if(PHPExcel_Shared_Date::isDateTime($cell)) {
									 $date_inscription = date($format='Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($date_inscription)); 
								}
							} else {
								$date_inscription=null;
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
							$date_enquete = $cell->getValue();
							if(isset($date_enquete) && $date_enquete>"") {
								if(PHPExcel_Shared_Date::isDateTime($cell)) {
									 $date_enquete = date($format='Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($date_enquete)); 
								}
							} else {
								$date_enquete=null;
							}								 							 
						 }
					}
					$nom=str_replace($search,$replace,$nom);
					$prenom=str_replace($search,$replace,$prenom);					
					$beneficiaire_existant=false;
					if($menage_ou_individu=="individu") {
						// Individu tout court
						$parametre_table="individu";
						$table ="individu";
						$table_controle ="individu_beneficiaire"; // Pour controler si un individu est déjà bénéficiaire de l'intervention
					} else if(strtolower($chef_menage) =="o") {
						// Si chef ménage
						$parametre_table="menage";
						$table ="menage";
						$table_controle ="menage_beneficiaire"; // Pour controler si un ménage est déjà bénéficiaire de l'intervention
					} else {
						// Individu apprtenant à un ménage
						$parametre_table="individu";
						$table ="individu";
						$table_controle ="menage_beneficiaire"; // Pour controler si un ménage est déjà bénéficiaire de l'intervention
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
							$retour=$this->ValidationbeneficiaireManager->RechercheFokontanyParNomPrenomCIN_Fokontany_Acteur($parametre_table,$identifiant_appariement,$id_acteur,$nom,$prenom,$cin,$id_fokontany,$id_menage);
							$nombre=0;
							foreach($retour as $k=>$v) {
								$nombre = $v->nombre;
							}							
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
								// Vérification si déjà bénéficiaire de l'intervention
								$nombre=0;
								$retour=$this->ValidationbeneficiaireManager->ControlerSiBeneficiaireIntervention($table_controle,$id_menage,$id_intervention);
								foreach($retour as $k=>$v) {
									 $nombre=$v->nombre;
								}
								// Bénéficiaire existant et bénéficie déjà de l'intervention =>ERREUR DOUBLON
								if($nombre >0) {
									// Doublon : ERREUR Marquage colonne AE par Doublon de couleur Jaune
									$sheet->getStyle("AE".$ligne)->getFill()->applyFromArray(
											 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
												 'startcolor' => array('rgb' => 'FFFF66'),
												 'endcolor'   => array('argb' => 'FFFF66')
											 )
									 );	
									$sheet->setCellValue("AE".$ligne, "Doublon : Déjà bénéficiaire de l'intervention");
									$nombre_erreur = $nombre_erreur + 1;	
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
								$sheet->setCellValue("AD".$ligne, $code_region."-".$code_district."-".$code_commune."-".$code_fokontany."-".$identifiant_unique);
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
								$sheet->getStyle("AE".$ligne)->getFill()->applyFromArray(
										 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
											 'startcolor' => array('rgb' => 'FFFF66'),
											 'endcolor'   => array('argb' => 'FFFF66')
										 )
								 );	
								$sheet->setCellValue("AE".$ligne, "Doublon : Déjà bénéficiaire de l'intervention");
								$nombre_erreur = $nombre_erreur + 1;	
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
									// Vérification si déjà bénéficiaire de l'intervention
									$nombre=0;
									$retour=$this->ValidationbeneficiaireManager->ControlerSiBeneficiaireIntervention($table_controle,$id_menage,$id_intervention);
									foreach($retour as $k=>$v) {
										 $nombre=$v->nombre;
									}
									// Bénéficiaire existant et bénéficie déjà de l'intervention =>ERREUR DOUBLON
									if($nombre >0) {
										// Doublon : ERREUR Marquage colonne AE par Doublon de couleur Jaune
										$sheet->getStyle("AE".$ligne)->getFill()->applyFromArray(
												 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
													 'startcolor' => array('rgb' => 'FFFF66'),
													 'endcolor'   => array('argb' => 'FFFF66')
												 )
										 );	
										$sheet->setCellValue("AE".$ligne, "Doublon : Déjà bénéficiaire de l'intervention");
										$nombre_erreur = $nombre_erreur + 1;	
									}						
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
								// Vérification si déjà bénéficiaire de l'intervention
								$nombre=0;
								$retour=$this->ValidationbeneficiaireManager->ControlerSiBeneficiaireIntervention($table_controle,$id_individu,$id_intervention);
								foreach($retour as $k=>$v) {
									 $nombre=$v->nombre;
								}
								// Bénéficiaire existant et bénéficie déjà de l'intervention =>ERREUR DOUBLON
								if($nombre >0) {
									// Doublon : ERREUR Marquage colonne AE par Doublon de couleur Jaune
									$sheet->getStyle("AE".$ligne)->getFill()->applyFromArray(
											 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
												 'startcolor' => array('rgb' => 'FFFF66'),
												 'endcolor'   => array('argb' => 'FFFF66')
											 )
									 );	
									$sheet->setCellValue("AE".$ligne, "Doublon : Déjà bénéficiaire de l'intervention");
									$nombre_erreur = $nombre_erreur + 1;	
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
						$date_naissance = date('Y-m-d', strtotime($now. ' -'.$age.' years +1 days'));
						
						// $date_naissance = $date_par_defaut->sub(DateInterval::createFromDateString("'".$age." year'"));
						$date_naissance=$date_naissance->format("d/m/Y");
						$sheet->setCellValue('F'.$ligne, $date_naissance);
						// $sheet->setCellValue('I3', $id_fokontany);
					}
				}	
				$ligne = $ligne + 1;
			}
			if($nombre_erreur > 0) {
				// Signaler les erreurs par mail
				$date_inscription_beneficiaire = new DateTime($date_envoi_fichier); 
				$date_inscription_beneficiaire =$date_inscription_beneficiaire->format('d/m/Y');				
				$val_ret["reponse"] = "ERREUR";
				$val_ret["region"] = $nom_region_original;
				$val_ret["district"] = $nom_district_original;
				$val_ret["commune"] = $nom_commune_original;
				$val_ret["fokontany"] = $nom_fokontany_original;
				$val_ret["intervention"] = $intitule_intervention;
				$val_ret["date_inscription"] = $date_inscription_beneficiaire;
				$val_ret["nombre_erreur"] = $nombre_erreur;				
				$newchiffrelettre = new chiffreEnLettre;
				$nombre_erreur_en_lettre= $newchiffrelettre->ConvNumberLetter($nombre_erreur,0,0);
				$val_ret["nombre_erreur_en_lettre"] = $nombre_erreur_en_lettre;		
				// Création deuxième feuille pour récapituler les erreurs
				$objPHPExcel = $excel->createSheet(1);
				$sheet = $excel->getSheet(1);
				$objPHPExcel =$sheet;
				$objPHPExcel->getColumnDimension('A')->setWidth(30);
				$objPHPExcel->getColumnDimension('B')->setWidth(13);
				$objPHPExcel->getStyle("A1:B2")->getFont()->setSize(12);			
				$objPHPExcel->getStyle('A1:B2')->getFont()->setBold(true);
				$objPHPExcel->getPageSetup()->setHorizontalCentered(true);
				$objPHPExcel->getStyle("A1:B2")->getAlignment()->setWrapText(true);				
				$objPHPExcel->getStyle("A1:B2")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getStyle("A1:B2")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$objPHPExcel->setTitle('Recapitulatif des erreurs');
				$objPHPExcel->mergeCells("A1:B1");				
				$objPHPExcel->setCellValue("A1", "RECAPITULATIF DES ERREURS");					
				$styleArray = array(
				  'borders' => array(
					'allborders' => array(
					  'style' => PHPExcel_Style_Border::BORDER_THIN
					)
				  )
				);		
				$objPHPExcel->getStyle("A1:B1")->applyFromArray($styleArray);
				$objPHPExcel->setCellValue("A2", "Colonne");					
				$objPHPExcel->setCellValue("B2", "Nombre d'erreur");					
				$objPHPExcel->getStyle("A2:B2")->applyFromArray($styleArray);
				$ligne=3;
				if($erreur_nom_acteur>0) {
					$objPHPExcel->setCellValue("A".$ligne, "Nom acteur");					
					$objPHPExcel->setCellValue("B".$ligne, $erreur_nom_acteur);					
					$objPHPExcel->getStyle("A".$ligne.":B".$ligne)->applyFromArray($styleArray);	
					$objPHPExcel->getStyle("A".$ligne)->getAlignment()->setWrapText(true);		
					$ligne=$ligne + 1;	
				}
				if($erreur_intitule_intervention>0) {
					$objPHPExcel->setCellValue("A".$ligne, "Intitule intervention");					
					$objPHPExcel->setCellValue("B".$ligne, $erreur_intitule_intervention);					
					$objPHPExcel->getStyle("A".$ligne.":B".$ligne)->applyFromArray($styleArray);	
					$objPHPExcel->getStyle("A".$ligne)->getAlignment()->setWrapText(true);		
					$ligne=$ligne + 1;						
				}
				if($erreur_date_envoi_fichier>0) {
					$objPHPExcel->setCellValue("A".$ligne, "Date d'envoi");					
					$objPHPExcel->setCellValue("B".$ligne, $erreur_date_envoi_fichier);					
					$objPHPExcel->getStyle("A".$ligne.":B".$ligne)->applyFromArray($styleArray);	
					$objPHPExcel->getStyle("A".$ligne)->getAlignment()->setWrapText(true);		
					$ligne=$ligne + 1;						
				}
				if($erreur_menage_ou_individu>0) {					
					$objPHPExcel->setCellValue("A".$ligne, "Cible");					
					$objPHPExcel->setCellValue("B".$ligne, $erreur_menage_ou_individu);					
					$objPHPExcel->getStyle("A".$ligne.":B".$ligne)->applyFromArray($styleArray);	
					$objPHPExcel->getStyle("A".$ligne)->getAlignment()->setWrapText(true);		
					$ligne=$ligne + 1;						
				}
				if($erreur_nom_region>0) {					
					$objPHPExcel->setCellValue("A".$ligne, "Région");					
					$objPHPExcel->setCellValue("B".$ligne, $erreur_nom_region);					
					$objPHPExcel->getStyle("A".$ligne.":B".$ligne)->applyFromArray($styleArray);	
					$objPHPExcel->getStyle("A".$ligne)->getAlignment()->setWrapText(true);		
					$ligne=$ligne + 1;						
				}
				if($erreur_nom_district>0) {					
					$objPHPExcel->setCellValue("A".$ligne, "District");					
					$objPHPExcel->setCellValue("B".$ligne, $erreur_nom_district);					
					$objPHPExcel->getStyle("A".$ligne.":B".$ligne)->applyFromArray($styleArray);	
					$objPHPExcel->getStyle("A".$ligne)->getAlignment()->setWrapText(true);		
					$ligne=$ligne + 1;						
				}
				if($erreur_nom_commune>0) {					
					$objPHPExcel->setCellValue("A".$ligne, "Commune");					
					$objPHPExcel->setCellValue("B".$ligne, $erreur_nom_commune);					
					$objPHPExcel->getStyle("A".$ligne.":B".$ligne)->applyFromArray($styleArray);	
					$objPHPExcel->getStyle("A".$ligne)->getAlignment()->setWrapText(true);		
					$ligne=$ligne + 1;						
				}
				if($erreur_nom_fokontany>0) {					
					$objPHPExcel->setCellValue("A".$ligne, "Fokontany");					
					$objPHPExcel->setCellValue("B".$ligne, $erreur_nom_fokontany);					
					$objPHPExcel->getStyle("A".$ligne.":B".$ligne)->applyFromArray($styleArray);	
					$objPHPExcel->getStyle("A".$ligne)->getAlignment()->setWrapText(true);		
					$ligne=$ligne + 1;						
				}
				if($erreur_identifiant_appariement >0) {					
					$objPHPExcel->setCellValue("A".$ligne, "Identifiant appariement");					
					$objPHPExcel->setCellValue("B".$ligne, $erreur_identifiant_appariement);					
					$objPHPExcel->getStyle("A".$ligne.":B".$ligne)->applyFromArray($styleArray);	
					$objPHPExcel->getStyle("A".$ligne)->getAlignment()->setWrapText(true);		
					$ligne=$ligne + 1;						
				}
				if($erreur_nom >0) {					
					$objPHPExcel->setCellValue("A".$ligne, "Nom");					
					$objPHPExcel->setCellValue("B".$ligne, $erreur_nom);					
					$objPHPExcel->getStyle("A".$ligne.":B".$ligne)->applyFromArray($styleArray);	
					$objPHPExcel->getStyle("A".$ligne)->getAlignment()->setWrapText(true);		
					$ligne=$ligne + 1;						
				}
				if($erreur_prenom >0) {					
					$objPHPExcel->setCellValue("A".$ligne, "Prénom");					
					$objPHPExcel->setCellValue("B".$ligne, $erreur_prenom);					
					$objPHPExcel->getStyle("A".$ligne.":B".$ligne)->applyFromArray($styleArray);	
					$objPHPExcel->getStyle("A".$ligne)->getAlignment()->setWrapText(true);		
					$ligne=$ligne + 1;						
				}				
				if($erreur_date_naissance_age>0) {					
					$objPHPExcel->setCellValue("A".$ligne, "Date de naissance/Age");					
					$objPHPExcel->setCellValue("B".$ligne, $erreur_date_naissance_age);					
					$objPHPExcel->getStyle("A".$ligne.":B".$ligne)->applyFromArray($styleArray);	
					$objPHPExcel->getStyle("A".$ligne)->getAlignment()->setWrapText(true);		
					$ligne=$ligne + 1;						
				}
				if($erreur_sexe>0) {					
					$objPHPExcel->setCellValue("A".$ligne, "Sexe");					
					$objPHPExcel->setCellValue("B".$ligne, $erreur_sexe);					
					$objPHPExcel->getStyle("A".$ligne.":B".$ligne)->applyFromArray($styleArray);	
					$objPHPExcel->getStyle("A".$ligne)->getAlignment()->setWrapText(true);		
					$ligne=$ligne + 1;						
				}
				if($erreur_situation_matrimonale>0) {					
					$objPHPExcel->setCellValue("A".$ligne, "Situation matrimoniale");					
					$objPHPExcel->setCellValue("B".$ligne, $erreur_situation_matrimonale);					
					$objPHPExcel->getStyle("A".$ligne.":B".$ligne)->applyFromArray($styleArray);	
					$objPHPExcel->getStyle("A".$ligne)->getAlignment()->setWrapText(true);		
					$ligne=$ligne + 1;						
				}
				if($erreur_cin>0) {					
					$objPHPExcel->setCellValue("A".$ligne, "CIN");					
					$objPHPExcel->setCellValue("B".$ligne, $erreur_cin);					
					$objPHPExcel->getStyle("A".$ligne.":B".$ligne)->applyFromArray($styleArray);	
					$objPHPExcel->getStyle("A".$ligne)->getAlignment()->setWrapText(true);		
					$ligne=$ligne + 1;						
				}
				if($erreur_profession>0) {					
					$objPHPExcel->setCellValue("A".$ligne, "Profession");					
					$objPHPExcel->setCellValue("B".$ligne, $erreur_profession);					
					$objPHPExcel->getStyle("A".$ligne.":B".$ligne)->applyFromArray($styleArray);	
					$objPHPExcel->getStyle("A".$ligne)->getAlignment()->setWrapText(true);		
					$ligne=$ligne + 1;						
				}
				if($erreur_adresse>0) {					
					$objPHPExcel->setCellValue("A".$ligne, "Adresse");					
					$objPHPExcel->setCellValue("B".$ligne, $erreur_adresse);					
					$objPHPExcel->getStyle("A".$ligne.":B".$ligne)->applyFromArray($styleArray);	
					$objPHPExcel->getStyle("A".$ligne)->getAlignment()->setWrapText(true);		
					$ligne=$ligne + 1;						
				}
				if($erreur_surnom>0) {					
					$objPHPExcel->setCellValue("A".$ligne, "Surnom");					
					$objPHPExcel->setCellValue("B".$ligne, $erreur_surnom);					
					$objPHPExcel->getStyle("A".$ligne.":B".$ligne)->applyFromArray($styleArray);	
					$objPHPExcel->getStyle("A".$ligne)->getAlignment()->setWrapText(true);		
					$ligne=$ligne + 1;						
				}
				if($erreur_lien_de_parente>0) {					
					$objPHPExcel->setCellValue("A".$ligne, "Lien de parenté");					
					$objPHPExcel->setCellValue("B".$ligne, $erreur_lien_de_parente);					
					$objPHPExcel->getStyle("A".$ligne.":B".$ligne)->applyFromArray($styleArray);	
					$objPHPExcel->getStyle("A".$ligne)->getAlignment()->setWrapText(true);		
					$ligne=$ligne + 1;						
				}
				if($erreur_niveau_classe>0) {					
					$objPHPExcel->setCellValue("A".$ligne, "Niveau de classe");					
					$objPHPExcel->setCellValue("B".$ligne, $erreur_niveau_classe);					
					$objPHPExcel->getStyle("A".$ligne.":B".$ligne)->applyFromArray($styleArray);	
					$objPHPExcel->getStyle("A".$ligne)->getAlignment()->setWrapText(true);		
					$ligne=$ligne + 1;						
				}
				if($erreur_langue>0) {					
					$objPHPExcel->setCellValue("A".$ligne, "Langue");					
					$objPHPExcel->setCellValue("B".$ligne, $erreur_langue);					
					$objPHPExcel->getStyle("A".$ligne.":B".$ligne)->applyFromArray($styleArray);	
					$objPHPExcel->getStyle("A".$ligne)->getAlignment()->setWrapText(true);		
					$ligne=$ligne + 1;						
				}
				if($erreur_revenu>0) {					
					$objPHPExcel->setCellValue("A".$ligne, "Revenu");					
					$objPHPExcel->setCellValue("B".$ligne, $erreur_revenu);					
					$objPHPExcel->getStyle("A".$ligne.":B".$ligne)->applyFromArray($styleArray);	
					$objPHPExcel->getStyle("A".$ligne)->getAlignment()->setWrapText(true);		
					$ligne=$ligne + 1;						
				}
				if($erreur_depense>0) {					
					$objPHPExcel->setCellValue("A".$ligne, "Dépense");					
					$objPHPExcel->setCellValue("B".$ligne, $erreur_depense);					
					$objPHPExcel->getStyle("A".$ligne.":B".$ligne)->applyFromArray($styleArray);	
					$objPHPExcel->getStyle("A".$ligne)->getAlignment()->setWrapText(true);		
					$ligne=$ligne + 1;						
				}
				if($erreur_date_inscription_detail_beneficiaire>0) {					
					$objPHPExcel->setCellValue("A".$ligne, "Date inscription bénéficiaire");					
					$objPHPExcel->setCellValue("B".$ligne, $erreur_date_inscription_detail_beneficiaire);					
					$objPHPExcel->getStyle("A".$ligne.":B".$ligne)->applyFromArray($styleArray);	
					$objPHPExcel->getStyle("A".$ligne)->getAlignment()->setWrapText(true);		
					$ligne=$ligne + 1;						
				}
				if($erreur_telephone>0) {					
					$objPHPExcel->setCellValue("A".$ligne, "Téléphone");					
					$objPHPExcel->setCellValue("B".$ligne, $erreur_telephone);					
					$objPHPExcel->getStyle("A".$ligne.":B".$ligne)->applyFromArray($styleArray);	
					$objPHPExcel->getStyle("A".$ligne)->getAlignment()->setWrapText(true);		
					$ligne=$ligne + 1;						
				}
				if($erreur_handicap_visuel>0) {					
					$objPHPExcel->setCellValue("A".$ligne, "Handicap visuel");					
					$objPHPExcel->setCellValue("B".$ligne, $erreur_handicap_visuel);					
					$objPHPExcel->getStyle("A".$ligne.":B".$ligne)->applyFromArray($styleArray);	
					$objPHPExcel->getStyle("A".$ligne)->getAlignment()->setWrapText(true);		
					$ligne=$ligne + 1;						
				}
				if($erreur_handicap_auditif>0) {					
					$objPHPExcel->setCellValue("A".$ligne, "Handicap auditif");					
					$objPHPExcel->setCellValue("B".$ligne, $erreur_handicap_auditif);					
					$objPHPExcel->getStyle("A".$ligne.":B".$ligne)->applyFromArray($styleArray);	
					$objPHPExcel->getStyle("A".$ligne)->getAlignment()->setWrapText(true);		
					$ligne=$ligne + 1;						
				}
				if($erreur_handicap_parole>0) {					
					$objPHPExcel->setCellValue("A".$ligne, "Handicap parole");					
					$objPHPExcel->setCellValue("B".$ligne, $erreur_handicap_parole);					
					$objPHPExcel->getStyle("A".$ligne.":B".$ligne)->applyFromArray($styleArray);	
					$objPHPExcel->getStyle("A".$ligne)->getAlignment()->setWrapText(true);		
					$ligne=$ligne + 1;						
				}
				if($erreur_handicap_moteur>0) {					
					$objPHPExcel->setCellValue("A".$ligne, "Handicap moteur");					
					$objPHPExcel->setCellValue("B".$ligne, $erreur_handicap_moteur);					
					$objPHPExcel->getStyle("A".$ligne.":B".$ligne)->applyFromArray($styleArray);	
					$objPHPExcel->getStyle("A".$ligne)->getAlignment()->setWrapText(true);		
					$ligne=$ligne + 1;						
				}
				if($erreur_handicap_mental>0) {					
					$objPHPExcel->setCellValue("A".$ligne, "Handicap mental");					
					$objPHPExcel->setCellValue("B".$ligne, $erreur_handicap_mental);					
					$objPHPExcel->getStyle("A".$ligne.":B".$ligne)->applyFromArray($styleArray);	
					$objPHPExcel->getStyle("A".$ligne)->getAlignment()->setWrapText(true);		
					$ligne=$ligne + 1;						
				}
				if($erreur_nom_enqueteur>0) {					
					$objPHPExcel->setCellValue("A".$ligne, "Nom enqueteur");					
					$objPHPExcel->setCellValue("B".$ligne, $erreur_nom_enqueteur);					
					$objPHPExcel->getStyle("A".$ligne.":B".$ligne)->applyFromArray($styleArray);	
					$objPHPExcel->getStyle("A".$ligne)->getAlignment()->setWrapText(true);		
					$ligne=$ligne + 1;						
				}
				if($erreur_date_inscription>0) {					
					$objPHPExcel->setCellValue("A".$ligne, "Date inscription");					
					$objPHPExcel->setCellValue("B".$ligne, $erreur_date_inscription);					
					$objPHPExcel->getStyle("A".$ligne.":B".$ligne)->applyFromArray($styleArray);	
					$objPHPExcel->getStyle("A".$ligne)->getAlignment()->setWrapText(true);		
					$ligne=$ligne + 1;						
				}
				if($erreur_indice_vulnerabilite>0) {					
					$objPHPExcel->setCellValue("A".$ligne, "Indice de vulnérabilité");					
					$objPHPExcel->setCellValue("B".$ligne, $erreur_indice_vulnerabilite);					
					$objPHPExcel->getStyle("A".$ligne.":B".$ligne)->applyFromArray($styleArray);	
					$objPHPExcel->getStyle("A".$ligne)->getAlignment()->setWrapText(true);		
					$ligne=$ligne + 1;						
				}	
				$objPHPExcel->getStyle("A".$ligne)->getFont()->setSize(12);			
				$objPHPExcel->getStyle("A".$ligne)->getFont()->setBold(true);
				$objPHPExcel->getStyle("A".$ligne.":B".$ligne)->applyFromArray($styleArray);
				$objPHPExcel->setCellValue("A".$ligne, "TOTAL : ");					
				$objPHPExcel->getStyle("A".$ligne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				$objPHPExcel->setCellValue('B'. ($ligne),'=SUM(B3:B'.($ligne - 1).')');				
				$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
				$objWriter->save(dirname(__FILE__) . "/../../../../" .$repertoire. $nomfichier);
				// Fermer fichier Excel
				unset($objWriter);
				// DEBUT ENVOI MAIL SIGNALANT LES ERREURS
				$data["nombre_erreur"] = $nombre_erreur;				
				$data["nombre_erreur_en_lettre"] = $nombre_erreur_en_lettre;				
				$data["type_fichier"] = " bénéficiaire ";
				$data["region"] = $nom_region_original;
				$data["district"] = $nom_district_original;
				$data["commune"] = $nom_commune_original;
				$data["fokontany"] = $nom_fokontany_original;
				$data["intervention"] = $intitule_intervention_original;
				$data["date_inscription"] = $date_inscription_beneficiaire;
				$sujet = 'Erreur lors de la validation de la liste des bénéficiaire';
				$corps = $this->load->view('mail/signaler_erreur_import.php', $data, true);
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
				$mail->addAttachment(dirname(__FILE__) . "/../../../../" .$repertoire. $nomfichier);
				$mail->setFrom($sender);
				$mail->addAddress($adresse_mail);
				$mail->isHTML(true);
				$mail->Subject = $sujet;
				$mail->Body = $corps;
				if (!$mail->send()) {
					$data = 0;
				} else {
					$data = 1;
				}		
				// FIN ENVOI MAIL SIGNALANT LES ERREURS
			} else {
				$val_ret["reponse"] = "OK";			
				$val_ret["region"] = $nom_region_original;
				$val_ret["district"] = $nom_district_original;
				$val_ret["commune"] = $nom_commune_original;
				$val_ret["fokontany"] = $nom_fokontany_original;
				$val_ret["intervention"] = $intitule_intervention_original;
				$val_ret["date_inscription"] = $date_inscription_beneficiaire;
				$val_ret["nombre_erreur"] = 0;	
				$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
				$objWriter->save(dirname(__FILE__) . "/../../../../" .$repertoire. $nomfichier);
			}
	/*	}	*/
		return ($val_ret);
	}	
	// Envoi email vers acteur pour signaler qu'aucune erreur a été détéctée
	public function envoyer_mail_validation_donnees() {
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
		$retour=$this->ListevalidationbeneficiaireManager->findByMaxDateReceptionAndUtilisateur($id_utilisateur);
		$date_reception="";
		if($retour) {
			foreach($retour as $k=>$v) {
				$date_reception=$v->date_reception;
			}
			$date_reception = new DateTime($date_reception); 
			$date_reception =$date_reception->format('d/m/Y H:m:s');			
		}
		// DEBUT ENVOI MAIL SIGNALANT QUE TOUT EST OK
		$data["type_fichier"] = " bénéficiaire intervention ".($retour !="" ? "(envoyé le ".$date_reception.")" : "");
		$data["region"] = $region;
		$data["district"] = $district;
		$data["commune"] = $commune;
		$data["fokontany"] = $fokontany;
		$data["intervention"] = $intervention;
		$data["date_inscription"] = $date_inscription;
		$sender = "ndrianaina.aime.bruno@gmail.com";
		$mdpsender = "finaritra";
		$sujet = "Accusé de reception : fichier excel bénéficiaire";
		$corps = $this->load->view('mail/signaler_import_valide.php', $data, true);
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
		$mail->addAddress($adresse_mail);
		$mail->isHTML(true);
		$mail->Subject = $sujet;
		$mail->Body = $corps;
		if (!$mail->send()) {
			$data = 0;
		} else {
			$data = 1;
		}	
		echo ($data);
		// FIN ENVOI MAIL SIGNALANT QUE TOUT EST OK
	}
} ?>	
