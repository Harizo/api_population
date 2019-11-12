<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Intervention extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('intervention_model', 'InterventionManager');
        $this->load->model('type_transfert_model', 'TypetrasfertManager');
        $this->load->model('type_action_model', 'TypeactionManager');
        $this->load->model('acteur_model', 'ActeurManager');
        $this->load->model('detail_type_transfert_intervention_model', 'DetailtypetransfertinterventionManager');
        $this->load->model('frequence_transfert_model', 'FrequencetransfertManager');
    }

    public function index_get() {
        $id = $this->get('id');
        $cle_etrangere = $this->get('cle_etrangere');
		$data = array();
		if ($id) {
			$temporaire = $this->InterventionManager->findById($id);
			if($temporaire) {
				// $data=$temporaire;
				$menu=$temporaire;
			} else {
				$menu=array();
			}
			$ou=1;
		} else if($cle_etrangere) {
			$menu = $this->InterventionManager->findByProgramme($cle_etrangere);
			$ou=2;	
		} else {			
			$menu = $this->InterventionManager->findAll();
			$ou=2;
		}
		// if($ou==2) {
			if ($menu) {
                foreach ($menu as $key => $value) {
                    $typetransfert = array();
                    $type_fin = $this->TypetrasfertManager->findById($value->id_type_transfert);
					if(count($type_fin) >0) {
						$typetransfert=$type_fin;
					}	
                    $acteur = array();
                    $ag = $this->ActeurManager->findById($value->id_acteur);
					if(count($ag) >0) {
						$acteur=$ag;
					}	
                    $typeaction = array();
                    $ac = $this->TypeactionManager->findById($value->id_type_action);
					if(count($ac) >0) {
						$typeaction=$ac;
					}	
					$detail_type_transfert_intervention=null;
					$dettpintv= $this->DetailtypetransfertinterventionManager->findByIntervention($value->id);
					if(count($dettpintv) >0) {
						$detail_type_transfert_intervention=$dettpintv;
					}	
					$frequencetransfert=null;
					$freq= $this->FrequencetransfertManager->findById($value->id);
					if(count($freq) >0) {
						$frequencetransfert=$freq;
					}	
					$detail_transfert="";
					$detail_trasfert_temporaire=$this->DetailtypetransfertinterventionManager->findByInterventionParConcatenation($value->id);
					if($detail_trasfert_temporaire) {
						foreach($detail_trasfert_temporaire as $k=>$v) {
							$detail_transfert=$detail_transfert.$v->detail_transfert."; ";
						}
					}
                    $data[$key]['id'] = $value->id;
                    $data[$key]['id_programme'] = $value->id_programme;
                    $data[$key]['identifiant'] = $value->identifiant;
                    $data[$key]['nom_informateur'] = $value->nom_informateur;
                    $data[$key]['prenom_informateur'] = $value->prenom_informateur;
                    $data[$key]['telephone_informateur'] = $value->telephone_informateur;
                    $data[$key]['email_informateur'] = $value->email_informateur;
                    $data[$key]['ministere_tutelle'] = $value->ministere_tutelle;
                    $data[$key]['intitule'] = $value->intitule;
                    $data[$key]['id_acteur'] = $value->id_acteur;
                    $data[$key]['acteur'] = $acteur;
                    $data[$key]['categorie_intervention'] = $value->categorie_intervention;
                    $data[$key]['id_type_action'] = $value->id_type_action;
                    $data[$key]['typeaction'] = $typeaction;
                    $data[$key]['inscription_budgetaire'] = $value->inscription_budgetaire;
                    $data[$key]['programmation'] = $value->programmation;
                    $data[$key]['duree'] = $value->duree;
                    $data[$key]['unite_duree'] = $value->unite_duree;
                    $data[$key]['id_type_transfert'] = $value->id_type_transfert;
                    $data[$key]['typetransfert'] = $typetransfert;
                    $data[$key]['id_frequence_transfert'] = $value->id_frequence_transfert;
                    $data[$key]['frequencetransfert'] = $frequencetransfert;
                    //$data[$key]['montant_transfert'] = $value->montant_transfert;
                    $data[$key]['flag_integration_donnees'] = $value->flag_integration_donnees;
                    $data[$key]['nouvelle_integration'] = $value->nouvelle_integration;
                    $data[$key]['commentaire'] = $value->commentaire;
                    $data[$key]['detail_transfert'] = $detail_transfert;
                    $data[$key]['detail_type_transfert_intervention'] = $detail_type_transfert_intervention;
                    $data[$key]['detail_financement_intervention'] = array();
                    $data[$key]['detail_zone_intervention'] = array();
                    $data[$key]['detail_charge'] = 0;
				}
			}			
		// }
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
		$id_type_transfert=null;
		$temporaire=$this->post('id_type_transfert');
		if(isset($temporaire) && $temporaire !="" && intval($temporaire) >0) {
			$id_type_transfert=$temporaire;
		}
		$id_acteur=null;
		$temporaire=$this->post('id_acteur');
		if(isset($temporaire) && $temporaire !="" && intval($temporaire) >0) {
			$id_acteur=$temporaire;
		}
		$id_type_action=null;
		$temporaire=$this->post('id_type_action');
		if(isset($temporaire) && $temporaire !="" && intval($temporaire) >0) {
			$id_type_action=$temporaire;
		}
		$id_frequence_transfert=null;
		$temporaire=$this->post('id_frequence_transfert');
		if(isset($temporaire) && $temporaire !="" && intval($temporaire) >0) {
			$id_frequence_transfert=$temporaire;
		}
 		$data = array(
			'id_programme' => $this->post('id_programme'),
			'identifiant' => $this->post('identifiant'),
			'nom_informateur' => $this->post('nom_informateur'),
			'prenom_informateur' => $this->post('prenom_informateur'),
			'telephone_informateur' => $this->post('telephone_informateur'),
			'email_informateur' => $this->post('email_informateur'),
			'ministere_tutelle' => $this->post('ministere_tutelle'),
			'intitule' => $this->post('intitule'),
			'id_acteur' => $id_acteur,
			'categorie_intervention' => $this->post('categorie_intervention'),
			'id_type_action' => $id_type_action,
			'id_frequence_transfert' => $id_frequence_transfert,
			'inscription_budgetaire' => $this->post('inscription_budgetaire'),
			'programmation' => $this->post('programmation'),
			'duree' => $this->post('duree'),
			'unite_duree' => $this->post('unite_duree'),
			'id_type_transfert' => $id_type_transfert,
			'flag_integration_donnees' => $this->post('flag_integration_donnees'),
			'nouvelle_integration' => $this->post('nouvelle_integration'),
			'commentaire' => $this->post('commentaire'),
		); 
		$detail_type_transfert=array();
		$nombre = intval($this->post('nombre_detail_type_transfert'));
        if ($supprimer == 0) {
            if ($id == 0) {
                if (!$data) {
                    $this->response([
                        'status' => FALSE,
                        'response' => 0,
                        'message' => 'No request found'
                            ], REST_Controller::HTTP_BAD_REQUEST);
                }
                $dataId = $this->InterventionManager->add($data);      
				if($nombre >0) {
					for($i=0;$i<$nombre;$i++) {
						$type_trf=array(
							'id_intervention' => $dataId,
							'id_detail_type_transfert' => $this->post('id_detail_type_transfert_'.$i),
							'valeur_quantite' => $this->post('valeur_quantite_'.$i)
						);
						$retour = $this->DetailtypetransfertinterventionManager->add($type_trf);     
					}
				}
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
                $update = $this->InterventionManager->update($id, $data);              
				$del = $this->DetailtypetransfertinterventionManager->deleteByIntervention($id);  
				if($nombre >0) {
					for($i=0;$i<$nombre;$i++) {
						$type_trf=array(
							'id_intervention' => $this->post('id_intervention_'.$i),
							'id_detail_type_transfert' => $this->post('id_detail_type_transfert_'.$i),
							'valeur_quantite' => $this->post('valeur_quantite_'.$i)
						);
						$retour = $this->DetailtypetransfertinterventionManager->add($type_trf);     
					}
				}
                if(!is_null($update)){
                    $this->response([
                        'status' => TRUE, 
                        // 'response' => 1,
                        'response' => $type_trf,
                        'message' => 'Update data success'
                            ], REST_Controller::HTTP_OK);
                } else {
                    $this->response([
                        'status' => FALSE,
                        'response' => $type_trf,
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
			$del = $this->DetailtypetransfertinterventionManager->deleteByIntervention($id);  
            $delete = $this->InterventionManager->delete($id);          
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