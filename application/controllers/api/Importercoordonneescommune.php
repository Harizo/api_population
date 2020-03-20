<?php

defined('BASEPATH') OR exit('No direct script access allowed');
//harizo
// afaka fafana refa ts ilaina
// require APPPATH . '/libraries/REST_Controller.php';

class Importercoordonneescommune extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('importercoordonneescommune_model', 'ImportercoordonneescommuneManager');
        $this->load->model('region_model', 'RegionManager');
        $this->load->model('district_model', 'DistrictManager');
        $this->load->model('commune_model', 'CommuneManager');
        $this->load->model('fokontany_model', 'FokontanyManager');        
        $this->load->model('validationbeneficiaire_model', 'ValidationbeneficiaireManager');        
    }
	public function save_upload_file() {	
		$erreur="aucun";
		$replace=array('e','e','e','a','o','c','_');
		$search= array('é','è','ê','à','ö','ç',' ');
		$repertoire= $_POST['repertoire'];

		$repertoire=str_replace($search,$replace,$repertoire);
		//The name of the directory that we need to create.
		$directoryName = dirname(__FILE__) ."/../../../../" .$repertoire;
		//Check if the directory already exists.
		if(!is_dir($directoryName)){
			//Directory does not exist, so lets create it.
			mkdir($directoryName, 0777,true);
		}				

		$emplacement=array();
		$emplacement[0]=dirname(__FILE__) ."/../../../../" .$repertoire;
		$config['upload_path']          = dirname(__FILE__) ."/../../../../".$repertoire;
		$config['allowed_types'] = 'gif|jpg|png|xls|xlsx|doc|docx|pdf';
		$config['max_size'] = 222048;
		$config['overwrite'] = TRUE;
		if (isset($_FILES['file']['tmp_name'])) {
			$name=$_FILES['file']['name'];
			$name1=str_replace($search,$replace,$name);
			$emplacement[1]=$name1;
			$emplacement[2]=$repertoire;
			$config['file_name'] = $name1;
			$this->load->library('upload', $config);
			$this->upload->initialize($config);
			$ff=$this->upload->do_upload('file');
		} else {
            echo 'File upload not found';
		} 
		echo json_encode($emplacement);
	}  
	public function importcoordonneescommune() {	
		require_once 'Classes/PHPExcel.php';
		require_once 'Classes/PHPExcel/IOFactory.php';
		set_time_limit(0);
		$repertoire= $_POST['repertoire'];
		$nomfichier= $_POST['nom_fichier'];
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
		$remplacer=array('e','e','e','a','o','c');
		$trouver= array("é","è","ê","à","ö","ç");		
		foreach($rowIterator as $row) {
			$ligne = $row->getRowIndex ();
			if($ligne >=2) {
				// Contrôle découpage administratif
				 $cellIterator = $row->getCellIterator();
				 // Loop all cells, even if it is not set
				 $cellIterator->setIterateOnlyExistingCells(false);
				 $rowIndex = $row->getRowIndex ();
				 $a_inserer =0;
				foreach ($cellIterator as $cell) {
					if('D' == $cell->getColumn()) {
						$nom_region = $cell->getValue();
						$nom_region_original = $cell->getValue();
					 } else if('F' == $cell->getColumn()) {
						$nom_district = $cell->getValue();
						$nom_district_original = $cell->getValue();
					 } else if('H' == $cell->getColumn()) {
							$nom_commune =$cell->getValue();	
							$nom_commune_original =$cell->getValue();	
					 }	else if('B' == $cell->getColumn()) {
							$coordonnees =$cell->getValue();
					 }
				}
				// Controle region,district,commune : si tout est ok =>
				$amoron_mania=false;
				$nom_commune = strtolower($nom_commune);
				$nom_district = strtolower($nom_district);
				$nom_region = strtolower($nom_region);
				$x= strpos($nom_region,'mania');
				if($x > 0) {
					$amoron_mania=true;
				} else {
					$amoron_mania=false;
				}
				$nom_commune=str_replace($trouver,$remplacer,$nom_commune);
				$nom_district=str_replace($trouver,$remplacer,$nom_district);
				$nom_region=str_replace($trouver,$remplacer,$nom_region);
				$matsiatra=false;
				if($nom_region=="haute matsiatra") {
					$matsiatra=true;
				}
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
				$erreur_nom_commune = 0;
				$erreur_nom_district =0; 
				$erreur_nom_region =0; 
				$reg=array();
				$place_espace = strpos($nom_region," ");
				$place_apostrophe = strpos($nom_region,"'");
					if($nom_region >'') {
						if($amoron_mania==false) {
							if($place_espace >0) {
								$region_temporaire1 = substr ( $nom_region , 0 ,($place_espace - 1));
								$region_temporaire2 = substr ( $nom_region , ($place_espace + 1));
								$reg = $this->ImportercoordonneescommuneManager->selectionregion_avec_espace($region_temporaire1,$region_temporaire2);
							} else if($place_apostrophe >0) {
								$region_temporaire1 = substr ( $nom_region , 0 ,($place_apostrophe - 1));
								$region_temporaire2 = substr ( $nom_region , ($place_apostrophe + 1));
							} else {	
								$reg = $this->ImportercoordonneescommuneManager->selectionregion($nom_region);
							}	
						} else {
							$reg = $this->ImportercoordonneescommuneManager->selectionregionparid(6);
						}	
						if(count($reg) >0) {
							foreach($reg as $indice=>$v) {
								$id_region = $v->id;
								$code_region=$v->code;
							} 						
						} else {
							// Pas de région : marquer tous les découpages administratif 
							$sheet->getStyle("D".$ligne)->getFill()->applyFromArray(
									 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
										 'startcolor' => array('rgb' => 'FF0000'),
										 'endcolor'   => array('argb' => 'FF0000')
									 )
							 );	
							$sheet->getStyle("F".$ligne)->getFill()->applyFromArray(
									 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
										 'startcolor' => array('rgb' => 'FF0000'),
										 'endcolor'   => array('argb' => 'FF0000')
									 )
							 );	
							$sheet->getStyle("H".$ligne)->getFill()->applyFromArray(
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
									$dis = $this->ImportercoordonneescommuneManager->selectiondistrict_avec_espace($district_temporaire1,$district_temporaire2,$id_region);
								} else if($place_apostrophe >0) {
									$district_temporaire1 = substr ( $nom_district , 0 ,($place_apostrophe - 1));
									$district_temporaire2 = substr ( $nom_district , ($place_apostrophe + 1));
									$dis = $this->ImportercoordonneescommuneManager->selectiondistrict_avec_espace($district_temporaire1,$district_temporaire2,$id_region);
								} else {
									$dis = $this->ImportercoordonneescommuneManager->selectiondistrict($nom_district,$id_region);
								}	
								if(count($dis) >0) {
									foreach($dis as $indice=>$v) {
										$id_district = $v->id;
										$codedistrict= $v->code;
									}
								} else {
									// Pas de district : marquer district,commune,fokontany 
									$sheet->getStyle("F".$ligne)->getFill()->applyFromArray(
											 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
												 'startcolor' => array('rgb' => 'FF0000'),
												 'endcolor'   => array('argb' => 'FF0000')
											 )
									 );	
									$sheet->getStyle("H".$ligne)->getFill()->applyFromArray(
											 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
												 'startcolor' => array('rgb' => 'FF0000'),
												 'endcolor'   => array('argb' => 'FF0000')
											 )
									 );	
									$nombre_erreur = $nombre_erreur + 1;	
									$erreur_nom_district = $erreur_nom_district + 1;	
								}
								if($matsiatra==true) {
									if($nom_district=="ambalavao") {
										$id_district=24;
									}
									if($nom_district=="ambohimahasoa") {
										$id_district=27;
									}
									if($nom_district=="fianarantsoa i") {
										$id_district=20;
									}
									if($nom_district=="fianarantsoa ii") {
										$id_district=39;
									}
									if($nom_district=="ikalamavony") {
										$id_district=38;
									}
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
											////// Mise à jour coordonnées géographique
											$sheet->setCellValue("I".$ligne, $id_region);
											$sheet->setCellValue("J".$ligne, $id_district);
											$sheet->setCellValue("K".$ligne, $id_commune);																						
										} else {
											// Pas de commune : marquer commune,fokontany 
											$sheet->getStyle("H".$ligne)->getFill()->applyFromArray(
													 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
														 'startcolor' => array('rgb' => 'FF0000'),
														 'endcolor'   => array('argb' => 'FF0000')
													 )
											 );	
											$nombre_erreur = $nombre_erreur + 1;
											$erreur_nom_commune = $erreur_nom_commune + 1;	
											$sheet->setCellValue("I".$ligne, $id_region);
											$sheet->setCellValue("J".$ligne, $id_district);
										}	
									} else {										
										// Pas de commune : marquer commune,fokontany 
										$sheet->getStyle("H".$ligne)->getFill()->applyFromArray(
												 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
													 'startcolor' => array('rgb' => 'FF0000'),
													 'endcolor'   => array('argb' => 'FF0000')
												 )
										 );	
										$nombre_erreur = $nombre_erreur + 1;
										$erreur_nom_commune = $erreur_nom_commune + 1;	
									}										}
							} else {
								// Pas de district : marquer district,commune,fokontany 
								$sheet->getStyle("F".$ligne)->getFill()->applyFromArray(
										 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
											 'startcolor' => array('rgb' => 'FF0000'),
											 'endcolor'   => array('argb' => 'FF0000')
										 )
								 );	
								$sheet->getStyle("H".$ligne)->getFill()->applyFromArray(
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
						$sheet->getStyle("D".$ligne)->getFill()->applyFromArray(
								 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
									 'startcolor' => array('rgb' => 'FF0000'),
									 'endcolor'   => array('argb' => 'FF0000')
								 )
						 );	
						$sheet->getStyle("F".$ligne)->getFill()->applyFromArray(
								 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
									 'startcolor' => array('rgb' => 'FF0000'),
									 'endcolor'   => array('argb' => 'FF0000')
								 )
						 );	
						$sheet->getStyle("H".$ligne)->getFill()->applyFromArray(
								 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
									 'startcolor' => array('rgb' => 'FF0000'),
									 'endcolor'   => array('argb' => 'FF0000')
								 )
						 );	
						$nombre_erreur = $nombre_erreur + 1;
						$erreur_nom_region = $erreur_nom_region + 1;	
					}
				$ligne = $ligne + 1;
			}		
		}
		$val_ret = array();
		// Fermer fichier Excel
		$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$objWriter->save(dirname(__FILE__) . "/../../../../" .$repertoire. $nomfichier);
		unset($excel);
		unset($objWriter);
		// Mise à jour liste validation bénéficiaire
		if($nombre_erreur > 0) {
			// Signaler les erreurs par mail
			$val_ret["reponse"] = "ERREUR";
			$val_ret["region"] = $nom_region_original;
			$val_ret["district"] = $nom_district_original;
			$val_ret["commune"] = $nom_commune_original;
			$val_ret["nombre_erreur"] = $nombre_erreur;
			// Fermer fichier Excel
		} else {
			$val_ret["reponse"] = "OK";
			$val_ret["region"] = $nom_region_original;
			$val_ret["district"] = $nom_district_original;
			$val_ret["commune"] = $nom_commune_original;
			$val_ret["nombre_erreur"] = 0;			
		}
		echo json_encode($val_ret);
	}
	public function Mise_a_jour_coordonnees() {
		$trouver= array(")");		
		$remplacer=array('');
		$les_communes = $this->CommuneManager->FindAll();
		$nombre_miseajour=0;
		foreach($les_communes as $k=>$v) {
			$id_commune = $v->id;
			if($id_commune==240 || $id_commune==21) { // Pour test
				$liste_coordonees=$this->ImportercoordonneescommuneManager->selection_coordonnees_commune($id_commune);
				$coordonnees_text="";
				$resultat_coordonnees=array();
			if($liste_coordonees) {	
				foreach($liste_coordonees as $key=>$value) {
					$coordonnees=$value->coordonnees;
					$longueur = strlen($coordonnees);
					$position_truncated=strpos($coordonnees,"TRUNCATED");
					if($position_truncated >0) {
						$coordonnees_text=substr($coordonnees,31);
					} else {
						$coordonnees_text=substr($coordonnees,16);
					}
					// Remplacer ')' par ''
					$coordonnees_text=str_replace($trouver,$remplacer,$coordonnees_text);
					// Transformer en tableau
					$coordonnees_text=explode(",",$coordonnees_text);
					foreach($coordonnees_text as $indice=>$valeur) {
						// Eclater chaque élément en 2 parties : latitude et longitude
						$position_espace=strpos($valeur,"-");
						$longitude=substr($valeur,0,($position_espace - 1));
						$latitude=substr($valeur,($position_espace));
						$valeur_temp=array();
						$valeur_temp["latitude"]=$latitude;
						$valeur_temp["longitude"]=$longitude;						
						$resultat_coordonnees[]=$valeur_temp;
					}
					$miseajour=$this->ImportercoordonneescommuneManager->miseajour_coordonnees_commune($id_commune,serialize($resultat_coordonnees));
					if($miseajour) {
						$nombre_miseajour=$nombre_miseajour + 1;
					}
				}
			}	
			}	
		}
		echo json_encode($nombre_miseajour);
	}
} ?>	
