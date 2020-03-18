<?php

defined('BASEPATH') OR exit('No direct script access allowed');
//harizo
// afaka fafana refa ts ilaina
require APPPATH . '/libraries/REST_Controller.php';

class Listecanevasformate extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('listecanevasformate_model', 'ListecanevasformateManager');
    }
public function convertDateAngular($daty){
	// Conversion date angular en format Y-m-d
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
        $data = array();
		$id = $this->get('id');
        if ($id) {
			// Récupération des recommandations non id
            $data = $this->ListecanevasformateManager->findById();
		} else {
			// Récupération de tous les recommandations
            $data = $this->ListecanevasformateManager->getlescanevasformate();
		}	
		if(!$data)
			$data=array();
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
        $dateupld = $this->convertDateAngular($this->post('date_upload'));     
		$emplacement = $this->post('repertoire');
		$temporaire = $this->post('id_utilisateur');
		$id_utilisateur=null;
		if(isset($temporaire) && $temporaire !="" && intval($temporaire) >0) {
			$id_utilisateur=$temporaire;
		}		
		$temporaire = $this->post('site_id');
		$site_id=null;
		if(isset($temporaire) && $temporaire !="" && intval($temporaire) >0) {
			$site_id=$temporaire;
		}	
		// Affectation des valeurs
		$data = array(
			'resume' => $this->post('resume'),
			'id_utilisateur' => $id_utilisateur,
			'nom_fichier' => $this->post('nom_fichier'),
			'repertoire' => $this->post('repertoire'),
			'date_upload' => $dateupld,
		);
        if ($supprimer == 0)  {
            if ($id == 0)  {
                if (!$data) {
                    $this->response([
                        'status' => FALSE,
                        'response' => 0,
                        'message' => 'No request found'
                            ], REST_Controller::HTTP_BAD_REQUEST);
                }
				// Ajout d'un enregistrement
                $dataId = $this->ListecanevasformateManager->add($data);
                
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
            }  else {
                if (!$data || !$id) {
                    $this->response([
                        'status' => FALSE,
                        'response' => 0,
                        'message' => 'No request found'
                            ], REST_Controller::HTTP_BAD_REQUEST);
                }
				// Mise à jour d'un enregistrement
                $update = $this->ListecanevasformateManager->update($id, $data);
                if(!is_null($update)) {
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
        } else  {
            if (!$id) {
                $this->response([
                    'status' => FALSE,
                    'response' => 0,
                    'message' => 'No request found'
                        ], REST_Controller::HTTP_BAD_REQUEST);
            }
			// Suppression d'un enregistrement
            $delete = $this->ListecanevasformateManager->delete($id);     
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
                        ], REST_Controller::HTTP_BAD_REQUEST);
            }
        }        
    }
}
/* End of file controllername.php */
/* Location: ./application/controllers/controllername.php */
?>