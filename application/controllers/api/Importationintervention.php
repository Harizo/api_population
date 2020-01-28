<?php

defined('BASEPATH') OR exit('No direct script access allowed');
// require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/PHPMailer/PHPMailerAutoload.php';

class Importationintervention extends CI_Controller {
    public function __construct() {
        parent::__construct();
		// Ouverture des modèles utilisées
        $this->load->model('importationintervention_model', 'ImportationinterventionManager');
        $this->load->model('validationintervention_model', 'ValidationinterventionManager');
        $this->load->model('region_model', 'RegionManager');
        $this->load->model('district_model', 'DistrictManager');
        $this->load->model('commune_model', 'CommuneManager');
        $this->load->model('fokontany_model', 'FokontanyManager');        
        $this->load->model('acteur_model', 'ActeurManager');        
        $this->load->model('intervention_model', 'InterventionManager');        
        $this->load->model('menage_model', 'MenageManager');        
        $this->load->model('individu_model', 'IndividuManager');        
        $this->load->model('suivi_menage_model', 'SuiviMenageManager');        
        $this->load->model('suivi_menage_entete_model', 'SuiviMenageEnteteManager');        
        $this->load->model('suivi_menage_detail_transfert_model', 'SuiviMenageDetailtransfertManager');        
        $this->load->model('suivi_individu_model', 'SuiviIndividuManager');        
        $this->load->model('suivi_individu_entete_model', 'SuiviIndividuEnteteManager');        
        $this->load->model('suivi_individu_detail_transfert_model', 'SuiviIndividuDetailtransfertManager');        
        $this->load->model('detail_type_transfert_intervention_model', 'DetailTypeTransfertInterventionManager');        
    }
	// Copie fichier excel vers le serveur
	public function upload_importationdonneesintervention() {	
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
		} 
		echo json_encode($valeur_retour);
	}  
	public function importer_donnees_intervention() {	
		require_once 'Classes/PHPExcel.php';
		require_once 'Classes/PHPExcel/IOFactory.php';
		set_time_limit(0);
		$repertoire= $_POST['repertoire'];
		$nomfichier= $_POST['nom_fichier'];
		$id_liste_validation_intervention= $_POST['id_liste_validation_intervention'];
		$id_utilisateur= $_POST['id_utilisateur'];
		$id_liste_validation_intervention= $_POST['id'];
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
		$nombre_insertion_entete=0; // Utilisée une fois pour toute pendant la lecture du fichier et les détails à insérer par ligne excel
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
							$date_suivi = $cell->getValue();
							if(isset($date_suivi) && $date_suivi>"") {
								if(PHPExcel_Shared_Date::isDateTime($cell)) {
									 $date_suivi = date($format='Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($date_suivi)); 
								}
							} else {
								$date_suivi=null;
							}	
						} else if('H' == $cell->getColumn()) {
							$menage_ou_individu = $cell->getValue();
						}	 
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
							$montant_transfert = $v->montant_transfert;
						}	
					} else {
						$id_intervention=null;
						$montant_transfert =null;
					}					
					// A utliser ultérieurement si tout est OK pour lamise à jour de la table menage ou individu selon le cas
					$menage_ou_individu = strtolower($menage_ou_individu);
					if($menage_ou_individu=="ménage" || $menage_ou_individu=="menage") {
						$menage_ou_individu="ménage";
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
							// Selection region par nom
							$reg = $this->ImportationinterventionManager->selectionregion($nom_region);
						} else {
							// Selection region par id
							$reg = $this->ImportationinterventionManager->selectionregionparid(5);
						}	
						if(count($reg) >0) {
							foreach($reg as $indice=>$v) {
								$id_region = $v->id;
								$code_region=$v->code;
							} 						
						} else {
							$id_region=null;
							$code_region="????";
						}						
						if(intval($id_region) >0) {
							if($nom_district >'') {
								$region_ok = true;
								// Selection district par nom et id_region
								$dis = $this->ImportationinterventionManager->selectiondistrict($nom_district,$id_region);
								if(count($dis) >0) {
									foreach($dis as $indice=>$v) {
										$id_district = $v->id;
										$code_district= $v->code;
									}
								} else {
									$id_district =null;
									$code_district="????";
								}
								if(intval($id_district) >0) {
									if($nom_commune >'') {
										$district_ok = true;
										// Selection commune par nom et id_district
										$comm = $this->ImportationinterventionManager->selectioncommune($nom_commune,$id_district);
										if(count($comm) >0) {
											foreach($comm as $indice=>$v) {
												$id_commune = $v->id;
												$code_commune = $v->code;
											}
										} else {
											// Pas de commune : marquer commune,fokontany 
											$id_commune = null;
											$code_commune = "????";
										}	
										if(intval($id_commune) >0) {
											if($nom_fokontany >'') {
												$fkt = $this->ImportationinterventionManager->selectionfokontany($nom_fokontany,$id_commune);
												if(count($fkt) >0) {
													foreach($fkt as $indice=>$v) {
														// A utliser ultérieurement lors de la deuxième vérification : id_fokontany
														$id_fokontany = $v->id;
														$code_fokontany = $v->code;
													}
												} else {													
													// Pas de fokontany : marquer fokontany 
													$id_fokontany = null;
													$code_fokontany = "????";
												}												
											}
										} 
									} else {										
										// Pas de commune : marquer commune,fokontany 
										$id_commune = null;
										$code_commune = "????";
									}		
								}
							} else {
								// Pas de district : marquer district,commune,fokontany 
								$id_district =null;
								$code_district="????";
							}		
						}
					} else {
						// Pas de région : marquer tous les découpages administratif 
						$id_region=null;
						$code_region="????";
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
					// Le contrôle n'est pas du tout necessaire car il a été déjà fait auparavant : ici 
					// c'est intégration de données seulement
					// Insértion dans en-tête intervention : table suivi_individu_entete ou suivi_menage_entete
						if($menage_ou_individu=="individu") {
							// Individu tout court
							$parametre_table="individu";
						} else {
							// Si chef ménage
							$parametre_table="menage";
						}
					if($menage_ou_individu=="ménage") {						
						// 1- Recherche par identifiant_appariement = $identifiant_appariement et $id_acteur stocké auparavant
						$parametre_table="menage";
						$id_menage=null;
						$insertion_suivi=false;
						$retour=$this->ImportationinterventionManager->RechercheParIdentifiantActeur($parametre_table,$identifiant_appariement,$id_acteur);
						if($retour) {	
							foreach($retour as $k=>$v) {
								$id_menage = $v->id_menage;
							}
							$insertion_suivi=true;
						}	
						if(null==$id_menage) {
							// Non trouvé continuer recherche
							// 2- Recherche par nom , prenom , CIN, id_fokontany , id_acteur
							// Recherche selon le cas : liste par ménage ou individu
							// De plus si la liste est ménage; il faut chercher dans la table menage si chef_menage = "O"
							// sinon recherche dans la table individu
							$retour=$this->ImportationinterventionManager->RechercheParNomPrenom_Fokontany_Acteur($parametre_table,$identifiant_appariement,$id_acteur,$nom,$prenom,$id_fokontany);
							$id_menage=null;
							if($retour) {
								foreach($retour as $k=>$v) {
									$id_menage = $v->id_menage;
								}
								$insertion_suivi=true;
							}
							if(null==$id_menage) {
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
						if($insertion_suivi==true) {
							// C'est-à-dire le bénéficiaire est présent dans la BDD => insertion dans suivi
							if($nombre_insertion_entete==0) {
								// insertion dans la table suivi_menage_entete et suivi_menage_detail_transfert
								//  une seule fois : la variable $nombre_insertion_entete sera = 1
								// la table suivi_menage_detail_transfert est utilisée car il se peut qu'ultérieurement
								// les détails de transfert seront modifiés ????? : prudence
								$data = array(
									'id_intervention' => $id_intervention,
									'id_fokontany' => $id_fokontany,
									'id_liste_validation_intervention' => $id_liste_validation_intervention,
									'date_suivi' => $date_suivi,
									'montant_transfert' => $montant_transfert,
									'observation' => null,
								);
								$id_entete = $this->SuiviMenageEnteteManager->add($data);
								// Insertion dans suivi_menage_detail_transfert : la variable $id_entete est utilisée comme clé étrangère dedans
								// Récupérons d'abord les détails dans la table detail_type_transfert_intervention et puis boucler
								// pour insérer toutes les lignes dans suivi_menage_detail_transfert : par id_intervention
								$detail_type_transfert = $this->DetailTypeTransfertInterventionManager->findByIntervention($id_intervention);
								if($detail_type_transfert) {
									foreach($detail_type_transfert as $k=>$v) {
										$data = array(
											'id_suivi_menage_entete' => $id_entete,
											'id_detail_type_transfert' => $v->id_detail_type_transfert,
											'valeur_quantite' => $v->valeur_quantite,
										);
										$id_detail_transfert = $this->SuiviMenageDetailtransfertManager->add($data);
									}	
								}	
								$nombre_insertion_entete=1;
							}
							// Insertion détail_suivi_menage : à chaque lecture ligne du fichier excel
							$data = array(
								'id_menage' => $id_menage,
								'id_suivi_menage_entete' => $id_entete,
							);
							$id_suivi = $this->SuiviMenageManager->add($data);
						}
					} else{
						$parametre_table="individu";
						$id_individu=null;
						$insertion_suivi=false;
						$retour=$this->ImportationinterventionManager->RechercheParIdentifiantActeur($parametre_table,$identifiant_appariement,$id_acteur);
						if($retour) {	
							foreach($retour as $k=>$v) {
								$id_individu = $v->id_individu;
							}
							$insertion_suivi=true;
						}	
						if(null==$id_individu) {
							// Non trouvé continuer recherche
							// 2- Recherche par nom , prenom , CIN, id_fokontany , id_acteur
							// Recherche selon le cas : liste par ménage ou individu
							// De plus si la liste est ménage; il faut chercher dans la table menage si chef_menage = "O"
							// sinon recherche dans la table individu
							$retour=$this->ImportationinterventionManager->RechercheParNomPrenom_Fokontany_Acteur($parametre_table,$identifiant_appariement,$id_acteur,$nom,$prenom,$id_fokontany);
							$id_individu=null;
							if($retour) {
								foreach($retour as $k=>$v) {
									$id_individu = $v->id_individu;
								}
								$insertion_suivi=true;
							}	
							if(null==$id_individu) {
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
						if($insertion_suivi==true) {
							// C'est-à-dire le bénéficiaire est présent dans la BDD => insertion dans suivi
							if($nombre_insertion_entete==0) {
								// insertion dans la table suivi_individu_entete et suivi_menage_detail_transfert
								//  une seule fois : la variable $nombre_insertion_entete sera = 1
								// la table suivi_individu_detail_transfert est utilisée car il se peut qu'ultérieurement
								// les détails de transfert seront modifiés ????? : prudence
								$data = array(
									'id_intervention' => $id_intervention,
									'id_fokontany' => $id_fokontany,
									'id_liste_validation_intervention' => $id_liste_validation_intervention,
									'date_suivi' => $date_suivi,
									'montant_transfert' => $montant_transfert,
									'observation' => null,
								);
								$id_entete = $this->SuiviIndividuEnteteManager->add($data);
								// Insertion dans suivi_individu_detail_transfert : la variable $id_entete est utilisée comme clé étrangère dedans
								// Récupérons d'abord les détails dans la table detail_type_transfert_intervention et puis boucler
								// pour insérer toutes les lignes dans suivi_individu_detail_transfert : par id_intervention
								$detail_type_transfert = $this->DetailTypeTransfertInterventionManager->findByIntervention($id_intervention);
								if($detail_type_transfert) {
									foreach($detail_type_transfert as $k=>$v) {
										$data = array(
											'id_suivi_individu_entete' => $id_entete,
											'id_detail_type_transfert' => $v->id_detail_type_transfert,
											'valeur_quantite' => $v->valeur_quantite,
										);
										$id_detail_transfert = $this->SuiviIndividuDetailtransfertManager->add($data);
									}	
								}	
								$nombre_insertion_entete=1;
								
							}
							// Insertion détail_suivi_individu : à chaque lecture ligne du fichier excel
							$data = array(
								'id_individu' => $id_individu,
								'id_suivi_menage_entete' => $id_entete,
							);
							$id_suivi = $this->SuiviIndividuManager->add($data);
						}
						$sheet->setCellValue("E".$ligne, $code_region."-".$code_district."-".$code_commune."-".$code_fokontany."-".$identifiant_unique);
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
		$retour = $this->ImportationinterventionManager->MiseAJourListeValidationIntervention($id_liste_validation_intervention,$date_validation,$id_utilisateur);
		if($nombre_erreur > 0) {
			// Signaler les erreurs par mail
			$val_ret["reponse"] = "ERREUR";
			$val_ret["region"] = $nom_region_original;
			$val_ret["district"] = $nom_district_original;
			$val_ret["commune"] = $nom_commune_original;
			$val_ret["fokontany"] = $nom_fokontany_original;
			$val_ret["intervention"] = $intitule_intervention;
			$val_ret["nombre_erreur"] = $nombre_erreur;
			// Fermer fichier Excel
		} else {
			// Tout est ok
			$val_ret["reponse"] = "OK";
			$val_ret["region"] = $nom_region_original;
			$val_ret["district"] = $nom_district_original;
			$val_ret["commune"] = $nom_commune_original;
			$val_ret["fokontany"] = $nom_fokontany_original;
			$val_ret["intervention"] = $intitule_intervention;
			$val_ret["nombre_erreur"] = 0;			
		}
		echo json_encode($val_ret);
	}	
} ?>	
