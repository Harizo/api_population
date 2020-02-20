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

        
        
        if ($menu =='req38_theme2')
        {
            $data = $this->Systeme_protection_socialManager->repartition_par_age_sexe_beneficiaire();
            
            
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
            if ($menu == 'req18_theme2') 
            {
                $data = $this->Systeme_protection_socialManager->proportion_des_intervention_par_type_de_cible() ;
            }

            if ($menu=='req14_theme2')
            {
                $tmp = $this->Systeme_protection_socialManager->req14theme2_interven_nbrinter_budgetinit_peffectif_pcout_region_district();
                if($tmp)
                {
                    $data=$tmp;
                }
                else 
                    $data = array();

            }

            if($menu=='req20_theme2')
            {
                $data = $this->Systeme_protection_socialManager->proportion_des_intervention_avec_critere_sexe() ;
            }

            if ($menu == 'req19_theme2') //Age par rapport à la date de suivi de l'intervention
            {
                $data = $this->Systeme_protection_socialManager->proportion_des_intervention_avec_critere_age() ;
            }

            if ($menu == 'req32_theme2') 
            {
                $data = $this->Systeme_protection_socialManager->nbr_nouveau_beneficiaire($date_debut, $date_fin) ;
            }


            if ($menu == 'req_multiple_21_to_30_theme2') 
            {
                $data = $this->Systeme_protection_socialManager->req_multiple_21_to_30() ;
            }


            if ($menu == 'req6_theme2') 
            {
                $data = $this->Systeme_protection_socialManager->req6_theme2() ;
            }

            


        //FIN CODE HARIZO


        //CODE CORRIGER Par Harizo
        if ($menu =='req1_theme1') //Age par rapport à la date du jour 
        {
            $data = $this->Environment_demo_socioManager->effectif_par_age_sexe_population($enfant,$scolaire_min,$scolaire_max,$travail_min,$travail_max,$agee);
           
        }
        if ($menu == 'req34_theme2') //Age par rapport à la date d'inscription
        {
            $data = $this->Systeme_protection_socialManager->taux_atteinte_resultat() ;
        }
        if ($menu =='req3_theme1')
        {            
            $data = $this->Environment_demo_socioManager->menage_ayant_efant($enfant,$scolaire_min,$scolaire_max);      
           
        }
        if ($menu=='req7_theme2')//situtation(en cours ou new) par rapport à la debut et fin du programme
        {
            $tmp = $this->Systeme_protection_socialManager->repartition_financement_programme();
            if($tmp)
            {
                $data=$tmp;
            }else $data = array();
        }

        if ($menu=='req8_theme2')//situtation(en cours ou new) par rapport à la debut et fin du programme
        {
            $tmp = $this->Systeme_protection_socialManager->repartition_financement_source_financement();
            if($tmp)
            {
                $data=$tmp;
            }else $data = array();
        }

        if ($menu=='req9_theme2')//situtation(en cours ou new) par rapport à la debut et fin du programme
        {
            $tmp = $this->Systeme_protection_socialManager->repartition_financement_tutelle();
            if($tmp)
            {
                $data=$tmp;
            }else $data = array();
        }
        //fin CODE CORRIGER Par Harizo

        

        
        //Bruce
        

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

        

        /*if ($menu=='req19theme2_interven_pourcenenfan_pourcensco_pourcentra_pourcenage_pcout')
        {
            $tmp = $this->Systeme_protection_socialManager->req19theme2_interven_pourcenenfan_pourcensco_pourcentra_pourcenage_pcout($enfant,$scolaire_min,$scolaire_max,$travail_min,$travail_max,$agee);
            if($tmp)
            {
                $data=$tmp;
            }else $data = array();

        }*/

        if ($menu=='req20theme2_interven_pourcenfille_pourcenhomme_pcout')
        {
            $tmp = $this->Systeme_protection_socialManager->req20theme2_interven_pourcenfille_pourcenhomme_pcout();
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