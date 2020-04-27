<?php

defined('BASEPATH') OR exit('No direct script access allowed');
//harizo
// afaka fafana refa ts ilaina
// require APPPATH . '/libraries/REST_Controller.php';

class Importerdecoupageadministratif extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('importerdecoupageadministratif_model', 'ImporterdecoupageadministratifManager');
        $this->load->model('region_model', 'RegionManager');
        $this->load->model('district_model', 'DistrictManager');
        $this->load->model('commune_model', 'CommuneManager');
        $this->load->model('fokontany_model', 'FokontanyManager');        
        $this->load->model('enquete_menage_model', 'EnquetemenageManager');        
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
	public function importfichierdecoupageadministratifregion() {	
		require_once 'Classes/PHPExcel.php';
		require_once 'Classes/PHPExcel/IOFactory.php';
		set_time_limit(0);
		$replace=array('e','e','e','a','o','c','_');
		$search= array('é','è','ê','à','ö','ç',' ');
		$repertoire= "importdecoupage/";
		$nomfichier = "region.xlsx";		
		$repertoire=str_replace($search,$replace,$repertoire);
		//The name of the directory that we need to create.
		$directoryName = dirname(__FILE__) ."/../../../../" .$repertoire;
		//Check if the directory already exists.
		if(!is_dir($directoryName)){
			//Directory does not exist, so lets create it.
			mkdir($directoryName, 0777,true);
		}				
	
		$chemin=dirname(__FILE__) . "/../../../../".$repertoire;
		// $nomfichier = 'fokontany.xlsx';
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
		$erreur="";
		$region_inserer="";
		$district_inserer="";
		$commune_inserer="";
		$premier=0; 
		$remplacer=array("'");
		$trouver= array("’");
		$remplacer=array('&eacute;','e','e','a','o','c','_');
		$trouver= array('é','è','ê','à','ö','ç',' ');
		foreach($rowIterator as $row) {
			 $ligne = $row->getRowIndex ();
			if($ligne >=2) {
				 $cellIterator = $row->getCellIterator();
				 // Loop all cells, even if it is not set
				 $cellIterator->setIterateOnlyExistingCells(false);
				 $rowIndex = $row->getRowIndex ();
				 $a_inserer =0;
				foreach ($cellIterator as $cell) {
					if('A' == $cell->getColumn()) {
						$code_region =$cell->getValue();
					} else if('C' == $cell->getColumn()) {
							$nom_region =$cell->getValue();
							$nom_region_original =$cell->getValue();
					} else if('G' == $cell->getColumn()) {
							$chef_lieu =$cell->getValue();	
					} 
				}
				$amoron_mania=false;
				$nom_region = strtolower($nom_region);
				$x= strpos($nom_region,'mania');
				if($x > 0) {
					$amoron_mania=true;
				} else {
					$amoron_mania=false;
				}
				$nom_region=str_replace($trouver,$remplacer,$nom_region);
				$region_ok = false;
				$insert_region=false;
				$id_region=null;
				$reg=array();
				if($code_region >'') {
					// Selection region par code
					if($amoron_mania==false) {
						$reg = $this->ImporterdecoupageadministratifManager->selectionregion($code_region);
					} else {
						// Selection region par id
						$reg = $this->ImporterdecoupageadministratifManager->selectionregionparid("ania");
					}	
					if(count($reg) >0) {
						foreach($reg as $k=>$v) {
							$id_region = $v->id;
							$code_region=$v->code;
						} 						
					} else {
						$insert_region=true;
						$data = array(
							'code' => $code_region,
							'nom' => $nom_region_original,
							'surface' => null,
							'chef_lieu' => $chef_lieu,
						);  
						// Ajour d'une région
						$id_region = $this->RegionManager->addImport($data);	
					}	
				} 
				if(intval($id_region) >0) {
					$sheet->setCellValue('I'.$ligne, $id_region);						
				}
				if($region_ok==true) {
					if($insert_region==true) {
						$sheet->setCellValue('N'.$ligne, 'Ins');	
						$sheet->getStyle('K'.$ligne)->getFill()->applyFromArray(
								 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
									 'startcolor' => array('rgb' => '60E7B8'),
									 'endcolor'   => array('argb' => '60E7B8')
								 )
						 );	
						$sheet->getStyle('K'.$ligne)->applyFromArray(array(
							'font'  => array(
								'bold'  => true,
								'color' => array('rgb' => '000000'),
								'size'  => 11,
								'name'  => 'Verdana'
							))
						);											
					}
				}
			}		
		}
		$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$objWriter->save(dirname(__FILE__) . "/../../../../" .$repertoire. $nomfichier);
		$region = $this->RegionManager->findAll();
		$data=array();
		if ($region) {
			foreach ($region as $key => $value) {
				$data[$key]['id'] = $value->id;
				$data[$key]['code'] = $value->code;
				$data[$key]['nom'] = $value->nom;
				$data[$key]['chef_lieu'] = $value->chef_lieu;
			};
		}
		$val_ret = array();
		if($erreur > "") {
			$val_ret["reponse"] = "ERREUR";
			$val_ret["erreur"] = $erreur;
			$val_ret["donnees"] = $data;
		} else {
			$val_ret["reponse"] = "OK";			
			$val_ret["erreur"] = "";			
			$val_ret["donnees"] = $data;
		}	
			echo json_encode($val_ret);
	}	
	public function importfichierdecoupageadministratifdistrict() {	
		require_once 'Classes/PHPExcel.php';
		require_once 'Classes/PHPExcel/IOFactory.php';
		set_time_limit(0);
		$replace=array('e','e','e','a','o','c','_');
		$search= array('é','è','ê','à','ö','ç',' ');
		$repertoire= "importdecoupage/";
		$nomfichier = "district.xlsx";		
		$repertoire=str_replace($search,$replace,$repertoire);
		//The name of the directory that we need to create.
		$directoryName = dirname(__FILE__) ."/../../../../" .$repertoire;
		//Check if the directory already exists.
		if(!is_dir($directoryName)){
			//Directory does not exist, so lets create it.
			mkdir($directoryName, 0777,true);
		}				
	
		$chemin=dirname(__FILE__) . "/../../../../".$repertoire;
		// $nomfichier = 'fokontany.xlsx';
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
		$erreur="";
		$region_inserer="";
		$district_inserer="";
		$commune_inserer="";
		$premier=0; 
		$remplacer=array("'");
		$trouver= array("’");
		$remplacer=array('&eacute;','e','e','a','o','c','_');
		$trouver= array('é','è','ê','à','ö','ç',' ');
		foreach($rowIterator as $row) {
			 $ligne = $row->getRowIndex ();
			if($ligne >=2) {
				 $cellIterator = $row->getCellIterator();
				 // Loop all cells, even if it is not set
				 $cellIterator->setIterateOnlyExistingCells(false);
				 $rowIndex = $row->getRowIndex ();
				 $a_inserer =0;
				foreach ($cellIterator as $cell) {
					if('A' == $cell->getColumn()) {
						$code_district =$cell->getValue();
					} else if('C' == $cell->getColumn()) {
						$code_region =$cell->getValue();
						$code_region_original =$cell->getValue();
					} else if('B' == $cell->getColumn()) {
						$nom_district =$cell->getValue();	
						$nom_district_original =$cell->getValue();	
					} 
				}
				$nom_district = strtolower($nom_district);
				$x= strpos($nom_district,'mania');
				$nom_district=str_replace($trouver,$remplacer,$nom_district);
				$region_ok = false;
				$insert_region=false;
				$id_district=null;
				$region_id=null;
				$reg=array();
				if($nom_district >'') {
						// Récupération region_id correspondant
						$reg = $this->ImporterdecoupageadministratifManager->selectionregionparcode($code_region);
					if(count($reg) >0) {
						foreach($reg as $k=>$v) {
							$region_id = $v->id;
							$code_region=$v->code;
						} 
						$dist = $this->ImporterdecoupageadministratifManager->selectiondistrictparcode($code_district);
						// District inexistant => ajouter sinon RIEN
						if(!$dist) {	
							$data = array(
								'code' => $code_district,
								'nom' => $nom_district_original,
								'region_id' => $region_id,
							);  
							$id_district = $this->DistrictManager->add($data);	
						}	
					}	
				} 
				if(intval($id_district) >0) {
					$sheet->setCellValue('I'.$ligne, $id_district);						
				}
				if($region_ok==true) {
					if($insert_region==true) {
						$sheet->setCellValue('F'.$ligne, 'Ins');	
						$sheet->getStyle('G'.$ligne)->getFill()->applyFromArray(
								 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
									 'startcolor' => array('rgb' => '60E7B8'),
									 'endcolor'   => array('argb' => '60E7B8')
								 )
						 );	
						$sheet->getStyle('G'.$ligne)->applyFromArray(array(
							'font'  => array(
								'bold'  => true,
								'color' => array('rgb' => '000000'),
								'size'  => 11,
								'name'  => 'Verdana'
							))
						);											
					}
				}
			}		
		}
		$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$objWriter->save(dirname(__FILE__) . "/../../../../" .$repertoire. $nomfichier);
		$district = $this->DistrictManager->findAll();
		$data=array();
		if ($district) {
			foreach ($district as $key => $value) {
				$data[$key]['id'] = $value->id;
				$data[$key]['code'] = $value->code;
				$data[$key]['nom'] = $value->nom;
			};
		}
		$val_ret = array();
		if($erreur > "") {
			$val_ret["reponse"] = "ERREUR";
			$val_ret["erreur"] = $erreur;
			$val_ret["donnees"] = $data;
		} else {
			$val_ret["reponse"] = "OK";			
			$val_ret["erreur"] = "";			
			$val_ret["donnees"] = $data;
		}	
			echo json_encode($val_ret);
	}	
	public function importfichierdecoupageadministratifcommune() {	
		require_once 'Classes/PHPExcel.php';
		require_once 'Classes/PHPExcel/IOFactory.php';
		set_time_limit(0);
		$replace=array('e','e','e','a','o','c','_');
		$search= array('é','è','ê','à','ö','ç',' ');
		$repertoire= "importdecoupage/";
		$nomfichier = "commune.xlsx";		
		$repertoire=str_replace($search,$replace,$repertoire);
		//The name of the directory that we need to create.
		$directoryName = dirname(__FILE__) ."/../../../../" .$repertoire;
		//Check if the directory already exists.
		if(!is_dir($directoryName)){
			//Directory does not exist, so lets create it.
			mkdir($directoryName, 0777,true);
		}				
	
		$chemin=dirname(__FILE__) . "/../../../../".$repertoire;
		// $nomfichier = 'fokontany.xlsx';
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
		$erreur="";
		$region_inserer="";
		$district_inserer="";
		$commune_inserer="";
		$premier=0; 
		$remplacer=array("'");
		$trouver= array("’");
		$remplacer=array('&eacute;','e','e','a','o','c','_');
		$trouver= array('é','è','ê','à','ö','ç',' ');
		foreach($rowIterator as $row) {
			 $ligne = $row->getRowIndex ();
			if($ligne >=2) {
				 $cellIterator = $row->getCellIterator();
				 // Loop all cells, even if it is not set
				 $cellIterator->setIterateOnlyExistingCells(false);
				 $rowIndex = $row->getRowIndex ();
				 $a_inserer =0;
				foreach ($cellIterator as $cell) {
					if('A' == $cell->getColumn()) {
						$code_commune =$cell->getValue();
					} else if('C' == $cell->getColumn()) {
						$code_district =$cell->getValue();
					} else if('B' == $cell->getColumn()) {
						$nom_commune =$cell->getValue();	
						$nom_commune_original =$cell->getValue();	
					} 
				}
				$nom_commune = strtolower($nom_commune);
				$x= strpos($nom_commune,'mania');
				$nom_commune=str_replace($trouver,$remplacer,$nom_commune);
				$region_ok = false;
				$insert_region=false;
				$district_id=null;
				$region_id=null;
				$dist=array();
				if($nom_commune >'') {
						// Récupération district_id correspondant
						$dist = $this->ImporterdecoupageadministratifManager->selectiondistrictparcode($code_district);
					if(count($dist) >0) {
						foreach($dist as $k=>$v) {
							$district_id = $v->id;
							$code_district=$v->code;
						} 
						$comm = $this->ImporterdecoupageadministratifManager->selectioncommuneparcode($code_commune);
						// Commune inexistant => ajouter sinon RIEN
						if(!$comm) {	
							$data = array(
								'code' => $code_commune,
								'nom' => $nom_commune_original,
								'district_id' => $district_id,
								'coordonnees' => null,
							);  
							$id_commune = $this->CommuneManager->add($data);	
						}	
					}	
				} 
				if(intval($id_commune) >0) {
					$sheet->setCellValue('I'.$ligne, $id_commune);						
				}
				if($region_ok==true) {
					if($insert_region==true) {
						$sheet->setCellValue('F'.$ligne, 'Ins');	
						$sheet->getStyle('G'.$ligne)->getFill()->applyFromArray(
								 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
									 'startcolor' => array('rgb' => '60E7B8'),
									 'endcolor'   => array('argb' => '60E7B8')
								 )
						 );	
						$sheet->getStyle('G'.$ligne)->applyFromArray(array(
							'font'  => array(
								'bold'  => true,
								'color' => array('rgb' => '000000'),
								'size'  => 11,
								'name'  => 'Verdana'
							))
						);											
					}
				}
			}		
		}
		$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$objWriter->save(dirname(__FILE__) . "/../../../../" .$repertoire. $nomfichier);
		$commune = $this->CommuneManager->findAll();
		$data=array();
		if ($commune) {
			foreach ($commune as $key => $value) {
				$data[$key]['id'] = $value->id;
				$data[$key]['code'] = $value->code;
				$data[$key]['nom'] = $value->nom;
			};
		}
		$val_ret = array();
		if($erreur > "") {
			$val_ret["reponse"] = "ERREUR";
			$val_ret["erreur"] = $erreur;
			$val_ret["donnees"] = $data;
		} else {
			$val_ret["reponse"] = "OK";			
			$val_ret["erreur"] = "";			
			$val_ret["donnees"] = $data;
		}	
			echo json_encode($val_ret);
	}	
	public function importfichierdecoupageadministratiffokontany() {	
		require_once 'Classes/PHPExcel.php';
		require_once 'Classes/PHPExcel/IOFactory.php';
		set_time_limit(0);
		$replace=array('e','e','e','a','o','c','_');
		$search= array('é','è','ê','à','ö','ç',' ');
		$repertoire= "importdecoupage/";
		$nomfichier = "fokontany.xlsx";		
		$repertoire=str_replace($search,$replace,$repertoire);
		//The name of the directory that we need to create.
		$directoryName = dirname(__FILE__) ."/../../../../" .$repertoire;
		//Check if the directory already exists.
		if(!is_dir($directoryName)){
			//Directory does not exist, so lets create it.
			mkdir($directoryName, 0777,true);
		}				
	
		$chemin=dirname(__FILE__) . "/../../../../".$repertoire;
		// $nomfichier = 'fokontany.xlsx';
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
		$erreur="";
		$region_inserer="";
		$district_inserer="";
		$commune_inserer="";
		$premier=0; 
		$remplacer=array("'");
		$trouver= array("’");
		$remplacer=array('&eacute;','e','e','a','o','c','_');
		$trouver= array('é','è','ê','à','ö','ç',' ');
		foreach($rowIterator as $row) {
			 $ligne = $row->getRowIndex ();
			if($ligne >=2) {
				 $cellIterator = $row->getCellIterator();
				 // Loop all cells, even if it is not set
				 $cellIterator->setIterateOnlyExistingCells(false);
				 $rowIndex = $row->getRowIndex ();
				 $a_inserer =0;
				foreach ($cellIterator as $cell) {
					if('A' == $cell->getColumn()) {
						$code_fokontany =$cell->getValue();
					} else if('E' == $cell->getColumn()) {
						$code_commune =$cell->getValue();
					} else if('F' == $cell->getColumn()) {
						$nom_fokontany =$cell->getValue();	
						$nom_fokontany_original =$cell->getValue();	
					} 
				}
				$nom_fokontany = strtolower($nom_fokontany);
				$x= strpos($nom_fokontany,'mania');
				$nom_fokontany=str_replace($trouver,$remplacer,$nom_fokontany);
				$region_ok = false;
				$insert_region=false;
				$id_commune=null;
				$id_fokontany=null;
				$dist=array();
				if($nom_fokontany >'') {
						// Récupération id_commune correspondant
						$comm = $this->ImporterdecoupageadministratifManager->selectioncommuneparcode($code_commune);
					if(count($comm) >0) {
						foreach($comm as $k=>$v) {
							$id_commune = $v->id;
							$code_commune=$v->code;
						} 
						$fkt = $this->ImporterdecoupageadministratifManager->selectionfokontanyparcode($code_fokontany);
						// Fokontany inexistant => ajouter sinon RIEN
						if(!$fkt) {	
							$data = array(
								'code' => $code_fokontany,
								'nom' => $nom_fokontany_original,
								'id_commune' => $id_commune,
							);  
							$id_fokontany = $this->FokontanyManager->add($data);	
						}	
					}	
				} 
				if(intval($id_fokontany) >0) {
					$sheet->setCellValue('I'.$ligne, $id_fokontany);						
				}
				if($region_ok==true) {
					if($insert_region==true) {
						$sheet->setCellValue('F'.$ligne, 'Ins');	
						$sheet->getStyle('G'.$ligne)->getFill()->applyFromArray(
								 array('type'       => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0,
									 'startcolor' => array('rgb' => '60E7B8'),
									 'endcolor'   => array('argb' => '60E7B8')
								 )
						 );	
						$sheet->getStyle('G'.$ligne)->applyFromArray(array(
							'font'  => array(
								'bold'  => true,
								'color' => array('rgb' => '000000'),
								'size'  => 11,
								'name'  => 'Verdana'
							))
						);											
					}
				}
			}		
		}
		$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$objWriter->save(dirname(__FILE__) . "/../../../../" .$repertoire. $nomfichier);
		$fokontany = $this->FokontanyManager->findAll();
		$data=array();
		if ($fokontany) {
			$data=$fokontany;
		}
		$val_ret = array();
		if($erreur > "") {
			$val_ret["reponse"] = "ERREUR";
			$val_ret["erreur"] = $erreur;
			$val_ret["donnees"] = $data;
		} else {
			$val_ret["reponse"] = "OK";			
			$val_ret["erreur"] = "";			
			$val_ret["donnees"] = $data;
		}	
			echo json_encode($val_ret);
	}	
	public function exportdecoupage() {	
		require_once 'Classes/PHPExcel.php';
		require_once 'Classes/PHPExcel/IOFactory.php';
		ini_set('memory_limit', '1024M');
		set_time_limit(0);
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
		// DEBUT EXPORT DECOUPAGE ADMINISTRATIF
		$liste_region = $this->ImporterdecoupageadministratifManager->Recuperer_Region();
		$liste_district = $this->ImporterdecoupageadministratifManager->Recuperer_District();
		$liste_commune = $this->ImporterdecoupageadministratifManager->Recuperer_Commune();
		$liste_fokontany = $this->ImporterdecoupageadministratifManager->Recuperer_Fokontany();
		$chemin=dirname(__FILE__) . "/../../../../".$repertoire;
		
		if (count($liste_region) >0) {
			$objPHPExcel = new PHPExcel();
			$objPHPExcel->getProperties()->setCreator("ASTRUM")
								 ->setLastModifiedBy("ASTRUM")
								 ->setTitle("Export découpage administratif (ASTRUM)")
								 ->setSubject("Export découpage administratif (ASTRUM)")
								 ->setDescription("Export découpage administratif (ASTRUM)")
								 ->setKeywords("Export découpage administratif (ASTRUM)")
								 ->setCategory("Export découpage administratif (ASTRUM)");
			$objRichText = new PHPExcel_RichText();
			$objRichText->createText('N&deg; ');
			// Début export Liste région
			$objPHPExcel->setActiveSheetIndex(0);
			$objPHPExcel->getActiveSheet()->setTitle('Région');
			$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(40);
			$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(40);
			$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT)	;		
			$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(25);
			$objPHPExcel->getActiveSheet()->getStyle("A1:P1")->getFont()->setSize(14);			
			$objPHPExcel->getActiveSheet()->getStyle('A1:P1')->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getPageSetup()->setHorizontalCentered(true);
			$objPHPExcel->getActiveSheet()->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(1, 1);
			$objPHPExcel->getActiveSheet()->getPageMargins()->setTop(0.4);
			$objPHPExcel->getActiveSheet()->getPageMargins()->setRight(0.2);
			$objPHPExcel->getActiveSheet()->getPageMargins()->setLeft(0.2);
			$objPHPExcel->getActiveSheet()->getPageMargins()->setBottom(0.5);
			$objPHPExcel->getActiveSheet()->getPageSetup()->setScale(100); // % impression : 10% à 400%
			$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&L&11&B EXPORT DECOUPAGE ADMINISTRATIF (ASTRUM) &R&11&B Page &P / &N');
			$objPHPExcel->getActiveSheet()->getHeaderFooter()->setEvenFooter('&L&11&B EXPORT DECOUPAGE ADMINISTRATIF (ASTRUM) &R&11&B Page &P / &N');
			$styleArray = array(
			  'borders' => array(
				'allborders' => array(
				  'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			  )
			);		
			$objPHPExcel->getActiveSheet()->setCellValue('A1', 'code');
			$objPHPExcel->getActiveSheet()->setCellValue('B1', 'nom');
			$objPHPExcel->getActiveSheet()->setCellValue('C1', 'chef_lieu');
			$objPHPExcel->getActiveSheet()->getStyle('A1:C1')->applyFromArray($styleArray);
			$objPHPExcel->getActiveSheet()->getStyle('A1:C1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle('A1:C1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$ligne=2;
			foreach ($liste_region as $k=>$v) {
				$objPHPExcel->getActiveSheet()->setCellValueExplicit('A'.$ligne, $v->code, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet()->setCellValueExplicit('B'.$ligne, $v->nom, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet()->setCellValueExplicit('C'.$ligne, $v->chef_lieu, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet()->getStyle('A'.$ligne.':C'.$ligne)->applyFromArray($styleArray);
				$objPHPExcel->getActiveSheet()->getStyle('A'.$ligne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getStyle('A'.$ligne.':C'.$ligne)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":C".$ligne)->getAlignment()->setWrapText(true);				
				$ligne=$ligne + 1;			
			}
			// Fin export Liste région
			// Début export Liste district
			$objWorkSheet = $objPHPExcel->createSheet(1);
			$objPHPExcel->setActiveSheetIndex(1);
			$objPHPExcel->getActiveSheet()->setTitle('District');
			$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(14);
			$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(40);
			$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT)	;		
			$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(25);
			$objPHPExcel->getActiveSheet()->getStyle("A1:P1")->getFont()->setSize(14);			
			$objPHPExcel->getActiveSheet()->getStyle('A1:P1')->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getPageSetup()->setHorizontalCentered(true);
			$objPHPExcel->getActiveSheet()->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(1, 1);
			$objPHPExcel->getActiveSheet()->getPageMargins()->setTop(0.4);
			$objPHPExcel->getActiveSheet()->getPageMargins()->setRight(0.2);
			$objPHPExcel->getActiveSheet()->getPageMargins()->setLeft(0.2);
			$objPHPExcel->getActiveSheet()->getPageMargins()->setBottom(0.5);
			$objPHPExcel->getActiveSheet()->getPageSetup()->setScale(100); // % impression : 10% à 400%
			$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&L&11&B EXPORT DECOUPAGE ADMINISTRATIF (ASTRUM) &R&11&B Page &P / &N');
			$objPHPExcel->getActiveSheet()->getHeaderFooter()->setEvenFooter('&L&11&B EXPORT DECOUPAGE ADMINISTRATIF (ASTRUM) &R&11&B Page &P / &N');
			$objPHPExcel->getActiveSheet()->setCellValue('A1', 'code_region');
			$objPHPExcel->getActiveSheet()->setCellValue('B1', 'code');
			$objPHPExcel->getActiveSheet()->setCellValue('C1', 'nom');
			$objPHPExcel->getActiveSheet()->getStyle('A1:C1')->applyFromArray($styleArray);
			$objPHPExcel->getActiveSheet()->getStyle('A1:C1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle('A1:C1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$ligne=2;
			foreach ($liste_district as $k=>$v) {
				$objPHPExcel->getActiveSheet()->setCellValueExplicit('A'.$ligne, $v->code_region, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet()->setCellValueExplicit('B'.$ligne, $v->code, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet()->setCellValueExplicit('C'.$ligne, $v->nom, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet()->getStyle('A'.$ligne.':C'.$ligne)->applyFromArray($styleArray);
				$objPHPExcel->getActiveSheet()->getStyle('A'.$ligne.':B'.$ligne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getStyle('A'.$ligne.':C'.$ligne)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":C".$ligne)->getAlignment()->setWrapText(true);				
				$ligne=$ligne + 1;			
			}
			// Fin export Liste district
			// Début export Liste commune
			$objWorkSheet = $objPHPExcel->createSheet(2);
			$objPHPExcel->setActiveSheetIndex(2);
			$objPHPExcel->getActiveSheet()->setTitle('Commune');
			$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(14);
			$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(40);
			$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT)	;		
			$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(25);
			$objPHPExcel->getActiveSheet()->getStyle("A1:P1")->getFont()->setSize(14);			
			$objPHPExcel->getActiveSheet()->getStyle('A1:P1')->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getPageSetup()->setHorizontalCentered(true);
			$objPHPExcel->getActiveSheet()->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(1, 1);
			$objPHPExcel->getActiveSheet()->getPageMargins()->setTop(0.4);
			$objPHPExcel->getActiveSheet()->getPageMargins()->setRight(0.2);
			$objPHPExcel->getActiveSheet()->getPageMargins()->setLeft(0.2);
			$objPHPExcel->getActiveSheet()->getPageMargins()->setBottom(0.5);
			$objPHPExcel->getActiveSheet()->getPageSetup()->setScale(100); // % impression : 10% à 400%
			$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&L&11&B EXPORT DECOUPAGE ADMINISTRATIF (ASTRUM) &R&11&B Page &P / &N');
			$objPHPExcel->getActiveSheet()->getHeaderFooter()->setEvenFooter('&L&11&B EXPORT DECOUPAGE ADMINISTRATIF (ASTRUM) &R&11&B Page &P / &N');
			$objPHPExcel->getActiveSheet()->setCellValue('A1', 'code_district');
			$objPHPExcel->getActiveSheet()->setCellValue('B1', 'code');
			$objPHPExcel->getActiveSheet()->setCellValue('C1', 'nom');
			$objPHPExcel->getActiveSheet()->getStyle('A1:C1')->applyFromArray($styleArray);
			$objPHPExcel->getActiveSheet()->getStyle('A1:C1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle('A1:C1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$ligne=2;
			foreach ($liste_commune as $k=>$v) {
				$objPHPExcel->getActiveSheet()->setCellValueExplicit('A'.$ligne, $v->code_district, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet()->setCellValueExplicit('B'.$ligne, $v->code, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet()->setCellValueExplicit('C'.$ligne, $v->nom, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet()->getStyle('A'.$ligne.':C'.$ligne)->applyFromArray($styleArray);
				$objPHPExcel->getActiveSheet()->getStyle('A'.$ligne.':B'.$ligne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getStyle('A'.$ligne.':C'.$ligne)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":C".$ligne)->getAlignment()->setWrapText(true);				
				$ligne=$ligne + 1;			
			}
			// Fin export Liste commune
			// Début export Liste fokontany
			$objWorkSheet = $objPHPExcel->createSheet(3);
			$objPHPExcel->setActiveSheetIndex(3);
			$objPHPExcel->getActiveSheet()->setTitle('Fokontany');
			$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(14);
			$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(40);
			$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT)	;		
			$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(25);
			$objPHPExcel->getActiveSheet()->getStyle("A1:P1")->getFont()->setSize(14);			
			$objPHPExcel->getActiveSheet()->getStyle('A1:P1')->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getPageSetup()->setHorizontalCentered(true);
			$objPHPExcel->getActiveSheet()->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(1, 1);
			$objPHPExcel->getActiveSheet()->getPageMargins()->setTop(0.4);
			$objPHPExcel->getActiveSheet()->getPageMargins()->setRight(0.2);
			$objPHPExcel->getActiveSheet()->getPageMargins()->setLeft(0.2);
			$objPHPExcel->getActiveSheet()->getPageMargins()->setBottom(0.5);
			$objPHPExcel->getActiveSheet()->getPageSetup()->setScale(100); // % impression : 10% à 400%
			$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&L&11&B EXPORT DECOUPAGE ADMINISTRATIF (ASTRUM) &R&11&B Page &P / &N');
			$objPHPExcel->getActiveSheet()->getHeaderFooter()->setEvenFooter('&L&11&B EXPORT DECOUPAGE ADMINISTRATIF (ASTRUM) &R&11&B Page &P / &N');
			$objPHPExcel->getActiveSheet()->setCellValue('A1', 'code_commune');
			$objPHPExcel->getActiveSheet()->setCellValue('B1', 'code');
			$objPHPExcel->getActiveSheet()->setCellValue('C1', 'nom');
			$objPHPExcel->getActiveSheet()->getStyle('A1:C1')->applyFromArray($styleArray);
			$objPHPExcel->getActiveSheet()->getStyle('A1:C1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle('A1:C1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$ligne=2;
			foreach ($liste_fokontany as $k=>$v) {
				$objPHPExcel->getActiveSheet()->setCellValueExplicit('A'.$ligne, $v->code_commune, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet()->setCellValueExplicit('B'.$ligne, $v->code, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet()->setCellValueExplicit('C'.$ligne, $v->nom, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet()->getStyle('A'.$ligne.':C'.$ligne)->applyFromArray($styleArray);
				$objPHPExcel->getActiveSheet()->getStyle('A'.$ligne.':B'.$ligne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getStyle('A'.$ligne.':C'.$ligne)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":C".$ligne)->getAlignment()->setWrapText(true);				
				$ligne=$ligne + 1;			
			}
			// Fin export Liste fokontany
				//  setActiveSheetIndex =0 : Activer la premiere feuillesauvegarde export découpage administratif	
				$objPHPExcel->setActiveSheetIndex(0);
				$date_edition = date("d-m-Y");
				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
				$objWriter->save(dirname(__FILE__) . "/../../../../".$repertoire."Découpage administratif".".xlsx");
				unset($objPHPExcel);
				unset($objWriter);
			// FIN EXPORT DECOUPAGE ADMINISTRATIF
/***********************************************************************************************************************	*/			
			// DEBUT EXPORT VARIABLE SUR INTERVENTION
				$liste_variable = $this->ImporterdecoupageadministratifManager->Recuperer_Variable();		
				$objPHPExcel = new PHPExcel();
				$objPHPExcel->getProperties()->setCreator("ASTRUM")
									 ->setLastModifiedBy("ASTRUM")
									 ->setTitle("Export variable sur intervention (ASTRUM)")
									 ->setSubject("Export variable sur intervention (ASTRUM)")
									 ->setDescription("Export variable sur intervention (ASTRUM)")
									 ->setKeywords("Export variable sur intervention (ASTRUM)")
									 ->setCategory("Export variable sur intervention (ASTRUM)");
				$objRichText = new PHPExcel_RichText();
				$objRichText->createText('N&deg; ');
				$styleArray = array(
				  'borders' => array(
					'allborders' => array(
					  'style' => PHPExcel_Style_Border::BORDER_THIN
					)
				  )
				);		
				$objPHPExcel->setActiveSheetIndex(0);
				$objPHPExcel->getActiveSheet()->setTitle('Variable sur intervention');
				$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
				$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(40);
				$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
				$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(40);
				$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT)	;		
				$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(25);
				$objPHPExcel->getActiveSheet()->mergeCells('A1:D1');
				$objPHPExcel->getActiveSheet()->mergeCells('A2:B2');
				$objPHPExcel->getActiveSheet()->mergeCells('C2:D2');
				$objPHPExcel->getActiveSheet()->getStyle('A1:D1')->applyFromArray($styleArray);
				$objPHPExcel->getActiveSheet()->getStyle('A2:B2')->applyFromArray($styleArray);
				$objPHPExcel->getActiveSheet()->getStyle('C2:D2')->applyFromArray($styleArray);
				$objPHPExcel->getActiveSheet()->setCellValue('A1', 'RELATION PERE FILS');
				$objPHPExcel->getActiveSheet()->setCellValue('A2', 'PERE (TABLE : liste_variable)');
				$objPHPExcel->getActiveSheet()->setCellValue('C2', 'FILS (TABLE : variable)');
				$objPHPExcel->getActiveSheet()->getStyle('A1:D3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getStyle('A1:D3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$objPHPExcel->getActiveSheet()->getStyle("A1:D3")->getAlignment()->setWrapText(true);
				$objPHPExcel->getActiveSheet()->getStyle("A1:D3")->getFont()->setSize(14);			
				$objPHPExcel->getActiveSheet()->getStyle('A1:D3')->getFont()->setBold(true);
				$objPHPExcel->getActiveSheet()->getPageSetup()->setHorizontalCentered(true);
				$objPHPExcel->getActiveSheet()->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(1, 3);
				$objPHPExcel->getActiveSheet()->getPageMargins()->setTop(0.4);
				$objPHPExcel->getActiveSheet()->getPageMargins()->setRight(0.2);
				$objPHPExcel->getActiveSheet()->getPageMargins()->setLeft(0.2);
				$objPHPExcel->getActiveSheet()->getPageMargins()->setBottom(0.5);
				$objPHPExcel->getActiveSheet()->getPageSetup()->setScale(100); // % impression : 10% à 400%
				$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&L&11&B EXPORT LISTE VARIABLE SUR INTERVENTION (ASTRUM) &R&11&B Page &P / &N');
				$objPHPExcel->getActiveSheet()->getHeaderFooter()->setEvenFooter('&L&11&B EXPORT LISTE VARIABLE SUR INTERVENTION (ASTRUM) &R&11&B Page &P / &N');
				$objPHPExcel->getActiveSheet()->setCellValue('A3', 'code');
				$objPHPExcel->getActiveSheet()->setCellValue('B3', 'Description');
				$objPHPExcel->getActiveSheet()->setCellValue('C3', 'code');
				$objPHPExcel->getActiveSheet()->setCellValue('D3', 'Description');
				$objPHPExcel->getActiveSheet()->getStyle('A3:D3')->applyFromArray($styleArray);
				$ligne=4;
				foreach ($liste_variable as $k=>$v) {
					$objPHPExcel->getActiveSheet()->setCellValueExplicit('A'.$ligne, $v->code_liste_variable, PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet()->setCellValueExplicit('B'.$ligne, $v->description_liste_variable, PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet()->setCellValueExplicit('C'.$ligne, $v->code, PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet()->setCellValueExplicit('D'.$ligne, $v->description, PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet()->getStyle('A'.$ligne.':D'.$ligne)->applyFromArray($styleArray);
					$objPHPExcel->getActiveSheet()->getStyle('A'.$ligne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('C'.$ligne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('A'.$ligne.':D'.$ligne)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":D".$ligne)->getAlignment()->setWrapText(true);				
					$ligne=$ligne + 1;			
				} 
				//  setActiveSheetIndex =0 : Activer la premiere feuillesauvegarde export découpage administratif	
				$objPHPExcel->setActiveSheetIndex(0);
				$date_edition = date("d-m-Y");
				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
				$objWriter->save(dirname(__FILE__) . "/../../../../".$repertoire."Variable sur intervention".".xlsx");
				unset($objPHPExcel);
				unset($objWriter);
				// FIN EXPORT VARIABLE SUR INTERVENTION 
/***********************************************************************************************************************	*/						
/***********************************************************************************************************************	*/			
			// DEBUT EXPORT TYPE DE TRANSFERT
				$liste_type_transfert = $this->ImporterdecoupageadministratifManager->Recuperer_Type_transfert();		
				$liste_unite_de_mesure = $this->EnquetemenageManager->findAll("unite_mesure");		
				$liste_frequence_transfert = $this->EnquetemenageManager->findAll("frequence_transfert");		
				$objPHPExcel = new PHPExcel();
				$objPHPExcel->getProperties()->setCreator("ASTRUM")
									 ->setLastModifiedBy("ASTRUM")
									 ->setTitle("Export type de transfert (ASTRUM)")
									 ->setSubject("Export type de transfert (ASTRUM)")
									 ->setDescription("Export type de transfert (ASTRUM)")
									 ->setKeywords("Export type de transfert (ASTRUM)")
									 ->setCategory("Export type de transfert (ASTRUM)");
				$objRichText = new PHPExcel_RichText();
				$objRichText->createText('N&deg; ');
				$styleArray = array(
				  'borders' => array(
					'allborders' => array(
					  'style' => PHPExcel_Style_Border::BORDER_THIN
					)
				  )
				);		
				$objPHPExcel->setActiveSheetIndex(0);
				$objPHPExcel->getActiveSheet()->setTitle('Type de transfert');
				$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
				$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
				$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
				$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(35);
				$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
				$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
				$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT)	;		
				$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(25);
				$objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(40);
				$objPHPExcel->getActiveSheet()->mergeCells('A1:F1');
				$objPHPExcel->getActiveSheet()->mergeCells('A2:B2');
				$objPHPExcel->getActiveSheet()->mergeCells('C2:D2');
				$objPHPExcel->getActiveSheet()->mergeCells('E2:F2');
				$objPHPExcel->getActiveSheet()->getStyle('A1:F1')->applyFromArray($styleArray);
				$objPHPExcel->getActiveSheet()->getStyle('A2:B2')->applyFromArray($styleArray);
				$objPHPExcel->getActiveSheet()->getStyle('C2:D2')->applyFromArray($styleArray);
				$objPHPExcel->getActiveSheet()->getStyle('E2:F2')->applyFromArray($styleArray);
				$objPHPExcel->getActiveSheet()->setCellValue('A1', 'RELATION PERE FILS');
				$objPHPExcel->getActiveSheet()->setCellValue('A2', 'PERE 1 (TABLE : type_de_transfert)');
				$objPHPExcel->getActiveSheet()->setCellValue('C2', 'FILS 1 / PERE 2 (TABLE : detail_type_de_transfert)');
				$objPHPExcel->getActiveSheet()->setCellValue('E2', 'FILS 2 (TABLE : unite_de_mesure)');
				$objPHPExcel->getActiveSheet()->getStyle('A1:F3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getStyle('A1:F3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$objPHPExcel->getActiveSheet()->getStyle("A1:F3")->getAlignment()->setWrapText(true);
				$objPHPExcel->getActiveSheet()->getStyle("A1:F3")->getFont()->setSize(14);			
				$objPHPExcel->getActiveSheet()->getStyle('A1:F3')->getFont()->setBold(true);
				$objPHPExcel->getActiveSheet()->getPageSetup()->setHorizontalCentered(true);
				$objPHPExcel->getActiveSheet()->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(1, 3);
				$objPHPExcel->getActiveSheet()->getPageMargins()->setTop(0.4);
				$objPHPExcel->getActiveSheet()->getPageMargins()->setRight(0.2);
				$objPHPExcel->getActiveSheet()->getPageMargins()->setLeft(0.2);
				$objPHPExcel->getActiveSheet()->getPageMargins()->setBottom(0.5);
				$objPHPExcel->getActiveSheet()->getPageSetup()->setScale(100); // % impression : 10% à 400%
				$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&L&11&B EXPORT LISTE TYPE DE TRANSFERT (ASTRUM) &R&11&B Page &P / &N');
				$objPHPExcel->getActiveSheet()->getHeaderFooter()->setEvenFooter('&L&11&B EXPORT LISTE TYPE DE TRANSFERT (ASTRUM) &R&11&B Page &P / &N');
				$objPHPExcel->getActiveSheet()->setCellValue('A3', 'code');
				$objPHPExcel->getActiveSheet()->setCellValue('B3', 'Description');
				$objPHPExcel->getActiveSheet()->setCellValue('C3', 'code');
				$objPHPExcel->getActiveSheet()->setCellValue('D3', 'Description');
				$objPHPExcel->getActiveSheet()->setCellValue('E3', 'code');
				$objPHPExcel->getActiveSheet()->setCellValue('F3', 'Description');
				$objPHPExcel->getActiveSheet()->getStyle('A3:F3')->applyFromArray($styleArray);
				$ligne=4;
				foreach ($liste_type_transfert as $k=>$v) {
					$objPHPExcel->getActiveSheet()->setCellValueExplicit('A'.$ligne, $v->code_type_transfert, PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet()->setCellValueExplicit('B'.$ligne, $v->description_type_transfert, PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet()->setCellValueExplicit('C'.$ligne, $v->code, PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet()->setCellValueExplicit('D'.$ligne, $v->description, PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet()->setCellValueExplicit('E'.$ligne, $v->code_unite_mesure, PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet()->setCellValueExplicit('F'.$ligne, $v->description_unite_mesure, PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet()->getStyle('A'.$ligne.':F'.$ligne)->applyFromArray($styleArray);
					$objPHPExcel->getActiveSheet()->getStyle('A'.$ligne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('C'.$ligne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('E'.$ligne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('A'.$ligne.':F'.$ligne)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":F".$ligne)->getAlignment()->setWrapText(true);				
					$ligne=$ligne + 1;			
				} 
				// Début liste unité de mésure
				$objWorkSheet = $objPHPExcel->createSheet(1);
				$objPHPExcel->setActiveSheetIndex(1);
				$objPHPExcel->getActiveSheet()->setTitle('Unité de mesure');
				$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
				$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(40);
				$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT)	;		
				$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(25);
				$objPHPExcel->getActiveSheet()->getStyle('A1:B1')->applyFromArray($styleArray);
				$objPHPExcel->getActiveSheet()->getStyle('A1:B1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getStyle('A1:B1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$objPHPExcel->getActiveSheet()->getStyle("A1:B1")->getAlignment()->setWrapText(true);
				$objPHPExcel->getActiveSheet()->getStyle("A1:B1")->getFont()->setSize(14);			
				$objPHPExcel->getActiveSheet()->getStyle('A1:B1')->getFont()->setBold(true);
				$objPHPExcel->getActiveSheet()->getPageSetup()->setHorizontalCentered(true);
				$objPHPExcel->getActiveSheet()->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(1, 2);
				$objPHPExcel->getActiveSheet()->getPageMargins()->setTop(0.4);
				$objPHPExcel->getActiveSheet()->getPageMargins()->setRight(0.2);
				$objPHPExcel->getActiveSheet()->getPageMargins()->setLeft(0.2);
				$objPHPExcel->getActiveSheet()->getPageMargins()->setBottom(0.5);
				$objPHPExcel->getActiveSheet()->getPageSetup()->setScale(100); // % impression : 10% à 400%
				$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&L&11&B EXPORT LISTE UNITE DE MESURE (ASTRUM) &R&11&B Page &P / &N');
				$objPHPExcel->getActiveSheet()->getHeaderFooter()->setEvenFooter('&L&11&B EXPORT LISTE UNITE DE MESURE (ASTRUM) &R&11&B Page &P / &N');			
				$objPHPExcel->getActiveSheet()->setCellValue('A1', 'code');
				$objPHPExcel->getActiveSheet()->setCellValue('B1', 'description');
				$objPHPExcel->getActiveSheet()->getStyle('A1:B1')->applyFromArray($styleArray);
				$objPHPExcel->getActiveSheet()->getStyle('A1:B1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getStyle('A1:B1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$ligne=2;
				foreach ($liste_unite_de_mesure as $k=>$v) {
					$objPHPExcel->getActiveSheet()->setCellValueExplicit('A'.$ligne, $v->code, PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet()->setCellValueExplicit('B'.$ligne, $v->description, PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet()->getStyle('A'.$ligne.':B'.$ligne)->applyFromArray($styleArray);
					$objPHPExcel->getActiveSheet()->getStyle('A'.$ligne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('A'.$ligne.':B'.$ligne)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":B".$ligne)->getAlignment()->setWrapText(true);				
					$ligne=$ligne + 1;			
				}
				// Fin liste unité de mésure
				// Début liste fréquence de transfert
				$objWorkSheet = $objPHPExcel->createSheet(2);
				$objPHPExcel->setActiveSheetIndex(2);
				$objPHPExcel->getActiveSheet()->setTitle('Fréquence de transfert');
				$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
				$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(40);
				$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT)	;		
				$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(25);
				$objPHPExcel->getActiveSheet()->getStyle('A1:B1')->applyFromArray($styleArray);
				$objPHPExcel->getActiveSheet()->getStyle('A1:B1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getStyle('A1:B1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$objPHPExcel->getActiveSheet()->getStyle("A1:B1")->getAlignment()->setWrapText(true);
				$objPHPExcel->getActiveSheet()->getStyle("A1:B1")->getFont()->setSize(14);			
				$objPHPExcel->getActiveSheet()->getStyle('A1:B1')->getFont()->setBold(true);
				$objPHPExcel->getActiveSheet()->getPageSetup()->setHorizontalCentered(true);
				$objPHPExcel->getActiveSheet()->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(1, 2);
				$objPHPExcel->getActiveSheet()->getPageMargins()->setTop(0.4);
				$objPHPExcel->getActiveSheet()->getPageMargins()->setRight(0.2);
				$objPHPExcel->getActiveSheet()->getPageMargins()->setLeft(0.2);
				$objPHPExcel->getActiveSheet()->getPageMargins()->setBottom(0.5);
				$objPHPExcel->getActiveSheet()->getPageSetup()->setScale(100); // % impression : 10% à 400%
				$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&L&11&B EXPORT LISTE FREQUENCE DE TRANSFERT (ASTRUM) &R&11&B Page &P / &N');
				$objPHPExcel->getActiveSheet()->getHeaderFooter()->setEvenFooter('&L&11&B EXPORT LISTE FREQUENCE DE TRANSFERT (ASTRUM) &R&11&B Page &P / &N');			
				$objPHPExcel->getActiveSheet()->setCellValue('A1', 'code');
				$objPHPExcel->getActiveSheet()->setCellValue('B1', 'description');
				$objPHPExcel->getActiveSheet()->getStyle('A1:B1')->applyFromArray($styleArray);
				$objPHPExcel->getActiveSheet()->getStyle('A1:B1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getStyle('A1:B1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$ligne=2;
				foreach ($liste_frequence_transfert as $k=>$v) {
					$objPHPExcel->getActiveSheet()->setCellValueExplicit('A'.$ligne, $v->code, PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet()->setCellValueExplicit('B'.$ligne, $v->description, PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet()->getStyle('A'.$ligne.':B'.$ligne)->applyFromArray($styleArray);
					$objPHPExcel->getActiveSheet()->getStyle('A'.$ligne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('A'.$ligne.':B'.$ligne)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":B".$ligne)->getAlignment()->setWrapText(true);				
					$ligne=$ligne + 1;			
				}
				// Fin liste fréquence de transfert
				//  setActiveSheetIndex =0 : Activer la premiere feuillesauvegarde export découpage administratif	
				$objPHPExcel->setActiveSheetIndex(0);
				$date_edition = date("d-m-Y");
				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
				$objWriter->save(dirname(__FILE__) . "/../../../../".$repertoire."Type transfert unite de mesure et frequence transfert".".xlsx");
				unset($objPHPExcel);
				unset($objWriter);
				// FIN EXPORT TYPE DE TRANSFERT
/***********************************************************************************************************************	*/						
/***********************************************************************************************************************	*/			
			// DEBUT EXPORT VARIABLE SUR INDIVIDU
				$liste_lien_de_parente = $this->EnquetemenageManager->findAll("liendeparente");		
				$liste_situation_matrimoniale = $this->EnquetemenageManager->findAll("situation_matrimoniale");		
				$liste_langue = $this->EnquetemenageManager->findAll("liste_langue");		
				$liste_type_ecole = $this->EnquetemenageManager->findAll("type_ecole");		
				$liste_niveau_de_classe = $this->EnquetemenageManager->findAll("niveau_de_classe");		
				$objPHPExcel = new PHPExcel();
				$objPHPExcel->getProperties()->setCreator("ASTRUM")
									 ->setLastModifiedBy("ASTRUM")
									 ->setTitle("Export variable sur individu (ASTRUM)")
									 ->setSubject("Export variable sur individu (ASTRUM)")
									 ->setDescription("Export variable sur individu (ASTRUM)")
									 ->setKeywords("Export variable sur individu (ASTRUM)")
									 ->setCategory("Export variable sur individu (ASTRUM)");
				$objRichText = new PHPExcel_RichText();
				$objRichText->createText('N&deg; ');
				$styleArray = array(
				  'borders' => array(
					'allborders' => array(
					  'style' => PHPExcel_Style_Border::BORDER_THIN
					)
				  )
				);	
				// Début lien de parenté		
				$objPHPExcel->setActiveSheetIndex(0);
				$objPHPExcel->getActiveSheet()->setTitle('Lien de parenté');
				$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
				$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(40);
				$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT)	;		
				$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(25);
				$objPHPExcel->getActiveSheet()->getStyle('A1:B1')->applyFromArray($styleArray);
				$objPHPExcel->getActiveSheet()->getStyle('A1:B1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getStyle('A1:B1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$objPHPExcel->getActiveSheet()->getStyle("A1:B1")->getAlignment()->setWrapText(true);
				$objPHPExcel->getActiveSheet()->getStyle("A1:B1")->getFont()->setSize(14);			
				$objPHPExcel->getActiveSheet()->getStyle('A1:B1')->getFont()->setBold(true);
				$objPHPExcel->getActiveSheet()->getPageSetup()->setHorizontalCentered(true);
				$objPHPExcel->getActiveSheet()->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(1, 2);
				$objPHPExcel->getActiveSheet()->getPageMargins()->setTop(0.4);
				$objPHPExcel->getActiveSheet()->getPageMargins()->setRight(0.2);
				$objPHPExcel->getActiveSheet()->getPageMargins()->setLeft(0.2);
				$objPHPExcel->getActiveSheet()->getPageMargins()->setBottom(0.5);
				$objPHPExcel->getActiveSheet()->getPageSetup()->setScale(100); // % impression : 10% à 400%
				$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&L&11&B EXPORT LISTE VARIABLE SUR INDIVIDU (ASTRUM) &R&11&B Page &P / &N');
				$objPHPExcel->getActiveSheet()->getHeaderFooter()->setEvenFooter('&L&11&B EXPORT LISTE VARIABLE SUR INDIVIDU (ASTRUM) &R&11&B Page &P / &N');			
				$objPHPExcel->getActiveSheet()->setCellValue('A1', 'code');
				$objPHPExcel->getActiveSheet()->setCellValue('B1', 'description');
				$objPHPExcel->getActiveSheet()->getStyle('A1:B1')->applyFromArray($styleArray);
				$objPHPExcel->getActiveSheet()->getStyle('A1:B1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getStyle('A1:B1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$ligne=2;
				foreach ($liste_lien_de_parente as $k=>$v) {
					$objPHPExcel->getActiveSheet()->setCellValueExplicit('A'.$ligne, $v->code, PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet()->setCellValueExplicit('B'.$ligne, $v->description, PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet()->getStyle('A'.$ligne.':B'.$ligne)->applyFromArray($styleArray);
					$objPHPExcel->getActiveSheet()->getStyle('A'.$ligne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('A'.$ligne.':B'.$ligne)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":B".$ligne)->getAlignment()->setWrapText(true);				
					$ligne=$ligne + 1;			
				}
				// Fin lien de parenté		
				// Début Situation matrimoniale
				$objWorkSheet = $objPHPExcel->createSheet(1);
				$objPHPExcel->setActiveSheetIndex(1);
				$objPHPExcel->getActiveSheet()->setTitle('Situation matrimoniale');
				$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
				$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(40);
				$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT)	;		
				$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(25);
				$objPHPExcel->getActiveSheet()->getStyle('A1:B1')->applyFromArray($styleArray);
				$objPHPExcel->getActiveSheet()->getStyle('A1:B1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getStyle('A1:B1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$objPHPExcel->getActiveSheet()->getStyle("A1:B1")->getAlignment()->setWrapText(true);
				$objPHPExcel->getActiveSheet()->getStyle("A1:B1")->getFont()->setSize(14);			
				$objPHPExcel->getActiveSheet()->getStyle('A1:B1')->getFont()->setBold(true);
				$objPHPExcel->getActiveSheet()->getPageSetup()->setHorizontalCentered(true);
				$objPHPExcel->getActiveSheet()->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(1, 2);
				$objPHPExcel->getActiveSheet()->getPageMargins()->setTop(0.4);
				$objPHPExcel->getActiveSheet()->getPageMargins()->setRight(0.2);
				$objPHPExcel->getActiveSheet()->getPageMargins()->setLeft(0.2);
				$objPHPExcel->getActiveSheet()->getPageMargins()->setBottom(0.5);
				$objPHPExcel->getActiveSheet()->getPageSetup()->setScale(100); // % impression : 10% à 400%
				$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&L&11&B EXPORT LISTE VARIABLE SUR INDIVIDU (ASTRUM) &R&11&B Page &P / &N');
				$objPHPExcel->getActiveSheet()->getHeaderFooter()->setEvenFooter('&L&11&B EXPORT LISTE VARIABLE SUR INDIVIDU (ASTRUM) &R&11&B Page &P / &N');			
				$objPHPExcel->getActiveSheet()->setCellValue('A1', 'code');
				$objPHPExcel->getActiveSheet()->setCellValue('B1', 'description');
				$objPHPExcel->getActiveSheet()->getStyle('A1:B1')->applyFromArray($styleArray);
				$objPHPExcel->getActiveSheet()->getStyle('A1:B1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getStyle('A1:B1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$ligne=2;
				foreach ($liste_situation_matrimoniale as $k=>$v) {
					$objPHPExcel->getActiveSheet()->setCellValueExplicit('A'.$ligne, $v->code, PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet()->setCellValueExplicit('B'.$ligne, $v->description, PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet()->getStyle('A'.$ligne.':B'.$ligne)->applyFromArray($styleArray);
					$objPHPExcel->getActiveSheet()->getStyle('A'.$ligne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('A'.$ligne.':B'.$ligne)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":B".$ligne)->getAlignment()->setWrapText(true);				
					$ligne=$ligne + 1;			
				}
				// Fin Situation matrimoniale
				// Début liste langue
				$objWorkSheet = $objPHPExcel->createSheet(2);
				$objPHPExcel->setActiveSheetIndex(2);
				$objPHPExcel->getActiveSheet()->setTitle('Liste langue');
				$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
				$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(40);
				$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT)	;		
				$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(25);
				$objPHPExcel->getActiveSheet()->getStyle('A1:B1')->applyFromArray($styleArray);
				$objPHPExcel->getActiveSheet()->getStyle('A1:B1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getStyle('A1:B1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$objPHPExcel->getActiveSheet()->getStyle("A1:B1")->getAlignment()->setWrapText(true);
				$objPHPExcel->getActiveSheet()->getStyle("A1:B1")->getFont()->setSize(14);			
				$objPHPExcel->getActiveSheet()->getStyle('A1:B1')->getFont()->setBold(true);
				$objPHPExcel->getActiveSheet()->getPageSetup()->setHorizontalCentered(true);
				$objPHPExcel->getActiveSheet()->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(1, 2);
				$objPHPExcel->getActiveSheet()->getPageMargins()->setTop(0.4);
				$objPHPExcel->getActiveSheet()->getPageMargins()->setRight(0.2);
				$objPHPExcel->getActiveSheet()->getPageMargins()->setLeft(0.2);
				$objPHPExcel->getActiveSheet()->getPageMargins()->setBottom(0.5);
				$objPHPExcel->getActiveSheet()->getPageSetup()->setScale(100); // % impression : 10% à 400%
				$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&L&11&B EXPORT LISTE VARIABLE SUR INDIVIDU (ASTRUM) &R&11&B Page &P / &N');
				$objPHPExcel->getActiveSheet()->getHeaderFooter()->setEvenFooter('&L&11&B EXPORT LISTE VARIABLE SUR INDIVIDU (ASTRUM) &R&11&B Page &P / &N');			
				$objPHPExcel->getActiveSheet()->setCellValue('A1', 'code');
				$objPHPExcel->getActiveSheet()->setCellValue('B1', 'description');
				$objPHPExcel->getActiveSheet()->getStyle('A1:B1')->applyFromArray($styleArray);
				$objPHPExcel->getActiveSheet()->getStyle('A1:B1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getStyle('A1:B1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$ligne=2;
				foreach ($liste_langue as $k=>$v) {
					$objPHPExcel->getActiveSheet()->setCellValueExplicit('A'.$ligne, $v->code, PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet()->setCellValueExplicit('B'.$ligne, $v->description, PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet()->getStyle('A'.$ligne.':B'.$ligne)->applyFromArray($styleArray);
					$objPHPExcel->getActiveSheet()->getStyle('A'.$ligne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('A'.$ligne.':B'.$ligne)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":B".$ligne)->getAlignment()->setWrapText(true);				
					$ligne=$ligne + 1;			
				}
				// Fin liste langue
				// Début liste type école
				$objWorkSheet = $objPHPExcel->createSheet(3);
				$objPHPExcel->setActiveSheetIndex(3);
				$objPHPExcel->getActiveSheet()->setTitle('Type école');
				$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
				$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(40);
				$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT)	;		
				$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(25);
				$objPHPExcel->getActiveSheet()->getStyle('A1:B1')->applyFromArray($styleArray);
				$objPHPExcel->getActiveSheet()->getStyle('A1:B1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getStyle('A1:B1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$objPHPExcel->getActiveSheet()->getStyle("A1:B1")->getAlignment()->setWrapText(true);
				$objPHPExcel->getActiveSheet()->getStyle("A1:B1")->getFont()->setSize(14);			
				$objPHPExcel->getActiveSheet()->getStyle('A1:B1')->getFont()->setBold(true);
				$objPHPExcel->getActiveSheet()->getPageSetup()->setHorizontalCentered(true);
				$objPHPExcel->getActiveSheet()->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(1, 2);
				$objPHPExcel->getActiveSheet()->getPageMargins()->setTop(0.4);
				$objPHPExcel->getActiveSheet()->getPageMargins()->setRight(0.2);
				$objPHPExcel->getActiveSheet()->getPageMargins()->setLeft(0.2);
				$objPHPExcel->getActiveSheet()->getPageMargins()->setBottom(0.5);
				$objPHPExcel->getActiveSheet()->getPageSetup()->setScale(100); // % impression : 10% à 400%
				$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&L&11&B EXPORT LISTE VARIABLE SUR INDIVIDU (ASTRUM) &R&11&B Page &P / &N');
				$objPHPExcel->getActiveSheet()->getHeaderFooter()->setEvenFooter('&L&11&B EXPORT LISTE VARIABLE SUR INDIVIDU (ASTRUM) &R&11&B Page &P / &N');			
				$objPHPExcel->getActiveSheet()->setCellValue('A1', 'code');
				$objPHPExcel->getActiveSheet()->setCellValue('B1', 'description');
				$objPHPExcel->getActiveSheet()->getStyle('A1:B1')->applyFromArray($styleArray);
				$objPHPExcel->getActiveSheet()->getStyle('A1:B1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getStyle('A1:B1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$ligne=2;
				foreach ($liste_type_ecole as $k=>$v) {
					$objPHPExcel->getActiveSheet()->setCellValueExplicit('A'.$ligne, $v->code, PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet()->setCellValueExplicit('B'.$ligne, $v->description, PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet()->getStyle('A'.$ligne.':B'.$ligne)->applyFromArray($styleArray);
					$objPHPExcel->getActiveSheet()->getStyle('A'.$ligne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('A'.$ligne.':B'.$ligne)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":B".$ligne)->getAlignment()->setWrapText(true);				
					$ligne=$ligne + 1;			
				}
				// Fin liste type école
				// Début liste niveau de classe
				$objWorkSheet = $objPHPExcel->createSheet(4);
				$objPHPExcel->setActiveSheetIndex(4);
				$objPHPExcel->getActiveSheet()->setTitle('Niveau de classe');
				$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
				$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(40);
				$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT)	;		
				$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(25);
				$objPHPExcel->getActiveSheet()->getStyle('A1:B1')->applyFromArray($styleArray);
				$objPHPExcel->getActiveSheet()->getStyle('A1:B1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getStyle('A1:B1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$objPHPExcel->getActiveSheet()->getStyle("A1:B1")->getAlignment()->setWrapText(true);
				$objPHPExcel->getActiveSheet()->getStyle("A1:B1")->getFont()->setSize(14);			
				$objPHPExcel->getActiveSheet()->getStyle('A1:B1')->getFont()->setBold(true);
				$objPHPExcel->getActiveSheet()->getPageSetup()->setHorizontalCentered(true);
				$objPHPExcel->getActiveSheet()->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(1, 2);
				$objPHPExcel->getActiveSheet()->getPageMargins()->setTop(0.4);
				$objPHPExcel->getActiveSheet()->getPageMargins()->setRight(0.2);
				$objPHPExcel->getActiveSheet()->getPageMargins()->setLeft(0.2);
				$objPHPExcel->getActiveSheet()->getPageMargins()->setBottom(0.5);
				$objPHPExcel->getActiveSheet()->getPageSetup()->setScale(100); // % impression : 10% à 400%
				$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&L&11&B EXPORT LISTE VARIABLE SUR INDIVIDU (ASTRUM) &R&11&B Page &P / &N');
				$objPHPExcel->getActiveSheet()->getHeaderFooter()->setEvenFooter('&L&11&B EXPORT LISTE VARIABLE SUR INDIVIDU (ASTRUM) &R&11&B Page &P / &N');			
				$objPHPExcel->getActiveSheet()->setCellValue('A1', 'code');
				$objPHPExcel->getActiveSheet()->setCellValue('B1', 'description');
				$objPHPExcel->getActiveSheet()->getStyle('A1:B1')->applyFromArray($styleArray);
				$objPHPExcel->getActiveSheet()->getStyle('A1:B1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getStyle('A1:B1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$ligne=2;
				foreach ($liste_niveau_de_classe as $k=>$v) {
					$objPHPExcel->getActiveSheet()->setCellValueExplicit('A'.$ligne, $v->code, PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet()->setCellValueExplicit('B'.$ligne, $v->description, PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet()->getStyle('A'.$ligne.':B'.$ligne)->applyFromArray($styleArray);
					$objPHPExcel->getActiveSheet()->getStyle('A'.$ligne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('A'.$ligne.':B'.$ligne)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":B".$ligne)->getAlignment()->setWrapText(true);				
					$ligne=$ligne + 1;			
				}
				// Fin liste niveau de classe
				
				//  setActiveSheetIndex =0 : Activer la premiere feuille sauvegarde export variable sur individu
				$objPHPExcel->setActiveSheetIndex(0);
				$date_edition = date("d-m-Y");
				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
				$objWriter->save(dirname(__FILE__) . "/../../../../".$repertoire."Variable sur individu".".xlsx");
				unset($objPHPExcel);
				unset($objWriter);
				// FIN EXPORT VARIABLE SUR INDIVIDU
/***********************************************************************************************************************	*/						
/***********************************************************************************************************************	*/			
			// DEBUT EXPORT NOMENCLATURE INTERVENTION
				$liste_variable = $this->ImporterdecoupageadministratifManager->Recuperer_Nomenclature_intervention();		
				$objPHPExcel = new PHPExcel();
				$objPHPExcel->getProperties()->setCreator("ASTRUM")
									 ->setLastModifiedBy("ASTRUM")
									 ->setTitle("Export nomenclature intervention (ASTRUM)")
									 ->setSubject("Export nomenclature intervention (ASTRUM)")
									 ->setDescription("Export nomenclature intervention (ASTRUM)")
									 ->setKeywords("Export nomenclature intervention (ASTRUM)")
									 ->setCategory("Export nomenclature intervention (ASTRUM)");
				$objRichText = new PHPExcel_RichText();
				$objRichText->createText('N&deg; ');
				$styleArray = array(
				  'borders' => array(
					'allborders' => array(
					  'style' => PHPExcel_Style_Border::BORDER_THIN
					)
				  )
				);		
				$objPHPExcel->setActiveSheetIndex(0);
				$objPHPExcel->getActiveSheet()->setTitle('Nomenclature intervention');
				$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(12);
				$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
				$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(12);
				$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
				$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(12);
				$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(30);
				$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(12);
				$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(30);
				$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT)	;		
				$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(25);
				$objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(40);
				$objPHPExcel->getActiveSheet()->mergeCells('A1:H1');
				$objPHPExcel->getActiveSheet()->mergeCells('A2:B2');
				$objPHPExcel->getActiveSheet()->mergeCells('C2:D2');
				$objPHPExcel->getActiveSheet()->mergeCells('E2:F2');
				$objPHPExcel->getActiveSheet()->mergeCells('G2:H2');
				$objPHPExcel->getActiveSheet()->getStyle('A1:H1')->applyFromArray($styleArray);
				$objPHPExcel->getActiveSheet()->getStyle('A2:B2')->applyFromArray($styleArray);
				$objPHPExcel->getActiveSheet()->getStyle('C2:D2')->applyFromArray($styleArray);
				$objPHPExcel->getActiveSheet()->getStyle('E2:F2')->applyFromArray($styleArray);
				$objPHPExcel->getActiveSheet()->getStyle('G2:H2')->applyFromArray($styleArray);
				$objPHPExcel->getActiveSheet()->setCellValue('A1', 'RELATION PERE FILS');
				$objPHPExcel->getActiveSheet()->setCellValue('A2', 'PERE1 (TABLE : nomenclature1)');
				$objPHPExcel->getActiveSheet()->setCellValue('C2', 'FILS1/PERE2 (TABLE : nomenclature2)');
				$objPHPExcel->getActiveSheet()->setCellValue('E2', 'FILS2/PERE3 (TABLE : nomenclature3)');
				$objPHPExcel->getActiveSheet()->setCellValue('G2', 'FILS3 (TABLE : nomenclature4)');
				$objPHPExcel->getActiveSheet()->getStyle('A1:H3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getStyle('A1:H3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$objPHPExcel->getActiveSheet()->getStyle("A1:H3")->getAlignment()->setWrapText(true);
				$objPHPExcel->getActiveSheet()->getStyle("A1:H3")->getFont()->setSize(14);			
				$objPHPExcel->getActiveSheet()->getStyle('A1:H3')->getFont()->setBold(true);
				$objPHPExcel->getActiveSheet()->getPageSetup()->setHorizontalCentered(true);
				$objPHPExcel->getActiveSheet()->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(1, 3);
				$objPHPExcel->getActiveSheet()->getPageMargins()->setTop(0.4);
				$objPHPExcel->getActiveSheet()->getPageMargins()->setRight(0.2);
				$objPHPExcel->getActiveSheet()->getPageMargins()->setLeft(0.2);
				$objPHPExcel->getActiveSheet()->getPageMargins()->setBottom(0.5);
				$objPHPExcel->getActiveSheet()->getPageSetup()->setScale(100); // % impression : 10% à 400%
				$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&L&11&B EXPORT LISTE NOMENCLATURE INTERVENTION (ASTRUM) &R&11&B Page &P / &N');
				$objPHPExcel->getActiveSheet()->getHeaderFooter()->setEvenFooter('&L&11&B EXPORT LISTE NOMENCLATURE INTERVENTION (ASTRUM) &R&11&B Page &P / &N');
				$objPHPExcel->getActiveSheet()->setCellValue('A3', 'code');
				$objPHPExcel->getActiveSheet()->setCellValue('B3', 'Description');
				$objPHPExcel->getActiveSheet()->setCellValue('C3', 'code');
				$objPHPExcel->getActiveSheet()->setCellValue('D3', 'Description');
				$objPHPExcel->getActiveSheet()->setCellValue('E3', 'code');
				$objPHPExcel->getActiveSheet()->setCellValue('F3', 'Description');
				$objPHPExcel->getActiveSheet()->setCellValue('G3', 'code');
				$objPHPExcel->getActiveSheet()->setCellValue('H3', 'Description');
				$objPHPExcel->getActiveSheet()->getStyle('A3:H3')->applyFromArray($styleArray);
				$ligne=4;
				foreach ($liste_variable as $k=>$v) {
					$objPHPExcel->getActiveSheet()->setCellValueExplicit('A'.$ligne, $v->code1, PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet()->setCellValueExplicit('B'.$ligne, $v->description1, PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet()->setCellValueExplicit('C'.$ligne, $v->code2, PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet()->setCellValueExplicit('D'.$ligne, $v->description2, PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet()->setCellValueExplicit('E'.$ligne, $v->code3, PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet()->setCellValueExplicit('F'.$ligne, $v->description3, PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet()->setCellValueExplicit('G'.$ligne, $v->code, PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet()->setCellValueExplicit('H'.$ligne, $v->description, PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet()->getStyle('A'.$ligne.':H'.$ligne)->applyFromArray($styleArray);
					$objPHPExcel->getActiveSheet()->getStyle('A'.$ligne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('C'.$ligne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('E'.$ligne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('G'.$ligne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('A'.$ligne.':H'.$ligne)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":H".$ligne)->getAlignment()->setWrapText(true);				
					$ligne=$ligne + 1;			
				} 
				//  setActiveSheetIndex =0 : Activer la premiere feuillesauvegarde export découpage administratif	
				$objPHPExcel->setActiveSheetIndex(0);
				$date_edition = date("d-m-Y");
				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
				$objWriter->save(dirname(__FILE__) . "/../../../../".$repertoire."Nomenclature intervention".".xlsx");
				unset($objPHPExcel);
				unset($objWriter);
				// FIN EXPORT NOMENCLATURE INTERVENTION 
/***********************************************************************************************************************	*/						
				$data['donnees'] = $this->CommuneManager->findAll() ;		
				$data['erreur'] = "" ;		
				$data['retour'] = "OK" ;		
			echo json_encode($data);
		} else {
			$data['erreur'] = 'AUCUNE INFORMATION EXPORTEES' ;		
			$data['retour'] = 'AUCUN' ;		
			echo json_encode($data);			
		}	
	}	
} ?>	
