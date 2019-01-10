<?php

defined('BASEPATH') OR exit('No direct script access allowed');
//harizo
// afaka fafana refa ts ilaina


require APPPATH . '/libraries/REST_Controller.php';

class Site extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('site_model', 'SiteManager');
    }
    public function index_get() {
        $id = $this->get('id');
        if ($id) {
            $data = $this->SiteManager->findById($id);
            if (!$data)
                $data = array();
        } else {
			$data=array();
				$menu = $this->SiteManager->findAll();	
            if ($menu) {
                foreach ($menu as $key => $value) {
                    $data[$key]['id'] = $value->id;
                    $data[$key]['code'] = $value->code;
                    $data[$key]['libelle'] = $value->libelle;
                    $data[$key]['adresse'] = $value->adresse;
                }
            }
            if (!$data)
                $data = array();
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
        if ($supprimer == 0) {
            if ($id == 0) {
                $data = array(
                    'code'    => $this->post('code'),
                    'libelle' => $this->post('libelle'),
                    'adresse' => $this->post('adresse')
                );
                if (!$data) {
                    $this->response([
                        'status' => FALSE,
                        'response' => 0,
                        'message' => 'No request found'
                            ], REST_Controller::HTTP_BAD_REQUEST);
                }
                $dataId = $this->SiteManager->add($data);
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
                $data = array(
                    'code'    => $this->post('code'),
                    'libelle' => $this->post('libelle'),
                    'adresse' => $this->post('adresse')
                );
                if (!$data || !$id) {
                    $this->response([
                        'status' => FALSE,
                        'response' => 0,
                        'message' => 'No request found'
                            ], REST_Controller::HTTP_BAD_REQUEST);
                }
                $update = $this->SiteManager->update($id, $data);               
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
            $delete = $this->SiteManager->delete($id);
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