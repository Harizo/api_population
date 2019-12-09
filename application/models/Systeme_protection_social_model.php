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
      $result = $this->db->query( "
        select 
                detail.id_region,detail.nom_region,
                detail.intitule_interven as intitule_intervention,
                detail.intitule_prog as intitule_programme,
                sum(detail.intervention_prevu) as total_intervention_prevu,
                sum(detail.programme_an_prevu) as nbr_an_programme,
                sum(detail.programme_prevu) as nbr_total_prevu
        FROM 
              (select 
                      reg.id as id_region,
                      reg.nom as nom_region,
                      interven.intitule as intitule_interven,
                      prog.id as id_program,prog.intitule as intitule_prog,
                      sum(zone_inter.menage_beneficiaire_prevu + zone_inter.individu_beneficiaire_prevu) as intervention_prevu,
                      0 as programme_prevu,
                      0 as programme_an_prevu
                  from 
                      zone_intervention as zone_inter

                      join fokontany as foko on foko.id=zone_inter.id_fokontany
                      join commune as com on com.id=foko.id_commune
                      join district as dist on dist.id=com.district_id
                      join region as reg on reg.id=dist.region_id
                      join intervention as interven on interven.id=zone_inter.id_intervention
                      join programme as prog on prog.id=interven.id_programme
                      
                      where  ".$requete."
                      
                      group by  reg.id,reg.nom,interven.id,interven.intitule,prog.id,prog.intitule
        
              UNION

              select 
                      reg.id as id_region,
                      reg.nom as nom_region,
                      interven.intitule as intitule_interven,prog.id as id_program,
                      prog.intitule as intitule_prog,0 as intervention_prevu,
                      sum(zone_inter_pro.menage_beneficiaire_prevu + zone_inter_pro.individu_beneficiaire_prevu) as programme_prevu,
                      CASE WHEN 
                                DATE_PART('year', prog.date_fin::date)-DATE_PART('year', prog.date_debut::date) =0 THEN sum(zone_inter_pro.menage_beneficiaire_prevu + zone_inter_pro.individu_beneficiaire_prevu)
                            ELSE 
                                (sum(zone_inter_pro.menage_beneficiaire_prevu + zone_inter_pro.individu_beneficiaire_prevu)/(DATE_PART('year', prog.date_fin::date)-DATE_PART('year', prog.date_debut::date)))
                      END as programme_an_prevu
                  from 
                      zone_intervention_programme as zone_inter_pro
                      
                      join region as reg on reg.id=zone_inter_pro.id_region
                      join programme as prog on prog.id=zone_inter_pro.id_programme
                      join intervention as interven on interven.id_programme=prog.id
              
              where  ".$requete."
              
              group by  reg.id,reg.nom,interven.id,interven.intitule,prog.id,prog.intitule
              ) as detail

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
       $result = $this->db->query( "
        select 
              detail.id_region,
              detail.nom_region,
              detail.intitule_interven as intitule_intervention,
              detail.intitule_prog as intitule_programme,
              sum(detail.nbr_mena+detail.nbr_ind) as total_bene,
              CASE  WHEN 
                      sum(detail.intervention_prevu) =0 THEN 100
                    ELSE 
                    ((sum(detail.nbr_mena)*100)/sum(detail.intervention_prevu))
              END as taux_intervention,
              CASE  WHEN 
                      sum(detail.intervention_prevu) =0 THEN 100
                    ELSE 
                      ((sum(detail.nbr_mena)*100)/sum(detail.intervention_prevu))
              END as taux_programme,
              sum(detail.intervention_prevu) as total_intervention_prevu
        FROM 
              (select 
                      reg.id as id_region,
                      reg.nom as nom_region,
                      interven.intitule as intitule_interven,
                      prog.id as id_program,
                      prog.intitule as intitule_prog,
                      count(mena_bene.id) as nbr_mena, 
                      0 as nbr_ind,
                      0 as intervention_prevu
                  from 
                      menage_beneficiaire as mena_bene
                      
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

              select 
                      reg.id as id_region,
                      reg.nom as nom_region,
                      interven.intitule as intitule_interven,
                      prog.id as id_program,
                      prog.intitule as intitule_prog,
                      0 as nbr_mena,
                      count(ind_bene.id) as nbr_ind,
                      0 as intervention_prevu
                  from 
                      individu_beneficiaire as ind_bene
                      
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

              select 
                      reg.id as id_region,
                      reg.nom as nom_region,
                      interven.intitule as intitule_interven,prog.id as id_program,
                      prog.intitule as intitule_prog, 0 as nbr_mena,
                      0 as nbr_ind,
                      sum(zone_inter.menage_beneficiaire_prevu + zone_inter.individu_beneficiaire_prevu) as intervention_prevu
                  from 
                      zone_intervention as zone_inter
                      
                      join fokontany as foko on foko.id=zone_inter.id_fokontany
                      join commune as com on com.id=foko.id_commune
                      join district as dist on dist.id=com.district_id
                      join region as reg on reg.id=dist.region_id
                      join intervention as interven on interven.id=zone_inter.id_intervention
                      join programme as prog on prog.id=interven.id_programme
                  
                  where  ".$requete."
                  
                  group by  reg.id,reg.nom,interven.id,interven.intitule,prog.id,prog.intitule
              ) as detail
        
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

  //requete Répartition géographique des interventions
   public function req14theme2_interven_nbrinter_budgetinit_peffectif_pcout_region_district($requete)
    {  
       $result = $this->db->query( "
        select 
              detail.id_region,
              detail.nom_region,
              detail.id_district,
              detail.nom_dist,
              detail.intitule_interven as intitule_intervention,
              sum(detail.nbr_mena+detail.nbr_ind) as total_bene,
              sum(detail.intervention_prevu) as total_intervention_prevu,
              sum(detail.budget_init) as budget_initial,
              sum(detail.budget_modif) as budget_modif,
              sum(detail.v_quantite) as va_quantite,
              CASE  WHEN 
                      sum(detail.intervention_prevu) =0 THEN 0
                    ELSE 
                    (sum(detail.nbr_mena+detail.nbr_ind)*100)/sum(detail.intervention_prevu)
              END as pourcen_effectif,
              CASE  WHEN 
                      sum(detail.budget_init) =0 THEN 0
                    ELSE 
                    (sum(detail.v_quantite)*100)/sum(detail.budget_init)
              END as pourcen_cout
        FROM 
              (select 
                      reg.id as id_region,
                      reg.nom as nom_region,
                      dist.id as id_district,
                      dist.nom as nom_dist,
                      interven.intitule as intitule_interven,
                      count(mena_bene.id) as nbr_mena, 
                      0 as nbr_ind,
                      0 as intervention_prevu,
                      0 as budget_init,
                      0 as budget_modif,
                      0 as v_quantite
                  from 
                      menage_beneficiaire as mena_bene
                      
                      join menage as men on men.id=mena_bene.id_menage
                      join fokontany as foko on foko.id=men.id_fokontany
                      join commune as com on com.id=foko.id_commune
                      join district as dist on dist.id=com.district_id
                      join region as reg on reg.id=dist.region_id
                      join intervention as interven on interven.id=mena_bene.id_intervention
              
                  where  ".$requete."
              
                  group by  reg.id,reg.nom,dist.id,dist.nom,interven.id,interven.intitule

              UNION

              select 
                      reg.id as id_region,
                      reg.nom as nom_region,
                      dist.id as id_district,
                      dist.nom as nom_dist,
                      interven.intitule as intitule_interven,
                      0 as nbr_mena,
                      count(ind_bene.id) as nbr_ind,
                      0 as intervention_prevu,
                      0 as budget_init,
                      0 as budget_modif,
                      0 as v_quantite
                  from 
                      individu_beneficiaire as ind_bene
                      
                      join individu as ind on ind.id=ind_bene.id_individu
                      join fokontany as foko on foko.id=ind.id_fokontany
                      join commune as com on com.id=foko.id_commune
                      join district as dist on dist.id=com.district_id
                      join region as reg on reg.id=dist.region_id
                      join intervention as interven on interven.id=ind_bene.id_intervention
              
                  where  ".$requete."
              
                  group by  reg.id,reg.nom,dist.id,dist.nom,interven.id,interven.intitule

              UNION

              select 
                      reg.id as id_region,
                      reg.nom as nom_region,
                      dist.id as id_district,
                      dist.nom as nom_dist,
                      interven.intitule as intitule_interven,
                      0 as nbr_mena,
                      0 as nbr_ind,
                      sum(zone_inter.menage_beneficiaire_prevu + zone_inter.individu_beneficiaire_prevu) as intervention_prevu,
                      0 as budget_init,
                      0 as budget_modif,
                      0 as v_quantite
                  from 
                      zone_intervention as zone_inter
                      
                      join fokontany as foko on foko.id=zone_inter.id_fokontany
                      join commune as com on com.id=foko.id_commune
                      join district as dist on dist.id=com.district_id
                      join region as reg on reg.id=dist.region_id
                      join intervention as interven on interven.id=zone_inter.id_intervention
              
                  where  ".$requete."
              
                  group by  reg.id,reg.nom,dist.id,dist.nom,interven.id,interven.intitule

              UNION

              select 
                      reg.id as id_region,
                      reg.nom as nom_region,
                      dist.id as id_district,
                      dist.nom as nom_dist,
                      interven.intitule as intitule_interven,
                      0 as nbr_mena, 
                      0 as nbr_ind,
                      0 as intervention_prevu,
                      financ_inte.budget_initial as budget_init,
                      financ_inte.budget_modifie as budget_modif,
                      0 as v_quantite
                  from 
                      financement_intervention as financ_inte
                      join intervention as interven on interven.id=financ_inte.id_intervention
                      
                      join zone_intervention as zone_inter on zone_inter.id_intervention=interven.id
                      join fokontany as foko on foko.id=zone_inter.id_fokontany
                      join commune as com on com.id=foko.id_commune
                      join district as dist on dist.id=com.district_id
                      join region as reg on reg.id=dist.region_id
              
                  where  ".$requete."
              
                  group by  reg.id,reg.nom,dist.id,dist.nom,interven.id,interven.intitule,financ_inte.id_intervention,budget_init,budget_modif

              UNION

              select 
                      reg.id as id_region,
                      reg.nom as nom_region,
                      dist.id as id_district,
                      dist.nom as nom_dist,
                      interven.intitule as intitule_interven,
                      0 as nbr_mena, 
                      0 as nbr_ind,
                      0 as intervention_prevu,
                      0 as budget_init,
                      0 as budget_modif,
                      detail_trans_inter.valeur_quantite as v_quantite
                  from 
                      zone_intervention as zone_inter
                      join intervention as interven on interven.id=zone_inter.id_intervention
                      
                      join fokontany as foko on foko.id=zone_inter.id_fokontany
                      join commune as com on com.id=foko.id_commune
                      join district as dist on dist.id=com.district_id
                      join region as reg on reg.id=dist.region_id
                      join detail_type_transfert_intervention as detail_trans_inter on detail_trans_inter.id_intervention = interven.id 
              
                  where  ".$requete." and detail_trans_inter.id_detail_type_transfert=1
              
                  group by  reg.id,reg.nom,dist.id,dist.nom,interven.id,interven.intitule,detail_trans_inter.id_intervention,v_quantite

              ) as detail
        
      group by detail.id_region,detail.nom_region,detail.id_district,detail.nom_dist,detail.intitule_interven
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

    //Proportion des interventions avec critères d'âge
   public function req19theme2_interven_pourcenenfan_pourcensco_pourcentra_pourcenage_pcout($enfant,$scolaire_min,$scolaire_max,$travail_min,$travail_max,$agee)
    {  
       $result = $this->db->query( "
        select               
              detail.intitule_interven as intitule_intervention,
              sum(detail.nbr_indi_enfan) as total_bene_enfan,
              sum(detail.nbr_indi_agesco) as total_bene_agesco,
              sum(detail.nbr_indi_agetra+detail.nbr_mena_agetra) as total_bene_agetra,
              sum(detail.nbr_indi_agee+detail.nbr_mena_agee) as total_bene_agee,
              sum(detail.intervention_prevu) as total_intervention_prevu,
              sum(detail.budget_init) as budget_initial,
              sum(detail.budget_modif) as budget_modif,
              sum(detail.v_quantite) as va_quantite,
              CASE  WHEN 
                      sum(detail.intervention_prevu) =0 THEN 0
                    ELSE 
                    (sum(detail.nbr_indi_enfan)*100)/sum(detail.intervention_prevu)
              END as pourcen_enfant,
              CASE  WHEN 
                      sum(detail.intervention_prevu) =0 THEN 0
                    ELSE 
                    (sum(detail.nbr_indi_agesco)*100)/sum(detail.intervention_prevu)
              END as pourcen_agesco,
              CASE  WHEN 
                      sum(detail.intervention_prevu) =0 THEN 0
                    ELSE 
                    (sum(detail.nbr_indi_agetra+detail.nbr_mena_agetra)*100)/sum(detail.intervention_prevu)
              END as pourcen_agetra,
              CASE  WHEN 
                      sum(detail.intervention_prevu) =0 THEN 0
                    ELSE 
                    (sum(detail.nbr_indi_agee+detail.nbr_mena_agee)*100)/sum(detail.intervention_prevu)
              END as pourcen_agee,
              CASE  WHEN 
                      sum(detail.budget_modif) =0 THEN 0
                    ELSE 
                    (sum(detail.v_quantite)*100)/sum(detail.budget_modif)
              END as pourcen_cout
        FROM 
              (select                       
                      interven.intitule as intitule_interven,
                      count(indi_bene.id) as nbr_indi_enfan,
                      0 as nbr_indi_agesco,
                      0 as nbr_indi_agetra,
                      0 as nbr_indi_agee, 
                      0 as nbr_mena_agetra, 
                      0 as nbr_mena_agee,
                      0 as intervention_prevu,
                      0 as budget_init,
                      0 as budget_modif,
                      0 as v_quantite
                  from 
                      individu_beneficiaire as indi_bene
                      
                      join individu as indi on indi.id=indi_bene.id_individu
                      join intervention as interven on interven.id=indi_bene.id_intervention
              
                  where indi.date_naissance >= '".$enfant."'
              
                  group by  interven.id,interven.intitule

              UNION

              select                       
                      interven.intitule as intitule_interven,
                      0 as nbr_indi_enfan,
                      count(indi_bene.id) as nbr_indi_agesco,
                      0 as nbr_indi_agetra,
                      0 as nbr_indi_agee, 
                      0 as nbr_mena_agetra, 
                      0 as nbr_mena_agee,
                      0 as intervention_prevu,
                      0 as budget_init,
                      0 as budget_modif,
                      0 as v_quantite
                  from 
                      individu_beneficiaire as indi_bene
                      
                      join individu as indi on indi.id=indi_bene.id_individu
                      join intervention as interven on interven.id=indi_bene.id_intervention
              
                  where indi.date_naissance BETWEEN '".$scolaire_max."' AND '".$scolaire_min."'
              
                  group by  interven.id,interven.intitule

              UNION

              select                       
                      interven.intitule as intitule_interven,
                      0 as nbr_indi_enfan,
                      0 as nbr_indi_agesco,
                      count(indi_bene.id) as nbr_indi_agetra, 
                      0 as nbr_indi_agee,
                      0 as nbr_mena_agetra,
                      0 as nbr_mena_agee,
                      0 as intervention_prevu,
                      0 as budget_init,
                      0 as budget_modif,
                      0 as v_quantite
                  from 
                      individu_beneficiaire as indi_bene
                      
                      join individu as indi on indi.id=indi_bene.id_individu
                      join intervention as interven on interven.id=indi_bene.id_intervention
              
                  where indi.date_naissance BETWEEN '".$travail_max."' AND '".$travail_min."'
              
                  group by  interven.id,interven.intitule

              UNION

              select                       
                      interven.intitule as intitule_interven,
                      0 as nbr_indi_enfan,
                      0 as nbr_indi_agesco,
                      0 as nbr_indi_agetra,
                      count(indi_bene.id) as nbr_indi_agee,
                      0 as nbr_mena_agetra, 
                      0 as nbr_mena_agee,
                      0 as intervention_prevu,
                      0 as budget_init,
                      0 as budget_modif,
                      0 as v_quantite
                  from 
                      individu_beneficiaire as indi_bene
                      
                      join individu as indi on indi.id=indi_bene.id_individu
                      join intervention as interven on interven.id=indi_bene.id_intervention
              
                  where indi.date_naissance <= '".$agee."'
              
                  group by  interven.id,interven.intitule

              UNION

              select                       
                      interven.intitule as intitule_interven,
                      0 as nbr_indi_enfan,
                      0 as nbr_indi_agesco,
                      0 as nbr_indi_agetra,
                      0 as nbr_indi_agee,
                      count(mena_bene.id) as nbr_mena_agetra, 
                      0 as nbr_mena_agee,
                      0 as intervention_prevu,
                      0 as budget_init,
                      0 as budget_modif,
                      0 as v_quantite
                  from 
                      menage_beneficiaire as mena_bene
                      
                      join menage as mena on mena.id=mena_bene.id_menage
                      join intervention as interven on interven.id=mena_bene.id_intervention
              
                  where mena.date_naissance BETWEEN '".$travail_max."' AND '".$travail_min."'
              
                  group by  interven.id,interven.intitule

              UNION

              select                       
                      interven.intitule as intitule_interven,
                      0 as nbr_indi_enfan,
                      0 as nbr_indi_agesco,
                      0 as nbr_indi_agetra,
                      0 as nbr_indi_agee,
                      0 as nbr_mena_agetra, 
                      count(mena_bene.id) as nbr_mena_agee,
                      0 as intervention_prevu,
                      0 as budget_init,
                      0 as budget_modif,
                      0 as v_quantite
                  from 
                      menage_beneficiaire as mena_bene
                      
                      join menage as mena on mena.id=mena_bene.id_menage
                      join intervention as interven on interven.id=mena_bene.id_intervention
              
                  where mena.date_naissance <= '".$agee."'
              
                  group by  interven.id,interven.intitule

              UNION

              select
                      interven.intitule as intitule_interven,
                      0 as nbr_indi_enfan,
                      0 as nbr_indi_agesco,
                      0 as nbr_indi_agetra,
                      0 as nbr_indi_agee,
                      0 as nbr_mena_agetra, 
                      0 as nbr_mena_agee,
                      sum(zone_inter.menage_beneficiaire_prevu + zone_inter.individu_beneficiaire_prevu) as intervention_prevu,
                      0 as budget_init,
                      0 as budget_modif,
                      0 as v_quantite
                  from 
                      zone_intervention as zone_inter
                      join intervention as interven on interven.id=zone_inter.id_intervention
              
                  group by interven.id,interven.intitule

              UNION

              select
                      interven.intitule as intitule_interven,
                      0 as nbr_indi_enfan,
                      0 as nbr_indi_agesco,
                      0 as nbr_indi_agetra,
                      0 as nbr_indi_agee,
                      0 as nbr_mena_agetra, 
                      0 as nbr_mena_agee,
                      0 as intervention_prevu,
                      financ_inte.budget_initial as budget_init,
                      financ_inte.budget_modifie as budget_modif,
                      0 as v_quantite
                  from 
                      financement_intervention as financ_inte
                      join intervention as interven on interven.id=financ_inte.id_intervention
                      
                      join zone_intervention as zone_inter on zone_inter.id_intervention=interven.id             
              
                  group by interven.id,interven.intitule,financ_inte.id_intervention,budget_init,budget_modif

              UNION

              select
                      interven.intitule as intitule_interven,
                      0 as nbr_indi_enfan,
                      0 as nbr_indi_agesco,
                      0 as nbr_indi_agetra,
                      0 as nbr_indi_agee,
                      0 as nbr_mena_agetra, 
                      0 as nbr_mena_agee,
                      0 as intervention_prevu,
                      0 as budget_init,
                      0 as budget_modif,
                      detail_trans_inter.valeur_quantite as v_quantite
                  from 
                      zone_intervention as zone_inter
                      join intervention as interven on interven.id=zone_inter.id_intervention
                      
                      join detail_type_transfert_intervention as detail_trans_inter on detail_trans_inter.id_intervention = interven.id 
              
                  where  detail_trans_inter.id_detail_type_transfert=1
              
                  group by interven.id,interven.intitule,detail_trans_inter.id_intervention,v_quantite

              ) as detail
        
      group by detail.intitule_interven
      order by detail.intitule_interven
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


    //CODE HARIZO

        public function beneficiare_sortie_programme()//effectif beneficiaire sorti du programme (41)
        {

                $sql = "select sum(principale.nombre_menage)  as nombre_menage, 
                         sum(principale.nombre_individu) as nombre_individu,
                         principale.intervention,
                         principale.sexe


                    FROM (select count(mb.id) as nombre_menage, 0 as nombre_individu, m.sexe as sexe ,
                            it.intitule as intervention
                            from menage as m
                            join menage_beneficiaire as mb on mb.id_menage = m.id
                            join intervention as it on it.id = mb.id_intervention  
                        where mb.date_sortie is not null
                        group by it.id, m.sexe 

                        UNION 

                        select 0 as nombre_menage, count(ib.id) as nombre_individu, i.sexe as sexe ,
                                it.intitule as intervention

                            from individu as i
                            join individu_beneficiaire as ib on ib.id_individu=i.id
                            join intervention as it on it.id=ib.id_intervention
                        where ib.date_sortie is not null
                        group by it.id, i.sexe 

                         ) principale

                group by 
                        principale.intervention, principale.sexe" ;


            return $this->db->query($sql)->result();

      
        }


        public function nombre_beneficiaire_handicap()//Effectif beneficiaire handicapés(40)
        {
             $sql = "select sum(principale.nbr_hand_visu) as nbr_hand_visu,
                            sum(principale.nbr_hand_paro) as nbr_hand_paro,
                            sum(principale.nbr_hand_audi) as nbr_hand_audi,
                            sum(principale.nbr_hand_ment) as nbr_hand_ment,
                            sum(principale.nbr_hand_mote) as nbr_hand_mote,
                            principale.intervention


                    FROM (

                            select count(enq_ind.id) as nbr_hand_visu,
                                   0 as nbr_hand_paro,
                                   0 as nbr_hand_audi,
                                   0 as nbr_hand_ment,
                                   0 as nbr_hand_mote,
                                   int.intitule as intervention

                            from enquete_individu as enq_ind

                                join individu as i on i.id = enq_ind.id_individu
                                join individu_beneficiaire as indiv_bene on i.id = indiv_bene.id_individu
                                join intervention as int on int.id = indiv_bene.id_intervention
                                
                                where enq_ind.id_handicap_visuel is not null
                                and ((select count(*) from individu_beneficiaire as indiv_bene where indiv_bene.id_individu = enq_ind.id_individu) > 0)

                                group by int.id

                            UNION

                            select 0 as nbr_hand_visu, 
                                   count(enq_ind.id) as nbr_hand_paro,
                                   0 as nbr_hand_audi,
                                   0 as nbr_hand_ment,
                                   0 as nbr_hand_mote,
                                   int.intitule as intervention

                            from enquete_individu as enq_ind

                                join individu as i on i.id = enq_ind.id_individu
                                join individu_beneficiaire as indiv_bene on i.id = indiv_bene.id_individu
                                join intervention as int on int.id = indiv_bene.id_intervention

                                where enq_ind.id_handicap_parole is not null
                                and ((select count(*) from individu_beneficiaire as indiv_bene where indiv_bene.id_individu = enq_ind.id_individu) > 0)
                                group by int.id

                            UNION

                            select 0 as nbr_hand_visu, 
                                   0 as nbr_hand_paro,
                                   count(enq_ind.id) as nbr_hand_audi,
                                   0 as nbr_hand_ment,
                                   0 as nbr_hand_mote,
                                   int.intitule as intervention

                            from enquete_individu as enq_ind

                                join individu as i on i.id = enq_ind.id_individu
                                join individu_beneficiaire as indiv_bene on i.id = indiv_bene.id_individu
                                join intervention as int on int.id = indiv_bene.id_intervention

                                where enq_ind.id_handicap_auditif is not null
                                and ((select count(*) from individu_beneficiaire as indiv_bene where indiv_bene.id_individu = enq_ind.id_individu) > 0)
                                group by int.id

                            UNION

                            select 0 as nbr_hand_visu, 
                                   0 as nbr_hand_paro,
                                   0 as nbr_hand_audi,
                                   count(enq_ind.id) as nbr_hand_ment,
                                   0 as nbr_hand_mote,
                                   int.intitule as intervention

                            from enquete_individu as enq_ind

                                join individu as i on i.id = enq_ind.id_individu
                                join individu_beneficiaire as indiv_bene on i.id = indiv_bene.id_individu
                                join intervention as int on int.id = indiv_bene.id_intervention

                                where enq_ind.id_handicap_mental is not null
                                and ((select count(*) from individu_beneficiaire as indiv_bene where indiv_bene.id_individu = enq_ind.id_individu) > 0)
                                group by int.id


                            UNION

                            select 0 as nbr_hand_visu, 
                                   0 as nbr_hand_paro,
                                   0 as nbr_hand_audi,
                                   0 as nbr_hand_ment,
                                   count(enq_ind.id) as nbr_hand_mote,
                                   int.intitule as intervention

                            from enquete_individu as enq_ind

                                join individu as i on i.id = enq_ind.id_individu
                                join individu_beneficiaire as indiv_bene on i.id = indiv_bene.id_individu
                                join intervention as int on int.id = indiv_bene.id_intervention

                                where enq_ind.id_handicap_moteur is not null
                                and ((select count(*) from individu_beneficiaire as indiv_bene where indiv_bene.id_individu = enq_ind.id_individu) > 0)
                                group by int.id
                            

                         ) principale

                    group by principale.intervention

                " ;

                return $this->db->query($sql)->result();
        }

        public function total_transfert($id_type_transfert, $date_debut, $date_fin)
        {
            $sql = "

                    select int.intitule as intitule_intervention,
                           sum(smdt.valeur_quantite) as quantite,
                           um.description as unite_mesure,
                           dtt.description as detail_type_transfert


                    from suivi_menage_detail_transfert as smdt

                            join suivi_menage_entete as sme on sme.id = smdt.id_suivi_menage_entete
                            join intervention as int on int.id = sme.id_intervention
                            join detail_type_transfert as dtt on dtt.id = smdt.id_detail_type_transfert
                            join unite_mesure as um on um.id = dtt.id_unite_mesure
                            join type_transfert as tt on tt.id = dtt.id_type_transfert
                    where tt.id = ".$id_type_transfert." and sme.date_suivi BETWEEN '".$date_debut."' AND '".$date_fin."'

                    group by um.id, int.id, dtt.id,int.id



            " ;

            return $this->db->query($sql)->result();
        }


        public function Moyenne_transfert($id_type_transfert, $date_debut, $date_fin)
        {
            $sql = "

                    select int.intitule as intitule_intervention,
                           (sum(smdt.valeur_quantite)/count(smdt.id)) as moyenne,
                           um.description as unite_mesure,
                           dtt.description as detail_type_transfert


                    from suivi_menage_detail_transfert as smdt

                            join suivi_menage_entete as sme on sme.id = smdt.id_suivi_menage_entete
                            join intervention as int on int.id = sme.id_intervention
                            join detail_type_transfert as dtt on dtt.id = smdt.id_detail_type_transfert
                            join unite_mesure as um on um.id = dtt.id_unite_mesure
                            join type_transfert as tt on tt.id = dtt.id_type_transfert
                    where tt.id = ".$id_type_transfert." and sme.date_suivi BETWEEN '".$date_debut."' AND '".$date_fin."'

                    group by um.id, int.id, dtt.id,int.id



            " ;

            return $this->db->query($sql)->result();
        }


        public function decaissement_par_programme()
        {
            $this->db->select(" programme.id as id_prog, programme.intitule as intitule_programme,
                                intervention.id as id_interv, intervention.intitule as intitule_intervention,
                                sum(decaissement.montant_initial) as montant_init, sum(decaissement.montant_revise) as montant_revise,
                                devise.id as id_devise, devise.description as devise");
        
            


            $result =  $this->db->from('programme, intervention, financement_intervention, decaissement, devise')
                        
                        ->where('programme.id = intervention.id_programme')
                        ->where('intervention.id = financement_intervention.id_intervention')
                        ->where('financement_intervention.id = decaissement.id_financement_intervention')
                        ->where('financement_intervention.id_devise = devise.id')


                        ->group_by('id_prog,id_interv,devise.id')
                                           
                        ->get()
                        ->result();                              

            if($result)
            {
                return $result;
            }else{
                return null;
            }          
        }

        public function decaissement_par_agence_execution()
        {
            /*$this->db->select(" acteur.id as id_act, acteur.nom as nom_acteur,
                                intervention.id as id_interv, intervention.intitule as intitule_intervention,
                                sum(decaissement.montant_initial) as montant_init, sum(decaissement.montant_revise) as montant_revise,
                                devise.id as id_devise, devise.description as devise");*/
            $this->db->select(" programme.id as id_prog, programme.intitule as intitule_programme,
                                acteur.id as id_act, acteur.nom as nom_acteur,
                                intervention.id as id_interv, intervention.intitule as intitule_intervention");
        
            


            $result =  $this->db->from('acteur, intervention,programme')
                        
                        ->where('acteur.id = intervention.id_acteur')

                        ->where('programme.id = intervention.id_programme')
                        


                        ->group_by('id_prog,id_act,id_interv')
                                           
                        ->get()
                        ->result();                              

            if($result)
            {
                return $result;
            }else{
                return null;
            }          
        }

        public function decaissement_par_tutelle()
        {
            $this->db->select(" intervention.id as id_interv, intervention.intitule as intitule_intervention,intervention.ministere_tutelle as tutelle,
                                sum(decaissement.montant_initial) as montant_init, sum(decaissement.montant_revise) as montant_revise,
                                devise.id as id_devise, devise.description as devise");
        
            


            $result =  $this->db->from(' intervention, financement_intervention, decaissement, devise')
                        
                      
                        ->where('intervention.id = financement_intervention.id_intervention')
                        ->where('financement_intervention.id = decaissement.id_financement_intervention')
                        ->where('financement_intervention.id_devise = devise.id')


                        ->group_by('tutelle,id_interv,devise.id')
                                           
                        ->get()
                        ->result();                              

            if($result)
            {
                return $result;
            }else{
                return null;
            } 
        }


        public function montant_budget_non_consommee_par_programme()
        {
            $this->db->select(" programme.id as id_prog, programme.intitule as intitule_programme,
                                devise.id as id_dev,devise.description as desc_devise,
                                sum(financement_programme.budget_modifie) as budget_prevu");


            $this->db ->select("(select sum(decais.montant_revise) from intervention as int 
                                            inner join financement_intervention as fin_int on int.id = fin_int.id_intervention
                                            inner join decaissement as decais on fin_int.id = decais.id_financement_intervention
                                            inner join devise as dev on fin_int.id_devise = dev.id
                                            where int.id_programme= programme.id and dev.id=devise.id) as somme_decaissement",false);

            $this->db ->select("(sum(financement_programme.budget_modifie) - (select sum(decais.montant_revise) from intervention as int 
                                                        inner join financement_intervention as fin_int on int.id = fin_int.id_intervention
                                                        inner join decaissement as decais on fin_int.id = decais.id_financement_intervention
                                                        inner join devise as dev on fin_int.id_devise = dev.id
                                                        where int.id_programme= programme.id and dev.id=devise.id)) as budget_non_comnsommee",false);

             $this->db ->select("(((select sum(decais.montant_revise) from intervention as int 
                                                                      inner join financement_intervention as fin_int on int.id = fin_int.id_intervention
                                                                      inner join decaissement as decais on fin_int.id = decais.id_financement_intervention
                                                                      inner join devise as dev on fin_int.id_devise = dev.id
                                                                      where int.id_programme= programme.id and dev.id=devise.id) * 100)/sum(financement_programme.budget_modifie)) as prop",false);
                                 
                                
        
            


            $result =  $this->db->from('programme, financement_programme, devise')
                        
                       
                        ->where('programme.id = financement_programme.id_programme')
                        ->where('devise.id = financement_programme.id_devise')
                        


                        ->group_by('id_prog, id_dev')
                                           
                        ->get()
                        ->result();                              

            if($result)
            {
                return $result;
            }else{
                return null;
            }          
        }


        public function taux_de_decaissement_par_programme()
        {
            $this->db->select(" programme.id as id_prog, programme.intitule as intitule_programme,
                                intervention.id as id_interv, intervention.intitule as intitule_intervention,
                                sum(financement_intervention.budget_modifie) as sum_financement_par_intervention_par_programme,
                                sum(decaissement.montant_revise) as sum_decaissement,
                                devise.id as id_devise, devise.description as devise,
                                ((sum(decaissement.montant_revise)*100)/sum(financement_intervention.budget_modifie)) as prop");
        
            


            $result =  $this->db->from('programme, intervention, financement_intervention, decaissement, devise')
                        
                        ->where('programme.id = intervention.id_programme')
                        ->where('intervention.id = financement_intervention.id_intervention')
                        ->where('financement_intervention.id = decaissement.id_financement_intervention')
                        ->where('financement_intervention.id_devise = devise.id')


                        ->group_by('id_prog,id_interv,devise.id')
                                           
                        ->get()
                        ->result();                              

            if($result)
            {
                return $result;
            }else{
                return null;
            }          
        }

       
    //FIN CODE HARIZO

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
