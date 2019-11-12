<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Zone_intervention extends REST_Controller {

    public function __construct() { 
        parent::__construct();
        $this->load->model('zone_intervention_model', 'ZoneinterventionManager');
        $this->load->model('intervention_model', 'InterventionManager');
        $this->load->model('fokontany_model', 'FokontanyManager');
        $this->load->model('commune_model', 'CommuneManager');
        $this->load->model('district_model', 'DistrictManager');
        $this->load->model('region_model', 'RegionManager');
    }

    public function index_get() {
        $id = $this->get('id');
        $cle_etrangere = $this->get('cle_etrangere');
		$data = array();
		if ($id) {
			$temporaire = $this->ZoneinterventionManager->findById($id);
			if($temporaire) {
				$data=$temporaire;
			}
			$ou=1;
		} else if($cle_etrangere){			
			$menu = $this->ZoneinterventionManager->findByIdIntervention($cle_etrangere);
			$ou=2;
		} else {			
			$menu = $this->ZoneinterventionManager->findAll();
			$ou=2;
		}
		if($ou==2) {			
			if ($menu) {
                foreach ($menu as $key => $value) {
					$intervention =array();
					if($value->id_intervention) {
						$prg = $this->InterventionManager->findById($value->id_intervention);
						if(count($prg) >0) {
							$intervention=$prg;
						}						
					}					
                    $fokontany = array();
                    $tpf = $this->FokontanyManager->findById($value->id_fokontany);
					if(count($tpf) >0) {
						$fokontany=$tpf;
					}
                    $commune = array();
                    $co = $this->CommuneManager->findById($fokontany[0]->id_commune);
					if(count($co) >0) {
						$commune=$co;
					}
                    $district = array();
                    $dis = $this->DistrictManager->findById($commune[0]->district_id);
					if(count($dis) >0) {
						$district=$dis;
					}
                    $region = array();
                    $dis = $this->RegionManager->findByIdArray($district[0]->region_id);
					if(count($dis) >0) {
						$region=$dis;
					}
                    $data[$key]['id'] = $value->id;
                    $data[$key]['id_intervention'] = $value->id_intervention;
                    $data[$key]['intervention'] = $intervention;
                    $data[$key]['id_fokontany'] = $value->id_fokontany;
                    $data[$key]['fokontany'] = $fokontany;
                    $data[$key]['id_commune'] = $fokontany[0]->id_commune;
                    $data[$key]['commune'] = $commune;
                    $data[$key]['id_district'] = $commune[0]->district_id;
                    $data[$key]['district'] = $district;
                    $data[$key]['id_region'] = $district[0]->region_id;
                    $data[$key]['region'] = $region;
                    $data[$key]['menage_beneficiaire_prevu'] = $value->menage_beneficiaire_prevu;
                    $data[$key]['individu_beneficiaire_prevu'] = $value->individu_beneficiaire_prevu;
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
		$id_fokontany=null;
		$temporaire=$this->post('id_fokontany');
		if(isset($temporaire) && $temporaire !="" && intval($temporaire) >0) {
			$id_fokontany=$temporaire;
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
 		$data = array(
			'id_intervention'             => $this->post('id_intervention'),
			'id_fokontany'                => $id_fokontany,
			'menage_beneficiaire_prevu'   => $menage_beneficiaire_prevu,
			'individu_beneficiaire_prevu' => $individu_beneficiaire_prevu,
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
                $dataId = $this->ZoneinterventionManager->add($data);              
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
                $update = $this->ZoneinterventionManager->update($id, $data);              
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
            $delete = $this->ZoneinterventionManager->delete($id);          
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