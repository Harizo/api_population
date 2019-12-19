<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Nomenclature_intervention3 extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('nomenclature_intervention3_model', 'Nomenclatureintervention3Manager');
        $this->load->model('nomenclature_intervention2_model', 'Nomenclatureintervention2Manager');
    }
    //recuperation nomenclature intervention  niveau 2
    public function index_get() {
        $id = $this->get('id');
        $cle_etrangere = $this->get('cle_etrangere');
        if ($cle_etrangere){
            $data = array();
			// Récupération de tous les nomenclature intervention par id_nomenclature2
            $temporaire = $this->Nomenclatureintervention3Manager->findAllByNomenclature2($cle_etrangere);
            if ($temporaire) 
            {
                foreach ($temporaire as $key => $value) 
                {
					// Récupération description nomenclature intervention niveau 1
                    $nomenclature2 = array();
                    $nomenclature2 = $this->Nomenclatureintervention2Manager->findById($value->id_nomenclature2);
                    $data[$key]['id'] = $value->id;
                    $data[$key]['code'] = $value->code;
                    $data[$key]['description'] = $value->description;
                    $data[$key]['nomenclature2'] = $nomenclature2;
                }
            }
            
        } else {
            if ($id) {
                $data = array();
				// Récupération par id
                $data = $this->Nomenclatureintervention3Manager->findById($id);
            } else {
				// Récupération de tous les nomenclature intervention niveau 2
                $menu = $this->Nomenclatureintervention3Manager->findAll();
                if ($menu) {
                    foreach ($menu as $key => $value) {
                        $nomenclature2 = array();
                        $nomenclature2 = $this->Nomenclatureintervention2Manager->findById($value->id_nomenclature2);
                        $data[$key]['id'] = $value->id;
                        $data[$key]['code'] = $value->code;
                        $data[$key]['description'] = $value->description;
                        $data[$key]['id_nomenclature2'] = $value->id_nomenclature2;
                        $data[$key]['nomenclature2'] = $nomenclature2;
                    }
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
                'status' => FALSE,
                'response' => array(),
                'message' => 'No data were found'
            ], REST_Controller::HTTP_OK);
        }
    }
    //insertion,modification,suppression nomenclateure niveau 3
    public function index_post() {
        $id = $this->post('id') ;
        $supprimer = $this->post('supprimer') ;
        if ($supprimer == 0) {
            if ($id == 0) {
                $data = array(
                    'code' => $this->post('code'),
                    'description' => $this->post('description'),
                    'id_nomenclature2' => $this->post('id_nomenclature2')
                );
                if (!$data) {
                    $this->response([
                        'status' => FALSE,
                        'response' => 0,
                        'message' => 'No request found'
                            ], REST_Controller::HTTP_BAD_REQUEST);
                }
				// Ajout d'un enregistrement
                $dataId = $this->Nomenclatureintervention3Manager->add($data);
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
                    'code' => $this->post('code'),
                    'description' => $this->post('description'),
                    'id_nomenclature2' => $this->post('id_nomenclature2')
                );
                if (!$data || !$id) {
                    $this->response([
                        'status' => FALSE,
                        'response' => 0,
                        'message' => 'No request found'
                    ], REST_Controller::HTTP_BAD_REQUEST);
                }
				// Mise à jour d'un enregistrement
                $update = $this->Nomenclatureintervention3Manager->update($id, $data);
                if(!is_null($update)) {
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
            $delete = $this->Nomenclatureintervention3Manager->delete($id);         
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