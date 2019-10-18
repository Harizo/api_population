<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Detail_type_transfert_intervention extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('detail_type_transfert_intervention_model', 'DetailtypetransfertinterventionManager');
        $this->load->model('type_transfert_model', 'TypetransfertManager');
        $this->load->model('unite_mesure_model', 'UnitemesureManager');
    }

    public function index_get() {
        $id = $this->get('id');
        $cle_etrangere = $this->get('cle_etrangere');
		$data = array();
		if ($id) {
			$tmp = $this->DetailtypetransfertinterventionManager->findById($id);
			if($tmp) {
				$data=$tmp;
			}
			$ou=1;
		} else if ($cle_etrangere){
			$menu = $this->DetailtypetransfertinterventionManager->findByIntervention($cle_etrangere);
			$ou=2;
		} else {	
			$menu = $this->DetailtypetransfertinterventionManager->findAll();
			$ou=2;
		}
		if($ou==2) {
			if($menu) {
                foreach ($menu as $key => $value) {
                    $data[$key]['id'] = $value->id;
                    $data[$key]['id_intervention'] = $value->id_intervention;
                    $data[$key]['id_detail_type_transfert'] = $value->id_detail_type_transfert;
                    $data[$key]['valeur_quantite'] = $value->valeur_quantite;
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
		$data = array(
			'id_intervention' => $this->post('id_intervention'),
			'id_detail_type_transfert' => $this->post('id_detail_type_transfert'),
			'valeur_quantite' => $this->post('valeur_quantite'),
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
                $dataId = $this->DetailtypetransfertinterventionManager->add($data);              
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
                $update = $this->DetailtypetransfertinterventionManager->update($id, $data);              
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
            $delete = $this->DetailtypetransfertinterventionManager->delete($id);          
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