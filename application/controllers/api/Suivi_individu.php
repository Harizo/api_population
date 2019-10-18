<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Suivi_individu extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('suivi_individu_model', 'SuiviindividuManager');
        $this->load->model('sourcefinancement_model', 'SourcefinancementManager');
        $this->load->model('type_transfert_model', 'TypetransfertManager');
        $this->load->model('intervention_model', 'InterventionManager');
    }

    public function index_get() {
        $id = $this->get('id');

        $cle_etrangere = $this->get('cle_etrangere');
        $id_intervention = $this->get('id_intervention');
        $id_individu = $this->get('id_individu');
        $data = array() ;
        if ($cle_etrangere) 
        {
            $suivi_individu = $this->SuiviindividuManager->findAllByMenage($cle_etrangere);

            

            if ($suivi_individu) 
            {
                $data['id'] = ($suivi_individu->id);
                $data['id_individu'] = ($suivi_individu->id_individu);
                $data['id_intervention'] = unserialize($suivi_individu->id_intervention);
                
            }
        }
        else
        {
            if ($id_intervention && $id_individu) 
			{ 
				$data=array();
 				$id_i=$id_intervention;
               $id_prog = '"%'.$id_intervention.'%"' ;
                $list_suivi_individu = $this->SuiviindividuManager->findAllByProgrammeAndIndividu($id_intervention,$id_individu);
                if ($list_suivi_individu) 
                {
						$detail_suivi_individu=array();
						$nutrition=array();
						$transfert_argent=array();
						$mariage_precoce=array();
						$promotion_genre=array();
					
                    foreach ($list_suivi_individu as $key => $value) 
                    {
						$intervention=array();
						$typetransfert=array();
						$intervention = $this->InterventionManager->findById($id_i);
						$typetransfert = $this->TypetransfertManager->findById($value->id_type_transfert);
						$tmp=array();
						
						$tmp['id'] = $value->id;
                        $tmp['id_menage'] = ($value->id_menage);
                        $tmp['id_type_transfert'] = ($value->id_type_transfert);
                        $tmp['typetransfert'] = ($typetransfert);
                        $tmp['nom'] = ($value->nom);
                        $tmp['prenom'] = ($value->prenom);
                        $tmp['date_naissance'] = ($value->date_naissance);
                        $tmp['date_suivi'] = $value->date_suivi;
                        $tmp['id_intervention'] = $id_i;
                        $tmp['intervention'] = $intervention;
                        $tmp['montant'] = $value->montant;
                        $tmp['observation'] = $value->observation;	
						$detail_suivi_individu=$tmp;					
						if(intval($id_intervention)==5) {
							// Promotion genre : séparer en 2 les enregistrements
							if(intval($value->id_type_mariage) >0) {
								$mariage_precoce[]=$tmp;
							}	
							if(intval($value->id_type_violence) >0) {
								$promotion_genre[]=$tmp;
							} 
						} else if(intval($id_intervention)==3) {
							// Nutrition
							$nutrition[] =$tmp;
						} else  {
							// Transfert monétaire par défaut
							$transfert_argent[]=$tmp;
						}				   
					}
					$data[0]['detail_suivi_individu']=$detail_suivi_individu;
					$data[0]['mariage_precoce']=$mariage_precoce;
					$data[0]['promotion_genre']=$promotion_genre;
					$data[0]['nutrition']=$nutrition;
					$data[0]['transfert_argent']=$transfert_argent;					
                }				
			} 
			else	
            if ($id_intervention) 
            {
                $id_prog = '"'.$id_intervention.'"' ;
                $list_suivi_individu = $this->SuiviindividuManager->findAllByProgramme($id_prog);
                if ($list_suivi_individu) 
                {
                    foreach ($list_suivi_individu as $key => $value) 
                    {
                        $data[$key]['id'] = $value->id;
                        $data[$key]['NomInscrire'] = ($value->NomInscrire);
                        $data[$key]['PersonneInscription'] = ($value->PersonneInscription);
                        $data[$key]['AgeInscrire'] = ($value->AgeInscrire);
                        $data[$key]['Addresse'] = ($value->Addresse);
                        $data[$key]['NumeroEnregistrement'] = ($value->NumeroEnregistrement);
                       // $data['id_individu'] = ($suivi_individu->id_individu);
                        $data[$key]['id_intervention'] = ($id_intervention);
                        //$data[$key]['menage'] = $this->menageManager->findById($value->id_individu);
                       
                    }
                }
            }
            else
            {
                if ($id) 
                {
                    $data = $this->SuiviindividuManager->findById($id);
                } 
                else 
                {
                    $data = $this->SuiviindividuManager->findAll();                   
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
		$id_partenaire=null;
		$id_acteur=null;
		$id_type_transfert=null;
		$id_situation_matrimoniale=null;
		$id_type_mariage=null;
		$id_type_violence=null;
		$age=null;
		$montant=null;
		$poids=null;
		$perimetre_bracial=null;
		$age_mois=null;
		$taille=null;
		$zscore=null;
		$mois_grossesse=null;
		$tmp=$this->post('id_partenaire') ;
		if($tmp && intval($tmp) >0) {
			$id_partenaire=$tmp;
		}
		$tmp=$this->post('id_acteur') ;
		if($tmp && intval($tmp) >0) {
			$id_acteur=$tmp;
		}
		$tmp=$this->post('id_type_transfert') ;
		if($tmp && intval($tmp) >0) {
			$id_type_transfert=$tmp;
		}
		$tmp=$this->post('id_situation_matrimoniale') ;
		if($tmp && intval($tmp) >0) {
			$id_situation_matrimoniale=$tmp;
		}
		$tmp=$this->post('id_type_mariage') ;
		if($tmp && intval($tmp) >0) {
			$id_type_mariage=$tmp;
		}
		$tmp=$this->post('id_type_violence') ;
		if($tmp && intval($tmp) >0) {
			$id_type_violence=$tmp;
		}
		$tmp=$this->post('age') ;
		if($tmp && intval($tmp) >0) {
			$age=$tmp;
		}
		$tmp=$this->post('montant') ;
		if($tmp && intval($tmp) >0) {
			$montant=$tmp;
		}
		$tmp=$this->post('poids') ;
		if($tmp && intval($tmp) >0) {
			$poids=$tmp;
		}
		$tmp=$this->post('perimetre_bracial') ;
		if($tmp && intval($tmp) >0) {
			$perimetre_bracial=$tmp;
		}
		$tmp=$this->post('age_mois') ;
		if($tmp && intval($tmp) >0) {
			$age_mois=$tmp;
		}
		$tmp=$this->post('taille') ;
		if($tmp && intval($tmp) >0) {
			$taille=$tmp;
		}
		$tmp=$this->post('zscore') ;
		if($tmp) {
			$zscore=$tmp;
		}
		$tmp=$this->post('mois_grossesse') ;
		if($tmp && intval($tmp) >0) {
			$mois_grossesse=$tmp;
		}
        if ($supprimer == 0) {
			$data = array(
				'id_intervention' => $this->post('id_intervention'),
				'date_suivi' => $this->post('date_suivi'),
				'montant' => $montant,
				'observation' => $this->post('observation'),
				'id_individu' => $this->post('id_individu'),
				'id_type_transfert' => $id_type_transfert,
			);               
            if ($id == 0) {
                if (!$data) 
                {
                    $this->response([
                        'status' => FALSE,
                        'response' => 0,
                        'message' => 'No request found'
                            ], REST_Controller::HTTP_BAD_REQUEST);
                }

                $dataId = $this->SuiviindividuManager->add($data);

                if (!is_null($dataId)) 
                {
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
                $update = $this->SuiviindividuManager->update($id, $data);              
                if(!is_null($update)){
                    $this->response([
                        'status' => TRUE, 
                        'response' => $id,
                        'message' => 'Update data success'
                            ], REST_Controller::HTTP_OK);
                } else {
                    $this->response([
                        'status' => FALSE,
                        'message' => 'No request found'
                            ], REST_Controller::HTTP_OK);
                }
            }
        } else {
            if (!$id) {
            $this->response([
            'status' => FALSE,
            'response' => 0,
            'message' => 'No request found'
                ], REST_Controller::HTTP_BAD_REQUEST);
            }
            $delete = $this->SuiviindividuManager->delete($id);          
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
}
?>