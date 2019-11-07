<?php

defined('BASEPATH') OR exit('No direct script access allowed');
//harizo
// afaka fafana refa ts ilaina
// require APPPATH . '/libraries/REST_Controller.php';

class Validationintervention extends CI_Controller {
    public function __construct() {
        parent::__construct();
		// Modèle utilisés lor de la validation des données
        $this->load->model('validationintervention_model', 'ValidationinterventionManager');
        $this->load->model('region_model', 'RegionManager');
        $this->load->model('district_model', 'DistrictManager');
        $this->load->model('commune_model', 'CommuneManager');
        $this->load->model('fokontany_model', 'FokontanyManager');        
        $this->load->model('acteur_model', 'ActeurManager');        
        $this->load->model('intervention_model', 'InterventionManager');        
    }
	// Fonction qui récupère le fichier envoyé par l'acteur pour l'neregistrer dans le repertoire dédié dans le serveur
	// Structure repertoire validationdonnees/intervention/'nom_acteur'/'nom de fichier.sxlsx'
	public function upload_validationdonneesintervention() {	
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
			$retour = $this->controler_donnees_intervention($emplacement[1],$emplacement[2]);
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
	public function controler_donnees_intervention($filename,$directory) {	
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
							$date_intervention = $cell->getValue();
							if(isset($date_intervention) && $date_intervention>"") {
								if(PHPExcel_Shared_Date::isDateTime($cell)) {
									 $date_intervention = date($format='Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($date_intervention)); 
								}
							} else {
								$date_intervention=null;
							}	
						} else if('H' == $cell->getColumn()) {
							$menage_ou_individu = $cell->getValue();
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
							// id_intervention : à utliser plus tard pour vérifier si l'intervention se déroule dans le fokontany en question
							// sinon : paramétrage DDB à modifier et insérer le fokontany 
							$id_intervention=null;
							foreach($retour as $k=>$v) {
								$id_intervention=$v->id;
							}
						}
					}
					if(!$date_intervention) {
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
						if($menage_ou_individu=="ménage" || $menage_ou_individu=="menage") {
							$menage_ou_individu="ménage";
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
							$reg = $this->ValidationinterventionManager->selectionregion($nom_region);
						} else {
							$reg = $this->ValidationinterventionManager->selectionregionparid(5);
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
								$dis = $this->ValidationinterventionManager->selectiondistrict($nom_district,$id_region);
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
										$comm = $this->ValidationinterventionManager->selectioncommune($nom_commune,$id_district);
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
												$fkt = $this->ValidationinterventionManager->selectionfokontany($nom_fokontany,$id_commune);
												if(count($fkt) >0) {
													foreach($fkt as $indice=>$v) {
														// A utliser ultérieurement lors de la deuxième vérification : id_fokontany
														$id_fokontany = $v->id;
														$code_fokontany = $v->code;
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
							$identifiant_unique = $cell->getValue();
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
				}	
				$ligne = $ligne + 1;
			}		
		}
		$val_ret = array();
		// Fermer fichier Excel
			$sender = "ndrianaina.aime.bruno@gmail.com";
			$mdpsender = "finaritra";
		if($nombre_erreur > 0) {
			// Signaler les erreurs par mail
			$val_ret["reponse"] = "ERREUR";
			$val_ret["nombre_erreur"] = $nombre_erreur;
			$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
			$objWriter->save(dirname(__FILE__) . "/../../../../" .$repertoire. $nomfichier);
			// Fermer fichier Excel
			// unset($excel);
			// unset($objWriter);
			/*
			$sujet = 'Code de confirmation';
			$corps = $this->load->view('mail/activation.php', $data, true);
			$mail = new PHPMailer;
			$mail->isSMTP();
			$mail->Host = 'smtp.gmail.com';
			$mail->SMTPAuth = true;
			$mail->Username = $sender;
			$mail->Password = $mdpsender;
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
			$mail->addAddress($to);
			$mail->isHTML(true);
			$mail->Subject = $sujet;
			$mail->Body = $corps;
			if (!$mail->send()) {
				$data = 0;
			} else {
				$data = 1;
			}	*/		
		} else {
			// DEUXIEME VERIFICATION : vérification doublon			
			$nombre_erreur=0; // compter le nombre d'erreur afin de pouvoir renvoyer le fichier à l'envoyeur
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
			// Contrôler si doublon dans la table suivi_menage_entete ou suivi_individu_entete selon les cas
			$retour = $this->ValidationinterventionManager->RechercheDoublonInterventionParDateEtFokontany($menage_ou_individu,$date_intervention,$id_fokontany,$id_intervention);
			$nombre=0;
			foreach($retour as $k=>$v) {
				$nombre=$v->nombre;
			}
			if($nombre >0) {
				// Doublon : ERREUR
				$sheet->getStyle("G4")->getFill()->applyFromArray(
						 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
							 'startcolor' => array('rgb' => 'FF0000'),
							 'endcolor'   => array('argb' => 'FF0000')
						 )
				 );	
				 $sheet->setCellValue('G4', 'Fichier déjà importé dans la BDD ??? : Nécessite une vérification');
				$nombre_erreur = $nombre_erreur + 1;						
			}
			// get all the row of my file
			$rowIterator = $Excel->getActiveSheet()->getRowIterator();
			$premier=0; 
			$remplacer=array("'");
			$trouver= array("’");
			$remplacer=array('&eacute;','e','e','a','o','c','_');
			$trouver= array('é','è','ê','à','ö','ç',' ');
			foreach($rowIterator as $row) {
				// Contrôle détail s'il y a des doublons
				// Le controle se fait en 2 étapes
				// 1- Par identifiant_appariement
				// 2- Par nom,prenom CIN / Fokontany
				$ligne = $row->getRowIndex ();
				if($ligne >=5) {
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
							$identifiant_unique = $cell->getValue();
						 }
					}
					//$id_acteur
					// 1- Recherche par identifiant_appariement = $identifiant_appariement et $id_acteur stocké auparavant
					$retour=$this->ValidationinterventionManager->RechercheParIdentifiantActeur($identifiant_appariement,$id_acteur);
					$nombre=0;
					foreach($retour as $k=>$v) {
						$nombre = $v->nombre;
					}
					if($nombre ==0) {
						// Non trouvé continuer recherche
						// 2- Recherche par nom , prenom , CIN, id_fokontany , id_acteur
						// Recherche selon le cas : liste par ménage ou individu
						// De plus si la liste est ménage; il faut chercher dans la table menage si chef_menage = "O"
						// sinon recherche dans la table individu
						if($menage_ou_individu=="individu") {
							// Individu tout court
							$parametre_table="individu";
						} else if(strtolower($chef_menage) =="o") {
							// Si chef ménage
							$parametre_table="menage";
						} else {
							// Individu apprtenant à un ménage
							$parametre_table="individu_menage";
						}
						$retour=$this->ValidationinterventionManager->RechercheParNomPrenomCIN_Fokontany_Acteur($parametre_table,$identifiant_appariement,$id_acteur,$nom,$prenom,$cin,$id_fokontany);
						$nombre=0;
						foreach($retour as $k=>$v) {
							$nombre = $v->nombre;
						}
						if($nombre ==0) {
							 // Bénéficiaire introuvable : ERREUR Marquage colonne F par Bénéficiaire inexistant dans la BDD de couleur Jaune
							$sheet->getStyle("F".$ligne)->getFill()->applyFromArray(
									 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
										 'startcolor' => array('rgb' => 'FFFF66'),
										 'endcolor'   => array('argb' => 'FFFF66')
									 )
							 );	
							 $sheet->setCellValue('F'.$ligne, 'Bénéficiaire inexistant dans la BDD');
							$nombre_erreur = $nombre_erreur + 1;						
						}
					}
				}	
				$ligne = $ligne + 1;
			}
			// Vérification : si l'intervention se déroule bien dans le fokontany
			$retour=$this->ValidationinterventionManager->RechercheFokontanyIntervention($id_fokontany,$id_intervention);
			foreach($retour as $k=>$v) {
				$nombre = $v->nombre;
			}
			if($nombre==0) {
				$sheet->getStyle("G4")->getFill()->applyFromArray(
						 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
							 'startcolor' => array('rgb' => 'FF0000'),
							 'endcolor'   => array('argb' => 'FF0000')
						 )
				 );	
				 $sheet->setCellValue("G4", "L'intervention spécifié ne se déroule pas dans le fokontany");
				$nombre_erreur = $nombre_erreur + 1;										
			}
			if($nombre_erreur > 0) {
				// Signaler les erreurs par mail
				$val_ret["reponse"] = "ERREUR";
				$val_ret["nombre_erreur"] = $nombre_erreur;
				$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
				$objWriter->save(dirname(__FILE__) . "/../../../../" .$repertoire. $nomfichier);
				// Fermer fichier Excel
				unset($objWriter);
			} else {
				$val_ret["reponse"] = "OK";			
				$val_ret["nombre_erreur"] = 0;				
				$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
				$objWriter->save(dirname(__FILE__) . "/../../../../" .$repertoire. $nomfichier);
			}
		}	
		return ($val_ret);
	}	
} ?>	
