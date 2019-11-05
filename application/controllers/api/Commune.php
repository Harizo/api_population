<?php
//harizo
defined('BASEPATH') OR exit('No direct script access allowed');

// afaka fafana refa ts ilaina
require APPPATH . '/libraries/REST_Controller.php';

class Commune extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('commune_model', 'CommuneManager');
        $this->load->model('district_model', 'DistrictManager');
    }
    //recuperation commune
    public function index_get() {
        $id = $this->get('id');
        $cle_etrangere = $this->get('cle_etrangere');
        $id_commune = $this->get('id_commune');
        $id_district = $this->get('id_district');
        $id_region = $this->get('id_region');
		$taiza="";
        if ($cle_etrangere) {
            //$data = $this->CommuneManager->findAllByDistrict($cle_etrangere);
            $data = array();
            $tmp = $this->CommuneManager->findAllByDistrict($cle_etrangere);
            if ($tmp) 
            {
                foreach ($tmp as $key => $value) 
                {
                    $district = array();
                    $district = $this->DistrictManager->findByIdOLD($value->district_id);
                    $data[$key]['id'] = $value->id;
                    $data[$key]['code'] = $value->code;
                    $data[$key]['nom'] = $value->nom;
                    $data[$key]['district'] = $district;
                }
            }           
        } else {
            if ($id)  {
                $data = array();
                $data = $this->CommuneManager->findById($id);
            } else if($id_commune) {
				$taiza="Ato ambony ary id_commune=".$id_commune."  ary id_region=".$id_region; 
				$menu = $this->CommuneManager->find_Fokontany_avec_District_et_Region($id_commune);
                if ($menu) {
					$data=$menu;
                } else
                    $data = array();
			} else if($id_district) {	
				$menu = $this->CommuneManager->find_Commune_avec_District_et_Region($id_district);
                if ($menu) {
					$data=$menu;
                } else
                    $data = array();
			} else {
				$taiza="findAll no nataony";
                $menu = $this->CommuneManager->findAll();
                if ($menu) {
                    foreach ($menu as $key => $value) {
                        $district = array();
                        $district = $this->DistrictManager->findById($value->district_id);
                        $data[$key]['id'] = $value->id;
                        $data[$key]['code'] = $value->code;
                        $data[$key]['nom'] = $value->nom;
                        $data[$key]['district_id'] = $value->district_id;
                        $data[$key]['district'] = $district;
                    }
                } else
                    $data = array();
            }
        }
        if (count($data)>0) {
            $this->response([
                'status' => TRUE,
                'response' => $data,
                'message' => $taiza,
                // 'message' => 'Get data success',
            ], REST_Controller::HTTP_OK);
        } else {
            $this->response([
                'status' => FALSE,
                'response' => array(),
                'message' => 'No data were found'
            ], REST_Controller::HTTP_OK);
        }
    }
    //insertion,modification,suppression commune
    public function index_post() {
        $id = $this->post('id') ;
        $supprimer = $this->post('supprimer') ;
        if ($supprimer == 0) {
            if ($id == 0) {
                $data = array(
                    'code' => $this->post('code'),
                    'nom' => $this->post('nom'),
                    'district_id' => $this->post('district_id')
                );
                if (!$data) {
                    $this->response([
                        'status' => FALSE,
                        'response' => 0,
                        'message' => 'No request found'
                            ], REST_Controller::HTTP_BAD_REQUEST);
                }
                $dataId = $this->CommuneManager->add($data);
                if (!is_null($dataId))  {
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
                $data = array(
                    'code' => $this->post('code'),
                    'nom' => $this->post('nom'),
                    'district_id' => $this->post('district_id')
                );
                if (!$data || !$id) {
                    $this->response([
                        'status' => FALSE,
                        'response' => 0,
                        'message' => 'No request found'
                            ], REST_Controller::HTTP_BAD_REQUEST);
                }
                $update = $this->CommuneManager->update($id, $data);
                if(!is_null($update)) {
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
            $delete = $this->CommuneManager->delete($id);
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
/* End of file controllername.php */
/* Location: ./application/controllers/controllername.php */
