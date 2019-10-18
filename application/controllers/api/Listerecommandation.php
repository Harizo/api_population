<?php

defined('BASEPATH') OR exit('No direct script access allowed');
//harizo
// afaka fafana refa ts ilaina
require APPPATH . '/libraries/REST_Controller.php';

class Listerecommandation extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('listerecommandation_model', 'listerecommandationManager');
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
        $data = array();
		$fait = $this->get('fait');
        if ($fait) {
            $data = $this->listerecommandationManager->getlesnonfait();
		} else {
            $data = $this->listerecommandationManager->getlesrecommandations();
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
		$tmp = $this->post('utilisateur_id');
		$utilisateur_id=null;
		if(isset($tmp) && $tmp !="" && intval($tmp) >0) {
			$utilisateur_id=$tmp;
		}		
		$tmp = $this->post('site_id');
		$site_id=null;
		if(isset($tmp) && $tmp !="" && intval($tmp) >0) {
			$site_id=$tmp;
		}		
		$data = array(
			'resume' => $this->post('resume'),
			'url' => $this->post('url'),
			'validation' => $this->post('validation'),
			'utilisateur_id' => $utilisateur_id,
			'site_id' => $site_id,
			'nom_fichier' => $this->post('nom_fichier'),
			'repertoire' => $this->post('repertoire'),
			'date_upload' => $dateupld,
			'fait' => $this->post('fait')
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
                $dataId = $this->listerecommandationManager->add($data);
                
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
                $update = $this->listerecommandationManager->update($id, $data);
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
            $delete = $this->listerecommandationManager->delete($id);     
		/*	// suppression fichier correspondant
			// $dossier_traite = "images/jpeg";
			$dossier_traite = dirname(__FILE__) ."/../../../../" .$emplacement;		
			$repertoire = opendir($dossier_traite); // On définit le répertoire dans lequel on souhaite travailler.
			$fichier=  $this->post('nom_fichier');
			while (false !== ($fichier = readdir($repertoire))) { // On lit chaque fichier du répertoire dans la boucle.
				$chemin = $dossier_traite."/".$fichier; // On définit le chemin du fichier à effacer.
				// Si le fichier n'est pas un répertoire…
				if ($fichier != ".." AND $fichier != "." AND !is_dir($fichier)) {
				   unlink($chemin); // On efface.
				}
			}
			closedir($repertoire); // Ne pas oublier de fermer le dossier ***EN DEHORS de la boucle*** ! Ce qui évitera à PHP beaucoup de calculs et des problèmes liés à l'ouverture du dossier.
		*/	
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