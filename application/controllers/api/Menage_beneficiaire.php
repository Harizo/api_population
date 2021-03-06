<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
class Menage_beneficiaire extends REST_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('menage_beneficiaire_model', 'MenagebeficiaireManager');
        $this->load->model('menage_model', 'menageManager');
    }
    public function index_get() {
        $id = $this->get('id');
        $cle_etrangere = $this->get('cle_etrangere');
        $id_intervention = $this->get('id_intervention');
        $id_fokontany = $this->get('id_fokontany');
        $data = array() ;
        if ($cle_etrangere) {
			// Selection des enregistrements par ménage
            $menage_programme = $this->MenagebeficiaireManager->findAllByMenage($cle_etrangere);          
            if ($menage_programme) {
                $data['id'] = ($menage_programme->id);
                $data['id_menage'] = ($menage_programme->id_menage);
                $data['id_intervention'] = ($menage_programme->id_intervention);                
                $data['date_sortie'] = ($menage_programme->date_sortie);                
                $data['date_inscription'] = ($menage_programme->date_inscription);                
            }
        } else {
            if ($id_intervention && $id_fokontany) {  
                $id_prog = "'%".'"'.$id_intervention.'"'."%'" ;
				// Selection des ménage par programme et par fokontany
                $list_menage = $this->MenagebeficiaireManager->findAllByProgrammeAndVillage($id_prog,$id_fokontany);
                if ($list_menage)  {
                    foreach ($list_menage as $key => $value)  {
                        $data[$key]['id'] = $value->id;
                        $data[$key]['id_menage'] = ($value->id_menage);
                        $data[$key]['nom'] = ($value->nom);
                        $data[$key]['prenom'] = ($value->prenom);
                        $data[$key]['date_naissance'] = ($value->date_naissance);
                        $data[$key]['cin'] = ($value->cin);
                        $data[$key]['profession'] = ($value->profession);
                        $data[$key]['date_inscription'] = ($value->date_inscription);
                        $data[$key]['id_intervention'] = ($value->id_intervention);
                        $data[$key]['date_sortie'] = ($value->date_sortie);
                        $data[$key]['date_inscription'] = ($value->date_inscription);
                        $data[$key]['detail_suivi_menage'] = array();
                        $data[$key]['detail_charge'] = 0;
                    }
                }				
			} else	if ($id_intervention) {
                $id_prog = '"'.$id_intervention.'"' ;
				// Selection ménage par programme
                $list_menage_programme = $this->MenagebeficiaireManager->findAllByProgramme($id_prog);
                if ($list_menage_programme) {
                    foreach ($list_menage_programme as $key => $value) {
                        $data[$key]['id'] = $value->id;
                        $data[$key]['NomInscrire'] = ($value->NomInscrire);
                        $data[$key]['PersonneInscription'] = ($value->PersonneInscription);
                        $data[$key]['AgeInscrire'] = ($value->AgeInscrire);
                        $data[$key]['Addresse'] = ($value->Addresse);
                        $data[$key]['NumeroEnregistrement'] = ($value->NumeroEnregistrement);
                        $data[$key]['id_intervention'] = ($id_intervention);
                        $data[$key]['date_sortie'] = ($value->date_sortie);
                        $data[$key]['date_inscription'] = ($value->date_inscription);
                    }
                }
            } else {
                if ($id) {
					// Selection ménage par id (id=clé primaire)
                    $data = $this->MenagebeficiaireManager->findById($id);
                } else {
					// Selection de tous les ménages
                    $data = $this->MenagebeficiaireManager->findAll();                   
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
		$data = array(
			'id_menage' => $this->post('id_menage'),
			'id_intervention' => ($this->post('id_intervention')),
			'date_sortie' => ($this->post('date_sortie')),
			'date_inscription' => ($this->post('date_inscription')),
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
				// Ajout d'un enregistrement
                $dataId = $this->MenagebeficiaireManager->add($data);
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
                $update = $this->MenagebeficiaireManager->update($id, $data);              
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
            $delete = $this->MenagebeficiaireManager->delete($id);          
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