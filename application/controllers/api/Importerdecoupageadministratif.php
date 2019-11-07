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
							$code_region =$cell->getValue();
							$code_region_original =$cell->getValue();
					} else if('G' == $cell->getColumn()) {
							$chef_lieu =$cell->getValue();	
					} 
				}
				$amoron_mania=false;
				$code_region = strtolower($code_region);
				$x= strpos($code_region,'mania');
				if($x > 0) {
					$amoron_mania=true;
				} else {
					$amoron_mania=false;
				}
				$code_region=str_replace($trouver,$remplacer,$code_region);
				$region_ok = false;
				$insert_region=false;
				$id_region=null;
				$reg=array();
				if($code_region >'') {
					if($amoron_mania==false) {
						$reg = $this->ImporterdecoupageadministratifManager->selectionregion($code_region);
					} else {
						$reg = $this->ImporterdecoupageadministratifManager->selectionregionparid("ania");
					}	
					if(count($reg) >0) {
						foreach($reg as $indice=>$v) {
							$id_region = $v->id;
							$code_region=$v->code;
						} 						
					} else {
						$insert_region=true;
						$data = array(
							'code' => $code_region,
							'nom' => $code_region_original,
							'surface' => null,
							'chef_lieu' => $chef_lieu,
						);  
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
						foreach($reg as $indice=>$v) {
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
						foreach($dist as $indice=>$v) {
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
						foreach($comm as $indice=>$v) {
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
	
} ?>	
