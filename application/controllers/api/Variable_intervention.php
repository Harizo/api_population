<?php
//harizo
defined('BASEPATH') OR exit('No direct script access allowed');

// afaka fafana refa ts ilaina
require APPPATH . '/libraries/REST_Controller.php';

class Variable_intervention extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('variable_intervention_model', 'VariableInterventionManager');
        $this->load->model('liste_variable_model', 'ListeVariableManager');
		$this->load->model('variable_model', 'VariableManager');
    }
    //recuperation détail variable intervention
    public function index_get() {
        $id = $this->get('id');
        $cle_etrangere = $this->get('cle_etrangere');
        if ($cle_etrangere){
            $data = array();
			// Selection liste variable
            $temporaire = $this->VariableInterventionManager->findAllByIdIntervention($cle_etrangere);
            if ($temporaire) {
               foreach ($temporaire as $key => $value) {
					// Affectation des valeurs des id_intervention dans un tableau : seul les id_intervention nous interesse
					// pour être selectionné dans des choix multiple 
                    $data[$key] = $value->id_variable;
                }
            }           
        } else {
            if ($id) {
                $data = array();
				// Selection par id
                $data = $this->VariableInterventionManager->findById($id);
            } else {
				// Selection de tous les enregistrements
                $menu = $this->VariableInterventionManager->findAll();
                if ($menu) {
                    foreach ($menu as $key => $value) {
						// Affectation des valeurs dans un tableau
                        $listevariable = array();
                        $listevariable = $this->ListeVariableManager->findById($value->id_liste_variable);
                        $data[$key]['id'] = $value->id;
                        $data[$key]['id_variable'] = $value->id_variable;
                        $data[$key]['id_liste_validation_beneficiaire'] = $value->id_liste_validation_beneficiaire;
                        $data[$key]['id_liste_variable'] = $value->id_liste_variable;
                        $data[$key]['listevariable'] = $listevariable;
                    }
                } else
                    $data = array();
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
    //insertion,modification,suppression détail variable intervention
    public function index_post() {
		$id_intervention = $this->post('id_intervention');
		$intitule_intervention = $this->post('intitule_intervention');
		$nombre_variable_intervention = $this->post('nombre_variable_intervention');
		$id_liste_validation_beneficiaire=null;
		$temporaire=$this->post('id_liste_validation_beneficiaire');
		if(isset($temporaire) && $temporaire !="" && intval($temporaire) >0) {
			$id_liste_validation_beneficiaire=$temporaire;
		}
		// Supprimer d'abord les variables existantes dans la table; puis réinsérer après
		$nombre_enregistrement_supprime= $this->VariableInterventionManager->deleteByIntervention($id_intervention);
		if($nombre_variable_intervention >0) {
			// Ajout détail type transfert
			for($i=1;$i<=$nombre_variable_intervention;$i++) {
				$id_variable = $this->post('id_variable_'.$i);
				$id_liste_variable =null;
				// Récupération id_liste_variable correspondant à id_variable
				$retour= $this->VariableManager->findById($id_variable);
				foreach($retour as $k=>$v) {
					$id_liste_variable = $v->id_liste_variable;
				}
				$data = array(
					'id_variable' => $id_variable,
					'id_liste_variable' => $id_liste_variable,
					'id_liste_validation_beneficiaire' => $id_liste_validation_beneficiaire,
					'id_intervention' => $id_intervention ,
				);
				$retour = $this->VariableInterventionManager->add($data);     
			}
		}
		$message_retour=" dans Annuaire d'intervention (variable); intervention :".$intitule_intervention; 
		if($nombre_enregistrement_supprime > 0 && $nombre_variable_intervention > 0) {
			$message_retour = "Modification".$message_retour;
		} else {
			$message_retour = "Ajout".$message_retour;
		} 
		$this->response([
			'status' => TRUE,
			'response' => $message_retour,
			'message' => 'Data insert success'
				], REST_Controller::HTTP_OK);			
    }
}
/* End of file controllername.php */
/* Location: ./application/controllers/controllername.php */
?>