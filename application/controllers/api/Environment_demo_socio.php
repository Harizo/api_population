<?php

defined('BASEPATH') OR exit('No direct script access allowed');
//harizo
// afaka fafana refa ts ilaina
require APPPATH . '/libraries/REST_Controller.php';

class Environment_demo_socio extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('environment_demo_socio_model', 'Environment_demo_socioManager');
        $this->load->model('region_model', 'RegionManager');
        $this->load->model('district_model', 'DistrictManager');
        $this->load->model('commune_model', 'CommuneManager');
    }

    public function index_get() {
        
        $menu = $this->get('menu');
        $id_region= $this->get('id_region'); 
        $id_district= $this->get('id_district');
        $id_commune= $this->get('id_commune');      

       /* if ($menu =='effectif_sexe_age')
        {   $menage = array();
            $individu = array();
            $data = array();
            $menage = $this->Environment_demo_socioManager->findMenageEffectif_sexe_age();
            $individu = $this->Environment_demo_socioManager->findIndividuEffectif_sexe_age();
            $indice = 0;
            
            $tmp = array();
            $to= array_merge($individu,$menage);
            if ($individu)
            {
                foreach ($individu as $keyind => $valueind)
                {
                    $nbrmenage = 0;
                    $menagetrue= false;
                    if ($menage)
                    {
                        foreach ($menage as $key => $value)
                        {   
                            if ($valueind->annee == $value->annee 
                                && $valueind->sexe == $value->sexe
                                && $valueind->commune_id == $value->commune_id
                            )
                            {
                                $nbrmenage =$value->nombre;
                                $menagetrue= true;
                                $menage = array_diff_key($menage, [$key => $value->annee]);
                                
                            }
                            
                        }
                    }        
                    
                    $tmp[$indice]['annee'] = $valueind->annee;
                    $tmp[$indice]['nombre'] = $valueind->nombre+$nbrmenage;
                    $tmp[$indice]['sexe'] = $valueind->sexe;
                    $tmp[$indice]['commune_nom'] = $valueind->commune_nom;
                    $tmp[$indice]['men'] = $menage;
                    $tmp[$indice]['ind'] = $individu;   
                    $indice++; 
                }
                $data=array_merge($tmp,$menage);
                }
            
            
        }*/
        if ($menu =='effectif_population_petitenfan_agesco_agetrava_agee_region_dist_comm')
        {
            $individu = array();
            $district = array();
            $commune = array();
            $data = array();
            $now = date('Y-m-d');
            $enfant = date('Y-m-d', strtotime($now. ' -7 years +1 days'));
            
            $scolaire_max = date('Y-m-d', strtotime($now. ' -18 years +1 days'));
            $scolaire_min = date('Y-m-d', strtotime($now. ' -7 years')); 

            $travail_max = date('Y-m-d', strtotime($now. ' -60 years +1 days'));
            $travail_min = date('Y-m-d', strtotime($now. ' -18 years'));
            $agee = date('Y-m-d', strtotime($now. ' -60 years')); 

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
                {   //$tm=array();
                    
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
            
            $object = json_decode(json_encode($commune), FALSE);
            $indice = 0;
               foreach ($district as $keydistrict => $valuedistrict)
                {
                   //$data[$keydistrict]['azez'] = $valuedistrict->id;
                    foreach ($object as $keycommune => $valuecommune)
                    {
                       $tmp = $this->Environment_demo_socioManager->findEffectif_sexe_age($this->generer_requete_filtre($id_region,$valuedistrict->id,$valuecommune->id),$enfant,$scolaire_min,$scolaire_max,$travail_min,$travail_max,$agee);
                        if($tmp)
                        {
                            foreach ($tmp as $key => $value)
                            {

                                $data[$indice]['nbr_enfant_fille'] = $value->nombre_enfant_individu_f + $value->nombre_enfant_menage_f;
                                $data[$indice]['nbr_enfant_homme'] = $value->nombre_enfant_individu_h + $value->nombre_enfant_menage_h;

                                $data[$indice]['nbr_agescolaire_fille'] = $value->nombre_agescolaire_individu_f + $value->nombre_agescolaire_menage_f;
                                $data[$indice]['nbr_agescolaire_homme'] = $value->nombre_agescolaire_individu_h + $value->nombre_agescolaire_menage_h;

                                $data[$indice]['nbr_agetravaille_fille'] = $value->nombre_agetravaille_individu_f + $value->nombre_agetravaille_menage_f;
                                $data[$indice]['nbr_agetravaille_homme'] = $value->nombre_agetravaille_individu_h + $value->nombre_agetravaille_menage_h;

                                $data[$indice]['nbr_agee_fille'] = $value->nombre_agee_individu_f + $value->nombre_agee_menage_f;
                                $data[$indice]['nbr_agee_homme'] = $value->nombre_agee_individu_h + $value->nombre_agee_menage_h;

                                $data[$indice]['commune'] = $valuecommune->nom;
                                $data[$indice]['district'] = $valuedistrict->nom;
                                $data[$indice]['region'] = $value->nom_region;
                                $indice++;
                            }
                        }

                    }
                } 
        }
        elseif ($menu =='effectif_menage_enfant_menagenfan_menagescolai_region_dist_comm')
        {
            $individu = array();
            $district = array();
            $commune = array();
            $data = array();
            $now = date('Y-m-d');
            $enfant = date('Y-m-d', strtotime($now. ' -7 years +1 days'));
            
            $scolaire_max = date('Y-m-d', strtotime($now. ' -18 years +1 days'));
            $scolaire_min = date('Y-m-d', strtotime($now. ' -7 years'));  

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
                {   //$tm=array();
                    
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
            
            $object = json_decode(json_encode($commune), FALSE);
            $indice = 0;
               foreach ($district as $keydistrict => $valuedistrict)
                {
                   //$data[$keydistrict]['azez'] = $valuedistrict->id;
                    foreach ($object as $keycommune => $valuecommune)
                    {
                       $tmp = $this->Environment_demo_socioManager->findEffectif_menage_enfant($this->generer_requete_filtre($id_region,$valuedistrict->id,$valuecommune->id),$enfant,$scolaire_min,$scolaire_max);
                        if($tmp)
                        {
                            foreach ($tmp as $key => $value)
                            {

                                $data[$indice]['nbr_menage_enfant'] = $value->nombre_menage_enfant;
                                $data[$indice]['nbr_menage_agescollaire'] = $value->nombre_menage_agescollaire;

                                $data[$indice]['commune'] = $valuecommune->nom;
                                $data[$indice]['district'] = $valuedistrict->nom;
                                $data[$indice]['region'] = $value->nom_region;
                                $indice++;
                            }
                        }

                    }
                }
        } 
        else {
            /*if ($id) {
                $data = array();
                $region = $this->RegionManager->findById($id);
                $data['id'] = $region->id;
                $data['code'] = $region->code;
                $data['nom'] = $region->nom;
                
            } else {
                $region = $this->RegionManager->findAll();
                if ($region) {
                    foreach ($region as $key => $value) {
                        
                        $data[$key]['id'] = $value->id;
                        $data[$key]['code'] = $value->code;
                        $data[$key]['nom'] = $value->nom;
                        
                    };
                } else
                    $data = array();
            }*/
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
    public function generer_requete_filtre($id_region,$id_district,$id_commune)
    {
        $requete = "region.id='".$id_region."'";
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

        return $requete;
    }
}
/* End of file controllername.php */
/* Location: ./application/controllers/controllername.php */