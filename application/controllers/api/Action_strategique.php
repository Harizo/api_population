<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';//Appel du librairie REST_Controller.php

class Action_strategique extends REST_Controller {

    //Début Importation des models
    public function __construct() {
        parent::__construct();
        $this->load->model('action_strategique_model', 'ActionstrategiqueManager');//fichier Action_strategique_model.php
        $this->load->model('axe_strategique_model', 'AxestrategiqueManager');//fichier Axe_strategique_model.php
    }
    //fin Importation des models

    // Début Fonction pour la récupération des données de la base de données(Table "action_strategique")
    public function index_get() 
    {
        //Début Récupération des données passé en paramètre dans le controlleur angular js
        $id = $this->get('id');
        //fin Récupération des données passé en paramètre dans le controlleur angular js
		$data = array();
		if ($id) 
        {
			$tmp = $this->ActionstrategiqueManager->findById($id);//Récupération des données de la base de données par Id, appel du fonction "findById" du model "Action_strategique_model.php"
			if($tmp) 
            {
				$data=$tmp;
			}
		} 
        else 
        {			
			$menu = $this->ActionstrategiqueManager->findAll();//Récupération des données de la base de données, appel du fonction "findAll" du model "Action_strategique_model.php" 
			if ($menu) //s'il y a de données dans la base
            {
                foreach ($menu as $key => $value) 
                {
					$axestrategique =array();
					if($value->id_axe_strategique) 
                    {
						$prg = $this->AxestrategiqueManager->findById($value->id_axe_strategique);//Récupération de l'axe strategique par id_axe_strategique, appel du fonction "findById" du model "Axe_strategique_model.php" 
						if(count($prg) >0) 
                        {
							$axestrategique=$prg;
						}						
					}					
                    $data[$key]['id'] = $value->id;
                    $data[$key]['id_axe_strategique'] = $value->id_axe_strategique;
                    $data[$key]['axestrategique'] = $axestrategique;
                    $data[$key]['code'] = $value->code;
                    $data[$key]['action'] = $value->action;
				}
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
        else //Si la table est vide, retourne une tableau vide
        {
            $this->response([
                'status' => FALSE,
                'response' => array(),
                'message' => 'No data were found'
            ], REST_Controller::HTTP_OK);
        }

        //Fin Etat de sortie
    }
    // Fin Fonction pour la récupération des données de la base de données(Table "action_strategique")

    // Début Fonction pour l'ajout,modification et suppresion des données de la base de données
    public function index_post() 
    {

        //Début Récupération des données passé en paramètre dans le controlleur angular js
        $id = $this->post('id') ;
        $supprimer = $this->post('supprimer') ;
		$id_axe_strategique=null;
		$tmp=$this->post('id_axe_strategique');
		if(isset($tmp) && $tmp !="" && intval($tmp) >0) {
			$id_axe_strategique=$tmp;
		}

        //Fin Récupération des données passé en paramètre dans le controlleur angular js


		$data = array(
			'action' => $this->post('action'),
			'code' => $this->post('code'),
			'id_axe_strategique' => $id_axe_strategique,
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

                $dataId = $this->ActionstrategiqueManager->add($data);      //Appel du fonction "add" du model 

                //Début Etat de sortie
                if (!is_null($dataId)) //Si insérer avec succes
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
                //Fin Etat de sortie
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

                $update = $this->ActionstrategiqueManager->update($id, $data); //Appel du fonction "update" du model 

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

            $delete = $this->ActionstrategiqueManager->delete($id);     //Appel du fonction "delete" du model   

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