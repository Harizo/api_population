<?php

defined('BASEPATH') OR exit('No direct script access allowed');
// afaka fafana refa ts ilaina
require APPPATH . '/libraries/REST_Controller.php';

class Biens_eqipements extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('biens_eqipements_model', 'BienseqipementsManager');
        $this->load->model('menage_model', 'MenageManager');
        $this->load->model('enquete_menage_model', 'EnquetemenageManager');
    }
    public function index_get() {
        $id = $this->get('id');
        $cle_etrangere = $this->get('cle_etrangere');
		$data=array();
        if ($cle_etrangere) {
			// Récupération par ménage
            $retour = $this->BienseqipementsManager->findAllByMenage($cle_etrangere);
			if($retour) {
				foreach ($retour as $key => $value) {
					// Détail description d'un ménage
					$menage = array();
					$biens_equipements = array();
					$men = $this->MenageManager->findById($value->id_menage);                   
					if(count($men) >0) {
						$menage =$men;
					}
					// Détail description type de bien et équipement
					$eq = $this->EnquetemenageManager->findById($value->id_biens_equipements,"type_bien_equipement");                   
					if(count($eq) >0) {
						$biens_equipements =$eq;
					}
					$typeregularisation = array();
					$data[$key]['id'] = $value->id;
					$data[$key]['id_menage'] = $value->id_menage;
					$data[$key]['menage'] = $menage;
					$data[$key]['id_biens_equipements'] = $value->id_biens_equipements;
					$data[$key]['biens_equipements'] = $biens_equipements;
				 }				
			}
		} else {	
			if ($id) {
				// Récupération par id (id=clé primaire)
				$data = $this->BienseqipementsManager->findById($id);
				if (!$data)
					$data = array();
			} else {
				// Récupération de tous les enregistrements
				$menu = $this->BienseqipementsManager->findAll();	
				if ($menu) {
					foreach ($menu as $key => $value) {
						// Détail description d'un ménage
						$menage = array();
						$biens_equipements = array();
						$men = $this->MenageManager->findById($value->id_menage);                   
						if(count($men) >0) {
							$menage =$men;
						}
						// Détail description type bien et équipement
						$equipet = $this->EnquetemenageManager->findById($value->id_biens_equipements,"type_bien_equipement");                   
						if(count($equipet) >0) {
							$biens_equipements =$equipet;
						}
						// Affectation des valeurs dans un tableau
						$typeregularisation = array();
						$data[$key]['id'] = $value->id;
						$data[$key]['id_menage'] = $value->id_menage;
						$data[$key]['menage'] = $menage;
						$data[$key]['id_biens_equipements'] = $value->id_biens_equipements;
						$data[$key]['biens_equipements'] = $biens_equipements;
					 }
				}
				if (!$data)
					$data = array();
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
        $existe = $this->post('existe') ;
		$id_menage=null;
		$id_biens_equipements=null;
		$tmp=$this->post('id_menage');
		if(isset($tmp) && $tmp !="" && intval($tmp) >0) {
			$id_menage=$tmp;
		} 
		$tmp=$this->post('id_biens_equipements');
		if(isset($tmp) && $tmp !="" && intval($tmp) >0) {
			$id_biens_equipements=$tmp;
		} 
		$data = array(
			'id_menage'            => $id_menage,
			'id_biens_equipements' => $this->post('id_biens_equipements'),
		);
        if ($existe == 1) {
			// Suppresion détail type bien et équipement avant insertion de nouveau
			$del = $this->BienseqipementsManager->deleteByMenage($id_menage,"biens_equipements");
			// Ajout d'un enregistrement de type bien et equipement
			$dataId = $this->BienseqipementsManager->add($data,"biens_equipements");
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
        } else  {
			// Ajout d'un enregistrement de type bien et equipement
			$dataId = $this->BienseqipementsManager->add($data,"biens_equipements");
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
        }
    }
}
?>