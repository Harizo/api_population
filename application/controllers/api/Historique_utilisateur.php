<?php
//harizo
defined('BASEPATH') OR exit('No direct script access allowed');

// afaka fafana refa ts ilaina
require APPPATH . '/libraries/REST_Controller.php';

class Historique_utilisateur extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('utilisateurs_model', 'UserManager');
        $this->load->model('historique_utilisateur_model', 'HistoriqueutilisateurManager');
        $this->load->model('site_model', 'SiteManager');
    }

    //recuperation donnée
    public function index_get() {
        $id = $this->get('id');
        $menu= $this->get('menu');
        $date_debut = $this->get('date_debut'); 
        $date_fin = $this->get('date_fin'); 
        $id_utilisateur = $this->get('id_utilisateur');  

        if ($id) 
        {
            $data = array();
            $histo_user = $this->HistoriqueutilisateurManager->findById($id);
            if ($histo_user) 
            {
                $user = $this->UserManager->findById($histo_user->id_utilisateur);
                $data['id'] = $histo_user->id;
                $data['action'] = $histo_user->action;
                $data['id_utilisateur'] = $histo_user->id_utilisateur;
                $data['user'] = $user;
            }
            
        } 
        elseif($menu=="filtrehistorique")
        {   $data = array();
            $historique = $this->HistoriqueutilisateurManager->findByDateUtilisateur($this->generer_requete_filtre($date_debut,$date_fin,$id_utilisateur));
            if($historique)
            {   
                foreach ($historique as $key => $value)
                {
                    $utilisateur = $this->UserManager->findById($value->id_utilisateur);
                    $data[$key]['id'] = $value->id;
                    $data[$key]['action'] = $value->action;
                    $data[$key]['date_action'] = $value->date_action;
                    $data[$key]['user'] = $utilisateur;
                }
                
            }
        }
        else {
            $menu = $this->HistoriqueutilisateurManager->findAll();
            if ($menu) {
                foreach ($menu as $key => $value) {
                    $user = array();
                    $user = $this->UserManager->findById($value->id_utilisateur);
                    $data[$key]['id'] = $value->id;
                    $data[$key]['action'] = $value->action;
                    $data[$key]['date_action'] = $value->date_action;
                    $data[$key]['id_utilisateur'] = $value->id_utilisateur;
                    //$data[$key]['user'] = $user;
                    $data[$key]['site'] = $this->SiteManager->findById($user->site_id);
                    $data[$key]['nom'] = $user->nom;
                    $data[$key]['prenom'] = $user->prenom;
                    $data[$key]['telephone'] = $user->telephone;
                    $data[$key]['cin'] = $user->cin;
                }
            } else
                $data = array();
        }
        
        

        if (count($data)>0)
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
                'status' => FALSE,
                'response' => array(),
                'message' => 'No data were found'
            ], REST_Controller::HTTP_OK);
        }
    }

    //insertion,modification, suppression donnée
    public function index_post() {

        $id = $this->post('id') ;
    
          
        $data = array(
            'action' => $this->post('action'),
            'id_utilisateur' => $this->post('id_utilisateur')
        );
        if (!$data) {
            $this->response([
                'status' => FALSE,
                'response' => 0,
                'message' => 'No request found'
                    ], REST_Controller::HTTP_BAD_REQUEST);
        }

        $dataId = $this->HistoriqueutilisateurManager->add($data);

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

    public function index_put($id) {
        $data = array(
            'code' => $this->put('code'),
            'nom' => $this->put('nom'),
            'district_id' => $this->put('district_id')
        );

        if (!$data || !$id) {
            $this->response([
                'status' => FALSE,
                'response' => 0,
                'message' => 'No request found'
                    ], REST_Controller::HTTP_BAD_REQUEST);
        }

        $update = $this->HistoriqueutilisateurManager->update($id, $data);

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
                    ], REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    public function index_delete($id) {
        if (!$id) {
            $this->response([
                'status' => FALSE,
                'response' => 0,
                'message' => 'No request found'
                    ], REST_Controller::HTTP_BAD_REQUEST);
        }
        $delete = $this->HistoriqueutilisateurManager->delete($id);
        if (!is_null($delete)) {
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
                    ], REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    //requete du filtre 
    public function generer_requete_filtre($date_debut,$date_fin,$id_utilisateur)
    {
        $requete = "date_action BETWEEN '".$date_debut."' AND '".$date_fin."' " ;
        if($id_utilisateur!='*' && $id_utilisateur!='undefined')
        {
            $requete = $requete." AND id_utilisateur='".$id_utilisateur."'" ;
        }
        return $requete;
    }

}

/* End of file controllername.php */
/* Location: ./application/controllers/controllername.php */
