<?php

defined('BASEPATH') OR exit('No direct script access allowed');
//harizo
// afaka fafana refa ts ilaina
require APPPATH . '/libraries/REST_Controller.php';

class Cours_de_change extends REST_Controller {

    public function __construct() {
        parent::__construct();
		$this->load->model('cours_de_change_model', 'CoursdechangeManager');
		$this->load->model('devise_model', 'DeviseManager');
    }
    public function index_get() {
        $id = $this->get('id');
        if ($id) {
            $data = $this->CoursdechangeManager->findById($id);
            if (!$data)
                $data = array();
        } else {
			$data=array();
			$menu = $this->CoursdechangeManager->findAll();	
            if ($menu) {
                foreach ($menu as $key => $value) {
					// Description devise
                    $devise = array();
                    $devise_temp = $this->DeviseManager->findById($value->id_devise);
					if(count($devise_temp) >0) {
						$devise=$devise_temp;
					}
                    $data[$key]['id'] = $value->id;
                    $data[$key]['id_devise'] = $value->id_devise;
                    $data[$key]['devise'] = $devise;
                    $data[$key]['date_cours'] = $value->date_cours;
                    $data[$key]['cours'] = $value->cours;
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
		// Affectation des valeurs de chaque colonne de la table
        $id = $this->post('id') ;
        $supprimer = $this->post('supprimer') ;
		$cours=null;
		$temporaire=$this->post('cours');
		if(isset($temporaire) && $temporaire !="" && intval($temporaire) >0) {
			$cours=$temporaire;
		}
		$data = array(
			'id_devise'   => $this->post('id_devise'),
			'date_cours'  => $this->post('date_cours'),
			'cours'       => $cours,
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
                $dataId = $this->CoursdechangeManager->add($data);
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
                $update = $this->CoursdechangeManager->update($id, $data);
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
			// Suppression d'un enregistrement
            $delete = $this->CoursdechangeManager->delete($id);
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