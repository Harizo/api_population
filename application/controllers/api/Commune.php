<?php
//harizo
defined('BASEPATH') OR exit('No direct script access allowed');

// afaka fafana refa ts ilaina
require APPPATH . '/libraries/REST_Controller.php';

class Commune extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('commune_model', 'CommuneManager');
        $this->load->model('district_model', 'DistrictManager');
    }
    
    public function index_get() 
    {
        set_time_limit(0);
        ini_set ('memory_limit', '2048M');
        $id = $this->get('id');
        $cle_etrangere = $this->get('cle_etrangere');
        $id_commune = $this->get('id_commune');
        $id_district = $this->get('id_district');
        $id_region = $this->get('id_region');
        if ($cle_etrangere) {
            $data = array();
			// Récupération des enregistrements par district
            $temporaire = $this->CommuneManager->findAllByDistrict($cle_etrangere);
            if ($temporaire) 
            {
                foreach ($temporaire as $key => $value) 
                {
                    $district = array();
					// Récupération description district
                    $district = $this->DistrictManager->findByIdOLD($value->district_id);
                    $data[$key]['id'] = $value->id;
                    $data[$key]['code'] = $value->code;
                    $data[$key]['nom'] = $value->nom;
                    $data[$key]['coordonnees'] = unserialize($value->coordonnees);
                    $data[$key]['district'] = $district;
                }
            }           
        } else {
            if ($id)  {
				// Récupération par id (id=clé primaire)
                $data = array();
                $data = $this->CommuneManager->findById($id);
            } else if($id_commune) {
				$menu = $this->CommuneManager->find_Fokontany_avec_District_et_Region($id_commune);
                if ($menu) {
					$data=$menu;
                } else
                    $data = array();
			} else if($id_district) {	
				// Récupération des communes par district 
				$menu = $this->CommuneManager->find_Commune_avec_District_et_Region($id_district);
                if ($menu) {
					$data=$menu;
                } else
                    $data = array();
			} else {
				// Récupération de tous les enregistrements
                $menu = $this->CommuneManager->findAll();
                if ($menu) {
                    foreach ($menu as $key => $value) {
                        $district = array();
                        $district = $this->DistrictManager->findById($value->district_id);
                        $data[$key]['id'] = $value->id;
                        $data[$key]['code'] = $value->code;
                        $data[$key]['nom'] = $value->nom;
                        $data[$key]['coordonnees'] = unserialize($value->coordonnees);
                        $data[$key]['district_id'] = $value->district_id;
                        $data[$key]['district'] = $district;
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
    //insertion,modification,suppression commune
    public function index_post() {
        $id = $this->post('id') ;
        $supprimer = $this->post('supprimer') ;
        if ($supprimer == 0) {
            if ($id == 0) {
                $data = array(
                    'code' => $this->post('code'),
                    'nom' => $this->post('nom'),
                    'coordonnees' => $this->post('coordonnees'),
                    'district_id' => $this->post('district_id')
                );
                if (!$data) {
                    $this->response([
                        'status' => FALSE,
                        'response' => 0,
                        'message' => 'No request found'
                            ], REST_Controller::HTTP_BAD_REQUEST);
                }
				// Ajout d'un enregistrement
                $dataId = $this->CommuneManager->add($data);
                if (!is_null($dataId))  {
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
                    'nom' => $this->post('nom'),
                    'coordonnees' => $this->post('coordonnees'),
                    'district_id' => $this->post('district_id')
                );
                if (!$data || !$id) {
                    $this->response([
                        'status' => FALSE,
                        'response' => 0,
                        'message' => 'No request found'
                            ], REST_Controller::HTTP_BAD_REQUEST);
                }
				// Mise à jour d'un enregistrement
                $update = $this->CommuneManager->update($id, $data);
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
            $delete = $this->CommuneManager->delete($id);
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
