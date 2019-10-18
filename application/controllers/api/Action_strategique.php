<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Action_strategique extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('action_strategique_model', 'ActionstrategiqueManager');
        $this->load->model('axe_strategique_model', 'AxestrategiqueManager');
    }

    public function index_get() {
        $id = $this->get('id');
		$data = array();
		if ($id) {
			$tmp = $this->ActionstrategiqueManager->findById($id);
			if($tmp) {
				$data=$tmp;
			}
		} else {			
			$menu = $this->ActionstrategiqueManager->findAll();
			if ($menu) {
                foreach ($menu as $key => $value) {
					$axestrategique =array();
					if($value->id_axe_strategique) {
						$prg = $this->AxestrategiqueManager->findById($value->id_axe_strategique);
						if(count($prg) >0) {
							$axestrategique=$prg;
						}						
					}					
                    $data[$key]['id'] = $value->id;
                    $data[$key]['id_axe_strategique'] = $value->id_axe_strategique;
                    $data[$key]['axestrategique'] = $axestrategique;
                    $data[$key]['code'] = $value->code;
                    $data[$key]['action'] = $value->action;
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
		$id_axe_strategique=null;
		$tmp=$this->post('id_axe_strategique');
		if(isset($tmp) && $tmp !="" && intval($tmp) >0) {
			$id_axe_strategique=$tmp;
		}
		$data = array(
			'action' => $this->post('action'),
			'code' => $this->post('code'),
			'id_axe_strategique' => $id_axe_strategique,
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
                $dataId = $this->ActionstrategiqueManager->add($data);              
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
                $update = $this->ActionstrategiqueManager->update($id, $data);              
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
            $delete = $this->ActionstrategiqueManager->delete($id);          
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