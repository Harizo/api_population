<?php

defined('BASEPATH') OR exit('No direct script access allowed');
// require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/PHPMailer/PHPMailerAutoload.php';

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
			$total_non_validees = $total_non_validees + $v->nombre_beneficiaire_non_valides;
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
						} else if('F' == $cell->getColumn()) {
							$date_inscription = $cell->getValue();
							if(isset($date_inscription) && $date_inscription>"") {
								if(PHPExcel_Shared_Date::isDateTime($cell)) {
									 $date_inscription = date($format='Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($date_inscription)); 
									 $date_inscription_beneficiaire = $date_inscription;
								}
							} else {
								$date_inscription=null;
								$date_inscription_beneficiaire =null;
							}	
						} else if('H' == $cell->getColumn()) {
							$menage_ou_individu = $cell->getValue();
							$menage_ou_groupe = $cell->getValue();
						}	 
					}
					// Si donnée incorrect : coleur cellule en rouge
					if($nom_acteur=="") {
						$nombre_erreur = $nombre_erreur + 1;						
						$sheet->getStyle("B2")->getFill()->applyFromArray(
								 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
									 'startcolor' => array('rgb' => 'FF0000'),
									 'endcolor'   => array('argb' => 'FF0000')
								 )
						 );													
					} else {
						// Vérifier si nom_acteur existe dans la BDD
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
						}
					} 
					if($intitule_intervention=="") {
						$nombre_erreur = $nombre_erreur + 1;						
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
						} else {
							foreach($retour as $k=>$v) {
								$id_intervention = $v->id;
							}
						}
					}
					if(!$date_inscription) {
						$nombre_erreur = $nombre_erreur + 1;						
						$sheet->getStyle("F2")->getFill()->applyFromArray(
								 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
									 'startcolor' => array('rgb' => 'FF0000'),
									 'endcolor'   => array('argb' => 'FF0000')
								 )
						 );													
					} 
					if($menage_ou_individu=="") {
						$nombre_erreur = $nombre_erreur + 1;						
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
						$menage_ou_individu = substr($menage_ou_individu,3);
						$menage_ou_groupe = substr($menage_ou_groupe,3);						
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
					if($nom_region >'') {
						if($amoron_mania==false) {
							$reg = $this->ValidationbeneficiaireManager->selectionregion($nom_region);
						} else {
							$reg = $this->ValidationbeneficiaireManager->selectionregionparid(5);
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
						}	
						if(intval($id_region) >0) {
							if($nom_district >'') {
								$region_ok = true;
								$dis = $this->ValidationbeneficiaireManager->selectiondistrict($nom_district,$id_region);
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
								}
								if(intval($id_district) >0) {
									if($nom_commune >'') {
										$district_ok = true;
										$comm = $this->ValidationbeneficiaireManager->selectioncommune($nom_commune,$id_district);
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
										}	
										if(intval($id_commune) >0) {
											if($nom_fokontany >'') {
												$fkt = $this->ValidationbeneficiaireManager->selectionfokontany($nom_fokontany,$id_commune);
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
					}
				}		
			/*	if($ligne ==4) {
					// Contrôle catégorie d'age, sexe, vulnérabilité, pauvreté, type ménage, avec enfant
					 $cellIterator = $row->getCellIterator();
					 $cellIterator->setIterateOnlyExistingCells(false);
					 $rowIndex = $row->getRowIndex ();
					foreach ($cellIterator as $cell) {
						if('B' == $cell->getColumn()) {
							$categorie_age =$cell->getValue();
						} else if('D' == $cell->getColumn()) {
							$sexe =$cell->getValue();	
						} else if('F' == $cell->getColumn()) {
							$vulnerabilite = $cell->getValue();
						} else if('H' == $cell->getColumn()) {
							$pauvrete = $cell->getValue();
						} else if('J' == $cell->getColumn()) {
							$type_menage = $cell->getValue();
						} else if('L' == $cell->getColumn()) {
							$avec_enfant = $cell->getValue();
						}	 
					}
					// Si donnée incorrect : coleur cellule en rouge
					if($categorie_age=="") {
						$nombre_erreur = $nombre_erreur + 1;						
						$sheet->getStyle("B4")->getFill()->applyFromArray(
								 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
									 'startcolor' => array('rgb' => 'FF0000'),
									 'endcolor'   => array('argb' => 'FF0000')
								 )
						 );													
					} 
					if($sexe=="") {
						$nombre_erreur = $nombre_erreur + 1;						
						$sheet->getStyle("D4")->getFill()->applyFromArray(
								 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
									 'startcolor' => array('rgb' => 'FF0000'),
									 'endcolor'   => array('argb' => 'FF0000')
								 )
						 );													
					}
					if($vulnerabilite=="") {
						$nombre_erreur = $nombre_erreur + 1;						
						$sheet->getStyle("F4")->getFill()->applyFromArray(
								 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
									 'startcolor' => array('rgb' => 'FF0000'),
									 'endcolor'   => array('argb' => 'FF0000')
								 )
						 );													
					}
					if($pauvrete=="") {
						$nombre_erreur = $nombre_erreur + 1;						
						$sheet->getStyle("H4")->getFill()->applyFromArray(
								 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
									 'startcolor' => array('rgb' => 'FF0000'),
									 'endcolor'   => array('argb' => 'FF0000')
								 )
						 );													
					}
					if($type_menage=="") {
						$nombre_erreur = $nombre_erreur + 1;						
						$sheet->getStyle("J4")->getFill()->applyFromArray(
								 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
									 'startcolor' => array('rgb' => 'FF0000'),
									 'endcolor'   => array('argb' => 'FF0000')
								 )
						 );													
					}
					if($avec_enfant=="") {
						$nombre_erreur = $nombre_erreur + 1;						
						$sheet->getStyle("L4")->getFill()->applyFromArray(
								 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
									 'startcolor' => array('rgb' => 'FF0000'),
									 'endcolor'   => array('argb' => 'FF0000')
								 )
						 );													
					}
				}	
				if($ligne ==5) {
					// Contrôle mecanisme de ciblage, ciblage communautaire,ciblage catégoriel,ciblage économique,autres methodes
					 $cellIterator = $row->getCellIterator();
					 $cellIterator->setIterateOnlyExistingCells(false);
					 $rowIndex = $row->getRowIndex ();
					foreach ($cellIterator as $cell) {
						if('B' == $cell->getColumn()) {
							$mecanisme_ciblage =$cell->getValue();
						} else if('D' == $cell->getColumn()) {
							$ciblage_communautaire =$cell->getValue();	
						} else if('F' == $cell->getColumn()) {
							$ciblage_categoriel = $cell->getValue();
						} else if('H' == $cell->getColumn()) {
							$ciblage_economique = $cell->getValue();
						} else if('J' == $cell->getColumn()) {
							$autres_methode = $cell->getValue();
						}	 
					}
					// Si donnée incorrect : coleur cellule en rouge
					if($mecanisme_ciblage=="") {
						$nombre_erreur = $nombre_erreur + 1;						
						$sheet->getStyle("B5")->getFill()->applyFromArray(
								 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
									 'startcolor' => array('rgb' => 'FF0000'),
									 'endcolor'   => array('argb' => 'FF0000')
								 )
						 );													
					} 
					if($ciblage_communautaire=="") {
						$nombre_erreur = $nombre_erreur + 1;						
						$sheet->getStyle("D5")->getFill()->applyFromArray(
								 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
									 'startcolor' => array('rgb' => 'FF0000'),
									 'endcolor'   => array('argb' => 'FF0000')
								 )
						 );													
					}
					if($ciblage_categoriel=="") {
						$nombre_erreur = $nombre_erreur + 1;						
						$sheet->getStyle("F5")->getFill()->applyFromArray(
								 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
									 'startcolor' => array('rgb' => 'FF0000'),
									 'endcolor'   => array('argb' => 'FF0000')
								 )
						 );													
					}
					if($ciblage_economique=="") {
						$nombre_erreur = $nombre_erreur + 1;						
						$sheet->getStyle("H5")->getFill()->applyFromArray(
								 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
									 'startcolor' => array('rgb' => 'FF0000'),
									 'endcolor'   => array('argb' => 'FF0000')
								 )
						 );													
					}
					if($autres_methode=="") {
						$nombre_erreur = $nombre_erreur + 1;						
						$sheet->getStyle("J5")->getFill()->applyFromArray(
								 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
									 'startcolor' => array('rgb' => 'FF0000'),
									 'endcolor'   => array('argb' => 'FF0000')
								 )
						 );													
					}
				}*/
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
							$date_inscription = $cell->getValue();
							if(isset($date_inscription) && $date_inscription>"") {
								if(PHPExcel_Shared_Date::isDateTime($cell)) {
									 $date_inscription = date($format='Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($date_inscription)); 
								}
							} else {
								$date_inscription=null;
							}								 							 
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
					}
					if($nom=='') {
						$sheet->getStyle("C".$ligne)->getFill()->applyFromArray(
								 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
									 'startcolor' => array('rgb' => 'FF0000'),
									 'endcolor'   => array('argb' => 'FF0000')
								 )
						 );	
						$nombre_erreur = $nombre_erreur + 1;						
					}
					if($prenom=='') {
						$sheet->getStyle("D".$ligne)->getFill()->applyFromArray(
								 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
									 'startcolor' => array('rgb' => 'FF0000'),
									 'endcolor'   => array('argb' => 'FF0000')
								 )
						 );	
						$nombre_erreur = $nombre_erreur + 1;						
					}
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
					} else if(intval($age)>0) {
						// Calcul par défaut date naissance au 01/01/AAAA
						
					} */
					if($sexe=='') {
						$sheet->getStyle("H".$ligne)->getFill()->applyFromArray(
								 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
									 'startcolor' => array('rgb' => 'FF0000'),
									 'endcolor'   => array('argb' => 'FF0000')
								 )
						 );	
						$nombre_erreur = $nombre_erreur + 1;						
					}
				/*	if($situation_matrimonale=='') {
						$sheet->getStyle("I".$ligne)->getFill()->applyFromArray(
								 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
									 'startcolor' => array('rgb' => 'FF0000'),
									 'endcolor'   => array('argb' => 'FF0000')
								 )
						 );	
						$nombre_erreur = $nombre_erreur + 1;						
					}
					if($cin=='') {
						$sheet->getStyle("J".$ligne)->getFill()->applyFromArray(
								 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
									 'startcolor' => array('rgb' => 'FF0000'),
									 'endcolor'   => array('argb' => 'FF0000')
								 )
						 );	
						$nombre_erreur = $nombre_erreur + 1;						
					} */
					if($profession=='') {
						$sheet->getStyle("K".$ligne)->getFill()->applyFromArray(
								 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
									 'startcolor' => array('rgb' => 'FF0000'),
									 'endcolor'   => array('argb' => 'FF0000')
								 )
						 );	
						$nombre_erreur = $nombre_erreur + 1;						
					}
				/*	if($adresse=='') {
						$sheet->getStyle("L".$ligne)->getFill()->applyFromArray(
								 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
									 'startcolor' => array('rgb' => 'FF0000'),
									 'endcolor'   => array('argb' => 'FF0000')
								 )
						 );	
						$nombre_erreur = $nombre_erreur + 1;						
					}
					if($surnom=='') {
						$sheet->getStyle("M".$ligne)->getFill()->applyFromArray(
								 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
									 'startcolor' => array('rgb' => 'FF0000'),
									 'endcolor'   => array('argb' => 'FF0000')
								 )
						 );	
						$nombre_erreur = $nombre_erreur + 1;						
					}
					if($lien_de_parente=='') {
						$sheet->getStyle("N".$ligne)->getFill()->applyFromArray(
								 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
									 'startcolor' => array('rgb' => 'FF0000'),
									 'endcolor'   => array('argb' => 'FF0000')
								 )
						 );	
						$nombre_erreur = $nombre_erreur + 1;						
					}
					if($niveau_classe=='') {
						$sheet->getStyle("P".$ligne)->getFill()->applyFromArray(
								 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
									 'startcolor' => array('rgb' => 'FF0000'),
									 'endcolor'   => array('argb' => 'FF0000')
								 )
						 );	
						$nombre_erreur = $nombre_erreur + 1;						
					}
					if($langue=='') {
						$sheet->getStyle("Q".$ligne)->getFill()->applyFromArray(
								 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
									 'startcolor' => array('rgb' => 'FF0000'),
									 'endcolor'   => array('argb' => 'FF0000')
								 )
						 );	
						$nombre_erreur = $nombre_erreur + 1;						
					}
					if($revenu=='') {
						$sheet->getStyle("R".$ligne)->getFill()->applyFromArray(
								 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
									 'startcolor' => array('rgb' => 'FF0000'),
									 'endcolor'   => array('argb' => 'FF0000')
								 )
						 );	
						$nombre_erreur = $nombre_erreur + 1;						
					}
					if($depense=='') {
						$sheet->getStyle("S".$ligne)->getFill()->applyFromArray(
								 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
									 'startcolor' => array('rgb' => 'FF0000'),
									 'endcolor'   => array('argb' => 'FF0000')
								 )
						 );	
						$nombre_erreur = $nombre_erreur + 1;						
					}
					if($date_inscription=='') {
						$sheet->getStyle("T".$ligne)->getFill()->applyFromArray(
								 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
									 'startcolor' => array('rgb' => 'FF0000'),
									 'endcolor'   => array('argb' => 'FF0000')
								 )
						 );	
						$nombre_erreur = $nombre_erreur + 1;						
					}
					if($telephone=='') {
						$sheet->getStyle("U".$ligne)->getFill()->applyFromArray(
								 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
									 'startcolor' => array('rgb' => 'FF0000'),
									 'endcolor'   => array('argb' => 'FF0000')
								 )
						 );	
						$nombre_erreur = $nombre_erreur + 1;						
					}
					if($handicap_visuel=='') {
						$sheet->getStyle("V".$ligne)->getFill()->applyFromArray(
								 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
									 'startcolor' => array('rgb' => 'FF0000'),
									 'endcolor'   => array('argb' => 'FF0000')
								 )
						 );	
						$nombre_erreur = $nombre_erreur + 1;						
					}
					if($handicap_auditif=='') {
						$sheet->getStyle("W".$ligne)->getFill()->applyFromArray(
								 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
									 'startcolor' => array('rgb' => 'FF0000'),
									 'endcolor'   => array('argb' => 'FF0000')
								 )
						 );	
						$nombre_erreur = $nombre_erreur + 1;						
					}
					if($handicap_parole=='') {
						$sheet->getStyle("X".$ligne)->getFill()->applyFromArray(
								 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
									 'startcolor' => array('rgb' => 'FF0000'),
									 'endcolor'   => array('argb' => 'FF0000')
								 )
						 );	
						$nombre_erreur = $nombre_erreur + 1;						
					}
					if($handicap_moteur=='') {
						$sheet->getStyle("Y".$ligne)->getFill()->applyFromArray(
								 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
									 'startcolor' => array('rgb' => 'FF0000'),
									 'endcolor'   => array('argb' => 'FF0000')
								 )
						 );	
						$nombre_erreur = $nombre_erreur + 1;						
					}
					if($handicap_mental=='') {
						$sheet->getStyle("Z".$ligne)->getFill()->applyFromArray(
								 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
									 'startcolor' => array('rgb' => 'FF0000'),
									 'endcolor'   => array('argb' => 'FF0000')
								 )
						 );	
						$nombre_erreur = $nombre_erreur + 1;						
					}
					if($nom_enqueteur=='') {
						$sheet->getStyle("AA".$ligne)->getFill()->applyFromArray(
								 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
									 'startcolor' => array('rgb' => 'FF0000'),
									 'endcolor'   => array('argb' => 'FF0000')
								 )
						 );	
						$nombre_erreur = $nombre_erreur + 1;						
					}
					if($date_inscription=='') {
						$sheet->getStyle("AB".$ligne)->getFill()->applyFromArray(
								 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
									 'startcolor' => array('rgb' => 'FF0000'),
									 'endcolor'   => array('argb' => 'FF0000')
								 )
						 );	
						$nombre_erreur = $nombre_erreur + 1;						
					} */
				}	
				$ligne = $ligne + 1;
			}		
		}
		$date_inscription_beneficiaire = new DateTime($date_inscription_beneficiaire); 
		$date_inscription_beneficiaire =$date_inscription_beneficiaire->format('d/m/Y');				
		$val_ret = array();
			$sender = "ndrianaina.aime.bruno@gmail.com";
			$mdpsender = "finaritra";
		if($nombre_erreur > 0) {
			// Signaler les erreurs par mail
			$val_ret["reponse"] = "ERREUR";
			$val_ret["region"] = $nom_region_original;
			$val_ret["district"] = $nom_district_original;
			$val_ret["commune"] = $nom_commune_original;
			$val_ret["fokontany"] = $nom_fokontany_original;
			$val_ret["intervention"] = $intitule_intervention;
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
			$data["intervention"] = $intitule_intervention;
			$val_ret["date_inscription"] = $date_inscription_beneficiaire;
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
			$nombre_erreur=0; // compter le nombre d'erreur afin de pouvoir renvoyer le fichier à l'envoyeur
			$id_menage=null;
			foreach($rowIterator as $row) {
				// Contrôle détail s'il y a des doublons
				// Le controle se fait en 2 étapes
				// 1- Par identifiant_appariement
				// 2- Par nom,prenom CIN / Fokontany
				$beneficiaire_existant = false; // Si bénéficiaire déjà repertorié => Vérifier si déjà bénéficiaire de l'intervention =>Doublon
				$ligne = $row->getRowIndex ();
				if($ligne >=7) {
					// Contrôle de toutes les cellules à partir de la ligne 5
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
							$date_inscription = $cell->getValue();
							if(isset($date_inscription) && $date_inscription>"") {
								if(PHPExcel_Shared_Date::isDateTime($cell)) {
									 $date_inscription = date($format='Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($date_inscription)); 
								}
							} else {
								$date_inscription=null;
							}								 							 
						 }
					}
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
									$sheet->setCellValue("AC".$ligne, $code_region."-".$code_district."-".$code_commune."-".$code_fokontany."-".$identifiant_unique);
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
									$sheet->setCellValue("AC".$ligne, $code_region."-".$code_district."-".$code_commune."-".$code_fokontany."-".$identifiant_unique);
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
									// Doublon : ERREUR Marquage colonne AD par Doublon de couleur Jaune
									$sheet->getStyle("AD".$ligne)->getFill()->applyFromArray(
											 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
												 'startcolor' => array('rgb' => 'FFFF66'),
												 'endcolor'   => array('argb' => 'FFFF66')
											 )
									 );	
									$sheet->setCellValue("AD".$ligne, "Doublon : Déjà bénéficiaire de l'intervention");
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
									$sheet->setCellValue("AC".$ligne, $code_region."-".$code_district."-".$code_commune."-".$code_fokontany."-".$identifiant_unique);
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
								$sheet->setCellValue("AC".$ligne, $code_region."-".$code_district."-".$code_commune."-".$code_fokontany."-".$identifiant_unique);
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
								// Doublon : ERREUR Marquage colonne AD par Doublon de couleur Jaune
								$sheet->getStyle("AD".$ligne)->getFill()->applyFromArray(
										 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
											 'startcolor' => array('rgb' => 'FFFF66'),
											 'endcolor'   => array('argb' => 'FFFF66')
										 )
								 );	
								$sheet->setCellValue("AD".$ligne, "Doublon : Déjà bénéficiaire de l'intervention");
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
									$sheet->setCellValue("AC".$ligne, $code_region."-".$code_district."-".$code_commune."-".$code_fokontany."-".$identifiant_unique);
									$beneficiaire_existant=true;
									// Vérification si déjà bénéficiaire de l'intervention
									$nombre=0;
									$retour=$this->ValidationbeneficiaireManager->ControlerSiBeneficiaireIntervention($table_controle,$id_menage,$id_intervention);
									foreach($retour as $k=>$v) {
										 $nombre=$v->nombre;
									}
									// Bénéficiaire existant et bénéficie déjà de l'intervention =>ERREUR DOUBLON
									if($nombre >0) {
										// Doublon : ERREUR Marquage colonne AD par Doublon de couleur Jaune
										$sheet->getStyle("AD".$ligne)->getFill()->applyFromArray(
												 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
													 'startcolor' => array('rgb' => 'FFFF66'),
													 'endcolor'   => array('argb' => 'FFFF66')
												 )
										 );	
										$sheet->setCellValue("AD".$ligne, "Doublon : Déjà bénéficiaire de l'intervention");
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
									$sheet->setCellValue("AC".$ligne, $code_region."-".$code_district."-".$code_commune."-".$code_fokontany."-".$identifiant_unique);
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
								$sheet->setCellValue("AC".$ligne, $code_region."-".$code_district."-".$code_commune."-".$code_fokontany."-".$identifiant_unique);
								$beneficiaire_existant=true;
								// Vérification si déjà bénéficiaire de l'intervention
								$nombre=0;
								$retour=$this->ValidationbeneficiaireManager->ControlerSiBeneficiaireIntervention($table_controle,$id_individu,$id_intervention);
								foreach($retour as $k=>$v) {
									 $nombre=$v->nombre;
								}
								// Bénéficiaire existant et bénéficie déjà de l'intervention =>ERREUR DOUBLON
								if($nombre >0) {
									// Doublon : ERREUR Marquage colonne AD par Doublon de couleur Jaune
									$sheet->getStyle("AD".$ligne)->getFill()->applyFromArray(
											 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
												 'startcolor' => array('rgb' => 'FFFF66'),
												 'endcolor'   => array('argb' => 'FFFF66')
											 )
									 );	
									$sheet->setCellValue("AD".$ligne, "Doublon : Déjà bénéficiaire de l'intervention");
									$nombre_erreur = $nombre_erreur + 1;	
								}						
							}	 
						}							
					} 					
					if(null==$date_naissance) {
						// Calcul date_naissance par défaut
						$date_actuelle  = new DateTime();
						$annee_actuelle= $date_actuelle->format("Y");
						$sheet->setCellValue('I'.$ligne, $annee_actuelle);
						$age=intval($age);
						$date_par_defaut = $annee_actuelle."-01-01";	
						$date_par_defaut = new DateTime($date_par_defaut);
						$date_naissance = $date_par_defaut->sub(DateInterval::createFromDateString("'".$age." year'"));
						$date_naissance=$date_naissance->format("d/m/Y");
						$sheet->setCellValue('F'.$ligne, $date_naissance);
						$sheet->setCellValue('I3', $id_fokontany);
					}
				}	
				$ligne = $ligne + 1;
			}
			if($nombre_erreur > 0) {
				// Signaler les erreurs par mail
				$val_ret["reponse"] = "ERREUR";
				$val_ret["region"] = $nom_region_original;
				$val_ret["district"] = $nom_district_original;
				$val_ret["commune"] = $nom_commune_original;
				$val_ret["fokontany"] = $nom_fokontany_original;
				$val_ret["intervention"] = $intitule_intervention;
				$val_ret["date_inscription"] = $date_inscription_beneficiaire;
				$val_ret["nombre_erreur"] = $nombre_erreur;
				$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
				$objWriter->save(dirname(__FILE__) . "/../../../../" .$repertoire. $nomfichier);
				// Fermer fichier Excel
				unset($objWriter);
				// DEBUT ENVOI MAIL SIGNALANT LES ERREURS
				$data["type_fichier"] = " bénéficiaire ";
				$data["region"] = $nom_region_original;
				$data["district"] = $nom_district_original;
				$data["commune"] = $nom_commune_original;
				$data["fokontany"] = $nom_fokontany_original;
				$data["intervention"] = $intitule_intervention;
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
				$val_ret["intervention"] = $intitule_intervention;
				$val_ret["date_inscription"] = $date_inscription_beneficiaire;
				$val_ret["nombre_erreur"] = 0;	
				$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
				$objWriter->save(dirname(__FILE__) . "/../../../../" .$repertoire. $nomfichier);
			}
		}	
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
