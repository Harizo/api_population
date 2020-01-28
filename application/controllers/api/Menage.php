<?php

defined('BASEPATH') OR exit('No direct script access allowed');
//harizo
// afaka fafana refa ts ilaina
require APPPATH . '/libraries/REST_Controller.php';

class Menage extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('menage_model', 'MenageManager');
        $this->load->model('fokontany_model', 'FokontanyManager');
        $this->load->model('acteur_model', 'ActeurManager');
        $this->load->model('type_beneficiaire_model', 'TypebeneficiaireManager');
    }
	// Conversion date angular date longue en  format y-m-d
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
        $cle_etrangere = $this->get('cle_etrangere');
		$data=array();
        if ($id) {
			// Récupération par id (clé primaire)
            $data = $this->MenageManager->findById($id);
            if (!$data)
                $data = array();
			$choix=1;
        } else if($cle_etrangere) {
			// Récupération par id_fokontany
			$choix=2;
			$menu = $this->MenageManager->findByIdFokontany($cle_etrangere);	
		} else {
			// Récupération de tous les enregistrements
			$choix=2;			
			$menu = $this->MenageManager->findAll();	
        }
		if($choix==2) {
            if ($menu) {
                foreach ($menu as $key => $value) {
					// Description fokontany
                    $fokontany = array();
                    $type_emp = $this->FokontanyManager->findById($value->id_fokontany);
					if(count($type_emp) >0) {
						$fokontany=$type_emp;
					}
					// Description type bénéficiaire
                    $type_beneficiaire = array();
                    $type_emp = $this->TypebeneficiaireManager->findById($value->id_type_beneficiaire);
					if(count($type_emp) >0) {
						$type_beneficiaire=$type_emp;
					}	
					// Description acteur
                    $acteur = array();
                    $acteur_temp = $this->ActeurManager->findById($value->id_type_beneficiaire);
					if(count($acteur_temp) >0) {
						$acteur=$acteur_temp;
					}
					// Valeur retourné en tableau	
                    $data[$key]['id'] = $value->id;
                    $data[$key]['identifiant_unique'] = $value->identifiant_unique;
                    $data[$key]['identifiant_appariement'] = $value->identifiant_appariement;
                    $data[$key]['numero_sequentiel'] = $value->numero_sequentiel;
                    $data[$key]['lieu_residence'] = $value->lieu_residence;
                    $data[$key]['surnom_chefmenage'] = $value->surnom_chefmenage;
                    $data[$key]['nom'] = $value->nom;
                    $data[$key]['prenom'] = $value->prenom;
                    $data[$key]['cin'] = $value->cin;
                    $data[$key]['chef_menage'] = $value->chef_menage;
                    $data[$key]['adresse'] = $value->adresse;
                    $data[$key]['date_naissance'] = $value->date_naissance;
                    $data[$key]['profession'] = $value->profession;
                    $data[$key]['id_situation_matrimoniale'] = $value->id_situation_matrimoniale;
                    $data[$key]['sexe'] = $value->sexe;
                    $data[$key]['date_inscription'] = $value->date_inscription;
                    $data[$key]['nom_prenom_pere'] = $value->nom_prenom_pere;
                    $data[$key]['nom_prenom_mere'] = $value->nom_prenom_mere;
                    $data[$key]['telephone'] = $value->telephone;
                    $data[$key]['statut'] = $value->statut;
                    $data[$key]['date_sortie'] = $value->date_sortie;
                    $data[$key]['nom_enqueteur'] = $value->nom_enqueteur;
                    $data[$key]['date_enquete'] = $value->date_enquete;
                    $data[$key]['nom_superviseur_enquete'] = $value->nom_superviseur_enquete;
                    $data[$key]['date_supervision'] = $value->date_supervision;
                    $data[$key]['flag_integration_donnees'] = $value->flag_integration_donnees;
                    $data[$key]['nouvelle_integration'] = $value->nouvelle_integration;
                    $data[$key]['commentaire'] = $value->commentaire;
                    $data[$key]['revenu_mensuel'] = $value->revenu_mensuel;
                    $data[$key]['depense_mensuel'] = $value->depense_mensuel;
                    $data[$key]['id_fokontany'] = $value->id_fokontany;
                    $data[$key]['fokontany'] = $fokontany;
                    $data[$key]['id_acteur'] = $value->id_acteur;
                    $data[$key]['acteur'] = $acteur;
                    $data[$key]['id_type_beneficiaire'] = $value->id_type_beneficiaire;
                    $data[$key]['type_beneficiaire'] = $type_beneficiaire;
                    $data[$key]['etat_groupe'] = $value->etat_groupe;
					$data[$key]['decede'] = $value->decede;
                    $data[$key]['date_deces'] = $value->date_deces;
          }
            }
            if (!$data)
                $data = array();			
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
		$date_naissance = $this->convertDateAngular($this->post('date_naissance'));
		$date_inscription = $this->convertDateAngular($this->post('date_inscription'));
		$date_deces = $this->convertDateAngular($this->post('date_deces'));
		$id_fokontany=null;
		$id_type_beneficiaire=null;
		$id_acteur=null;
		$temporaire=$this->post('id_fokontany');
		if(isset($temporaire) && $temporaire !="" && intval($temporaire) >0) {
			$id_fokontany=$temporaire;
		}
		$temporaire=$this->post('id_type_beneficiaire');
		if(isset($temporaire) && $temporaire !="" && intval($temporaire) >0) {
			$id_type_beneficiaire=$temporaire;
		}
		$temporaire=$this->post('id_acteur');
		if(isset($temporaire) && $temporaire !="" && intval($temporaire) >0) {
			$id_acteur=$temporaire;
		}
		// Affectation valeur colonne
		$data = array(
			'identifiant_unique'     => $this->post('identifiant_unique'),
			'identifiant_appariement'=> $this->post('identifiant_appariement'),
			'numero_sequentiel'      => $this->post('numero_sequentiel'),
			'lieu_residence'         => $this->post('lieu_residence'),
			'surnom_chefmenage'      => $this->post('surnom_chefmenage'),
			'nom'                    => $this->post('nom'),
			'prenom'                 => $this->post('prenom'),
			'cin'                    => $this->post('cin'),
			'chef_menage'            => $this->post('chef_menage'),
			'adresse'                => $this->post('adresse'),
			'date_naissance'         => $date_naissance,
			'profession'             => $this->post('profession'),
			'id_situation_matrimoniale' => $this->post('id_situation_matrimoniale'),
			'sexe'                   => $this->post('sexe'),
			'date_inscription'       => $date_inscription,
			'nom_prenom_pere'         => $this->post('nom_prenom_pere'),
			'nom_prenom_mere'         => $this->post('nom_prenom_mere'),
			'telephone'               => $this->post('telephone'),
			'statut'                  => $this->post('statut'),
			'date_sortie'            => $this->post('date_sortie'),
			'nom_enqueteur'            => $this->post('nom_enqueteur'),
			'date_enquete'            => $this->post('date_enquete'),
			'nom_superviseur_enquete' => $this->post('nom_superviseur_enquete'),
			'date_supervision' => $this->post('date_supervision'),
			'flag_integration_donnees' => $this->post('flag_integration_donnees'),
			'nouvelle_integration' => $this->post('nouvelle_integration'),
			'commentaire' => $this->post('commentaire'),
			'revenu_mensuel'         => $this->post('revenu_mensuel'),
			'depense_mensuel'        => $this->post('depense_mensuel'),
			'id_fokontany'           => $id_fokontany,
			'id_acteur'              => $id_acteur,
			'id_type_beneficiaire'   => $id_type_beneficiaire,
			'etat_groupe'            => $this->post('etat_groupe'),
			'decede'                 => $this->post('decede'),
			'date_deces'             => $date_deces,
		);
		// Supprimer =0 veut dire : soit un ajout ou mise a jour sinon suppression d'un enregistrement
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
                $dataId = $this->MenageManager->add($data);
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
                $update = $this->MenageManager->update($id, $data);
                if(!is_null($update)) {
                    $this->response([
                        'status' => TRUE,
                        'response' => 1,
                        'message' => 'Update data success'
                            ], REST_Controller::HTTP_OK);
                } else  {
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
            $delete = $this->MenageManager->delete($id);
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
?>