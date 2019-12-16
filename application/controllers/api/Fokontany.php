<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Fokontany extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('fokontany_model', 'FokontanyManager');        
        $this->load->model('commune_model', 'CommuneManager');
        $this->load->model('site_model', 'SiteManager');
    }
    //recuperation fokontany
    public function index_get() {
		set_time_limit(0);
        $id = $this->get('id');
        $cle_etrangere = $this->get('cle_etrangere');

        if ($cle_etrangere) {
            $data = array();
			// Récupération des fokontany par commune
            $tmp = $this->FokontanyManager->findAllByCommune($cle_etrangere);
            if ($tmp) 
            {
                foreach ($tmp as $key => $value) 
                {
					// Récupérationdescription commune
                    $commune = array();
                    $commune = $this->CommuneManager->findByIdOLD($value->id_commune);
                    $data[$key]['id'] = $value->id;
                    $data[$key]['code'] = $value->code;
                    $data[$key]['nom'] = $value->nom;
                    $data[$key]['latitude'] = $value->latitude;
                    $data[$key]['longitude'] = $value->longitude;
                    $data[$key]['commune'] = $commune;
                }
            }    
        } else {
            if ($id) {
                $data = array();
				// Récupération par id
                $fokontany = $this->FokontanyManager->findById($id);
                $commune = $this->CommmuneManager->findById($fokontany->id_commune);
                $data['id'] = $fokontany->id;
                $data['code'] = $fokontany->code;
                $data['nom'] = $fokontany->nom;
                $data['latitude'] = $fokontany->latitude;
                $data['longitude'] = $fokontany->longitude;
                $data['commune'] = $commune;
                
            } else {
				// Récupération de tous les fokontany
                $fokontany = $this->FokontanyManager->findAll();
                if ($fokontany) {
                    foreach ($fokontany as $key => $value) {
                        $commune = array();
                        $commune = $this->CommuneManager->findById($value->id_commune);
                        $data[$key]['id'] = $value->id;
                        $data[$key]['code'] = $value->code;
                        $data[$key]['nom'] = $value->nom;
                        $data[$key]['id_commune'] = $value->id_commune;
                        $data[$key]['latitude'] = $value->latitude;
                        $data[$key]['longitude'] = $value->longitude;
                        $data[$key]['commune'] = $commune;

                    };
                } else
                    $data = array();
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
                'status' => TRUE,
                'response' => array(),
                'message' => 'No data were found'
            ], REST_Controller::HTTP_OK);
        }
    }
    //insertion,modification,suppression fokontany
    public function index_post() {
        $id = $this->post('id') ;
        $supprimer = $this->post('supprimer') ;
		$data = array(
			'code' => $this->post('code'),
			'nom' => $this->post('nom'),
			'latitude' => $this->post('latitude'),
			'longitude' => $this->post('longitude'),
			'id_commune' => $this->post('id_commune')
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
                $dataId = $this->FokontanyManager->add($data);              
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
                $update = $this->FokontanyManager->update($id, $data);              
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
            $delete = $this->FokontanyManager->delete($id);          
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