<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Privilege_groupe extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('privilege_groupe_model', 'Privilege_groupeManager');
    }

    public function index_get() {
        $id = $this->get('id');
        $id_groupe = $this->get('id_groupe');
		$data = array();


		if ($id) 
        {
			// Récupération par id (id=clé primaire)
			$tmp = $this->Privilege_groupeManager->findById($id);
			if($tmp) {
				$data=$tmp;
			}
		} 
        else 
        {	
			if ($id_groupe) 
            {
                $tmp = $this->Privilege_groupeManager->findBygroupe($id_groupe);
                if($tmp) {
                    foreach ($tmp as $key => $value) 
                    {
                        $data[$key]['privileges'] = unserialize($value->privileges);
                        $data[$key]['id'] = ($value->id);
                        $data[$key]['id_groupe'] = ($value->id_groupe);
                    }
                }
            }
            else
            {
                // Récupération de tous les enregistrements
                $tmp = $this->Privilege_groupeManager->findAll();
                if ($tmp) 
                {
                    foreach ($tmp as $key => $value) 
                    {
                        $data[$key]['privileges'] = unserialize($value->privileges);
                        $data[$key]['id_groupe'] = ($value->id_groupe);
                    }
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
		// Affectation de valeur de la colonne
		$data = array(
			'privileges' => serialize($this->post('privileges')),
			'id_groupe' => $this->post('id_groupe')
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
                $dataId = $this->Privilege_groupeManager->add($data);              
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
                $update = $this->Privilege_groupeManager->update($id, $data);              
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
            $delete = $this->Privilege_groupeManager->delete($id);          
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