<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Zone_intervention_programme extends REST_Controller {

    public function __construct() { 
        parent::__construct();
        $this->load->model('programme_model', 'ProgrammeManager');
        $this->load->model('zone_intervention_programme_model', 'ZoneinterventionprogrammeManager');
        $this->load->model('district_model', 'DistrictManager');
        $this->load->model('region_model', 'RegionManager');
    }

    public function index_get() {
        $id = $this->get('id');
        $cle_etrangere = $this->get('cle_etrangere');
		$data = array();
		if ($id) {
			// Récupération par id (id=cle primaire)
			$temporaire = $this->ZoneinterventionprogrammeManager->findById($id);
			if($temporaire) {
				$data=$temporaire;
			}
			$ou=1;
		} else if($cle_etrangere) {	
			// Récupération par programme
			$menu=$this->ZoneinterventionprogrammeManager->findByIdProgramme($cle_etrangere);
			$ou=2;
		} else {	
			// Récupération de tous les enregistrements
			$menu = $this->ZoneinterventionprogrammeManager->findAll();
			$ou=2;
		}
		if($ou==2) {
			if ($menu) {
                foreach ($menu as $key => $value) {
					// Détail description programme
                    $programme = array();
                    $prg = $this->ProgrammeManager->findById($value->id_programme);
					if(count($prg) >0) {
						$programme=$prg;
					}
					// Détail description district
					$district =array();
					if($value->id_district) {
						$dist = $this->DistrictManager->findById($value->id_district);
						if(count($dist) >0) {
							$district=$dist;
						}						
					}
					// Détail description région	
					$region =array();
					if($value->id_region) {
						$reg = $this->RegionManager->findByIdArray($value->id_region);
						if(count($reg) >0) {
							$region=$reg;
						}						
					}	
					// Affectation des valeurs dans un tableau	
                    $data[$key]['id'] = $value->id;
                    $data[$key]['id_programme'] = $value->id_programme;
                    $data[$key]['programme'] = $programme;
                    $data[$key]['id_district'] = $value->id_district;
                    $data[$key]['district'] = $district;
                    $data[$key]['id_region'] = $value->id_region;
                    $data[$key]['region'] = $region;
                    $data[$key]['menage_beneficiaire_prevu'] = $value->menage_beneficiaire_prevu;
                    $data[$key]['individu_beneficiaire_prevu'] = $value->individu_beneficiaire_prevu;
                    $data[$key]['groupe_beneficiaire_prevu'] = $value->groupe_beneficiaire_prevu;
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
		// Initialisation des valeurs à null pour éviter le ZERO par défaut inséré dans la BDD
		$id_district=null;
		$temporaire=$this->post('id_district');
		if(isset($temporaire) && $temporaire !="" && intval($temporaire) >0) {
			$id_district=$temporaire;
		}
		$id_region=null;
		$temporaire=$this->post('id_region');
		if(isset($temporaire) && $temporaire !="" && intval($temporaire) >0) {
			$id_region=$temporaire;
		}
		$menage_beneficiaire_prevu=null;
		$temporaire=$this->post('menage_beneficiaire_prevu');
		if(isset($temporaire) && $temporaire !="" && intval($temporaire) >0) {
			$menage_beneficiaire_prevu=$temporaire;
		}
		$individu_beneficiaire_prevu=null;
		$temporaire=$this->post('individu_beneficiaire_prevu');
		if(isset($temporaire) && $temporaire !="" && intval($temporaire) >0) {
			$individu_beneficiaire_prevu=$temporaire;
		}
		$groupe_beneficiaire_prevu=null;
		$temporaire=$this->post('groupe_beneficiaire_prevu');
		if(isset($temporaire) && $temporaire !="" && intval($temporaire) >0) {
			$groupe_beneficiaire_prevu=$temporaire;
		}
		// Affectation des valeurs de chaque colonne
 		$data = array(
			'id_programme' => $this->post('id_programme'),
			'id_district' => $id_district,
			'id_region' => $id_region,
			'menage_beneficiaire_prevu' => $menage_beneficiaire_prevu,
			'individu_beneficiaire_prevu' => $individu_beneficiaire_prevu,
			'groupe_beneficiaire_prevu' => $groupe_beneficiaire_prevu,
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
                $dataId = $this->ZoneinterventionprogrammeManager->add($data);              
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
                $update = $this->ZoneinterventionprogrammeManager->update($id, $data);              
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
			// Suppression d'un enregistrement
            $delete = $this->ZoneinterventionprogrammeManager->delete($id);          
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