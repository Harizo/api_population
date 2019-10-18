<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Acteur extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('acteur_model', 'ActeurManager');
        $this->load->model('type_acteur_model', 'TypeacteurManager');
    }

    public function index_get() {
        $id = $this->get('id');
		$data = array();
		if ($id) {
			$tmp = $this->ActeurManager->findById($id);
			if($tmp) {
				$typeacteur = $this->TypeacteurManager->findByIdArray($tmp->id_type_acteur);
                $data['id'] = $tmp->id;
                $data['nom'] = $tmp->nom;
                $data['contact'] = $tmp->contact;
                $data['representant'] = $tmp->representant;
                $data['adresse'] = $tmp->adresse;
				$data['id_type_acteur'] = $tmp->id_type_acteur;
				$data['typeacteur'] = $typeacteur;
			}
		} else {			
			$tmp = $this->ActeurManager->findAll();
			if ($tmp) {
				foreach ($tmp as $key => $value) {
					$typeacteur = $this->TypeacteurManager->findByIdArray($value->id_type_acteur);
					$data[$key]['id'] = $value->id;
					$data[$key]['nom'] = $value->nom;
					$data[$key]['contact'] = $value->contact;
					$data[$key]['representant'] = $value->representant;
					$data[$key]['adresse'] = $value->adresse;
					$data[$key]['id_type_acteur'] = $value->id_type_acteur;
					$data[$key]['typeacteur'] = $typeacteur;
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
		$tmp=$this->post('id_type_acteur');
		$id_type_acteur=null;
		if(isset($tmp) && $tmp !="" && intval($tmp) >0) {
			$id_type_acteur=$tmp;
		}		
		$data = array(
			'nom'          => $this->post('nom'),
			'representant' => $this->post('representant'),
			'contact'      => $this->post('contact'),
			'adresse'      => $this->post('adresse'),
			'id_type_acteur' => $id_type_acteur
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
                $dataId = $this->ActeurManager->add($data);              
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
                $update = $this->ActeurManager->update($id, $data);              
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
            $delete = $this->ActeurManager->delete($id);          
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