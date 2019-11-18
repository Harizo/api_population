<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Systeme_protection_social_model extends CI_Model
{    
   
    public function repartitionBeneficiaireIndividu_sexe_age($requete,$enfant,$scolaire_min,$scolaire_max,$travail_min,$travail_max,$agee)
    {
       $this->db->select("region.id as id_reg,region.nom as nom_region, district.id as id_dist, district.nom as nom_dist, commune.id as id_com, commune.nom as nom_com, intervention.id as id_int, intervention.intitule as intitule_intervention");

       $this->db ->select("((select count(DISTINCT(indi_bene.id_individu)) from individu_beneficiaire as indi_bene inner join individu as indi on indi.id= indi_bene.id_individu inner join intervention as interv on interv.id= indi_bene.id_intervention inner join fokontany as foko on indi.id_fokontany= foko.id inner join commune as com on com.id= foko.id_commune inner join district as dist on com.district_id= dist.id where com.id=commune.id and interv.id=intervention.id and ".$requete." and indi.date_naissance >= '".$enfant."' and indi.sexe = 'H')) as nbr_enfant_homme",false);

       $this->db ->select("((select count(DISTINCT(indi_bene.id_individu)) from individu_beneficiaire as indi_bene inner join individu as indi on indi.id= indi_bene.id_individu inner join intervention as interv on interv.id= indi_bene.id_intervention inner join fokontany as foko on indi.id_fokontany= foko.id inner join commune as com on com.id= foko.id_commune inner join district as dist on com.district_id= dist.id where com.id=commune.id and interv.id=intervention.id and ".$requete." and indi.date_naissance >= '".$enfant."' and indi.sexe = 'F')) as nbr_enfant_fille",false);
        
       $this->db ->select("((select count(DISTINCT(indi_bene.id_individu)) from individu_beneficiaire as indi_bene inner join individu as indi on indi.id= indi_bene.id_individu inner join intervention as interv on interv.id= indi_bene.id_intervention inner join fokontany as foko on indi.id_fokontany= foko.id inner join commune as com on com.id= foko.id_commune inner join district as dist on com.district_id= dist.id where com.id=commune.id and interv.id=intervention.id and ".$requete." and indi.date_naissance BETWEEN '".$scolaire_max."' AND '".$scolaire_min."' and indi.sexe = 'H')) as nbr_agescolaire_homme",false);

       $this->db ->select("((select count(DISTINCT(indi_bene.id_individu)) from individu_beneficiaire as indi_bene inner join individu as indi on indi.id= indi_bene.id_individu inner join intervention as interv on interv.id= indi_bene.id_intervention inner join fokontany as foko on indi.id_fokontany= foko.id inner join commune as com on com.id= foko.id_commune inner join district as dist on com.district_id= dist.id where com.id=commune.id and interv.id=intervention.id and".$requete." and indi.date_naissance BETWEEN '".$scolaire_max."' AND '".$scolaire_min."' and indi.sexe = 'F')) as nbr_agescolaire_fille",false); 

       $this->db ->select("((select count(DISTINCT(indi_bene.id_individu)) from individu_beneficiaire as indi_bene inner join individu as indi on indi.id= indi_bene.id_individu inner join intervention as interv on interv.id= indi_bene.id_intervention inner join fokontany as foko on indi.id_fokontany= foko.id inner join commune as com on com.id= foko.id_commune inner join district as dist on com.district_id= dist.id where com.id=commune.id and interv.id=intervention.id and ".$requete." and indi.date_naissance BETWEEN '".$travail_max."' AND '".$travail_min."' and indi.sexe = 'F')) as nbr_agetravaille_fille",false);

       $this->db ->select("((select count(DISTINCT(indi_bene.id_individu)) from individu_beneficiaire as indi_bene inner join individu as indi on indi.id= indi_bene.id_individu inner join intervention as interv on interv.id= indi_bene.id_intervention inner join fokontany as foko on indi.id_fokontany= foko.id inner join commune as com on com.id= foko.id_commune inner join district as dist on com.district_id= dist.id where com.id=commune.id and interv.id=intervention.id and ".$requete." and indi.date_naissance BETWEEN '".$travail_max."' AND '".$travail_min."' and indi.sexe = 'H')) as nbr_agetravaille_homme",false);

       $this->db ->select("((select count(DISTINCT(indi_bene.id_individu)) from individu_beneficiaire as indi_bene inner join individu as indi on indi.id= indi_bene.id_individu inner join intervention as interv on interv.id= indi_bene.id_intervention inner join fokontany as foko on indi.id_fokontany= foko.id inner join commune as com on com.id= foko.id_commune inner join district as dist on com.district_id= dist.id where com.id=commune.id and interv.id=intervention.id and ".$requete." and indi.date_naissance <= '".$agee."' and indi.sexe = 'F')) as nbr_agee_fille",false);

       $this->db ->select("((select count(DISTINCT(indi_bene.id_individu)) from individu_beneficiaire as indi_bene inner join individu as indi on indi.id= indi_bene.id_individu inner join intervention as interv on interv.id= indi_bene.id_intervention inner join fokontany as foko on indi.id_fokontany= foko.id inner join commune as com on com.id= foko.id_commune inner join district as dist on com.district_id= dist.id where com.id=commune.id and interv.id=intervention.id and ".$requete." and indi.date_naissance <= '".$agee."' and indi.sexe = 'H')) as nbr_agee_homme",false);

       

        $result =  $this->db->from('region,commune,district,fokontany,individu,individu_beneficiaire,intervention')
                    
                    ->where('region.id = district.region_id')
                    ->where('district.id = commune.district_id')
                    ->where('commune.id = fokontany.id_commune')
                    
                    ->where('individu.id_fokontany = fokontany.id')                    
                    ->where('individu.id = individu_beneficiaire.id_individu')
                    ->where('individu_beneficiaire.id_intervention = intervention.id')
                   
                    ->where($requete)
                    ->group_by('id_reg,id_dist,id_com,id_int')
                                       
                    ->get()
                    ->result();                              

        if($result)
        {
            return $result;
        }else{
            return null;
        }                 
    }

    public function findNbr_cumule_beneficiaire($requete)
    {
       $this->db->select("region.id as id_reg,region.nom as nom_region,district.id as id_dist,commune.id as id_com ");
        
       $this->db ->select("((select count(DISTINCT(individu_beneficiaire.id_individu)) from individu_beneficiaire inner join individu on individu.id= individu_beneficiaire.id_individu inner join intervention on intervention.id= individu_beneficiaire.id_intervention inner join fokontany on individu.id_fokontany= fokontany.id inner join commune on commune.id= fokontany.id_commune inner join district on commune.district_id= district.id where  ".$requete.")) as nombre_individu",false);     

       $this->db ->select("((select count(DISTINCT(menage_beneficiaire.id_menage)) from menage_beneficiaire inner join menage on menage.id=menage_beneficiaire.id_menage inner join intervention on intervention.id= menage_beneficiaire.id_intervention inner join fokontany on menage.id_fokontany= fokontany.id inner join commune on commune.id= fokontany.id_commune inner join district on commune.district_id= district.id where  ".$requete.")) as nombre_menage",false);
       

        $result =  $this->db->from('region,commune,district ,individu,menage,fokontany,intervention')
                    
                    ->where('region.id = district.region_id')
                    ->where('district.id = commune.district_id')
                    ->where('commune.id = fokontany.id_commune')
                    ->where('individu.id_fokontany = fokontany.id')
                    ->where('menage.id_fokontany = fokontany.id')
                    ->where($requete)
                    ->group_by('id_reg,id_dist,id_com')
                                       
                    ->get()
                    ->result();                              

        if($result)
        {
            return $result;
        }else{
            return null;
        }                 
    }

//*****Bruce***********
    //requete Répartition financement par programme
    public function req7theme2_budgetinit_budgetmodif_situation()
    {        
       $result = $this->db ->select('sum(financement_programme.budget_initial) as budget_initial, sum(financement_programme.budget_modifie) as budget_modifie,programme.situation_intervention as situation')
                    ->from('financement_programme')
                    ->join('programme','programme.id= financement_programme.id_programme')
                    ->group_by('programme.situation_intervention')                                      
                    ->get()
                    ->result();                              

        if($result)
        {
            return $result;
        }else{
            return null;
        } 

    }

    //requete Répartition financement par source
    public function req8theme2_budgetinit_budgetmodif_situation_source()
    {        
       $result = $this->db ->select('sum(financement_programme.budget_initial) as budget_initial, sum(financement_programme.budget_modifie) as budget_modifie,programme.situation_intervention as situation,source_financement.nom as nom_source')
                    ->from('financement_programme')
                    ->join('programme','programme.id= financement_programme.id_programme')
                    ->join('source_financement','source_financement.id= financement_programme.id_source_financement')
                    ->group_by('programme.situation_intervention,source_financement.id')                                      
                    ->get()
                    ->result();                              

        if($result)
        {
            return $result;
        }else{
            return null;
        } 

    }

    //requete Répartition financement par tutele
    public function req9theme2_budgetinit_budgetmodif_situation_tutelle()
    {        
       $result = $this->db ->select('sum(financement_programme.budget_initial) as budget_initial, sum(financement_programme.budget_modifie) as budget_modifie,programme.situation_intervention as situation, tutelle.nom as nom_tutelle')
                    ->from('financement_programme')
                    ->join('programme','programme.id= financement_programme.id_programme')
                    ->join('tutelle','tutelle.id= programme.id_tutelle')
                    ->group_by('programme.situation_intervention, tutelle.id')                                      
                    ->get()
                    ->result();                              

        if($result)
        {
            return $result;
        }else{
            return null;
        } 

    }

    //requete Nombre des bénéficiaires prévus
   public function req31theme2_interven_nbrinter_program_beneparan_beneprevu_region($requete)
    {
      $result = $this->db->query( "select detail.id_region,detail.nom_region,detail.intitule_interven as intitule_intervention,detail.intitule_prog as intitule_programme,
        sum(detail.intervention_prevu) as total_intervention_prevu,sum(detail.programme_an_prevu) as nbr_an_programme,sum(detail.programme_prevu) as nbr_total_prevu
FROM 
        (select reg.id as id_region,reg.nom as nom_region,
        interven.intitule as intitule_interven,prog.id as id_program,prog.intitule as intitule_prog,
        sum(zone_inter.menage_beneficiaire_prevu + zone_inter.individu_beneficiaire_prevu) as intervention_prevu,0 as programme_prevu, 0 as programme_an_prevu
        from zone_intervention as zone_inter
        join fokontany as foko on foko.id=zone_inter.id_fokontany
        join commune as com on com.id=foko.id_commune
        join district as dist on dist.id=com.district_id
        join region as reg on reg.id=dist.region_id
        join intervention as interven on interven.id=zone_inter.id_intervention
        join programme as prog on prog.id=interven.id_programme
        where  ".$requete."
        group by  reg.id,reg.nom,interven.id,interven.intitule,prog.id,prog.intitule
        
        UNION

        select reg.id as id_region,reg.nom as nom_region,
        interven.intitule as intitule_interven,prog.id as id_program,prog.intitule as intitule_prog,0 as intervention_prevu,
        sum(zone_inter_pro.menage_beneficiaire_prevu + zone_inter_pro.individu_beneficiaire_prevu) as programme_prevu,
        CASE WHEN 
                DATE_PART('year', prog.date_fin::date)-DATE_PART('year', prog.date_debut::date) =0 THEN sum(zone_inter_pro.menage_beneficiaire_prevu + zone_inter_pro.individu_beneficiaire_prevu)
        ELSE 
              (sum(zone_inter_pro.menage_beneficiaire_prevu + zone_inter_pro.individu_beneficiaire_prevu)/(DATE_PART('year', prog.date_fin::date)-DATE_PART('year', prog.date_debut::date)))
        END as programme_an_prevu
        from zone_intervention_programme as zone_inter_pro
        join region as reg on reg.id=zone_inter_pro.id_region
        join programme as prog on prog.id=zone_inter_pro.id_programme
        join intervention as interven on interven.id_programme=prog.id
        where  ".$requete."
        group by  reg.id,reg.nom,interven.id,interven.intitule,prog.id,prog.intitule) as detail

group by detail.id_region,detail.nom_region,detail.intitule_interven,detail.intitule_prog
order by detail.id_region,detail.nom_region,detail.intitule_interven
        ")
      ->result();
      if($result)
        {
            return $result;
        }else{
            return null;
        }

    }

     //requete Taux d’atteinte des résultats
   public function req34theme2_program_interven_nbrbene_nbrinter_tauxinter_region($requete)
    {  
       $result = $this->db->query( "select detail.id_region,detail.nom_region,detail.intitule_interven as intitule_intervention,detail.intitule_prog as intitule_programme,
        sum(detail.nbr_mena+detail.nbr_ind) as total_bene,
        CASE WHEN 
                sum(detail.intervention_prevu) =0 THEN 100
        ELSE 
              ((sum(detail.nbr_mena)*100)/sum(detail.intervention_prevu))
        END as taux_intervention,
        CASE WHEN 
                sum(detail.intervention_prevu) =0 THEN 100
        ELSE 
              ((sum(detail.nbr_mena)*100)/sum(detail.intervention_prevu))
        END as taux_programme,
        sum(detail.intervention_prevu) as total_intervention_prevu
FROM 
        (select reg.id as id_region,reg.nom as nom_region,
        interven.intitule as intitule_interven,prog.id as id_program,prog.intitule as intitule_prog,
        count(mena_bene.id) as nbr_mena, 0 as nbr_ind,0 as intervention_prevu
        from menage_beneficiaire as mena_bene
        join menage as men on men.id=mena_bene.id_menage
        join fokontany as foko on foko.id=men.id_fokontany
        join commune as com on com.id=foko.id_commune
        join district as dist on dist.id=com.district_id
        join region as reg on reg.id=dist.region_id
        join intervention as interven on interven.id=mena_bene.id_intervention
        join programme as prog on prog.id=interven.id_programme
        where  ".$requete."
        group by  reg.id,reg.nom,interven.id,interven.intitule,prog.id,prog.intitule

        UNION

        select reg.id as id_region,reg.nom as nom_region,
        interven.intitule as intitule_interven,prog.id as id_program,prog.intitule as intitule_prog,0 as nbr_mena,
        count(ind_bene.id) as nbr_ind,0 as intervention_prevu
        from individu_beneficiaire as ind_bene
        join individu as ind on ind.id=ind_bene.id_individu
        join fokontany as foko on foko.id=ind.id_fokontany
        join commune as com on com.id=foko.id_commune
        join district as dist on dist.id=com.district_id
        join region as reg on reg.id=dist.region_id
        join intervention as interven on interven.id=ind_bene.id_intervention
        join programme as prog on prog.id=interven.id_programme
        where  ".$requete."
        group by  reg.id,reg.nom,interven.id,interven.intitule,prog.id,prog.intitule

        UNION

        select reg.id as id_region,reg.nom as nom_region,
        interven.intitule as intitule_interven,prog.id as id_program,prog.intitule as intitule_prog, 0 as nbr_mena,0 as nbr_ind,
        sum(zone_inter.menage_beneficiaire_prevu + zone_inter.individu_beneficiaire_prevu) as intervention_prevu
        from zone_intervention as zone_inter
        join fokontany as foko on foko.id=zone_inter.id_fokontany
        join commune as com on com.id=foko.id_commune
        join district as dist on dist.id=com.district_id
        join region as reg on reg.id=dist.region_id
        join intervention as interven on interven.id=zone_inter.id_intervention
        join programme as prog on prog.id=interven.id_programme
        where  ".$requete."
        group by  reg.id,reg.nom,interven.id,interven.intitule,prog.id,prog.intitule) as detail
        
group by detail.id_region,detail.nom_region,detail.intitule_interven,detail.intitule_prog
order by detail.id_region,detail.nom_region,detail.intitule_interven
        ")
      ->result();
      if($result)
        {
            return $result;
        }else{
            return null;
        }

    }
//*********Bruce**********

    public function findEffectif_beneficiaire_handicape($requete)
    {
       $result =  $this->db->select('count(DISTINCT(individu_beneficiaire.id_individu)) as nombre_individu')
                        ->from($this->table)
                        ->where("id", $id)
                        ->order_by('id', 'asc')
                        ->get()
                        ->result();
        if($result) {
            return $result;
        }else{
            return null;
        }                 
    }

    /* public function repartitionBeneficiaire_sexe_age($requete,$enfant,$scolaire_min,$scolaire_max,$travail_min,$travail_max,$agee)
    {
      $this->db->select("intervention.id as id_int, intervention.intitule as intitule_intervention");

       $this->db ->select("(select count((individu_beneficiaire.id)) from individu_beneficiaire inner join  individu as ind on ind.id= individu_beneficiaire.id_individu inner join fokontany  on ind.id_fokontany= fokontany.id inner join commune  on commune.id= fokontany.id_commune inner join district  on commune.district_id= district.id inner join region  on region.id = district.region_id  where individu_beneficiaire.id_intervention = intervention.id  and ".$requete." and ind.date_naissance >= '".$enfant."' and ind.sexe = 'H') as nbr_enfant_homme",false);

       $this->db ->select("(select count((individu_beneficiaire.id)) from individu_beneficiaire inner join  individu as ind on ind.id= individu_beneficiaire.id_individu inner join fokontany  on ind.id_fokontany= fokontany.id inner join commune  on commune.id= fokontany.id_commune inner join district  on commune.district_id= district.id inner join region  on region.id = district.region_id  where individu_beneficiaire.id_intervention = intervention.id  and ".$requete." and ind.date_naissance >= '".$enfant."' and ind.sexe = 'F') as nbr_enfant_fille",false);

       $this->db ->select("(select count((individu_beneficiaire.id)) from individu_beneficiaire inner join  individu as ind on ind.id= individu_beneficiaire.id_individu inner join fokontany  on ind.id_fokontany= fokontany.id inner join commune  on commune.id= fokontany.id_commune inner join district  on commune.district_id= district.id inner join region  on region.id = district.region_id  where individu_beneficiaire.id_intervention = intervention.id  and ".$requete." and ind.date_naissance BETWEEN '".$scolaire_max."' AND '".$scolaire_min."' and ind.sexe = 'F') as nbr_agescolaire_fille",false);

        $this->db ->select("(select count((individu_beneficiaire.id)) from individu_beneficiaire inner join  individu as ind on ind.id= individu_beneficiaire.id_individu inner join fokontany  on ind.id_fokontany= fokontany.id inner join commune  on commune.id= fokontany.id_commune inner join district  on commune.district_id= district.id inner join region  on region.id = district.region_id  where individu_beneficiaire.id_intervention = intervention.id  and ".$requete." and ind.date_naissance BETWEEN '".$scolaire_max."' AND '".$scolaire_min."' and ind.sexe = 'H') as nbr_agescolaire_homme",false); 

       $this->db ->select("((select count((individu_beneficiaire.id)) from individu_beneficiaire inner join  individu as ind on ind.id= individu_beneficiaire.id_individu inner join fokontany  on ind.id_fokontany= fokontany.id inner join commune  on commune.id= fokontany.id_commune inner join district  on commune.district_id= district.id inner join region  on region.id = district.region_id  where individu_beneficiaire.id_intervention = intervention.id  and ".$requete." and ind.date_naissance BETWEEN '".$travail_max."' AND '".$travail_min."' and ind.sexe = 'F')+(select count((menage_beneficiaire.id)) from menage_beneficiaire inner join  menage as mena on mena.id= menage_beneficiaire.id_menage  inner join fokontany  on mena.id_fokontany= fokontany.id inner join commune  on commune.id= fokontany.id_commune inner join district  on commune.district_id= district.id inner join region  on region.id = district.region_id  where menage_beneficiaire.id_intervention = intervention.id  and ".$requete." and mena.date_naissance BETWEEN '".$travail_max."' AND '".$travail_min."' and mena.sexe = 'F')) as nbr_agetravaille_fille",false);

       $this->db ->select("((select count((individu_beneficiaire.id)) from individu_beneficiaire inner join  individu as ind on ind.id= individu_beneficiaire.id_individu inner join fokontany  on ind.id_fokontany= fokontany.id inner join commune  on commune.id= fokontany.id_commune inner join district  on commune.district_id= district.id inner join region  on region.id = district.region_id  where individu_beneficiaire.id_intervention = intervention.id  and ".$requete." and ind.date_naissance BETWEEN '".$travail_max."' AND '".$travail_min."' and ind.sexe = 'H')+(select count((menage_beneficiaire.id)) from menage_beneficiaire inner join  menage as mena on mena.id= menage_beneficiaire.id_menage  inner join fokontany  on mena.id_fokontany= fokontany.id inner join commune  on commune.id= fokontany.id_commune inner join district  on commune.district_id= district.id inner join region  on region.id = district.region_id  where menage_beneficiaire.id_intervention = intervention.id  and ".$requete." and mena.date_naissance BETWEEN '".$travail_max."' AND '".$travail_min."' and mena.sexe = 'H')) as nbr_agetravaille_homme",false);

       $this->db ->select("((select count((individu_beneficiaire.id)) from individu_beneficiaire inner join  individu as ind on ind.id= individu_beneficiaire.id_individu inner join fokontany  on ind.id_fokontany= fokontany.id inner join commune  on commune.id= fokontany.id_commune inner join district  on commune.district_id= district.id inner join region  on region.id = district.region_id  where individu_beneficiaire.id_intervention = intervention.id  and ".$requete." and ind.date_naissance <= '".$agee."' and ind.sexe = 'F')+(select count((menage_beneficiaire.id)) from menage_beneficiaire inner join  menage as mena on mena.id= menage_beneficiaire.id_menage  inner join fokontany  on mena.id_fokontany= fokontany.id inner join commune  on commune.id= fokontany.id_commune inner join district  on commune.district_id= district.id inner join region  on region.id = district.region_id  where menage_beneficiaire.id_intervention = intervention.id  and ".$requete." and mena.date_naissance <= '".$agee."' and mena.sexe = 'F')) as nbr_agee_fille",false);

       $this->db ->select("((select count((individu_beneficiaire.id)) from individu_beneficiaire inner join  individu as ind on ind.id= individu_beneficiaire.id_individu inner join fokontany  on ind.id_fokontany= fokontany.id inner join commune  on commune.id= fokontany.id_commune inner join district  on commune.district_id= district.id inner join region  on region.id = district.region_id  where individu_beneficiaire.id_intervention = intervention.id  and ".$requete." and ind.date_naissance <= '".$agee."' and ind.sexe = 'H')+(select count((menage_beneficiaire.id)) from menage_beneficiaire inner join  menage as mena on mena.id= menage_beneficiaire.id_menage  inner join fokontany  on mena.id_fokontany= fokontany.id inner join commune  on commune.id= fokontany.id_commune inner join district  on commune.district_id= district.id inner join region  on region.id = district.region_id  where menage_beneficiaire.id_intervention = intervention.id  and ".$requete." and mena.date_naissance <= '".$agee."' and mena.sexe = 'H')) as nbr_agee_homme",false);
       
       //HARIZO                               

        $result =  $this->db->from('intervention')
                    ->get()
                    ->result();  

        //HARIZO                            

        if($result)
        {
            return $result;
        }else{
            return null;
        }                 
    }*/
}
