<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Suivi_individu extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('suivi_individu_model', 'SuiviindividuManager');
        $this->load->model('sourcefinancement_model', 'SourcefinancementManager');
        $this->load->model('type_transfert_model', 'TypetransfertManager');
        $this->load->model('intervention_model', 'InterventionManager');
    }
	// TABLE CONCERNEE DANS LA BDD : suivi_individu
	// index_get : 1- récupération des données suivant les cas : clé etrangère = id_menage
	// 2- récupération des données suivant id_intervention et id_menage
	// 3- récupération des données : par intervention
	// 4- récupération de toutes les données dans la table suivi_individu
    public function index_get() {
        $id = $this->get('id');

        $cle_etrangere = $this->get('cle_etrangere');
        $id_intervention = $this->get('id_intervention');
        $id_individu = $this->get('id_individu');
        $data = array() ;
        if ($cle_etrangere) 
        {    // Selection détails intervention par individu
            $suivi_individu = $this->SuiviindividuManager->findAllByIndividu($cle_etrangere);
            if ($suivi_individu) 
            {
                $data['id'] = ($suivi_individu->id);
                $data['id_individu'] = ($suivi_individu->id_individu);
                $data['id_intervention'] = unserialize($suivi_individu->id_intervention);                
            }
        }
        else
        {
            if ($id_intervention && $id_individu) 
			{ 
				$data=array();
 				$id_i=$id_intervention;
               $id_prog = '"%'.$id_intervention.'%"' ;
			   // Selection par programme et par individu
                $list_suivi_individu = $this->SuiviindividuManager->findAllByProgrammeAndIndividu($id_intervention,$id_individu);
                if ($list_suivi_individu) 
                {
						$detail_suivi_individu=array();
					
                    foreach ($list_suivi_individu as $key => $value) 
                    {
						$intervention=array();
						// Selection description intervention
						$intervention = $this->InterventionManager->findById($id_i);
						$tmp=array();
						
						$tmp['id'] = $value->id;
                        $tmp['id_suivi_individu_entete'] = ($value->id_suivi_individu_entete);
                        $tmp['nom'] = ($value->nom);
                        $tmp['prenom'] = ($value->prenom);
                        $tmp['date_naissance'] = ($value->date_naissance);
                        $tmp['date_suivi'] = $value->date_suivi;
                        $tmp['id_intervention'] = $id_i;
                        $tmp['intervention'] = $intervention;
						$detail_suivi_individu=$tmp;					
					}
					$data[0]['detail_suivi_individu']=$detail_suivi_individu;
                }				
			} 
			else	
            if ($id_intervention) 
            {
                $id_prog = '"'.$id_intervention.'"' ;
				// Selection par programme
                $list_suivi_individu = $this->SuiviindividuManager->findAllByProgramme($id_prog);
                if ($list_suivi_individu) 
                {
                    foreach ($list_suivi_individu as $key => $value) 
                    {
                        $data[$key]['id'] = $value->id;
                        $data[$key]['NomInscrire'] = ($value->NomInscrire);
                        $data[$key]['PersonneInscription'] = ($value->PersonneInscription);
                        $data[$key]['AgeInscrire'] = ($value->AgeInscrire);
                        $data[$key]['Addresse'] = ($value->Addresse);
                        $data[$key]['NumeroEnregistrement'] = ($value->NumeroEnregistrement);
                        $data[$key]['id_intervention'] = ($id_intervention);
                       
                    }
                }
            }
            else
            {
                if ($id) 
                {	// Selection par id
                    $data = $this->SuiviindividuManager->findById($id);
                } 
                else 
                {	// Selection de tous les enregistrements
                    $data = $this->SuiviindividuManager->findAll();                   
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
	// TABLE CONCERNEE DANS LA BDD : suivi_individu
	// index_post : sauvegarde les données dans la table
	// ou bien suppression des données dans la table si la variable $supprimer = 1 (via controleur javascript)
	// ou bien mise à jour table si la variable $id >0
    public function index_post() {
        $id = $this->post('id') ;
        $supprimer = $this->post('supprimer') ;
        if ($supprimer == 0) {
			$data = array(
				'id_individu' => $this->post('id_individu'),
				'id_suivi_individu_entete' => $this->post('id_suivi_individu_entete'),
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
                $dataId = $this->SuiviindividuManager->add($data);

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
                $update = $this->SuiviindividuManager->update($id, $data);              
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
            $delete = $this->SuiviindividuManager->delete($id);          
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