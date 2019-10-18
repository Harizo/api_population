<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Acteur_regional extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('acteur_regional_model', 'ActeurregionalManager');
        $this->load->model('region_model', 'RegionManager');
        $this->load->model('type_acteur_model', 'TypeacteurManager');
    }

    public function index_get() {
        $id = $this->get('id');
		$data = array();
		if ($id) {
			$tmp = $this->ActeurregionalManager->findById($id);
			if($tmp) {
                $region = $this->RegionManager->findByIdArray($tmp->id_region);
				$typeacteur = $this->TypeacteurManager->findByIdArray($tmp->id_type_acteur);
                $data['id'] = $tmp->id;
                $data['nom'] = $tmp->nom;
                $data['contact'] = $tmp->contact;
                $data['representant'] = $tmp->representant;
                $data['adresse'] = $tmp->adresse;
                $data['id_region'] = $tmp->id_region;
                $data['region'] = $region;
                $data['id_type_acteur'] = $tmp->id_type_acteur;
				$data['typeacteur'] = $typeacteur;
			}
		} else {			
			$tmp = $this->ActeurregionalManager->findAll();
			if ($tmp) {
				foreach ($tmp as $key => $value) {
					$region = $this->RegionManager->findByIdArray($value->id_region);
					$typeacteur = $this->TypeacteurManager->findByIdArray($value->id_type_acteur);
					$data[$key]['id'] = $value->id;
					$data[$key]['nom'] = $value->nom;
					$data[$key]['contact'] = $value->contact;
					$data[$key]['representant'] = $value->representant;
					$data[$key]['adresse'] = $value->adresse;
					$data[$key]['id_region'] = $value->id_region;
					$data[$key]['region'] = $region;
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
		$id_region=null;
		$tmp=$this->post('id_region') ;
		if($tmp && intval($tmp) >0) {
			$id_region=$tmp;
		}
		$tmp=$this->post('id_type_acteur');
		$id_type_acteur=null;
		if(isset($tmp) && $tmp !="" && intval($tmp) >0) {
			$id_type_acteur=$tmp;
		}		
		$data = array(
			'nom'            => $this->post('nom'),
			'representant'   => $this->post('representant'),
			'contact'        => $this->post('contact'),
			'adresse'        => $this->post('adresse'),
			'id_region'      => $id_region,
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
                $dataId = $this->ActeurregionalManager->add($data);              
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
                $update = $this->ActeurregionalManager->update($id, $data);              
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
            $delete = $this->ActeurregionalManager->delete($id);          
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