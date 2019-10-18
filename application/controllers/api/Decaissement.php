<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Decaissement extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('decaissement_model', 'DecaissementManager');
        $this->load->model('acteur_model', 'ActeurManager');
        $this->load->model('financement_intervention_model', 'FinancementinterventionManager');
    }

    public function index_get() {
        $id = $this->get('id');
        $cle_etrangere = $this->get('cle_etrangere');
		$data = array();
		if ($id) {
			$tmp = $this->DecaissementManager->findById($id);
			if($tmp) {
				$data=$tmp;
			}
			$ou=1;
		} else if($cle_etrangere) {
			$menu = $this->DecaissementManager->findAllByFinancementintervention($cle_etrangere);
			$ou=2;
		} else {		
			$menu = $this->DecaissementManager->findAll();
			$ou=2;
		}
		if($ou==2) {
			if ($menu) {
                foreach ($menu as $key => $value) {
                    $financementintervention = array();
                    $type_fin = $this->FinancementinterventionManager->findById($value->id_financement_intervention);
					if(count($type_fin) >0) {
						$financementintervention=$type_fin;
					}	
                    $acteur = array();
                    $ag = $this->ActeurManager->findById($value->id_acteur);
					if(count($ag) >0) {
						$acteur=$ag;
					}	
                    $data[$key]['id'] = $value->id;
                    $data[$key]['id_financement_intervention'] = $value->id_financement_intervention;
                    $data[$key]['financementintervention'] = $financementintervention;
					$data[$key]['nom_informateur'] = $value->nom_informateur;
                    $data[$key]['prenom_informateur'] = $value->prenom_informateur;
                    $data[$key]['telephone_informateur'] = $value->telephone_informateur;
                    $data[$key]['email_informateur'] = $value->email_informateur;
                    $data[$key]['id_acteur'] = $value->id_acteur;
                    $data[$key]['acteur'] = $acteur;
                    $data[$key]['montant_initial'] = $value->montant_initial;
                    $data[$key]['montant_revise'] = $value->montant_revise;
                    $data[$key]['date_revision'] = $value->date_revision;
                    $data[$key]['montant_mesure_accompagnement'] = $value->montant_mesure_accompagnement;
                    $data[$key]['decaissement_prevu'] = $value->decaissement_prevu;
                    $data[$key]['decaissement_effectif'] = $value->decaissement_effectif;
                    $data[$key]['decaissement_prevu_cumule'] = $value->decaissement_prevu_cumule;
                    $data[$key]['decaissement_cumule'] = $value->decaissement_cumule;
                    $data[$key]['decaissement_effectif_beneficiaire'] = $value->decaissement_effectif_beneficiaire;
                    $data[$key]['decaissement_effectif_beneficiaire_cumule'] = $value->decaissement_effectif_beneficiaire_cumule;
                    $data[$key]['nombre_beneficiaire'] = $value->nombre_beneficiaire;
                    $data[$key]['nombre_beneficiaire_cumule'] = $value->nombre_beneficiaire_cumule;
                    $data[$key]['nombre_beneficiaire_sortant'] = $value->nombre_beneficiaire_sortant;
                    $data[$key]['nombre_beneficiaire_sortant_cumule'] = $value->nombre_beneficiaire_sortant_cumule;
                    $data[$key]['transfert_direct_beneficiaire'] = $value->transfert_direct_beneficiaire;
                    $data[$key]['date_debut_periode'] = $value->date_debut_periode;
                    $data[$key]['date_fin_periode'] = $value->date_fin_periode;
                    $data[$key]['flag_integration_donnees'] = $value->flag_integration_donnees;
                    $data[$key]['nouvelle_integration'] = $value->nouvelle_integration;
                    $data[$key]['commentaire'] = $value->commentaire;
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
		$id_financement_intervention=null;
		$tmp=$this->post('id_financement_intervention');
		if(isset($tmp) && $tmp !="" && intval($tmp) >0) {
			$id_financement_intervention=$tmp;
		}
		$id_acteur=null;
		$tmp=$this->post('id_acteur');
		if(isset($tmp) && $tmp !="" && intval($tmp) >0) {
			$id_acteur=$tmp;
		}
		$date_revision=null;
		$date_debut_periode=null;
		$date_fin_periode=null;
		if($this->post('date_revision')) {
			$date_revision=$this->post('date_revision');
		}
		if($this->post('date_debut_periode')) {
			$date_debut_periode=$this->post('date_debut_periode');
		}
		if($this->post('date_fin_periode')) {
			$date_fin_periode=$this->post('date_fin_periode');
		}
		$montant_initial=null;
		if($this->post('montant_initial')) {
			$montant_initial=$this->post('montant_initial');
		}
		$montant_revise=null;
		if($this->post('montant_revise')) {
			$montant_revise=$this->post('montant_revise');
		}
		$montant_mesure_accompagnement=null;
		if($this->post('montant_mesure_accompagnement')) {
			$montant_mesure_accompagnement=$this->post('montant_mesure_accompagnement');
		}
		$decaissement_prevu=null;
		if($this->post('decaissement_prevu')) {
			$decaissement_prevu=$this->post('decaissement_prevu');
		}
		$decaissement_effectif=null;
		if($this->post('decaissement_effectif')) {
			$decaissement_effectif=$this->post('decaissement_effectif');
		}
		$decaissement_prevu_cumule=null;
		if($this->post('decaissement_prevu_cumule')) {
			$decaissement_prevu_cumule=$this->post('decaissement_prevu_cumule');
		}
		$decaissement_cumule=null;
		if($this->post('decaissement_cumule')) {
			$decaissement_cumule=$this->post('decaissement_cumule');
		}
		$decaissement_effectif_beneficiaire=null;
		if($this->post('decaissement_effectif_beneficiaire')) {
			$decaissement_effectif_beneficiaire=$this->post('decaissement_effectif_beneficiaire');
		}
		$decaissement_effectif_beneficiaire_cumule=null;
		if($this->post('decaissement_effectif_beneficiaire_cumule')) {
			$decaissement_effectif_beneficiaire_cumule=$this->post('decaissement_effectif_beneficiaire_cumule');
		}
		$nombre_beneficiaire=null;
		if($this->post('nombre_beneficiaire')) {
			$nombre_beneficiaire=$this->post('nombre_beneficiaire');
		}
		$nombre_beneficiaire_cumule=null;
		if($this->post('nombre_beneficiaire_cumule')) {
			$nombre_beneficiaire_cumule=$this->post('nombre_beneficiaire_cumule');
		}
		$nombre_beneficiaire_sortant=null;
		if($this->post('nombre_beneficiaire_sortant')) {
			$nombre_beneficiaire_sortant=$this->post('nombre_beneficiaire_sortant');
		}
		$nombre_beneficiaire_sortant_cumule=null;
		if($this->post('nombre_beneficiaire_sortant_cumule')) {
			$nombre_beneficiaire_sortant_cumule=$this->post('nombre_beneficiaire_sortant_cumule');
		}
		$transfert_direct_beneficiaire=null;
		if($this->post('transfert_direct_beneficiaire')) {
			$transfert_direct_beneficiaire=$this->post('transfert_direct_beneficiaire');
		}
 		$data = array(
			'id_financement_intervention'               => $this->post('id_financement_intervention'),
			'nom_informateur'                           => $this->post('nom_informateur'),
			'prenom_informateur'                        => $this->post('prenom_informateur'),
			'telephone_informateur'                     => $this->post('telephone_informateur'),
			'email_informateur'                         => $this->post('email_informateur'),
			'id_acteur'                                 => $id_acteur,
			'montant_initial'                           => $montant_initial,
			'montant_revise'                            => $montant_revise,
			'date_revision'                             => $date_revision,
			'montant_mesure_accompagnement'             => $montant_mesure_accompagnement,
			'decaissement_prevu'                        =>$decaissement_prevu,
			'decaissement_effectif'                     => $decaissement_effectif,
			'decaissement_prevu_cumule'                 => $decaissement_prevu_cumule,
			'decaissement_cumule'                       => $decaissement_cumule,
			'decaissement_effectif_beneficiaire'        => $decaissement_effectif_beneficiaire,
			'decaissement_effectif_beneficiaire_cumule' => $decaissement_effectif_beneficiaire_cumule,
			'nombre_beneficiaire'                       => $nombre_beneficiaire,
			'nombre_beneficiaire_cumule'                => $nombre_beneficiaire_cumule,
			'nombre_beneficiaire_sortant'               => $nombre_beneficiaire_sortant,
			'nombre_beneficiaire_sortant_cumule'        => $nombre_beneficiaire_sortant_cumule,
			'transfert_direct_beneficiaire'             => $transfert_direct_beneficiaire,
			'date_debut_periode'                        => $date_debut_periode,
			'date_fin_periode'                          => $date_fin_periode,
			'flag_integration_donnees'                  => $this->post('flag_integration_donnees'),
			'nouvelle_integration'                      => $this->post('nouvelle_integration'),
			'commentaire'                               => $this->post('commentaire'),
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
                $dataId = $this->DecaissementManager->add($data);              
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
                $update = $this->DecaissementManager->update($id, $data);              
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
            $delete = $this->DecaissementManager->delete($id);          
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