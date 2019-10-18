<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Acteur extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('acteur_model', 'ActeurManager');
        $this->load->model('fokontany_model', 'FokontanyManager');
        $this->load->model('type_acteur_model', 'TypeacteurManager');
    }

    public function index_get() {
        $id = $this->get('id');
        $nom_partenaire = strtolower($this->get('nom_partenaire'));
		$data = array();
		if($nom_partenaire) {
			$data=$this->ActeurManager->findByNom($nom_partenaire);
		} else	if ($id) {
				$tmp = $this->ActeurManager->findById($id);
				if($tmp) {
					$data=$tmp;
				}
			} else {			
				$menu = $this->ActeurManager->findAll();
				if ($menu) {
					foreach ($menu as $key => $value) {
						$fokontany = array();
						$type_fin = $this->FokontanyManager->findById($value->id_fokontany);
						if(count($type_fin) >0) {
							$fokontany=$type_fin;
						}	
						$typeacteur = array();
						$type_fin = $this->TypeacteurManager->findById($value->id_type_acteur);
						if(count($type_fin) >0) {
							$typeacteur=$type_fin;
						}	
						$data[$key]['id'] = $value->id;
						$data[$key]['nom'] = $value->nom;
						$data[$key]['nif'] = $value->nif;
						$data[$key]['stat'] = $value->stat;
						$data[$key]['adresse'] = $value->adresse;
						$data[$key]['id_fokontany'] = $value->id_fokontany;
						$data[$key]['fokontany'] = $fokontany;
						$data[$key]['id_type_acteur'] = $value->id_type_acteur;
						$data[$key]['typeacteurs'] = $typeacteur;
						$data[$key]['representant'] = $value->representant;
						$data[$key]['fonction'] = $value->fonction;
						$data[$key]['telephone'] = $value->telephone;
						$data[$key]['email'] = $value->email;
						$data[$key]['rcs'] = $value->rcs;
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
		$id_fokontany=null;
		$tmp=$this->post('id_fokontany');
		if(isset($tmp) && $tmp !="" && intval($tmp) >0) {
			$id_fokontany=$tmp;
		}
		$id_type_acteur=null;
		$tmp=$this->post('id_type_acteur');
		if(isset($tmp) && $tmp !="" && intval($tmp) >0) {
			$id_type_acteur=$tmp;
		}
 		$data = array(
			'nom' => $this->post('nom'),
			'nif' => $this->post('nif'),
			'stat' => $this->post('stat'),
			'adresse' => $this->post('adresse'),
			'id_fokontany' => $id_fokontany,
			'representant' => $this->post('representant'),
			'fonction' => $this->post('fonction'),
			'telephone' => $this->post('telephone'),
			'email' => $this->post('email'),
			'rcs' => $this->post('rcs'),
			'id_type_acteur' => $id_type_acteur,
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