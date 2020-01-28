<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Individu_beneficiaire extends REST_Controller {

    public function __construct() {
        parent::__construct();
		// Ouverture des modèles utilisées
        $this->load->model('individu_beneficiaire_model', 'IndividubeneficiaireManager');
        $this->load->model('individu_model', 'individuManager');
    }
    public function index_get() {
        $id = $this->get('id');
        $cle_etrangere = $this->get('cle_etrangere');
        $id_intervention = $this->get('id_intervention');
        $id_fokontany = $this->get('id_fokontany');
        $data = array() ;
        if ($cle_etrangere)  {
			// Selection d'un individu
            $individu_beneficiaire = $this->IndividubeneficiaireManager->findAllByIndividu($cle_etrangere);
			$data = array() ;
            if ($individu_beneficiaire) {
                $data['id'] = ($individu_beneficiaire->id);
                $data['id_individu'] = ($individu_beneficiaire->id_individu);
                $data['id_intervention'] = ($individu_beneficiaire->id_intervention);              
                $data['date_sortie'] = ($individu_beneficiaire->date_sortie);              
                $data['date_inscription'] = ($individu_beneficiaire->date_inscription);              
            }
        } else {
            if ($id_intervention && $id_fokontany) 	{ 
                $id_prog = "'%".'"'.$id_intervention.'"'."%'" ;
				// Selection des individus par programme et par fokontany
                $list_individu_programme = $this->IndividubeneficiaireManager->findAllByProgrammeAndVillage($id_prog,$id_fokontany);
                if ($list_individu_programme) {
                    foreach ($list_individu_programme as $key => $value) {
                        $data[$key]['id'] = $value->id;
                        $data[$key]['id_individu'] = $value->id_individu;
                        $data[$key]['nom'] = $value->nom;
                        $data[$key]['prenom'] = $value->prenom;
                        $data[$key]['adresse'] = $value->adresse;
                        $data[$key]['id_menage'] = $value->id_menage;
                        $data[$key]['date_naissance'] = $value->date_naissance;
                        $data[$key]['date_sortie'] = $value->date_sortie;
                        $data[$key]['date_inscription'] = $value->date_inscription;
                        $data[$key]['id_intervention'] = $id_intervention;
                        $data[$key]['detail_charge'] = 0;
                        $data[$key]['detail_suivi_individu'] = array();
                    }
                }				
			} else	if ($id_intervention) {
                $id_prog = '"'.$id_intervention.'"' ;
				// Selection individu par programme
                $list_individu_programme = $this->IndividubeneficiaireManager->findAllByProgramme($id_prog);
                if ($list_individu_programme) {
                    foreach ($list_individu_programme as $key => $value) {
                        $data[$key]['id'] = $value->id;
                        $data[$key]['id_individu'] = $value->id_individu;
                        $data[$key]['Nom'] = $value->Nom;
                        $data[$key]['Addresse'] = $value->Addresse;
                        $data[$key]['NumeroEnregistrement'] = $value->NumeroEnregistrement;
                        $data[$key]['DateNaissance'] = $value->DateNaissance;
                        $data[$key]['id_intervention'] = $id_intervention;
                        $data[$key]['NomInscrire'] = ($value->NomInscrire);
                        $data[$key]['PersonneInscription'] = ($value->PersonneInscription);
                        $data[$key]['AgeInscrire'] = ($value->AgeInscrire);
                        $data[$key]['Addresse'] = ($value->Addresse);
                        $data[$key]['NumeroEnregistrement'] = ($value->NumeroEnregistrement);
                        $data[$key]['date_sortie'] = ($value->date_sortie);
                        $data[$key]['date_inscription'] = ($value->date_inscription);
                        $data[$key]['id_intervention'] = ($id_intervention);
                    }
                }
            }  else  {
                if ($id) {
					// Selection d'un individu par id
                    $data = $this->IndividubeneficiaireManager->findById($id);
                }  else {
					// Selection de tous les individus
                    $data = $this->IndividubeneficiaireManager->findAll();                   
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
		// Affectation des valeurs de chaque colonne de la table
		$data = array(
			'id_individu' => $this->post('id_individu'),
			'id_intervention' => ($this->post('id_intervention')),
			'date_sortie' => ($this->post('date_sortie')),
			'date_inscription' => ($this->post('date_inscription')),
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
                $dataId = $this->IndividubeneficiaireManager->add($data);
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
            }  else  {
                if (!$data || !$id) {
                    $this->response([
                        'status' => FALSE,
                        'response' => 0,
                        'message' => 'No request found'
                            ], REST_Controller::HTTP_BAD_REQUEST);
                }
				// Mise à jour d'un enregistrement
				$update = $this->IndividubeneficiaireManager->update($id, $data);
                if(!is_null($update)){
                    $this->response([
                        'status' => TRUE, 
                        'response' => $update,

                        'message' => 'Update data success'
                            ], REST_Controller::HTTP_OK);
                } else {
                    $this->response([
                        'status' => FALSE,
                        'message' => 'No request found'
                            ], REST_Controller::HTTP_OK);
                }
            }
        }  else {
            if (!$id) {
            $this->response([
            'status' => FALSE,
            'response' => 0,
            'message' => 'No request found'
                ], REST_Controller::HTTP_BAD_REQUEST);
            }
			// Suppression d'un enregistrement
            $delete = $this->IndividubeneficiaireManager->delete($id);          
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