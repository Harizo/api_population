<?php

defined('BASEPATH') OR exit('No direct script access allowed');
//harizo
// afaka fafana refa ts ilaina
require APPPATH . '/libraries/REST_Controller.php';

class Environment_et_systeme extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('environment_demo_socio_model', 'Environment_demo_socioManager');
        $this->load->model('systeme_protection_social_model', 'Systeme_protection_socialManager');
        $this->load->model('region_model', 'RegionManager');
        $this->load->model('district_model', 'DistrictManager');
        $this->load->model('commune_model', 'CommuneManager');
        $this->load->model('intervention_model', 'InterventionManager');
    }

    public function index_get() {
        
        $menu = $this->get('menu');
        $id_region= $this->get('id_region'); 
        $id_district= $this->get('id_district');
        $id_commune= $this->get('id_commune');
        $id_intervention= $this->get('id_intervention');

        //CODE HARIZO
        $id_type_transfert= $this->get('id_type_transfert');
        $date_debut= $this->get('date_debut');
        $date_fin= $this->get('date_fin');
        //FIN CODE HARIZO
        $now = date('Y-m-d');

        $scolaire_max = date('Y-m-d', strtotime($now. ' -18 years +1 days'));
        $scolaire_min = date('Y-m-d', strtotime($now. ' -7 years')); 
        $agee = date('Y-m-d', strtotime($now. ' -60 years'));

        $travail_max = date('Y-m-d', strtotime($now. ' -60 years +1 days'));
        $travail_min = date('Y-m-d', strtotime($now. ' -18 years'));
        $enfant = date('Y-m-d', strtotime($now. ' -7 years +1 days'));

        if ($menu =='req1theme1_petitenfan_agesco_agetrava_agee_region_dist_comm')
        {
            $data = $this->Environment_demo_socioManager->findEffectif_sexe_age($this->generer_requete_filtre($id_region,$id_district,$id_commune,'*'),$enfant,$scolaire_min,$scolaire_max,$travail_min,$travail_max,$agee);
           
        }
        if ($menu =='req3theme1_menagenfan_menagscolai_region_dist_comm')
        {            
            $data = $this->Environment_demo_socioManager->findEffectif_menage_enfant($this->generer_requete_filtre($id_region,$id_district,$id_commune,'*'),$enfant,$scolaire_min,$scolaire_max);      
           
        }
        if ($menu =='req38theme2_interven_petitenfan_agesco_agetrava_agee_region_dist_comm')
        {
            $data = $this->Systeme_protection_socialManager->repartitionBeneficiaireIndividu_sexe_age($this->generer_requete_filtre($id_region,$id_district,$id_commune,$id_intervention),$enfant,$scolaire_min,$scolaire_max,$travail_min,$travail_max,$agee);
            
            
        }

        if ($menu =='req33theme2_interven_nbrbenef_region_dist_comm')
        {
            $individu = array();
            $region = array();
            $district = array();
            $commune = array();
            $intervention = array();
            $data = array(); 

            if ($id_intervention !='*' && $id_intervention!='undefined')
            { 
                $intervention = $this->InterventionManager->findById($id_intervention) ;
            }else{
                $intervention = $this->InterventionManager->findAll() ;
            }
           /* if ($id_region !='*' && $id_region!='undefined')
            { 
                $region = $this->RegionManager->findById($id_region) ;
            }
            else{
                $region = $this->RegionManager->findAllByRegion($id_region) ;
            }*/
            if ($id_region)
            {
                if ($id_district !='*' && $id_district!='undefined' && $id_district!='null')
                {
                    $district = $this->DistrictManager->findById($id_district) ;
                }else{
                    $district = $this->DistrictManager->findAllByRegion($id_region) ;
                }
                if ($id_commune !='*' && $id_commune!='undefined' && $id_commune!='null')
                {   
                    $commune = $this->CommuneManager->findById($id_commune); 
                }
                else
                {                     
                    foreach ($district as $k => $v)
                    {
                        $comm = $this->CommuneManager->findAllByDistrict($v->id);
                        foreach ($comm as $ke => $val)
                        {
                            $tm['id']=$val->id;
                            $tm['code']=$val->code;
                            $tm['nom']=$val->nom;
                            $tm['district_id']=$val->district_id;
                            array_push($commune, $tm);
                            
                        }
                        
                    } 
                 }  
             
            }  
             
           
            $commun = json_decode(json_encode($commune), FALSE);
            $indice = 0;
            foreach ($intervention as $keyintervention => $valueintervention)
            {
                foreach ($district as $keydistrict => $valuedistrict)
                {
                   //$data[$keydistrict]['azez'] = $valuedistrict->id;
                    foreach ($commun as $keycommune => $valuecommune)
                    {
                       $tmp = $this->Systeme_protection_socialManager->findNbr_cumule_beneficiaire($this->generer_requete_filtre($id_region,$valuedistrict->id,$valuecommune->id,$valueintervention->id));
                        if($tmp)
                        {
                            foreach ($tmp as $key => $value)
                            {
                                $data[$indice]['intervention'] = $valueintervention->intitule;
                                $data[$indice]['nbr_beneficiaire'] = $value->nombre_individu + $value->nombre_menage;

                                $data[$indice]['commune'] = $valuecommune->nom;
                                $data[$indice]['district'] = $valuedistrict->nom;
                                $data[$indice]['region'] = $value->nom_region;
                                $indice++;
                            }
                        }

                    }
                }
            }
               

        } 

        //CODE HARIZO
        if ($menu == 'req41_theme2') 
        {
            $data = $this->Systeme_protection_socialManager->beneficiare_sortie_programme() ;
        }

        if ($menu == 'req40_theme2') 
        {
            $data = $this->Systeme_protection_socialManager->nombre_beneficiaire_handicap() ;
        }

        if ($menu == 'req42_theme2') 
        {
            $data = $this->Systeme_protection_socialManager->Moyenne_transfert($id_type_transfert, $date_debut, $date_fin) ;
        }

        if ($menu == 'req43_theme2') 
        {
            $data = $this->Systeme_protection_socialManager->total_transfert($id_type_transfert, $date_debut, $date_fin) ;
        }

        if ($menu == 'req10_theme2') 
        {
            $data = $this->Systeme_protection_socialManager->decaissement_par_programme() ;
        }
        if ($menu == 'req11_theme2') 
        {
            $data = $this->Systeme_protection_socialManager->decaissement_par_tutelle() ;
        }
        if ($menu == 'req12_theme2') 
        {
            $data = $this->Systeme_protection_socialManager->decaissement_par_agence_execution() ;
        }
        if ($menu == 'req37_theme2') 
        {
            $data = $this->Systeme_protection_socialManager->montant_budget_non_consommee_par_programme() ;
        }
        if ($menu == 'req36_theme2') 
        {
            $data = $this->Systeme_protection_socialManager->taux_de_decaissement_par_programme() ;
        }
        //FIN CODE HARIZO

        

        
        //Bruce
        if ($menu=='req7theme2_budgetinit_budgetmodif_situation')
        {
            $tmp = $this->Systeme_protection_socialManager->req7theme2_budgetinit_budgetmodif_situation();
            if($tmp)
            {
                $data=$tmp;
            }else $data = array();
        }

        if ($menu=='req8theme2_budgetinit_budgetmodif_situation_source')
        {
            $tmp = $this->Systeme_protection_socialManager->req8theme2_budgetinit_budgetmodif_situation_source();
            if($tmp)
            {
                $data=$tmp;
            }else $data = array();
        }

        if ($menu=='req9theme2_budgetinit_budgetmodif_situation_tutelle')
        {
            $tmp = $this->Systeme_protection_socialManager->req9theme2_budgetinit_budgetmodif_situation_tutelle();
            if($tmp)
            {
                $data=$tmp;
            }else $data = array();
        }

        if ($menu=='req31theme2_interven_nbrinter_program_beneparan_beneprevu_region')
        {
            $tmp = $this->Systeme_protection_socialManager->req31theme2_interven_nbrinter_program_beneparan_beneprevu_region($this->generer_requete_sql($id_region,'*','*',$id_intervention));
            if($tmp)
            {
                $data=$tmp;
            }else $data = array();

        }

        if ($menu=='req34theme2_program_interven_nbrbene_nbrinter_tauxinter_region')
        {
            $tmp = $this->Systeme_protection_socialManager->req34theme2_program_interven_nbrbene_nbrinter_tauxinter_region($this->generer_requete_sql($id_region,'*','*',$id_intervention));
            if($tmp)
            {
                $data=$tmp;
            }else $data = array();

        }
        //Bruce


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
    //misy amboarina le intervention
    public function generer_requete_filtre($id_region,$id_district,$id_commune,$id_intervention)
    {
        $requete = " region.id='".$id_region."'";
       /* if ($date_debut!=$date_debut) 
        {
            $requete = $requete."date_naissance BETWEEN '".$date_debut."' AND '".$date_fin."' " ;
        }else{
            $requete = $requete."date_naissance ='".$date_debut."'";
        }*/
        if (($id_district!='*')&&($id_district!='undefined')) 
        {
            $requete = $requete." AND district.id='".$id_district."'" ;
        }
        if (($id_commune!='*')&&($id_commune!='undefined')) 
        {
            $requete = $requete." AND commune.id='".$id_commune."'" ;
        }
        if (($id_intervention!='*')&&($id_intervention!='undefined')) 
        {
            $requete = $requete." AND intervention.id='".$id_intervention."'" ;
        }

        return $requete;
    }
    
    public function generer_requete_sql($id_region,$id_district,$id_commune,$id_intervention)
    {
        $requete = " reg.id='".$id_region."'";
        if (($id_intervention!='*')&&($id_intervention!='undefined')) 
        {
            $requete = $requete." AND interven.id='".$id_intervention."'" ;
        }

        return $requete;
    }
}
/* End of file controllername.php */
/* Location: ./application/controllers/controllername.php */