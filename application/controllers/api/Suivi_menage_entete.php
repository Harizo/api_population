<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Suivi_menage_entete extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('suivi_menage_entete_model', 'SuivimenageenteteManager');
        $this->load->model('sourcefinancement_model', 'SourcefinancementManager');
        $this->load->model('type_transfert_model', 'TypetransfertManager');
        $this->load->model('intervention_model', 'InterventionManager');
    }
	// TABLE CONCERNEE DANS LA BDD : suivi_menage_entete
	// index_get : 1- récupération des données suivant les cas : clé etrangère = id_menage
	// 2- récupération des données suivant id_intervention et id_menage
	// 3- récupération des données : par intervention
	// 4- récupération de toutes les données dans la table suivi_menage_entete
    public function index_get() {
        $id = $this->get('id');

        $cle_etrangere = $this->get('cle_etrangere');
        $id_intervention = $this->get('id_intervention');
        $id_menage = $this->get('id_menage');
        $data = array() ;
        if ($cle_etrangere) 
        {	// Selection par ménage
            $suivi_menage = $this->SuivimenageenteteManager->findAllByMenage($cle_etrangere);

            

            if ($suivi_menage) 
            {
                $data['id'] = ($suivi_menage->id);
                $data['date_suivi'] = ($suivi_menage->date_suivi);
                $data['id_intervention'] = unserialize($suivi_menage->id_intervention);
                $data['id_fokontany'] = unserialize($suivi_menage->id_fokontany);
                $data['id_liste_validation_intervention'] = unserialize($suivi_menage->id_liste_validation_intervention);
                
            }
        }
        else
        {
            if ($id_intervention && $id_menage) 
			{ 
				$id_i=$id_intervention;
                $id_prog = '"%'.$id_intervention.'%"' ;
				// Selection par programme et par ménage
                $list_suivi_menage = $this->SuivimenageenteteManager->findAllByProgrammeAndMenage($id_intervention,$id_menage);
                if ($list_suivi_menage) 
                {
						$detail_suivi_menage=array();
						$nutrition=array();
						$transfert_argent=array();
                    foreach ($list_suivi_menage as $key => $value) 
                    {
						$intervention=array();
						$typetransfert=array();
						$intervention = $this->InterventionManager->findById($id_i);
						$typetransfert = $this->TypetransfertManager->findById($value->id_type_transfert);
 						$temporaire=array();
						$temporaire['id'] = $value->id;
                        $temporaire['id_menage'] = ($value->id_menage);
                        $temporaire['id_type_transfert'] = ($value->id_type_transfert);
                        $temporaire['typetransfert'] = ($typetransfert);
                        $temporaire['nom'] = ($value->nom);
                        $temporaire['prenom'] = ($value->prenom);
                        $temporaire['date_naissance'] = ($value->date_naissance);
                        $temporaire['date_inscription'] = ($value->date_inscription);
                        $temporaire['profession'] = ($value->profession);
                        $temporaire['date_suivi'] = $value->date_suivi;
                        $temporaire['id_intervention'] = $id_i;
                        $temporaire['intervention'] = $intervention;
                        $temporaire['id_fokontany'] = $value->id_fokontany;
                        $temporaire['id_liste_validation_intervention'] = $value->id_liste_validation_intervention;
                        $temporaire['observation'] = $value->observation;
						$detail_suivi_menage []=$temporaire;
						if(intval($id_intervention)==3) {
							// Nutrition
							$nutrition[] =$temporaire;
						} else  {
							// Transfert monétaire par défaut
							$transfert_argent[]=$temporaire;
						}				   
                    }
					$data[0]['detail_suivi_menage']=$detail_suivi_menage;
					$data[0]['nutrition']=$nutrition;
					$data[0]['transfert_argent']=$transfert_argent;					
                }				
			} 
			else	
            if ($id_intervention) 
            {
                $id_prog = '"'.$id_intervention.'"' ;
				// Selection par programme
                $list_suivi_menage = $this->SuivimenageenteteManager->findAllByProgramme($id_prog);
                if ($list_suivi_menage) 
                {
                    foreach ($list_suivi_menage as $key => $value) 
                    {
                        $data[$key]['id'] = $value->id;
                        $data[$key]['NomInscrire'] = ($value->NomInscrire);
                        $data[$key]['PersonneInscription'] = ($value->PersonneInscription);
                        $data[$key]['AgeInscrire'] = ($value->AgeInscrire);
                        $data[$key]['Addresse'] = ($value->Addresse);
                        $data[$key]['NumeroEnregistrement'] = ($value->NumeroEnregistrement);
                        $data[$key]['id_liste_validation_intervention'] = ($value->id_liste_validation_intervention);
                        $data[$key]['id_intervention'] = ($id_intervention);
                        $data[$key]['id_fokontany'] = ($value->id_fokontany);
                        $data[$key]['date_suivi'] = ($date_suivi);
                    }
                }
            }
            else
            {
                if ($id) 
                {	// Selection par id
                    $data = $this->SuivimenageenteteManager->findById($id);
                } 
                else 
                {	// Selection de tous les enregistrements
                    $data = $this->SuivimenageenteteManager->findAll();                   
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
	// TABLE CONCERNEE DANS LA BDD : suivi_menage_entete
	// index_post : sauvegarde les données dans la table
	// ou bien suppression des données dans la table si la variable $supprimer = 1 (via controleur javascript)
	// ou bien mise à jour table si la variable $id >0
    public function index_post() {
        $id = $this->post('id') ;
        $supprimer = $this->post('supprimer') ;
        if ($supprimer == 0) {
			// Affectation des valeurs
			$data = array(
				'id_intervention' => $this->post('id_intervention'),
				'date_suivi' => $this->post('date_suivi'),
				'observation' => $this->post('observation'),
				'id_fokontany' => $this->post('id_fokontany'),
				'id_liste_validation_intervention' => $this->post('id_liste_validation_intervention'),
			);               
            if ($id == 0) {
                if (!$data) 
                {
                    $this->response([
                        'status' => FALSE,
                        'response' => 0,
                        'message' => 'No request found'
                            ], REST_Controller::HTTP_BAD_REQUEST);
                }
				// Ajout d'un enregistrement
                $dataId = $this->SuivimenageenteteManager->add($data);

                if (!is_null($dataId)) 
                {
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
                $update = $this->SuivimenageenteteManager->update($id, $data);              
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
            $delete = $this->SuivimenageenteteManager->delete($id);          
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