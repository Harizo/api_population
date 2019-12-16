<?php

/*Les CRUD des tables : type_logement, occupation_logement, revetement_toit, revetement_sol, revetement_mur,
 source_eclairage, combustible, toilette, source_eau, bien_equipement, lien_parente, handicap_visuel,
 handicap_parole, handicap_auditif, handicap_mental, handicap_moteur, type_ecole, langue, niveau_de_classe,
 groupe_appartenance, situation_matrimoniale sont gérer par cette controller et sont model*/

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';//Appel du librairie REST_Controller.php

class Enquete_menage extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('enquete_menage_model', 'EnquetemenageManager');//fichier Enquete_menage_model.php
    }

    // Début Fonction pour la récupération des données de la base de données
    public function index_get() 
    {
        //Début Récupération des données passé en paramètre dans le controlleur angular js
        $id = $this->get('id');
        $nom_table = $this->get('nom_table');	

        //Fin Récupération des données passé en paramètre dans le controlleur angular js	
		$data = array();

		if ($id) 
        {
			/*$tmp = $this->EnquetemenageManager->findById($nom_table);
			if($tmp) {
				$data=$tmp;
			}*/
		} 
        else 
        {			
			$tmp = $this->EnquetemenageManager->findAll($nom_table);//Récupération des données de la base de données, appel du fonction "findAll" du model "enquete_menage_model.php" 
			if ($tmp) 
            {
				$data=$tmp;
			}
		}
        //Début Etat de sortie
        if (count($data)>0) //S'il y a des données dans la table,retourne le tableau $data
        {
            $this->response([
                'status' => TRUE,
                'response' => $data,
                'message' => 'Get data success',
            ], REST_Controller::HTTP_OK);
        } 
        else 
        {
            $this->response([
                'status' => TRUE,
                'response' => array(),
                'message' => 'No data were found'
            ], REST_Controller::HTTP_OK);
        }
        //Fin Etat de sortie
    }
    // Fin Fonction pour la récupération des données de la base de données

    // Début Fonction pour l'ajout,modification et suppresion des données de la base de données
    public function index_post() 
    {
        //Début Récupération des données passé en paramètre dans le controlleur angular js
        $id = $this->post('id') ;
        $nom_table = $this->post('nom_table') ;
        $supprimer = $this->post('supprimer') ;
        //Fin Récupération des données passé en paramètre dans le controlleur angular js

		$data = array(
			'description' => $this->post('description'),
			'code' => $this->post('code'),
		);               

        if ($supprimer == 0) //Si c'est pas une suppression de donnée
        {
            if ($id == 0) //Si Ajout de nouvel enregistrement
            {
                if (!$data) 
                {
                    $this->response([
                        'status' => FALSE,
                        'response' => 0,
                        'message' => 'No request found'
                            ], REST_Controller::HTTP_BAD_REQUEST);
                }

                $dataId = $this->EnquetemenageManager->add($data,$nom_table);  

                if (!is_null($dataId)) 
                {
                    $this->response([
                        'status' => TRUE,
                        'response' => $dataId,
                        'message' => 'Data insert success'
                            ], REST_Controller::HTTP_OK);
                } 
                else 
                {
                    $this->response([
                        'status' => FALSE,
                        'response' => 0,
                        'message' => 'No request found'
                            ], REST_Controller::HTTP_BAD_REQUEST);
                }
            } 
            else //Si mise à jour
            {
                if (!$data || !$id) 
                {
                    $this->response([
                        'status' => FALSE,
                        'response' => 0,
                        'message' => 'No request found'
                            ], REST_Controller::HTTP_BAD_REQUEST);
                }

                $update = $this->EnquetemenageManager->update($id, $data,$nom_table); 

                if(!is_null($update))
                {
                    $this->response([
                        'status' => TRUE, 
                        'response' => 1,
                        'message' => 'Update data success'
                            ], REST_Controller::HTTP_OK);
                } 
                else 
                {
                    $this->response([
                        'status' => FALSE,
                        'message' => 'No request found'
                            ], REST_Controller::HTTP_OK);
                }
            }
        } 
        else //Si Suppression de donnée
        {
            if (!$id) {
            $this->response([
            'status' => FALSE,
            'response' => 0,
            'message' => 'No request found'
                ], REST_Controller::HTTP_BAD_REQUEST);
            }

            $delete = $this->EnquetemenageManager->delete($id,$nom_table);  

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
    // Fin Fonction pour l'ajout,modification et suppresion des données de la base de données
}
?>