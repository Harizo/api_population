<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Secteur_programme_projet extends REST_Controller {

    public function __construct() {
        parent::__construct();
		$this->load->model('secteur_programme_projet_model', 'SecteurprogrammeManager');
    }
    public function index_get() {
        $id = $this->get('id');
        if ($id) {
			// Récupération par id (id= clé primaire)
            $data = $this->SecteurprogrammeManager->findById($id);
            if (!$data)
                $data = array();
        } else {
			$data=array();
			// Récupération de tous les enregistrements
			$menu = $this->SecteurprogrammeManager->findAll();	
            if ($menu) {
                foreach ($menu as $key => $value) {
                    $data[$key]['id'] = $value->id;
                    $data[$key]['id_programme'] = $value->id_programme;
                    $data[$key]['id_financement_programme'] = $value->id_financement_programme;
                    $data[$key]['id_type_secteur'] = $value->id_type_secteur;
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
		// Récupération des données
        $id = $this->post('id') ;
        $supprimer = $this->post('supprimer') ;
		$id_programme=null;
		$temporaire=$this->post('id_programme');
		if(isset($temporaire) && $temporaire !="" && intval($temporaire) >0) {
			$id_programme=$temporaire;
		}
		$id_financement_programme=null;
		$temporaire=$this->post('id_financement_programme');
		if(isset($temporaire) && $temporaire !="" && intval($temporaire) >0) {
			$id_financement_programme=$temporaire;
		}
		$id_type_secteur=null;
		$temporaire=$this->post('id_type_secteur');
		if(isset($temporaire) && $temporaire !="" && intval($temporaire) >0) {
			$id_type_secteur=$temporaire;
		}
		// Affectation de valeur de la colonne de la table
		$data = array(
			'id_programme'           => $this->post('id_programme'),
			'id_financement_programme' => $this->post('id_financement_programme'),
			'id_type_secteur'         => $this->post('id_type_secteur'),
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
				// Ajour d'un enregistrement
                $dataId = $this->SecteurprogrammeManager->add($data);
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
                $update = $this->SecteurprogrammeManager->update($id, $data);
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
            $delete = $this->SecteurprogrammeManager->delete($id);
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
?>