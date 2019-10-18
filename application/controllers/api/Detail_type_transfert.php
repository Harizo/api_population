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
			$tmp = $this->DetailtypetransfertManager->findById($id);
			if($tmp) {
				$data=$tmp;
			}
			$ou=1;
		} else if ($cle_etrangere){
			$menu = $this->DetailtypetransfertManager->findByTypetransfert($cle_etrangere);
			// $menu = $this->DetailtypetransfertManager->findAll();
			$ou=2;
		} else {	
			$menu = $this->DetailtypetransfertManager->findAll();
			$ou=2;
		}
		if($ou==2) {
			if($menu) {
                foreach ($menu as $key => $value) {
                    $unitedemesure = array();
                    $type_fin = $this->UnitemesureManager->findById($value->id_unite_mesure);
					if(count($type_fin) >0) {
						$unitedemesure=$type_fin;
					}	
                    $typedetransfert = array();
                    $ag = $this->TypetransfertManager->findById($value->id_type_transfert);
					if(count($ag) >0) {
						$typedetransfert=$ag;
					}	
                    $data[$key]['id'] = $value->id;
                    $data[$key]['code'] = $value->code;
                    $data[$key]['description'] = $value->description;
                    $data[$key]['id_unite_mesure'] = $value->id_unite_mesure;
                    $data[$key]['unitedemesure'] = $unitedemesure;
                    $data[$key]['id_type_transfert'] = $value->id_type_transfert;
                    $data[$key]['typedetransfert'] = $typedetransfert;
                    $data[$key]['id_detail_type_transfert'] = 0;
					if($id_intervetion) {
						$val_qte= $this->DetailtypetransfertinterventionManager->findByInterventionIdtypetransfert($id_intervetion,$value->id);
						if($val_qte) { 
							foreach ($val_qte as $k => $v) {	
								if($k==0) {
									$data[$key]['valeur_quantite'] = $v->valeur_quantite;
									$data[$key]['id_detail_type_transfert'] =$value->id;
								}	
							}	
						} else {
							$data[$key]['valeur_quantite'] =null;
						}	
					} else {
						$data[$key]['valeur_quantite'] = null;
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