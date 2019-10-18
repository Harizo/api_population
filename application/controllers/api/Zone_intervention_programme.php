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
			$tmp = $this->ZoneinterventionprogrammeManager->findById($id);
			if($tmp) {
				$data=$tmp;
			}
			$ou=1;
		} else if($cle_etrangere) {	
			$menu=$this->ZoneinterventionprogrammeManager->findByIdProgramme($cle_etrangere);
			$ou=2;
		} else {			
			$menu = $this->ZoneinterventionprogrammeManager->findAll();
			$ou=2;
		}
		if($ou==2) {
			if ($menu) {
                foreach ($menu as $key => $value) {
                    $programme = array();
                    $prg = $this->ProgrammeManager->findById($value->id_programme);
					if(count($prg) >0) {
						$programme=$prg;
					}
					$district =array();
					if($value->id_district) {
						$dist = $this->DistrictManager->findById($value->id_district);
						if(count($dist) >0) {
							$district=$dist;
						}						
					}					
					$region =array();
					if($value->id_region) {
						$reg = $this->RegionManager->findByIdArray($value->id_region);
						if(count($reg) >0) {
							$region=$reg;
						}						
					}					
                    $data[$key]['id'] = $value->id;
                    $data[$key]['id_programme'] = $value->id_programme;
                    $data[$key]['programme'] = $programme;
                    $data[$key]['id_district'] = $value->id_district;
                    $data[$key]['district'] = $district;
                    $data[$key]['id_region'] = $value->id_region;
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
		$id_district=null;
		$tmp=$this->post('id_district');
		if(isset($tmp) && $tmp !="" && intval($tmp) >0) {
			$id_district=$tmp;
		}
		$id_region=null;
		$tmp=$this->post('id_region');
		if(isset($tmp) && $tmp !="" && intval($tmp) >0) {
			$id_region=$tmp;
		}
 		$data = array(
			'id_programme' => $this->post('id_programme'),
			'id_district' => $id_district,
			'id_region' => $id_region,
			'menage_beneficiaire_prevu' => $this->post('menage_beneficiaire_prevu'),
			'individu_beneficiaire_prevu' => $this->post('individu_beneficiaire_prevu'),
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