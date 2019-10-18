<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Programme extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('programme_model', 'ProgrammeManager');
        $this->load->model('type_action_model', 'TypeactionManager');
        $this->load->model('type_financement_model', 'TypefinancementManager');
        $this->load->model('tutelle_model', 'TutelleManager');
    }

    public function index_get() {
        $id = $this->get('id');
		$data = array();
		if ($id) {
			$tmp = $this->ProgrammeManager->findById($id);
			if($tmp) {
				$data=$tmp;
			}
		} else {			
			$menu = $this->ProgrammeManager->findAll();
			if ($menu) {
                foreach ($menu as $key => $value) {
                    $typefinacement = array();
                    $type_fin = $this->TypefinancementManager->findById($value->id_type_financement);
					if(count($type_fin) >0) {
						$typefinacement=$type_fin;
					}	
                    $typeaction = array();
                    $type_ac = $this->TypeactionManager->findById($value->id_type_action);
					if(count($type_ac) >0) {
						$typeaction=$type_ac;
					}	
                    $tutelle = array();
                    $tut = $this->TutelleManager->findById($value->id_tutelle);
					if(count($tut) >0) {
						$tutelle=$tut;
					}	
                    $data[$key]['id'] = $value->id;
                    $data[$key]['nom'] = $value->nom;
                    $data[$key]['prenom'] = $value->prenom;
                    $data[$key]['telephone'] = $value->telephone;
                    $data[$key]['email'] = $value->email;
                    $data[$key]['id_tutelle'] = $value->id_tutelle;
                    $data[$key]['tutelle'] = $tutelle;
                    $data[$key]['id_type_action'] = $value->id_type_action;
                    $data[$key]['typeaction'] = $typeaction;
                    $data[$key]['intitule'] = $value->intitule;
                    $data[$key]['situation_intervention'] = $value->situation_intervention;
                    $data[$key]['id_type_financement'] = $value->id_type_financement;
                    $data[$key]['typefinacement'] = $typefinacement;
                    $data[$key]['date_debut'] = $value->date_debut;
                    $data[$key]['date_fin'] = $value->date_fin;
                    $data[$key]['description'] = $value->description;
                    $data[$key]['flag_integration_donnees'] = $value->flag_integration_donnees;
                    $data[$key]['nouvelle_integration'] = $value->nouvelle_integration;
                    $data[$key]['commentaire'] = $value->commentaire;
                    $data[$key]['identifiant'] = $value->identifiant;
                    $data[$key]['inscription_budgetaire'] = $value->inscription_budgetaire;
                    $data[$key]['detail_charge'] = 0;
                    $data[$key]['detail_zone_intervention_programme'] = array();
                    $data[$key]['detail_financement_programme'] = array();
                    $data[$key]['detail_intervention'] = array();
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
		$id_type_financement=null;
		$tmp=$this->post('id_type_financement');
		if(isset($tmp) && $tmp !="" && intval($tmp) >0) {
			$id_type_financement=$tmp;
		}
		$id_type_action=null;
		$tmp=$this->post('id_type_action');
		if(isset($tmp) && $tmp !="" && intval($tmp) >0) {
			$id_type_action=$tmp;
		}
		$id_tutelle=null;
		$tmp=$this->post('id_tutelle');
		if(isset($tmp) && $tmp !="" && intval($tmp) >0) {
			$id_tutelle=$tmp;
		}
 		$data = array(
			'nom' => $this->post('nom'),
			'prenom'                   => $this->post('prenom'),
			'telephone'                => $this->post('telephone'),
			'email'                    => $this->post('email'),
			'id_tutelle'               => $id_tutelle,
			'id_type_action'           => $id_type_action,
			'intitule'                 => $this->post('intitule'),
			'situation_intervention'   => $this->post('situation_intervention'),
			'id_type_financement'      => $id_type_financement,
			'date_debut'               => $this->post('date_debut'),
			'date_fin'                 => $this->post('date_fin'),
			'description'              => $this->post('description'),
			'flag_integration_donnees' => $this->post('flag_integration_donnees'),
			'nouvelle_integration'     => $this->post('nouvelle_integration'),
			'commentaire'              => $this->post('commentaire'),
			'identifiant'              => $this->post('identifiant'),
			'inscription_budgetaire'   => $this->post('inscription_budgetaire'),
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
                $dataId = $this->ProgrammeManager->add($data);              
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
                $update = $this->ProgrammeManager->update($id, $data);              
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
            $delete = $this->ProgrammeManager->delete($id);          
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