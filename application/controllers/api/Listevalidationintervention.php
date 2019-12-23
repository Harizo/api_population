<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Listevalidationintervention extends REST_Controller {

    public function __construct() {
        parent::__construct();
		// Ouverture des modèles utilisées
        $this->load->model('listevalidationintervention_model', 'ListevalidationinterventionManager');
        $this->load->model('utilisateurs_model', 'UtilisateursManager');
    }

    public function index_get() {
        $donnees_validees = $this->get('donnees_validees');
        $id_utilisateur = $this->get('id_utilisateur');
        $etat = $this->get('etat');
		$data = array();
		if ($etat) {
			if(intval($etat)==10) {
				$etat=0;
			} else {
				$etat=1;
			}
			// Selection liste des données par état : 0 non validées , 1 : validées
			$listedonnees = $this->ListevalidationinterventionManager->findByValidation($etat);
		} else if($donnees_validees && $id_utilisateur) {	
			// Selection liste des données par état et par utlisateur
			$listedonnees = $this->ListevalidationinterventionManager->findByValidationAndUtilisateur($donnees_validees,$id_utilisateur);
		} else {	
			// Selection de tous les enregistrements de la table
			$listedonnees = $this->ListevalidationinterventionManager->findAll();
		}
		if ($listedonnees) {
			foreach ($listedonnees as $key => $value) {
				if($value->id_utilisateur) {
					// Selection description d'un utilisateur
					$utilisateur = $this->UtilisateursManager->findById($value->id_utilisateur);
				} else {
					$utilisateur=array();
				}	
				if($value->id_utilisateur_validation) {
					// Selection description d'un utilisateur qui valide les données
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
				$date_reception = new DateTime($value->date_reception);
				$date_reception =$date_reception->format('d/m/Y H:i:s');		
				$data[$key]['date_reception'] =$date_reception ;
				$data[$key]['nom_fichier'] = $value->nom_fichier;
				$data[$key]['donnees_validees'] = $value->donnees_validees;
				if($value->date_validation) {
					$date_validation = new DateTime($value->date_validation);
					$date_validation =$date_validation->format('d/m/Y H:i:s');		
					$data[$key]['date_validation'] = $date_validation;
				} else {
					$data[$key]['date_validation'] = $value->date_validation;
				}
				$data[$key]['repertoire'] = $value->repertoire;
				$data[$key]['id_utilisateur_validation'] = $value->id_utilisateur_validation;
				$data[$key]['utilisateur_validation'] = $utilisateur_validation;
				$data[$key]['nomutilisateurvalidation'] ="";
				if($utilisateur_validation) {
					foreach($utilisateur_validation as $k=>$v) {
						$data[$key]['nomutilisateurvalidation'] = $utilisateur_validation->prenom . " ".$utilisateur_validation->nom;
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
		// Intitialisation des valeurs à null pour éviter le ZERO inséré dans la BDD
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
				// Ajout d'un enregistrement
                $Id_data_inserted = $this->ListevalidationinterventionManager->add($data); 
				// Selection d'un enregistrement nouvellement inséré	
				$retour = 	$this->ListevalidationinterventionManager->findById($Id_data_inserted);
				$valeur_retour=array();
				foreach($retour as $k=>$v) {
					$date_reception = new DateTime($v->date_reception);
					$date_reception =$date_reception->format('d/m/Y H:i:s');		
					$valeur_retour[$k]["date_reception"]=$date_reception;
					$valeur_retour[$k]["id"]=$v->id;
					$valeur_retour[$k]["repertoire"]=$v->repertoire;
				}
                if (!is_null($Id_data_inserted)) {
                    $this->response([
                        'status' => TRUE,
                        'response' => $valeur_retour,
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
                $update = $this->ListevalidationinterventionManager->update($id, $data);              
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
			// Suppression d'un enregistrement
            $delete = $this->ListevalidationinterventionManager->delete($id);          
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