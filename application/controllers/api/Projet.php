<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Projet extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('projet_model', 'ProjetManager');
        $this->load->model('programme_model', 'ProgrammeManager');
        $this->load->model('objectif_specifique_model', 'ObjectifspecifiqueManager');
        $this->load->model('bailleur_projet_model', 'BailleurprojetManager');
        $this->load->model('tutelle_projet_model', 'TutelleprojetManager');
        $this->load->model('secteur_projet_model', 'SecteurprojetManager');
        $this->load->model('acteur_projet_model', 'ActeurprojetManager');
        $this->load->model('bailleur_model', 'BailleurManager');
        $this->load->model('type_financement_model', 'TypefinancementManager');
        $this->load->model('tutelle_model', 'TutelleManager');
        $this->load->model('secteur_model', 'SecteurManager');
        $this->load->model('acteur_model', 'ActeurManager');
        $this->load->model('acteur_regional_model', 'ActeurregionalManager');
    }
    public function convertDateAngular($daty){
        if(isset($daty) && $daty != ""){
            if(strlen($daty) >33) {
                $daty=substr($daty,0,33);
            }
            $xx  = new DateTime($daty);
            if($xx->getTimezone()->getName() == "Z"){
                $xx->add(new DateInterval("P1D"));
                return $xx->format("Y-m-d");
            }else{
                return $xx->format("Y-m-d");
            }
        }else{
            return null;
        }
    }
    public function index_get() {
        $id = $this->get('id');
		$parent_id = $this->get('parent_id');
		$data = array();		
		if ($id) {
			$tmp = $this->ProjetManager->findById($id);
			if ($tmp) {
				$data=$tmp;
			}	
		} else {
			if (isset($parent_id) && intval($parent_id) >0) {
				// Détail 1 : Objectif spécifique
				$objec = $this->ObjectifspecifiqueManager->findByIdParent($parent_id);  
				if($objec) {
					$data[0]['Objectifspecifique'] = $objec;
				} else {
					$data[0]['Objectifspecifique'] = array();
				}
				// Détail 2 : Bailleur projet
				$bail = $this->BailleurprojetManager->findByIdParent($parent_id);  				
				if($bail) {
					$ret=array();
					foreach($bail as $k=>$v) {
						$tmp=array();
						$ba= $this->BailleurManager->findById($v->id_bailleur); 
						$ttrsfr= $this->TypefinancementManager->findById($v->id_type_financement); 
						$tmp["id"]=$v->id;	
						$tmp["id_projet"]=$v->id_projet;	
						$tmp["id_bailleur"]=$v->id_bailleur;	
						$tmp["bailleur"]=$ba;	
						$tmp["id_type_financement"]=$v->id_type_financement;	
						$tmp["typefinancement"]=$ttrsfr;	
						$tmp["type_transfert"]=$v->type_transfert;	
						$tmp["monnaie"]=$v->monnaie;	
						$tmp["cout"]=$v->cout;	
						$ret[]=$tmp;
					}
					$data[0]['Bailleur'] = $ret;
				} else {
					$data[0]['Bailleur'] = array();
				}
				// Détail 3 : Tutelle projet
				$tut = $this->TutelleprojetManager->findByIdParent($parent_id);  				
				if($tut) {
					$ret=array();
					foreach($tut as $k=>$v) {
						$tmp=array();
						$ba= $this->TutelleManager->findById($v->id_tutelle); 
						$tmp["id"]=$v->id;	
						$tmp["id_projet"]=$v->id_projet;	
						$tmp["id_tutelle"]=$v->id_tutelle;	
						$tmp["tutelle"]=$ba;	
						$ret[]=$tmp;
					}
					$data[0]['Tutelle'] = $ret;
				} else {
					$data[0]['Tutelle'] = array();
				}
				// Détail 4 : Secteur projet
				$tut = $this->SecteurprojetManager->findByIdParent($parent_id);  				
				if($tut) {
					$ret=array();
					foreach($tut as $k=>$v) {
						$tmp=array();
						$ba= $this->SecteurManager->findById($v->id_secteur); 
						$tmp["id"]=$v->id;	
						$tmp["id_projet"]=$v->id_projet;	
						$tmp["id_secteur"]=$v->id_secteur;	
						$tmp["secteur"]=$ba;	
						$ret[]=$tmp;
					}
					$data[0]['Secteur'] = $ret;
				} else {
					$data[0]['Secteur'] = array();
				}
				// Détail 5 : Acteur projet
				$bail = $this->ActeurprojetManager->findByIdParent($parent_id);  				
				if($bail) {
					$ret=array();
					foreach($bail as $k=>$v) {
						$tmp=array();
						$ba= $this->ActeurManager->findById($v->id_acteur); 
						$ttrsfr= $this->ActeurregionalManager->findById($v->id_acteur_regional); 
						$tmp["id"]=$v->id;	
						$tmp["id_projet"]=$v->id_projet;	
						$tmp["id_acteur"]=$v->id_acteur;	
						$tmp["acteur"]=$ba;	
						$tmp["id_acteur_regional"]=$v->id_acteur_regional;	
						$tmp["acteurregional"]=$ttrsfr;	
						$ret[]=$tmp;
					}
					$data[0]['Acteur'] = $ret;
				} else {
					$data[0]['Acteur'] = array();
				}
			} else {	
				$menu = $this->ProjetManager->findAll();
				if ($menu) {
					foreach ($menu as $key => $value) {
					   /*$programme = array();
						$prg = $this->ProgrammeManager->findById($value->id_programme);
						if(count($prg) >0) {
							$programme = $prg;
						} */
						$data[$key]['id'] = $value->id;
						/*$data[$key]['id_programme'] = $value->id_programme;
						$data[$key]['programme'] = $programme;*/
						$data[$key]['date_debut'] = $value->date_debut;
						$data[$key]['date_fin'] = $value->date_fin;
						$data[$key]['intitule'] = $value->intitule;
						$data[$key]['objectif_general'] = $value->objectif_general;
						$data[$key]['observation'] = $value->observation;
						$data[$key]['type_intervention'] = $value->type_intervention;
						// Initialisation table détail
						$data[$key]['Objectifspecifique'] = array();
						$data[$key]['Bailleur'] = array();
						$data[$key]['Tutelle'] = array();
						$data[$key]['Secteur'] = array();
						$data[$key]['Acteur'] = array();
						$data[$key]['detail_charge'] = 0;
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
		$date_debut = $this->convertDateAngular($this->post('date_debut'));		
		$date_fin = $this->convertDateAngular($this->post('date_fin'));	
	/*	$id_programme=null;
		$tmp1=$this->post('id_programme');
		if(isset($tmp1) && $tmp1 !="" && intval($tmp1) >0) {
			$id_programme=$tmp1;
		} 	*/		
		$data = array(
			'intitule'         => $this->post('intitule'),
			'objectif_general' => $this->post('objectif_general'),
			'observation'      => $this->post('observation'),
			'type_intervention' => $this->post('type_intervention'),
			// 'id_programme'     => $id_programme,
			'date_debut'       => $date_debut,
			'date_fin'         => $date_fin,
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
                $dataId = $this->ProjetManager->add($data);              
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
                $update = $this->ProjetManager->update($id, $data);              
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
            $delete = $this->ProjetManager->delete($id);          
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