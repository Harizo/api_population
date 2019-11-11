<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Suivi_menage extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('suivi_menage_model', 'SuivimenageManager');
        $this->load->model('sourcefinancement_model', 'SourcefinancementManager');
        $this->load->model('type_transfert_model', 'TypetransfertManager');
        $this->load->model('intervention_model', 'InterventionManager');
    }

	// TABLE CONCERNEE DANS LA BDD : suivi_menage
	// index_get : 1- récupération des données suivant les cas : clé etrangère = id_menage
	// 2- récupération des données suivant id_intervention et id_menage
	// 3- récupération des données : par intervention
	// 4- récupération de toutes les données dans la table suivi_menage
    public function index_get() {
        $id = $this->get('id');

        $cle_etrangere = $this->get('cle_etrangere');
        $id_intervention = $this->get('id_intervention');
        $id_menage = $this->get('id_menage');
        $data = array() ;
        if ($cle_etrangere) 
        {
            $suivi_menage = $this->SuivimenageManager->findAllByMenage($cle_etrangere);

            

            if ($suivi_menage) 
            {
                $data['id'] = ($suivi_menage->id);
                $data['id_menage'] = ($suivi_menage->id_menage);
                $data['id_intervention'] = unserialize($suivi_menage->id_intervention);
                
            }
        }
        else
        {
            if ($id_intervention && $id_menage) 
			{ 
				$id_i=$id_intervention;
                $id_prog = '"%'.$id_intervention.'%"' ;
                $list_suivi_menage = $this->SuivimenageManager->findAllByProgrammeAndMenage($id_intervention,$id_menage);
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
 						$tmp=array();
						$tmp['id'] = $value->id;
                        $tmp['id_menage'] = ($value->id_menage);
                        $tmp['id_type_transfert'] = ($value->id_type_transfert);
                        $tmp['typetransfert'] = ($typetransfert);
                        $tmp['nom'] = ($value->nom);
                        $tmp['prenom'] = ($value->prenom);
                        $tmp['date_naissance'] = ($value->date_naissance);
                        $tmp['date_inscription'] = ($value->date_inscription);
                        $tmp['profession'] = ($value->profession);
                        $tmp['date_suivi'] = $value->date_suivi;
                        $tmp['id_intervention'] = $id_i;
                        $tmp['intervention'] = $intervention;
                        $tmp['montant'] = $value->montant;
                        $tmp['observation'] = $value->observation;
						$detail_suivi_menage []=$tmp;
						if(intval($id_intervention)==3) {
							// Nutrition
							$nutrition[] =$tmp;
						} else  {
							// Transfert monétaire par défaut
							$transfert_argent[]=$tmp;
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
                $list_suivi_menage = $this->SuivimenageManager->findAllByProgramme($id_prog);
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
                       // $data['id_menage'] = ($suivi_menage->id_menage);
                        $data[$key]['id_intervention'] = ($id_intervention);
                        //$data[$key]['menage'] = $this->menageManager->findById($value->id_menage);
                       
                    }
                }
            }
            else
            {
                if ($id) 
                {
                    $data = $this->SuivimenageManager->findById($id);
                } 
                else 
                {
                    $data = $this->SuivimenageManager->findAll();                   
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
 	// TABLE CONCERNEE DANS LA BDD : suivi_menage
	// index_post : sauvegarde les données dans la table
	// ou bien suppression des données dans la table si la variable $supprimer = 1 (via controleur javascript)
	// ou bien mise à jour table si la variable $id >0
   public function index_post() {
        $id = $this->post('id') ;
        $supprimer = $this->post('supprimer') ;
        if ($supprimer == 0) {
			$data = array(
				'id_menage' => $this->post('id_menage'),
				'id_suivi_menage_entete' => $this->post('id_suivi_menage_entete'),
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

                $dataId = $this->SuivimenageManager->add($data);

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
                $update = $this->SuivimenageManager->update($id, $data);              
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
            $delete = $this->SuivimenageManager->delete($id);          
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