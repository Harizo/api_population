<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';//Appel du librairie REST_Controller.php

class Axe_strategique extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('axe_strategique_model', 'AxestrategiqueManager');//fichier Axe_strategique_model.php
    }

    // Début Fonction pour la récupération des données de la base de données
    public function index_get() 
    {
        //Début Récupération des données passé en paramètre dans le controlleur angular js
        $id = $this->get('id');
        //fin Récupération des données passé en paramètre dans le controlleur angular js
		$data = array();

		if ($id) 
        {
			$tmp = $this->AxestrategiqueManager->findById($id);//Récupération de donnée par Id, appel du fonction "findById" du model "Axe_strategique_model.php" 
			if($tmp) {
				$data=$tmp;
			}
		} 
        else 
        {			
			$tmp = $this->AxestrategiqueManager->findAll();//Récupération des données de la base de données, appel du fonction "findAll" du model "Axe_strategique_model.php" 
			if ($tmp) {
				$data=$tmp;
			}
		}

        //Début Etat de sortie
        if (count($data)>0) //S'il y a des données dans la table,retourne le tableau $data
                $this->response([
                'status' => TRUE,
                'response' => $data,
                'message' => 'Get data success',
            ], REST_Controller::HTTP_OK);
        } 
        else 
        {
            $this->response([
                'status' => FALSE,
                'response' => array(),
                'message' => 'No data were found'
            ], REST_Controller::HTTP_OK);
        }
        //Début Etat de sortie
    }

    // Fin Fonction pour la récupération des données de la base de données

    // Début Fonction pour l'ajout,modification et suppresion des données de la base de données
    public function index_post() 
    {
        //Début Récupération des données passé en paramètre dans le controlleur angular js

        $id = $this->post('id') ;
        $supprimer = $this->post('supprimer') ;
        //Fin Récupération des données passé en paramètre dans le controlleur angular js

		$data = array(
			'objectif' => $this->post('objectif'),
			'axe' => $this->post('axe'),
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

                $dataId = $this->AxestrategiqueManager->add($data); 

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

                $update = $this->AxestrategiqueManager->update($id, $data);  

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
            if (!$id) 
            {
                $this->response([
                'status' => FALSE,
                'response' => 0,
                'message' => 'No request found'
                    ], REST_Controller::HTTP_BAD_REQUEST);
            }

            $delete = $this->AxestrategiqueManager->delete($id);

            if (!is_null($delete)) 
            {
                $this->response([
                    'status' => TRUE,
                    'response' => 1,
                    'message' => "Delete data success"
                        ], REST_Controller::HTTP_OK);
            } 
            else 
            {
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