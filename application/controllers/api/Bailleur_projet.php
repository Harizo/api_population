<?php

defined('BASEPATH') OR exit('No direct script access allowed');
//harizo
// afaka fafana refa ts ilaina
require APPPATH . '/libraries/REST_Controller.php';

class Bailleur_projet extends REST_Controller {

    public function __construct() {
        parent::__construct();
		$this->load->model('bailleur_projet_model', 'BailleurprojetManager');
    }
    public function index_get() {
        $id = $this->get('id');
        if ($id) {
            $data = $this->BailleurprojetManager->findById($id);
            if (!$data)
                $data = array();
        } else {
			$data=array();
			$menu = $this->BailleurprojetManager->findAll();	
            if ($menu) {
                foreach ($menu as $key => $value) {
                    $data[$key]['id'] = $value->id;
                    $data[$key]['id_projet'] = $value->id_projet;
                    $data[$key]['id_bailleur'] = $value->id_bailleur;
                    $data[$key]['id_type_financement'] = $value->id_type_financement;
                    $data[$key]['type_transfert'] = $value->type_transfert;
                    $data[$key]['monnaie'] = $value->monnaie;
                    $data[$key]['cout'] = $value->cout;
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
			'id_projet'           => $this->post('id_projet'),
			'id_bailleur'         => $this->post('id_bailleur'),
			'id_type_financement' => $this->post('id_type_financement'),
			'type_transfert'      => $this->post('type_transfert'),
			'monnaie'             => $this->post('monnaie'),
			'cout'                => $this->post('cout'),
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
                $dataId = $this->BailleurprojetManager->add($data);
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
                $update = $this->BailleurprojetManager->update($id, $data);
                if(!is_null($update)) {
                    $this->response([
                        'status' => TRUE,
                        'response' => 1,
                        'message' => 'Update data success'
                            ], REST_Controller::HTTP_OK);
                } else  {
                    $this->response([
                        'status' => FALSE,
                        'message' => 'No request found'
                            ], REST_Controller::HTTP_OK);
                }
            }
        } else  {
            if (!$id) {
            $this->response([
                'status' => FALSE,
                'response' => 0,
                'message' => 'No request found'
                    ], REST_Controller::HTTP_BAD_REQUEST);
            }
            $delete = $this->BailleurprojetManager->delete($id);
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
                        ], REST_Controller::HTTP_BAD_REQUEST);
            }
        }
    }
}
/* End of file controllername.php */
/* Location: ./application/controllers/controllername.php */
?>