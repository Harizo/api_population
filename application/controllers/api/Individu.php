<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Individu extends REST_Controller {

    public function __construct() {
        parent::__construct();
		// Ouverture des modèles utilisées
        $this->load->model('individu_model', 'IndividuManager');
        $this->load->model('fokontany_model', 'FokontanyManager');
        $this->load->model('acteur_model', 'ActeurManager');
        $this->load->model('type_beneficiaire_model', 'TypebeneficiaireManager');
        $this->load->model('enquete_menage_model', 'EnquetemenageManager');
    }
	// Conversion d'un date angular (date longue) au forlat Y-m-d
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
		$data=array();
        $id = $this->get('id');
        $cle_etrangere = $this->get('cle_etrangere');
        if ($id) {
			// Selection individu par id
            $data = $this->IndividuManager->findById($id);
            if (!$data)
                $data = array();
			$choix=1;
        } 
        else if($cle_etrangere) 
        {
			// Selection individu par ménage
			$menu = $this->IndividuManager->findAllByMenage($cle_etrangere);
			$choix=2;
		} else {	
			$choix=2;
			// Selection de tous les individus
			$menu = $this->IndividuManager->findAll();	
        }
		if($choix==2) {
            if ($menu) {
                foreach ($menu as $key => $value) {
					// Selection groupe appartenance
					$ga = $this->EnquetemenageManager->findById($value->id_groupe_appartenance,"groupe_appartenance");
					// Selection indice de vulnérabilité
					$indicevulnerable = $this->EnquetemenageManager->findById($value->id_indice_vulnerabilite,"indice_vulnerabilite");
                    $acteur = array();
                    $fokontany = array();
					// Selection description fokontany
                    $type_emp = $this->FokontanyManager->findById($value->id_fokontany);
					if(count($type_emp) >0) {
						$fokontany=$type_emp;
					}	
                    $data[$key]['id'] = $value->id;
                    $data[$key]['id_menage'] = $value->id_menage;
                    $data[$key]['identifiant_unique'] = $value->identifiant_unique;
                    $data[$key]['identifiant_appariement'] = $value->identifiant_appariement;
                    $data[$key]['date_enregistrement'] = $value->date_enregistrement;
                    $data[$key]['numero_ordre'] = $value->numero_ordre;
                    $data[$key]['numero_ordre_pere'] = $value->numero_ordre_pere;
                    $data[$key]['numero_ordre_mere'] = $value->numero_ordre_mere;
                    $data[$key]['inscription_etatcivil'] = $value->inscription_etatcivil;
                    $data[$key]['possede_cin'] = $value->possede_cin;
                    $data[$key]['numero_extrait_naissance'] = $value->numero_extrait_naissance;
                    $data[$key]['id_groupe_appartenance'] = $value->id_groupe_appartenance;
                    $data[$key]['groupe_appartenance'] = $ga;
                    $data[$key]['frequente_ecole'] = $value->frequente_ecole;
                    $data[$key]['avait_frequente_ecole'] = $value->avait_frequente_ecole;
                    $data[$key]['nom_ecole'] = $value->nom_ecole;
                    $data[$key]['occupation'] = $value->occupation;
                    $data[$key]['statut'] = $value->statut;
                    $data[$key]['date_sortie'] = $value->date_sortie;
                    $data[$key]['flag_integration_donnees'] = $value->flag_integration_donnees;
                    $data[$key]['nouvelle_integration'] = $value->nouvelle_integration;
                    $data[$key]['commentaire'] = $value->commentaire;
                    $data[$key]['nom'] = $value->nom;
                    $data[$key]['prenom'] = $value->prenom;
                    $data[$key]['cin'] = $value->cin;
                    $data[$key]['date_naissance'] = $value->date_naissance;
                    $data[$key]['sexe'] = $value->sexe;
					// Selection description : lien de parenté,les différents type d'handicaps,type école,niveau de classe et situation matrimoniale
					$lien = $this->EnquetemenageManager->findById($value->id_liendeparente,"liendeparente");
					$hv = $this->EnquetemenageManager->findById($value->id_handicap_visuel,"handicap_visuel");
					$hp = $this->EnquetemenageManager->findById($value->id_handicap_parole,"handicap_parole");
					$ha = $this->EnquetemenageManager->findById($value->id_handicap_auditif,"handicap_auditif");
					$hm = $this->EnquetemenageManager->findById($value->id_handicap_mental,"handicap_mental");
					$hmot = $this->EnquetemenageManager->findById($value->id_handicap_moteur,"handicap_moteur");
					$te = $this->EnquetemenageManager->findById($value->id_type_ecole,"type_ecole");
					$nclass = $this->EnquetemenageManager->findById($value->id_niveau_de_classe,"niveau_de_classe");
					$situationmatrimoniale = $this->EnquetemenageManager->findById($value->id_situation_matrimoniale,"situation_matrimoniale");
                    $data[$key]['liendeparente'] = $lien;
                    $data[$key]['id_liendeparente'] = $value->id_liendeparente;
                    $data[$key]['handicap_visuel_tab'] = $hv;
                    $data[$key]['id_handicap_visuel'] = $value->id_handicap_visuel;
                    $data[$key]['handicap_parole_tab'] = $hp;
                    $data[$key]['id_handicap_parole'] = $value->id_handicap_parole;
                    $data[$key]['handicap_auditif_tab'] = $ha;
                    $data[$key]['id_handicap_auditif'] = $value->id_handicap_auditif;
                    $data[$key]['handicap_mental_tab'] = $hm;
                    $data[$key]['id_handicap_mental'] = $value->id_handicap_mental;
                    $data[$key]['handicap_moteur_tab'] = $hmot;
                    $data[$key]['id_handicap_moteur'] = $value->id_handicap_moteur;
                    $data[$key]['type_ecole'] = $te;
                    $data[$key]['id_type_ecole'] = $value->id_type_ecole;
                    $data[$key]['niveau_de_classe'] = $nclass;
                    $data[$key]['id_niveau_de_classe'] = $value->id_niveau_de_classe;
                    $data[$key]['langue'] = $value->langue;
                    $data[$key]['situationmatrimoniale'] = $situationmatrimoniale;
                    $data[$key]['id_situation_matrimoniale'] = $value->id_situation_matrimoniale;
                    $data[$key]['id_fokontany'] = $value->id_fokontany;
                    $data[$key]['fokontany'] = $fokontany;
                    $data[$key]['id_acteur'] = $value->id_acteur;
                    $data[$key]['acteur'] = $acteur;
                    $data[$key]['decede'] = $value->decede;
                    $data[$key]['date_deces'] = $value->date_deces;
                    $data[$key]['chef_menage'] = $value->chef_menage;
                    $data[$key]['handicap_visuel'] = $value->handicap_visuel;
                    $data[$key]['handicap_parole'] = $value->handicap_parole;
                    $data[$key]['handicap_auditif'] = $value->handicap_auditif;
                    $data[$key]['handicap_moteur'] = $value->handicap_moteur;
                    $data[$key]['handicap_mental'] = $value->handicap_mental;
                    $data[$key]['id_indice_vulnerabilite'] = $value->id_indice_vulnerabilite;
					if($indicevulnerable) {
						$data[$key]['indicevulnerabilite'] =$indicevulnerable;
					} else {	
						$data[$key]['indicevulnerabilite'] = array();
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
		$date_naissance = $this->convertDateAngular($this->post('date_naissance'));
		$date_deces = $this->convertDateAngular($this->post('date_deces'));
		// Initialisation null et affectation des valeurs : pour éviter le ZERO par défaut au lieu de null
		$id_liendeparente=null;
		$lien = $this->post('id_liendeparente') ;
		if(isset($lien) && $lien !="" && intval($lien) >0) {
			$id_liendeparente=$lien;
		}
		$id_handicap_visuel=null;
		$hv = $this->post('id_handicap_visuel') ;
		if(isset($hv) && $hv !="" && intval($hv) >0) {
			$id_handicap_visuel=$hv;
		}
		$id_handicap_parole=null;
		$hv = $this->post('id_handicap_parole') ;
		if(isset($hv) && $hv !="" && intval($hv) >0) {
			$id_handicap_parole=$hv;
		}
		$id_handicap_auditif=null;
		$hv = $this->post('id_handicap_auditif') ;
		if(isset($hv) && $hv !="" && intval($hv) >0) {
			$id_handicap_auditif=$hv;
		}
		$id_handicap_mental=null;
		$hv = $this->post('id_handicap_mental') ;
		if(isset($hv) && $hv !="" && intval($hv) >0) {
			$id_handicap_mental=$hv;
		}
		$id_handicap_moteur=null;
		$hv = $this->post('id_handicap_moteur') ;
		if(isset($hv) && $hv !="" && intval($hv) >0) {
			$id_handicap_moteur=$hv;
		}
		$id_type_ecole=null;
		$hv = $this->post('id_type_ecole') ;
		if(isset($hv) && $hv !="" && intval($hv) >0) {
			$id_type_ecole=$hv;
		}
		$id_niveau_de_classe=null;
		$hv = $this->post('id_niveau_de_classe') ;
		if(isset($hv) && $hv !="" && intval($hv) >0) {
			$id_niveau_de_classe=$hv;
		}
		$id_situation_matrimoniale=null;
		$hv = $this->post('id_situation_matrimoniale') ;
		if(isset($hv) && $hv !="" && intval($hv) >0) {
			$id_situation_matrimoniale=$hv;
		}
		$id_acteur=null;
		$temporaire = $this->post('id_acteur') ;
		if(isset($temporaire) && $temporaire !="" && intval($temporaire) >0) {
			$id_acteur=$temporaire;
		}
		$id_fokontany=null;
		$temporaire = $this->post('id_fokontany') ;
		if(isset($temporaire) && $temporaire !="" && intval($temporaire) >0) {
			$id_fokontany=$temporaire;
		}
		$temporaire=$this->post('id_indice_vulnerabilite');
		if(isset($temporaire) && $temporaire !="" && intval($temporaire) >0) {
			$id_indice_vulnerabilite=$temporaire;
		}
		// Affectation des valeurs de chaque colonne de la table
		$data = array(
			'id_menage'                => $this->post('id_menage'),
			'identifiant_unique'       => $this->post('identifiant_unique'),
			'identifiant_appariement'  => $this->post('identifiant_appariement'),
			'date_enregistrement'      => $this->post('date_enregistrement'),
			'numero_ordre'             => $this->post('numero_ordre'),
			'numero_ordre_pere'        => $this->post('numero_ordre_pere'),
			'numero_ordre_mere'        => $this->post('numero_ordre_mere'),
			'inscription_etatcivil'    => $this->post('inscription_etatcivil'),
			'possede_cin'              => $this->post('possede_cin'),
			'numero_extrait_naissance' => $this->post('numero_extrait_naissance'),
			'id_groupe_appartenance'   => $this->post('id_groupe_appartenance'),
			'frequente_ecole'          => $this->post('frequente_ecole'),
			'avait_frequente_ecole'    => $this->post('avait_frequente_ecole'),
			'nom_ecole'                => $this->post('nom_ecole'),
			'occupation'               => $this->post('occupation'),
			'statut'                   => $this->post('statut'),
			'date_sortie'              => $this->post('date_sortie'),
			'flag_integration_donnees' => $this->post('flag_integration_donnees'),
			'nouvelle_integration'     => $this->post('nouvelle_integration'),
			'commentaire'              => $this->post('commentaire'),
			'nom'                      => $this->post('nom'),
			'prenom'                   => $this->post('prenom'),
			'cin'                      => $this->post('cin'),
			'date_naissance'           => $date_naissance,
			'sexe'                     => $this->post('sexe'),
			'id_liendeparente'         => $id_liendeparente,
			'id_handicap_visuel'       => $id_handicap_visuel,
			'id_handicap_parole'       => $id_handicap_parole,
			'id_handicap_auditif'      => $id_handicap_auditif,
			'id_handicap_mental'       => $id_handicap_mental,
			'id_handicap_moteur'       => $id_handicap_moteur,
			'id_type_ecole'            => $id_type_ecole,
			'id_niveau_de_classe'      => $id_niveau_de_classe,
			//'langue'                   => $langue,
			'id_situation_matrimoniale' => $id_situation_matrimoniale,
			'id_fokontany'             => $id_fokontany,
			'id_acteur'                => $id_acteur,
			'decede'                   => $this->post('decede'),
			'date_deces'               => $date_deces,
			'chef_menage'              => $this->post('chef_menage'),
			'handicap_visuel'          => $this->post('handicap_visuel'),
			'handicap_parole'          => $this->post('handicap_parole'),
			'handicap_auditif'         => $this->post('handicap_auditif'),
			'handicap_moteur'          => $this->post('handicap_moteur'),
			'handicap_mental'          => $this->post('handicap_mental'),
			'id_indice_vulnerabilite'  => $id_indice_vulnerabilite,
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
				// Ajout d'un enregistrement
                $dataId = $this->IndividuManager->add($data);
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
                $update = $this->IndividuManager->update($id, $data);
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
			// Suppresion d'un enregistrement
            $delete = $this->IndividuManager->delete($id);
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