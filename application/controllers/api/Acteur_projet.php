<?php

defined('BASEPATH') OR exit('No direct script access allowed');
//harizo
// afaka fafana refa ts ilaina
require APPPATH . '/libraries/REST_Controller.php';

class Acteur_projet extends REST_Controller {

    public function __construct() {
        parent::__construct();
		$this->load->model('acteur_projet_model', 'ActeurprojetManager');
    }
    public function index_get() {
        $id = $this->get('id');
        if ($id) {
            $data = $this->ActeurprojetManager->findById($id);
            if (!$data)
                $data = array();
        } else {
			$data=array();
			$menu = $this->ActeurprojetManager->findAll();	
            if ($menu) {
                foreach ($menu as $key => $value) {
                    $data[$key]['id'] = $value->id;
                    $data[$key]['id_projet'] = $value->id_projet;
                    $data[$key]['id_acteur'] = $value->id_acteur;
                    $data[$key]['id_acteur_regional'] = $value->id_acteur_regional;
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
        $id_acteur =null ;
        $tmp = $this->post('id_acteur') ;
		if(isset($tmp) && intval($tmp) >0) {
			$id_acteur=$tmp;
		}
        $id_acteur_regional =null ;
        $tmp1 = $this->post('id_acteur_regional') ;
		if(isset($tmp1) && intval($tmp1) >0) {
			$id_acteur_regional=$tmp1;
		}
		$data = array(
			'id_projet'          => $this->post('id_projet'),
			'id_acteur'          => $id_acteur,
			'id_acteur_regional' => $id_acteur_regional,
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
                $dataId = $this->ActeurprojetManager->add($data);
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
                $update = $this->ActeurprojetManager->update($id, $data);
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
            $delete = $this->ActeurprojetManager->delete($id);
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