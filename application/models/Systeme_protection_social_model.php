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

    
    


    //Proportion des interventions avec critères d'âge TSY METY ITY 
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

    //Proportion des interventions avec critères d'âge
   public function req20theme2_interven_pourcenfille_pourcenhomme_pcout()
    {  
       $result = $this->db->query( "
        select               
              detail.intitule_interven as intitule_intervention,
              sum(detail.nbr_indi_f) as bene_f,
              sum(detail.nbr_indi_h) as bene_h,
              sum(detail.nbr_indi_f+detail.nbr_mena_f) as total_bene_f,
              sum(detail.nbr_indi_h+detail.nbr_mena_h) as total_bene_h,
              sum(detail.intervention_prevu) as total_intervention_prevu,
              sum(detail.budget_init) as budget_initial,
              sum(detail.budget_modif) as budget_modif,
              sum(detail.v_quantite) as va_quantite,
              CASE  WHEN 
                      sum(detail.intervention_prevu) =0 THEN 0
                    ELSE 
                    (sum(detail.nbr_indi_f+detail.nbr_mena_f)*100)/sum(detail.intervention_prevu)
              END as pourcen_f,
              CASE  WHEN 
                      sum(detail.intervention_prevu) =0 THEN 0
                    ELSE 
                    (sum(detail.nbr_indi_h+detail.nbr_mena_h)*100)/sum(detail.intervention_prevu)
              END as pourcen_h,              
              CASE  WHEN 
                      sum(detail.budget_modif) =0 THEN 0
                    ELSE 
                    (sum(detail.v_quantite)*100)/sum(detail.budget_modif)
              END as pourcen_cout
        FROM 
              (select                       
                      interven.intitule as intitule_interven,
                      count(indi_bene.id) as nbr_indi_f,
                      0 as nbr_indi_h,
                      0 as nbr_mena_f,
                      0 as nbr_mena_h,
                      0 as intervention_prevu,
                      0 as budget_init,
                      0 as budget_modif,
                      0 as v_quantite
                  from 
                      individu_beneficiaire as indi_bene
                      
                      join individu as indi on indi.id=indi_bene.id_individu
                      join intervention as interven on interven.id=indi_bene.id_intervention
              
                  where indi.sexe = 'F'
              
                  group by  interven.id,interven.intitule

              UNION

              select                       
                      interven.intitule as intitule_interven,
                      0 as nbr_indi_f,
                      count(indi_bene.id) as nbr_indi_h,
                      0 as nbr_mena_f,
                      0 as nbr_mena_h,
                      0 as intervention_prevu,
                      0 as budget_init,
                      0 as budget_modif,
                      0 as v_quantite
                  from 
                      individu_beneficiaire as indi_bene
                      
                      join individu as indi on indi.id=indi_bene.id_individu
                      join intervention as interven on interven.id=indi_bene.id_intervention
              
                  where indi.sexe = 'H'
              
                  group by  interven.id,interven.intitule

              UNION

              select                       
                      interven.intitule as intitule_interven,
                      0 as nbr_indi_f,
                      0 as nbr_indi_h,
                      count(mena_bene.id) as nbr_mena_f,
                      0 as nbr_mena_h,
                      0 as intervention_prevu,
                      0 as budget_init,
                      0 as budget_modif,
                      0 as v_quantite
                  from 
                      menage_beneficiaire as mena_bene
                      
                      join menage as mena on mena.id=mena_bene.id_menage
                      join intervention as interven on interven.id=mena_bene.id_intervention
              
                  where mena.sexe = 'F'
              
                  group by  interven.id,interven.intitule

              UNION

              select                       
                      interven.intitule as intitule_interven,
                      0 as nbr_indi_f,
                      0 as nbr_indi_h,
                      0 as nbr_mena_f, 
                      count(mena_bene.id) as nbr_mena_h,
                      0 as intervention_prevu,
                      0 as budget_init,
                      0 as budget_modif,
                      0 as v_quantite
                  from 
                      menage_beneficiaire as mena_bene
                      
                      join menage as mena on mena.id=mena_bene.id_menage
                      join intervention as interven on interven.id=mena_bene.id_intervention
              
                  where mena.sexe = 'H'
              
                  group by  interven.id,interven.intitule

              UNION

              select
                      interven.intitule as intitule_interven,
                      0 as nbr_indi_f,
                      0 as nbr_indi_h,
                      0 as nbr_mena_f, 
                      0 as nbr_mena_h,
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
                      0 as nbr_indi_f,
                      0 as nbr_indi_h,
                      0 as nbr_mena_f, 
                      0 as nbr_mena_h,
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
                      0 as nbr_indi_f,
                      0 as nbr_indi_h,
                      0 as nbr_mena_f, 
                      0 as nbr_mena_h,
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

                    group by um.id, int.id, dtt.id



            " ;

            return $this->db->query($sql)->result();
        }


        public function Moyenne_transfert($id_type_transfert, $date_debut, $date_fin)
        {
            $sql = "

                    select int.intitule as intitule_intervention,

                            CASE 
                              WHEN count(smdt.id) = 0
                                THEN '00'
                              ELSE
                                (sum(smdt.valeur_quantite)/count(smdt.id))

                            END as moyenne,
                           um.description as unite_mesure,
                           dtt.description as detail_type_transfert


                    from suivi_menage_detail_transfert as smdt

                            join suivi_menage_entete as sme on sme.id = smdt.id_suivi_menage_entete
                            join intervention as int on int.id = sme.id_intervention
                            join detail_type_transfert as dtt on dtt.id = smdt.id_detail_type_transfert
                            join unite_mesure as um on um.id = dtt.id_unite_mesure
                            join type_transfert as tt on tt.id = dtt.id_type_transfert
                    where tt.id = ".$id_type_transfert." and sme.date_suivi BETWEEN '".$date_debut."' AND '".$date_fin."'

                    group by um.id, int.id, dtt.id



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
                                CASE
                                  WHEN sum(financement_intervention.budget_modifie) = 0
                                    THEN 0
                                  ELSE
                                    ((sum(decaissement.montant_revise)*100)/sum(financement_intervention.budget_modifie))


                                END as prop");
        
            


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

        public function proportion_des_intervention_par_type_de_cible()
        {
          $sql = "

                    select 
                        pplee2.intitule_intervention as intitule_intervention,

                        CASE WHEN (sum(pplee2.total_montant_menage) + sum(pplee2.total_montant_groupe) + sum(pplee2.total_montant_individu)) = 0
                          THEN 0
                        ELSE
                          ROUND((((sum(pplee2.total_montant_menage)) * 100)/((sum(pplee2.total_montant_menage)) + (sum(pplee2.total_montant_groupe)) + (sum(pplee2.total_montant_individu)))),2) 
                        END as stat_montant_menage,

                        CASE WHEN (sum(pplee2.total_montant_menage) + sum(pplee2.total_montant_groupe) + sum(pplee2.total_montant_individu)) = 0
                          THEN 0
                        ELSE
                          ROUND((((sum(pplee2.total_montant_groupe)) * 100)/((sum(pplee2.total_montant_menage)) + (sum(pplee2.total_montant_groupe)) + (sum(pplee2.total_montant_individu)))),2) 
                        END as stat_montant_groupe,


                        CASE WHEN (sum(pplee2.total_montant_menage) + sum(pplee2.total_montant_groupe) + sum(pplee2.total_montant_individu)) = 0
                          THEN 0
                        ELSE
                          ROUND((((sum(pplee2.total_montant_individu)) * 100)/((sum(pplee2.total_montant_menage)) + (sum(pplee2.total_montant_groupe)) + (sum(pplee2.total_montant_individu)))),2) 
                        END as stat_montant_individu,

                        CASE WHEN (MAX(pplee2.nombre_menage) + MAX(pplee2.nombre_groupe) + MAX(pplee2.nombre_individu)) = 0
                          THEN 0
                        ELSE
                          ROUND((((MAX(pplee2.nombre_menage)) * 100)/((MAX(pplee2.nombre_menage)) + (MAX(pplee2.nombre_groupe)) + (MAX(pplee2.nombre_individu)))),2) 
                        END as stat_menage,

                        CASE WHEN (MAX(pplee2.nombre_menage) + MAX(pplee2.nombre_groupe) + MAX(pplee2.nombre_individu)) = 0
                          THEN 0
                        ELSE
                          ROUND((((MAX(pplee2.nombre_groupe)) * 100)/((MAX(pplee2.nombre_menage)) + (MAX(pplee2.nombre_groupe)) + (MAX(pplee2.nombre_individu)))),2) 
                        END as stat_groupe,

                        CASE WHEN (MAX(pplee2.nombre_menage) + MAX(pplee2.nombre_groupe) + MAX(pplee2.nombre_individu)) = 0
                          THEN 0
                        ELSE
                          ROUND((((MAX(pplee2.nombre_individu)) * 100)/((MAX(pplee2.nombre_menage)) + (MAX(pplee2.nombre_groupe)) + (MAX(pplee2.nombre_individu)))),2) 
                        END as stat_individu


                    from

                        (
                        select sum(principale.nombre_menage)  as nombre_menage, 
                            sum(principale.nombre_groupe) as nombre_groupe,
                            sum(principale.nombre_individu) as nombre_individu,
                            principale.montant_transfert_menage,
                            principale.montant_transfert_groupe,
                            principale.montant_transfert_individu,
                            (principale.montant_transfert_menage * principale.nombre_menage) as total_montant_menage,
                            (principale.montant_transfert_groupe * principale.nombre_groupe) as total_montant_groupe,
                            (principale.montant_transfert_individu * principale.nombre_individu) as total_montant_individu,
                            principale.intitule_intervention as intitule_intervention


                        FROM (
                            select  count(DISTINCT(sm.id_menage)) as nombre_menage, 
                                0 as nombre_groupe ,
                                0 as nombre_individu ,
                                int.intitule as intitule_intervention,
                                sme.montant_transfert as montant_transfert_menage,
                                0 as montant_transfert_groupe,
                                0 as montant_transfert_individu
                            from suivi_menage as sm
                                join suivi_menage_entete as sme on sme.id = sm.id_suivi_menage_entete
                                join menage as m on m.id = sm.id_menage
                                join intervention as int on int.id = sme.id_intervention
                                where m.etat_groupe = 0
                                group by sme.id_intervention ,int.id, sme.montant_transfert

                            UNION 

                            select  0 as nombre_menage, 
                                count(DISTINCT(sm.id_menage)) as nombre_groupe ,
                                0 as nombre_individu ,
                                int.intitule as intitule_intervention,
                                0 as montant_transfert_menage,
                                sme.montant_transfert as montant_transfert_groupe,
                                0 as montant_transfert_individu
                            from suivi_menage as sm
                                join suivi_menage_entete as sme on sme.id = sm.id_suivi_menage_entete
                                join menage as m on m.id = sm.id_menage
                                join intervention as int on int.id = sme.id_intervention
                                where m.etat_groupe = 1
                                group by sme.id_intervention , int.id, sme.montant_transfert

                            UNION 

                            select  0 as nombre_menage, 
                                0 as nombre_groupe ,
                                count(DISTINCT(si.id_individu)) as nombre_individu ,
                                int.intitule as intitule_intervention,
                                0 as montant_transfert_menage,
                                0 as montant_transfert_groupe,
                                sie.montant_transfert as montant_transfert_individu
                            from suivi_individu as si
                                join suivi_individu_entete as sie on sie.id = si.id_suivi_individu_entete
                                join menage as m on m.id = si.id_individu
                                join intervention as int on int.id = sie.id_intervention
                                group by sie.id_intervention , int.id, sie.montant_transfert

                        ) principale 

                        group by  principale.intitule_intervention, 
                        principale.montant_transfert_menage,
                        principale.montant_transfert_groupe,
                        principale.montant_transfert_individu,
                        principale.nombre_menage,
                        principale.nombre_groupe,
                        principale.nombre_individu
                    ) pplee2 group by  pplee2.intitule_intervention
              
                  " ;


              return $this->db->query($sql)->result();
        }
        

        //requete Répartition géographique des interventions


        public function req14theme2_interven_nbrinter_budgetinit_peffectif_pcout_region_district()
        {  
           

            $sql= "
                    select
                      niveau_1.intitule_intervention as intitule_inter,
                      niveau_1.id_interv as id_inter,
                      niveau_1.id_region as id_reg,
                      niveau_1.nom_region as nom_reg,
                      niveau_1.id_district as id_dist,
                      niveau_1.nom_district as nom_dist,
                      (sum(niveau_1.valeur_par_entete_menage) + sum(niveau_1.valeur_par_entete_individu)) as total_cout_district,

                      (sum(niveau_1.nbr_intervention_total_menage) + sum(niveau_1.nbr_intervention_total_individu)) as nbr_total_interv,

                      (sum(nbr_intervention_menage_par_district) + sum(nbr_intervention_individu_par_district)) as nbr_interv_par_district,

                      (((sum(nbr_intervention_menage_par_district) + sum(nbr_intervention_individu_par_district)) * 100)/(sum(niveau_1.nbr_intervention_total_menage) + sum(niveau_1.nbr_intervention_total_individu))) as effectif_intervention,

                      ( select

                          sum(srq_niveau_1.cout_menage_par_entete) + sum(srq_niveau_1.cout_individu_par_entete) as total_cout_menage

                        from ( select 

                                  (count(suivi_menage.id_menage) * suivi_menage_entete.montant_transfert) as cout_menage_par_entete,
                                  0 as cout_individu_par_entete,
                                  intervention.id as id_int

                                from 
                                  suivi_menage_entete,
                                  suivi_menage,
                                  intervention

                                where 
                                  suivi_menage_entete.id = suivi_menage.id_suivi_menage_entete
                                  and intervention.id = suivi_menage_entete.id_intervention
                                  and intervention.id = niveau_1.id_interv
                                group by 
                                  suivi_menage_entete.id, id_int

                              UNION

                              select 

                                  0 as cout_menage_par_entete,
                                  (count(suivi_individu.id_individu) * suivi_individu_entete.montant_transfert) as cout_individu_par_entete,
                                  intervention.id as id_int

                                from 
                                  suivi_individu_entete,
                                  suivi_individu,
                                  intervention

                                where 
                                  suivi_individu_entete.id = suivi_individu.id_suivi_individu_entete
                                  and intervention.id = suivi_individu_entete.id_intervention
                                  and intervention.id = niveau_1.id_interv
                                group by 
                                  suivi_individu_entete.id, id_int

                              ) srq_niveau_1

                              group by srq_niveau_1.id_int

                            
                      )

                    from  (
                            select 
                              
                              intervention.intitule as intitule_intervention,
                              intervention.id as id_interv,
                              district.id as id_district,
                              district.nom as nom_district,
                              region.id as id_region,
                              region.nom as nom_region,
                              count(suivi_menage.id_menage) as nbr_menage_par_entete,
                              ((count(suivi_menage.id_menage)) * suivi_menage_entete.montant_transfert) as valeur_par_entete_menage,
                              0 as valeur_par_entete_individu,

                              count(DISTINCT(suivi_menage_entete.id)) as nbr_intervention_menage_par_district,
                              (select count(id) from suivi_menage_entete where id_intervention = intervention.id) as nbr_intervention_total_menage,

                              0 as nbr_intervention_individu_par_district,
                              0 as nbr_intervention_total_individu

                            from 
                              suivi_menage_entete, 
                              suivi_menage, 
                              intervention,
                              fokontany,
                              commune,
                              district,
                              region

                            where 

                              suivi_menage_entete.id = suivi_menage.id_suivi_menage_entete
                              and intervention.id = suivi_menage_entete.id_intervention
                              and fokontany.id = suivi_menage_entete.id_fokontany
                              and commune.id = fokontany.id_commune
                              and district.id = commune.district_id
                              and region.id = district.region_id
                            group by 
                              suivi_menage_entete.id,
                              id_interv,
                              district.id,
                              region.id

                            UNION

                            select 
                              
                              intervention.intitule as intitule_intervention,
                              intervention.id as id_interv,
                              district.id as id_district,
                              district.nom as nom_district,
                              region.id as id_region,
                              region.nom as nom_region,
                              count(suivi_individu.id_individu) as nbr_individu_par_entete,
                              0 as valeur_par_entete_menage,
                              ((count(suivi_individu.id_individu)) * suivi_individu_entete.montant_transfert) as valeur_par_entete_individu,

                              count(DISTINCT(suivi_individu_entete.id)) as nbr_intervention_individu_par_district,
                              (select count(id) from suivi_individu_entete where id_intervention = intervention.id) as nbr_intervention_total_individu,

                              0 as nbr_intervention_menage_par_district,
                              0 as nbr_intervention_total_menage

                            from 
                              suivi_individu_entete, 
                              suivi_individu, 
                              intervention,
                              fokontany,
                              commune,
                              district,
                              region

                            where 

                              suivi_individu_entete.id = suivi_individu.id_suivi_individu_entete
                              and intervention.id = suivi_individu_entete.id_intervention
                              and fokontany.id = suivi_individu_entete.id_fokontany
                              and commune.id = fokontany.id_commune
                                and district.id = commune.district_id
                                and region.id = district.region_id
                            group by 
                              suivi_individu_entete.id,
                              id_interv,
                              district.id,
                              region.id

                          ) niveau_1


                          group by 

                                  niveau_1.intitule_intervention ,
                                  niveau_1.id_interv ,
                                  niveau_1.id_region ,
                                  niveau_1.nom_region ,
                                  niveau_1.id_district,
                                  niveau_1.nom_district 

                          




                  " ;
                  return $this->db->query($sql)->result();

        }     

        //fin proportion_des_intervention_par_type_de_cible

        /*Proportion des interventions avec critères de sexe*/
        public function proportion_des_intervention_avec_critere_sexe()
        {

            $sql =  " 
                      select
                        niveau_1.intitule_intervention as intitule_interv,
                        niveau_1.id_intervention id_interv,
               
                        (sum(niveau_1.cout_par_entete_menage_h) + sum(niveau_1.cout_par_entete_individu_h)) cout_total_intervention_h,
                        (sum(niveau_1.cout_par_entete_menage_f) + sum(niveau_1.cout_par_entete_individu_f)) cout_total_intervention_f,

                        ( select
                            (sum(nbr_homme.nbr_menage_h) + sum(nbr_homme.nbr_indiviu_h)) as nbr_total_homme
                          from 
                          ( 
                            select
                              count(DISTINCT(srq_sm.id_menage)) as nbr_menage_h,
                              0 as nbr_indiviu_h
                            from
                              suivi_menage_entete as srq_sme,
                              suivi_menage srq_sm,
                              menage srq_m,
                              intervention as sqr_int

                            where 
                              sqr_int.id = srq_sme.id_intervention
                              and srq_sme.id = srq_sm.id_suivi_menage_entete
                              and srq_m.id = srq_sm.id_menage
                              and srq_m.sexe = 'H'
                              and sqr_int.id = niveau_1.id_intervention

                            UNION

                            select
                              0 as nbr_menage_h,
                              count(DISTINCT(srq_si.id_individu)) as nbr_indiviu_h
                            from
                              suivi_individu_entete as srq_sie,
                              suivi_individu srq_si,
                              individu srq_i,
                              intervention as sqr_int

                            where 
                              sqr_int.id = srq_sie.id_intervention
                              and srq_sie.id = srq_si.id_suivi_individu_entete
                              and srq_i.id = srq_si.id_individu
                              and srq_i.sexe = 'H'
                              and sqr_int.id = niveau_1.id_intervention


                          ) nbr_homme

                        ),

                        ( select
                            (sum(nbr_homme.nbr_menage_f) + sum(nbr_homme.nbr_indiviu_f)) as nbr_total_femme
                          from 
                          ( 
                            select
                              count(DISTINCT(srq_sm.id_menage)) as nbr_menage_f,
                              0 as nbr_indiviu_f
                            from
                              suivi_menage_entete as srq_sme,
                              suivi_menage srq_sm,
                              menage srq_m,
                              intervention as sqr_int

                            where 
                              sqr_int.id = srq_sme.id_intervention
                              and srq_sme.id = srq_sm.id_suivi_menage_entete
                              and srq_m.id = srq_sm.id_menage
                              and srq_m.sexe = 'F'
                              and sqr_int.id = niveau_1.id_intervention

                            UNION

                            select
                              0 as nbr_menage_f,
                              count(DISTINCT(srq_si.id_individu)) as nbr_indiviu_f
                            from
                              suivi_individu_entete as srq_sie,
                              suivi_individu srq_si,
                              individu srq_i,
                              intervention as sqr_int

                            where 
                              sqr_int.id = srq_sie.id_intervention
                              and srq_sie.id = srq_si.id_suivi_individu_entete
                              and srq_i.id = srq_si.id_individu
                              and srq_i.sexe = 'F'
                              and sqr_int.id = niveau_1.id_intervention


                          ) nbr_homme

                        ),
                        ( select

                          sum(srq_niveau_1.cout_menage_par_entete) + sum(srq_niveau_1.cout_individu_par_entete) as total_cout_menage

                        from ( select 

                                  (count(suivi_menage.id_menage) * suivi_menage_entete.montant_transfert) as cout_menage_par_entete,
                                  0 as cout_individu_par_entete,
                                  intervention.id as id_int

                                from 
                                  suivi_menage_entete,
                                  suivi_menage,
                                  intervention

                                where 
                                  suivi_menage_entete.id = suivi_menage.id_suivi_menage_entete
                                  and intervention.id = suivi_menage_entete.id_intervention
                                  and intervention.id = niveau_1.id_intervention
                                group by 
                                  suivi_menage_entete.id, id_int

                              UNION

                              select 

                                  0 as cout_menage_par_entete,
                                  (count(suivi_individu.id_individu) * suivi_individu_entete.montant_transfert) as cout_individu_par_entete,
                                  intervention.id as id_int

                                from 
                                  suivi_individu_entete,
                                  suivi_individu,
                                  intervention

                                where 
                                  suivi_individu_entete.id = suivi_individu.id_suivi_individu_entete
                                  and intervention.id = suivi_individu_entete.id_intervention
                                  and intervention.id = niveau_1.id_intervention
                                group by 
                                  suivi_individu_entete.id, id_int

                              ) srq_niveau_1

                              group by srq_niveau_1.id_int

                            
                      ),

                      ( select
                          (sum(srq_2_niveau_1.nbr_beneficiaires_menage) + sum(srq_2_niveau_1.nbr_beneficiaires_individu)) as total_beneficiaire 
                        from  ( 

                                select
                                  count(DISTINCT(srq_sm.id_menage)) as nbr_beneficiaires_menage,
                                  0 as nbr_beneficiaires_individu
                                from
                                  suivi_menage_entete as srq_sme,
                                  suivi_menage srq_sm,
                                  menage srq_m,
                                  intervention as sqr_int

                                where 
                                  sqr_int.id = srq_sme.id_intervention
                                  and srq_sme.id = srq_sm.id_suivi_menage_entete
                                  and srq_m.id = srq_sm.id_menage
                                 
                                  and sqr_int.id = niveau_1.id_intervention

                                UNION

                                select
                                  0 as nbr_beneficiaires_menage,
                                  count(DISTINCT(srq_si.id_individu)) as nbr_beneficiaires_individu
                                from
                                  suivi_individu_entete as srq_sie,
                                  suivi_individu srq_si,
                                  individu srq_i,
                                  intervention as sqr_int

                                where 
                                  sqr_int.id = srq_sie.id_intervention
                                  and srq_sie.id = srq_si.id_suivi_individu_entete
                                  and srq_i.id = srq_si.id_individu
                                 
                                  and sqr_int.id = niveau_1.id_intervention
                              ) srq_2_niveau_1
                      )

                      from  (

                          select 
                            intervention.intitule as intitule_intervention,
                            intervention.id as id_intervention,

                            (count(suivi_menage.id_menage) * suivi_menage_entete.montant_transfert) as cout_par_entete_menage_h,
                            0 as cout_par_entete_menage_f,

                            0 as cout_par_entete_individu_h,
                            0 as cout_par_entete_individu_f

                          from 
                            suivi_menage_entete,
                            suivi_menage,
                            menage,
                            intervention
                          where
                            intervention.id = suivi_menage_entete.id_intervention
                            and suivi_menage_entete.id = suivi_menage.id_suivi_menage_entete
                            and menage.id = suivi_menage.id_menage
                            and menage.sexe = 'H'

                          group by 
                            intervention.id,
                            suivi_menage_entete.id

                          UNION

                          select 
                            intervention.intitule as intitule_intervention,
                            intervention.id as id_intervention,

                            0 as cout_par_entete_menage_h,
                            (count(suivi_menage.id_menage) * suivi_menage_entete.montant_transfert) as cout_par_entete_menage_f,

                            0 as cout_par_entete_individu_h,
                            0 as cout_par_entete_individu_f

                          from 
                            suivi_menage_entete,
                            suivi_menage,
                            menage,
                            intervention
                          where
                            intervention.id = suivi_menage_entete.id_intervention
                            and suivi_menage_entete.id = suivi_menage.id_suivi_menage_entete
                            and menage.id = suivi_menage.id_menage
                            and menage.sexe = 'F'

                          group by 
                            intervention.id,
                            suivi_menage_entete.id

                          UNION

                          select 
                            intervention.intitule as intitule_intervention,
                            intervention.id as id_intervention,

                            (count(suivi_individu.id_individu) * suivi_individu_entete.montant_transfert) as cout_par_entete_individu_h,
                            0 as cout_par_entete_individu_f,

                            0 as cout_par_entete_menage_h,
                            0 as cout_par_entete_menage_f

                          from 
                            suivi_individu_entete,
                            suivi_individu,
                            individu,
                            intervention
                          where
                            intervention.id = suivi_individu_entete.id_intervention
                            and suivi_individu_entete.id = suivi_individu.id_suivi_individu_entete
                            and individu.id = suivi_individu.id_individu
                            and individu.sexe = 'H'

                          group by 
                            intervention.id,
                            suivi_individu_entete.id

                          UNION

                          select 
                            intervention.intitule as intitule_intervention,
                            intervention.id as id_intervention,

                            0 as cout_par_entete_individu_h,
                            (count(suivi_individu.id_individu) * suivi_individu_entete.montant_transfert) as cout_par_entete_individu_f,

                            0 as cout_par_entete_menage_h,
                            0 as cout_par_entete_menage_f

                          from 
                            suivi_individu_entete,
                            suivi_individu,
                            individu,
                            intervention
                          where
                            intervention.id = suivi_individu_entete.id_intervention
                            and suivi_individu_entete.id = suivi_individu.id_suivi_individu_entete
                            and individu.id = suivi_individu.id_individu
                            and individu.sexe = 'F'

                          group by 
                            intervention.id,
                            suivi_individu_entete.id
                            ) niveau_1

                      group by 
                        niveau_1.id_intervention,
                        niveau_1.intitule_intervention

                    " ;

            return $this->db->query($sql)->result(); 

        }
        /*Fin Proportion des interventions avec critères de sexe*/


        /*Proportion des interventions avec critères Age*/

        public function proportion_des_intervention_avec_critere_age()
        {


              $sql =  " 
                        select
                          intitule_intervention,
                          id_intervention,

                          (sum(cout_par_entete_enfant_menage)) + (sum(cout_par_entete_enfant_individu)) as cout_enfant,
                          (sum(cout_par_entete_age_scolaire_menage)) + (sum(cout_par_entete_age_scolaire_individu)) as cout_age_scolaire,
                          (sum(cout_par_entete_age_travail_menage)) + (sum(cout_par_entete_age_travail_individu)) as cout_age_travail,
                          (sum(cout_par_entete_agee_menage)) + (sum(cout_par_entete_agee_individu)) as cout_agee,

                          (sum(nbr_enfant_par_entete_menage)) + (sum(nbr_enfant_par_entete_individu)) as nbr_enfant,
                          (sum(nbr_age_scolaire_par_entete_menage)) + (sum(nbr_age_scolaire_par_entete_individu)) as nbr_age_scolaire,
                          (sum(nbr_age_travail_par_entete_menage)) + (sum(nbr_age_travail_par_entete_individu)) as nbr_age_travail,
                          (sum(nbr_agee_par_entete_menage)) + (sum(nbr_agee_par_entete_individu)) as nbr_agee,



                          ((sum(nbr_enfant_par_entete_menage)) + (sum(nbr_enfant_par_entete_individu))) + 
                          ((sum(nbr_age_scolaire_par_entete_menage)) + (sum(nbr_age_scolaire_par_entete_individu))) + 
                          ((sum(nbr_age_travail_par_entete_menage)) + (sum(nbr_age_travail_par_entete_individu))) + 
                          ((sum(nbr_agee_par_entete_menage)) + (sum(nbr_agee_par_entete_individu))) as nbr_total_benaficiare,


                          ( select

                            sum(srq_niveau_1.cout_menage_par_entete) + sum(srq_niveau_1.cout_individu_par_entete) as total_cout

                          from ( select 

                                    (count(suivi_menage.id_menage) * suivi_menage_entete.montant_transfert) as cout_menage_par_entete,
                                    0 as cout_individu_par_entete,
                                    intervention.id as id_int

                                  from 
                                    suivi_menage_entete,
                                    suivi_menage,
                                    intervention

                                  where 
                                    suivi_menage_entete.id = suivi_menage.id_suivi_menage_entete
                                    and intervention.id = suivi_menage_entete.id_intervention
                                    and intervention.id = niveau_1.id_intervention
                                  group by 
                                    suivi_menage_entete.id, id_int

                                UNION

                                select 

                                    0 as cout_menage_par_entete,
                                    (count(suivi_individu.id_individu) * suivi_individu_entete.montant_transfert) as cout_individu_par_entete,
                                    intervention.id as id_int

                                  from 
                                    suivi_individu_entete,
                                    suivi_individu,
                                    intervention

                                  where 
                                    suivi_individu_entete.id = suivi_individu.id_suivi_individu_entete
                                    and intervention.id = suivi_individu_entete.id_intervention
                                    and intervention.id = niveau_1.id_intervention
                                  group by 
                                    suivi_individu_entete.id, id_int

                                ) srq_niveau_1

                                group by srq_niveau_1.id_int

                              
                        )


                        from (  select 
                                  intervention.intitule as intitule_intervention,
                                  intervention.id as id_intervention,

                                  
                                  (count(suivi_menage.id_menage) * suivi_menage_entete.montant_transfert) as cout_par_entete_enfant_menage,
                                  0 as cout_par_entete_age_scolaire_menage,
                                  0 as cout_par_entete_age_travail_menage,
                                  0 as cout_par_entete_agee_menage,

                                  count(suivi_menage.id_menage) as nbr_enfant_par_entete_menage,
                                  0 as nbr_age_scolaire_par_entete_menage,
                                  0 as nbr_age_travail_par_entete_menage,
                                  0 as nbr_agee_par_entete_menage,


                                  0 as cout_par_entete_enfant_individu,
                                  0 as cout_par_entete_age_scolaire_individu,
                                  0 as cout_par_entete_age_travail_individu,
                                  0 as cout_par_entete_agee_individu,

                                  0 as nbr_enfant_par_entete_individu,
                                  0 as nbr_age_scolaire_par_entete_individu,
                                  0 as nbr_age_travail_par_entete_individu,
                                  0 as nbr_agee_par_entete_individu

                                  

                                from 
                                  suivi_menage_entete,
                                  suivi_menage,
                                  menage,
                                  intervention
                                where
                                  intervention.id = suivi_menage_entete.id_intervention
                                  and suivi_menage_entete.id = suivi_menage.id_suivi_menage_entete
                                  and menage.id = suivi_menage.id_menage

                                  and (SELECT DATE_PART('year', suivi_menage_entete.date_suivi) - DATE_PART('year', menage.date_naissance)) < 7
                              

                                group by 
                                  intervention.id,
                                  suivi_menage_entete.id


                              UNION

                              select 
                                  intervention.intitule as intitule_intervention,
                                  intervention.id as id_intervention,

                                  
                                  0 as cout_par_entete_enfant_menage,
                                  (count(suivi_menage.id_menage) * suivi_menage_entete.montant_transfert) as cout_par_entete_age_scolaire_menage,
                                  0 as cout_par_entete_age_travail_menage,
                                  0 as cout_par_entete_agee_menage,

                                  0 as nbr_enfant_par_entete_menage,
                                  count(suivi_menage.id_menage) as nbr_age_scolaire_par_entete_menage,
                                  0 as nbr_age_travail_par_entete_menage,
                                  0 as nbr_agee_par_entete_menage,


                                  0 as cout_par_entete_enfant_individu,
                                  0 as cout_par_entete_age_scolaire_individu,
                                  0 as cout_par_entete_age_travail_individu,
                                  0 as cout_par_entete_agee_individu,

                                  0 as nbr_enfant_par_entete_individu,
                                  0 as nbr_age_scolaire_par_entete_individu,
                                  0 as nbr_age_travail_par_entete_individu,
                                  0 as nbr_agee_par_entete_individu
                                  

                                from 
                                  suivi_menage_entete,
                                  suivi_menage,
                                  menage,
                                  intervention
                                where
                                  intervention.id = suivi_menage_entete.id_intervention
                                  and suivi_menage_entete.id = suivi_menage.id_suivi_menage_entete
                                  and menage.id = suivi_menage.id_menage

                                  and (SELECT DATE_PART('year', suivi_menage_entete.date_suivi) - DATE_PART('year', menage.date_naissance)) >= 7
                                  and (SELECT DATE_PART('year', suivi_menage_entete.date_suivi) - DATE_PART('year', menage.date_naissance)) < 18
                              

                                group by 
                                  intervention.id,
                                  suivi_menage_entete.id


                              UNION

                              select 
                                  intervention.intitule as intitule_intervention,
                                  intervention.id as id_intervention,

                                  
                                  0 as cout_par_entete_enfant_menage,
                                  0 as cout_par_entete_age_scolaire_menage,
                                  (count(suivi_menage.id_menage) * suivi_menage_entete.montant_transfert) as cout_par_entete_age_travail_menage,
                                  0 as cout_par_entete_agee_menage,

                                  0 as nbr_enfant_par_entete_menage,
                                  0 as nbr_age_scolaire_par_entete_menage,
                                  count(suivi_menage.id_menage) as nbr_age_travail_par_entete_menage,
                                  0 as nbr_agee_par_entete_menage,


                                  0 as cout_par_entete_enfant_individu,
                                  0 as cout_par_entete_age_scolaire_individu,
                                  0 as cout_par_entete_age_travail_individu,
                                  0 as cout_par_entete_agee_individu,

                                  0 as nbr_enfant_par_entete_individu,
                                  0 as nbr_age_scolaire_par_entete_individu,
                                  0 as nbr_age_travail_par_entete_individu,
                                  0 as nbr_agee_par_entete_individu
                                  

                                from 
                                  suivi_menage_entete,
                                  suivi_menage,
                                  menage,
                                  intervention
                                where
                                  intervention.id = suivi_menage_entete.id_intervention
                                  and suivi_menage_entete.id = suivi_menage.id_suivi_menage_entete
                                  and menage.id = suivi_menage.id_menage

                                  and (SELECT DATE_PART('year', suivi_menage_entete.date_suivi) - DATE_PART('year', menage.date_naissance)) >= 18
                                  and (SELECT DATE_PART('year', suivi_menage_entete.date_suivi) - DATE_PART('year', menage.date_naissance)) < 60
                              

                                group by 
                                  intervention.id,
                                  suivi_menage_entete.id

                              UNION

                              select 
                                  intervention.intitule as intitule_intervention,
                                  intervention.id as id_intervention,

                                  
                                  0 as cout_par_entete_enfant_menage,
                                  0 as cout_par_entete_age_scolaire_menage,
                                  0 as cout_par_entete_age_travail_menage,
                                  (count(suivi_menage.id_menage) * suivi_menage_entete.montant_transfert) as cout_par_entete_agee_menage,

                                  0 as nbr_enfant_par_entete_menage,
                                  0 as nbr_age_scolaire_par_entete_menage,
                                  0 as nbr_age_travail_par_entete_menage,
                                  count(suivi_menage.id_menage) as nbr_agee_par_entete_menage,


                                  0 as cout_par_entete_enfant_individu,
                                  0 as cout_par_entete_age_scolaire_individu,
                                  0 as cout_par_entete_age_travail_individu,
                                  0 as cout_par_entete_agee_individu,

                                  0 as nbr_enfant_par_entete_individu,
                                  0 as nbr_age_scolaire_par_entete_individu,
                                  0 as nbr_age_travail_par_entete_individu,
                                  0 as nbr_agee_par_entete_individu
                                  

                                from 
                                  suivi_menage_entete,
                                  suivi_menage,
                                  menage,
                                  intervention
                                where
                                  intervention.id = suivi_menage_entete.id_intervention
                                  and suivi_menage_entete.id = suivi_menage.id_suivi_menage_entete
                                  and menage.id = suivi_menage.id_menage

                                  and (SELECT DATE_PART('year', suivi_menage_entete.date_suivi) - DATE_PART('year', menage.date_naissance)) >= 60
                                  
                              

                                group by 
                                  intervention.id,
                                  suivi_menage_entete.id

                              UNION

                              select 
                                  intervention.intitule as intitule_intervention,
                                  intervention.id as id_intervention,

                                  0 as cout_par_entete_enfant_menage,
                                  0 as cout_par_entete_age_scolaire_menage,
                                  0 as cout_par_entete_age_travail_menage,
                                  0 as cout_par_entete_agee_menage,

                                  0 as nbr_enfant_par_entete_menage,
                                  0 as nbr_age_scolaire_par_entete_menage,
                                  0 as nbr_age_travail_par_entete_menage,
                                  0 as nbr_agee_par_entete_menage,

                                  
                                  (count(suivi_individu.id_individu) * suivi_individu_entete.montant_transfert) as cout_par_entete_enfant_individu,
                                  0 as cout_par_entete_age_scolaire_individu,
                                  0 as cout_par_entete_age_travail_individu,
                                  0 as cout_par_entete_agee_individu,

                                  count(suivi_individu.id_individu) as nbr_enfant_par_entete_individu,
                                  0 as nbr_age_scolaire_par_entete_individu,
                                  0 as nbr_age_travail_par_entete_individu,
                                  0 as nbr_agee_par_entete_individu

                                  

                                from 
                                  suivi_individu_entete,
                                  suivi_individu,
                                  individu,
                                  intervention
                                where
                                  intervention.id = suivi_individu_entete.id_intervention
                                  and suivi_individu_entete.id = suivi_individu.id_suivi_individu_entete
                                  and individu.id = suivi_individu.id_individu

                                  and (SELECT DATE_PART('year', suivi_individu_entete.date_suivi) - DATE_PART('year', individu.date_naissance)) < 7
                              

                                group by 
                                  intervention.id,
                                  suivi_individu_entete.id


                              UNION

                              select 
                                  intervention.intitule as intitule_intervention,
                                  intervention.id as id_intervention,

                                  0 as cout_par_entete_enfant_menage,
                                  0 as cout_par_entete_age_scolaire_menage,
                                  0 as cout_par_entete_age_travail_menage,
                                  0 as cout_par_entete_agee_menage,

                                  0 as nbr_enfant_par_entete_menage,
                                  0 as nbr_age_scolaire_par_entete_menage,
                                  0 as nbr_age_travail_par_entete_menage,
                                  0 as nbr_agee_par_entete_menage,
                                  
                                  0 as cout_par_entete_enfant_individu,
                                  (count(suivi_individu.id_individu) * suivi_individu_entete.montant_transfert) as cout_par_entete_age_scolaire_individu,
                                  0 as cout_par_entete_age_travail_individu,
                                  0 as cout_par_entete_agee_individu,

                                  0 as nbr_enfant_par_entete_individu,
                                  count(suivi_individu.id_individu) as nbr_age_scolaire_par_entete_individu,
                                  0 as nbr_age_travail_par_entete_individu,
                                  0 as nbr_agee_par_entete_individu
                                  

                                from 
                                  suivi_individu_entete,
                                  suivi_individu,
                                  individu,
                                  intervention
                                where
                                  intervention.id = suivi_individu_entete.id_intervention
                                  and suivi_individu_entete.id = suivi_individu.id_suivi_individu_entete
                                  and individu.id = suivi_individu.id_individu

                                  and (SELECT DATE_PART('year', suivi_individu_entete.date_suivi) - DATE_PART('year', individu.date_naissance)) >= 7
                                  and (SELECT DATE_PART('year', suivi_individu_entete.date_suivi) - DATE_PART('year', individu.date_naissance)) < 18
                              

                                group by 
                                  intervention.id,
                                  suivi_individu_entete.id


                              UNION

                              select 
                                  intervention.intitule as intitule_intervention,
                                  intervention.id as id_intervention,

                                  0 as cout_par_entete_enfant_menage,
                                  0 as cout_par_entete_age_scolaire_menage,
                                  0 as cout_par_entete_age_travail_menage,
                                  0 as cout_par_entete_agee_menage,

                                  0 as nbr_enfant_par_entete_menage,
                                  0 as nbr_age_scolaire_par_entete_menage,
                                  0 as nbr_age_travail_par_entete_menage,
                                  0 as nbr_agee_par_entete_menage,

                                  
                                  0 as cout_par_entete_enfant_individu,
                                  0 as cout_par_entete_age_scolaire_individu,
                                  (count(suivi_individu.id_individu) * suivi_individu_entete.montant_transfert) as cout_par_entete_age_travail_individu,
                                  0 as cout_par_entete_agee_individu,

                                  0 as nbr_enfant_par_entete_individu,
                                  0 as nbr_age_scolaire_par_entete_individu,
                                  count(suivi_individu.id_individu) as nbr_age_travail_par_entete_individu,
                                  0 as nbr_agee_par_entete_individu
                                  

                                from 
                                  suivi_individu_entete,
                                  suivi_individu,
                                  individu,
                                  intervention
                                where
                                  intervention.id = suivi_individu_entete.id_intervention
                                  and suivi_individu_entete.id = suivi_individu.id_suivi_individu_entete
                                  and individu.id = suivi_individu.id_individu

                                  and (SELECT DATE_PART('year', suivi_individu_entete.date_suivi) - DATE_PART('year', individu.date_naissance)) >= 18
                                  and (SELECT DATE_PART('year', suivi_individu_entete.date_suivi) - DATE_PART('year', individu.date_naissance)) < 60
                              

                                group by 
                                  intervention.id,
                                  suivi_individu_entete.id

                              UNION

                              select 
                                  intervention.intitule as intitule_intervention,
                                  intervention.id as id_intervention,

                                  0 as cout_par_entete_enfant_menage,
                                  0 as cout_par_entete_age_scolaire_menage,
                                  0 as cout_par_entete_age_travail_menage,
                                  0 as cout_par_entete_agee_menage,

                                  0 as nbr_enfant_par_entete_menage,
                                  0 as nbr_age_scolaire_par_entete_menage,
                                  0 as nbr_age_travail_par_entete_menage,
                                  0 as nbr_agee_par_entete_menage,

                                  
                                  0 as cout_par_entete_enfant_individu,
                                  0 as cout_par_entete_age_scolaire_individu,
                                  0 as cout_par_entete_age_travail_individu,
                                  (count(suivi_individu.id_individu) * suivi_individu_entete.montant_transfert) as cout_par_entete_agee_individu,

                                  0 as nbr_enfant_par_entete_individu,
                                  0 as nbr_age_scolaire_par_entete_individu,
                                  0 as nbr_age_travail_par_entete_individu,
                                  count(suivi_individu.id_individu) as nbr_agee_par_entete_individu
                                  

                                from 
                                  suivi_individu_entete,
                                  suivi_individu,
                                  individu,
                                  intervention
                                where
                                  intervention.id = suivi_individu_entete.id_intervention
                                  and suivi_individu_entete.id = suivi_individu.id_suivi_individu_entete
                                  and individu.id = suivi_individu.id_individu

                                  and (SELECT DATE_PART('year', suivi_individu_entete.date_suivi) - DATE_PART('year', individu.date_naissance)) >= 60
                                  
                              

                                group by 
                                  intervention.id,
                                  suivi_individu_entete.id

                            ) niveau_1

                                group by  intitule_intervention,
                                          id_intervention


                      " ;

              return $this->db->query($sql)->result(); 
        }
        /*Fin Proportion des interventions avec critères Age*/

        public function nbr_nouveau_beneficiaire($date_debut, $date_fin)
        {

          $sql =  "
                      select
                        niveau_1.id_intervention as id_interv,
                        niveau_1.intitule_intervention as intitule_interv,
                        (sum(niveau_1.nbr_menage) + sum(niveau_1.nbr_individu)) as nbr_total_benaficiaire,
                        niveau_1.nom_region as nom_reg,
                        niveau_1.id_region as id_reg

                      from(
                          select
                              intervention.id as id_intervention,
                              intervention.intitule as intitule_intervention,
                              count(menage_beneficiaire.id_menage) as nbr_menage,
                              0 as nbr_individu,
                              region.nom as nom_region,
                              region.id as id_region

                          from 
                              intervention,
                              menage,
                              menage_beneficiaire,
                              fokontany,
                              commune,
                              district,
                              region
                          where
                              intervention.id = menage_beneficiaire.id_intervention
                              and menage.id = menage_beneficiaire.id_menage
                              and menage.id_fokontany = fokontany.id
                              and fokontany.id_commune = commune.id
                              and commune.district_id = district.id
                              and district.region_id = region.id
                              and menage_beneficiaire.date_inscription BETWEEN '".$date_debut."' AND '".$date_fin."'

                          group by 

                              intervention.id,
                              region.id


                          UNION


                          select
                              intervention.id as id_intervention,
                              intervention.intitule as intitule_intervention,
                              0 as nbr_menage,
                              count(individu_beneficiaire.id_individu) as nbr_individu,
                              region.nom as nom_region,
                              region.id as id_region

                          from 
                              intervention,
                              individu,
                              individu_beneficiaire,
                              fokontany,
                              commune,
                              district,
                              region
                          where
                              intervention.id = individu_beneficiaire.id_intervention
                              and individu.id = individu_beneficiaire.id_individu
                              and individu.id_fokontany = fokontany.id
                              and fokontany.id_commune = commune.id
                              and commune.district_id = district.id
                              and district.region_id = region.id
                              and individu_beneficiaire.date_inscription BETWEEN '".$date_debut."' AND '".$date_fin."'

                          group by 

                              intervention.id,
                              region.id

                      ) niveau_1

                          group by 
                            niveau_1.id_intervention,
                            niveau_1.intitule_intervention,
                            niveau_1.id_region,
                            niveau_1.nom_region


                  " ;

          return $this->db->query($sql)->result(); 
        }


        public function taux_atteinte_resultat()
        {

          $sql =  "

                      select

                        niveau_1.id_programme as id_prog,
                        niveau_1.intitule_programme,
                        niveau_1.id_intervention as id_interv,
                        niveau_1.intitule_intervention,
                        niveau_1.id_region as id_regi,
                        niveau_1.nom_region,
                        sum(niveau_1.nbr_menage) as nbr_menage_beneficiaire,
                        sum(niveau_1.nbr_individu) as nbr_individu_beneficiaire,
                        (
                          select
                            zip.menage_beneficiaire_prevu
                          from
                              zone_intervention_programme as zip
                          where
                              zip.id_programme = niveau_1.id_programme
                              and zip.id_region = niveau_1.id_region


                        ) as nbr_menage_prevu,

                        (
                          select
                            zip.individu_beneficiaire_prevu
                          from
                              zone_intervention_programme as zip
                          where
                              zip.id_programme = niveau_1.id_programme
                              and zip.id_region = niveau_1.id_region


                        ) as nbr_individu_prevu

                      from( select
                                programme.id as id_programme,
                                programme.intitule as intitule_programme,
                                intervention.id as id_intervention,
                                intervention.intitule as intitule_intervention,
                                region.id as id_region,
                                region.nom as nom_region,

                                count(menage_beneficiaire.id_menage) as nbr_menage,
                                0 as nbr_individu
                            from 
                                programme,
                                intervention,
                                menage_beneficiaire,
                                menage,
                                fokontany,
                                commune,
                                district,
                                region
                            where
                                programme.id = intervention.id_programme
                                and intervention.id = menage_beneficiaire.id_intervention
                                and menage.id = menage_beneficiaire.id_menage
                                and fokontany.id = menage.id_fokontany
                                and commune.id = fokontany.id_commune
                                and district.id = commune.district_id
                                and region.id = district.region_id

                            group by
                                programme.id,
                                intervention.id,
                                region.id

                            UNION


                            select
                                programme.id as id_programme,
                                programme.intitule as intitule_programme,
                                intervention.id as id_intervention,
                                intervention.intitule as intitule_intervention,
                                region.id as id_region,
                                region.nom as nom_region,

                                0 as nbr_menage,
                                count(individu_beneficiaire.id_individu) as nbr_individu
                            from 
                                programme,
                                intervention,
                                individu_beneficiaire,
                                individu,
                                fokontany,
                                commune,
                                district,
                                region
                            where
                                programme.id = intervention.id_programme
                                and intervention.id = individu_beneficiaire.id_intervention
                                and individu.id = individu_beneficiaire.id_individu
                                and fokontany.id = individu.id_fokontany
                                and commune.id = fokontany.id_commune
                                and district.id = commune.district_id
                                and region.id = district.region_id

                            group by
                                programme.id,
                                intervention.id,
                                region.id

                        ) niveau_1

                            group by 
                                id_prog,
                                id_interv,
                                id_regi,
                                niveau_1.intitule_programme,
                                niveau_1.intitule_intervention,
                                niveau_1.nom_region

                  " ;
          return $this->db->query($sql)->result(); 

        }


    //Début Req38-theme 1
      public function repartition_par_age_sexe_beneficiaire()
      {
          $sql =  "

                                  select

                                      niveau_1.id_intervention as id_intervention,
                                      niveau_1.intitule_intervention as intitule_intervention,

                                      niveau_1.nom_region as nom_region,
                                      niveau_1.nom_dist as nom_dist,
                                      niveau_1.nom_com as nom_com,
                                      niveau_1.id_commune as id_commune,

                                      sum(niveau_1.nbr_enfant_homme) as nbr_enfant_homme,
                                      sum(niveau_1.nbr_enfant_fille) as nbr_enfant_fille,
                                      sum(niveau_1.nbr_agescolaire_fille) as nbr_agescolaire_fille,
                                      sum(niveau_1.nbr_agescolaire_homme) as nbr_agescolaire_homme,

                                      (sum(niveau_1.nbr_agetravaille_homme_menage) + sum(niveau_1.nbr_agetravaille_homme_individu)) as nbr_agetravaille_homme,
                                      (sum(niveau_1.nbr_agetravaille_fille_menage) + sum(niveau_1.nbr_agetravaille_fille_individu)) as nbr_agetravaille_fille,

                                      (sum(niveau_1.nbr_agee_homme_menage) + sum(niveau_1.nbr_agee_homme_individu)) as nbr_agee_homme,
                                      (sum(niveau_1.nbr_agee_fille_menage) + sum(niveau_1.nbr_agee_fille_individu)) as nbr_agee_fille


                                  from(
                                      (
                                          select 

                                              int.id as id_intervention,
                                              int.intitule as intitule_intervention,

                                              reg.id as id_region,
                                              reg.nom as nom_region,
                                              dist.id as id_district,
                                              dist.nom as nom_dist,
                                              com.id as id_commune,
                                              com.nom as nom_com,


                                              count(DISTINCT(indi.id)) as nbr_enfant_homme,
                                              0 as nbr_enfant_fille,
                                              0 as nbr_agescolaire_fille,
                                              0 as nbr_agescolaire_homme,
                                              0 as nbr_agetravaille_homme_menage,
                                              0 as nbr_agetravaille_homme_individu,
                                              0 as nbr_agetravaille_fille_menage,
                                              0 as nbr_agetravaille_fille_individu,

                                              0 as nbr_agee_homme_menage,
                                              0 as nbr_agee_homme_individu,
                                              0 as nbr_agee_fille_menage,
                                              0 as nbr_agee_fille_individu


                                          from    
                                              individu as indi 
                                              inner join fokontany as foko on indi.id_fokontany= foko.id 
                                              inner join commune as com on com.id= foko.id_commune 
                                              inner join district as dist on com.district_id= dist.id 
                                              inner join region as reg on dist.region_id= reg.id 
                                              inner join individu_beneficiaire as ib on indi.id = ib.id_individu
                                              inner join intervention as int on int.id = ib.id_intervention
                                          where   
                                              
                                               (SELECT DATE_PART('year', ib.date_inscription) - DATE_PART('year', indi.date_naissance)) < 7
                                              and indi.sexe = 'H'

                                          group by 
                                              reg.id,
                                              dist.id,
                                              com.id ,
                                              int.id,
                                              int.intitule
                                      )
                                      UNION
                                      (
                                          select 

                                              int.id as id_intervention,
                                              int.intitule as intitule_intervention,

                                              reg.id as id_region,
                                              reg.nom as nom_region,
                                              dist.id as id_district,
                                              dist.nom as nom_dist,
                                              com.id as id_commune,
                                              com.nom as nom_com,

                                              0 as nbr_enfant_homme,
                                              count(DISTINCT(indi.id)) as nbr_enfant_fille,
                                              0 as nbr_agescolaire_fille,
                                              0 as nbr_agescolaire_homme,
                                              0 as nbr_agetravaille_homme_menage,
                                              0 as nbr_agetravaille_homme_individu,
                                              0 as nbr_agetravaille_fille_menage,
                                              0 as nbr_agetravaille_fille_individu,

                                              0 as nbr_agee_homme_menage,
                                              0 as nbr_agee_homme_individu,
                                              0 as nbr_agee_fille_menage,
                                              0 as nbr_agee_fille_individu


                                          from    
                                              individu as indi 
                                              inner join fokontany as foko on indi.id_fokontany= foko.id 
                                              inner join commune as com on com.id= foko.id_commune 
                                              inner join district as dist on com.district_id= dist.id 
                                              inner join region as reg on dist.region_id= reg.id 
                                              inner join individu_beneficiaire as ib on indi.id = ib.id_individu
                                              inner join intervention as int on int.id = ib.id_intervention
                                          where   
                                              
                                               (SELECT DATE_PART('year', ib.date_inscription) - DATE_PART('year', indi.date_naissance)) < 7
                                              and indi.sexe = 'F'

                                          group by 
                                              reg.id,
                                              dist.id,
                                              com.id ,

                                              int.id,
                                              int.intitule
                                      )
                                      UNION
                                      (
                                          select 

                                              int.id as id_intervention,
                                              int.intitule as intitule_intervention,

                                              reg.id as id_region,
                                              reg.nom as nom_region,
                                              dist.id as id_district,
                                              dist.nom as nom_dist,
                                              com.id as id_commune,
                                              com.nom as nom_com,

                                              0 as nbr_enfant_homme,
                                              0 as nbr_enfant_fille,
                                              count(DISTINCT(indi.id)) as nbr_agescolaire_fille,
                                              0 as nbr_agescolaire_homme,
                                              0 as nbr_agetravaille_homme_menage,
                                              0 as nbr_agetravaille_homme_individu,
                                              0 as nbr_agetravaille_fille_menage,
                                              0 as nbr_agetravaille_fille_individu,

                                              0 as nbr_agee_homme_menage,
                                              0 as nbr_agee_homme_individu,
                                              0 as nbr_agee_fille_menage,
                                              0 as nbr_agee_fille_individu


                                          from 
                                              individu as indi 
                                              inner join fokontany as foko on indi.id_fokontany= foko.id 
                                              inner join commune as com on com.id= foko.id_commune 
                                              inner join district as dist on com.district_id= dist.id 
                                              inner join region as reg on dist.region_id= reg.id 
                                              inner join individu_beneficiaire as ib on indi.id = ib.id_individu
                                              inner join intervention as int on int.id = ib.id_intervention
                                          where 
                                              
                                               (SELECT DATE_PART('year', ib.date_inscription) - DATE_PART('year', indi.date_naissance)) >= 7
                                              and (SELECT DATE_PART('year', ib.date_inscription) - DATE_PART('year', indi.date_naissance)) < 18
                                              and indi.sexe = 'F'

                                          group by 
                                              reg.id,
                                              dist.id,
                                              com.id ,

                                              int.id,
                                              int.intitule
                                      )
                                      UNION
                                      (
                                          select 
                                              int.id as id_intervention,
                                              int.intitule as intitule_intervention,


                                              reg.id as id_region,
                                              reg.nom as nom_region,
                                              dist.id as id_district,
                                              dist.nom as nom_dist,
                                              com.id as id_commune,
                                              com.nom as nom_com,

                                              0 as nbr_enfant_homme,
                                              0 as nbr_enfant_fille,
                                              0 as nbr_agescolaire_fille,
                                              count(DISTINCT(indi.id)) as nbr_agescolaire_homme,
                                              0 as nbr_agetravaille_homme_menage,
                                              0 as nbr_agetravaille_homme_individu,
                                              0 as nbr_agetravaille_fille_menage,
                                              0 as nbr_agetravaille_fille_individu,

                                              0 as nbr_agee_homme_menage,
                                              0 as nbr_agee_homme_individu,
                                              0 as nbr_agee_fille_menage,
                                              0 as nbr_agee_fille_individu


                                          from 
                                              individu as indi 
                                              inner join fokontany as foko on indi.id_fokontany= foko.id 
                                              inner join commune as com on com.id= foko.id_commune 
                                              inner join district as dist on com.district_id= dist.id
                                              inner join region as reg on dist.region_id= reg.id 
                                              inner join individu_beneficiaire as ib on indi.id = ib.id_individu 
                                              inner join intervention as int on int.id = ib.id_intervention
                                          where 
                                              (SELECT DATE_PART('year', ib.date_inscription) - DATE_PART('year', indi.date_naissance)) >= 7
                                              and (SELECT DATE_PART('year', ib.date_inscription) - DATE_PART('year', indi.date_naissance)) < 18
                                              and indi.sexe = 'H'

                                          group by 
                                              reg.id,
                                              dist.id,
                                              com.id ,

                                              int.id,
                                              int.intitule
                                      )
                                      UNION
                                      (
                                          select 

                                              int.id as id_intervention,
                                              int.intitule as intitule_intervention,

                                              reg.id as id_region,
                                              reg.nom as nom_region,
                                              dist.id as id_district,
                                              dist.nom as nom_dist,
                                              com.id as id_commune,
                                              com.nom as nom_com,

                                              0 as nbr_enfant_homme,
                                              0 as nbr_enfant_fille,
                                              0 as nbr_agescolaire_fille,
                                              0 as nbr_agescolaire_homme,
                                              count(DISTINCT(mena.id)) as nbr_agetravaille_homme_menage,
                                              0 as nbr_agetravaille_homme_individu,
                                              0 as nbr_agetravaille_fille_menage,
                                              0 as nbr_agetravaille_fille_individu,

                                              0 as nbr_agee_homme_menage,
                                              0 as nbr_agee_homme_individu,
                                              0 as nbr_agee_fille_menage,
                                              0 as nbr_agee_fille_individu


                                          from 
                                              menage as mena 
                                              inner join fokontany as foko on mena.id_fokontany= foko.id 
                                              inner join commune as com on com.id= foko.id_commune 
                                              inner join district as dist on com.district_id= dist.id 
                                              inner join region as reg on dist.region_id= reg.id 
                                              inner join menage_beneficiaire as mb on mena.id = mb.id_menage 
                                              inner join intervention as int on int.id = mb.id_intervention
                                          where  
                                              (SELECT DATE_PART('year', mb.date_inscription) - DATE_PART('year', mena.date_naissance)) >= 18
                                              and (SELECT DATE_PART('year', mb.date_inscription) - DATE_PART('year', mena.date_naissance)) < 60
                                              and mena.sexe = 'H'

                                          group by 
                                              reg.id,
                                              dist.id,
                                              com.id ,

                                              int.id,
                                              int.intitule
                                      )
                                      UNION
                                      (
                                          select 

                                              int.id as id_intervention,
                                              int.intitule as intitule_intervention,


                                              reg.id as id_region,
                                              reg.nom as nom_region,
                                              dist.id as id_district,
                                              dist.nom as nom_dist,
                                              com.id as id_commune,
                                              com.nom as nom_com,

                                              0 as nbr_enfant_homme,
                                              0 as nbr_enfant_fille,
                                              0 as nbr_agescolaire_fille,
                                              0 as nbr_agescolaire_homme,
                                              0 as nbr_agetravaille_homme_menage,
                                              count(DISTINCT(indi.id)) as nbr_agetravaille_homme_individu,
                                              0 as nbr_agetravaille_fille_menage,
                                              0 as nbr_agetravaille_fille_individu,

                                              0 as nbr_agee_homme_menage,
                                              0 as nbr_agee_homme_individu,
                                              0 as nbr_agee_fille_menage,
                                              0 as nbr_agee_fille_individu


                                          from 
                                              individu as indi 
                                              inner join fokontany as foko on indi.id_fokontany= foko.id 
                                              inner join commune as com on com.id= foko.id_commune 
                                              inner join district as dist on com.district_id= dist.id 
                                              inner join region as reg on dist.region_id= reg.id 
                                              inner join individu_beneficiaire as mb on indi.id = mb.id_individu 
                                              inner join intervention as int on int.id = mb.id_intervention
                                          where  
                                              (SELECT DATE_PART('year', mb.date_inscription) - DATE_PART('year', indi.date_naissance)) >= 18
                                              and (SELECT DATE_PART('year', mb.date_inscription) - DATE_PART('year', indi.date_naissance)) < 60
                                              and indi.sexe = 'H'

                                          group by 
                                              reg.id,
                                              dist.id,
                                              com.id ,

                                              int.id,
                                              int.intitule
                                      )
                                      UNION
                                      (
                                          select 

                                              int.id as id_intervention,
                                              int.intitule as intitule_intervention,

                                              reg.id as id_region,
                                              reg.nom as nom_region,
                                              dist.id as id_district,
                                              dist.nom as nom_dist,
                                              com.id as id_commune,
                                              com.nom as nom_com,

                                              0 as nbr_enfant_homme,
                                              0 as nbr_enfant_fille,
                                              0 as nbr_agescolaire_fille,
                                              0 as nbr_agescolaire_homme,
                                              0 as nbr_agetravaille_homme_menage,
                                              0 as nbr_agetravaille_homme_individu,
                                              count(DISTINCT(mena.id)) as nbr_agetravaille_fille_menage,
                                              0 as nbr_agetravaille_fille_individu,

                                              0 as nbr_agee_homme_menage,
                                              0 as nbr_agee_homme_individu,
                                              0 as nbr_agee_fille_menage,
                                              0 as nbr_agee_fille_individu


                                          from 
                                              menage as mena 
                                              inner join fokontany as foko on mena.id_fokontany= foko.id 
                                              inner join commune as com on com.id= foko.id_commune 
                                              inner join district as dist on com.district_id= dist.id 
                                              inner join region as reg on dist.region_id= reg.id 
                                              inner join menage_beneficiaire as mb on mena.id = mb.id_menage 
                                              inner join intervention as int on int.id = mb.id_intervention
                                          where  
                                              (SELECT DATE_PART('year', mb.date_inscription) - DATE_PART('year', mena.date_naissance)) >= 18
                                              and (SELECT DATE_PART('year', mb.date_inscription) - DATE_PART('year', mena.date_naissance)) < 60
                                              and mena.sexe = 'F'

                                          group by 
                                              reg.id,
                                              dist.id,
                                              com.id ,

                                              int.id,
                                              int.intitule
                                      )
                                      UNION
                                      (
                                          select 

                                              int.id as id_intervention,
                                              int.intitule as intitule_intervention,

                                              reg.id as id_region,
                                              reg.nom as nom_region,
                                              dist.id as id_district,
                                              dist.nom as nom_dist,
                                              com.id as id_commune,
                                              com.nom as nom_com,

                                              0 as nbr_enfant_homme,
                                              0 as nbr_enfant_fille,
                                              0 as nbr_agescolaire_fille,
                                              0 as nbr_agescolaire_homme,
                                              0 as nbr_agetravaille_homme_menage,
                                              0 as nbr_agetravaille_homme_individu,
                                              0 as nbr_agetravaille_fille_menage,
                                              count(DISTINCT(indi.id)) as nbr_agetravaille_fille_individu,

                                              0 as nbr_agee_homme_menage,
                                              0 as nbr_agee_homme_individu,
                                              0 as nbr_agee_fille_menage,
                                              0 as nbr_agee_fille_individu


                                          from 
                                              individu as indi 
                                              inner join fokontany as foko on indi.id_fokontany= foko.id 
                                              inner join commune as com on com.id= foko.id_commune 
                                              inner join district as dist on com.district_id= dist.id 
                                              inner join region as reg on dist.region_id= reg.id 
                                              inner join individu_beneficiaire as mb on indi.id = mb.id_individu
                                              inner join intervention as int on int.id = mb.id_intervention 
                                          where  
                                              (SELECT DATE_PART('year', mb.date_inscription) - DATE_PART('year', indi.date_naissance)) >= 18
                                              and (SELECT DATE_PART('year', mb.date_inscription) - DATE_PART('year', indi.date_naissance)) < 60
                                              and indi.sexe = 'F'

                                          group by 
                                              reg.id,
                                              dist.id,
                                              com.id ,

                                              int.id,
                                              int.intitule
                                      )
                                      UNION
                                      (
                                          select 
                                              int.id as id_intervention,
                                              int.intitule as intitule_intervention,


                                              reg.id as id_region,
                                              reg.nom as nom_region,
                                              dist.id as id_district,
                                              dist.nom as nom_dist,
                                              com.id as id_commune,
                                              com.nom as nom_com,

                                              0 as nbr_enfant_homme,
                                              0 as nbr_enfant_fille,
                                              0 as nbr_agescolaire_fille,
                                              0 as nbr_agescolaire_homme,
                                              0 as nbr_agetravaille_homme_menage,
                                              0 as nbr_agetravaille_homme_individu,
                                              0 as nbr_agetravaille_fille_menage,
                                              0 as nbr_agetravaille_fille_individu,

                                              count(DISTINCT(mena.id)) as nbr_agee_homme_menage,
                                              0 as nbr_agee_homme_individu,
                                              0 as nbr_agee_fille_menage,
                                              0 as nbr_agee_fille_individu


                                          from 
                                              menage as mena 
                                              inner join fokontany as foko on mena.id_fokontany= foko.id 
                                              inner join commune as com on com.id= foko.id_commune 
                                              inner join district as dist on com.district_id= dist.id 
                                              inner join region as reg on dist.region_id= reg.id 
                                              inner join menage_beneficiaire as mb on mena.id = mb.id_menage 
                                              inner join intervention as int on int.id = mb.id_intervention
                                          where  
                                              (SELECT DATE_PART('year', mb.date_inscription) - DATE_PART('year', mena.date_naissance)) >= 60
                                              and mena.sexe = 'H'

                                          group by 
                                              reg.id,
                                              dist.id,
                                              com.id ,

                                              int.id,
                                              int.intitule
                                      )
                                      UNION
                                      (
                                          select 

                                              int.id as id_intervention,
                                              int.intitule as intitule_intervention,

                                              reg.id as id_region,
                                              reg.nom as nom_region,
                                              dist.id as id_district,
                                              dist.nom as nom_dist,
                                              com.id as id_commune,
                                              com.nom as nom_com,

                                              0 as nbr_enfant_homme,
                                              0 as nbr_enfant_fille,
                                              0 as nbr_agescolaire_fille,
                                              0 as nbr_agescolaire_homme,
                                              0 as nbr_agetravaille_homme_menage,
                                              0 as nbr_agetravaille_homme_individu,
                                              0 as nbr_agetravaille_fille_menage,
                                              0 as nbr_agetravaille_fille_individu,

                                              0 as nbr_agee_homme_menage,
                                              count(DISTINCT(indi.id)) as nbr_agee_homme_individu,
                                              0 as nbr_agee_fille_menage,
                                              0 as nbr_agee_fille_individu


                                          from 
                                              individu as indi 
                                              inner join fokontany as foko on indi.id_fokontany= foko.id 
                                              inner join commune as com on com.id= foko.id_commune 
                                              inner join district as dist on com.district_id= dist.id 
                                              inner join region as reg on dist.region_id= reg.id 
                                              inner join individu_beneficiaire as mb on indi.id = mb.id_individu 
                                              inner join intervention as int on int.id = mb.id_intervention
                                          where  
                                              (SELECT DATE_PART('year', mb.date_inscription) - DATE_PART('year', indi.date_naissance)) >= 60
                                              and indi.sexe = 'H'

                                          group by 
                                              reg.id,
                                              dist.id,
                                              com.id ,

                                              int.id,
                                              int.intitule
                                      )
                                      UNION
                                      (
                                          select 
                                              int.id as id_intervention,
                                              int.intitule as intitule_intervention,

                                              reg.id as id_region,
                                              reg.nom as nom_region,
                                              dist.id as id_district,
                                              dist.nom as nom_dist,
                                              com.id as id_commune,
                                              com.nom as nom_com,

                                              0 as nbr_enfant_homme,
                                              0 as nbr_enfant_fille,
                                              0 as nbr_agescolaire_fille,
                                              0 as nbr_agescolaire_homme,
                                              0 as nbr_agetravaille_homme_menage,
                                              0 as nbr_agetravaille_homme_individu,
                                              0 as nbr_agetravaille_fille_menage,
                                              0 as nbr_agetravaille_fille_individu,

                                              0 as nbr_agee_homme_menage,
                                              0 as nbr_agee_homme_individu,
                                              count(DISTINCT(mena.id))  as nbr_agee_fille_menage,
                                              0 as nbr_agee_fille_individu


                                          from 
                                              menage as mena 
                                              inner join fokontany as foko on mena.id_fokontany= foko.id 
                                              inner join commune as com on com.id= foko.id_commune 
                                              inner join district as dist on com.district_id= dist.id 
                                              inner join region as reg on dist.region_id= reg.id 
                                              inner join menage_beneficiaire as mb on mena.id = mb.id_menage 
                                              inner join intervention as int on int.id = mb.id_intervention
                                          where  
                                              (SELECT DATE_PART('year', mb.date_inscription) - DATE_PART('year', mena.date_naissance)) >= 60
                                              and mena.sexe = 'F'

                                          group by 
                                              reg.id,
                                              dist.id,
                                              com.id ,

                                              int.id,
                                              int.intitule
                                      )
                                      UNION
                                      (
                                          select 

                                              int.id as id_intervention,
                                              int.intitule as intitule_intervention,

                                              reg.id as id_region,
                                              reg.nom as nom_region,
                                              dist.id as id_district,
                                              dist.nom as nom_dist,
                                              com.id as id_commune,
                                              com.nom as nom_com,

                                              0 as nbr_enfant_homme,
                                              0 as nbr_enfant_fille,
                                              0 as nbr_agescolaire_fille,
                                              0 as nbr_agescolaire_homme,
                                              0 as nbr_agetravaille_homme_menage,
                                              0 as nbr_agetravaille_homme_individu,
                                              0 as nbr_agetravaille_fille_menage,
                                              0 as nbr_agetravaille_fille_individu,

                                              0 as nbr_agee_homme_menage,
                                              0 as nbr_agee_homme_individu,
                                              0 as nbr_agee_fille_menage,
                                              count(DISTINCT(indi.id)) as nbr_agee_fille_individu


                                          from 
                                              individu as indi 
                                              inner join fokontany as foko on indi.id_fokontany= foko.id 
                                              inner join commune as com on com.id= foko.id_commune 
                                              inner join district as dist on com.district_id= dist.id 
                                              inner join region as reg on dist.region_id= reg.id 
                                              inner join individu_beneficiaire as mb on indi.id = mb.id_individu
                                              inner join intervention as int on int.id = mb.id_intervention 
                                          where  
                                              (SELECT DATE_PART('year', mb.date_inscription) - DATE_PART('year', indi.date_naissance)) >= 60
                                              and indi.sexe = 'F'

                                          group by 
                                              reg.id,
                                              dist.id,
                                              com.id ,

                                              int.id,
                                              int.intitule
                                      )

                                  ) niveau_1

                                          group by 
                                              nom_region,
                                              nom_dist,
                                              nom_com,
                                              id_commune,
                                              niveau_1.id_intervention,
                                              niveau_1.intitule_intervention
                          

                  " ;

          return $this->db->query($sql)->result();
      }
    //Fin Req38-theme 1

    //req7_theme2 
    public function repartition_financement_programme()
    {
      $now = date('Y-m-d');
      $sql =  "
                select
                  niveau_1.id_program as id_program,
                  niveau_1.intitule_programme as intitule_programme,
                  niveau_1.id_intervention as id_intervention,
                  niveau_1.intitule_intervention as intitule_intervention,

                  niveau_1.id_devise as id_devise,
                  niveau_1.description_devise as description_devise,


                  sum(niveau_1.budget_initial_en_cours) as budget_initial_en_cours,
                  sum(niveau_1.budget_modifie_en_cours) as budget_modifie_en_cours,

                  sum(niveau_1.budget_initial_nouveau) as budget_initial_nouveau,
                  sum(niveau_1.budget_modifie_nouveau) as budget_modifie_nouveau,

                  (sum(niveau_1.budget_initial_en_cours) + sum(niveau_1.budget_modifie_en_cours)) as etat_en_cours,
                  (sum(niveau_1.budget_initial_nouveau) + sum(niveau_1.budget_modifie_nouveau)) as etat_nouveau,

                  (select description from nomenclature_intervention4 where id = niveau_1.id_nomenclature_intervention) as nomenclature


                from(

                        (
                        select 
                          programme.id as id_program,
                          programme.intitule as intitule_programme,
                          intervention.id as id_intervention,
                          intervention.intitule as intitule_intervention,
                          intervention.id_nomenclature_intervention as id_nomenclature_intervention,

                          devise.id as id_devise,
                          devise.description as description_devise,

                          sum(financement_intervention.budget_initial) as budget_initial_en_cours,
                          sum(financement_intervention.budget_modifie) as budget_modifie_en_cours,

                          0 as budget_initial_nouveau,
                          0 as budget_modifie_nouveau

                        from 
                          intervention,
                          programme,
                          financement_intervention,
                          devise

                        where

                          programme.id  = intervention.id_programme
                          and intervention.id = financement_intervention.id_intervention
                          and devise.id = financement_intervention.id_devise
                          and '".$now."' > programme.date_debut
                          and '".$now."' <= programme.date_fin

                        group by 
                          programme.id,
                          intervention.id,
                          devise.id
                        )

                        UNION

                        (
                        select 
                          programme.id as id_program,
                          programme.intitule as intitule_programme,
                          intervention.id as id_intervention,
                          intervention.intitule as intitule_intervention,
                          intervention.id_nomenclature_intervention as id_nomenclature_intervention,

                          devise.id as id_devise,
                          devise.description as description_devise,

                          0 as budget_initial_en_cours,
                          0 as budget_modifie_en_cours,

                          sum(financement_intervention.budget_initial) as budget_initial_nouveau,
                          sum(financement_intervention.budget_modifie) as budget_modifie_nouveau

                        from 
                          intervention,
                          programme,
                          financement_intervention,
                          devise

                        where

                          programme.id  = intervention.id_programme
                          and intervention.id = financement_intervention.id_intervention
                          and devise.id = financement_intervention.id_devise
                          
                          and '".$now."' <= programme.date_debut

                        group by 
                          programme.id,
                          intervention.id,
                          devise.id
                        )
                    )niveau_1

                      group by
                        niveau_1.id_program,
                        niveau_1.id_intervention,
                        niveau_1.intitule_programme,
                        niveau_1.intitule_intervention,
                        niveau_1.id_devise,
                        niveau_1.description_devise ,
                        niveau_1.id_nomenclature_intervention



              " ;

        return $this->db->query($sql)->result();
    }
    //fin req7_theme2  
    //req8_theme2  
    public function repartition_financement_source_financement()
    {
      $now = date('Y-m-d');
      $sql =  "
                select
                  niveau_1.id_program as id_program,
                  niveau_1.intitule_programme as intitule_programme,
                  niveau_1.id_intervention as id_intervention,
                  niveau_1.intitule_intervention as intitule_intervention,

                  niveau_1.id_devise as id_devise,
                  niveau_1.description_devise as description_devise,

                  niveau_1.id_source_financement as id_source_financement,
                  niveau_1.nom_source_financement as nom_source_financement,


                  sum(niveau_1.budget_initial_en_cours) as budget_initial_en_cours,
                  sum(niveau_1.budget_modifie_en_cours) as budget_modifie_en_cours,

                  sum(niveau_1.budget_initial_nouveau) as budget_initial_nouveau,
                  sum(niveau_1.budget_modifie_nouveau) as budget_modifie_nouveau,

                  (sum(niveau_1.budget_initial_en_cours) + sum(niveau_1.budget_modifie_en_cours)) as etat_en_cours,
                  (sum(niveau_1.budget_initial_nouveau) + sum(niveau_1.budget_modifie_nouveau)) as etat_nouveau


                from(

                        (
                        select 
                          programme.id as id_program,
                          programme.intitule as intitule_programme,
                          intervention.id as id_intervention,
                          intervention.intitule as intitule_intervention,

                          devise.id as id_devise,
                          devise.description as description_devise,

                          source_financement.id as id_source_financement,
                          source_financement.nom as nom_source_financement,

                          sum(financement_intervention.budget_initial) as budget_initial_en_cours,
                          sum(financement_intervention.budget_modifie) as budget_modifie_en_cours,

                          0 as budget_initial_nouveau,
                          0 as budget_modifie_nouveau

                        from 
                          intervention,
                          programme,
                          financement_intervention,
                          devise,
                          source_financement

                        where

                          programme.id  = intervention.id_programme
                          and intervention.id = financement_intervention.id_intervention
                          and devise.id = financement_intervention.id_devise
                          and source_financement.id = financement_intervention.id_source_financement
                          and '".$now."' > programme.date_debut
                          and '".$now."' <= programme.date_fin

                        group by 
                          programme.id,
                          intervention.id,
                          devise.id,
                          source_financement.id
                        )

                        UNION

                        (
                        select 
                          programme.id as id_program,
                          programme.intitule as intitule_programme,
                          intervention.id as id_intervention,
                          intervention.intitule as intitule_intervention,

                          devise.id as id_devise,
                          devise.description as description_devise,

                          source_financement.id as id_source_financement,
                          source_financement.nom as nom_source_financement,

                          0 as budget_initial_en_cours,
                          0 as budget_modifie_en_cours,

                          sum(financement_intervention.budget_initial) as budget_initial_nouveau,
                          sum(financement_intervention.budget_modifie) as budget_modifie_nouveau

                        from 
                          intervention,
                          programme,
                          financement_intervention,
                          devise,
                          source_financement

                        where

                          programme.id  = intervention.id_programme
                          and intervention.id = financement_intervention.id_intervention
                          and devise.id = financement_intervention.id_devise
                          and source_financement.id = financement_intervention.id_source_financement
                          
                          and '".$now."' <= programme.date_debut

                        group by 
                          programme.id,
                          intervention.id,
                          devise.id,
                          source_financement.id
                        )
                    )niveau_1

                      group by
                        niveau_1.id_program,
                        niveau_1.id_intervention,
                        niveau_1.intitule_programme,
                        niveau_1.intitule_intervention,
                        niveau_1.id_devise,
                        niveau_1.description_devise ,
                        niveau_1.id_source_financement,
                        niveau_1.nom_source_financement



              " ;

        return $this->db->query($sql)->result();
    }
    //fin req8_theme2  

    //req9_theme2  
    public function repartition_financement_tutelle()
    {
      $now = date('Y-m-d');
      $sql =  "
                select
                  niveau_1.id_program as id_program,
                  niveau_1.intitule_programme as intitule_programme,
                  niveau_1.id_intervention as id_intervention,
                  niveau_1.intitule_intervention as intitule_intervention,
                  niveau_1.ministere_tutelle as ministere_tutelle,

                  niveau_1.id_devise as id_devise,
                  niveau_1.description_devise as description_devise,


                  sum(niveau_1.budget_initial_en_cours) as budget_initial_en_cours,
                  sum(niveau_1.budget_modifie_en_cours) as budget_modifie_en_cours,

                  sum(niveau_1.budget_initial_nouveau) as budget_initial_nouveau,
                  sum(niveau_1.budget_modifie_nouveau) as budget_modifie_nouveau,

                  (sum(niveau_1.budget_initial_en_cours) + sum(niveau_1.budget_modifie_en_cours)) as etat_en_cours,
                  (sum(niveau_1.budget_initial_nouveau) + sum(niveau_1.budget_modifie_nouveau)) as etat_nouveau


                from(

                        (
                        select 
                          programme.id as id_program,
                          programme.intitule as intitule_programme,
                          intervention.id as id_intervention,
                          intervention.intitule as intitule_intervention,
                          intervention.ministere_tutelle as ministere_tutelle,

                          devise.id as id_devise,
                          devise.description as description_devise,

                          sum(financement_intervention.budget_initial) as budget_initial_en_cours,
                          sum(financement_intervention.budget_modifie) as budget_modifie_en_cours,

                          0 as budget_initial_nouveau,
                          0 as budget_modifie_nouveau

                        from 
                          intervention,
                          programme,
                          financement_intervention,
                          devise

                        where

                          programme.id  = intervention.id_programme
                          and intervention.id = financement_intervention.id_intervention
                          and devise.id = financement_intervention.id_devise
                          and '".$now."' > programme.date_debut
                          and '".$now."' <= programme.date_fin

                        group by 
                          programme.id,
                          intervention.id,
                          devise.id,
                          intervention.ministere_tutelle
                        )

                        UNION

                        (
                        select 
                          programme.id as id_program,
                          programme.intitule as intitule_programme,
                          intervention.id as id_intervention,
                          intervention.intitule as intitule_intervention,
                          intervention.ministere_tutelle as ministere_tutelle,

                          devise.id as id_devise,
                          devise.description as description_devise,

                          0 as budget_initial_en_cours,
                          0 as budget_modifie_en_cours,

                          sum(financement_intervention.budget_initial) as budget_initial_nouveau,
                          sum(financement_intervention.budget_modifie) as budget_modifie_nouveau

                        from 
                          intervention,
                          programme,
                          financement_intervention,
                          devise

                        where

                          programme.id  = intervention.id_programme
                          and intervention.id = financement_intervention.id_intervention
                          and devise.id = financement_intervention.id_devise
                          
                          and '".$now."' <= programme.date_debut

                        group by 
                          programme.id,
                          intervention.id,
                          devise.id,
                          intervention.ministere_tutelle
                        )
                    )niveau_1

                      group by
                        niveau_1.id_program,
                        niveau_1.id_intervention,
                        niveau_1.intitule_programme,
                        niveau_1.intitule_intervention,
                        niveau_1.id_devise,
                        niveau_1.description_devise ,
                        niveau_1.ministere_tutelle



              " ;

        return $this->db->query($sql)->result();
    }
    //fin req9_theme2  
      

    //req multiple

      public function req_multiple_21_to_30()
      {
        $sql =  "

                  select 

                    int.id as id_intervention,
                    int.intitule as intitule_intervention,

                    nmcl4.description as nomenclature_description,

                    (select count(id) from intervention) as nbr_intervention_total,

                    (((select count(DISTINCT(id_intervention)) from variable_intervention where id_variable = (select id from variable where id = var_int.id_variable)) * 100) / (select count(id) from intervention)) as effectif ,

                   

                    (select description from variable where id = var_int.id_variable) as variable,
                    (select description from liste_variable where id = var_int.id_liste_variable) as liste_variable,


                    (select count(DISTINCT(id_intervention)) from variable_intervention where id_variable = (select id from variable where id = var_int.id_variable)) as nbr_intervention,


                    (select

                      sum(niveau_1.cout_menage_par_entete) + sum(niveau_1.cout_individu_par_entete)

                    from

                      ( 
                        select 

                            (count(suivi_menage.id_menage) * suivi_menage_entete.montant_transfert) as cout_menage_par_entete,
                            0 as cout_individu_par_entete

                          from 
                            suivi_menage_entete,
                            suivi_menage

                          where 
                            suivi_menage_entete.id = suivi_menage.id_suivi_menage_entete
                            and suivi_menage_entete.id_intervention = int.id

                          
                          group by 
                            suivi_menage_entete.id

                        UNION

                        select 

                            0 as cout_menage_par_entete,
                            (count(suivi_individu.id_individu) * suivi_individu_entete.montant_transfert) as cout_individu_par_entete

                          from 
                            suivi_individu_entete,
                            suivi_individu

                          where 
                            suivi_individu_entete.id = suivi_individu.id_suivi_individu_entete
                            and suivi_individu_entete.id_intervention = int.id
                          group by 
                            suivi_individu_entete.id

                      ) niveau_1

                    ) as cout_par_intervention ,

                    (select

                      sum(niveau_1.cout_menage_par_entete) + sum(niveau_1.cout_individu_par_entete)

                    from

                      ( 
                        select 

                            (count(suivi_menage.id_menage) * suivi_menage_entete.montant_transfert) as cout_menage_par_entete,
                            0 as cout_individu_par_entete

                          from 
                            suivi_menage_entete,
                            suivi_menage

                          where 
                            suivi_menage_entete.id = suivi_menage.id_suivi_menage_entete

                          
                          group by 
                            suivi_menage_entete.id

                        UNION

                        select 

                            0 as cout_menage_par_entete,
                            (count(suivi_individu.id_individu) * suivi_individu_entete.montant_transfert) as cout_individu_par_entete

                          from 
                            suivi_individu_entete,
                            suivi_individu

                          where 
                            suivi_individu_entete.id = suivi_individu.id_suivi_individu_entete
                          group by 
                            suivi_individu_entete.id

                      ) niveau_1

                    ) as cout_total_intervention ,


                    (((select
                                        
                        sum(niveau_1.cout_menage_par_entete) + sum(niveau_1.cout_individu_par_entete)
  
                      from
  
                        ( 
                          select 
  
                              (count(suivi_menage.id_menage) * suivi_menage_entete.montant_transfert) as cout_menage_par_entete,
                              0 as cout_individu_par_entete
  
                            from 
                              suivi_menage_entete,
                              suivi_menage
  
                            where 
                              suivi_menage_entete.id = suivi_menage.id_suivi_menage_entete
                              and suivi_menage_entete.id_intervention = int.id
  
                            
                            group by 
                              suivi_menage_entete.id
  
                          UNION
  
                          select 
  
                              0 as cout_menage_par_entete,
                              (count(suivi_individu.id_individu) * suivi_individu_entete.montant_transfert) as cout_individu_par_entete
  
                            from 
                              suivi_individu_entete,
                              suivi_individu
  
                            where 
                              suivi_individu_entete.id = suivi_individu.id_suivi_individu_entete
                              and suivi_individu_entete.id_intervention = int.id
                            group by 
                              suivi_individu_entete.id
  
                        ) niveau_1
  
                      ) * 100) 

                      / 

                      (select

                        sum(niveau_1.cout_menage_par_entete) + sum(niveau_1.cout_individu_par_entete)

                      from

                        ( 
                          select 

                              (count(suivi_menage.id_menage) * suivi_menage_entete.montant_transfert) as cout_menage_par_entete,
                              0 as cout_individu_par_entete

                            from 
                              suivi_menage_entete,
                              suivi_menage

                            where 
                              suivi_menage_entete.id = suivi_menage.id_suivi_menage_entete

                            
                            group by 
                              suivi_menage_entete.id

                          UNION

                          select 

                              0 as cout_menage_par_entete,
                              (count(suivi_individu.id_individu) * suivi_individu_entete.montant_transfert) as cout_individu_par_entete

                            from 
                              suivi_individu_entete,
                              suivi_individu

                            where 
                              suivi_individu_entete.id = suivi_individu.id_suivi_individu_entete
                            group by 
                              suivi_individu_entete.id

                        ) niveau_1

                      )) as stat_cout

                     

                  from

                    intervention as int,
                    variable_intervention as var_int,
                    nomenclature_intervention4 as nmcl4

                  where
                    nmcl4.id = int.id_nomenclature_intervention
                    and int.id = var_int.id_intervention


                 

                  group by 
                    
                    int.id,
                    var_int.id,
                    nmcl4.id

                  order by 
                    nomenclature_description

                " ;

        return $this->db->query($sql)->result();
      }
    //fin req multiple


    //req_6_theme2

      public function req6_theme2()
      {
        $sql =  "
                  select

                  niveau_1.nom_region,
                  niveau_1.nom_district,
                  niveau_1.code_vulnerabilite,
                  niveau_1.description_vulnerabilite,
                  (sum(niveau_1.nbr_menage) + sum(niveau_1.nbr_individu)) as nbr 
                  

                  from

                  (
                    select

                      region.id as id_region,
                      region.nom as nom_region,
                  
                      district.id as id_district,
                      district.nom as nom_district,
                      indice_vulnerabilite.id as id_vulnerabilite,
                      indice_vulnerabilite.code as code_vulnerabilite,
                      indice_vulnerabilite.description as description_vulnerabilite,
                      count(menage.id) as nbr_menage,
                      0 as nbr_individu
  
                    from 
                      menage,
                      indice_vulnerabilite,
                      fokontany,
                      commune,
                      district,
                      region
                    where 
  
                      fokontany.id = menage.id_fokontany
                      and commune.id = fokontany.id_commune
                      and district.id = commune.district_id
                      and region.id = district.region_id
                      and indice_vulnerabilite.id = menage.id_indice_vulnerabilite
  
                    group by 
                      region.id,
                      district.id,
                      indice_vulnerabilite.id
  
  
                    UNION
  
  
                    select

                      region.id as id_region,
                      region.nom as nom_region,
  
                      district.id as id_district,
                      district.nom as nom_district,
                      indice_vulnerabilite.id as id_vulnerabilite,
                      indice_vulnerabilite.code as code_vulnerabilite,
                      indice_vulnerabilite.description as description_vulnerabilite,
                      count(individu.id) as nbr_individu,
                      0 as nbr_menage
  
                    from 
                      individu,
                      indice_vulnerabilite,
                      fokontany,
                      commune,
                      district,
                      region
                    where 
  
                      fokontany.id = individu.id_fokontany
                      and commune.id = fokontany.id_commune
                      and district.id = commune.district_id
                      and region.id = district.region_id
                      and indice_vulnerabilite.id = individu.id_indice_vulnerabilite
  
                    group by 
                      region.id,
                      district.id,
                      indice_vulnerabilite.id
                  ) niveau_1


                    group by 
                      niveau_1.id_region,
                      niveau_1.id_district,
                      niveau_1.nom_region,
                      niveau_1.nom_district,
                      niveau_1.code_vulnerabilite,
                      niveau_1.description_vulnerabilite,
                      niveau_1.id_vulnerabilite

                    order by
                      niveau_1.nom_region
                " ;


          return $this->db->query($sql)->result();
      }
    //fin req_6_theme2
    //FIN CODE HARIZO

   
}
