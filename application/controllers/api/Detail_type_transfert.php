<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Detail_type_transfert extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('detail_type_transfert_model', 'DetailtypetransfertManager');
        $this->load->model('detail_type_transfert_intervention_model', 'DetailtypetransfertinterventionManager');
        $this->load->model('type_transfert_model', 'TypetransfertManager');
        $this->load->model('unite_mesure_model', 'UnitemesureManager');
    }

    public function index_get() {
        $id = $this->get('id');
        $cle_etrangere = $this->get('cle_etrangere');
        $id_intervetion = $this->get('id_intervetion');
		$data = array();
		if ($id) {
			// Récupération par id (id=clé primaire)
			$tmp = $this->DetailtypetransfertManager->findById($id);
			if($tmp) {
				$data=$tmp;
			}
			$choix=1;
		} else if ($cle_etrangere){
			// Récupération par type de transfert
			$menu = $this->DetailtypetransfertManager->findByTypetransfert($cle_etrangere);
			$choix=2;
		} else {
			// Récupération de tous les enregistrements	
			$menu = $this->DetailtypetransfertManager->findAll();
			$choix=2;
		}
		if($choix==2) {
			if($menu) {
                foreach ($menu as $key => $value) {
					// Détail description unité de mesure
                    $unitedemesure = array();
                    $type_fin = $this->UnitemesureManager->findById($value->id_unite_mesure);
					if(count($type_fin) >0) {
						$unitedemesure=$type_fin;
					}	
					// Détail description type de transfert
                    $typedetransfert = array();
                    $ag = $this->TypetransfertManager->findById($value->id_type_transfert);
					if(count($ag) >0) {
						$typedetransfert=$ag;
					}
					// Affectation dans un tableau pour affichage	
                    $data[$key]['id'] = $value->id;
                    $data[$key]['code'] = $value->code;
                    $data[$key]['description'] = $value->description;
                    $data[$key]['id_unite_mesure'] = $value->id_unite_mesure;
                    $data[$key]['unitedemesure'] = $unitedemesure;
                    $data[$key]['id_type_transfert'] = $value->id_type_transfert;
                    $data[$key]['typedetransfert'] = $typedetransfert;
					// initialisation id_detail_type_transfert=0 et valeur_quantite=null
                    $data[$key]['id_detail_type_transfert'] = 0;
                    $data[$key]['valeur_quantite'] = null;
					if($id_intervetion) {
						// Récupération détail valeur_quantite du type de transfert par intervention
						$val_qte= $this->DetailtypetransfertinterventionManager->findByInterventionIdtypetransfert($id_intervetion,$value->id);
						if($val_qte) { 
							foreach ($val_qte as $k => $v) {	
								if($k==0) {
									$data[$key]['valeur_quantite'] = $v->valeur_quantite;
									$data[$key]['id_detail_type_transfert'] =$value->id;
								}	
							}	
						}	
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
		// Affectation de valeur colonne de la table
		$data = array(
			'code' => $this->post('code'),
			'description' => $this->post('description'),
			'id_unite_mesure' => $this->post('id_unite_mesure'),
			'id_type_transfert' => $this->post('id_type_transfert'),
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
                $dataId = $this->DetailtypetransfertManager->add($data);              
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
                $update = $this->DetailtypetransfertManager->update($id, $data);              
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
            $delete = $this->DetailtypetransfertManager->delete($id);          
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