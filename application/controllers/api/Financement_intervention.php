<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Financement_intervention extends REST_Controller {

    public function __construct() { 
        parent::__construct();
        $this->load->model('financement_intervention_model', 'FinancementinterventionManager');
        $this->load->model('intervention_model', 'InterventionManager');
        $this->load->model('sourcefinancement_model', 'SourcefinancementManager');
        $this->load->model('action_strategique_model', 'ActionstrategiqueManager');
        $this->load->model('devise_model', 'DeviseManager');
        $this->load->model('type_secteur_model', 'TypesecteurManager');
    }

    public function index_get() {
        $id = $this->get('id');
        $cle_etrangere = $this->get('cle_etrangere');
		$data = array();
		if ($id) {
			$tmp = $this->FinancementinterventionManager->findById($id);
			if($tmp) {
				$data=$tmp;
			}
			$ou=1;
		} else if($cle_etrangere) {			
			$menu = $this->FinancementinterventionManager->findByIdIntervention($cle_etrangere);
			$ou=2;
		} else {
			$menu = $this->FinancementinterventionManager->findAll();
			$ou=2;			
		}
		if($ou==2) {
			if ($menu) {
                foreach ($menu as $key => $value) {
					$intervention =array();
					if($value->id_intervention) {
						$prg = $this->InterventionManager->findById($value->id_intervention);
						if(count($prg) >0) {
							$intervention=$prg;
						}						
					}					
                    $sourcefinancement = array();
                    $tpf = $this->SourcefinancementManager->findById($value->id_source_financement);
					if(count($tpf) >0) {
						$sourcefinancement=$tpf;
					}
					$actionstrategique =array();
					if($value->id_action_strategique) {
						$reg = $this->ActionstrategiqueManager->findById($value->id_action_strategique);
						if(count($reg) >0) {
							$actionstrategique=$reg;
						}						
					}					
					$devise =array();
					if($value->id_devise) {
						$devi = $this->DeviseManager->findById($value->id_devise);
						if(count($devi) >0) {
							$devise=$devi;
						}						
					}					
					$typesecteur =array();
					if($value->id_type_secteur) {
						$ts = $this->TypesecteurManager->findById($value->id_type_secteur);
						if(count($ts) >0) {
							$typesecteur=$ts;
						}						
					}					
                    $data[$key]['id'] = $value->id;
                    $data[$key]['id_intervention'] = $value->id_intervention;
                    $data[$key]['intervention'] = $intervention;
                    $data[$key]['id_source_financement'] = $value->id_source_financement;
                    $data[$key]['sourcefinancement'] = $sourcefinancement;
                    $data[$key]['id_action_strategique'] = $value->id_action_strategique;
                    $data[$key]['actionstrategique'] = $actionstrategique;
                    $data[$key]['id_devise'] = $value->id_devise;
                    $data[$key]['devise'] = $devise;
                    $data[$key]['id_type_secteur'] = $value->id_type_secteur;
                    $data[$key]['typesecteur'] = $typesecteur;
                    $data[$key]['budget_initial'] = $value->budget_initial;
                    $data[$key]['budget_modifie'] = $value->budget_modifie;
                    $data[$key]['detail_decaissement'] = array();
                    $data[$key]['detail_charge'] = 0;
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
		$id_source_financement=null;
		$tmp=$this->post('id_source_financement');
		if(isset($tmp) && $tmp !="" && intval($tmp) >0) {
			$id_source_financement=$tmp;
		}
		$id_action_strategique=null;
		$tmp=$this->post('id_action_strategique');
		if(isset($tmp) && $tmp !="" && intval($tmp) >0) {
			$id_action_strategique=$tmp;
		}
		$id_devise=null;
		$tmp=$this->post('id_devise');
		if(isset($tmp) && $tmp !="" && intval($tmp) >0) {
			$id_devise=$tmp;
		}
		$id_type_secteur=null;
		$tmp=$this->post('id_type_secteur');
		if(isset($tmp) && $tmp !="" && intval($tmp) >0) {
			$id_type_secteur=$tmp;
		}
 		$data = array(
			'id_intervention' => $this->post('id_intervention'),
			'id_source_financement' => $id_source_financement,
			'id_action_strategique' => $id_action_strategique,
			'id_devise' => $id_devise,
			'id_type_secteur' => $id_type_secteur,
			'budget_initial' => $this->post('budget_initial'),
			'budget_modifie' => $this->post('budget_modifie'),
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
                $dataId = $this->FinancementinterventionManager->add($data);              
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
                $update = $this->FinancementinterventionManager->update($id, $data);              
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
            $delete = $this->FinancementinterventionManager->delete($id);          
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