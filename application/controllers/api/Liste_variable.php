<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Liste_variable extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('liste_variable_model', 'ListevariableManager');
        $this->load->model('variable_model', 'VariableManager');
    }
    //recuperation liste variable
    public function index_get() {
        $id = $this->get('id');
		if ($id) {
			$data = array();
			$listevar = $this->ListevariableManager->findById($id);
			$data['id'] = $listevar->id;
			$data['code'] = $listevar->code;
			$data['description'] = $listevar->description;			
		} else {
			$listevar = $this->ListevariableManager->findAll();
			if ($listevar) {
				foreach ($listevar as $key => $value) {                      
					$data[$key]['id'] = $value->id;
					$data[$key]['code'] = $value->code;
					$data[$key]['description'] = $value->description;  
					$data[$key]['detail_variable']=array();	
					// Détail liste variable pour chaque id
					$temporaire = $this->VariableManager->findAllByIdlistevariable($value->id);
					if($temporaire) {
						$data[$key]['detail_variable']=$temporaire;
					}
				};
			} else
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
    //insertion,modification,suppression liste variable
    public function index_post() {
        $id = $this->post('id') ;
        $supprimer = $this->post('supprimer') ;
        if ($supprimer == 0) {
            if ($id == 0) {
				// Nouvel enregistrement
                $data = array(
                    'code' => $this->post('code'),
                    'description' => $this->post('description'),
                );               
                if (!$data) {
                    $this->response([
                        'status' => FALSE,
                        'response' => 0,
                        'message' => 'No request found'
                            ], REST_Controller::HTTP_BAD_REQUEST);
                }
                $dataId = $this->ListevariableManager->add($data);              
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
				// Mise à jour d'un enregistrement
                $data = array(
                    'code' => $this->post('code'),
                    'description' => $this->post('description')
                );              
                if (!$data || !$id) {
                    $this->response([
                        'status' => FALSE,
                        'response' => 0,
                        'message' => 'No request found'
                            ], REST_Controller::HTTP_BAD_REQUEST);
                }
                $update = $this->ListevariableManager->update($id, $data);              
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
            $delete = $this->ListevariableManager->delete($id);          
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
?>