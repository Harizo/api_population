<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Financement_programme extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('financement_programme_model', 'FinancementprogrammeManager');
        $this->load->model('sourcefinancement_model', 'SourcefinancementManager');
        $this->load->model('type_financement_model', 'TypefinancementManager');
        $this->load->model('programme_model', 'ProgrammeManager');
        $this->load->model('axe_strategique_model', 'AxestrategiqueManager');
        $this->load->model('devise_model', 'DeviseManager');
        $this->load->model('type_secteur_model', 'TypesecteurManager');
        $this->load->model('secteur_programme_model', 'SecteurprogrammeManager');
    }

    public function index_get() {
        $id = $this->get('id');
        $cle_etrangere = $this->get('cle_etrangere');
		$data = array();
		if ($id) {
			// Récupération par id (clé primaire)
			$temporaire = $this->FinancementprogrammeManager->findById($id);
			if($temporaire) {
				$data=$temporaire;
			}
			$choix=1;
		}else if($cle_etrangere) {	
			$menu=$this->FinancementprogrammeManager->findByIdProgramme($cle_etrangere);
			$choix=2;
		} else {
			// Récupération de tous les enregistrements	
			$menu = $this->FinancementprogrammeManager->findAll();
			$choix=2;
		}
		if($choix==2) {
			if ($menu) {
				// Récupération du résultat dans tableau
                foreach ($menu as $key => $value) {
					$programme =array();
					// Description programme
					if($value->id_programme) {
						$prg = $this->ProgrammeManager->findById($value->id_programme);
						if(count($prg) >0) {
							$programme=$prg;
						}						
					}	
					// Description source de financement	
                    $sourcefinancement = array();
                    $srcfinance = $this->SourcefinancementManager->findById($value->id_source_financement);
					if(count($srcfinance) >0) {
						$sourcefinancement=$srcfinance;
					}
					// Description type financement
                    $typefinancement = array();
                    $tpf = $this->TypefinancementManager->findById($value->id_type_financement);
					if(count($tpf) >0) {
						$typefinancement=$tpf;
					}
					// Description axe stratégique
					$axestrategique =array();
					if($value->id_axe_strategique) {
						$reg = $this->AxestrategiqueManager->findById($value->id_axe_strategique);
						if(count($reg) >0) {
							$axestrategique=$reg;
						}						
					}	
					// Description devise utilisé
					$devise =array();
					if($value->id_devise) {
						$devi = $this->DeviseManager->findById($value->id_devise);
						if(count($devi) >0) {
							$devise=$devi;
						}						
					}	
					// Liste  type secteur concerné		
					$typesecteur =array();
					$tsecteur = $this->SecteurprogrammeManager->findByIdprogrammeAndIdfinancementprogramme($value->id_programme,$value->id);
					if(!$tsecteur) {
						$tsecteur=array();	
					}
					// Stocker dans tableau le id du type de secteur (seulement)
					if(count($tsecteur) >0) {
						foreach($tsecteur as $k=>$v) {
							$typesecteur[$k]=$v->id_type_secteur;
						}
					}						
                    $data[$key]['id'] = $value->id;
                    $data[$key]['id_programme'] = $value->id_programme;
                    $data[$key]['programme'] = $programme;
                    $data[$key]['id_source_financement'] = $value->id_source_financement;
                    $data[$key]['sourcefinancement'] = $sourcefinancement;
                    $data[$key]['id_type_financement'] = $value->id_type_financement;
                    $data[$key]['typefinancement'] = $typefinancement;
                    $data[$key]['id_axe_strategique'] = $value->id_axe_strategique;
                    $data[$key]['axestrategique'] = $axestrategique;
                    $data[$key]['id_devise'] = $value->id_devise;
                    $data[$key]['devise'] = $devise;
                    $data[$key]['typesecteur'] = $typesecteur;
                    $data[$key]['budget_initial'] = $value->budget_initial;
                    $data[$key]['budget_modifie'] = $value->budget_modifie;
				}
			}
		}
        if (count($data)>0) {
            $this->response([
                'status' => TRUE,
                'response' => $data,
                'message' => 'Get data success',
            ], REST_Controller::HTTP_OK);
        } else {
            $this->response([
                'status' => FALSE,
                'response' => array(),
                'message' => 'No data were found'
            ], REST_Controller::HTTP_OK);
        }
    }
    public function index_post() {
        $id = $this->post('id') ;
        $supprimer = $this->post('supprimer') ;
        $nombre_detail_type_secteur = $this->post('nombre_detail_type_secteur') ;
		// Initialisation des colonnes (clé étrangère) par null pour éviter le ZERO par défaut inséré dans la BDD : ATTENTION
		$id_source_financement=null;
		$temporaire=$this->post('id_source_financement');
		if(isset($temporaire) && $temporaire !="" && intval($temporaire) >0) {
			$id_source_financement=$temporaire;
		}
		$id_type_financement=null;
		$temporaire=$this->post('id_type_financement');
		if(isset($temporaire) && $temporaire !="" && intval($temporaire) >0) {
			$id_type_financement=$temporaire;
		}
		$id_axe_strategique=null;
		$temporaire=$this->post('id_axe_strategique');
		if(isset($temporaire) && $temporaire !="" && intval($temporaire) >0) {
			$id_axe_strategique=$temporaire;
		}
		$id_devise=null;
		$temporaire=$this->post('id_devise');
		if(isset($temporaire) && $temporaire !="" && intval($temporaire) >0) {
			$id_devise=$temporaire;
		}
		$id_programme=$this->post('id_programme');
		// Affectation des colonnes de la table	
 		$data = array(
			'id_programme' => $id_programme,
			'id_source_financement' => $id_source_financement,
			'id_type_financement' => $id_type_financement,
			'id_axe_strategique' => $id_axe_strategique,
			'id_devise' => $id_devise,
			'budget_initial' => $this->post('budget_initial'),
			'budget_modifie' => $this->post('budget_modifie'),
		);               
        if ($supprimer == 0) {
            if ($id == 0) {
                if (!$data) {
                    $this->response([
                        'status' => FALSE,
                        'response' => 0,
                        'message' => 'No request found'
                            ], REST_Controller::HTTP_BAD_REQUEST);
                }
				// Ajout d'un enregistrement
                $dataId = $this->FinancementprogrammeManager->add($data);			
				if($nombre_detail_type_secteur >0) {
					// Ajout détail type secteur	
					for($i=0;$i<$nombre_detail_type_secteur;$i++) {
						$type_secteur=array(
							'id_programme' => $id_programme,
							'id_financement_programme' => $dataId,
							'id_type_secteur' => $this->post('id_type_secteur_'.$i)
						);
						$retour = $this->SecteurprogrammeManager->add($type_secteur);     
					}
				}
                if (!is_null($dataId)) {
                    $this->response([
                        'status' => TRUE,
                        'response' => $dataId,
                        'message' => 'Data insert success'
                            ], REST_Controller::HTTP_OK);
                } else {
                    $this->response([
                        'status' => FALSE,
                        'response' => 0,
                        'message' => 'No request found'
                            ], REST_Controller::HTTP_BAD_REQUEST);
                }
            } else {
                if (!$data || !$id) {
                    $this->response([
                        'status' => FALSE,
                        'response' => 0,
                        'message' => 'No request found'
                            ], REST_Controller::HTTP_BAD_REQUEST);
                }
				// Mise à jour d'un enregistrement
                $update = $this->FinancementprogrammeManager->update($id, $data);              
				// Suppresion de tous les détails type secteur avant insertion de nouveau
				$del = $this->SecteurprogrammeManager->deleteByIdprogrammeAndIdfinancementprogramme($id_programme,$id);  
				if($nombre_detail_type_secteur >0) {
					// Ajout détail type secteur	
					for($i=0;$i<$nombre_detail_type_secteur;$i++) {
						$type_secteur=array(
							'id_programme' => $id_programme,
							'id_financement_programme' => $id,
							'id_type_secteur' => $this->post('id_type_secteur_'.$i)
						);
						$retour = $this->SecteurprogrammeManager->add($type_secteur);     
					}
				}
                if(!is_null($update)){
                    $this->response([
                        'status' => TRUE, 
                        'response' => 1,
                        'message' => 'Update data success'
                            ], REST_Controller::HTTP_OK);
                } else {
                    $this->response([
                        'status' => FALSE,
                        'message' => 'No request found'
                            ], REST_Controller::HTTP_OK);
                }
            }
        } else {
            if (!$id) {
            $this->response([
            'status' => FALSE,
            'response' => 0,
            'message' => 'No request found'
                ], REST_Controller::HTTP_BAD_REQUEST);
            }
			// Suppression des fils type_secteur_programme
			$del = $this->SecteurprogrammeManager->deleteByIdprogrammeAndIdfinancementprogramme($id_programme,$id);  
			// Suppression d'un enregistrement
            $delete = $this->FinancementprogrammeManager->delete($id);          
            if (!is_null($delete)) {
                $this->response([
                    'status' => TRUE,
                    'response' => 1,
                    'message' => "Delete data success"
                        ], REST_Controller::HTTP_OK);
            } else {
                $this->response([
                    'status' => FALSE,
                    'response' => 0,
                    'message' => 'No request found'
                        ], REST_Controller::HTTP_OK);
            }
        }   
    }
}
?>