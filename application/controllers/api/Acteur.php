<?php

    defined('BASEPATH') OR exit('No direct script access allowed');

    require APPPATH . '/libraries/REST_Controller.php'; //Appel du librairie REST_Controller.php

    class Acteur extends REST_Controller 
    {

        //Début Importation des models
        public function __construct() 
        {
            parent::__construct();
            $this->load->model('acteur_model', 'ActeurManager'); //fichier Acteur_model.php
            $this->load->model('fokontany_model', 'FokontanyManager');//fichier Fokontany_model.php
            $this->load->model('type_acteur_model', 'TypeacteurManager');//fichier Type_acteur_model.php
        }
        //fin Importation des models

        // Début Fonction pour la récupération des données de la base de données(Table "acteur")
        public function index_get() 
        {
            //Début Récupération des données passé en paramètre dans le controlleur angular js
            $id = $this->get('id');
            $nom_partenaire = strtolower($this->get('nom_partenaire'));
            //Fin Récupération des données passé en paramètre dans le controlleur angular js
    		$data = array();//Initialisation du tableau de sortie $data

    		if($nom_partenaire) 
            {
    			$data=$this->ActeurManager->findByNom($nom_partenaire);//Récupération des données de la base de données par partenaire,appel du fonction "findByNom" du model "Acteur_model.php"
    		} 
            else	if ($id) 
            {
    				$tmp = $this->ActeurManager->findById($id);//Récupération des données de la base de données par Id, appel du fonction "findById" du model "Acteur_model.php"
    				if($tmp) 
                    {
    					$data=$tmp;
    				}
    		} 
            else 
            {			
    			$menu = $this->ActeurManager->findAll();//Récupération des données de la base de données, appel du fonction "findAll" du model "Acteur_model.php" 
    			if ($menu) 
                {
    				foreach ($menu as $key => $value) 
                    {
    					$fokontany = array();
    					$type_fin = $this->FokontanyManager->findById($value->id_fokontany);//Récupération de l'objet Fokontany par id_fokontany, appel du fonction "findById" du model "Fokontany_model.php" 

    					if(count($type_fin) >0) 
                        {
    						$fokontany=$type_fin;
    					}	

    					$typeacteur = array();
    					$type_fin = $this->TypeacteurManager->findById($value->id_type_acteur);//Récupération de l'objet Type Acteur par id_type_acteur, appel du fonction "findById" du model "Type_acteur_model.php" 

    					if(count($type_fin) >0) 
                        {
    						$typeacteur=$type_fin;
    					}	

    					$data[$key]['id'] = $value->id;
    					$data[$key]['code'] = $value->code;
    					$data[$key]['nom'] = $value->nom;
    					$data[$key]['nif'] = $value->nif;
    					$data[$key]['stat'] = $value->stat;
    					$data[$key]['adresse'] = $value->adresse;
    					$data[$key]['id_fokontany'] = $value->id_fokontany;
    					$data[$key]['fokontany'] = $fokontany;
    					$data[$key]['id_type_acteur'] = $value->id_type_acteur;
    					$data[$key]['typeacteurs'] = $typeacteur;
    					$data[$key]['representant'] = $value->representant;
    					$data[$key]['fonction'] = $value->fonction;
    					$data[$key]['telephone'] = $value->telephone;
    					$data[$key]['email'] = $value->email;
    					$data[$key]['rcs'] = $value->rcs;
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
        // Fin Fonction pour la récupération des données de la base de données(Table "acteur")

        // Début Fonction pour l'ajout,modification et suppresion des données de la base de données(Table "acteur")
        public function index_post() 
        {
            //Début Récupération des données passé en paramètre dans le controlleur angular js
            $id = $this->post('id') ;
            $supprimer = $this->post('supprimer') ;
    		$id_fokontany=null;
    		$tmp=$this->post('id_fokontany');
    		if(isset($tmp) && $tmp !="" && intval($tmp) >0) 
            {
    			$id_fokontany=$tmp;
    		}
    		$id_type_acteur=null;
    		$tmp=$this->post('id_type_acteur');
    		if(isset($tmp) && $tmp !="" && intval($tmp) >0) 
            {
    			$id_type_acteur=$tmp;
    		}
            //Fin Récupération des données passé en paramètre dans le controlleur angular js
     		$data = array(
    			'code' => $this->post('code'),
    			'nom' => $this->post('nom'),
    			'nif' => $this->post('nif'),
    			'stat' => $this->post('stat'),
    			'adresse' => $this->post('adresse'),
    			'id_fokontany' => $id_fokontany,
    			'representant' => $this->post('representant'),
    			'fonction' => $this->post('fonction'),
    			'telephone' => $this->post('telephone'),
    			'email' => $this->post('email'),
    			'rcs' => $this->post('rcs'),
    			'id_type_acteur' => $id_type_acteur,
    		);      
            if ($supprimer == 0) //Si c'est pas une suppression de donnée
            {

                if (!$data) //Vérification si $data a été créer
                {
                    $this->response([
                        'status' => FALSE,
                        'response' => 0,
                        'message' => 'Data not found'
                            ], REST_Controller::HTTP_BAD_REQUEST);
                }


                if ($id == 0) //Si Ajout de nouvel enregistrement
                {
                    
                    $dataId = $this->ActeurManager->add($data);  //Appel du fonction "add" du model "Acteur_model.php"
                    
                    //Début Etat de sortie
                    if (!is_null($dataId)) //Si insérer avec succes
                    {
                        $this->response([
                            'status' => TRUE,
                            'response' => $dataId,
                            'message' => 'Data insert success'
                                ], REST_Controller::HTTP_OK);
                    } 
                    else //Si une erreur est survenu
                    {
                        $this->response([
                            'status' => FALSE,
                            'response' => 0,
                            'message' => 'No request found'
                                ], REST_Controller::HTTP_BAD_REQUEST);
                    }
                    //Fin Etat de sortie
                } //Fin Ajout
                else //Si mise à jour
                {
                    

                    $update = $this->ActeurManager->update($id, $data); //Appel du fonction "update" du model "Acteur_model.php"

                    //Début Etat de sortie 
                    if(!is_null($update))//Si mise à jour avec succès
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
                    //Fin Etat de sortie 
                }//fin mise à jour
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
                $delete = $this->ActeurManager->delete($id); //Appel du fonction "delete" du model "Acteur_model.php"
                if (!is_null($delete)) //Si supprimer avec succès
                {
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
            }   //Fin suppression de donnée
        }
        // Fin Fonction pour l'ajout,modification et suppresion des données de la base de données(Table "acteur")
    }
?>