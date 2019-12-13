<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Enquete_sur_menage extends REST_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('enquete_sur_menage_model', 'EnquetesurmenageManager');
    }
    public function index_get() {
        $id = $this->get('id');
        $cle_etrangere = $this->get('cle_etrangere');
        $data = array() ;
		if($id) {
			// Récupération par id (id=cle primaire)
			$data = $this->EnquetesurmenageManager->findById($id);
            if (!$data)
                $data = array();
			$choix=1;
		} else if ($cle_etrangere)  {
			// Récupération par ménage
            $menu = $this->EnquetesurmenageManager->findAllByMenage($cle_etrangere);
			$choix=2;
        } else  {
			// Récupération de tous les enregistrements
			$menu = $this->EnquetesurmenageManager->findAll();
			$choix=2;
        }
		if($choix==2) {
            if ($menu)  {
				// Affectation des valeurs dans un tableau
                $data['id'] = ($menu->id);
                $data['id_menage'] = $menu->id_menage;
                $data['id_type_logement'] = $menu->id_type_logement;
                $data['id_occupation_logement'] = $menu->id_occupation_logement;
                $data['revetement_toit'] = unserialize($menu->revetement_toit);
                $data['revetement_sol'] = unserialize($menu->revetement_sol);
                $data['revetement_mur'] = unserialize($menu->revetement_mur);
                $data['source_eclairage'] = unserialize($menu->source_eclairage);
                $data['combustible'] = unserialize($menu->combustible);
                $data['toilette'] = unserialize($menu->toilette);
                $data['source_eau'] = unserialize($menu->source_eau);
                $data['bien_equipement'] = unserialize($menu->bien_equipement);
                $data['moyen_production'] = unserialize($menu->moyen_production);
                $data['source_revenu'] = unserialize($menu->source_revenu);
                $data['elevage'] = unserialize($menu->elevage);
                $data['culture'] = unserialize($menu->culture);
                $data['aliment'] = unserialize($menu->aliment);
                $data['source_aliment'] = unserialize($menu->source_aliment);
                $data['strategie_alimentaire'] = unserialize($menu->strategie_alimentaire);
                $data['probleme_sur_revenu'] = unserialize($menu->probleme_sur_revenu);
                $data['strategie_sur_revenu'] = unserialize($menu->strategie_sur_revenu);
                $data['activite_recours'] = unserialize($menu->activite_recours);
                $data['service_beneficie'] = unserialize($menu->service_beneficie);
                $data['infrastructure_frequente'] = unserialize($menu->infrastructure_frequente);
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
		// Affectation des valeurs des colonnes de la table
		$data = array(
			'id_menage' => $this->post('id_menage'),
			'id_type_logement'         => $this->post('id_type_logement'),
			'id_occupation_logement'   => $this->post('id_occupation_logement'),
			'revetement_toit'          => serialize($this->post('revetement_toit')),
			'revetement_sol'           => serialize($this->post('revetement_sol')),
			'revetement_mur'           => serialize($this->post('revetement_mur')),
			'source_eclairage'         => serialize($this->post('source_eclairage')),
			'combustible'              => serialize($this->post('combustible')),
			'toilette'                 => serialize($this->post('toilette')),
			'source_eau'               => serialize($this->post('source_eau')),
			'bien_equipement'          => serialize($this->post('bien_equipement')),
			'moyen_production'         => serialize($this->post('moyen_production')),
			'source_revenu'            => serialize($this->post('source_revenu')),
			'elevage'                  => serialize($this->post('elevage')),
			'culture'                  => serialize($this->post('culture')),
			'aliment'                  => serialize($this->post('aliment')),
			'source_aliment'           => serialize($this->post('source_aliment')),
			'strategie_alimentaire'    => serialize($this->post('strategie_alimentaire')),
			'probleme_sur_revenu'      => serialize($this->post('probleme_sur_revenu')),
			'strategie_sur_revenu'     => serialize($this->post('strategie_sur_revenu')),
			'activite_recours'         => serialize($this->post('activite_recours')),
			'service_beneficie'        => serialize($this->post('service_beneficie')),
			'infrastructure_frequente' => serialize($this->post('infrastructure_frequente')),
		);               
        if ($supprimer == 0)  {
            if ($id == 0) {
                if (!$data) {
                    $this->response([
                        'status' => FALSE,
                        'response' => 0,
                        'message' => 'No request found'
                            ], REST_Controller::HTTP_BAD_REQUEST);
                }
				// Ajout d'un enregistrement
                $dataId = $this->EnquetesurmenageManager->add($data);
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
                $update = $this->EnquetesurmenageManager->update($id, $data);              
                if(!is_null($update)){
                    $this->response([
                        'status' => TRUE, 
                        'response' => $id,
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
            $delete = $this->EnquetesurmenageManager->delete($id);          
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