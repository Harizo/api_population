<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Enquete_sur_individu extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('enquete_sur_individu_model', 'EnqueteindividuManager');
    }
    public function index_get() {
        $id = $this->get('id');

        $cle_etrangere = $this->get('cle_etrangere');
        $data = array() ;
        if ($cle_etrangere) {
			// Récupération par individu
            $menu = $this->EnqueteindividuManager->findAllByindividu($cle_etrangere);
            if ($menu) {
                $data['id'] = ($menu->id);
                $data['id_individu'] = $menu->id_individu;
                $data['id_lien_de_parente'] = $menu->id_lien_de_parente;
                $data['id_handicap_visuel'] = $menu->id_handicap_visuel;
                $data['id_handicap_parole'] = $menu->id_handicap_parole;
                $data['id_handicap_auditif'] = $menu->id_handicap_auditif;
                $data['id_handicap_mental'] = $menu->id_handicap_mental;
                $data['id_handicap_moteur'] = $menu->id_handicap_moteur;
                $data['id_type_ecole'] = $menu->id_type_ecole;
                $data['langue'] = unserialize($menu->langue);
                $data['id_niveau_de_classe'] = $menu->id_niveau_de_classe;
                $data['id_groupe_appartenance'] = $menu->id_groupe_appartenance;
            }
        }  else {
            if ($id) {    
				// Récupération par id (id=clé primaire)
                $data = $this->EnqueteindividuManager->findById($id);
            } else {
				// Récupération de tous les enregistrments
                $data = $this->EnqueteindividuManager->findAll();
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
		// Initialisations de valeurs des colonnes de latable pour éviter le ZERO par défaut dans la BDD : ATTENTION
		$id_lien_de_parente=null;
		$id_handicap_visuel=null;
		$id_handicap_parole=null;
		$id_handicap_auditif=null;
		$id_handicap_mental=null;
		$id_handicap_moteur=null;
		$id_type_ecole=null;
		$id_niveau_de_classe=null;
		$tmp=$this->post('id_lien_de_parente');
		if(isset($tmp) && $tmp !="" && intval($tmp) >0) {
			$id_lien_de_parente=$tmp;
		}
		$tmp=$this->post('id_handicap_visuel');
		if(isset($tmp) && $tmp !="" && intval($tmp) >0) {
			$id_handicap_visuel=$tmp;
		}
		$tmp=$this->post('id_handicap_parole');
		if(isset($tmp) && $tmp !="" && intval($tmp) >0) {
			$id_handicap_parole=$tmp;
		}
		$tmp=$this->post('id_handicap_auditif');
		if(isset($tmp) && $tmp !="" && intval($tmp) >0) {
			$id_handicap_auditif=$tmp;
		}
		$tmp=$this->post('id_handicap_mental');
		if(isset($tmp) && $tmp !="" && intval($tmp) >0) {
			$id_handicap_mental=$tmp;
		}
		$tmp=$this->post('id_handicap_moteur');
		if(isset($tmp) && $tmp !="" && intval($tmp) >0) {
			$id_handicap_moteur=$tmp;
		}
		$tmp=$this->post('id_type_ecole');
		if(isset($tmp) && $tmp !="" && intval($tmp) >0) {
			$id_type_ecole=$tmp;
		}
		$tmp=$this->post('id_niveau_de_classe');
		if(isset($tmp) && $tmp !="" && intval($tmp) >0) {
			$id_niveau_de_classe=$tmp;
		}
		$tmp=$this->post('id_groupe_appartenance');
		if(isset($tmp) && $tmp !="" && intval($tmp) >0) {
			$id_groupe_appartenance=$tmp;
		}
		// Affectation des valeurs des colonnes
		$data = array(
			'id_individu' 			 => $this->post('id_individu'),
			'id_lien_de_parente'     => $id_lien_de_parente,
			'id_handicap_visuel' 	 => $id_handicap_visuel,
			'id_handicap_parole'     => $id_handicap_parole,
			'id_handicap_auditif' 	 => $id_handicap_auditif,
			'id_handicap_mental' 	 => $id_handicap_mental,
			'id_handicap_moteur' 	 => $id_handicap_moteur,
			'id_type_ecole' 	     => $id_type_ecole,
			'langue' 	             => serialize($this->post('langue')),
			'id_niveau_de_classe'    => $id_niveau_de_classe,
			'id_groupe_appartenance' => $id_groupe_appartenance,
		);               
        if ($supprimer == 0)  {
            if ($id == 0) {
                if (!$data)  {
                    $this->response([
                        'status' => FALSE,
                        'response' => 0,
                        'message' => 'Data 0'
                            ], REST_Controller::HTTP_BAD_REQUEST);
                }
				// Ajout d'un enregistrement
                $dataId = $this->EnqueteindividuManager->add($data);
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
                        'message' => 'No request foundQSqs'
                            ], REST_Controller::HTTP_BAD_REQUEST);
                }
            }  else {
                if (!$data || !$id) {
                    $this->response([
                        'status' => FALSE,
                        'response' => 0,
                        'message' => 'No request found'
                            ], REST_Controller::HTTP_BAD_REQUEST);
                }
				// Mise à jour d'un enregistrement
                $update = $this->EnqueteindividuManager->update($id, $data);              
                if(!is_null($update)){
                    $this->response([
                        'status' => TRUE, 
                        'response' => $id,
                        'message' => 'Update data success'
                            ], REST_Controller::HTTP_OK);
                } else {
                    $this->response([
                        'status' => FALSE,
                        'message' => 'No request found dqsdqsd'
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
            $delete = $this->EnqueteindividuManager->delete($id);          
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