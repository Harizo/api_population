<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Listevalidationbeneficiaire extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('listevalidationbeneficiaire_model', 'ListevalidationbeneficiaireManager');
        $this->load->model('utilisateurs_model', 'UtilisateursManager');
    }

    public function index_get() {
        $donnees_validees = $this->get('donnees_validees');
        $id_utilisateur = $this->get('id_utilisateur');
		$data = array();
		if ($donnees_validees && $id_utilisateur) {
			$listedonnees = $this->ListevalidationbeneficiaireManager->findByValidationAndUtilisateur($donnees_validees,$id_utilisateur);
		} else if($donnees_validees) {	
			$listedonnees = $this->ListevalidationbeneficiaireManager->findByValidation($donnees_validees);
		} else {			
			$listedonnees = $this->ListevalidationbeneficiaireManager->findAll();
		}
		if ($listedonnees) {
			foreach ($listedonnees as $key => $value) {
				if($value->id_utilisateur) {
					$utilisateur = $this->UtilisateursManager->findById($value->id_utilisateur);
				} else {
					$utilisateur=array();
				}	
				if($value->id_utilisateur_validation) {
					$utilisateur_validation = $this->UtilisateursManager->findById($value->id_utilisateur_validation);
				} else {
					$utilisateur_validation=array();
				}	
				$data[$key]['id'] = $value->id;
				$data[$key]['id_utilisateur'] = $value->id_utilisateur;
				$data[$key]['utilisateur'] = $utilisateur;
				$data[$key]['nomutilisateur'] ="";
				$data[$key]['raisonsociale'] ="";
				if($utilisateur) {
					foreach($utilisateur as $k=>$v) {
						$data[$key]['nomutilisateur'] = $utilisateur->prenom . " ".$utilisateur->nom;
						$data[$key]['raisonsociale'] = $utilisateur->raison_sociale ;
					}
				}
				$data[$key]['date_reception'] = $value->date_reception;
				$data[$key]['nom_fichier'] = $value->nom_fichier;
				$data[$key]['donnees_validees'] = $value->donnees_validees;
				$data[$key]['date_validation'] = $value->date_validation;
				$data[$key]['repertoire'] = $value->repertoire;
				$data[$key]['id_utilisateur_validation'] = $value->id_utilisateur_validation;
				$data[$key]['utilisateur_validation'] = $utilisateur_validation;
				if($utilisateur_validation) {
					foreach($utilisateur_validation as $k=>$v) {
						$data[$key]['nomutilisateur'] = $utilisateur_validation->prenom . " ".$utilisateur_validation->nom;
						$data[$key]['raisonsociale'] = $utilisateur_validation->raison_sociale ;
					}
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
		$id_utilisateur=null;
		$tmp=$this->post('id_utilisateur');
		if(isset($tmp) && $tmp !="" && intval($tmp) >0) {
			$id_utilisateur=$tmp;
		}
		$id_utilisateur_validation=null;
		$tmp=$this->post('id_utilisateur_validation');
		if(isset($tmp) && $tmp !="" && intval($tmp) >0) {
			$id_utilisateur_validation=$tmp;
		}
		$date_reception = new DateTime; # $date_reception = DateTime::createFromFormat('H:i:s d/m/Y', $ladate);
		$date_reception->add(new DateInterval('PT1H'));
		$date_reception =$date_reception->format('Y-m-d H:i:s');		
 		$data = array(
			'id_utilisateur' => $id_utilisateur,
			'nom_fichier' => $this->post('nom_fichier'),
			'repertoire' => $this->post('repertoire'),
			'donnees_validees' => $this->post('donnees_validees'),
			'date_reception' => $date_reception,
			'date_validation' => null,
			'id_utilisateur_validation' => $id_utilisateur_validation,
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
                $Id_data_inserted = $this->ListevalidationbeneficiaireManager->add($data);  
				$retour = 	$this->ListevalidationbeneficiaireManager->findById($Id_data_inserted);
                if (!is_null($Id_data_inserted)) {
                    $this->response([
                        'status' => TRUE,
                        'response' => $retour,
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
                $update = $this->ListevalidationbeneficiaireManager->update($id, $data);              
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
            $delete = $this->ListevalidationbeneficiaireManager->delete($id);          
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