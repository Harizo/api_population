<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Suivi_menage_detail_transfert extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('suivi_menage_entete_model', 'SuivimenageenteteManager');
        $this->load->model('sourcefinancement_model', 'SourcefinancementManager');
        $this->load->model('type_transfert_model', 'TypetransfertManager');
        $this->load->model('intervention_model', 'InterventionManager');
    }
	// TABLE CONCERNEE DANS LA BDD : suivi_menage_detail_transfert
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
        {
            $suivi_menage_detail_transfert = $this->SuivimenageenteteManager->findAllByIndividu($cle_etrangere);

            

            if ($suivi_menage_detail_transfert) 
            {
                $data['id'] = ($suivi_menage_detail_transfert->id);
                $data['id_suivi_menage_entete'] = ($suivi_menage_detail_transfert->id_suivi_menage_entete);
                $data['id_detail_type_transfert'] = ($suivi_menage_detail_transfert->id_detail_type_transfert);
                $data['valeur_quantite'] = ($suivi_menage_detail_transfert->valeur_quantite);
                
            }
        }
        else
        {
            if ($id_intervention && $id_menage) 
			{ 
				$data=array();
 				$id_i=$id_intervention;
               $id_prog = '"%'.$id_intervention.'%"' ;
                $list_suivi_menage = $this->SuivimenageenteteManager->findAllByProgrammeAndIndividu($id_intervention,$id_menage);
                if ($list_suivi_menage) 
                {
						$detail_suivi_menage=array();
						$nutrition=array();
						$transfert_argent=array();
						$mariage_precoce=array();
						$promotion_genre=array();
					
                    foreach ($list_suivi_menage as $key => $value) 
                    {
						$intervention=array();
						$intervention = $this->InterventionManager->findById($id_i);
						$temporaire=array();
						
						$temporaire['id'] = $value->id;
                        $temporaire['id_menage'] = ($value->id_menage);
                        $temporaire['nom'] = ($value->nom);
                        $temporaire['prenom'] = ($value->prenom);
                        $temporaire['date_naissance'] = ($value->date_naissance);
                        $temporaire['date_suivi'] = $value->date_suivi;
                        $temporaire['id_suivi_menage_entete'] = $value->id_suivi_menage_entete;
                        $temporaire['id_detail_type_transfert'] = $value->id_detail_type_transfert;	
                        $temporaire['valeur_quantite'] = $value->valeur_quantite;	
						$detail_suivi_menage=$temporaire;					
					}
					$data[0]['detail_suivi_menage']=$detail_suivi_menage;
                }				
			} 
			else	
            if ($id_intervention) 
            {
                $id_prog = '"'.$id_intervention.'"' ;
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
                       // $data['id_menage'] = ($suivi_menage_detail_transfert->id_menage);
                        $data[$key]['id_intervention'] = ($id_intervention);
                        //$data[$key]['menage'] = $this->menageManager->findById($value->id_menage);
                       
                    }
                }
            }
            else
            {
                if ($id) 
                {
                    $data = $this->SuivimenageenteteManager->findById($id);
                } 
                else 
                {
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
	// TABLE CONCERNEE DANS LA BDD : suivi_menage_detail_transfert
	// index_post : sauvegarde les données dans la table
	// ou bien suppression des données dans la table si la variable $supprimer = 1 (via controleur javascript)
	// ou bien mise à jour table si la variable $id >0
    public function index_post() {
        $id = $this->post('id') ;
        $supprimer = $this->post('supprimer') ;
        if ($supprimer == 0) {
			$data = array(
				'id_suivi_menage_entete' => $this->post('id_suivi_menage_entete'),
				'id_detail_type_transfert' => $this->post('id_detail_type_transfert'),
				'valeur_quantite' => $this->post('valeur_quantite'),
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